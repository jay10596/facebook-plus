<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

use App\Bookmark;
use App\Item;
use App\User;


$factory->define(Bookmark::class, function (Faker $faker) {
    return [
        'item_id' => function() {
            return Item::all()->random();
        },
        'user_id' => function() {
            return User::all()->random();
        }
    ];
});
