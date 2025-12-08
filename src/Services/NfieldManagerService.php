<?php

namespace Nikoleesg\NfieldAdmin\Services;

use BadMethodCallException;
use Nikoleesg\NfieldAdmin\Data\SurveyData;
use Nikoleesg\NfieldAdmin\Resources\SamplingPointResource;
use Nikoleesg\NfieldAdmin\Resources\SurveyResource;
use Spatie\LaravelData\DataCollection;

/**
 * @method DataCollection|SurveyData|null find(string|array $surveyId)
 */
class NfieldManagerService
{
    // TODO: add other (level 1) services, e.g. capiInterviewer

    public function __construct(
        protected SurveyService $surveyService,
    ) {}

    public function __call(string $name, array $arguments)
    {
        $registry = $this->serviceRegistry();

        if (array_key_exists($name, $registry)) {
            [$serviceClass, $methodName] = $registry[$name];
            return $this->resolveService($serviceClass)->$methodName(...$arguments);
        }

        throw new BadMethodCallException("Method $name does not exist.");
    }

    protected function resolveService(string $serviceClass)
    {
        // Lazy load with property caching
        $property = lcfirst(class_basename($serviceClass));

        if (!isset($this->$property)) {
            $this->$property = app($serviceClass);
        }

        return $this->$property;
    }

    protected function serviceRegistry(): array
    {
        return [
            'listSurveys'           => [SurveyService::class, 'listSurveys'],
            'findSurveys'           => [SurveyService::class, 'findSurveys'],
            'createSurvey'          => [SurveyService::class, 'createSurvey'],
            'searchRespondent'      => [SurveyService::class, 'findSurveysByRespondent'],
            'getBackgroundActivity' => [BackgroundActivitiesService::class, 'getBackgroundActivity'],
        ];
    }

    public function withSurvey(string $surveyId): SurveyResource
    {
        return $this->surveyService
            ->forSurvey($surveyId);
    }

    public function withSurveySamplingPoint(string $surveyId, string $samplingPoint): SamplingPointResource
    {
        return $this
            ->surveyService
            ->forSurvey($surveyId)
            ->samplingPoints()
            ->forSamplingPoint($samplingPoint);
    }
}
