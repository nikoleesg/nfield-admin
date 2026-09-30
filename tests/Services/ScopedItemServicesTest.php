<?php

declare(strict_types=1);

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\BackgroundActivitiesEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointQuotaTargetsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyInterviewQualityEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\InterviewDetailsModel;
use Nikoleesg\NfieldAdmin\Exceptions\MissingScopeException;
use Nikoleesg\NfieldAdmin\Resources\SurveyResource;
use Nikoleesg\NfieldAdmin\Services\BackgroundActivitiesService;
use Nikoleesg\NfieldAdmin\Services\SamplingPointQuotaTargetsService;
use Nikoleesg\NfieldAdmin\Services\SurveyInterviewQualityService;

/**
 * #72: the last item operations that took their id now select the item once.
 */
afterEach(function () {
    Mockery::close();
});

it('reads the quality record of the interview it is scoped to, by its string id', function () {
    $endpoint = Mockery::mock(SurveyInterviewQualityEndpointInterface::class);
    $endpoint->shouldReceive('get')->with('survey-1', 'int-1')->once()->andReturn([
        'id' => 'int-1', 'interviewQuality' => 1, 'interviewerId' => 'usr-1', 'samplingPointId' => null, 'officeId' => null,
    ]);

    app()->instance(SurveyInterviewQualityEndpointInterface::class, $endpoint);

    $record = (new SurveyResource)->setSurveyId('survey-1')->interviewQuality()->forInterview('int-1');

    expect($record)->toBeInstanceOf(SurveyInterviewQualityService::class)
        ->and($record->get())->toBeInstanceOf(InterviewDetailsModel::class);
});

it('refuses an item call before its item is selected', function (Closure $call) {
    expect($call)->toThrow(MissingScopeException::class);
})->with([
    'quota level' => fn () => (new SamplingPointQuotaTargetsService(Mockery::mock(SamplingPointQuotaTargetsEndpointInterface::class)))
        ->setSurveyId('survey-1')->setSamplingPointId('sp-1')->get(),
    'interview quality' => fn () => (new SurveyInterviewQualityService(Mockery::mock(SurveyInterviewQualityEndpointInterface::class)))
        ->setSurveyId('survey-1')->get(),
    'background activity' => fn () => (new BackgroundActivitiesService(Mockery::mock(BackgroundActivitiesEndpointInterface::class)))->get(),
]);
