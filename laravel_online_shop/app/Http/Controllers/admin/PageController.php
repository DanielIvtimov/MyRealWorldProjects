<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Page;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $pages = Page::query()->latest();

        if (!empty($request->get('keyword'))) {
            $keyword = $request->get('keyword');
            $pages->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('slug', 'like', '%' . $keyword . '%');
            });
        }

        $pages = $pages->paginate(10);
        $pages->appends($request->all());

        return view("admin.pages.list", compact("pages"));
    }
    public function create()
    {
       return view("admin.pages.create");
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:255',
            'slug' => 'required|unique:pages,slug',
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $page = new Page();
        $page->name = $request->name;
        $page->slug = $request->slug;
        $page->content = $request->content;
        $page->save();

        $message = "Page created successfully";

        session()->flash('success', $message);

        return response()->json([
            'status' => true,
            'message' => $message
        ]);
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
