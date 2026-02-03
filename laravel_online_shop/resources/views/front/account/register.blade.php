@extends('front.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                    <li class="breadcrumb-item">Register</li>
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
                <form action="" method="post" name="registrationForm" id="registrationForm">
                    @csrf
                    <h4 class="modal-title">Register Now</h4>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Name" id="name" name="name">
                        <p></p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Email" id="email" name="email">
                        <p></p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Phone" id="phone" name="phone">
                        <p></p>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Password" id="password" name="password">
                        <p></p>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Confirm Password" id="password_confirmation" name="password_confirmation">
                        <p></p>
                    </div>
                    <div class="form-group small">
                        <a href="#" class="forgot-link">Forgot Password?</a>
                    </div>
                    <button type="submit" class="btn btn-dark btn-block btn-lg" value="Register">Register</button>
                </form>
                <div class="text-center small">Already have an account? <a href="{{ route('account.login') }}">Login Now</a></div>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    <script>
        $("#registrationForm").submit(function(event){
            event.preventDefault();
            $.ajax({
                url: "{{ route('account.processRegister') }}",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function (response) {
                    // Clear all previous errors
                    const fields = ['name', 'email', 'phone', 'password', 'password_confirmation'];
                    fields.forEach(function(field) {
                        $("#" + field).removeClass('is-invalid');
                        $("#" + field).siblings("p").removeClass('invalid-feedback').html('');
                    });
                    
                    if (response.status == false) {
                        // Display errors dynamically
                        if (response.errors) {
                            Object.keys(response.errors).forEach(function(field) {
                                const errorMessage = response.errors[field][0];
                                $("#" + field).siblings("p").addClass('invalid-feedback').html(errorMessage);
                                $("#" + field).addClass('is-invalid');
                            });
                        }
                    } else {
                        // Registration successful - redirect to login page
                        window.location.href = "{{ route('account.login') }}";
                    }
                },
                error: function(jQXHR, execption){
                    console.log("Something went wrong");
                }
            });
        });
    </script>
@endsection 