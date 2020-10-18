<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['title', 'name'];
    
    public function roles()
    {
        return $this->belongsToMany(Role::class)
            ->whereNull('permission_role.deleted_at')
            ->withTimestamps();
    }

    public function rolesToString()
    {
        return $this->roles->implode('name', ', ');
    }

}
