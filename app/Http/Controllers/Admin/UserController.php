<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use DB;

use App\User;
use App\Profile;
use App\Role;

class UserController extends Controller
{
    protected $rulesCreate = [
        'name' => 'required|min:3',
        'email' => 'required|unique:users|email'
    ];
    protected $rulesUpdate = [
        'name' => 'required|min:3',
        'email' => 'unique:users|email'
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
            'name' => trans('crud.name'),
            'email' => trans('crud.email')
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
        if(Gate::denies('users-view'))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);
            
        $q = $request->get('q');
        $items = User::where(function ($query) use ($q) {
                $query->where('name', 'ilike', "%{$q}%")
                    ->orWhere('email', 'ilike', "%{$q}%");
            })
            ->orderBy('name')
            ->paginate();
        
        return view('admin.users.list', compact('items', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Gate::denies('users-create'))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);

        $subitems = Role::orderBy('title')->get();
        return view('admin.users.form', compact('subitems'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Gate::denies('users-create'))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);

        $fields = $request->item;
        $fields['profile'] = $request->profile;
        $subfields = isset($request->subitems) ? array_map('intval', $request->subitems) : [];
        $validator = Validator::make($fields, $this->rulesCreate, [], $this->names);
        if ($validator->fails())
            return Redirect::back()->withErrors($validator)->withInput();

        if(!isset($fields['password']))
            $fields['password'] = $fields['confirm-password'] = User::generatePassword();
        
        if(empty($fields['confirm-password']) || $fields['password'] != $fields['confirm-password'])
        {
            return Redirect::back()
                ->withErrors([trans('auth.please-confirm-password')])
                ->withInput();
        }

        try {
            DB::transaction(function() use($fields, $subfields) {
                $fields['password'] = Hash::make($fields['password']);
                $item = User::create($fields);
                
                $fields['profile']['identity'] = isset($fields['profile']['identity']) ? Profile::clearMask($fields['profile']['identity']) : null;
                $fields['profile']['birthdate'] = isset($fields['profile']['birthdate']) ? Carbon::createFromFormat('d/m/Y', $fields['profile']['birthdate']) : null;
                $fields['profile']['user_id'] = $item->id;
                Profile::create($fields['profile']);

                foreach($subfields as $subitemId)
                    $item->roles()->attach($subitemId);
                
                $item->save();
            });
            return Redirect::route('admin:users.index')
                ->with(['success' => trans('crud.successfully-added', [trans('admin.user')])]);
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
        if(Gate::denies('users-view'))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);

        $item = User::find($id);
        if(!isset($item))
            return Redirect::back()->withErrors([trans('crud.item-not-found')]);
            
        return view('admin.users.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Gate::denies('users-update'))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);

        $item = User::find($id);
        if(!isset($item))
            return Redirect::back()->withErrors([trans('crud.item-not-found')]);

        $subitems = Role::orderBy('name')->get();
        return view('admin.users.form', compact('item', 'subitems'));
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
        if(Gate::denies('users-update'))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);
        
        $item = User::find($id);

        $fields = $request->item;
        $fields['profile'] = $request->profile;
        $subfields = array_map('intval', $request->subitems);
        $newPassword = null;
        
        if($item->email == $fields['email']) unset($fields['email']);
        if(!empty($fields['password']))
        {
            if(empty($fields['confirm-password']) || $fields['password'] != $fields['confirm-password'])
            {
                return Redirect::back()
                    ->withErrors([trans('auth.please-confirm-password')])
                    ->withInput();
            }
            $newPassword = Hash::make($fields['password']);
        }

        $subitemsOnDatabase = $item->roles->map(function($role){ return $role->id; })->toArray();
        $subitemsToDelete = array_diff($subitemsOnDatabase, $subfields);
        $subitemsToInsert = array_unique(array_diff($subfields, $subitemsOnDatabase));

        $validator = Validator::make($fields, $this->rulesUpdate, [], $this->names);
        if ($validator->fails())
            return Redirect::back()->withErrors($validator)->withInput();
        
        try {
            $item->name = $fields['name'];
            if(!empty($fields['email'])) $item->email = $fields['email'];
            if(!empty($newPassword)) $item->password = $newPassword;
            
            DB::transaction(function() use($item, $fields, $subitemsToDelete, $subitemsToInsert) {
                if(!isset($item->profile))
                {
                    $profile = Profile::create([
                        'user_id' => $item->id
                    ]);
                } else $profile = $item->profile;

                $profile->identity = isset($fields['profile']['identity']) ? Profile::clearMask($fields['profile']['identity']) : null;
                $profile->birthdate = isset($fields['profile']['birthdate']) ? Carbon::createFromFormat('d/m/Y', $fields['profile']['birthdate']) : null;
                $profile->save();
            
                foreach($subitemsToDelete as $subitemId)
                    $item->roles()->detach($subitemId);
                foreach($subitemsToInsert as $subitemId)
                    $item->roles()->attach($subitemId);

                $item->save();
            });
            return Redirect::route('admin:users.index')
                ->with(['success' => trans('crud.successfully-updated', [trans('admin.user')])]);
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
        if(Gate::denies('users-delete'))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);

        try {
            $item = User::find($id);
            $item->delete();

            return Redirect::route('admin:users.index')
                ->with(['success' => trans('crud.successfully-deleted', [trans('admin.user')])]);
        } catch (\Exception $e) {
            return Redirect::back()
                ->withErrors([trans('crud.error-occurred') . $e->getMessage()])
                ->withInput();
        }
    }
}
