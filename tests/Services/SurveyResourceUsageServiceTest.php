<?php

declare(strict_types=1);

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyResourceUsageEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Domain\SurveyResources\SurveyResourceUsageModel;
use Nikoleesg\NfieldAdmin\Enums\SurveyChannelEnum;
use Nikoleesg\NfieldAdmin\Enums\SurveyStateEnum;
use Nikoleesg\NfieldAdmin\Facades\NfieldManager;
use Nikoleesg\NfieldAdmin\Services\SurveyResourceUsageService;

afterEach(function () {
    Mockery::close();
});

function surveyResourceUsagePayload(array $overrides = []): array
{
    return $overrides + [
        'surveyId' => 'survey-1',
        'name' => 'Wave 1',
        'channel' => 3,
        'creationDate' => '2026-01-02T03:04:05Z',
        'clientName' => 'Acme',
        'state' => 1,
        'owner' => 'Ada',
        'lastDataDownloadDate' => null,
        'lastDataCollectionDate' => '2026-09-20T10:00:00Z',
        'willBeStoppedOn' => null,
        'willBeDeletedOn' => '2027-01-02T00:00:00Z',
        'size' => 5_368_709_120,
        'isExcludedFromAutomaticCleanup' => false,
    ];
}

it('lists and filters survey resource usage as models', function () {
    $endpoint = Mockery::mock(SurveyResourceUsageEndpointInterface::class);

    $endpoint->shouldReceive('list')->once()->andReturn([surveyResourceUsagePayload()]);
    $endpoint->shouldReceive('find')
        ->with(['$filter' => 'State eq 1'])
        ->once()
        ->andReturn([surveyResourceUsagePayload(['surveyId' => 'survey-2'])]);

    $service = new SurveyResourceUsageService($endpoint);

    $all = $service->list();

    expect($all)->toBeInstanceOf(Collection::class)
        ->and($all->first())->toBeInstanceOf(SurveyResourceUsageModel::class)
        ->and($all->first()->channel)->toBe(SurveyChannelEnum::Capi)
        ->and($all->first()->state)->toBe(SurveyStateEnum::Started)
        ->and($all->first()->willBeDeletedOn)->toBeInstanceOf(Carbon::class)
        ->and($all->first()->lastDataDownloadDate)->toBeNull()
        // Sizes are int64 bytes; 5 GiB would overflow a 32-bit int.
        ->and($all->first()->size)->toBe(5_368_709_120)
        ->and($service->find(['$filter' => 'State eq 1'])->first()->surveyId)->toBe('survey-2');
});

it('is reached from the manager', function () {
    expect(NfieldManager::surveyResourceUsage())->toBeInstanceOf(SurveyResourceUsageService::class);
});
