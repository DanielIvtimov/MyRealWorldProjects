<?php 

use App\Models\Category;
use App\Models\ProductImage;

function getCategories(){
    return Category::orderBy('name', 'ASC')
    ->with('sub_category')
    ->orderBy('name', 'ASC')
    ->where('status', 1)
    ->where('showHome', 'Yes')
    ->get();
}

function getProductImage($productId){
    return ProductImage::where('product_id', $productId)->first();
}

?>
