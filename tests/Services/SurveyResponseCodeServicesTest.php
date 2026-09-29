<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyResponseCodeCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyResponseCodeEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyResponseCodeModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyResponseCodeUpdateModel;
use Nikoleesg\NfieldAdmin\Exceptions\MissingScopeException;
use Nikoleesg\NfieldAdmin\Resources\SurveyResource;
use Nikoleesg\NfieldAdmin\Services\SurveyResponseCodeCollectionService;
use Nikoleesg\NfieldAdmin\Services\SurveyResponseCodeService;

afterEach(function () {
    Mockery::close();
});

function surveyResponseCodePayload(array $overrides = []): array
{
    return $overrides + [
        'responseCode' => 210,
        'description' => 'Callback',
        'isDefinite' => false,
        'isSelectable' => true,
        'allowAppointment' => true,
        'relocationUrl' => null,
    ];
}

it('lists, filters and creates the response codes of the survey it is scoped to', function () {
    $endpoint = Mockery::mock(SurveyResponseCodeCollectionEndpointInterface::class);

    $endpoint->shouldReceive('list')->with('survey-1')->once()->andReturn([surveyResponseCodePayload()]);
    $endpoint->shouldReceive('find')
        ->with('survey-1', ['$filter' => 'IsDefinite eq true'])
        ->once()
        ->andReturn([]);
    $endpoint->shouldReceive('create')
        ->withArgs(fn (string $surveyId, array $payload) => $surveyId === 'survey-1' && $payload['responseCode'] === 211)
        ->twice()
        ->andReturn(surveyResponseCodePayload(['responseCode' => 211]));

    $service = (new SurveyResponseCodeCollectionService($endpoint))->setSurveyId('survey-1');

    $all = $service->list();

    expect($all)->toBeInstanceOf(Collection::class)
        ->and($all->first())->toBeInstanceOf(SurveyResponseCodeModel::class)
        ->and($all->first()->allowAppointment)->toBeTrue()
        ->and($service->find(['$filter' => 'IsDefinite eq true']))->toHaveCount(0)
        ->and($service->create(['responseCode' => 211])->responseCode)->toBe(211)
        ->and($service->create(new SurveyResponseCodeModel(211, 'Callback'))->responseCode)->toBe(211);
});

it('reads, updates and deletes the response code it is scoped to', function () {
    $endpoint = Mockery::mock(SurveyResponseCodeEndpointInterface::class);

    $endpoint->shouldReceive('get')->with('survey-1', 210)->once()->andReturn(surveyResponseCodePayload());
    $endpoint->shouldReceive('update')
        ->with('survey-1', 210, ['description' => 'Call back later'])
        ->twice()
        ->andReturn(surveyResponseCodePayload(['description' => 'Call back later']));
    $endpoint->shouldReceive('delete')->with('survey-1', 210)->once();

    $service = (new SurveyResponseCodeService($endpoint))->setSurveyId('survey-1')->setResponseCode(210);

    expect($service->get()->description)->toBe('Callback')
        ->and($service->update(['description' => 'Call back later'])->description)->toBe('Call back later')
        ->and($service->update(new SurveyResponseCodeUpdateModel(description: 'Call back later'))->responseCode)->toBe(210);

    $service->delete();
});

it('is reached from the survey with both scopes handed on', function () {
    $code = (new SurveyResource)->setSurveyId('survey-1')->responseCodes()->forResponseCode(210);

    expect($code)->toBeInstanceOf(SurveyResponseCodeService::class)
        ->and($code->getSurveyId())->toBe('survey-1')
        ->and($code->getResponseCode())->toBe(210);
});

it('refuses a response code call before either scope is set', function () {
    $endpoint = Mockery::mock(SurveyResponseCodeEndpointInterface::class);

    expect(fn () => (new SurveyResponseCodeService($endpoint))->setSurveyId('survey-1')->get())
        ->toThrow(MissingScopeException::class)
        ->and(fn () => (new SurveyResponseCodeService($endpoint))->setResponseCode(210)->get())
        ->toThrow(MissingScopeException::class);
});
