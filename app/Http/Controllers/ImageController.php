<?php

namespace App\Http\Controllers;

use App\Http\Resources\Item as ItemResource;
use Illuminate\Http\Request;

use App\Image;
use App\Item;


class ImageController extends Controller
{
    public function uploadImage()
    {
        $imageData = request()->validate([
            'image' => 'required',
        ]);

        $itemData = request()->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required',
            'category_id' => 'required'
        ]);

        $item = null;
        //$item = Item::find($itemData['item_id']);

        //Store or Update the post body in the posts table of database.
        if($item != null) {
            $item->update(['body'=> $itemData['body']]);
        } else {
            $item = request()->user()->items()->create($itemData);
        }

        //Store images in the database.
        $images = $imageData['image'];

        foreach ($images as $image) {
            //Create link to the storage and save the image there.
            $storedImage = $image->store('uploadedImages', 'public');

            //Crop image in respect of requested height and width in case the size of image is bigger than requested width and height.
            \Intervention\Image\Facades\Image::make($image)
                ->fit(750, 750)
                ->save(storage_path('app/public/uploadedImages/' . $image->hashName()));

            //Save the picture in the pictures table of database.
            Image::create([
                'path' => $storedImage,
                'item_id' => $item->id
            ]);
        }

        return (new ItemResource($item))->response()->setStatusCode(201);
    }
}
