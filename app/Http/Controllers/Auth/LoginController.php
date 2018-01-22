<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm() {
        $template = 'app';
        $object = new User;

        return view('auth.login', compact('object', 'template'));
    }
    
    public function redirectTo() {
        $locale = \Request::segment(1);
        return $locale === app()->getLocale() ? $locale . '/' : '/';
    }
    
    public function logout(Request $request) {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect($this->redirectTo());
    }
}
