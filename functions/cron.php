<?php header("Access-Control-Allow-Origin: *");
@session_start();
if($_SERVER['REMOTE_ADDR']=="5.154.69.122" || $_SERVER['REMOTE_ADDR']=="185.178.136.16"){

    $_SESSION['ip_admin']=1;
    error_reporting(E_ALL);

}else{
    
    error_reporting(0);
}


date_default_timezone_set('Europe/Madrid');
ini_set('memory_limit', '9999M');
ini_set('max_execution_time', '120');
set_time_limit(120);
include 'funciones.php';
include 'phpMailer/PHPMailer.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

# ACTUALIZADOR DE CONTADOR DE FACTURAS
# ACTUALIZADOR DE PEDIDOS DE CLIENTES NO HABITUALES A ENTREGADOS 
# AVISADOR POR EMAIL DE LAS TAREA QUE ESTEN POR GESTIONAR
# RENOVAMOS LOS EVENTOS QUE NO TIENEN FIN QUE TENGAN MAS DE 2 AÑOS

# ACTUALIZADOR DE CONTADOR DE FACTURAS
if(date('m') == 1 && date('d') == 1){

    $DB->query('update contadores set contador = 0');

}

# ACTUALIZADOR DE PEDIDOS DE CLIENTES NO HABITUALES A ENTREGADOS 
if(date('H:s') == '06:30'){

        $DB->query('select p.id from pedidos p inner join clientes c on c.id = p.id_cliente where c.es_recurrente=0 and fecha_entrega < "'.date('Y-m-d').'" and p.estado=0');
        while($d = $DB->fetch()){
            
            // echo 
            $DB2->query('update pedidos set pedido_cobrado=1, estado=1 where id ='.$d['id']);
            $DB2->query('update facturas set estado=1, fecha_cobrada="'.now().'" where id_pedido='.$d['id']);
        }
}

// enviado

$DB->query('select * from tareas where fecha_realizada = "0000-00-00 00:00:00" and start<="'.date('Y-m-d').'" and avisar=1 and enviado = 0');

if($DB->rows()>0){

    $DB2->query('select 
        nombre,
        email,
        email2,
        color_email,
        web
        from delegaciones where id=1');

    $datos=$DB2->fetch();

    while ($d = $DB->fetch()) {
                                                            
        if(strtotime(now()) >= strtotime($d['start'].' '.$d['hora'])){ 

            # enviamos email
            $mail[$d['id']] = new phpmailer($datos['email']);
            $mail[$d['id']]->setFrom($datos['email'],'Pastelería La Argentina');
            $mail[$d['id']]->Subject='Evento : '.utf8_encode($d['title']); 
            // $mail[$d['id']]->AddAddress('desarrollo@ckcreativos.com');
            $mail[$d['id']]->AddAddress('lulomon26@gmail.com');

            $texto_email[$d['id']]='Hola Luis,<br><br>Te adjuntamos toda la información del evento que tienes pendiente por realizar:
                        <br>';

            $texto_email[$d['id']].='<br>Título : '.utf8_encode($d['title']);
            $texto_email[$d['id']].='<br>Hora : '.$d['hora'];
            $texto_email[$d['id']].='<br>Notas : '.utf8_encode($d['notas']);

            $texto_email[$d['id']].='
                        <div style="width:100%; text-align: center;
                                border-bottom:#CCC 1px dotted; padding:10px 0 10px 0">
                                <br><br>
                                Esperamos que tengas un buen d&iacute;a ;)
                        </div>';

            $mail[$d['id']]->Body=body_email(t,'',1,$texto_email[$d['id']]);
            $mail[$d['id']]->send();

            # realizamos las modificaciones al evento:
            $q = $d['autocompletar'] == 1 ? ',fecha_realizada="'.now().'",visto=1' : '';
            $DB2->query('update tareas set enviado = 1'.$q.' where id = '.$d['id']);
        } // if(strtotime(now()) >= strtotime($d['start'].

    } // while ($d = $DB->fetch()) {

} // if($DB->rows()>0){

// $str = new DateTime($start);
// if($finaliza == 0){

//     $str->add(new DateInterval('P2Y'));
//     $end = $str->format('Y-m-d');
$fc =  date("Y-m-d",strtotime(date('Y-m-d')."- 2 year"));

$DB->query('select * from tareas_recurrentes where start="'.$fc.'" and finaliza = 0');

