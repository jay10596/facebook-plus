<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Friend as FriendResource;
use App\Http\Resources\Avatar as AvatarResource;

use App\Friend;
use Carbon\Carbon;


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
            'birthday' => [
                'when' => $this->birthday->format('d') - Carbon::now()->format('d'),
                'age' => Carbon::now()->format('Y') - $this->birthday->format('Y'), //'y' displays 20 and 'Y' displays 2020
                'day_name' => $this->birthday->format('l'),
                'day' => $this->birthday->day,
                'month' => $this->birthday->month,
                'year' => $this->birthday->year,
            ],
            'interest' => $this->interest,
            'about' => $this->about,

            'friendship' => new FriendResource(Friend::friendship($this->id)),

            'cover_image' => new AvatarResource($this->coverImage),

            'profile_image' => new AvatarResource($this->profileImage),

            'path' => $this->path
        ];
    }
}
