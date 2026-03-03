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


# formulario_contacto_modal => formularios de envio de emails desde la web
# acepto_cookies
# logear_usuario_inicio
# logear_empresas
# log_out_admin 
# borrar_registro
# }else if(isset($accion) && $accion==i && !isset($formularios)){
# }else if(isset($tabla_cargar_datos_registros)){ 
# }else if(isset($accion) && $accion==u && !isset($formularios)){
# tabla_cargar_datos_registros
# formularios
# json_campos_formulario
# buscar_tables_ajax -> buscamos por muchos campos y montamos t_body 
# buscar_comercio_ajax 
# buscar_sector_ajax
# buscar_cliente_autocomplete
# canjear_cupon 
# buscar_cupon
# importar_cvs 
# copiar_datos_comercio
# registrar_acciones -> resgitramos las acciones de los usuarios
# pagar_factura -> paga factura y la enviar por email
# cobrado_masivo -> cobrado masivo desde el modulo de clientes
# password_actual -> comprobar password zona empresas
# edita_password -> editar el password de los comercios 
# filtro_year_contabilidad -> filtramos la contabilidad por años 
# quitar_pago -> quitar pago albaranes facturas

############ ----------------
# concepto_personalizado -> metemos los conceptos de las facturas
# modificar_linea_productos -> modificamos la línea de los productos
# precio_final_tarta -> sumar tarta al carrito de pedidos
# conceptos_personalizado -> sumar conceptos al carrito de pedidos
# importe_final_editar -> editar los pedidos 
# pedido_cobrado -> finalizar el pedido

######
# caracteristica_sum -> sumamos caracteristicas en el nuevo formulario de productos
# refresh_panel -> decidimos si vamos o no a refrescar el de los pedidos automáticamente.
#
#

######
# suma_producto_modal -> sacamos los datos del producto en la ventana modal de la web
# subir_imagen_blog -> subimos la imágen principal de un post
# aprobar_comentario -> aprobamos el comentario y enviamos email agradecimiento al cliente
#


