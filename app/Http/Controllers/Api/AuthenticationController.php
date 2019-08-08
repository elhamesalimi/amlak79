<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('phone', $request->username)->first();
        if ($user) {
            if($user->activation_code === $request->password){
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                return response(['token'=>$token],200);
            }else{
                return response('Password mismatch',422);
            }
        } else {
            return response('User dos\'nt exist', 404);
        }
    }

    public function logoutAPI(){

        if (Auth::check()) {
            Auth::user()->AauthAcessToken()->delete();
        }
        return response("logged out", 200);
    }
}
