<?php
namespace App\Helpers;

class UtilHelper 
{    
   public static function money($value, $withCurrency = false)
    {
        return ($withCurrency ? 'R$ ' : '') . number_format($value, 2, ',', '.');
    }

    public static function maskCPF($identity)
    {
        return isset($identity) ? preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", str_pad($identity, 11, "0", STR_PAD_LEFT)) : null;
    }
    
    public static function maskCNPJ($identity)
    {
        return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", str_pad($identity, 14, "0", STR_PAD_LEFT));
    }

    public static function clearMask($identity)
    {
        return preg_replace( '/[^0-9]/', '', $identity);
    }
}