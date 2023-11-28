<?php

namespace Nikoleesg\NfieldAdmin\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Nikoleesg\NfieldAdmin\Models\ResponseCode as Model;
use Nikoleesg\NfieldAdmin\Facades\Survey;
use Nikoleesg\NfieldAdmin\Facades\Domain;

class SyncResponseCodesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nfield-admin:sync-response-codes {--survey=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Domain or Survey ResponseCodes from Nfield API and sync with local database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $surveyId = $this->option('survey');

        if ($surveyId && !Str::isUuid($surveyId)) {
            $this->error('Survey ID should be UUID.');
            return ;
        }

        $this->info('Syncing data from Nfield API to local database...');

        if ($surveyId) {
            $responseCodes = Survey::setSurvey($surveyId)->getResponseCodes();
        } else {
            $responseCodes = Domain::getResponseCodes();
        }

        if ($responseCodes->count() > 0) {

            foreach ($responseCodes as $responseCode)
            {
                $responseCodeModel = array_merge($responseCode->toArray(), ['survey_id' => $surveyId ?? null]);

                Model::updateOrCreate($responseCodeModel);
            }

            $this->info('Data synchronized successfully.');
        } else {
            $this->error('Failed to fetch data from Nfield API.');
        }
    }
}
