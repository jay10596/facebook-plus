<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Favourite extends JsonResource
{
    public function toArray($request)
    {
        return [
            'type' => $this->type,
            'user_id' => $this->user_id,
            'comment_id' => $this->comment_id,
            'created_at' => $this->created_at->now()->diffForHumans(),
        ];
    }
}
