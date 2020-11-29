<?php
namespace App\Libraries\Template\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Libraries\Template\Template
 */
class Template extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'template';
    }
}
