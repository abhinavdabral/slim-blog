<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Carbon\Carbon;

class Post extends Model {

    // Database table associated with Post Model
    protected $table = 'posts';

    // Guarded attributes
    protected $guarded = [];

    /**
     * Relationship mapping for the user
     * that authored this post.
     * 
     * @return Eloquent Relation
     */
    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Relationship mapping for the Category
     * that this post comes under.
     * 
     * @return Eloquent Relation
     */
    public function category(){
        return $this->belongsTo('App\Models\Category');
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