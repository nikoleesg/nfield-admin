<?php

namespace Nikoleesg\NfieldAdmin;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Nikoleesg\NfieldAdmin\Commands\NfieldAdminCommand;

class NfieldAdminServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('nfield-admin')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_nfield-admin_table')
            ->hasCommand(NfieldAdminCommand::class);
    }
}
