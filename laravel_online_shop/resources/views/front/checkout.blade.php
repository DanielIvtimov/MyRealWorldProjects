@extends('front.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="#">Shop</a></li>
                    <li class="breadcrumb-item">Checkout</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-9 pt-4">
        <div class="container">
            <form id="orderForm" name="orderForm" action="" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="sub-title">
                            <h2>Shipping Address</h2>
                        </div>
                        <div class="card shadow-lg border-0">
                            <div class="card-body checkout-form">
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" value="{{ (!empty($customerAddress) ? $customerAddress->first_name : '')}}">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" value="{{ (!empty($customerAddress) ? $customerAddress->last_name : '')}}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="email" id="email" class="form-control" placeholder="Email" value="{{ (!empty($customerAddress) ? $customerAddress->email : '' )}}" >
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <select name="country" id="country" class="form-control">
                                                <option value="">Select a Country</option>
                                                @if($countries->isNotEmpty())
                                                    @foreach($countries as $country)
                                                        <option {{ (!empty($customerAddress) && $customerAddress->country_id == $country->id) ? 'selected' : '' }} value="{{ $country->id }}">{{ $country->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="address" id="address" cols="30" rows="3" placeholder="Address"
                                                class="form-control">{{ (!empty($customerAddress) ? $customerAddress->address : '') }}</textarea>
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="apartment" id="apartment" class="form-control"
                                                placeholder="Apartment, suite, unit, etc. (optional)" value="{{ (!empty($customerAddress) ? $customerAddress->apartment : '') }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="city" id="city" class="form-control" placeholder="City" value="{{ (!empty($customerAddress) ? $customerAddress->city : '') }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="state" id="state" class="form-control" placeholder="State" value="{{ (!empty($customerAddress) ? $customerAddress->state : '') }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="zip" id="zip" class="form-control" placeholder="Zip" value="{{ (!empty($customerAddress) ? $customerAddress->zip : '') }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="mobile" id="mobile" class="form-control"
                                                placeholder="Mobile No." value="{{ (!empty($customerAddress) ? $customerAddress->mobile : '') }}">
                                            <p></p>
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="order_notes" id="order_notes" cols="30" rows="2"
                                                placeholder="Order Notes (optional)" class="form-control"></textarea>
                                            <p></p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="sub-title">
                            <h2>Order Summery</h3>
                        </div>
                        <div class="card cart-summery">
                            <div class="card-body">
                                @foreach(Cart::content() as $item)
                                    <div class="d-flex justify-content-between pb-2">
                                        <div class="h6">{{ $item->name }} X {{ $item->qty }}</div>
                                        <div class="h6">${{ $item->price * $item->qty }}</div>
                                    </div>
                                @endforeach

                                <div class="d-flex justify-content-between summery-end">
                                    <div class="h6"><strong>Subtotal</strong></div>
                                    <div class="h6"><strong id="subTotal">${{ Cart::subtotal() }}</strong></div>
                                </div>
                                <div class="d-flex justify-content-between summery-end">
                                    <div class="h6"><strong>Discount</strong></div>
                                    <div class="h6"><strong id="discountAmount">${{ number_format($discount ?? 0, 2) }}</strong></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <div class="h6"><strong>Shipping</strong></div>
                                    <div class="h6"><strong id="shippingCharge">${{ number_format($totalShippingCharge ?? 0, 2) }}</strong></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2 summery-end">
                                    <div class="h5"><strong>Total</strong></div>
                                    <div class="h5"><strong id="grandTotal">${{ number_format($grandTotal ?? 0, 2) }}</strong></div>
                                </div>
                            </div>
                        </div>

                        <div class="input-group apply-coupan mt-4">
                            <input type="text" placeholder="Coupon Code" class="form-control" name="discount_code" id="discount_code">
                            <button class="btn btn-dark" type="button" id="apply-discount">Apply Coupon</button>
                        </div>

                        <div id="discount-response-wrapper"> 
                            @if(Session::has('code'))
                                <div class="mt-4" id="discount-response">
                                    <strong>{{ Session::get('code')->code }}</strong>
                                    <a class="btn btn-sm btn-danger" id="remove-discount"><i class="fa fa-times"></i></a>
                                </div>
                            @endif 
                        </div>
                        

                        <div class="card payment-form">
                            <h3 class="card-title h5 mb-3">Payment Method</h3>

                            <div class="">
                                <input checked type="radio" name="payment_method" value="cod" id="payment_method_one" />
                                <label for="payment_method_one" class="form-check-label">COD</label>
                            </div>
                            <div class="">
                                <input type="radio" name="payment_method" value="cod" id="payment_method_two" />
                                <label for="payment_method_two" class="form-check-label">Stripe</label>
                            </div>

                            <div class="card-body p-0 d-none mt-3" id="card-payment-form">
                                <div class="mb-3">
                                    <label for="card_number" class="mb-2">Card Number</label>
                                    <input type="text" name="card_number" id="card_number" placeholder="Valid Card Number"
                                        class="form-control">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="expiry_date" class="mb-2">Expiry Date</label>
                                        <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YYYY"
                                            class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="expiry_date" class="mb-2">CVV Code</label>
                                        <input type="text" name="expiry_date" id="expiry_date" placeholder="123"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="pt-4">
                                <!-- <a href="#" class="btn-dark btn btn-block w-100">Pay Now</a> -->
                                <button type="submit" class="btn btn-dark btn-block w-100">Pay Now</button>
                            </div>
                        </div>


                        <!-- CREDIT CARD FORM ENDS HERE -->

                    </div>
                </div>
            </form>

        </div>
    </section>
@endsection

@section('customJs')
    <script>
        $("#payment_method_one").click(function(){
            if($(this).is(":checked") == true){
                $("#card-payment-form").addClass("d-none");
            }
        });
        $("#payment_method_two").click(function(){
            if($(this).is(":checked") == true){
                $("#card-payment-form").removeClass("d-none");
            }
        });

        $("#orderForm").submit(function(event){
            event.preventDefault();
            $('button[type="submit"]').prop('disabled', true);
            $.ajax({
                url: "{{ route('front.processCheckout') }}", 
                type: "post",
                data: $(this).serialize(),
                dataType: "json",
                success: function(response){
                    // Clear all previous errors
                    $('button[type="submit"]').prop('disabled', false);
                    const fields = ['first_name', 'last_name', 'email', 'country', 'address', 'apartment', 'city', 'state', 'zip', 'mobile', 'order_notes'];
                    fields.forEach(function(field) {
                        $("#" + field).removeClass('is-invalid');
                        $("#" + field).siblings("p").removeClass('invalid-feedback').html('');
                    });
                    
                    if (response.status == false) {
                        // Display errors dynamically
                        if (response.errors) {
                            Object.keys(response.errors).forEach(function(field) {
                                const errorMessage = response.errors[field][0];
                                $("#" + field).addClass('is-invalid');
                                $("#" + field).siblings("p").addClass('invalid-feedback').html(errorMessage);
                            });
                        }
                    } else {
                        // Success - redirect to thank you page
                        var orderId = response.orderId;
                        window.location.href = "{{ route('front.thankyou', ':orderId') }}".replace(':orderId', orderId);
                    }
                },
                error: function(xhr, status, error){
                    // Handle validation errors (422 status)
                    $('button[type="submit"]').prop('disabled', false);
                    if(xhr.status == 422){
                        var response = JSON.parse(xhr.responseText);
                        // Clear all previous errors
                        const fields = ['first_name', 'last_name', 'email', 'country', 'address', 'apartment', 'city', 'state', 'zip', 'mobile', 'order_notes'];
                        fields.forEach(function(field) {
                            $("#" + field).removeClass('is-invalid');
                            $("#" + field).siblings("p").removeClass('invalid-feedback').html('');
                        });
                        
                        // Display errors dynamically
                        if (response.errors) {
                            Object.keys(response.errors).forEach(function(field) {
                                const errorMessage = response.errors[field][0];
                                $("#" + field).addClass('is-invalid');
                                $("#" + field).siblings("p").addClass('invalid-feedback').html(errorMessage);
                            });
                        }
                    } else {
                        console.log(xhr.responseText);
                        alert('An error occurred. Please try again.');
                    }
                }
            });
        });

        $("#country").change(function(){
            $.ajax({
                url: "{{ route('front.getOrderSummery') }}",
                type: "post",
                data: {
                    country_id: $(this).val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                }, 
                dataType: "json",
                success: function(response){
                    if(response.status == true){
                        $("#shippingCharge").html('$' + response.shippingCharge);
                        $("#grandTotal").html('$' + response.grandTotal);
                    } else {
                        $("#shippingCharge").html('$0.00');
                        $("#grandTotal").html('$' + response.grandTotal);
                    }
                },
                error: function(xhr, status, error){
                    console.log("Something went wrong");
                }
            });
        });

        $("#apply-discount").click(function(){
            $.ajax({
                url: "{{ route('front.applyDiscount') }}",
                type: "post",
                data: {code: $("#discount_code").val(), country_id: $("#country").val(), _token: $('meta[name="csrf-token"]').attr('content')},
                dataType: "json",
                success: function(response){
                    if(response.status == true){
                        $("#discountAmount").html('$' + response.discount);
                        $("#shippingCharge").html('$' + response.shippingCharge);
                        $("#grandTotal").html('$' + response.grandTotal);
                        $("#discount-response-wrapper").html(response.discountString);
                    } else {
                        alert(response.message || "Invalid coupon code");
                    }
                },
                error: function(xhr, status, error){
                    if(xhr.responseJSON && xhr.responseJSON.message){
                        alert(xhr.responseJSON.message);
                    } else {
                        alert("Something went wrong. Please try again.");
                    }
                }
            });
        });

        // Use event delegation for dynamically added remove button
        $(document).on('click', '#remove-discount', function(e){
            e.preventDefault();
            $.ajax({
                url: "{{ route('front.removeDiscount') }}",
                type: "post",
                data: {country_id: $("#country").val(), _token: $('meta[name="csrf-token"]').attr('content')},
                dataType: "json",
                success: function(response){
                    if(response.status == true){
                        $("#discountAmount").html('$' + response.discount);
                        $("#shippingCharge").html('$' + response.shippingCharge);
                        $("#grandTotal").html('$' + response.grandTotal);
                        $("#discount-response-wrapper").html('');
                    } else {
                        alert(response.message || "Something went wrong");
                    }
                },
                error: function(xhr, status, error){
                    alert("Something went wrong. Please try again.");
                }
            });
        });
    </script>
@endsection