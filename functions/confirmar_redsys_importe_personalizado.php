<?php session_start();
    error_reporting(E_ALL);
    date_default_timezone_set('Europe/Madrid');
    

    // librerias necesarias
    include 'redsysHMAC256_API_PHP_4.0.2/apiRedsys.php';
    include 'funciones.php';
    include 'phpMailer/PHPMailer.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

   //$pruebas=1;
    if(isset($pruebas) && $pruebas==1){

        $DS_MERCHANT_ORDER=1537277886;
        $response='0000';
        $signatureCalculada="";
        $signatureRecibida="";

    }else{

        // Generamos el objeto Redsys
        $miObj = new RedsysAPI;
        // $ del form para su proceso.
        $params=$_POST['Ds_MerchantParameters'];
        $version=$_POST['Ds_SignatureVersion'];
        $signatureRecibida=$_POST['Ds_Signature'];

        // decodificamos los parametros de la operacion
        $decodec = $miObj->decodeMerchantParameters($params);

        // sacamos el parametro de la orden
        $DS_MERCHANT_ORDER=$miObj->getParameter('Ds_Order');

        // COMPROBAR LA OPERACION
        $response=$miObj->getParameter('Ds_Response');

    }


    // cogemos todos los datos del form, producto y delegacion
    $comunicacion_tpv=$DB->select('varpost,total','comunicacion_tpv_personalizado','DS_MERCHANT_ORDER='.$DS_MERCHANT_ORDER);

    // SACAMOS LOS DATOS DEL CLIENTES 
    $datos_cliente = json_decode(utf8_encode($comunicacion_tpv[f][0]['varpost']),true);
    
    // sacamos los datos de la delegacion
    $comercio=$DB->inner_join('d.nombre,
                    d.id as id_delegacion, 
                    d.tpv_pass,
                    d.email as remitente,
                    d.id_operadora_web,
                    d.id_via_cobro_web,
                    d.web,
                    c.nombre as cliente,
                    c.identificador,
                    c.email as destinatario',
                    'delegaciones d inner join clientes c',
                    'c.id_delegacion=d.id where c.id='.$datos_cliente['id_cliente']);

    $kc = $comercio[f][0]['tpv_pass']; 

    // calcuamos la firma recibida
    $signatureCalculada=$miObj->createMerchantSignatureNotif($kc,$params);



    // SI LA FIRMA ES CORRECTA ENTRAMOS.
    if($signatureCalculada===$signatureRecibida){


        // Si la operacion es ok entramos
        if($response=='0000'){

            // ahora vamos a cambiar a OK la operacion del TPV:
            $DB->query('update comunicacion_tpv_personalizado set finalizada=1 where DS_MERCHANT_ORDER='.$DS_MERCHANT_ORDER);

            ### EMAIL AL CLIENTE
            $mail_c = new phpmailer($comercio[f][0]['remitente']);  
            $mail_c->setFrom($comercio[f][0]['remitente'],$comercio[f][0]['nombre']);
           
            // new solution here
            $j_destinatarios = json_decode($comercio[f][0]['destinatario']);

            $E=1;
            foreach ($j_destinatarios as $value) {
                   
                if($E==1){
                    $mail_c->AddAddress($value);
                }else if($E>1){
                    $mail_c->AddCC($value);
                } 

                $E++;
            }

            $txt_cliente = "Hola ".utf8_encode($comercio[f][0]['cliente']).",<br><br>
            Te informamos que hemos recibido satisfactoriamente el pago de un importe ".number_format($comunicacion_tpv[f][0]['total']/100,2,".","") . " &euro;";
            
            $mail_c->Subject="Pago Recibido en ".$comercio[f][0]['nombre']; 
            $mail_c->Body=body_email(t,"",$comercio[f][0]['id_delegacion'],$txt_cliente);
            $mail_c->send();


            ### A NOSOTROS 
            $mail = new phpmailer($comercio[f][0]['remitente']);  
            $mail->setFrom($comercio[f][0]['remitente'],$comercio[f][0]['nombre']);
            $mail->AddAddress('teresaotero@soltury.com');
            $mail->AddCC("pedrogil@soltury.com");
            
            $txt_email = "Hemos recibido un pago de importe personalizado con los siguientes datos : 
            <br><br>".utf8_encode($comercio[f][0]['cliente'])." (".$comercio[f][0]['identificador'].") 
            <br><br> Por un importe de :".number_format($comunicacion_tpv[f][0]['total']/100,2,".","") . " &euro;
            <br><br>If do you need anything, only let me that";

            $mail->Subject="Operacion Tpv ".$comercio[f][0]['nombre']." Importe Personalizado"; 
            $mail->Body=body_email(t,"",$comercio[f][0]['id_delegacion'],$txt_email);
            $mail->send();

            script("location.href='https://".$comercio[f][0]['web']."/pago_personalizado_recibido/ok'");

        // pago_personalizado_recibido.php
        }else if($response!='0000'){ // eof if($response=='0000'){
                    // AQUI VENIMOS CUANDO OPERACION ES NEGATIVA


            $motivo=$DB->select("motivo","repuestas_redsys","id=".$response);
            $importe_devuelto = $comunicacion_tpv[f][0]['total']/100;
            // LOS EMAILS DE COMUNICACION DE VENTAS, LOS TENEMOS QUE 
            // ADAPTAR TAL Y COMO VIENEN EN procesarCompraTalonarios.php
            $mail = new phpmailer(comercio[f][0]['remitente']);
            $mail->setFrom($comercio[f][0]['remitente'],$comercio[f][0]['nombre']);  
            $mail->AddAddress('teresaotero@soltury.com');
            $mail->AddCC("pedrogil@soltury.com");

            $mail->Subject="Operacion Denegada TPV ".$comercio[f][0]['nombre']; 
            $mail->Body=body_email(t,"",$comercio[f][0]['id_delegacion'],"Se ha denegado una operacion de importe personalzado de ".$importe_devuelto." &euro; por el motivo: ".utf8_encode($motivo[f][0]['motivo']));
            $mail->send();

            script("location.href='https://".$comercio[f][0]['web']."/pago_personalizado_recibido/ko'");
        } // }else if($response!='0000'){

                    
    
    }else{ // eof if($signatureCalculada===$signatureRecibida){

        // aqui venimos cuando la firma del TPV ha sido erronea.
        $mail = new phpmailer($comercio[f][0]['remitente']); 
        $mail->setFrom($comercio[f][0]['remitente'],$comercio[f][0]['nombre']);
        $mail->AddAddress('pedrogil@soltury.com');
        $mail->Subject="Fallo Firma TPV ".$comercio[f][0]['nombre']; 
        $mail->Body=body_email(t,"",$comercio[f][0]['id_delegacion'],"Tenemos un problema en el tpv ".$comercio[f][0]['nombre']);

        $mail->send();

        script("location.href='https://".$_SERVER['SERVER_NAME']."/pago_personalizado_recibido/ko'");

    }

?>