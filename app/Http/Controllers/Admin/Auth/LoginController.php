<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    protected $guard = 'admin';
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
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('admin.Auth.login');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();

        return redirect('/');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required|min:6',
        ]);
        $credential = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::guard('admin')->attempt($credential, $request->remember)) {
            if (Auth::guard('admin')->user()->role === 'admin' || Auth::guard('admin')->user()->role === 'super_admin') {
                $token = Auth::guard('admin')->user()->createToken('Laravel Password Grant Client')->accessToken;
                return response(['token' => $token], 200);
                return redirect()->intended(action('Admin\DashboardController@index'));
            }
        }
        return response(['error' => 'نام کاربری یا رمز عبور صحیح نمی باشد.'], 422);

        return redirect()->back()->withInput($request->only('username', 'remember'));
    }
}
