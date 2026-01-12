<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Brand;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $brands = Brand::query();
        
        if(!empty($request->keyword)){
            $brands = $brands->where(function($query) use ($request) {
                $query->where('name', 'like', '%'.$request->keyword.'%')
                      ->orWhere('slug', 'like', '%'.$request->keyword.'%');
            });
        }
        
        $brands = $brands->latest('id')->paginate(10);
        $brands->appends($request->all());
        
        return view("admin.brands.list", compact('brands'));
    }
    public function create()
    {
        return view("admin.brands.create");
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:255",
            "slug" => "required|unique:brands,slug",
            "status" => "required|integer|in:0,1",
        ]);
        
        if($validator->passes()){
            $brand = Brand::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'status' => $request->status ?? 1,
            ]);

            $request->session()->flash('success', 'Brand has been created successfully');

            return response()->json([
                'status' => true,
                'message' => 'Brand has been created successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()   
            ]);
        }
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Brand $brand, Request $request)
    {
        if(empty($brand)){
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => "Brand not found",
            ]);
        }

        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:255",
            "slug" => "required|unique:brands,slug,".$brand->id.",id",
            "status" => "required|integer|in:0,1",
        ]);
        
        if($validator->passes()){
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status ?? 1;
            $brand->save();

            $request->session()->flash('success', 'Brand has been updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Brand has been updated successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()   
            ]);
        }
    }

    public function destroy(Brand $brand, Request $request)
    {
        if(empty($brand)){
            return response()->json([
                'status' => false,
                'message' => "Brand not found",
            ]);
        }

        $brand->delete();

        $request->session()->flash('success', "Brand deleted successfully");

        return response()->json([
            'status' => true,
            'message' => "Brand deleted successfully",
        ]);
    }
}
