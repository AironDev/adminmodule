<?php

namespace Modules\Admin\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Modules\Admin\Events;
use Modules\Admin\Listeners;

class AdminModuleEventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Events\BreadAdded::class => [
            Listeners\AddBreadMenuItem::class,
            Listeners\AddBreadPermission::class,
        ],
        Events\BreadDeleted::class => [
            Listeners\DeleteBreadMenuItem::class,
        ],
        Events\SettingUpdated::class => [
            Listeners\ClearCachedSettingValue::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
