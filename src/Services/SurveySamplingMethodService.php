<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySamplingMethodEndpointInterface;

class SurveySamplingMethodService
{
    public function __construct(
        protected SurveySamplingMethodEndpointInterface $surveySamplingMethodEndpoint,
        protected readonly string $surveyId,
    ) {}

    public function getSamplingMethod(): array
    {
        return $this->surveySamplingMethodEndpoint->get($this->surveyId);
    }

    public function setSamplingMethod(array $samplingMethodModel): void
    {
        $this->surveySamplingMethodEndpoint->update($this->surveyId, $samplingMethodModel);
    }
}
