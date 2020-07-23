<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

use App\Comment;
use App\Post;
use App\User;


$factory->define(Comment::class, function (Faker $faker) {
    return [
        'body' => $faker->text,
        'gif' => null,
        'post_id' => function() {
            return Post::all()->random();
        },
        'user_id' => function() {
            return User::all()->random();
        }
    ];
});
