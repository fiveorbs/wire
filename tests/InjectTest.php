<?php

declare(strict_types=1);

namespace Conia\Wire\Tests;

use Conia\Wire\CallableResolver;
use Conia\Wire\ConstructorResolver;
use Conia\Wire\Creator;
use Conia\Wire\Exception\WireException;
use Conia\Wire\Inject;
use Conia\Wire\Tests\Fixtures\Container;
use Conia\Wire\Tests\Fixtures\TestClass;
use Conia\Wire\Tests\Fixtures\TestClassApp;
use Conia\Wire\Tests\Fixtures\TestClassInject;
use Conia\Wire\Tests\TestCase;
use Conia\Wire\Type;

final class InjectTest extends TestCase
{
    public function testInjectClosureWithAttribute(): void
    {
        $container = $this->container();
        $container->add('injected', new TestClassApp('injected'));
        $creator = new Creator($container);
        $resolver = new CallableResolver($creator);

        $func = #[Inject(name: ['Chuck', Type::Literal], app: 'injected')] function (
            Container $r,
            TestClassApp $app,
            string $name
        ): array {
            return [$app->app, $name, $r];
        };

        $result = $func(...$resolver->resolve($func));

        $this->assertSame('injected', $result[0]);
        $this->assertSame('Chuck', $result[1]);
        $this->assertInstanceOf(Container::class, $result[2]);
    }

    public function testInjectConstructorWithAttribute(): void
    {
        $container = $this->container();
        $container->add('injected', new TestClassApp('injected'));
        $creator = new Creator($container);
        $resolver = new ConstructorResolver($creator);

        $args = $resolver->resolve(TestClassInject::class);
        $obj = new TestClassInject(...$args);

        $this->assertSame('injected', $obj->app->app());
        $this->assertSame('arg1', $obj->arg1);
        $this->assertSame(13, $obj->arg2);
        $this->assertInstanceOf(Container::class, $obj->container);
        $this->assertSame('Stringable extended', (string)$obj->tc);
    }

    public function testInjectAttributeDoesNotAllowUnnamedArgs(): void
    {
        $this->throws(WireException::class, 'Arguments for Inject');

        new Inject('arg');
    }

    public function testInjectTypedArguments(): void
    {
        putenv('TEST_ENV_VAR=test-env-value');
        $container = $this->container();
        $container->add('the-entry', new TestClassApp('the-entry'));
        $creator = new Creator($container);
        $resolver = new CallableResolver($creator);
        $args = $resolver->resolve([TestClassInject::class, 'injectTypes']);
        $result = TestClassInject::injectTypes(...$args);

        $this->assertSame('the-entry', $result['literal']);
        $this->assertInstanceOf(TestClass::class, $result['create']);
        $this->assertSame('the-entry', $result['entry']->app());
        $this->assertSame('test-env-value', $result['env']);
    }

    public function testInjectSimpleString(): void
    {
        $resolver = new CallableResolver(new Creator());
        $args = $resolver->resolve([TestClassInject::class, 'injectSimpleString']);
        $result = TestClassInject::injectSimpleString(...$args);

        $this->assertSame('no-entry-or-class', $result);
    }

    public function testInjectSimpleArray(): void
    {
        $resolver = new CallableResolver(new Creator());
        $args = $resolver->resolve([TestClassInject::class, 'injectSimpleArray']);
        $result = TestClassInject::injectSimpleArray(...$args);

        $this->assertSame([13, 23], $result[0]);
        $this->assertSame([13, 17, 23, 29, 31], $result[1]);
    }

    public function testInjectAdhocEntry(): void
    {
        $container = $this->container();
        $creator = new Creator($container);
        $resolver = new CallableResolver($creator);
        $args = $resolver->resolve(
            [TestClassInject::class, 'injectAdhocEntry'],
            adhocEntries: ['the-entry' => new TestClassApp('the-adhoc-entry')]
        );
        $result = TestClassInject::injectAdhocEntry(...$args);

        $this->assertSame('the-adhoc-entry', $result['entry']->app());
    }

    public function testInjectAdhocString(): void
    {
        $creator = new Creator();
        $resolver = new CallableResolver($creator);
        $args = $resolver->resolve(
            [TestClassInject::class, 'injectAdhocStringEntry'],
            adhocEntries: ['the-adhoc-entry' => new TestClassApp('the-adhoc-entry')]
        );
        $result = TestClassInject::injectAdhocEntry(...$args);

        $this->assertSame('the-adhoc-entry', $result['entry']->app());
    }

    public function testInjectEnvVarDoesNotExist(): void
    {
        $resolver = new CallableResolver(new Creator());
        $args = $resolver->resolve([TestClassInject::class, 'injectEnvVarDoesNotExist']);

        $this->assertSame(false, $args[0]);
    }

    public function testInjectEntryWithoutContainer(): void
    {
        $this->throws(WireException::class, 'No container');

        $resolver = new CallableResolver(new Creator());
        $resolver->resolve([TestClassInject::class, 'injectEntryWithoutContainer']);
    }

    public function testInjectCreateNoClass(): void
    {
        $this->throws(WireException::class, 'No valid class string "no-class"');

        $resolver = new CallableResolver(new Creator());
        $resolver->resolve([TestClassInject::class, 'injectCreateNoClass']);
    }

    public function testInjectEnvVarNoString(): void
    {
        $this->throws(WireException::class, 'must be a string');

        $resolver = new CallableResolver(new Creator());
        $resolver->resolve([TestClassInject::class, 'injectEnvVarNoString']);
    }
}
