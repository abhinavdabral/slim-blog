<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Carbon\Carbon;


class Role extends Model {
    protected $table = 'roles';
    protected $guarded = [];

    public function users(){
        return $this->belongsToMany('App\Models\User', 'role_user');
    }

    public function permissions(){
        return $this->belongsToMany('App\Models\Permission');
    }

    public function getCreatedAt(){
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    public function getUpdatedAt(){
        return Carbon::parse($this->updated_at)->diffForHumans();
    }
}