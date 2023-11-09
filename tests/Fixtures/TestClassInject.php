<?php

declare(strict_types=1);

namespace Conia\Wire\Tests\Fixtures;

use Conia\Wire\Call;
use Conia\Wire\Inject;

#[Call('callThis')]
class TestClassInject
{
    public ?Container $container = null;
    public ?TestClassApp $app = null;
    public ?TestClass $tc = null;
    public string $arg1 = '';
    public int $arg2 = 0;
    public string $calledArg1 = '';
    public int $calledArg2 = 0;

    #[
        Inject(arg2: 13, tc: TestClassExtended::class),
        Inject(app: 'injected', arg1: ['arg1', Inject::Literal])
    ]
    public function __construct(
        string $arg1,
        Container $container,
        TestClassApp $app,
        int $arg2,
        TestClass $tc,
    ) {
        $this->container = $container;
        $this->app = $app;
        $this->arg1 = $arg1;
        $this->arg2 = $arg2;
        $this->tc = $tc;
    }

    #[Inject(calledArg2: 73, calledArg1: 'calledArg1')]
    public function callThis(string $calledArg1, int $calledArg2): void
    {
        $this->calledArg1 = $calledArg1;
        $this->calledArg2 = $calledArg2;
    }

    #[Inject(
        literal: ['the-entry', Inject::Literal],
        create: [TestClass::class, Inject::Create],
        entry: ['the-entry', Inject::Entry],
        env: ['TEST_ENV_VAR', Inject::Env],
    )]
    public static function injectTypes(string $literal, object $create, object $entry, string $env): array
    {
        return [
            'literal' => $literal,
            'create' => $create,
            'entry' => $entry,
            'env' => $env,
        ];
    }

    #[Inject(literal: 'no-entry-or-class')]
    public static function injectSimpleString(string $literal): string
    {
        return $literal;
    }

    #[Inject(literal2Values: [13, 23], literalMoreValues: [13, 17, 23, 29, 31])]
    public static function injectSimpleArray(array $literal2Values, array $literalMoreValues): array
    {
        return [$literal2Values, $literalMoreValues];
    }

    #[Inject(entry: [666, Inject::Entry])]
    public static function injectEntryNoString(object $entry): object
    {
        return $entry;
    }

    #[Inject(entry: ['no-container', Inject::Entry])]
    public static function injectEntryWithoutContainer(object $entry): object
    {
        return $entry;
    }

    #[Inject(entry: [666, Inject::Create])]
    public static function injectCreateNoString(object $create): object
    {
        return $create;
    }

    #[Inject(entry: ['no-class', Inject::Create])]
    public static function injectCreateNoClass(object $create): object
    {
        return $create;
    }

    #[Inject(env: [666, Inject::Env])]
    public static function injectEnvVarNoString(string $env): string
    {
        return $env;
    }
}
