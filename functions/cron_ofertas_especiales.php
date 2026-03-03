<?php header("Access-Control-Allow-Origin: *");
@session_start();
if($_SERVER['REMOTE_ADDR']=="5.154.69.122" || $_SERVER['REMOTE_ADDR']=="185.178.136.16"){

    $_SESSION['ip_admin']=1;
    error_reporting(E_ALL);

}else{
    
    error_reporting(0);
}

    error_reporting(E_ALL);


date_default_timezone_set('Europe/Madrid');
ini_set('memory_limit', '9999M');
ini_set('max_execution_time', '120');
set_time_limit(120);
include 'funciones.php';
include 'phpMailer/PHPMailer.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

### insertamos api whatsapp
require_once 'twilio/vendor/autoload.php'; 
use Twilio\Rest\Client; 
//$sid    = "***"; 
$token  = "d0133ca5069721bd00f018b71846ecba"; 
$twilio = new Client($sid, $token); 

# Generamos obj de email
$DB->query('select 
nombre,
email,
email2,
color_email,
web
from delegaciones where id='.$_SESSION['id_web']);

$datos=$DB->fetch();

// echo $datos['email']. ' ' . $datos['nombre'];
$mail = new phpmailer($datos['email']);
$mail->setFrom($datos['email'],'Pastelería La Argentina');
$mail->Subject = 'Pastelería la Argentina'; 


## fechas Especiales:

# fecha_nacimiento
# fecha_santo
# fecha_aniversario

$arr = array(
            'fecha_nacimiento'=>'Cumpleaños',
            'fecha_santo'=>'Santo',
            'fecha_aniversario'=>'Aniversario',
            // 'fecha_personalizada'=>'query'
            );


$mes = date('m');
$dia = date('d');
$year = date('Y');

$emails = 0;
$whatsapp = 0;
$cw = $DB->select('costo','costo_whatsapp','id = 1');
$cw = $cw[f][0]['costo'];

$gasto = $DB->select('sum(costo_whatsapp) as gasto','control_envios','fecha between "'.$year.'-'.$mes.'-01" and "'.$year.'-'.$mes.'-31"');
$gasto = $gasto[f][0]['gasto'];

$presupuesto = $DB->select('presupuesto','costo_whatsapp','id = 1');
$presupuesto = $presupuesto[f][0]['presupuesto'];

foreach ($arr as $k =>$v) {
    
    # seleccionamos a todos los clientes que coincidan con las fechas
    $DB->query('select * from clientes where '.$k.' like "%'.$mes.'-'.$dia.'%"');
    if($DB->rows() > 0){

        # Sacamos los datos de la oferta especial
        $dc = $DB2->select('*','ofertas_especiales','fecha like "'.$k.'"');
        $dc = $dc[f][0];
        $fecha_caducidad = suma_dias(date('Y-m-d'),$dc['caducidad'],m,m);

        $DB2->openTable('cupones_descuento');
        while ($d = $DB->fetch()) {

            #insertamos los cupones
            $DB2->ta['tipo'] = $k;
            $DB2->ta['id_cliente'] = $d['id'];
            $DB2->ta['fecha_caducidad'] = $fecha_caducidad;
            $id_cupon = $DB2->insert();

            list($nombre, $apellidos) = explode(' ',$d['nombre']);
            $felicitacion = $v == 'query' ? 'personalizada' : $v;
            # enviamos el email
            $mail->AddAddress($d['email']);
            $texto_email='Hola '.ucwords(strtolower(utf8_encode($nombre))).',<br><br>';
                
            $texto_email.='Desde Pastelerías la Argentina queremos felicitarte por tu '.$felicitacion.' y por ello te hacemos entrega de un cupón de descuento. Esperamos que lo disfrutes, s&oacute;lo tiene que pulsar el siguiente bot&oacute;n 
                    <br><br>
                    <div style="width:100%; text-align: center;
                            border-bottom:#CCC 1px dotted; padding:10px 0 10px 0">
                    <a href="https://pastelerialaargentina.com/descargar_cupon/'.$id_cupon.'"
                            style="font-family: Playfair Display,serif; border-radius: 1px; font-weight: 600;border-width: 0;
                            padding: 8px 25px; transition: color .5s , border-color .5s , background-color .5s; font-size: 16px;
                            padding: 10px 35px; color: #fff; background-color: #'.$datos['color_email'].';
                            text-decoration:none;">
                            Pulse aqu&iacute;
                            </a>
                            <br><br>
                        </div>';


            $mail->Body=body_email(t,'',$_SESSION['id_web'],$texto_email);
            $mail->send();

            $emails ++;

            if($gasto < $presupuesto){

                # enviamos el was
                $message = $twilio->messages 
                          ->create("whatsapp:+34681660826", // to 
                                   array( 
                                       "from" => "whatsapp:+14155238886",       
                                       "body" => 'Hola '.utf8_decode($nombre).', Desde Pastelerías la Argentina queremos felicitarte por tu '.$felicitacion.' y por ello te hacemos entrega de un cupón de descuento. Esperamos que lo disfrutes https://pastelerialaargentina.com/descargar_cupon/'.$id_cupon.'',
                                   ) 
                          ); 
         
                // print($message->sid);
                $whatsapp ++;
                $gasto += $cw;

            } // if($gasto < $presupuesto){

        } // while ($d = $DB->fetch()) {

    } // if($DB->rows() > 0){

} // foreach ($arr as $k) {

### fechas personalizadas:
$DB->query('select f.*,c.nombre as cliente from fechas_especiales f inner join clientes c on f.id_cliente = c.id where f.mes = "'.$mes.'" and f.dia = "'.$dia.'"');

if($DB->rows() > 0){

    $DB2->openTable('cupones_descuento');
    $fecha_caducidad = suma_dias(date('Y-m-d'),5,m,m);
    while ($d = $DB->fetch()) {

        #insertamos los cupones
        $DB2->ta['tipo'] = 'personalizada';
        $DB2->ta['id_cliente'] = $d['id_cliente'];
        $DB2->ta['id_fechas_especiales'] = $d['id'];
        $DB2->ta['fecha_caducidad'] = $fecha_caducidad;
        $id_cupon = $DB2->insert();

        list($name,$ap) = explode(' ',$d['cliente']);
        # enviamos el email
        $mail->AddAddress($d['email']);
        $texto_email='Hola '.ucwords(strtolower(utf8_encode($d['nombre']))).',<br><br>';
                
        $texto_email.='Desde Pastelerías la Argentina queremos felicitarte de parte de tu '.utf8_encode($d['parentesco']).' '.utf8_encode($name).' por tu <b>'.utf8_encode($d['mensaje_dia']).'</b> y por ello te hacemos entrega de un cupón de descuento. <br><br>';

        if($d['nota'] != ''){

            $texto_email.='<b>Nota especial para tí:</b><br>'.utf8_encode($d['nota']).'<br><br>';

        }

        if($d['foto'] != ''){

            $texto_email.='<b>Foto especial para tí:</b><br><img src="https://pastelerialaargentina.com/new_app/img/emails/'.($d['foto']).'" alt=""><br>';

        }

        $texto_email.='
                    Esperamos que lo disfrutes, s&oacute;lo tiene que pulsar el siguiente bot&oacute;n 
                    <br><br>
                    <div style="width:100%; text-align: center;
                            border-bottom:#CCC 1px dotted; padding:10px 0 10px 0">
                            <a href="https://pastelerialaargentina.com/descargar_cupon/'.$id_cupon.'"
                            style="font-family: Playfair Display,serif; border-radius: 1px; font-weight: 600;border-width: 0;
                            padding: 8px 25px; transition: color .5s , border-color .5s , background-color .5s; font-size: 16px;
                            padding: 10px 35px; color: #fff; background-color: #'.$datos['color_email'].';
                            text-decoration:none;">
                            Pulse aqu&iacute;
                            </a>
                            <br><br>
                        </div>';

        $mail->Body=body_email(t,'',$_SESSION['id_web'],$texto_email);
        $mail->send();


        $emails ++;

        # enviamos el was
        $whatsapp ++;
        if($gasto < $presupuesto){

            # enviamos el was
            $message = $twilio->messages 
                          ->create("whatsapp:+34681660826", // to 
                                   array( 
                                       "from" => "whatsapp:+14155238886",       
                                       "body" => 'Hola '.utf8_encode($d['nombre']).', Desde Pastelerías la Argentina queremos felicitarte de parte de tu '.utf8_encode($d['parentesco']).' '.utf8_encode($name).' por tu '.utf8_encode($d['mensaje_dia']).' y por ello te hacemos entrega de un cupón de descuento. Esperamos que lo disfrutes https://pastelerialaargentina.com/descargar_cupon/'.$id_cupon.'',
                                   ) 
                          ); 
         
            // print($message->sid);
            $whatsapp ++;
            $gasto += $cw;

        } // if($gasto < $presupuesto){

    } // while ($d = $DB->fetch()) {

} // if($DB->rows() > 0){

# cogemos las ofertas que tengamos para día de hoy:
$DB->query('select * from ofertas_puntuales where fecha = "'.date('Y-m-d').'"');
echo 'tenemos '.$DB->rows();
if($DB->rows() > 0){

    $DB2->openTable('cupones_descuento');
    while ($d = $DB->fetch()) {

        $fecha_caducidad = suma_dias(date('Y-m-d'),$d['caducidad'],m,m);
        # primero emails 
        // $Q->query('select nombre, email from clientes where email like "%@%"');
        $Q->query('select id,nombre, email from clientes where email like "%desarrollo@ckcreativos.com%"');
        while($d2 = $Q->fetch()){

             #insertamos los cupones
            $DB2->ta['tipo'] = 'puntual';
            $DB2->ta['id_cliente'] = $d2['id'];
            $DB2->ta['id_ofertas_puntuales'] = $d['id'];
            $DB2->ta['fecha_caducidad'] = $fecha_caducidad;
            $id_cupon = $DB2->insert();

            # lanzamos el email
            list($nombre, $apellidos) = explode(' ',$d2['nombre']);

            $mail->AddAddress($d2['email']);
            $texto_email='Hola '.ucwords(strtolower(utf8_encode($nombre))).',<br><br>';
                
            $texto_email.='Desde Pastelerías la Argentina queremos felicitarte por '.utf8_encode($d['motivo']).' y por ello te hacemos entrega de un cupón de descuento. Esperamos que lo disfrutes, s&oacute;lo tiene que pulsar el siguiente bot&oacute;n 
                    <br><br>
                    <div style="width:100%; text-align: center;
                            border-bottom:#CCC 1px dotted; padding:10px 0 10px 0">
                    <a href="https://pastelerialaargentina.com/descargar_cupon/'.$id_cupon.'"
                            style="font-family: Playfair Display,serif; border-radius: 1px; font-weight: 600;border-width: 0;
                            padding: 8px 25px; transition: color .5s , border-color .5s , background-color .5s; font-size: 16px;
                            padding: 10px 35px; color: #fff; background-color: #'.$datos['color_email'].';
                            text-decoration:none;">
                            Pulse aqu&iacute;
                            </a>
                            <br><br>
                        </div>';


            $mail->Body=body_email(t,'',$_SESSION['id_web'],$texto_email);
            $mail->send();
            $emails ++;

        } // while($d2 = $Q->fetch()){


        // $Q->query('select id, nombre, telefono from clientes where telefono like "6%" or telefono like "7%"');
        $Q->query('select id,nombre, email from clientes where email like "%desarrollo@ckcreativos.com%"');
        while($d2 = $Q->fetch()){

             #insertamos los cupones
            $DB2->ta['tipo'] = 'puntual';
            $DB2->ta['id_cliente'] = $d2['id'];
            $DB2->ta['id_ofertas_puntuales'] = $d['id'];
            $DB2->ta['fecha_caducidad'] = $fecha_caducidad;
            $id_cupon = $DB2->insert();

            # lanzamos el email
            list($nombre, $apellidos) = explode(' ',$d2['nombre']);

            if($gasto < $presupuesto){

                # enviamos el was
                $message = $twilio->messages 
                          ->create("whatsapp:+34681660826", // to 
                                   array( 
                                       "from" => "whatsapp:+14155238886",       
                                       "body" => 'Hola '.utf8_decode($nombre).', Desde Pastelerías la Argentina queremos felicitarte por '.utf8_encode($d['motivo']).' y por ello te hacemos entrega de un cupón de descuento. Esperamos que lo disfrutes https://pastelerialaargentina.com/descargar_cupon/'.$id_cupon.'',
                                   ) 
                          ); 
         
                // print($message->sid);
                $whatsapp ++;
                $gasto += $cw;

            } // if($gasto < $presupuesto){

        } // while($d2 = $Q->fetch()){

        # luego whatsapp

    } // while ($d = $DB->fetch()) {

} // if($DB->rows() > 0){

# insertamos el control de los envios:
$costo = $whatsapp*$cw;
$DB->query('insert into control_envios (fecha,whatsapp,costo_whatsapp,emails) values ("'.date('Y-m-d').'",'.$whatsapp.',"'.$costo.'",'.$emails.')');

?>