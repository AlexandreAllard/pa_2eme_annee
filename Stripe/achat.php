<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="/css/style.css">
  </head>
  <body>
    <form action="payment.php" method="post" id="payment-form">
      <div>
        <lapel for="nom">Nom <input type="text" name="nom" placeholder="Nom"></label>
      </div>
      <div>
        <lapel for="email">Email <input type="text" name="email" placeholder="Email"></label>
      </div>
      <div>
        <lapel for="adresse">Adresse <input type="text" name="adresse" placeholder="Adresse"></label>
      </div>
      <div class="form-row">
        <label for="credit_element">Credit or debit card</label>
      </div>
      <div id="card-element">

      </div>
      <div id="card-errors" role="alert"></div>
      <input type="hidden" value="<?php echo $_GET['produit'];?>" name="id_produit">
      <button>Submit Payment</button>

    </form>




    <script src="https://js.stripe.com/v3/"> </script>
    <script type="text/javascript" src="java.js"></script>

    </script>
    <script>
      var stripe = Stripe('pk_test_51KwQzsKlgSGtYbAEtA4Rpg27zk7sqlpziHtkK2XRz4uuXE3pGwFmv3c5sKsqyHqI9BSPiYCdqhJOjJ7CRC6UgSwF00rCxO6YmU');
      var elements = stripe.elements();
      var style = {
        base: {
          frontSize: '16px',
          color: "#32325d",
        }
      };
      var card =elements.create('card', {style: style});
      card.mount('#card-element');

      card.addEventListener('change', function (event) {
        var displayError = document.getElementById('card-errors');
        if(event.error) {
          displayError.textContent = event.error.message;
        }else{
          displayError.textContent = '';
        }
      });

      var form = document.getElementById('payment-form');
      form.addEventListener('submit', function (event){
        event.preventDefault();

        stripe.createToken(card).then(function (result) {
          if (result.error) {
            var errorElement = document.getElementById('card-errors');
            errorElement.textContent = result.error.message;
          }else{
            stripeTokenHandler(result.token);
          }
        });
      });

      function stripeTokenHandler(token) {
        var form = document.getElementById('payment-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type','hidden');
        hiddenInput.setAttribute('name','stripeToken');
        hiddenInput.setAttribute('value',token.id);
        form.appendChild(hiddenInput);

        form.submit();
      }
    </script>
  </body>
</html>
