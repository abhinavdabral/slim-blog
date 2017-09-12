<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

class User extends Model {
    protected $table = 'users';
    protected $guarded = [];

    public function posts(){
        return $this->hasMany('App\Models\Post');
    }

    public function roles(){
        return $this->belongsToMany('App\Models\Role');
    }    

    public function setPassword($password){
        $this->update([
            'password'  =>  password_hash($password, PASSWORD_DEFAULT)
        ]);
    }
}