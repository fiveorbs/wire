<?php

declare(strict_types=1);

namespace FiveOrbs\Wire\Tests\Fixtures;

use FiveOrbs\Wire\Call;
use FiveOrbs\Wire\Inject;
use FiveOrbs\Wire\Type;

#[Call('callThis')]
class TestClassInject
{
    public ?Container $container = null;
    public ?TestClassApp $app = null;
    public ?TestClass $testobj = null;
    public string $arg1 = '';
    public int $arg2 = 0;
    public string $calledArg1 = '';
    public int $calledArg2 = 0;

    public function __construct(
        #[Inject('arg1', Type::Literal)]
        string $arg1,
        Container $container,
        #[Inject('injected')]
        TestClassApp $app,
        #[Inject(13)]
        int $arg2,
        #[Inject(TestClassExtended::class)]
        TestClass $testobj,
    ) {
        $this->container = $container;
        $this->app = $app;
        $this->arg1 = $arg1;
        $this->arg2 = $arg2;
        $this->testobj = $testobj;
    }

    public function callThis(
        #[Inject('calledArg1')]
        string $calledArg1,
        #[Inject(73)]
        int $calledArg2
    ): void {
        $this->calledArg1 = $calledArg1;
        $this->calledArg2 = $calledArg2;
    }

    public static function injectTypes(
        #[Inject('the-entry', Type::Literal)]
        string $literal,
        #[Inject(TestClass::class, Type::Create)]
        object $create,
        #[Inject('the-entry', Type::Entry)]
        object $entry,
        #[Inject('TEST_ENV_VAR', Type::Env)]
        string|bool $env
    ): array {
        return [
            'literal' => $literal,
            'create' => $create,
            'entry' => $entry,
            'env' => $env,
        ];
    }

    public static function injectPredefinedType(#[Inject('the-type', Type::Entry)] object $value): array
    {
        return [
            'value' => $value,
        ];
    }

    public static function injectPredefinedTypeString(#[Inject('the-predefined-type')] object $value): array
    {
        return [
            'value' => $value,
        ];
    }

    public static function injectSimpleString(#[Inject('no-entry-or-class')] string $literal): string
    {
        return $literal;
    }

    public static function injectSimpleArray(
        #[Inject([13, 23])]
        array $literal2Values,
        #[Inject([13, 17, 23, 29, 31])]
        array $literalMoreValues
    ): array {
        return [$literal2Values, $literalMoreValues];
    }

    public static function injectEntryWithoutContainer(#[Inject('no-container', Type::Entry)] object $entry): object
    {
        return $entry;
    }

    public static function injectCreateNoClass(#[Inject('no-class', Type::Create)] object $create): object
    {
        return $create;
    }

    public static function injectEnvVarNoString(#[Inject(666, Type::Env)] string|bool $env): string
    {
        return $env;
    }

    public static function injectEnvVarDoesNotExist(
        #[Inject('CONIA_ENV_VAR_DOES_NOT_EXIST', Type::Env)]
        string|bool $env
    ): string|bool {
        return $env;
    }

    public static function injectWithCallback(
        #[Inject('test', Type::Callback, id: 'second')]
        string $value
    ): string|bool {
        return $value;
    }
}