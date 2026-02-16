<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::select('orders.*', 'users.name as user_name', 'users.email as user_email')
            ->leftJoin('users', 'orders.user_id', '=', 'users.id')
            ->latest('orders.created_at');
        
        if($request->get('keyword') != ""){
            $keyword = $request->get('keyword');
            $orders = $orders->where(function($query) use ($keyword) {
                $query->where('users.name', 'like', '%' . $keyword . '%')
                      ->orWhere('users.email', 'like', '%' . $keyword . '%')
                      ->orWhere('orders.id', 'like', '%' . $keyword . '%');
            });
        }
        
        $orders = $orders->paginate(10);

        return view('admin.orders.list', [
            'orders' => $orders,
        ]);
    }
    public function detail($orderId) 
    {
        $order = Order::select('orders.*', 'countries.name as countryName')
            ->where('orders.id', $orderId)
            ->leftJoin('countries', 'orders.country_id', '=', 'countries.id')
            ->with('orderItems')
            ->first();
        
        if(empty($order)){
            return redirect()->route('orders.index')->with('error', 'Order not found');
        }

        $orderItems = OrderItem::where('order_id', $orderId)->get();
        
        return view('admin.orders.detail', [
            'order' => $order,
            'orderItems' => $orderItems,
        ]);
    }
}
