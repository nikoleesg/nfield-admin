<?php

namespace Nikoleesg\NfieldAdmin\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Nikoleesg\NfieldAdmin\Models\BackgroundActivity as Model;
use Nikoleesg\NfieldAdmin\Facades\Background;

class SyncBackgroundActivitiesDetailsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nfield-admin:sync-activity-details {--activity=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve details of a specific background activity.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $activityId = $this->option('activity');

        if ($activityId && !Str::isUuid($activityId)) {
            $this->error('Activity ID should be UUID.');
            return ;
        }

        $this->info('Start sync activities details from Nfield API to local database...');

        // Look for the activities
        $activities = Model::whereIn('status', [0, 1])->orWhereNull('status')
            ->when($activityId, function (Builder $query) use ($activityId) {
                $query->where('activity_id', $activityId);
            })
            ->get();

        $count = 0;

        foreach($activities as $activity)
        {
            $activityDetails = Background::getBackgroundActivity($activity->activity_id);

            if (!is_null($activityDetails)) {
                // activity found
                $activity->update($activityDetails->toArray());
                $count++;
            } else {
                // activity not found
            }
        }

        $this->info("Finish sync ($count) activities details.");
    }
}
