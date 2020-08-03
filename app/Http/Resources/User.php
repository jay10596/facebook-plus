<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Friend as FriendResource;
use App\Http\Resources\Avatar as AvatarResource;

use App\Friend;


class User extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'city' => $this->city,
            'gender' => $this->gender,
            //'birthday' => $this->birthday,
            'interest' => $this->interest,
            'about' => $this->about,


            'friendship' => new FriendResource(Friend::friendship($this->id)),

            'cover_image' => new AvatarResource($this->coverImage),

            'profile_image' => new AvatarResource($this->profileImage),

            'path' => $this->path
        ];
    }
}
