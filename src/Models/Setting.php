<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Events\SettingUpdated;

class Setting extends Model
{
    protected $table = 'settings';

    protected $guarded = [];

    public $timestamps = false;

    protected $dispatchesEvents = [
        'updating' => SettingUpdated::class,
    ];
}
