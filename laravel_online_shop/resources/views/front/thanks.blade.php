@extends('front.layouts.app')

@section('content')
    <section class="section-9 pt-4">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-center py-5">
                        @if(Session::has('success'))
                            <div class="alert alert-success">
                                {{ Session::get('success') }}
                            </div>
                        @endif
                        <h1>Thank you!</h1>
                        @if(!empty($order))
                            <p class="lead">Your Order ID is: <strong>#{{ $order->id }}</strong></p>
                            <p>We have received your order and will process it shortly.</p>
                        @else
                            <p>Order information not available.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

