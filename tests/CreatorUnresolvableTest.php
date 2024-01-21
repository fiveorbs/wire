<?php

declare(strict_types=1);

namespace Conia\Wire\Tests;

use Conia\Wire\Creator;
use Conia\Wire\Exception\WireException;
use Conia\Wire\Tests\Fixtures\TestClassDefault;
use Conia\Wire\Tests\Fixtures\TestClassIntersectionTypeConstructor;
use Conia\Wire\Tests\Fixtures\TestClassUnionTypeConstructor;
use Conia\Wire\Tests\Fixtures\TestClassUntypedConstructor;

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
