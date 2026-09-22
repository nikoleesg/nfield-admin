<?php

declare(strict_types=1);

use Nikoleesg\NfieldAdmin\Data\AddressDTO;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivityDTO;
use Nikoleesg\NfieldAdmin\Data\QuotaFrameVersionData;
use Nikoleesg\NfieldAdmin\Data\SurveyQuotaFrame\SurveyQuotaFrameLevelData;
use Nikoleesg\NfieldAdmin\Data\SurveyUpdateSampleRecordDTO;

it('AddressDTO::fromResponse creates instance correctly', function () {
    $dto = AddressDTO::fromResponse([
        'AddressId' => '123',
        'Details' => 'Detail text',
        'AppointmentDate' => '2023-01-01',
        'SampleData' => [],
    ]);
    expect($dto->address_id)->toBe('123')
        ->and($dto->details)->toBe('Detail text');
});

it('BackgroundActivityDTO::fromInitialised creates instance correctly for short array', function () {
    $dto = BackgroundActivityDTO::fromInitialised(['ActivityId' => 'act-123']);
    expect($dto->activity_id)->toBe('act-123')
        ->and($dto->name)->toBeNull();
});

it('SurveyUpdateSampleRecordDTO::fromResponse creates instance correctly', function () {
    $dto = SurveyUpdateSampleRecordDTO::fromResponse([
        'SampleRecordId' => 123,
        'TargetList' => [],
    ]);
    expect($dto->sample_record_id)->toBe(123);
});

it('QuotaFrameVersionData::fromResponse creates instance correctly', function () {
    $dto = QuotaFrameVersionData::fromResponse([
        'Id' => 'ver-123',
        'ETag' => 'etag',
        'PublishedDate' => '2023-01-01',
    ]);
    expect($dto->id)->toBe('ver-123');
});

it('SurveyQuotaFrameLevelData::fromResponse creates instance correctly', function () {
    $dto = SurveyQuotaFrameLevelData::fromResponse([
        'Id' => 'lvl-123',
        'DefinitionId' => 'def-123',
        'Target' => 100,
        'MaxTarget' => 100,
        'MaxOvershoot' => 0,
        'MaxCount' => 100,
        'TargetsByDefinition' => [],
        'IsHidden' => false,
    ]);
    expect($dto->id)->toBe('lvl-123');
});
