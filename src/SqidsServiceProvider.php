<?php

namespace Guava\Sqids;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SqidsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('sqids-for-laravel');

        $this->app->bind(Sqids::class, function ($app, $parameters) {
            return new Sqids(...$parameters);
        });
    }
}
