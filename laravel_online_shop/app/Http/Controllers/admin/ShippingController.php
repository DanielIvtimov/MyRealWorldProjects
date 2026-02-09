<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingCharge;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShippingController extends Controller
{
    public function create()
    {
        $countries = Country::get();
        $data['countries'] = $countries;

        $shippingCharges = ShippingCharge::select('shipping_charges.*', 'countries.name as country_name')
            ->leftJoin('countries', 'countries.id', '=', 'shipping_charges.country_id')
            ->get();
        $data['shippingCharges'] = $shippingCharges;

        return view('admin.shipping.create', $data);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'country' => 'required',
            'amount' => 'required|numeric',
        ]);

        if($validator->passes()){
            $count = ShippingCharge::where('country_id', $request->country)->count();
            if($count > 0){
                session()->flash('error', 'Shipping already exists for this country.');
                return response()->json([
                    'status' => true,
                ]);
            }

            $shipping = new ShippingCharge();
            $shipping->country_id = $request->country;
            $shipping->amount = $request->amount;
            $shipping->save();

            session()->flash('success', 'Shipping created successfully');

            return response()->json([
                'status' => true,
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
        $shippingCharge = ShippingCharge::find($id);
        
        if(empty($shippingCharge)){
            session()->flash('error', 'Shipping charge not found');
            return redirect()->route('shipping.create');
        }

        $countries = Country::get();
        $data['countries'] = $countries;
        $data['shippingCharge'] = $shippingCharge;

        return view('admin.shipping.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'country' => 'required',
            'amount' => 'required|numeric',
        ]);
        if($validator->passes()){
            $shipping = ShippingCharge::find($id);
            
            if(empty($shipping)){
                return response()->json([
                    'status' => false,
                    'notFound' => true,
                    'message' => 'Shipping charge not found',
                ]);
            }

            // Check if another shipping charge with same country_id exists (excluding current one)
            $count = ShippingCharge::where('country_id', $request->country)
                ->where('id', '!=', $id)
                ->count();
            
            if($count > 0){
                session()->flash('error', 'Shipping already exists for this country.');
                return response()->json([
                    'status' => true,
                ]);
            }

            $shipping->country_id = $request->country;
            $shipping->amount = $request->amount;
            $shipping->save();

            session()->flash('success', 'Shipping updated successfully');

            return response()->json([
                'status' => true,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),

            ]);
        }
    }
    public function destroy($id)
    {
        $shippingCharge = ShippingCharge::find($id);
        if(empty($shippingCharge)){
            return response()->json([
                'status' => false,
                'message' => 'Shipping charge not found',
            ]);
        }
        $shippingCharge->delete();
        session()->flash('success', 'Shipping deleted successfully');
        return response()->json([
            'status' => true,
        ]);
    }
}
