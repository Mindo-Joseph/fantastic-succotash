<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyCard extends Model
{
    use HasFactory;

    public static function getLoyaltyPoint($minimum_points, $payable_amount){
    	$per_order_points = 0;
    	$result = LoyaltyCard::where('minimum_points','<=', $minimum_points)->orderBy('minimum_points', 'DESC')->first();
    	if($result){
    		$amount_per_loyalty_point = ($payable_amount / $result->amount_per_loyalty_point);
    		$per_order_points = $result->per_order_points+$amount_per_loyalty_point;
    	}
    	return $per_order_points;
    }
}
