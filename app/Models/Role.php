<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Carbon\Carbon;


class Role extends Model {

    // Database table associated with Role Model
    protected $table = 'roles';

    // Guarded attributes
    protected $guarded = [];

    // To store permissions associated role
    protected $perms=[];
    
    /**
        * Constructor for Role model.
        * - Refreshes Permissions associated with the user.
        */
    public function __construct(array $attirbutes = array()){
        parent::__construct($attirbutes);
        $this->refreshPermissions();
    }

    /** 
     * Because BelongsToManyThrough is too mainstream for this.
     * This function simply returns an array with all permissions
     * available to the user.
     */
    public function refreshPermissions(){      
        $this->perms = $this->permissions->pluck('id')->toArray();
    }

    
    /**
     * Relationship mapping for the users
     * that have this role assigned to them.
     * 
     * @return Eloquent Relation
     */
    public function users(){
        return $this->belongsToMany('App\Models\User', 'role_user');
    }

    /**
     * Relationship mapping for the permissions
     * that are allowed by this role.
     * 
     * @return Eloquent Relation
     */
    public function permissions(){
        return $this->belongsToMany('App\Models\Permission');
    }

    public function hasPermission($permission){
        $this->refreshPermissions();   
        return in_array($permission, $this->perms);
    }

    public function setPermissions($permissions){
        $this->permissions()->detach();
        $this->permissions()->attach($permissions);
        $this->save();
        return true;
    }

    public function detachAndDelete(){
        $this->permissions()->detach();
        $this->save();                
        $this->destroy($this->id);
        return true;
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