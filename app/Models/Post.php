<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Carbon\Carbon;

class Post extends Model {
    protected $table = 'posts';
    protected $guarded = [];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function category(){
        return $this->belongsTo('App\Models\Category');
    }

    public function getCreatedAt(){
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    public function getUpdatedAt(){
        return Carbon::parse($this->updated_at)->diffForHumans();
    }

}