<?php
require "functions.php";

//check if stripe token exist to proceed with payment
ini_set('display_errors',1);

//include('includes/head.php');
  if(!empty($_POST['stripeToken'])){
    // get token and user details
    $stripeToken  = $_POST['stripeToken'];
    $id_produit = $_POST['id_produit'];
    $custName = $_POST['nom'];
    $custEmail =$_POST['email'];
    $address = $_POST['adresse'];
    //include Stripe PHP library
    require_once('vendor/stripe/stripe-php/init.php');
    //set stripe secret key and publishable key
    $stripe = array(
      "secret_key"      => "sk_test_51KwQzsKlgSGtYbAEAlMzv1hwOQcFz557fzskk4imD0xab6ah7SfYILunvqUi89JM5SR8taricHxT5TmyLM5Zx8o200oDkS0gLB",
      "publishable_key" => "pk_test_51KwQzsKlgSGtYbAEtA4Rpg27zk7sqlpziHtkK2XRz4uuXE3pGwFmv3c5sKsqyHqI9BSPiYCdqhJOjJ7CRC6UgSwF00rCxO6YmU"
    );
    \Stripe\Stripe::setApiKey($stripe['secret_key']);

    //add customer to stripe
    $customer = \Stripe\Customer::create(array(
        'email' => $custEmail,
        'source'  => $stripeToken
    ));



//Données du produit acheté
$idItemSelect=2; //id du produit que le souhaite payer

$connection = connectDB();
$produitById = $connection->prepare("SELECT * FROM caf_produit WHERE id_produit=:id_produit");
$produitById->execute(["id_produit"=>$idItemSelect]);
$produit = $produitById ->fetch();
$itemName = $produit['nom_produit'];
$itemPrice = $produit['prix_produit']*100;;
$currency = "EUR";
$itemID = $produit['id_produit'];



    // details for which payment performed
    $payDetails = \Stripe\Charge::create(array(
        'customer' => $customer->id,
        'amount'   => $itemPrice,
        'currency' => $currency,
        'description' => $itemName,
        'metadata' => array(
            'order_id' => $itemID
        )
    ));




    // get payment details
    $paymentResponse = $payDetails->jsonSerialize();
    // check whether the payment is successful
    if($paymentResponse['amount_refunded'] == 0 && empty($paymentResponse['failure_code']) && $paymentResponse['paid'] == 1 && $paymentResponse['captured'] == 1){
        // transaction details
        $amountPaid = $paymentResponse['amount'];
        $balanceTransaction = $paymentResponse['balance_transaction'];
        $paidCurrency = $paymentResponse['currency'];
        $paymentStatus = $paymentResponse['status'];
        $paymentDate = date("Y-m-d H:i:s");

        //TODO
        echo "Commande validée, produit acheté : ".$itemName." | Prix : ".$produit['prix_produit']."€"."<br>";
        echo ".........".$amountPaid;
        echo ".........".$balanceTransaction;
        echo ".........".$paidCurrency;
        echo ".........".$paymentStatus;
        echo ".........".$paymentDate;


      }//TODO

}//TODO
 ?>
