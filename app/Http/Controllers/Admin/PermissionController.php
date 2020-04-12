<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Gate;

use App\Permission;

class PermissionController extends Controller
{
    protected $rules = [
        'title' => 'required|min:3',
        'name' => 'required|unique:permissions|min:3'
    ];
    protected $names;

    /**
     * Create a new instance.
     *
     * @return void
     */
    function __construct()
    {
        $this->names = [
            'title' => trans('crud.title'),
            'name' => trans('crud.name')
        ];

        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Gate::denies('roles-view'))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);
            
        $q = $request->get('q');
        $items = Permission::where(function ($query) use ($q) {
                $query->where('title', 'ilike', "%{$q}%")
                    ->orWhere('name', 'ilike', "%{$q}%");
            })
            ->orderBy('name')
            ->paginate();
        
        return view('admin.permissions.list', compact('items', 'request'));
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

        return view('admin.permissions.form');
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
        $validator = Validator::make($fields, $this->rules, [], $this->names);
        if ($validator->fails())
            return Redirect::back()->withErrors($validator)->withInput();

        try {
            $item = Permission::create($fields);
            
            return Redirect::route('admin:permissions.index')
                ->with(['success' => trans('crud.successfully-added', [trans('admin.permission')])]);
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

        $item = Permission::find($id);
        if(!isset($item))
            return Redirect::back()->withErrors([trans('crud.item-not-found')]);
            
        return view('admin.permissions.show', compact('item'));
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

        $item = Permission::find($id);
        if(!isset($item))
            return Redirect::back()->withErrors([trans('crud.item-not-found')]);

        return view('admin.permissions.form', compact('item'));
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
        
        $item = Permission::find($id);

        $fields = $request->item;
        if($item->name == $fields['name']) unset($fields['name']);

        $validator = Validator::make($fields, $this->rules, [], $this->names);
        if ($validator->fails())
            return Redirect::back()->withErrors($validator)->withInput();
        
        try {
            $item->title = $fields['title'];
            if(isset($fields['name'])) $item->name = $fields['name'];
            
            $item->save();

            return Redirect::route('admin:permissions.index')
                ->with(['success' => trans('crud.successfully-updated', [trans('admin.permission')])]);
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
            $item = Permission::find($id);
            $item->delete();

            return Redirect::route('admin:permissions.index')
                ->with(['success' => trans('crud.successfully-deleted', [trans('admin.permission')])]);
        } catch (\Exception $e) {
            return Redirect::back()
                ->withErrors([trans('crud.error-occurred') . $e->getMessage()])
                ->withInput();
        }
    }
}
