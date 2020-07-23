<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Avatar extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'path' => $this->path,
            'width' => $this->width,
            'height' => $this->height,
            'type' => $this->type,
        ];
    }
}
