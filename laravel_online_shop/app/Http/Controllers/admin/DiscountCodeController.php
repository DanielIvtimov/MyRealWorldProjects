<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\DiscountCoupon;


class DiscountCodeController extends Controller
{
    public function index(Request $request)
    {
        $discountCoupons = DiscountCoupon::latest();

        if(!empty($request->get('keyword'))){
            $discountCoupons = $discountCoupons->where(function($query) use ($request) {
                $query->where('name', 'like', '%' . $request->get('keyword') . '%')
                      ->orWhere('code', 'like', '%' . $request->get('keyword') . '%');
            });
        }
        $discountCoupons = $discountCoupons->paginate(10);

        return view('admin.coupon.list', compact('discountCoupons'));
    }
    public function create()
    {
        return view('admin.coupon.create');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'type' => 'required',
            'discount_amount' => 'required|numeric',
            'status' => 'required',
        ]);
        if($validator->passes()){

            // Starting date must be greater than current date/time
            if(!empty($request->starts_at)){
                $now = Carbon::now();
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);
                if($startAt->lte($now)){
                    return response()->json([
                        'status' => false,
                        'errors' => ['starts_at' => 'Start date can not be less than current date time']
                    ]);
                }
            }

            // Expires date must be greater than start date 
            if(!empty($request->starts_at) && !empty($request->expires_at)){
                $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->expires_at);
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);
                if($expiresAt->lte($startAt)){
                    return response()->json([
                        'status' => false,
                        'errors' => ['expires_at' => 'Expires date must be greater than start date']
                    ]);
                }
            }

            $discountCode = new DiscountCoupon();
            $discountCode->code = $request->code;
            $discountCode->name = $request->name;
            $discountCode->description = $request->description;
            $discountCode->max_uses = $request->max_uses;
            $discountCode->max_uses_user = $request->max_uses_user;
            $discountCode->type = $request->type;
            $discountCode->discount_amount = $request->discount_amount;
            $discountCode->min_amount = $request->min_amount;
            $discountCode->status = $request->status;
            $discountCode->starts_at = $request->starts_at;
            $discountCode->expires_at = $request->expires_at;
            $discountCode->save();

            $message = 'Discount code created successfully';
 
            session()->flash('success', $message);

            return response()->json([
                'status' => true,
                'message' => $message,
            ]);
         } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }
    public function edit($id)
    {
        $coupon = DiscountCoupon::find($id);
        
        if($coupon == null){
            session()->flash('error', 'Record not found');
            return redirect()->route('coupouns.index');
        }
        $data['coupon'] = $coupon;
        return view('admin.coupon.edit', $data);
    }
    public function update(Request $request, $id)
    {
        $discountCode = DiscountCoupon::find($id);

        if($discountCode == null){
            session()->flash('error', 'Record not found');
            return response()->json([
                'status' => false,
            ]);
        }

        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'type' => 'required',
            'discount_amount' => 'required|numeric',
            'status' => 'required',
        ]);
        if($validator->passes()){

            // Starting date must be greater than current date/time
            if(!empty($request->starts_at)){
                $now = Carbon::now();
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);
                if($startAt->lte($now)){
                    return response()->json([
                        'status' => false,
                        'errors' => ['starts_at' => 'Start date can not be less than current date time']
                    ]);
                }
            }

            // Expires date must be greater than start date 
            if(!empty($request->starts_at) && !empty($request->expires_at)){
                $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->expires_at);
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);
                if($expiresAt->lte($startAt)){
                    return response()->json([
                        'status' => false,
                        'errors' => ['expires_at' => 'Expires date must be greater than start date']
                    ]);
                }
            }

            $discountCode->code = $request->code;
            $discountCode->name = $request->name;
            $discountCode->description = $request->description;
            $discountCode->max_uses = $request->max_uses;
            $discountCode->max_uses_user = $request->max_uses_user;
            $discountCode->type = $request->type;
            $discountCode->discount_amount = $request->discount_amount;
            $discountCode->min_amount = $request->min_amount;
            $discountCode->status = $request->status;
            $discountCode->starts_at = $request->starts_at;
            $discountCode->expires_at = $request->expires_at;
            $discountCode->save();

            $message = 'Discount code updated successfully';
 
            session()->flash('success', $message);

            return response()->json([
                'status' => true,
                'message' => $message,
            ]);
         } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }
    public function destroy(Request $request, $id)
    {
        $discountCode = DiscountCoupon::find($id);
        if($discountCode == null){
            session()->flash('error', 'Record not found');
            return response()->json([
                'status' => false,
            ]);
        }
        $discountCode->delete();

        session()->flash('success', 'Discount code deleted successfully');
        return response()->json([
            'status' => true,
        ]);
    }
}
