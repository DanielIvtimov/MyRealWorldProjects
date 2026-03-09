<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\ProductRating;

class ShopController extends Controller
{
    public function index(Request $request, $categorySlug = null, $subCategorySlug = null)
    {
        $categorySelected = null;
        $subCategorySelected = null;
        $brandsArray = [];
        

        $categories = Category::orderBy('name', 'ASC')
            ->with('sub_category')
            ->where('status', 1)
            ->get();

        $brands = Brand::orderBy('name', 'ASC')
            ->where('status', 1)
            ->get();

        // Build products query with eager loading to avoid N+1 problems
        $products = Product::with('productImages')->where('status', 1);

        // Filter by category if provided
        if ($categorySlug) {
            $category = Category::where('slug', $categorySlug)->firstOrFail();
            $products->where('category_id', $category->id);
            $categorySelected = $category->id;
        }

        // Filter by subcategory if provided
        if ($subCategorySlug) {
            $subCategory = SubCategory::where('slug', $subCategorySlug)->firstOrFail();
            $products->where('sub_category_id', $subCategory->id);
            $subCategorySelected = $subCategory->id;
        }

        // Filter by brands if provided
        if(!empty($request->get('brand'))){
            $brandsArray = array_filter(explode(',', $request->get('brand')));
            if(!empty($brandsArray)){
                $products->whereIn('brand_id', $brandsArray);
            }
        }

        // Filter by price range if provided
        $priceMin = $request->get('price_min');
        $priceMax = $request->get('price_max');
        
        if(!empty($priceMin) || !empty($priceMax)){
            $minPrice = !empty($priceMin) ? intval($priceMin) : 0;
            $maxPrice = !empty($priceMax) ? intval($priceMax) : 1000000;
            $products->whereBetween('price', [$minPrice, $maxPrice]);
        }

        // Filter by search keyword if provided
        if(!empty($request->get('search'))){
            $searchKeyword = trim($request->get('search'));
            if(!empty($searchKeyword)){
                $products->where('title', 'like', '%'.$searchKeyword.'%');
            }
        }

        // Get default price values for slider initialization
        $priceMin = !empty($priceMin) ? intval($priceMin) : 0;
        $priceMax = !empty($priceMax) ? intval($priceMax) : 1000;

        // Apply sorting based on request parameter
        $sort = $request->get('sort', 'latest');
        switch($sort) {
            case 'price-desc':
                $products->orderBy('price', 'DESC');
                break;
            case 'price-asc':
                $products->orderBy('price', 'ASC');
                break;
            case 'latest':
            default:
                $products->orderBy('id', 'DESC');
                break;
        }

        $products = $products->paginate(6);

        return view('front.shop', compact(
            'categories',
            'brands',
            'products',
            'categorySelected',
            'subCategorySelected',
            'brandsArray',
            'priceMin',
            'priceMax',
            'sort'
        ));
    }

    public function product($slug){
        $product = Product::where('slug', $slug)
            ->with(['productImages', 'product_ratings'])
            ->withCount('product_ratings')
            ->withSum('product_ratings', 'rating')
            ->firstOrFail();

        if($product == null){
            abort(404);
        }

        $relatedProducts = [];

        if($product->related_products != ""){
            $productArray = explode(',', $product->related_products);
            $relatedProducts = Product::whereIn('id', $productArray)
                ->with('productImages')
                ->where('status', 1)
                ->get();
        }

        $data['product'] = $product;
        $data['relatedProducts'] = $relatedProducts;

        // Rating Calculation
        $avgRating = "0.00";
        $avgRatingPer = 0;
        if($product->product_ratings_count > 0){
            $avgRating = number_format(($product->product_ratings_sum_rating / $product->product_ratings_count), 2);
            $avgRatingPer = ($avgRating*100)/5;
        }

        $data['avgRating'] = $avgRating;
        $data['avgRatingPer'] = $avgRatingPer;
        

        return view('front.product', $data);
    }
    public function saveRating(Request $request, $productId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5',
            'email' => 'required|email',
            'comment' => 'required',
            'rating' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        $count = ProductRating::where('email', $request->email)->count();
        if($count > 0){
            session()->flash('error', 'You have already rated this product.');
            return response()->json([
                'status' => true,
            ]);
        }

        $productRating = new ProductRating();
        $productRating->product_id = $productId;
        $productRating->username = $request->name;
        $productRating->email = $request->email;
        $productRating->comment = $request->comment;
        $productRating->rating = $request->rating;
        $productRating->status = 0;
        $productRating->save();

        session()->flash('success', 'Thank you for your rating.');

        return response()->json([
            'status' => true,
            'message' => 'Thank you for your rating.',
        ]);
    }
}
