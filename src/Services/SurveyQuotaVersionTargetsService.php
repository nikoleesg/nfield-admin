<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyQuotaFrameEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyQuotaTargetsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\QuotaVersionScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota\SurveyQuotaFrameEtagRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota\SurveyQuotaFrameEtagResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota\SurveysQuotaTargetsEtagResponseModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToQuotaVersion;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

/**
 * The targets of one quota frame version, reached through
 * `$survey->quota()->targets()->forVersion($eTag)`.
 */
class SurveyQuotaVersionTargetsService implements QuotaVersionScopedInterface
{
    use ScopedToQuotaVersion;
    use ScopedToSurvey;

    public function __construct(
        protected SurveyQuotaTargetsEndpointInterface $surveyQuotaTargetsEndpoint,
        protected SurveyQuotaFrameEndpointInterface $surveyQuotaFrameEndpoint,
    ) {}

    public function get(): SurveysQuotaTargetsEtagResponseModel
    {
        return SurveysQuotaTargetsEtagResponseModel::from(
            $this->surveyQuotaTargetsEndpoint->getQuotaTargetsByETag($this->getSurveyId(), $this->getQuotaVersion())
        );
    }

    /**
     * Update the targets of this version.
     *
     * The API exposes this as `PUT surveyQuotaFrame/{eTag}`, but it sets
     * targets for a frame version, so it lives with the version's targets
     * rather than with the frame.
     *
     * @param  array<string, mixed>|SurveyQuotaFrameEtagRequestModel  $data
     */
    public function update(array|SurveyQuotaFrameEtagRequestModel $data): SurveyQuotaFrameEtagResponseModel
    {
        $payload = SurveyQuotaFrameEtagRequestModel::from($data)->toArray();

        return SurveyQuotaFrameEtagResponseModel::from(
            $this->surveyQuotaFrameEndpoint->setQuotaLevelsTargets($this->getSurveyId(), $this->getQuotaVersion(), $payload)
        );
    }
}
