<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Tests\Data;

use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\EditCapiInterviewerRequestModel;
use Nikoleesg\NfieldAdmin\Data\Domain\ResponseCodes\DomainResponseCodeUpdateModel;
use Nikoleesg\NfieldAdmin\Data\Events\UpdateSubscriptionModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingMethodModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\ActivateSpareSamplingPointRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\ReplaceSamplingPointWithSpareRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointQuotaLevelTargetModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointQuotaLevelTargetUpdateRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointUpdateRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyGeneralSettingsUpdateModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyResponseCodeUpdateModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyUpdateModel;
use Nikoleesg\NfieldAdmin\Enums\SamplingPointKindEnum;
use Spatie\LaravelData\Exceptions\CannotCreateData;

it('omits unset properties when partially serializing SurveyUpdateModel', function () {
    expect(SurveyUpdateModel::from(['surveyName' => 'X'])->toArray())
        ->toBe(['surveyName' => 'X'])
        ->and(SurveyUpdateModel::from([])->toArray())
        ->toBe([])
        ->and(SurveyUpdateModel::from(['surveyName' => 'X', 'clientName' => null])->toArray())
        ->toBe(['surveyName' => 'X', 'clientName' => null])
        ->and(SurveyUpdateModel::from(['description' => ''])->toArray())
        ->toBe(['description' => '']);
});

it('omits unset properties when partially serializing SamplingPointUpdateRequestModel', function () {
    expect(SamplingPointUpdateRequestModel::from(['name' => 'SP 1'])->toArray())
        ->toBe(['name' => 'SP 1'])
        ->and(SamplingPointUpdateRequestModel::from([
            'name' => 'SP 1',
            'description' => null,
            'kind' => 1,
        ])->toArray())
        ->toBe([
            'name' => 'SP 1',
            'description' => null,
            'kind' => 1,
        ])
        ->and(SamplingPointUpdateRequestModel::from([
            'name' => 'SP 1',
            'description' => '',
            'kind' => SamplingPointKindEnum::Spare,
        ])->toArray())
        ->toBe([
            'name' => 'SP 1',
            'description' => '',
            'kind' => 1,
        ])
        ->and(SamplingPointUpdateRequestModel::from([
            'name' => 'SP 1',
            'customDataItems' => [['name' => 'region', 'value' => 'north']],
        ])->toArray())
        ->toBe([
            'name' => 'SP 1',
            'customDataItems' => [['name' => 'region', 'value' => 'north']],
        ]);
});

it('requires name when constructing SamplingPointUpdateRequestModel', function () {
    expect(fn () => SamplingPointUpdateRequestModel::from([]))
        ->toThrow(CannotCreateData::class);
});

it('omits unset properties and respects nullability on EditCapiInterviewerRequestModel', function () {
    expect(EditCapiInterviewerRequestModel::from(['firstName' => 'Ada'])->toArray())
        ->toBe(['firstName' => 'Ada'])
        ->and(EditCapiInterviewerRequestModel::from([])->toArray())
        ->toBe([])
        ->and(EditCapiInterviewerRequestModel::from([
            'firstName' => 'Ada',
            'emailAddress' => null,
            'isSupervisor' => false,
        ])->toArray())
        ->toBe([
            'firstName' => 'Ada',
            'emailAddress' => null,
            'isSupervisor' => false,
        ]);
});

it('omits unset properties on UpdateSubscriptionModel', function () {
    expect(UpdateSubscriptionModel::from(['endpoint' => 'https://example.com/webhook'])->toArray())
        ->toBe(['endpoint' => 'https://example.com/webhook'])
        ->and(UpdateSubscriptionModel::from([])->toArray())
        ->toBe([])
        ->and(UpdateSubscriptionModel::from(['eventTypes' => null])->toArray())
        ->toBe(['eventTypes' => null]);
});

it('omits unset properties and supports ownerId on SurveyGeneralSettingsUpdateModel', function () {
    expect(SurveyGeneralSettingsUpdateModel::from(['name' => 'Renamed'])->toArray())
        ->toBe(['name' => 'Renamed'])
        ->and(SurveyGeneralSettingsUpdateModel::from([])->toArray())
        ->toBe([])
        ->and(SurveyGeneralSettingsUpdateModel::from([
            'name' => 'Renamed',
            'ownerId' => 'user-123',
            'excludeFromAutomaticCleanup' => null,
        ])->toArray())
        ->toBe([
            'name' => 'Renamed',
            'excludeFromAutomaticCleanup' => null,
            'ownerId' => 'user-123',
        ]);
});

it('serializes only target property for SamplingPointQuotaLevelTargetUpdateRequestModel', function () {
    expect(SamplingPointQuotaLevelTargetUpdateRequestModel::from(['target' => 25])->toArray())
        ->toBe(['target' => 25])
        ->and(SamplingPointQuotaLevelTargetUpdateRequestModel::from([])->toArray())
        ->toBe([])
        ->and(SamplingPointQuotaLevelTargetUpdateRequestModel::from(['target' => null])->toArray())
        ->toBe(['target' => null]);
});

it('omits unset properties on SamplingPointQuotaLevelTargetModel', function () {
    expect(SamplingPointQuotaLevelTargetModel::from(['target' => 20])->toArray())
        ->toBe(['target' => 20]);
});

it('omits unset properties on SamplingMethodModel', function () {
    expect(SamplingMethodModel::from(['samplingMethod' => 'Random'])->toArray())
        ->toBe(['samplingMethod' => 'Random'])
        ->and(SamplingMethodModel::from([])->toArray())
        ->toBe([])
        ->and(SamplingMethodModel::from(['samplingMethod' => null])->toArray())
        ->toBe(['samplingMethod' => null]);
});

it('omits unset properties on ActivateSpareSamplingPointRequestModel', function () {
    expect(ActivateSpareSamplingPointRequestModel::from(['target' => 5])->toArray())
        ->toBe(['target' => 5])
        ->and(ActivateSpareSamplingPointRequestModel::from([])->toArray())
        ->toBe([]);
});

it('omits unset properties on ReplaceSamplingPointWithSpareRequestModel', function () {
    expect(ReplaceSamplingPointWithSpareRequestModel::from(['spareSamplingPointId' => 'sp-9'])->toArray())
        ->toBe(['spareSamplingPointId' => 'sp-9'])
        ->and(ReplaceSamplingPointWithSpareRequestModel::from(['target' => 10])->toArray())
        ->toBe(['target' => 10])
        ->and(ReplaceSamplingPointWithSpareRequestModel::from([])->toArray())
        ->toBe([]);
});

it('omits unset properties on DomainResponseCodeUpdateModel', function () {
    expect(DomainResponseCodeUpdateModel::from(['url' => 'https://example.com'])->toArray())
        ->toBe(['url' => 'https://example.com'])
        ->and(DomainResponseCodeUpdateModel::from([])->toArray())->toBe([])
        // An explicit null is still sent, so a field can be cleared.
        ->and(DomainResponseCodeUpdateModel::from(['description' => null, 'channelCati' => false])->toArray())
        ->toBe(['description' => null, 'channelCati' => false]);
});

it('omits unset properties on SurveyResponseCodeUpdateModel', function () {
    expect(SurveyResponseCodeUpdateModel::from(['description' => 'Callback'])->toArray())
        ->toBe(['description' => 'Callback'])
        ->and(SurveyResponseCodeUpdateModel::from([])->toArray())->toBe([])
        ->and(SurveyResponseCodeUpdateModel::from(['relocationUrl' => null])->toArray())->toBe(['relocationUrl' => null]);
});
