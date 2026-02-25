<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


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

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|integer|in:0,1',
        ]);

        if ($validator->passes()) {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->phone = $request->phone ?? null;
            $user->status = $request->status ?? 1;
            $user->role = '1';
            $user->save();

            session()->flash('success', 'User has been created successfully');
            return response()->json([
                'status' => true,
                'message' => 'User has been created successfully',
            ]);
        }

        return response()->json([
            'status' => false,
            'errors' => $validator->errors(),
        ]);
    }
    public function edit(Request $request, $id)
    {
        $user = User::find($id);

        if($user == null){
            $message = "User not found";

            session()->flash('error', $message);
            return redirect()->route('users.index');
        }

        return view('admin.users.edit', compact('user'));
    }
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if ($user == null) {
            session()->flash('error', 'User not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'User not found',
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:255',
            'email' => 'required|email|unique:users,email,' . $id . ',id',
            'password' => 'nullable|min:5',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|integer|in:0,1',
        ]);

        if ($validator->passes()) {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone ?? null;
            $user->status = $request->status ?? 1;

            if (!empty($request->password)) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            session()->flash('success', 'User updated successfully');
            return response()->json([
                'status' => true,
                'message' => 'User updated successfully',
            ]);
        }

        return response()->json([
            'status' => false,
            'errors' => $validator->errors(),
        ]);
    }
    public function destroy(Request $request, $id)
    {
        $user = User::find($id);

        if($user == null){
            $message = "User not found";
            session()->flash('error', $message);
            return response()->json([
                'status' => false,
                'message' => $message,
            ]);
        }

        $user->delete();

        $message = "User has been deleted successfully";

        session()->flash('success', $message);
        return response()->json([
            'status' => true,
            'message' => $message,
        ]);
    }
}
