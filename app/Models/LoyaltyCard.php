<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyCard extends Model
{
    use HasFactory;

    public static function getLoyaltyPoint($minimum_points){
    	$per_order_points = 0;
    	$result = LoyaltyCard::where('minimum_points','=<', $minimum_points)->first();
    	if($result){
    		$per_order_points = $result->per_order_points;
    	}
    	return $per_order_points;
    }
}
