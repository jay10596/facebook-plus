<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;


$factory->define(User::class, function (Faker $faker) {
    $gender = $faker->randomElement(['male', 'female']);

    return [
        'name' => $faker->name($gender),
        'email' => $faker->unique()->safeEmail,
        'city' => $faker->city,
        'gender' => $gender,
        //'birthday' =>
        'interest' => $faker->randomElement(['male', 'female', 'both']),
        'about' => $faker->text,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});
