<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\Picture as PictureResource;
use App\Http\Resources\Post as PostResource;


class Post extends JsonResource
{
    public function toArray($request)
    {
        $sharedPost =  \App\Post::find($this->repost_id);

        return [
            'id' => $this->id,
            'body' => $this->body,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at->diffForHumans(),

            'comments' => new CommentCollection($this->comments),

            'likes' => new LikeCollection($this->likes),

            'pictures' => new PictureCollection($this->pictures),

            // PostResource inside PostResource
            'shared_post' => new PostResource($sharedPost),

            'posted_by' => new UserResource($this->user),

            'path' => $this->path,
        ];
    }
}
