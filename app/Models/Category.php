<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

class Category extends Model {
    protected $table = 'categories';
    protected $guarded = [];

    public function posts(){
        return $this->hasMany('App\Models\Post');
    }
}