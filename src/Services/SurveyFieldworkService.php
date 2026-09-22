<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyFieldworkEndpointInterface;

class SurveyFieldworkService
{
    protected ?string $surveyId = null;

    public function __construct(
        protected SurveyFieldworkEndpointInterface $surveyFieldworkEndpoint,
    ) {}

    public function setSurveyId(string $surveyId): self
    {
        $this->surveyId = $surveyId;

        return $this;
    }

    public function start(): void
    {
        $this->surveyFieldworkEndpoint->start($this->surveyId);
    }

    public function status(): int
    {
        return $this->surveyFieldworkEndpoint->status($this->surveyId);
    }

    public function counts(): array
    {
        return $this->surveyFieldworkEndpoint->counts($this->surveyId);
    }

    public function stop(array $surveysFieldworkStopRequestModel): void
    {
        $this->surveyFieldworkEndpoint->stop($this->surveyId, $surveysFieldworkStopRequestModel);
    }
}
