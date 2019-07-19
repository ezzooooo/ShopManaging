@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="login-form text-center text-white index-top-mg">
                <div class="login-form-header">
                    <h1 style="font-weight:10px;">Login</h1>
                </div>
                <div class="login-form-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-md-1">
                                        &nbsp;
                                    </div>
                                    <label for="email" class="col-md-2 col-form-label text-md-center"><h3>{{ __('ID') }}</h3></label>
                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} login-input" name="email" value="{{ old('email') }}" required autofocus autocomplete="off">
                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-1">
                                        &nbsp;
                                    </div>
                                    <label for="password" class="col-md-2 col-form-label text-md-center"><h3>{{ __('P/W') }}</h3></label>
                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} login-input" name="password" required>
                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="text-white">
                                <button type="submit" class="enter-button">
                                    <h4>{{ __('ENTER') }}</h4>
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <!--<input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                    -->
                                </div>
                            </div>
                        </div>   
                    </form>
                    <div class="text-md-right">
                    @if (Route::has('register'))
                        <a class="btn btn-link text-white register-text" href="{{ route('register') }}">
                            {{ __('회원가입') }}
                        </a>
                    @endif    
                    </div>
                    <div class="text-md-right">
                    @if (Route::has('password.request'))
                        <a class="btn btn-link text-white forget-text" href="{{ route('user.select_inquire') }}">
                            {{ __('Do you forget your ID or P/W?') }}
                        </a>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
