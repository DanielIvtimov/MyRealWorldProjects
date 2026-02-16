@extends("admin.layouts.app")

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Orders</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        
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
                <div class="card">
                    <form action="{{ route('orders.index') }}" method="get">
                        <div class="card-header">
                            <a href="{{ route('orders.index') }}" class="btn btn-default btn-sm">Reset</a>
                            <div class="card-tools">
                                <div class="input-group input-group" style="width: 250px;">
                                    <input value="{{ request('keyword') }}" type="text" name="keyword" class="form-control float-right" placeholder="Search">

                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th width="60">Order#</th>
                                    <th>Customer</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                    <th>Date Purchased</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($orders->isNotEmpty())
                                    @foreach($orders as $order)
                                        <tr>
                                            <td><a href="{{ route('orders.detail', $order->id) }}">{{ $order->id }}</a></td>
                                            <td>
                                                @if(!empty($order->user_name))
                                                    {{ $order->user_name }}
                                                @else
                                                    {{ $order->first_name }} {{ $order->last_name }}
                                                @endif
                                            </td>
                                            <td>
                                                @if(!empty($order->user_email))
                                                    {{ $order->user_email }}
                                                @else
                                                    {{ $order->email }}
                                                @endif
                                            </td>
                                            <td>{{ $order->mobile }}</td>
                                            <td>
                                                @if($order->status == 'pending') 
                                                    <span class="badge bg-danger">Pending</span>
                                                @elseif($order->status == "shipped")
                                                    <span class="badge bg-info">Shipped</span>
                                                @elseif($order->status == "delivered")
                                                    <span class="badge bg-success">Delivered</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                                @endif
                                            </td>
                                            <td>${{ number_format($order->grand_total, 2) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y')}}</td>
                                            <!-- <td>
                                                <a href="{{ route('categories.edit', $order->id )}}">
                                                    <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path
                                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                                        </path>
                                                    </svg>
                                                </a>
                                                <a href="#" onclick="deleteCategory({{$order->id}})" class="text-danger w-4 h-4 mr-1">
                                                    <svg wire:loading.remove.delay="" wire:target=""
                                                        class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path ath fill-rule="evenodd"
                                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                </a>
                                            </td> -->
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">Records Not Found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer clearfix">
                        {{$orders->links()}}
                        
                    <!-- <ul class="pagination pagination m-0 float-right">
                        <li class="page-item"><a class="page-link" href="#">«</a></li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">»</a></li>
                    </ul> -->
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </section>
        <!-- /.content -->
@endsection

@section('customJs')
<script>
    
</script>
@endsection