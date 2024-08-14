# Overriding Routes

You can override any AdminModule routes by writing the routes you want to overwrite below `AdminModule::routes()`. For example if you want to override your post LoginController:

```php
Route::group(['prefix' => 'admin'], function () {
   AdminModule::routes();

   // Your overwrites here
   Route::post('login', ['uses' => 'MyAuthController@postLogin', 'as' => 'postlogin']);
});
```

