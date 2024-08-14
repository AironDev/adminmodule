<?php

use Illuminate\Support\Str;
use Modules\Admin\Events\Routing;
use Modules\Admin\Events\RoutingAdmin;
use Modules\Admin\Events\RoutingAdminAfter;
use Modules\Admin\Events\RoutingAfter;
use Modules\Admin\Facades\AdminModule;

/*
|--------------------------------------------------------------------------
| AdminModule Routes
|--------------------------------------------------------------------------
|
| This file is where you may override any of the routes that are included
| with AdminModule.
|
*/

Route::group(['as' => 'voyager.'], function () {
    event(new Routing());

    $namespacePrefix = '\\'.config('voyager.controllers.namespace').'\\';

    Route::get('login', ['uses' => $namespacePrefix.'AdminModuleAuthController@login',     'as' => 'login']);
    Route::post('login', ['uses' => $namespacePrefix.'AdminModuleAuthController@postLogin', 'as' => 'postlogin']);

    Route::group(['middleware' => 'admin.user'], function () use ($namespacePrefix) {
        event(new RoutingAdmin());

        // Main Admin and Logout Route
        Route::get('/', ['uses' => $namespacePrefix.'AdminModuleController@index',   'as' => 'dashboard']);
        Route::post('logout', ['uses' => $namespacePrefix.'AdminModuleController@logout',  'as' => 'logout']);
        Route::post('upload', ['uses' => $namespacePrefix.'AdminModuleController@upload',  'as' => 'upload']);

        Route::get('profile', ['uses' => $namespacePrefix.'AdminModuleUserController@profile', 'as' => 'profile']);

        try {
            foreach (AdminModule::model('DataType')::all() as $dataType) {
                $breadController = $dataType->controller
                                 ? Str::start($dataType->controller, '\\')
                                 : $namespacePrefix.'AdminModuleBaseController';

                Route::get($dataType->slug.'/order', $breadController.'@order')->name($dataType->slug.'.order');
                Route::post($dataType->slug.'/action', $breadController.'@action')->name($dataType->slug.'.action');
                Route::post($dataType->slug.'/order', $breadController.'@update_order')->name($dataType->slug.'.update_order');
                Route::get($dataType->slug.'/{id}/restore', $breadController.'@restore')->name($dataType->slug.'.restore');
                Route::get($dataType->slug.'/relation', $breadController.'@relation')->name($dataType->slug.'.relation');
                Route::post($dataType->slug.'/remove', $breadController.'@remove_media')->name($dataType->slug.'.media.remove');
                Route::resource($dataType->slug, $breadController, ['parameters' => [$dataType->slug => 'id']]);
            }
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException("Custom routes hasn't been configured because: ".$e->getMessage(), 1);
        } catch (\Exception $e) {
            // do nothing, might just be because table not yet migrated.
        }

        // Menu Routes
        Route::group([
            'as'     => 'menus.',
            'prefix' => 'menus/{menu}',
        ], function () use ($namespacePrefix) {
            Route::get('builder', ['uses' => $namespacePrefix.'AdminModuleMenuController@builder',    'as' => 'builder']);
            Route::post('order', ['uses' => $namespacePrefix.'AdminModuleMenuController@order_item', 'as' => 'order_item']);

            Route::group([
                'as'     => 'item.',
                'prefix' => 'item',
            ], function () use ($namespacePrefix) {
                Route::delete('{id}', ['uses' => $namespacePrefix.'AdminModuleMenuController@delete_menu', 'as' => 'destroy']);
                Route::post('/', ['uses' => $namespacePrefix.'AdminModuleMenuController@add_item',    'as' => 'add']);
                Route::put('/', ['uses' => $namespacePrefix.'AdminModuleMenuController@update_item', 'as' => 'update']);
            });
        });

        // Settings
        Route::group([
            'as'     => 'settings.',
            'prefix' => 'settings',
        ], function () use ($namespacePrefix) {
            Route::get('/', ['uses' => $namespacePrefix.'AdminModuleSettingsController@index',        'as' => 'index']);
            Route::post('/', ['uses' => $namespacePrefix.'AdminModuleSettingsController@store',        'as' => 'store']);
            Route::put('/', ['uses' => $namespacePrefix.'AdminModuleSettingsController@update',       'as' => 'update']);
            Route::delete('{id}', ['uses' => $namespacePrefix.'AdminModuleSettingsController@delete',       'as' => 'delete']);
            Route::get('{id}/move_up', ['uses' => $namespacePrefix.'AdminModuleSettingsController@move_up',      'as' => 'move_up']);
            Route::get('{id}/move_down', ['uses' => $namespacePrefix.'AdminModuleSettingsController@move_down',    'as' => 'move_down']);
            Route::put('{id}/delete_value', ['uses' => $namespacePrefix.'AdminModuleSettingsController@delete_value', 'as' => 'delete_value']);
        });

        // Admin Media
        Route::group([
            'as'     => 'media.',
            'prefix' => 'media',
        ], function () use ($namespacePrefix) {
            Route::get('/', ['uses' => $namespacePrefix.'AdminModuleMediaController@index',              'as' => 'index']);
            Route::post('files', ['uses' => $namespacePrefix.'AdminModuleMediaController@files',              'as' => 'files']);
            Route::post('new_folder', ['uses' => $namespacePrefix.'AdminModuleMediaController@new_folder',         'as' => 'new_folder']);
            Route::post('delete_file_folder', ['uses' => $namespacePrefix.'AdminModuleMediaController@delete', 'as' => 'delete']);
            Route::post('move_file', ['uses' => $namespacePrefix.'AdminModuleMediaController@move',          'as' => 'move']);
            Route::post('rename_file', ['uses' => $namespacePrefix.'AdminModuleMediaController@rename',        'as' => 'rename']);
            Route::post('upload', ['uses' => $namespacePrefix.'AdminModuleMediaController@upload',             'as' => 'upload']);
            Route::post('crop', ['uses' => $namespacePrefix.'AdminModuleMediaController@crop',             'as' => 'crop']);
        });

        // BREAD Routes
        Route::group([
            'as'     => 'bread.',
            'prefix' => 'bread',
        ], function () use ($namespacePrefix) {
            Route::get('/', ['uses' => $namespacePrefix.'AdminModuleBreadController@index',              'as' => 'index']);
            Route::get('{table}/create', ['uses' => $namespacePrefix.'AdminModuleBreadController@create',     'as' => 'create']);
            Route::post('/', ['uses' => $namespacePrefix.'AdminModuleBreadController@store',   'as' => 'store']);
            Route::get('{table}/edit', ['uses' => $namespacePrefix.'AdminModuleBreadController@edit', 'as' => 'edit']);
            Route::put('{id}', ['uses' => $namespacePrefix.'AdminModuleBreadController@update',  'as' => 'update']);
            Route::delete('{id}', ['uses' => $namespacePrefix.'AdminModuleBreadController@destroy',  'as' => 'delete']);
            Route::post('relationship', ['uses' => $namespacePrefix.'AdminModuleBreadController@addRelationship',  'as' => 'relationship']);
            Route::get('delete_relationship/{id}', ['uses' => $namespacePrefix.'AdminModuleBreadController@deleteRelationship',  'as' => 'delete_relationship']);
        });

        // Database Routes
        Route::resource('database', $namespacePrefix.'AdminModuleDatabaseController');

        // Compass Routes
        Route::group([
            'as'     => 'compass.',
            'prefix' => 'compass',
        ], function () use ($namespacePrefix) {
            Route::get('/', ['uses' => $namespacePrefix.'AdminModuleCompassController@index',  'as' => 'index']);
            Route::post('/', ['uses' => $namespacePrefix.'AdminModuleCompassController@index',  'as' => 'post']);
        });

        event(new RoutingAdminAfter());
    });

    //Asset Routes
    Route::get('voyager-assets', ['uses' => $namespacePrefix.'AdminModuleController@assets', 'as' => 'voyager_assets']);

    event(new RoutingAfter());
});
