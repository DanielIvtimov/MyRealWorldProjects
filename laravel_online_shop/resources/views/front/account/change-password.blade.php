@extends('front.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">My Account</a></li>
                    <li class="breadcrumb-item">Change Password</li>
                </ol>
            </div>
        </div>
    </section>
    <section class=" section-11 ">
        <div class="container  mt-5">
            <div class="row">
                <div class="col-md-12">
                    @include('front.account.common.message')
                </div>
                <div class="col-md-3">
                    @include('front.account.common.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Change Password</h2>
                        </div>
                        <form action="" method="post" id="changePasswordForm" name="changePasswordForm">
                            @csrf
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="name">Old Password</label>
                                        <input type="password" name="old_password" id="old_password" placeholder="Old Password" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="name">New Password</label>
                                        <input type="password" name="new_password" id="new_password" placeholder="New Password" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="name">Confirm Password</label>
                                        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="d-flex">
                                        <button id="submit" name="submit" type="submit" class="btn btn-dark">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    <script type="text/javascript">
        $("#changePasswordForm").submit(function(e){
            e.preventDefault();

            var $form = $(this);

            // Clear previous errors
            $form.find('.is-invalid').removeClass('is-invalid');
            $form.find('.invalid-feedback').removeClass('invalid-feedback').html('');

            $("#submit").prop('disabled', true);

            $.ajax({
                url: "{{ route('account.processChangePassword') }}",
                type: "post",
                data: $form.serializeArray(),
                dataType: "json",
                success: function(response){
                    $("#submit").prop('disabled', false);
                    if(response.status == true){
                        window.location.href = "{{ route('account.showChangePassword') }}";
                    } else {
                        var errors = response.errors || {};
                        var fields = ['old_password', 'new_password', 'confirm_password'];

                        fields.forEach(function(field){
                            var $input = $('#' + field);
                            var $msgEl = $input.siblings('p');
                            var message = errors[field];

                            if (message) {
                                message = Array.isArray(message) ? message[0] : message;
                                $input.addClass('is-invalid');
                                $msgEl.addClass('invalid-feedback').html(message);
                            } else {
                                $input.removeClass('is-invalid');
                                $msgEl.removeClass('invalid-feedback').html('');
                            }
                        });
                    }
                },
                error: function(xhr){
                    $("#submit").prop('disabled', false);

                    var msg = 'Something went wrong. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    
                    $("#changePasswordForm").find('.alert.alert-danger').remove();
                    $("#changePasswordForm .card-body .row").before(
                        '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                            msg +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                        '</div>'
                    );
                },
            });
        });
    </script>
@endsection