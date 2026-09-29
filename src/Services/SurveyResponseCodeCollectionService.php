<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyResponseCodeCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyResponseCodeModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

/**
 * A survey's response codes, reached through `$survey->responseCodes()`.
 *
 * Services mirror the endpoint naming: this pairs with
 * SurveyResponseCodeCollectionEndpoint, and {@see SurveyResponseCodeService}
 * with SurveyResponseCodeEndpoint.
 */
class SurveyResponseCodeCollectionService implements SurveyScopedInterface
{
    use ScopedToSurvey;

    public function __construct(
        protected SurveyResponseCodeCollectionEndpointInterface $surveyResponseCodeCollectionEndpoint,
    ) {}

    /** @return Collection<int, SurveyResponseCodeModel> */
    public function list(): Collection
    {
        return SurveyResponseCodeModel::collect(
            $this->surveyResponseCodeCollectionEndpoint->list($this->getSurveyId()),
            Collection::class
        );
    }

    /**
     * Filter and sort with OData query options, e.g. `['$filter' => 'IsDefinite eq true']`.
     *
     * @param  array<string, mixed>  $filter
     * @return Collection<int, SurveyResponseCodeModel>
     */
    public function find(array $filter): Collection
    {
        return SurveyResponseCodeModel::collect(
            $this->surveyResponseCodeCollectionEndpoint->find($this->getSurveyId(), $filter),
            Collection::class
        );
    }

    /**
     * @param  array<string, mixed>|SurveyResponseCodeModel  $data
     */
    public function create(array|SurveyResponseCodeModel $data): SurveyResponseCodeModel
    {
        $payload = SurveyResponseCodeModel::from($data)->toArray();

        return SurveyResponseCodeModel::from(
            $this->surveyResponseCodeCollectionEndpoint->create($this->getSurveyId(), $payload)
        );
    }

    /**
     * One response code of this survey.
     */
    public function forResponseCode(int $responseCode): SurveyResponseCodeService
    {
        return app(SurveyResponseCodeService::class)
            ->setSurveyId($this->getSurveyId())
            ->setResponseCode($responseCode);
    }
}
