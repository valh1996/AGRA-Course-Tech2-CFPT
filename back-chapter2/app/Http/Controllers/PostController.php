<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StorePostRequest;
use Image;
use App\Post;
use DB;
use Storage;

class PostController extends Controller
{
    const FOLDER_PATH = 'cover_images';
    const DEFAULT_IMG_WIDTH = 1024;
    const DEFAULT_IMG_HEIGHT = 768;

    /**
     * show all post
     * 
     * @return Response 
     */
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->paginate(3);

        return view('home', ['posts' => $posts]);
    }

    /**
     * Store a new post
     * 
     * @param App\Http\Requests\StorePostRequest $request
     * 
     * @return Response 
     */
    public function store(StorePostRequest $request)
    {
        DB::transaction(function () use ($request) {
            
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
                    
                    try {
                        $imgResize->save(storage_path("app/public/{$path}"));
                        $paths[] = new \App\Image(['name' => $path]);
                    } catch (Exception $e) {
                        //upload failed... image isn't saved
                    }
                }
            }
            
            //create new post
            $post = new Post();
            $post->description = $request->message;
            
            //attach images to the post
            if ($post->save()) {
                if (count($paths) > 0) {
                    if (!$post->images()->saveMany($paths)) {
                        $deleteImg = [];

                        foreach ($paths as $image) {
                            $deleteImg[] = "public/{$image->name}";
                        }

                        Storage::delete($deleteImg);
                    }
                }
            }
        }, 1);

        return back()->with('status', 'Votre annonce a été publiée !');
    }

    /**
     * Delete a given post
     * 
     * @param int $id   Post Identifier
     * 
     * @return Response 
     */
    public function delete($id)
    {
        $tmpPaths = [];

        try {
            DB::beginTransaction();

            $post = Post::find($id);

            foreach ($post->images as $image) {
                $path = "public/{$image->name}";
                $tmpPath = "{$image->name}";
                $tmpPaths[] = $tmpPath;
                Storage::move($path, "delete/{$tmpPath}");
            }

            if (count($post->images()->delete()) > 0) {
                $postDeleted = $post->images()->delete();
            }

            if (!$post->delete() || $postDeleted) {
                throw new \Exception('Unable to delete images from database!');
            }

            DB::commit();

            foreach ($tmpPaths as $tmp) {
                Storage::delete("delete/{$tmp}");
            }

        } catch (\Exception $e) {
            DB::rollBack();

            foreach ($tmpPaths as $tmp) {
                $currentFile = "public/{$tmp}";
                if (!Storage::exists($currentFile)) {
                    Storage::move("delete/$tmp", $currentFile);
                }
            }

            return back()->with('error_messages', 'Des erreurs sont survenues durant la publication !');

        }

        return back()->with('status', 'L\'annonce a été supprimée !');
    }

}
