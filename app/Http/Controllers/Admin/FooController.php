<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CrudController;

class FooController extends CrudController
{
    protected $model = \App\Models\Foo::class;
    protected $onlyMine = true;

    protected $rules = [
        'title' => 'required|min:3',
    ];

    function __construct()
    {
        $this->names = [
            'title' => trans('crud.title'),
        ];

        $this->icon = 'file';
        $this->label = trans('Foo'); // Create a new file at "resources/lang/" to translate
        $this->title = trans('Foos'); // Create a new file at "resources/lang/" to translate

        parent::__construct();
    }
}
