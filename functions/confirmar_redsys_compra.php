<?php session_start();
    error_reporting(E_ALL);
    date_default_timezone_set('Europe/Madrid');
    
    // librerias necesarias
    include 'redsysHMAC256_API_PHP_4.0.2/apiRedsys.php';
    include 'funciones.php';
    include 'phpMailer/PHPMailer.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    if($_SERVER['REMOTE_ADDR']=="5.154.69.122" || $_SERVER['REMOTE_ADDR']=="185.178.136.16"){
        
        // $pruebas=1;

    }
    
    if(isset($pruebas) && $pruebas==1){

        $DS_MERCHANT_ORDER=1553628083;
        $response='0000';
        $signatureCalculada="1";
        $signatureRecibida="1";

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
    $comunicacion_tpv=$DB->select('*','comunicacion_tpv','DS_MERCHANT_ORDER='.$DS_MERCHANT_ORDER);
    $comunicacion_tpv = $comunicacion_tpv[f][0];
    $comercio = array(

                    'nombre' => 'Pastelería la Argentina',
                    'tpv_pass' => 'sq7HjrUOBfKmC576ILgskD5srU870gJ7',
                    'email' => 'web@pastelerialaargentina.com',
                    'web' => 'pastelerialaargentina.com',
                    'color_email' => '190098'

                );

    $kc = $comercio['tpv_pass']; 

    if(!isset($pruebas)){
        // calcuamos la firma recibida
        $signatureCalculada=$miObj->createMerchantSignatureNotif($kc,$params);
    }

    // $pruebas=1;
    // SI LA FIRMA ES CORRECTA ENTRAMOS.
    if($signatureCalculada===$signatureRecibida || isset($pruebas) && $pruebas==1){

        // Si la operacion es ok entramos
        if($response=='0000' || isset($pruebas) && $pruebas==1){

            // SI NO EXISTE ENTRAMOS 
            // ahora vamos a cambiar a OK la operacion del TPV y el pedido
            $DB->query('update comunicacion_tpv set finalizada=1 where DS_MERCHANT_ORDER='.$DS_MERCHANT_ORDER);
            $DB->query('update pedidos set pedido_cobrado = 1 where id = '.$comunicacion_tpv['id_pedido']);

            ## insertamos el movimiento bancario:

            # Email a la empressa
            $mail = new phpmailer($comercio['email']);  
            $mail->setFrom($comercio['email'],$comercio['nombre']); 
            $mail->AddAddress('lulomon26@hotmail.com');
            $mail->AddCC('pastelerialaargentina@gmail.com');
            $mail->Subject=$comercio['nombre']." - Confirmación de Compra"; 
            $dp = $DB->inner_join('c.nombre,c.email,c.telefono,c.direccion,cp,c.poblacion,p.*','pedidos p inner join clientes c','p.id_cliente = c.id where p.id = '.$comunicacion_tpv['id_pedido']);
            $dp = $dp[f][0];

            $DB2->opentable('movimientos_bancarios');
            $DB2->ta['fecha']=now();
            $DB2->ta['id_usuario']=47;
            $DB2->ta['id_delegacion']=6;
            $DB2->ta['tipo']=1;
            $DB2->ta['clase']='venta';
            $DB2->ta['importe']=($dp['importe_final']*100);
            $DB2->ta['concepto']='Venta web ('.$dp['nombre'].')';
            $DB2->ta['id_via_cobro']=2;
            $DB2->ta['verificado']=1;
            $DB2->insert();

            $entrega = $$dp['tipo_entrega'] == 'recogida_en_tienda' ? 'Recogida en tienda' : 'Entrega a Domicilio';

            $txt = 'Hola Compañer@s,<br><br>
                    Hemos recibido un pedido on-line, estos son los datos del Pedido:<br><br>
                    <b>Tipo de pago</b> : Pagado por Tpv (El pedido ya esta abonado).<br>
                    <b>Tipo de entrega</b> : '.$entrega.'.<br>

                    <br>
                    <h3><u>Datos del Envío</u></h3>
                    <b>Cliente</b> : '.utf8_encode($dp['nombre']).'<br>
                    <b>Teléfono</b> : '.($dp['telefono']).'<br>
                    <b>Dirección</b> : '.utf8_encode($dp['direccion']).'<br>
                    <b>Población</b> : '.utf8_encode($dp['poblacion']).'<br>
                    <b>Código Postal</b> : '.utf8_encode($dp['cp']).'<br>
                    <h3><u>Productos</u></h3>
                    <table style="width : 100%">
                        <tbody style="width : 100%">
                            <tr style="border: 1px solid #'.$comercio['color_email'].';">
                                <td style="border: #'.$comercio['color_email'].' 1px solid; text-align: center ; padding: 5px; width : 50%;">Producto</td>
                                <td style="border: #'.$comercio['color_email'].' 1px solid; text-align: center ; padding: 5px;">Uni/Porciones</td>
                                <td style="border: #'.$comercio['color_email'].' 1px solid; text-align: center ; padding: 5px;">Pvp</td>
                                <td style="border: #'.$comercio['color_email'].' 1px solid; text-align: center ; padding: 5px;">Pr.Final</td>
                            </tr>';

                    if($dp['fotos'] != ''){

                        $arr_f = explode(',',$dp['fotos']);
                        $fotos = array();
                        foreach ($arr_f as $key => $value) {
                            
                            list($ft,$in) = explode('-',$value);
                            $fotos[$in] = $ft;
                        }

                    }
                    $pr = json_decode($dp['productos'],true);
                    $p = 1;
                    $pf = 0;
                    foreach ($pr as $k => $v) {

                        if($v['id_familia'] != 4){

                            $d = $DB->select('*','productos_web','id = '.$v['productos']['id_producto']);
                            $d = $d[f][0];
                            if($v['id_familia'] == 5 || $v['id_familia'] == 10 || $v['id_familia'] == 9){

                                $pf += ($v['productos']['pvp']*$v['productos']['unidades']); 
                                $pvp = ($v['productos']['pvp']*$v['productos']['unidades']);
                                $pvp_unidad = $v['productos']['pvp'];

                            }else{

                                $pf += $v['productos']['pvp'];
                                $pvp = $v['productos']['pvp'];
                                $pvp_unidad = $v['productos']['pvp'];

                            }

                            $cartel = $d['nombre_producto']; 
                            $cartel .= $v['id_familia'] == 11 ? '<span style="color : red;"><br>Oferta Especial</span>' : '';

                            if($v['id_familia'] == 6 || $v['id_familia'] == 7 || $v['id_familia'] == 8){

                                $unidades = $v['productos']['tamano'];

                            }else{

                                $unidades = $v['productos']['unidades'];

                            }


                            if(isset($v['productos']['foto_tartas_personalizadas']) && $v['productos']['foto_tartas_personalizadas'] == 1){

                                $cartel .= '<br><img src="https://pastelerialaargentina.com/new_app/img/pedidos_web/'.$fotos[$k].'" alt="" style="width : 100%;"><br>
                                <a href="https://pastelerialaargentina.com/new_app/img/pedidos_web/'.$fotos[$k].'" >Descargar Imagen</a>';

                            }

                            if(isset($v['productos']['foto_tartas_galletas']) && $v['productos']['foto_tartas_galletas'] == 1){

                                $cartel .= '<br><img src="https://pastelerialaargentina.com/new_app/img/pedidos_web/'.$fotos[$k].'" alt="" style="width : 100%;"><br>
                                <a href="https://pastelerialaargentina.com/new_app/img/pedidos_web/'.$fotos[$k].'" >Descargar Imagen</a>';
                            }

                            if(isset($v['productos']['foto_pastelon']) && $v['productos']['foto_pastelon'] == 1){

                                $cartel .= '<br><img src="https://pastelerialaargentina.com/new_app/img/pedidos_web/'.$fotos[$k].'" alt="" style="width : 100%;"><br>
                                <a href="https://pastelerialaargentina.com/new_app/img/pedidos_web/'.$fotos[$k].'" >Descargar Imagen</a>';
                            }

                            if(isset($v['productos']['foto_tartas_predeterminadas']) && $v['productos']['foto_tartas_predeterminadas'] == 1){

                                $cartel .= '<br><img src="https://pastelerialaargentina.com/new_app/img/pedidos_web/'.$fotos[$k].'" alt="" style="width : 100%;"><br>
                                <a href="https://pastelerialaargentina.com/new_app/img/pedidos_web/'.$fotos[$k].'" >Descargar Imagen</a>';
                            }

                        }else{

                            $cartel = 'Bandeja Pasteles Surtidos : ';
                            $pvp_bandeja = 0;
                            $cantidad_bandeja = 0;
                            foreach ($v['productos'] as $l => $val) {

                                $pvp_bandeja += ($val['pvp']*$val['cantidad']);
                                $cantidad_bandeja += $val['cantidad'];
                                $dp = $DB->select('nombre_producto','productos_web','id = '.$val['id_producto']);
                                $cartel .= '<br> <b>'.utf8_encode($dp[f][0]['nombre_producto']).' ('.$val['cantidad'].' Unds)</b>';

                            } // foreach ($v['productos'] as $key => $value) { 

                            $unidades = 1;
                            $pf += $pvp_bandeja;
                            $pvp = $pvp_bandeja;
                            $pvp_unidad = $pvp_bandeja;
                        }


                        $txt.='
                                    <tr style="border: #'.$comercio['color_email'].' 1px solid ;">
                                        <td style="border: #'.$comercio['color_email'].' 1px solid;  padding: 5px; width : 50%;">'.utf8_encode($cartel).'</td>
                                        <td style="border: #'.$comercio['color_email'].' 1px solid; text-align: right; padding: 5px;">'.$unidades.'</td>
                                        <td style="border: #'.$comercio['color_email'].' 1px solid; text-align: right; padding: 5px;">'.nf($pvp_unidad).'&euro;</td>
                                        <td style="border: #'.$comercio['color_email'].' 1px solid; text-align: right; padding: 5px;">'.nf($pvp).'&euro;</td>
                                    </tr>';

                        $p++;

                    } // foreach ($pr as $key => $value) {

                    $txt .= '
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="border: #'.$comercio['color_email'].' 1px solid; text-align: right; padding: 5px;">'.number_format(($pf/100),2,'.','').'&euro;</td>
                                </tr>
                            </tbody>
                        </table>
                        <br><br>
                        La información completa de cada uno de las tartas que pueda contener el pedido, saldrá en la comanda como en el resto de los pedidos';

            $mail->Body=body_email(t,"",1,$txt);
            $mail->send();

            #email al cliente
            $dp = $DB->inner_join('c.nombre,c.email,c.telefono,c.direccion,cp,c.poblacion,p.*','pedidos p inner join clientes c','p.id_cliente = c.id where p.id = '.$comunicacion_tpv['id_pedido']);
            $dp = $dp[f][0];
            $mail_c = new phpmailer($comercio['email']);  
            $mail_c->setFrom($comercio['email'],$comercio['nombre']); 
            $mail_c->AddAddress($dp['email']);
            $mail_c->Subject=$comercio['nombre']." - Confirmación de Compra";
            list($nombre,$apellidos) = explode(' ',$dp['nombre']);

            $frase_1 = $dp['tipo_entrega'] == 'recogida_en_tienda' ? 'podrás recogerlo en la tienda seleccionada' : 'lo recibirás en casa';
            $txt_c = 'Hola '.utf8_encode($nombre).',<br><br>
                    Te confirmamos que hemos recibido correctamente tu pedido, '.$frase_1.' en la fecha que nos has indicado. <br><br>Tu pedido ya ha sido abonado.<br><br>Te recodamos los datos que nos has facilitado:
                    <br>
                    <h3><u>Datos del Envío</u></h3>
                    <b>Cliente</b> : '.utf8_encode($dp['nombre']).'<br>
                    <b>Teléfono</b> : '.($dp['telefono']).'<br>
                    <b>Dirección</b> : '.utf8_encode($dp['direccion']).'<br>
                    <b>Población</b> : '.utf8_encode($dp['poblacion']).'<br>
                    <b>Código Postal</b> : '.utf8_encode($dp['cp']).'<br>
                    <h3><u>Productos</u></h3>
                    <table style="width : 100%">
                        <tbody style="width : 100%">
                            <tr style="border: 1px solid #'.$comercio['color_email'].';">
                                <td style="border: #'.$comercio['color_email'].' 1px solid; text-align: center ; padding: 5px; width : 50%;">Producto</td>
                                <td style="border: #'.$comercio['color_email'].' 1px solid; text-align: center ; padding: 5px;">Uni/Porciones</td>
                                <td style="border: #'.$comercio['color_email'].' 1px solid; text-align: center ; padding: 5px;">Pvp</td>
                                <td style="border: #'.$comercio['color_email'].' 1px solid; text-align: center ; padding: 5px;">Pr.Final</td>
                            </tr>';

                    $pr = json_decode($dp['productos'],true);
                    $p = 1;
                    $pf = 0;
                    foreach ($pr as $k => $v) {

                        if($v['id_familia'] != 4){

                            $d = $DB->select('*','productos_web','id = '.$v['productos']['id_producto']);
                            $d = $d[f][0];
                            if($v['id_familia'] == 5 || $v['id_familia'] == 10 || $v['id_familia'] == 9){

                                $pf += ($v['productos']['pvp']*$v['productos']['unidades']); 
                                $pvp = ($v['productos']['pvp']*$v['productos']['unidades']);
                                $pvp_unidad = $v['productos']['pvp'];

                            }else{

                                $pf += $v['productos']['pvp'];
                                $pvp = $v['productos']['pvp'];
                                $pvp_unidad = $v['productos']['pvp'];

                            }

                            $cartel = $d['nombre_producto']; 
                            $cartel .= $v['id_familia'] == 11 ? '<span style="color : red;"><br>Oferta Especial</span>' : '';
                            if($v['id_familia'] == 6 || $v['id_familia'] == 7 || $v['id_familia'] == 8){

                                $unidades = $v['productos']['tamano'];

                            }else{

                                $unidades = $v['productos']['unidades'];

                            }


                            if(isset($v['productos']['foto_tartas_personalizadas']) && $v['productos']['foto_tartas_personalizadas'] == 1){

                                $cartel .= '<br><img src="https://pastelerialaargentina.com/new_app/img/pedidos_web/'.$fotos[$k].'" alt="" style="width : 100%;">';

                            }

                            if(isset($v['productos']['foto_tartas_galletas']) && $v['productos']['foto_tartas_galletas'] == 1){

                                $cartel .= '<br><img src="https://pastelerialaargentina.com/new_app/img/pedidos_web/'.$fotos[$k].'" alt="" style="width : 100%;">';
                            }

                            if(isset($v['productos']['foto_pastelon']) && $v['productos']['foto_pastelon'] == 1){

                                $cartel .= '<br><img src="https://pastelerialaargentina.com/new_app/img/pedidos_web/'.$fotos[$k].'" alt="" style="width : 100%;">';
                            }

                            if(isset($v['productos']['foto_tartas_predeterminadas']) && $v['productos']['foto_tartas_predeterminadas'] == 1){

                                $cartel .= '<br><img src="https://pastelerialaargentina.com/new_app/img/pedidos_web/'.$fotos[$k].'" alt="" style="width : 100%;">';
                            }

                        }else{

                            $cartel = 'Bandeja Pasteles Surtidos : ';
                            $pvp_bandeja = 0;
                            $cantidad_bandeja = 0;
                            foreach ($v['productos'] as $l => $val) {

                                $pvp_bandeja += ($val['pvp']*$val['cantidad']);
                                $cantidad_bandeja += $val['cantidad'];
                                $dp = $DB->select('nombre_producto','productos_web','id = '.$val['id_producto']);
                                $cartel .= '<br> <b>'.utf8_encode($dp[f][0]['nombre_producto']).' ('.$val['cantidad'].' Unds)</b>';

                            } // foreach ($v['productos'] as $key => $value) { 

                            $unidades = 1;
                            $pf += $pvp_bandeja;
                            $pvp = $pvp_bandeja;
                            $pvp_unidad = $pvp_bandeja;
                        }


                        $txt_c.='
                                    <tr style="border: #'.$comercio['color_email'].' 1px solid ;">
                                        <td style="border: #'.$comercio['color_email'].' 1px solid;  padding: 5px; width : 50%;">'.utf8_encode($cartel).'</td>
                                        <td style="border: #'.$comercio['color_email'].' 1px solid; text-align: right; padding: 5px;">'.$unidades.'</td>
                                        <td style="border: #'.$comercio['color_email'].' 1px solid; text-align: right; padding: 5px;">'.nf($pvp_unidad).'&euro;</td>
                                        <td style="border: #'.$comercio['color_email'].' 1px solid; text-align: right; padding: 5px;">'.nf($pvp).'&euro;</td>
                                    </tr>';

                        $p++;

                    } // foreach ($pr as $key => $value) {

                    $txt_c .= '
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="border: #'.$comercio['color_email'].' 1px solid; text-align: right; padding: 5px;">'.number_format(($pf/100),2,'.','').'&euro;</td>
                                </tr>
                            </tbody>
                        </table>
                        <br><br>
                        Le agradecemos la confianza depositada en nosotros y esperamos volver a verte pronto por '.($comercio['nombre']).'. <br><br>Un cordial saludo del Equipo de '.($comercio['nombre']).'.';

            $mail_c->Body=body_email(t,"",1,$txt_c);
            $mail_c->send();

            // if(!isset($pruebas)){

                script("location.href='https://".$comercio['web']."/gracias.php?id=".$comunicacion_tpv['id_pedido']."'");

            // }
        
        }else if($response!='0000'){ // eof if($response=='0000'){
            
            // AQUI VENIMOS CUANDO OPERACION ES NEGATIVA
            $motivo=$DB->select("motivo","repuestas_redsys","id=".$response);
            $importe_devuelto = $comunicacion_tpv['total']/100;
            // LOS EMAILS DE COMUNICACION DE VENTAS, LOS TENEMOS QUE 
            // ADAPTAR TAL Y COMO VIENEN EN procesarCompraTalonarios.php
            $mail = new phpmailer($comercio['email']); 
            $mail->setFrom($comercio['email'],$comercio['nombre']);
            $mail->AddAddress('lulomon26@hotmail.com');
            $mail->AddCC('pastelerialaargentina@gmail.com');
            $mail->Subject="Operacion denegada TPV ".$comercio['nombre']; 
            $mail->Body=body_email(t,"",1,"Se ha denegado una operacion de ".$importe_devuelto." &euro; por el motivo: ".utf8_encode($motivo[f][0]['motivo']));
            $mail->send();

            if(!isset($pruebas)){

                script("location.href='https://".$comercio['web']."/pago_fallido.php?id_pedido=".$comunicacion_tpv['id_pedido']."'");

            }

        } // }else if($response!='0000'){

    }else{ // eof if($signatureCalculada===$signatureRecibida){

        // aqui venimos cuando la firma del TPV ha sido erronea.
        $mail = new phpmailer($comercio['email']); 
        $mail->setFrom($comercio['email'],$comercio['nombre']);
        $mail->AddAddress('lulomon26@hotmail.com');
        $mail->AddCC('pastelerialaargentina@gmail.com');
        $mail->Subject="Fallo Firma TPV ".$comercio['nombre']; 
        $mail->Body=body_email(t,"",1,"Tenemos un problema en el tpv ".$comercio['nombre']);
        $mail->send();

            if(!isset($pruebas)){

                script("location.href='https://".$comercio['web']."/pago_fallido.php?id_pedido=".$comunicacion_tpv['id_pedido']."'");

            }

    }

?>