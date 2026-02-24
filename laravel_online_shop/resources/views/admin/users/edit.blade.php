@extends("admin.layouts.app")

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit User</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('users.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <form action="" method="post" id="userForm" name="userForm">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name" value="{{ $user->name }}">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email">Email</label>
                                <input type="text" name="email" id="email" class="form-control" placeholder="Email" value="{{ $user->email }}">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone">Phone</label>
                                <input type="text" name="phone" id="phone" class="form-control" placeholder="Phone" value="{{ $user->phone }}">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option {{ $user->status == 1 ? 'selected' : '' }} value="1">Active</option>
                                    <option {{ $user->status == 0 ? 'selected' : '' }} value="0">Block</option>
                                </select>
                                <p></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('users.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
</section>
@endsection

@section('customJs')
<script>
$(document).ready(function(){
    $("#userForm").submit(function(event){
        event.preventDefault();

        var $form = $(this);
        $("button[type='submit']").prop('disabled', true);

        $.ajax({
            url: "{{ route('users.update', $user->id) }}",
            type: "put",
            data: $form.serialize(),
            dataType: "json",
            success: function(response){
                $("button[type='submit']").prop('disabled', false);

                if (response.status == true) {
                    window.location.href = "{{ route('users.index') }}";
                } else {
                    if (response.notFound == true) {
                        window.location.href = "{{ route('users.index') }}";
                    } else {
                        var errors = response.errors || {};
                        var fields = ['name', 'email', 'phone', 'status'];
                        fields.forEach(function(field) {
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
                }
            },
            error: function(jqXHR, exception){
                $("button[type='submit']").prop('disabled', false);
                console.log("Something went wrong");
                console.log(jqXHR);
            }
        });
    });
});
</script>
@endsection
