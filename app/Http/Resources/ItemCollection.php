<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ItemCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'item_count' => $this->count(),
            'links' => [
                'self' => '/posts',
            ],
        ];
    }
}
