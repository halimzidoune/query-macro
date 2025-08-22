<?php

namespace Hz\QueryMacroHelper\Tests;

use Hz\QueryMacroHelper\QueryMacroHelperServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [QueryMacroHelperServiceProvider::class];
    }

    protected function defineEnvironment($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate', ['--path' => 'tests/fixtures/migrations', '--database' => 'testing'])->run();
    }

    protected function tearDown(): void
    {
        $this->artisan('migrate:reset', ['--database' => 'testing'])->run();
        parent::tearDown();
    }
} 