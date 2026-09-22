<?php

declare(strict_types=1);

use Nikoleesg\NfieldAdmin\Data\AddressDTO;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivityDTO;
use Nikoleesg\NfieldAdmin\Data\QuotaFrameVersionData;
use Nikoleesg\NfieldAdmin\Data\SurveyQuotaFrame\SurveyQuotaFrameLevelData;
use Nikoleesg\NfieldAdmin\Data\SurveyUpdateSampleRecordDTO;

it('AddressDTO::fromResponse creates instance correctly', function () {
    $dto = AddressDTO::fromResponse([
        'addressId' => '123',
        'details' => 'Detail text',
        'appointmentDate' => '2023-01-01',
        'sampleData' => [],
    ]);
    expect($dto->addressId)->toBe('123')
        ->and($dto->details)->toBe('Detail text');
});

it('BackgroundActivityDTO::fromInitialised creates instance correctly for short array', function () {
    $dto = BackgroundActivityDTO::fromInitialised(['activityId' => 'act-123']);
    expect($dto->activityId)->toBe('act-123')
        ->and($dto->name)->toBeNull();
});

it('SurveyUpdateSampleRecordDTO::fromResponse creates instance correctly', function () {
    $dto = SurveyUpdateSampleRecordDTO::fromResponse([
        'sampleRecordId' => 123,
        'targetList' => [],
    ]);
    expect($dto->sampleRecordId)->toBe(123);
});

it('QuotaFrameVersionData::fromResponse creates instance correctly', function () {
    $dto = QuotaFrameVersionData::fromResponse([
        'id' => 'ver-123',
        'eTag' => 'etag',
        'publishedDate' => '2023-01-01',
    ]);
    expect($dto->id)->toBe('ver-123');
});

it('SurveyQuotaFrameLevelData::fromResponse creates instance correctly', function () {
    $dto = SurveyQuotaFrameLevelData::fromResponse([
        'id' => 'lvl-123',
        'definitionId' => 'def-123',
        'target' => 100,
        'maxTarget' => 100,
        'maxOvershoot' => 0,
        'maxCount' => 100,
        'targetsByDefinition' => [],
        'isHidden' => false,
    ]);
    expect($dto->id)->toBe('lvl-123');
});
