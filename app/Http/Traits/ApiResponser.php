<?php

namespace App\Http\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\{Product,OrderProductRating};
trait ApiResponser{

    protected function successResponse($data, $message = null, $code = 200)
	{
		return response()->json([
			'status'=> 'Success', 
			'message' => $message, 
			'data' => $data
		], $code);
	}

	protected function errorResponse($message = null, $code)
	{
		return response()->json([
			'status'=>'Error',
			'message' => $message,
			'data' => null
		], $code);
	}

	protected function updateaverageRating($product_id, $message = null, $code = 200)
	{	
		$ava_rating = OrderProductRating::where(['status' => '1','product_id' => $product_id])->avg('rating');
		$up_rat = Product::where('id',$product_id)->update(['averageRating' => $ava_rating]);
		return response()->json([
			'status'=>'Success',
			'message' => $message,
			'data' => $up_rat
		], $code);
	}
	

}