<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

use App\Favourite;
use App\Comment;
use App\User;

$factory->define(Favourite::class, function (Faker $faker) {
    return [
        'type' => $faker->randomElement([1, 2, 3]),
        'Comment_id' => function() {
            return Comment::all()->random();
        },
        'user_id' => function() {
            return User::all()->random();
        }
    ];
});
