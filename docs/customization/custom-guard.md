# Custom guard

Starting with AdminModule 1.2 you can define a \(custom\) guard which is used throughout AdminModule.  
To do so, just bind the name of your auth-guard to `AdminModuleGuard`.  
First, make sure you have defined a guard as per the [Laravel documentation](https://laravel.com/docs/authentication#adding-custom-guards).  
After that open your `AuthServiceProvider` and add the following to the register method:

```php
$this->app->singleton('AdminModuleGuard', function () {
    return 'your-custom-guard-name';
});
```

Now this guard is used instead of the default guard.


# Example - using a different model and table for Admins

First you have to create a new table. Let's call it `admins`:  
```php
<?php
Schema::create('admins', function (Blueprint $table) {
    $table->bigIncrements('id');
    $table->bigInteger('role_id')->unsigned()->nullable();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('avatar')->nullable()->default('users/default.png');
    $table->string('password')->nullable();
    $table->string('remember_token')->nullable();
    $table->text('settings')->nullable()->default(null);
    $table->timestamps();
    $table->foreign('role_id')->references('id')->on('roles');
});
```

and a model which extends AdminModules user-model:

```php
<?php

namespace App;

class Admin extends \Modules\Admin\Models\User
{

}
```

Next, create a guard named `admin` in your `config/auth.php`:
```php
'guards' => [
    'admin' => [
        'driver' => 'session',
        'provider' => 'admins',
    ],

    // ...
],
```
And a user provider called `admins`:
```php
'providers' => [
    'admins' => [
        'driver' => 'eloquent',
        'model' => App\Admin::class,
    ],

    // ...
],
```

Next you have to tell AdminModule to use your new guard.  
Open you `AppServiceProvider.php` and add the following to the `register` method:

```php
public function register()
{
    $this->app->singleton('AdminModuleGuard', function () {
        return 'admin';
    });
}
```

{% hint style="info" %}
Please note that the user-bread is still responsible to edit users - not admins.  
Create a BREAD for the `admins` table if you want to change Admins.
{% endhint %}