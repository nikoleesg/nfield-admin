<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyScriptEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyGetScriptModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveySetScriptModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

/**
 * A survey's current ODIN script, reached through `$survey->script()`. The
 * script of an earlier version is `$survey->versions()->forVersion($eTag)->script()`.
 *
 * Services mirror the endpoint naming: this pairs with SurveyScriptEndpoint.
 */
class SurveyScriptService implements SurveyScopedInterface
{
    use ScopedToSurvey;

    public function __construct(
        protected SurveyScriptEndpointInterface $surveyScriptEndpoint,
    ) {}

    public function get(): SurveyGetScriptModel
    {
        return SurveyGetScriptModel::from($this->surveyScriptEndpoint->get($this->getSurveyId()));
    }

    /**
     * Replace the script. A plain string is the script itself; the response
     * carries any parse warnings.
     *
     * @param  string|array<string, mixed>|SurveySetScriptModel  $data
     */
    public function update(string|array|SurveySetScriptModel $data): SurveyGetScriptModel
    {
        $payload = SurveySetScriptModel::from(is_string($data) ? ['script' => $data] : $data)->toArray();

        return SurveyGetScriptModel::from($this->surveyScriptEndpoint->update($this->getSurveyId(), $payload));
    }
}
