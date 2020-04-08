<?php
namespace App\Helpers;

class TemplateHelper 
{
    
    public static function getItemID(){
        $params = \Route::getCurrentRoute()->parameters();
        return !empty($params) ? array_values($params)[0] : false;
    }
    public static function getCurrentResource(){
        $resource = null;
        if (preg_match('#(\w*::)*(\w+).*#', \Route::getCurrentRoute()->getName(), $matches))
            $resource = $matches[2];
        return $resource;
    }
    
    public static function isMenuActive($item){
        if(!empty($item))
        {
            // Same Action
            if(isset($item['action']) && $item['action'] == \Route::getCurrentRoute()->getName())
                return true;

            // Same Resource
            if(isset($item['resource']) && $item['resource'] == self::getCurrentResource())
                return true;
            
            // Has Ative Subitem
            if(isset($item['children']))
                foreach($item['children'] as $child)
                    if(isset($child['action']) && $child['action'] == \Route::getCurrentRoute()->getName())
                        return true;
        }
        return false;
    }
}