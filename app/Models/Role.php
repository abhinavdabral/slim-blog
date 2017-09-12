<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Role extends Model {
    protected $table = 'roles';
    protected $guarded = [];

    public function users(){
        return $this->belongsToMany('App\Model\User');
    }
    public function permissions(){
        return $this->belongsToMany('App\Model\Permission');
    }
}