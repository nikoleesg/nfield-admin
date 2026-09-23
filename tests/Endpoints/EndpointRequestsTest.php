<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints as Contracts;

/**
 * #29: one case per endpoint method, asserting the verb, the URL and the body
 * the class actually puts on the wire.
 *
 * These go through the real `HttpClient` and `Http::fake()` rather than a
 * mocked client, so a path built by the wrong `EndpointPath` helper, a wrong
 * verb, or a body that never made it out of the method all fail here. This is
 * the only thing that protects the OpenAPI alignment — the spec's paths are
 * not otherwise executable.
 */
beforeEach(function () {
    Http::fake([
        '*/v2/token' => Http::response(['AccessToken' => 'endpoint-test-token'], 200),
        // The one operation whose response is a bare JSON scalar.
        '*/fieldwork/status' => Http::response('1', 200, ['Content-Type' => 'application/json']),
        '*' => Http::response(['ok' => true], 200),
    ]);
});

/**
 * Every endpoint method: [contract, method, arguments, verb, uri, body].
 *
 * Keyed by `<EndpointClassWithoutSuffix>::<method>` so the coverage test below
 * can tell which methods have no case.
 */
function endpointCallCases(): array
{
    return [
        // ── Event Subscriptions ──────────────────────────────────────────────
        'SubscriptionCollection::list' => [
            Contracts\SubscriptionCollectionEndpointInterface::class, 'list', [],
            'GET', 'v2/events/subscriptions', [],
        ],
        'SubscriptionCollection::create' => [
            Contracts\SubscriptionCollectionEndpointInterface::class, 'create', [['name' => 'sub1']],
            'POST', 'v2/events/subscriptions', ['name' => 'sub1'],
        ],
        'Subscription::get' => [
            Contracts\SubscriptionEndpointInterface::class, 'get', ['sub1'],
            'GET', 'v2/events/subscriptions/sub1', [],
        ],
        'Subscription::updatePartial' => [
            Contracts\SubscriptionEndpointInterface::class, 'updatePartial', ['sub1', ['name' => 'sub1']],
            'PATCH', 'v2/events/subscriptions/sub1', ['name' => 'sub1'],
        ],
        'Subscription::destroy' => [
            Contracts\SubscriptionEndpointInterface::class, 'destroy', ['sub1'],
            'DELETE', 'v2/events/subscriptions/sub1', [],
        ],
        // ── Access & Authentication ──────────────────────────────────────────
        'Roles::list' => [
            Contracts\RolesEndpointInterface::class, 'list', [],
            'GET', 'v2/roles', [],
        ],
        'UserRole::get' => [
            Contracts\UserRoleEndpointInterface::class, 'get', [],
            'GET', 'v2/me/role', [],
        ],

        // ── Background activities ────────────────────────────────────────────
        'BackgroundActivities::get' => [
            Contracts\BackgroundActivitiesEndpointInterface::class, 'get', ['activity-1'],
            'GET', 'v2/BackgroundActivities/activity-1', [],
        ],

        // ── CAPI interviewers ────────────────────────────────────────────────
        'CapiInterviewersCollection::list' => [
            Contracts\CapiInterviewersCollectionEndpointInterface::class, 'list', [],
            'GET', 'v2/capiInterviewers', [],
        ],
        'CapiInterviewersCollection::find' => [
            Contracts\CapiInterviewersCollectionEndpointInterface::class, 'find', [['officeId' => 'office-1']],
            'GET', 'v2/capiInterviewers?officeId=office-1', ['officeId' => 'office-1'],
        ],
        'CapiInterviewersCollection::create' => [
            Contracts\CapiInterviewersCollectionEndpointInterface::class, 'create', [['userName' => 'ivw-1']],
            'POST', 'v2/capiInterviewers', ['userName' => 'ivw-1'],
        ],
        'CapiInterviewersCollection::getByClientId' => [
            Contracts\CapiInterviewersCollectionEndpointInterface::class, 'getByClientId', ['client-1'],
            'GET', 'v2/capiInterviewers/getByClientId/client-1', [],
        ],
        'CapiInterviewers::get' => [
            Contracts\CapiInterviewersEndpointInterface::class, 'get', ['ivw-1'],
            'GET', 'v2/capiInterviewers/ivw-1', [],
        ],
        'CapiInterviewers::delete' => [
            Contracts\CapiInterviewersEndpointInterface::class, 'delete', ['ivw-1'],
            'DELETE', 'v2/capiInterviewers/ivw-1', [],
        ],
        'CapiInterviewers::update' => [
            Contracts\CapiInterviewersEndpointInterface::class, 'update', ['ivw-1', ['emailAddress' => 'a@b.c', 'firstName' => null]],
            'PATCH', 'v2/capiInterviewers/ivw-1', ['emailAddress' => 'a@b.c'],
        ],
        'CapiInterviewers::resetPassword' => [
            Contracts\CapiInterviewersEndpointInterface::class, 'resetPassword', ['ivw-1', ['password' => 'secret']],
            'PUT', 'v2/capiInterviewers/ivw-1', ['password' => 'secret'],
        ],
        'CapiInterviewersAssignments::list' => [
            Contracts\CapiInterviewersAssignmentsEndpointInterface::class, 'list', ['ivw-1'],
            'GET', 'v2/capiInterviewers/ivw-1/assignments', [],
        ],
        'CapiInterviewersOffices::list' => [
            Contracts\CapiInterviewersOfficesEndpointInterface::class, 'list', ['ivw-1'],
            'GET', 'v2/capiInterviewers/ivw-1/offices', [],
        ],
        'CapiInterviewersOffices::update' => [
            Contracts\CapiInterviewersOfficesEndpointInterface::class, 'update', ['ivw-1', 'office-1'],
            'PATCH', 'v2/capiInterviewers/ivw-1/offices/office-1', [],
        ],
        'CapiInterviewersOffices::delete' => [
            Contracts\CapiInterviewersOfficesEndpointInterface::class, 'delete', ['ivw-1', 'office-1'],
            'DELETE', 'v2/capiInterviewers/ivw-1/offices/office-1', [],
        ],

        // ── Surveys ──────────────────────────────────────────────────────────
        'SurveyCollection::list' => [
            Contracts\SurveyCollectionEndpointInterface::class, 'list', [],
            'GET', 'v2/surveys', [],
        ],
        'SurveyCollection::find' => [
            Contracts\SurveyCollectionEndpointInterface::class, 'find', [['surveyName' => 'demo']],
            'GET', 'v2/surveys?surveyName=demo', ['surveyName' => 'demo'],
        ],
        'SurveyCollection::create' => [
            Contracts\SurveyCollectionEndpointInterface::class, 'create', [['surveyName' => 'demo']],
            'POST', 'v2/surveys', ['surveyName' => 'demo'],
        ],
        'SurveyCollection::createFromBlueprint' => [
            Contracts\SurveyCollectionEndpointInterface::class, 'createFromBlueprint', [['blueprintId' => 'bp-1']],
            'POST', 'v2/surveys/createSurveyFromBlueprint', ['blueprintId' => 'bp-1'],
        ],
        'SurveyCollection::search' => [
            Contracts\SurveyCollectionEndpointInterface::class, 'search', ['respondent-1'],
            'GET', 'v2/surveys/search?value=respondent-1', ['value' => 'respondent-1'],
        ],
        'Survey::get' => [
            Contracts\SurveyEndpointInterface::class, 'get', ['survey-1'],
            'GET', 'v2/surveys/survey-1', [],
        ],
        'Survey::destroy' => [
            Contracts\SurveyEndpointInterface::class, 'destroy', ['survey-1'],
            'DELETE', 'v2/surveys/survey-1', [],
        ],
        'Survey::updatePartial' => [
            Contracts\SurveyEndpointInterface::class, 'updatePartial', ['survey-1', ['surveyName' => 'renamed']],
            'PATCH', 'v2/surveys/survey-1', ['surveyName' => 'renamed'],
        ],
        'Survey::counts' => [
            Contracts\SurveyEndpointInterface::class, 'counts', ['survey-1'],
            'GET', 'v2/surveys/survey-1/counts', [],
        ],
        'Survey::getCustomColumns' => [
            Contracts\SurveyEndpointInterface::class, 'getCustomColumns', ['survey-1'],
            'GET', 'v2/surveys/survey-1/customColumns', [],
        ],
        'Survey::batchActivateSamplingPoints' => [
            Contracts\SurveyEndpointInterface::class, 'batchActivateSamplingPoints', ['survey-1', ['samplingPointIds' => ['sp-1']]],
            'POST', 'v2/surveys/survey-1/activateSamplingpoints', ['samplingPointIds' => ['sp-1']],
        ],
        'SurveyBlueprints::update' => [
            Contracts\SurveyBlueprintsEndpointInterface::class, 'update', ['bp-1', ['surveyName' => 'demo']],
            'PUT', 'v2/surveyBlueprints/bp-1/update', ['surveyName' => 'demo'],
        ],

        // ── Fieldwork ────────────────────────────────────────────────────────
        'SurveyFieldwork::start' => [
            Contracts\SurveyFieldworkEndpointInterface::class, 'start', ['survey-1'],
            'PUT', 'v2/surveys/survey-1/fieldwork/start', [],
        ],
        'SurveyFieldwork::status' => [
            Contracts\SurveyFieldworkEndpointInterface::class, 'status', ['survey-1'],
            'GET', 'v2/surveys/survey-1/fieldwork/status', [],
        ],
        'SurveyFieldwork::counts' => [
            Contracts\SurveyFieldworkEndpointInterface::class, 'counts', ['survey-1'],
            'GET', 'v2/surveys/survey-1/fieldwork/counts', [],
        ],
        'SurveyFieldwork::stop' => [
            Contracts\SurveyFieldworkEndpointInterface::class, 'stop', ['survey-1', ['interviewingRestrictionType' => 0]],
            'PUT', 'v2/surveys/survey-1/fieldwork/stop', ['interviewingRestrictionType' => 0],
        ],

        // ── Settings ─────────────────────────────────────────────────────────
        'SurveySettings::listSettings' => [
            Contracts\SurveySettingsEndpointInterface::class, 'listSettings', ['survey-1'],
            'GET', 'v2/surveys/survey-1/settings', [],
        ],
        'SurveySettings::addOrUpdateSetting' => [
            Contracts\SurveySettingsEndpointInterface::class, 'addOrUpdateSetting', ['survey-1', ['name' => 'a', 'value' => 'b']],
            'POST', 'v2/surveys/survey-1/settings', ['name' => 'a', 'value' => 'b'],
        ],
        'SurveyGeneralSettings::getGeneralSettings' => [
            Contracts\SurveyGeneralSettingsEndpointInterface::class, 'getGeneralSettings', ['survey-1'],
            'GET', 'v2/surveys/survey-1/generalSettings', [],
        ],
        'SurveyGeneralSettings::updateGeneralSettings' => [
            Contracts\SurveyGeneralSettingsEndpointInterface::class, 'updateGeneralSettings', ['survey-1', ['description' => 'x']],
            'PATCH', 'v2/surveys/survey-1/generalSettings', ['description' => 'x'],
        ],
        'SurveySamplingMethod::get' => [
            Contracts\SurveySamplingMethodEndpointInterface::class, 'get', ['survey-1'],
            'GET', 'v2/surveys/survey-1/samplingMethod', [],
        ],
        'SurveySamplingMethod::update' => [
            Contracts\SurveySamplingMethodEndpointInterface::class, 'update', ['survey-1', ['samplingMethod' => 1]],
            'PATCH', 'v2/surveys/survey-1/samplingMethod', ['samplingMethod' => 1],
        ],
        'SurveyPublicIds::list' => [
            Contracts\SurveyPublicIdsEndpointInterface::class, 'list', ['survey-1'],
            'GET', 'v2/surveys/survey-1/publicIds', [],
        ],
        'SurveyPublicIds::update' => [
            Contracts\SurveyPublicIdsEndpointInterface::class, 'update', ['survey-1', [['publicId' => 'p-1']]],
            'PUT', 'v2/surveys/survey-1/publicIds', [['publicId' => 'p-1']],
        ],

        // ── Publish ──────────────────────────────────────────────────────────
        'SurveyPublish::getPublishState' => [
            Contracts\SurveyPublishEndpointInterface::class, 'getPublishState', ['survey-1'],
            'GET', 'v2/surveys/survey-1/publish', [],
        ],
        'SurveyPublish::publish' => [
            Contracts\SurveyPublishEndpointInterface::class, 'publish', ['survey-1', ['packageType' => 1]],
            'PUT', 'v2/surveys/survey-1/publish', ['packageType' => 1],
        ],
        'SurveyPublish::startPublish' => [
            Contracts\SurveyPublishEndpointInterface::class, 'startPublish', ['survey-1', ['packageType' => 1]],
            'POST', 'v2/surveys/survey-1/publish/start', ['packageType' => 1],
        ],

        // ── Quota ────────────────────────────────────────────────────────────
        'SurveyQuotaFrame::getQuotaFrame' => [
            Contracts\SurveyQuotaFrameEndpointInterface::class, 'getQuotaFrame', ['survey-1'],
            'GET', 'v2/surveys/survey-1/surveyQuotaFrame', [],
        ],
        'SurveyQuotaFrame::setQuotaFrame' => [
            Contracts\SurveyQuotaFrameEndpointInterface::class, 'setQuotaFrame', ['survey-1', ['levels' => []]],
            'PUT', 'v2/surveys/survey-1/surveyQuotaFrame', ['levels' => []],
        ],
        'SurveyQuotaFrame::setQuotaLevelsTargets' => [
            Contracts\SurveyQuotaFrameEndpointInterface::class, 'setQuotaLevelsTargets', ['survey-1', '42', ['levels' => []]],
            'PUT', 'v2/surveys/survey-1/surveyQuotaFrame/42', ['levels' => []],
        ],
        'SurveyQuotaTargets::getQuotaTargets' => [
            Contracts\SurveyQuotaTargetsEndpointInterface::class, 'getQuotaTargets', ['survey-1'],
            'GET', 'v2/surveys/survey-1/quotaTargets', [],
        ],
        'SurveyQuotaTargets::getQuotaTargetsByETag' => [
            Contracts\SurveyQuotaTargetsEndpointInterface::class, 'getQuotaTargetsByETag', ['survey-1', 42],
            'GET', 'v2/surveys/survey-1/quotaTargets/42', [],
        ],
        'SurveyQuotaVersions::getQuotaVersions' => [
            Contracts\SurveyQuotaVersionsEndpointInterface::class, 'getQuotaVersions', ['survey-1'],
            'GET', 'v2/surveys/survey-1/quotaVersions', [],
        ],
        'SurveyQuotaVersions::getQuotaVersionsByETag' => [
            Contracts\SurveyQuotaVersionsEndpointInterface::class, 'getQuotaVersionsByETag', ['survey-1', 42],
            'GET', 'v2/surveys/survey-1/quotaVersions/42', [],
        ],

        // ── Data delivery ────────────────────────────────────────────────────
        'SurveyData::downloadData' => [
            Contracts\SurveyDataEndpointInterface::class, 'downloadData', ['survey-1', ['fileName' => 'f.zip']],
            'POST', 'v2/surveys/survey-1/dataDownload', ['fileName' => 'f.zip'],
        ],
        'SurveyData::downloadInterviewData' => [
            Contracts\SurveyDataEndpointInterface::class, 'downloadInterviewData', ['survey-1', 'interview-1', ['fileName' => 'f.zip']],
            'POST', 'v2/surveys/survey-1/dataDownload/interview-1', ['fileName' => 'f.zip'],
        ],
        'SurveyInterview::deleteInterviewData' => [
            Contracts\SurveyInterviewEndpointInterface::class, 'deleteInterviewData', ['survey-1', 'interview-1'],
            'DELETE', 'v2/surveys/survey-1/interviews/interview-1', [],
        ],

        // ── Sample ───────────────────────────────────────────────────────────
        'SurveySampleCollection::download' => [
            Contracts\SurveySampleCollectionEndpointInterface::class, 'download', ['survey-1'],
            'GET', 'v2/surveys/survey-1/sample', [],
        ],
        'SurveySampleCollection::destroy' => [
            Contracts\SurveySampleCollectionEndpointInterface::class, 'destroy', ['survey-1', [['name' => 'c', 'op' => 'eq', 'value' => '1']]],
            'DELETE', 'v2/surveys/survey-1/sample', [['name' => 'c', 'op' => 'eq', 'value' => '1']],
        ],
        'SurveySampleCollection::block' => [
            Contracts\SurveySampleCollectionEndpointInterface::class, 'block', ['survey-1', [['name' => 'c']]],
            'PUT', 'v2/surveys/survey-1/sample/block', [['name' => 'c']],
        ],
        'SurveySampleCollection::create' => [
            Contracts\SurveySampleCollectionEndpointInterface::class, 'create', ['survey-1', [['name' => 'col']]],
            'POST', 'v2/surveys/survey-1/sample/create', [['name' => 'col']],
        ],
        'SurveySampleCollection::reset' => [
            Contracts\SurveySampleCollectionEndpointInterface::class, 'reset', ['survey-1', [['name' => 'c']]],
            'PUT', 'v2/surveys/survey-1/sample/reset', [['name' => 'c']],
        ],
        'SurveySampleCollection::clear' => [
            Contracts\SurveySampleCollectionEndpointInterface::class, 'clear', ['survey-1', ['columns' => ['c']]],
            'PUT', 'v2/surveys/survey-1/sample/clear', ['columns' => ['c']],
        ],
        'SurveySampleCollection::update' => [
            Contracts\SurveySampleCollectionEndpointInterface::class, 'update', ['survey-1', ['interviewId' => 1]],
            'PUT', 'v2/surveys/survey-1/sample/update', ['interviewId' => 1],
        ],
        'SurveySample::get' => [
            Contracts\SurveySampleEndpointInterface::class, 'get', ['survey-1', 7],
            'GET', 'v2/surveys/survey-1/sample/7', [],
        ],
        'SurveySampleDataDownload::requestDownload' => [
            Contracts\SurveySampleDataDownloadEndpointInterface::class, 'requestDownload', ['survey-1', 'sample.csv'],
            'POST', 'v2/surveys/survey-1/sampleDataDownload/sample.csv', [],
        ],

        // ── Sampling points ──────────────────────────────────────────────────
        'SamplingPointCollection::list' => [
            Contracts\SamplingPointCollectionEndpointInterface::class, 'list', ['survey-1'],
            'GET', 'v2/surveys/survey-1/samplingPoints', [],
        ],
        'SamplingPointCollection::find' => [
            Contracts\SamplingPointCollectionEndpointInterface::class, 'find', ['survey-1', ['kind' => 1]],
            'GET', 'v2/surveys/survey-1/samplingPoints?kind=1', ['kind' => 1],
        ],
        'SamplingPointCollection::create' => [
            Contracts\SamplingPointCollectionEndpointInterface::class, 'create', ['survey-1', ['name' => 'sp']],
            'POST', 'v2/surveys/survey-1/samplingPoints', ['name' => 'sp'],
        ],
        'SamplingPoint::get' => [
            Contracts\SamplingPointEndpointInterface::class, 'get', ['survey-1', 'sp-1'],
            'GET', 'v2/surveys/survey-1/samplingPoints/sp-1', [],
        ],
        'SamplingPoint::delete' => [
            Contracts\SamplingPointEndpointInterface::class, 'delete', ['survey-1', 'sp-1'],
            'DELETE', 'v2/surveys/survey-1/samplingPoints/sp-1', [],
        ],
        'SamplingPoint::update' => [
            Contracts\SamplingPointEndpointInterface::class, 'update', ['survey-1', 'sp-1', ['name' => 'sp']],
            'PATCH', 'v2/surveys/survey-1/samplingPoints/sp-1', ['name' => 'sp'],
        ],
        'SamplingPoint::activate' => [
            Contracts\SamplingPointEndpointInterface::class, 'activate', ['survey-1', 'sp-1'],
            'PATCH', 'v2/surveys/survey-1/samplingPoints/sp-1/activate', [],
        ],
        'SamplingPoint::replace' => [
            Contracts\SamplingPointEndpointInterface::class, 'replace', ['survey-1', 'sp-1', ['samplingPointId' => 'sp-2']],
            'PATCH', 'v2/surveys/survey-1/samplingPoints/sp-1/replace', ['samplingPointId' => 'sp-2'],
        ],
        'SamplingPointAssignment::list' => [
            Contracts\SamplingPointAssignmentEndpointInterface::class, 'list', ['survey-1', 'sp-1'],
            'GET', 'v2/surveys/survey-1/samplingPoints/sp-1/assignments', [],
        ],
        'SamplingPointAssignment::assign' => [
            Contracts\SamplingPointAssignmentEndpointInterface::class, 'assign', ['survey-1', 'sp-1', 'ivw-1'],
            'POST', 'v2/surveys/survey-1/samplingPoints/sp-1/assignments/ivw-1', [],
        ],
        'SamplingPointAssignment::unassign' => [
            Contracts\SamplingPointAssignmentEndpointInterface::class, 'unassign', ['survey-1', 'sp-1', 'ivw-1'],
            'DELETE', 'v2/surveys/survey-1/samplingPoints/sp-1/assignments/ivw-1', [],
        ],
        'SamplingPointQuotaTargets::list' => [
            Contracts\SamplingPointQuotaTargetsEndpointInterface::class, 'list', ['survey-1', 'sp-1'],
            'GET', 'v2/surveys/survey-1/samplingPoints/sp-1/quotaTargets', [],
        ],
        'SamplingPointQuotaTargets::get' => [
            Contracts\SamplingPointQuotaTargetsEndpointInterface::class, 'get', ['survey-1', 'sp-1', 'level-1'],
            'GET', 'v2/surveys/survey-1/samplingPoints/sp-1/quotaTargets/level-1', [],
        ],
        'SamplingPointQuotaTargets::update' => [
            Contracts\SamplingPointQuotaTargetsEndpointInterface::class, 'update', ['survey-1', 'sp-1', 'level-1', ['target' => 10]],
            'PATCH', 'v2/surveys/survey-1/samplingPoints/sp-1/quotaTargets/level-1', ['target' => 10],
        ],
        'SamplingPointAddressCollection::list' => [
            Contracts\SamplingPointAddressCollectionEndpointInterface::class, 'list', ['survey-1', 'sp-1'],
            'GET', 'v2/surveys/survey-1/samplingPoints/sp-1/addresses', [],
        ],
        'SamplingPointAddressCollection::find' => [
            Contracts\SamplingPointAddressCollectionEndpointInterface::class, 'find', ['survey-1', 'sp-1', ['status' => 1]],
            'GET', 'v2/surveys/survey-1/samplingPoints/sp-1/addresses?status=1', ['status' => 1],
        ],
        'SamplingPointAddressCollection::create' => [
            Contracts\SamplingPointAddressCollectionEndpointInterface::class, 'create', ['survey-1', 'sp-1', ['addressDetail' => 'x']],
            'POST', 'v2/surveys/survey-1/samplingPoints/sp-1/addresses', ['addressDetail' => 'x'],
        ],
        'SamplingPointAddress::get' => [
            Contracts\SamplingPointAddressEndpointInterface::class, 'get', ['survey-1', 'sp-1', 'addr-1'],
            'GET', 'v2/surveys/survey-1/samplingPoints/sp-1/addresses/addr-1', [],
        ],
        'SamplingPointAddress::delete' => [
            Contracts\SamplingPointAddressEndpointInterface::class, 'delete', ['survey-1', 'sp-1', 'addr-1'],
            'DELETE', 'v2/surveys/survey-1/samplingPoints/sp-1/addresses/addr-1', [],
        ],
        'SurveySamplingPointsAssignments::massAssign' => [
            Contracts\SurveySamplingPointsAssignmentsEndpointInterface::class, 'massAssign', ['survey-1', ['samplingPointIds' => ['sp-1']]],
            'POST', 'v2/surveys/survey-1/samplingPointsAssignments', ['samplingPointIds' => ['sp-1']],
        ],
        'SurveySamplingPointsAssignments::massUnassign' => [
            Contracts\SurveySamplingPointsAssignmentsEndpointInterface::class, 'massUnassign', ['survey-1', ['samplingPointIds' => ['sp-1']]],
            'DELETE', 'v2/surveys/survey-1/samplingPointsAssignments', ['samplingPointIds' => ['sp-1']],
        ],
    ];
}

