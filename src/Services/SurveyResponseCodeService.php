<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyResponseCodeEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\ResponseCodeScopedInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyResponseCodeModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyResponseCodeUpdateModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToResponseCode;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

/**
 * One response code of a survey, reached through
 * `$survey->responseCodes()->forResponseCode($code)`.
 *
 * Services mirror the endpoint naming: this pairs with SurveyResponseCodeEndpoint,
 * and {@see SurveyResponseCodeCollectionService} with
 * SurveyResponseCodeCollectionEndpoint.
 */
class SurveyResponseCodeService implements ResponseCodeScopedInterface, SurveyScopedInterface
{
    use ScopedToResponseCode;
    use ScopedToSurvey;

    public function __construct(
        protected SurveyResponseCodeEndpointInterface $surveyResponseCodeEndpoint,
    ) {}

    public function get(): SurveyResponseCodeModel
    {
        return SurveyResponseCodeModel::from(
            $this->surveyResponseCodeEndpoint->get($this->getSurveyId(), $this->getResponseCode())
        );
    }

    /**
     * Update the given fields; fields not set on the model are not sent.
     *
     * @param  array<string, mixed>|SurveyResponseCodeUpdateModel  $data
     */
    public function update(array|SurveyResponseCodeUpdateModel $data): SurveyResponseCodeModel
    {
        $payload = SurveyResponseCodeUpdateModel::from($data)->toArray();

        return SurveyResponseCodeModel::from(
            $this->surveyResponseCodeEndpoint->update($this->getSurveyId(), $this->getResponseCode(), $payload)
        );
    }

    public function delete(): void
    {
        $this->surveyResponseCodeEndpoint->delete($this->getSurveyId(), $this->getResponseCode());
    }
}