if($DB->rows()>0){

    while ($d = $DB->fetch()) {

        $it = $DB2->select('id','tareas',' id = (select min(id) from tareas where id_tarea_recurrente = '.$d['id'].')');
        # tengo que eliminar el evento que pertenece justo a este día de esta serie, por lo que se queda como el menor id, el siguiente evento
        $DB2->query('delete from tareas where id = '.$it[f][0]['id']);

        # tengo que generar un evento con siguiente repetición de esa serie por el final.
        $fe = $DB2->select('start','tareas','id = (select min(id) from tareas where id_tarea_recurrente = '.$d['id'].')');

        $DB2->query('update tareas_recurrentes set start = "'.$fe[f][0]['start'].'" where id = '.$d['id']);

        $str = date("Y-m-d",strtotime($fe[f][0]['start']."+ 4 year"));
        # tengo que coger la fecha de incio de ese evento y modificarsela a la seríe.

        list($yr,$mr,$dr) = explode('-',$str);

        if($dr <= 28){

            $insertar = 1;

        }else{

            # primero vamos a conocer el dia en el que debe de tocar

            if($mr == 2){

                #calculamos años bisisestos
                $df = $yr%4 == 0 && $yr%100 != 0 || $yr%400 == 0 ? 29 : 28;

            }else if($dr == 31 && ($mr == 4 || $mr == 6 || $mr == 9 || $mr == 11)){

                $df = 30;

            }else{
                                                    
                $df = $dr;

            }

            $str = $yr.'-'.$mr.'-'.$df;


            # tengo que comprobar que no haya ningun evento de esta serie en el día que toca
            $comp = $DB2->select('id','tareas',' start = "'.$str.'" and id_tarea_recurrente = '.$d['id']);

            if($comp[r] == 0){

                $insertar=1;

            }

        }

        if(isset($insertar) && $insertar == 1){

            $DB2->openTable('tareas');
            $DB2->ta['id_tarea_recurrente'] = $d['id'];
            $DB2->ta['repetir'] = 1;
            foreach ($d as $k => $v) {
                
                ## aqui hay que desgranar la fecha y decidir si hay que meterlo o no.
                $v = $k != 'start' ? $v : $str;
                if($k != 'id'){

                    $DB2->ta[$k] = utf8_encode($v);

                }
            }
            $DB2->insert();
            
        }else{
            echo 'no insert';
        }

        

    } // while ($d = $DB->fetch()) {

} // if($DB->rows()>0){



// if(!isset($_SESSION['ip_admin'])){
        # enviamos al cliente:

    $DB2->query('select 
        nombre,
        email,
        email2,
        color_email,
        web
        from delegaciones where id=1');

        $datos=$DB2->fetch();


        $mail = new phpmailer($datos['email']);
        $mail->setFrom($datos['email'],'Pastelería La Argentina');
        $mail->Subject='Pedidos Diarios'; 


        // $mail->AddAddress('desarrollo@ckcreativos.com');

        $mail->AddAddress('pastelerialaargentinazafra@gmail.com');
        $mail->addCC('lulomon26@hotmail.com');
        $mail->addCC('laargentinazafra@yahoo.es');
        // $mail->addCC('desarrollo@ckcreativos.com');

        

        $texto_email='Buenos D&iacute;as Luis,<br><br>';
                
        $texto_email.='Te adjuntamos el enlace para que puedas descargar todos los pedidos del d&iacute;a de hoy. 
                    <br><br>
                    <div style="width:100%; text-align: center;
                            border-bottom:#CCC 1px dotted; padding:10px 0 10px 0">
                    <a href="https:'.$datos['web'].'/dulceria/functions/descargar_pedidos.php?fecha='.date('Y-m-d').'"
                            style="font-family: Playfair Display,serif; border-radius: 1px; font-weight: 600;border-width: 0;
                            padding: 8px 25px; transition: color .5s , border-color .5s , background-color .5s; font-size: 16px;
                            padding: 10px 35px; color: #fff; background-color: #'.$datos['color_email'].';
                            text-decoration:none;">
                            Pulsa aqu&iacute;
                            </a>
                            <br><br>
                            Esperamos que tengas un buen d&iacute;a ;)
                        </div>';


        $mail->Body=body_email(t,'',1,$texto_email);

        $mail->send();

// }








?>