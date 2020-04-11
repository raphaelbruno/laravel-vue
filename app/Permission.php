<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['title', 'name'];
    
    public function roles()
    {
        return $this->belongsToMany('App\Role')
            ->whereNull('permission_role.deleted_at')
            ->withTimestamps();
    }
}
