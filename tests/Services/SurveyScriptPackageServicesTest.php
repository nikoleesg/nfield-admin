<?php

declare(strict_types=1);

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyPackageEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyScriptEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyVarFileEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyVersionsEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\Package\SurveyPackageV1Model;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyGetScriptModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveySetScriptModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyVarFileModel;
use Nikoleesg\NfieldAdmin\Enums\SurveyPackageTypeEnum;
use Nikoleesg\NfieldAdmin\Exceptions\MissingScopeException;
use Nikoleesg\NfieldAdmin\Resources\SurveyResource;
use Nikoleesg\NfieldAdmin\Services\SurveyPackageService;
use Nikoleesg\NfieldAdmin\Services\SurveyScriptService;
use Nikoleesg\NfieldAdmin\Services\SurveyVarFileService;
use Nikoleesg\NfieldAdmin\Services\SurveyVersionService;
use Nikoleesg\NfieldAdmin\Services\SurveyVersionsService;

afterEach(function () {
    Mockery::close();
});

it('reads the live package by default and the test package on request', function () {
    $endpoint = Mockery::mock(SurveyPackageEndpointInterface::class);

    $endpoint->shouldReceive('get')->with('survey-1', 1)->once()->andReturn(['surveyName' => 'Wave 1', 'eTag' => 42]);
    $endpoint->shouldReceive('get')->with('survey-1', 2)->once()->andReturn(['surveyName' => 'Wave 1', 'eTag' => 43]);

    $service = (new SurveyPackageService($endpoint))->setSurveyId('survey-1');

    expect($service->get())->toBeInstanceOf(SurveyPackageV1Model::class)
        ->and($service->get(SurveyPackageTypeEnum::Test)->eTag)->toBe(43);
});

it('reads and replaces the current script from a string, an array or a model', function () {
    $endpoint = Mockery::mock(SurveyScriptEndpointInterface::class);
    $expected = ['script' => '*QUESTION 1', 'fileName' => null, 'unfixedIsOk' => false];

    $endpoint->shouldReceive('get')->with('survey-1')->once()->andReturn(['script' => '*QUESTION 1', 'fileName' => 'q.odin']);
    $endpoint->shouldReceive('update')->with('survey-1', $expected)->times(3)->andReturn([
        'script' => '*QUESTION 1',
        'fileName' => 'q.odin',
        'warningMessages' => ['Unused variable'],
    ]);

    $service = (new SurveyScriptService($endpoint))->setSurveyId('survey-1');

    expect($service->get())->toBeInstanceOf(SurveyGetScriptModel::class)
        ->and($service->update('*QUESTION 1')->warningMessages)->toBe(['Unused variable'])
        ->and($service->update(['script' => '*QUESTION 1'])->fileName)->toBe('q.odin')
        ->and($service->update(new SurveySetScriptModel('*QUESTION 1'))->script)->toBe('*QUESTION 1');
});

it('reads the current var file', function () {
    $endpoint = Mockery::mock(SurveyVarFileEndpointInterface::class);
    $endpoint->shouldReceive('get')->with('survey-1')->once()->andReturn(['fileContent' => 'Q1 1-1', 'fileName' => 'survey.var']);

    $varFile = (new SurveyVarFileService($endpoint))->setSurveyId('survey-1')->get();

    expect($varFile)->toBeInstanceOf(SurveyVarFileModel::class)
        ->and($varFile->fileName)->toBe('survey.var');
});

it('reads the script and var file of one published version', function () {
    $script = Mockery::mock(SurveyScriptEndpointInterface::class);
    $varFile = Mockery::mock(SurveyVarFileEndpointInterface::class);

    $script->shouldReceive('getVersion')->with('survey-1', '0x8DC')->once()->andReturn(['script' => 'old']);
    $varFile->shouldReceive('getVersion')->with('survey-1', '0x8DC')->once()->andReturn(['fileContent' => 'old var']);

    app()->instance(SurveyScriptEndpointInterface::class, $script);
    app()->instance(SurveyVarFileEndpointInterface::class, $varFile);

    $version = (new SurveyVersionsService(Mockery::mock(SurveyVersionsEndpointInterface::class)))
        ->setSurveyId('survey-1')
        ->forVersion('0x8DC');

    expect($version)->toBeInstanceOf(SurveyVersionService::class)
        ->and($version->script()->script)->toBe('old')
        ->and($version->varFile()->fileContent)->toBe('old var');
});

it('reaches the script, var file and package services from the survey', function () {
    $survey = (new SurveyResource)->setSurveyId('survey-1');

    expect($survey->script())->toBeInstanceOf(SurveyScriptService::class)
        ->and($survey->varFile())->toBeInstanceOf(SurveyVarFileService::class)
        ->and($survey->package())->toBeInstanceOf(SurveyPackageService::class)
        ->and($survey->versions()->forVersion('v1')->getSurveyVersion())->toBe('v1')
        ->and($survey->versions()->forVersion('v1')->getSurveyId())->toBe('survey-1');
});

it('refuses a version call before the version is set', function () {
    $service = (new SurveyVersionService(
        Mockery::mock(SurveyScriptEndpointInterface::class),
        Mockery::mock(SurveyVarFileEndpointInterface::class),
    ))->setSurveyId('survey-1');

    expect(fn () => $service->script())->toThrow(MissingScopeException::class);
});
