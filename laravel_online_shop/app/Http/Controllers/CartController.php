<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Country;
use App\Models\ShippingCharge;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        // Validate input
        if(empty($request->id)){
            return response()->json([
                'status' => false,
                'message' => 'Product ID is required'
            ]);
        }

        $product = Product::with('productImages')->find($request->id);
        if($product == null){
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ]);
        }

        // Check if product is active
        if($product->status != 1){
            return response()->json([
                'status' => false,
                'message' => 'Product is not available'
            ]);
        }
        
        $productAlreadyExists = false;
        if(Cart::count() > 0){
            $cartContent = Cart::content();
            foreach($cartContent as $item){
                if($item->id == $product->id){
                    $productAlreadyExists = true;
                    break;
                }
            }   
        }
        
        if($productAlreadyExists == false){
            // Get product image
            $productImage = '';
            if(!empty($product->productImages) && $product->productImages->count() > 0){
                $firstImage = $product->productImages->first();
                if(!empty($firstImage) && !empty($firstImage->image)){
                    $productImage = $firstImage->image;
                }
            }
            
            Cart::add(
                $product->id,
                $product->title,
                1,
                $product->price,
                [
                    'productImage' => $productImage
                ]
            );
            $status = true;
            $message = '<strong>'.$product->title.'</strong> added in cart successfully';
            session()->flash('success', $message);
        } else {
            $status = false;
            $message = '<strong>'.$product->title.'</strong> already added in cart';
            session()->flash('error', $message);
        }
        
        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }
    public function cart()
    {
        $cartContent = Cart::content();
        
        // Calculate subtotal manually to ensure accuracy
        $subtotal = 0;
        foreach($cartContent as $item) {
            $subtotal += $item->price * $item->qty;
        }
        
        // Shipping cost (can be configured later)
        $shipping = 0;
        
        // Total = Subtotal + Shipping
        $total = $subtotal + $shipping;
        
        $data['cartContent'] = $cartContent;
        $data['cartCount'] = Cart::count();
        $data['cartSubtotal'] = $subtotal;
        $data['cartShipping'] = $shipping;
        $data['cartTotal'] = $total;
        return view('front.cart', $data);
    }
    public function updateCart(Request $request)
    {
        $rowId = $request->rowId;
        $qty = $request->qty;

        // Validate input
        if(empty($rowId) || empty($qty)){
            $message = '<strong>Invalid request.</strong> Please try again.';
            session()->flash('error', $message);
            return response()->json([
                'status' => false,
                'message' => $message
            ]);
        }

        // Get cart item
        $itemInfo = Cart::get($rowId);
        if(empty($itemInfo)){
            $message = '<strong>Item not found in cart.</strong>';
            session()->flash('error', $message);
            return response()->json([
                'status' => false,
                'message' => $message
            ]);
        }

        // Get product from database
        $product = Product::find($itemInfo->id);
        if(empty($product)){
            $message = '<strong>Product not found.</strong>';
            session()->flash('error', $message);
            return response()->json([
                'status' => false,
                'message' => $message
            ]);
        }

        // Validate quantity
        $qty = (int)$qty;
        if($qty < 1){
            $message = '<strong>Quantity must be at least 1.</strong>';
            session()->flash('error', $message);
            return response()->json([
                'status' => false,
                'message' => $message
            ]);
        }

        // Check if product tracks quantity
        if($product->track_qty == "Yes"){
            if($qty <= $product->qty){
                Cart::update($rowId, $qty);
                $message = '<strong>Cart updated successfully!</strong>';
                session()->flash('success', $message);
                $status = true;
            } else {
                $message = '<strong>Requested quantity ('.$qty.') is not available.</strong> Only '.$product->qty.' available.';
                session()->flash('error', $message);
                $status = false;
            }
        } else {
            Cart::update($rowId, $qty);
            $message = '<strong>Cart updated successfully!</strong>';
            session()->flash('success', $message);
            $status = true;
        } 

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }
    public function deleteItem(Request $request)
    {
        $rowId = $request->rowId;

        // Validate input
        if(empty($rowId)){
            $message = '<strong>Invalid request.</strong> Please try again.';
            session()->flash('error', $message);
            return response()->json([
                'status' => false,
                'message' => $message
            ]);
        }

        // Get cart item to verify it exists
        $itemInfo = Cart::get($rowId);

        if($itemInfo == null){
            $message = '<strong>Item not found in cart.</strong>';
            session()->flash('error', $message);
            return response()->json([
                'status' => false,
                'message' => $message
            ]);
        }

        // Remove item from cart
        Cart::remove($rowId);
        
        $message = '<strong>Item deleted successfully!</strong>';
        session()->flash('success', $message);
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    public function checkout()
    {
        // if cart is empty, redirect to cart page
        if(Cart::count() == 0){
            return redirect()->route('front.cart');
        }
        
        // if user is not logged in, redirect to login page
        if(Auth::check() == false){
            if(!session()->has('url.intented')){
                session(['url.intented' => route('front.checkout')]);
            }
            return redirect()->route('account.login');
        }

        $customerAddress = CustomerAddress::where('user_id', Auth::user()->id)->first();

        session()->forget('url.intented');

        $countries = Country::orderBy('name', 'ASC')->get();

        // Calculate shipping here
        $totalQty = 0;
        $totalShippingCharge = 0;
        $grandTotal = 0;
        
        foreach(Cart::content() as $item){
            $totalQty += $item->qty;
        }
        
        // If customer has address, calculate shipping based on country
        if(!empty($customerAddress) && !empty($customerAddress->country_id)){
            $userCountry = $customerAddress->country_id;
            
            // Try to find specific shipping for user's country
            $shippingInfo = ShippingCharge::where('country_id', $userCountry)->first();
            // Fallback to "rest_of_world" if no specific record
            if(empty($shippingInfo)){
                $shippingInfo = ShippingCharge::where('country_id', 'rest_of_world')->first();
            }
            
            // If we have shipping info, calculate per-quantity charge; otherwise keep 0
            if(!empty($shippingInfo)){
                $totalShippingCharge = $totalQty * $shippingInfo->amount;
            }
        }

        $grandTotal = Cart::subtotal(2, '.', '') + $totalShippingCharge;

        return view('front.checkout', [
            'countries' => $countries,
            'customerAddress' => $customerAddress,
            'totalShippingCharge' => $totalShippingCharge,
            'grandTotal' => $grandTotal 
        ]);
    }
    public function processCheckout(Request $request)
    {
        // Check if cart is empty
        if(Cart::count() == 0){
            return response()->json([
                'status' => false,
                'message' => 'Your cart is empty',
            ]);
        }

        // Apply Validaiton 
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255|min:3',
            'last_name' => 'required|string|max:255|min:3',
            'email' => 'required|email|string',
            'country' => 'required',
            'address' => 'required|min:5',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'mobile' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Please fix the errors',
                'errors' => $validator->errors(),
            ]);
        } 

        // Save user address
        
        $user = Auth::user();
        
        CustomerAddress::updateOrCreate(
            ['user_id' => $user->id],
            [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'country_id' => $request->country,
                'address' => $request->address,
                'apartment' => $request->apartment ?? null,
                'city' => $request->city,
                'state' => $request->state,
                'zip' => $request->zip,
            ]
        );

        // Store data in orders table 
        if($request->payment_method == 'cod'){

            // Calculate Shipping 
            $subTotal = Cart::subtotal(2, '.', '');
            $discount = 0;
            
            $shippingInfo = ShippingCharge::where('country_id', $request->country)->first();

            $totalQty = 0;
            foreach(Cart::content() as $item){
                $totalQty += $item->qty;
            }

            $shippingCharge = 0;
            if($shippingInfo != null){
                $shippingCharge = $totalQty * $shippingInfo->amount;
            } else {
                // Fallback to "rest_of_world" if no specific record
                $shippingInfo = ShippingCharge::where('country_id', 'rest_of_world')->first();
                if(!empty($shippingInfo)){
                    $shippingCharge = $totalQty * $shippingInfo->amount;
                }
            }

            $grandTotal = $subTotal + $shippingCharge;

            $order = new Order();
            $order->subtotal = $subTotal;
            $order->shipping = $shippingCharge;
            $order->grand_total = $grandTotal;
            $order->user_id = $user->id;
            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            $order->mobile = $request->mobile;
            $order->address = $request->address;
            $order->apartment = $request->apartment ?? null;
            $order->state = $request->state;
            $order->city = $request->city;
            $order->zip = $request->zip;
            $order->notes = !empty($request->order_notes) ? $request->order_notes : '';
            $order->country_id = $request->country;
            $order->save();

            // Store order items in order items table
            foreach(Cart::content() as $item){
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item->id;
                $orderItem->name = $item->name;
                $orderItem->qty = $item->qty;
                $orderItem->price = $item->price;
                $orderItem->total = $item->price * $item->qty;
                $orderItem->save();
            }

            // Clear cart after successful order
            Cart::destroy();

            session()->flash('success', 'You have successfully placed your order.');

            return response()->json([
                'message' => 'Order saved successfully',
                'orderId' => $order->id,
                'status' => true,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Payment method not supported yet',
            ]);
        }
    }
    public function thankyou($orderId)
    {
        $order = Order::with('orderItems')->find($orderId);
        
        if(empty($order)){
            return redirect()->route('front.home');
        }

        // Check if user is authenticated and owns this order
        if(Auth::check() && Auth::id() != $order->user_id){
            return redirect()->route('front.home');
        }

        return view('front.thanks', [
            'order' => $order
        ]);
    }
    public function getOrderSummery(Request $request)
    {
        $subTotal = Cart::subtotal(2, '.', '');

        if($request->country_id > 0){

            $shippingInfo = ShippingCharge::where('country_id', $request->country_id)->first();

            $totalQty = 0;
            foreach(Cart::content() as $item){
                $totalQty += $item->qty;
            }

            if($shippingInfo != null){

                $shippingCharge = $totalQty * $shippingInfo->amount;

                $grandTotal = $subTotal + $shippingCharge;
                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal, 2),
                    'shippingCharge' => number_format($shippingCharge, 2),
                ]);
            } else {
                // Fallback to "rest_of_world" if no specific record
                $shippingInfo = ShippingCharge::where('country_id', 'rest_of_world')->first();
                
                $shippingCharge = 0;
                if(!empty($shippingInfo)){
                    $shippingCharge = $totalQty * $shippingInfo->amount;
                }
                
                $grandTotal = $subTotal + $shippingCharge; 

                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal, 2),
                    'shippingCharge' => number_format($shippingCharge, 2),
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'grandTotal' => number_format(0, 2),
                'shippingCharge' => number_format(0, 2),
            ]);
        }
    }
}
