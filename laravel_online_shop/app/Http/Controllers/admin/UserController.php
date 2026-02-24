<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()->latest();

        if (!empty($request->get('keyword'))) {
            $keyword = $request->get('keyword');
            $users->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('email', 'like', '%' . $keyword . '%');
            });
        }

        $users = $users->paginate(10);
        $users->appends($request->all());

        return view('admin.users.list', compact('users'));
    }
}
