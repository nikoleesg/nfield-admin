<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySamplingMethodEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingMethodModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

class SurveySamplingMethodService implements SurveyScopedInterface
{
    use ScopedToSurvey;

    public function __construct(
        protected SurveySamplingMethodEndpointInterface $surveySamplingMethodEndpoint,
    ) {}

    public function getSamplingMethod(): SamplingMethodModel
    {
        return SamplingMethodModel::from($this->surveySamplingMethodEndpoint->get($this->getSurveyId()));
    }

    public function setSamplingMethod(array|SamplingMethodModel $data): void
    {
        $payload = SamplingMethodModel::from($data)->toArray();

        $this->surveySamplingMethodEndpoint->update($this->getSurveyId(), $payload);
    }
}
