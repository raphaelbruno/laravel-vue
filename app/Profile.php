<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Profile extends Model
{
    use SoftDeletes;

    protected $fillable = ['identity', 'birthdate'];
    protected $dates = ['birthdate'];
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getMaskedIdentity()
    {
        return $this->maskCPF($this->identity);
    }

    function maskCPF($identity) {
        return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", str_pad($identity, 11, "0", STR_PAD_LEFT));
    }
    
    function maskCNPJ($identity) {
        return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", str_pad($identity, 14, "0", STR_PAD_LEFT));
    }
}
