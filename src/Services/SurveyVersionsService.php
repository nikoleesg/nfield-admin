<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyVersionsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyVersionModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

/**
 * A survey's published versions, reached through `$survey->versions()`.
 *
 * Services mirror the endpoint naming: this pairs with SurveyVersionsEndpoint.
 */
class SurveyVersionsService implements SurveyScopedInterface
{
    use ScopedToSurvey;

    public function __construct(
        protected SurveyVersionsEndpointInterface $surveyVersionsEndpoint,
    ) {}

    /** @return Collection<int, SurveyVersionModel> */
    public function list(): Collection
    {
        return SurveyVersionModel::collect(
            $this->surveyVersionsEndpoint->list($this->getSurveyId()),
            Collection::class
        );
    }
}
