<?php
namespace App\Library\Form\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Library\Form\Form
 */
class Form extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'form';
    }
}
