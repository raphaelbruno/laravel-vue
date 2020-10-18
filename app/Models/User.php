<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'google_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class)
            ->whereNull('role_user.deleted_at')
            ->orderBy('level')
            ->orderBy('name')
            ->withTimestamps();
    }

    public function hasPermission($permission)
    {
        $permissionName = $permission;
        if($permission instanceof Permission) $permissionName = $permission->name;

        return $this->roles->filter(function($role) use($permissionName) {
            return $role->permissions->contains('name', $permissionName);
        })->count() > 0;
    }

    public function rolesToString()
    {
        return $this->roles->implode('title', ', ');
    }

    public function isSuperUser()
    {
        return $this->roles->filter(function($role){
            return $role->level === 0;
        })->count() > 0;
    }
    
    public function getHighestLevel(){
        return $this->roles->max('level');
    }

    public function firstName()
    {
        $names = explode(' ', trim($this->name));
        if(count($names) >= 1) return $names[0];
        return $this->name;
    }

    public function lastName()
    {
        $names = explode(' ', trim($this->name));
        if(count($names) >= 1) return $names[count($names)-1];
        return $this->name;
    }

    public function shortName()
    {
        $names = explode(' ', trim($this->name));
        if(count($names) >= 2)
            return $names[0] . ' ' . $names[count($names)-1];
        return $this->name;
    }

    public static function generatePassword($length = 8)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }
}