<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    function login(Request $request){
        $fields = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        $names = [
            'email' => trans('crud.email'),
            'password' => trans('crud.password'),
        ];

        $rules = [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ];

        $validator = Validator::make($fields, $rules, [], $names);

        if($validator->fails() || !Auth::attempt($fields)){
            return response(['message' => trans('auth.failed')]);
        }

        $user = Auth::user();
        $user->revokeTokens();
        $token = $user->createToken('authToken');
        
        return response([
            'user' => User::with(['roles', 'roles.permissions', 'profile'])->find($user->id),
            'token' => $token->accessToken,
        ]);
    }

    function user(Request $request){
        $user = $request->user();
        return $user;
    }

    function register(Request $request){
        $fields = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ];

        $names = [
            'name' => trans('crud.name'),
            'email' => trans('crud.email'),
            'password' => trans('crud.password'),
        ];

        $rules = [
            'name' => 'required|string|min:3',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
        ];

        $validator = Validator::make($fields, $rules, [], $names);

        if($validator->fails()){
            return response(['message' => $validator->errors()]);
        }

        $fields['password'] = Hash::make($fields['password']);
        return User::create($fields);
    }

    function logout(Request $request){
        $request->user()->revokeTokens();
        return response(['message' => trans('auth.logged-out-successfully')]);
    }
}