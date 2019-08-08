<?php

namespace App\Http\Controllers\Auth;

use App\Address;
use App\Email;
use App\User;
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
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:11|phone|unique:users',
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

        $user = User::create([
            'name' => $request['name'],
            'password' => Hash::make($request['password']),
            'phone' => $request['phone'],
            'userTypeId' => User::BUYER
        ]);
        $email = new Email();
        $email->email =  $request['email'];
        $email->user_id = $user->id;
        $email->save();

        return response("success", 200);
    }
}
