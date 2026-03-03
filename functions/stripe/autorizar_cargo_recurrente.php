<?php session_start();
    error_reporting(E_ALL);
    date_default_timezone_set('Europe/Madrid');
    

    // librerias necesarias
    include 'init.php';

    \Stripe\Stripe::setApiKey($skApiKey);



      try {
        
      $intent = null;
        if (isset($_GET['intent_id'])) {
          $intent = \Stripe\PaymentIntent::retrieve(
            $_GET['intent_id']
          );
          $intent->confirm();
        }
        generatePaymentResponse($intent);


      } catch (\Stripe\Error\Base $e) {
        # Display error on client
        echo json_encode([
          'error' => $e->getMessage()
        ]);
      }

      function generatePaymentResponse($intent) {
        # Note that if your API version is before 2019-02-11, 'requires_action'
        # appears as 'requires_source_action'.
        if ($intent->status == 'requires_action' &&
            $intent->next_action->type == 'use_stripe_sdk') {
          # Tell the client to handle the action
          echo json_encode([
            'requires_action' => true,
            'payment_intent_client_secret' => $intent->client_secret
          ]);

        } else if ($intent->status == 'succeeded') {

          # The payment didn’t need any additional actions and completed!
          # Handle post-payment fulfillment
          echo json_encode([
            "success" => trues
          ]);

        } else {

          # Invalid status
          http_response_code(500);
          echo json_encode(['error' => 'Invalid PaymentIntent status']);

        }
      }

?>