<?php

namespace Nikoleesg\NfieldAdmin\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\WithData;
use Nikoleesg\NfieldAdmin\Traits\HasTablePrefix;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivityDTO;

class BackgroundActivity extends Model
{
    use HasTablePrefix, WithData;

    protected $dataClass = BackgroundActivityDTO::class;

    protected $guarded = [];
}
