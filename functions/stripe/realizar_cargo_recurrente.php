<?php session_start(); 
    error_reporting(E_ALL);
    date_default_timezone_set('Europe/Madrid');
    

  // librerias necesarias
  include 'init.php';
  include '../funciones.php';

  \Stripe\Stripe::setApiKey($skApiKey);

  $intent = \Stripe\PaymentIntent::create([
        'amount' => $amount,
        'currency' => 'eur',
        'payment_method_types' => ['card'],
        'customer' => $stripe,
        'payment_method' => $payment_method,
        ]);

    $DB2->opentable('facturas');
    $DB2->edit($id_factura); 
    $DB2->ta['pi_stripe'] = $intent->id;
    $DB2->update();

  $intent->confirm([
      'off_session' => true
    ]);


    echo json_encode($intent);

  
?>