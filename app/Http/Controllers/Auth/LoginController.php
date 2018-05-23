<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Socialite;
use App\User;

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


    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function redirect()
    {
        return Socialite::driver('google')
                        ->scopes(config('google.scopes'))
                        ->with([
                            'access_type'     => config('google.access_type'),
                            'approval_prompt' => config('google.approval_prompt'),
                        ])
                        ->redirect();
    }

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function callback()
    {
        if (!request()->has('code')) {
            return redirect('/');
        }

        /**
         * @var \Laravel\Socialite\Two\User $user
         */
        $user = Socialite::driver('google')->user();

        dd($user);

        return redirect('/home');
    }

    public function logout()
    {
        auth()->logout();

        return redirect('/');
    }
}
