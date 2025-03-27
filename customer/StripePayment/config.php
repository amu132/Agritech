<?php
	require_once "stripe-php-master/init.php";
	require_once "products.php";

$stripeDetails = array(
		"secretKey" => "sk_test_51QqxeqJWplaBFam5J4cFg5jmFWHqnIsqBaNfJ3VJq6tYnCEaqHosHK6y0YWlhnD7qroJKUeAIrfEHoTPsqbJhveW00By9dfsWv",  //Your Stripe Secret key
		"publishableKey" => "pk_test_51QqxeqJWplaBFam5drdtFgqzErrZ6Rm83bUdBYZUTQEFRb9klBGTxuq87vyQ4FJCfGojOCXWcVwcuLtLNletK1Sh00wzEp1oiU"  //Your Stripe Publishable key
	);

	// Set your secret key: remember to change this to your live secret key in production
	// See your keys here: https://dashboard.stripe.com/account/apikeys
	\Stripe\Stripe::setApiKey($stripeDetails['secretKey']);

	
?>
