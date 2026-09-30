<?php

declare(strict_types=1);

use Carbon\Carbon;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Nikoleesg\NfieldAdmin\Data\Domain\SurveyResources\SurveyResourceUsageModel;
use Nikoleesg\NfieldAdmin\Data\Requests\RequestConfigurationHeaderModel;
use Nikoleesg\NfieldAdmin\Data\Requests\RequestConfigurationModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\InterviewDetailsModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\ManagerInterviewDetailsModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\Monitoring\MetricCountsModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\Monitoring\SurveyMetricCountsModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\Monitoring\SurveyMetricsModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\Package\SurveyPackageV1Model;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyFieldwork\SurveyFieldworkCountsResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyGroups\SurveyGroupDirectoryAssignmentModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyModel;
use Nikoleesg\NfieldAdmin\Enums\DirectoryObjectTypeEnum;
use Nikoleesg\NfieldAdmin\Enums\RequestHttpMethodEnum;
use Nikoleesg\NfieldAdmin\Enums\SurveyChannelEnum;
use Nikoleesg\NfieldAdmin\Enums\SurveyStateEnum;
use Nikoleesg\NfieldAdmin\Facades\NfieldManager;
use Nikoleesg\NfieldAdmin\Services\Http\HttpClient;
use Nikoleesg\NfieldAdmin\Services\SurveyCollectionService;
use Nikoleesg\NfieldAdmin\Services\SurveyFieldworkService;

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

    $survey = app(SurveyCollectionService::class)->list()->first();

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

    $collection = $service->list();
    expect($collection->first())->toBeInstanceOf(InterviewDetailsModel::class)
        ->and($collection->first()->id)->toBe('int-1')
        ->and($collection->first()->interviewQuality->value)->toBe(1);

    $item = $service->forInterview('int-1')->get();
    expect($item)->toBeInstanceOf(InterviewDetailsModel::class)
        ->and($item->id)->toBe('int-1')
        ->and($item->interviewQuality->value)->toBe(2);

    $updated = $service->update(['interviewId' => 'int-1', 'newState' => 1]);
    expect($updated)->toBeInstanceOf(ManagerInterviewDetailsModel::class)
        ->and($updated->interviewDuration)->toBe(300)
        ->and($updated->isScreenedOut)->toBeFalse()
        ->and($updated->interviewStartTime)->toBeInstanceOf(Carbon::class);
});

it('normalises the nested metric counts of a performance response', function () {
    // #63: a list of objects, each holding two further objects.
    Http::fake([
        '*/v2/token' => Http::response(['AccessToken' => 'token'], 200),
        '*/performance/metrics/*' => Http::response([
            'Id' => 'survey-1',
            'PublishedCount' => 40,
            'TotalCount' => 55,
            'Counts' => [
                [
                    'MetricName' => 'Page Complexity',
                    'All' => ['Warn' => 3, 'Block' => 1],
                    'Published' => ['Warn' => 2, 'Block' => 0],
                ],
            ],
        ], 200),
    ]);

    $metrics = NfieldManager::surveys()->forSurvey('survey-1')->performance()->live();

    expect($metrics)->toBeInstanceOf(SurveyMetricsModel::class)
        ->and($metrics->publishedCount)->toBe(40)
        ->and($metrics->totalCount)->toBe(55)
        ->and($metrics->counts[0])->toBeInstanceOf(SurveyMetricCountsModel::class)
        ->and($metrics->counts[0]->metricName)->toBe('Page Complexity')
        ->and($metrics->counts[0]->all)->toBeInstanceOf(MetricCountsModel::class)
        ->and($metrics->counts[0]->all->warn)->toBe(3)
        ->and($metrics->counts[0]->published->block)->toBe(0);
});

it('hydrates survey resource usage from a real PascalCase payload', function () {
    // #61: integer enums and nullable dates; the spec's example dates are
    // not ISO 8601, so the cast must parse leniently.
    Http::fake([
        '*/v2/token' => Http::response(['AccessToken' => 'token'], 200),
        '*/v2/surveyResources*' => Http::response([[
            'SurveyId' => 'survey-1',
            'Name' => 'Wave 1',
            'Channel' => 2,
            'CreationDate' => '12/25/2024 12:00:00 AM',
            'ClientName' => 'Acme',
            'State' => 3,
            'Owner' => 'Ada',
            'LastDataDownloadDate' => null,
            'LastDataCollectionDate' => '2026-09-20T10:00:00Z',
            'WillBeStoppedOn' => null,
            'WillBeDeletedOn' => null,
            'Size' => 1024,
            'IsExcludedFromAutomaticCleanup' => true,
        ]], 200),
    ]);

    $usage = NfieldManager::surveyResourceUsage()->list()->first();

    expect($usage)->toBeInstanceOf(SurveyResourceUsageModel::class)
        ->and($usage->surveyId)->toBe('survey-1')
        ->and($usage->channel)->toBe(SurveyChannelEnum::Online)
        ->and($usage->state)->toBe(SurveyStateEnum::Paused)
        ->and($usage->creationDate->format('Y-m-d'))->toBe('2024-12-25')
        ->and($usage->isExcludedFromAutomaticCleanup)->toBeTrue();
});

