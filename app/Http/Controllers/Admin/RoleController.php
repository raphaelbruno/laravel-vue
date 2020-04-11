<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Gate;
use DB;

use App\Role;
use App\Permission;

class RoleController extends Controller
{
    protected $rulesCreate = [
        'title' => 'required|min:3',
        'name' => 'required|unique:roles|min:3',
        'level' => 'nullable|numeric|between:0,99',
        'default' => 'in:0,1'
    ];
    protected $rulesUpdate = [
        'title' => 'required|min:3',
        'name' => 'unique:roles|min:3',
        'level' => 'nullable|numeric|between:0,99'
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
            'name' => trans('crud.name'),
            'level' => trans('crud.level')
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
        $items = Role::where(function ($query) use ($q) {
                $query->where('title', 'ilike', "%{$q}%")
                    ->orWhere('name', 'ilike', "%{$q}%");
            })
            ->orderBy('name')
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

        $subitems = Permission::orderBy('name')->get();
        return view('admin.roles.form', compact('subitems'));
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
        $subfields = isset($request->subitems) ? array_map('intval', $request->subitems) : [];
        $validator = Validator::make($fields, $this->rulesCreate, [], $this->names);
        if ($validator->fails())
            return Redirect::back()->withErrors($validator)->withInput();

        try {
            DB::transaction(function() use($fields, $subfields) {
                $item = Role::create($fields);
                
                foreach($subfields as $subitemId)
                    $item->permissions()->attach($subitemId);
                
                $item->save();
            });
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

        $subitems = Permission::orderBy('name')->get();
        return view('admin.roles.form', compact('item', 'subitems'));
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
        $subfields = array_map('intval', $request->subitems);
        if($item->name == $fields['name']) unset($fields['name']);

        $subitemsOnDatabase = $item->permissions->map(function($permission){ return $permission->id; })->toArray();
        $subitemsToDelete = array_diff($subitemsOnDatabase, $subfields);
        $subitemsToInsert = array_unique(array_diff($subfields, $subitemsOnDatabase));

        $validator = Validator::make($fields, $this->rulesUpdate, [], $this->names);
        if ($validator->fails())
            return Redirect::back()->withErrors($validator)->withInput();
        
        try {
            $item->title = $fields['title'];
            if(isset($fields['name'])) $item->name = $fields['name'];
            $item->level = $fields['level'];
            $item->default = isset($fields['default']) ? true : false;
            
            DB::transaction(function() use($item, $subitemsToDelete, $subitemsToInsert) {
                foreach($subitemsToDelete as $subitemId)
                    $item->permissions()->detach($subitemId);
                foreach($subitemsToInsert as $subitemId)
                    $item->permissions()->attach($subitemId);

                $item->save();
            });
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
