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

        foreach ($cover_images as $cover_image) {
            $filename = "cover_image-" . uniqid(time()) . ".{$cover_image->getClientOriginalExtension()}";
            // save $filename to FOLDER_PATH (constant)
            $paths[] = new \App\Image(['image' => $cover_image->storeAs(self::FOLDER_PATH, $filename)]);
        }
        
        $post = new Post();
        $post->message = $request->message;
        $post->images()->save($paths);
    }
}
