@extends('admin.master')
@section('title','  تغییر رمز عبور')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">

                    <div class="card-body">

                        <form method="POST" action="{{ action('Admin\UserController@changePassword') }}">
                            @csrf
                            <input type="hidden" name="id" value="{{$user->id}}"/>

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">نام کارشناس</label>

                                <div class="col-md-6">
                                    <label class="control-label">{{$user->name}}</label>
                                </div>
                            </div>
                            @if($user->role==='super_admin')
                                <div class="form-group row">
                                    <label for="old_password" class="col-md-4 col-form-label text-md-right"> رمز عبور
                                        فعلی</label>
                                    <div class="col-md-6">
                                        <input id="old_password" type="password"
                                               class="form-control{{ $errors->has('old_password') ? ' is-invalid' : '' }}"
                                               name="old_password" value="{{ old('old_password') }}" required>

                                        @if ($errors->has('old_password'))
                                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('old_password') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">رمز عبور</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                           class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                           name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">تکرار رمز
                                    عبور</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                           name="password_confirmation" required>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary pull-right">
                                        تغییر رمز عبور
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection