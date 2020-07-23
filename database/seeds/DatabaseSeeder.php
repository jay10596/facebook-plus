<?php

use Illuminate\Database\Seeder;

use App\User;
use App\Post;
use App\Comment;
use App\Like;
use App\Item;
use App\Category;
use App\Bookmark;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        factory(User::class, 5)->create();
        factory(Post::class, 10)->create();
        factory(Like::class, 5)->create();
        factory(Comment::class, 10)->create();
        factory(Category::class, 5)->create();
        factory(Item::class, 10)->create();
        factory(Bookmark::class, 5)->create();
    }
}
