<?php

declare(strict_types=1);

namespace FiveOrbs\Wire;

use FiveOrbs\Wire\Exception\WireException;

readonly class Injected
{
	public static function value(
		Inject $inject,
		CreatorInterface $creator,
		array $predefinedTypes,
		?callable $injectCallback,
	): mixed {
		return match ($inject->type) {
			Type::Literal => $inject->value,
			Type::Create => self::getObject($creator, $inject->value, $predefinedTypes, $injectCallback),
			Type::Entry => self::getEntry($creator, (string) $inject->value, $predefinedTypes),
			Type::Env => self::getEnvVar($inject->value),
			Type::Callback => self::getFromCallback($inject, $injectCallback),
			null => self::getValue($creator, $inject->value, $predefinedTypes, $injectCallback),
		};
	}

	protected static function getValue(
		CreatorInterface $creator,
		mixed $value,
		array $predefinedTypes,
		?callable $injectCallback,
	): mixed {
		if (is_string($value)) {
			if (isset($predefinedTypes[$value])) {
				return $predefinedTypes[$value];
			}

			$container = $creator->container();

			if ($container?->has($value)) {
				return $container->get($value);
			}

			if (class_exists($value)) {
				return $creator->create($value, predefinedTypes: $predefinedTypes, injectCallback: $injectCallback);
			}
		}

		return $value;
	}

	protected static function getEntry(
		CreatorInterface $creator,
		string $value,
		array $predefinedTypes,
	): mixed {
		if (isset($predefinedTypes[$value])) {
			return $predefinedTypes[$value];
		}

		$container = $creator->container();

		if (is_null($container)) {
			throw new WireException('No container available to resolve injected id "' . $value . '"!');
		}

		return $container->get($value);
	}

	protected static function getObject(
		CreatorInterface $creator,
		mixed $value,
		array $predefinedTypes,
		?callable $injectCallback,
	): mixed {
		if (!is_string($value) || !class_exists($value)) {
			throw new WireException('No valid class string "' . (string) $value . '"!');
		}

		return $creator->create($value, predefinedTypes: $predefinedTypes, injectCallback: $injectCallback);
	}

	protected static function getEnvVar(mixed $value): bool|string
	{
		if (!is_string($value)) {
			throw new WireException('Environment variable must be a string!');
		}

		return getenv($value);
	}

	protected static function getFromCallback(Inject $inject, ?callable $injectCallback): mixed
	{
		if (is_callable($injectCallback)) {
			return $injectCallback($inject);
		}

		throw new WireException('Inject callback not available');
	}
}
