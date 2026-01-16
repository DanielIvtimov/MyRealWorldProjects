<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductImage;
use App\Models\TempImage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class ProductImageController extends Controller
{
    public function update(Request $request)
    {
        $image = $request->file('image');
        
        if(empty($image)){
            return response()->json([
                'status' => false,
                'message' => 'Image not found',
            ]);
        }

        if(empty($request->product_id)){
            return response()->json([
                'status' => false,
                'message' => 'Product ID is required',
            ]);
        }

        $ext = $image->getClientOriginalExtension();
        
        // Create ProductImage record first
        $productImage = new ProductImage();
        $productImage->product_id = $request->product_id;
        $productImage->image = 'NULL';
        $productImage->save();

        $imageName = $request->product_id.'-'.$productImage->id.'-'.time().'.'.$ext;
        $productImage->image = $imageName;
        $productImage->save();

        // Move uploaded file to temp directory first
        $tempName = time().'.'.$ext;
        $image->move(public_path().'/temp', $tempName);
        $sourcePath = public_path().'/temp/'.$tempName;

        // Large Image
        $destPath = public_path().'/uploads/product/large/'.$imageName;
        
        // Create directory if it doesn't exist
        $largeDir = public_path().'/uploads/product/large';
        if(!file_exists($largeDir)){
            mkdir($largeDir, 0755, true);
        }
        
        $img = Image::make($sourcePath);
        $img->resize(1400, null, function($constraint){
            $constraint->aspectRatio();
        });
        $img->save($destPath);
        
        // Small Image 
        $smallDir = public_path().'/uploads/product/small';
        if(!file_exists($smallDir)){
            mkdir($smallDir, 0755, true);
        }
        
        $destPath = public_path().'/uploads/product/small/'.$imageName;
        $img = Image::make($sourcePath);
        $img->fit(300, 300);
        $img->save($destPath);
        
        // Delete temp image
        if(file_exists($sourcePath)){
            unlink($sourcePath);
        }

        return response()->json([
            'status' => true,
            'image_id' => $productImage->id,
            'imagePath' => asset('uploads/product/small/'.$imageName),
            'message' => 'Image saved successfully',
        ]);
    }

    public function destroy(Request $request)
    {
        $productImage = ProductImage::find($request->image_id);

        if(empty($productImage)){
            return response()->json([
                'status' => false,
                'message' => 'Image not found',
            ]);
        }

        // Delete image from folder
        if(!empty($productImage->image)){
            $largeImagePath = public_path().'/uploads/product/large/'.$productImage->image;
            $smallImagePath = public_path().'/uploads/product/small/'.$productImage->image;
            
            if(File::exists($largeImagePath)){
                File::delete($largeImagePath);
            }
            
            if(File::exists($smallImagePath)){
                File::delete($smallImagePath);
            }
        }

        $productImage->delete();

        return response()->json([
            'status' => true,
            'message' => 'Image deleted successfully',
        ]);
    }
}
