@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Order: #{{ $order->id }}</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('orders.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-9">
                    @include('admin.message')
                    <div class="card">
                        <div class="card-header pt-3">
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                    <h1 class="h5 mb-3">Shipping Address</h1>
                                    <address>
                                        <strong>{{ $order->first_name.' '.$order->last_name }}</strong><br>
                                        {{ $order->address}}<br>
                                        @if(!empty($order->apartment))
                                            {{ $order->apartment }},<br>
                                        @endif
                                        {{ $order->city}}, {{ $order->state }} {{ $order->zip }}<br>
                                        @if(!empty($order->countryName))
                                            {{ $order->countryName }}<br>
                                        @endif
                                        Phone: {{ $order->mobile }}<br>
                                        Email: {{ $order->email }}
                                    </address>
                                    <strong>Shipping Date:</strong><br />
                                    @if(!empty($order->shipping_date))
                                        {{ \Carbon\Carbon::parse($order->shipping_date)->format('d M, Y') }}
                                    @else 
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </div>



                                <div class="col-sm-4 invoice-col">
                                    <b>Order ID:</b> {{ $order->id }}<br>
                                    <b>Order Date:</b> {{ \Carbon\Carbon::parse($order->created_at)->format('d M, Y') }}<br>
                                    <b>Subtotal:</b> ${{ number_format($order->subtotal, 2) }}<br>
                                    @if($order->discount > 0)
                                    <b>Discount:</b> -${{ number_format($order->discount, 2) }}<br>
                                    @endif
                                    <b>Shipping:</b> ${{ number_format($order->shipping, 2) }}<br>
                                    <b>Total:</b> ${{ number_format($order->grand_total, 2) }}<br>
                                    <!-- <b>Payment Status:</b> 
                                    @if($order->payment_status == 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @else
                                        <span class="badge bg-danger">Not Paid</span>
                                    @endif -->
                                    <!-- <br> -->
                                    <b>Order Status:</b> 
                                    @if($order->status == 'pending') 
                                        <span class="text-danger">Pending</span>
                                    @elseif($order->status == "shipped")
                                        <span class="text-info">Shipped</span>
                                    @elseif($order->status == "delivered")
                                        <span class="text-success">Delivered</span>
                                    @else
                                        <span class="text-secondary">{{ ucfirst($order->status) }}</span>
                                    @endif
                                    <br>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-3">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th width="100">Price</th>
                                        <th width="100">Qty</th>
                                        <th width="100">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($orderItems->isNotEmpty())
                                    @foreach($orderItems as $item)
                                        <tr>
                                            <td>{{ $item->name }}</td>
                                            <td>${{ number_format($item->price, 2) }}</td>
                                            <td>{{ $item->qty }}</td>
                                            <td>${{ number_format($item->total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="4" class="text-center">No order items found</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th colspan="3" class="text-right">Subtotal:</th>
                                        <td>${{ number_format($order->subtotal, 2) }}</td>
                                    </tr>
                                    @if($order->discount > 0)
                                    <tr>
                                        <th colspan="3" class="text-right">Discount{{ (!empty($order->promo_code)) ? ' ('.$order->promo_code.')' : '' }}:</th>
                                        <td>-${{ number_format($order->discount, 2) }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th colspan="3" class="text-right">Shipping:</th>
                                        <td>${{ number_format($order->shipping, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-right">Grand Total:</th>
                                        <td>${{ number_format($order->grand_total, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Order Status</h2>
                            <form id="changeOrderStatusForm" name="changeOrderStatusForm" method="post">
                                @csrf
                                <div class="mb-3">
                                    <select name="status" id="status" class="form-control">
                                        <option value="pending" {{ ($order->status == 'pending') ? 'selected' : '' }}>Pending</option>
                                        <option value="shipped" {{ ($order->status == 'shipped') ? 'selected' : '' }}>Shipped</option>
                                        <option value="delivered" {{ ($order->status == 'delivered') ? 'selected' : '' }}>Delivered</option>
                                        <option value="cancelled" {{ ($order->status == 'cancelled') ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="shipping_date">Shipping Date</label>
                                    <input placeholder="Shipped Date" type="text" name="shipping_date" id="shipping_date" class="form-control" value="{{ !empty($order->shipping_date) ? \Carbon\Carbon::parse($order->shipping_date)->format('Y-m-d H:i:s') : '' }}" />
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="POST" name="sendInvoiceEmail" id="sendInvoiceEmail">
                                @csrf
                                <h2 class="h4 mb-3">Send Invoice Email</h2>
                                <div class="mb-3">
                                    <select name="userType" id="userType" class="form-control">
                                        <option value="customer">Customer</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">Send</button>
                                </div>
                            </form>
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
        $(document).ready(function(){
            $('#shipping_date').datetimepicker({
                format: 'Y-m-d H:i:s',
            });
        });

        $("#changeOrderStatusForm").submit(function(e){
            e.preventDefault();
            if(confirm('Are you sure you want to change order status?')){
                $.ajax({
                    url: "{{ route('orders.changeOrderStatus', $order->id) }}",
                    type: "POST",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function(response){
                        if(response.status == 'success'){
                            window.location.href = "{{ route('orders.detail', $order->id) }}";
                        }
                    },
                    error: function(xhr, status, error){
                        if(xhr.responseJSON && xhr.responseJSON.message){
                            alert(xhr.responseJSON.message);
                        } else {
                            alert('An error occurred. Please try again.');
                        }
                    }
                });
            }
        });

        $("#sendInvoiceEmail").submit(function(e){
            e.preventDefault();
            
            if(confirm('Are you sure you want to send email?')){
                $.ajax({
                    url: "{{ route('orders.sendInvoiceEmail', $order->id) }}",
                    type: "POST",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function(response){
                        if(response.status == true){
                            alert(response.message);
                            window.location.href = "{{ route('orders.detail', $order->id) }}";
                        } else {
                            alert(response.message || 'Failed to send email. Please try again.');
                        }
                    },
                    error: function(xhr, status, error){
                        if(xhr.responseJSON && xhr.responseJSON.message){
                            alert(xhr.responseJSON.message);
                        } else {
                            alert('An error occurred while sending email. Please try again.');
                        }
                    }
                });
            }
        });
    </script>
@endsection