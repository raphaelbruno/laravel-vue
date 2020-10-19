<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CrudController;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;

use App\Models\Role;
use App\Models\User;

class UserController extends CrudController
{
    protected $model = User::class;
    
    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|string|email|unique:users,email,NULL,id,deleted_at,NULL',
    ];
    
    function __construct()
    {
        $this->names = [
            'name' => trans('crud.name'),
            'email' => trans('crud.email'),
            'password' => trans('crud.password'),
            'confirm-password' => trans('crud.confirm-password'),
            'identity' => trans('admin.identity'),
            'birthdate' => trans('admin.birthdate'),
        ];

        $this->icon = 'user';
        $this->item = trans('admin.user');
        $this->title = trans('admin.users');

        parent::__construct();
    }

    public function create()
    {
        $subitems = Role::where(function ($query){
                if(!Auth::user()->isSuperUser()){
                    $query->where('level', '>', Auth::user()->getHighestLevel());
                    if(is_numeric(Auth::user()->getHighestLevel()))
                        $query->orWhere('level', null);
                }
            })
            ->orderBy('title')
            ->get();
        
        $this->addToView(compact('subitems'));
        return parent::create();
    }

    public function store(Request $request)
    {
        if($request->item['password'] != $request->item['confirm-password'])
        {
            return Redirect::back()
                ->withErrors([trans('auth.please-confirm-password')])
                ->withInput();
        }

        return parent::store($request);
    }

    public function prepareFieldStore($fields)
    {
        if(!isset($fields['password']))
            $fields['password'] = Hash::make(User::generatePassword());

        return $fields;
    }

    public function afterStore($item, $request)
    {
        $profile = $request->profile;
        $profile['identity'] = isset($profile['identity']) ? Profile::clearMask($profile['identity']) : null;
        $profile['birthdate'] = isset($profile['birthdate']) ? Carbon::createFromFormat('d/m/Y', $profile['birthdate']) : null;
        $profile['dark_mode'] = (Boolean) (isset($profile['dark_mode']) ? $profile['dark_mode'] : false);
        $profile['user_id'] = $item->id;

        return self::subitems($item, 'roles', $request->subitems) && Profile::create($profile);
    }

    public function show($id)
    {
        $item = User::find($id);

        if(!isset($item))
            return Redirect::back()->withErrors([trans('crud.item-not-found')]);

        if(Gate::denies('has-level', $item))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);

        return parent::show($id);
    }

    public function edit($id)
    {
        $item = User::find($id);

        if(!isset($item))
            return Redirect::back()->withErrors([trans('crud.item-not-found')]);

        if(Gate::denies('has-level', $item))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);

        $subitems = Role::where(function ($query){
                if(!Auth::user()->isSuperUser()){
                    $query->where('level', '>', Auth::user()->getHighestLevel());
                    if(is_numeric(Auth::user()->getHighestLevel()))
                        $query->orWhere('level', null);
                }
            })
            ->orderBy('title')
            ->get();

        $this->addToView(compact('subitems'));
        return parent::edit($id);
    }

    public function update(Request $request, $id)
    {
        $item = User::find($id);

        if(!isset($item))
            return Redirect::back()->withErrors([trans('crud.item-not-found')]);

        if(Gate::denies('has-level', $item))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);

        if(!empty($request->item['password']))
        {
            if(empty($request->item['confirm-password']) || $request->item['password'] != $request->item['confirm-password'])
            {
                return Redirect::back()
                    ->withErrors([trans('auth.please-confirm-password')])
                    ->withInput();
            }
        }

        return parent::update($request, $id);
    }

    public function prepareValidationUpdate(Request $request, $item)
    {
        $this->rules['email'] = "required|string|email|unique:users,email,{$item->id},id,deleted_at,NULL";

        return $request->item;
    }
    
    public function prepareFieldUpdate($fields, $item)
    {
        if(!empty($fields['password']))
            $fields['password'] = Hash::make($fields['password']);
        return $fields;
    }

    public function afterUpdate($item, $request)
    {
        $profile = $request->profile;
        if(isset($profile['identity'])) $item->profile->identity = Profile::clearMask($profile['identity']);
        if(isset($profile['birthdate'])) $item->profile->birthdate = Carbon::createFromFormat('d/m/Y', $profile['birthdate']);
        $item->profile->dark_mode = (Boolean) (isset($profile['dark_mode']) ? $profile['dark_mode'] : false);
        
        return self::subitems($item, 'roles', $request->subitems) && $item->profile->save();
    }
    
    public function destroy($id)
    {
        $item = User::find($id);

        if(!isset($item))
            return Redirect::back()->withErrors([trans('crud.item-not-found')]);

        if(Gate::denies('has-level', $item))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);

       return parent::destroy($id);
    }
}