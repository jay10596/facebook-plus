<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\CommentCollection;
use App\Http\Resources\Post as PostResource;
use App\Http\Requests\CommentRequest;
use App\Notifications\CommentNotification;

use App\Comment;
use App\Post;


class CommentController extends Controller
{
    public function index(Post $post)
    {
        return new PostResource($post);
    }

    public function store(CommentRequest $request, Post $post)
    {
        /*
            public function store(Post $post)
            {
                $data = request()->validate([
                    'body' => 'required',
                ]);

                $post->comments()->create(array_merge($data, ['user_id' => auth()->user()->id]));

                return new CommentCollection($post->comments);
            }
        */
        $request['user_id'] = auth()->user()->id;

        $comment = $post->comments()->create($request->all());

        //Send Notifications
        $user = $post->user;

        if($comment->user_id != $post->user_id) {
            $user->notify(new CommentNotification($comment));
        }

        return new CommentCollection($post->comments);
    }

    public function show(Comment $comment)
    {
        //
    }

    public function update(CommentRequest $request, Post $post, Comment $comment)
    {
        $comment->update($request->all());

        return new CommentCollection($post->comments);
    }

    public function destroy(Post $post, Comment $comment)
    {
        $comment->delete();

        return response('Deleted', 204);
    }
}
