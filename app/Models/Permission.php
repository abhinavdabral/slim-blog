<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Role;
use Carbon\Carbon;


class Permission extends Model {

    // Database table associated with Permission Model
    protected $table = 'permissions';

    // Guarded attributes
    protected $guarded = [];

    /**
     * Relationship mapping for the roles
     * that have this permission associated with them.
     * 
     * @return Eloquent Relation
     */
    public function roles(){
        return $this->belongsToMany('App\Models\Role');
    }

    /**
     * Mutator method that returns the Created At (TIMESTAMP)
     * in Carbon format.
     * 
     * @return Eloquent Relation
     */
     public function getCreatedAt(){
        return Carbon::parse($this->created_at)->diffForHumans();
    }

     /**
     * Mutator method that returns the Updated At (TIMESTAMP)
     * in Carbon format.
     * 
     * @return Eloquent Relation
     */
    public function getUpdatedAt(){
        return Carbon::parse($this->updated_at)->diffForHumans();
    }
}