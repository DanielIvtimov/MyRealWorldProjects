<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Validator;
use App\Models\ProductImage;
use App\Models\TempImage;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('productImages')->latest('id');
        
        if(!empty($request->keyword)){
            $products = $products->where(function($query) use ($request) {
                $query->where('title', 'like', '%'.$request->keyword.'%')
                      ->orWhere('sku', 'like', '%'.$request->keyword.'%');
            });
        }
        
        $products = $products->paginate(10);
        $products->appends($request->all());
        
        $data['products'] = $products;
        return view('admin.products.list', $data);
    }
    public function create()
    {
        $data = [];
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        return view('admin.products.create', $data);
    }
    public function store(Request $request)
    {
        // dd($request->image_array);       
        // exit();
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];

        if(!empty($request->track_qty) && $request->track_qty == "Yes"){
            $rules['qty'] = 'required|numeric|min:0';
        }

        $validator = Validator::make($request->all(), $rules);

        if($validator->passes()){
            $product = new Product();
            
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;

            $product->save();

            // Save Gallery Pics
            if(!empty($request->image_array)){
                foreach($request->image_array as $temp_image_id){

                    $tempImageInfo = TempImage::find($temp_image_id);
                    
                    if(empty($tempImageInfo)){
                        continue;
                    }
                    
                    $extArray = explode('.', $tempImageInfo->name);
                    $ext = last($extArray); // like jpg, gif, png etc
                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image = "NULL";
                    $productImage->save();

                    $imageName = $product->id.'-'.$productImage->id.'-'.time().'.'.$ext;
                    // product_id => 4; product_image_id => 1;
                    // 4-1-jpg => 4/1/4-1-jpg.jpg 
                    $productImage->image = $imageName;
                    $productImage->save();

                    // Generate Product Thumbnail

                    // Large Image
                    $sourcePath = public_path().'/temp/'.$tempImageInfo->name;
                    $destPath = public_path().'/uploads/product/large/'.$imageName;
                    
                    // Create directory if it doesn't exist
                    $largeDir = public_path().'/uploads/product/large';
                    if(!file_exists($largeDir)){
                        mkdir($largeDir, 0755, true);
                    }
                    
                    $image = Image::make($sourcePath);
                    $image->resize(1400, null, function($constraint){
                        $constraint->aspectRatio();
                    });
                    $image->save($destPath);
                    
                    // Small Image 
                    $smallDir = public_path().'/uploads/product/small';
                    if(!file_exists($smallDir)){
                        mkdir($smallDir, 0755, true);
                    }
                    
                    $destPath = public_path().'/uploads/product/small/'.$imageName;
                    $image = Image::make($sourcePath);
                    $image->fit(300, 300);
                    $image->save($destPath);
                    
                    // Delete temp image
                    if(file_exists($sourcePath)){
                        unlink($sourcePath);
                    }
                    
                    // Delete temp thumbnail
                    $tempThumbPath = public_path().'/temp/thumb/'.$tempImageInfo->name;
                    if(file_exists($tempThumbPath)){
                        unlink($tempThumbPath);
                    }
                    
                    // Delete temp image record
                    $tempImageInfo->delete();
                }
            }

            $request->session()->flash('success', 'Product has been created successfully');

            return response()->json([
                'status' => true,
                'message' => 'Product has been created successfully',
            ]);
             
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),   
            ]);
        }
    }

    public function edit($id)
    {
        $product = Product::find($id);

        if(empty($product)){
            session()->flash('error', 'Product not found');
            return redirect()->route('products.index');
        }

        // Fetch Product Images

        $productImages = ProductImage::where('product_id', $product->id)->get();

        $subCategories = SubCategory::where('category_id', $product->category_id)->get();

        $data = [];
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['product'] = $product;
        $data['subCategories'] = $subCategories;
        $data['productImages'] = $productImages;
        return view('admin.products.edit', $data);
    }
    public function update($id, Request $request)
    {
        $product = Product::find($id);
        
        if(empty($product)){
            session()->flash('error', 'Product not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Product not found',
            ]);
        }
        
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products,slug,'.$product->id.',id',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products,sku,'.$product->id.',id',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];

        if(!empty($request->track_qty) && $request->track_qty == "Yes"){
            $rules['qty'] = 'required|numeric|min:0';
        }

        $validator = Validator::make($request->all(), $rules);

        if($validator->passes()){
            
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = ($request->track_qty == 'Yes') ? $request->qty : null;
            $product->status = $request->status;
            
            // Reset sub_category if category changed
            if($product->category_id != $request->category){
                $product->sub_category_id = null;
            }
            
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;

            $product->save();

            

            $request->session()->flash('success', 'Product updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Product updated successfully',
            ]);
             
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),   
            ]);
        }
    }
}
