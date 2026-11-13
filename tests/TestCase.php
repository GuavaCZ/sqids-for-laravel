<?php

namespace Guava\Sqids\Tests;

use Guava\Sqids\SqidsServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Guava\\Sqids\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            SqidsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
    }

    protected function defineDatabaseMigrations(): void
    {
        foreach (['posts', 'comments'] as $table) {
            Schema::create($table, function (Blueprint $table) {
                $table->id();
            });
        }

        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable()->unique();
            $table->string('summary')->nullable();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('sqid')->nullable()->unique();
        });
    }
}