it('puts the documented request on the wire', function (
    string $contract,
    string $method,
    array $arguments,
    string $expectedVerb,
    string $expectedUri,
    array $expectedBody
) {
    app($contract)->{$method}(...$arguments);

    $request = sentRequest();

    expect($request->method())->toBe($expectedVerb)
        ->and(sentUri($request))->toBe($expectedUri)
        ->and($request->data())->toBe($expectedBody);
})->with(endpointCallCases());

it('covers every public endpoint method', function () {
    // The dataset above is hand-written, so it can fall behind the code. This
    // fails when an endpoint method has no case named after it.
    $covered = array_merge(
        array_keys(endpointCallCases()),
        // Covered by a dedicated test below: its body is a multipart file part,
        // not a JSON array the dataset can compare.
        ['SurveySampleCollection::upload'],
    );

    $missing = [];

    foreach (glob(__DIR__.'/../../src/Endpoints/v2/*.php') as $file) {
        $name = basename($file, '.php');

        if ($name === 'BaseEndpoint') {
            continue;
        }

        $reflection = new ReflectionClass('Nikoleesg\NfieldAdmin\Endpoints\v2\\'.$name);
        $short = preg_replace('/Endpoint$/', '', $name);

        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->isConstructor() || $method->getDeclaringClass()->getName() !== $reflection->getName()) {
                continue;
            }

            $case = $short.'::'.$method->getName();

            if (! in_array($case, $covered, true)) {
                $missing[] = $case;
            }
        }
    }

    expect($missing)->toBe([]);
});

it('uploads sample data as a multipart file', function () {
    // The one call that does not send JSON: the sample upload attaches the
    // CSV as a file part, so its body cannot be compared as an array.
    app(Contracts\SurveySampleCollectionEndpointInterface::class)
        ->upload('survey-1', "a\tb\n1\t2", 'sample.csv');

    $request = sentRequest();

    expect($request->method())->toBe('POST')
        ->and(sentUri($request))->toBe('v2/surveys/survey-1/sample')
        ->and($request->isMultipart())->toBeTrue()
        ->and($request->body())->toContain('filename="sample.csv"')
        ->and($request->body())->toContain("a\tb\n1\t2")
        ->and($request->body())->toContain('name="File"');
});
