<?php

declare(strict_types=1);

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyPerformanceEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\Monitoring\SurveyMetricsModel;
use Nikoleesg\NfieldAdmin\Exceptions\MissingScopeException;
use Nikoleesg\NfieldAdmin\Resources\SurveyResource;
use Nikoleesg\NfieldAdmin\Services\SurveyPerformanceService;

afterEach(function () {
    Mockery::close();
});

it('reads the live and test metrics of the survey it is scoped to', function () {
    $endpoint = Mockery::mock(SurveyPerformanceEndpointInterface::class);

    $endpoint->shouldReceive('live')->with('survey-1')->once()->andReturn([
        'id' => 'survey-1',
        'publishedCount' => 40,
        'totalCount' => 55,
        'counts' => [['metricName' => 'Interview State Size', 'all' => ['warn' => 1, 'block' => 0], 'published' => null]],
    ]);
    $endpoint->shouldReceive('test')->with('survey-1')->once()->andReturn([
        'id' => 'survey-1',
        'publishedCount' => 0,
        'totalCount' => 4,
        'counts' => null,
    ]);

    $service = (new SurveyPerformanceService($endpoint))->setSurveyId('survey-1');

    $live = $service->live();
    $test = $service->test();

    expect($live)->toBeInstanceOf(SurveyMetricsModel::class)
        ->and($live->counts[0]->metricName)->toBe('Interview State Size')
        ->and($live->counts[0]->published)->toBeNull()
        ->and($test->totalCount)->toBe(4)
        ->and($test->counts)->toBeNull();
});

it('is reached from the survey with its scope', function () {
    $service = (new SurveyResource)->setSurveyId('survey-1')->performance();

    expect($service)->toBeInstanceOf(SurveyPerformanceService::class)
        ->and($service->getSurveyId())->toBe('survey-1');
});

it('refuses a metrics call before the survey scope is set', function () {
    $service = new SurveyPerformanceService(Mockery::mock(SurveyPerformanceEndpointInterface::class));

    expect(fn () => $service->live())->toThrow(MissingScopeException::class);
});
