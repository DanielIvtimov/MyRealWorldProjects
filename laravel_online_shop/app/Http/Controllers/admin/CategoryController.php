<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Models\Category;
use App\Models\TempImage;




class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::query();
        
        if(!empty($request->keyword)){
            $categories = $categories->where('name', 'like', '%'.$request->keyword.'%')
                                    ->orWhere('slug', 'like', '%'.$request->keyword.'%');
        }
        
        $categories = $categories->latest()->paginate(10);
        $categories->appends($request->all());
        
        return view("admin.category.list", compact('categories'));
    }
    public function create()
    {
        return view("admin.category.create");
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "name" => "required|string|max:255",
            "slug" => "required|unique:categories",
            "status" => "required|integer|in:0,1",
        ]);
        
        if($validator->passes()){
            $category = Category::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'status' => $request->status ?? 1,
                'showHome' => $request->showHome ?? 'No', 
            ]);

            if(!empty($request->image_id)){
                $tempImage = TempImage::find($request->image_id);
                if($tempImage){
                    $extArray = explode('.', $tempImage->name);
                    $ext = last($extArray);
                    
                    $newImageName = $category->id.'.'.$ext;
                    $sPath = public_path().'/temp/'.$tempImage->name;
                    $dPath = public_path().'/uploads/category/'.$newImageName;
                    
                    // Ensure destination directory exists
                    $destinationDir = public_path().'/uploads/category';
                    if(!File::exists($destinationDir)){
                        File::makeDirectory($destinationDir, 0755, true);
                    }
                    
                    // Copy file from temp to uploads
                    if(File::exists($sPath)){
                        File::copy($sPath, $dPath);

                        // Generate Image Thumbnail 
                        $thumbPath = public_path().'/uploads/category/thumb/'.$newImageName;
                        
                        // Ensure thumb directory exists
                        $thumbDir = public_path().'/uploads/category/thumb';
                        if(!File::exists($thumbDir)){
                            File::makeDirectory($thumbDir, 0755, true);
                        }
                        
                        $img = Image::make($sPath);
                        $img->fit(450, 600, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        $img->save($thumbPath);
                        
                        // Update category with image name
                        $category->image = $newImageName;
                        $category->save();
                        
                        // Delete temp image
                        File::delete($sPath);
                        $tempImage->delete();
                    }
                }
            }

            $request->session()->flash('success', "Category added successfully"); 

            return response()->json(['status' => true, 'message' => "Category added successfully"]);
        } else {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }
    }
    public function edit($category)
    { 
        $category = Category::find($category); 
        if(empty($category)){
            return redirect()->route('categories.index')->with('error', "Category not found");
        }
        return view("admin.category.edit", compact('category'));
    }
    public function update($category, Request $request)
    {
        $category = Category::find($category);
        if(empty($category)){
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => "Category not found",
            ]);
        }
        $validator = Validator::make($request->all(),[
            "name" => "required|string|max:255",
            "slug" => "required|unique:categories,slug,".$category->id.",id",
            "status" => "required|integer|in:0,1",
        ]);
        
        if($validator->passes()){
           
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status ?? 1;
            $category->showHome = $request->showHome ?? 'No';
            $category->save();

            if(!empty($request->image_id)){
                $tempImage = TempImage::find($request->image_id);
                if($tempImage){
                    $extArray = explode('.', $tempImage->name);
                    $ext = last($extArray);
                    
                    $newImageName = $category->id.'.'.$ext;
                    $sPath = public_path().'/temp/'.$tempImage->name;
                    $dPath = public_path().'/uploads/category/'.$newImageName;
                    
                    // Delete old image if exists
                    if(!empty($category->image)){
                        $oldImage = public_path().'/uploads/category/'.$category->image;
                        $oldThumb = public_path().'/uploads/category/thumb/'.$category->image;
                        if(File::exists($oldImage)){
                            File::delete($oldImage);
                        }
                        if(File::exists($oldThumb)){
                            File::delete($oldThumb);
                        }
                    }
                    
                    // Ensure destination directory exists
                    $destinationDir = public_path().'/uploads/category';
                    if(!File::exists($destinationDir)){
                        File::makeDirectory($destinationDir, 0755, true);
                    }
                    
                    // Copy file from temp to uploads
                    if(File::exists($sPath)){
                        File::copy($sPath, $dPath);

                        // Generate Image Thumbnail 
                        $thumbPath = public_path().'/uploads/category/thumb/'.$newImageName;
                        
                        // Ensure thumb directory exists
                        $thumbDir = public_path().'/uploads/category/thumb';
                        if(!File::exists($thumbDir)){
                            File::makeDirectory($thumbDir, 0755, true);
                        }
                        
                        $img = Image::make($sPath);
                        $img->fit(450, 600, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        $img->save($thumbPath);
                        
                        // Update category with image name
                        $category->image = $newImageName;
                        $category->save();
                        
                        // Delete temp image
                        File::delete($sPath);
                        $tempImage->delete();
                    }
                }
            }

            $request->session()->flash('success', "Category updated successfully"); 

            return response()->json(['status' => true, 'message' => "Category updated successfully"]);
        } else {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }
    }
    public function destroy($category, Request $request)
    {
        $category = Category::find($category);
        if(empty($category)){
            return response()->json([
                'status' => false,
                'message' => "Category not found",
            ]);
        }

        // Delete old image if exists
        if(!empty($category->image)){
            $oldImage = public_path().'/uploads/category/'.$category->image;
            $oldThumb = public_path().'/uploads/category/thumb/'.$category->image;
            
            if(File::exists($oldImage)){
                File::delete($oldImage);
            }
            if(File::exists($oldThumb)){
                File::delete($oldThumb);
            }
        }

        $category->delete();

        $request->session()->flash('success', "Category deleted successfully");

        return response()->json([
            'status' => true,
            'message' => "Category deleted successfully",
        ]);
    }
}
