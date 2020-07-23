<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Post as PostResource;

use Auth;


class ShareController extends Controller
{
    public function sharePost(Request $request)
    {
        $post = Auth::user()->posts()->create([
            'body' => $request->body,
            'repost_id' => $request->repost_id,
            'user_id' => Auth::user()->id
        ]);

        return (new PostResource($post))->response()->setStatusCode(201);
    }
}
