<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\Category as CategoryResource;


class Item extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'category_id' => $this->category_id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at->diffForHumans(),

            'images' => new ImageCollection($this->images),

            'bookmarks' => new BookmarkCollection($this->bookmarks),

            //'replies' => new CommentCollection($this->comments),

            'category' =>new CategoryResource($this->category),

            'posted_by' => new UserResource($this->user),

            'path' => '/items'
        ];
    }
}
