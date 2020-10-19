<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CrudController;

class PermissionController extends CrudController
{
    protected $model = \App\Models\Permission::class;
    
    protected $rules = [
        'title' => 'required|min:3',
        'name' => 'required|unique:permissions|min:3',
    ];
    
    function __construct()
    {
        $this->names = [
            'title' => trans('crud.title'),
            'name' => trans('crud.name')
        ];

        $this->icon = 'clipboard-list';
        $this->item = trans('admin.permission');
        $this->title = trans('admin.permissions');

        parent::__construct();
    }
}