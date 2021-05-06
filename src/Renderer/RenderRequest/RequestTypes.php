<?php

namespace App\Renderer\RenderRequest;

use ReflectionClass;

class RequestTypes
{
    const LANDSCAPE_YEAR = 'LandscapeYear';

    public static function isValidRequestType(string $type): bool
    {
        $reflection = new ReflectionClass(RequestTypes::class);
        return in_array($type, $reflection->getConstants());
    }
}
