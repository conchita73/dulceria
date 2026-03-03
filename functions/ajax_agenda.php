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

################ FUNCIONALIDAD DE AGENDA COMPLETA
# isset($accion) && $accion==i
# $tabla_cargar_datos_registros 
# isset($accion) && $accion==u
# $refresacar_tareas
################ FUNCIONALIDAD DE AGENDA COMPLETA
$arr_campos_fechas = array(
                'periodo',
                'entrada',
                'salida',
                'fecha_pago_cliente',
                'fecha',
                'fecha_cobro',
                'fecha_pago',
                'fecha_de_pago',
                'start',
                'fecha_fin');

if(isset($accion) && $accion==i){



        $DB->openTable($tabla_insertar);

        # aqui todas las tablas que vamos a 
        # separar por delegaciones
        $arr_tablas_delegaciones = array(
                    'usuarios',
                    'comercios',
                    'comercios_clientes');

        if(in_array($tabla_insertar,$arr_tablas_delegaciones)){

            $DB->ta['id_delegacion'] = $_SESSION['id_delegacion'];
        }

            
        foreach ($_POST as $key => $value) {

            if($key!="accion" && $key!="tabla_insertar"){

                $buscador=stripos($key,"buscador");

                if($buscador===false){ // usadas xra buscador_
                    // si es un password, lo encriptamos
                    if(in_array($key,$arr_campos_fechas)){

                        // AQUI METEMOS ESPECIALIDADES DE FECHAS.

                        if($key=='periodo'){

                            list($dia,$mes,$year) =  explode("/",$value);
                            $DB->ta['mes']=$mes;
                            $DB->ta['year']=$year;
                        }

                        $value=formatear_fecha($value,i,m);

                        $value .= $tabla_insertar == 'movimientos_bancarios' ? ' '.date('H:i:s') : '';

                        $DB->ta[$key]=$value;

                    }else if(
                                $key!="pvp_personalizado" &&
                                $key!="re_login" && 
                                $key!="noty" && 
                                $key!="notify" && 
                                $key!="contrato_rapido" && 
                                $key!="modulo" && 
                                $key!="solo_cita"
                            ){

                        $DB->ta[$key]=$DB->textCheck(str_replace("€","&euro;",$value));
                         //+++ FIN TODAS LAS DEMAS TABLAS +++///
                    }

                // eof if($buscador===false){
                }
            //eof if($key!="accion" && $key!="tabla_insertar"){
            }
        // eof foreach ($_POST as $key => $value) {
        }

        if(isset($re_login)){

            

        }

        // especialidades de otras tablas aki


        switch ($tabla_insertar) {


            case 'tareas':

                if($repetir == 0){

                    $id_insert = $DB->insert();
                    $txt = 'Registro Insertado';

                }else{ // if($repetir == 0){

                    $txt = 'Eventos insertados';
                    $fecha_fin = formatear_fecha($fecha_fin,i,m);
                    $start = formatear_fecha($start,i,m);

                    if(!isset($id_tarea_recurrente)){

                        $DB2->openTable('tareas_recurrentes');
                        $DB2->ta['title'] = $title;
                        $DB2->ta['start'] = $start;
                        $DB2->ta['hora'] = $hora;
                        $DB2->ta['className'] = $className;
                        $DB2->ta['id_usuario'] = $_SESSION['id_usuario'];
                        $DB2->ta['notas'] = $notas;
                        $DB2->ta['tipo_repeticion'] = $tipo_repeticion;
                        $DB2->ta['frecuencia_repeticion'] = $frecuencia_repeticion;
                        $DB2->ta['finaliza'] = $finaliza;
                        $DB2->ta['fecha_fin'] = $fecha_fin;
                        $DB2->ta['avisar'] = $avisar;
                        $DB2->ta['autocompletar'] = $autocompletar;
                        $id_tarea_rec = $DB2->insert();

                    }else{

                        $id_tarea_rec = $id_tarea_recurrente;

                    }



                    $str = new DateTime($start);
                    if($finaliza == 0){

                        $str->add(new DateInterval('P2Y'));
                        $end = $str->format('Y-m-d');

                    }else{

                        $end = $fecha_fin;

                    }


                    $ne = comparar_fechas($start,$end,m)/$frecuencia_repeticion;


                    if($tipo_repeticion == 'weeks'){

                        $ne = comparar_fechas($start,$end,m)/7;
                                
                    }

                    if($tipo_repeticion == 'month'){

                        $ne = comparar_fechas($start,$end,m)/30;
                    
                    }
                    
                    if($tipo_repeticion == 'year'){

                        $ne = 10;
                    
                    }

                    for ($i=0; $i<=$ne; $i++) {

                                    
                        if($tipo_repeticion == 'month'){

                            list($ys,$ms,$ds) = explode('-',$start);

                            if($ds>28){ 

                                $starte = $ys.'-'.$ms.'-28';

                                $str = date("Y-m-d",strtotime($starte."+ ".$i." ".$tipo_repeticion));

                                list($yr,$mr,$dr) = explode('-',$str);

                                if($mr == 2){

                                    #calculamos años bisisestos
                                    $db = $yr%4 == 0 && $yr%100 != 0 || $yr%400 == 0 ? 29 : 28;
                                    $DB->ta['start'] = $yr.'-'.$mr.'-'.$db;

                                }else if($ds == 31 && ($mr == 4 || $mr == 6 || $mr == 9 || $mr == 11)){

                                    $DB->ta['start'] = $yr.'-'.$mr.'-30';

                                }else{
                                                    
                                    $DB->ta['start'] = $yr.'-'.$mr.'-'.$ds;

                                }

                            }else{ // if($ds>28){

                                $DB->ta['start'] = date("Y-m-d",strtotime($start."+ ".$i." ".$tipo_repeticion));
                            }

                        }else if($tipo_repeticion == 'days'){ // if($tipo_repeticion == 'month'){

                            $DB->ta['start'] = date("Y-m-d",strtotime($start."+ ".$i*$frecuencia_repeticion." ".$tipo_repeticion));

                        
                        }else{ // if($tipo_repeticion == 'days'){

                            $DB->ta['start'] = date("Y-m-d",strtotime($start."+ ".$i." ".$tipo_repeticion));

                        }

                        $DB->ta['id_tarea_recurrente'] = $id_tarea_rec; 
                        $id_insert = $DB->insert();


                    } // for ($i=0; $i<=$ne; $i++) {

                    if($notify!=2){

                        alert('Serie de Tareas insertada. Actualizamos para cargar las tareas');
                        script("location.reload();");

                    }
                    

                }  // if($repetir == 0){   

            break; // movimientos_bancarios


        }  // switch ($tabla_insertar) {


        // AQUI VIENEN TODAS LAS DEMAS INSERCCIONES
        // $id_insert = $DB->insert(); 

        if($id_insert == false){

            alert('Registro No instertado, posiblemente este duplicado');

        }else{

            if(isset($noty) || isset($notify)){

                script('$("#nuevo_id").val('.$id_insert.')');
                notify($txt,'success');

            }else{
                 
                 // if(!isset($_SESSION['ip_admin'])){

                //script("location.reload();");

                 // }  
            }
        }

//eof if(isset($accion) && $accion==i){
}else if(isset($borrar_registro)){ 

    // $tabla_eliminar
    // $id_eliminar

    $DB->delete($tabla_eliminar,$id_eliminar);

    if($tabla_eliminar == 'tareas' && isset($idtr) && $idtr>0 && isset($edicion_masiva) && $edicion_masiva == 'all'){

        $DB->query('delete from tareas_recurrentes where id = '.$idtr);
        $DB->query('delete from tareas where id_tarea_recurrente = '.$idtr);

    }


    if(!isset($reload) || isset($reload) && $reload==1){

        script("location.reload()");

    }

// EOF isset($borrar_registro)
}else if(isset($tabla_cargar_datos_registros)){


                // alert($_SERVER['HTTP_REFERER']);

//////*******TENER EN CUENTA QUE VAMOS A TRAERNOS TODOS LOS 
    // CAMPOS CON EL AÑADIDO _editar 

////// ELIMINAR DE $select ////////////
         // accion
        // tabla_editar
        // id_editar

    unset($_POST['notify']);
    $select=$DB->limpiar_editar($_POST);
    $edit = "_editar";


    $select_registro=$DB->select($select['key'],$tabla_editar," id=".$id_editar,f);
    // editar registros
        //meter $ general de update ($update)
    $clean   = array("\r\n", "\n", "\r","\t","\0","\x0B");

    echo "<script>";
    
        foreach ($select_registro[f][0] as $key => $value) {

            $value=str_replace($clean, '',$value);

            if(in_array($key,$arr_campos_fechas)){

                if($key=='periodo'){

                    list($dia,$mes,$year) =  explode("/",$value);
                    $DB->ta['mes']=$mes;
                    $DB->ta['year']=$year;

                }

                $value=formatear_fecha($value,m,i);

            }

            if($key == 'id_tarea_recurrente' && $value>0){

                echo "
                    $('#div_edicion_masiva').removeClass('d-none');";

            }else if($key == 'id_tarea_recurrente' && $value == 0){

                echo "
                    $('#div_edicion_masiva').addClass('d-none');";

            }

            echo "
                
                var tag = $('[name=\"".$key.$edit."\"]').prop('tagName');


                if(tag=='SELECT'){
        
                    $('#".$key.$edit." option')
                           .each(function(indice,elemento) { 
                            $(elemento).attr('selected',false);
                        });
          
                    $('#".$key.$edit." option[value=\"".trim($value)."\"]').attr('selected',true);

                    
            ";
                
            if($key == 'className'){

                echo "
                    $('#".$key.$edit."').selectpicker('val', '".trim($value)."');
                    ";
            }
                
                 // 
            if($key == 'repetir' && $value == 1){

                if($_SERVER['HTTP_REFERER'] != 'https://pastelerialaargentina.com/new_app/agenda.php'){

                    echo "
                        $('#div_repetir_editar').removeClass('d-none');
                       ";
                }else{

                     echo "
                        $('#".$key.$edit."').next('div').removeClass('d-none');
                        ";
                }

            }else if($key == 'repetir' && $value == 0){

                if($_SERVER['HTTP_REFERER'] != 'https://pastelerialaargentina.com/new_app/agenda.php'){

                    echo "
                        $('#div_repetir_editar').addClass('d-none');
                        $('#div_repetir_editar').next('div').children('[name^=\"fecha_fin\"]').attr('verify' , 0);

                       ";
                }else{

                     echo "
                        $('#".$key.$edit."').next('div').addClass('d-none');
                        $('#".$key.$edit."').next('div').children('[name^=\"fecha_fin\"]').attr('verify' , 0);
                    ";
                }
                

            }

            if($key == 'finaliza' && $value == 1){

                
                if($_SERVER['HTTP_REFERER'] != 'https://pastelerialaargentina.com/new_app/agenda.php'){

                    echo "
                        $('#div_finaliza_editar').removeClass('d-none');
                        $('#div_finaliza_editar').children('[name^=\"fecha_fin\"]').attr('verify' , 1);

                       ";
                }else{

                    echo "$('#".$key.$edit."').next('div').removeClass('d-none');
                    $('#".$key.$edit."').next('div').children('[name^=\"fecha_fin\"]').attr('verify' , 1);
                    ";
                }

            }else if($key == 'finaliza' && $value == 0){

               
                if($_SERVER['HTTP_REFERER'] != 'https://pastelerialaargentina.com/new_app/agenda.php'){

                    echo "
                        $('#div_finaliza_editar').addClass('d-none');
                        $('#div_finaliza_editar').children('[name^=\"fecha_fin\"]').attr('verify' , 0);

                       ";
                }else{

                     echo "$('#".$key.$edit."').next('div').addClass('d-none');
                        $('#".$key.$edit."').next('div').children('[name^=\"fecha_fin\"]').attr('verify' , 0);
                        ";
                }

            }

            echo "
                }else if(tag=='TEXTAREA'){
         
                    $('#".$key.$edit."').html('".str_replace('&euro;','€',utf8_encode(trim($value)))."');
                         
                }else{
         
                    $('#".$key.$edit."').attr('value' , '".str_replace('&euro;','€',utf8_encode(trim($value)))."'); 
                }
            ";


        } // foreach ($select_registro[f][0] as $key => $value) {
        echo "</script>";

// }else if(isset($tabla_cargar_datos_registros)){  
}else if(isset($accion) && $accion==u){


    $datos=$DB->limpiar_editar($_POST,0,'_edit',';'); 

    //array de keys y values
    $set=explode(";",$datos['key']);
    $update=explode(";",$datos['value']);

    $DB->opentable($tabla_editar);
    $DB->edit($id_editar);
    $fin=count($update);

    for($x=0;$x<$fin;$x++) {


        if(in_array($set[$x],$arr_campos_fechas)){

            if($set[$x]=='periodo'){

                list($dia,$mes,$year) =  explode("/",$update[$x]);
                $DB->ta['mes']=$mes;
                $DB->ta['year']=$year;
                    
            }

            $update[$x]=formatear_fecha($update[$x],i,m);
            $DB->ta[$set[$x]]=$update[$x];

        }else if($set[$x]!="sin_reload" && $set[$x]!="sin_reload_zona_privada" && $set[$x]!="" && $set[$x]!="notify" && $set[$x]!="noty" && $set[$x]!="panel_gestionar_tarea"){

            $DB->ta[$set[$x]]=$DB->textCheck(utf8_decode(str_replace('€','&euro;',$update[$x])));

        } // }else if($set[$x]!="sin_reload" && $set[$x]!="sin_reload_zona_privada"){

    } // for($x=0;$x<$fin;$x++) {


    $id_editado = $DB->update();

    if(isset($te) && $te == 'all'){

        ## update a toda la serie;
        $DB2->opentable('tareas_recurrentes');
        $DB2->edit($id_tarea_recurrente_editar);
        $DB2->ta['title'] = $title_editar;
        $DB2->ta['start'] = formatear_fecha($start_editar,i,m);
        $DB2->ta['hora'] = $hora_editar;
        $DB2->ta['className'] = $className_editar;
        $DB2->ta['id_usuario'] = $_SESSION['id_usuario'];
        $DB2->ta['notas'] = $notas_editar;
        $DB2->ta['tipo_repeticion'] = $tipo_repeticion_editar;
        $DB2->ta['frecuencia_repeticion'] = $frecuencia_repeticion_editar;
        $DB2->ta['finaliza'] = $finaliza_editar;
        $DB2->ta['fecha_fin'] = formatear_fecha($fecha_fin_editar,i,m);
        $DB2->ta['avisar'] = $avisar_editar;
        $DB2->ta['autocompletar'] = $autocompletar_editar;
        $DB2->update();

        ## ELIMINAR LA SERIE DE TAREAS Y CREARLAS DE NUEVO
        ## PARA QUE CONSERVEN LA SEPARACION DE LOS DIAS

        $DB2->query('delete from tareas where id_tarea_recurrente = '.$id_tarea_recurrente_editar);

        $url_cURL="https://".$_SERVER['HTTP_HOST']."/new_app/functions/ajax_agenda.php";
        $post_cURL=array(
                    "accion"=>i,
                    "tabla_insertar"=>"tareas",
                    'id_tarea_recurrente' => $id_tarea_recurrente_editar,
                    'title' => $title_editar,
                    'start' => $start_editar,
                    'hora' => $hora_editar,
                    'className' => $className_editar,
                    'id_usuario' => $_SESSION['id_usuario'],
                    'notas' => $notas_editar,
                    'tipo_repeticion' => $tipo_repeticion_editar,
                    'frecuencia_repeticion' => $frecuencia_repeticion_editar,
                    'finaliza' => $finaliza_editar,
                    'repetir' => $repetir_editar,
                    'fecha_fin' => $fecha_fin_editar,
                    'avisar' => $avisar_editar,
                    'autocompletar' => $autocompletar_editar
                            );

        if($_SERVER['HTTP_REFERER'] != 'https://pastelerialaargentina.com/new_app/agenda.php'){
            
            $post_cURL['notify'] = 2;
        }

        // operacion curl.
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_URL, $url_cURL);  
        curl_setopt($ch, CURLOPT_POSTFIELDS,$post_cURL);
        curl_exec($ch);    
        curl_close($ch);



    }


    if($id_editado == -1){

        notify('Error al editar, intentalo de nuevo','danger');

    }else{

        if(isset($sin_reload) || isset($sin_reload_zona_privada)){

            alert('Datos Modificados');

        }else if(isset($noty) || isset($notify)){

            notify('Datos Modificados','success');

        }else{

            if(!isset($_SESSION['ip_admin'])){

               // location(2); 

            }

        }

    }

