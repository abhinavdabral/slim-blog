<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Post;
use App\Models\Role;

class User extends Model {
    protected $table = 'users';
    protected $guarded = [];
    protected $permissions=[];

    public function __construct(array $attirbutes = array()){
        parent::__construct($attirbutes);
        $this->refreshPermissions();
    }

    public function posts(){
        return $this->hasMany('App\Models\Post');
    }

    public function roles(){
        return $this->belongsToMany('App\Models\Role', 'role_user');
    }    

    /** 
     * Because BelongsToManyThrough is too mainstream for this.
     * This function simply returns an array with all permissions
     * available to the user.
     * 
     * @return array() 
     */
     public function refreshPermissions(){
        $roles = $this->roles()->get();
        $permissions = [];
        foreach($roles as $role)
            $permissions = array_merge($permissions, $role->permissions->pluck('name')->toArray());
        
        $this->permissions = array_unique($permissions);
    }
    /**
     * This function returns the if the current role has
     * the specific permission or not.
     * 
     * @param \App\UserManagement\Permission $permission
     * @return boolean
     */
    public function hasPermission($permission)
    {
        $this->refreshPermissions();
        return in_array($permission, $this->permissions);
    }

    public function setPassword($password){
        $this->update([
            'password'  =>  password_hash($password, PASSWORD_DEFAULT)
        ]);
    }
}