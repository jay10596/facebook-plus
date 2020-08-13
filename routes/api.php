<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Every route in this file MUST have an api prefix.

//AUTH
Route::post('/login', 'AuthController@login');
Route::post('/register', 'AuthController@register');
Route::post('/me', 'AuthController@me');
Route::post('/logout', 'AuthController@logout');
//auth middleware in Constructor of AuthController is added. If not, I need to put me and logout routes inside the Route::middleware('auth:api') group

//SOCIALITE
Route::get('/login/{provider}', 'AuthController@redirectToProvider');
Route::get('/login/{provider}/callback', 'AuthController@handleProviderCallback');

Route::middleware('auth:api')->group(function () {
    //CRUD
    Route::apiResource('/posts', 'PostController');
    Route::apiResource('/users', 'UserController');
    Route::apiResource('/posts/{post}/comments', 'CommentController');
    Route::apiResource('/items', 'ItemController');
    Route::apiResource('/categories', 'CategoryController');

    //FRIEND REQUEST
    Route::post('/send-request', 'FriendController@sendRequest');
    Route::post('/confirm-request', 'FriendController@confirmRequest');
    Route::post('/delete-request', 'FriendController@deleteRequest');

    //LIKE, FAVOURITE, BOOKMARK
    Route::post('/posts/{post}/like-dislike', 'LikeController@likeDislike');
    Route::post('/posts/{post}/comments/{comment}/favourite-unfavourite', 'FavouriteController@favouriteUnfavourite');
    Route::post('/items/{item}/bookmark-unbookmark', 'BookmarkController@bookmarkUnbookmark');

    //IMAGE
    Route::post('/upload-avatars', 'AvatarController@uploadAvatar');
    Route::post('/upload-pictures', 'PictureController@uploadPicture');
    Route::post('/upload-images', 'ImageController@uploadImage');
    Route::post('/upload-gif', 'GifController@uploadGif');

    //SHARE
    Route::post('/share-post', 'ShareController@sharePost');

    //FEATURES
    Route::post('/filter-birthdays', 'FeatureController@filterBirthdays');
    Route::post('/wish-birthday', 'FeatureController@wishBirthday');
});
