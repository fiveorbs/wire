<?php

declare(strict_types=1);

namespace Conia\Wire;

use ReflectionMethod;
use ReflectionParameter;

class ParameterInfo
{
    public static function info(ReflectionParameter $param): string
    {
        $type = $param->getType();
        $rfn = $param->getDeclaringFunction();
        $rcls = null;

        if ($rfn instanceof ReflectionMethod) {
            $rcls = $rfn->getDeclaringClass();
        }

        return ($rcls ? $rcls->getName() . '::' : '') .
            ($rfn->getName() . '(..., ') .
            ($type ? (string)$type . ' ' : '') .
            '$' . $param->getName() . ', ...)';
    }
}
