<?php

declare(strict_types=1);

use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\EditCapiInterviewerRequestModel;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\NewCapiInterviewerRequestModel;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\ResetCapiInterviewerPasswordRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\ClearSurveySampleModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\SampleFilterModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\SurveyCreateSampleColumnModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\SurveyUpdateSampleRecordModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingMethodModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\ActivateSpareSamplingPointRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\ActivateSpareSamplingPointsRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\Addresses\AddressModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\ReplaceSamplingPointWithSpareRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointCreateRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointInterviewerAssignmentsModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointQuotaLevelTargetModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointUpdateRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyCreateModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyDataInterviewRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyDataRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyFieldwork\SurveysFieldworkStopRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyFromBlueprintModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyGeneralSettingsUpdateModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyPublicIdModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota\SurveyQuotaFrameEtagRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota\SurveyQuotaFrameRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveySettingModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyUpdateModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\UpdateBlueprintModel;

/**
 * #29/#36: every model a service sends, round-tripped.
 *
 * Since #36 there is exactly one convention on both sides of a DTO: camelCase
 * in, camelCase out, with no mapper in between. These cases pin it — a model
 * that started mapping again, or that dropped a property the API expects,
 * fails here rather than at the first real request.
 */

/**
 * Every request model, with a payload that exercises each of its properties.
 *
 * @return array<string, array{0: class-string, 1: array<string, mixed>}>
 */
function requestModelCases(): array
{
    return [
        // ── Surveys ──────────────────────────────────────────────────────
        SurveyCreateModel::class => [SurveyCreateModel::class, [
            'surveyName' => 'Demo',
            'clientName' => 'Acme',
            'surveyType' => 'Capi',
            'description' => 'A demo',
            'interviewerInstruction' => 'Be nice',
            'surveyGroupId' => 1,
            'isBlueprint' => false,
            'enableRespondentsGateway' => true,
        ]],
        SurveyUpdateModel::class => [SurveyUpdateModel::class, [
            'surveyName' => 'Renamed',
            'clientName' => 'Acme',
            'description' => 'Updated',
            'interviewerInstruction' => 'Be nicer',
        ]],
        SurveyFromBlueprintModel::class => [SurveyFromBlueprintModel::class, [
            'surveyName' => 'Wave 2',
            'blueprintSurveyId' => 'bp-1',
            'enableRespondentsGateway' => true,
        ]],
        UpdateBlueprintModel::class => [UpdateBlueprintModel::class, [
            'surveyId' => 'survey-1',
            'includedConfiguration' => 0,
        ]],
        SurveyGeneralSettingsUpdateModel::class => [SurveyGeneralSettingsUpdateModel::class, [
            'name' => 'Demo',
            'client' => 'Acme',
            'description' => 'A demo',
            'excludeFromAutomaticCleanup' => true,
        ]],
        SurveySettingModel::class => [SurveySettingModel::class, [
            'name' => 'HideQuotaPage',
            'value' => 'true',
        ]],
        SurveyPublicIdModel::class => [SurveyPublicIdModel::class, [
            'id' => 'p-1',
            'linkType' => 'LiveId',
            'url' => 'https://example.test/1',
            'active' => true,
        ]],
        SamplingMethodModel::class => [SamplingMethodModel::class, [
            'samplingMethod' => 'Random',
        ]],
        SurveysFieldworkStopRequestModel::class => [SurveysFieldworkStopRequestModel::class, [
            'interviewingRestrictionType' => 1,
        ]],

        // ── Data delivery ────────────────────────────────────────────────
        SurveyDataInterviewRequestModel::class => [SurveyDataInterviewRequestModel::class, [
            'fileName' => 'one.zip',
        ]],
        SurveyDataRequestModel::class => [SurveyDataRequestModel::class, [
            'fileName' => 'export.zip',
            'surveyVersion' => 'v3',
        ]],

        // ── Sampling points ──────────────────────────────────────────────
        SamplingPointCreateRequestModel::class => [SamplingPointCreateRequestModel::class, [
            'name' => 'SP 1',
            'description' => 'First',
            'fieldworkOfficeId' => 'office-1',
            'groupId' => 'group-1',
            'stratum' => 'north',
            'customDataItems' => [['name' => 'region', 'value' => 'north']],
            'kind' => 0,
            'samplingPointId' => 'sp-1',
        ]],
        SamplingPointUpdateRequestModel::class => [SamplingPointUpdateRequestModel::class, [
            'name' => 'SP 1',
            'description' => 'First',
            'fieldworkOfficeId' => 'office-1',
            'groupId' => 'group-1',
            'stratum' => 'north',
            'customDataItems' => [['name' => 'region', 'value' => 'north']],
            'kind' => 1,
        ]],
        ActivateSpareSamplingPointRequestModel::class => [ActivateSpareSamplingPointRequestModel::class, [
            'target' => 5,
        ]],
        ActivateSpareSamplingPointsRequestModel::class => [ActivateSpareSamplingPointsRequestModel::class, [
            'samplingPointIds' => ['sp-1', 'sp-2'],
        ]],
        ReplaceSamplingPointWithSpareRequestModel::class => [ReplaceSamplingPointWithSpareRequestModel::class, [
            'spareSamplingPointId' => 'sp-9',
            'target' => 5,
        ]],
        SamplingPointQuotaLevelTargetModel::class => [SamplingPointQuotaLevelTargetModel::class, [
            'surveyId' => 'survey-1',
            'samplingPointId' => 'sp-1',
            'samplingPoint' => null,
            'levelId' => 'level-1',
            'target' => 10,
            'maxTarget' => 20,
        ]],
        SamplingPointInterviewerAssignmentsModel::class => [SamplingPointInterviewerAssignmentsModel::class, [
            'samplingPointIds' => ['sp-1'],
            'interviewerIds' => ['ivw-1'],
        ]],
        AddressModel::class => [AddressModel::class, [
            'addressId' => 'addr-1',
            'details' => '1 Example Street',
            'appointmentDate' => '2026-09-23T10:00:00Z',
            'sampleData' => [['name' => 'phone', 'value' => '555']],
        ]],

        // ── Sample ───────────────────────────────────────────────────────
        SampleFilterModel::class => [SampleFilterModel::class, [
            'name' => 'Status',
            'op' => 'eq',
            'value' => 'Open',
        ]],
        ClearSurveySampleModel::class => [ClearSurveySampleModel::class, [
            'filters' => [['name' => 'Status', 'op' => 'eq', 'value' => 'Open']],
            'columns' => ['Phone'],
        ]],
        SurveyCreateSampleColumnModel::class => [SurveyCreateSampleColumnModel::class, [
            'columnName' => 'Phone',
            'value' => '555',
        ]],
        SurveyUpdateSampleRecordModel::class => [SurveyUpdateSampleRecordModel::class, [
            'sampleRecordId' => 7,
            'columnUpdates' => [['columnName' => 'Phone', 'value' => '555']],
        ]],

        // ── Quota ────────────────────────────────────────────────────────
        SurveyQuotaFrameRequestModel::class => [SurveyQuotaFrameRequestModel::class, [
            'target' => 100,
            'variableDefinitions' => [],
            'frameVariables' => [],
        ]],
        SurveyQuotaFrameEtagRequestModel::class => [SurveyQuotaFrameEtagRequestModel::class, [
            'levels' => [['id' => 'level-1', 'target' => 10, 'maxTarget' => 20, 'maxOvershoot' => 0]],
        ]],

        // ── CAPI interviewers ────────────────────────────────────────────
        NewCapiInterviewerRequestModel::class => [NewCapiInterviewerRequestModel::class, [
            'firstName' => 'Ada',
            'lastName' => 'Lovelace',
            'emailAddress' => 'ada@example.test',
            'telephoneNumber' => '555',
            'isSupervisor' => true,
            'userName' => 'ada',
            'password' => 'hunter2',
            'clientInterviewerId' => 'client-1',
        ]],
        EditCapiInterviewerRequestModel::class => [EditCapiInterviewerRequestModel::class, [
            'firstName' => 'Ada',
            'lastName' => 'Lovelace',
            'emailAddress' => 'ada@example.test',
            'telephoneNumber' => '555',
            'isSupervisor' => false,
        ]],
        ResetCapiInterviewerPasswordRequestModel::class => [ResetCapiInterviewerPasswordRequestModel::class, [
            'password' => 'hunter2',
        ]],
    ];
}

