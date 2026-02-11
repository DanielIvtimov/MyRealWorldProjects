<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Country;
use App\Models\ShippingCharge;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CustomerAddress;
use App\Models\DiscountCoupon;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

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
        
        // Check if product has stock (if tracking quantity)
        if($product->track_qty == "Yes" && $product->qty <= 0){
            return response()->json([
                'status' => false,
                'message' => 'Product is out of stock'
            ]);
        }
        
        // Validate product price
        $productPrice = (float) $product->price;
        if($productPrice < 0){
            return response()->json([
                'status' => false,
                'message' => 'Invalid product price'
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
                $productPrice,
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
            // Ensure price and qty are numeric and positive
            $price = (float) $item->price;
            $qty = (int) $item->qty;
            if($price < 0) $price = 0;
            if($qty < 0) $qty = 0;
            $subtotal += $price * $qty;
        }
        
        // Round subtotal to 2 decimal places
        $subtotal = round($subtotal, 2);
        
        // Shipping cost (can be configured later)
        $shipping = 0;
        
        // Total = Subtotal + Shipping
        $total = round($subtotal + $shipping, 2);
        
        // Ensure values are not negative
        if($subtotal < 0) $subtotal = 0;
        if($total < 0) $total = 0;
        
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

        // Validate product quantity (ensure it's not negative)
        $productQty = (int) $product->qty;
        if($productQty < 0){
            $productQty = 0;
        }
        
        // Check if product tracks quantity
        if($product->track_qty == "Yes"){
            if($qty <= $productQty){
                Cart::update($rowId, $qty);
                $message = '<strong>Cart updated successfully!</strong>';
                session()->flash('success', $message);
                $status = true;
            } else {
                $message = '<strong>Requested quantity ('.$qty.') is not available.</strong> Only '.$productQty.' available.';
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
        $discount = 0;
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

        // Calculate shipping here - convert string to float for calculations
        $subTotal = (float) Cart::subtotal(2, '.', '');
        $totalQty = 0;
        $totalShippingCharge = 0;
        $grandTotal = 0;
        
        foreach(Cart::content() as $item){
            $totalQty += $item->qty;
        }
        
        // Calculate discount if coupon is applied
        if(session()->has('code')){
            $code = session()->get('code');
            if($code->type == "percent"){
                $discount = ($code->discount_amount / 100) * $subTotal;
            } else {
                $discount = (float) $code->discount_amount;
            }
            // Ensure discount doesn't exceed subtotal
            if($discount > $subTotal){
                $discount = $subTotal;
            }
            // Ensure discount is not negative
            if($discount < 0){
                $discount = 0;
            }
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
            if(!empty($shippingInfo) && !empty($shippingInfo->amount)){
                $totalShippingCharge = $totalQty * (float) $shippingInfo->amount;
                // Ensure shipping is not negative
                if($totalShippingCharge < 0){
                    $totalShippingCharge = 0;
                }
            }
        }

        // Calculate grand total with proper precision
        $grandTotal = round(($subTotal - $discount) + $totalShippingCharge, 2);
        // Ensure grand total is not negative
        if($grandTotal < 0){
            $grandTotal = 0;
        }

        return view('front.checkout', [
            'countries' => $countries,
            'customerAddress' => $customerAddress,
            'totalShippingCharge' => $totalShippingCharge,
            'discount' => $discount,
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
        $discountString = '';
        
        if($request->payment_method == 'cod'){

            // Calculate Shipping - convert string to float for calculations
            $discountCodeId = null;
            $promoCode = null;
            $discount = 0;
            $subTotal = (float) Cart::subtotal(2, '.', '');
            
            
            // Calculate discount if coupon is applied
            if(session()->has('code')){
                $code = session()->get('code');
                if($code->type == "percent"){
                    $discount = ($code->discount_amount / 100) * $subTotal;
                } else {
                    $discount = (float) $code->discount_amount;
                }
                // Ensure discount doesn't exceed subtotal
                if($discount > $subTotal){
                    $discount = $subTotal;
                }
                // Ensure discount is not negative
                if($discount < 0){
                    $discount = 0;
                }

                // Store discount code information
                $discountCodeId = (int) $code->id;
                $promoCode = (string) $code->code;

                $discountString = '<div class="mt-4" id="discount-response">
                    <strong>'.session()->get('code')->code.'</strong>
                    <a class="btn btn-sm btn-danger" id="remove-discount"><i class="fa fa-times"></i></a>
                </div>';
            }
            
            // Get shipping info - country_id can be integer or string
            $shippingInfo = ShippingCharge::where('country_id', $request->country)->first();

            $totalQty = 0;
            foreach(Cart::content() as $item){
                $totalQty += $item->qty;
            }

            $shippingCharge = 0;
            if(!empty($shippingInfo) && !empty($shippingInfo->amount)){
                $shippingCharge = $totalQty * (float) $shippingInfo->amount;
            } else {
                // Fallback to "rest_of_world" if no specific record
                $shippingInfo = ShippingCharge::where('country_id', 'rest_of_world')->first();
                if(!empty($shippingInfo) && !empty($shippingInfo->amount)){
                    $shippingCharge = $totalQty * (float) $shippingInfo->amount;
                }
            }
            
            // Ensure shipping is not negative
            if($shippingCharge < 0){
                $shippingCharge = 0;
            }

            // Calculate grand total with proper precision
            $grandTotal = round(($subTotal - $discount) + $shippingCharge, 2);
            // Ensure grand total is not negative
            if($grandTotal < 0){
                $grandTotal = 0;
            }

            $order = new Order();
            // Ensure all values are properly formatted and not negative
            $order->subtotal = round((float) $subTotal, 2);
            $order->discount = round((float) $discount, 2);
            // Set discount code information if coupon was applied
            if($discountCodeId !== null){
                $order->discount_code_id = $discountCodeId;
            }
            if($promoCode !== null){
                $order->promo_code = $promoCode;
            }
            $order->shipping = round((float) $shippingCharge, 2);
            $order->grand_total = round((float) $grandTotal, 2);
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
                // Ensure all values are properly formatted
                $itemPrice = (float) $item->price;
                $itemQty = (int) $item->qty;
                
                // Ensure values are not negative
                if($itemPrice < 0) $itemPrice = 0;
                if($itemQty < 0) $itemQty = 0;
                
                $itemTotal = round($itemPrice * $itemQty, 2);
                
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item->id;
                $orderItem->name = $item->name;
                $orderItem->qty = $itemQty;
                $orderItem->price = round($itemPrice, 2);
                $orderItem->total = $itemTotal;
                $orderItem->save();
            }

            // Clear cart after successful order
            Cart::destroy();

            session()->flash('success', 'You have successfully placed your order.');

            return response()->json([
                'message' => 'Order saved successfully',
                'orderId' => $order->id,
                'discountString' => $discountString,
                'status' => true,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Payment method not supported yet',
                'discountString' => $discountString,
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
        // Convert string to float for calculations
        $subTotal = (float) Cart::subtotal(2, '.', '');
        $discount = 0;
        $discountString = '';

        // Apply Discount here
        if(session()->has('code')){
            $code = session()->get('code');
            if($code->type == "percent"){
                $discount = ($code->discount_amount / 100) * $subTotal;
            } else {
                $discount = (float) $code->discount_amount;
            }
            // Ensure discount doesn't exceed subtotal
            if($discount > $subTotal){
                $discount = $subTotal;
            }
            // Ensure discount is not negative
            if($discount < 0){
                $discount = 0;
            }
            $discountString = '<div class="mt-4" id="discount-response">
                <strong>'.session()->get('code')->code.'</strong>
                <a class="btn btn-sm btn-danger" id="remove-discount"><i class="fa fa-times"></i></a>
            </div>';
        }

        // Check if country_id is provided (can be integer or string like 'rest_of_world')
        if(!empty($request->country_id)){

            $shippingInfo = ShippingCharge::where('country_id', $request->country_id)->first();

            $totalQty = 0;
            foreach(Cart::content() as $item){
                $totalQty += $item->qty;
            }

            $shippingCharge = 0;
            if(!empty($shippingInfo) && !empty($shippingInfo->amount)){
                $shippingCharge = $totalQty * (float) $shippingInfo->amount;
            } else {
                // Fallback to "rest_of_world" if no specific record
                $shippingInfo = ShippingCharge::where('country_id', 'rest_of_world')->first();
                if(!empty($shippingInfo) && !empty($shippingInfo->amount)){
                    $shippingCharge = $totalQty * (float) $shippingInfo->amount;
                }
            }
            
            // Ensure shipping is not negative
            if($shippingCharge < 0){
                $shippingCharge = 0;
            }
            
            // Calculate grand total with proper precision
            $grandTotal = round(($subTotal - $discount) + $shippingCharge, 2);
            // Ensure grand total is not negative
            if($grandTotal < 0){
                $grandTotal = 0;
            }

            return response()->json([
                'status' => true,
                'grandTotal' => number_format($grandTotal, 2),
                'discount' => number_format($discount, 2),
                'discountString' => $discountString,
                'shippingCharge' => number_format($shippingCharge, 2),
            ]);
        } else {
            // Calculate grand total even when no country is selected (without shipping)
            $grandTotal = round($subTotal - $discount, 2);
            if($grandTotal < 0){
                $grandTotal = 0;
            }
            
            return response()->json([
                'status' => false,
                'grandTotal' => number_format($grandTotal, 2),
                'discount' => number_format($discount, 2),
                'shippingCharge' => number_format(0, 2),
                'discountString' => $discountString,
            ]);
        }
    }
    public function applyDiscount(Request $request)
    {
        // Validate coupon code input
        if(empty($request->code)){
            return response()->json([
                'status' => false,
                'message' => 'Coupon code is required',
            ]);
        }

        $code = DiscountCoupon::where('code', $request->code)->first();
        if($code == null){
            return response()->json([
                'status' => false,
                'message' => 'Invalid coupon code',
            ]);
        }

        // Check if coupon is active
        if($code->status != 1){
            return response()->json([
                'status' => false,
                'message' => 'Coupon is not active',
            ]);
        }

        // Check minimum amount requirement
        $subTotal = (float) Cart::subtotal(2, '.', '');
        if(!empty($code->min_amount) && $subTotal < (float) $code->min_amount){
            return response()->json([
                'status' => false,
                'message' => 'Minimum order amount of $'.number_format($code->min_amount, 2).' required for this coupon',
            ]);
        }

        // Check if coupon start date is valid
        $now = Carbon::now();
        // Only check starts_at if it's not null and not empty
        if($code->starts_at !== null && $code->starts_at !== ''){
            try {
                // Try to parse as Carbon if it's already a Carbon instance, otherwise parse as string
                $startDate = $code->starts_at instanceof Carbon ? $code->starts_at : Carbon::parse($code->starts_at);
                // If current time is less than start date, coupon is not yet valid
                if($now->lt($startDate)){
                    return response()->json([
                        'status' => false,
                        'message' => 'Coupon is not yet valid',
                    ]);
                }
            } catch (\Exception $e) {
                // If date parsing fails, skip this check
            }
        }

        // Check if coupon has expired
        if($code->expires_at !== null && $code->expires_at !== ''){
            try {
                // Try to parse as Carbon if it's already a Carbon instance, otherwise parse as string
                $endDate = $code->expires_at instanceof Carbon ? $code->expires_at : Carbon::parse($code->expires_at);
                // If current time is greater than end date, coupon has expired
                if($now->gt($endDate)){
                    return response()->json([
                        'status' => false,
                        'message' => 'Coupon has expired',
                    ]);
                }
            } catch (\Exception $e) {
                // If date parsing fails, skip this check
            }
        }

        session()->put('code', $code);
        
        // Call getOrderSummery with proper request
        $requestForSummery = new Request();
        $requestForSummery->merge(['country_id' => $request->country_id ?? '']);
        return $this->getOrderSummery($requestForSummery);
    }

    public function removeDiscount(Request $request)
    {
        session()->forget('code');
        return $this->getOrderSummery($request);
    }
}
