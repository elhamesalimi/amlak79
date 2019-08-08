<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Email;
use App\Admin as User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:45',
            'username' => 'required|string|max:45|unique:admins',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|digits:11|phone|unique:admins',
            'role' => 'required|in:admin,super_admin,expert',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'username' => $data['username'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function register(Request $request){
        $user_check = User::where('phone', $request['phone'])->first();
        if($user_check){
            // the user already exists
            return response()->json(["message" => " این شماره تماس قبلا ثبت شده است."], 400);
        }
        $data =  [ 'username' => 'salimi',
            'role' => $request['role'],
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'phone' => $request['phone'],
        ];
        $user = User::create($data);
//        $email = new Email();
//        $email->email =  $request['email'];
//        $email->user_id = $user->id;
//        $email->save();

        return response("success", 200);
    }
}
