<?php

namespace Nikoleesg\NfieldAdmin\Commands;

use Illuminate\Console\Command;
use Nikoleesg\NfieldAdmin\Models\Interviewer as Model;
use Nikoleesg\NfieldAdmin\Facades\Interviewer;

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

            $interviewerCollectionData = $capiInterviewers->toArray();

            Model::upsert(
                $interviewerCollectionData,
                ['interviewer_id'],
                [
                    'user_name', 'first_name', 'last_name', 'email_address', 'telephone_number',
                    'last_password_change_time', 'client_interviewer_id', 'successful_count',
                    'unsuccessful_count', 'dropped_out_count', 'rejected_count', 'last_sync_date',
                    'is_full_synced', 'is_last_sync_successful', 'is_supervisor'
                ]
            );

            $this->info('Data synchronized successfully.');
        } else {
            $this->error('Failed to fetch data from Nfield API.');
        }
    }
}
