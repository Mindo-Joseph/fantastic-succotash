<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <!-- 1: References the Square payment library to initalize the SDK -->
  <script src="https://sandbox.web.squarecdn.com/v1/square.js"></script>
</head>
<body>
  <form id="payment-form">
    <div id="card-container"></div>
    <button id="card-button" type="button">Pay</button>
  </form>
  <!-- Configure the Web Payments SDK and Card payment method -->
  <script type="text/javascript">
    var APPLICATION_ID = "{{$data['application_id']}}";
    var LOCATION_ID = "{{$data['location_id']}}";
    async function main() {
      const payments = Square.payments(APPLICATION_ID, LOCATION_ID);
      const card = await payments.card();
      await card.attach('#card-container');

      async function eventHandler(event) {
        event.preventDefault();

        try {
          const result = await card.tokenize();
          if (result.status === 'OK') {
            console.log(`Payment token is ${result.token}`);
          }
        } catch (e) {
          console.error(e);
        }
      };

      const cardButton = document.getElementById('card-button');
      cardButton.addEventListener('click', eventHandler);
    }

    main();
  </script>
</body>

</html>
