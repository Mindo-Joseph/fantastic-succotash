<?php

namespace App\Http\Controllers\Client;

use DB;
use Session;
use \DateTimeZone;
use Carbon\Carbon;
use App\Jobs\UpdateClient;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Jobs\ProcessClientDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Client\BaseController;
use App\Models\{Banner, Brand, Category, Client, ClientPreference, MapProvider, SmsProvider, Template, Currency, Language, Country, Order, Product, User, Vendor, VendorOrderStatus};

class DashBoardController extends BaseController{
    use ApiResponser;
    private $folderName = 'Clientlogo';

    public function index(){
        return view('backend/dashboard');
    }

    public function postFilterData(Request $request){
        try {
            $labels = array();
            $series = array();
            $categories = array();
            $total_brands = Brand::count();
            $total_vendor = Vendor::count();
            $total_banners = Banner::count();
            $total_products = Product::count();
            $total_categories = Category::count();
            $total_revenue = Order::sum('payable_amount');
            $today_sales = Order::whereDay('created_at', now()->day)->sum('payable_amount');
            $total_pending_order = VendorOrderStatus::where('order_status_option_id', 1)->count();
            $total_rejected_order = VendorOrderStatus::where('order_status_option_id', 3)->count();
            $total_delivered_order = VendorOrderStatus::where('order_status_option_id', 6)->count();
            $total_active_order = VendorOrderStatus::where('order_status_option_id', '!=', 3)->where('order_status_option_id', '!=', 1)->count();
            $orders = Order::with(array('products' => function ($query) {
                    $query->select('order_id', 'category_id');
                }))->whereMonth('created_at', Carbon::now()->month)->select('id')->get();
            foreach ($orders as $order) {
                foreach ($order->products as $product) {
                    $category = Category::with('english')->where('id', $product->category_id)->first();
                    if ($category) {
                        if (array_key_exists($category->slug, $categories)) {
                            $categories[Str::limit($category->english->name, 5, '..')] += 1;
                        } else {
                            $categories[Str::limit($category->english->name, 5, '..')] = 1;
                        }
                    }
                }
            }
            foreach ($categories as $key => $value) {
                $labels[] = $key;
                $series[] = $value;
            }
            $response = [
                'labels' => $labels,
                'series' => $series,
                'today_sales' => $today_sales, 
                'total_vendor' => $total_vendor, 
                'total_brands' => $total_brands, 
                'total_banners' => $total_banners, 
                'total_revenue' => $total_revenue, 
                'total_products' => $total_products, 
                'total_categories' => $total_categories,
                'total_active_order' => $total_active_order, 
                'total_pending_order' => $total_pending_order, 
                'total_rejected_order' => $total_rejected_order, 
                'total_delivered_order' => $total_delivered_order, 
            ];
            return $this->successResponse($response);
        } catch (Exception $e) {
            
        }
    }
    
    public function monthlySalesInfo(){
        $monthlysales = DB::table('orders')
            ->select(DB::raw('sum(payable_amount) as y'), DB::raw('count(*) as z'), DB::raw('date(created_at) as x'))
            ->whereRaw('MONTH(created_at) = ?', [date('m')])
            ->groupBy('x')
            ->get();
        $dates = array();
        $revenue = array();
        $sales = array();
        foreach ($monthlysales as $monthly) {
            $dates[] = $monthly->x;
            $sales[] = $monthly->z;
            $revenue[] = $monthly->y;
        }
        return response()->json(['dates' => $dates, 'revenue' => $revenue, 'sales' => $sales]);
    }

    public function yearlySalesInfo(){
        $yearlysales = DB::table('orders')
            ->select(DB::raw('sum(payable_amount) as y'), DB::raw('count(*) as z'), DB::raw('monthname(created_at) as x'))
            ->whereRaw('YEAR(created_at) = ?', [date('Y')])
            ->groupBy('x')
            ->orderBy('x', 'desc')
            ->get();
        $dates = array();
        $revenue = array();
        $sales = array();
        foreach ($yearlysales as $yearly) {
            $dates[] = $yearly->x;
            $revenue[] = $yearly->y;
            $sales[] = $yearly->z;
        }
        return response()->json(['dates' => $dates, 'revenue' => $revenue, 'sales' => $sales]);
    }

    public function weeklySalesInfo(){
        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        $weeklysales = DB::table('orders')
            ->select(DB::raw('sum(payable_amount) as y'), DB::raw('count(*) as z'), DB::raw('date(created_at) as x'))
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->groupBy('x')
            ->orderBy('x', 'asc')
            ->get();
        $dates = array();
        $revenue = array();
        $sales = array();
        foreach ($weeklysales as $weekly) {
            $dates[] = $weekly->x;
            $revenue[] = $weekly->y;
            $sales[] = $weekly->z;
        }
        return response()->json(['dates' => $dates, 'revenue' => $revenue, 'sales' => $sales]);
    }

    public function categoryInfo(){
        $orders = Order::with(array('products' => function ($query) {
                    $query->select('order_id', 'category_id');
                }))->whereMonth('created_at', Carbon::now()->month)->select('id')->get();
        $categories = array();
        foreach ($orders as $order) {
            foreach ($order->products as $product) {
                $category = Category::with('english')->where('id', $product->category_id)->first();
                if ($category) {
                    if (array_key_exists($category->slug, $categories)) {
                        $categories[Str::limit($category->english->name, 5, '..')] += 1;
                    } else {
                        $categories[Str::limit($category->english->name, 5, '..')] = 1;
                    }
                }
            }
        }
        $names = array();
        $orders = array();
        foreach ($categories as $key => $value) {
            $names[] = $key;
            $orders[] = $value;
        }
        return response()->json(['names' => $names, 'orders' => $orders]);
    }

    public function thousandsCurrencyFormat($num) {
        if($num>1000) {
              $x = round($num);
              $x_number_format = number_format($x);
              $x_array = explode(',', $x_number_format);
              $x_parts = array('k', 'm', 'b', 't');
              $x_count_parts = count($x_array) - 1;
              $x_display = $x;
              $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
              $x_display .= $x_parts[$x_count_parts - 1];
              return $x_display;
        }
        return $num;
      }
}
