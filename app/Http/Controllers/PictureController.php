<?php

namespace App\Http\Controllers;

use App\Http\Resources\Picture as PictureResource;
use App\Http\Resources\Post as PostResource;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

use App\Picture;
use App\Post;


class PictureController extends Controller
{
    public function uploadPicture()
    {
        $pictureData = request()->validate([
            'picture' => 'required',
        ]);

        $postData = request()->validate([
            'post_id' => 'required',
            'body' => 'required'
        ]);

        $post = null;
        $post = Post::find($postData['post_id']);

        //Store or Update the post body in the posts table of database.
        if($post != null) {
            $post->update(['body'=> $postData['body']]);
        } else {
            $post = request()->user()->posts()->create($postData);
        }

        //Store pictures in database.
        $pictures = $pictureData['picture'];

        foreach ($pictures as $picture) {
            //Create link to the storage and save the image there.
            $storedPicture = $picture->store('uploadedPictures', 'public');

            //Crop image in respect of requested height and width in case the size of image is bigger than requested width and height.
            Image::make($picture)
                ->fit(750, 750)
                ->save(storage_path('app/public/uploadedPictures/' . $picture->hashName()));

            //Save the picture in the pictures table of database.
            Picture::create([
                'path' => $storedPicture,
                'post_id' => $post->id
            ]);
        }

        return (new PostResource($post))->response()->setStatusCode(201);
    }
}
