<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyScriptEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyVarFileEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyVersionScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyGetScriptModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyVarFileModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurveyVersion;

/**
 * One published version of a survey, reached through
 * `$survey->versions()->forVersion($eTag)`: the script and var file as they
 * were in that version.
 *
 * Services mirror the endpoint naming: this pairs with SurveyVersionsEndpoint's
 * eTags, read through the script/{eTag} and varFile/{eTag} endpoints.
 */
class SurveyVersionService implements SurveyVersionScopedInterface
{
    use ScopedToSurvey;
    use ScopedToSurveyVersion;

    public function __construct(
        protected SurveyScriptEndpointInterface $surveyScriptEndpoint,
        protected SurveyVarFileEndpointInterface $surveyVarFileEndpoint,
    ) {}

    public function script(): SurveyGetScriptModel
    {
        return SurveyGetScriptModel::from(
            $this->surveyScriptEndpoint->getVersion($this->getSurveyId(), $this->getSurveyVersion())
        );
    }

    public function varFile(): SurveyVarFileModel
    {
        return SurveyVarFileModel::from(
            $this->surveyVarFileEndpoint->getVersion($this->getSurveyId(), $this->getSurveyVersion())
        );
    }
}
