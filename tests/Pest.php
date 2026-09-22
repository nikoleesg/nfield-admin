<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Nikoleesg\NfieldAdmin\Enums\SamplingPointKindEnum;
use Nikoleesg\NfieldAdmin\Enums\SurveyStateEnum;
use Nikoleesg\NfieldAdmin\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

/**
 * The single non-token request the call under test produced.
 */
function sentRequest(): Request
{
    $requests = collect(Http::recorded())
        ->map(fn (array $pair): Request => $pair[0])
        ->reject(fn (Request $request): bool => str_contains($request->url(), '/v2/token'))
        ->values();

    expect($requests)->toHaveCount(1);

    return $requests->first();
}

/**
 * The request URI, relative to the configured base URL.
 */
function sentUri(Request $request): string
{
    return ltrim(str_replace(config('nfield-admin.base_url'), '', $request->url()), '/');
}

/**
 * A full survey payload as an endpoint returns it (camelCase, post-normalization).
 */
function surveyPayload(array $overrides = []): array
{
    return array_merge([
        'surveyId' => 'survey-1',
        'surveyName' => 'Demo',
        'clientName' => 'Acme',
        'surveyType' => 'Capi',
        'description' => 'A demo survey',
        'questionnaireMD5' => 'abc',
        'interviewerInstruction' => 'Be nice',
        'surveyState' => SurveyStateEnum::Started->value,
        'surveyGroupId' => 1,
        'isBlueprint' => false,
        'enableRespondentsGateway' => false,
        'lastStartDate' => null,
    ], $overrides);
}

/**
 * A full sampling point payload as an endpoint returns it.
 */
function samplingPointPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'SP 1',
        'description' => 'First sampling point',
        'fieldworkOfficeId' => 'office-1',
        'groupId' => null,
        'stratum' => null,
        'customDataItems' => [['name' => 'region', 'value' => 'north']],
        'kind' => SamplingPointKindEnum::Regular->value,
        'samplingPointId' => 'sp-1',
    ], $overrides);
}
