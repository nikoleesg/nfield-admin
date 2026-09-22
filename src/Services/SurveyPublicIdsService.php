<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyPublicIdsEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyPublicIdModel;

class SurveyPublicIdsService
{
    public function __construct(
        protected SurveyPublicIdsEndpointInterface $surveyPublicIdsEndpoint,
        protected readonly string $surveyId,
    ) {}

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
            fn (SurveyPublicIdModel $model) => $model->toArray(),
            $models
        );

        $this->surveyPublicIdsEndpoint->update($this->surveyId, $payload);
    }
}
