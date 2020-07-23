<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Bookmark extends JsonResource
{
    public function toArray($request)
    {
        return [
            'user_id' => $this->pivot->user_id,
            'item_id' => $this->pivot->item_id,
            'created_at' => $this->created_at->now()->diffForHumans(),
            'path' => '/items/' . $this->pivot->item_id,
        ];
    }
}
