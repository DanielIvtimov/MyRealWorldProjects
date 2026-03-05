@extends('front.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                    <li class="breadcrumb-item">Reset Password</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-10">
        <div class="container">
            <div class="login-form">
                @if(Session::has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {!! Session::get('success') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(Session::has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {!! Session::get('error') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <form action="{{ route('front.processResetPassword') }}" method="post">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}" />
                    <h4 class="modal-title">Reset Password</h4>
                    <div class="form-group">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="New Password" id="password" name="password" value="">
                        @error('password')
                            <p class="invalid-feedback d-block">{{ $message }}</p>
                        @else
                            <p></p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <input type="password" class="form-control @error('confirm_password') is-invalid @enderror" placeholder="Confirm Password" id="confirm_password" name="confirm_password" value="">
                        @error('confirm_password')
                            <p class="invalid-feedback d-block">{{ $message }}</p>
                        @else
                            <p></p>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-dark btn-block btn-lg" value="Submit">Submit</button>
                </form>
                <div class="text-center small"><a href="{{ route('account.login') }}">Click Here to Login</a></div>
            </div>
        </div>
    </section>
@endsection
