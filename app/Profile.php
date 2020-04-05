<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Profile extends Model
{
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getBeautyIdentity()
    {
        return $this->maskCPF($this->identity);
    }

    public function getBeautyBirthdate()
    {
        $birthdate = new Carbon($this->birthdate);
        return $birthdate->format('d/m/Y');
    }
    
    function maskCPF($identity) {
        return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", str_pad($identity, 11, "0", STR_PAD_LEFT));
    }
    
    function maskCNPJ($identity) {
        return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", str_pad($identity, 14, "0", STR_PAD_LEFT));
    }
}
