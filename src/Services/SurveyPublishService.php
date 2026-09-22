<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyPublishEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityStatus;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyPublishStateModel;
use Nikoleesg\NfieldAdmin\Enums\SurveyPackageTypeEnum;
use Nikoleesg\NfieldAdmin\Enums\SurveyPublishForceUpgradeEnum;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

class SurveyPublishService implements SurveyScopedInterface
{
    use ScopedToSurvey;

    public function __construct(
        protected SurveyPublishEndpointInterface $surveyPublishEndpoint,
    ) {}

    public function getState(): SurveyPublishStateModel
    {
        return SurveyPublishStateModel::from(
            $this->surveyPublishEndpoint->getPublishState($this->getSurveyId())
        );
    }

    public function publish(SurveyPackageTypeEnum $packageType, SurveyPublishForceUpgradeEnum $forceUpgrade): void
    {
        $this->surveyPublishEndpoint->publish($this->getSurveyId(), [
            'packageType' => $packageType->value,
            'forceUpgrade' => $forceUpgrade->value,
        ]);
    }

    public function start(SurveyPackageTypeEnum $packageType, SurveyPublishForceUpgradeEnum $forceUpgrade): BackgroundActivityStatus
    {
        return BackgroundActivityStatus::from(
            $this->surveyPublishEndpoint->startPublish($this->getSurveyId(), [
                'packageType' => $packageType->value,
                'forceUpgrade' => $forceUpgrade->value,
            ])
        );
    }

    public function publishLive(): void
    {
        $this->publish(SurveyPackageTypeEnum::Live, SurveyPublishForceUpgradeEnum::NoUpgrade);
    }

    public function forcePublishLive(): void
    {
        $this->publish(SurveyPackageTypeEnum::Live, SurveyPublishForceUpgradeEnum::ForceUpgrade);
    }

    public function publishTest(): void
    {
        $this->publish(SurveyPackageTypeEnum::Test, SurveyPublishForceUpgradeEnum::NoUpgrade);
    }

    public function startPublishLive(): BackgroundActivityStatus
    {
        return $this->start(SurveyPackageTypeEnum::Live, SurveyPublishForceUpgradeEnum::NoUpgrade);
    }

    public function startForcePublishLive(): BackgroundActivityStatus
    {
        return $this->start(SurveyPackageTypeEnum::Live, SurveyPublishForceUpgradeEnum::ForceUpgrade);
    }
}
