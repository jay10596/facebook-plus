<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User as UserResource;

use App\User;


class Comment extends JsonResource
{
    public function toArray($request)
    {
        //Operation For Tag
        $users = User::all();

        $newBody = null;
        $taggedUserID = null;
        $taggedUserName = null;

        foreach ($users as $user) {
            if (strpos($this->body, $user->name)) {
                $replace = str_replace($user->name, '{ReplaceMe}', $this->body);
                $newBody = explode("{ReplaceMe}", $replace);
                $taggedUserID = $user->id;
                $taggedUserName = $user->name;
                break;
            }
        }

        return [
            'id' => $this->id,
            'body' => $this->body,
            'gif' => $this->gif,
            'post_id' => $this->post_id,
            'updated_at' => $this->updated_at->diffForHumans(),

            'favourites' => new FavouriteCollection($this->favourites),
            'user_favourited' => !! $this->favourites->where('user_id', auth()->id())->count(),
            'favourited_type' => $this->favourites->where('user_id', auth()->id())->pluck('type'),

            'tag' => [
                'newBody' => $newBody,
                'taggedUserID'=> $taggedUserID,
                'taggedUserName'=> $taggedUserName,
            ],

            'commented_by' => new UserResource($this->user),

            'path' => '/posts/' . $this->post_id . '/comments/' . $this->id,
        ];
    }
}
