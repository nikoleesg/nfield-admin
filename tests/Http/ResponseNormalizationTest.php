<?php

declare(strict_types=1);

use Carbon\Carbon;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Nikoleesg\NfieldAdmin\Data\Surveys\InterviewDetailsModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\ManagerInterviewDetailsModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyFieldwork\SurveyFieldworkCountsResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyModel;
use Nikoleesg\NfieldAdmin\Facades\NfieldManager;
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

it('hydrates the interview quality DTOs from real PascalCase payloads', function () {
    Http::fake([
        '*/v2/surveys/survey-1/interviewQuality/int-1' => Http::response([
            'Id' => 'int-1',
            'InterviewQuality' => 2,
            'InterviewerId' => 'usr-1',
            'SamplingPointId' => 'sp-1',
            'OfficeId' => 'off-1',
        ], 200),
        '*/v2/surveys/survey-1/interviewQuality' => function (Request $request) {
            if ($request->method() === 'PUT') {
                return Http::response([
                    'InterviewId' => 'int-1',
                    'SurveyId' => 'survey-1',
                    'InterviewQuality' => 1,
                    'ClientInterviewerId' => 'ext-1',
                    'Interviewer' => 'Bob',
                    'SamplingPointName' => 'North',
                    'SamplingPointId' => 'sp-1',
                    'OfficeId' => 'off-1',
                    'OfficeName' => 'HQ',
                    'InterviewDuration' => 300,
                    'AverageInterviewDuration' => 250,
                    'InterviewMedianQuestionDuration' => 5,
                    'InterviewStartTime' => '2023-01-01T10:00:00Z',
                    'InterviewEndTime' => '2023-01-01T10:05:00Z',
                    'ResponseCode' => 20,
                    'InterviewResult' => 1,
                    'IsScreenedOut' => false,
                ], 200);
            }

            return Http::response([
                [
                    'Id' => 'int-1',
                    'InterviewQuality' => 1,
                    'InterviewerId' => 'usr-1',
                    'SamplingPointId' => 'sp-1',
                    'OfficeId' => 'off-1',
                ],
            ], 200);
        },
    ]);

    $service = NfieldManager::surveys()->forSurvey('survey-1')->interviewQuality();

    $collection = $service->getInterviews();
    expect($collection->first())->toBeInstanceOf(InterviewDetailsModel::class)
        ->and($collection->first()->id)->toBe('int-1')
        ->and($collection->first()->interviewQuality->value)->toBe(1);

    $item = $service->getInterview('int-1');
    expect($item)->toBeInstanceOf(InterviewDetailsModel::class)
        ->and($item->id)->toBe('int-1')
        ->and($item->interviewQuality->value)->toBe(2);

    $updated = $service->updateQuality(['interviewId' => 'int-1', 'newState' => 1]);
    expect($updated)->toBeInstanceOf(ManagerInterviewDetailsModel::class)
        ->and($updated->interviewDuration)->toBe(300)
        ->and($updated->isScreenedOut)->toBeFalse()
        ->and($updated->interviewStartTime)->toBeInstanceOf(Carbon::class);
});
