<?php

declare(strict_types=1);

namespace FiveOrbs\Wire;

use FiveOrbs\Wire\Exception\WireException;
use Psr\Container\ContainerInterface as Container;
use ReflectionClass;
use ReflectionObject;
use Throwable;

/** @psalm-api */
class Creator implements CreatorInterface
{
	use ResolvesAbstractFunctions;

	public function __construct(protected readonly Container|WireContainer|null $container = null) {}

	/** @psalm-param class-string $class */
	public function create(
		string $class,
		array $predefinedArgs = [],
		array $predefinedTypes = [],
		?callable $injectCallback = null,
		string $constructor = '',
	): object {
		try {
			if ($constructor !== '') {
				// Factory method
				$rmethod = (new ReflectionClass($class))->getMethod($constructor);
				$args = $this->resolveArgs(
					$rmethod,
					predefinedArgs: $predefinedArgs,
					predefinedTypes: $predefinedTypes,
					injectCallback: $injectCallback,
				);
				$instance = $rmethod->invoke(null, ...$args);
			} elseif ($this->container && $this->container->has($class)) {
				if (is_a($this->container, WireContainer::class)) {
					/** @psalm-suppress MixedAssignment */
					$value = $this->container->definition($class);

					if (is_string($value) && class_exists($value)) {
						$instance = $this->resolveConstructor(
							$class,
							$predefinedArgs,
							$predefinedTypes,
							$injectCallback,
						);
					} else {
						/** @psalm-suppress MixedAssignment */
						$instance = $this->container->get($class);
					}
				} else {
					/** @psalm-suppress MixedAssignment */
					$instance = $this->container->get($class);
				}
			} else {
				$instance = $this->resolveConstructor($class, $predefinedArgs, $predefinedTypes, $injectCallback);
			}

			assert(is_object($instance));

			return $this->applyCallAttributes($instance, $predefinedTypes, $injectCallback);
		} catch (Throwable $e) {
			throw new WireException(
				'Unresolvable: ' . $class . ' Details: ' . $e::class . ' ' . $e->getMessage(),
			);
		}
	}

	/** @psalm-param class-string $class */
	protected function resolveConstructor(
		string $class,
		array $predefinedArgs,
		array $predefinedTypes,
		callable|null $injectCallback,
	): object {
		$rcls = new ReflectionClass($class);

		// Regular constructor
		$args = (new ConstructorResolver($this))->resolve(
			$rcls,
			predefinedArgs: $predefinedArgs,
			predefinedTypes: $predefinedTypes,
			injectCallback: $injectCallback,
		);

		return $rcls->newInstance(...$args);
	}

	protected function applyCallAttributes(
		object $instance,
		array $predefinedTypes = [],
		?callable $injectCallback = null,
	): object {
		$callAttrs = (new ReflectionObject($instance))->getAttributes(Call::class);

		// See if the attribute itself has one or more Call attributes. If so,
		// resolve/autowire the arguments of the method it states and call it.
		foreach ($callAttrs as $callAttr) {
			$callAttr = $callAttr->newInstance();
			$methodToResolve = $callAttr->method;

			/** @psalm-var callable */
			$callable = [$instance, $methodToResolve];
			$args = (new CallableResolver($this))->resolve(
				$callable,
				predefinedArgs: $callAttr->args,
				predefinedTypes: $predefinedTypes,
				injectCallback: $injectCallback,
			);
			$callable(...$args);
		}

		return $instance;
	}

	public function container(): ?Container
	{
		return $this->container;
	}

	public function creator(): CreatorInterface
	{
		return $this;
	}
}
