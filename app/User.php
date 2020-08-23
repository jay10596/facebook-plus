<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Laravel\Scout\Searchable;

use App\Post;
use App\Comment;
use App\Friend;
use App\Avatar;
use App\Item;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'name', 'email', 'city', 'gender', 'birthday', 'interest', 'about', 'provider_id', 'provider_name', 'password',
    ];

    protected $dates = ['birthday'];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getPathAttribute()
    {
        return "/users/$this->id";
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function friends()
    {
        return $this->belongsToMany(User::class, 'friends', 'friend_id', 'user_id');
    }

    public function likes()
    {
        return $this->belongsToMany(Post::class, 'likes', 'user_id', 'post_id');
    }

    public function images()
    {
        return $this->hasMany(Avatar::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function bookmarks()
    {
        return $this->belongsToMany(Item::class, 'bookmarks', 'user_id', 'item_id');
    }

    public function coverImage()
    {
        return $this->hasOne(Avatar::class)
            ->orderByDesc('id')
            ->where('type', 'cover')
            ->withDefault(function ($image) {
                $image->path = 'uploadedAvatars/cover.jpg';
                $image->width = 1500;
                $image->height = 500;
                $image->type = 'cover';
            });
    }

    public function profileImage()
    {
        return $this->hasOne(Avatar::class)
            ->orderByDesc('id')
            ->where('type', 'profile')
            ->withDefault(function ($image) {
                $image->path = 'uploadedAvatars/profile.jpg';
                $image->width = 750;
                $image->height = 750;
                $image->type = 'profile';
            });
    }

    //While post or put request, the birthday would be a string of '27/05/2000'. It needs to be converted into Carbon date before saving into the database. That's why this inbuilt function is used.
    public function setBirthdayAttribute($birthday)
    {
        $this->attributes['birthday'] = Carbon::parse($birthday);
    }
}
