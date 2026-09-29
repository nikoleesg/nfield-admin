<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\InterviewersWorklogEndpointInterface;

final class InterviewersWorklogEndpoint extends BaseEndpoint implements InterviewersWorklogEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/interviewersWorklog";
    }

    public function download(array $interviewersWorklogRequestModel): array
    {
        $uri = $this->basePath();

        return $this->httpClient->post($uri, $interviewersWorklogRequestModel)->json();
    }
}
