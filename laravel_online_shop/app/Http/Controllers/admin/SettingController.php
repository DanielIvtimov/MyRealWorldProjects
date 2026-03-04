<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    public function showChangePasswordForm()
    {
        return view('admin.users.change-password');
    }
    public function processChangePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
        ]);

        $admin = Auth::guard('admin')->user();

        if($validator->passes()){
            if(!$admin || !Hash::check($request->old_password, $admin->password)){
                session()->flash('error', 'Old password is incorrect');
                return response()->json([
                    'status' => false,
                    'errors' => [
                        'old_password' => ['Old password is incorrect']
                    ]
                ]);
            }

            $admin->password = Hash::make($request->new_password);
            $admin->save();
            session()->flash('success', 'You have successfully changed your password');
            return response()->json([
                'status' => true,
                'message' => 'You have successfully changed your password'
            ]); 
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
}
