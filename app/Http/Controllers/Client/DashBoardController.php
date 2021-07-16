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
use App\Models\{Banner, Brand, Category, Country, Order, Product, Vendor, VendorOrderStatus, UserAddress,OrderVendor};

class DashBoardController extends BaseController{
    use ApiResponser;
    public function index(){
        return view('backend/dashboard');
    }
    public function postFilterData(Request $request){
        try {
            $type = $request->type;
            $total_brands = Brand::count();
            /// Vendors count 
            $total_vendor = Vendor::orderBy('id','desc');
            if (Auth::user()->is_superadmin == 0) {
                $total_vendor = $total_vendor->whereHas('permissionToUser', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                });
            }
            $total_vendor = $total_vendor->count();

            $total_banners = Banner::count();
            
            // total products 
            $total_products = Product::orderBy('id','desc');
            if (Auth::user()->is_superadmin == 0) {
                $total_products = $total_products->whereHas('vendor.permissionToUser', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                });
            }
            $total_products = $total_products->count();

            $date_filter = $request->date_filter;
            if($date_filter){
                $date_explode = explode('to', $date_filter);
                $from_date = $date_explode[0].' 00:00:00';
                $end_date = $date_explode[1].' 23:59:59';
            }
            $total_categories = Category::count();
            
            $total_revenue = Order::orderBy('id','desc');
            if (Auth::user()->is_superadmin == 0) {
                $total_revenue = $total_revenue->whereHas('vendors.vendor.permissionToUser', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                });
            }
            $total_revenue = $total_revenue->sum('payable_amount');


            $today_sales = Order::whereDay('created_at', now()->day);
            if (Auth::user()->is_superadmin == 0) {
                $today_sales = $today_sales->whereHas('vendors.vendor.permissionToUser', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                });
            }
            $today_sales = $today_sales->sum('payable_amount');

            
            #all pending orders 
            $total_pending_order = OrderVendor::where('order_status_option_id',1);
            if (Auth::user()->is_superadmin == 0) {
                $total_pending_order = $total_pending_order->whereHas('vendor.permissionToUser', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                });
            }
            $total_pending_order = $total_pending_order->count();

             #total_rejected_order
             $total_rejected_order = OrderVendor::where('order_status_option_id',3);
             if (Auth::user()->is_superadmin == 0) {
                 $total_rejected_order = $total_rejected_order->whereHas('vendor.permissionToUser', function ($query) {
                     $query->where('user_id', Auth::user()->id);
                 });
             }
             $total_rejected_order = $total_rejected_order->count();


              #total_delivered_order
              $total_delivered_order = OrderVendor::where('order_status_option_id',6);
              if (Auth::user()->is_superadmin == 0) {
                  $total_delivered_order = $total_delivered_order->whereHas('vendor.permissionToUser', function ($query) {
                      $query->where('user_id', Auth::user()->id);
                  });
              }
              $total_delivered_order = $total_delivered_order->count();

            $dates = $sales = $labels = $series = $categories = $revenue = $address_ids = $markers =[];
             #total_active_order
            $total_active_order = OrderVendor::whereNotIn('order_status_option_id',[3,6]);
            if (Auth::user()->is_superadmin == 0) {
                $total_active_order = $total_active_order->whereHas('vendor.permissionToUser', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                });
            }
            $total_active_order = $total_active_order->count();

            $orders_query = Order::with(array('products' => function ($query) {
                    $query->select('order_id', 'category_id');
                }));
            
                if (Auth::user()->is_superadmin == 0) {
                    $orders_query = $orders_query->whereHas('vendors.vendor.permissionToUser', function ($query) {
                        $query->where('user_id', Auth::user()->id);
                    });
                }


            if($date_filter){
                $orders = $orders_query->whereBetween('created_at', [$from_date, $end_date])->select('id')->get();
            }else{
                $orders = $orders_query->whereMonth('created_at', Carbon::now()->month)->select('id')->get();
            }
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
            if (Auth::user()->is_superadmin == 0) {
                $monthly_sales_query = $monthly_sales_query->whereHas('vendors.vendor.permissionToUser', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                });
            }

            if($date_filter){
                $monthly_sales_query->whereBetween('created_at', [$from_date, $end_date]);
            }else{
                switch ($type) {
                    case 'monthly':
                        $monthly_sales_query->whereRaw('MONTH(created_at) = ?', [date('m')]);
                    break;
                    case 'weekly':
                        Carbon::setWeekStartsAt(Carbon::SUNDAY);
                        $monthly_sales_query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]); 
                    break;
                    case 'yearly':
                        $monthly_sales_query->whereRaw('YEAR(created_at) = ?', [date('Y')]);
                    break;
                    default:
                        $monthly_sales_query->whereRaw('MONTH(created_at) = ?', [date('m')]);
                    break;
                }
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
