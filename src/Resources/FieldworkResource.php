<?php

namespace Nikoleesg\NfieldAdmin\Resources;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\FieldworkEndpoint as FieldworkEndpointInterface;

class FieldworkResource
{
    public function __construct(
        protected FieldworkEndpointInterface $endpoint,
        protected string $surveyId
    ) {}

    public function start(): void
    {
        $this->endpoint->start($this->surveyId);
    }

    public function status()
    {
        return $this->endpoint->status($this->surveyId);
    }

    public function counts()
    {
        return $this->endpoint->counts($this->surveyId);
    }

    public function stop(array $surveysFieldworkStopRequestModel): void
    {
        $this->endpoint->stop($this->surveyId, $surveysFieldworkStopRequestModel);
    }

}
