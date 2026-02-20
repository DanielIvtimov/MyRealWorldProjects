<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;

class FrontController extends Controller
{
    public function index()
    {
        // Eager load images to avoid N+1 queries in the view
        $featuredProducts = Product::with('productImages')
            ->where('status', 1)
            ->where('is_featured', 'Yes')
            ->orderBy('id', 'DESC')
            ->take(8)
            ->get();

        $latestProducts = Product::with('productImages')
            ->where('status', 1)
            ->orderBy('id', 'DESC')
            ->take(8)
            ->get();

        return view('front.home', [
            'featuredProducts' => $featuredProducts,
            'latestProducts'   => $latestProducts,
        ]);
    }
    public function addToWishList(Request $request, $productId){
        if(Auth::check() == false){
            session(['url.intended' => url()->previous()]);

            return response()->json([
                'status' => false,
                'message' => 'Please login to add to wishlist',
            ]);
        }

        // Check if product exists
        $product = Product::find($productId);
        if(empty($product)){
            return response()->json([
                'status' => false,
                'message' => 'Product not found',
            ]);
        }

        // Check if product is already in wishlist
        $existingWishlist = Wishlist::where('user_id', Auth::user()->id)
            ->where('product_id', $productId)
            ->first();

        if(!empty($existingWishlist)){
            return response()->json([
                'status' => false,
                'message' => '<div class="alert alert-info"><strong>"'.$product->title.'"</strong> is already in your wishlist</div>',
            ]);
        }

        // Add to wishlist
        Wishlist::create([
            'user_id' => Auth::user()->id,
            'product_id' => $productId,
        ]);

        return response()->json([
            'status' => true,
            'message' => '<div class="alert alert-success"><strong>"'.$product->title.'"</strong> added to wishlist successfully</div>',
        ]);
    }
}
