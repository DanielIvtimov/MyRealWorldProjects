<?php 

use App\Models\Category;
use App\Models\ProductImage;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderEmail;
use App\Models\Country;

function getCategories(){
    return Category::orderBy('name', 'ASC')
    ->with('sub_category')
    ->orderBy('name', 'ASC')
    ->where('status', 1)
    ->where('showHome', 'Yes')
    ->get();
}

function getProductImage($productId){
    return ProductImage::where('product_id', $productId)->first();
}

function orderEmail($orderId){
    try {
        $order = Order::where('id', $orderId)->with('items')->first(); 
        
        if(empty($order)){
            \Log::error('Order not found for email: ' . $orderId);
            return false;
        }
        
        if(empty($order->email)){
            \Log::error('Order email address is empty for order: ' . $orderId);
            return false;
        }

        $mailData = [
            'subject' => 'Thanks for your order',
            'order' => $order,
        ];

        Mail::to($order->email)->send(new OrderEmail($mailData));
        return true;
    } catch (\Exception $e) {
        \Log::error('Error sending order email: ' . $e->getMessage());
        return false;
    }
}

function getCountryInfo($countryId){
   return Country::where('id', $countryId)->first();
}

?>
