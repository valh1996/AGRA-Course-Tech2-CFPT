<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Image;
use App\Post;
use App\Image as ImageModel;
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
     * edit one post by id
     * 
     * @param int $id Post Identifier 
     * 
     * @return Response 
     */
    public function edit($id)
    {
        $post = Post::find($id);

        return view('edit', ['post' => $post]);
    }

    /**
     * update post by id
     * 
     * @param int $id Post Identifier 
     * 
     * @return Response 
     */
    public function update($id, UpdatePostRequest $request)
    {
        DB::transaction(function () use ($request, $id) {

            $currentImg = $request->input('current_cover_images');
            $newImg = $request->file('new_cover_images');
            $oldImg = ImageModel::where('post_id', $id)->get();

            //remove selected images who are already uploaded
            foreach ($oldImg as $img) {
                if (!in_array($img->id, $currentImg)) {
                    //try to delete image from database, if error cancel storage delete
                    try {
                        ImageModel::where('id', $img->id)->delete();
                    } catch (\Exception $e) {
                        throw new \Exception('Unable to delete image from database!');
                    }

                    //try to delete from storage, rollback database if error
                    try {
                        Storage::delete("public/{$img->name}");
                    } catch (\Exception $e) {
                        throw new \Exception('Unable to delete image from link!');
                    }
                }
            }

            $paths = [];

            //upload new image
            if (is_array($newImg)) {
                foreach ($newImg as $newImg) {
                    $filename = "cover_image-" . uniqid(time()) . ".{$newImg->getClientOriginalExtension()}";

                    $imgResize = Image::make($newImg)->fit(self::DEFAULT_IMG_WIDTH, self::DEFAULT_IMG_HEIGHT, function ($constraint) {
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
            $post = Post::find($id);
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

        return redirect('/')->with('status', 'L\'annonce a été modifiée avec succès !');
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
