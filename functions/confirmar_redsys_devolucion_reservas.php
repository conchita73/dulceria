<?php session_start();
    error_reporting(E_ALL);
    date_default_timezone_set('Europe/Madrid');

    // librerias necesarias
    include 'redsysHMAC256_API_PHP_4.0.2/apiRedsys.php';
    include 'funciones.php';
    include 'phpMailer/PHPMailer.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    $miObj = new RedsysAPI;
    // $ del form para su proceso.
    $params=$_POST['Ds_MerchantParameters'];
    $version=$_POST['Ds_SignatureVersion'];
    $signatureRecibida=$_POST['Ds_Signature'];

    // decodificamos los parametros de la operacion
    $decodec = $miObj->decodeMerchantParameters($params);

    // sacamos el parametro de la orden
    $DS_MERCHANT_ORDER=$miObj->getParameter('Ds_Order');
    //$DS_MERCHANT_ORDER=1534256684;

    // cogemos todos los datos del form, producto y delegacion
    $comunicacion_tpv=$DB->select('id,varpost,total','comunicacion_tpv_reservas','DS_MERCHANT_ORDER='.$DS_MERCHANT_ORDER);

    // SACAMOS LOS DATOS DE LA RESERVA (aqui solo viene el id del cliente)
    $datos_reserva = json_decode(utf8_encode($comunicacion_tpv[f][0]['varpost']),true);

    $data_cliente = $DB->select('id,id_delegacion,identificador','clientes','id='.$datos_reserva['id_cliente']);
    $datos_cliente =$data_cliente[f][0];
    // sacamos los datos de la delegacion
    $comercio=$DB->select('nombre,tpv_pass,email,id_operadora_web,id_via_cobro_web,web','delegaciones','id='.$datos_cliente['id_delegacion']);

    $kc = $comercio[f][0]['tpv_pass']; 

    // calcuamos la firma recibida
    $signatureCalculada=$miObj->createMerchantSignatureNotif($kc,$params);

    // SI LA FIRMA ES CORRECTA ENTRAMOS.
    if($signatureCalculada===$signatureRecibida /* || $DS_MERCHANT_ORDER==1534256684*/){

        // COMPROBAR LA OPERACION
        $response=$miObj->getParameter('Ds_Response');


        // Si la operacion es ok entramos
        if($response=='0900' /*|| $DS_MERCHANT_ORDER==1534256684*/){

                $importe_devuelto=$miObj->getParameter('Ds_Amount');
                // $importe_devuelto=110;

                $id_reserva = $DB->select('id,localizador,fecha_cobro,historico','reservas_realizadas','id_comunicacion_tpv_reservas='.$comunicacion_tpv[f][0]['id']);

                $DB->openTable('movimientos_bancarios');
                $DB->ta['id_via_cobro']=$comercio[f][0]['id_via_cobro_web'];
                $DB->ta['importe']=$importe_devuelto;
                $DB->ta['tipo']=0; // 1= suma /0=resta
                $DB->ta['procedencia']='reservas_realizadas';
                //el id de la procedencia viene de las reservas
                // en las reservas voy a poner este id_por ahora
                $DB->ta['id_procedencia']=$id_reserva[f][0]['id'];
                $DB->ta['fecha']=now();
                $DB->insert();

                // hacemos los cambios pertinentes en reservas_realizadas
                $DB->openTable('reservas_realizadas');
                $DB->edit($id_reserva[f][0]['id']);
                $DB->ta['fecha_devolucion']=now();
                $DB->ta['importe_devolucion']=$importe_devuelto;
                $DB->ta['id_via_cobro_devolucion']=$comercio[f][0]['id_via_cobro_web'];
                $nuevo_historico = sumar_historico_xml($id_reserva[f][0]['historico'],$datos_cliente['identificador'],'Devolucion',$comunicacion_tpv[f][0]['id'],$importe_devuelto);
                $DB->ta['historico']=$nuevo_historico;
                $DB->update();

                // email de confirmacion de la devolucion:

                // a nosotros
                $mail = new phpmailer($comercio[f][0]['email']); 
                $mail->setFrom($comercio[f][0]['email'],$comercio[f][0]['nombre']);
                $mail->AddAddress('teresaotero@soltury.com');
                $mail->AddCC("pedrogil@soltury.com");
                $mail->Subject="Devolución Tpv por Cancelacion ".$comercio[f][0]['nombre']; 
                $mail->Body=body_email(t,"",$datos_cliente['id_delegacion'],"
                    Hemos realizado una devoluci&oacute;n AUTOMATICA por cancelaci&oacute;n.<br><br>
                    Localizador <b>".$id_reserva[f][0]['localizador']."</b> <br> 
                    Importe devoluci&oacute;n  <b>".number_format($importe_devuelto/100,2,".","") . " &euro;</b><br>
                    Identificador <b>".$datos_cliente['identificador']."</b> <br>
                    Titular de la reserva <b>".$datos_reserva['titular_reserva']."</b><br><br>
                    SI NECESITAS MAS DATOS ME LOS PIDES");
                $mail->send();
 
                // al cliente
                $mail2 = new phpmailer($comercio[f][0]['email']);
                $mail2->setFrom($comercio[f][0]['email'],$comercio[f][0]['nombre']);  
               
                $mail2->AddAddress($datos_reserva['email_reserva']);
                $mail2->Subject="Devolucion TPV ".$comercio[f][0]['nombre']; 
                $mail2->Body=body_email(t,"",$datos_cliente['id_delegacion'],"Hola ".$datos_reserva['titular_reserva'].",<br><br>
                Te confirmamos que te hemos realizado una devoluci&oacute;n: por un importe de <b>".number_format($importe_devuelto/100,2,".","") . " &euro;</b> en concepto de la cancelaci&oacute;n de la reserva con localizador <b>".$id_reserva[f][0]['localizador']."</b>. De ahora en adelante puedes consultar todos los datos de cancelaci&oacute;n desde la secci&oacute;n de <b>\"Mis Reservas\"</b> dentro de tu zona privada de Cliente.<br><br>
                Un Saludo del Equipo de Soltury.");
                $mail2->send();

                if($devolucion==1){

                    script("location.href='https://".$comercio[f][0]['web']."/zona-privada/".$datos_cliente['identificador']."/mis-reservas/2'");

                }else if($devolucion==2){

                    script("location.href='https://soltury.com/dulceria/super_admin.php?devolucion_realizada=1'");

                }
                

        
        }else if($response!='0900'){ // eof if($response=='0900'){
                    // AQUI VENIMOS CUANDO OPERACION ES NEGATIVA


            $motivo=$DB->select("motivo","repuestas_redsys","id=".$response);
            $importe_devuelto = $comunicacion_tpv[f][0]['total']/100;
            // LOS EMAILS DE COMUNICACION DE VENTAS, LOS TENEMOS QUE 
            // ADAPTAR TAL Y COMO VIENEN EN procesarCompraTalonarios.php
            $mail = new phpmailer($comercio[f][0]['email']); 
            $mail->setFrom($comercio[f][0]['email'],$comercio[f][0]['nombre']);
            
            $mail->AddAddress('teresaotero@soltury.com');
            $mail->AddCC("pedrogil@soltury.com");
            $mail->Subject="Operacion Denegada TPV ".$comercio[f][0]['nombre']; 
            $mail->Body=body_email(t,"",$datos_cliente['id_delegacion'],"Se ha denegado una devolucion de ".$importe_devuelto." &euro; por el motivo: ".utf8_encode($motivo[f][0]['motivo']));
            $mail->send();


                if($devolucion==0){

                    script("location.href='https://".$comercio[f][0]['web']."/zona-privada/".$datos_cliente['identificador']."/mis-reservas/3'");

                }else if($devolucion==3){

                    script("location.href='https://soltury.com/dulceria/super_admin.php?devolucion_realizada=0'");

                }

        } // }else if($response!='0000'){

    
    }else{ // eof if($signatureCalculada===$signatureRecibida){

        // aqui venimos cuando la firma del TPV ha sido erronea.
        $mail = new phpmailer($comercio[f][0]['email']);  
        $mail->setFrom($comercio[f][0]['email'],$comercio[f][0]['nombre']);

        $mail->AddAddress("pedrogil@soltury.com");
        $mail->Subject="Fallo Firma TPV ".$comercio[f][0]['nombre']; 
        $mail->Body=body_email(t,"",$datos_cliente['id_delegacion'],"Tenemos un problema en el tpv ".$comercio[f][0]['nombre']);

        $mail->send();

        script("location.href='https://".$_SERVER['SERVER_NAME']."/zona-privada/".$datos_cliente['identificador']."/mis-reservas/3'");


    }

?>