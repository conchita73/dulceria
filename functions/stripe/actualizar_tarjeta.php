<?php session_start();
    error_reporting(E_ALL);
    date_default_timezone_set('Europe/Madrid');
    

    // librerias necesarias
    include 'init.php';
    include '../funciones.php';

    try {
        # actualizamos el metodo de pago al cliente
        $DB->query('update comercios set payment_method = "'. $payment['setupIntent']['payment_method'].'" where id = '.$id_comercio);

        $d = $DB->inner_join('c.id as id_comercio,
                            c.persona_contacto,
                            c.nombre_comercial,
                            c.email,
                            c.stripe,
                            c.payment_method,
                            c.cif,
                            c.poblacion,
                            f.*,
                            p.nombre_producto,
                            cr.aplica_iva',
                            'comercios c inner join facturas f 
                            ','c.id = f.id_comercio 
                            inner join productos p on f.id_producto = p.id 
                            inner join contratos cr on f.id_contrato = cr.id
                            WHERE f.id = '.$id_factura);


        \Stripe\Stripe::setApiKey($skApiKey);

        $payment_method = \Stripe\PaymentMethod::retrieve($payment['setupIntent']['payment_method']);
        $payment_method->attach(['customer' => $d[f][0]['stripe']]);

        $intent = \Stripe\PaymentIntent::create([
                'amount' => $d[f][0]['importe'],
                'currency' => 'eur',
                'payment_method_types' => ['card'],
                'customer' => $d[f][0]['stripe'],
                'payment_method' => $payment['setupIntent']['payment_method'],
                'off_session' => true,
                'confirm' => true
                ]);


        if($intent->status == 'succeeded'){

            $DB2->opentable('facturas');
            $DB2->edit($id_factura); 
            $DB2->ta['pi_stripe'] = $intent->id;
            $DB2->ta['fecha_cobrada'] = now();
            $DB2->ta['id_via_cobro'] = 1;
            $DB2->ta['estado'] = 1;
            $DB2->update();

            # Enviamos email de pago a Pablo
            $url_cURL="https://".$_SERVER['HTTP_HOST']."/new_app/functions/stripe/pago_procesado.php";
            $post_cURL=array("id_factura"=>$id_factura);

            // operacion curl.
            $ch = curl_init();  
            curl_setopt($ch, CURLOPT_URL, $url_cURL);  
            curl_setopt($ch, CURLOPT_POSTFIELDS,$post_cURL);
            curl_exec($ch);    
            curl_close($ch);

            script('            
                    $("#modal_espera_general").modal("hide");
                    swal("Tarjeta Guardada y cargo Realizado", "Haz Click para cerrar la ventana", {
                        icon : "success",
                        buttons: {                  
                            confirm: {
                                text : "Cerrar la ventana",
                                className : "btn btn-success"
                                }
                        },
                    }).then(

                        function() {
                             
                             setTimeout(function(){ location.href= "https://ckcreativos.com"; }, 3000)
                             $("#agradecimiento").removeClass("hide"); 
                             $("#pasarela_pago").addClass("hide");
                            }

                    );'
                );

        }
        

    } catch (\Stripe\Error\Base $e) {
        # Display error on client
        
            script('            
                $("#modal_espera_general").modal("hide");
                swal("Proceso Fallido!", "No hemos realizar el cargo, intentalo de nuevo", {
                        icon : "error",
                        buttons: {                  
                            confirm: {
                                text : "Cerrar la ventana",
                                className : "btn btn-danger"
                                }
                    },
                }).then(

                    function() {
                             
                        location.reload();
                    }

                );'
            );
    }

?>