<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyVarFileEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyVarFileModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

/**
 * A survey's current var file, reached through `$survey->varFile()`. The var
 * file of an earlier version is `$survey->versions()->forVersion($eTag)->varFile()`.
 *
 * Services mirror the endpoint naming: this pairs with SurveyVarFileEndpoint.
 */
class SurveyVarFileService implements SurveyScopedInterface
{
    use ScopedToSurvey;

    public function __construct(
        protected SurveyVarFileEndpointInterface $surveyVarFileEndpoint,
    ) {}

    public function get(): SurveyVarFileModel
    {
        return SurveyVarFileModel::from($this->surveyVarFileEndpoint->get($this->getSurveyId()));
    }
}
