<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyResponseCodeCollectionEndpointInterface;

final class SurveyResponseCodeCollectionEndpoint extends BaseEndpoint implements SurveyResponseCodeCollectionEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    public function list(string $surveyId): array
    {
        return $this->find($surveyId, []);
    }

    public function find(string $surveyId, array $data): array
    {
        $uri = $this->subResourcePath($surveyId, 'responseCodes');

        // The spec advertises OData content types, so accept the envelope too.
        return $this->unwrapList($this->httpClient->get($uri, $data)->json());
    }

    public function create(string $surveyId, array $surveyResponseCodeModel): array
    {
        $uri = $this->subResourcePath($surveyId, 'responseCodes');

        return $this->httpClient->post($uri, $surveyResponseCodeModel)->json();
    }
}
