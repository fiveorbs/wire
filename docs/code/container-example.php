<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use FiveOrbs\Wire\Tests\Fixtures\Container;
use FiveOrbs\Wire\Wire;

class Value
{
	public function __construct(protected string $str) {}

	public function get(): string
	{
		return $this->str;
	}
}

class Model
{
	public function __construct(protected Value $value) {}

	public function get(): string
	{
		return $this->value->get();
	}
}

$container = new Container();
$container->add(Value::class, new Value('Model value'));

$creator = Wire::creator($container);
$model = $creator->create(Model::class);

assert($model->get() === 'Model value');
