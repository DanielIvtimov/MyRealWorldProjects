<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return view("");
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
            ]);

            $request->session()->flash('success', "Category added successfully"); 

            return response()->json(['status' => true, 'message' => "Category added successfully"]);
        } else {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }
    }
    public function edit()
    {

    }
    public function update()
    {

    }
    public function destroy()
    {

    }
}
