<?php

namespace App\Http\Controllers;

use App\Http\Resources\FavouriteCollection;
use Illuminate\Http\Request;

use App\Comment;
use App\Favourite;
use App\Post;


class FavouriteController extends Controller
{
    /*
        Unlike Likes and Bookmarks, Here we can not us Toogle because it is for ManyToMany and takes only one parameter(user_id).
        Here, we have to get the type as well with user_id, which is why we used HasMany and created the entry in the DB manually.
    */
    public function favouriteUnfavourite(Post $post, Comment $comment, Request $request)
    {
        if (Favourite::where('comment_id', $comment->id)->where('user_id', auth()->id())->exists()) {
            $comment->favourites()->where('user_id', auth()->id())->first()->delete();
        } else {
            $comment->favourites()->create([
                'type' => $request->type,
                'user_id' => auth()->id(),
            ]);
        }

        return new FavouriteCollection($comment->favourites);
    }
}
