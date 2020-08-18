<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Post as PostResource;
use App\Notifications\ShareNotification;

use Auth;
use App\Post;


class ShareController extends Controller
{
    public function sharePost(Request $request)
    {
        $post = Auth::user()->posts()->create([
            'body' => $request->body,
            'repost_id' => $request->repost_id,
            'user_id' => Auth::user()->id
        ]);

        //Send Notifications
        $shared_post = Post::find($post->repost_id);

        $user = $shared_post->user;

        if($post->user_id != $shared_post->user_id) {
            $user->notify(new ShareNotification($post));
        }

        return (new PostResource($post))->response()->setStatusCode(201);
    }
}
