<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CrudController;
use Illuminate\Http\Request;

use App\Models\Permission;

class RoleController extends CrudController
{
    protected $model = \App\Models\Role::class;

    protected $rules = [
        'title' => 'required|min:3',
        'name' => 'required|min:2|unique:roles,name,NULL,id,deleted_at,NULL',
        'level' => 'nullable|numeric|between:0,99',
        'default' => 'sometimes|boolean',
    ];

    function __construct()
    {
        $this->names = [
            'title' => trans('crud.title'),
            'name' => trans('crud.name'),
            'level' => trans('admin.level'),
            'default' => trans('admin.default')
        ];

        $this->icon = 'id-card';
        $this->label = trans('admin.role');
        $this->title = trans('admin.roles');

        parent::__construct();
    }

    public function create()
    {
        $subitems = Permission::orderBy('title')->get();
        $this->addToView(compact('subitems'));
        return parent::create();
    }

    public function edit($id)
    {
        $subitems = Permission::orderBy('title')->get();
        $this->addToView(compact('subitems'));
        return parent::edit($id);
    }

    public function prepareValidationStore(Request $request)
    {
        $requestItem = $request->item;
        $requestItem['default'] = (Boolean) (isset($requestItem['default']) ? $requestItem['default'] : false);
        return $requestItem;
    }

    public function afterStore($item, $request)
    {
        return self::subitems($item, 'permissions', $request->subitems);
    }

    public function prepareValidationUpdate(Request $request, $item)
    {
        $requestItem = $request->item;
        $requestItem['default'] = (Boolean) (isset($requestItem['default']) ? $requestItem['default'] : false);

        $this->rules['name'] = "required|min:2|unique:roles,name,{$item->id},id,deleted_at,NULL";

        return $requestItem;
    }

    public function afterUpdate($item, $request)
    {
        return self::subitems($item, 'permissions', $request->subitems);
    }

}