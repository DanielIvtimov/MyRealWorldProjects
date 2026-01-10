<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\SubCategory;

class SubCategoryController extends Controller
{
    public function index(Request $request)
    {
        $subCategories = SubCategory::select('sub_categories.*', 'categories.name as categoryName')
                            ->leftJoin('categories', 'categories.id', 'sub_categories.category_id');

        if(!empty($request->keyword)){
            $subCategories = $subCategories->where(function($query) use ($request) {
                $query->where('sub_categories.name', 'like', '%'.$request->keyword.'%')
                      ->orWhere('sub_categories.slug', 'like', '%'.$request->keyword.'%');
            });
        }
        
        $subCategories = $subCategories->latest('sub_categories.id')->paginate(10);
        $subCategories->appends($request->all());
        
        return view('admin.sub_category.list', compact('subCategories'));
    }
    public function create()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        return view('admin.sub_category.create', compact('categories'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:255",
            "slug" => "required|unique:sub_categories,slug",
            "category" => "required|integer|exists:categories,id",
            "status" => "required|integer|in:0,1",
        ]);
        
        if($validator->passes()){
            $subCategory = SubCategory::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'status' => $request->status ?? 1,
                'category_id' => $request->category,
            ]);

            $request->session()->flash('success', 'Sub Category has been created successfully');

            return response()->json([
                'status' => true,
                'message' => 'Sub Category has been created successfully',
            ]);

        } else {
            return response()->json([
                "status" => false,
                "errors" => $validator->errors(),
            ]);
        }
    }

    public function edit(SubCategory $subCategory)
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        return view('admin.sub_category.edit', compact('subCategory', 'categories'));
    }

    public function update(SubCategory $subCategory, Request $request)
    {
        if(empty($subCategory)){
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => "Sub Category not found",
            ]);
        }

        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:255",
            "slug" => "required|unique:sub_categories,slug,".$subCategory->id.",id",
            "category" => "required|integer|exists:categories,id",
            "status" => "required|integer|in:0,1",
        ]);
        
        if($validator->passes()){
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status ?? 1;
            $subCategory->category_id = $request->category;
            $subCategory->save();

            $request->session()->flash('success', 'Sub Category has been updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Sub Category has been updated successfully',
            ]);

        } else {
            return response()->json([
                "status" => false,
                "errors" => $validator->errors(),
            ]);
        }
    }

    public function destroy(SubCategory $subCategory, Request $request)
    {
        if(empty($subCategory)){
            return response()->json([
                'status' => false,
                'message' => "Sub Category not found",
            ]);
        }

        $subCategory->delete();

        $request->session()->flash('success', "Sub Category deleted successfully");

        return response()->json([
            'status' => true,
            'message' => "Sub Category deleted successfully",
        ]);
    }
}
