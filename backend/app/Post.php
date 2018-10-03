<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * Get the images for the blog post.
     */
    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
