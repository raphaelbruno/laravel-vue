<?php
namespace App\Library\Template\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Library\Template\Template
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
