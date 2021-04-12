<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;
    use HasApiTokens;

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

    protected $appends = ['me', 'first_name', 'last_name', 'short_name'];
    
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

    public function revokeTokens(){
        $this->tokens->each(function($token){
            $token->revoke();
        });
    }

    public function getMeAttribute(){ return Auth::user()->id === $this->id; }
    public function getFirstNameAttribute(){ return firstName($this->name); }
    public function getLastNameAttribute(){ return lastName($this->name); }
    public function getShortNameAttribute(){ return shortName($this->name); }

    public static function generatePassword($length = 8)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }
}
