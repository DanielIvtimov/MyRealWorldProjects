<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;


class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $product = Product::with('productImages')->find($request->id);
        if($product == null){
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
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
            $message = $product->title.' added in cart successfully';
        } else {
            $status = false;
            $message = $product->title.' already added in cart';
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
}
