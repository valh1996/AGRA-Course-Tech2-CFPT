<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['image'];

    /**
     * Get the blog post for the images
     */
    public function post()
    {
        $this->hasOne(Post::class);
    }
}
