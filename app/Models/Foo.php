<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Foo extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['title', 'user_id'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
