<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BookmarkCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'bookmark_count' => $this->count(),
            'user_bookmarked' => $this->collection->contains('id', auth()->user()->id),
            'links' => [
                'self' => '/items',
            ],
        ];
    }
}
