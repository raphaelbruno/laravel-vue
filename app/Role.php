<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['title', 'name', 'level', 'default'];

    public function users()
    {
        return $this->belongsToMany('App\User')->whereNull('role_user.deleted_at')->withTimestamps();
    }

    public function permissions()
    {
        return $this->belongsToMany('App\Permission')->whereNull('permission_role.deleted_at')->withTimestamps();
    }
}
