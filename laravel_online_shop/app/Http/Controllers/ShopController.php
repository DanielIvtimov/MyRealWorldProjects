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

        $products = Product::where('status', 1);

        if ($categorySlug) {
            $category = Category::where('slug', $categorySlug)->firstOrFail();
            $products->where('category_id', $category->id);
            $categorySelected = $category->id;
        }

        if ($subCategorySlug) {
            $subCategory = SubCategory::where('slug', $subCategorySlug)->firstOrFail();
            $products->where('sub_category_id', $subCategory->id);
            $subCategorySelected = $subCategory->id;
        }

        if(!empty($request->get('brand'))){
            $brandsArray = explode(',', $request->get('brand'));
            $products = $products->whereIn('brand_id', $brandsArray);
        }
        // $brandsArray = $request->get('brand');

        $products = $products->orderBy('id', 'DESC')->get();

        return view('front.shop', compact(
            'categories',
            'brands',
            'products',
            'categorySelected',
            'subCategorySelected',
            'brandsArray',
        ));
    }
}
