<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'identity', 'birthdate', 'avatar'];
    protected $dates = ['birthdate'];
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getMaskedIdentity()
    {
        return self::maskCPF($this->identity);
    }

    public function getPublicPath($path)
    {
        return str_replace('public/', 'storage/', $path);
    }

    public static function maskCPF($identity) {
        return isset($identity) ? preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", str_pad($identity, 11, "0", STR_PAD_LEFT)) : null;
    }
    
    public static function maskCNPJ($identity) {
        return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", str_pad($identity, 14, "0", STR_PAD_LEFT));
    }

    public static function clearMask($identity) {
        return preg_replace( '/[^0-9]/', '', $identity);
    }
}
