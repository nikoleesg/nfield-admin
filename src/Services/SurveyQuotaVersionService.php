<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyQuotaVersionsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\QuotaVersionScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\Quota\QuotaFrameModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToQuotaVersion;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

/**
 * One quota frame version, reached through
 * `$survey->quota()->versions()->forVersion($eTag)`.
 */
class SurveyQuotaVersionService implements QuotaVersionScopedInterface
{
    use ScopedToQuotaVersion;
    use ScopedToSurvey;

    public function __construct(
        protected SurveyQuotaVersionsEndpointInterface $surveyQuotaVersionsEndpoint,
    ) {}

    public function get(): QuotaFrameModel
    {
        return QuotaFrameModel::from(
            $this->surveyQuotaVersionsEndpoint->get($this->getSurveyId(), $this->getQuotaVersion())
        );
    }
}
