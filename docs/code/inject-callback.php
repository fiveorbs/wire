<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Conia\Wire\Inject;
use Conia\Wire\Tests\Fixtures\Container;
use Conia\Wire\Type;
use Conia\Wire\Wire;

class Value
{
    public function __construct(public readonly string $str)
    {
    }
}

class Model
{
    public function __construct(
        #[Inject(Value::class, Type::Callback, tag: 'tag2')]
        public readonly Value $value
    ) {
    }
}

$container = new Container();
$container->add(Value::class . 'tag1', new Value('Tagged with 1'));
$container->add(Value::class . 'tag2', new Value('Tagged with 2'));

$creator = Wire::creator($container);

$model = $creator->create(
    Model::class,
    injectCallback: function (Inject $inject) use ($container): mixed {
        return $container->get($inject->value . $inject->meta['tag']);
    }
);

assert($model instanceof Model);
assert($model->value->str === 'Tagged with 2');
