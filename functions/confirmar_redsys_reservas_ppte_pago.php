<?php session_start();
    error_reporting(E_ALL);
    date_default_timezone_set('Europe/Madrid');

    // librerias necesarias
    include 'redsysHMAC256_API_PHP_4.0.2/apiRedsys.php';
    include 'funciones.php';
    include 'phpMailer/PHPMailer.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;


    // Generamos el objeto Redsys
    $miObj = new RedsysAPI;
    // $ del form para su proceso.
    $params=$_POST['Ds_MerchantParameters'];
    $version=$_POST['Ds_SignatureVersion'];
    $signatureRecibida=$_POST['Ds_Signature'];

    // decodificamos los parametros de la operacion
    $decodec = $miObj->decodeMerchantParameters($params);

    // // sacamos el parametro de la orden
    $DS_MERCHANT_ORDER=$miObj->getParameter('Ds_Order');

// $DS_MERCHANT_ORDER=1533308777;
    // cogemos todos los datos del form, producto y delegacion
    $comunicacion_tpv=$DB->select('id,varpost,total','comunicacion_tpv_reservas','DS_MERCHANT_ORDER='.$DS_MERCHANT_ORDER);

    // SACAMOS LOS DATOS DEL CLIENTES 
    $datos_reserva = json_decode(utf8_encode($comunicacion_tpv[f][0]['varpost']),true);

    $cliente = $DB->select('id_delegacion,nombre,identificador','clientes','id='.$datos_reserva['id_cliente']);
    $datos_cliente = $cliente[f][0];

    // sacamos los datos de la delegacion
    $comercio=$DB->select('nombre,
                        tpv_pass,
                        email as remitente,
                        id_operadora_web,
                        id_via_cobro_web,
                        web,
                        color_email',
                        'delegaciones',
                        'id='.$datos_cliente['id_delegacion']);

    $kc = $comercio[f][0]['tpv_pass']; 

    // calcuamos la firma recibida
   $signatureCalculada=$miObj->createMerchantSignatureNotif($kc,$params);

    // SI LA FIRMA ES CORRECTA ENTRAMOS.
    if($signatureCalculada===$signatureRecibida){

        // COMPROBAR LA OPERACION
        $response=$miObj->getParameter('Ds_Response');

        // Si la operacion es ok entramos
        if($response=='0000'){

            // PRIMERO VAMOS A MODIFICAR A FINALIZADA LA COMUNICACION CON EL TPV
            $DB->query('update comunicacion_tpv_reservas set finalizada=1 where id='.$comunicacion_tpv[f][0]['id']);

            // cogemos el localizador de la reserva
            $data_reserva = $DB->select('id,localizador,fecha_pago_cliente,entrada,salida,historico','reservas_realizadas','id_comunicacion_tpv_reservas='.$comunicacion_tpv[f][0]['id']);

            // hacemos los cambios pertinentes en reservas_realizadas
            $DB->openTable('reservas_realizadas');
            $DB->edit($data_reserva[f][0]['id']);
            $DB->ta['fecha_cobro']=now();
            $DB->ta['cobrada']=1;
            $DB->ta['id_via_cobro']=$comercio[f][0]['id_via_cobro_web'];
            $nuevo_historico = sumar_historico_xml($data_reserva[f][0]['historico'],$datos_cliente['identificador'],'Cobro',$comunicacion_tpv[f][0]['id'],$comunicacion_tpv[f][0]['total']);
            $DB->ta['historico']=$nuevo_historico;
            $DB->update();

            // añado el movimiento bancario
            $DB->openTable('movimientos_bancarios');
            $DB->ta['id_via_cobro']=$comercio[f][0]['id_via_cobro_web'];
            $DB->ta['importe']=$comunicacion_tpv[f][0]['total'];
            $DB->ta['tipo']=1; // 1= suma /0=resta
            $DB->ta['procedencia']='reservas_realizadas';
            $DB->ta['id_procedencia']=$data_reserva[f][0]['id'];
            $DB->ta['fecha']=now();
            $DB->insert();

            // email de confirmacion de la compra a nosotros mismos
            $mail = new phpmailer($comercio[f][0]['remitente']);  
            $mail->setFrom($comercio[f][0]['remitente'],$comercio[f][0]['nombre']);
            $mail->AddAddress('teresaotero@soltury.com');
            $mail->AddCC("pedrogil@soltury.com");
            $mail->Subject="Reserva que estaba Pendiente de Pago ".$comercio[f][0]['nombre']; 

            $textos="Hemos cobrado la reserva con localizador ".$data_reserva[f][0]['localizador']." que estaba pendiente de pago: <br><br> 
                Por un importe de: ".number_format($comunicacion_tpv[f][0]['total']/100,2,".","") . " &euro;<br><br>
                La fecha de pago clientes es : ".formatear_fecha($data_reserva[f][0]['fecha_pago_cliente'],m,i)."<br /><br />
                El titular de la reserva es : ".$datos_reserva['titular_reserva']."<br><br>
                El titular del Talonario es : ".utf8_encode($datos_cliente['nombre'])." (".$datos_cliente['identificador'].")<br><br>";

            $mail->Body=body_email(t,"",$datos_cliente['id_delegacion'],$textos);
            $mail->send();

            // email de confirmacion de la compra al clientes
            $mailC = new phpmailer($comercio[f][0]['remitente']);  
            $mailC->setFrom($comercio[f][0]['remitente'],$comercio[f][0]['nombre']);
            $mailC->AddAddress($datos_reserva['email_reserva']);
            $mailC->Subject="Pago Recibido en ".$comercio[f][0]['nombre']; 

            $textos = "Hola ".$datos_reserva['titular_reserva'].",<br><br>
                Hemos recibido satisfactoriamente el pago de tu reserva con localizador ".$data_reserva[f][0]['localizador']." <br><br>
                Por un importe de : ".number_format($comunicacion_tpv[f][0]['total']/100,2,".","") . " &euro; <br><br> 
                Si aun no ha descargado la documentaci&oacute;n que tiene que llevar al hotel, puede hacerlo aqu&iacute; mismo:
                <br><br>
                <div style='width:100%; text-align: center; border-bottom:#CCC 1px dotted;  padding:10px 0 10px 0'>
                
                <a href='https:".$comercio[f][0]['web']."/new_app/functions/descargar_documentacion.php?id_voucher=".$data_reserva[f][0]['id']."'
                    style='font-family: Playfair Display,serif; border-radius: 1px; font-weight: 600;border-width: 0;
                    padding: 8px 25px; transition: color .5s , border-color .5s , background-color .5s; font-size: 16px;
                    padding: 10px 35px; color: #fff; background-color: #".$comercio[f][0]['color_email'].";
                    text-decoration:none;'>
                    DESCARGAR DOCUMENTACION PARA EL HOTEL</a>
                <br><br>
                <b style='font-family: Playfair Display,serif;'>Te agradecemos que hayas confiado en nosotros y te deseamos una Feliz Estancia!</b>
                </div>";

            $mailC->Body=body_email(t,"",$datos_cliente['id_delegacion'],$textos);
            $mailC->send();

            script("location.href='https://".$_SERVER['SERVER_NAME']."/zona-privada/".$datos_cliente['identificador']."/mis-reservas/1'");
    

        }else if($response!='0000'){ // eof if($response=='0000'){

            // AQUI VENIMOS CUANDO OPERACION ES NEGATIVA
            $motivo=$DB->select("motivo","repuestas_redsys","id=".$response);
            $importe_devuelto = $comunicacion_tpv[f][0]['total']/100;
            // LOS EMAILS DE COMUNICACION DE VENTAS, LOS TENEMOS QUE 
            // ADAPTAR TAL Y COMO VIENEN EN procesarCompraTalonarios.php
            $mail = new phpmailer($comercio[f][0]['remitente']);  
            $mail->setFrom($comercio[f][0]['remitente'],$comercio[f][0]['nombre']);
            $mail->AddAddress("pedrogil@soltury.com");
            $mail->Subject="Operacion Denegada TPV ".$comercio[f][0]['nombre']; 
            $mail->Body=body_email(t,"",$datos_cliente['id_delegacion'],"Se ha denegado una operacion de pago de una reserva pendiente de pago de ".$importe_devuelto." &euro; por el motivo: <br>".utf8_encode($motivo[f][0]['motivo']));
            $mail->send();

            script("location.href='https://".$_SERVER['SERVER_NAME']."/zona-privada/".$datos_cliente['identificador']."/mis-reservas/0'");

        } // }else if($response!='0000'){
    
    }else{ // eof if($signatureCalculada===$signatureRecibida){

        // aqui venimos cuando la firma del TPV ha sido erronea.
        $mail = new phpmailer($comercio[f][0]['remitente']);  
        $mail->setFrom($comercio[f][0]['remitente'],$comercio[f][0]['nombre']);
        $mail->AddAddress("pedrogil@soltury.com");
        $mail->Subject="Fallo Firma TPV ".$comercio[f][0]['nombre']; 
        $mail->Body=body_email(t,"",$datos_cliente['id_delegacion'],"Tenemos un problema en el tpv ".$comercio[f][0]['nombre']);

        $mail->send();

        script("location.href='https://".$_SERVER['SERVER_NAME']."/zona-privada/".$datos_cliente['identificador']."/mis-reservas/0'");

    }

?>