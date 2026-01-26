<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\SubCategory;

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

        $products = $products->get();

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
}
