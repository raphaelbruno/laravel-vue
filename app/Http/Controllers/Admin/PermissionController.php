<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CrudController;
use Illuminate\Http\Request;

class PermissionController extends CrudController
{
    protected $model = \App\Models\Permission::class;

    protected $rules = [
        'title' => 'required|min:3',
        'name' => 'required|min:2|unique:permissions,name,NULL,id,deleted_at,NULL',
    ];

    function __construct()
    {
        $this->names = [
            'title' => trans('crud.title'),
            'name' => trans('crud.name')
        ];

        $this->icon = 'clipboard-list';
        $this->label = trans('admin.permission');
        $this->title = trans('admin.permissions');

        parent::__construct();
    }

    public function prepareValidationUpdate(Request $request, $item)
    {
        $this->rules['name'] = "required|min:2|unique:permissions,name,{$item->id},id,deleted_at,NULL";
        return $request->item;
    }
}