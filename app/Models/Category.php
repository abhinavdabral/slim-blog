<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Post;
use Carbon\Carbon;

class Category extends Model {

    // Database table associated with Category Model
    protected $table = 'categories';

    // Guarded attributes
    protected $guarded = [];

    /**
     * Relationship mapping for the posts
     * that come under this category.
     * 
     * @return Eloquent Relation
     */
    public function posts(){
        return $this->hasMany('App\Models\Post');
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