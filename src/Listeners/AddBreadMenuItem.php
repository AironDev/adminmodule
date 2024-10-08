<?php

namespace Modules\Admin\Listeners;

use Modules\Admin\Events\BreadAdded;
use Modules\Admin\Facades\AdminModule;

class AddBreadMenuItem
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Create a MenuItem for a given BREAD.
     *
     * @param BreadAdded $event
     *
     * @return void
     */
    public function handle(BreadAdded $bread)
    {
        if (config('voyager.bread.add_menu_item') && file_exists(base_path('routes/web.php'))) {
            $menu = AdminModule::model('Menu')->where('name', config('voyager.bread.default_menu'))->firstOrFail();

            $menuItem = AdminModule::model('MenuItem')->firstOrNew([
                'menu_id' => $menu->id,
                'title'   => $bread->dataType->getTranslatedAttribute('display_name_plural'),
                'url'     => '',
                'route'   => 'voyager.'.$bread->dataType->slug.'.index',
            ]);

            $order = AdminModule::model('MenuItem')->highestOrderMenuItem();

            if (!$menuItem->exists) {
                $menuItem->fill([
                    'target'     => '_self',
                    'icon_class' => $bread->dataType->icon,
                    'color'      => null,
                    'parent_id'  => null,
                    'order'      => $order,
                ])->save();
            }
        }
    }
}
