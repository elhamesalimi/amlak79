<?php

namespace App\Http\Controllers\Admin;

use App\Email;
use App\Admin as User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereNotIn('role', ['superAdmin'])->latest()->paginate(10);
        return view('admin.users.ajax', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:45',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:11|min:11|unique:admins',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
        ]);
        $user = new User();
        $user->name = $request['name'];
        $user->password = Hash::make($request['password']);
        $user->phone = $request['phone'];
        $user->role = $request['role'];
        $user->username = $request['username'];
        $user->email = $request['email'];
        $user->save();

        return redirect()->action('Admin\UserController@index');
    }

    public function edit(User $user)
    {
        if (!$user) {
            return redirect()->action('Admin\UserController@index')->with('error', 'کاربر یافت نشد.');
        }
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:45',
            'username' => 'required|string|max:50',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:11|min:11|unique:admins,id,' . $user->id,

        ]);
        $user = User::find($user->id);
        $user->name = $request['name'];
        $user->username = $request['username'];
        $user->phone = $request['phone'];
        $user->role = $request['role'];
        $user->email = $request['email'];
        $user->save();

        return redirect()->action('Admin\UserController@index')->with('message', 'کاربر با موفقیت ویرایش شد.');
    }

    public function showChangePasswordForm(User $user)
    {
        return view('admin.users.change-password', compact('user'));
    }

    public function changePassword(Request $request)
    {
        if (Auth()->guard('admin')->user()->role !== 'super_admin') {
            return abort(403);
        }
        $user = User::find($request->id);
        $role = $user->role;
        $rules = [
            'old_password' => ($role === 'super_admin') ? 'required|string|min:6' : '',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
        ];
        $request->validate($rules);
        if ($role === 'super_admin' && !Hash::check($request->old_password, $user->password)) {
            return redirect()->back()->withErrors(['old_password' => 'رمز عبور وارد شده صحیح نمی باشد'])->withInput();
        }
        $user->password = Hash::make($request->password);
        $user->save();
        DB::table('oauth_access_tokens')->where('user_id', $user->id)->delete();
        return redirect()->action('Admin\UserController@index')->with('message', 'رمز عبور کاربر با موفقیت تغییر یافت.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->action('Admin\UserController@index')->with('message', 'کاربر با موفقیت حذف شد.');
    }
}
