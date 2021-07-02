<?php

namespace App\Http\Controllers\Client;

use DB;
use Session;
use \DateTimeZone;
use App\Jobs\UpdateClient;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
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
use Carbon\Carbon;

class DashBoardController extends BaseController
{

    private $folderName = 'Clientlogo';
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $total_revenue = Order::sum('payable_amount');
        $today_sales = Order::whereDay('created_at', now()->day)->sum('payable_amount');
        $total_categories = Category::count();
        $total_products = Product::count();
        $total_vendor = Vendor::count();
        $total_banners = Banner::count();
        $total_brands = Brand::count();
        $total_pending_order = VendorOrderStatus::where('order_status_option_id', 1)->count();
        $total_rejected_order = VendorOrderStatus::where('order_status_option_id', 3)->count();
        $total_delivered_order = VendorOrderStatus::where('order_status_option_id', 6)->count();
        $total_active_order = VendorOrderStatus::where('order_status_option_id', '!=', 3)->where('order_status_option_id', '!=', 1)->count();
        return view('backend/dashboard')->with(['total_brands' => $total_brands, 'total_banners' => $total_banners, 'total_delivered_order' => $total_delivered_order, 'total_active_order' => $total_active_order, 'total_rejected_order' => $total_rejected_order, 'total_pending_order' => $total_pending_order, 'total_vendor' => $total_vendor, 'total_products' => $total_products, 'total_revenue' => $total_revenue, 'today_sales' => $today_sales, 'total_categories' => $total_categories]);
    }

    public function profile()
    {
        $countries = Country::all();
        $client = Client::where('code', Auth::user()->code)->first();
        $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        return view('backend/setting/profile')->with(['client' => $client, 'countries' => $countries, 'tzlist' => $tzlist]);
    }

    public function changePassword(Request $request)
    {
        $client = User::where('id', Auth::id())->first();
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        if (Hash::check($request->old_password, $client->password)) {
            $client->password = Hash::make($request->password);
            $client->save();
            $clientData = 'empty';
            return redirect()->back()->with('success', 'Password Changed successfully!');
        } else {
            $request->session()->flash('error', 'Wrong Old Password');
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request, $domain = '', $id)
    {
        $user = Auth::user();
        $client = Client::where('code', $user->code)->firstOrFail();
        $rules = array(
            'name' => 'required|string|max:50',
            'phone_number' => 'required|digits:10',
            'company_name' => 'required',
            'company_address' => 'required',
            'country_id' => 'required',
            'timezone' => 'required',
        );
        $validation  = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }
        $data = array();
        foreach ($request->only('name', 'phone_number', 'company_name', 'company_address', 'country_id', 'timezone') as $key => $value) {
            $data[$key] = $value;
        }
        $client = Client::where('code', Auth::user()->code)->first();
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $file_name = 'Clientlogo/' . uniqid() . '.' .  $file->getClientOriginalExtension();
            $path = Storage::disk('s3')->put($file_name, file_get_contents($file), 'public');
            $data['logo'] = $file_name;
        } else {
            $data['logo'] = $client->getRawOriginal('logo');
        }
        $client = Client::where('code', Auth::user()->code)->update($data);
        $userdata = array();
        foreach ($request->only('name', 'phone_number', 'timezone') as $key => $value) {
            $userdata[$key] = $value;
        }
        $user = User::where('id', Auth::id())->update($userdata);
        return redirect()->back()->with('success', 'Client Updated successfully!');
    }

    public function monthlySalesInfo()
    {
        $monthlysales = DB::table('orders')
            ->select(DB::raw('sum(payable_amount) as y'), DB::raw('count(*) as z'), DB::raw('date(created_at) as x'))
            ->whereRaw('MONTH(created_at) = ?', [date('m')])
            ->groupBy('x')
            // ->orderBy('x','desc')
            ->get();
        $dates = array();
        $revenue = array();
        $sales = array();
        foreach ($monthlysales as $monthly) {
            $dates[] = $monthly->x;
            $revenue[] = $monthly->y;
            $sales[] = $monthly->z;
        }
        return response()->json(['dates' => $dates, 'revenue' => $revenue, 'sales' => $sales]);
    }

    public function yearlySalesInfo()
    {
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

    public function weeklySalesInfo()
    {
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

    public function categoryInfo()
    {
        $orders = Order::with(array('products' => function ($query) {
            $query->select('order_id', 'category_id');
        }))->whereMonth('created_at', Carbon::now()->month)->select('id')->get();
        $categories = array();
        foreach ($orders as $order) {
            foreach ($order->products as $product) {
                $category = Category::where('id', $product->category_id)->first();
                if ($category) {
                    if (array_key_exists($category->slug, $categories)) {
                        $categories[$category->slug] += 1;
                    } else {
                        $categories[$category->slug] = 1;
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
