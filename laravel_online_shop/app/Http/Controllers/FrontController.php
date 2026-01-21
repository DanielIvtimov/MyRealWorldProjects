<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

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
}
