<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StorePostRequest;
use Image;
use App\Post;

class PostController extends Controller
{
    const FOLDER_PATH = 'cover_images';
    const DEFAULT_IMG_WIDTH = 1024;
    const DEFAULT_IMG_HEIGHT = 768;

    public function store(StorePostRequest $request)
    {
        $cover_images = $request->file('cover_images');
        $paths = [];

        if (is_array($cover_images)) {
            foreach ($cover_images as $cover_image) {
                $filename = "cover_image-" . uniqid(time()) . ".{$cover_image->getClientOriginalExtension()}";

                $imgResize = Image::make($cover_image)->fit(self::DEFAULT_IMG_WIDTH, self::DEFAULT_IMG_HEIGHT, function ($constraint) {
                    //keep the maximal original image size
                    $constraint->upsize();
                });

                //save in storage/app...
                $path = self::FOLDER_PATH . '/' . $filename;
                $imgResize->save(storage_path("app/{$path}"));

                $paths[] = new \App\Image(['name' => $path]);
            }
        }
        
        //create new post
        $post = new Post();
        $post->description = $request->message;
        
        //attach images to the post
        if ($post->save()) {
            if (count($paths) > 0) {
                $post->images()->saveMany($paths);
            }
        }

        return back()->with('status', 'Votre annonce a été publiée !');
    }
}
