<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\Country;


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

        session()->forget('url.intented');

        $countries = Country::orderBy('name', 'ASC')->get();

        return view('front.checkout', [
            'countries' => $countries,
        ]);
    }
}
