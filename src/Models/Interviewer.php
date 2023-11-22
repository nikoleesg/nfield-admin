<?php

namespace Nikoleesg\NfieldAdmin\Models;

use Illuminate\Database\Eloquent\Model;
use Nikoleesg\NfieldAdmin\Data\InterviewerDTO;
use Nikoleesg\NfieldAdmin\Traits\HasTablePrefix;
use Spatie\LaravelData\WithData;

class Interviewer extends Model
{
    use HasTablePrefix, WithData;

    protected $dataClass = InterviewerDTO::class;

    protected $guarded = [];

    protected $casts = [
        'last_password_change_time' => 'datetime',
        'last_sync_date' => 'datetime',
    ];
}
