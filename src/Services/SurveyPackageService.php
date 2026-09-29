<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyPackageEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\Package\SurveyPackageV1Model;
use Nikoleesg\NfieldAdmin\Enums\SurveyPackageTypeEnum;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

/**
 * A survey's published packages, reached through `$survey->package()`.
 *
 * Services mirror the endpoint naming: this pairs with SurveyPackageEndpoint.
 */
class SurveyPackageService implements SurveyScopedInterface
{
    use ScopedToSurvey;

    public function __construct(
        protected SurveyPackageEndpointInterface $surveyPackageEndpoint,
    ) {}

    /**
     * The live or test package, as it was published.
     */
    public function get(SurveyPackageTypeEnum $type = SurveyPackageTypeEnum::Live): SurveyPackageV1Model
    {
        return SurveyPackageV1Model::from($this->surveyPackageEndpoint->get($this->getSurveyId(), $type->value));
    }
}
