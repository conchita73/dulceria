<?php session_start(); 
    error_reporting(E_ALL);
    date_default_timezone_set('Europe/Madrid');
    

  // librerias necesarias
  include 'init.php';
  include '../funciones.php';

  \Stripe\Stripe::setApiKey($skApiKey);

  $intent = \Stripe\PaymentIntent::retrieve($pi_stripe);

  $r = $intent->last_payment_error['decline_code'];
  
  echo json_encode($r);


  
?>