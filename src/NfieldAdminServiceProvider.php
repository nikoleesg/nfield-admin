<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints as Contracts;
use Nikoleesg\NfieldAdmin\Contracts\Http\HttpClientInterface;
use Nikoleesg\NfieldAdmin\Endpoints\v2 as Endpoints;
use Nikoleesg\NfieldAdmin\Services\Http\HttpClient;
use Nikoleesg\NfieldAdmin\Services\NfieldManagerService;
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
            ->hasConfigFile();
    }

    public function registeringPackage(): void
    {
        $this->app->singleton('nfield-manager', NfieldManagerService::class);

        $this->app->singleton(HttpClientInterface::class, HttpClient::class);

        $this->app->singleton(Contracts\BackgroundActivitiesEndpointInterface::class, Endpoints\BackgroundActivitiesEndpoint::class);
        $this->app->singleton(Contracts\InterviewersWorklogEndpointInterface::class, Endpoints\InterviewersWorklogEndpoint::class);
        $this->app->singleton(Contracts\ResponseCodeCollectionEndpointInterface::class, Endpoints\ResponseCodeCollectionEndpoint::class);
        $this->app->singleton(Contracts\ResponseCodeEndpointInterface::class, Endpoints\ResponseCodeEndpoint::class);

        // CAPI Interviewers
        $this->app->singleton(Contracts\CapiInterviewersCollectionEndpointInterface::class, Endpoints\CapiInterviewersCollectionEndpoint::class);
        $this->app->singleton(Contracts\CapiInterviewersEndpointInterface::class, Endpoints\CapiInterviewersEndpoint::class);
        $this->app->singleton(Contracts\CapiInterviewersAssignmentsEndpointInterface::class, Endpoints\CapiInterviewersAssignmentsEndpoint::class);
        $this->app->singleton(Contracts\CapiInterviewersOfficesEndpointInterface::class, Endpoints\CapiInterviewersOfficesEndpoint::class);

        $this->app->singleton(Contracts\SurveyCollectionEndpointInterface::class, Endpoints\SurveyCollectionEndpoint::class);
        $this->app->singleton(Contracts\SurveyEndpointInterface::class, Endpoints\SurveyEndpoint::class);
        $this->app->singleton(Contracts\SurveyDataRetentionSettingsEndpointInterface::class, Endpoints\SurveyDataRetentionSettingsEndpoint::class);
        $this->app->singleton(Contracts\SurveyMoveEndpointInterface::class, Endpoints\SurveyMoveEndpoint::class);
        $this->app->singleton(Contracts\SurveyVersionsEndpointInterface::class, Endpoints\SurveyVersionsEndpoint::class);
        $this->app->singleton(Contracts\SurveyResourceUsageEndpointInterface::class, Endpoints\SurveyResourceUsageEndpoint::class);
        $this->app->singleton(Contracts\SurveyGroupCollectionEndpointInterface::class, Endpoints\SurveyGroupCollectionEndpoint::class);
        $this->app->singleton(Contracts\SurveyGroupEndpointInterface::class, Endpoints\SurveyGroupEndpoint::class);
        $this->app->singleton(Contracts\SurveyGroupDirectoryAssignmentsEndpointInterface::class, Endpoints\SurveyGroupDirectoryAssignmentsEndpoint::class);
        $this->app->singleton(Contracts\SurveyGroupLocalAssignmentsEndpointInterface::class, Endpoints\SurveyGroupLocalAssignmentsEndpoint::class);
        $this->app->singleton(Contracts\SurveyGroupSurveysEndpointInterface::class, Endpoints\SurveyGroupSurveysEndpoint::class);
        $this->app->singleton(Contracts\SurveySamplingPointsAssignmentsEndpointInterface::class, Endpoints\SurveySamplingPointsAssignmentsEndpoint::class);
        $this->app->singleton(Contracts\SurveyFieldworkEndpointInterface::class, Endpoints\SurveyFieldworkEndpoint::class);
        $this->app->singleton(Contracts\SurveyDataEndpointInterface::class, Endpoints\SurveyDataEndpoint::class);
        $this->app->singleton(Contracts\SurveyInterviewEndpointInterface::class, Endpoints\SurveyInterviewEndpoint::class);
        $this->app->singleton(Contracts\SurveySamplingMethodEndpointInterface::class, Endpoints\SurveySamplingMethodEndpoint::class);
        $this->app->singleton(Contracts\SurveyQuotaFrameEndpointInterface::class, Endpoints\SurveyQuotaFrameEndpoint::class);
        $this->app->singleton(Contracts\SurveyQuotaTargetsEndpointInterface::class, Endpoints\SurveyQuotaTargetsEndpoint::class);
        $this->app->singleton(Contracts\SurveyQuotaVersionsEndpointInterface::class, Endpoints\SurveyQuotaVersionsEndpoint::class);

        $this->app->singleton(Contracts\SurveySampleCollectionEndpointInterface::class, Endpoints\SurveySampleCollectionEndpoint::class);
        $this->app->singleton(Contracts\SurveySampleEndpointInterface::class, Endpoints\SurveySampleEndpoint::class);
        $this->app->singleton(Contracts\SurveySampleDataDownloadEndpointInterface::class, Endpoints\SurveySampleDataDownloadEndpoint::class);

        $this->app->singleton(Contracts\SurveyInterviewQualityCollectionEndpointInterface::class, Endpoints\SurveyInterviewQualityCollectionEndpoint::class);
        $this->app->singleton(Contracts\SurveyInterviewQualityEndpointInterface::class, Endpoints\SurveyInterviewQualityEndpoint::class);
        $this->app->singleton(Contracts\SurveyPerformanceEndpointInterface::class, Endpoints\SurveyPerformanceEndpoint::class);

        $this->app->singleton(Contracts\SamplingPointCollectionEndpointInterface::class, Endpoints\SamplingPointCollectionEndpoint::class);
        $this->app->singleton(Contracts\SamplingPointEndpointInterface::class, Endpoints\SamplingPointEndpoint::class);
        $this->app->singleton(Contracts\SamplingPointAddressCollectionEndpointInterface::class, Endpoints\SamplingPointAddressCollectionEndpoint::class);
        $this->app->singleton(Contracts\SamplingPointAddressEndpointInterface::class, Endpoints\SamplingPointAddressEndpoint::class);
        $this->app->singleton(Contracts\SamplingPointAssignmentEndpointInterface::class, Endpoints\SamplingPointAssignmentEndpoint::class);
        $this->app->singleton(Contracts\SamplingPointQuotaTargetsEndpointInterface::class, Endpoints\SamplingPointQuotaTargetsEndpoint::class);

        $this->app->singleton(Contracts\SurveySettingsEndpointInterface::class, Endpoints\SurveySettingsEndpoint::class);
        $this->app->singleton(Contracts\SurveyResponseCodeCollectionEndpointInterface::class, Endpoints\SurveyResponseCodeCollectionEndpoint::class);
        $this->app->singleton(Contracts\SurveyResponseCodeEndpointInterface::class, Endpoints\SurveyResponseCodeEndpoint::class);
        $this->app->singleton(Contracts\SurveyGeneralSettingsEndpointInterface::class, Endpoints\SurveyGeneralSettingsEndpoint::class);

        $this->app->singleton(Contracts\SurveyBlueprintsEndpointInterface::class, Endpoints\SurveyBlueprintsEndpoint::class);

        $this->app->singleton(Contracts\SurveyPublishEndpointInterface::class, Endpoints\SurveyPublishEndpoint::class);
        $this->app->singleton(Contracts\SurveyPublicIdsEndpointInterface::class, Endpoints\SurveyPublicIdsEndpoint::class);

        // Events
        $this->app->singleton(Contracts\SubscriptionCollectionEndpointInterface::class, Endpoints\SubscriptionCollectionEndpoint::class);
        $this->app->singleton(Contracts\SubscriptionEndpointInterface::class, Endpoints\SubscriptionEndpoint::class);

        // Roles
        $this->app->singleton(Contracts\UserRoleEndpointInterface::class, Endpoints\UserRoleEndpoint::class);
        $this->app->singleton(Contracts\RolesEndpointInterface::class, Endpoints\RolesEndpoint::class);
    }
}
