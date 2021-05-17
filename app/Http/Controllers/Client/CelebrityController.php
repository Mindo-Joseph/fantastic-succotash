<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Client\BaseController;
use App\Models\{LoyaltyCard, Celebrity, Product, Brand};
use Dotenv\Loader\Loader;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CelebrityController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $celebrities = Celebrity::where('status', '!=', '3')->get();
        // dd($celebrities->toArray());
        // $celebrities = LoyaltyCard::where('status', '!=', '2')->get();
        return view('backend/celebrity/index')->with(['celebrities' => $celebrities]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $rules = array(
            'name' => 'required|string|max:150',
            //'email' => 'required|email|max:150|unique:celebrities',
            //'phone_number' => 'required',
            'address' => 'required',
        );

        if ($request->hasFile('image')) {    /* upload logo file */
            $rules['image'] =  'image|mimes:jpeg,png,jpg,gif';
        }

        $validation  = Validator::make($request->all(), $rules)->validate();

        $celebrity = new Celebrity();
        $celebrity->name = $request->input('name');
        $celebrity->email = $request->input('email');
        $celebrity->phone_number = $request->input('phone_number');
        $celebrity->address = $request->input('address');
        $celebrity->status = '1';

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $images = Storage::disk('s3')->put('/celebrity', $file, 'public');
            $celebrity->avatar = $images;
        }

        $celebrity->save();

        $celebrity->products()->sync($request->products);
        if ($celebrity->id > 0) {
            return response()->json([
                'status' => 'success',
                'message' => 'Celebrity created Successfully!',
                'data' => $celebrity
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($domain = '', $id)
    {
        $celeb = Celebrity::where('id', $id)->first();
        $pros = array();
        foreach ($celeb->products as $repo) {
            $pros[] = $repo->id;
        }
        $brands = Brand::all();
        $returnHTML = view('backend.celebrity.form')->with(['lc' => $celeb, 'brands' => $brands, 'pros' => $pros])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($domain = '', Request $request, $id)
    {
        //
        //  dd($request->all());
        $rules = array(
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:celebrities,email,' . $id,
            'phone_number' => 'required',
            'address' => 'required',
        );

        if ($request->hasFile('image')) {    /* upload logo file */
            $rules['image'] =  'image|mimes:jpeg,png,jpg,gif';
        }

        $validation  = Validator::make($request->all(), $rules)->validate();

        $celebrity = Celebrity::where('id', $id)->firstOrFail();;
        $celebrity->name = $request->input('name');
        $celebrity->email = $request->input('email');
        $celebrity->phone_number = $request->input('phone_number');
        $celebrity->address = $request->input('address');
        $celebrity->status = '1';

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $images = Storage::disk('s3')->put('/celebrity', $file, 'public');
            $celebrity->avatar = $images;
        }

        $celebrity->save();

        $celebrity->products()->sync($request->products);


        if ($celebrity->id > 0) {
            return response()->json([
                'status' => 'success',
                'message' => 'Celebrity created Successfully!',
                'data' => $celebrity
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($domain = '', $id)
    {
        //
        Celebrity::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Celebrity deleted successfully!');
    }

    /**
     * Change the status of Loyalty card.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request, $domain = '')
    {
        $loyaltyCard = Celebrity::find($request->id);
        $loyaltyCard->status = $request->status;
        $loyaltyCard->save();
    }

    /**
     * Get the default value of Redeem Point
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getProducts($domain = '')
    {
        $products = Product::all();
        return response()->json($products);
    }
}
