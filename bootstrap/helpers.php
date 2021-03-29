<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

if (! function_exists('getItemID')) {
    function getItemID()
    {
        if(!Route::getCurrentRoute()) return false;
        
        $params = Route::getCurrentRoute()->parameters();
        return !empty($params) ? array_values($params)[0] : false;
    }
}
    
function getCurrentResource()
{
    if(!Route::getCurrentRoute()) return null;
    
    $resource = null;
    if (preg_match('#(\w*:)*([-a-z]+).*#', Route::getCurrentRoute()->getName(), $matches))
        $resource = $matches[2];

    return $resource;
}

function filePath($url, $noImage = false)
{
    return isset($url) && !empty($url)
            ? (
                empty(parse_url($url)['scheme'])
                    ? asset(Storage::url($url))
                    : $url
                )
            : ($noImage ? asset(is_string($noImage) ? $noImage : 'img/noimage.jpg') : null);
}

function avatar($url)
{
    return filePath($url, 'img/avatar.jpg');
}

function isDarkMode($user = null)
{
    $user ??= Auth::user();
    return (isset($user->profile) && isset($user->profile->dark_mode)) 
        ? $user->profile->dark_mode
        : app('config')->get('template')['dark-mode'];
}

function money($value, $withCurrency = false)
{
    return ($withCurrency ? 'R$ ' : '') . number_format($value, 2, ',', '.');
}

function maskCPF($identity)
{
    return isset($identity) ? preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", str_pad($identity, 11, "0", STR_PAD_LEFT)) : null;
}

function maskCNPJ($identity)
{
    return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", str_pad($identity, 14, "0", STR_PAD_LEFT));
}

function clearMask($identity)
{
    return preg_replace( '/[^0-9]/', '', $identity);
}

function firstName($name)
{
    $names = explode(' ', trim($name));
    if(count($names) >= 1) return $names[0];
    return $name;
}

function lastName($name)
{
    $names = explode(' ', trim($name));
    if(count($names) >= 1) return $names[count($names)-1];
    return $name;
}

function shortName($name)
{
    $names = explode(' ', trim($name));
    if(count($names) >= 2)
        return $names[0] . ' ' . $names[count($names)-1];
    return $name;
}
