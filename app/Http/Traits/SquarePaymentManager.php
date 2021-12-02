<?php
namespace App\Http\Traits;
use Square\SquareClient;
use Square\LocationsApi;
use Square\Exceptions\ApiException;
use Square\Http\ApiResponse;
use Square\Models\ListLocationsResponse;
use Square\Models\CreateCustomerRequest;
use Square\Environment;
use App\Models\PaymentOption;
use Ramsey\Uuid\Uuid;
use Auth;
trait SquarePaymentManager{

  private $public_key;
  private $private_key;
  private $client;
  public function __construct()
  {
    $square_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'square')->where('status', 1)->first();
    $creds_arr = json_decode($square_creds->credentials);
    $this->application_id = $creds_arr->application_id??'';
    $this->access_token = $creds_arr->api_access_token??'';
    $this->idempotency_key = Uuid::uuid4();
  }
  public function init()
  {
    return new SquareClient([
        'accessToken' => $this->access_token,
        'environment' => Environment::SANDBOX,
      ]);
  }

  public function getLocationId($data)
  {
    try{
      $client = $this->init();
      $locationsApi = $client->getLocationsApi();
      $apiResponse = $locationsApi->listLocations();

      if ($apiResponse->isSuccess()) {
          $listLocationsResponse = $apiResponse->getResult();
          $locationsList = $listLocationsResponse->getLocations();
          foreach ($locationsList as $location) {
          dd($location);
          }
      } else {
          dd($apiResponse->getErrors());
      }
    } catch (ApiException $e) {
      dd("Recieved error while calling Square: " . $e->getMessage());
    } 
  }

  public function createOrder($data)
  {
    $client = $this->init();
    $amount_money = new \Square\Models\Money();
    $amount_money->setAmount(1000);
    $amount_money->setCurrency('USD');

    $order_service_charge = new \Square\Models\OrderServiceCharge();
    $order_service_charge->setName('dd');
    $order_service_charge->setAmountMoney($amount_money);
    $order_service_charge->setCalculationPhase('TOTAL_PHASE');

    $service_charges = [$order_service_charge];
    $order = new \Square\Models\Order('LK0JR9366ZGCS');
    $order->setReferenceId('123456');
    $order->setServiceCharges($service_charges);

    $body = new \Square\Models\CreateOrderRequest();
    $body->setOrder($order);
    $body->setIdempotencyKey('ab3bfb57-c263-44fe-a333-94940cb08974');

    $api_response = $client->getOrdersApi()->createOrder($body);
    dd($api_response);

    if ($api_response->isSuccess()) {
        $result = $api_response->getResult();
    } else {
        $errors = $api_response->getErrors();
    }
  }

  public function createSquareCustomer($data)
  {
    $client = $this->init();
    $customersApi = $client->getCustomersApi();

    // Create customer
    $request = new CreateCustomerRequest;
    $request->setGivenName(Auth::user()->name);
    $request->setFamilyName('');
    $request->setPhoneNumber("1-252-555-4240");
    $request->setNote('A customer');

    try {
        $result = $customersApi->createCustomer($request);
        dd($result);

        if ($result->isSuccess()) {
            print_r($result->getResult()->getCustomer());
        } else {
            print_r($result->getErrors());
        }
    } catch (ApiException $e) {
        print_r("Recieved error while calling Square: " . $e->getMessage());
    } 
  }

  public function createSquarePayment($data)
  {
    $client = $this->init();
    $amount_money = new \Square\Models\Money();
    $amount_money->setAmount(1000);
    $amount_money->setCurrency('USD');

    $app_fee_money = new \Square\Models\Money();
    $app_fee_money->setAmount(0);
    $app_fee_money->setCurrency('USD');

    $body = new \Square\Models\CreatePaymentRequest(
        'ccof:customer-card-id-ok',
        '084e23be-106d-4df5-b7d4-a382d77d7133',
        $amount_money
    );
    $body->setAppFeeMoney($app_fee_money);
    $body->setAutocomplete(true);

    $api_response = $client->getPaymentsApi()->createPayment($body);
    dd($api_response);

    if ($api_response->isSuccess()) {
        $result = $api_response->getResult();
    } else {
        $errors = $api_response->getErrors();
    }
  }
  public function createOrderPayment($data)
  {
    $body_sourceId = 'ccof:GaJGNaZa8x4OgDJn4GB';
    $body_idempotencyKey = '7b0f3ec5-086a-4871-8f13-3c81b3875218';
    $body_amountMoney = new Models\Money;
    $body_amountMoney->setAmount(1000);
    $body_amountMoney->setCurrency(Models\Currency::USD);
    $body = new Models\CreatePaymentRequest(
        $body_sourceId,
        $body_idempotencyKey,
        $body_amountMoney
    );
    $body->setTipMoney(new Models\Money);
    $body->getTipMoney()->setAmount(198);
    $body->getTipMoney()->setCurrency(Models\Currency::CHF);
    $body->setAppFeeMoney(new Models\Money);
    $body->getAppFeeMoney()->setAmount(10);
    $body->getAppFeeMoney()->setCurrency(Models\Currency::USD);
    $body->setDelayDuration('delay_duration6');
    $body->setAutocomplete(true);
    $body->setOrderId('order_id0');
    $body->setCustomerId('W92WH6P11H4Z77CTET0RNTGFW8');
    $body->setLocationId('L88917AVBK2S5');
    $body->setReferenceId('123456');
    $body->setNote('Brief description');

    $apiResponse = $paymentsApi->createPayment($body);

    if ($apiResponse->isSuccess()) {
        $createPaymentResponse = $apiResponse->getResult();
    } else {
        $errors = $apiResponse->getErrors();
    }
  }

}
