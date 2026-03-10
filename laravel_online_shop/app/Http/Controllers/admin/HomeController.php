<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\TempImage;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {

        $totalOrders = Order::where('status', '!=', 'cancelled')->count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', '1')->count(); 

        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('grand_total');

        // This month revenue
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $currentDate = Carbon::now()->format('Y-m-d');

        $renevueThisMonth = Order::where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$startOfMonth, $currentDate])
            ->sum('grand_total');

        // Last month revenue
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
        $currentDate = Carbon::now()->subMonth()->format('Y-m-d');
        $lastMonthName = Carbon::now()->subMonth()->format('M');

        $renevueLastMonth = Order::where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$startOfLastMonth, $currentDate])
            ->sum('grand_total');

        // Last 30 days revenue
        $startOf30Days = Carbon::now()->subDays(30)->format('Y-m-d');
        $currentDate = Carbon::now()->format('Y-m-d');

        $renevueLast30Days = Order::where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$startOf30Days, $currentDate])
            ->sum('grand_total');

        // Delete temp images older than 1 day
        $dayBeforeToday = Carbon::now()->subDay();

        $tempImages = TempImage::whereDate('created_at', '<=', $dayBeforeToday)->get();

        foreach ($tempImages as $tempImage) {
            $path = public_path('/temp/' . $tempImage->name);
            $thumbPath = public_path('/temp/thumb/' . $tempImage->name);

            // Delete Main Image
            if (File::exists($path)) {
                File::delete($path);
            }

            // Delete Thumb Image
            if (File::exists($thumbPath)) {
                File::delete($thumbPath);
            }

            $tempImage->delete();
        }

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalProducts',
            'totalCustomers',
            'totalRevenue', 
            'renevueThisMonth', 
            'renevueLastMonth', 
            'renevueLast30Days', 
            'lastMonthName',
        ));
    }
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
