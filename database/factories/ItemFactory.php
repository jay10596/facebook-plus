<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

use App\Item;
use App\User;
use App\Category;


$factory->define(Item::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'description' => $faker->text,
        'price' => $faker->numberBetween(1, 500),
        'category_id' => function() {
            return Category::all()->random();
        },
        'user_id' => function() {
            return User::all()->random();
        }
    ];
});