// }else if(isset($accion) && $accion==u){ badge-menu not_header
}else if(isset($refresacar_tareas)){

        // comprobamos si tenemos mensajes pendientes de leer
    $DB->query('select * from tareas where fecha_realizada = "0000-00-00 00:00:00" and start<="'.date('Y-m-d').'"');


    $sound = 0;
    if($DB->rows()>0){

        $bag = $DB->rows();
        while ($d = $DB->fetch()) {
            
            if($d['visto'] == 0){

                if(strtotime(now()) >= strtotime($d['start'].' '.$d['hora'])){ 

                    $DB2->query('update tareas set visto = 1 where id='.$d['id']);

                    if($sound == 0){
                        $sound ++;

                        script('$("#chatAudio")[0].play();');
                    }

                }else{

                    $bag--;
                }

            } // if($d['visto'] == 0){

            if(strtotime(now()) >= strtotime($d['start'].' '.$d['hora'])){ 

                $arr_pen_envent[] = array(
                    'order'=>strtotime($d['start'].' '.$d['hora']),
                    'id'=>$d['id'],
                    'title'=>$d['title'], 
                    'start'=>$d['start'], 
                    'hora'=>$d['hora'], 
                    'className'=>$d['className'],
                    'notas'=>$d['notas'],
                    'idtr'=>$d['id_tarea_recurrente']
                );

            }else if($d['start'] == date('Y-m-d')){ // if(strtotime(now()) >= strtotime($d['st ....

                $arr_next_event[] = array(
                    'order'=>strtotime($d['start'].' '.$d['hora']),
                    'id'=>$d['id'],
                    'title'=>$d['title'], 
                    'start'=>$d['start'], 
                    'hora'=>$d['hora'], 
                    'className'=>$d['className'],
                    'notas'=>$d['notas'],
                    'idtr'=>$d['id_tarea_recurrente']
                );

            } // }else if($d['start'] == date('Y-m-d')){

            ## actualizamos el avisador de arriba
            script('
                    $("#not_header").removeClass("d-none");
                    $("#not_header").html("'.$bag.'");
                    $("#badge-menu").removeClass("d-none");
                    $("#badge-menu").html("'.$bag.'");
                ');

        } // while ($d = $DB->fetch()) {

        ## actualizamos el listado de las tareas

        $events = '';                                              
        if(isset($arr_pen_envent) && count($arr_pen_envent)>0){

            arsort($arr_pen_envent);
            $ev = 0;
            foreach ($arr_pen_envent as $key => $value) {

                if(strpos($value['className'],'-solid') !== false){
                                                            
                    list($f,$bge[$ev],$s) = explode('-',$value['className']);
                    $bge[$ev] ='bg-'.$bge[$ev].' text-white'; 

                }else{

                    $bge[$ev] = '';
                    list($f,$bgd[$ev]) = explode('fc-',$value['className']);
                                                        
                }
                    $events.='<div class="user mb-2 '.$bge[$ev] .'">
                                <button type="button" class="btn btn-danger btn-sm float-right gestionar_evento" id_evento="'.$value['id'].'" data-toggle="tooltip" data-placement="top" title="Doble Click para borrar"><i class="fas fa-thumbs-up"></i></button>

                                <a href="#" title="'.utf8_encode($value['title']).'" id_evento="'.$value['id'].'" hora="'.$value['hora'].'" idtr="'.$value['idtr'].'">
                                    <div class="user-data">
                                        <span class="name">';

                                        if(isset($bgd[$ev])){
                                                                                        
                                            $events.= ' <div class="div_colores_c bg-'.$bgd[$ev].'" ></div> ';

                                        }
                                                        
                                $events.= utf8_encode($value['title']).'</span>
                                        <span class="message">
                                            '.formatear_fecha($value['start'],m,i).' '.$value['hora'].'
                                        </span>
                                    </div>
                                </a>
                            </div>';
                    $ev ++;
            } // foreach ($arr_pen_envent as $key => $value) {

        }else{

            $events='No tienes eventos pendientes';

        }  // if(count($arr_pen_envent)>0){

        $events_p = '';                                              
        if(isset($arr_next_event) && count($arr_next_event)>0){

            arsort($arr_next_event);
            $ev = 0;
            foreach ($arr_next_event as $key => $value) {

                if(strpos($value['className'],'-solid') !== false){
                                                            
                    list($f,$bgen[$ev],$s) = explode('-',$value['className']);
                    $bgen[$ev] ='bg-'.$bgen[$ev].' text-white'; 

                }else{

                    $bgen[$ev] = '';
                    list($f,$bgdn[$ev]) = explode('fc-',$value['className']);
                                                        
                }
                    $events_p.='<div class="user mb-2 '.$bgen[$ev] .'">
                                <button type="button" class="btn btn-danger btn-sm float-right gestionar_evento" id_evento="'.$value['id'].'" data-toggle="tooltip" data-placement="top" title="Doble Click para borrar"><i class="fas fa-thumbs-up"></i></button>

                                <a href="#" title="'.utf8_encode($value['title']).'" id_evento="'.$value['id'].'" hora="'.$value['hora'].'" idtr="'.$value['idtr'].'">
                                    <div class="user-data">
                                        <span class="name">';

                                        if(isset($bgdn[$ev])){
                                                                                        
                                            $events_p.= ' <div class="div_colores_c bg-'.$bgdn[$ev].'" ></div> ';

                                        }
                                                        
                                $events_p.= utf8_encode($value['title']).'</span>
                                        <span class="message">
                                            '.formatear_fecha($value['start'],m,i).' '.$value['hora'].'
                                        </span>
                                    </div>
                                </a>
                            </div>';
                    $ev ++;
            } // foreach ($arr_next_event as $key => $value) {

        }else{

            $events_p='No tienes próximos eventos';

        } // if(count($arr_next_event)>0){

        
        $events=str_replace($clean, '',$events);
        $events=str_replace('"', "'", $events);
        $events_p=str_replace($clean, '',$events_p);
        $events_p=str_replace('"', "'", $events_p);

        script('
            $("#events_activos").html("'.$events.'");
            $("#events_next").html("'.$events_p.'");
            ');


    }else{

        script('
                $("#events_activos").html("No tienes eventos pendientes");
                $("#events_next").html("No tienes próximos eventos");
                $("#not_header").addClass("d-none");
                $("#badge-menu").addClass("d-none");
            ');

    }
// }else if(isset($refresacar_tareas)){
}

?>