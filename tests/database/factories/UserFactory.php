<?php

namespace Modules\Admin\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
 
class UserFactory extends Factory
{
    protected $model = \Modules\Admin\Models\User::class;

    public function definition()
    {
        static $password;

        return [
            'name'           => $this->faker->name(),
            'email'          => $this->faker->unique()->safeEmail(),
            'password'       => $password ?: $password = bcrypt('secret'),
            'remember_token' => Str::random(10),
        ];
    }
}
