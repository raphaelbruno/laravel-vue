<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CrudController;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;

use App\Models\Role;
use App\Models\User;

class UserController extends CrudController
{
    protected $model = User::class;

    protected $rules = [
        'name' => 'required|string|min:3',
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
        $this->label = trans('admin.user');
        $this->title = trans('admin.users');

        parent::__construct();
    }

    public function create()
    {
        $subitems = Role::where(function ($query){
                if(!Auth::user()->isSuperUser()){
                    if(!Auth::user()->getHighestLevel()){
                        $query->whereRaw("false");
                        return;
                    }

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
            return $this->backOrJson(request(), 'confirm_password', 'auth.please-confirm-password');

        return parent::store($request);
    }

    public function prepareFieldStore($fields)
    {
        if(isset($fields['password'])) $fields['password'] = Hash::make($fields['password']);
        else $fields['password'] = Hash::make(User::generatePassword());

        return $fields;
    }

    public function afterStore($item, $request)
    {
        $profile = $request->profile;
        $profile['identity'] = isset($profile['identity']) ? clearMask($profile['identity']) : null;
        $profile['birthdate'] = isset($profile['birthdate']) ? Carbon::createFromFormat('d/m/Y', $profile['birthdate']) : null;
        $profile['dark_mode'] = (Boolean) (isset($profile['dark_mode']) ? $profile['dark_mode'] : false);
        $profile['user_id'] = $item->id;

        return self::subitems($item, 'roles', $request->subitems) && Profile::create($profile);
    }

    public function show($id)
    {
        $item = User::find($id);

        if(!isset($item))
            return $this->backOrJson(request(), 'item_not_found', 'crud.item-not-found');
    
        if(Gate::denies('has-level', $item))
            return $this->backOrJson(request(), 'not_authorized', 'crud.not-authorized');
    
        return parent::show($id);
    }

    public function edit($id)
    {
        $item = User::find($id);
        
        if(!isset($item))
            return $this->backOrJson(request(), 'item_not_found', 'crud.item-not-found');
    
        if(Gate::denies('has-level', $item))
            return $this->backOrJson(request(), 'not_authorized', 'crud.not-authorized');

        $subitems = Role::where(function ($query){
                if(!Auth::user()->isSuperUser()){
                    if(!Auth::user()->getHighestLevel()){
                        $query->whereRaw("false");
                        return;
                    }

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
            return $this->backOrJson(request(), 'item_not_found', 'crud.item-not-found');
    
        if(Gate::denies('has-level', $item))
            return $this->backOrJson(request(), 'not_authorized', 'crud.not-authorized');

        if(!empty($request->item['password']))
            if(empty($request->item['confirm-password']) || $request->item['password'] != $request->item['confirm-password'])
                return $this->backOrJson(request(), 'confirm_password', 'auth.please-confirm-password');

        return parent::update($request, $id);
    }

    public function prepareValidationUpdate(Request $request, $item)
    {
        $this->rules['email'] = "sometimes|string|email|unique:users,email,{$item->id},id,deleted_at,NULL";

        return $request->item;
    }

    public function prepareFieldUpdate($fields, $item)
    {
        if(empty($fields['password'])) unset($fields['password']);
        else $fields['password'] = Hash::make($fields['password']);            

        return $fields;
    }

    public function afterUpdate($item, $request)
    {
        $fields = $request->profile;
        $profile = $item->profile ? $item->profile : new Profile(['user_id' => $item->id]);

        if(isset($fields['identity'])) $profile->identity = clearMask($fields['identity']);
        if(isset($fields['birthdate'])) $profile->birthdate = Carbon::createFromFormat('d/m/Y', $fields['birthdate']);
        $profile->dark_mode = (Boolean) (isset($fields['dark_mode']) ? $fields['dark_mode'] : false);
        
        return self::subitems($item, 'roles', $request->subitems) && $profile->save();
    }

    public function destroy($id)
    {
        $item = User::find($id);

        if(!isset($item))
            return $this->backOrJson(request(), 'item_not_found', 'crud.item-not-found');
    
        if(Gate::denies('has-level', $item))
            return $this->backOrJson(request(), 'not_authorized', 'crud.not-authorized');

       return parent::destroy($id);
    }
}
