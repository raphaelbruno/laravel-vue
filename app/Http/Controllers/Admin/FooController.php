<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CrudController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FooController extends CrudController
{
    protected $model = \App\Foo::class;
    protected $onlyMine = true;
    protected $names;
    protected $item;
    protected $title;
    
    protected $rules = [
        'title' => 'required|min:3'
    ];
    
    function __construct()
    {
        $this->names = [
            'title' => trans('crud.title')
        ];

        $this->item = trans('Foo'); // Create a new file at "resources/lang/" to translate
        $this->title = trans('Foos'); // Create a new file at "resources/lang/" to translate

        parent::__construct();
    }
}