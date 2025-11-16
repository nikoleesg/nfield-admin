<?php

namespace Nikoleesg\NfieldAdmin\Services;

use BadMethodCallException;
use Nikoleesg\NfieldAdmin\Data\SurveyData;
use Nikoleesg\NfieldAdmin\Resources\SurveyResource;
use Spatie\LaravelData\DataCollection;

/**
 * @method DataCollection|SurveyData|null find(string|array $surveyId)
 */
class NfieldManagerService
{
    public function __construct(
        protected SurveyService $surveyService,
    ) {}

    public function __call(string $name, array $arguments)
    {
        if ($serviceClass = data_get($this->domainMethodReflection(), $name)) {
            return (new $serviceClass)->$name(...$arguments);
        }

        throw new BadMethodCallException("Method $name does not exist.");
    }

    protected function domainMethodReflection(): array
    {
        return [
            'find' => SurveyService::class,
            'search' => SurveyService::class,
        ];
    }


    public function withSurvey(string $surveyId): SurveyResource
    {
        return $this->surveyService->for($surveyId);
    }

    public function withSurveySamplingPoint(string $surveyId, string $samplingPoint): SamplingPointService
    {
        return new SamplingPointService($surveyId, $samplingPoint);
    }

}
