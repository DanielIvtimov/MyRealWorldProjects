@extends('front.layouts.app');

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">My Account</a></li>
                    <li class="breadcrumb-item">Settings</li>
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
                            <h2 class="h5 mb-0 pt-2 pb-2">Personal Information</h2>
                        </div>
                        <form action="" name="profileForm" id="profileForm">
                            @csrf
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="name">Name</label>
                                        <input value="{{ $user->name }}" type="text" name="name" id="name" placeholder="Enter Your Name" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email">Email</label>
                                        <input value="{{ $user->email }}" type="text" name="email" id="email" placeholder="Enter Your Email" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone">Phone</label>
                                        <input value="{{ $user->phone }}" type="text" name="phone" id="phone" placeholder="Enter Your Phone" class="form-control">
                                        <p></p>
                                    </div>

                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-dark">Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card mt-5">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Address</h2>
                        </div>
                        <form action="" name="addressForm" id="addressForm">
                            @csrf
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="first_name">First Name</label>
                                        <input value="{{ (!empty($address)) ? $address->first_name : '' }}" type="text" name="first_name" id="first_name" placeholder="Enter Your First Name" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="last_name">Last Name</label>
                                        <input value="{{ (!empty($address)) ? $address->last_name : '' }}" type="text" name="last_name" id="last_name" placeholder="Enter Your Last Name" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="address_form_email">Email</label>
                                        <input value="{{ (!empty($address)) ? $address->email : '' }}" type="text" name="email" id="address_form_email" placeholder="Enter Your Email" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="mobile">Mobile</label>
                                        <input value="{{ (!empty($address)) ? $address->mobile : '' }}" type="text" name="mobile" id="mobile" placeholder="Enter Your Mobile Number" class="form-control">
                                        <p></p>
                                    </div>

                                    <div class="mb-3">
                                        <label for="country_id">Country</label>
                                        <select name="country_id" id="country_id" class="form-control">
                                            <option value="">Select Country</option>
                                            @if($countries->isNotEmpty())
                                                @foreach($countries as $country)
                                                    <option {{ (!empty($address) && $address->country_id == $country->id) ? 'selected' : '' }} value="{{ $country->id }}" >{{ $country->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <p></p>
                                    </div>

                                    <div class="mb-3">
                                        <label for="address">Address</label>
                                        <textarea name="address" id="address" cols="30" rows="5" class="form-control" placeholder="Street address">{{ (!empty($address)) ? $address->address : '' }}</textarea>
                                        <p></p>
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label for="apartment">Apartment</label>
                                        <input value="{{ (!empty($address)) ? $address->apartment : '' }}" type="text" name="apartment" id="apartment" placeholder="Apartment" class="form-control">
                                        <p></p>
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label for="city">City</label>
                                        <input value="{{ (!empty($address)) ? $address->city : '' }}" type="text" name="city" id="city" placeholder="City" class="form-control">
                                        <p></p>
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label for="state">State</label>
                                        <input value="{{ (!empty($address)) ? $address->state : '' }}" type="text" name="state" id="state" placeholder="State" class="form-control">
                                        <p></p>
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label for="zip">Zip</label>
                                        <input value="{{ (!empty($address)) ? $address->zip : '' }}" type="text" name="zip" id="zip" placeholder="Zip" class="form-control">
                                        <p></p>
                                    </div>

                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-dark">Update</button>
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
    <script>
        $("#profileForm").submit(function(event){
            event.preventDefault();

            var $form = $(this);
            var $submitBtn = $form.find('button[type="submit"]');

            // Clear previous errors and feedback
            $form.find('.is-invalid').removeClass('is-invalid');
            $form.find('.invalid-feedback').removeClass('invalid-feedback').html('');

            $.ajax({
                url: "{{ route('account.updateProfile') }}",
                type: "POST",
                data: $form.serializeArray(),
                dataType: "json",
                beforeSend: function(){
                    $submitBtn.prop('disabled', true).text('Updating...');
                },
                success: function(response){
                    if(response.status == true){
                        window.location.href = "{{ route('account.profile') }}";
                        return;
                    }

                    // Handle validation errors (Laravel sends arrays per field)
                    var errors = response.errors || {};
                    var fields = ['name', 'email', 'phone'];
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
                },
                error: function(xhr) {
                    var msg = 'An error occurred. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    } else if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        var first = Object.keys(errors)[0];
                        if (first) msg = Array.isArray(errors[first]) ? errors[first][0] : errors[first];
                    }
                    $form.find('.card-body').prepend('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + msg + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                },
                complete: function(){
                    $submitBtn.prop('disabled', false).text('Update');
                }
            });
        });

        // Address Form
        $("#addressForm").submit(function(event){
            event.preventDefault();

            var $form = $(this);
            var $submitBtn = $form.find('button[type="submit"]');

            // Clear previous errors and feedback
            $form.find('.is-invalid').removeClass('is-invalid');
            $form.find('.invalid-feedback').removeClass('invalid-feedback').html('');

            $.ajax({
                url: "{{ route('account.updateAddress') }}",
                type: "POST",
                data: $form.serializeArray(),
                dataType: "json",
                beforeSend: function(){
                    $submitBtn.prop('disabled', true).text('Updating...');
                },
                success: function(response){
                    if(response.status == true){
                        window.location.href = "{{ route('account.profile') }}";
                        return;
                    }

                    // Handle validation errors (Laravel sends arrays per field)
                    var errors = response.errors || {};
                    var fields = ['first_name', 'last_name', 'email', 'country_id', 'address', 'apartment', 'city', 'state', 'zip', 'mobile'];
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
                },
                error: function(xhr) {
                    var msg = 'An error occurred. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    } else if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        var first = Object.keys(errors)[0];
                        if (first) msg = Array.isArray(errors[first]) ? errors[first][0] : errors[first];
                    }
                    $form.find('.card-body .alert.alert-danger').remove();
                    $form.find('.card-body').prepend('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + msg + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                },
                complete: function(){
                    $submitBtn.prop('disabled', false).text('Update');
                }
            });
        });
    </script>
@endsection 