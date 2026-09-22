<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySamplingMethodEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingMethodModel;

class SurveySamplingMethodService
{
    public function __construct(
        protected SurveySamplingMethodEndpointInterface $surveySamplingMethodEndpoint,
        protected readonly string $surveyId,
    ) {}

    public function getSamplingMethod(): SamplingMethodModel
    {
        return SamplingMethodModel::from($this->surveySamplingMethodEndpoint->get($this->surveyId));
    }

    public function setSamplingMethod(array|SamplingMethodModel $data): void
    {
        $payload = SamplingMethodModel::from($data)->toArray();

        $this->surveySamplingMethodEndpoint->update($this->surveyId, $payload);
    }
}
