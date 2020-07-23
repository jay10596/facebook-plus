<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

use App\Like;
use App\User;
use App\Post;

/*
	In blogSPA we didn't add post_id and used $post as RMB because we used hasMany relationship.
	It won't work here because we are using mant2many relationship in this project.
*/
$factory->define(Like::class, function (Faker $faker) {
    return [
        'post_id' => function() {
            return Post::all()->random();
        },
        'user_id' => function() {
            return User::all()->random();
        }
    ];
});
