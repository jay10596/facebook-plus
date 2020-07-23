<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Like extends JsonResource
{
    public function toArray($request)
    {
        return [
            'user_id' => $this->pivot->user_id,
            'post_id' => $this->pivot->post_id,
            'created_at' => $this->created_at->now()->diffForHumans(),
            'path' => '/posts/' . $this->pivot->post_id
        ];
    }
}
