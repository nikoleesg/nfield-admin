<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyPackageEndpointInterface;

final class SurveyPackageEndpoint extends BaseEndpoint implements SurveyPackageEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    public function get(string $surveyId, int $type): array
    {
        $uri = $this->subResourcePath($surveyId, 'package');

        return $this->httpClient->get($uri, ['type' => $type])->json();
    }
}
