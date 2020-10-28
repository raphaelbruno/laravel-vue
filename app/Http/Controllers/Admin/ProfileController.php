<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\UtilHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

use App\Models\Profile;

class ProfileController extends Controller
{
    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

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
        $fields = $request->user;
        $fields['profile'] = $request->profile;
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

        unset($fields['password']);
        unset($fields['confirm-password']);
        if(!empty($newPassword)) $fields['password'] = $newPassword;

        if(isset($fields['profile']['avatar'])) $fields['profile']['avatar'] = $fields['profile']['avatar']->store('public/avatars');
        $fields['profile']['identity'] = isset($fields['profile']['identity']) ? UtilHelper::clearMask($fields['profile']['identity']) : null;
        $fields['profile']['birthdate'] = isset($fields['profile']['birthdate']) ? Carbon::createFromFormat('d/m/Y', $fields['profile']['birthdate']) : null;
        $fields['profile']['dark_mode'] = (Boolean) (isset($fields['profile']['dark_mode']) ? $fields['profile']['dark_mode'] : false);

        try {
            $profile = null;
            DB::transaction(function() use($fields, $profile) {
                $user = Auth::user();
                if(!isset($user->profile))
                {
                    $profile = Profile::create([
                        'user_id' => $user->id
                    ]);
                } else $profile = $user->profile;

                if(isset($fields['profile']['avatar']))
                    $previousAvatar = $profile->avatar;

                $profile->update($fields['profile']);
                $user->update($fields);

                if(isset($previousAvatar))
                    Storage::delete($previousAvatar);
            });
            return Redirect::route('admin:profile')
                ->with(['success' => trans('crud.successfully-saved', [trans('admin.profile')])]);
        } catch (\Exception $e) {
            return Redirect::back()
                ->withErrors([trans('crud.error-occurred') . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function toggleDarkMode()
    {
        $user = Auth::user();
        if(isset($user->profile)){
            $user->profile->dark_mode = !$user->profile->dark_mode;
            $user->profile->save();
        }
    }

}
