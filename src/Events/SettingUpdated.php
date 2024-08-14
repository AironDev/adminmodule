<?php

namespace Modules\Admin\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Admin\Models\Setting;

class SettingUpdated
{
    use SerializesModels;

    public $setting;

    public function __construct(Setting $setting)
    {
        $this->setting = $setting;
    }
}
