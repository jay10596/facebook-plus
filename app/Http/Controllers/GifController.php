<?php

namespace App\Http\Controllers;

use App\Http\Resources\Comment as CommentResource;
use App\Http\Resources\CommentCollection;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

use App\Post;


class GifController extends Controller
{
    public function uploadGif()
    {
        $data = request()->validate([
            'body' => 'required',
            'gif' => 'required',
            'post_id' => 'required',
            // Passing Height and Width from the Dropzone params.
            'width' => '',
            'height' => '',
        ]);

        //Create link to the storage and save the image there.
        $gif = $data['gif']->store('uploadedGifs', 'public');

        //Crop image in respect of requested height and width in case the size of image is bigger than requested width and height.
        Image::make($data['gif'])
            ->fit($data['width'], $data['height'])
            ->save(storage_path('app/public/uploadedGifs/' . $data['gif']->hashName()));

        //Save the comment in the database.
        $comment = auth()->user()->comments()->create([
            'body' => $data['body'],
            'gif' => $gif,
            'post_id' => $data['post_id'],
        ]);

        return new CommentResource($comment);
    }
}
