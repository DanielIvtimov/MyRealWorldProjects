<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Wishlist;
use App\Models\Country;
use App\Models\CustomerAddress;
use Illuminate\Support\Str;
use App\Mail\ResetPasswordEmail;

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

                if(session()->has('url.intented')){
                    return redirect(session()->get('url.intented'));
                }
                
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

        $userId = Auth::user()->id;

        $countries = Country::orderBy('name', 'ASC')->get();

        $user = User::where('id', $userId)->first();

        $address = CustomerAddress::where('user_id', $userId)->first();

        return view('front.account.profile', compact('user', 'countries', 'address'));
    }

    public function updateProfile(Request $request)
    {
        $userId = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->passes()) {
            $user = User::findOrFail($userId);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone ?? null;
            $user->save();

            session()->flash('success', 'Profile successfully updated');

            return response()->json([
                'status' => true,
                'message' => 'Profile successfully updated'
            ]);
        }

        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ]);
    }

    public function updateAddress(Request $request)
    {
        $userId = Auth::user()->id;

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:2|max:255',
            'last_name' => 'required|min:2|max:255',
            'email' => 'required|email',
            'country_id' => 'required|exists:countries,id',
            'address' => 'required|min:5|max:500',
            'apartment' => 'nullable|string|max:100',
            'city' => 'required|min:2|max:100',
            'state' => 'required|min:2|max:100',
            'zip' => 'required|min:2|max:20',
            'mobile' => 'required|string|max:20',
        ]);

        if ($validator->passes()) {
            CustomerAddress::updateOrCreate(
                ['user_id' => $userId],
                [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'country_id' => $request->country_id,
                    'address' => $request->address,
                    'apartment' => $request->apartment ?? null,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip' => $request->zip,
                    'mobile' => $request->mobile,
                ]
            );

            session()->flash('success', 'Address successfully updated');

            return response()->json([
                'status' => true,
                'message' => 'Address successfully updated'
            ]);
        }

        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login')->with('success', 'You have been logged out successfully.');
    }

    public function orders()
    {
        $user = Auth::user();

        if(empty($user)){
            return redirect()->route('account.login');
        }

        $orders = Order::where('user_id', $user->id)->orderBy('id', 'DESC')->get();

        $data['orders'] = $orders;

        return view('front.account.order', $data);
    }
    public function orderDetail($id)
    {
        $user = Auth::user();
        
        if(empty($user)){
            return redirect()->route('account.login');
        }
        
        $order = Order::where('user_id', $user->id)->where('id', $id)->first();
        
        if(empty($order)){
            return redirect()->route('front.account.orders')->with('error', 'Order not found');
        }

        $orderItems = OrderItem::where('order_id', $order->id)->get();
        
        $data['order'] = $order;
        $data['orderItems'] = $orderItems;
        return view('front.account.order-detail', $data);
    }
    public function wishlist()
    {
        $user = Auth::user();
        
        if(empty($user)){
            return redirect()->route('account.login');
        }

        $wishlists = Wishlist::where('user_id', $user->id)
            ->with(['product.productImages'])
            ->orderBy('id', 'DESC')
            ->get();

        $data['wishlists'] = $wishlists;

        return view('front.account.wishlist', $data);
    }

    public function removeProductFromWishList(Request $request, $id)
    {
        $user = Auth::user();
        
        if(empty($user)){
            return response()->json([
                'status' => false,
                'message' => 'Please login to continue'
            ]);
        }

        // $id is the product_id from the route parameter
        $wishlist = Wishlist::where('user_id', $user->id)
            ->where('product_id', $id)
            ->first(); 
            
        if(empty($wishlist)){
            session()->flash('error', 'Product already removed from wishlist');
            return response()->json([
                'status' => false,
                'message' => 'Product already removed from wishlist'
            ]);
        } else {
            $wishlist->delete();
            session()->flash('success', 'Product removed from wishlist successfully');
            return response()->json([
                'status' => true,
                'message' => 'Product removed from wishlist successfully'
            ]);
        }
    }

    public function showChangePassword()
    {
        return view('front.account.change-password');
    }
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
        ]);

        if($validator->passes()){
            $user = User::select('id', 'password')->where('id', Auth::user()->id)->first();

            if(!Hash::check($request->old_password, $user->password)){

                session()->flash('error', 'Old password is incorrect');

                return response()->json([
                    'status' => false,
                    'errors' => [
                        'old_password' => ['Old password is incorrect']
                    ]
                ]);
            }

            User::where('id', $user->id)->update([
                'password' => Hash::make($request->new_password)
            ]);

            session()->flash('success', 'Password changed successfully');
            
            return response()->json([
                'status' => true,
                'message' => 'Password changed successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function forgotPassword()
    {
        return view('front.account.forgot-password');
    }
    public function processForgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if($validator->fails()){
            return redirect()->route('front.forgotPassword')->withInput()->withErrors($validator);
        }

        $token = Str::random(60);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        $user = User::where('email', $request->email)->first();

        $formData = [
            'token' => $token,
            'user' => $user,
            'mailSubject' => 'You have requested to reset your password',
        ];

        Mail::to($request->email)->send(new ResetPasswordEmail($formData));

        return redirect()->route('front.forgotPassword')->with('success', 'We have emailed your password reset link.');
    }
    public function resetPassword($token)
    {

        $tokenExists = DB::table('password_reset_tokens')->where('token', $token)->first();

        if($tokenExists == null){
            return redirect()->route('front.forgotPassword')->with('error', 'Invalid token.');
        }

        return view('front.account.reset-password', compact('token'));
    }
    public function processResetPassword(Request $request)
    {
        $token = $request->token;

        $tokenObj = DB::table('password_reset_tokens')->where('token', $token)->first();

        if($tokenObj == null){
            return redirect()->route('front.forgotPassword')->with('error', 'Invalid token.');
        }

        $user = User::where('email', $tokenObj->email)->first();

        $validator = Validator::make($request->all(), [
            'password' => 'required|min:5',
            'confirm_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            return redirect()->route('front.resetPassword', $token)->withErrors($validator);
        }

        User::where('email', $tokenObj->email)->update([
            'password' => Hash::make($request->password)
        ]);

        DB::table('password_reset_tokens')->where('email', $tokenObj->email)->delete();

        return redirect()->route('account.login')->with('success', 'Password reset successfully.');
    }
}


