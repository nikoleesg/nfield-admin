<?php

namespace Nikoleesg\NfieldAdmin\Commands;

use Illuminate\Console\Command;
use Nikoleesg\NfieldAdmin\Facades\Interviewer;
use Nikoleesg\NfieldAdmin\Models\Interviewer as Model;

class SyncCapiInterviewerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nfield-admin:sync-capi-interviewer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch CapiInterviewer from Nfield API and sync with local database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Syncing data from Nfield API to local database...');

        $capiInterviewers = Interviewer::getCapiInterviewers();

        if ($capiInterviewers->count() > 0) {

            foreach ($capiInterviewers as $capiInterviewer) {
                Model::updateOrCreate($capiInterviewer->toArray());
            }

            $this->info('Data synchronized successfully.');
        } else {
            $this->error('Failed to fetch data from Nfield API.');
        }
    }
}
