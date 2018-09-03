<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    /**
     * Get the blog post for the images
     */
    public function post()
    {
        $this->hasOne(Post::class);
    }
}
