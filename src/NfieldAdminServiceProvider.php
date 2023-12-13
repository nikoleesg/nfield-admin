<?php

namespace Nikoleesg\NfieldAdmin;

use Nikoleesg\NfieldAdmin\Commands;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
            ->hasMigrations([
                'create_interviewers_table',
                'create_response_codes_table',
                'create_background_activities_table',
                'add_unique_to_interviewer_id.php'
            ])
            ->hasCommands([
                Commands\SyncCapiInterviewerCommand::class,
                Commands\SyncResponseCodesCommand::class,
                Commands\SyncBackgroundActivitiesDetailsCommand::class
            ]);
    }
}
