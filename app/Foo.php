<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Foo extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['something', 'user_id'];
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
