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
                                                <option value="{{ $country->id }}">{{ $country->name }}</option> 
                                            @endforeach
                                            <option value="rest_of_world">Rest of the World</option>
                                        @endif
                                    </select> 
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <input type="text" name="amount" id="amount" class="form-control" placeholder="Amount" >
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">Create</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                              <table class="table table-striped">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                                @if($shippingCharges->isNotEmpty())
                                    @foreach($shippingCharges as $shippingCharge)
                                        <tr>
                                            <td>{{ $shippingCharge->id }}</td>
                                            <td>{{ ($shippingCharge->country_id == 'rest_of_world') ? 'Rest of the world' : $shippingCharge->country_name ?? 'N/A' }}</td>
                                            <td>${{ $shippingCharge->amount }}</td>
                                            <td>
                                                <a href="{{ route('shipping.edit', $shippingCharge->id) }}" class="btn btn-primary">Edit</a>
                                                <a href="javascript:void(0)" onclick="deleteRecord({{ $shippingCharge->id }})" class="btn btn-danger">Delete</a>
                                            </td>
                                        </tr>
                                    @endforeach 
                                @endif
                              </table>  
                        </div>
                    </div>
                </div>
            </div>
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
                    url: "{{ route('shipping.store') }}",
                    type: "post",
                    data: element.serialize(),
                    dataType: "json",
                    success: function (response) {
                        $("button[type='submit']").prop('disabled', false);

                        if (response['status'] == true) {
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
                    }, error: function (jqXHR, exception) {
                        $("button[type='submit']").prop('disabled', false);
                        console.log("Something went wrong");
                    }
                })
            });
        });

        function deleteRecord(id){
            let url = "{{ route('shipping.delete', 'ID') }}";
            let newUrl = url.replace('ID', id);
            
            if(confirm("Are you sure you want to delete this shipping charge?")){
                $.ajax({
                    url: newUrl,
                    type: "post",
                    data: {
                        _method: 'DELETE',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    success: function (response) {
                        if(response["status"] == true){
                            window.location.href = "{{ route('shipping.create') }}";
                        } else {
                            alert(response["message"] || "Something went wrong");
                        }
                    },
                    error: function (jqXHR, exception) {
                        console.log("Something went wrong");
                    }
                });
            }
        }
    </script>
@endsection