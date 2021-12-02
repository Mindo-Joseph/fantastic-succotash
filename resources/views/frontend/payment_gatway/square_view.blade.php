<html>

<head>
  <title>My Payment Flow</title>
  <!-- link to the Square web payment SDK library -->
  <script type="text/javascript" src="{{$data['square_url']}}"></script>
  <script type="text/javascript">
    window.applicationId = "{{$data['application_id']}}";
    window.locationId = "{{$data['location_id']}}";
    window.currency = "{{$data['currency']}}";
    window.country = "{{$data['country']}}";
  </script>
  <link rel="stylesheet" type="text/css" href="{{asset('square/css/style.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('square/css/sq-payment.css')}}">
</head>

<body>
  <form class="payment-form" id="fast-checkout">
    <div class="wrapper">
      <div id="apple-pay-button" alt="apple-pay" type="button"></div>
      <div id="google-pay-button" alt="google-pay" type="button"></div>
      <div class="border">
        <span>OR</span>
      </div>
      <div id="ach-wrapper">
        <label for="ach-account-holder-name">Full Name</label>
        <input id="ach-account-holder-name" type="text" placeholder="Jane Doe" name="ach-account-holder-name" autocomplete="name" /><span id="ach-message"></span><button id="ach-button" type="button">Pay with Bank Account</button>

        <div class="border">
          <span>OR</span>
        </div>
      </div>
      <div id="card-container"></div><button id="card-button" type="button">Pay with Card</button>
      <span id="payment-flow-message"></span>
    </div>
  </form>

  <script type="text/javascript" src="{{asset('square/js/sq-ach.js')}}"></script>
  <script type="text/javascript" src="{{asset('square/js/sq-apple-pay.js')}}"></script>
  <script type="text/javascript" src="{{asset('square/js/sq-card-pay.js')}}"></script>
  <script type="text/javascript" src="{{asset('square/js/sq-google-pay.js')}}"></script>
  <script type="text/javascript" src="{{asset('square/js/sq-payment-flow.js')}}"></script>
</body>

</html>
