<?php

namespace Nikoleesg\NfieldAdmin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Nikoleesg\NfieldAdmin\Traits\HasTablePrefix;

class ResponseCode extends Model
{
    use HasTablePrefix;

    protected $guarded = [];

    public function scopeDomain(Builder $query)
    {
        return $query->whereNull('survey_id');
    }

    public function scopeSurvey(Builder $query, string $surveyId = null)
    {
        return $query->when(is_null($surveyId), function (Builder $query) {

            $query->whereNotNull('survey_id');

        })->when(!is_null($surveyId), function (Builder $query) use ($surveyId) {

            $query->where('survey_id', $surveyId);

        });
    }
}
