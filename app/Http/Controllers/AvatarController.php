<?php

namespace App\Http\Controllers;

use App\Avatar;
use App\Http\Resources\Avatar as AvatarResource;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;

class AvatarController extends Controller
{
    public function uploadAvatar()
    {
        $data = request()->validate([
            'avatar' => 'required',
            'width' => '',
            'height' => '',
            'type' => '',
        ]);

        //Create link to the storage and save the image there.
        $avatar = $data['avatar']->store('uploadedAvatars', 'public');

        //Crop image ni respect of requested height and width in case the size of image is bigger than requested width and height.
        Image::make($data['avatar'])
            ->fit($data['width'], $data['height'])
            ->save(storage_path('app/public/uploadedAvatars/' . $data['avatar']->hashName()));

        //Save the image in the database.
        $userAvatar = auth()->user()->images()->create([
            'path' => $avatar,
            'width' => $data['width'],
            'height' => $data['height'],
            'type' => $data['type']
        ]);

        return new AvatarResource($userAvatar);
    }
}
