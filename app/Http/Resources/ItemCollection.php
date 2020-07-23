<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ItemCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'bookmark_count' => $this->count(),
            //No need to write 'user_id', contains('id') checks the available id there is.
            'user_liked' => $this->collection->contains('id', auth()->user()->id),
            'links' => [
                'self' => '/posts',
            ],
        ];
    }
}
