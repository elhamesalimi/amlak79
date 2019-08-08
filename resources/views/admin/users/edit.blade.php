@extends('admin.master')
@section('title','ویرایش کاربر')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">

                    <div class="card-body">
                        <form method="POST" action="{{ action('Admin\UserController@update',$user) }}">
                            {{method_field('PUT')}}
                            @csrf

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">نام و نام خانوادگی</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control  {{ $errors->has('name') ? ' has-error' : '' }}" name="name" value="{{ old('name', $user->name) }}" required autofocus>

                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="username" class="col-md-4 col-form-label text-md-right">نام کاربری</label>

                                <div class="col-md-6">
                                    <input id="username" type="text" class="form-control  {{ $errors->has('username') ? ' has-error' : '' }}" name="username" value="{{ old('username', $user->username) }}" required autofocus>

                                    @if ($errors->has('username'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="phone" class="col-md-4 col-form-label text-md-right"> شماره تماس</label>

                                <div class="col-md-6">
                                    <input id="phone" type="number" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{ old('phone',$user->phone) }}" required>

                                    @if ($errors->has('phone'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div> <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">آدرس ایمیل</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email',$user->email) }}" required>

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right"> نقش کاربر </label>

                                <div class="col-md-6">
                                    <label for="expert" ><input id="expert" type="radio" name="role" value="expert" {{(old('role' , $user->role))==='expert' ? 'checked':''}}  /> کارشناس </label>
                                    <label for="admin" ><input  id="admin" type="radio" name="role" value="admin" {{(old('role' , $user->role))==='admin' ? 'checked':''}} /> ادمین </label>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary pull-right">
 ویرایش                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection