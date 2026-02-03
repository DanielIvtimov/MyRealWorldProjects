@extends('front.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.shop') }}">Shop</a></li>
                    <li class="breadcrumb-item">Cart</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-9 pt-4">
        <div class="container">
            <div class="row">
                @if(Session::has('success'))
                    <div class="col-md-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {!! Session::get('success') !!}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif
                @if(Session::has('error'))
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {!! Session::get('error') !!}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif
                <div class="col-md-8">
                    <div class="table-responsive">
                        <table class="table" id="cart">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($cartContent) && $cartContent->count() > 0)
                                    @foreach($cartContent as $rowId => $item)
                                        <tr>
                                            <td class="text-start">
                                                <div class="d-flex align-items-center">
                                                    @php
                                                        $productImage = '';
                                                        if(!empty($item->options)) {
                                                            if(is_object($item->options) && isset($item->options->productImage)) {
                                                                $productImage = $item->options->productImage;
                                                            }
                                                            elseif(is_array($item->options) && isset($item->options['productImage'])) {
                                                                $productImage = $item->options['productImage'];
                                                            }
                                                            elseif(method_exists($item->options, 'get')) {
                                                                $productImage = $item->options->get('productImage', '');
                                                            }
                                                        }
                                                    @endphp
                                                    @if(!empty($productImage))
                                                        <img src="{{ asset('uploads/product/small/'.$productImage) }}" width="100" height="100" alt="{{ $item->name }}" style="object-fit: cover;">
                                                    @else
                                                        <img src="{{ asset('admin-assets/img/default-150x150.png') }}" width="100" height="100" alt="{{ $item->name }}" style="object-fit: cover;">
                                                    @endif 
                                                    <h2 class="ml-3">{{ $item->name ?? 'N/A' }}</h2>
                                                </div>
                                            </td>
                                            <td>${{ number_format($item->price, 2) }}</td>
                                            <td>
                                                <div class="input-group quantity mx-auto" style="width: 100px;">
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-sm btn-dark btn-minus p-2 pt-1 pb-1 sub" data-id="{{ $item->rowId }}">
                                                            <i class="fa fa-minus"></i>
                                                        </button>
                                                    </div>
                                                    <input type="text" class="form-control form-control-sm  border-0 text-center qty-input" value="{{ $item->qty ?? 1 }}" data-rowid="{{ $item->rowId }}" readonly>
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-sm btn-dark btn-plus p-2 pt-1 pb-1 add" data-id="{{ $item->rowId }}">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                ${{ number_format($item->price * $item->qty, 2) }}
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-danger" onClick="deleteItem('{{ $item->rowId }}')" ><i class="fa fa-times"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center">Your cart is empty</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card cart-summery">
                        <div class="sub-title">
                            <h2 class="bg-white">Cart Summery</h3>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between pb-2">
                                <div>Subtotal</div>
                                <div>${{ number_format($cartSubtotal ?? 0, 2) }}</div>
                            </div>
                            <div class="d-flex justify-content-between pb-2">
                                <div>Shipping</div>
                                <div>${{ number_format($cartShipping ?? 0, 2) }}</div>
                            </div>
                            <div class="d-flex justify-content-between summery-end">
                                <div><strong>Total</strong></div>
                                <div><strong>${{ number_format($cartTotal ?? 0, 2) }}</strong></div>
                            </div>
                            <div class="pt-5">
                                @if(!empty($cartContent) && $cartContent->count() > 0)
                                    <a href="login.php" class="btn-dark btn btn-block w-100">Proceed to Checkout</a>
                                @else
                                    <a href="{{ route('front.shop') }}" class="btn-dark btn btn-block w-100">Continue Shopping</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('customJs')
    <script>
        $(document).ready(function(){
            $('.add').click(function(){
                let qtyElement = $(this).parent().prev();
                let qtyValue = parseInt(qtyElement.val()); 
                if(qtyValue < 10){
                    let rowId = $(this).data('id');
                    let newQty = qtyValue + 1;
                    qtyElement.val(newQty);
                    updateCart(rowId, newQty);
                }
            });

            $('.sub').click(function(){
                let qtyElement = $(this).parent().next();
                let qtyValue = parseInt(qtyElement.val()); 
                if(qtyValue > 1){
                    let rowId = $(this).data('id');
                    let newQty = qtyValue - 1;
                    qtyElement.val(newQty);
                    updateCart(rowId, newQty);
                }
            });
        });

        function updateCart(rowId, qty){
            // Disable buttons during update
            $('.add, .sub').prop('disabled', true);
            
            $.ajax({
                url: '{{ route('front.updateCart')}}',
                type: 'post',
                data: {
                    rowId: rowId, 
                    qty: qty,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(response){
                    // Always redirect to show Bootstrap alerts
                    window.location.href = "{{ route('front.cart') }}";
                },
                error: function(xhr, status, error){
                    // Redirect to cart page - error will be shown via session if set by server
                    // If no session error is set, a generic one can be added in the controller
                    window.location.href = "{{ route('front.cart') }}";
                },
                complete: function(){
                    // Re-enable buttons
                    $('.add, .sub').prop('disabled', false);
                }
            })
        }

        function deleteItem(rowId){
            if(confirm("Are you sure you want to delete this item?")){
                // Disable delete button during request
                $('button[onClick*="' + rowId + '"]').prop('disabled', true);
                
                $.ajax({
                    url: "{{ route('front.deleteItem.cart') }}",
                    type: "post",
                    data: {
                        rowId: rowId,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: "json",
                    success: function(response){
                        // Always redirect to show Bootstrap alerts
                        window.location.href = "{{ route('front.cart') }}";
                    },
                    error: function(xhr, status, error){
                        // Redirect to show error message
                        window.location.href = "{{ route('front.cart') }}";
                    }
                });
            }
        }
    </script>                                                        
@endsection 