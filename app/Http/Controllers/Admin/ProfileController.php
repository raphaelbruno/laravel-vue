<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

use App\Profile;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = Auth::user();
        return view('admin.profile.form', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $fields = $request->get('user');
        $fields['profile'] = $request->get('profile');
        $newPassword = null;
        
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

        try {
            $user = Auth::user();

            if(!isset($user->profile)){
                Profile::create([
                    'user_id' => $user->id
                ]);

                $user = Auth::user();
            }
            
            $user->name = $fields['name'];
            //if(!empty($newPassword)) $user->password = $newPassword;

            $user->profile->identity = Profile::clearMask($fields['profile']['identity']);
            $user->profile->birthdate = Carbon::createFromFormat('d/m/Y', $fields['profile']['birthdate']);

            $user->profile->save();
            $user->save();

            return Redirect::route('admin::profile')
                ->with(['success' => trans('crud.successfully-saved', [trans('admin.profile')])]);
        } catch (\Exception $e) {
            return Redirect::back()
                ->withErrors([trans('crud.error-occurred') . $e->getMessage()])
                ->withInput();
        }
    }
}
