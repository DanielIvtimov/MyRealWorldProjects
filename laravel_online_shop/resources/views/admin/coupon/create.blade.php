@extends("admin.layouts.app")

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Coupon Code</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('categories.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" method="post" id="discountForm" name="discountForm">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Code</label>
                                <input type="text" name="code" id="code" class="form-control" placeholder="Coupon Code">    
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Coupon Code Name">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Max Uses</label>
                                <input type="number" name="max_uses" id="max_uses" class="form-control" placeholder="Max Uses">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Max Uses User</label>
                                <input type="text" name="max_uses_user" id="max_uses_user" class="form-control" placeholder="Max Uses User">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Type</label>
                                <select name="type" id="type" class="form-control">
                                    <option value="percent">Percent</option>
                                    <option value="fixed">Fixed</option>
                                </select>
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Discount Amount</label>
                                <input type="text" name="discount_amount" id="discount_amount" class="form-control" placeholder="Discount Amount">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Min Amount</label>
                                <input type="text" name="min_amount" id="min_amount" class="form-control" placeholder="Min Amount">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Block</option>
                                </select>
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Starts At</label>
                                <input type="text" name="starts_at" id="starts_at" class="form-control" placeholder="Starts At">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Expires At</label>
                                <input type="text" name="expires_at" id="expires_at" class="form-control" placeholder="Expires At">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Description</label>
                                <textarea name="description" id="description" class="form-control" cols="30" rows="5"></textarea>
                                <p></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection 

@section('customJs')
<script>
$(document).ready(function(){

    $("#starts_at").datetimepicker({
        format: 'Y-m-d H:i:s',
    });

    $("#expires_at").datetimepicker({
        format: 'Y-m-d H:i:s',
    });

    $("#discountForm").submit(function(event){
        event.preventDefault();

        let element = $(this);

        $("button[type='submit']").prop('disabled', true);

        $.ajax({
            url: "{{ route('coupons.store') }}", 
            type: "post",
            data: element.serialize(),
            dataType: "json",
            success: function(response){
                $("button[type='submit']").prop('disabled', false);

                // All fields we might validate/show errors for
                const fields = ['code', 'name', 'max_uses', 'max_uses_user', 'type', 'discount_amount', 'min_amount', 'status', 'starts_at', 'expires_at', 'description'];

                // Clear previous errors
                fields.forEach(function(field){
                    $("#" + field)
                        .removeClass("is-invalid")
                        .siblings("p")
                        .removeClass("invalid-feedback")
                        .html("");
                });

                if(response['status'] == true){
                    window.location.href = "{{ route('coupouns.index')}}";
                } else {
                    let errors = response['errors'] || {};

                    Object.keys(errors).forEach(function(field){
                        let message = errors[field];

                        // Laravel validator sends arrays; custom errors can be strings
                        if(Array.isArray(message)){
                            message = message[0];
                        }

                        let $input = $("#" + field);
                        if($input.length){
                            $input
                                .addClass("is-invalid")
                                .siblings("p")
                                .addClass("invalid-feedback")
                                .html(message);
                        }
                    });
                }
            }, error: function(jqXHR, exception){
                $("button[type='submit']").prop('disabled', false);
                console.log("Something went wrong");
            }
        })
    });
});

</script>
@endsection