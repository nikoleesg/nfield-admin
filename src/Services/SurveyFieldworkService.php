<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyFieldworkEndpointInterface;

class SurveyFieldworkService
{
    public function __construct(
        protected SurveyFieldworkEndpointInterface $surveyFieldworkEndpoint,
        protected readonly string $surveyId,
    ) {}

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
