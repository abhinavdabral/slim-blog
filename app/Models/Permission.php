<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Role;
use Carbon\Carbon;


class Permission extends Model {
    protected $table = 'permissions';
    protected $guarded = [];

    public function roles(){
        return $this->belongsToMany('App\Models\Role');
    }

    public function getCreatedAt(){
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    public function getUpdatedAt(){
        return Carbon::parse($this->updated_at)->diffForHumans();
    }
}