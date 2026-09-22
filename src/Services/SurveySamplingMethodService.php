<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySamplingMethodEndpointInterface;

class SurveySamplingMethodService
{
    protected ?string $surveyId = null;

    public function __construct(
        protected SurveySamplingMethodEndpointInterface $surveySamplingMethodEndpoint,
    ) {}

    public function setSurveyId(string $surveyId): self
    {
        $this->surveyId = $surveyId;

        return $this;
    }

    public function getSamplingMethod(): array
    {
        return $this->surveySamplingMethodEndpoint->get($this->surveyId);
    }

    public function setSamplingMethod(array $samplingMethodModel): void
    {
        $this->surveySamplingMethodEndpoint->update($this->surveyId, $samplingMethodModel);
    }
}
