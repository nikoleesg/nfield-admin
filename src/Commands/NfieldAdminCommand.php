<?php

namespace Nikoleesg\NfieldAdmin\Commands;

use Illuminate\Console\Command;

class NfieldAdminCommand extends Command
{
    public $signature = 'nfield-admin';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
