<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Gate;

use App\Role;

class RoleController extends Controller
{
    protected $rulesCreate = [
        'title' => 'required|min:3',
        'name' => 'required|unique:roles|min:3',
        'level' => 'numeric|between:0,99',
        'default' => 'in:0,1'
    ];
    protected $rulesUpdate = [
        'title' => 'required|min:3',
        'name' => 'unique:roles|min:3',
        'level' => 'numeric|between:0,99'
    ];
    protected $names = [
        'title' => 'crud.title',
        'name' => 'crud.name',
        'level' => 'crud.level'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Gate::denies('roles-view'))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);
            
        $query = $request->get('q');
        $items = Role::where('title', 'ilike', "%{$query}%")
                    ->paginate();
        return view('admin.roles.list', compact('items', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Gate::denies('roles-create'))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);

        return view('admin.roles.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Gate::denies('roles-create'))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);

        $fields = $request->item;
        $validator = Validator::make($fields, $this->rulesCreate, [], $this->names);
        if ($validator->fails())
            return Redirect::back()->withErrors($validator)->withInput();

        try {
            Role::create($fields);

            return Redirect::route('admin:roles.index')
                ->with(['success' => trans('crud.successfully-added', [trans('admin.role')])]);
        } catch (\Exception $e) {
            return Redirect::back()
                ->withErrors([trans('crud.error-occurred') . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Gate::denies('roles-view'))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);

        $item = Role::find($id);
        if(!isset($item))
            return Redirect::back()->withErrors([trans('crud.item-not-found')]);
            
        return view('admin.roles.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Gate::denies('roles-update'))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);

        $item = Role::find($id);
        if(!isset($item))
            return Redirect::back()->withErrors([trans('crud.item-not-found')]);

        return view('admin.roles.form', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(Gate::denies('roles-update'))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);

        $item = Role::find($id);
            
        $fields = $request->item;
        if($item->name == $fields['name']) unset($fields['name']);

        $validator = Validator::make($fields, $this->rulesUpdate, [], $this->names);
        if ($validator->fails())
            return Redirect::back()->withErrors($validator)->withInput();
        
        try {
            $item->title = $fields['title'];
            if(isset($fields['name'])) $item->name = $fields['name'];
            $item->level = $fields['level'];
            $item->default = isset($fields['default']) ? true : false;
            $item->save();

            return Redirect::route('admin:roles.index')
                ->with(['success' => trans('crud.successfully-updated', [trans('admin.role')])]);
        } catch (\Exception $e) {
            return Redirect::back()
                ->withErrors([trans('crud.error-occurred') . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Gate::denies('roles-delete'))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);

        try {
            $item = Role::find($id);
            $item->delete();

            return Redirect::route('admin:roles.index')
                ->with(['success' => trans('crud.successfully-deleted', [trans('admin.role')])]);
        } catch (\Exception $e) {
            return Redirect::back()
                ->withErrors([trans('crud.error-occurred') . $e->getMessage()])
                ->withInput();
        }
    }
}
