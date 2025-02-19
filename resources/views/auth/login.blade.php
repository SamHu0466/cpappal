@extends('layouts.app')

@section('bodyClass', 'bg-gradient-primary')

@section('contents')

        <div class="container">

            <!-- Outer Row -->
            <div class="row justify-content-center">

                <div class="col-xl-10 col-lg-12 col-md-9">

                    <div class="card o-hidden border-0 shadow-lg my-5" style="margin-top: 10rem !important">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="row">
                                <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                                <div class="col-lg-6">
                                    <div class="p-5">
                                        <div class="text-center">
                                            <h1 class="h4 text-gray-900 mb-4">CPAPPAL</h1>
                                        </div>
                                        <form method="POST" action="{{ route('login') }}">
                                            @csrf
                                            <div class="form-group">
                                                <input id="email" type="email" class="input-material form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder=" ">
                                                <label for="email" class="input-label">{{ __('Email') }}</label>
                                                @if ($errors->has('email'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                                @endif
                                                {{--<input type="email" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address...">--}}
                                            </div>
                                            <div class="form-group">
                                                <input id="password" type="password" class="input-material form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required autocomplete="current-password" placeholder=" ">
                                                <label for="password" class="input-label">{{ __('Password') }}</label>
                                                @if ($errors->has('password'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                @endif
                                                {{--<input type="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password">--}}
                                            </div>
                                            {{--<div class="form-group">--}}
                                                {{--<div class="custom-control custom-checkbox small">--}}
                                                    {{--<input type="checkbox" class="custom-control-input" id="customCheck">--}}
                                                    {{--<label class="custom-control-label" for="customCheck">Remember Me</label>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                                {{ __('Login') }}
                                            </button>
                                            {{--<hr>--}}
                                            {{--<a href="index.html" class="btn btn-google btn-user btn-block">--}}
                                                {{--<i class="fab fa-google fa-fw"></i> Login with Google--}}
                                            {{--</a>--}}
                                            {{--<a href="index.html" class="btn btn-facebook btn-user btn-block">--}}
                                                {{--<i class="fab fa-facebook-f fa-fw"></i> Login with Facebook--}}
                                            {{--</a>--}}
                                        </form>
                                        {{--<hr>--}}
                                        {{--<div class="text-center">--}}
                                            {{--<a class="small" href="forgot-password.html">Forgot Password?</a>--}}
                                        {{--</div>--}}
                                        {{--<div class="text-center">--}}
                                            {{--<a class="small" href="register.html">Create an Account!</a>--}}
                                        {{--</div>--}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