if(isset($formulario_contacto_modal) || isset($formulario_encargo_modal)){


    $mail = new phpmailer('web@pastelerialaargentina.com');
    $mail->setFrom('web@pastelerialaargentina.com','La Argentina');
    $mail->AddAddress(strtolower($email_contacto));

    $texto_email='Hola '.ucwords($nombre_contacto).',
    <br><br>
    Hemos recibido correctamente tu contacto y te contestaremos en un m&aacute;ximo de 24h laborables.<br><br> 
    Estos son los datos de tu pedido:';
    foreach ($_POST as $key => $value) {
        
        if(strpos($key,'_contacto') !== false){

            if(($key == 'horario_contacto' || $key == 'fecha_contacto') && $key['fecha_contacto'] != ''){

                $texto_email.= '<br>'.str_replace('_contacto',' ',ucwords($key)).' '.$value;

            }else{

                $texto_email.= '<br>'.str_replace('_',' ',ucwords($key)).' '.$value;

            }
        }
    }


    $eraser = $asunto_contacto == 'Encargar Tarta de Gominolas' ? '_gominolas' : ($asunto_contacto == 'Encargar Tarta de Personalizada' ? '_personalizada' : ($asunto_contacto == 'Encargar Tarta de Galletas' ? '_galletas' : ($asunto_contacto == 'Encargar El pastelón' ? '_pastelon' : '')));

    ## aqui tengo que quitar los que no pertenzcna, 
    if($eraser != ''){

        foreach ($_POST as $key => $value) {
        
            if(strpos($key,$eraser) !== false){

                if(strpos($key,'tamano') !== false){

                    $texto_email.= '<br>Precio Final '.$value.'&euro;';

                }else{

                    $texto_email.= '<br>'.str_replace('_',' ',ucwords($key)).' '.$value;
                }
            }
        }

    } // if($eraser == ''){



    $texto_email.='<br><br>
    Gracias por confiar en nosotros, un saludo.
    <br><br>
    Pasteler&iacute;as La Argentina <br>
    Desde 1928';

    // SALUDO Y BODY EMAIL, SEGUN TABLA
    $contacto = isset($formulario_contacto_modal) ? 'Contacto' : 'Encargo';
    $mail->Subject= $contacto.' web Recibido (La Argentina)'; 
    $mail->Body=$texto_email;
    $mail->send();


    $mail2 = new phpmailer('web@pastelerialaargentina.com');
    $mail2->setFrom('web@pastelerialaargentina.com','La Argentina');
    $mail2->AddAddress('pastelerialaargentinazafra@gmail.com');

    // $mail2->AddAddress('pedrogil@soltury.com');

    $mail2->AddReplyTo(strtolower($email_contacto));
    $mail2->Subject=$asunto_contacto; 

    // $hora_contact = isset($fecha_contacto) ? 'Fecha y Hora de Entrega : '.$fecha_contacto : '';
    $email_administracion= 'Contacto recibido via web 
    <br><br>
    <u>Datos de Contacto</u>';

    foreach ($_POST as $key => $value) {
        
        if(strpos($key,'_contacto') !== false){

            if(($key == 'horario_contacto' || $key == 'fecha_contacto') && $key['fecha_contacto'] != ''){

                $email_administracion.= '<br>'.str_replace('_contacto',' ',ucwords($key)).' '.$value;

            }else{

                $email_administracion.= '<br>'.str_replace('_',' ',ucwords($key)).' '.$value;

            }
        }
    }


    $eraser = $asunto_contacto == 'Encargar Tarta de Gominolas' ? '_gominolas' : ($asunto_contacto == 'Encargar Tarta de Personalizada' ? '_personalizada' : ($asunto_contacto == 'Encargar Tarta de Galletas' ? '_galletas' : ($asunto_contacto == 'Encargar El pastelón' ? '_pastelon' : '')));

    ## aqui tengo que quitar los que no pertenzcna, 
    if($eraser != ''){

        foreach ($_POST as $key => $value) {
        
            if(strpos($key,$eraser) !== false){

                if(strpos($key,'tamano') !== false){

                    $email_administracion.= '<br>Precio Final '.$value.'&euro;';

                }else{

                    $email_administracion.= '<br>'.str_replace('_',' ',ucwords($key)).' '.$value;
                }
            }
        }

    } // if($eraser == ''){



        $email_administracion.= '<br><br>
        Gracias por confiar en nosotros, un saludo.
        <br><br>
        Pasteler&iacute;as La Argentina <br>
        Desde 1928';
        $mail2->Body=$email_administracion;
        $mail2->send();
    
 
    // $modal = isset($fecha_contacto) ? 'modal_encargos' : 'modal_contacto';
   // script("$('#".$modal."').modal('hide');");  
        alert('Mensaje Enviado, Gracias por confiar en nosotros');
        script('location.href="https://pastelerialaargentina.com"');

                
//  
// }else if(isset($formulario_contacto_modal)){
}else if(isset($acepto_cookies)){


    setcookie("aceptada", 1, time()+864000*365,"/");
    $_COOKIE['aceptada']=1;

    if($cookie_marketing==1){

        setcookie("cookie_marketing", 1, time()+864000*365,"/");
        $_COOKIE['cookie_marketing']=1;

    }

    if($cookie_estadistica==1){

        setcookie("cookie_estadistica", 1, time()+864000*365,"/");
        $_COOKIE['cookie_estadistica']=1;

    }
    
        script('$(".fixed_cookies").fadeOut(2000);
        $.notify({title: "",message: "Cookies Modificadas"},{type: "success",allow_dismiss: true,newest_on_top: true});');


// }else if(isset($acepto_cookies)){
}else if(isset($logear_usuario_inicio)){


    $DB->query('select id,nombre,password,id_nivel,id_delegacion,estado,arr_permisos from usuarios where login like "'.$usr.'"');


        if($DB->rows()==0){

            notify("El usuario no existe","warning");

        }else if($DB->rows()==1){

            $datos_login_inicio=$DB->fetch();

                if($datos_login_inicio['estado']==0){

                    notify('Tu usuario esta desactivado',"warning");

                }else if(!password_verify($pwd,$datos_login_inicio['password'])){

                    notify('la contraseña es incorrecta',"warning");

                }else if(password_verify($pwd,$datos_login_inicio['password'])){

                    $_SESSION['Authenticated']=$datos_login_inicio['id_nivel'];  
                    $_SESSION['nombre_usuario']=$datos_login_inicio['nombre'];
                    $_SESSION['id_usuario']=$datos_login_inicio['id'];
                    $_SESSION['id_delegacion']=$_SESSION['id_web']=$datos_login_inicio['id_delegacion'];
                    $_SESSION['arr_permisos'] = json_decode($datos_login_inicio['arr_permisos'],true);

                    script("location.href='/new_app/panel.php'");


                }


        }
// isset($logear_usuario_inicio) 
}else if(isset($logear_empresas)){

    $DB->query('select id,usuario,nombre_comercial,password,id_delegacion from comercios where usuario like "'.$usr.'"');


    if($DB->rows()==0){

        notify("El usuario no existe","warning");

    }else if($DB->rows()==1){

        $datos_login_inicio=$DB->fetch();

        if(!password_verify($pwd,$datos_login_inicio['password'])){

            notify('la contraseña es incorrecta',"warning");

        }else if(password_verify($pwd,$datos_login_inicio['password'])){

            $_SESSION['nombre_comercial']=$datos_login_inicio['nombre'];
            $_SESSION['id_comercio']=$datos_login_inicio['id'];
            $_SESSION['id_delegacion']=$_SESSION['id_web']=$datos_login_inicio['id_delegacion'];

            script("location.href='/new_app/area_clientes.php'");

        }

    }


//}else if(isset($log_out_admin)){
}else if(isset($log_out_admin)){

    if(isset($_SESSION['id_comercio'])){

        session_destroy();
        script("location.href='accesos.php'");

    }else{

        registrar_accion($_SESSION['id_usuario'],'cerrar_session');
        $_SESSION['Authenticated']="0";
        session_destroy();
        script("location.href='index.php'"); 
    }


// EOF isset($log_out_admin)
}else if(isset($borrar_registro) || isset($borrar_registro_2)){ 

    // $tabla_eliminar
    // $id_eliminar
    if($tabla_eliminar == 'pedidos'){

        $DB->query('delete from facturas where id_pedido = '.$id_eliminar);
    }

    $DB->delete($tabla_eliminar,$id_eliminar);

    script("location.reload()");

// EOF isset($borrar_registro)
}else if(isset($accion) && $accion==i && !isset($formularios)){

    try {


        switch ($tabla_insertar) {

            case 'clientes':
                
                $c = $DB->select('id','clientes','email like "'.$email.'"');

                if($c[r]>0){

                    # avisamos al comercio y cliente
                    $url_cURL="https://".$_SERVER['HTTP_HOST']."/new_app/functions/ajax.php";
                    $post_cURL=array(
                            "enviar_email"=>1,
                            "tabla_enviar"=>"recupera_pass",
                            "id"=>$c[f][0]['id'],
                            "id_web"=>1);

                    // operacion curl.
                    $ch = curl_init();  
                    curl_setopt($ch, CURLOPT_URL, $url_cURL);  
                    curl_setopt($ch, CURLOPT_POSTFIELDS,$post_cURL);
                    curl_exec($ch);    
                    curl_close($ch);
                        
                    script("$('#modal_espera_general').modal('hide');");
                    throw new Exception("Ya tienes un usuario registrado con este email, en breve le enviamos su nueva contraseña a su email.");

                }

            break;

        }


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
                    if($key=="password"){

                        // este if hay que meterlo TB
                        // en los updates
                        if($value==""){

                            $new_pass=getPass(8);
                            $DB->ta[$key]=password_hash($new_pass, PASSWORD_DEFAULT); 
                            $DB->ta["comentario"]=$new_pass;

                        }else if($value!=""){

                            $DB->ta[$key]=password_hash($value, PASSWORD_DEFAULT); 
                            $DB->ta["comentario"]=$value;

                        }

                    }
                    // else if($key=="email"){
                        
                    //     $n_email = json_email($value);
                    //     $DB->ta['email'] = $n_email;


                    // }
                    else if($key=="pvp" || $key=="costo" || $key=="importe"){

                        $DB->ta[$key]=$value*100;

                    }else if($tabla_insertar=="facturas" && $key=="concepto"){

                        $concept = json_encode($value,JSON_UNESCAPED_UNICODE);

                    }else if(in_array($key,$arr_campos_fechas) || strpos($key,'fecha')!==false){

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
                                $key!="solo_cita" &&
                                $key!="id_imagenes_productos" && 
                                $key!="ingredientes_productos" && 
                                $key!="registro_web"
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


            case 'comentarios_blog':

                $id_insert = $DB->insert();
                # avisamos al comercio y cliente
                $url_cURL="https://".$_SERVER['HTTP_HOST']."/new_app/functions/ajax.php";
                $post_cURL=array(
                        "enviar_email"=>1,
                        "tabla_enviar"=>"moderar_comentario",
                        "id"=>$id_insert,
                        "id_web"=>1);

                // operacion curl.
                $ch = curl_init();  
                curl_setopt($ch, CURLOPT_URL, $url_cURL);  
                curl_setopt($ch, CURLOPT_POSTFIELDS,$post_cURL);
                curl_exec($ch);    
                curl_close($ch);

                $no_insert=1;

            break; // comentarios_blog

            case 'facturas':

                if($estado == 1){

                    $n = $DB2->select('contador,prefijo','contadores','id=1');
                    $cont = $n[f][0]['contador']+1;
                    $DB2->query('update contadores set contador = '.$cont.' where id = 1');

                    $DB->ta['numero'] = $n[f][0]['prefijo'].'-'.$cont;
                    $DB->ta['fecha_cobrada'] = now();    
                    $DB->ta['concepto']=$concept;

                }

            break; // facturas

            case 'tareas':

                $DB->ta['id_usuario'] = $_SESSION['id_usuario'];            

            break; // tareas

            case 'clientes':

                $DB->ta['id_delegacion'] = $_SESSION['id_delegacion']; 
                $DB->ta['fecha'] = date('Y-m-d'); 
                // $new_pass=getPass(8);
                // $DB->ta['password']=password_hash($new_pass, PASSWORD_DEFAULT); 
                // $DB->ta["comentario"]=$new_pass;   

                $id_insert = $DB->insert(); 

                if(isset($registro_web)){

                    # avisamos al comercio y cliente
                    $url_cURL="https://".$_SERVER['HTTP_HOST']."/new_app/functions/ajax.php";
                    $post_cURL=array(
                            "enviar_email"=>1,
                            "tabla_enviar"=>"bienvenida",
                            "id"=>$id_insert,
                            "id_web"=>1);

                    // operacion curl.
                    $ch = curl_init();  
                    curl_setopt($ch, CURLOPT_URL, $url_cURL);  
                    curl_setopt($ch, CURLOPT_POSTFIELDS,$post_cURL);
                    curl_exec($ch);    
                    curl_close($ch);

                    sleep(1);
                    $_SESSION['id'] = $id_insert;
                    script('location.href="https://pastelerialaargentina.com/zona_privada.php?bienvenida=1"');
                }

            break; // clientes

            case 'movimientos_bancarios':

                $id_del = isset($id_delegacion) ? $id_delegacion : 4;
                $DB->ta['id_delegacion'] = $id_del;            
                $DB->ta['id_usuario'] = $_SESSION['id_usuario'];    
                $tipo = $clase == 'venta' ? 1 : 0;
                $DB->ta['tipo'] = $tipo;

            break; // movimientos_bancarios

            case 'productos_web':

                $DB->ta['ingredientes'] = json_encode($ingredientes_productos, JSON_UNESCAPED_UNICODE);
                $dir = '../img/';
                // ahora tengo que modificar todos los ficheros
                // preparo las imágenes y las meto en su nuevo directorio
                $DB2->query('Select foto from imagenes_pre_productos where id_imagen = '.$id_imagenes_productos);

                if($DB2->rows() > 0){

                    $arr_fot = array();
                    while($d2 = $DB2->fetch()){

                        $arr_fot[] = $d2['foto'];

                    }
                    foreach ($arr_fot as $k => $v) {

                        # con esto modificamos el archivo, para que no se pueda 
                        # duplicar en la carpeta de fotos. 
                        $info = new SplFileInfo($v);
                        $ext = $info->getExtension();
                        list($na,$ea) = explode('.'.$ext, $v);
                        $nn = getPass(8).'.'.$ext;

                        $arr[] = 'https://'.$_SERVER['HTTP_HOST'].'/new_app/img/productos/'.$nn; 
                        rename($dir.'temporal/'.$v,$dir.'productos/'.$nn);

                    }

                    # eliminamos todas las fotos de la tabla.
                    $DB2->query('delete from imagenes_pre_productos where id_imagen = '.$id_imagenes_productos);
                    $DB->ta['fotos'] = json_encode($arr);

                } // if($DB2->rows() > 0){

                // # inserto el producto
                $id_insert = $DB->insert(1); 

                alert('Producto creado correctamente');
                location(0);
                $no_insert = 1;

            break; // productos_web


        }  // switch ($tabla_insertar) {


        // AQUI VIENEN TODAS LAS DEMAS INSERCCIONES
        if(!isset($no_insert)){

            $id_insert = $DB->insert(1); 

        }
        
        if($id_insert == false){

            alert('Registro No instertado, posiblemente este duplicado');

        }else{

            if(isset($noty) || isset($notify)){

                notify('Registro Insertado','success');
                if($tabla_insertar == 'clientes'){

                    script('$("#id_cliente_factura").val('.$id_insert.')');
                }


            }else{
                 
                if(!isset($_SESSION['ip_admin']) && !isset($registro_web)){

                    script("location.reload();");

                }  
            }
        }

    } catch (Exception $e) {
        
        notify($e->getMessage(),'danger');
    }

//eof if(isset($accion) && $accion==i){
}else if(isset($tabla_cargar_datos_registros)){



//////*******TENER EN CUENTA QUE VAMOS A TRAERNOS TODOS LOS 
    // CAMPOS CON EL AÑADIDO _editar 

////// ELIMINAR DE $select ////////////
         // accion
        // tabla_editar
        // id_editar

if(!isset($copiar_datos_comercio)){

    if($tabla_editar != 'tipos_pagos'){

        $select=$DB->limpiar_editar($_POST);
        $edit = "_editar";

    }else{

        $edit = "_editar";
        $DB->query("SHOW COLUMNS FROM tipos_pagos");

        while ($row = $DB->fetch()) {

            if($row['Field']!="id"){

                if(!isset($select['key'])){

                    $select['key'] = $row['Field'];

                }else{  

                    $select['key'] .=",".$row['Field'];

                }

            } // if($row['Field']!="id"){
                
        } // while ($row = $DB->fetch()) {

    }// if($tabla_editar != 'tipos_pagos ... 

}else{

    $edit = "";
    $DB->query("SHOW COLUMNS FROM comercios");

    while ($row = $DB->fetch()) {

        if($row['Field']!="id"){

            if(!isset($select['key'])){

                $select['key'] = $row['Field'];

            }else{  

                $select['key'] .=",".$row['Field'];

            }

        } // if($row['Field']!="id"){
            
    } // while ($row = $DB->fetch()) {
}

// alert($select['key']);
// alert($tabla_editar);
// alert(" id=".$id_editar);

// tenemos que diferenciar la carga de los datos 
// para los formularios por XML
if($tabla_editar!="formulario_modal"){


    $select_registro=$DB->select($select['key'],$tabla_editar," id=".$id_editar,f);
    // editar registros
        //meter $ general de update ($update)
    $clean   = array("\r\n", "\n", "\r","\t","\0","\x0B");

    echo "<script>";
    
        foreach ($select_registro[f][0] as $key => $value) {

                
                $value=str_replace($clean, '',$value);

                if($key=="comentario" && $tabla_editar !="comentarios_blog"){
                    $key="password";
                }

                // if($key=="email"){
                //     // si el cliente tiene + de 1 email en 
                //     // BBDD
                //     $email_arr=json_decode($value);
                //     $end=count($email_arr);

                //     // si solo 1
                //     if($end==1){
                //         $value = $email_arr[0];
                //     }else if($end>1){ // if($end==1){
                //         // si mas de 1
                //         $new_value="";
                //         for($e=1;$e<=$end;$e++){
                //             if($e<$end){
                //                 $new_value.=$email_arr[$e-1].";";
                //             }else{
                //                 $new_value.=$email_arr[$e-1];
                //             }
                //         }// for($e=1;$e<=$end;$e++){
                //         $value=$new_value;
                //     }// }else if($end>1){

                // }// if($key=="email"){

                if($key=='pvp' || $key=='neto' || $key=='total' || $key=='importe'){
                    $value=($value/100);
                }


                if($tabla_editar=="otra_tabla" && $key=="poblacion"){


                    // $DB2 = new DataAccess();
                    $provincia_id = $DB2->select('provincia_id','poblaciones','id='.$id_editar);
                    
                    $DB2->query('select id,provincia from provincias');
                    
                    $option = "";
                    while($data = $DB2->fetch()){

                        $selected = $data['id']==$provincia_id[f][0]['provincia_id'] ? 'selected="selected"' : '';
                        $option.='<option value="'.$data['id'].'" '.$selected.' >'.utf8_encode($data['provincia']).'</option>';
                    }

                    echo "$('#provincia_id_editar').html('".$option."');";

                }else{

                    if($tabla_editar=="poblaciones" && $key=="poblacion"){


                        // $DB2 = new DataAccess();
                        $provincia_id = $DB2->select('provincia_id','poblaciones','id='.$id_editar);
                        
                        $DB2->query('select id,provincia from provincias');
                        
                        $option = "";
                        while($data = $DB2->fetch()){

                            $selected = $data['id']==$provincia_id[f][0]['provincia_id'] ? 'selected="selected"' : '';
                            $option.='<option value="'.$data['id'].'" '.$selected.' >'.utf8_encode($data['provincia']).'</option>';
                        }

                        echo "$('#provincia_id_editar').html('".$option."');";

                    }else if($tabla_editar=="tipos_pagos" && $key == "cuotas"){

                        $arr_cuotas = json_decode($select_registro[f][0]['cuotas']);

                        foreach ($arr_cuotas as $key => $value) {


                            foreach ($value as $k => $v) {
                               
                              
                                echo " 
                                $('[name=\"".$k."_".$key."_editar\"]').val('".$v."');";

                            }
                            # code...
                        }

                    }else if( $key == 'dia' || $key == 'mes'){

                        $value = (int)$value;

                    }else if(in_array($key,$arr_campos_fechas)){

                        if($key=='periodo'){

                            list($dia,$mes,$year) =  explode("/",$value);
                            $DB->ta['mes']=$mes;
                            $DB->ta['year']=$year;
                        }

                        if($tabla_editar == 'movimientos_bancarios'){

                            list($fecha,$hora) = explode(' ',$value);
                            $value = formatear_fecha($fecha,m,i);

                        }else{

                            $value=formatear_fecha($value,m,i);

                        }

                    }

                    echo "
                
                    var tag = $('[name=\"".$key.$edit."\"]').prop('tagName');


                    if(tag=='SELECT'){
        

                        $('#".$key.$edit." option')
                           .each(function(indice,elemento) { 
                            $(elemento).attr('selected',false);
                        });
          
                        $('#".$key.$edit." option[value=\"".trim($value)."\"]').attr('selected',true);

                    }else if(tag=='TEXTAREA'){
         
                        $('#".$key.$edit."').html('".str_replace('&euro;','€',utf8_encode(trim($value)))."');
                         
                    }else{
         
                        $('#".$key.$edit."').attr('value' , '".str_replace('&euro;','€',utf8_encode(trim($value)))."'); 
                    }
                    ";

                } // if($tabla_editar=="productos" && $key=="id_operadora" ... else{

        } // foreach ($select_registro[f][0] as $key => $value) {
        echo "</script>";

    // eof if($tabla_editar!="formularios_modal")
    }
// }else if(isset($tabla_cargar_datos_registros)){  
}else if(isset($accion) && $accion==u && !isset($formularios)){

        if($tabla_editar == 'productos_web') $_POST = $_GET;

        if($tabla_editar == 'productos_web'){

            $_POST['ingredientes'] = $_POST['ingredientes_productos'];
            unset($_POST['ingredientes_productos']);

        }

        $datos=$DB->limpiar_editar($_POST,0,'_edit',';'); 

        //array de keys y values
        $set=explode(";",$datos['key']);
        $update=explode(";",$datos['value']);

        $DB->opentable($tabla_editar);
        $DB->edit($id_editar);
        $fin=count($update);


        // especialidades de tablas aqui.
        if(isset($campo_personalizado_editar) && isset($value_personalizado_editar)){

            $edit = array('_editar','_edit');
            $camp = str_replace($edit,'',$campo_personalizado_editar);
            // if(str_poss())
            if(strpos($camp,'fecha')!==false){

                $value_personalizado_editar=formatear_fecha($value_personalizado_editar,i,m);
                                  
            }

            $DB->ta[$camp]=$DB->textCheck($value_personalizado_editar);

        }else{ //


            for($x=0;$x<$fin;$x++) {


                if($set[$x]=="comentario" && ($tabla_editar!='comercios' && $tabla_editar !="comentarios_blog")){

                    $DB->ta[$set[$x]]=$DB->textCheck($update[$x]);
                    $DB->ta['password']=password_hash($update[$x], PASSWORD_DEFAULT);

                }else if(in_array($set[$x],$arr_campos_fechas)){

                    if($set[$x]=='periodo'){

                        list($dia,$mes,$year) =  explode("/",$update[$x]);
                        $DB->ta['mes']=$mes;
                        $DB->ta['year']=$year;
                    }

                    $update[$x]=formatear_fecha($update[$x],i,m);
                    $DB->ta[$set[$x]]=$update[$x];

                }else if($set[$x]=='pvp' || $set[$x]=='neto' || $set[$x]=='total' || $set[$x]=='importe'){
                    
                    $new_number = str_replace(",", ".", $update[$x]);
                    $new_number = number_format($new_number,2,".","");
                    $new_number=$new_number*100;
                    $DB->ta[$set[$x]]=$new_number;

                }else if($set[$x] == "concepto" && $tabla_editar=="facturas"){ 

                    $DB->ta[$set[$x]] = json_encode($concepto);

                }else if($set[$x] == "cuotas" && $tabla_editar=="tipos_pagos"){ 

                        // alert($cuotas);
                    $DB->ta[$set[$x]] = json_encode($cuotas);

                }else if($set[$x] == "id_tipo_tarea" && $tabla_editar=="tareas"){ 

                    $DB->ta['id_tipo_tarea'] = $update[$x];
                    if(in_array("panel_gestionar_tarea",$set)){

                        $mov = $DB2->select('crea_movimiento,tipo_movimiento','tipos_tareas','id = '.$update[$x]);
                        $m = $mov[f][0];
                        # creamos un movimiento si procede
                        if($m['crea_movimiento']==1){
                            
                            $data = $DB2->select('importe,titulo','tareas','id = '.$id_editar);
                            $d = $data[f][0];

                            $DB2->opentable('movimientos_bancarios');
                            $DB2->ta['fecha']=$fecha_realizada_editar;
                            $DB2->ta['id_usuario']=$id_usuario_editar;
                            $DB2->ta['id_delegacion']=$_SESSION['id_delegacion'];
                            $DB2->ta['tipo']=$m['tipo_movimiento'];
                            $DB2->ta['importe']=$d['importe'];
                            $DB2->ta['concepto']=$d['titulo'];
                            $DB2->ta['id_via_cobro']=1;
                            $DB2->ta['verificado']=1;
                            $DB2->insert();

                        }

                    }

                }else if($tabla_editar == 'pedidos' && $set[$x] == "facturar"){ 

                    if($update[$x]==1 && $es_recurrente_editar == 0){

                        $pago_procesado = 1;
                        # sacamos contador
                        $contador = $DB->select('prefijo,contador','contadores','id=1');
                        $nuevo_contador = $contador[f][0]['contador']+1;   

                        # pagamos la factura;
                       $DB->query('update facturas set estado = 1, fecha_cobrada = "'.now().'", fecha_cobro = "'.date('Y-m-d').'", numero="'.$contador[f][0]['prefijo'].'-'.$nuevo_contador.'", id_via_cobro=1 where id_pedido = '.$id_editar);

                        #actualizamos contador
                        $DB->query('update contadores set contador = '.$nuevo_contador.' where id = 1');

                    }else if($update[$x]==1 && $es_recurrente_editar == 0){

                        $pago_procesado = 0;
                        # pagamos la factura;
                        $DB->query('update facturas set estado = 1, fecha_cobrada = "'.now().'", fecha_cobro = "'.date('Y-m-d').'", id_via_cobro=1 where id_pedido = '.$id_editar);

                    }

                    # id_cliente
                    $id_cli= $DB->select('id_cliente','pedidos','id='.$id_editar);
                    $id_cliente = $id_cli[f][0]['id_cliente'];

                    #id_factura
                    $id_fac = $DB->select('id','facturas','id_pedido='.$id_editar);
                    $id_factura = $id_fac[f][0]['id'];

/*
                    $url_cURL="https://".$_SERVER['HTTP_HOST']."/new_app/functions/ajax.php";
                    $post_cURL=array(
                                "enviar_email"=>1,
                                "tabla_enviar"=>"facturas",
                                "pago_procesado"=>$pago_procesado,
                                "id_pedido"=>$id_editar,
                                "id"=>$id_factura,
                                "id_cliente"=>$id_cliente,
                                "id_web"=>$_SESSION['id_web']);

                    // operacion curl.
                    $ch = curl_init();  
                    curl_setopt($ch, CURLOPT_URL, $url_cURL);  
                    curl_setopt($ch, CURLOPT_POSTFIELDS,$post_cURL);
                    curl_exec($ch);    
                    curl_close($ch);
*/

                }else if($set[$x] == "clase" && $tabla_editar=="movimientos_bancarios"){ 

                    $DB->ta[$set[$x]]=$DB->textCheck($update[$x]);
                    $tipo = $update[$x] == 'venta' ? 1 : 0;
                    $DB->ta['tipo'] = $tipo;
                    $DB2->query('update movimientos_bancarios set tipo = '.$tipo.' where id = '.$id_editar);

                }else if($set[$x] == "ingredientes" && $tabla_editar=="productos_web"){ 

                    $DB->ta[$set[$x]] = json_encode_bbdd($_POST['ingredientes']);

                }else if($set[$x] == "fotos" && $tabla_editar=="productos_web"){ 


                    // $imge = 'https://'.$_SERVER['HTTP_HOST'].HOME_PATH.'/img/temporal/'.$new_name;

// alert(0);
                    ###############################

                    ## eliminar las viejas del array y del servidor.
                    # en fotos viene el array a borrar 

                    if(strpos($update[$x],',') !== false){

                        $arr_fot =  explode(',',$update[$x]);

                    }else{

                        $arr_fot[0] =  $update[$x];

                    }
                    $arr = array();
                    $af = $DB2->select('fotos',$tabla_editar,' id = '.$id_editar);
                    $af = json_decode($af[f][0]['fotos'], true, JSON_UNESCAPED_UNICODE);

                    $arr_nf = array();
                    foreach ($af as $k) {
                              
                        list($url[$f],$foto[$f]) = explode('productos/',$k);
                        if(array_search($foto[$f],$arr_fot) !== false){

                            unlink('../img/productos/'.$k);

                        }else{

                            $arr_nf[] = $k;

                        }

                    }

                    $dir = '../img/';


                    // ahora tengo que modificar todos los ficheros
                    # preparo las imágenes y las meto en su nuevo directorio
                    $DB2->query('Select foto from imagenes_pre_productos where id_imagen = '.$id_editar);
                    $arr_fot = array();
                    while($d2 = $DB2->fetch()){

                        $arr_fot[] = $d2['foto'];

                    }

                    foreach ($arr_fot as $k => $v) {

                        # con esto modificamos el archivo, para que no se pueda 
                        # duplicar en la carpeta de fotos. 
                        $info = new SplFileInfo($v);
                        $ext = $info->getExtension();
                        list($na,$ea) = explode('.'.$ext, $v);
                        $nn = getPass(8).'.'.$ext;

                        $arr_nf[] = 'https://'.$_SERVER['HTTP_HOST'].'/new_app/img/productos/'.$nn; 
                        rename($dir.'temporal/'.$v,$dir.'productos/'.$nn);

                    }

                    // var_dump($arr_nf);
                    # eliminamos todas las fotos de la tabla.
                    $DB2->query('delete from imagenes_pre_productos where id_imagen = '.$id_editar);

                    $DB->ta['fotos'] = json_encode($arr_nf);

                }else if($set[$x]!="sin_reload" && $set[$x]!="sin_reload_zona_privada" && $set[$x]!=""
                        && $set[$x]!="notify" && $set[$x]!="noty" && $set[$x]!="panel_gestionar_tarea"){

                    if(isset($sin_reload_zona_privada)){
                        $_SESSION[$set[$x].'_zona_privada']=$update[$x];
                    }

                    $DB->ta[$set[$x]]=$DB->textCheck(utf8_decode(str_replace('€','&euro;',$update[$x])));

                } // }else if($set[$x]!="sin_reload" && $set[$x]!="sin_reload_zona_privada"){

            } // for($x=0;$x<$fin;$x++) {

        } //if(isset($campo_personalizado_editar) && isset ....

        if($tabla_editar == 'blog'){

            $DB->ta['texto'] = $DB->textCheck(utf8_decode(str_replace('€','&euro;',$texto_editar)));

        }

        $id_editado = $DB->update(1);


        if($id_editado == -1){

            notify('Error al editar, intentalo de nuevo','danger');

        }else{

            if(isset($sin_reload) || isset($sin_reload_zona_privada)){

                alert('Datos Modificados');

            }else if(isset($noty) || isset($notify)){

                notify('Datos Modificados','success');

            }else{

                  if(!isset($_SESSION['ip_admin'])){
                    location(2);  
                  }
            }
        }

// }else if(isset($accion) && $accion==u && !isset($formularios)){
}else if(isset($enviar_email)){
    
    try {
        
        $_SESSION['id_web'] = isset($_SESSION['id_web']) ? $_SESSION['id_web'] : $id_web;
                # enviamos al cliente:
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

        switch ($tabla_enviar) { // 

            case 'moderar_comentario':

                $dc =  $DB->select('nombre,comentario','comentarios_blog ','id='.$id);
                $cliente = $dc[f][0];
                $cliente['email'] = 'desarrollo@ckcreativos.com';
                $subjet = 'Comentario insertado en Pastelería la Argentina';

            break; // case 'moderar_comentario':

            case 'aprobar_comentario':

                $dc =  $DB->select('nombre,email','comentarios_blog ','id='.$id);
                $cliente = $dc[f][0];

                $subjet = 'Comentario aprobado en Pastelería la Argentina';

            break; // case 'aprobar_comentario':

            case 'bienvenida':

                $dc =  $DB->select('nombre,email,comentario','clientes ','id='.$id);
                $cliente = $dc[f][0];

                $subjet = 'Bienvenid@ a Pastelería la Argentina';

            break; // case 'bienvenida':

            case 'recupera_pass':

                $new_pass=getPass(8);
                $DB->query('update clientes set comentario = "'.$new_pass.'", password = "'.password_hash($new_pass, PASSWORD_DEFAULT).'" where id = '.$id);

                $dc =  $DB->select('nombre,email','clientes ','id='.$id);
                $cliente = $dc[f][0];

                $subjet = 'Nueva Contraseña Pastelería La Argentina';

            break; // case 'recupera_pass':

            case 'facturas':

                # esta primera opcion la estamos usando
                # solo en el doble click de faturacion.php,
                if(isset($id_cliente)){

                    $dc =  $DB->select('nombre,nombre_fiscal,email','clientes','id='.$id_cliente);
                    $cliente = $dc[f][0];

                }else{

                    $dc =  $DB->inner_join('c.nombre,c.nombre_fiscal,c.email','facturas f inner join clientes c','f.id_cliente = c.id where f.id='.$id);
                    $cliente = $dc[f][0];

                }
                
                $subjet = $pago_procesado == 1 ? 'Factura' : 'Albarán';

                $subjet .= ' Pastelería la Argentina';

            break; // case 'facturas':

            case 'formulario_contacto_modal':

                $cliente['email'] = $email_contacto;
                $subjet = 'Contacto Recibido - Pastelería la Argentina';


            break; // case 'formulario_contacto_modal':

        } // switch ($tabla_enviar) {


        if(isset($envio_admon) && $envio_admon==1 || isset($envio_admin) && $envio_admin==1){

            // $mail->AddAddress('pedrogil@soltury.com');
            $mail->addCC($datos['email2']);


        }else{

            if($cliente['email']!=''){


                // if(isset($_SESSION['ip_admin'])){

                //     $mail->AddAddress('pedrogil@soltury.com');

                // }else{
                    
                    $mail->AddAddress($cliente['email']);

                // }

            }else{

                alert('Este cliente no tiene ningún email asociado, te acabamos de enviar el email, para que se lo puedas reenviar al cliente');
                    
                // if(isset($_SESSION['ip_admin'])){

                //     $mail->AddAddress('pedrogil@soltury.com');

                // }else{
                    
                    // $mail->AddAddress($datos['email']);
                    $mail->addCC($datos['email']);

                // }


            }
            
        }
     
        $mail->Subject=$subjet; 


        switch ($tabla_enviar) {

            case 'moderar_comentario':


                $texto_email='Hola Compañeros,<br><br>
                    Hemos recibido un nuevo comentario de un cliente en el blog, para revisarlo sólo tienes que pulsar el siguiente botón:
                    <br><br>
                    <div style="width:100%; text-align: center;
                            border-bottom:#CCC 1px dotted; padding:10px 0 10px 0">
                    <a href="https://'.$_SERVER['SERVER_NAME'].'/moderar_comentario.php?id='.$id.'"
                            style="font-family: Playfair Display,serif; border-radius: 1px; font-weight: 600;border-width: 0;
                            padding: 8px 25px; transition: color .5s , border-color .5s , background-color .5s; font-size: 16px;
                            padding: 10px 35px; color: #fff; background-color: #'.$datos['color_email'].';
                            text-decoration:none;">
                            Pulse aqu&iacute;
                            </a>
                            <br><br>
                        </div>
                    <br><br>
                    Recibe un cordial saludo del equipo de Pastelería la Argentina.';

                $mail->Body=body_email(t,'',$_SESSION['id_web'],$texto_email);

            break; // case 'moderar_comentario':

            case 'aprobar_comentario':

                list($nombre,$apellidos) = explode(' ',$cliente['nombre']); 

                $texto_email='Hola '.ucwords(strtolower(utf8_encode($nombre))).',<br><br>';
                
                $texto_email.='Estamos encantados de saludarte desde  y te agradecemos enormemente que hayas participado en nuestras publicaciones. A partir de ahora podrás visualizar tu comentario en nuestro blog, esperamos que sigas disfrutando de la información que compartimos.
                    <br><br>
                    Recibe un cordial saludo del equipo de Pastelería la Argentina.';

                $mail->Body=body_email(t,'',$_SESSION['id_web'],$texto_email);

            break; // case 'aprobar_comentario':

            case 'bienvenida':

                list($nombre,$apellidos) = explode(' ',$cliente['nombre']); 

                $texto_email='Hola '.ucwords(strtolower(utf8_encode($nombre))).',<br><br>';
                
                $texto_email.='Estamos encantados de saludarte desde  y te damos la bienvenida a nuestra web. A partir de ahora podrás acceder a tu zona privada con tu email utilizando la siguiente contraseña <br><br>
                    <b>'.$cliente['comentario'].'</b><br><br>
                    Puedes modificarla en cualquier momento desde la sección de "Mis Datos" de tu zona privada.
                    Recibe un cordial saludo del equipo de Pastelería la Argentina.';


                $mail->Body=body_email(t,'',$_SESSION['id_web'],$texto_email);
                $mail->send();
                $email_enviado = 1;

            break; // case 'bienvenida':

            case 'recupera_pass':

                list($nombre,$apellidos) = explode(' ',$cliente['nombre']); 

                $texto_email='Hola '.ucwords(strtolower(utf8_encode($nombre))).',<br><br>';
                
                $texto_email.='Tal y como le acabamos de indicar le enviamos tu contraseña. Por motivos de seguridad esta ha sido cambiada. Puedes cambiarla de nuevo dentro de tu zona privada. <br><br>
                    <b>Nueva Contraseña : </b>'.$new_pass.'<br><br>
                    Recibe un cordial saludo del equipo de Pastelería La Argentina.';


                $mail->Body=body_email(t,'',$_SESSION['id_web'],$texto_email);
                $mail->send();
                $email_enviado = 1;

            break; // case 'recupera_pass':

            case 'facturas':

                $id_print = $pago_procesado == 1 ? 'factura='.$id : 'pedido='.$id_pedido;
                $msj = $pago_procesado == 1 ? 'factura. Para descargarla' : 'albar&aacute;n. Para descargalo';
                list($nombre,$apellidos) = explode(' ',$cliente['nombre']); 

                $texto_email='Hola '.ucwords(strtolower(utf8_encode($nombre))).',<br><br>';
                
                $texto_email.='Tal y como le acabamos de indicar, le adjuntamos su '.$msj.' s&oacute;lo tiene que pulsar el siguiente bot&oacute;n 
                    <br><br>
                    <div style="width:100%; text-align: center;
                            border-bottom:#CCC 1px dotted; padding:10px 0 10px 0">
                    <a href="https://'.$datos['web'].'/new_app/functions/descargar_documentacion.php?id_'.$id_print.'"
                            style="font-family: Playfair Display,serif; border-radius: 1px; font-weight: 600;border-width: 0;
                            padding: 8px 25px; transition: color .5s , border-color .5s , background-color .5s; font-size: 16px;
                            padding: 10px 35px; color: #fff; background-color: #'.$datos['color_email'].';
                            text-decoration:none;">
                            Pulse aqu&iacute;
                            </a>
                            <br><br>
                        </div>';


                $mail->Body=body_email(t,'',$_SESSION['id_web'],$texto_email);

                if(!isset($notify)){

                    notify('Proceso realizado Correctamente','success');
                    //location(2);

                }else if(isset($notify) && $notify == 1){

                    script('$("#modal_email").modal("hide")');
                    notify('Proceso realizado Correctamente','success');
                }

            break;

            case 'formulario_contacto_modal':

                $texto_email='Hola '.ucwords(strtolower(utf8_encode($nombre_contacto))).',<br><br>';
                $texto_email.='Le confirmamos que hemos recibido correctamente su contacto y le contactaremos en la mayor brevedad posible, estos son sus datos de contacto: 
                    <br><br>
                    <b>Nombre</b> : '.$nombre_contacto.'<br>
                    <b>Teléfono</b> : '.$telefono_contacto.'<br>
                    <b>E-mail</b> : '.$email_contacto.'<br>
                    <b>Asunto</b> : '.$asunto_contacto.'<br>
                    <b>Mensaje</b> : '.$cuerpo_contacto.'<br><br>
                    Gracias por confiar en nosotros, un saludo.
                    <br><br>
                    Pasteler&iacute;as La Argentina <br>
                    Desde 1928';


                $mail->Body=body_email(t,'',$_SESSION['id_web'],$texto_email);


                $mail_2 = new phpmailer($datos['email']);
                $mail_2->setFrom($datos['email'],'Pastelería La Argentina');
                $mail_2->AddAddress('lulomon26@hotmail.com');
                $mail_2->AddCC('pastelerialaargentina@gmail.com');
                // $mail_2->AddAddress('desarrollo@ckcreativos.com');


                $mail_2->Subject="Contacto web - Pastelería La Argentina"; 

                $texto_email_2='Hola Compañeros,<br><br>
                    Hemos recibido un contacto desde la web y el cliente ya ha sido avisado con la confirmación de nuestra recepción, estos son sus datos de contacto y lo que nos solicita: 
                    <br><br>
                    <b>Nombre</b> : '.$nombre_contacto.'<br>
                    <b>Teléfono</b> : '.$telefono_contacto.'<br>
                    <b>E-mail</b> : '.$email_contacto.'<br>
                    <b>Asunto</b> : '.$asunto_contacto.'<br>
                    <b>Mensaje</b> : '.$cuerpo_contacto.'<br><br>
                    Gracias por confiar en nosotros, un saludo.
                    <br><br>
                    Pasteler&iacute;as La Argentina <br>
                    Desde 1928';


                $mail_2->Body=body_email(t,'',$_SESSION['id_web'],$texto_email_2);
                $mail_2->send();

                script('$("#modal_contacto").modal("hide")');


            break;
            
        } // switch ($tabla_enviar) {

        
        if(!isset($email_enviado)){

            if($mail->send()){

               if($tabla_enviar != 'facturas' && $tabla_enviar != 'renovaciones'){

                    notify('Envio realizado','success');

                    if($tabla_enviar == 'aprobar_comentario'){

                        sleep(2);
                        location(2);
                    }
                }

            }else{

                notify('Envio NO realizado','warning');

            }

        } // if(!isset($email_enviado)){

    } catch (Exception $e) {
        
    }



// }else if(isset($enviar_email)){
}else if(isset($formularios)){
   

    if($accion==i){

        //++++++ $ recibidas ++++
        // 
        // $id_formulario
        // $title
        // $submit
        // $warning

        $form_mod['datos']["title"] = (string)trim(($title));
        $form_mod['datos']["submit"] = (string)trim($submit);
        $form_mod['datos']["warning"] = (string)trim($warning);
        $form_mod["bodyForm"]['campo'] = $campos_form;
        $j = json_encode($form_mod, JSON_UNESCAPED_UNICODE);

        $DB->query("insert into formulario_modal_json (nombre,json) values ('".$id_formulario."','".$j."')");
        script("location.reload()");

    }else if($accion==u){

                $form=array(
                        'datos'=>array(
                            'title'=>$title,
                            'submit'=>$submit,
                            'warning'=>$warning
                            )
                        );  


                $for = count($id_nombre_campo_);
                $form["bodyForm"]['campo']=array();

                for($x=0;$x<$for;$x++){

                    if($borrar_campo_[$x]==0){

                        $form["bodyForm"]['campo'][$x]=array(
                            'orden'=>$posicion_campo_[$x],
                            'types'=>$type_campo_[$x],
                            'names'=>$id_nombre_campo_[$x],
                            'verifys'=>$verify_campo_[$x],
                            'placeholders'=>$place_label_campo_[$x]
                        
                        );//eof $form["bodyForm"]['campo'][$x]=array( 

                        if($type_campo_[$x]=='textArea'){

                            $form["bodyForm"]['campo'][$x]['rows']=$rows_textArea_[$x];
                            $form["bodyForm"]['campo'][$x]['cols']=$cols_textArea_[$x];
                    
                        } // if($type_campo_[$x]=='textArea'){

                        if($type_campo_[$x]=='select'){

                            $form["bodyForm"]['campo'][$x]['boleano']=$boleano_select_[$x];
                            $form["bodyForm"]['campo'][$x]['mes']=$mes_select_[$x];

                            $form["bodyForm"]['campo'][$x]['numeros']=$numeros_select_[$x];
                            $form["bodyForm"]['campo'][$x]['comienzo']=$comienzo_select_[$x];
                            $form["bodyForm"]['campo'][$x]['fin']=$fin_select_[$x];

                            $form["bodyForm"]['campo'][$x]['extra']=$extra_select_[$x];
                            $form["bodyForm"]['campo'][$x]['extra_val']=$val_extra_[$x];
                            $form["bodyForm"]['campo'][$x]['extra_text']=$text_extra_[$x];

                            $form["bodyForm"]['campo'][$x]['tabla']=$tabla_select_[$x];
                            $form["bodyForm"]['campo'][$x]['select_val']=$val_select_[$x];
                            $form["bodyForm"]['campo'][$x]['select_text']=$text_select_[$x];
                            $form["bodyForm"]['campo'][$x]['where']=$where_select_[$x];
                            
                        } // eof  if($type_campo_[$x]=='select'){

                    } //eof if($borrar_campo_[$x]==0){
                
                } // eof for($x=0;$x<$for;$x++){

                // ahora tenemos que ordenar el array como
                // queremos que vaya ejjejej
                usort($form["bodyForm"]['campo'], 'sort_by_orden');
                // unimos el array que traemos los nuevos campos
                if($sumar_campos == 1){
                    $form["bodyForm"]['campo'] = array_merge($form["bodyForm"]['campo'],$campos_form);
                }

                $j = json_encode($form, JSON_UNESCAPED_UNICODE);

                $DB->query("update formulario_modal_json set nombre = '".$id_formulario."' , json = '".$j."' where id = ".$id_editar);
                
                alert('formulario modificado');
                script("location.reload()");


    }// eof  }else if($accion==u){

   // EOF isset($formularios) 
}else if(isset($json_campos_formulario)){

    // foreach ($_POST as $key => $value) {
        
    //     if(${$key}=="") ${$key}="";
    // }
    $type=array(1=>"text",
                2=>"password",
                3=>"hidden",
                4=>"select",
                5=>"select",
                6=>"textArea",
                7=>"select",
                8=>"select");
 

    $mes = $type_campo==8 ? 1 : 0;
    $boleano = $type_campo==4 ? 1 : 0;
    $numeros = $type_campo==7 ? 1 : 0;


    $new_campo = "$('#div_campos_json').append(function(){
        return '<div class=\"alert alert-info alert-dismissible fade show\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>Campo : ".$type[$type_campo]." - ".$id_nombre_campo."<input type=\"hidden\" name=\"campo_sum\" types=\"".$type[$type_campo]."\" names=\"".$id_nombre_campo."\" verifys=\"".$verify_campo."\" val=\"\" placeholders=\"".$place_label_campo."\" boleano=\"".$boleano."\" mes=\"".$mes."\" numeros=\"".$numeros."\" comienzo=\"".$comienzo_select."\" fin=\"".$fin_select."\" extra=\"".$extra_select."\" extra_val=\"".$val_extra."\" extra_text=\"".$text_extra."\" tabla=\"".$tabla_select."\" select_val=\"".$val_select."\" select_text=\"".$text_select."\" where=\"".$where_select."\" rows=\"".$rows_textArea."\"  cols=\"".$cols_textArea."\" verify=\"0\"></div>'});";


        script($new_campo."

            $('select[name=\"type_campo\"]>option[value=\"1\"]').attr('selected','selected');

            var textArea = $('div[name=\"campos_textArea\"]');
            var select = $('div[name=\"campos_select\"]');
            var selectN = $('div[name=\"campos_select_numero\"]');

            if(select.is(':visible')){

                select.css('display','none');

            }else if(textArea.is(':visible')){

                textArea.css('display','none');

            }else if(selectN.is(':visible')){

                selectN.css('display','none');
            }

            $('select[name=\"type_campo\"] option[value=1]').attr('selected',true);
            $('#sumar_campo_ajax_form input').each(function(indice, elemento){ 

                if($(elemento).val()!='Añadir Campo' && $(elemento).attr('name')!='xml_campos_formulario')
                    $(elemento).val('');
            });");


// EOF isset($json_campos_formulario)
}else if(isset($buscar_tables_ajax)){

    try {

        // opciones existentes para poder entrar
        switch ($tabla) {

            case 'campanas_puntuales':

                $tbody = $tabla;
                
                if($desde == '' || $hasta == ''){

                    throw new Exception("Tienes que poner un rango de fechas");

                }else{

                    $where = ' fecha between "'.formatear_fecha($desde,i,m).'" and "'.formatear_fecha($hasta,i,m).'"';

                }

                $modal[0] = "";

            break; // case 'campanas_puntuales':

            case 'control_envios':

                $tbody = $tabla;
                
                if($desde == '' || $hasta == ''){

                    throw new Exception("Tienes que poner un rango de fechas");

                }else{

                    $where = ' fecha between "'.formatear_fecha($desde,i,m).'" and "'.formatear_fecha($hasta,i,m).'"';

                }

                $modal[0] = "";

            break; // case 'control_envios':

            case 'facturas_pendientes':

                $tbody = $tabla;
                
                $hasta = $hasta!='' ? $hasta : date('d/m/Y');

                $where=' where id_cliente='.$id_cliente.' and f.estado = 0 and fecha_cobro between "'.formatear_fecha($desde,i,m).'" and "'.formatear_fecha($hasta,i,m).'"';

                $modal[0] = "";

            break; // case 'facturas_pendientes':

            case 'movimientos_bancarios':

                $tbody = $tabla;
                
                $hasta = $hasta!='' ? $hasta : date('d/m/Y');

                $where = ' where fecha between "'.formatear_fecha($desde,i,m).' 00:00:00" and "'.formatear_fecha($hasta,i,m).' 23:59:59"';

                $where .= $clase == 'todos' ? '' : ' and clase like "'.$clase.'"';
                $where .= $id_delegacion == '0' ? '' : ' and m.id_delegacion = '.$id_delegacion;

                $modal[0] = "movimientos_bancarios";

            break; // case 'movimientos_bancarios':

            case 'facturas':

                $tbody = $tabla;
                
                $estado = $estado == 2 ? ' in (0,1)' : ' ='.$estado;
                $where=' where f.estado '.$estado;

                $tipo_fecha = !isset($tipo_fecha) ? 'fecha_cobrada' : $tipo_fecha;
                if($desde!='' || $hasta!=''){

                    $hi = $tipo_fecha == 'fecha_cobrada' ? ' 00:00:00' : '';
                    $hf = $tipo_fecha == 'fecha_cobrada' ? ' 23:59:59' : '';

                    $desde = $desde!='' ? $desde : date('d/m/Y');
                    $hasta = $hasta!='' ? $hasta : date('d/m/Y');
                    $where.= ' and f.'.$tipo_fecha.' between "'.formatear_fecha($desde,i,m).$hi.'" and "'.formatear_fecha($hasta,i,m).$hf.'"';
                }

                $where.= $id_cliente>0 ? ' and f.id_cliente = '.$id_cliente : '';

                $where.= $facturada==0 ? ' and f.numero like ""' : ($facturada==1 ? ' and f.numero not like ""' : '');

                $modal[0] = "";

            break; // case 'facturas':

            
            default:

                echo 'No hay ning&uacute;n resultado';

            break; // default:
        }


        if($buscar_tables_ajax==""){ 

                script("$('#ultimas_".$tabla."').removeClass('d-none');
                    $('#resultado_".$tabla."').addClass('d-none');");

        }else if(pintar_tbody($tbody,$modal,$where)[r]==0){

            $extras = '';

            switch ($tabla) {

                case 'facturas_pendientes':

                    $extras.='
                        $(".ver_albaranes").attr("href","");
                        $("#deuda_span").html("No hay facturas pendientes");

                    ';

                break; // case 'control_envios':

                case 'control_envios':

                    $movimientos =  $table;
                
                    // si es mayor que 0
                    // .ver_albaranes -> aqui hay que tirar la nueva href
                    // #deuda_span aqui hay que tirar el nuevo precio

                    $extras.='
                            
                            $("#costo_whatsapp_span").html("0.00");
                             
                        ';
                   
                break; // case 'control_envios':
                

            }
                script("$('#resultado_".$tabla."').removeClass('d-none');
                    $('#resultado_".$tabla."').html('Con estos datos no tenemos ning&uacute;n registro.');
                    $('#ultimas_".$tabla."').addClass('d-none');
                    ".$extras);
             


        }else if(pintar_tbody($tbody,$modal,$where)[r]>0){

            $extras = '';
            ## aqu ipuedo meter cualquier dato mas que quiera al encabezado

            $table = pintar_tbody($tbody,$modal,$where);
            switch ($tabla) {

                case 'facturas_pendientes':
                   
                    $movimientos =  $table;
                
                    // si es mayor que 0
                    // .ver_albaranes -> aqui hay que tirar la nueva href
                    // #deuda_span aqui hay que tirar el nuevo precio

                    $extras.='
                            $(".ver_albaranes").attr("href","/new_app/functions/descargar_documentacion.php?id_cliente='.$id_cliente.'&pagadas=0&desde='.formatear_fecha($desde,i,m).'&hasta='.formatear_fecha($hasta,i,m).'");
                            $("#deuda_span").html("Ver albaranes Pendientes por '.number_format(($movimientos['totales']),2,'.','').' &euro;");

                        ';

                break; // case 'facturas_pendientes':

                case 'facturas':

                    $movimientos =  $table;
                
                    // si es mayor que 0
                    // .ver_albaranes -> aqui hay que tirar la nueva href
                    // #deuda_span aqui hay que tirar el nuevo precio

                    $extras.='
                            
                            $("#albaranes_cobrados_span").html("'.number_format(($movimientos['total_ingresos']),2,'.','').'");
                             $("#albaranes_pendientes_span").html("'.number_format(($movimientos['total_gastos']),2,'.','').'");

                        ';
                   
                   // h3_filtro
                break; // case 'facturas':

                case 'movimientos_bancarios':

                    $movimientos =  $table;
                
                    // si es mayor que 0
                    // .ver_albaranes -> aqui hay que tirar la nueva href
                    // #deuda_span aqui hay que tirar el nuevo precio

                    $extras.='
                            
                            $("#movimientos_ventas_span").html("'.number_format(($movimientos['total_ingresos']),2,'.','').'");
                             $("#movimientos_compras_span").html("'.number_format(($movimientos['totales']),2,'.','').'");
                             $("#movimientos_gastos_span").html("'.number_format(($movimientos['total_gastos']),2,'.','').'");

                        ';
                   
                   // h3_filtro
                break; // case 'movimientos_bancarios':

                case 'control_envios':

                    $movimientos =  $table;
                
                    // si es mayor que 0
                    // .ver_albaranes -> aqui hay que tirar la nueva href
                    // #deuda_span aqui hay que tirar el nuevo precio

                    $extras.='
                            
                            $("#costo_whatsapp_span").html("'.($movimientos['totales']).'");
                             
                        ';
                   
                break; // case 'control_envios':
                

            }

            $table = $table['body'];
            $clean   = array("\r\n", "\n", "\r","\t","\0","\x0B");
            $table=str_replace($clean, '',$table);
            $table=str_replace('"', "'", $table);

            script('
                    $("#resultado_'.$tabla.'").removeClass("d-none");
                    $("#resultado_'.$tabla.'").html("'.$table.'");
                    $("#ultimas_'.$tabla.'").addClass("d-none");
                    $(".datatable").dataTable();
                    '.$extras);

        }   // if($resultado[r]>0){ 

    } catch (Exception $e) {
        
        notify($e->getMessage(),'danger');

    }

// }else if(isset($buscar_tables_ajax)){
}else if(isset($buscar_datos_gestoria)){

    if($desde == '' || $hasta == ''){

        notify('Tienes que seleccionar un rango de fechas','danger');

    }else{

        // comercio: 
        // desde: 19/09/2019
        // hasta: 20/09/2019
        // tipo_fecha: fecha_cobro
        $q = 'select c.nombre_fiscal,f.importe from facturas f inner join clientes c on f.id_cliente = c.id 
            where fecha_cobrada between "'.formatear_fecha($desde,i,m).'" and "'.formatear_fecha($hasta,i,m).'" and f.estado = 1';
       
        # si viene el comercio
        $q.= $id_cliente>0 ? ' and f.id_cliente = '.$id_cliente : '';

        $tit = 'Todos los Clientes';
        $imp = 0;
        $DB->query($q);
        while($d = $DB->fetch()){

            if($imp == 0 && $id_cliente>0){
                $tit = $d['nombre_fiscal'];
            }
            $imp+=$d['importe'];

        }

        $iva = ($imp/100)*10/100;
        $base = ($imp/100)*90/100;
        $total = $imp/100;
        $table='<div class="row text-secondary">
                    <div class="col-12">
                        <h1 class="text-center"><u>Consulta para '.$tit.'</u></h1>
                        <hr>
                    </div>
                    <div class="col-6 text-center"><h3><b>Desde : '.$desde.'</b></h3><hr></div>
                    <div class="col-6 text-center"><h3><b>Hasta : '.$hasta.'</b></h3><hr></div>
                    <div class="col-4 text-center">
                        <h3><b><u>Base imponible</u></b></h3>
                        <h4 class="text-success">'.number_format($base,2,'.','').' &euro;</h4>
                    </div>  
                    <div class="col-4 text-center">
                        <h3><b><u>Iva</u></b></h3>
                        <h4 class="text-success">'.number_format($iva,2,'.','').' &euro;</h4>
                    </div>
                    <div class="col-4 text-center">
                        <h3><b><u>Total</u></b></h3>
                        <h4 class="text-success">'.number_format($total,2,'.','').' &euro;</h4>
                    </div>
                    <div class="col-12 text-center">
                        <form method="POST" action="descargar_excel.php">
                            <input type="hidden" name="desde" value="'.$desde.'">
                            <input type="hidden" name="hasta" value="'.$hasta.'">
                            <input type="hidden" name="id_cliente" value="'.$id_cliente.'">
                            
                                <button type="submit" class="btn btn-lg btn-warning mt-2 ml-auto mr-auto">Descargar Excel</button>
                            
                        </form>
                    </div>
                </div>';
           
        $clean   = array("\r\n", "\n", "\r","\t","\0","\x0B");
        $table=str_replace($clean, '',$table);
        $table=str_replace('"', "'", $table);

        script('
                $("#resultado_'.$tabla.'").removeClass("d-none");
                $("#resultado_'.$tabla.'").html("'.$table.'");
                $("#ultimas_'.$tabla.'").addClass("d-none");
                $(".datatable").dataTable();
            ');

    }



// }else if(isset($buscar_datos_gestoria)){
}else if(isset($buscar_comercio_ajax)){
// este array es el que devolvemos 
// para todas las busquedas
    $output = array();
    $term = utf8_decode($term);


    $DB->query('select id,razon_social, nombre_comercial from comercios where (razon_social like "%'.$term.'%" or nombre_comercial like "%'.$term.'%") and id_delegacion = '.$_SESSION['id_delegacion'].' order by razon_social ASC limit 10');

    if($DB->rows() == 0){

            $output[] = array(
                            "label" => "No hay Resultados",
                            "category" => "Comercios",
                            "id_comercio" => 0
                            
                        );
    }else{

        while($row=$DB->fetch()){


            $label = utf8_encode($row['nombre_comercial']);
            $label .= $row['razon_social']!=""  ? " (".utf8_encode($row['razon_social']).")" : "";

            $output[] = array(
                            "label" => $label,
                            "category" => "Comercios",
                            "id_comercio" => $row['id']
                            
                        );
        }

    }

    print my_json_encode($output);

// }else if(isset($buscar_comercio_ajax)){ 
}else if(isset($buscar_sector_ajax)){

    $output = array();
    $term = utf8_decode($term);


    $DB->query('select id,sector from sectores where sector like "%'.$term.'%"  order by sector ASC limit 10');

    if($DB->rows() == 0){

            $output[] = array(
                            "label" => "No hay Resultados",
                            "category" => "Sectores",
                            "id_comercio" => 0
                            
                        );
    }else{

        while($row=$DB->fetch()){


            $label = utf8_encode($row['sector']);
            
            $output[] = array(
                            "label" => $label,
                            "category" => "Sectores",
                            "id_sector" => $row['id']
                            
                        );
        }

    }

    print my_json_encode($output);

// }else if(isset($buscar_sector_ajax)){
}else if(isset($buscar_cliente_autocomplete)){

    $output = array();
    $term = utf8_decode($term);
    $span= !isset($no_icon) ? "<span class='fas fa-user'></span> " : '';

    $DB->query('select * from clientes where nombre like "%'.$term.'%" or
                    cif like "%'.$term.'%" or 
                    telefono like  "%'.$term.'%" or 
                    nombre_fiscal like  "%'.$term.'%" or 
                    email like  "%'.$term.'%" or 
                    direccion like  "%'.$term.'%" limit 10');

    // if($DB->rows()==0){

    //     $output[] = array(
    //                     "label" => 'Este cliente no existe',
    //                     "value" => 'Este cliente no existe',
    //                     "category" => $span."Clientes",
    //                     "id_cliente" => '',
    //                     "cif" => '',
    //                     "direccion" => '',
    //                     "telefono" => '', 
    //                     "nombre_fiscal" => '',
    //                     "email" => ''
    //                 );

    // }else{

        while($row=$DB->fetch()){

            $label = utf8_encode($row['nombre']);
            $label .= $row['nombre_fiscal']!='' ? ' ('.utf8_encode($row['nombre_fiscal']).')' : ' ('.($row['telefono']).')';

            $output[] = array(
                            "label" => $label,
                            "value" => utf8_encode($row['nombre']),
                            "category" => $span."Clientes",
                            "id_cliente" => $row['id'],
                            "cif" => $row['cif'],
                            "id_provincia" => $row['id_provincia'],
                            "cp" => $row['cp'],
                            "poblacion" => utf8_encode($row['poblacion']),
                            "direccion" => utf8_encode($row['direccion']),
                            "telefono" => $row['telefono'], 
                            "nombre_fiscal" => utf8_encode($row['nombre_fiscal']),
                            "email" => $row['email'],
                            "descuento" => $row['descuento'],
                            "es_recurrente" => $row['es_recurrente']

                        );

        } // while($row=$DB->fetch()){

   // } // if($DB->rows()==0){....

    print my_json_encode($output);

// }else if(isset($buscar_cliente_autocomplete)){  
}else if(isset($buscar_cupon)){  

    $output = array();

    $DB->query('select * from cupones_descuento where id = '.$term);

    if($DB->rows()==0){

        $output[] = array(
                        "label" => 'Este cupón no Existe',
                        "value" => 'Este cupón no Existe',
                        "category" => "<span class='fas fa-tags'></span> Cupones",
                        
                    );

    }else{

        while($row=$DB->fetch()){

            echo $label;
            $label = (str_pad($row['id'], 4, "0", STR_PAD_LEFT));
            if($row['utilizado'] == 1) $label .= ' - Cupón Cajeado';
            if($row['fecha_caducidad'] < date('Y-m-d')) $label .= ' - Cupón Caducado';


            $output[] = array(
                            "label" => $label,
                            "value" => (str_pad($row['id'], 4, "0", STR_PAD_LEFT)),
                            "category" => "<span class='fas fa-tags'></span> Cupones",
                            "id" => $row['id'],



                        );
        } // while($row=$DB->fetch()){

    } // if($DB->rows()==0){....

    print my_json_encode($output);

// }else if(isset($buscar_cupon)){  
}else if(isset($canjear_cupon)){  

    try {

        $d = $DB->select('*','cupones_descuento','id = '.$id);
        $d = $d[f][0];

        if ($d['utilizado'] == 1){

            throw new Exception("El cupón ya ha sido canjeado anteriormente");
            
        } 

        if ($d['fecha_caducidad'] < date('Y-m-d')){

            throw new Exception("El cupón esta caducado");
            
        } 

        $DB->query('update cupones_descuento set utilizado = 1 where id = '.$id);
        noty('Cupón Canjeado Correctamente');
        script('$("#id_cupon").val("")');

    } catch (Exception $e) {
        
        notify($e->getMessage(),'danger');
    }

// }else if(isset($canjear_cupon)){  
}else if(isset($importar_cvs)){ 

    notify('entre s'.$_FILES['upload']['name']." s".$_FILES['upload']['size'],'success');

    try {
        
        // if (strlen($_FILES['upload']['name'])==0){

        //     throw new Exception("El archivo esta vacio");
            
        // } 

        //ABRIMOS EL ARCHIVO
        $file = fopen($_FILES['upload']['tmp_name'], "r");

        // LO ENCERRAMOS EN LA $csv
        $csv = '';
        while (!feof($file)) {
            $csv.=fread($file,$_FILES['upload']['size']);
        }
        // LO CERRAMOS
        fclose($file);
                        
        //HACEMOS UN ARRAY -> ACESSO CADA UNO DE LAS FILAS
        $array_csv = explode("\n",$csv);


    } catch (Exception $e) {
        
        notify($e->getMessage(),'warning');
    }


// }else if(isset($importar_cvs)){
}else if(isset($registrar_acciones)){

    if(isset($_SESSION['id_usuario'])){

        registrar_accion($id_usuario,$accion,$elemento);

    }
// }else if(isset($registrar_acciones)){ 
}else if(isset($pagar_factura)){ 

    # sacamos contador
    $contador = $DB->select('prefijo,contador','contadores','id=1');
    $nuevo_contador = $contador[f][0]['contador']+1;   

    # pagamos la factura;
    $DB->query('update facturas set estado = 1, fecha_cobrada = "'.now().'", fecha_cobro = "'.date('Y-m-d').'", numero="'.$contador[f][0]['prefijo'].'-'.$nuevo_contador.'", id_via_cobro='.$id_via_cobro.' where id = '.$id_factura);

    #actualizamos contador
    $DB->query('update contadores set contador = '.$nuevo_contador.' where id = 1');

    $id_cli= $DB->select('id_cliente,importe','facturas','id='.$id_factura);

    $id_cliente = $id_cli[f][0]['id_cliente'];

    ### hacemos el movimiento bancario
    $DB->openTable('movimientos_bancarios');
    $DB->ta['fecha']=now();
    $DB->ta['id_usuario']=$_SESSION['id_usuario'];
    $DB->ta['id_delegacion']=4;
    $DB->ta['tipo']=1;
    $DB->ta['clase']='venta';
    $DB->ta['importe']=$id_cli[f][0]['importe'];
    $concept = 'Cobro Factura '.$contador[f][0]['prefijo'].'-'.$nuevo_contador;
    $DB->ta['concepto']=$concept;
    $DB->ta['id_via_cobro']=1;
    $DB->ta['verificado']=1;
    $DB->insert();

    $url_cURL="https://".$_SERVER['HTTP_HOST']."/new_app/functions/ajax.php";
    $post_cURL=array(
                "enviar_email"=>1,
                "tabla_enviar"=>"facturas",
                "pago_procesado"=>1,
                "id_factura"=>$id_factura,
                "id"=>$id_factura,
                "id_cliente"=>$id_cliente,
                "id_web"=>$_SESSION['id_web']);

    // operacion curl.
    $ch = curl_init();  
    curl_setopt($ch, CURLOPT_URL, $url_cURL);  
    curl_setopt($ch, CURLOPT_POSTFIELDS,$post_cURL);
    curl_exec($ch);    
    curl_close($ch);

    sleep(3);
    location(2);

// }else if(isset($pagar_factura)){
}else if(isset($cobrado_masivo)){

    ## HAY QUE REPLANTEAR ESTO Y HACER UN GRUPO CON TODO LO QUE VIENE 
    ## PARA COBRARSE POR FACTURA Y LO QUE NO.
    ## AGRUPARSE POR SEPARADO Y HACER 1 ALBARÁN AGRUPADO Y 1 FACTURA AGRUPADA
    ## ESTUDIAR LA MODIFICACIÓN DE ESOS ALBARANES 
    $fecha_cobrada = $fecha_cobrada == '' ? date('Y-m-d') : formatear_fecha($fecha_cobrada,i,m);

    if($n_cobros > 1 ){

        // tengo que meter todos los albaranes en 1 factura.
        // sumar todos los importes
        // sumar todos los conceptos con sus importes correspondientes
        // hacer un movimiento bancario
        if($n_cobros == $n_facturas){

            ## pasamos cada uno de los albaranes como conbrados
            $ids_query = '';
            $impote_total = 0;
            $conceptos = array();
            // si hacen todas los cobros
            for ($c=0; $c < $n_cobros ; $c++) { 
                


                $field = $hacer_movimientos == 1 ? ' , estado = 1,fecha_cobrada="'.now().'"' : '';
                    # las pasamos a cobradas
                $DB->query('update facturas set agrupado = 2'.$field.' where id = '.$cobrados[$c]);
                
                

                #sacamos el importe
                $importe[$c] = $DB->select('importe,id_delegacion,fecha_cobro','facturas','id = '.$cobrados[$c]);
                $impote_total+=$importe[$c][f][0]['importe'];

                # montamos el concepto de la nueva factura
                $conceptos[$c] = array(
                            'id_factura' => $cobrados[$c], 
                            'importe'=>number_format(($importe[$c][f][0]['importe']/100),2,'.',''),
                            'fecha'=>$importe[$c][f][0]['fecha_cobro']
                        );

                // sacamos el dato del cliente, por que el cobrado masivo solo se puede hacer por cliente concreto
                if($c == 0){

                    $cl = $DB->inner_join('c.nombre,c.id as id_cliente','facturas f inner join clientes c','f.id_cliente = c.id where f.id = '.$cobrados[$c]);
                }
               
            } // for ($c=0; $c < $n_cobros ; $c++) { 

            ## metemos la nueva factura
            $DB->opentable('facturas');
            $DB->ta['importe'] = $impote_total;
            $DB->ta['fecha_cobro'] = $fecha_cobrada;

            if($hacer_movimientos == 1){
                $DB->ta['estado'] = 1;
            }

            $DB->ta['id_cliente'] = $cl[f][0]['id_cliente'];
            $DB->ta['id_delegacion'] = $importe[0][f][0]['id_delegacion'];
            $DB->ta['id_usuario'] = $_SESSION['id_usuario'];
            $DB->ta['id_via_cobro'] = 1;
            $DB->ta['fecha_cobrada'] = $fecha_cobrada.' '.date('H:i:s');

            ## sacamos el contador y lo actualizamos
            $n = $DB2->select('contador,prefijo','contadores','id=1');
            $cont = $n[f][0]['contador']+1;
            $DB2->query('update contadores set contador = '.$cont.' where id = 1');

            $DB->ta['numero'] = $n[f][0]['prefijo'].'-'.$cont;
            $DB->ta['concepto'] = json_encode($conceptos);
            $DB->ta['agrupado'] = 1;
            $DB->insert();

            if($hacer_movimientos == 1){

                ### hacemos el movimiento bancario
                $DB->openTable('movimientos_bancarios');
                $DB->ta['fecha']=$fecha_cobrada.' '.date('H:i:s');
                $DB->ta['id_usuario']=$_SESSION['id_usuario'];

                $DB->ta['id_delegacion']=4;

                $DB->ta['tipo']=1;
                $DB->ta['clase']='venta';
                $DB->ta['importe']=$impote_total;
                $concept = 'Cobro Facturado y Agrupado '.utf8_encode($cl[f][0]['nombre']);
                $concept .= $desde!='' && $hasta!='' ? ' ('.$desde.' - '.$hasta.')' : '';
                $DB->ta['concepto']=$concept;
                $DB->ta['id_via_cobro']=1;
                $DB->ta['verificado']=1;
                $DB->insert();

            }

            sleep(2);
            alert('Gestión Realizada');
            location(2);

        }else{ // if($n_cobros == $n_facturas){

            // aqui venimos cuando no hay que facturar todo 
            // por lo que hay que agrupar los albaranes por un lado y la factura por otro

            if($n_facturas>0){

                ## pasamos cada uno de los albaranes como conbrados
                $impote_total = 0;
                $conceptos = array();
                // si hacen todas los cobros
                for ($c=0; $c < $n_facturas ; $c++) { 
                    
                    // borramos los indices de los cobrados que van a aser facturados
                    $ind[$c] = array_search($facturados[$c],$cobrados,false);
                    unset($cobrados[$ind[$c]]);

                    $field = $hacer_movimientos == 1 ? ' , estado = 1,fecha_cobrada="'.now().'"' : '';
                    # las pasamos a cobradas
                    $DB->query('update facturas set agrupado = 2'.$field.' where id = '.$facturados[$c]);
                    

                    #sacamos el importe
                    $importe[$c] = $DB->select('importe,id_delegacion,fecha_cobro','facturas','id = '.$facturados[$c]);
                    $impote_total+=$importe[$c][f][0]['importe'];

                    # montamos el concepto de la nueva factura
                    $conceptos[$c] = array(
                                'id_factura' => $facturados[$c], 
                                'importe'=>number_format(($importe[$c][f][0]['importe']/100),2,'.',''),
                                'fecha'=>$importe[$c][f][0]['fecha_cobro']
                            );

                    // sacamos el dato del cliente, por que el cobrado masivo solo se puede hacer por cliente concreto
                    if($c == 0){

                        $cl = $DB->inner_join('c.nombre,c.id as id_cliente','facturas f inner join clientes c','f.id_cliente = c.id where f.id = '.$facturados[$c]);
                    }
                   
                } // for ($c=0; $c < $n_cobros ; $c++) { 

                ## metemos la nueva factura
                $DB->opentable('facturas');
                $DB->ta['importe'] = $impote_total;
                $DB->ta['fecha_cobro'] = $fecha_cobrada;

                if($hacer_movimientos == 1){
                    $DB->ta['estado'] = 1;
                }

                $DB->ta['id_cliente'] = $cl[f][0]['id_cliente'];
                $DB->ta['id_delegacion'] = $importe[0][f][0]['id_delegacion'];
                $DB->ta['id_usuario'] = $_SESSION['id_usuario'];
                $DB->ta['id_via_cobro'] = 1;
                $DB->ta['fecha_cobrada'] = $fecha_cobrada.' '.date('H:i:s');

                ## sacamos el contador y lo actualizamos
                $n = $DB2->select('contador,prefijo','contadores','id=1');
                $cont = $n[f][0]['contador']+1;
                $DB2->query('update contadores set contador = '.$cont.' where id = 1');

                $DB->ta['numero'] = $n[f][0]['prefijo'].'-'.$cont;
                $DB->ta['concepto'] = json_encode($conceptos);
                $DB->ta['agrupado'] = 1;
                $DB->insert();

                if($hacer_movimientos == 1){
                    ### hacemos el movimiento bancario
                    $DB->openTable('movimientos_bancarios');
                    $DB->ta['fecha']=$fecha_cobrada.' '.date('H:i:s');
                    $DB->ta['id_usuario']=$_SESSION['id_usuario'];
                    $DB->ta['id_delegacion']=4;
                    $DB->ta['tipo']=1;
                    $DB->ta['clase']='venta';
                    $DB->ta['importe']=$impote_total;
                    $concept = 'Cobro Facturado y Agrupado '.utf8_encode($cl[f][0]['nombre']);
                    $concept .= $desde!='' && $hasta!='' ? ' ('.$desde.' - '.$hasta.')' : '';
                    $DB->ta['concepto']=$concept;
                    $DB->ta['id_via_cobro']=1;
                    $DB->ta['verificado']=1;
                    $DB->insert();
                }

            } // if($n_facturas>0){


            // repetimos la operacion con los solo cobrados  
            $impote_total = 0;
            $conceptos = array();
            // si hacen todas los cobros
            for ($c=0; $c < $n_cobros ; $c++) { 

                ## controlamos los indices facturados anteriomente
                if(isset($cobrados[$c])){
                
                    $field = $hacer_movimientos == 1 ? ' , estado = 1,fecha_cobrada="'.now().'"' : '';
                    # las pasamos a cobradas
                    $DB->query('update facturas set agrupado = 2'.$field.' where id = '.$cobrados[$c]);

                    #sacamos el importe
                    $importe[$c] = $DB->select('importe,id_delegacion,fecha_cobro','facturas','id = '.$cobrados[$c]);
                    $impote_total+=$importe[$c][f][0]['importe'];

                    # montamos el concepto de la nueva factura
                    $conceptos[$c] = array(
                                'id_factura' => $cobrados[$c], 
                                'importe'=>number_format(($importe[$c][f][0]['importe']/100),2,'.',''),
                                'fecha'=>$importe[$c][f][0]['fecha_cobro']
                            );

                    // sacamos el dato del cliente, por que el cobrado masivo solo se puede hacer por cliente concreto
                    if($c == 0){

                        $cl = $DB->inner_join('c.nombre,c.id as id_cliente','facturas f inner join clientes c','f.id_cliente = c.id where f.id = '.$cobrados[$c]);
                    }

                }// if(isset($cobrados[$c])){
                   
            } // for ($c=0; $c < $n_cobros ; $c++) { 

            ## metemos la nueva factura
            $DB->opentable('facturas');
            $DB->ta['importe'] = $impote_total;
            $DB->ta['fecha_cobro'] = $fecha_cobrada;
            if($hacer_movimientos == 1){
                $DB->ta['estado'] = 1;
            }
            $DB->ta['id_cliente'] = $cl[f][0]['id_cliente'];
            $DB->ta['id_delegacion'] = $importe[0][f][0]['id_delegacion'];
            $DB->ta['id_usuario'] = $_SESSION['id_usuario'];
            $DB->ta['id_via_cobro'] = 1;
            $DB->ta['fecha_cobrada'] = $fecha_cobrada.' '.date('H:i:s');
            $DB->ta['concepto'] = json_encode($conceptos);
            $DB->ta['agrupado'] = 1;
            $DB->insert();

            if($hacer_movimientos == 1){
                ### hacemos el movimiento bancario
                $DB->openTable('movimientos_bancarios');
                $DB->ta['fecha']=$fecha_cobrada.' '.date('H:i:s');
                $DB->ta['id_usuario']=$_SESSION['id_usuario'];
                $DB->ta['id_delegacion']=4;
                $DB->ta['tipo']=1;
                $DB->ta['clase']='venta';
                $DB->ta['importe']=$impote_total;
                $concept = 'Cobro Agrupado '.utf8_encode($cl[f][0]['nombre']);
                $concept .= $desde!='' && $hasta!='' ? ' ('.$desde.' - '.$hasta.')' : '';
                $DB->ta['concepto']=$concept;
                $DB->ta['id_via_cobro']=1;
                $DB->ta['verificado']=1;
                $DB->insert();
            }

            sleep(1);
            alert('Gestión Realizada');
            location(2);

        } // if($n_cobros == $n_facturas){ ....

    }else{ // if($n_cobros > 1 ){


        $ids_query = '';
        // si hacen todas los cobros
        for ($c=0; $c < $n_cobros ; $c++) { 

            $field = $hacer_movimientos == 1 ? ' , estado = 1,fecha_cobrada="'.$fecha_cobrada.' '.date('H:i:s').'"' : '';

            $grup = $DB->select('agrupado','facturas',' id = '.$cobrados[$c]);
            $arg = $grup[f][0]['agrupado'] == 1 ? 1 : 2;
            # las pasamos a cobradas
            $DB->query('update facturas set agrupado = '.$arg.$field.' where id = '.$cobrados[$c]);

            echo 'update facturas set agrupado = '.$arg.$field.' where id = '.$cobrados[$c];


            $ids_query .= $c == 0 ? '('.$cobrados[$c] : ','.$cobrados[$c];
            $ids_query .= $c == ($n_cobros-1) ? ')' : '';


            // sacamos el dato del cliente, por que el cobrado masivo solo se puede hacer por cliente concreto
            if($c == 0){

                $cl = $DB->inner_join('c.nombre','facturas f inner join clientes c','f.id_cliente = c.id where f.id = '.$cobrados[$c]);
            }
           
        }

        if($hacer_movimientos == 1){
            // vamos a generar el movimiento_bancario
            $importe = $DB->select('sum(importe) as total','facturas','id in '.$ids_query);

            $DB->openTable('movimientos_bancarios');
            $DB->ta['fecha']=$fecha_cobrada.' '.date('H:i:s');
            $DB->ta['id_usuario']=$_SESSION['id_usuario'];
            $DB->ta['id_delegacion']=4;
            $DB->ta['tipo']=1;
            $DB->ta['clase']='venta';
            $DB->ta['importe']=$importe[f][0]['total'];
            $concept = 'Cobro '.utf8_encode($cl[f][0]['nombre']);
            $concept .= $desde!='' && $hasta!='' ? ' ('.$desde.' - '.$hasta.')' : '';
            $DB->ta['concepto']=$concept;
            $DB->ta['id_via_cobro']=1;
            $DB->ta['verificado']=1;
            $DB->insert();
        }

        // si viene mas de una factura se hacen todas
        // ####### AQUI ES DONDE TENGO QUE HACER LAS FACTURAS AGRUPADAS.
        if($n_facturas > 0){

            $n = $DB2->select('contador,prefijo','contadores','id=1');
            $cont = $n[f][0]['contador']+1;

            for ($f=0; $f < $n_facturas ; $f++) { 
                
                $DB->query('update facturas set numero = "'.$n[f][0]['prefijo'].'-'.$cont.'" where id='.$facturados[$f]);
                $cont++;

            }

            $DB->query('update contadores set contador = '.$cont.' where id = 1');

        }

        sleep(1);
        alert('Gestión Realizada');
        // if(!isset($_SESSION['ip_admin'])){

            location(2);

        // }

    } // if($n_cobros > 1 ){.....


// }else if(isset($cobrado_masivo)){  
}else if(isset($password_actual)){ 

    $d = $DB->select('password','comercios','id = '.$id_comercio_pass);


    if(!password_verify($password_actual,$d[f][0]['password'])){

        notify('La contraseña es incorrecta',"warning");

    }else{

        notify('La contraseña es Correcta',"success");
        script('$("#nuevo_password").removeClass("d-none")');

    }

// }else if(isset($password_actual)){ 
}else if(isset($edita_password)){ 


    $password = password_hash($edita_password, PASSWORD_DEFAULT);
    $DB->query('update comercios set comentario = "'.$edita_password.'", password = "'.$password.'" where id='. $id_comercio_pass);
    notify('Contraseña Modificada',"success");

// }else if(isset($edita_password)){   
}else if(isset($filtro_year_contabilidad)){  

    $meses = array(
        'Ene'=>"01",
        'Feb'=>"02",
        'Mar'=>"03",
        'Abr'=>"04",
        'May'=>"05",
        'Jun'=>"06",
        'Jul'=>"07",
        'Ago'=>"08",
        'Sep'=>"09",
        'Oct'=>10,
        'Nov'=>11,
        'Dic'=>12,
    );

    $ing = '';
                        $gas = '';
                        $ben = '';
                        $comp = '';


                        $ingreso_total=0;
                        $gastos_total=0;
                        $beneficio_total=0;
                        $compras_total=0;
                        $filtro_tienda = $id_delegacion == '0' ? '' : ' and id_delegacion = '.$id_delegacion;
                        foreach ($meses as $key => $value) {

                            # sacamos los ingresos
                            $DB->query('select sum(importe) as ingresos from movimientos_bancarios where fecha BETWEEN "'.$filtro_year_contabilidad.'-'.$value.'-01 00:00:00" AND "'.$filtro_year_contabilidad.'-'.$value.'-31 23:59:59" and tipo = 1'.$filtro_tienda);


                            ${$key.'_ing'}=$DB->fetch();

                            ${$key.'_ing'}['ingresos'] = ${$key.'_ing'}['ingresos'] != '' ? ${$key.'_ing'}['ingresos'] : 0;
                            $ing .= number_format((${$key.'_ing'}['ingresos']/100),2,'.','');
                            $ing .= $value!=12 ? ',' : '';
                            $ingreso_total += ${$key.'_ing'}['ingresos'];

                                

                            # sacamos los gastos
                            $DB->query('select sum(importe) as gastos from movimientos_bancarios where fecha BETWEEN "'.$filtro_year_contabilidad.'-'.$value.'-01 00:00:00" AND "'.$filtro_year_contabilidad.'-'.$value.'-31 23:59:59" and tipo = 0 and clase like "gasto"'.$filtro_tienda);

                            ${$key.'_gast'}=$DB->fetch();

                            ${$key.'_gast'}['gastos'] = ${$key.'_gast'}['gastos'] != '' ? ${$key.'_gast'}['gastos'] : 0;

                            $gas .= number_format((${$key.'_gast'}['gastos']/100),2,'.','');
                            $gas .= $value!=12 ? ',' : '';
                            $gastos_total += ${$key.'_gast'}['gastos'];

                            # sacamos las compras
                            $DB->query('select sum(importe) as compras from movimientos_bancarios where fecha BETWEEN "'.$filtro_year_contabilidad.'-'.$value.'-01 00:00:00" AND "'.$filtro_year_contabilidad.'-'.$value.'-31 23:59:59" and tipo = 0 and clase like "compra"'.$filtro_tienda);

                            ${$key.'_comp'}=$DB->fetch();

                            ${$key.'_comp'}['compras'] = ${$key.'_comp'}['compras'] != '' ? ${$key.'_comp'}['compras'] : 0;

                            $comp .= number_format((${$key.'_comp'}['compras']/100),2,'.','');
                            $comp .= $value!=12 ? ',' : '';
                            $compras_total += ${$key.'_comp'}['compras'];


                            # sacamos el beneficio
                            ${$key.'_ben'} = (int)${$key.'_ing'}['ingresos']-(int)${$key.'_gast'}['gastos']-(int)${$key.'_comp'}['compras'];
                            $ben .= number_format(((int)${$key.'_ben'}/100),2,'.','');
                            $ben .= $value!=12 ? ',' : '';
                            $beneficio_total += ${$key.'_ben'};
                            // echo "{ y: '".$key."', a: ".(${$key}['total']/100).", b:".(${$key}['neto_total']/100).", c:".($beneficio/100)." , d : ".${$key}['reservas']."}";
                            // if($value!=12){
                            //     echo ",";
                            // }

                        } // foreach ($meses as $key => $value) {

    script('        
        $("#one_chart").addClass("d-none");
        $("#myChartLegend").addClass("d-none");


        var htmlLegendsChart = document.getElementById("htmlLegendsChart-two").getContext("2d");
        // Chart with HTML Legends

        var gradientStroke = htmlLegendsChart.createLinearGradient(500, 0, 100, 0);
        gradientStroke.addColorStop(0, "#177dff");
        gradientStroke.addColorStop(1, "#80b6f4");

        var gradientFill = htmlLegendsChart.createLinearGradient(500, 0, 100, 0);
        gradientFill.addColorStop(0, "rgba(23, 125, 255, 0.7)");
        gradientFill.addColorStop(1, "rgba(128, 182, 244, 0.3)");

        var gradientStroke2 = htmlLegendsChart.createLinearGradient(500, 0, 100, 0);
        gradientStroke2.addColorStop(0, "#f3545d");
        gradientStroke2.addColorStop(1, "#ff8990");

        var gradientFill2 = htmlLegendsChart.createLinearGradient(500, 0, 100, 0);
        gradientFill2.addColorStop(0, "rgba(243, 84, 93, 0.7)");
        gradientFill2.addColorStop(1, "rgba(255, 137, 144, 0.3)");

        var gradientStroke3 = htmlLegendsChart.createLinearGradient(500, 0, 100, 0);
        gradientStroke3.addColorStop(0, "#fdaf4b");
        gradientStroke3.addColorStop(1, "#ffc478");

        var gradientFill3 = htmlLegendsChart.createLinearGradient(500, 0, 100, 0);
        gradientFill3.addColorStop(0, "rgba(253, 175, 75, 0.7)");
        gradientFill3.addColorStop(1, "rgba(255, 196, 120, 0.3)");

        var gradientStroke4 = htmlLegendsChart.createLinearGradient(500, 0, 100, 0);
        gradientStroke3.addColorStop(0, "#256D3C");
        gradientStroke3.addColorStop(1, "#3CBB65");

        var gradientFill4 = htmlLegendsChart.createLinearGradient(500, 0, 100, 0);
        gradientFill4.addColorStop(0, "rgba(43, 128, 70, 0.7)");
        gradientFill4.addColorStop(1, "rgba(60, 187, 101, 0.3)");

        $("#ing_final").html("'.number_format(((int)$ingreso_total/100),2,'.','').'");
        $("#gast_final").html("'.number_format(((int)$gastos_total/100),2,'.','').'");
        $("#ben_final").html("'.number_format(((int)$beneficio_total/100),2,'.','').'");
        $("#comp_final").html("'.number_format(((int)$compras_total/100),2,'.','').'");
        $("#span_data_year").html("'.$filtro_year_contabilidad.'");

                var myHtmlLegendsChart = new Chart(htmlLegendsChart, {
            type: "line",
            data: {
                labels: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
                datasets: [ {
                    label: "Compras",
                    borderColor: gradientStroke4,
                    pointBackgroundColor: gradientStroke4,
                    pointRadius: 0,
                    backgroundColor: gradientFill4,
                    legendColor: "#256D3C",
                    fill: true,
                    borderWidth: 1,

                    data: ['.$comp.']

                },{
                    label: "Gastos",
                    borderColor: gradientStroke2,
                    pointBackgroundColor: gradientStroke2,
                    pointRadius: 0,
                    backgroundColor: gradientFill2,
                    legendColor: "#f3545d",
                    fill: true,
                    borderWidth: 1,
                    data: ['.$gas.']

                },{
                    label: "Beneficios",
                    borderColor: gradientStroke3,
                    pointBackgroundColor: gradientStroke3,
                    pointRadius: 0,
                    backgroundColor: gradientFill3,
                    legendColor: "#fdaf4b",
                    fill: true,
                    borderWidth: 1,

                    data: ['.$ben.']

                },{
                    label: "Ingresos",
                    borderColor: gradientStroke,
                    pointBackgroundColor: gradientStroke,
                    pointRadius: 0,
                    backgroundColor: gradientFill,
                    legendColor: "#177dff",
                    fill: true,
                    borderWidth: 1,

                    data: ['.$ing.']

                }]
            },
            options : {
                responsive: true, 
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                tooltips: {
                    bodySpacing: 4,
                    mode:"nearest",
                    intersect: 0,
                    position:"nearest",
                    xPadding:10,
                    yPadding:10,
                    caretPadding:10
                },
                layout:{
                    padding:{left:15,right:15,top:15,bottom:15}
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            fontColor: "rgba(0,0,0,0.5)",
                            fontStyle: "500",
                            beginAtZero: false,
                            maxTicksLimit: 5,
                            padding: 20
                        },
                        gridLines: {
                            drawTicks: false,
                            display: false
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            zeroLineColor: "transparent"
                        },
                        ticks: {
                            padding: 20,
                            fontColor: "rgba(0,0,0,0.5)",
                            fontStyle: "500"
                        }
                    }]
                }, 
                legendCallback: function(chart) { 
                    var text = []; 
                    text.push(\'<ul class="\' + chart.id + \'-legend html-legend">\'); 
                    for (var i = 0; i < chart.data.datasets.length; i++) { 
                        text.push(\'<li><span style="background-color:\' + chart.data.datasets[i].legendColor + \'"></span>\'); 
                        if (chart.data.datasets[i].label) { 
                            text.push(chart.data.datasets[i].label); 
                        } 
                        text.push("</li>"); 
                    } 
                    text.push("</ul>"); 
                    return text.join(""); 
                }  
            }
        });

        var myLegendContainer = document.getElementById("myChartLegend-two");

        // generate HTML legend
        myLegendContainer.innerHTML = myHtmlLegendsChart.generateLegend();

        // bind onClick event to all LI-tags of the legend
        var legendItems = myLegendContainer.getElementsByTagName("li");
        for (var i = 0; i < legendItems.length; i += 1) {
            legendItems[i].addEventListener("click", legendClickCallback, false);
        }


    ');


// }else if(isset($filtro_year_contabilidad)){ 
}else if(isset($quitar_pago)){   

    $DB->query('update facturas set estado = 0 where id = '.$id_factura); 

    $df = $DB->inner_join('f.importe,f.fecha_cobro,c.nombre','facturas f inner join clientes c','f.id_cliente = c.id where f.id = '.$id_factura);
    $f = $df[f][0];

    $DB->openTable('movimientos_bancarios');
    $DB->ta['fecha']=now();
    $DB->ta['id_usuario']=$_SESSION['id_usuario'];
    $DB->ta['id_delegacion']=$_SESSION['id_delegacion'];
    $DB->ta['tipo']=0;
    $DB->ta['clase']='gasto';
    $DB->ta['importe']=$f['importe'];
    $DB->ta['concepto']='Devolución '.$f['nombre'].' albarán '.$id_factura;
    $DB->ta['id_via_cobro']=1;
    $DB->ta['verificado']=1;
    $DB->insert();

    alert('Modificacion Realizada');
    location(2);
    
// }else if(isset($quitar_pago)){  
}else if(isset($controlador_pills)){   

    $_SESSION[$session] = $href;
    
// }else if(isset($controlador_pills)){  
 }else if(isset($concepto_personalizado)){   

    if($concepto_producto > 0){

        $dp = $DB->select('nombre_producto,pvp,caracteristicas_producto','productos','id='.$concepto_producto);
        $pvp = $dp[f][0]['pvp'];
        $nombre = utf8_encode($dp[f][0]['nombre_producto']);

    }else{

        $pvp = $pvp_personalizado*100;
        $nombre = $concepto_personalizado;

    }

    $importe += $pvp;

    script('$("#div_conceptos").append(\'<div class="alert alert-info alert-dismissible fade show mt-3" role="alert" id="alert_'.time().'"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'.$nombre.' - '.number_format(($pvp/100),2,'.','').'&euro;<input type="hidden" name="concep_sum" pvp="'.$pvp.'" nombre="'.($nombre).'"></div>\');
        $(\'[name="importe"]\').val('.$importe.'); 
        $("#pvp_span").html("'.number_format(($importe/100),2,'.','').'");
        $(\'[name="concepto_producto"] option\').prop("selected",false);
        $(\'[name="concepto_personalizado"]\').val(""); 
        $(\'[name="pvp_personalizado"]\').val(""); 

        $("#alert_'.time().'").on("close.bs.alert", function () {
            var pvp = $(this).children(\'[name="concep_sum"]\').attr("pvp");
            var importe = $("#importe_final").val();
            var final = importe-pvp;
            $(\'[name="importe"]\').val(final); 
            var f = final/100;
            var s = String(f);
            if(s.length == 2){ s = s+".00"; }
            $("#pvp_span").html(s);
        })');


// }else if(isset($concepto_personalizado)){ 
}else if(isset($precio_final_tarta)){  

        

// select_tarta: personalizada
// tamano_galletas: 11
// tamano_pastelon: 13
// forma_gominolas: Cuadrada
// tamano_gominolas: 10
// tamano_personalizada: 11
// capa_inferior_personalizada: Crema
// capa_media_personalizada: Crema
// capa_superior_personalizada: Crema
// borde_exterior_personalizada: Merengue
// incluir_foto: No
// incluir_mensaje : 
// precio_final_tarta: 11.00

        
    if($select_tarta!="0"){


        $eraser = '_'.$select_tarta;

        ## aqui tengo que quitar los que no pertenzcna, 

        $txt = 'Tarta : '.$select_tarta.'<br> Tamaño : '.$porciones.'';
        $fieds = 'select_tarta="'.$select_tarta.'" porciones="'.$porciones.'" nombre="Tarta '.ucwords($select_tarta).'" pvp="'.number_format(($precio_final_tarta),2,'.','').'"';
        foreach ($_POST as $key => $value) {
            
            if(strpos($key,$eraser) !== false){

                if(strpos($key,'tamano') === false){

                    $txt.= '<br>'.str_replace(['_',$select_tarta],' ',ucwords($key)).': '.$value;
                    $fieds .=str_replace('_'.$select_tarta,'',ucwords($key)).'="'.$value.'" ';
                }
            }
        } // foreach ($_POST as $key => $value) {

        $comentarios = $comentarios=='' ? 'No' : str_replace('"','&quot;',$comentarios);
        $incluir_mensaje = $incluir_mensaje=='' ? 'No' : str_replace('"','&quot;',$incluir_mensaje);
        $txt .= '<br>Lleva Foto: '.$incluir_foto.'<br>Incluir mensaje: '.$incluir_mensaje.' <br>Comentarios: '.$comentarios.'<br>Precio Final: '.number_format(($precio_final_tarta),2,'.','').'&euro;';

        $fieds .='incluir_foto="'.$incluir_foto.'" precio_final="'.number_format(($precio_final_tarta),2,'.','').'" incluir_mensaje="'.$incluir_mensaje.'" predeterminada="0" comentarios="'.$comentarios.'" ';

        $importe_final += $precio_final_tarta;

        script('$("#carrito_div").append(\'<div class="alert alert-info alert-dismissible fade show mt-3" role="alert" id="alert_'.time().'"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'.$txt.'<input type="hidden" name="concep_sum" tipo="tarta" '.$fieds.' ></div>\');
            
            $(\'[name="select_tarta"] option[value="0"]\').prop("selected",true);
            $(\'[name="incluir_foto"] option[value="No"]\').prop("selected",true);
            $(\'[name="select_tarta"]\').focus();
            $(\'[name="comentarios"]\').val(""); 
            $(\'[name="incluir_mensaje"]\').val(""); 
            $(\'[name="porciones"]\').val(1); 
            $(".personalizada, .galletas, .pastelon, .gominolas").addClass("d-none");
            $(\'[name="importe_final"]\').val('.$importe_final.'); 
            $(\'[name="pvp_span"]\').html("'.number_format(($importe_final),2,'.','').'");
                

            $("#alert_'.time().'").on("close.bs.alert", function () {
                var pvp = $(this).children(\'[name="concep_sum"]\').attr("precio_final");
                var importe = $(\'[name="importe_final"]\').val();
                var final = importe-pvp;
                //var f = final/100;
                var s = String(final);
                if(s.length == 2){ s = s+".00"; }
                $(\'[name="importe_final"]\').val(s); 
                $(\'[name="pvp_span"]\').html(s);
                
            });

            ');

    }else if($select_tarta_predeterminada!="0"){

             
        ## aqui tengo que quitar los que no pertenzcna, 

        $txt = 'Tarta : '.$nombre_predeterminada;
        $fieds = 'select_tarta="'.$nombre_predeterminada.'" predeterminada="1" porciones="predeterminadas" nombre="Tarta '.ucwords(trim($nombre_predeterminada)).'" pvp="'.number_format(($precio_final_tarta),2,'.','').'"';


        foreach ($_POST as $key => $value) {
            
            if(strpos($key,$eraser) !== false){

                if(strpos($key,'tamano') === false){

                    $txt.= '<br>'.str_replace(['_',$select_tarta],' ',ucwords($key)).': '.$value;
                    $fieds .=str_replace('_'.$select_tarta,'',ucwords($key)).'="'.$value.'" ';
                }
            }
        } // foreach ($_POST as $key => $value) {

        
        $comentarios = $comentarios=='' ? 'No' : str_replace('"','&quot;',$comentarios);
        $incluir_mensaje = $incluir_mensaje=='' ? 'No' : str_replace('"','&quot;',$incluir_mensaje);
        $txt .= '<br>Lleva Foto: '.$incluir_foto.'<br>Incluir mensaje: '.$incluir_mensaje.' <br>Comentarios: '.$comentarios.' <br>Precio Final: '.number_format(($precio_final_tarta),2,'.','').'&euro;';

        $fieds .='incluir_foto="'.$incluir_foto.'" precio_final="'.number_format(($precio_final_tarta),2,'.','').'" incluir_mensaje="'.$incluir_mensaje.'" comentarios="'.$comentarios.'" ';

        $importe_final += $precio_final_tarta;

        script('$("#carrito_div").append(\'<div class="alert alert-info alert-dismissible fade show mt-3" role="alert" id="alert_'.time().'"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'.$txt.'<input type="hidden" name="concep_sum" tipo="tarta" '.$fieds.' ></div>\');
            
            $(\'[name="select_tarta"] option[value="0"]\').prop("selected",true);
            $(\'[name="select_tarta_predeterminada"] option[value="0"]\').prop("selected",true);
            $(\'[name="incluir_foto"] option[value="No"]\').prop("selected",true);
            $(\'[name="select_tarta"]\').focus();
            $(\'[name="comentarios"]\').val(""); 
            $(\'[name="incluir_mensaje"]\').val(""); 
            $(".personalizada, .galletas, .pastelon, .gominolas").addClass("d-none");
            $(\'[name="importe_final"]\').val('.$importe_final.'); 
            $(\'[name="pvp_span"]\').html("'.number_format(($importe_final),2,'.','').'");
                

            $("#alert_'.time().'").on("close.bs.alert", function () {
                var pvp = $(this).children(\'[name="concep_sum"]\').attr("precio_final");
                var importe = $(\'[name="importe_final"]\').val();
                var final = importe-pvp;
                //var f = final/100;
                var s = String(final);
                if(s.length == 2){ s = s+".00"; }
                $(\'[name="importe_final"]\').val(s); 
                $(\'[name="pvp_span"]\').html(s);
                
            });

            ');

    } // if($select_tarta!=0){.......

    notify('Tarta añadida al carro','success');


// }else if(isset($precio_final_tarta)){  
}else if(isset($modificar_linea_tartas)){  


    $nombre = $tipo == 'predeterminada' ? 'Tarta '.$nombre : 'Tarta '.$tipo;
    $tarta = $tipo == 'predeterminada' ? $nombre : $tipo;
    $porciones = $tipo == 'predeterminada' ? 'predeterminadas' : $porciones;
    $predeterminada = $tipo == 'predeterminada' ? 1 : 0;


    $extras = '';
    if($tipo == 'personalizada'){

        $extras = '
                    $("#concepto_sum_'.$kp.'").attr("capa_inferior","'.$capa_inferior_personalizada.'");
                    $("#concepto_sum_'.$kp.'").attr("capa_media","'.$capa_media_personalizada.'");
                    $("#concepto_sum_'.$kp.'").attr("capa_superior","'.$capa_superior_personalizada.'");
                    $("#concepto_sum_'.$kp.'").attr("borde_exterior","'.$borde_exterior_personalizada.'");
                ';

    }else if($tipo == 'gominolas'){

                $extras = '
                    $("#concepto_sum_'.$kp.'").attr("forma","'.$forma_gominolas.'");
                ';

    }



    # calcular nuevo pvp del carrito
    $importe_final -= $pvp_linea;
    $importe_final += $pvp;
    if($incluir_foto == 'Si'){

        $importe_final += 4;
        $pvp += 4;
    } 

    $comentarios = $comentarios=='' ? 'No' : str_replace('"','&quot;',$comentarios);
    $incluir_mensaje = $incluir_mensaje=='' ? 'No' : str_replace('"','&quot;',$incluir_mensaje);

    $nuevo_cartel =  ($nombre).'<br>Pvp : '.number_format(($pvp),2,'.','').'<br>Porciones : '.$porciones.'<br>Lleva Foto: '.$incluir_foto.'<br>Incluir mensaje: '.$incluir_mensaje.' <br>Comentarios: '.$comentarios;


    # generar el nuevo div
    if($tipo == 'personalizada'){

        $nuevo_cartel .= '<br>Capa Inferior : '.$capa_inferior_personalizada.' <br>Capa Media : '.$capa_media_personalizada.' <br>Capa Superior : '.$capa_superior_personalizada.' <br>Borde Exterior : '.$borde_exterior_personalizada;
        // 

    }


    # generar el nuevo fieldset del concepto sum y modificar los atributos del mismo.
    script('
            $("#div_info_'.$kp.'").html("'.$nuevo_cartel.'");
            $("#concepto_sum_'.$kp.'").attr("nombre","'.$nombre.'");
            $("#concepto_sum_'.$kp.'").attr("pvp","'.number_format(($pvp),2,'.','').'");
            $("#concepto_sum_'.$kp.'").attr("tarta","'.$tarta.'");
            $("#concepto_sum_'.$kp.'").attr("incluir_foto","'.$incluir_foto.'");
            $("#concepto_sum_'.$kp.'").attr("incluir_mensaje","'.$incluir_mensaje.'");
            $("#concepto_sum_'.$kp.'").attr("comentarios","'.$comentarios.'");
            $("#concepto_sum_'.$kp.'").attr("predeterminada","'.$predeterminada.'");
            $("#concepto_sum_'.$kp.'").attr("porciones","'.$porciones.'");
            '.$extras.'
            $(\'[name="importe_final"]\').val("'.number_format(($importe_final),2,'.','').'"); 
            $(\'[name="pvp_span"]\').html("'.number_format(($importe_final),2,'.','').'");
        ');

    noty('Linea modificada','success');

// }else if(isset($modificar_linea_tartas)){      
}else if(isset($conceptos_personalizado)){ 

    if($concepto_producto > 0){

        $dp = $DB->select('nombre_producto,pvp,caracteristicas_producto','productos','id='.$concepto_producto);
        $pvp = ($dp[f][0]['pvp']/100)*$unidades_producto;
        $nombre = utf8_encode($dp[f][0]['nombre_producto']);

    }else{

        $pvp = $pvp_personalizado;
        $nombre = $conceptos_personalizado;

    }

    $importe_final += $pvp;
    $comentarios_prod = isset($comentarios_pastelinos) && $comentarios_pastelinos!='' ? $comentarios_pastelinos : '';
    $com = isset($comentarios_pastelinos) && $comentarios_pastelinos!='' ? '<br>Comentarios: '.$comentarios_pastelinos : '';

    script('$("#carrito_div").append(\'<div class="alert alert-info alert-dismissible fade show mt-3" role="alert" id="alert_'.time().'"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'.$nombre.' <br>'.$unidades_producto.' Unidades <br>'.number_format(($pvp),2,'.','').'&euro;'.$com.'<input type="hidden" name="concep_sum" tipo="producto" nombre="'.($nombre).'" cantidad="'.$unidades_producto.'" pvp="'.number_format($pvp,2,'.','').'" select_tarta="0" comentarios="'.$comentarios_prod.'"></div>\');
        $(\'[name="importe_final"]\').val('.number_format(($importe_final),2,'.','').'); 
        $(\'[name="pvp_span"]\').html("'.number_format(($importe_final),2,'.','').'");
        $(\'[name="concepto_producto"]\').selectpicker("val",0);
        $(\'[name="unidades_producto"]\').val("");
        $(\'[name="comentarios_pastelinos"]\').val("");
        $(\'[name="comentarios_pastelinos"]\').html("");
        $(\'[name="conceptos_personalizado"]\').val(""); 
        $(\'[name="pvp_personalizado"]\').val(""); 

        $("#alert_'.time().'").on("close.bs.alert", function () {
            var pvp = $(this).children(\'[name="concep_sum"]\').attr("pvp");
            var importe = $(\'[name="importe_final"]\').val();
            var final = importe-pvp;
            // var f = final/100;
            var s = String(final);
            if(s.length == 2){ s = s+".00"; }
            $(\'[name="pvp_span"]\').html(s);
            $(\'[name="importe_final"]\').val(s); 
        })');

    notify('Producto añadido al carro','success');


// }else if(isset($conceptos_personalizado)){
}else if(isset($modificar_linea_productos)){ 

    # sacar los datos del producto
    $dp = $DB->select('nombre_producto,pvp,caracteristicas_producto','productos','id='.$id_producto);
    $pvp = ($dp[f][0]['pvp']/100)*$unidades;
    $nombre = utf8_encode($dp[f][0]['nombre_producto']);

    # calcular nuevo pvp del carrito
    $importe_final -= $pvp_linea;
    $importe_final += $pvp;

    # generar el nuevo div
    $nuevo_cartel = $nombre.' <br>'.$unidades.' Unidades <br>'.number_format(($pvp),2,'.','').'&euro;';

    # generar el nuevo fieldset del concepto sum y modificar los atributos del mismo.
    script('
            $("#div_info_'.$kp.'").html("'.$nuevo_cartel.'");
            $("#concepto_sum_'.$kp.'").attr("nombre","'.$nombre.'");
            $("#concepto_sum_'.$kp.'").attr("cantidad","'.$unidades.'");
            $("#concepto_sum_'.$kp.'").attr("pvp","'.number_format(($pvp),2,'.','').'");
            $(\'[name="importe_final"]\').val('.number_format(($importe_final),2,'.','').'); 
            $(\'[name="pvp_span"]\').html("'.number_format(($importe_final),2,'.','').'");
        ');

// }else if(isset($modificar_linea_productos)){ 
}else if(isset($importe_final_editar)){


    # si facturamos editamos el cliente
    if($facturar == 1){

        $DB->opentable('clientes');
        $DB->edit($id_cliente);
        $DB->ta['nombre_fiscal'] = utf8_decode($nombre_fiscal);
        $DB->ta['direccion'] = utf8_decode($direccion);
        $DB->ta['poblacion'] = utf8_decode($poblacion);
        $DB->ta['cif'] = $cif;
        $DB->ta['cp'] = $cp;
        $DB->update();

    }

    # editamos el pedido
    $DB->openTable('pedidos');
    $DB->edit($id_pedido_editar);
    $DB->ta['descuento'] = $descuento;
    $DB->ta['facturar'] = $facturar;

    if($descuento>0){

        $uni = $importe_final_editar/100;
        $porcentaje = $uni*$descuento;
        $importe_final_editar-=$porcentaje;
    }

    $DB->ta['importe_final'] = number_format($importe_final_editar,2,'.','');
    $DB->ta['fecha_entrega'] = formatear_fecha($fecha_entrega,i,m);
    $DB->ta['hora_entrega'] = $hora_entrega;
    $DB->ta['pedido_cobrado'] = $pedido_cobrado;

    if($pedido_cobrado == 'importe'){

        $DB->ta['importe_pagado'] = $importe_pagado;

    }

    $DB->ta['id_usuario_creacion'] = $_SESSION['id_usuario'];
    $DB->ta['id_delegacion'] = $_SESSION['id_delegacion'];

    if($tarta!=''){

        $DB->ta['tarta'] = json_encode($tarta, JSON_UNESCAPED_UNICODE);

    }else{

        $DB->ta['tarta'] = '';

    }

    if($producto!=''){
        
        $DB->ta['producto'] = json_encode($producto, JSON_UNESCAPED_UNICODE);

    }else{

        $DB->ta['producto'] = '';

    }

    $DB->update();


    # editamos la facturta 
    $DB->openTable('facturas');
    $DB->edit($id_factura);
    $DB->ta['importe'] = $importe_final_editar*100;
    $DB->ta['fecha_cobro'] = formatear_fecha($fecha_entrega,i,m);
    $DB->ta['descuento'] = $descuento;
        
    if($pedido_cobrado == 'Si' && $facturar == 1){

        $DB->ta['estado'] = 1;
        $DB->ta['fecha_cobrada'] = now(); 

        $n = $DB2->select('contador,prefijo','contadores','id=1');
        $cont = $n[f][0]['contador']+1;
        $DB2->query('update contadores set contador = '.$cont.' where id = 1');
        $DB->ta['numero'] = $n[f][0]['prefijo'].'-'.$cont;

    }

    if($tarta != '' && $producto == ''){

        $DB->ta['concepto'] = json_encode($tarta, JSON_UNESCAPED_UNICODE);

    }else if($tarta == '' && $producto != ''){

        $DB->ta['concepto'] = json_encode($producto, JSON_UNESCAPED_UNICODE);

    }else if($tarta != '' && $producto != ''){

        $concept = array_merge($tarta,$producto);
        $DB->ta['concepto'] = json_encode($concept, JSON_UNESCAPED_UNICODE);

    }

    $DB->update();

    notify('Pedido y albaran modificado','success');

    if(!isset($_SESSION['ip_admin'])){

        script('opener.location.reload(); window.close();');
                
    }



// }else if(isset($importe_final_editar)){
}else if(isset($pedido_cobrado)){ 

    # si el cliente no existe lo insertamos 
    if($id_cliente == 0 && $es_vitrina == 0){

        $DB->opentable('clientes');
        $DB->ta['nombre'] = $DB->textCheck($nombre_cliente);
        $DB->ta['telefono'] = $telefono_cliente;
        $DB->ta['email'] = $email_cliente;

        if($facturar == 1){

            $DB->ta['nombre_fiscal'] = $DB->textCheck($nombre_fiscal);
            $DB->ta['direccion'] = $DB->textCheck($direccion);
            $DB->ta['poblacion'] = $DB->textCheck($poblacion);
            $DB->ta['cif'] = $cif;
            $DB->ta['cp'] = $cp;

        }

        $DB->ta['fecha'] = date('Y-m-d');

        $id_insert = $DB->insert();

    }else if($id_cliente > 0 && $es_vitrina == 0){

        $id_insert = $id_cliente;
        $DB->opentable('clientes');
        $DB->edit($id_insert);
        $DB->ta['nombre'] = utf8_decode($nombre_cliente);
        $DB->ta['telefono'] = $telefono_cliente;
        $DB->ta['email'] = $email_cliente;
        if($facturar == 1){

            $DB->ta['nombre_fiscal'] = utf8_decode($nombre_fiscal);
            $DB->ta['direccion'] = utf8_decode($direccion);
            $DB->ta['poblacion'] = utf8_decode($poblacion);
            $DB->ta['cif'] = $cif;
            $DB->ta['cp'] = $cp;

        }
        $DB->update();

    }else{ // if($id_cliente == 0){else{

        $id_insert = $id_cliente;

    } // if($id_cliente == 0){


    $DB->openTable('pedidos');
    $DB->ta['id_cliente'] = $id_insert;
    $DB->ta['local_entrega'] = $local_entrega;
    $DB->ta['descuento'] = $descuento;
    $DB->ta['es_recurrente'] = $es_recurrente;
    $DB->ta['facturar'] = $facturar;

    if($descuento>0){

        $uni = $importe_final/100;
        $porcentaje = $uni*$descuento;
        $importe_final-=$porcentaje;
    }

    $DB->ta['importe_final'] = number_format($importe_final,2,'.','');
    $DB->ta['fecha_entrega'] = formatear_fecha($fecha_entrega,i,m);
    $DB->ta['hora_entrega'] = $hora_entrega;
    $DB->ta['pedido_cobrado'] = $pedido_cobrado;
    $DB->ta['es_vitrina'] = $es_vitrina;

    if($pedido_cobrado == 'importe'){

        $DB->ta['importe_pagado'] = $importe_pagado;

    }

    $DB->ta['id_usuario'] = $id_usuario;
    $DB->ta['id_usuario_creacion'] = $_SESSION['id_usuario'];
    $DB->ta['id_delegacion'] = $_SESSION['id_delegacion'];

    if($tarta!=''){

        $DB->ta['tarta'] = json_encode($tarta, JSON_UNESCAPED_UNICODE);

    }

    if($producto!=''){
        
        $DB->ta['producto'] = json_encode($producto, JSON_UNESCAPED_UNICODE);

    }

    $id_pedido = $DB->insert();


    if($id_insert>0){

        $DB->openTable('facturas');
        $DB->ta['id_cliente'] = $id_insert;
        $DB->ta['importe'] = $importe_final*100;
        $DB->ta['fecha_cobro'] = formatear_fecha($fecha_entrega,i,m);
        $DB->ta['id_delegacion'] = $_SESSION['id_delegacion'];
        $DB->ta['id_usuario'] = $_SESSION['id_usuario'];
        $DB->ta['id_pedido'] = $id_pedido; 
        $DB->ta['descuento'] = $descuento;
        $DB->ta['id_via_cobro'] = $id_via_cobro;   
        
        if($pedido_cobrado == 'Si' && $facturar == 1){

            $DB->ta['estado'] = 1;
            $DB->ta['fecha_cobrada'] = now(); 

            $n = $DB2->select('contador,prefijo','contadores','id=1');
            $cont = $n[f][0]['contador']+1;
            $DB2->query('update contadores set contador = '.$cont.' where id = 1');
            $DB->ta['numero'] = $n[f][0]['prefijo'].'-'.$cont;

        }

        if($tarta != '' && $producto == ''){

            $DB->ta['concepto'] = json_encode($tarta, JSON_UNESCAPED_UNICODE);

        }else if($tarta == '' && $producto != ''){

            $DB->ta['concepto'] = json_encode($producto, JSON_UNESCAPED_UNICODE);

        }else if($tarta != '' && $producto != ''){

            $concept = array_merge($tarta,$producto);
            $DB->ta['concepto'] = json_encode($concept, JSON_UNESCAPED_UNICODE);

        }

        $DB->insert();

    } // if($es_vitrina == 0){



        if($id_pedido == false){

            alert('Registro No instertado, posiblemente este duplicado');

        }else{

            sleep(1);
            alert('Pedido por '.number_format($importe_final,2,'.','').'€ Realizado');
               if(!isset($_SESSION['ip_admin'])){

                script("location.reload();");

               }

        }

// }else if(isset($pedido_cobrado)){ 
}else if(isset($caracteristica_sum)){  

    script("
            $('#div_crt').append(function(){
                return '<div class=\"alert alert-info alert-dismissible fade show\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>Ingrediente : ".$caracteristica_sum."<input type=\"hidden\" name=\"crt_sum\" value=\"".$caracteristica_sum."\" ></div>'});
            $('[name=\"ingrediente\"]').val('')");


// }else if(isset($caracteristica_sum)){  
}else if(isset($refresh_panel)){  

    $sp = $DB->select('max(id) as id_pedido','pedidos',' id > 0');

    $arr['refresh'] = $sp[f][0]['id_pedido'] > $_SESSION['id_pedido'] ? 1 : 0;

    if($arr['refresh'] == 1){

        $_SESSION['id_pedido'] = $sp[f][0]['id_pedido'];
        
    }

    header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode($arr);


// }else if(isset($refresh_panel)){  
}else if(isset($suma_producto_modal)){  

    $dp = $DB->select('*','productos_web','id = '.$id_producto);
    $dp = $dp[f][0];

    if($dp['fotos'] == ''){

        $ft = '/images/logo_argentina_min.png';

    }else{

        $arr_f = json_decode($dp['fotos'],true);
        $ft = is_array($arr_f) && isset($arr_f[0]) ? $arr_f[0] : '/images/logo_argentina_min.png';

    }


    echo '          <div class="col-lg-5 divide-right">
                        <div class="row">
                            <div class="col-md-6">
                                <img src="'.$ft.'" alt="img">
                            </div>
                            <div class="col-md-6 mb-2 mb-md-0">
                                <h4 class="product-name">'.utf8_encode($dp['nombre_producto']).'</h4>';


    echo '                  <h5 class="price">'.nf($dp['pvp']).' &euro;/Unidad</h5>
                                <h6 class="quantity"><strong>Quantity:</strong>&nbsp;1</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="modal-cart-content">
                            <p class="cart-products-count">There is 1 item in your cart.</p>
                            <p><strong>Total products:</strong>&nbsp;$123.72</p>
                            <p><strong>Total shipping:</strong>&nbsp;$7.00 </p>
                            <p><strong>Taxes</strong>&nbsp;$0.00</p>
                            <p><strong>Total:</strong>&nbsp;$130.72 (tax excl.)</p>
                            <div class="cart-content-btn">
                                <button type="button" class="btn theme-btn--dark1 btn--md mt-4"
                                    data-dismiss="modal">Continuar Comprando</button>
                                <button class="btn theme-btn--dark1 btn--md mt-4"><i
                                        class="ion-checkmark-round"></i>Proceed to
                                    checkout</button>
                            </div>
                        </div>
                    </div>';


// }else if(isset($suma_producto_modal)){  
}else if(isset($subir_imagen_blog)){  

    try {

        $dir = '../img/blog/';
        $fichero_subido = $dir . basename($_FILES['upload']['name']);


        # Subimos el fichero
        if(move_uploaded_file($_FILES["upload"]["tmp_name"],$fichero_subido)){

            $new_name = time().'.jpg';

            rename($dir.$_FILES['upload']['name'],$dir.$new_name);
            // ftp_rename($conn_id, $_FILES["upload"]["name"], $new_name);

            $imge = 'https://pastelerialaargentina.com/new_app/img/blog/'.$new_name;
            list($width, $height, $type, $attr) = getimagesize($imge);

            if($width>3000 || $height>3000){

                throw new Exception('La imágen no puede superar los 3000 de alto ni los 3000 de ancho');

            }else{

                // fotos_subidas este es el div sobre el que vamos a tirar la foto
                script('$("[name=\'fotos_subidas\']").append(\'<div class="alert alert-success alert-dismissible fade show mt-3 col-6" role="alert" style="margin : auto;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><img src="'.$imge.'" alt="" style="max-width : 120px;"></div>\');$("[name=\'foto_principal\']").val("'.$new_name.'")');
            }

        }else{

            throw new Exception("No ha sido posible subir el fichero");
           
        }


    } catch (Exception $e) {
        
        notify($e->getMessage(),'danger');
        
    }


// }else if(isset($subir_imagen_blog)){ 
}else if(isset($aprobar_comentario)){ 

    $DB->query('update comentarios_blog set aprobado = 1 where id = '.$id);

    $url_cURL="https://".$_SERVER['HTTP_HOST']."/new_app/functions/ajax.php";
    $post_cURL=array(
            "enviar_email"=>1,
            "tabla_enviar"=>"aprobar_comentario",
            "id"=>$id,
            "id_web"=>1);

    // operacion curl.
    $ch = curl_init();  
    curl_setopt($ch, CURLOPT_URL, $url_cURL);  
    curl_setopt($ch, CURLOPT_POSTFIELDS,$post_cURL);
    curl_exec($ch);    
    curl_close($ch);


// }else if(isset($aprobar_comentario)){ 
}else if(isset($moderar_comentario)){ 

    if($moderar_comentario == 1){

        $DB->openTable('comentarios_blog');
        $DB->edit($id_blog);
        $DB->ta['email'] = $email;
        $DB->ta['aprobado'] = 1;
        $DB->ta['nombre'] = utf8_decode($nombre);
        $DB->ta['asunto'] = utf8_decode($asunto);
        $DB->ta['comentario'] = utf8_decode($comentario);
        $DB->update();

        $url_cURL="https://".$_SERVER['HTTP_HOST']."/new_app/functions/ajax.php";
        $post_cURL=array(
                "enviar_email"=>1,
                "tabla_enviar"=>"aprobar_comentario",
                "id"=>$id_blog,
                "id_web"=>1);

        // operacion curl.
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_URL, $url_cURL);  
        curl_setopt($ch, CURLOPT_POSTFIELDS,$post_cURL);
        curl_exec($ch);    
        curl_close($ch);

        sleep(1);
        alert('Comentario aprobabado');
        script('location.href="https://pastelerialaargentina.com"');

    }else{

        $DB->query('delete from comentarios_blog where id = '.$id_blog);
        alert('Comentario Borrado');
        script('location.href="https://pastelerialaargentina.com"');
    }
// }else if(isset($moderar_comentario)){ 
}
//  
/*$DB->ta[$set[$x]]=$DB->textCheck($update[$x]);  modal_productos_body
                    $DB->ta['password']=password_hash($update[$x], PASSWORD_DEFAULT);





                */


?>