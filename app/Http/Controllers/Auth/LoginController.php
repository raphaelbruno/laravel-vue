<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Profile;
use App\Models\Role;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProviderGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderGoogleCallback()
    {
        return $this->handleProviderCallback('google');
    }


    public function handleProviderCallback($driver)
    {
        try {
            $user = Socialite::driver($driver)->user();
        } catch (\Exception $e) {
            return Redirect::route('login')->withErrors([trans('auth.failed')]);
        }

        $existingUser = User::with(['roles', 'roles.permissions', 'profile'])
            ->where('email', $user->getEmail())->first();
        if($existingUser){
            DB::transaction(function() use($user, $existingUser, $driver) {
                if(is_null($existingUser->{$driver.'_id'})){
                    $existingUser->update([
                        'name' => $user->getName(),
                        'email' => $user->getEmail(),
                        $driver.'_id' => $user->getId()
                    ]);
                    if($existingUser->profile){
                        $existingUser->profile->update(['avatar' => $user->getAvatar()]);
                    }else{
                        Profile::create([
                            'user_id' => $existingUser->id,
                            'avatar' => $user->getAvatar()
                        ]);
                    }
                }
                Auth::login($existingUser);
            });
        }else {
            DB::transaction(function() use($user, $driver) {
                $newUser = User::create([
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    $driver.'_id' => $user->getId()
                ]);
                
                Profile::create([
                    'user_id' => $newUser->id,
                    'avatar' => $user->getAvatar()
                ]);

                foreach(Role::where('default', true)->get() as $role)
                    $newUser->roles()->attach($role->id);

                Auth::login($newUser);
            });
        }
        return Redirect::to($this->redirectTo);
    }
}
