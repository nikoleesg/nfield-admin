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

    public function get(): SamplingMethodModel
    {
        return SamplingMethodModel::from($this->surveySamplingMethodEndpoint->get($this->getSurveyId()));
    }

    /**
     * @param  array<string, mixed>|SamplingMethodModel  $data
     */
    public function update(array|SamplingMethodModel $data): void
    {
        $payload = SamplingMethodModel::from($data)->toArray();

        $this->surveySamplingMethodEndpoint->update($this->getSurveyId(), $payload);
    }
}
