<?php

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyPublicIdsEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyPublicIdModel;

class SurveyPublicIdsService
{
    protected ?string $surveyId = null;

    public function __construct(
        protected SurveyPublicIdsEndpointInterface $surveyPublicIdsEndpoint,
    ) {}

    public function setSurveyId(string $surveyId): self
    {
        $this->surveyId = $surveyId;

        return $this;
    }

    public function list(): Collection
    {
        return SurveyPublicIdModel::collect(
            $this->surveyPublicIdsEndpoint->list($this->surveyId),
            Collection::class
        );
    }

    public function update(array $models): void
    {
        $payload = array_map(
            fn(SurveyPublicIdModel $model) => $model->toArray(),
            $models
        );

        $this->surveyPublicIdsEndpoint->update($this->surveyId, $payload);
    }
}
