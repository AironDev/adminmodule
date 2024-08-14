# Overriding files

## Overriding BREAD Views

You can override any of the BREAD views for a **single** BREAD by creating a new folder in `resources/views/vendor/voyager/slug-name` where _slug-name_ is the _slug_ that you have assigned for that table. There are 4 files that you can override:

* browse.blade.php
* edit-add.blade.php
* read.blade.php
* order.blade.php

Alternatively you can override the views for **all** BREADs by creating any of the above files under `resources/views/vendor/voyager/bread`

## Overriding submit button:
You can override the submit button without the need to override the whole `edit-add.blade.php` by extending the `submit-buttons` section:  
```blade
@extends('voyager::bread.edit-add')
@section('submit-buttons')
    @parent
    <button type="submit" class="btn btn-primary save">Save And Publish</button>
@endsection
```

## Using custom Controllers

You can override the controller for a single BREAD by creating a controller which extends AdminModules controller, for example:

```php
<?php

namespace App\Http\Controllers;

class AdminModuleCategoriesController extends \Modules\Admin\Http\Controllers\AdminModuleBaseController
{
    //...
}
```

After that go to the BREAD-settings and fill in the Controller Name with your fully-qualified class-name:

![](../.gitbook/assets/bread_controller.png)

You can now override all methods from the [AdminModuleBaseController](https://github.com/the-control-group/voyager/blob/1.6/src/Http/Controllers/AdminModuleBaseController.php)

## Overriding AdminModules Controllers

{% hint style="danger" %}
**Only use this method if you know what you are doing**  
We don't recommend or support overriding all controllers as you won't get any code-changes made in future updates.
{% endhint %}

If you want to override any of AdminModules core controllers you first have to change your config file `config/voyager.php`:

```php
/*
|--------------------------------------------------------------------------
| Controllers config
|--------------------------------------------------------------------------
|
| Here you can specify voyager controller settings
|
*/

'controllers' => [
    'namespace' => 'App\\Http\\Controllers\\AdminModule',
],
```

Then run `php artisan voyager:controllers`, AdminModule will now use the child controllers which will be created at `App/Http/Controllers/AdminModule`

## Overriding AdminModule-Models

You are also able to override AdminModules models if you need to.  
To do so, you need to add the following to your AppServiceProviders register method:

```php
AdminModule::useModel($name, $object);
```

Where **name** is the class-name of the model and **object** the fully-qualified name of your custom model. For example:

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Events\Dispatcher;
use Modules\Admin\Facades\AdminModule;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        AdminModule::useModel('DataRow', \App\DataRow::class);
    }
    // ...
}
```

The next step is to create your model and make it extend the original model. In case of `DataRow`:

```php
<?php

namespace App;

class DataRow extends \Modules\Admin\Models\DataRow
{
    // ...
}
```

If the model you are overriding has an associated BREAD, go to the BREAD settings for the model you are overriding
and replace the Model Name with your fully-qualified class-name. For example, if you are overriding the AdminModule `Menu`
model with your own `App\Menu` model:

![](../.gitbook/assets/bread_override_voyager_models.png)

