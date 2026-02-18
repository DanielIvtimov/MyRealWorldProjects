<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

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
    public function changeOrderStatus(Request $request, $id)
    {
        $order = Order::find($id);
        
        if(empty($order)){
            return response()->json([
                'status' => false,
                'message' => 'Order not found',
            ]);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,shipped,delivered,cancelled',
            'shipping_date' => 'nullable|date',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Please fix the errors',
                'errors' => $validator->errors(),
            ]);
        }

        $order->status = $request->status;
        
        if(!empty($request->shipping_date)){
            $order->shipping_date = Carbon::parse($request->shipping_date);
        } else {
            $order->shipping_date = null;
        }
        
        $order->save();

        $message = 'Order status changed successfully';

        session()->flash('success', $message);

        return response()->json([
            'status' => 'success',
            'message' => $message,
        ]);
    }
}
