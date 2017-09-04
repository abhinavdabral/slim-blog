<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Post;
use Carbon\Carbon;

class Category extends Model {
    protected $table = 'categories';
    protected $guarded = [];

    public function posts(){
        return $this->hasMany('App\Models\Post');
    }

    public function getCreatedAt(){
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    public function getUpdatedAt(){
        return Carbon::parse($this->updated_at)->diffForHumans();
    }
}