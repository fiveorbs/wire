<?php

declare(strict_types=1);

namespace FiveOrbs\Wire\Tests;

use FiveOrbs\Wire\Creator;
use FiveOrbs\Wire\Exception\WireException;
use FiveOrbs\Wire\Tests\Fixtures\TestClassDefault;
use FiveOrbs\Wire\Tests\Fixtures\TestClassIntersectionTypeConstructor;
use FiveOrbs\Wire\Tests\Fixtures\TestClassUnionTypeConstructor;
use FiveOrbs\Wire\Tests\Fixtures\TestClassUntypedConstructor;

final class CreatorUnresolvableTest extends TestCase
{
    public function testTryToResolveUnresolvable(): void
    {
        $this->throws(WireException::class, 'Unresolvable');

        $creator = new Creator();
        $creator->create(TestClassDefault::class);
    }

    public function testRejectClassWithUntypedConstructor(): void
    {
        $this->throws(WireException::class, 'typed constructor parameters');

        $creator = new Creator();
        $creator->create(TestClassUntypedConstructor::class);
    }

    public function testRejectClassWithUnsupportedConstructorUnionTypes(): void
    {
        $this->throws(WireException::class, 'union or intersection');

        $creator = new Creator();
        $creator->create(TestClassUnionTypeConstructor::class);
    }

    public function testRejectClassWithUnsupportedConstructorIntersectionTypes(): void
    {
        $this->throws(WireException::class, 'union or intersection');

        $creator = new Creator();
        $creator->create(TestClassIntersectionTypeConstructor::class);
    }
}