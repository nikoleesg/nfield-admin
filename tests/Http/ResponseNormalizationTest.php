<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyFieldwork\SurveyFieldworkCountsResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyModel;
use Nikoleesg\NfieldAdmin\Services\Http\HttpClient;
use Nikoleesg\NfieldAdmin\Services\SurveyFieldworkService;
use Nikoleesg\NfieldAdmin\Services\SurveyService;

/**
 * The API returns PascalCase; the package speaks camelCase everywhere.
 * These fixtures are real PascalCase payloads, so they fail if the single
 * normalization point at the HTTP boundary ever stops working.
 */
beforeEach(function () {
    Http::preventStrayRequests();

    Http::fake([
        '*/v2/token' => Http::response([
            'AccessToken' => 'fake-token',
            'ExpiresIn' => 3600,
            'TokenType' => 'Bearer',
            'RefreshToken' => 'fake-refresh',
        ], 200),
    ]);
});

it('normalizes PascalCase response keys to camelCase', function () {
    Http::fake([
        '*/v2/surveys/survey-1/counts' => Http::response([
            'SurveyId' => 'survey-1',
            'SuccessfulCount' => 12,
            'ScreenedOutCount' => 3,
        ], 200),
    ]);

    $json = app(HttpClient::class)->get('/v2/surveys/survey-1/counts')->json();

    expect($json)->toBe([
        'surveyId' => 'survey-1',
        'successfulCount' => 12,
        'screenedOutCount' => 3,
    ]);
});

it('normalizes keys recursively, through lists and nested objects', function () {
    Http::fake([
        '*/v2/surveys/survey-1/quotaTargets' => Http::response([
            'ETag' => 'etag-1',
            'Variables' => [
                [
                    'DefinitionId' => 'def-1',
                    'Levels' => [
                        ['Id' => 'lvl-1', 'Target' => 10, 'IsHidden' => false],
                    ],
                ],
            ],
        ], 200),
    ]);

    $json = app(HttpClient::class)->get('/v2/surveys/survey-1/quotaTargets')->json();

    expect($json)->toBe([
        'eTag' => 'etag-1',
        'variables' => [
            [
                'definitionId' => 'def-1',
                'levels' => [
                    ['id' => 'lvl-1', 'target' => 10, 'isHidden' => false],
                ],
            ],
        ],
    ]);
});

it('leaves values, list indexes and the raw body untouched', function () {
    Http::fake([
        '*/v2/surveys/survey-1/customColumns' => Http::response(['MyColumn', 'AnotherColumn'], 200),
    ]);

    $response = app(HttpClient::class)->get('/v2/surveys/survey-1/customColumns');

    expect($response->json())->toBe(['MyColumn', 'AnotherColumn'])
        ->and($response->body())->toBe('["MyColumn","AnotherColumn"]');
});

it('leaves dictionary-shaped responses on exempted paths alone', function () {
    Http::fake([
        '*/v2/roles' => Http::response([
            'Fieldwork Manager' => [['Name' => 'Surveys', 'Level' => 'Write']],
        ], 200),
    ]);

    $json = app(HttpClient::class)->get('/v2/roles')->json();

    expect($json)->toBe([
        'Fieldwork Manager' => [['Name' => 'Surveys', 'Level' => 'Write']],
    ]);
});

it('hydrates a mapper-free DTO straight from a real PascalCase payload', function () {
    Http::fake([
        '*/v2/surveys' => Http::response([
            [
                'SurveyId' => 'survey-1',
                'SurveyName' => 'Omnibus',
                'ClientName' => 'ACME',
                'SurveyType' => 'Capi',
                'Description' => null,
                'QuestionnaireMD5' => 'abc123',
                'InterviewerInstruction' => null,
                'SurveyState' => 0,
                'SurveyGroupId' => 7,
                'IsBlueprint' => false,
                'EnableRespondentsGateway' => true,
                'LastStartDate' => null,
            ],
        ], 200),
    ]);

    $survey = app(SurveyService::class)->list()->first();

    expect($survey)->toBeInstanceOf(SurveyModel::class)
        ->and($survey->surveyId)->toBe('survey-1')
        ->and($survey->surveyName)->toBe('Omnibus')
        ->and($survey->questionnaireMD5)->toBe('abc123')
        ->and($survey->surveyGroupId)->toBe(7);
});

it('reads the access token from a real PascalCase token response', function () {
    Http::fake([
        '*/v2/test' => Http::response(['Success' => true], 200),
    ]);

    app(HttpClient::class)->get('/v2/test');

    Http::assertSent(fn (Request $request) => ! str_ends_with($request->url(), '/v2/test')
        || $request->header('Authorization')[0] === 'Bearer fake-token');
});

it('hydrates the fieldwork counts DTO, nested list included', function () {
    Http::fake([
        '*/v2/surveys/survey-1/fieldwork/counts' => Http::response([
            'SurveyId' => 'survey-1',
            'Successful' => 12,
            'SuccessfulLast24Hours' => 4,
            'ScreenedOut' => 3,
            'DroppedOut' => 1,
            'Rejected' => 0,
            'SuccessfulDeleted' => 0,
            'ScreenedOutDeleted' => 0,
            'DroppedOutDeleted' => 0,
            'RejectedDeleted' => 0,
            'ActiveInterviews' => 2,
            'ScreenedOutOverview' => [
                ['ResponseCode' => 21, 'Count' => 3],
            ],
        ], 200),
    ]);

    $counts = app(SurveyFieldworkService::class)->setSurveyId('survey-1')->counts();

    expect($counts)->toBeInstanceOf(SurveyFieldworkCountsResponseModel::class)
        ->and($counts->surveyId)->toBe('survey-1')
        ->and($counts->successfulLast24Hours)->toBe(4)
        ->and($counts->screenedOutOverview[0]->responseCode)->toBe(21);
});
