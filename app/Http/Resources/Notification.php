<?php

namespace App\Http\Resources;

use App\Http\Resources\User as UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class Notification extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => $this->data['user'],
            'content' => $this->data['content'], //Post, comment, like whatever there is.
            'message' => $this->data['message'],
            'type' => substr($this->type, 18),
            'created_at' => $this->created_at->diffForHumans()
        ];
    }
}
