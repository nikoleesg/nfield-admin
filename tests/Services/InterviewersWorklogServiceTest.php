<?php

declare(strict_types=1);

use Carbon\Carbon;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\InterviewersWorklogEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityStatus;
use Nikoleesg\NfieldAdmin\Data\Domain\InterviewersWorklogRequestModel;
use Nikoleesg\NfieldAdmin\Facades\NfieldManager;
use Nikoleesg\NfieldAdmin\Services\InterviewersWorklogService;

afterEach(function () {
    Mockery::close();
});

it('requests a worklog download and returns the activity', function () {
    $endpoint = Mockery::mock(InterviewersWorklogEndpointInterface::class);

    $endpoint->shouldReceive('download')
        ->with(['from' => '2026-09-01T00:00:00+00:00', 'to' => '2026-09-30T23:59:59+00:00'])
        ->twice()
        ->andReturn(['activityId' => 'worklog-1']);

    $service = new InterviewersWorklogService($endpoint);

    // Arrays and the model go out as the same wire format.
    $fromArray = $service->download(['from' => '2026-09-01', 'to' => '2026-09-30 23:59:59']);
    $fromModel = $service->download(new InterviewersWorklogRequestModel(
        Carbon::parse('2026-09-01T00:00:00Z'),
        Carbon::parse('2026-09-30T23:59:59Z'),
    ));

    expect($fromArray)->toBeInstanceOf(BackgroundActivityStatus::class)
        ->and($fromArray->activityId)->toBe('worklog-1')
        ->and($fromModel->activityId)->toBe('worklog-1');
});

it('sends the date range in UTC whatever timezone it was given in', function () {
    // The API documents both dates as UTC.
    $payload = (new InterviewersWorklogRequestModel(
        Carbon::parse('2026-09-01T08:00:00+08:00'),
        Carbon::parse('2026-09-30T20:00:00-04:00'),
    ))->toArray();

    expect($payload)->toBe([
        'from' => '2026-09-01T00:00:00+00:00',
        'to' => '2026-10-01T00:00:00+00:00',
    ]);
});

it('is reached from the manager', function () {
    expect(NfieldManager::interviewersWorklog())->toBeInstanceOf(InterviewersWorklogService::class);
});
