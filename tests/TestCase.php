<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Tests;

use Illuminate\Support\Facades\Http;
use Nikoleesg\NfieldAdmin\NfieldAdminServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\LaravelData\LaravelDataServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        // No test may reach the real NField API: an unfaked request throws
        // instead of leaving the suite dependent on a network and credentials.
        Http::preventStrayRequests();
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelDataServiceProvider::class,
            NfieldAdminServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
    }
}
