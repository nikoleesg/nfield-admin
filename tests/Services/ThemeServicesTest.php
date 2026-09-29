<?php

declare(strict_types=1);

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\ThemeCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\ThemeEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityResponseModel;
use Nikoleesg\NfieldAdmin\Data\Templates\ThemeUrlResponseModel;
use Nikoleesg\NfieldAdmin\Exceptions\MissingScopeException;
use Nikoleesg\NfieldAdmin\Facades\NfieldManager;
use Nikoleesg\NfieldAdmin\Services\ThemeCollectionService;
use Nikoleesg\NfieldAdmin\Services\ThemeService;

afterEach(function () {
    Mockery::close();
});

it('uploads a theme file and returns the background activity', function () {
    $endpoint = Mockery::mock(ThemeCollectionEndpointInterface::class);

    $endpoint->shouldReceive('upload')
        ->with('template-1', 'Brand A', 'zip-bytes', 'brand-a.zip')
        ->once()
        ->andReturn([
            'id' => 'activity-1',
            'activityType' => 12,
            'activityTypeName' => 'ThemeUpload',
            'activityName' => 'Brand A',
            'status' => 0,
            'statusName' => 'Pending',
            'userId' => 'user-1',
            'creationTime' => '2026-09-29T10:00:00Z',
            'startTime' => null,
            'finishTime' => null,
            'downloadDataUrl' => null,
        ]);

    $activity = (new ThemeCollectionService($endpoint))->upload('template-1', 'Brand A', 'zip-bytes', 'brand-a.zip');

    expect($activity)->toBeInstanceOf(BackgroundActivityResponseModel::class)
        ->and($activity->id)->toBe('activity-1');
});

it('reads the download URL of the theme it is scoped to', function () {
    $collection = Mockery::mock(ThemeCollectionEndpointInterface::class);
    $collection->shouldReceive('downloadUrl')->with('theme-1')->once()->andReturn(['url' => 'https://cdn.example.com/theme-1.zip']);

    $url = (new ThemeService(Mockery::mock(ThemeEndpointInterface::class), $collection))->setThemeId('theme-1')->downloadUrl();

    expect($url)->toBeInstanceOf(ThemeUrlResponseModel::class)
        ->and($url->url)->toBe('https://cdn.example.com/theme-1.zip');
});

it('deletes the theme it is scoped to', function () {
    $item = Mockery::mock(ThemeEndpointInterface::class);
    $item->shouldReceive('delete')->with('theme-1')->once();

    (new ThemeService($item, Mockery::mock(ThemeCollectionEndpointInterface::class)))->setThemeId('theme-1')->delete();
});

it('scopes the service it hands out to one theme', function () {
    expect(NfieldManager::themes()->forTheme('theme-1')->getThemeId())->toBe('theme-1');
});

it('refuses a theme call before the theme is set', function () {
    $service = new ThemeService(Mockery::mock(ThemeEndpointInterface::class), Mockery::mock(ThemeCollectionEndpointInterface::class));

    expect(fn () => $service->delete())->toThrow(MissingScopeException::class);
});
