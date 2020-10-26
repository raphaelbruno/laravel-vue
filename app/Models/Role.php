<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['title', 'name', 'level', 'default'];
    protected $with = ['permissions'];

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->whereNull('role_user.deleted_at')
            ->orderBy('name')
            ->withTimestamps();
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class)
            ->whereNull('permission_role.deleted_at')
            ->orderBy('name')
            ->withTimestamps();
    }

    public function permissionsToString()
    {
        return $this->permissions->implode('name', ', ');
    }
}
