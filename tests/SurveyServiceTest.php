<?php

declare(strict_types=1);

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyBlueprintsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyCreateModel;
use Nikoleesg\NfieldAdmin\Services\SurveyService;

afterEach(function () {
    Mockery::close();
});

it('creates a survey sending camelCase keys', function () {
    $collectionEndpoint = Mockery::mock(SurveyCollectionEndpointInterface::class);
    $endpoint = Mockery::mock(SurveyEndpointInterface::class);
    $blueprintsEndpoint = Mockery::mock(SurveyBlueprintsEndpointInterface::class);

    $service = new SurveyService($collectionEndpoint, $endpoint, $blueprintsEndpoint);

    $createModel = new SurveyCreateModel(
        surveyName: 'Test Survey',
        clientName: 'Test Client',
        surveyType: 'Capi'
    );

    $collectionEndpoint->shouldReceive('create')
        ->with(Mockery::on(function ($arg) {
            return is_array($arg)
                && array_key_exists('surveyName', $arg)
                && $arg['surveyName'] === 'Test Survey'
                && array_key_exists('clientName', $arg)
                && array_key_exists('surveyType', $arg)
                && ! array_key_exists('SurveyName', $arg);
        }))
        ->once()
        ->andReturn([
            'SurveyId' => 'test-id',
            'SurveyName' => 'Test Survey',
            'SurveyType' => 'Capi',
        ]);

    $result = $service->createSurvey($createModel);

    expect($result->surveyId)->toBe('test-id')
        ->and($result->surveyName)->toBe('Test Survey');
});
