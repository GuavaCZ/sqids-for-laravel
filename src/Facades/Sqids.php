<?php

namespace Guava\Sqids\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Guava\Sqids\Sqids
 */
class Sqids extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Guava\Sqids\Sqids::class;
    }

    public static function make(array $parameters = []): \Guava\Sqids\Sqids
    {
        return app(static::getFacadeAccessor(), $parameters);
    }
}
