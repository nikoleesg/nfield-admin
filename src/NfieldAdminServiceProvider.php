<?php

namespace Nikoleesg\NfieldAdmin;

use Nikoleesg\NfieldAdmin\Commands;
use Nikoleesg\NfieldAdmin\Contracts\Http\HttpClientInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyEndpoint as SurveyEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyCollectionEndpoint as SurveyCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyResourceEndpoint as SurveyResourceEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\FieldworkEndpoint as FieldworkEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleEndpoint as SurveySampleEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleCollectionEndpoint as SurveySampleCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleResourceEndpoint as SurveySampleResourceEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyQuotaTargetsEndpointInterface as SurveyQuotaTargetsEndpointInterface;

use Nikoleesg\NfieldAdmin\Endpoints\v2\SurveyEndpoint;
use Nikoleesg\NfieldAdmin\Endpoints\v2\FieldworkEndpoint;
use Nikoleesg\NfieldAdmin\Endpoints\v2\SurveySampleEndpoint;
use Nikoleesg\NfieldAdmin\Endpoints\v2\SurveyQuotaTargetsEndpoint;
use Nikoleesg\NfieldAdmin\Services\Http\HttpClient;
use Nikoleesg\NfieldAdmin\Services\NfieldManagerService;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints as EndpointsContract;
use Nikoleesg\NfieldAdmin\Endpoints\v2 as EndpointsV2;



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
                'create_background_activities_table'
            ])
            ->hasCommands([
                Commands\SyncCapiInterviewerCommand::class,
                Commands\SyncResponseCodesCommand::class,
                Commands\SyncBackgroundActivitiesDetailsCommand::class
            ]);
    }

    public function registeringPackage()
    {
        $this->app->singleton('nfield-manager', function ($app) {
            return new NfieldManagerService();
        });

        $this->app->singleton(HttpClientInterface::class, HttpClient::class);

//        $this->app->singleton(SurveyCollectionEndpointInterface::class, SurveyEndpoint::class);
//        $this->app->singleton(SurveyResourceEndpointInterface::class, SurveyEndpoint::class);
        $this->app->singleton(SurveyEndpointInterface::class, SurveyEndpoint::class);

        $this->app->singleton(FieldworkEndpointInterface::class, FieldworkEndpoint::class);

        $this->app->singleton(SurveySampleCollectionEndpointInterface::class, SurveySampleEndpoint::class);
        $this->app->singleton(SurveySampleResourceEndpointInterface::class, SurveySampleEndpoint::class);
        $this->app->singleton(SurveySampleEndpointInterface::class, SurveySampleEndpoint::class);

        $this->app->singleton(SurveyQuotaTargetsEndpointInterface::class, SurveyQuotaTargetsEndpointInterface::class);

        $this->app->singleton(EndpointsContract\SurveyQuotaFrameEndpointInterface::class, EndpointsV2\SurveyQuotaFrameEndpoint::class);
        $this->app->singleton(EndpointsContract\SurveyQuotaTargetsEndpointInterface::class, EndpointsV2\SurveyQuotaTargetsEndpoint::class);
    }
}
