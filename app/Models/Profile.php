<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'identity', 'birthdate', 'avatar', 'dark_mode'];
    protected $dates = ['birthdate'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getMaskedIdentity()
    {
        return maskCPF($this->identity);
    }
}
