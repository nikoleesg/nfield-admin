<?php

declare(strict_types=1);

use Carbon\Carbon;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityResponseModel;
use Nikoleesg\NfieldAdmin\Enums\ActivityStatusEnum;

it('parses timestamps with and without fractional seconds and preserves timezone on serialize', function (string $timestamp) {
    $model = BackgroundActivityResponseModel::from([
        'id' => 'act-1',
        'activityType' => 1,
        'activityTypeName' => 'DataDownload',
        'activityName' => 'Download data',
        'status' => ActivityStatusEnum::Started->value,
        'statusName' => 'Started',
        'userId' => 'user-1',
        'creationTime' => $timestamp,
        'startTime' => null,
        'finishTime' => null,
        'downloadDataUrl' => null,
    ]);

    expect($model->creationTime)->toBeInstanceOf(Carbon::class)
        ->and($model->creationTime->year)->toBe(2024)
        ->and($model->creationTime->month)->toBe(1)
        ->and($model->creationTime->day)->toBe(2)
        ->and($model->creationTime->hour)->toBe(3)
        ->and($model->creationTime->minute)->toBe(4)
        ->and($model->creationTime->second)->toBe(5)
        ->and($model->toArray()['creationTime'])->toBe('2024-01-02T03:04:05+00:00');
})->with([
    '7 fractional digits' => '2024-01-02T03:04:05.1234567Z',
    '3 fractional digits' => '2024-01-02T03:04:05.123Z',
    'no fractional digits' => '2024-01-02T03:04:05Z',
]);
