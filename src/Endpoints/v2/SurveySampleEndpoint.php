<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleEndpointInterface;

final class SurveySampleEndpoint extends BaseEndpoint implements SurveySampleEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    public function get(string $surveyId, int $interviewId): string
    {
        $uri = $this->subResourceItemPath($surveyId, 'sample', $interviewId);

        return $this->httpClient->get($uri)->body();
    }
}
