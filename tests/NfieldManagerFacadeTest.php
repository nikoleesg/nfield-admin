<?php

declare(strict_types=1);

use Nikoleesg\NfieldAdmin\Facades\NfieldManager;
use Nikoleesg\NfieldAdmin\Services\NfieldManagerService;

it('resolves NfieldManagerService from the container', function () {
    $resolved = NfieldManager::getFacadeRoot();

    expect($resolved)->toBeInstanceOf(NfieldManagerService::class);
});