it('hydrates survey group directory assignments from an OData envelope', function () {
    // #60: enum, UUIDs and a date inside the {"value": [...]} envelope.
    Http::fake([
        '*/v2/token' => Http::response(['AccessToken' => 'token'], 200),
        '*/v2/surveyGroups/7/directoryAssignments*' => Http::response(['value' => [[
            'SurveyGroupId' => 7,
            'TenantId' => '5f2c1f0e-0000-4000-8000-000000000001',
            'ObjectId' => '5f2c1f0e-0000-4000-8000-000000000002',
            'ObjectType' => 1,
            'DateAdded' => '2026-09-01T00:00:00Z',
        ]]], 200),
    ]);

    $assignment = NfieldManager::surveyGroups()->forSurveyGroup(7)->directoryAssignments()->first();

    expect($assignment)->toBeInstanceOf(SurveyGroupDirectoryAssignmentModel::class)
        ->and($assignment->surveyGroupId)->toBe(7)
        ->and($assignment->objectType)->toBe(DirectoryObjectTypeEnum::User)
        ->and($assignment->tenantId)->toBe('5f2c1f0e-0000-4000-8000-000000000001');
});

it('hydrates a request configuration with its headers from a real PascalCase payload', function () {
    // #57: an enum and a nested list of header objects.
    Http::fake([
        '*/v2/token' => Http::response(['AccessToken' => 'token'], 200),
        '*/v2/requests/12' => Http::response([
            'Id' => 12,
            'Name' => 'crm',
            'Description' => null,
            'Uri' => 'https://crm.example.com/hook',
            'PayloadTemplate' => '{"id":"{{RespondentKey}}"}',
            'RequestHttpMethod' => 2,
            'HelpUri' => null,
            'Headers' => [['Id' => 1, 'RequestId' => 12, 'IsObfuscated' => true, 'Name' => 'Authorization', 'Value' => '***']],
        ], 200),
    ]);

    $config = NfieldManager::requestConfigurations()->forRequestConfiguration(12)->get();

    expect($config)->toBeInstanceOf(RequestConfigurationModel::class)
        ->and($config->id)->toBe(12)
        ->and($config->requestHttpMethod)->toBe(RequestHttpMethodEnum::Post)
        ->and($config->headers[0])->toBeInstanceOf(RequestConfigurationHeaderModel::class)
        ->and($config->headers[0]->name)->toBe('Authorization')
        ->and($config->headers[0]->isObfuscated)->toBeTrue();
});

it('hydrates a published package from a deeply nested PascalCase payload', function () {
    // #64: lists of objects holding further lists, a single nested object,
    // and survey settings whose value may be null.
    Http::fake([
        '*/v2/token' => Http::response(['AccessToken' => 'token'], 200),
        '*/v2/surveys/survey-1/package*' => Http::response([
            'SurveyName' => 'Wave 1',
            'ETag' => 42,
            'ResponseCodes' => [['ResponseCode' => 210, 'Description' => 'Callback']],
            'Languages' => [['Id' => 1, 'Name' => 'English', 'Translations' => [['Name' => 'Next', 'Text' => 'Next']]]],
            'Relocations' => [['Reason' => '1', 'Url' => 'https://example.com/done']],
            'Settings' => [['Name' => 'HideQuotaPage', 'Value' => null]],
            'InstructionFile' => ['FileName' => 'brief.pdf', 'Md5' => 'abc', 'Size' => 2048],
            'MediaFiles' => [['FileName' => 'logo.png', 'Md5' => 'def', 'Size' => 512]],
            'QuestionnaireMd5' => 'ghi',
        ], 200),
    ]);

    $package = NfieldManager::surveys()->forSurvey('survey-1')->package()->get();

    expect($package)->toBeInstanceOf(SurveyPackageV1Model::class)
        ->and($package->eTag)->toBe(42)
        ->and($package->responseCodes[0]->responseCode)->toBe(210)
        ->and($package->languages[0]->translations[0]->text)->toBe('Next')
        ->and($package->relocations[0]->url)->toBe('https://example.com/done')
        ->and($package->settings[0]->value)->toBeNull()
        ->and($package->instructionFile->size)->toBe(2048)
        ->and($package->mediaFiles[0]->fileName)->toBe('logo.png');
});

it('hydrates the waves of a parent survey from an OData envelope', function () {
    // #67: waves are surveys; the list is OData.
    Http::fake([
        '*/v2/token' => Http::response(['AccessToken' => 'token'], 200),
        '*/v2/parentSurveys/parent-1/waves*' => Http::response(['value' => [[
            'SurveyId' => 'wave-1',
            'SurveyName' => 'Wave 1',
            'ClientName' => 'Acme',
            'SurveyType' => 'OnlineBasic',
            'SurveyState' => 1,
        ]]], 200),
    ]);

    $wave = NfieldManager::parentSurveys()->forParentSurvey('parent-1')->waves()->list()->first();

    expect($wave)->toBeInstanceOf(SurveyModel::class)
        ->and($wave->surveyId)->toBe('wave-1')
        ->and($wave->surveyName)->toBe('Wave 1');
});
