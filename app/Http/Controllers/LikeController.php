<?php

namespace App\Http\Controllers;

use App\Http\Resources\LikeCollection;
use App\Notifications\LikeNotification;
use Illuminate\Http\Request;

use App\Post;
use App\Like;


class LikeController extends Controller
{
    public function likeDislike(Post $post)
    {
        //Create like
        $post->likes()->toggle(auth()->user());

        //Sends notification
        $like = Like::orderby('created_at', 'desc')->first();

        $user = $post->user;

        if($like->user_id != $post->user_id) {
            $user->notify(new LikeNotification($post));
        }

        return new LikeCollection($post->likes);
    }
}
