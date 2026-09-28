<?php

declare(strict_types=1);

use Carbon\Carbon;
use Nikoleesg\NfieldAdmin\Data\Casts\StrictNullCast;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyModel;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

it('preserves "0" and converts blank or whitespace strings to null', function (mixed $input, ?string $expected) {
    $cast = new StrictNullCast;
    $property = Mockery::mock(DataProperty::class);
    $context = Mockery::mock(CreationContext::class);

    $result = $cast->cast($property, $input, [], $context);

    expect($result)->toBe($expected);
})->with([
    'string 0' => ['0', '0'],
    'empty string' => ['', null],
    'whitespace string' => ['   ', null],
    'tab and newline' => [" \t\n ", null],
    'null value' => [null, null],
    'regular string' => ['valid text', 'valid text'],
]);

it('hydrates SurveyModel with "0" clientName without converting to null', function () {
    $payload = surveyPayload(['clientName' => '0']);

    $model = SurveyModel::from($payload);

    expect($model->clientName)->toBe('0');
});

it('hydrates SurveyModel with empty surveyType without throwing TypeError', function () {
    $payload = surveyPayload(['surveyType' => '']);

    $model = SurveyModel::from($payload);

    expect($model->surveyType)->toBe('');
});

it('casts lastStartDate to Carbon when present', function () {
    $payload = surveyPayload(['lastStartDate' => '2024-01-02T03:04:05Z']);

    $model = SurveyModel::from($payload);

    expect($model->lastStartDate)->toBeInstanceOf(Carbon::class)
        ->and($model->lastStartDate->year)->toBe(2024)
        ->and($model->lastStartDate->month)->toBe(1)
        ->and($model->lastStartDate->day)->toBe(2);
});
