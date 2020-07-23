<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Category;
use App\Image;


class Item extends Model
{
    protected $fillable = ['title', 'description', 'price', 'category_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function bookmarks()
    {
        return $this->belongsToMany(User::class, 'bookmarks', 'item_id', 'user_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
