<?php
namespace App\Library\Template;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

class Template 
{
    
    function displayMenu($item)
    {
        if(!isset($item['permission'])) return true;
        else
        {
            $permissions = is_array($item['permission']) ? $item['permission'] : [$item['permission']];
            foreach($permissions as $permission)
                if(Gate::check($permission)) return true;
        }

        return false;
    }
    
    function isMenuActive($item)
    {
        if(!empty($item) && Route::getCurrentRoute())
        {
            // Same Action
            if(isset($item['action']) && $item['action'] == Route::getCurrentRoute()->getName())
                return true;

            // Same Resource
            if(isset($item['resource']) && in_array(getCurrentResource(), is_array($item['resource']) ? $item['resource'] : [$item['resource']]))
                return true;
            
            // Has Ative Subitem
            if(isset($item['children']))
                foreach($item['children'] as $child)
                    if(isset($child['action']) && $child['action'] == Route::getCurrentRoute()->getName())
                        return true;
        }
        return false;
    }

}