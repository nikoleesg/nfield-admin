<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyCountsModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyUpdateModel;
use Nikoleesg\NfieldAdmin\Resources\SurveyResource;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

/**
 * Operations on one survey, exposed through {@see SurveyResource}.
 *
 * Services mirror the endpoint naming: this pairs with SurveyEndpoint, and
 * {@see SurveyCollectionService} with SurveyCollectionEndpoint.
 */
class SurveyService implements SurveyScopedInterface
{
    use ScopedToSurvey;

    public function __construct(
        protected SurveyEndpointInterface $surveyEndpoint,
    ) {}

    public function get(): SurveyModel
    {
        return SurveyModel::from($this->surveyEndpoint->get($this->getSurveyId()));
    }

    /**
     * @param  array<string, mixed>|SurveyUpdateModel  $data
     */
    public function update(array|SurveyUpdateModel $data): SurveyModel
    {
        $payload = SurveyUpdateModel::from($data)->toArray();

        return SurveyModel::from($this->surveyEndpoint->updatePartial($this->getSurveyId(), $payload));
    }

    public function delete(): void
    {
        $this->surveyEndpoint->destroy($this->getSurveyId());
    }

    public function counts(): SurveyCountsModel
    {
        return SurveyCountsModel::from($this->surveyEndpoint->counts($this->getSurveyId()));
    }

    /** @return Collection<int, string> */
    public function customColumns(): Collection
    {
        return collect($this->surveyEndpoint->getCustomColumns($this->getSurveyId()));
    }
}
