@extends("admin.layouts.app")

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Shipping Management</h1>
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
            @include('admin.message')
            <form action="" method="post" id="shippingForm" name="shippingForm">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <select name="country" id="country" class="form-control">
                                        <option value="">Select Country</option>
                                        @if($countries->isNotEmpty())
                                            @foreach($countries as $country)
                                                <option {{ ($shippingCharge->country_id == $country->id) ? 'selected' : '' }} value="{{ $country->id }}">{{ $country->name }}</option> 
                                            @endforeach
                                            <option {{ ($shippingCharge->country_id == 'rest_of_world') ? 'selected' : '' }} value="rest_of_world">Rest of the World</option>
                                        @endif
                                    </select> 
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <input value="{{ $shippingCharge->amount }}" type="text" name="amount" id="amount" class="form-control" placeholder="Amount" >
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customJs')
    <script>
        $(document).ready(function () {
            $("#shippingForm").submit(function (event) {
                event.preventDefault();

                let element = $(this);

                $("button[type='submit']").prop('disabled', true);

                $.ajax({
                    url: "{{ route('shipping.update', $shippingCharge->id ) }}",
                    type: "put",
                    data: element.serialize(),
                    dataType: "json",
                    success: function (response) {
                        $("button[type='submit']").prop('disabled', false);

                        if (response['status'] == true) {
                            window.location.href = "{{ route('shipping.create')}}";
                        } else {
                            if (response['notFound'] == true) {
                                window.location.href = "{{ route('shipping.create')}}";
                            } else {
                                let errors = response['errors'];
                                let fields = ['country', 'amount'];
                                
                                fields.forEach(function(field) {
                                    let fieldElement = $("#" + field);
                                    if (errors[field]) {
                                        fieldElement.addClass("is-invalid").siblings("p").addClass("invalid-feedback").html(errors[field]);
                                    } else {
                                        fieldElement.removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html("");
                                    }
                                });
                            }
                        }
                    }, error: function (jqXHR, exception) {
                        $("button[type='submit']").prop('disabled', false);
                        console.log("Something went wrong");
                    }
                })
            });
        });
    </script>
@endsection