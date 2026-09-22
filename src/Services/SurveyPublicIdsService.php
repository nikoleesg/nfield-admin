<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyPublicIdsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyPublicIdModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

class SurveyPublicIdsService implements SurveyScopedInterface
{
    use ScopedToSurvey;

    public function __construct(
        protected SurveyPublicIdsEndpointInterface $surveyPublicIdsEndpoint,
    ) {}

    /** @return Collection<int, SurveyPublicIdModel> */
    public function list(): Collection
    {
        return SurveyPublicIdModel::collect(
            $this->surveyPublicIdsEndpoint->list($this->getSurveyId()),
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

        $this->surveyPublicIdsEndpoint->update($this->getSurveyId(), $payload);
    }
}
