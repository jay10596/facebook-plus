<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FavouriteCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'favourite_count' => $this->count(),
            /*
                unlike LikeCollection and BookmarkCollection, here 'id' refers to id of the entry in DB rather than user_id.
                It is because we are using HasMany rather than ManyToMany. Thus, We have to use user_favourited in the CommentResource
            */
            //'user_favourited' => $this->collection->contains('id', auth()->user()->id),
            'links' => [
                'self' => '/posts',
            ],
        ];
    }
}
