<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StorePostRequest;
use Image;
use App\Post;

class PostController extends Controller
{
    const FOLDER_PATH = 'cover_images';

    public function store(StorePostRequest $request)
    {
        $cover_images = $request->file('cover_images');
        $paths = [];

        if (is_array($cover_images)) {
            foreach ($cover_images as $cover_image) {
                $filename = "cover_image-" . uniqid(time()) . ".{$cover_image->getClientOriginalExtension()}";
                $paths[] = new \App\Image(['name' => $cover_image->storeAs(self::FOLDER_PATH, $filename)]);
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
