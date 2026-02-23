<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Wishlist;

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
        $user = Auth::user();
        return view('front.account.profile', compact('user'));
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
}


