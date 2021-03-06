<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    $pd_type = ['ecom','pos'];
    return [
        'name' => $faker->name,
        'proprietor' => $faker->firstName,
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->bankAccountNumber,
        'address' => $faker->address,
        'company' => 'Default Compnay',
        'division_id' => 6,
        'district_id' => 47,
        'area_id' => 6,
        'email_verified_at' => now(),
        'password' => Hash::make(12345678), 
        'user_type' => 'pos',
        'status' => 0,
        'remember_token' => Str::random(10),
    ];
});
