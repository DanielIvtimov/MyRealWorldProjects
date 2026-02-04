<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login()
    {
        return view('front.account.login');
    }
    public function register()
    {
        return view('front.account.register');
    }
    public function processRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|min:5|confirmed', 
        ]);
        
        if($validator->passes()){
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone ?? null;
            $user->password = Hash::make($request->password);
            $user->role = '1'; // Default role for regular users
            $user->save();

            session()->flash('success', '<strong>Registration successful!</strong> You can now login.');

            return response()->json([
                'status' => true,
                'message' => 'Registration successful'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function processLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if($validator->passes()){
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))){
                session()->flash('success', '<strong>Login successful!</strong> Welcome back.');
                
                return response()->json([
                    'status' => true,
                    'message' => 'Login successful'
                ]);
            } else {
                session()->flash('error', '<strong>Invalid credentials.</strong> Please check your email and password.');
                
                return response()->json([
                    'status' => false,
                    'message' => 'Either Email/Password is incorrect'
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function profile()
    {
        return view('front.account.profile');
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('front.account.login')->with('success', 'You have been logged out successfully.');
    }
}
