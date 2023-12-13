<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    // ログイン成功時
    protected $redirectTo = '/products';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

     protected function authenticated(Request $request, $user)
     {
         return redirect()->route('products.index'); 
     }

     protected function sendFailedLoginResponse(Request $request)
{
    return redirect()->route('login')
        ->withInput($request->only($this->username(), 'remember'))
        ->withErrors(['login' => __('auth.failed')]);
}

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
