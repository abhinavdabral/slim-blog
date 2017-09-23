<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Post;
use App\Models\Role;
use App\Models\Permission;
use Carbon\Carbon;


class User extends Model {

    // Database table associated with User Model
    protected $table = 'users';

    // Guarded attributes
    protected $guarded = [];

    // To store permissions of the user
    protected $permissions=[];

    protected $arr_roles=[];

    /**
     * Constructor for User model.
     * - Refreshes Permissions associated with the user.
     */
    public function __construct(array $attirbutes = array()){
        parent::__construct($attirbutes);
        $this->refreshPermissions();
    }

    /**
     * Relationship mapping for the posts
     * that the user have authored.
     * 
     * @return Eloquent Relation
     */
    public function posts(){
        return $this->hasMany('App\Models\Post');
    }

    /**
     * Relationship mapping for the roles
     * that the user have.
     * 
     * @return Eloquent Relation
     */
    public function roles(){
        return $this->belongsToMany('App\Models\Role', 'role_user');
    }    

    /** 
     * Because BelongsToManyThrough is too mainstream for this.
     * This function simply returns an array with all permissions
     * available to the user.
     */
     public function refreshPermissions(){
        $this->load('roles.permissions');
        $roles = $this->roles;
        $permissions = [];
        foreach($roles as $role)
            $permissions = array_merge($permissions, $role->permissions->pluck('name')->toArray());
        
        $this->permissions = array_unique($permissions);        
    }

    /** 
     * Because BelongsToManyThrough is too mainstream for this.
     * This function simply returns an array with all permissions
     * available to the user.
     */
     public function refreshRoles(){      
        $this->arr_roles = $this->roles->pluck('id')->toArray();
    }

    /**
     * This function returns the if the current role has
     * the specific permission or not.
     * 
     * @param String $permission
     * @return boolean
     */
    public function hasPermission($permission)
    {
        $this->refreshPermissions();
        return in_array($permission, $this->permissions);
    }

    public function getPermissions(){
        $this->refreshPermissions();
        return $this->permissions;
    }

    public function hasRole($role){
        $this->refreshRoles();   
        return in_array($role, $this->arr_roles);
    }


    public function setRoles($roles){
        $this->roles()->detach();
        $this->roles()->attach($roles);
        $this->save();
        return true;
    }

    public function detachAndDelete(){
        $this->roles()->detach();
        // Detach Posts
        $this->save();                
        $this->destroy($this->id);
        return true;
    }

    /**
     * This function sets the password for the current user.
     * 
     * @param String $password
     */
    public function setPassword($password){
        $this->update([
            'password'  =>  password_hash($password, PASSWORD_DEFAULT)
        ]);
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