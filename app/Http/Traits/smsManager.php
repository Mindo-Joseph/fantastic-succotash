<?php
namespace App\Http\Traits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use GuzzleHttp\Client;
use Log;
trait smsManager{

  public function __construct()
  {
    //
  }


    public function mTalkz_sms($to,$message,$crendentials)
    {
        $api_url = "http://msg.mtalkz.com/V2/http-api.php";
        $to_number = substr($to, 1);
        $endpoint = $api_url.'?apikey='.$crendentials->api_key.'&senderid='.$crendentials->sender_id.'&number='.$to_number.'&message='.$message.'&format=json';
        $response=$this->getGuzzle($endpoint);
        return $response;
    }

    public function mazinhost($to,$message,$crendentials)
    {
        $api_url = " https://mazinhost.com/smsv1/sms/api";
        $to_number = substr($to, 1);
        $endpoint = $api_url.'?action=send-sms&apikey='.$crendentials->api_key.'&to='.$to_number.'&from='.$crendentials->sender_id.'&message='.$message.'&format=json';
        $response=$this->getGuzzle($endpoint);
        return $response;
    }

    private function postCurl($data,$token=null):object{
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
        $headers = array();
        $headers[] = 'Accept: */*';
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return json_decode($result); 
    }
    private function getCurl($endpoint):object{
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        $headers = array();
        $headers[] = 'Accept: */*';
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return $result;
        return json_decode($result);

        $curl = curl_init();

    }
    public function getGuzzle($endpoint)
    {
        try{
            $client = new \GuzzleHttp\Client();
            $res = $client->get($endpoint);
            return $res->getStatusCode(); // 200
        }catch(Exception $e) {
            dd($e);
        }
    }

}
