<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Twilio\Rest\Client;
use Mail;
use ConvertCurrency;

class BaseController extends Controller
{

	protected function sendSms($recipients, $message)
	{
		//echo $recipients;die;
	    $sid = getenv("TWILIO_SID");
	    $token = getenv("TWILIO_AUTH_TOKEN");
	    $twilio_number = getenv("TWILIO_NUMBER");
	    $client = new Client($account_sid, $auth_token);
	    $client->messages->create('+91'.$recipients, 
	            ['from' => $twilio_number, 'body' => $message] );
	}

	public function buildTree($elements, $parentId = 1) {
        $branch = array();

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }

    protected function in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)
    {
      $i = $j = $c = 0;
      for ($i = 0, $j = $points_polygon-1 ; $i < $points_polygon; $j = $i++) {
        if ( (($vertices_y[$i] > $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
        ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i]) ) ) {
            $c = !$c;
        }
      }
      return $c;
    }

    protected function contains($point, $polygon)
    {
        if($polygon[0] != $polygon[count($polygon)-1])
            $polygon[count($polygon)] = $polygon[0];
        $j = 0;
        $oddNodes = false;
        $x = $point[1];
        $y = $point[0];
        $n = count($polygon);
        for ($i = 0; $i < $n; $i++)
        {
            $j++;
            if ($j == $n)
            {
                $j = 0;
            }
            if ((($polygon[$i]['lat'] < $y) && ($polygon[$j]['lat'] >= $y)) || (($polygon[$j]['lat'] < $y) && ($polygon[$i]['lat'] >=
                $y)))
            {
                if ($polygon[$i]['lng'] + ($y - $polygon[$i]['lat']) / ($polygon[$j]['lat'] - $polygon[$i]['lat']) * ($polygon[$j]['lng'] -
                    $polygon[$i]['lng']) < $x)
                {
                    $oddNodes = !$oddNodes;
                }
            }
        }
        return $oddNodes;
    }

    public function sendNotification(Request $request)
    {
        $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();
        $SERVER_API_KEY = 'XXXXXX';

        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,  
            ]
        ];

        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
        dd($response);

    }

    protected function changeCurrency($curr, $price)
    {
        $currency = ConvertCurrency::convert('USD',[$curr], $price);
        return $currency[0]['convertedAmount'];
       // print_r();die;
    }
}