it('serialises exactly the properties it declares, in camelCase', function (string $class, array $payload) {
    $serialised = $class::from($payload)->toArray();

    $declared = array_map(
        fn (ReflectionParameter $parameter): string => $parameter->getName(),
        (new ReflectionClass($class))->getConstructor()->getParameters()
    );

    expect(array_keys($serialised))->toBe($declared);

    foreach (array_keys($serialised) as $key) {
        expect($key)->toBe(lcfirst($key))
            ->and($key)->not->toContain('_');
    }
})->with(requestModelCases());

it('ignores a PascalCase input key, camelCase is the only convention', function () {
    // PascalCase is what the API *returns*; it is normalized away before a DTO
    // ever sees it (#36). A DTO that still accepted one would mask a
    // regression in that normalization.
    $model = SurveyCreateModel::from([
        'surveyName' => 'Demo',
        'clientName' => 'Acme',
        'surveyType' => 'Capi',
        'Description' => 'ignored',
        'EnableRespondentsGateway' => true,
    ]);

    expect($model->description)->toBeNull()
        ->and($model->enableRespondentsGateway)->toBeNull();
});

it('carries no casing mapper on any model', function (string $class) {
    $source = (string) file_get_contents((new ReflectionClass($class))->getFileName());

    expect($source)->not->toMatch('/#\[\s*Map(Input|Output)?Name\s*\(/');
})->with(array_map(fn (array $case): array => [$case[0]], requestModelCases()));
