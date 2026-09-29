<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyResponseCodeEndpointInterface;

final class SurveyResponseCodeEndpoint extends BaseEndpoint implements SurveyResponseCodeEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    public function get(string $surveyId, int $responseCode): array
    {
        $uri = $this->subResourceItemPath($surveyId, 'responseCodes', $responseCode);

        return $this->httpClient->get($uri)->json();
    }

    public function update(string $surveyId, int $responseCode, array $surveyResponseCodeUpdateModel): array
    {
        $uri = $this->subResourceItemPath($surveyId, 'responseCodes', $responseCode);

        return $this->httpClient->patch($uri, $surveyResponseCodeUpdateModel)->json();
    }

    public function delete(string $surveyId, int $responseCode): void
    {
        $uri = $this->subResourceItemPath($surveyId, 'responseCodes', $responseCode);

        $this->httpClient->delete($uri);
    }
}
