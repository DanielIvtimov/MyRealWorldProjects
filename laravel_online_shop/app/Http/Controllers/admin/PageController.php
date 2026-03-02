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
    public function edit($id)
    {
        $page = Page::find($id);

        if($page == null){
            session()->flash('error', 'Page not found');
            return redirect()->route('pages.index');
        }
        return view('admin.pages.edit', compact('page'));
    }
    public function update(Request $request, $id)
    {
        $page = Page::find($id);

        if ($page == null) {
            session()->flash('error', 'Page not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Page not found',
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:255',
            'slug' => 'required|unique:pages,slug,' . $id . ',id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $page->name = $request->name;
        $page->slug = $request->slug;
        $page->content = $request->content ?? null;
        $page->save();

        session()->flash('success', 'Page updated successfully');
        return response()->json([
            'status' => true,
            'message' => 'Page updated successfully'
        ]);
    }
    public function destroy(Request $request, $id)
    {
        $page = Page::find($id);

        if ($page == null) {
            session()->flash('error', 'Page not found');
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => false, 'message' => 'Page not found']);
            }
            return redirect()->route('pages.index');
        }

        $page->delete();
        session()->flash('success', 'Page deleted successfully');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status' => true, 'message' => 'Page deleted successfully']);
        }
        return redirect()->route('pages.index');
    }
}
