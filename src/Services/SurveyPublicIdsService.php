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

    /** @return Collection<int, SurveyPublicIdModel> */
    public function list(): Collection
    {
        return SurveyPublicIdModel::collect(
            $this->surveyPublicIdsEndpoint->list($this->surveyId),
            Collection::class
        );
    }

    /**
     * @param  iterable<int, array|SurveyPublicIdModel>  $models
     */
    public function update(iterable $models): void
    {
        $payload = [];

        foreach ($models as $model) {
            $payload[] = SurveyPublicIdModel::from($model)->toArray();
        }

        $this->surveyPublicIdsEndpoint->update($this->surveyId, $payload);
    }
}
