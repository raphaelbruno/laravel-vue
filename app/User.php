<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
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
        'name', 'email', 'password',
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
        return $this->hasOne('App\Profile');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Role')->whereNull('role_user.deleted_at')->withTimestamps();
    }

    public function hasPermission($permission)
    {
        return $this->roles->filter(function($role) use($permission) { return $permission->roles->contains($role); })->count() > 0;
    }

    public function isSuperUser()
    {
        return $this->roles->filter(function($role){ return $role->level == 0; })->count() > 0;
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
}
