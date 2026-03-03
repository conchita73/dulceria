<?php session_start();
    error_reporting(E_ALL);
    date_default_timezone_set('Europe/Madrid');
    

    include '../funciones.php';
    include '../phpMailer/PHPMailer.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;


        # sacamos contador
        $contador = $DB->select('contador','contadores','id=1');
        $nuevo_contador = $contador[f][0]['contador']+1;

        # actualizamos la factura 
        $DB->query('update facturas set 
                    estado = 1, 
                    id_via_cobro = 1,
                    numero='.$nuevo_contador.',
                    fecha_cobrada = "'.now().'" where id='.$id_factura);

        #actualizamos contador
        $DB->query('update contadores set contador = '.$nuevo_contador.' where id = 1');


        $id_delegacion = $DB->inner_join('f.id_delegacion,f.importe,c.nombre_comercial,c.razon_social','facturas f inner join comercios c','f.id_comercio = c.id where f.id='.$id_factura);


        $DB->query('select 
        nombre,
        email,
        email2,
        color_email,
        web
        from delegaciones where id='.$id_delegacion[f][0]['id_delegacion']);
        $datos=$DB->fetch();


        $mail = new phpmailer($datos['email']);
        $mail->setFrom($datos['email'],$datos['nombre']);

        // $mail->AddAddress($datos['email']);
        $mail->addCC($datos['email2']);


        $mail->Subject='Pago Recibido (Stripe) - ('.$id_delegacion['nombre_comercial'].')'; 

        $texto_email = 'Hola,<br><br>
            Te informamos que acabamos de recibir un cobro.
            <br><br>
            <b>Titular:</b>'.utf8_decode($id_delegacion['razon_social']).'<br>
            <b>Nombre Comercial</b>'.utf8_decode($id_delegacion['nombre_comercial']).'<br>
            <b>Importe</b>: '.number_format(($id_delegacion[f][0]['importe']/100),2,'.','').' &euro;<br>
            <b>Fatura N&ordm;</b>: CKM - 00'.$nuevo_contador;
            
        $mail->Body=body_email(t,'',$id_delegacion[f][0]['id_delegacion'],$texto_email);
        $mail->send();
        

?>