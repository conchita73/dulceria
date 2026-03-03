<?php session_start();
    error_reporting(E_ALL);
    date_default_timezone_set('Europe/Madrid');
        // librerias necesarias
    include 'init.php';
    \Stripe\Stripe::setApiKey($skApiKey);

    include '../funciones.php';

    header('Content-Type: application/json');

    # retrieve json from POST body
    $json_str = file_get_contents('php://input');
    $json_obj = json_decode($json_str);


    # SACAMOS DATA COMERCIO
    $d = $DB->select('nombre_comercial,email','comercios','id = '.$json_obj->id_comercio);
    $comercio = $d[f][0];

    # INSTANCIAMOS EL 0BJETO
    \Stripe\Stripe::setApiKey($skApiKey);

    try {

        $customer = \Stripe\Customer::create([
          'payment_method' => $json_obj->payment->setupIntent->payment_method,
          'description' => $json_obj->id_comercio
        ]);


        $DB->query('update comercios set stripe = "'.$customer->id.'",payment_method = "'. $json_obj->payment->setupIntent->payment_method.'"  where id = '.$json_obj->id_comercio);

         echo json_encode([
          'ok' => 1
        ]);

    } catch (\Stripe\Error\Base $e) {
        # Display error on client
        echo json_encode([
          'error' => $e->getMessage(),
          'ok'=>0
        ]);
      }


?>