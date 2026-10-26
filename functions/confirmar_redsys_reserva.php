<?php @session_start(); // quitar todo lo que poner quitar OK
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

    $params=$Ds_MerchantParameters;
    $version=$Ds_SignatureVersion;
    $signatureRecibida=$Ds_Signature;

    // decodificamos los parametros de la operacion
    $decodec = $miObj->decodeMerchantParameters($params);

    // sacamos el parametro de la orden
    $DS_MERCHANT_ORDER=$miObj->getParameter('Ds_Order');
    //$DS_MERCHANT_ORDER=1534775601; // quitar

    // cogemos todos los datos del form, producto y delegacion
    $comunicacion_tpv=$DB->select('id,varpost,total','comunicacion_tpv_reservas','DS_MERCHANT_ORDER='.$DS_MERCHANT_ORDER);

    // SACAMOS TODOS LOS DATOS Y LOS PASAMOS A POST Y DESPUES A LOCALES PARA
    // NO TENER QUE TOCAR NADA ABAJO.
    $_POST = json_decode(utf8_encode($comunicacion_tpv[f][0]['varpost']),true, 512, JSON_UNESCAPED_UNICODE);

    // añado esto, para que entre en $C como parametro y pueda usarlo en 
    // $C->confirmar() 4 coger los datos de titular etc.....
    $_POST['comunicacion_tpv_reservas']=$comunicacion_tpv[f][0]['id'];
    foreach ($_POST as $key => $value) {
        ${$key} = $value;
       
    }


    // sacamos los datos del cliente y los datos de la delegacion
    $comercio=$DB->inner_join('c.nombre as cliente,
                            c.id_delegacion,
                            c.id,
                            c.identificador,
                            c.comentario,
                            d.nombre as delegacion,
                            c.email as destinatario,
                            d.color_email,
                            d.IBAN,
                            d.email as remitente,
                            d.nombre,
                            d.tpv_pass,
                            d.id_operadora_web,
                            d.id_via_cobro_web,
                            d.web'
                            ,'delegaciones d inner join clientes c',
                            'd.id=c.id_delegacion where c.id='.$_POST['id_cliente'],f);
    $comercio=$comercio[f][0];

    ##### AQUI LO VAMOS A AJUSTAR A 1 SOLA CONSULTA.


    $kc = $comercio['tpv_pass']; 

    // calcuamos la firma recibida
    $signatureCalculada=$miObj->createMerchantSignatureNotif($kc,$params);

    // SI LA FIRMA ES CORRECTA ENTRAMOS.
    // $entrar=0; // quitar
    if($signatureCalculada===$signatureRecibida /*|| $DS_MERCHANT_ORDER==1534775601*/){

        // COMPROBAR LA OPERACION
        $response=$miObj->getParameter('Ds_Response');

        // $response='0000'; // quitar 
        // Si la operacion es ok entramos
        if($response=='0000' /*|| $DS_MERCHANT_ORDER==1534775601*/){

            # 1 = pago aceptado
            $DB->query('update comunicacion_tpv_reservas set procesos=1 where id='.$comunicacion_tpv[f][0]['id']);


            # 2 = confimacion lanzada
            include('proveedores/controller.php');
            $C = new control($_POST,$_POST['proveedor']);
            $confirmacion = $C->confirmar($comunicacion_tpv[f][0]['id']); // este me devuelve el 

           $DB->query('update comunicacion_tpv_reservas set procesos=2 where id='.$comunicacion_tpv[f][0]['id']);


            if($confirmacion=="" || $confirmacion==false || $confirmacion['confirmacion']==0){

                # 3 = confirmacion fallida
                $DB->query('update comunicacion_tpv_reservas set procesos=3 where id='.$comunicacion_tpv[f][0]['id']);

                
                # 4 = email confirmacion fallida nosotros

                $mail = new phpmailer($comercio['remitente']); 
                $mail->setFrom($comercio['remitente'],$comercio['nombre']); 
                $mail->AddAddress('teresaotero@soltury.com');
                $mail->AddCC("pedrogil@soltury.com");

                $mail->Subject="Error en la CONFIRMACION PAGADA con ".$_POST['proveedor']; 
                // sacamos aqui el cuerpo de 
                $data_confirmacion=$DB->inner_join('id_cliente,id_delegacion,xml,id_xml,id_booking','_xml_confirmaciones x inner join clientes c','x.id_cliente = c.id where x.id='.$_SESSION['id_confirmacion']);

                $data_xml = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '',$data_confirmacion[f][0]['xml']),true);

                $texto = "La reserva estaba PAGADA del cliente ".$data_confirmacion[f][0]['id_cliente']." con id_booking ".$data_confirmacion[f][0]['id_booking'].", hay que buscar una soluci&oacute;n ya que no le hemos devuelto el dinero al cliente y la respuesta del proveedor es :
                <br><br>" .$confirmacion['motivo']."<br><br>
                El xml devuelto es : <br><br>".$data_xml['xml_response'];

                $mail->Body=body_email(t,"",$data_confirmacion[f][0]['id_delegacion'],$texto);

                $mail->send();

                $DB->query('update comunicacion_tpv_reservas set procesos=4 where id='.$comunicacion_tpv[f][0]['id']);

                 
                # 5 = email confirmacion fallida cliente
                $mailC = new phpmailer($comercio['remitente']); 
                $mailC->setFrom($comercio['remitente'],$comercio['nombre']); 
                $mailC->AddAddress($email_reserva);
                $mailC->Subject="Error en la confirmacion de tu Reserva"; 

                $texto = "Hola ".$titular_reserva.",<br><br>
                Sentimos informarte que ha sucedido un error en la confirmaci&oacute;n de tu reserva, pero <b>no te precoupes</b> por nada por que en breve te contactaremos con <b>una soluci&oacute;n</b>. Vamos a intentar <b>confirmar</b> la reserva con el hotel o en caso contrario te realizaremos la <b>devoluci&oacute;n</b> del importe que has abonado.<br><br>Disculpa las molestias ocasionadas.";

                $mailC->Body=body_email(t,"",$comercio['id_delegacion'],$texto);

                $mailC->send();
                
                $DB->query('update comunicacion_tpv_reservas set procesos=5 where id='.$comunicacion_tpv[f][0]['id']);


                # 6 = movimiento bancario tpv_reservas x confir fallido
                $DB->openTable('movimientos_bancarios');
                $DB->ta['id_via_cobro']=$comercio['id_via_cobro_web'];//importe
                $DB->ta['importe']=$comunicacion_tpv[f][0]['total'];
                $DB->ta['tipo']=1; // 1= suma /0=resta
                $DB->ta['procedencia']='comunicacion_tpv_reservas';
                $DB->ta['id_procedencia']=$comunicacion_tpv[f][0]['id'];
                $DB->ta['fecha']=now();
                $DB->insert();
                
                $DB->query('update comunicacion_tpv_reservas set procesos=6 where id='.$comunicacion_tpv[f][0]['id']);


                script("location.href='https://".$_SERVER['SERVER_NAME']."/zona-privada/".$comercio['identificador']."/reserva_finalizada/".$comunicacion_tpv[f][0]['id']."/redsys'");


                /**######### AQUI PODRIAMOS METER UNA DEVOLCUION AUTOMATIC YA VEREMOS **/

            }else if($confirmacion['confirmacion']==1){

                $DB->query('update comunicacion_tpv_reservas set finalizada=1 where id='.$comunicacion_tpv[f][0]['id']);
                # 7 = confirmacion correcta
                $DB->query('update comunicacion_tpv_reservas set procesos=7 where id='.$comunicacion_tpv[f][0]['id']);


                # 8 = reserva registrada en el sistema
                $DB->openTable('reservas_realizadas');
                $DB->ta['id_cliente']=$id_cliente;
                $DB->ta['proveedor']=$proveedor;
                $DB->ta['localizador']=$confirmacion['localizador'];
                $DB->ta['entrada']=$entrada;
                $DB->ta['salida']=$salida;
                $DB->ta['estado']=1;
                $DB->ta['id_comunicacion_tpv_reservas']=$comunicacion_tpv[f][0]['id'];
                $DB->ta['id_confirmacion']=$_SESSION['id_confirmacion'];
                $_POST['pagadero']=$confirmacion['pagadero'];

                // se usara en las posibles cnacelaciones:
                // EUROPLAYAS => LOCALIZADOR
                // SERHS => codigo
                // BEDS => LOCALIZADOR
                // NEW => LOCALIZADOR
                // VETURIS => BookingID + SecurityCode
                if($proveedor=='id_serhs'){ 

                    $_POST['codigo']=$confirmacion['codigo'];

                }

                if($proveedor=='id_veturis'){

                    $_POST['SecurityCode']=$confirmacion['SecurityCode'];
                    $_POST['BookingID']=$confirmacion['BookingID'];
                    $_POST['observaciones_proveedor'] = str_replace("€","&euro;",$_POST['observaciones_proveedor']);
    
                    
                }

                if($confirmacion['observaciones_proveedor']!=""){

                    $find=array("´","'","’","€");
                    $changue=array('','','',"&euro;");
                    $confirmacion['observaciones_proveedor']= str_replace($find,$changue, $confirmacion['observaciones_proveedor']);
                    // quitar saltos de linea por que da error al json_decode en descargardocumentacion 
                    $order   = array("\r\n", "\n", "\r","\t","\0","\x0B","'"); 
                    // arreglo de las observaciones al paso por new_travelers


                    $_POST['observaciones_proveedor']=str_replace($order, '',tildes_to_html(strip_tags($confirmacion['observaciones_proveedor'])));

                    // if($proveedor=='id_versys'){

                    //     $_POST['observaciones_proveedor']=str_replace($order, '',$confirmacion['observaciones_proveedor']);

                    // }else{

                    //     $_POST['observaciones_proveedor']=str_replace($order, '',utf8_decode($confirmacion['observaciones_proveedor']));
                    // }
                    
                }


                #
                # VERSION 1.2 ELIMINAMOS QUE DEVUELVAN 
                # TODOS LOS PROVEEDORES LOS GASTOS 
                # Y LOS METEMOS DESDE DEL LISTADO, YA ESTA ECHO POR VETURIS.
                #

                // en veturis los traemos del listado
                if($proveedor!="id_veturis"){

                    // ordenamos los que traigan las fechas inversas
                    foreach ($confirmacion['cancelacion'] as $clave => $fila) {

                        $confirmacion['cancelacion'][$clave]['fecha'] = str_replace("/","-",$fila['fecha']);

                    }

                    // esta funcion solo ordena correctamemnte si la fecha tiene el formato
                    // 21-12-2017
                    usort($confirmacion['cancelacion'], 'ordenar_por_fecha');

                    $_POST['gastos']=count($confirmacion['cancelacion']);
                    for($i=0;$i<count($confirmacion['cancelacion']);$i++){

                        $_POST['fecha_'.$i]=str_replace("-","/",$confirmacion['cancelacion'][$i]['fecha']);
                        $_POST['importe_'.$i]=number_format($confirmacion['cancelacion'][$i]['total'],2,".","");
                                                           
                    } // for($i=0;$i<count($cancelaciones);$i++){

                }

                $_POST['titular_reserva']=utf8_encode($_POST['titular_reserva']);
                $_POST['observaciones_reserva']=utf8_encode($_POST['observaciones_reserva']);
                $_POST['regimen'] = utf8_encode($_POST['regimen']);
                $_POST['habitacion'] = utf8_encode($_POST['habitacion']);

                
                $DB->ta['voucher']=utf8_decode(my_json_encode($_POST));

                $DB->ta['fecha_cobro']=now();
                $DB->ta['cobrada']=1;
                $DB->ta['id_via_cobro']=$comercio['id_via_cobro_web'];
                $id_hoteles = $DB->select('id','_hoteles',$proveedor.' like "'.$codeHotel.'"');
                $DB->ta['id_hoteles']=$id_hoteles[f][0]['id'];
                $DB->ta['id_hotel']=$codeHotel;
                $DB->ta['neto']=$confirmacion['neto']*100;
                $DB->ta['total']=$total;
                $DB->ta['bonos_gastados']=$resta_bonos;
                $DB->ta['fecha']=now();
                // CREAMOS LA ESTRUCTURA
                $historico= new SimpleXMLElement('<?xml version="1.0"?><historico></historico>');
                // AÑADIMOS 1º REGISTRO
                $registro=$historico->addChild('registro');
                $registro->addChild('usuario',$comercio['identificador']);
                $registro->addChild('tipo','creacion');
                $registro->addChild('fecha',date('d/m/Y'));
                $registro->addChild('hora',date('H:i:s'));
                // AÑADO TAMBIEN EL PASO DEL COBRO
                $nuevo_historico = sumar_historico_xml($historico->asXML(),$comercio['identificador'],'Cobro',$comunicacion_tpv[f][0]['id'],$comunicacion_tpv[f][0]['total']);

                $DB->ta['historico']=$nuevo_historico;

                $id_reserva = $DB->insert();

                $DB->query('update comunicacion_tpv_reservas set procesos=8 where id='.$comunicacion_tpv[f][0]['id']);


                # 9 = operaciones de resta de bonos.
                if($resta_bonos>0){

                    // sacamos los datos
                    $noches=$DB->select('noches','clientes','id='.$id_cliente);
                    $data_bonos=json_decode($noches[f][0]['noches'],true);

                    // restamos los gastados
                    $arr_noches['disponibles'] = $data_bonos['disponibles']-$resta_bonos;
                    $arr_noches['usados'] = $data_bonos['usados']+$resta_bonos;
                    $arr_noches['comprados'] = (int) $data_bonos['comprados'];

                    $noches_json = json_encode($arr_noches);

                    // modificamos en bbdd
                    $DB->openTable('clientes');
                    $DB->edit($id_cliente);
                    $DB->ta['noches']=$noches_json;
                    $DB->update();

                    $bonos_gastados=0;
                    // si ha gasrtado mas de lo que tenía disponible
                    // incluimos avisador en email de confirmacion, proforma
                    if($arr_noches['disponibles']==0){
                        // enviamos oferta de talonario
                        $bonos_gastados=1;
                    }else if($arr_noches['disponibles']<0){
                        // les/nos mandamos ofertas de bonos + avisador 
                        $bonos_gastados=2;
                    }

                } // if($resta_bonos>0){

                $DB->query('update comunicacion_tpv_reservas set procesos=9 where id='.$comunicacion_tpv[f][0]['id']);

                # 10 = email enviado al cliente
                $mail = new phpmailer($comercio['remitente']);
                $mail->setFrom($comercio['remitente'],$comercio['nombre']);

                // new solution here
                $j_destinatarios = explode(";",$email_reserva);

                $E=1;
                foreach ($j_destinatarios as $value) {
                                       
                    if($E==1){
                        $mail->AddAddress($value);
                    }else if($E>1){
                        $mail->AddCC($value);
                    }
                                        
                    $E++;
                        
                } // foreach ($j_destinatarios as $value) {


                $mail->Subject="Confirmación de reserva en " .$comercio['delegacion'];

                $datos_cliente=array("id"=>$id_cliente,"nombre"=>utf8_decode($titular_reserva),
                "identificador"=>$comercio['identificador'],
                "comentario"=>$comercio['comentario']);

                // me falta meter el mensaje de que faltan bonos<
                $total_final=$total/100;
                $textos ='Tu reserva se ha <b>confirmado y pagado</b> correctamente con los siguientes datos:<br><br>

                <div style="width:100%; 
                    border-bottom:#CCC 1px dotted; 
                    border-top:#CCC 1px dotted;
                    padding:10px 0 10px 0">
                    <table style="width:100%; " >
                        <caption style="height:40px; "><b>LOCALIZADOR '.$confirmacion['localizador'].'</b></caption>

                        <tbody>
                            <tr>
                                <td style="width:50%;">Titular de la Reserva:</td>
                                <td style="width: 50%;"><b>'.$titular_reserva.'</b></td>
                            </tr>
                            <tr>                  
                                <td style="width:50%;">Fecha de Entrada:</td>
                                <td style="width: 50%;"><b>'.formatear_fecha($entrada,m,i).'</b></td>
                            </tr>
                            <tr>
                                <td style="width:50%;">Fecha de Salida:</td>
                                <td style="width: 50%;"><b>'.formatear_fecha($salida,m,i).'</b></td>
                            </tr>
                            <tr>
                                <td style="width:50%;">Tury-Checks Gastados:</td>
                                <td style="width: 50%;"><b>'.$resta_bonos.'</b></td>
                            </tr>
                            <tr>
                                <td style="width:50%;">Fecha L&iacute;mite de Pago:</td>
                                <td style="width: 50%;"><b>'.resta_dias($_POST['fecha_1'],5).'</b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
                En breve recibiras un email con la confirmaci&oacute;n del pago de tu reserva.
                <br><br>';

                if($comercio['delegacion'] == "Soltury"){

                    $txt_codigo = 'cargador'.$identificador_codigo_descuento;

                    $DB->openTable('codigos_descuento');
                    $DB->ta['texto']=$txt_codigo;
                    $fecha=date('Y-m-d');
                    $vigencia = suma_dias($fecha,2,m,m);
                    $DB->ta['vigencia']=$vigencia;
                    $DB->ta['id_cliente']=$id_cliente;
                    $DB->ta['importe']=60;
                    $DB->insert();

                    $txt_oferta = '<span style="font-size : 1.4em;"><b>Tu talonario de 24 noches por <span style="color : red;">solo 29,00€</span> + 1 Cargador Solar de Regalo.</b></span>';

                    $textos='<div style="width:100%; 
                    border-bottom:#CCC 1px dotted; 
                    border-top:#CCC 1px dotted;
                    padding:10px 0 10px 0; text-align:center; font-family: Playfair Display,serif; font-weight: 600; font-size: 1.2em;">
                    <div style="width:100%;  color:red; 
                    font-size: 1.2em; "> IMPORTANTE </div>
                        Con los Tury-Checks que has gastado en esta reserva has '.$frase_agotado.' tu talonario. Y por eso tenemos esta oferta especial para t&iacute;: <br><br> '.$txt_oferta.'<br><br> 

                            <span style="font-size: 1.5em;" ><b>Tu c&oacute;digo Personalizado es:</b> <br>

                            '.$txt_codigo.'</span><br><br>

                        <img src="https://'.$_SERVER['SERVER_NAME'].'/new_soltury/images/cargadores/0.jpg"><br><br><span style="color : red;">*Este c&oacute;digo tiene una validez de 2 d&iacute;as desde hoy <br> **El precio real sin el descuento es de 89,00€</span>
                        <br><br>
                        <a href="https://'.$_SERVER['SERVER_NAME'].'/new_soltury/index.php?proceso_entrada=regalo_cargador&id_cliente_oferta='.$id_cliente.'&codigo_personalizado='.$txt_codigo.'"
                    style="font-family: Playfair Display,serif; border-radius: 1px; font-weight: 600;border-width: 0;
                    padding: 8px 25px; transition: color .5s , border-color .5s , background-color .5s; font-size: 16px;
                    padding: 10px 35px; color: #fff; background-color: #'.$comercio['color_email'].';
                    text-decoration:none;">
                    USAR MI CODIGO PERSONALIZADO
                    </a>
                    <br><br>
                    </div>';

                }else{

                    $txt_codigo = $identificador_codigo_descuento.'vuelve75';

                    $DB->openTable('codigos_descuento');
                    $DB->ta['texto']=$txt_codigo;
                    $fecha=date('Y-m-d');
                    $vigencia = suma_dias($fecha,2,m,m);
                    $DB->ta['vigencia']=$vigencia;
                    $DB->ta['id_cliente']=$id_cliente;
                    $DB->ta['porcentaje']=75;
                    $DB->insert();

                    $txt_oferta = 'todos tus talonarios con descuentos del 75%';

                    $textos.='<div style="width:100%; 
                    border-bottom:#CCC 1px dotted; 
                    border-top:#CCC 1px dotted;
                    padding:10px 0 10px 0; text-align:center; font-family: Playfair Display,serif; font-weight: 600;">
                    <div style="width:100%;  color:red; 
                    font-size: 16px; "> IMPORTANTE </div>
                        Con los Tury-Checks que has gastado en esta reserva has '.$frase_agotado.' tu talonario. Y esto es una buena noticia, por que ahora tienes, por tiempo limitado<sup>*</sup> '.$txt_oferta.'<br> Introduce en la secci&oacute;n de &quot;Ofertas&quot; tu c&oacute;digo especial: <br> 
                        <span style="font-size: 16px;" >'.$txt_codigo.'</span><br>*Este c&oacute;digo tiene una validez de 2 d&iacute;as desde hoy 
                    </div>';

                }


            } //  if($bonos_gastados==1 || $bonos_gastados==2){

                $textos.='Para descargar la documentaci&oacute;n que tienes que llevar al hotel s&oacute;lo tienes que pulsar el siguiente boton:
                <br><br>
                <div style="width:100%; text-align: center;
                    border-bottom:#CCC 1px dotted; 

                    padding:10px 0 10px 0">
                
                <a href="https:'.$_SERVER['SERVER_NAME'].'/dulceria/functions/descargar_documentacion.php?id_voucher='.$id_reserva.'"
                    style="font-family: Playfair Display,serif; border-radius: 1px; font-weight: 600;border-width: 0;
                    padding: 8px 25px; transition: color .5s , border-color .5s , background-color .5s; font-size: 16px;
                    padding: 10px 35px; color: #fff; background-color: #'.$comercio['color_email'].';
                    text-decoration:none;">
                    DESCARGAR DOCUMENTACION PARA EL HOTEL
                    </a>
                    <br><br>
                <b style="ont-family: Playfair Display,serif;">Te agradecemos que hayas confiado en nosotros y te deseamos una Feliz Estancia!</b>
                </div>
                ';

                $mail->Body=body_email(f,$datos_cliente,$comercio['id_delegacion'],$textos);
                $mail->send();
                
                $DB->query('update comunicacion_tpv_reservas set procesos=10 where id='.$comunicacion_tpv[f][0]['id']);


                # 11 = movimiento bancario reservas_realizadas
                $DB->openTable('movimientos_bancarios');
                $DB->ta['id_via_cobro']=$comercio['id_via_cobro_web'];//importe
                $DB->ta['importe']=$comunicacion_tpv[f][0]['total'];
                $DB->ta['tipo']=1; // 1= suma /0=resta
                $DB->ta['procedencia']='reservas_realizadas';
                $DB->ta['id_procedencia']=$id_reserva;
                $DB->ta['fecha']=now();
                $DB->insert();
                
                $DB->query('update comunicacion_tpv_reservas set procesos=11 where id='.$comunicacion_tpv[f][0]['id']);

                # 12 = email de confirmacion a nosotros
                $mail_2 = new phpmailer($comercio['remitente']);
                $mail_2->setFrom($comercio['remitente'],$comercio['nombre']);
                $mail_2->AddAddress('teresaotero@soltury.com');
                $mail_2->AddCC("pedrogil@soltury.com");




                $mail_2->Subject="Reserva Pagada en " .$comercio['delegacion'];

                $datos_cliente=array("id"=>$id_cliente,"nombre"=>utf8_decode($titular_reserva),
                "identificador"=>$comercio['identificador'],
                "comentario"=>$comercio['comentario']);

                $total_final=$total/100;
                $textos ='Se ha confirmado <b>confirmado y pagado</b> una reserva en correctamente con los siguientes datos:<br><br>

                <div style="width:100%; 
                    border-bottom:#CCC 1px dotted; 
                    border-top:#CCC 1px dotted;
                    padding:10px 0 10px 0">
                    <table style="width:100%; " >
                        <caption style="height:40px; "><b>LOCALIZADOR '.$confirmacion['localizador'].'</b></caption>

                        <tbody>
                            <tr>
                                <td style="width:50%;">Titular de la Reserva:</td>
                                <td style="width: 50%;"><b>'.$titular_reserva.'</b></td>
                            </tr>
                            <tr>
                                <td style="width:50%;">Titular de los bonos</td>
                                <td style="width: 50%;"><b>'.utf8_encode($comercio['cliente']).' ('.$comercio['identificador'].')</b></td>
                            </tr>
                            <tr>                  
                                <td style="width:50%;">Fecha de Entrada:</td>
                                <td style="width: 50%;"><b>'.formatear_fecha($entrada,m,i).'</b></td>
                            </tr>
                            <tr>
                                <td style="width:50%;">Fecha de Salida:</td>
                                <td style="width: 50%;"><b>'.formatear_fecha($salida,m,i).'</b></td>
                            </tr>
                            <tr>
                                <td style="width:50%;">Importe Total:</td>
                                <td style="width: 50%;"><b>'.number_format($total_final,2,".","").' &euro;</b></td>
                            </tr>
                            <tr>
                                <td style="width:50%;">Tury-Checks Gastados:</td>
                                <td style="width: 50%;"><b>'.$resta_bonos.'</b></td>
                            </tr>
                            <tr>
                                <td style="width:50%;">Fecha L&iacute;mite de Pago:</td>
                                <td style="width: 50%;"><b>'.resta_dias($_POST['fecha_1'],5).'</b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>';

                if($bonos_gastados==1 || $bonos_gastados==2){


                    $frase_agotado='agotado';
                    if($bonos_gastados==2){
                        $frase_agotado='superado los bonos de';
                    }

                    $textos.='<div style="width:100%; 
                    border-bottom:#CCC 1px dotted; 
                    border-top:#CCC 1px dotted;
                    padding:10px 0 10px 0; text-align:center; font-family: Playfair Display,serif; font-weight: 600;">
                    <div style="width:100%;  color:red; 
                    font-size: 16px; "> IMPORTANTE </div>
                        El cliente ha '.$frase_agotado.' su talonario. Y esto es una buena noticia, por que ahora tienes la oportunidad de venderle otro talonario ;)
                    </div><br>';

                }

                $textos.='
                <div style="width:100%; text-align: center;
                    border-bottom:#CCC 1px dotted; 

                    padding:10px 0 10px 0">
                
                <a href="https:'.$comercio['web'].'/dulceria/functions/descargar_documentacion.php?id_proforma='.$id_reserva.'"
                    style="font-family: Playfair Display,serif; border-radius: 1px; font-weight: 600;border-width: 0;
                    padding: 8px 25px; transition: color .5s , border-color .5s , background-color .5s; font-size: 16px;
                    padding: 10px 35px; color: #fff; background-color: #'.$comercio['color_email'].';
                    text-decoration:none;">
                    DESCARGAR FACTURA PROFORMA
                    </a>
                    <br><br>
                </div>
                ';

                $mail_2->Body=body_email(t,"",$comercio['id_delegacion'],$textos);
                $mail_2->send();
                
                $DB->query('update comunicacion_tpv_reservas set procesos=12 where id='.$comunicacion_tpv[f][0]['id']);

                $mail = new phpmailer($comercio['remitente']); 
                $mail->setFrom($comercio['remitente'],$comercio['nombre']); 
                $mail->AddAddress($email_reserva);

                $textos_pago = 'Enhorabuena '.$titular_reserva.',<br><br> Hemos recibido satisfactoriamente el pago por un importe de <b>'.number_format($total_final,2,".","").'</b> &euro; en concepto de pago de la reserva con localizador <b>'.$confirmacion['localizador'].'</b>
                    <br><br>Esperamos que la disfrutes !';

                $mail->Subject="Confirmación de Pago en " .$comercio['delegacion'];
                $mail->Body=body_email(t,$datos_cliente,$comercio['id_delegacion'],$textos_pago);
                $mail->send();

                $DB->query('update comunicacion_tpv_reservas set procesos=13 where id='.$comunicacion_tpv[f][0]['id']);
                

     #### HACER ESTO UNA FUNCION  #### HACER ESTO UNA FUNCION  #### HACER ESTO UNA FUNCION 

                  script("location.href='https://".$_SERVER['SERVER_NAME']."/zona-privada/".$comercio['identificador']."/reserva_finalizada/".$comunicacion_tpv[f][0]['id']."/redsys'");


            } // else if($confirmacion['confirmacion']==1){
        
        }else if($response!='0000'){ // eof if($response=='0000'){

            // AQUI VENIMOS CUANDO OPERACION ES NEGATIVA
            $motivo=$DB->select("motivo","repuestas_redsys","id=".$response);

            $importe_devuelto = $comunicacion_tpv[f][0]['total']/100;

            $motivo_final = $motivo[f][0]['motivo']=="" ? "Motivo Desconocido" : $motivo[f][0]['motivo'];
            // LOS EMAILS DE COMUNICACION DE VENTAS, LOS TENEMOS QUE 
            // ADAPTAR TAL Y COMO VIENEN EN procesarCompraTalonarios.php
            $mail = new phpmailer($comercio['remitente']);  
            $mail->setFrom($comercio['remitente'],$comercio['nombre']);
            $mail->AddAddress('teresaotero@soltury.com');
            $mail->AddCC("pedrogil@soltury.com");
            
            $mail->Subject="Operacion Denegada TPV ".$comercio['nombre']; 
            $mail->Body=body_email(t,"",$comercio['id_delegacion'],"Se ha denegado una operacion de una reserva ".$importe_devuelto." &euro; por el motivo: ".utf8_encode($motivo_final));
            $mail->send();

            script("location.href='https://".$_SERVER['SERVER_NAME']."/zona-privada/".$comercio['identificador']."/reserva_finalizada/".$comunicacion_tpv[f][0]['id']."/redsys'");

        } // }else if($response!='0000'){

    }else{ // eof if($signatureCalculada===$signatureRecibida){

        // aqui venimos cuando la firma del TPV ha sido erronea.
        $mail = new phpmailer($comercio['remitente']); 
        $mail->setFrom($comercio['remitente'],$comercio['nombre']);
        $mail->AddAddress("pedrogil@soltury.com");
        $mail->Subject="Fallo Firma TPV ".$comercio['nombre']; 
        $mail->Body=body_email(t,"",$comercio['id_delegacion'],"Tenemos un problema en el tpv ".$comercio['nombre']);
        $mail->send();

        script("location.href='https://".$_SERVER['SERVER_NAME']."/zona-privada/".$comercio['identificador']."/reserva_finalizada/".$comunicacion_tpv[f][0]['id']."/redsys'");

    }

?>