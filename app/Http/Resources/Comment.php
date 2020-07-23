<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User as UserResource;


class Comment extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'gif' => $this->gif,
            'post_id' => $this->post_id,
            'updated_at' => $this->updated_at->diffForHumans(),

            'favourites' => new FavouriteCollection($this->favourites),
            'user_favourited' => !! $this->favourites->where('user_id', auth()->id())->count(),
            'favourited_type' => $this->favourites->where('user_id', auth()->id())->pluck('type'),

            'commented_by' => new UserResource($this->user),

            'path' => '/posts/' . $this->post_id . '/comments/' . $this->id,
        ];
    }
}
