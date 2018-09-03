<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StorePostRequest;
use Image;

class PostController extends Controller
{
    public function store(StorePostRequest $request)
    {
        dd($request);
    }
}
