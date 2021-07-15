<?php

namespace App\Http\Controllers\Client;

use DB;
use Session;
use \DateTimeZone;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Client\BaseController;
use App\Models\{Banner, Brand, Category, Country, Order, Product, Vendor, VendorOrderStatus, UserAddress};

class DashBoardController extends BaseController{
    use ApiResponser;
    public function index(){
        return view('backend/dashboard');
    }
    public function postFilterData(Request $request){
        try {
            $type = $request->type;
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
            $dates = $sales = $labels = $series = $categories = $revenue = $address_ids = $markers =[];
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
            $monthly_sales_query = Order::select(\DB::raw('sum(payable_amount) as y'), \DB::raw('count(*) as z'), \DB::raw('date(created_at) as x'), 'address_id');
            switch ($type) {
                case 'monthly':
                    $created_at = $monthly_sales_query->whereRaw('MONTH(created_at) = ?', [date('m')]);
                break;
                case 'weekly':
                    Carbon::setWeekStartsAt(Carbon::SUNDAY);
                    $created_at = $monthly_sales_query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]); 
                break;
                case 'yearly':
                    $created_at = $monthly_sales_query->whereRaw('YEAR(created_at) = ?', [date('Y')]);
                break;
                default:
                    $created_at = $monthly_sales_query->whereRaw('MONTH(created_at) = ?', [date('m')]);
                break;
            }
            $monthlysales = $monthly_sales_query->groupBy('x')->get();
            foreach ($monthlysales as $monthly) {
                $dates[] = $monthly->x;
                $sales[] = $monthly->z;
                $revenue[] = $monthly->y;
                $address_ids [] = $monthly->address_id;
            }
            $address_details = UserAddress::whereIn('id', $address_ids)->get();
            foreach ($address_details as $address_detail) {
                if(!$address_detail->latitude){
                    continue;
                }
                $markers[]= array(
                    'name' => $address_detail->city,
                    'latLng' => [$address_detail->latitude , $address_detail->longitude],
                );
            }
            $response = [
                'dates' => $dates,
                'sales' => $sales,
                'labels' => $labels,
                'series' => $series,
                'markers' => $markers,
                'revenue' => $revenue,
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

    public function thousandsCurrencyFormat($num) {
        if($num > 1000) {
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
