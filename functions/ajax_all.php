<?php header("Access-Control-Allow-Origin: *");
session_start();
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


# NEXT-FIBRA SFTP PORT 22
# u: pedro
# p: TERuTOriCk
#

/*
                                formato de un tooltip y popover por atributos
                                <i class='fa fa-exclamation-triangle tooltip_gastos'
                                    title='Gastos'
                                    data-content=''
                                    data-proveedor='".$data['proveedor']."'
                                    data-xml='".$listado['id_xml']."'
                                    data-hotel='".$data['codeHotel']."'
                                    data-gastos='".$pedir_gastos."'
                                    data-toggle='popover' 
                                    data-placement='bottom' 
                                    data-container='body'
                                    data-trigger='click'
                                    data-delay='{ \"show\": 500, \"hide\": 100 }'
                                    onclick='return true'>
                                </i>

SUPERIMPORTANTE EL AÑADIR A TODOS LOS 
htacces LA DIRECTIVA DE QUE confirmar_redsys_xxx.php
NO SEA REDIRIGIDA POR https: 

librerias modificadas de lugar (new_app/functions/)
redsys
class.php.mailer.php
class.smtp.php
ip2locationlite.php
directorio proveedores coompleto


/*
HAY TAMBIEN QUE DETERMINAR TODAS LAS TABLAS 
QUE VAN A SER MODIFICADAS:



 --INDICE ADMINISRACION USUARIOS BOOKING - EMPRESA --

$s_a -> SI VISITO DESDE LOCAL HOST O MY IP AUTOLOGIN.
  (PODER MEJORAR POR UNA COOKIE CON EL NAVEGADOR DESDE DONDE TU LO USAS.)

$logear_usuario_inicio -> VALIDACION DEL FROM DE LA ENTRADA (MEJORA AUTOLOGIN COOKIE[ARRAY]

$log_out_admin -> LOG UOT DE LA ADMINISTRACION.

$borrar_registro -> BORRAR REGISTROS

if(isset($accion) && $accion==i && !isset($formularios)) -> INSERTAR REGISTROS 

tabla_cargar_datos_registros -> CARGAR LOS DATOS EXISTENTES XRA UPDATE POSTERIOR

if(isset($accion) && $accion==u && !isset($formularios)) -> UPDATE REGISTROS

enviar_email-> enviar email (dentro añadiremos varios if para lso diferentes emails que tengamos que enviar)

$enviar_peticiones_booking -> ENVIAR PETICIONES DE BOOKING AUTOMATICAS.

$realizar_busqueda_hotel -> busquedas XML hoteles + SOLO GUARDAR LA BUSQUEDA 

detalles_hotel_booking -> proceso de detalles XML en booking

$formularios -> INSERT / UPDATE DE FORMULARIOS MODAL.

$xml_campos_formulario -> AÑADIR CAMPOS AL XML DEL FORM LUEGO LOS USAMOS EN A SIGUIENTE FUNCTION COMPLENATNDO ASI EL FORM.

sacar_operadoras -> sacar operadoras  de delegacion elegida en superadmin.php / crear cliente

sacar_promociones -> sacar  promociones de delegacion elegida en superadmin.php / crear cliente

cambiar_select_crear_envios -> RELLENAMOS SELECT DE DELEGACION,OPERADORA, PRODUCTOS AL FORM DE CREAR_ENVIOS QUE SE USA EN superadmin.php

buscar_destino_hotel -> SACAMOS LOS DATOS EN EL INPUT BUSCADOR DE TODOS LOS BUSCADORES DE HOTEL

buscar_destino_hotel_segmentos -> =que arriba + segmentos

buscar_cliente_autocomplete -> SACAR DATOS DE CLIENTES PARA FICHAS BOOKING ETC...

buscar_proveedor_ajax => traemos de la tabla que hayamos seleccionado, todo lo que el proveedor no tiene mapeado.

buscar_mapeo_ajax -> SACAR LOS DATOS DE _paises, _provincias, _poblaciones, _segmentos

clientes_fetico -> CLIENTES FETICO SACAR LOS TITUALRES DE LOS CODIGOS

buscar_cliente_ajax -> CLIENTES AL AJAX +  BOOKING + RESERVAS EN super_admin.php

buscar_cliente_grutinet -> DATOS DE CLIENTES QUE SE HAYAN ECHO EN GRUTINET

id_reserva_email -> montar el pago el tpv de las reservas e importes personalizados a traves del email enviado al cliente.

modificar_bonos_gastados -> modifica los bonos gastados de las reservas desde pop_up_reservas

pasar_a_pagado -> pagado manual rsv from pop_up_reserv (movimiento banca, historico, email confirm) 

devolucion_manual -> realizar una devolucion manual, hace las mismas funciones que arriba

modifica_voucher_reservas -> modifica el voucher desde pop_up_reservas

insertar_reservas_manuales -> insertamos manualmente las reservas desde pop_up_crear_reservas

datos_anuales_booking-> sacamos los datos anuales de booking

filtro_diario_movimientos_bancarios -> filtro para sacar los movimientos bancarios

id_pagar_reserva -> pasar la reserva a pagada a los proveedores

filtro_reservas_pendientes_pago -> filtramos las reservas pendientes de pago

filtro_gastos_personales -> filtramos los gastos personales

verificar_movimientos-> pasar a verificados los movimientos bancarios de 1 día y generar la entrada o salida en los saldos del banco.

conceptos_cobro -> busqueda de los conceptos de cobros de los gastos que metemos en movimientos bancarios

eliminar_hoteles -> eliminar los hoteles de las poblaciones o de los segmentos

incluir_hoteles -> incluir hoteles en las poblaciones o segmentos

insertar_segmento -> insertar segmentos nuevos, para generar grupos personalizados de hoteles

id_add_hotels -> insertamos hoteles en un segmento y le actualizamos el numero de hoteles

descargar_info_reservas -> descargamos la info de una reserva de un proveedor y la metemos en los inputs para guardarla posteiormente en la bbdd


##################################################################################
##################################################################################
///////////////////////// SECCION ZONA PRIVADA DE CLIENTES ////////////////////////
##################################################################################
##################################################################################


// dato_recupera_pass -> recuperacion de contraseña paso primero y ventana si no hay email

// $email_envia_pass_index -> si el cliente no tiene email, se introduce, modifica BBDD y envia pass

// $acepto_cookies -> aceptar cookies 

// $buscador_info_hoteles_zona_publica -> busca hoteles sobre BBDD y hace listado

// cargar_modal_info_hotel -> carga la modal con toda la info del hotel (a mejorar en su día)

// formulario_contacto_home -> fomulario de contacto con email a nosotros y cliente (lo estamos reutilizando des de la zona privada de clientes)

// identificador_acceso_zona_privada -> FORM ACCESO ZONA PRIVADA

// recordar_usuario_zona_privada -> COOKIE AUTOLOGIN

// cerrar_sesion_zona_privada -> CERRAR SESSION Y QUITAR COOKIE AUTOLOGIN

// password_actual -> COMPROBAR PASS ACTUAL XRA CAMBIO

//realizar_busqueda_hotel_cliente -> Realizar las busquedas de disponibilidad 

//pedir_gastos_listados -> pedir gastos de cancelacion desde los listados de los hoteles

//reservar_pendiente -> REALIZAR RESERVA aqui dentro tiene la opcion de hacer en pendiente o pagada

// $id_recibo_reserva -> sacamos en modal la informacion de pago de una reserva desde mis-reservas

//id_reserva_gastos -> conocer los gastos de cancelacion antes de cancelar

//id_reserva_cancelar -> cancelar la reserva 

//id_reserva_info_cancelacion -> sacar la informacion de la cancelacion, incluye el pago

// $cupon_descuento -> los cupones de descuento de los talonarios asignados a clientes

// codigo_1 -> VALIDACIONES DE LOS CODIGOS ALFANUMERICOS DE BDH Y ETC...

// id_cliente_apuntarse => VOLVER A APUNTARSE A LA NEWSLETTER

// texto_comentario => INSERTAR OPINIONES EN LA WEB +. EMAIL DE CONFIRMACION

*/

/////////#####################
include 'funciones.php';
// include 'proveedores/controller.php';
include 'phpMailer/PHPMailer.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


// A LOCAL TODO LO QUE VENGA POR POST O GET esta pasado a local en funciones.php.


if(isset($s_a)){


        $_SESSION['Authenticated']="1";// EN CASO DE 0 OUT
        $_SESSION['nombre_usuario']="Tere"; // USUARIO
        accion_admin(r,"../super_admin.php"); //colamos para adentro.

// OEF isset($s_a)
}else if(isset($logear_usuario_inicio)){


    $DB->query('select nombre,password,id_delegacion,permisos from usuarios where login like "'.$usr.'"');


        if($DB->rows()==0){

            alert("El usuario no existe");

        }else if($DB->rows()==1){

            $datos_login_inicio=$DB->fetch();


                if(!password_verify($pwd,$datos_login_inicio['password'])){

                    alert('la contraseña es incorrecta');

                }else if(password_verify($pwd,$datos_login_inicio['password'])){


                    $_SESSION['Authenticated']=$datos_login_inicio['permisos'];  
                    $_SESSION['nombre_usuario']=$datos_login_inicio['nombre'];
                    $_SESSION['permisos_delegacion']=$datos_login_inicio['id_delegacion']; 

                    if($_SESSION['Authenticated']==1){

                        script("location.href='super_admin.php'");

                    }else{

                        script("location.href='clientes.php'");

                    }

                }


        }
// OEF isset($logear_usuario_inicio)
}else if(isset($log_out_admin)){

   
    $_SESSION['Authenticated']="0";
    session_destroy();
    script("location.href='index.php'"); 
   // header('Location: ../index.php');

// EOF isset($log_out_admin)
}else if(isset($borrar_registro) || isset($borrar_registro_2)){

    // $tabla_eliminar
    // $id_eliminar

 $DB->delete($tabla_eliminar,$id_eliminar);

 script("location.reload()");

// EOF isset($borrar_registro)
}else if(isset($accion) && $accion==i && !isset($formularios)){



$DB->openTable($tabla_insertar);


// AL INSERT DE ENVIOS LE ELIMINAMOS LA COLETILLA _envios
// XRA NO_CONFILCT DE id EN superadmin.php


    if($tabla_insertar=="envios"){

        // esta funcion, me devuelve un nuevo $_POST
        // tener precaucion, por que en la tabla de 
        // envios, 

        $_POST=limpiar_keys($_POST,"_envios");

        // no existe esto ${$key}=$value
        // repetimos aki y fin de confilct
        foreach ($_POST as $key => $value) {
            ${$key} = $value;
        }

    }


foreach ($_POST as $key => $value) {

// alert($key ." ".$value);
// campos alwais isset  que tenemos que obviar
// accion
// tabla_insertar

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

        /////********** ESPECIALES TABLA CLIENTES/ENVIOS *****// 
                }else if($key=="e_mail"){
                    //*** NEW_EMAIL

                    $n_email = json_email($value);
                    $DB->ta['email'] = $n_email;


                }else if($key=="producto" && (!isset($noches_personalizado) || $noches_personalizado==0)){

                    $noches=$DB->select("noches,costo,pvp","productos","id=".$value);

                    if($tabla_insertar=="clientes"){

                        $noches[f][0]['noches'] = isset($noches[f][0]['noches']) ? $noches[f][0]['noches'] : 0;

                        $var = array("disponibles"=>$noches[f][0]['noches'],
                        "usados"=>0,
                        "comprados"=>$noches[f][0]['noches']);

                        $noches_json = json_encode($var);

                        $DB->ta['noches']=$noches_json;

                    }else if($tabla_insertar=="envios"){

                        $DB->ta['noches']=$noches[f][0]['noches']; 

                    }


                }else if($key=="noches_personalizado" && 
                    $value>0){

                    if($tabla_insertar=="clientes"){

                    $var = array("disponibles"=>$value,
                    "usados"=>0,
                    "comprados"=>$value);
                    $noches_json = json_encode($var);

                    $DB->ta['noches']=$noches_json;

                    }else if($tabla_insertar=="envios"){

                    $DB->ta['noches']=$value; 

                    }

        /////********** FIN ESPECIALES TABLA CLIENTES/ENVIOS *****// 

                    // FORMATO DE Nº ESPECIAL PARA LOS PRODUCTOS
                }else if($key=="pvp" || $key=="costo" || $key=="importe"){

                    $DB->ta[$key]=$value*100;

                }else if($key=='entrada' || $key=='salida' || $key=='periodo' || $key=='fecha'){

        // AQUI METEMOS ESPECIALIDADES DE FECHAS.

                    if($key=='periodo'){

                        list($dia,$mes,$year) =  explode("/",$value);
                        $DB->ta['mes']=$mes;
                        $DB->ta['year']=$year;
                    }

                    $value=formatear_fecha($value,i,m);

                    $DB->ta[$key]=$value;

                }else if($key!="pvp_personalizado" &&
                    $key!="noches_personalizado" &&
                    $key!="costo_personalizado" &&
                    $key!="producto" &&
                    $key!="estado_producto" &&
                    $key!="via_cobro" &&
                    $key!="cURL_redsys" &&
                    $key!="habitacion" &&
                    $key!="adultos" &&
                    $key!="ninos" &&
                    $key!="edad" &&
                    $key!="busqueda" &&
                    $key!="email_cliente_booking" &&
                    $key!="telefono_cliente_booking" &&
                    $key!="descuento" &&
                    $key!="codigo_1" &&
                    $key!="codigo_2" &&
                    $key!="re_login" &&
                    $key!="numero_bonos" &&
                    $key!="bonos_personalizados" && 
                    $key!="cabecera_personalizada"){
                    $DB->ta[$key]=$DB->textCheck(str_replace("€","&euro;",$value));
                     //+++ FIN TODAS LAS DEMAS TABLAS +++///
                }

                // eof if($buscador===false){
                }
            //eof if($key!="accion" && $key!="tabla_insertar"){
            }
        // eof foreach ($_POST as $key => $value) {
        }


        ///++++ AQUI VOY A METER TODAS LAS ESPECIALIDADES 
        // DE LAS DIFERENTES TABLAS:
        if($tabla_insertar=="clientes" || $tabla_insertar=="envios"){


        // ELIMINAMOS DUPLICIDADES DE CLIENTES EN BBDD
                if($tabla_insertar=="clientes"){
         //////*+++++++SOLO ESTAMO COMPROBANDO EMAIL, PODEMOS 
                    // METER MAS CONDICIONES
                    

                        $comprobar_cliente=$DB->select("id",
                        "clientes",
                        "email like '%".$e_mail."%' and telefono=".$telefono." and nombre like '".$nombre."' and id_delegacion=".$id_delegacion);
                    

                }

                if(isset($comprobar_cliente[r]) && $comprobar_cliente[r]>0){

                    alert("El cliente se encuentra en la base de datos");
                    exit();

                }else if(!isset($comprobar_cliente[r]) || 
                    isset($comprobar_cliente[r]) && $comprobar_cliente[r]==0){  


                    // INSERT DE ENVIOS Y ENVIO DE EMAILS DE BIENVENIDA if noches>0:
            

            // aki tenemso que meter la opcion de que 
                    // el cliente entre sin noches (prueba)
                    // TB diferentes estados de los envios (sumando o no noches)
                if($noches[f][0]['noches']>0 || $noches_personalizado>0){

                // definimos productos o noches personalizadas.
                    if($noches[f][0]['noches']>0){
                        
                        $noches_email=$noches[f][0]['noches'];
                        $costo=$noches[f][0]['costo'];
                        $pvp=$noches[f][0]['pvp'];

                        // añadimos los descuentos en caso de que existan
                        // vienen de confirmar_redsys......
                        if(isset($descuento) && $descuento>0){
                        
                            $descuento =  $noches[f][0]['pvp']/100*$descuento;
                            $pvp = $noches[f][0]['pvp']-$descuento;

                        }

                    }else if($noches_personalizado>0){
                        
                        $noches_email=$noches_personalizado;
                        $costo=$costo_personalizado*100;
                        $pvp=$pvp_personalizado*100;
                    }


                    if($tabla_insertar=="clientes"){

                        $DB->ta["dia"]=date("d");
                        $DB->ta["mes"]=date("m");
                        $DB->ta["year"]=date("Y");
                        $DB->ta['fecha']=date("Y-m-d");

                        // identificador:

                        $identificador=$DB->select("identificador","delegaciones","id=".$id_delegacion);
                        $max_iden=$DB->select("max(iden) as iden","clientes","id_delegacion=".$id_delegacion);
                        $new_iden=$max_iden[f][0]['iden']+1;
                        $new_identificador=$identificador[f][0]['identificador'].$new_iden;

                        $DB->ta["iden"]=$new_iden;
                        $DB->ta["identificador"]=$new_identificador;
                        // EL ESTADO COMO 1 , TENEMOS QUE DEFINIR
                        // MEJOR, PUESTO QUE AQUI DEBEN EXISTIR ALGUNOS
                        // IFS 
                        $DB->ta['estado']=1;
                        // insertamos el cliente y nos llevamos su ID
                        $id_cliente=$DB->insert();


                        // insertamos el envio
                        $DB->openTable('envios');
                        $DB->ta['id_cliente']=$id_cliente;
                        $DB->ta['id_operadora']=$id_operadora;
                        $DB->ta['id_delegacion']=$id_delegacion;
                        $DB->ta['id_estado']=$estado_producto;
                        $DB->ta['costo']=$costo;
                        $DB->ta['pvp']=$pvp;
                        $DB->ta['noches']=$noches_email;
                        $DB->ta["dia"]=date("d");
                        $DB->ta["mes"]=date("m");
                        $DB->ta["year"]=date("Y");
                        $DB->ta['fecha']=date("Y-m-d");
                        $id_procedencia=$DB->insert();

                        // if importe y la via_cobro sea mayor a 0  
                        // añadimos un movimiento bancario.
                        if ($pvp>0 && $via_cobro>0) {
                            
                            $DB->openTable('movimientos_bancarios');
                            $DB->ta['id_via_cobro']=$via_cobro;//importe
                            $DB->ta['importe']=$pvp;
                            $DB->ta['tipo']=1;
                            $DB->ta['procedencia']='envios';
                            //el id de la procedencia viene de los envios 
                            $DB->ta['id_procedencia']=$id_procedencia;
                            $DB->ta['fecha']=now();
                            $DB->insert();
                        }

                        if(isset($_SESSION['permisos_delegacion'])){

                            $DB->openTable('bonos_delegaciones');
                            $resultado = mueve_bonos_delegaciones($noches_email,0,$_SESSION['permisos_delegacion']);
                            $DB->ta['anterior']=$resultado['saldo_anterior'];;
                            $DB->ta['resultado']=$resultado['nuevo_saldo'];
                            $DB->ta['movimiento']=$noches_email;
                            $DB->ta['tipo']=0;
                            //el id de la procedencia viene de los envios 
                            $DB->ta['ids_movimientos']=$id_procedencia;
                            $DB->ta['id_delegacion']=$_SESSION['permisos_delegacion'];
                            $DB->ta['concepto']="envios";
                            $DB->ta['creacion']=now();

                            $DB->insert();

                        }

                    }else if($tabla_insertar=="envios"){  // eof if($tabla_insertar=="clientes"){

                        // insertar nuevo envio.
                        $DB->ta['id_estado']=$estado_producto; 
                        $DB->ta['costo']=$costo;
                        $DB->ta['pvp']=$pvp;
                        $DB->ta["dia"]=date("d");
                        $DB->ta["mes"]=date("m");
                        $DB->ta["year"]=date("Y");
                        $DB->ta['fecha']=date("Y-m-d");
                        $id_procedencia=$DB->insert();

                        // AÑADIR NOCHES A LA CUENTA DE USUARIO
                        $nuevas_noches=$DB->select("noches,identificador","clientes","id=".$id_cliente);
                        $noches_json = json_decode($nuevas_noches[f][0]['noches']);
                        $noches_json->disponibles=$noches_json->disponibles+$noches_email;
                        $noches_json->comprados=$noches_json->comprados+$noches_email;
                        $update_noches=json_encode($noches_json);

                        $DB->query('update clientes 
                            set noches=\''.$update_noches.'\'
                             where id='.$id_cliente);

                        // EOF  AÑADIR NOCHES A LA CUENTA DE USUARIO

                        // en caso de que el importe sea mayor a 0
                        // añadimos un movimiento bancario.
                        if ($pvp>0 && $via_cobro>0) {
                            # code...
                            $DB->openTable('movimientos_bancarios');
                            $DB->ta['id_via_cobro']=$via_cobro;//importe
                            $DB->ta['importe']=$pvp;
                            $DB->ta['tipo']=1;
                            $DB->ta['procedencia']='envios';
                            $DB->ta['id_procedencia']=$id_procedencia;
                            $DB->ta['fecha']=now();
                            $DB->insert();
                        }

                        if(isset($_SESSION['permisos_delegacion'])){

                            $DB->openTable('bonos_delegaciones');
                            $resultado = mueve_bonos_delegaciones($noches_email,0,$_SESSION['permisos_delegacion']);
                            $DB->ta['anterior']=$resultado['saldo_anterior'];;
                            $DB->ta['resultado']=$resultado['nuevo_saldo'];
                            $DB->ta['creacion']=now();

                            $DB->ta['movimiento']=$noches_email;
                            $DB->ta['tipo']=0;
                            //el id de la procedencia viene de los envios 
                            $DB->ta['ids_movimientos']=$id_procedencia;
                            $DB->ta['id_delegacion']=$_SESSION['permisos_delegacion'];
                            $DB->ta['concepto']="envios";
                            $DB->insert();

                        }

                        // si vienen de las validaciones de bonos de hotel
                        // sacamos el iden para el autologin mas abajo
                        if(isset($re_login)){
                            $new_identificador=$nuevas_noches[f][0]['identificador'];
                        }

                        // eof }else if($tabla_insertar=="envios"){
                    }


                    // si vienes codigos de validaciones los consumimos
                    if(isset($codigo_1)){
                        $DB->query('update validaciones_bdh set id_cliente="'.$id_cliente.'" where code like "'.$codigo_1.'"');
                    }

                    if(isset($codigo_2)){
                        $DB->query('update validaciones_bdh set id_cliente="'.$id_cliente.'" where code like "'.$codigo_2.'"');
                    }


                    
                    $datos=$DB->inner_join('c.nombre as cliente,
                        d.nombre as delegacion,
                        d.web,
                        c.id_delegacion,
                        c.identificador,
                        c.comentario,
                        d.email as remitente,
                        c.email as destinatario','clientes as c inner join delegaciones as d',
                        'c.id_delegacion = d.id and c.id='.$id_cliente,f);


                    $mail = new phpmailer($datos[f][0]['remitente']);
                    $mail->setFrom($datos[f][0]['remitente'],$datos[f][0]['delegacion']);
                   

                        // new solution here

                        $j_destinatarios = json_decode($datos[f][0]['destinatario']);

                        $E=1;
                        foreach ($j_destinatarios as $value) {
                           
                            if($E==1){
                                $mail->AddAddress($value);
                            }else if($E>1){
                                $mail->AddCC($value);
                            }
                            
                            $E++;
                        }
                        
                        // oooooo
                        // DATOS QUE VAMOS A PASAR
                        // POR PARAMETROS A body_email
                        $datos_cliente=array("id"=>$id_cliente,
                        "nombre"=>$datos[f][0]['cliente'],
                        "identificador"=>$datos[f][0]['identificador'],
                        "comentario"=>$datos[f][0]['comentario'],
                        "noches"=>$noches_email);


                        if(isset($bonos_personalizados) && isset($cabecera_personalizada)){

                            $datos_cliente['url_cabecera']=$cabecera_personalizada;
                            $datos_cliente['url_talonario']=$bonos_personalizados;

                        }

                        // SALUDO Y BODY EMAIL, SEGUN TABLA
                    if($tabla_insertar=="clientes"){

                        $mail->Subject="Bienvenid@ a " .$datos[f][0]['delegacion']; 
                        $mail->Body=body_email(b,$datos_cliente,
                        $datos[f][0]['id_delegacion']);

                    }else if($tabla_insertar=="envios"){

                        $mail->Subject="Saludos de " .$datos[f][0]['delegacion'];
                        $mail->Body=body_email(s,$datos_cliente,$datos[f][0]['id_delegacion']);

                    }


                    if(!isset($cURL_redsys)){

                        if($mail->send()){
                            alert("Envio Realizado");
                        }else{
                            alert("Envio NO Realizado"); 
                        }

                    }else if(isset($cURL_redsys)){

                        $mail->send();
                        exit();

                    }


                // CLIENTES PRUEBA 0 NOHES.    
                }else{ // if($noches[f][0]['noches']>0 || $noches_personalizado>0){
                    

                    $DB->ta["dia"]=date("d");
                    $DB->ta["mes"]=date("m");
                    $DB->ta["year"]=date("Y");
                    $DB->ta['fecha']=date("Y-m-d");

                    // identificador:

                    $identificador=$DB->select("identificador","delegaciones","id=".$id_delegacion);
                    $max_iden=$DB->select("max(iden) as iden","clientes","id_delegacion=".$id_delegacion);
                    $new_iden=$max_iden[f][0]['iden']+1;
                    $new_identificador=$identificador[f][0]['identificador'].$new_iden;

                    $DB->ta["iden"]=$new_iden;
                    $DB->ta["identificador"]=$new_identificador;


                    // EL RELOAD Y EL INSERT LO HACE ABAJO DEL TODO.
                    alert("Insertado cliente con 0 noches");
                    $DB->insert(); 
                    script("location.reload()");
                
                }

            
                if($tabla_insertar!="envios" && !isset($cURL_redsys)){
                    script("location.reload()");

                }else if(isset($cURL_redsys)){
                        exit();

                }else if($tabla_insertar=="envios" && !isset($cURL_redsys)){
                    script("$('[aria-labelledby=\"myModalLabel\"]').modal('hide');");
                }

        
                    //isset($comprobar_cliente[r]) && $comprobar_cliente[r]==0
            } // !isset($comprobar_cliente[r]) || .....

        // eof   if($tabla_insertar=="clientes" || $tabla_insertar=="envios"){
        }else if($tabla_insertar=="booking"){


            /* Estas son las peticiones que vamos ainsertar
                        //** DATOS DEL CLIENTE 
                    id_cliente : $('#id_cliente').val(),
                    email_cliente : $('#email_cliente').val(),
                    telefono_cliente : $('#telefono_cliente').val(),

                    //** DATOS DE LA PETICION 
                    gestion_manual : gestion_manual,
                    notas_manual : $('[name="notas_manual"]').val(),
                    entrada : $('[name="entrada"]').val(),
                    salida : $('[name="salida"]').val(),
                    habitacion : $('[name="habitacion"]').val(),
         
            */


            // empezamos por el cliente.
            // por si hay que cambiar el tlf o email
            // modificarlos en la BBDD
            $n_email = json_email($email_cliente_booking);
            $DB->query('update clientes set email=\''.$n_email.'\', telefono="'.$telefono_cliente_booking.'" where id='.$id_cliente);

            // todas las $ que no son array ya estan metidas
            // y estan arriba, exceto habitacion abajo ponemos las que tenemos que tratar.

            // xml de busquedas
            $busquedas= new SimpleXMLElement('<?xml version="1.0"?><busquedas></busquedas>');
            $peticion=$busquedas->addChild('peticion');
            $peticion->addChild('entrada',$entrada);
            $peticion->addChild('salida',$salida);
            $peticion->addChild('gestionada',0);
            $peticion->addChild('creacion',now());
            $habitaciones=$peticion->addChild('habitaciones');
            $habitaciones->addChild('numero',$habitacion);

            //  habitaciones - distribuciones - edades
            for($h=1;$h<=$habitacion;$h++){

                $nueva_habitacion[$h]=$habitaciones->addChild('habitacion');
                $nueva_habitacion[$h]->addChild('adultos',$adultos[$h]);
                $nueva_habitacion[$h]->addChild('ninos',$ninos[$h]);


                if($ninos[$h]>0){
                    $edades[$h]=$nueva_habitacion[$h]->addChild('edades');
                     for($e=1;$e<=$ninos[$h];$e++){

                     $edades[$h]->addChild('edad',$edad[$h][$e]);
                     }
                // eof if($ninos[$h]>0){
                }
            // eof for($h=1;$h<=$habitacion;$h++){
            }

        // solo me queda aqui la parte de las busquedas.
            $zonas=$peticion->addChild('zonas');

            foreach($busqueda as $key => $value){

                $zona=$zonas->addChild('zona');

                    foreach ($value as $tipo => $dato) {

                            $zona->addChild($tipo,$dato);
                    // oef foreach ($value as $tipo => $dato) {
                    }
            // eof foreach($busqueda as $key => $value){
            }

            // CREAMOS LA ESTRUCTURA
            $historico= new SimpleXMLElement('<?xml version="1.0"?><historico></historico>');
            // AÑADIMOS 1º REGISTRO
            $registro=$historico->addChild('registro');

            $registro->addChild('usuario',$_SESSION['nombre_usuario']);
            $registro->addChild('tipo','creacion');
            $registro->addChild('fecha',date('d/m/Y'));
            $registro->addChild('hora',date('H:i:s'));

            $DB->ta['historico']=$historico->asXML();
            $DB->ta['peticiones']=$busquedas->asXML();
            $DB->ta['creacion']=now();


            noty('Peticion insertada');
            $DB->insert(); 
            script("opener.location.reload();location.reload();");

    }else if($tabla_insertar=="booking_emails"){

            $DB->ta['creacion']=now();
            $DB->insert(); 
            noty("Email Guardado Correctamente");
            script("location.reload()");

    }else if($tabla_insertar=="movimientos_bancarios"){

            if($id_via_cobro==12){ // estos son los gastos, que los vamos a meter por aqui directamente

                $DB->ta['verificado']=1;

                $DB2->openTable('saldo_empresa');
                $DB2->ta['concepto']=$concepto;
                $DB2->ta['movimiento']=$importe;
                $DB2->ta['tipo']=0;
                $resultado = mueve_saldo($importe,0);
                $DB2->ta['anterior']=$resultado['saldo_anterior'];
                $DB2->ta['resultado']=$resultado['nuevo_saldo'];
                $DB2->ta['creacion']=now();
                $DB2->insert();

            }

            if($id_via_cobro==13){
                $DB->ta['verificado']=1;
            }

            // retroalimentacion de los conceptos, para su futura agrupacion
            $compruba_concepto = $DB2->select('concepto','conceptos_cobro','concepto like "'.$concepto.'"');
            if($compruba_concepto[r]==0){

                $DB2->openTable('conceptos_cobro');
                $DB2->ta['concepto']=$concepto;
                $DB2->insert();

            }

            $fecha_moviento = isset($fecha) && $fecha!="" ? formatear_fecha($fecha,i,m)." ".date('H:i:s') : now();
            $DB->ta['fecha']=$fecha_moviento;
            $DB->insert(); 
            noty("Movimiento Guardado Correctamente");
            script('$("#movimientos_bancarios").modal("hide")');

    }else if($tabla_insertar=="saldo_empresa"){

            $resultado = mueve_saldo($movimiento,$tipo);
            $DB->ta['anterior']=$resultado['saldo_anterior'];;
            $DB->ta['resultado']=$resultado['nuevo_saldo'];
            $DB->ta['creacion']=now();
            $DB->insert(); 
            noty("Movimiento Guardado Correctamente");
            script('$("#saldos").modal("hide")');

    }else if($tabla_insertar=="bonos_delegaciones"){

            $resultado = mueve_bonos_delegaciones($movimiento,$tipo,$id_delegacion);
            $DB->ta['anterior']=$resultado['saldo_anterior'];;
            $DB->ta['resultado']=$resultado['nuevo_saldo'];
            $DB->ta['creacion']=now();
            $DB->insert(); 
            noty("Bonos Sumados Correctamente");
            script('$("#bonos_delegaciones_modal").modal("hide")');

    }else{ // especiañidades de otras tablas aki

       $DB->insert(); 

    }

           // esto solo se usa en las validaciones de los bonos 
       // vistuales, que por ahora solo se hace en soltury.
       if(isset($re_login) && $re_login==1){

            script('location.href="https://'.$_SERVER['HTTP_HOST'].'/new_soltury/index.php?login_directo_zona_privada='.$new_identificador.'"');

       }else{
            script("location.reload()");

       }

//eof if(isset($accion) && $accion==i){
}else if(isset($tabla_cargar_datos_registros)){

//////*******TENER EN CUENTA QUE VAMOS A TRAERNOS TODOS LOS 
    // CAMPOS CON EL AÑADIDO _editar 

////// ELIMINAR DE $select ////////////
         // accion
        // tabla_editar
        // id_editar

$select=$DB->limpiar_editar($_POST);

//alert($select['key']);
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

                if($key=="comentario"){
                    $key="password";
                }

                if($key=="email"){
                    // si el cliente tiene + de 1 email en 
                    // BBDD
                    $email_arr=json_decode($value);
                    $end=count($email_arr);

                    // si solo 1
                    if($end==1){
                        $value = $email_arr[0];
                    }else if($end>1){ // if($end==1){
                        // si mas de 1
                        $new_value="";
                        for($e=1;$e<=$end;$e++){
                            if($e<$end){
                                $new_value.=$email_arr[$e-1].";";
                            }else{
                                $new_value.=$email_arr[$e-1];
                            }
                        }// for($e=1;$e<=$end;$e++){
                        $value=$new_value;
                    }// }else if($end>1){

                }// if($key=="email"){

                if($key=="importe"){
                    $value=($value/100);
                }


                if($tabla_editar=="productos" && $key=="id_operadora" && $value!=0){


                    // $DB2 = new DataAccess();
                    $id_delegacion = $DB2->select('id_delegacion','operadoras','id='.$value);
                    
                    $DB2->query('select id,nombre from operadoras where id_delegacion='.$id_delegacion[f][0]['id_delegacion'].' and estado=1');
                    
                    $option = "";
                    while($data = $DB2->fetch()){

                        $selected = $data['id']==$value ? 'selected="selected"' : '';
                        $option.='<option value="'.$data['id'].'" '.$selected.' >'.$data['nombre'].'</option>';
                    }

                    echo "$('#id_operadora_editar').html('".$option."');";

                }else{


                                        //*** posiblemente los check que vayamos a usar TB  ***
                    //*** lo veremos cuando se presente el asunto.      ***

        // comprobamos el tipo de campo y hacemos diferentes acciones -> var tag

        // los select, le tenemos que cambiar el dato de modo diferente. -> if(tag=='SELECT'){

         // con esto limpiamos el select de cualquier opcion preseleccionada anteriormente ->$('#".$key."_editar option').each

        // Cambiamos a true el que toca -> $('#".$key."_editar option[value=".$value."]').attr('selected',true);

                // else -> resto de campos no select

                    echo "
                
                    var tag = $('[name=\"".$key."_editar\"]').prop('tagName');


                    if(tag=='SELECT'){

                        $('#".$key."_editar option')
                           .each(function(indice,elemento) { 
                            $(elemento).attr('selected',false);
                        });
          
                        $('#".$key."_editar option[value=\"".trim($value)."\"]').attr('selected',true);

                    }else if(tag=='TEXTAREA'){
         
                        $('#".$key."_editar').html('".utf8_encode(trim($value))."'); 
                    }else{
         
                        $('#".$key."_editar').attr('value' , '".utf8_encode(trim($value))."'); 
                    }";

                } // if($tabla_editar=="productos" && $key=="id_operadora" ... else{

        } // foreach ($select_registro[f][0] as $key => $value) {
echo "</script>";

// eof if($tabla_editar!="formularios_modal")
}else if($tabla_editar=="formulario_modal"){

$n_c_a=count($id_nombre_campo_);



// $xml_select=$DB->select("xml",$tabla_editar," id=".$id_editar,f);// $campo_xml=html_entity_decode($xml_select[f][0]['xml']);// $campo_xml=str_replace('"','\"',trim($campo_xml));// $xml_decode=preg_replace('[\n|\r|\n\r]','',$campo_xml);// script("$('#text_formulario_editar').html('".$xml_decode."')");// $xml=new SimpleXMLElement(html_entity_decode($xml_select[f][0]['xml']));// eof if($tabla_editar=="formularios_modal"



}



// eof }else if(isset($tabla_cargar_datos_registros)){
}else if(isset($accion) && $accion==u && !isset($formularios)){

    // $ universales:
    // tabla_editar
    // id_editar 
    //clean string of "_editar"
    $datos=$DB->limpiar_editar($_POST,0); 

    //array de keys y values
    $set=explode(",",$datos['key']);
    $update=explode(",",$datos['value']);
    $DB->opentable($tabla_editar);
    $DB->edit($id_editar);
    $fin=count($update);

    // especialidades de tablas aqui.
    if(isset($campo_personalizado_editar) && isset($value_personalizado_editar)){

        $DB->ta[$campo_personalizado_editar]=$DB->textCheck($value_personalizado_editar);

    }else{ //if($tabla_editar=="_paises"){

        $arr_campos_fechas = array(
                    'periodo',
                    'entrada',
                    'salida',
                    'fecha_pago_cliente');

        for($x=0;$x<$fin;$x++) {


            if($set[$x]=="comentario"){

                $DB->ta[$set[$x]]=$DB->textCheck($update[$x]);
                $DB->ta['password']=password_hash($update[$x], PASSWORD_DEFAULT);

            }else if($set[$x]=='periodo' || $set[$x]=='entrada' || $set[$x]=='salida' || in_array($set[$x],$arr_campos_fechas)){

                if($set[$x]=='periodo'){

                    list($dia,$mes,$year) =  explode("/",$update[$x]);
                    $DB->ta['mes']=$mes;
                    $DB->ta['year']=$year;
                }

                $update[$x]=formatear_fecha($update[$x],i,m);
                $DB->ta[$set[$x]]=$update[$x];

            }else if($set[$x]=='neto' || $set[$x]=='total' || $set[$x]=='importe'){
                
                $new_number = str_replace(",", ".", $update[$x]);
                $new_number = number_format($new_number,2,".","");
                $new_number=$new_number*100;
                $DB->ta[$set[$x]]=$new_number;

            }else if($set[$x]=="email"){
               
                // es para el pequeño form del buscador 
                // de la zona privada de clientes.

                if(isset($sin_reload_zona_privada)){
                    

                    $json_email = $DB->select('email','clientes','id='.$id_editar);
                    $decode_email = json_decode($json_email[f][0]['email']);

                    if($decode_email[2]!=""){
                        $new_json = $update[$x].";".$decode_email[1].";".$decode_email[2];
                    }else if($decode_email[2]=="" && $decode_email[1]!=""){
                        $new_json = $update[$x].";".$decode_email[1];
                    }else if($decode_email[2]=="" && $decode_email[1]==""){
                        $new_json = $update[$x];
                    }

                    $n_email = json_email($new_json);
                    $DB->ta['email'] = $n_email; 
                    $_SESSION[$set[$x].'_zona_privada']=$update[$x];

                }else{

                    $n_email = json_email($update[$x]);
                    $DB->ta['email'] = $n_email; 

                }


            }else if($set[$x]!="sin_reload" && $set[$x]!="sin_reload_zona_privada" && $set[$x]!=""
                    && $set[$x]!="noty"){

                if(isset($sin_reload_zona_privada)){
                    $_SESSION[$set[$x].'_zona_privada']=$update[$x];
                }

                $DB->ta[$set[$x]]=$DB->textCheck(utf8_decode($update[$x]));

            } // }else if($set[$x]!="sin_reload" && $set[$x]!="sin_reload_zona_privada"){

        } // for($x=0;$x<$fin;$x++) {

    } //if($tabla_editar=="_paises"){

    $DB->update();

    if(isset($sin_reload) || isset($sin_reload_zona_privada)){

        alert('Datos Modificados');

    }else if(isset($noty)){
        script("noty({text: 'Datos Modificados', layout: 'topRight', type: 'success'});
            setTimeout('$.noty.closeAll()',5000);");
    }else{

        script("location.reload()");  
    }


// eof }else if(isset($tabla_cargar_datos_registros)){
}else if(isset($enviar_email)){

    

    // mirar las diferentes tablas que vamos a pasar por aqui.
    // marcar los diferentes campos, tipos de envios desde 
    // las tablas que le pasamos, siempre teniendo en  cuenta 
    // la delegacion xra (From , FromName , Subjet) 
    // xara que esto funcione bien, necesitamos siempre enviar la 
    // $id conteniendo el id del cliente.

    if($tabla_enviar=="clientes"){

        $id_cliente=$id;
        $subjet="Recuperación de Clave";

    }else if($tabla_enviar=="booking" && (isset($email_disponibilidad) && $email_disponibilidad==0 || !isset($email_disponibilidad))){

        $subjet= $asunto_email!="" ? $asunto_email : "Mensaje de ";

    }else if(isset($email_disponibilidad) && $email_disponibilidad==1){

        $subjet= "Disponibilidad de ";

    }else if($tabla_enviar=="reservas_realizadas" && (isset($email_pago_rsv) || isset($reenviar_voucher))){

        // necesito sacar el id del cliente, importe, localizadors.
        $data_reserva = $DB->select('id_cliente,total,localizador,entrada,salida,voucher,bonos_gastados','reservas_realizadas','id='.$id_reserva);
        // meto aqui el id del cliente, para poder sacar abajo los datos.
        $id_cliente = $data_reserva[f][0]['id_cliente'];

        $subjet= isset($email_pago_rsv) ? "Próxima entrada en gastos en " : "Voucher de reserva en ";

    }else if(isset($email_pago_manual) && $tabla_enviar==""){

        $subjet= "Confirmación de Reserva pagada en ";

    }else if(isset($email_devolucion_manual) && $tabla_enviar==""){

        $subjet= "Devolución Realizada en ";

    }else if(isset($enviar_email_pago) && $enviar_email_pago==1){

        $subjet= "Email de pago en ";

    }
    

    #### HACER ESTO UNA FUNCION
    // aqui vamos a sacar todos los datos 
    // para la personalizacion de cliente y marca
    $datos=$DB->inner_join('c.nombre as cliente,
    d.nombre as delegacion,
    d.web,
    d.color_email,
    c.id_delegacion,
    c.identificador,
    c.comentario,
    d.email as remitente,
    c.email as destinatario','clientes as c inner join delegaciones as d',
    'c.id_delegacion = d.id and c.id='.$id_cliente,f);


    $mail = new phpmailer($datos[f][0]['remitente']);
    $mail->setFrom($datos[f][0]['remitente'],$datos[f][0]['delegacion']);

    // new solution here

    if(!isset($email_destinatario) || $email_destinatario==""){

        $j_destinatarios = json_decode($datos[f][0]['destinatario']);

    }else if($email_destinatario!=""){

        $j_destinatarios = explode(";",$email_destinatario);


        if($accion_cuenta_adicional==2){

            // Enviar y guardar junto con los otros emails
            $old_destinatarios = json_decode($datos[f][0]['destinatario']);
            $j_destinatarios=array_merge($old_destinatarios,$j_destinatarios);
            $new_destinatarios = json_encode($j_destinatarios);

            $DB->query("update clientes set email='".$new_destinatarios."' where id=".$id_cliente);

        }else if($accion_cuenta_adicional==3){

            // Borrar todos los antiguos y poner estos
            $new_destinatarios = json_encode($j_destinatarios);

            $DB->query("update clientes set email='".$new_destinatarios."' where id=".$id_cliente);
        }
    } // if(!isset($email_destinatario) || $email_destinatario==""){ .... else....

    $E=1;
    foreach ($j_destinatarios as $value) {
                       
        if($E==1){
            $mail->AddAddress($value);
        }else if($E>1){
            $mail->AddCC($value);
        }
                        
        $E++;
        
    } // foreach ($j_destinatarios as $value) {


    $mail->Subject=$subjet ." " .$datos[f][0]['delegacion']; 

    $datos_cliente=array("id"=>$id_cliente,"nombre"=>$datos[f][0]['cliente'],
    "identificador"=>$datos[f][0]['identificador'],
    "comentario"=>$datos[f][0]['comentario']);


    // Recuperacion de contraseñas.
    if($tabla_enviar=="clientes"){

        $mail->Body=body_email(c,$datos_cliente,$datos[f][0]['id_delegacion']);

    }else if($tabla_enviar=="booking"){
        
        ### APARTE DE INCLUIR UN BODY AL EAMIL, TENGO QUE GUARDARLO EN LA BBDD EN 
        ## booking_emails 
        $tipo = isset($email_disponibilidad) && $email_disponibilidad==1 ? r : t;      
        $mail->Body=body_email($tipo,$datos_cliente,$datos[f][0]['id_delegacion'],$summernote);

        // 111111$
        $find=array("´","'","’","€");
        $changue=array('','','',"&euro;");
        $summernote = str_replace($find,$changue, $summernote);

        $DB->opentable('booking_emails');
        $DB->ta['id_booking']=$id_booking;
        $DB->ta['email']=$summernote;
        $DB->ta['usuario']=$_SESSION['nombre_usuario'];
        $DB->ta['creacion']=now();
        $DB->insert();

        $DB->query('update booking set id_estado=2 where id='.$id_booking);
        // vacio el txtarea y quito el emsil de dispo, x si queremo escribir 
        // txt aparte.
        script('$("#summernote_form_enviar_email").summernote("code","");
            $("[name=\'email_disponibilidad\']").val(0);
            $(\'[name="txt_recuperar_email"]\').val("");
            opener.location.reload();');


    }else if($tabla_enviar=="reservas_realizadas"){

        if(isset($email_pago_rsv)){

            $txt="Hola ".utf8_encode($datos[f][0]['cliente']).",
             <br><br>
            Te recordamos que pr&oacute;ximamente, entra en periodo de gastos la reserva con localizador ".$data_reserva[f][0]['localizador']." por un importe total de ".($data_reserva[f][0]['total']/100)." &euro;.
            <br><br>
            Puedes realizar el abono mediante tarjeta en el siguiente enlace:
            <br><br>
            <div style='width:100%; text-align: center; padding:10px 0 10px 0'>
                <a href='https://".$datos[f][0]['web']."/dulceria/functions/ajax.php?id_reserva_email=".$id_reserva."'
                    style='font-family: Playfair Display,serif; border-radius: 1px; font-weight: 600;border-width: 0;
                    padding: 8px 25px; transition: color .5s , border-color .5s , background-color .5s; font-size: 16px;
                    padding: 10px 35px; color: #fff; background-color: #".$datos[f][0]['color_email'].";
                    text-decoration:none;'>
                    PAGAR MI RESERVA
                </a>
                <br><br>
            </div>
            Tambi&eacute;n puedes realizar el abono realizando una transferencia a la siguiente cuenta:
            <br><br>
            ES34 0237 0626 20 9165790410
            <br><br>";

        }else if(isset($reenviar_voucher)){

            $data_voucher = json_decode(utf8_encode($data_reserva[f][0]['voucher']),true);


            

            $url = $id_reserva<=93044 ?  'ver_voucher' : 'descargar_documentacion';

            

            $txt='Hola '.utf8_encode($datos[f][0]['cliente']).'
                <br><br>
                Te recordamos los todos los datos de siguiente la reserva:
                <br><br>
                <div style="width:100%; 
                border-bottom:#CCC 1px dotted; 
                border-top:#CCC 1px dotted;
                padding:10px 0 10px 0">
                <table style="width:100%; " >
                    <caption style="height:40px; "><b>LOCALIZADOR '.$data_reserva[f][0]['localizador'].'</b></caption>
                    <tbody>
                        <tr>
                            <td style="width:50%;">Titular de la Reserva:</td>
                            <td style="width: 50%;"><b>'.$data_voucher['titular_reserva'].'</b></td>
                        </tr>
                        <tr>                  
                            <td style="width:50%;">Fecha de Entrada:</td>
                            <td style="width: 50%;"><b>'.formatear_fecha($data_reserva[f][0]['entrada'],m,i).'</b></td>
                        </tr>
                        <tr>
                            <td style="width:50%;">Fecha de Salida:</td>
                            <td style="width: 50%;"><b>'.formatear_fecha($data_reserva[f][0]['salida'],m,i).'</b></td>
                        </tr>
                        <tr>
                            <td style="width:50%;">Tury-Checks Gastados:</td>
                            <td style="width: 50%;"><b>'.$data_reserva[f][0]['bonos_gastados'].'</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br>
            Para descargar la documentaci&oacute;n que tienes que llevar al hotel s&oacute;lo tienes que pulsar el siguiente boton:
            <br><br>
            <div style="width:100%; text-align: center;
                border-bottom:#CCC 1px dotted; 

                padding:10px 0 10px 0">
            
            <a href="https:'.$datos[f][0]['web'].'/dulceria/functions/'.$url.'.php?id_voucher='.$id_reserva.'"
                style="font-family: Playfair Display,serif; border-radius: 1px; font-weight: 600;border-width: 0;
                padding: 8px 25px; transition: color .5s , border-color .5s , background-color .5s; font-size: 16px;
                padding: 10px 35px; color: #fff; background-color: #'.$datos[f][0]['color_email'].';
                text-decoration:none;">
                DESCARGAR DOCUMENTACION PARA EL HOTEL
                </a>
                <br><br>
            <b style="font-family: Playfair Display,serif;">Te agradecemos que hayas confiado en nosotros y te deseamos una Feliz Estancia!</b>
            </div>';

        } //if(isset($email_pago_rsv)){

        $mail->Body=body_email(t,$datos_cliente,$datos[f][0]['id_delegacion'],$txt);

    }else if(isset($email_pago_manual) && $tabla_enviar==""){

        $textos_pago = 'Enhorabuena '.utf8_encode($datos[f][0]['cliente']).',<br><br> Hemos recibido satisfactoriamente el pago por un importe de <b>'.number_format($total_final,2,".","").'</b> &euro; en concepto de pago de la reserva con localizador <b>'.$localizador.'</b>
                    <br><br>Esperamos que la disfrutes !';

        $mail->Body=body_email(t,$datos_cliente,$datos[f][0]['id_delegacion'],$textos_pago);
                
    }else if(isset($email_devolucion_manual) && $tabla_enviar==""){

        $textos_pago = 'Estimado '.utf8_encode($datos[f][0]['cliente']).',<br><br> Te confirmamos que te hemos enviado una devoluci&oacute;n  un importe de <b>'.number_format($total_final,2,".","").'</b> &euro; en concepto de modificaci&oacute;n o cancelaci&oacute;n de la reserva con localizador <b>'.$localizador.'</b>
            <br><br>Recibe un cordial Saludo.';

        $mail->Body=body_email(t,$datos_cliente,$datos[f][0]['id_delegacion'],$textos_pago);
                
    }else if(isset($enviar_email_pago) && $enviar_email_pago==1){

        $textos_pago="Hola ".utf8_encode($datos[f][0]['cliente']).",
             <br><br>
            Tal y como le hemos informado recientemente en nuestra conversaci&oacute;n le hacemos llegar este correo para que pueda realizar el abono por la cantidad de  ".number_format($importe_email_pago,2,".","")." &euro; a trav&eacute;s de nuestra pasarela de pago virtual.
            <br><br>
            Puedes realizar el abono mediante tarjeta en el siguiente enlace:
            <br><br>
            <div style='width:100%; text-align: center; padding:10px 0 10px 0'>
                <a href='https://".$datos[f][0]['web']."/dulceria/functions/ajax.php?id_reserva_email=0&id_cliente=".$id_cliente."&importe_personalizado=".($importe_email_pago*100)."'
                    style='font-family: Playfair Display,serif; border-radius: 1px; font-weight: 600;border-width: 0;
                    padding: 8px 25px; transition: color .5s , border-color .5s , background-color .5s; font-size: 16px;
                    padding: 10px 35px; color: #fff; background-color: #".$datos[f][0]['color_email'].";
                    text-decoration:none;'>
                    PAGAR EL IMPORTE 
                </a>
                <br><br>
            </div>
            Tambi&eacute;n puedes realizar el abono realizando una transferencia a la siguiente cuenta:
            <br><br>
            ES34 0237 0626 20 9165790410
            <br><br>";

            $mail->Body=body_email(t,$datos_cliente,$datos[f][0]['id_delegacion'],$textos_pago);

    }

    if($mail->send()){
     alert("Envio Realizado");

    }else{
       alert("Envio No Realizado"); 
    }

// eof }else if($enviar_email){
}else if(isset($enviar_peticiones_booking) && $enviar_peticiones_booking==1){

    /*
    sacar todas las pendientes.
    si son + de 0, bucle 
    */

    // sacamos si tenemos pendientes.
    $DB->query('select id,id_cliente,peticiones,historico from booking where id_estado=3 and gestion_manual=0 order by id ASC');

    if($DB->rows()>0){

        $DB2 = new DataAccess();

            $p=0;
            while($data = $DB->fetch()){
            //echo "<br>".$data['id'];
                $id_cliente=$data['id_cliente'];
                $id=$data['id'];
                $historico=$data['historico'];
                $peticion_xml=$data['peticiones'];


                $xml[$p] = new SimpleXMLElement($data['peticiones']);
                $xml[$p] = json_encode($xml[$p]);
                $peticiones[$p] = json_decode($xml[$p],TRUE);


                ##################################################################
                #
                # version 1.2:
                # añadir + posiciones al xml peticiones.
                # filtros de estrellas 
                # 2ª peticion para el resultado sin disponibilidad.
                # 
                # mejora del body email solo con el id del cliente.
                #
                ##################################################################

            #### PONER EL AVISADOR DE PETICIONES VACIAS 
                ## ACTIVAR LOS CAMBIOS EN LA BBDD 

                // 1º tenemos que determinar que no este buscada.
                if($peticiones[$p]['peticion']['gestionada']==0){

                    // ahora acortamos las dimensiones del array
                    $peticion[$p]=$peticiones[$p]['peticion'];
                    $habitaciones[$p]=$peticion[$p]['habitaciones'];
                    $zonas[$p]=$peticion[$p]['zonas'];

                    //2º sacar las $ generales a las zonas
                    $generales[$p]=array(
                        "fecha1" => trim($peticion[$p]['entrada']),
                        "fecha2" => trim($peticion[$p]['salida']),
                        "habitacion" => trim($habitaciones[$p]['numero'])
                    // eof $generales[$p]=array(    
                    );


                    // añadimos dimension a 1 solo result.
                    if($habitaciones[$p]['numero']==1){
                        $habitaciones[$p]['habitacion'][0]=$habitaciones[$p]['habitacion'];
                    //eof                     if($habitaciones[$p]['numero']==1){
                    }

                    //montamos las ocupaciones.
                    $ocupantes_division=0;
                    $hb=0;
                    for($h=1;$h<=$habitaciones[$p]['numero'];$h++){

                        $generales[$p]['adultos_'.$h]=$habitaciones[$p]['habitacion'][$hb]['adultos'];
                        $ocupantes_division=$generales[$p]['adultos_'.$h]+$ocupantes_division;
                        $generales[$p]['ninos_'.$h]=$habitaciones[$p]['habitacion'][$hb]['ninos'];


                        if($generales[$p]['ninos_'.$h]>0){

                            if($generales[$p]['ninos_'.$h]==1){
                                $habitaciones[$p]['habitacion'][$hb]['edades']['edad'][0]=$habitaciones[$p]['habitacion'][$hb]['edades']['edad'];
                            }
                            
                            $e=0;
                            for($n=1;$n<=$generales[$p]['ninos_'.$h];$n++){
                               $generales[$p]['edad_'.$h.'_'.$n]=$habitaciones[$p]['habitacion'][$hb]['edades']['edad'][$e];
                               if($generales[$p]['edad_'.$h.'_'.$n]>2){
                                $ocupantes_division++;
                               } 
                            $e++;   
                            }
                        }
                    $hb++;
                    // eof for($h=1;$h<=$habitaciones[$p]['numero'];$h++){
                    }

                    // aqui vamos a determinar el divisorio xra sacar el preu per/día.

                    $dias_division=comparar_fechas($peticion[$p]['entrada'],$peticion[$p]['salida']);
                    $total_division=$dias_division*$ocupantes_division;

                    // 3º determinar el nº de zonas que vamos a realizar para este email.

                    $n_zonas=count($zonas[$p]['zona']);

                    // añadimos la dimension 0 a 1 solo result
                    if(!isset($zonas[$p]['zona'][0])){
                        $zonas[$p]['zona'][0]=$zonas[$p]['zona'];
                        $n_zonas=1;
                    //  eof if(!isset($zonas[$p]['zona'][0])){   
                    }else{
                        $n_zonas=count($zonas[$p]['zona']);
                    }

                    $cuerpo_email[$p]="Fecha de Entrada: ".$generales[$p]['fecha1']."
                    <br> Fecha de Salida: ".$generales[$p]['fecha2']."
                    <br><br> N&ordm; de Habitaciones: ".$habitaciones[$p]['numero']."
                    <br> Distribuci&oacute;n de las Habitaciones:";

                    $hb=0;
                    for($h=1;$h<=$habitaciones[$p]['numero'];$h++){

                        $cuerpo_email[$p].="<br>Habitaci&oacute;n ".$h."&#170; - 
                         Adultos ".$habitaciones[$p]['habitacion'][$hb]['adultos'] ." / Ni&ntilde;os ".$habitaciones[$p]['habitacion'][$hb]['ninos'];

                        
                        if($habitaciones[$p]['habitacion'][$hb]['ninos']>0){

                            if($habitaciones[$p]['habitacion'][$hb]['ninos']==1){
                              $cuerpo_email[$p].=" (Edades: ".  $habitaciones[$p]['habitacion'][$hb]['edades']['edad']." a&ntilde;os)";
                            }else if($habitaciones[$p]['habitacion'][$hb]['ninos']>1){

                                $e=0;
                                $cuerpo_email[$p].=" (Edades: ";
                                for($n=1;$n<=$habitaciones[$p]['habitacion'][$hb]['ninos'];$n++){

                                    if($n==$habitaciones[$p]['habitacion'][$hb]['ninos']){
                                        $cuerpo_email[$p].=$habitaciones[$p]['habitacion'][$hb]['edades']['edad'][$e];
                                    }else if($n<$habitaciones[$p]['habitacion'][$hb]['ninos']){
                                        $cuerpo_email[$p].=$habitaciones[$p]['habitacion'][$hb]['edades']['edad'][$e]. ",";
                                    }
                                  
                                $e++;   
                                }
                                $cuerpo_email[$p].=" a&ntilde;os)";

                            }
                            

                        }
                    $hb++;
                    // eof for($h=1;$h<=$habitaciones[$p]['numero'];$h++){
                    }



                    for($z=0;$z<$n_zonas;$z++){

                        // aqui vamos a montar el cartel que define cada una de las 
                        // xonas que van detro de una peticion concreta:

                        if($zonas[$p]['zona'][$z]['hotel']!=0){
                            $busqueda = $DB2->select('nombre','_hoteles','id='.$zonas[$p]['zona'][$z]['hotel']);
                            $frase_sin_dispo='este hotel';  
                        }else if($zonas[$p]['zona'][$z]['segmento']!=0){
                            $busqueda = $DB2->select('nombre','_segmentos','id='.$zonas[$p]['zona'][$z]['segmento']);
                            $frase_sin_dispo='esta poblaci&oacute;n'; 
                        }else if($zonas[$p]['zona'][$z]['poblacion']!=0){
                            $busqueda = $DB2->select('nombre','_poblaciones','id='.$zonas[$p]['zona'][$z]['poblacion']);
                            $frase_sin_dispo='esta poblaci&oacute;n'; 
                        }else if($zonas[$p]['zona'][$z]['provincia']!=0){
                            $busqueda = $DB2->select('nombre','_provincias','id='.$zonas[$p]['zona'][$z]['provincia']);
                            $frase_sin_dispo='esta zona'; 
                        }

                        $destino = $DB2->select('destino,nombre','_provincias','id='.$zonas[$p]['zona'][$z]['provincia']);

                        if($destino[f][0]['destino']==1){
                            $tipo_destino="Vacacional";
                        }else if($destino[f][0]['destino']==2){
                            $tipo_destino="Urbano";
                        }

                        $cuerpo_email[$p].="<br><br> <b><u>Resultados de ".utf8_encode($busqueda[f][0]['nombre'])." (".$tipo_destino.")</b></u>";
                 
                        // oef cartel

                        $zona[$z]=array(
                            "provincia"=>$zonas[$p]['zona'][$z]['provincia'],
                            "poblacion"=>$zonas[$p]['zona'][$z]['poblacion'],
                            "segmento"=>$zonas[$p]['zona'][$z]['segmento'],
                            "idhotel"=>$zonas[$p]['zona'][$z]['hotel'],
                            "regimen"=>$zonas[$p]['zona'][$z]['regimen'],
                            "destino"=>$destino[f][0]['destino']
                        );


                    $url[$z]=array_merge($generales[$p],$zona[$z]);


                    $urls[$z]=my_json_encode($url[$z]);

                    //echo "<br><br>" . $urls[$z]."<br><br>";


                    $PET[$z]=new control($url[$z]);

                    //if(!isset($_SESSION['sessionEuro'])){
                    //$PET[$z]->abrirSession();
                    // }else if(isset($_SESSION['sessionEuro'])){
                    //  $PET[$z]->SessionIgnorar();
                    // }


                    $listado[$z]=$PET[$z]->disponibilidad_segmentada($id_cliente,$id,$urls[$z],1);

                    if(count($listado[$z])==0){
                        // aqui 
                        $cuerpo_email[$p].=' <br><br>En '.$frase_sin_dispo.' no tenemos disponibilidad, en breve le enviaremos otra b&uacute;squeda de los hoteles mas cercanos.';

                        // aqui meteremos la mejora , de que 
                        // realice una segunda peticion.

                        $mail = new phpmailer("booking@soltury.com");
                        $mail->setFrom("booking@soltury.com","Peticion Vacia");
                        $mail->addAddress("booking@soltury.com");
                        $mail->Subject='Peticion Sin resultados';
                        $mail->Body='La peticion con el '.$id .' No ha tenido resultados
                        mirar Manualmente';
                        $mail->send();

                    }else if(count($listado[$z])>0){

                        $limite=0;
                        $hoteles_listados=0;
                        foreach ($listado[$z] as $ide => $datos) {
                            $limite++;

                            if($limite<=$zonas[$p]['zona'][$z]['resultados']){

                                $n_distr[$ide]=count($datos['listadoPrecios']);

                                    // tenemos que montar por aqui el añadido de 
                                    // 1 solo regimen y el de precio maximo.

                                    //// AQUI TENEMOS QUE PONER LIBERTAR DE ELECCION A BOOKING 
                                    // CUANDO VENGAN SELECCIONADOS
                                    if($url[$z]['destino']==2){

                                        $SA[$ide]=0;
                                        $AD[$ide]=0;

                                        $MP[$ide]=1;
                                        $PC[$ide]=1;
                                        $TI[$ide]=1;

                                    }else if($url[$z]['destino']==1){

                                        $SA[$ide]=1;
                                        $AD[$ide]=1;

                                        $MP[$ide]=0;
                                        $PC[$ide]=0;
                                        $TI[$ide]=0;

                                    }

                                    // nuevo array con las hb
                                    $n_datos=array();


                                    //unset();
                                    
                                    // nuevo nº de hb segun coincidencias
                                    $in=0;

                                    ### AQUI VA EL NUEVO FILTRO DE LAS HABITACIONES
                                    $arr_hb_out = array('shared',
                                        'compartida',
                                        'compartido',
                                        'no wc/ducha',
                                        'sin baño',
                                        'sin bano',
                                        'Sin Baño',
                                        'Sin Bano');

                                    for($r=0;$r<$n_distr[$ide];$r++){

                                        if(strposa(strtolower($datos['listadoPrecios'][$r]['nombreHab']),$arr_hb_out)==false){

                                            //$datos['listadoPrecios'][$r]['nombreHab'];

                                            if($datos['listadoPrecios'][$r]['abrevReg']=="SA" && $SA[$ide]==0){
                                                // añadido este paso aqui, que realiza el filtro del regimen, 
                                                // con la mejora que evalua cada uno de los regiemenes y no el hotel alcompleto
                                                
                                                # con este paso afinamos el error de que sacaba los que hay por encima de 100.00
                                                $comparativa[$r]['total']=str_replace(",",".",$datos['listadoPrecios'][$r]['total']);
                                                
                                                if($zonas[$p]['zona'][$z]['precios']>0 && $comparativa[$r]['total']<=$zonas[$p]['zona'][$z]['precios'] || $zonas[$p]['zona'][$z]['precios']==0){

                                                    $n_datos['listadoPrecios'][$in]=$datos['listadoPrecios'][$r];
                                                    $SA[$ide]=1;
                                                    $in++;
                                                }
                                            
                                            }// if($datos['listadoPrecios'][$r]['abrevReg']=="SA" && $SA[$ide]==0){

                                            if($datos['listadoPrecios'][$r]['abrevReg']=="AD" && $AD[$ide]==0){
                                                
                                                $comparativa[$r]['total']=str_replace(",",".",$datos['listadoPrecios'][$r]['total']);
                                                
                                                if($zonas[$p]['zona'][$z]['precios']>0 && $comparativa[$r]['total']<=$zonas[$p]['zona'][$z]['precios'] || $zonas[$p]['zona'][$z]['precios']==0){
                                                    $n_datos['listadoPrecios'][$in]=$datos['listadoPrecios'][$r];
                                                    $AD[$ide]=1;
                                                    $in++;
                                                }
                                            
                                            } // if($datos['listadoPrecios'][$r]['abrevReg']=="AD" && $AD[$ide]==0){

                                            if($MP[$ide]==0 && $datos['listadoPrecios'][$r]['abrevReg']=="MP"){
                                                
                                                $comparativa[$r]['total']=str_replace(",",".",$datos['listadoPrecios'][$r]['total']);
                                                
                                                if($zonas[$p]['zona'][$z]['precios']>0 && $comparativa[$r]['total']<=$zonas[$p]['zona'][$z]['precios'] || $zonas[$p]['zona'][$z]['precios']==0){
                                                    $n_datos['listadoPrecios'][$in]=$datos['listadoPrecios'][$r];
                                                    $MP[$ide]=1;
                                                    $in++;
                                                }
                                            
                                            }// if($MP[$ide]==0 && $datos['listadoPrecios'][$r]['abrevReg']=="MP"){ 
                                                       
                                            if($PC[$ide]==0 && $datos['listadoPrecios'][$r]['abrevReg']=="PC"){
                                                
                                                $comparativa[$r]['total']=str_replace(",",".",$datos['listadoPrecios'][$r]['total']);
                                                
                                                if($zonas[$p]['zona'][$z]['precios']>0 && $comparativa[$r]['total']<=$zonas[$p]['zona'][$z]['precios'] || $zonas[$p]['zona'][$z]['precios']==0){
                                                    $n_datos['listadoPrecios'][$in]=$datos['listadoPrecios'][$r];
                                                    $PC[$ide]=1;
                                                    $in++;
                                                }
                                            
                                            }//if($PC[$ide]==0 && $datos['listadoPrecios'][$r]['abrevReg']=="PC"){

                                            if($TI[$ide]==0 && $datos['listadoPrecios'][$r]['abrevReg']=="TI"){
                                                
                                                $comparativa[$r]['total']=str_replace(",",".",$datos['listadoPrecios'][$r]['total']);
                                                
                                                if($zonas[$p]['zona'][$z]['precios']>0 && $comparativa[$r]['total']<=$zonas[$p]['zona'][$z]['precios'] || $zonas[$p]['zona'][$z]['precios']==0){

                                                    $n_datos['listadoPrecios'][$in]=$datos['listadoPrecios'][$r];
                                                    $TI[$ide]=1;
                                                    $in++;
                                                }
                                            
                                            }//if($TI[$ide]==0 && $datos['listadoPrecios'][$r]['abrevReg']=="TI"){
                                                     
                                        } //  if(preg_match('/(shared)( ....

                                    } // eof  for($r=0;$r<$n_distr[$ide];$r++){

                                    // muevo total de regimenes x hotel.
                                    $total_distribuciones[$ide]=$in;

                                  

                                    ###### EN CASO DE QUE TENGAMOS + DE 1 REGIMEN SACAMOS EL HOTEL.
                                    if($total_distribuciones[$ide]>0){
        
                                        $nombre_hotel=$DB2->select('nombre,provincia, poblacion,id_estrellas','_hoteles','id='.$ide);

                                        // $arr_foto = json_decode($nombre_hotel[f][0]['fotos']);

                                        // $n_fotos = count($arr_foto);

                                        $nombre_hotel[f][0]['nombre']= tildes_min_may($nombre_hotel[f][0]['nombre'],h);

                                        $cuerpo_email[$p].="<br><br>".strtoupper($nombre_hotel[f][0]['nombre']) 
                                        ." ".$nombre_hotel[f][0]['id_estrellas']." * - ".utf8_encode($nombre_hotel[f][0]['poblacion']);
                                                    
                                                
                                        $hoteles_listados++;
                                                
                                        for($i=0;$i<$total_distribuciones[$ide];$i++){

                                            $data=$n_datos['listadoPrecios'][$i];
                                            $data['total']=format_number($data['total']);
                                            $precio_division=$data['total']/$total_division;

                                            if($data['proveedor']=="id_bedsonline" ){
                                                $data['nombreHab']=utf8_decode($data['nombreHab']);
                                            }
                                            
                                            $data['nombreHab']=tildes_min_may($data['nombreHab'],b);

                                            ## aqui vamos a meter el filtro del texto que aparezca en la hb
                                            $arr_hbs_txt = array(", Guest Room, 1 Queen Or 2 Twin/single Bed(s) - Genr - Tbdb",
                                                ", Queen-size Bed With Convertibles Ofa ( No Breakfast )",
                                                "...lounge Access, Free Breakfast/drinks...bathrobe, Special Amenities, Wifi",
                                                "+ Rollaway Bed, Guest Room, 2 Twin/single Bed(s)",
                                                "+ Rollaway Bed, Guest Room, 2 Twin/single Bed(s) - Famz - Tbdf",
                                                "( None )",
                                                "...comp Wifi/herman Miller Chair/32 Inch Sat Tv...serta Bed/mini Fridge/pull Out Sofa",
                                                "...comp Wifi/herman Miller Chair/32 Inch Sat Tv...serta Bed/mini Fridge/24hrs Pavillion Pantry",
                                                "1 King Or 2 Twin/single Bed(s) - Genr - Tbdb",
                                                "Guest Room, Triple Room - Hotl - Tbde","With Private Bathroom (single Or Double Bed)",
                                                "Baño Privado",
                                                "1 King Or 2 Twin/single Bed(s), City View - Genr - Duqc",
                                                "..comp Wifi/herman Miller Chair/32 Inch Sat Tvs...living Room W/ Sofa/2nd Bathroom/serta Bed");

                                                $data['nombreHab']=str_replace($arr_hbs_txt,"",$data['nombreHab']);
                                            $cuerpo_email[$p].="<br>".$data['nombreHab']." (<b>".$data['abrevReg']."</b>) ".$data['total']."&euro; (".number_format($precio_division,2,".","")."&euro; Per/D&iacute;a)";

                                        }//for($i=0;$i<$in;$i++){

                                    } // if($total_distribuciones[$ide]>0){

                                }//if($limite<=$zonas[$p]['zona'][$z]['resultados']){
                        
                            }// foreach ($listado[$z] as $ide => $datos) {

                            // si no traemos ningun hotel por haberlo eliminado de las opciones 
                            if($hoteles_listados==0){
                                $cuerpo_email[$p].="Debido al limite de presupuesto o filtro de regimen no hemos obtenido ning&uacute;n resultado para esta b&uacute;squeda.";
                            }
                    
                        }// eof }else if(count($listado[$z])>0){
                         
                    }//eof for($z=0;$z<$n_zonas;$z++){

                    // aqui es donde tengo que meter el email.
                    // si tengo 1 solo regimen, lo mando.
                    // si tengo 0 me traigo todo. 
                    // si tengo varios, los filtro aqui.
                    // aqui vamos a sacar todos los datos 

                    // para la personalizacion de cliente y marca
                    $datos=$DB2->inner_join('c.nombre as cliente,
                    d.web,
                    d.nombre as delegacion,
                    c.id_delegacion,
                    c.identificador,
                    c.comentario,
                    d.email2 as remitente,
                    c.email as destinatario','clientes as c inner join delegaciones as d',
                    'c.id_delegacion = d.id and c.id='.$id_cliente,f);

                    //var_dump($datos);

                    $mail = new phpmailer($datos[f][0]['remitente']);
                    $mail->setFrom($datos[f][0]['remitente'],$datos[f][0]['delegacion']);
                    


                    ### activacion del envio de hoteles a los clientes 

                    // $j_destinatarios = json_decode($datos[f][0]['destinatario']);

                    // $E=1;
                    // foreach ($j_destinatarios as $value) {
                                   
                    //     if($E==1){
                    //         $mail->AddAddress($value);
                    //     }else if($E>1){
                    //         $mail->AddCC($value);
                    //     }
                                    
                    //     $E++;
                    
                    // } // foreach ($j_destinatarios as $value) {


                    $mail->AddAddress("teresaotero@soltury.com");

                    $mail->Subject="Disponibilidad " .$datos[f][0]['delegacion']; 

                    $datos_cliente=array("id"=>$id_cliente,"nombre"=>$datos[f][0]['cliente'],
                    "identificador"=>$datos[f][0]['identificador'],
                    "comentario"=>$datos[f][0]['comentario']);
                  
                    $mail->Body=body_email(r,$datos_cliente,$datos[f][0]['id_delegacion'],$cuerpo_email[$p]);
                    

                    if($mail->send()){

                        # GUARDAMOS LO ENVIADO:
                        $DB2->openTable('booking_emails');
                        $DB2->ta['id_booking']=$id;
                        $DB2->ta['email']=$DB2->textCheck($cuerpo_email[$p]);
                        $DB2->ta['usuario']='Automatic';
                        $DB2->ta['creacion']=now();
                        $id_booking_emails=$DB2->insert();

                        # MODIFICAMOS EL BOOKING:
                        $DB2->openTable('booking');
                        $DB2->edit($id);
                        $DB2->ta['id_estado']=2;

                        $nuevo_historico = sumar_historico_xml($historico,'Automatic','email',$id_booking_emails);
                        $DB2->ta['historico']=$nuevo_historico;
                        $nueva_peticion=new SimpleXMLElement($peticion_xml);
                        $nueva_peticion->peticion->gestionada=1;
                        $DB2->ta['peticiones']=$nueva_peticion->asXML();
                        $DB2->update();
                      
                    }else{
                    
                        # MODIFICAMOS EL BOOKING, XRA NO REENVIE ESTE EMAIL
                        $DB2->openTable('booking');
                        $DB2->edit($id);
                        $DB2->ta['id_estado']=15;
                        $DB2->update();
                        
                        $mail->FromName=$datos[f][0]['delegacion'];
                        $mail->From=$datos[f][0]['remitente'];
                        $mail->addAddress($EMAIL_ADMINISTRADOR);
                        $mail->Subject='Error Peticiones Automatic';
                        $mail->Body='Ha sucedido un error en el envio de la peticion con ID id '.$id;
                        $mail->send();

                    } // if($mail->send()){ ...  else{....}
                 
                } // eof if($peticiones[$p]['peticion']['gestionada']==0){

                $p++;
              
            } // eof while($data = $DB->fetch()){ 

    // if($DB->rows()>0){        
    }else if($DB->rows()<=0){

        exit();
    }

// eof }else if($enviar_peticiones_booking){ 
}else if(isset($realizar_busqueda_hotel)){

    #####################
    #
    # id_cliente
    # id_booking
    # POST
    #  ÚLTIMA $ QUE LE PASAMPOS A disponibilidad, es la manual o auto.
    #
    # edad_max_resultados:20
    # edad_max_precios:0
    #

    /* PARA QUE TODO VUELVA A LA NORMALIDAD
    TENEMOS QUE QUITAR DE CONTROLLER ESTO

       $_SESSION['admin_ok']=1;
   $mostrar=1;            
               

               Y DE AJAX VOLVER A PODER A 1 ESTAS $

                    $SA[$ide]=0;
                    $AD[$ide]=0; 

               */
    // ESTO LO USAMOS EN AMBAS OPCIONES
    $find=array("´","'","’","€");
    $changue=array('','','',"&euro;");
    $_POST = str_replace($find,$changue, $_POST);
    $url_json = json_encode_bbdd($_POST);

    // VOY A METER AQUI LA OPCION DE QUE SE GUARDE LA PETICION 
    if(isset($guardar_busqueda_booking) && $guardar_busqueda_booking==1){  

            //insertamos pet_xml para hacer debajo el seguimiento.
            $DB->query("insert into _xml_peticiones (
            busqueda,
            dominio,
            id_booking,
            id_cliente,
            creacion) values (
            '".$url_json."',
            '".$_SERVER['HTTP_HOST']."',
            ".$id_booking.",
            ".$id_cliente.",
            '".date('Y-m-d H:i:s')."')");

            script("$('#guardar_busqueda_booking').val(0);");
            noty('Busqueda Guardada');

    
    }else{  
        
        sleep(3);
        $C=new control($_POST);

        // if(isset($realizar_busqueda_hotel_segmentada)){

        if(!isset($sin_filtros_regimen)){
            $sin_filtros_regimen=0;
        }

        $listado=$C->disponibilidad_segmentada($id_cliente,$id_booking,$url_json,0,$sin_filtros_regimen);

        if(count($listado)==0){

            echo '<div class="well">Esta B&uacute;squeda no tiene Resultados. Tienes la informaci&oacute;n de cada proveedor mas abajo.</div>';

        }else if($listado>0){

            // sacamos el nº de ocupantes + dias, para la division 
            // persona y día.
                $ocupantes_division=0;
                $hb=0;
                $distribucion_email="<b>Entrada</b>: ".$fecha1." <br><b>Salida</b>: ".$fecha2." <br><br><b>N&ordm; De Habitaciones</b>: ".$habitacion;
                for($h=1;$h<=$habitacion;$h++){


                    $distribucion_email.="<br><b>Habitaci&oacute;n ".$h."&ordf;</b> ".${'adultos_'.$h}." Adulto/s - ".${'ninos_'.$h}." ni&ntilde;o/s " ;
                    $ocupantes_division=${'adultos_'.$h}+$ocupantes_division;
                            
                    if(${'ninos_'.$h}>0){
                        
                        $distribucion_email.="(";              
                        for($n=1;$n<=${'ninos_'.$h};$n++){
                            
                            $distribucion_email.=${'edad_'.$h.'_'.$n}." a&ntilde;o/s";

                            if($n<${'ninos_'.$h}){
                                $distribucion_email.=",";
                            }

                            if(${'edad_'.$h.'_'.$n}>2){
                                $ocupantes_division++;
                            } 
                                   
                        }
                        $distribucion_email.=")";
                    }
                $hb++;
                // eof for($h=1;$h<=$habitaciones[$p]['numero'];$h++){
                }


                // aqui sacamos el total de lo que tenemos que dividir.
                $dias_division=comparar_fechas($fecha1,$fecha2);
                $total_division=$dias_division*$ocupantes_division;


            // este es el contenerdor al que agergamos 
            // tots los hoteles.
            echo "<hr><div class='row' id='contenedor_listados_hoteles'>";

            // resultados -> edad_max_resultados 20
            // aqui traemos el filtro de los resultados

            $limite_hoteles = $edad_max_resultados=="" || $edad_max_resultados==0 ? 25 : $edad_max_resultados;

            $limite=0;
            $hotel_mostrado=0;

            
            $tabla_busqueda='';

            if($idhotel==0 && $segmento==0){

                $tabla_busqueda = $poblacion>0 ? 'poblaciones' : 'provincias';
                $campo_busqueda = $poblacion>0 ? 'poblacion' : 'provincia';
                $valor_busqueda = $poblacion>0 ? $poblacion : $provincia;

                $DB->query('select id_soltury from _hoteles_'.$tabla_busqueda.' where id_'.$campo_busqueda.'_solt='.$valor_busqueda);

                while($data=$DB->fetch()){

                    $id_hoteles_mostrar[] = $data['id_soltury'];

                }
            }


            foreach ($listado as $ide => $datos) {

                // filtro de hoteles que no pertenezcan a la zona, provincia
                if($tabla_busqueda=="" || in_array($ide,$id_hoteles_mostrar)){

                    // metemos el limite nada mas empezar.
                    $limite++;

                    if($limite<=$limite_hoteles){

                        // si el filtro es activo:
                        if(isset($filtro_hb_listados) && $filtro_hb_listados==1){
                            // generamos clave vacacional - urbano

                            if(isset($sin_filtros_regimen) && $sin_filtros_regimen==1){

                                $SA[$ide]=0;
                                $AD[$ide]=0;

                                $MP[$ide]=0;
                                $PC[$ide]=0;
                                $TI[$ide]=0;


                            }else if($destino==2){

                                $SA[$ide]=0;
                                $AD[$ide]=0;

                                $MP[$ide]=1;
                                $PC[$ide]=1;
                                $TI[$ide]=1;

                            }else if($destino==1){

                                $SA[$ide]=1;
                                $AD[$ide]=1;

                                $MP[$ide]=0;
                                $PC[$ide]=0;
                                $TI[$ide]=0;

                            }

                            $n_datos=Array();
                            $n_distr[$ide]=count($datos['listadoPrecios']);
                            $in=0;



                            $arr_hb_out = array('shared',
                                            'compartida',
                                            'compartido',
                                            'no wc/ducha',
                                            'sin baño',
                                            'sin bano',
                                            'Sin Baño',
                                            'Sin Bano');

                            
                            // nuevo array que solo contiene 1 poscion x regimen permitido
                            // sobre el destino que pertenezca.
                            $edad_max_precios = number_format($edad_max_precios,2,".","");
                            for($r=0;$r<$n_distr[$ide];$r++){

                                if(strposa(strtolower($datos['listadoPrecios'][$r]['nombreHab']),$arr_hb_out)==false){

                                    if($datos['listadoPrecios'][$r]['abrevReg']=="SA" && $SA[$ide]==0){
                                        // añadido este paso aqui, que realiza el filtro del regimen, 
                                        // con la mejora que evalua cada uno de los regiemenes y no el hotel alcompleto
                                        
                                        # con este paso afinamos el error de que sacaba los que hay por encima de 100.00
                                        $comparativa[$r]['total']=str_replace(",",".",$datos['listadoPrecios'][$r]['total']);
                                        
                                        if($edad_max_precios>0 && $comparativa[$r]['total']<=$edad_max_precios
                                        || $edad_max_precios==0){

                                            $n_datos['listadoPrecios'][$in]=$datos['listadoPrecios'][$r];
                                            $SA[$ide]=1;
                                            $in++;
                                        }
                                    
                                    }// if($datos['listadoPrecios'][$r]['abrevReg']=="SA" && $SA[$ide]==0){

                                    if($datos['listadoPrecios'][$r]['abrevReg']=="AD" && $AD[$ide]==0){
                                        
                                        $comparativa[$r]['total']=format_number($datos['listadoPrecios'][$r]['total']);
                                        
                                        if($edad_max_precios>0 && $comparativa[$r]['total']<=$edad_max_precios
                                        || $edad_max_precios==0){
                                            $n_datos['listadoPrecios'][$in]=$datos['listadoPrecios'][$r];
                                            $AD[$ide]=1;
                                            $in++;
                                        }
                                    
                                    } // if($datos['listadoPrecios'][$r]['abrevReg']=="AD" && $AD[$ide]==0){

                                    if($MP[$ide]==0 && $datos['listadoPrecios'][$r]['abrevReg']=="MP"){
                                        
                                        $comparativa[$r]['total']=format_number($datos['listadoPrecios'][$r]['total']);
                                        

                                        if($edad_max_precios>0 && $comparativa[$r]['total']<=$edad_max_precios
                                        || $edad_max_precios==0){
                                            $n_datos['listadoPrecios'][$in]=$datos['listadoPrecios'][$r];
                                            $MP[$ide]=1;
                                            $in++;
                                        }
                                    
                                    }// if($MP[$ide]==0 && $datos['listadoPrecios'][$r]['abrevReg']=="MP"){ 
                                               
                                    if($PC[$ide]==0 && $datos['listadoPrecios'][$r]['abrevReg']=="PC"){
                                        
                                        $comparativa[$r]['total']=format_number($datos['listadoPrecios'][$r]['total']);
                                        
                                        if($edad_max_precios>0 && $comparativa[$r]['total']<=$edad_max_precios
                                        || $edad_max_precios==0){
                                            $n_datos['listadoPrecios'][$in]=$datos['listadoPrecios'][$r];
                                            $PC[$ide]=1;
                                            $in++;
                                        }
                                    
                                    }//if($PC[$ide]==0 && $datos['listadoPrecios'][$r]['abrevReg']=="PC"){

                                    if($TI[$ide]==0 && $datos['listadoPrecios'][$r]['abrevReg']=="TI"){
                                        
                                        $comparativa[$r]['total']=format_number($datos['listadoPrecios'][$r]['total']);
                                        
                                        if($edad_max_precios>0 && $comparativa[$r]['total']<=$edad_max_precios || $edad_max_precios==0){

                                            $n_datos['listadoPrecios'][$in]=$datos['listadoPrecios'][$r];
                                            $TI[$ide]=1;
                                            $in++;
                                        }
                                    
                                    }//if($TI[$ide]==0 && $datos['listadoPrecios'][$r]['abrevReg']=="TI"){

                                } //   if(strposa(strtolower...

                            } // eof  for($r=0;$r<$n_distr[$ide];$r++){

                            // muevo total de regimenes x hotel.
                            $total_distribuciones[$ide]=$in;
                        // if($filtro_hb_listados==1){
                        }else if(isset($filtro_hb_listados) && $filtro_hb_listados==0 || !isset($filtro_hb_listados)){

                            // si no esta activo el filtro de habitaciones
                            // lo ajustamos como si hubiera entrado y asi
                            // podemos ahacerlo todo sin distinciones.

                            $n_datos=Array();
                            # si el filtro de precios esta activo
                            if($edad_max_precios>0){

                                // aqui tiene que venir, lo mismo que arriba, pero al global de array que 
                                // de todos los hoteles. 
                               
                                $n_distr[$ide]=count($datos['listadoPrecios']);
                                $in=0;

                                for($r=0;$r<$n_distr[$ide];$r++){

                                    if(strposa(strtolower($datos['listadoPrecios'][$r]['nombreHab']),$arr_hb_out)==false){

                                        $comparativa[$r]['total']=format_number($datos['listadoPrecios'][$r]['total']);

                                        if($comparativa[$r]['total']<=$edad_max_precios){

                                            $n_datos['listadoPrecios'][$in]=$datos['listadoPrecios'][$r];
                                            $in++;

                                        } // if($datos['listadoPrecios'][$r]['precio']<=$edad_max_precios){

                                    } ///if(strposa(strtolower($....

                                } // for($r=0;$r<$n_distr[$ide];$r++){
                            
                                $total_distribuciones[$ide]=$in;

                            }else{

                                # si no esta activo, metemos el 100% de los resultados que vienen
                                $total_distribuciones[$ide] = count($datos['listadoPrecios']);
                                $n_datos = $datos;
                            } // if($edad_max_precios>0){..else....
                            
                        }   // }else if(isset($filtro_hb_listados) && $filtro_hb_listados==0 || .....

                    
                        # con esto elimino que aparezca cualquier hotel que no traiga ninguna 
                        # distribucion
                        if($total_distribuciones[$ide]>0){

                            $hotel_mostrado++;

                            $nombre_hotel=$DB->select('nombre,provincia, poblacion,id_estrellas','_hoteles','estado=0 and id='.$ide);

                            $nombre_hotel[f][0]['nombre']=tildes_min_may($nombre_hotel[f][0]['nombre'],h);

                            // abrimos contenedro de cada uno de los hoteles.
                            // asignamos info esencial.
                            echo '
                            <div class="col-xs-12">
                                <br>
                                <span class="info_hotel_modal" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" title="Haz Click para ver ficha" id_hotel="'.$ide.'">
                                    <i class="fa fa-plus-square text_1_4" data-toggle="modal" data-target="#informacion_hotel_modal"></i>
                                </span> - 
                                <input type="checkbox" name="check_hotel_body_email" id_hotel="'.$ide.'" data-placement="top" data-toggle="tooltip" title="Este check te llevas el hotel y todas las habitaciones que contenga">

                                <div class="filtro_nombre" style="display:inline;">
                                    '.strtoupper($nombre_hotel[f][0]['nombre']).'
                                </div>
                                <div class="filtro_estrellas" style="display:inline;">
                                    '.$nombre_hotel[f][0]['id_estrellas'].' * -
                                </div>
                                <div class="filtro_poblacion" style="display:inline;">
                                    '.utf8_encode($nombre_hotel[f][0]['poblacion']).' ('.utf8_encode($nombre_hotel[f][0]['provincia']).')
                                </div>
                                <i class="fa fa-location-arrow" data-placement="top" data-toggle="tooltip" title="Pincha aqui para poder modificar la ubicaci&oacute;n del Hotel" name="open_pop_ubicar_hotel" id_hotel="'.$ide.'"></i>
                                <hr>
                                <div class="caja_precios alert alert-info">
                            ';

                        
                            //echo "<br> tienes " . $total_distribuciones[$ide] . " habitaciones";
                            // for por limite de distribuciones. 
                            for($i=0;$i<$total_distribuciones[$ide];$i++){

                                $data=$n_datos['listadoPrecios'][$i];

                                if($data['proveedor']=="id_bedsonline" ){
                                    $data['nombreHab']=utf8_decode($data['nombreHab']);
                                }
                                                    
                                $data['nombreHab']=tildes_min_may($data['nombreHab'],b);

                                $arr_hbs_txt = array(", Guest Room, 1 Queen Or 2 Twin/single Bed(s) - Genr - Tbdb",
                                                ", Queen-size Bed With Convertibles Ofa ( No Breakfast )",
                                                "...lounge Access, Free Breakfast/drinks...bathrobe, Special Amenities, Wifi",
                                                "+ Rollaway Bed, Guest Room, 2 Twin/single Bed(s)",
                                                "+ Rollaway Bed, Guest Room, 2 Twin/single Bed(s) - Famz - Tbdf",
                                                "( None )",
                                                "...comp Wifi/herman Miller Chair/32 Inch Sat Tv...serta Bed/mini Fridge/pull Out Sofa",
                                                "...comp Wifi/herman Miller Chair/32 Inch Sat Tv...serta Bed/mini Fridge/24hrs Pavillion Pantry",
                                                "1 King Or 2 Twin/single Bed(s) - Genr - Tbdb",
                                                "Guest Room, Triple Room - Hotl - Tbde","With Private Bathroom (single Or Double Bed)",
                                                "Baño Privado",
                                                "1 King Or 2 Twin/single Bed(s), City View - Genr - Duqc",
                                                "..comp Wifi/herman Miller Chair/32 Inch Sat Tvs...living Room W/ Sofa/2nd Bathroom/serta Bed");


                                $data['nombreHab']=str_replace($arr_hbs_txt,"",$data['nombreHab']);

                                $data['total']=format_number($data['total']);
                                $precio_division=$data['total']/$total_division;

                                    // aqui metemos el form de cada hb
                                    // para poder empaquetar todos los datos.
           
                                echo '<div class="col-xs-12 hr" style="border-bottom: 1px solid #31708f;">
                                        <form action="" id="h_'.$ide.'_r_'.$i.'" method="post" accept-charset="utf-8">
                                        <input type="hidden" name="detalles_hotel_booking" value="1">';
                                
                                        // PARCHE W2M    

                                        if($data['abrevReg']==""){
                                            $data['abrevReg']="SA";
                                        }
                                        include('../includes/inc_variables_listados_hoteles.php');
                                            

                                        // ##### seccion de impresion en pantalla 
                                        echo '<input type="checkbox" name="check_body_email" id_hotel="'.$ide.'" class="pull-left" data-placement="top" data-toggle="tooltip" title="Este check te llevas el hotel y solo esta habitacion"> 
                                            <div class="col-xs-8" name="div_body_email">';

                                        if(isset($whatsapp) && $whatsapp==1){

                                             echo '<input type="hidden" name="whatsapp_body_email" value="'.$data['nombreHab'].' ('.$data['abrevReg'].') '.$data['total'].'&euro; - ('.number_format($precio_division,2,".","").'&euro; Per/D&iacute;a)">';

                                        }

                                        echo $data['nombreHab'].' (<b>'.$data['abrevReg'].'</b>) <strike style="color:red;">'.number_format($data['recomendado'],2,".","").'&euro; </strike>  - <span style="font-size: 1.3em;" data-placement="top" data-toggle="tooltip" title="NETO : '.$data['precio'].' &euro;">'.$data['total'].'&euro;</span> - ('.number_format($precio_division,2,".","").'&euro; Per/D&iacute;a)';

                                        echo '</div>';#<div class="col-xs-8" name="div_body_email"> 
                                        if(isset($data['offer']) && $data['offer']!="" && strlen(trim($data['offer']))){


                                            echo '<div class="col-xs-8">
                                                <br> <b>Oferta : '.$data['offer'].'</b>
                                                </div>';

                                        }
                                        
                                        echo '<div class="col-xs-3 pull-right">
                                                '.$data['abrevProv'];

                                       
                                        // aqui vamos a poner los datos que necesitan cada
                                        // uno de los proveedores
                                        // en el id_xml, tenemos todos los datos la busqueda.
                                        // equivalentes al antiguo varget

                                        if($data['proveedor']=="id_europlayas"){

                                            $pedir_gastos=$data['gastos'];

                                        }else if($data['proveedor']=="id_veturis"){

                                            $pedir_gastos=$data['obj']."-".$data['DATOS'];

                                        }else if($data['proveedor']=="id_bedsonline"){

                                            $pedir_gastos = $data['IncomingOffice']
                                                ."-bds-".$data['token']
                                                ."-bds-".$data['ContractName']
                                                ."-bds-".$data['ticket']
                                                ."-bds-".$data['codeHab']
                                                ."-bds-".$data['codeReg']
                                                ."-bds-".$data['habCaract'];

                                        }else if($data['proveedor']=="id_versys" || $data['proveedor']=="id_solole"){

                                            $pedir_gastos = $data['SequenceNmbr']
                                                ."-jnp-".$data['RatePlanCode'];

                                        }else if($data['proveedor']=="id_serhs"){

                                            $pedir_gastos = $data['gastos'];
                                        }

                                        echo '<span class="text_1_4 texto_color_azul_claro">
                                                <i class="fa fa-exclamation-triangle tooltip_gastos"
                                                    data-toggle="modal" 
                                                    data-target="#info_gastos_cancelacion"
                                                    data-proveedor="'.$data['proveedor'].'"
                                                    data-hotel="'.$data['codeHotel'].'"
                                                    data-gastos="'.$pedir_gastos.'">
                                                </i>
                                            </span>
                                                
                                            <button type="button" class="btn btn-success pull-right" name="detalles_button">Detalles</button>
                                        </div>'; # <div class="col-xs-4">

                                // este es el cierre del form de cada una de las habitaciones.
                                echo '</form>
                                </div>';# <div class="col-xs-12 hr" style="border-bottom: 1px solid #31708f;">
                           
                            } //  for($i=0;$i<$total_distribuciones[$ide];$i++){

                                         
                            // este es el cierre de cada hijo 
                            // osea cada hotel.
                            echo '</div> <!-- alert -->
                        </div>'; // <div class="col-xs-12">
                        } # if($total_distribuciones[$ide]>0){

                    } // if($limite<$limite_hoteles){
                } // if($tabla_busqueda=="" || in_array($ide,$id_hoteles_mostrar)){

            } // foreach ($listado as $ide => $datos) { 

            if($hotel_mostrado==0){

                echo "<h3>Hay hoteles, pero ninguno cumple tus filtros</h3>";
            }

            echo '</div>
            <script>
            
                    $("[name=\'distribucion_disponibilidad\']").val("'.$distribucion_email.'");
                    $("#distribucion_disponibilidad_html").removeClass("hide");
                    $("#distribucion_disponibilidad_html").html("<b>Esta es la distribucion a envia, recuerda que siempre es la &uacute;ltima que se ha buscado</b>: <br><br>'.$distribucion_email.'");


                    // chek only 1 hb
                    $(\'[name="check_body_email"]\').on(\'click\' , function(e){

                        if($(this).is(\':checked\')){

                            var id_hotel = $(this).attr("id_hotel");
                            var span = $(\'span[id_hotel="\'+id_hotel+\'"]\');
                            var nombre_hotel = span.siblings("div.filtro_nombre").html();
                            var estrellas_hotel = span.siblings("div.filtro_estrellas").html();
                            var poblacion_hotel = span.siblings("div.filtro_poblacion").html();
                            var whatsapp = $(\'[name="whatsapp"]\').val();

                            var html = $(this).next(\'div[name^="div_body_email"]\').html();

                            if(whatsapp==0){

                                var html = $(this).next(\'div[name^="div_body_email"]\').html();
                                
                            }else{

                                var html = $(this).next(\'div[name^="div_body_email"]\').children(\'input[name="whatsapp_body_email"]\').val();
                                     
                            }
                            
                            var txt_anterior = $("#summernote_form_enviar_email").val();
                            var txt_recuperar = $(\'[name="txt_recuperar_email"]\').val();

                            var pre_new_txt = "<br><br>"+nombre_hotel+" "+estrellas_hotel+" "+poblacion_hotel+"<ul><li>"+html+"</li></ul>";
                            
                            var new_recuperar = txt_recuperar + pre_new_txt;

                            var txt_new = txt_anterior + pre_new_txt;
                            
                            $("#summernote_form_enviar_email").summernote("code",txt_new);
                            $(\'[name="txt_recuperar_email"]\').val(new_recuperar);

                            $("[name=\'email_disponibilidad\']").val(1);
                        }

                        noty({text: "Disponibilidad Copiada", layout: "topRight", type: "success"});
                            setTimeout("$.noty.closeAll()",5000);
                    });

                    // chek all hb

                    $(\'[name="check_hotel_body_email"]\').on(\'click\' , function(e){

                        if($(this).is(\':checked\')){

                            var id_hotel = $(this).attr("id_hotel");
                            
                            var nombre_hotel = $(this).siblings("div.filtro_nombre").html();
                            var estrellas_hotel = $(this).siblings("div.filtro_estrellas").html();
                            var poblacion_hotel = $(this).siblings("div.filtro_poblacion").html();
                            var whatsapp = $(\'[name="whatsapp"]\').val();

                            var html ="<ul>";
                            $(\'input[id_hotel="\'+id_hotel+\'"]\').each(function(){

                                if($(this).next(\'div[name^="div_body_email"]\').html()!=undefined){

                                    if(whatsapp==0){

                                        html+="<li>"+$(this).next(\'div[name^="div_body_email"]\').html()+"</li>";

                                    }else{

                                        html+="<li>"+$(this).next(\'div[name^="div_body_email"]\').children(\'input[name="whatsapp_body_email"]\').val()+"</li>";

                                    }

                                }


                            });
                            
                            var txt_anterior = $("#summernote_form_enviar_email").val();
                            var txt_recuperar = $(\'[name="txt_recuperar_email"]\').val();

                            var pre_new_txt = "<br><br>"+nombre_hotel+" "+estrellas_hotel+" "+poblacion_hotel+" "+html+"</ul>";

                            var txt_new = txt_anterior + pre_new_txt;
                            var new_recuperar = txt_recuperar + pre_new_txt;

                            $("#summernote_form_enviar_email").summernote("code",txt_new);
                            $(\'[name="txt_recuperar_email"]\').val(new_recuperar);

                            $("[name=\'email_disponibilidad\']").val(1);

                        }

                        noty({text: "Disponibilidad Copiada", layout: "topRight", type: "success"});
                            setTimeout("$.noty.closeAll()",5000);
                    });

                    // details XML process
                    $("[name=\'detalles_button\']").on(\'click\',function(){
        
                        var id_form = $(this).parents("form").attr("id");
                        $("#modal_cartel_busquedas").modal("show");
                        $("#"+id_form).call_ajax({
                            serialice: "#"+id_form,
                            respuesta : "#resultados_hoteles"
                        }); 
                        
                    }); // $(\'[name=\'detalles_button\']\').click(function(){

            </script>'; // echo "<hr><div id='contenedor_listados_hoteles'>";
          
        } // }else if($listado>0){  


        script("$('#modal_cartel_busquedas').modal('hide');
            $('#listados_hoteles').collapse('show');
            $(\"[data-toggle='popover']\").popover();");
        //return $listado;

    }
// eof }else if($realizar_busqueda_hotel){
}else if(isset($detalles_hotel_booking)){


    if(!isset($_SESSION['id_zona_privada']) && !isset($_SESSION['Authenticated'])){
        script('alert("Sesion Caducada");location.reload()');
    }

    $habitaciones = $DB->select('busqueda','_xml_peticiones','id='.$_SESSION['id_xml']);
    $C = new control($_POST,$proveedor);
    $resultado = $C->detalles($habitaciones[f][0]['busqueda']);

    // si falla el proceso lanzamoa atras
    if($resultado=="" || $resultado==false){

        alert("Debido a problema de Disponibilidad esta oferta no esta disponible, realizamos otra nueva busqueda.");
        script(" $('#listados_hoteles').collapse('hide');
                $('#resultados_hoteles').empty();
                $('#modal_cartel_busquedas').modal('show');
                $('#buscador_include').call_ajax({
                    serialice: '#buscador_include',
                    respuesta : '#resultados_hoteles'
                });");

    }else{ // si es ok, tiramos para adelante

        // SACAMOS LOS DATOS DEL HOTEL QUE VAMOS A UTILIZAR
            $datos_hotel = $DB->select("nombre,descripcion,direccion,cp,pais,
            fotos,id_estrellas,poblacion",
            "_hoteles",$proveedor." like '".$codeHotel."'");

            // subimos las posiciones al array
            $datos_hotel = $datos_hotel[f][0];

            // montar un array de fotos, eliminando las que no 
            // tienen url (con la bbdd nueva esto se puede eliminar)


            $arr_foto = json_decode($datos_hotel['fotos']);

            $nf = count($arr_foto);

            // sacamos los datos del cliente

            $cliente = $DB->select('nombre,email,telefono','clientes','id='.$_SESSION['id_cliente_booking']);

            $email_arr=json_decode($cliente[f][0]['email']);
            $end=count($email_arr);

            // si solo 1
            if($end==1){
                $email = $email_arr[0];
            }else if($end>1){ // if($end==1){
                // si mas de 1
                $new_value="";
                for($e=1;$e<=$end;$e++){
                    if($e<$end){
                        $new_value.=$email_arr[$e-1].";";
                    }else{
                        $new_value.=$email_arr[$e-1];
                    }
                }// for($e=1;$e<=$end;$e++){
                $email=$new_value;
            }// }else if($end>1){


// 18267 16026
            // sacamos los datos de las habitaciones que vamos a usar en las tablas 
            $data_rsv = json_decode(utf8_encode($habitaciones[f][0]['busqueda']),true);

            echo '  <div class="row mb100 mt30">';


                $total_comprobado = $total<$resultado['nuevo_total'] ? $resultado['nuevo_total'] : $total;

                if($total<$resultado['nuevo_total']){

                           echo '<div class="alert alert-warning alert-dismissible clearfix">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <div class="mg-alert-icon hidden-xs col-xs-2"><i class="fa fa-exclamation"></i></div>
                                        <br class="visible-lg">
                                        <h3 class="mg-alert-payment">El precio de tu reserva a sido modificado por parte del Hotel: <br> </h3>
                                        <h3 class="text-center mg-alert-payment"><b>El nuevo precio es '.$resultado['nuevo_total'].'</b></h3>
                                    </div>';


                }

                echo '<form action="#" method="post" id="realizar_reserva_form" accept-charset="UTF-8">
                            <div class="col-xs-12 text-center hr">
                                <h2>'.tildes_min_may($datos_hotel['nombre'],h).' - '.$datos_hotel['id_estrellas'].' * '.utf8_encode($datos_hotel['poblacion']).'</h2>
                            </div>
                            <div class="col-xs-5">
                                <div id="owl-demo-fotos_detalles" class="owl-carousel owl-theme" style="margin:auto;">';

                                    for($x=0;$x<$nf;$x++){

                                        echo '<div class="item" style="border:0;">
                                                <img src="'.$arr_foto[$x].'" onerror="src=\'/new_soltury/images/sustitucion.png\'"  class="img-responsive img-center" alt="Foto de '.$datos_hotel['nombre'].'">
                                            </div>';

                                    }
                                                                                                 
                echo            '</div> <!-- <div id="owl-demo" ..... -->
                            </div> <!-- <div class="col-xs-5"> -->
                            <div class="col-xs-7">
                                <h3 class="hr">'.$nombreHab.'</h3>
                                <p class="descripcion_hoteles_listados">
                                    '.htmlspecialchars_decode(utf8_encode($datos_hotel['descripcion'])).'
                                </p>
                            </div> <!-- <div class="col-xs-7"> -->
                            <div class="col-xs-12 hr">
                                <p class="text-center">*La ubicaci&oacute;n Puede ser Incorrecta</p>
                                    <iframe width="100%" height="250" frameborder="0" style="border:0"
                                        src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDdwoRvTCjfJr774seQjG3kvkHVb3TLXDQ&q='.$datos_hotel['nombre'].','.utf8_encode($datos_hotel['poblacion']).'">
                                    </iframe>                        
                            </div> <!-- <div class="col-xs-12"> -->
                            <div class="col-sm-6 borde_gris_right">
                                <h3 class="mb20">Datos Personales</h2>                       
                                <label>Titular de la Reserva </label>
                                    <input type="text" 
                                    class="form-control mb20" 
                                    name="titular_reserva" 
                                    value="'.utf8_encode($cliente[f][0]['nombre']).'" 
                                    placelholder="Titular de la Reserva" 
                                    autocomplete="name">
                                <label>E-mail <small>*M&aacute;s de un e-mail separados por punto y coma </small></label>
                                    <input type="email" 
                                    class="form-control mb20" 
                                    name="email_reserva" 
                                    value="'.$email.'" 
                                    placelholder="E-mail" autocomplete="email">         
                                <label>Tel&eacute;fono</label>
                                    <input type="tel" 
                                    class="form-control" 
                                    name="telefono_reserva" 
                                    value="'.$cliente[f][0]['telefono'].'" 
                                    placelholder="Tel&eacute;fono" 
                                    autocomplete="tel">                             
                            </div> <!-- <div class="col-sm-6"> -->
                            <br class="visible-xs visible-sm">
                            <div class="col-sm-6">
                                <h3 class="mb20">Peticiones Al hotel</h3>
                                <label>Observaciones Adicionales</label>
                                <br class="visible-xs">
                                <textarea name="observaciones_reserva" class="form-control mb20" cols="10" rows="5"></textarea>
                                <label>Posible Horario de LLegada</label>
                                <select name="horario_reserva" class="form-control">
                                    <option value="1">No lo se</option>
                                    <option value="12-18" selected="seleted">De 12:00 a 18:00</option>
                                    <option value="18-24">De 18:00 a 23:59</option>
                                    <option value="00">Despues de las 00:00</option>
                                </select>                                
                            </div><!-- <div class="col-md-6"> -->
                            <div class="col-xs-12"> <!-- tablas de las dsitribuciones -->
                                <hr>
                                <h3 class="mb20">Distribuci&oacute;n de las Habitaciones </h3>
                                <table class="table mb40">
                                    <thead>
                                        <tr>
                                            <th>Habitaci&oacute;n</th>
                                            <th>Ocupantes</th>
                                            <th>R&eacute;gimen</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                                                                  
                                    for($hb=1;$hb<=$data_rsv['habitacion'];$hb++){

                                        $s="";
                                        $es="";
                                        if($data_rsv['adultos_'.$hb]>1){
                                            $s="s";
                                            $es="es";
                                        }

                                        echo '<tr>
                                                <td>'.$nombreHab.'</td>
                                                    <input type="hidden" name="adultos_'.$hb.'" value="'.$data_rsv['adultos_'.$hb].'">
                                                    <input type="hidden" name="ninos_'.$hb.'" value="'.$data_rsv['ninos_'.$hb].'">
                                                <td>'.$data_rsv['adultos_'.$hb].' Adulto'.$s;

                                        if($data_rsv['ninos_'.$hb]>0){

                                            $s="";
                                            $es="";
                                            if($data_rsv['ninos_'.$hb]>1){
                                                $s="s";
                                                $es="es";
                                            }

                                            echo ' / '. $data_rsv['ninos_'.$hb].' Ni&ntilde;o'.$s.' (Edad'.$es.': ';
                                                                                
                                            for($n=1;$n<=$data_rsv['ninos_'.$hb];$n++){

                                                if($n==$data_rsv['ninos_'.$hb]){
                                                    echo $data_rsv['edad_'.$hb.'_'.$n];
                                                }else if($n<$data_rsv['ninos_'.$hb]){
                                                    echo $data_rsv['edad_'.$hb.'_'.$n]. ",";
                                                }
                                                echo '<input type="hidden" name="edad_'.$hb.'_'.$n.'" value="'.$data_rsv['edad_'.$hb.'_'.$n].'">';
                                                                                   
                                            }

                                            if($data_rsv['ninos_'.$hb]==1 && $data_rsv['edad_'.$hb.'_1']==1){
                                                $s="";
                                            }else{
                                                $s="s";
                                            }

                                            echo ' a&ntilde;o'.$s.')';

                                        } // if($data_rsv['ninos_'.$hb]>0){

                                        // PARCHE W2M    
                                        if(!isset($abrevReg) || $abrevReg==""){
                                            $abrevReg="SA";
                                        }

                                        echo '  </td>
                                                <td>'.str_replace(".","",$abrevReg).'</td>
                                            </tr>';
                                    } // for($hb=1;$hb<=$data_rsv['habitacion'];$hb++){
                                                                    
                        echo       '</tbody>
                                </table>
                                <hr>
                            </div> <!-- <div class="col-xs-12">  tablas de las distribuciones-->
                            <div class="col-sm-6 mb20 borde_gris_right">
                                <h3>Condiciones de Cancelaci&oacute;n</h3>';
                                        

                                // ordenamos los que traigan las fechas inversas
                                foreach ($resultado['cancelacion'] as $clave => $fila) {

                                    $resultado['cancelacion'][$clave]['fecha'] = str_replace("/","-",$fila['fecha']);

                                }

                                // esta funcion solo ordena correctamemnte si la fecha tiene el formato
                                // 21-12-2017
                                usort($resultado['cancelacion'], 'ordenar_por_fecha');

                     
                                foreach ($resultado['cancelacion'] as $clave => $fila) {

                                    $resultado['cancelacion'][$clave]['fecha'] = str_replace("-","/",$fila['fecha']);

                                }
                     

                                for($i=0;$i<count($resultado['cancelacion']);$i++){

                                    if($i>0){
                                        echo "<br>";
                                    }else if($i==0){

                                        // resto 1 dia al primero de las cancelaciones
                                        $resta_dias = resta_dias($resultado['cancelacion'][$i]['fecha'],1);
                                        $hoy = date("d/m/Y");

                                        $clx_free = comparar_fechas($hoy,$resta_dias,i);
                                            // tengo que sacar si es positivo el que se pueda cancelar 
                                            // sin gastos y sacamos o no este resultado y luego
                                            // sacamos o no el boton de reservar en pendiente de pago.

                                            if($clx_free>0){

                                                $resta_dias_pago = resta_dias($resultado['cancelacion'][$i]['fecha'],5);

                                                $reservar_en_pendiente = comparar_fechas($hoy,$resta_dias_pago,i);
                                                echo "
                                                <label>Cancelaci&oacute;n <b>Gratis</b> hasta el
                                                    <br class='visible-xs'> 
                                                    <span class='text_1_1'>
                                                        <b><u>" .$resta_dias."</b></u>
                                                    </span>
                                                </label>
                                                <br>";
                                            } // if($clx_free>0){

                                        } 
                                                               
                                        if(!isset($resultado['cancelacion'][$i]['total'])){
                                            $resultado['cancelacion'][$i]['total']=$total;
                                        }  

                                        echo  " <label>Cancelando desde 
                                                    <span class='text_1_1'>
                                                        <b><u>". $resultado['cancelacion'][$i]['fecha']."</b></u>
                                                    </span><br class='visible-xs'>
                                                     Un importe de 
                                                    <span class='text_1_1'><b><u>".number_format($resultado['cancelacion'][$i]['total'],2,".","")."&euro;</b></u></span>
                                                </label>";

                                        // montar aqui los gastos especielamente para veturis
                                        // para tenerlso por parametros y poderlos devolver
                                        // en las confirmaciones, ellos no los traen 
                                        if($proveedor=='id_veturis'){

                                            if($i==0){

                                                echo '<input type="hidden" name="gastos" value="'.count($resultado['cancelacion']).'">';

                                            }// if($i==count($resultado['cancelacion'])-1){

                                            
                                        echo '  <input type="hidden" name="fecha_'.$i.'" value="'.$resultado['cancelacion'][$i]['fecha'].'">
                                                <input type="hidden" name="importe_'.$i.'" value="'.number_format($resultado['cancelacion'][$i]['total'],2,".","").'">';

                                        } //if($proveedor=='id_veturis'){
                                                                     
                                } // for($i=0;$i<count($cancelaciones);$i++){
                    
                                echo '</div> <!-- <div class="col-md-6 borde_gris_right_md"> -->
                                    <div class="col-sm-6 mb20">
                                        <h3>Condiciones de la Reserva</h3>
                                        <b>';
                                        
                                        if(isset($resultado['observaciones']) && $resultado['observaciones']!=""){

                                            echo utf8_decode($resultado['observaciones']);

                                        }else{

                                            echo "Sin Condiciones ni obrsevaciones especiales.";
                                        }
                                                                                     
                                echo  '</b>
                            </div> <!-- <div class="col-md-6"> -->
                            <div class="col-xs-12">
                                <hr>
                                <h3 class="mg-min-left-title">Informaci&oacute;n Adicional</h3>
                                               
                                <table class="table mb40">
                                    <thead>
                                        <tr>
                                            <th>Entrada</th>
                                            <th>Salida</th>
                                            <th>Tury-checks</th>
                                            <th>Precio</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>'.$data_rsv['fecha1'].'</td>
                                            <td>'.$data_rsv['fecha2'].'</td>
                                            <td>';
                                                                       
                                                // sacamos los bonos a consumir
                                                if($data_rsv['destino']==1){

                                                     $resta_bonos=comparar_fechas($data_rsv['fecha1'],$data_rsv['fecha2'],i)*$data_rsv['habitacion'];

                                                    echo $resta_bonos;

                                                }else if($data_rsv['destino']==2){

                                                    $resta_bonos=0;

                                                    echo "0";
                                                }


                                         
                                            echo'</td>
                                            <td data-toggle="tooltip" data-placement="top" title="Pon siempre 2 decimales aunque sean 00 separados por puntos o comas">
                                                <input type="text" name="total_manipulado" class="form-control" value="'.$total_comprobado.'" style="width:100px; display:inline;"/> &euro;
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> <!-- <div class="col-xs-12"> -->';
                            if(isset($resta_dias_pago) && $reservar_en_pendiente>0 || isset($_SESSION['Authenticated'])){

                                if(isset($_SESSION['Authenticated']) && !isset($resta_dias_pago)){

                                    $txt_pago = "<b>EL CLIENTE NO PUEDE RESERVAR SIN PAGAR</b>";

                                }else{

                                    $txt_pago = '<b>El cliente puede realizar el pago hasta el '.$resta_dias_pago.'</b>';

                                }                
                                echo '  <div class="col-xs-12" style="margin-bottom:20px;">
                                            <div class="alert alert-success" role="alert">
                                                '.$txt_pago.'
                                            </div>
                                            <button type="button" class="btn btn-success btn-lg pull-right" id="button_reserva_pendiente">RESERVAR SIN PAGAR</button>
                                        </div>';
                            }

                            echo '<div class="col-xs-12">';
                                                            
                            if($clx_free>0){
                              
                                echo '<div class="alert alert-warning" role="alert">
                                        <b>El cliente puede cancelar hasta '.$resta_dias.' sin ningun gasto</b>
                                    </div>';
                            }
                            // todas las veriables completas que vendran del 
                            // resultado de las llamadas de detalles();
                            include('../includes/inc_varibales_detalles_hoteles.php');
                                                                     
                            echo   '<button type="button" class="btn btn-warning btn-lg pull-right" id="button_reserva_pagada">RESERVAR Y PAGAR</button>';

                            if(isset($_SESSION['Authenticated'])){

                                echo '<div class="pull-right col-xs-4 mb20">';

                                if(isset($resultado['neto'])){

                                    echo $proveedor.' <span style="color : red;">'.$resultado['neto'].'</span>';
                                }

                                echo '<input type="text" name="operacion_tpv" value="" placeholder="Operacion TPF Fisico" class="form-control "></div>';
                            }

                            echo   '</div>   
                        </form>
                    </div>
                    <script>
                        setTimeout(\'$("#owl-demo-fotos_detalles").owlCarousel({margin : 5,nav : true,items : 1,center : true,autoHeight : true});\', 500); 
                        $("#modal_cartel_busquedas").modal("hide");
                        $("html,body").animate({
                        scrollTop: $("#resultados_hoteles").offset().top
                        }, 2000);

                        $(\'[id^="button_reserva"]\').click(function(){

                            var accion = $(this).attr("id");

                    
                            $("#modal_cartel_busquedas").modal("show");

                            // hago este paso para determinar si es pendiente de pago no.
                            
                            if(accion=="button_reserva_pendiente"){

                                $(\'[name="reservar_pendiente"]\').val(1);
                                
                            }else{

                                $(\'[name="reservar_pendiente"]\').val(0);

                            }

                            $("#realizar_reserva_form").call_ajax({

                                url:"p",
                                serialice:"#realizar_reserva_form"

                            }); // $("#realizar_reserva_form").call_ajax({
    
                        }); // $("[id^="button_reserva"]"").click(function(){

                        $("[name=\'total_manipulado\']").keyup(function(){

                            var new_total = $(this).val();
                            new_total = new_total.replace(".","");
                            new_total = new_total.replace(",","");
                            $("[name=\'total\']").val(new_total);

                        });
                    </script>'; // <div class="row">

    } // if($resultado=="" || $resultado==false){ .. }else{....}

// eof }else if($detalles_hotel_booking){  
}else if(isset($formularios)){
   

    if($accion==i){

    //++++++ $ recibidas ++++
    // 
    // $id_formulario
    // $title
    // $submit
    // $warning

    $xml='<?xml version="1.0" encoding="utf-8"?>
    <formulario> 
    <datos id="'.$id_formulario.'"><title><![CDATA['.$title.']]></title> 
    <submit><![CDATA['.$submit.']]></submit>
    <warning><![CDATA['.$warning.']]></warning>
    </datos><bodyForm>';
    $xml.=$xml_campos_formulario;
    $xml.='</bodyForm> 
    </formulario>';

    $DB->query("insert into formulario_modal 
        (id,nombre,xml) 
        values
        (NULL,'".$id_formulario."','".$xml."')");

    script("location.reload()");

    }else if($accion==u){

        //  $ que vienen por defecto son:
        //  $id_editar
        //  $tabla_editar

$xml="<formulario>
</formulario>";

$xml = new SimpleXMLElement($xml);

// DATOS PRINCIPALES DEL FORM.
$datos=$xml->addChild('datos');
$datos->addAttribute('id',$id_form);
$datos->addChild('title',$title);
$datos->addChild('submit',$submit);
$datos->addChild('warning',$warning);

$bodyForm=$xml->addChild('bodyForm');

$for = count($id_nombre_campo_);

//alert($for);

// me quedan, darle el orden que queremos 

/*
ORDEN CAMPOS

ENCERRAR CADA ITERACION $x COMPLETA
EN UN ARRAY MULTY 
( EL VALOR EMPIEZA EN 1)
EL VALUE A ORDENAR ->$posicion_campo_[0];
MODIFICAR VALUES DE LA FORMACION XML
POR LAS POSICIONES DEL ARRAY 

*/

// aqui vamos a montar array x campo
// y seguidamente ordenaremos.
$campos=array();

for($x=0;$x<$for;$x++){

    if($borrar_campo_[$x]==0){

        $campos[$x]=array(
            'orden'=>$posicion_campo_[$x],
            'type'=>$type_campo_[$x],
            'name'=>$id_nombre_campo_[$x],
            'verify'=>$verify_campo_[$x],
            'placeholder'=>$place_label_campo_[$x]
        //eof $campos[$x]=array( 
        );

        if($type_campo_[$x]=='textArea'){
        $campos[$x]['rows']=$rows_textArea_[$x];
        $campos[$x]['cols']=$cols_textArea_[$x];
    // eof     if($type_campo_[$x]=='textArea'){
        }


        if($type_campo_[$x]=='select'){
        $campos[$x]['boleano']=$boleano_select_[$x];
        $campos[$x]['mes']=$mes_select_[$x];

        $campos[$x]['numeros_activo']=$numeros_select_[$x];
        $campos[$x]['comienzo_select']=$comienzo_select_[$x];
        $campos[$x]['fin_select']=$fin_select_[$x];

        $campos[$x]['extra_activo']=$extra_select_[$x];
        $campos[$x]['val_extra']=$val_extra_[$x];
        $campos[$x]['text_extra']=$text_extra_[$x];

        $campos[$x]['tabla']=$tabla_select_[$x];
        $campos[$x]['val']=$val_select_[$x];
        $campos[$x]['text']=$text_select_[$x];
        $campos[$x]['where']=$where_select_[$x];
        // eof  if($type_campo_[$x]=='select'){
        }


    //eof if($borrar_campo_[$x]==0){
    }
// eof for($x=0;$x<$for;$x++){
}

// ahora tenemos que ordenar el array como
// queremos que vaya ejjejej
usort($campos, 'sort_by_orden');


// zona de añadir campos al xml.
for($x=0;$x<$for;$x++){


        $campo[$x]=$bodyForm->addChild('campo');
        $campo[$x]->addAttribute('type',$campos[$x]['type']);
        $campo[$x]->addAttribute('name',$campos[$x]['name']);
        $campo[$x]->addAttribute('verify',$campos[$x]['verify']);
        $campo[$x]->addChild('placeholder',$campos[$x]['placeholder']);

        //empezamos con las especialidades de campos
        // diferentes.

        if($type_campo_[$x]=='textArea'){
            $campo[$x]->addChild('rows',$campos[$x]['rows']);
            $campo[$x]->addChild('cols',$campos[$x]['cols']);
            $campo[$x]->addChild('value');
    // eof     if($type_campo_[$x]=='textArea'){
        }

        if($type_campo_[$x]=='select'){
            $campo[$x]->addAttribute('boleano',$campos[$x]['boleano']);
            $campo[$x]->addChild('mes',$campos[$x]['mes']);

            $numeros[$x]=$campo[$x]->addChild('numeros');
            $numeros[$x]->addAttribute('activo',$campos[$x]['numeros_activo']);
            $numeros[$x]->addChild('comienzo_select',$campos[$x]['comienzo_select']);
            $numeros[$x]->addChild('fin_select',$campos[$x]['fin_select']);

            $extra[$x]=$campo[$x]->addChild('extra');
            $extra[$x]->addAttribute('activo',$campos[$x]['extra_activo']);
            $extra[$x]->addChild('val',$campos[$x]['val_extra']);
            $extra[$x]->addChild('text',$campos[$x]['text_extra']);

            $campo[$x]->addChild('tabla',$campos[$x]['tabla']);
            $campo[$x]->addChild('val',$campos[$x]['val']);
            $campo[$x]->addChild('text',$campos[$x]['text']);
            $campo[$x]->addChild('where',$campos[$x]['where']);
        // eof  if($type_campo_[$x]=='select'){
        }

// añadimo aqui el type, por los cambios en los select
// eof for($x=0;$x<$for;$x++){
}


// aqui sumamos los campos que metemos nuevos, 
// en los 
    if($suma_campo==1){
        $bodyForm->addChild($xml_campos_formulario);
        $new_xml=str_replace("<<","<",$xml->asXML());
        $new_xml=str_replace(">/></","></",$new_xml);

    }else{
        //Si no hay campo nuevo, asugnamos $
        $new_xml=$xml->asXML();   
    }


//Esta es la parte final a montar.

         $DB->opentable($tabla_editar);
         $DB->edit($id_editar);
         $DB->ta['xml']=$new_xml;
         $DB->update();
        
         script("alert('Formulario Modificado');
            window.close()");
// eof  }else if($accion==u){
    }

   // EOF isset($formularios) 
}else if(isset($xml_campos_formulario)){

//alert($type_campo);
    //++++++ $ recibidas ++++
    // 
    // $type_campo
    // $id_nombre_campo
    // $place_label_campo
    // $verify_campo

    //++++++ $ recibidas SELECT C++++
    // 
    // $extra_select
    // $val_extra
    // $text_extra

    // $tabla_select
    // $val_select
    // $text_select
    // $where_select

        //++++++ $ recibidas SELECT N++++
    // 
    // $comienzo_select
    // $fin_select


        //++++++ $ recibidas TEXTAREA++++
    // 
    // $type_campo
    // $id_nombre_campo
    // $place_label_campo
    // $verify_campo
$type=array(1=>"text",
                2=>"password",
                3=>"hidden",
                4=>"select",
                5=>"select",
                6=>"textArea",
                7=>"select",
                8=>"select");
 

    // IF DEL TIPO DE CAMPO QUE ESTAMO SRECIBIENDO.

// == 1 | 2 | 3 | 6
 
    if($type_campo==1 || 
       $type_campo==2 ||
       $type_campo==3 ||
       $type_campo==6){


$nuevo_campo='<campo 
type="'.$type[$type_campo].'" 
name="'.$id_nombre_campo.'" 
verify="'.$verify_campo.'">
        ';

if($type_campo==6){
$nuevo_campo.='<rows>'.$rows_textArea.'</rows>
<cols>'.$cols_textArea.'</cols>
';
// eof if($type_campo==6){
        }  


$nuevo_campo.='<value> 
<![CDATA[]]>
</value>
<placeholder>
<![CDATA['.$place_label_campo.']]>
</placeholder>
</campo>';

//echo $nuevo_campo;

// eof if($type_campo==6){
    }else if($type_campo==4 || 
       $type_campo==5 || 
       $type_campo==8 || 
       $type_campo==7){

// añadir 4 nuevas $
        // meses = 0 - 1
        // numeros = 0 -1 
        // en caso de 1 => comienzo , fin 

        $mes=1;
        $boleano=1;
        $numeros=1;

        if($type_campo==4 || $type_campo==7 || $type_campo==5){

        $mes=0;

        }


        if($type_campo==8 || $type_campo==7 || $type_campo==5){

        $boleano=0;

        }

        if($type_campo==8 || $type_campo==5 || $type_campo==4){

        $numeros=0;
        $comienzo_select=0;
        $fin_select=0;

        }


        if($type_campo==8 || $type_campo==7 || $type_campo==4){

        $extra_select=0;
        $val_extra="";
        $text_extra="";
        $tabla_select="";
        $val_select="";
        $text_select="";
        $where_select="";

        }

$nuevo_campo='<campo type="select" 
name="'.$id_nombre_campo.'" 
boleano="'.$boleano.'" 
verify="'.$verify_campo.'">
<placeholder>
<![CDATA['.$place_label_campo.']]>
</placeholder>
<mes>'.$mes.'</mes>
<numeros activo="'.$numeros.'">
<comienzo_select>'.$comienzo_select.'</comienzo_select>
<fin_select>'.$fin_select.'</fin_select>
</numeros>
<extra activo="'.$extra_select.'">
<val><![CDATA['.$val_extra.']]></val>
<text><![CDATA['.$text_extra.']]></text>
</extra>
<tabla><![CDATA['.$tabla_select.']]></tabla>
<val><![CDATA['.$val_select.']]></val>
<text><![CDATA['.$text_select.']]></text>
<where><![CDATA['.$where_select.']]></where>
</campo>';


// eof     }else if($type_campo==4 ||  $type_campo==5){
    }




    if($campos_xml_textArea==""){
        $campo_xml=str_replace('"','\"',$nuevo_campo);
        $campo_xml=preg_replace('[\n|\r|\n\r]','',$campo_xml);

    }else if($campos_xml_textArea!=""){
        $nuevo_campo=str_replace('"','\"',$nuevo_campo);
        $nuevo_campo=preg_replace('[\n|\r|\n\r]','',$nuevo_campo);
$campos_xml_textArea=str_replace('"','\"',trim($campos_xml_textArea));
$campos_xml_textArea=preg_replace('[\n|\r|\n\r]','',$campos_xml_textArea);

        $campo_xml=$campos_xml_textArea."".$nuevo_campo;
    }

    // AÑADIMOS LOS CAMPOS A xml_campos_formulario
    // CLEAN ALL $ EN EL FORM DE añadir_campos_form

        script("$('input[name=\"xml_campos_formulario\"]').val('".$campo_xml."');$('[name=\"campos_xml_textArea\"]').html('".$campo_xml."');$('select[name=\"type_campo\"]>option[value=\"1\"]').attr('selected','selected');
            var textArea = $('div[name=\"campos_textArea\"]');var select = $('div[name=\"campos_select\"]');var selectN = $('div[name=\"campos_select_numero\"]');if(select.is(':visible')){select.css('display','none');}else if(textArea.is(':visible')){textArea.css('display','none');}else if(selectN.is(':visible')){selectN.css('display','none');}$('select[name=\"type_campo\"] option[value=1]').attr('selected',true);$('#sumar_campo_ajax_form input').each(function(indice, elemento){ if($(elemento).val()!='Añadir Campo' && $(elemento).attr('name')!='xml_campos_formulario')$(elemento).val('');});");


// EOF isset($xml_campos_formulario)
}else if(isset($sacar_operadoras)){

    
$DB->query("select id,nombre from operadoras where id_delegacion=".$id_delegacion." and estado=1");
    echo "<option value='0'>Ninguna</option>";
while($rows=$DB->fetch()){

    echo "<option value='".$rows['id']."'>".$rows['nombre']."</option>";
}
echo "<script>$('select[id^=\"envios\"]').html(\"<option value='0'>Ninguna</option>\")</script>";
//eof (isset($sacar_operadoras))    
}else if(isset($sacar_promociones)){


// esta primera opcion , para clientes de prueba  
  echo "<option value='0'>Ninguna</option>";
$id_delegacion=$DB->select("id_delegacion","operadoras","id=".$id_operadora);
 
 // XARA LAS PROMOCIONES QUE TENGA UNA OPERADORA ASIGNADA EN CONCRETO   
$DB->query("select id,nombre,noches from productos where id_operadora=".$id_operadora." and estado=1");
// select rango from precios_productos where id_producto=9 order by rango ASC
while($rows=$DB->fetch()){
    echo "<option value='".$rows['id']."'>".$rows['nombre']."(".$rows['noches'].")</option>";
}

// XARA PROMOCIONES SIN OPERADORA ASIGNADA (CON ESTO NOS EVITAMOS TENER QUE 
// REPETIR TODAS LAS PROMOCIONES POR OPERADORA QUE NO SEAN ESPECIFICAS)
$DB->query("select id,nombre,noches from productos where id_operadora=0 and estado=1 and id_delegacion=".$id_delegacion[f][0]['id_delegacion']);
while($rows=$DB->fetch()){
    echo "<option value='".$rows['id']."'>".$rows['nombre']."(".$rows['noches'].")</option>";
}


//eof (isset($sacar_promociones))    
}else if((isset($cambiar_select_crear_envios))){

// SACAMOS TODOS LOS DATOS DEL CLIENTE
    $datos_cliente=$DB->select("nombre,id_delegacion,id_operadora","clientes","id=".$id_cliente);

    $select_cliente="<option value=\'".$id_cliente."\'>".utf8_encode($datos_cliente[f][0]['nombre'])."</option>";
    // CPGEMOS TODAS LAS DELEGACIONES 
    $DB->query("select id,nombre_delegacion as nombre from delegaciones");

    $select_delegaciones="";


    while($delegaciones=$DB->fetch()){

// SELECCIONAMOS LA DELEGACION PRINCIPAL QUE TRAE EL CLIENTE
            if($delegaciones['id']==$datos_cliente[f][0]['id_delegacion']){
        $select_delegaciones.="<option value=\'".$delegaciones['id']."\' selected=\'selected\'>".$delegaciones['nombre']."</option>";
            }else{
        $select_delegaciones.="<option value=\'".$delegaciones['id']."\'>".$delegaciones['nombre']."</option>";

            }


    }

// SACAMOS TODAS LAS OPERADORAS DE LA DELEGACION DEL CLIENTE
    $DB->query("select id,nombre from operadoras where id_delegacion=".$datos_cliente[f][0]['id_delegacion'] ." and estado=1");

    $select_operadoras="";


        while($operadoras=$DB->fetch()){

            // SELECCIONAMOS LA QUE TRAE EL CLIENTE
            if($operadoras['id']==$datos_cliente[f][0]['id_operadora']){

        $select_operadoras.="<option value=\'".$operadoras['id']."\' selected=\'selected\'>".$operadoras['nombre']."</option>";

            }else{

        $select_operadoras.="<option value=\'".$operadoras['id']."\'>".$operadoras['nombre']."</option>";                
            }

    }

     // XARA LAS PROMOCIONES QUE TENGA UNA OPERADORA ASIGNADA EN CONCRETO   
$DB->query("select id,nombre,noches from productos where id_operadora=".$datos_cliente[f][0]['id_operadora']." and estado=1");

    $select_productos="";

while($rows=$DB->fetch()){
    $select_productos.="<option value=\'".$rows['id']."\'>".$rows['nombre']."(".$rows['noches'].")</option>";
}

// XARA PROMOCIONES SIN OPERADORA ASIGNADA (CON ESTO NOS EVITAMOS TENER QUE 
// REPETIR TODAS LAS PROMOCIONES POR OPERADORA QUE NO SEAN ESPECIFICAS)
$DB->query("select id,nombre,noches from productos where id_operadora=0 and estado=1 and id_delegacion=".$datos_cliente[f][0]['id_delegacion']);

//echo "select id,nombre,noches from productos where id_operadora=0 and estado=1 and id_delegacion=".$datos_cliente[f][0]['id_delegacion'];
while($rows=$DB->fetch()){
    $select_productos.="<option value=\'".$rows['id']."\'>".$rows['nombre']."(".$rows['noches'].")</option>";
}



script("$('#id_cliente_envios').html('".$select_cliente."');$('#id_delegacion_envios').html('".$select_delegaciones."');
    $('#id_operadora_envios').html('".$select_operadoras."');
    $('#producto_envios').html('".$select_productos."');");
 
 //eof (isset($cambiar_select_crear_envios))       
}else if(isset($buscar_destino_hotel)){

// AQUI PODEMOS REALIZAR UN MEJORA MUY POTENCIAL
// A TRAVES DE REGEXP DE MYSQL.

// este array es el que devolvemos
// para todas las busquedas
    $output = array();
    $term = utf8_decode($term);

// MEJORA PARA REUTILIZARLO CUANDO VENGAN DESDE
// POP_UP_RESERVAS
if(!isset($buscar_solo_hotel)){

// SECCION DE PROVINCIAS/DESTINOS
    $term = utf8_decode($term);

    $DB->query('select id,nombre,nombre_pais,destino from _provincias where nombre like "%'.$term.'%" and n_hoteles>0 order by nombre limit 10');

    while($row=$DB->fetch()){

        $span="<span class='fa fa-map-marker'></span> ";
        $label = utf8_encode($row['nombre']).", ".utf8_encode($row['nombre_pais']);

        $output[] = array(
                        "label" => $span.$label,
                        "value" => $label,
                        "category" => $span."Destinos",
                        "provincia" => $row['id'],
                        "destino" => $row['destino'],
                        "poblacion" => "0",
                        "busc"=>$label,
                        "hotel" => "0"
                    );
    }

    // SECCION DE POBLACIONES
    $DB->query('select id,nombre,pais_solt,id_provincia_solt,prov_solt,destino from _poblaciones where nombre like "%'.$term.'%" and n_hoteles>0 order by nombre limit 10');


    while($row=$DB->fetch()){
 
        $span="<span class='fa fa-map-pin'></span> ";
        $label = utf8_encode($row['nombre']) ." (".utf8_encode($row['prov_solt']).") - (".utf8_encode($row['pais_solt']). ")";

        $output[] = array(
        
                        "label" => $span.$label,
                        "value" => $label,
                        "category" => $span ."Poblaciones",
                        "poblacion" => $row['id'],
                        "destino" => $row['destino'],
                        "provincia" => $row['id_provincia_solt'],
                        "busc"=>$label,
                        "hotel" => "0"
                    );
    }

} // if(!isset($buscar_solo_hotel){

    // SECCION DE HOTELES
    $DB->query('select id,nombre,poblacion,provincia,id_provincia_solt,id_poblacion_solt,destino from _hoteles where nombre like "%'.$term.'%" and estado=0 and destino!=0 limit 30');

    while($row=$DB->fetch()){
        if($row['nombre']!=NULL || $row['nombre']!=""){

            $span="<span class='fa fa-bed'></span> ";
            $label=utf8_encode($row['nombre'])." (".utf8_encode($row['poblacion']).", ".utf8_encode(trim($row['provincia'])).")";
            $output[] = array(
                            "label" => $span.$label,
                            "value" => $label,
                            "category" => $span."Hoteles",
                            "poblacion" => $row['id_poblacion_solt'],
                            "provincia" => $row['id_provincia_solt'],
                            "destino"=> $row['destino'],
                            "hotel" => $row['id'],
                            "busc"=>$label                             

                        );
        }
    }


    print my_json_encode($output);
 //eof (isset($buscar_destino_hotel))  
}else if(isset($buscar_destino_hotel_segmentos)){

// AQUI PODEMOS REALIZAR UN MEJORA MUY POTENCIAL
// A TRAVES DE REGEXP DE MYSQL.

// este array es el que devolvemos
// para todas las busquedas
    $output = array();

    $term = str_replace("ñ","n",utf8_decode($term));

// MEJORA PARA REUTILIZARLO CUANDO VENGAN DESDE
// POP_UP_RESERVAS
if(!isset($buscar_solo_hotel)){

    //##### SECCION DE PROVINCIAS/DESTINOS

    $DB->query('select id,nombre,nombre_pais,destino,segmentos from _provincias where nombre like "%'.$term.'%" and n_hoteles>0 order by segmentos DESC limit 10');

    while($row=$DB->fetch()){

        $span="<span class='fa fa-map-marker'></span> ";
        $label = utf8_encode($row['nombre']).", ".utf8_encode($row['nombre_pais']);

        $output[] = array(
                        "label" => $span.$label,
                        "value" => $label,
                        "category" => $span."Destinos",
                        "provincia" => $row['id'],
                        "destino" => $row['destino'],
                        "poblacion" => "0",
                        "segmento" => "0",
                        "segmento_2" => "0",
                        "busc"=>$label,
                        "hotel" => "0",
                        "position"=>1
                    );
        // ##### SECCION DE SEGMENTOS QUE PERTENECEN A POBLACIONES
        if($row['segmentos']>0){
            
            $DB2->query('select * from _segmentos where id_provincia_solt='.$row['id'].' and id_poblacion_solt=0');

            while ($row2=$DB2->fetch()) {

                $span="<i class='fa fa-location-arrow'></i> ";
                $label = utf8_encode($row2['nombre']) ." (".utf8_encode($row['nombre']).  ") - (" .utf8_encode($row['nombre_pais']). ")";

                $output[] = array(
        
                        "label" => $span.$label,
                        "value" => $label,
                        "category" => $span ."Segmentos de ".utf8_encode($row['nombre']),
                        "poblacion" => 0,
                        "destino" => $row['destino'],
                        "provincia" => $row['id'],
                        "segmento" => $row2['id'],
                        "segmento_2" => "0",
                        "busc"=>$label,
                        "hotel" => "0",
                        "position"=>2
                    );

            } // while ($row2=$DB2->fetch()) {

        } // if($row['segmentos']>0){
    } // while($row=$DB->fetch()){

    //##### SECCION DE POBLACIONES
    $DB->query('select id,nombre,pais_solt,id_provincia_solt,prov_solt,destino,segmentos from _poblaciones where nombre like "%'.$term.'%" and n_hoteles>0 order by segmentos ASC limit 10');


    while($row=$DB->fetch()){
    

        if(strpos($row['nombre'],'Capital') !== false){

            $span="<span class='fa fa-arrow-right'></span> ";
            $category = $span."Capitales";
            $position=4;
            
        }else{

            $span="<span class='fa fa-map-pin'></span> ";
            $category =$span."Zonas";
            $position=3;

            
        } // if(strpos($row['nombre'],'Capital') !== false){

        $label = utf8_encode($row['nombre']) ." (".utf8_encode($row['prov_solt']).  ") - (" .utf8_encode($row['pais_solt']). ")";
        

        $output[] = array(
        
                        "label" => $span.$label,
                        "value" => $label,
                        "category" => $category,
                        "poblacion" => $row['id'],
                        "destino" => $row['destino'],
                        "provincia" => $row['id_provincia_solt'],
                        "segmento" => "0",
                        "segmento_2" => "0",
                        "busc"=>$label,
                        "hotel" => "0",
                        "position"=>$position
                    );

        // ##### SECCION DE SEGMENTOS QUE PERTENECEN A POBLACIONES
        if($row['segmentos']>0){
            
            $DB2->query('select * from _segmentos where id_poblacion_solt='.$row['id']);

            while ($row2=$DB2->fetch()) {

                $span="<i class='fa fa-location-arrow'></i> ";
                $label = utf8_encode($row2['nombre']) ." (".utf8_encode($row['prov_solt']).  ") - (" .utf8_encode($row['pais_solt']). ")";

                $output[] = array(
        
                        "label" => $span.$label,
                        "value" => $label,
                        "category" => $span ."Segmentos de ".utf8_encode($row['nombre']),
                        "poblacion" => $row['id'],
                        "destino" => $row['destino'],
                        "provincia" => $row['id_provincia_solt'],
                        "segmento" => $row2['id'],
                        "segmento_2" => "0",
                        "busc"=>$label,
                        "hotel" => "0",
                        "position"=>5
                    );

            } // while ($row2=$DB2->fetch()) {

        } // if($row['segmentos']>0){

    } // while($row=$DB->fetch()){

} // if(!isset($buscar_solo_hotel){


    # SEGMETOS DE LAS CAPITALES 
    $DB->query('select 
            s.id,
            s.nombre,
            s.id_poblacion_solt,
            s.destino,
            pr.id as id_provincia_solt,
            pr.nombre as prov_solt,
            pr.nombre_pais from _segmentos s 
            inner join _provincias pr 
            on s.id_provincia_solt=pr.id where s.nombre like "%'.$term.'%" and s.destino>0 and id_poblacion_solt=0');


    while($row=$DB->fetch()){
 

            $span="<span class='fa fa-location-arrow'></span> ";
            $label = utf8_encode($row['nombre']) ." (".utf8_encode($row['prov_solt']).  ") - (" .utf8_encode($row['nombre_pais']). ")";

            $output[] = array(
            
                            "label" => $span.$label,
                            "value" => $label,
                            "category" => $span ."Segmentos de ".utf8_encode($row['prov_solt']),
                            "poblacion" => 0,
                            "destino" => $row['destino'],
                            "provincia" => $row['id_provincia_solt'],
                            "segmento" => $row['id'],
                            "segmento_2" => "0",
                            "busc"=>$label,
                            "hotel" => "0",
                            "position"=>6
                        );
    }




    ## SEGMENTOS DE LAS POBLACIONES 
    $DB->query('select 
            s.id,
            s.nombre,
            s.id_poblacion_solt,
            s.destino,
            p.id_provincia_solt,
            p.nombre as nombre_poblacion,
            p.prov_solt,
            p.pais_solt from _segmentos s 
            inner join _poblaciones p 
            on s.id_poblacion_solt=p.id where s.nombre like "%'.$term.'%" and s.destino>0 and id_poblacion_solt>0');


    while($row=$DB->fetch()){
 

            $span="<span class='fa fa-location-arrow'></span> ";
            $label = utf8_encode($row['nombre']) ." (".utf8_encode($row['prov_solt']).  ") - (" .utf8_encode($row['pais_solt']). ")";

            $output[] = array(
            
                            "label" => $span.$label,
                            "value" => $label,
                            "category" => $span ."Segmentos de ".utf8_encode($row['nombre_poblacion']),
                            "poblacion" => $row['id_poblacion_solt'],
                            "destino" => $row['destino'],
                            "provincia" => $row['id_provincia_solt'],
                            "segmento" => $row['id'],
                            "segmento_2" => "0",
                            "busc"=>$label,
                            "hotel" => "0",
                            "position"=>7
                        );
    }




    // SECCION DE HOTELES
    $DB->query('select id,
        nombre,
        poblacion,
        provincia,
        id_provincia_solt,
        id_poblacion_solt,
        id_segmento_solt,
        destino from _hoteles where nombre like "%'.$term.'%" and estado=0 and destino!=0 limit 30');

    while($row=$DB->fetch()){
        if($row['nombre']!=NULL || $row['nombre']!=""){

            $span="<span class='fa fa-bed'></span> ";
            $label=utf8_encode($row['nombre']) . " (" .utf8_encode($row['poblacion']). ", " .utf8_encode(trim($row['provincia'])). ")";
            $output[] = array(
                            "label" => $span.$label,
                            "value" => $label,
                            "category" => $span."Hoteles",
                            "poblacion" => $row['id_poblacion_solt'],
                            "provincia" => $row['id_provincia_solt'],
                            "destino"=> $row['destino'],
                            "hotel" => $row['id'],
                            "segmento" => $row['id_segmento_solt'],
                            "segmento_2" => 0,
                            "busc"=>$label,
                            "position"=>8                            

                        );
        }
    }


    // con esto eliminamos las duplicidades en los resultados,
    // cuando haciamos una consulta y contenia el segmento asociado a una poblacion
    // mas el segmento por coincidencias con el nombre
    $output = unique_multidim_array($output,'value');

    // CON ESTO ORDENAMOS Y UNIFICAMOS POR LAS CATEGORIAS Y ELIMINAMOS EL ERROR
    // DE QUE CUANDO UNA POBLACION CONTENIA SEGMENTOS, SE DIVIDIAN LAS POBLACIONES
    // ANUNCIABAN LOS SEGMENTOS Y LUEGO CONTINUABAN
    $output = array_orderby($output, 'position', SORT_ASC, 'category', SORT_ASC);

    print my_json_encode($output);
 //eof (isset($buscar_destino_hotel_segmentos))  
}else if(isset($buscar_cliente_autocomplete)){

    $output = array();
    $term = utf8_decode($term);
    $DB->query('select id, nombre, identificador,email,telefono from clientes where nombre like "%'.$term.'%" limit 10');

        while($row=$DB->fetch()){

            $span="<span class='fa fa-user'></span> ";
            $span2="<span class='fa fa-user-circle-o'></span> ";
            $label=utf8_encode($row['nombre']) . " (" .$row['identificador'].")";

            // if(isset($email_json) && $email_json==1){

                $email_arr=json_decode($row['email']);
                $end=count($email_arr);

                // si solo 1
                if($end==1){
                    $row['email'] = $email_arr[0];
                }else if($end>1){ // if($end==1){
                    // si mas de 1
                    $new_value="";
                    for($e=1;$e<=$end;$e++){
                        if($e<$end){
                            $new_value.=$email_arr[$e-1].";";
                        }else{
                            $new_value.=$email_arr[$e-1];
                        }
                    }// for($e=1;$e<=$end;$e++){
                    $row['email']=$new_value;
                }// }else if($end>1){

            // }

            $output[] = array(
                            "label" => $span2.$label,
                            "value" => $label,
                            "category" => $span."Clientes",
                            "id_cliente" => $row['id'],
                            "nombre" => $row['nombre'],
                            "email_cliente" => $row['email'],
                            "telefono_cliente" => $row['telefono'] 
                        );
        }


        print my_json_encode($output);

// eof }else if(isset($buscar_cliente_autocomplete)){ 
}else if(isset($clientes_fetico)){ 

        $output= array();
        $term = utf8_decode($term);

        $DB->query('select * from validaciones_bdh where code like "%'.$term.'%"');


        if($DB->rows()==0){

                $label = utf8_encode("Este codigo no existe");
                $output[] = array(
                                "label" => $label,
                                "value" => $label,
                                "desc" => "",
                                
                            );

        }else{ // if($DB->rows()==0){


            while($row=$DB->fetch()){
                  

                $QR = new DataAccess();
                $QR->query('select nombre,email from clientes where id='.$row['id_cliente']);

                if($QR->rows()==0){

                    $label = $row['code'];
                    $output[] = array(
                        "label" => $label,
                        "value" => $label,
                        "desc" => " No esta validado",
                        "category" => "Codigos Fetico"
                        );


                }else{

                    $label = $row['code'];
                    $cliente = $QR->fetch();
                    $output[] = array(
                        "label" => $label,
                        "value" => $label,
                        "desc" => $cliente['nombre']." ".$cliente['email'],
                        "category" => "Codigos Fetico"
                        );

                } // }else{
            } //  while($row=$DB->fetch()){
        } // if($DB->rows()==0){ .. else


    print my_json_encode($output);
        
// eof }else if(isset($clientes_fetico)){       
}else if(isset($buscar_cliente_ajax)){

        $buscar_cliente_ajax = utf8_decode($buscar_cliente_ajax);

        $DB->query('select id,nombre,identificador,comentario,noches,email,telefono,fecha from clientes 
            where id="'.$buscar_cliente_ajax.'" or  nombre like "%'.$buscar_cliente_ajax.'%" 
            or telefono like "%'.$buscar_cliente_ajax.'%" 
            or identificador like "%'.$buscar_cliente_ajax.'%"  
            or email like "%'.$buscar_cliente_ajax.'%" 
            or direccion like "%'.$buscar_cliente_ajax.'%"
            
            order by id DESC
            limit 10');



        if($buscar_cliente_ajax==""){ 

            script("$('#div_tabla_ultimos_clientes').removeClass('hide');
                $('#tabla_cliente').addClass('hide');");

        }else if($DB->rows()==0){

            script("$('#tabla_cliente').removeClass('hide');
                $('#tabla_cliente').html('Con estos datos no tenemos ning&uacute;n registro de cliente.');
                $('#div_tabla_ultimos_clientes').addClass('hide');");
         

        }else if($DB->rows()>0){


            $datos_cliente= '<table class="table table-hover table-bordered table-responsive table-striped">
                    <thead>
                        <tr>
                            <th>Ids</th>
                            <th>Nombre</th>
                            <th>Fecha</th>
                            <th>Identificador</th>
                            <th>Password</th>
                            <th>Reserva</th>             
                            <th>Envio</th>
                            <th>Email</th>
                            <th>Editar</th>
                            <th>Borar</th>
                        </tr>
                    </thead>
                    <tbody>';
            while($data = $DB->fetch()){

                $noches_c = json_decode($data['noches'],true);
                $email_c = email_json_decode($data['email']);

                $datos_cliente.= '<tr>
                        <td class="info">'.$data['id'].'</td>  
                        <td class="info" ><a href="mailto:'.$email_c.'" data-placement="bottom" data-toggle="tooltip" 
                        title="Email: '.$email_c.'\n Telefono: '.$data['telefono'].'\n Disponibles: '.$noches_c['disponibles'].'\n Usados: '.$noches_c['usados'].'\n Comprados: '.$noches_c['comprados'].'\n">'.utf8_encode($data['nombre']).'</a> ('.$data['id'].')</td>
                        <td class="info">'.formatear_fecha($data['fecha'],m,i).'</td>  
                        <td class="info">'.$data['identificador'].'</td>  
                        <td class="info">'.$data['comentario'].'</td>
                        <td class="info">  
                            <a href="#"
                            name="crear_reservas"
                            id_cliente="'.$data['id'].'">
                            <i class="fa fa-building-o fa-2x pull-right" style="color:green;"></i>
                            </a>
                        </td>
                        <td class="info"> 
                            <a href="#"
                            name="crear_envios"
                            data-toggle="modal" 
                            data-target="#crear_envios" 
                            id_cliente="'.$data['id'].'">
                            <i class="fa fa-paper-plane-o fa-2x pull-right"></i>
                            </a>
                        </td>
                        <td class="info">            
                            <a href="#"
                            data-toggle="modal" 
                            data-target="#email_registro"
                            name="enviar_email"
                            tabla="clientes"
                            id="'.$data['id'].'">
                            <i class="fa fa-envelope-o fa-2x pull-right" style="color:green;"></i>
                            </a>
                        </td>
                        <td class="info">            
                            <a href="#"
                            data-toggle="modal" 
                            data-target="#editar_clientes_editar"
                            name="editar"
                            tabla="clientes"
                            id="'.$data['id'].'">
                            <i class="fa fa-edit fa-2x pull-right" ></i>
                            </a>
                        </td>
                        <td class="info">
                            <a href="#"
                            data-toggle="modal" 
                            data-target="#borrar_registro"
                            name="delete" 
                            id="'.$data['id'].'">
                            <i class="fa fa-times-circle fa-2x pull-right" style="color:red;"></i>
                            </a>
                        </td>
                    </tr>';
            } //   while($data = $DB->fetch()){
            
            $datos_cliente.= "  </tbody>
                </table>";

            $clean   = array("\r\n", "\n", "\r","\t","\0","\x0B");
            $tabla_cliente=str_replace($clean, '',$datos_cliente);
            $tabla_cliente=str_replace('"', "'", $tabla_cliente);

            script('
                    $("#tabla_cliente").removeClass("hide");
                    $("#tabla_cliente").html("'.$tabla_cliente.'");
                    $("#div_tabla_ultimos_clientes").addClass("hide");
                ');

    } // }else if($DB->rows()>0){

    $query = 'select b.id as id,
                   b.id as ids,
            c.nombre as cliente,
            c.id as identificador,
            b.entrada as entrada,
            b.salida as salida,
            b.creacion,
            operadora,
            eb.nombre as estados from booking b inner join clientes c on b.id_cliente=c.id inner join estados_booking eb on eb.id=b.id_estado where';


    if (preg_match('/\d{2}\/\d{2}\/\d{4}/',$buscar_cliente_ajax)) {
        
        $query .= '
            b.entrada = "'.formatear_fecha($buscar_cliente_ajax,i,m).'"
            or b.salida = "'.formatear_fecha($buscar_cliente_ajax,i,m).'"
            order by b.id DESC limit 15';

    }else{
        

        $query .= '
            c.id="'.$buscar_cliente_ajax.'" 
            or b.id="'.$buscar_cliente_ajax.'"
            or c.nombre like "%'.$buscar_cliente_ajax.'%" 
            or eb.nombre like "%'.$buscar_cliente_ajax.'%"
            or c.identificador like "%'.$buscar_cliente_ajax.'%"
            or c.telefono like "%'.$buscar_cliente_ajax.'%"   
            or c.email like "%'.$buscar_cliente_ajax.'%" 
            or c.direccion like "%'.$buscar_cliente_ajax.'%" 
            order by b.id DESC limit 15';
    }

    $DB->query($query);

        if($buscar_cliente_ajax==""){ 

            script("$('#div_tabla_ultimos_booking').removeClass('hide');
                $('#tabla_booking').addClass('hide');");


        }else if($DB->rows()==0){

            script("$('#tabla_booking').removeClass('hide');
                $('#tabla_booking').html('Con estos datos no tenemos ning&uacute;n registro de booking.');
                $('#div_tabla_ultimos_booking').addClass('hide');");
         
        }else if($DB->rows()>0){

            $datos_booking= '<table class="table table-hover table-bordered table-responsive table-striped">
                    <thead>
                        <tr>
                            <th>Ids</th>
                            <th>Creacion</th>
                            <th>Cliente</th>
                            <th>Entrada</th>
                            <th>Salida</th>
                            <th>Operadora</th>             
                            <th>Estados</th>
                            <th>Borrar</th>
                        </tr>
                    </thead>
                    <tbody>';
            while($data_booking = $DB->fetch()){


                $tooltip_booking='';
                if($data_booking['estados']=="Gestionando"){
                    $bg_class='bg_amarillo';
                }else if($data_booking['estados']=="Pendiente"){
                    $bg_class='bg_rojo';
                    $tooltip_booking=' data-id="'.$data_booking['id'].'" data-placement="bottom" data-toggle="tooltip" title="Haz DobleClick para gestionarla"';
                }else if($data_booking['estados']=="En proceso"){
                    $bg_class='bg_naranja';
                }else if($data_booking['estados']=="Enviado"){
                    $bg_class='bg_verde';
                    $tooltip_booking=' data-id="'.$data_booking['id'].'" data-placement="bottom" data-toggle="tooltip" title="Haz DobleClick para ver lo enviado"';
                }else{
                    $bg_class='bg_verde';               
                }

                // sacamos la fecha de creacion 
                $pre_fecha = explode(" ",$data_booking['creacion']);
                $nueva_fecha=formatear_fecha($pre_fecha[0],m,i);

                $datos_booking.= '<tr>
                        <td class="info">'.$data_booking['ids'].'</td>
                        <td class="info">'.$nueva_fecha." ".$pre_fecha[1].'</td>
                        <td class="info">'.utf8_encode($data_booking['cliente']).' ('.$data_booking['identificador'].')</td>  
                        <td class="info">'.formatear_fecha($data_booking['entrada'],m,i).'</td>
                        <td class="info">'.formatear_fecha($data_booking['salida'],m,i).'</td>
                        <td class="info">'.utf8_encode($data_booking['operadora']).'</td> 
                        <td class="'.$bg_class.'" data="booking" '.$tooltip_booking.'>'.$data_booking['estados'].'</td>  
                        <td class="info">
                            <a href="#"
                            data-toggle="modal" 
                            data-target="#borrar_registro_2"
                            name="delete_2" 
                            id="'.$data_booking['id'].'">
                            <i class="fa fa-times-circle fa-2x pull-right" style="color:red;"></i>
                            </a>
                        </td>
                    </tr>';
            } //   while($data = $DB->fetch()){
            
            $datos_booking.= "  </tbody>
                </table>";

            $clean   = array("\r\n", "\n", "\r","\t","\0","\x0B");
            $tabla_booking=str_replace($clean, '',$datos_booking);
            script("
                    $('#tabla_booking').removeClass('hide');
                    $('#tabla_booking').html('".$tabla_booking."');
                    $('#div_tabla_ultimos_booking').addClass('hide');
                ");

        } // if($buscar_cliente_ajax==""){


        $query = 'select 
                r.id as ids,
                r.id,
                r.estado,
                r.localizador,
                r.voucher,
                r.cobrada,
                r.proveedor, 
                c.nombre,
                c.email,
                c.id as identificador,
                h.nombre as hotel
                from reservas_realizadas r inner join clientes c on r.id_cliente=c.id 
                inner join _hoteles h on r.id_hoteles = h.id 
                where';


        if (preg_match('/\d{2}\/\d{2}\/\d{4}/',$buscar_cliente_ajax)) {
            
            $query .= '
                r.entrada = "'.formatear_fecha($buscar_cliente_ajax,i,m).'" or 
                r.salida = "'.formatear_fecha($buscar_cliente_ajax,i,m).'" 
                order by r.id DESC limit 10';

        }else{
            

            $query .= '
                r.id = "'.$buscar_cliente_ajax.'" or
                r.voucher like "%'.$buscar_cliente_ajax.'%" or 
                r.localizador like "%'.$buscar_cliente_ajax.'%" or
                r.neto = '.((int) $buscar_cliente_ajax/100).' or
                r.total = '.((int) $buscar_cliente_ajax/100).' or
                c.nombre like "%'.$buscar_cliente_ajax.'%" or
                c.identificador like "%'.$buscar_cliente_ajax.'%" or
                c.telefono like "%'.$buscar_cliente_ajax.'%" or  
                c.email like "%'.$buscar_cliente_ajax.'%" or 
                h.nombre like "%'.$buscar_cliente_ajax.'%" 
                order by r.id DESC limit 10';
        }

        $DB->query($query);
            
            


        if($buscar_cliente_ajax==""){ 

            script("$('#div_tabla_ultimas_reservas').removeClass('hide');
                $('#tabla_reservas').addClass('hide');");

        }else if($DB->rows()==0){

            script("$('#tabla_reservas').removeClass('hide');
                $('#tabla_reservas').html('Con estos datos no tenemos ninguna reserva.');
                $('#div_tabla_ultimas_reservas').addClass('hide');");

         
        }else if($DB->rows()>0){

                $datos_reservas= '<table class="table table-hover table-bordered table-responsive table-striped">
                    <thead>
                        <tr>
                            <th>Ids</th>
                            <th>Cliente</th>
                            <th>Titular</th>
                            <th>Entrada</th>
                            <th>Salida</th>
                            <th>Localizador</th>
                            <th>Hotel</th>             
                            <th>Estado</th>
                            <th>Cobrada</th>
                            <th>Cancelar</th>
                        </tr>
                    </thead>
                    <tbody>';
            while($data_reservas = $DB->fetch()){

                if($data_reservas['estado']=="1"){
                    $bg_class='bg_verde';
                    $estado="Confirm";
                    $icono_clx='<a href="#"
                                    class="gastos_reserva"
                                    id_reserva="'.$data_reservas['id'].'">
                                    <i class="fa fa-times-circle fa-2x pull-right" style="color:red;" data-toggle="tooltip" data-placement="top" title="Click para Conocer los gastos"></i>
                                </a>';
                }else if($data_reservas['estado']=="2"){
                    $bg_class='bg_naranja';
                    $estado="Cancel";
                    $icono_clx='<i class="fa fa-times-circle fa-2x pull-right" style="color:green;" data-toggle="tooltip" data-placement="top" title="La Reserva ya esta Cancelada"></i>';
                }else if($data_reservas['estado']=="3"){
                    $bg_class='bg_amarillo';
                    $estado="Fact";
                    $icono_clx='<i class="fa fa-times-circle fa-2x pull-right" style="color:green;" data-toggle="tooltip" data-placement="top" title="La Reserva ya esta Facturada"></i>';
                }

                $email_pago_rsv="";
                $tooltip_email_pago="";

                if($data_reservas['cobrada']=="1"){

                    $bg_cobrada='bg_verde';
                    $cobrada="Si";

                }else if($data_reservas['cobrada']=="0"){

                    $bg_cobrada='bg_rojo';
                    $cobrada="No";

                    if($data_reservas['estado']=="1"){

                        $email_pago_rsv = "email_pago_rsv";
                        $tooltip_email_pago='data-toggle="tooltip" data-placement="top" title="DobleClick Para enviar email de pago"';
                    }

                }
                $voucher_reserva = json_decode(utf8_encode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '',$data_reservas['voucher'])),true);
                // $voucher_reserva = json_decode(utf8_encode($data_reservas['voucher']),true);
                $datos_reservas.= '<tr>
                        <td class="info" data-toggle="tooltip" data-placement="top" title="Click en el sobre para reenviar el voucher">'.$data_reservas['ids'].' - <i class="fa fa-envelope-o reenvio_voucher txt_red" id_reserva="'.$data_reservas['ids'].'" data-toggle="modal" data-target="#reenviar_voucher_modal"
                            data-email="'.email_json_decode($data_reservas['email']).'"></i>
                        </td>
                        <td class="info mostrar_voucher" data-toggle="tooltip" data-placement="top" 
                            title="DobleClick para mostrar el voucher" 
                            id_reserva="'.$data_reservas['ids'].'">'.utf8_encode($data_reservas['nombre']).' ('.$data_reservas['identificador'].')</td> 
                        <td class="info">'.$voucher_reserva['titular_reserva'].'</td> 
                        <td class="info">'.formatear_fecha($voucher_reserva['entrada'],m,i).'</td>
                        <td class="info">'.formatear_fecha($voucher_reserva['salida'],m,i).'</td>
                        <td class="info" data-toggle="tooltip" data-placement="top" title="'.$data_reservas['proveedor'].'">'.$data_reservas['localizador'].'</td>
                        <td class="info mostrar_proforma" data-toggle="tooltip" data-placement="top" 
                                                                title="DobleClick para mostrar la proforma" 
                                                                id_reserva="'.$data_reservas['ids'].'" >'.utf8_encode($data_reservas['hotel']).'</td>
                        <td class="'.$bg_class.' ficha_reserva" data-toggle="tooltip" data-placement="top" title="DobleClick Para abrir la ficha" id_reserva="'.$data_reservas['id'].'">'.$estado.'</td> 
                         <td class="'.$bg_cobrada.' '.$email_pago_rsv.'" id_reserva="'.$data_reservas['id'].'" '.$tooltip_email_pago.'>'.$cobrada.'</td>  
                        <td class="info">
                            <form action="" method="post" id="form_gastos_'.$data_reservas['id'].'">
                                <input type="hidden" name="id_reserva_gastos" value="'.$data_reservas['id'].'"/>
                                <input type="hidden" name="proveedor" value="'.$data_reservas['proveedor'].'"/>
                                '.$icono_clx.'
                            </form>
                        </td>
                    </tr>';
            } //   while($data = $DB->fetch()){
            
            $datos_reservas.= "  </tbody>
                </table>";

            $clean   = array("\r\n", "\n", "\r","\t","\0","\x0B");
            $tabla_reservas=str_replace($clean, '',$datos_reservas);
            script("
                    $('#tabla_reservas').removeClass('hide');
                    $('#tabla_reservas').html('".$tabla_reservas."');
                    $('#div_tabla_ultimas_reservas').addClass('hide');
                ");
            
        } // }else if($DB->rows()>0){


// eof }else if(isset($buscar_cliente_ajax)){ 
}else if(isset($buscar_proveedor_ajax)){

    if($tabla_mapeo=="_paises"){ 

        if($buscar_proveedor_ajax=='versys' || $buscar_proveedor_ajax=='veturis'){
            $cond = ' id_'.$buscar_proveedor_ajax.'=0';

        }else{
            $cond= ' id_'.$buscar_proveedor_ajax.' like "0" and id_'.$buscar_proveedor_ajax.' not like ""';
        }

        $DB->query('select id,nombre,id_'.$buscar_proveedor_ajax.' from _paises where '.$cond);

        // echo 'select id,nombre,id_'.$buscar_proveedor_ajax.' from _paises where '.$cond;



        $datos_tabla=array("id",
            "nombre",
            "id_".$buscar_proveedor_ajax);



    }else if($tabla_mapeo=="_segmentos_2"){ // if($tabla_mapeo=="_paises"){ 



        $DB->query('select id,nombre,id_'.$buscar_proveedor_ajax.', multy_'.$buscar_proveedor_ajax.'
            from _segmentos_2 where id_'.$buscar_proveedor_ajax.'=0 
            and multy_'.$buscar_proveedor_ajax.'=0');

        $datos_tabla=array("id",
            "nombre",
            "id_".$buscar_proveedor_ajax,
            "multy_".$buscar_proveedor_ajax);
 
    }else if($tabla_mapeo=="_poblaciones"){ // if($tabla_mapeo=="_segmentos_2"){ 



        $DB->query('select id,nombre,id_'.$buscar_proveedor_ajax.'
            from _poblaciones where id_'.$buscar_proveedor_ajax.'=0 and id_pais_solt=53');

        $datos_tabla=array("id",
            "nombre",
            "id_".$buscar_proveedor_ajax);
 
    } // }else if($tabla_mapeo=="_segmentos_2"){


        $datos_cliente= '<table class="table table-hover table-bordered table-responsive table-striped">
                    <thead>
                        <tr>';


        foreach($datos_tabla as $value) {
                        
            if($value!="id"){
                $datos_cliente.="<th>".ucwords(strtolower($value))."</th>";
            }
        } // foreach($datos_tabla as $value) {

        while($data = $DB->fetch()){

                $datos_cliente.= '<tr class="info">';

                    foreach($datos_tabla as $key) {

                        if($key!="id"){
                            
                            $datos_cliente.= '<td>
                            <input type="text"
                            tabla_editar="'.$tabla_mapeo.'"
                            id_editar="'.$data["id"].'" 
                            name="'.$key.'"
                            class="edita_input form-control"  
                            value="'.utf8_encode($data[$key]).'" 
                            placeholder="">
                            </td>';

                        }

                    }

                $datos_cliente.= '</tr>';
               
            } //   while($data = $DB->fetch()){
            
            $datos_cliente.= "  </tbody>
                </table><hr>";

            echo $datos_cliente;
    

// eof }else if(isset($buscar_proveedor_ajax)){
}else if(isset($buscar_mapeo_ajax)){ 


    if($tabla_mapeo=="_paises" && $buscar_mapeo_ajax!=""){


            $proveedor = $proveedor=="versys" ? "w2m" : $proveedor;

                $DB->query('select * from _paises_'.$proveedor.' where country_name like "%'.$buscar_mapeo_ajax.'%" and mapeado=0');

                $datos_tabla=array("id",
                            "country_name",
                            "country_id",
                            "mapeado");

    }else if($tabla_mapeo=="_segmentos_2" && $buscar_mapeo_ajax!=""){ // $tabla_mapeo=="_paises" ...
    

        if($proveedor=='veturis'){

            $datos_tabla=array("id",
                    "zone_name",
                    "zone_id",
                    "mapeado");

            $select = "";
            $c=0;
            foreach ($datos_tabla as $key) {
                
                if($c>0){

                    $select .=",";

                }
                $select .=$key;
                $c++;
            }
            $DB->query('select '.$select.' from _poblaciones_veturis where zone_name like "%'.$buscar_mapeo_ajax.'%"');


        } // if($buscar_proveedor_ajax=='veturis'){


   
    }else if($tabla_mapeo=="_poblaciones" && $buscar_mapeo_ajax!=""){ // $tabla_mapeo=="_paises" ...
    

        if($proveedor=='versys'){

            $datos_tabla=array("id",
                    "Name",
                    "Code",
                    "mapeado");

            $select = "";
            $c=0;
            foreach ($datos_tabla as $key) {
                
                if($c>0){

                    $select .=",";

                }
                $select .=$key;
                $c++;
            }
            $DB->query('select '.$select.' from _poblaciones_w2m where Name like "%'.$buscar_mapeo_ajax.'%" and mapeado=0 and country_id=118');



        } // if($buscar_proveedor_ajax=='veturis'){


    }
                $datos_cliente='<div class="col-xs-12" >
                                    <div class="input-group mb20">
                                        <span class="input-group-btn">
                                                <button class="btn btn-danger" type="button" name="borrado_unicersal">X</button>
                                        </span>
                                             <input type="text" name="buscar_mapeo_ajax_2" class="form-control col-xs-6" value="" placeholder="Buscador Universal">
                                             <span class="input-group-btn">
                                                <button class="btn btn-success" type="button" name="go">go</button>
                                              </span>
                                    </div><!-- /input-group --> 
                                </div>';

                if($DB->rows()==0){

                   $datos_cliente.= '<h3> '.$proveedor.' Sin Resultados   </h3>';

                }else{ // if($DB->rows()==0){

                    $datos_cliente.= '
                        <table class="table table-hover table-bordered table-responsive table-striped">

                        <thead>
                            <tr>';

                    foreach($datos_tabla as $value) {
                            
                        if($value!="id"){
                            $datos_cliente.="<th>".ucwords(strtolower($value))."</th>";
                        }
                    } // foreach($datos_tabla as $value) {

                    $datos_cliente.='</tr>
                        </thead>
                        <tbody>';

                    while($data = $DB->fetch()){

                        $datos_cliente.= '<tr class="info">';

                        foreach($datos_tabla as $key) {

                            if($key!="id"){
                                
                                $datos_cliente.= '<td>
                                <input type="text"
                                tabla_editar="'.$tabla_mapeo.'"
                                id_editar="'.$data["id"].'" 
                                name="'.$key.'"
                                class="edita_input form-control"  
                                value="'.utf8_encode($data[$key]).'" 
                                placeholder="">
                                </td>';

                            }

                        }

                        $datos_cliente.= '</tr>';
                   
                    } //   while($data = $DB->fetch()){

                } // }else{ // if($DB->rows()==0){
                     $datos_cliente.= "  </tbody>
                </table><hr>";
           
        echo $datos_cliente;


 // eof }else if(isset($buscar_mapeo_ajax)){ 
}else if(isset($buscar_cliente_grutinet)){

    $output = array();
    $term = utf8_decode($term);

    $DB->query('select id,nombre,tracking,telefono from grutinet_clientes where nombre like "%'.$term.'%" limit 10');

        while($row=$DB->fetch()){


            $label=utf8_encode($row['nombre']) . " ".$row['telefono']." (" .$row['tracking'].")";

            $output[] = array(
                            "category" => "Clientes",
                            "label" => $label,
                            "value" => $label,
                            'id'=>$row['id']
                        );

        }
        print my_json_encode($output);

// eof }else if(isset($buscar_cliente_grutinet)){ 
}else if(isset($id_reserva_email)){ 

    #  DEL CLIENTE -> IDENTIFICADOR 
    # DE LA DELEGACION -> URL, USURARIO, TPV_PASS
    # DE LA RESERVA -> Importe, id_comunicacion
    # de la comunicacion del tpv # el DS_MERCHANT_ORDER

    // metemos el tpv
    include('redsysHMAC256_API_PHP_4.0.2/apiRedsys.php');
    $miObj = new RedsysAPI;
    $action='https://sis.redsys.es/sis/realizarPago';//produccion
    $terminal="001";
    $moneda="978";
    $trans="0"; // cobro 
    //$trans="3"; // devolucion automatica 

    // reuso del proceso para el envio de los importes personalizados.
    if(isset($importe_personalizado) && $id_reserva_email==0){


            $data = $DB->inner_join('d.web,d.usuario,d.tpv_pass,d.id_via_cobro_web',
            'clientes c inner join delegaciones d',
            'c.id_delegacion=d.id where c.id='.$id_cliente);

            $datos = $data[f][0];

            $DS_MERCHANT_ORDER=time();
            $post_json=json_encode_bbdd($_GET, JSON_UNESCAPED_UNICODE);

            $DB->query("insert into comunicacion_tpv_personalizado 
            (id,varpost,id_cliente,DS_MERCHANT_ORDER,total,finalizada,fecha,id_via_cobro) values 
            (NULL,
            '".$post_json."',
            ".$id_cliente.",
            '".strval($DS_MERCHANT_ORDER)."',
            ".$importe_personalizado.",
            0,
            '".now()."',
            ".$datos['id_via_cobro_web'].")");

            $total = $importe_personalizado;
            $web = str_replace("www.", "", $datos['web']);
            $urlOK = "https://".$web."/confirmacion_de_pagos.php?pago_importe_personalizado=1";
            $urlKO = "https://".$web."/confirmacion_de_pagos.php?pago_importe_personalizado=0";
            $fuc=$datos['usuario'];
            $kc =$datos['tpv_pass']; 
            ## SIEMPRE AÑADIR ESTA URL A .htaccess SIN EL HTTPS 
            $url_url="https://".$datos['web'];


    }else if(!isset($importe_personalizado) && $id_reserva_email>0){

            $data = $DB->inner_join('r.total,
                r.id_comunicacion_tpv_reservas,
                t.DS_MERCHANT_ORDER,
                c.identificador,
                d.usuario,
                d.tpv_pass,
                d.web'
                ,'reservas_realizadas r inner join clientes c'
                ,'r.id_cliente = c.id inner join comunicacion_tpv_reservas t on r.id_comunicacion_tpv_reservas = t.id inner join delegaciones d on c.id_delegacion = d.id  where r.id='.$id_reserva_email,f);

            $datos = $data[f][0];
            $DS_MERCHANT_ORDER = $datos['DS_MERCHANT_ORDER'];
            $total = $datos['total'];

            $web = str_replace("www.", "", $datos['web']);

            $urlOK = "https://".$web."/confirmacion_de_pagos.php?pago_reserva_pendiente=1";
            $urlKO = "https://".$web."/confirmacion_de_pagos.php?pago_reserva_pendiente=0";

            $fuc=$datos['usuario'];
            $kc =$datos['tpv_pass']; 
            ## SIEMPRE AÑADIR ESTA URL A .htaccess SIN EL HTTPS 
            $url_url="https://".$datos['web'];

    } // }else if(!isset($importe_personalizado) && $id_reserva_email>0){


    $miObj->setParameter("DS_MERCHANT_AMOUNT",$total);
    $miObj->setParameter("DS_MERCHANT_ORDER",strval($DS_MERCHANT_ORDER));
    $miObj->setParameter("DS_MERCHANT_MERCHANTCODE",$fuc);
    $miObj->setParameter("DS_MERCHANT_CURRENCY",$moneda);
    $miObj->setParameter("DS_MERCHANT_TRANSACTIONTYPE",$trans);
    $miObj->setParameter("DS_MERCHANT_TERMINAL",$terminal);
    $miObj->setParameter("DS_MERCHANT_MERCHANTURL",$url_url);
    $miObj->setParameter("DS_MERCHANT_URLOK",$urlOK);   
    $miObj->setParameter("DS_MERCHANT_URLKO",$urlKO);

    //Datos de configuración
    $version="HMAC_SHA256_V1";

    // Se generan los parámetros de la petición
    // $request = "";
    $params = $miObj->createMerchantParameters();
    $signature = $miObj->createMerchantSignature($kc);

    echo "<form name='frm' id='frm' action='".$action."' method='POST' >\n";
    echo "<input type='hidden' name='Ds_SignatureVersion' value='".$version."'/>\n";
    echo "<input type='hidden' name='Ds_MerchantParameters' value='".$params."'/>\n";
    echo "<input type='hidden' name='Ds_Signature' value='".$signature."'/>\n";
    // // submitamos el form 
    script('document.getElementById("frm").submit();');

    exit();

// eof }else if(isset($id_reserva_email)){ 
}else if(isset($modificar_bonos_gastados)){ 

    // saco los bonos anteriores para poder sumar o restar segun 
    // me vengan en esta nueva cantidad
    $bonos_anteriores = $DB->select('bonos_gastados,historico','reservas_realizadas','id='.$id_reserva);

    // sacamos el tipo de operacion que vamos a hacer
    $tipo = $bonos_anteriores[f][0]['bonos_gastados'] > $modificar_bonos_gastados ? s : r;

    // ahora saco el numero de bonos que tengo que pasar por parametro a la operacion
    $new_bonos = $tipo==s ? $bonos_anteriores[f][0]['bonos_gastados'] - $modificar_bonos_gastados : $modificar_bonos_gastados - $bonos_anteriores[f][0]['bonos_gastados'];

    $nuevo_historico = sumar_historico_xml($bonos_anteriores[f][0]['historico'],$_SESSION['nombre_usuario'],'Cambio_Bonos',$tipo,$new_bonos);

     $DB->query("update reservas_realizadas set historico = '".$nuevo_historico."', bonos_gastados=".$modificar_bonos_gastados." where id=".$id_reserva);

    $nuevos_bonos = bonos($tipo,$new_bonos,$id_cliente);
    // modificamos las noches del cliente.
    $DB->query("update clientes set noches = '".$nuevos_bonos."' where id=".$id_cliente);

    script("noty({text: 'Modificacion Realizada', layout: 'topRight', type: 'success'});
                            setTimeout('$.noty.closeAll()',3000);");
//}else if(isset($modificar_bonos_gastados)){ 
}else if(isset($pasar_a_pagado) || isset($devolucion_manual)){  

    // generamos el movimiento bancario
    $DB->openTable('movimientos_bancarios');

    $id_via_cobro = isset($pasar_a_pagado) ? $pasar_a_pagado : $id_via_cobro_devolucion;
    $DB->ta['id_via_cobro']=$id_via_cobro;

    $importe = isset($pasar_a_pagado) ? ($total*100) : ($importe_devolucion*100);
    $DB->ta['importe']=$importe;

    $tipo = isset($pasar_a_pagado) ? 1 : 0;
    $DB->ta['tipo']=$tipo; // 1= suma /0=resta

    $DB->ta['procedencia']='reservas_realizadas';
    $DB->ta['id_procedencia']=$id_reserva;
    $DB->ta['fecha']=now();
    $id_movimiento_bancario=$DB->insert();
 
    
    $DB->openTable('reservas_realizadas');
    $DB->edit($id_reserva);

    if(isset($pasar_a_pagado)){
        // pasamos a pagado la reserva, 
        $DB->ta['cobrada']=1;
        $DB->ta['id_via_cobro']=$id_via_cobro;
        $DB->ta['fecha_cobro']=now();
        $accion_manual = 'Cobro_manual';
        $tipo_email = 'pago';
        $total_final = $total;

    }else if(isset($devolucion_manual)){
        // Devolcemos el importe 
        $DB->ta['importe_devolucion']=($importe_devolucion*100);
        $DB->ta['id_via_cobro_devolucion']=$id_via_cobro_devolucion;
        $DB->ta['fecha_devolucion']=now();
        $accion_manual = 'Devolucion_manual';
        $tipo_email = 'devolucion';
        $total_final = $importe_devolucion;
        
    }


    //generar el historico de pago
    $historico = $DB->inner_join('r.historico,r.localizador,r.id_cliente,d.web','reservas_realizadas r inner join clientes c','r.id_cliente=c.id inner join delegaciones d on c.id_delegacion=d.id where r.id='.$id_reserva);

    $nuevo_historico = sumar_historico_xml($historico[f][0]['historico'],$_SESSION['nombre_usuario'],$accion_manual,$id_movimiento_bancario,$total_final);
    $DB->ta['historico']=$nuevo_historico;
    $DB->update();

        // envio del email, desde aqui mismo
    $url_cURL_redsys="https://".$historico[f][0]['web']."/dulceria/functions/ajax.php";


    $post_cURL=array("email_".$tipo_email."_manual"=>"1",
        "enviar_email"=>1,
        "tabla_enviar"=>"",
        "localizador"=>$historico[f][0]['localizador'],
        "total_final"=>$total_final,
        "id_cliente"=>$historico[f][0]['id_cliente']);

    $ch = curl_init();  
    curl_setopt($ch, CURLOPT_URL, $url_cURL_redsys);  
    curl_setopt($ch, CURLOPT_POSTFIELDS,$post_cURL); 
    curl_exec($ch);    
    curl_close($ch);

            // enviamos al cliente el email de pago de la reserva.
    script("noty({text: '".ucwords($tipo_email)." Realizad@, se va a actualizar la pagina', layout: 'topRight', type: 'success'});setTimeout('location.reload()',2000);");
//}else if(isset($pasar_a_pagado) || isset($devolucion_manual)){
}else if(isset($modifica_voucher_reservas)){

    $data_voucher = $DB->select('voucher','reservas_realizadas','id='.$id_reserva);
    $voucher = json_decode(utf8_encode($data_voucher[f][0]['voucher']),true);

    foreach ($_POST as $key => $value) {
        
        if($key!="id_reserva" && $key!="modifica_voucher_reservas"){

            if($value==""){
                // con esto elimino las claves que vienen
                // vacias y consigo eliminar niños y gastos
                unset($voucher[$key]);

            }else{

                $voucher[$key] = $value;

            }
        }
    }

    $order   = array("\r\n", "\n", "\r","\t","\0","\x0B","'"); 
    $voucher=str_replace($order, '',$voucher);

    $new_voucher = json_encode_bbdd($voucher);

    $DB->query("update reservas_realizadas set voucher='".$new_voucher."' where id=".$id_reserva);

    noty();
   

//}else if(isset($modifica_voucher_reservas)){
}else if(isset($insertar_reservas_manuales)){

    // insertarn la reserva 
    // tener en cuenta los campos vacios del json de las habitaciones.<
    // hacer las operaciones de los talones 
    $DB->opentable('reservas_realizadas');
    $DB->ta['id_cliente']=$id_cliente;
    $DB->ta['localizador']=$localizador;
    $DB->ta['proveedor']=$proveedor;
    $DB->ta['entrada']=formatear_fecha($entrada,i,m);
    $DB->ta['salida']=formatear_fecha($salida,i,m);
    $DB->ta['fecha_pago_cliente']=formatear_fecha($fecha_pago_cliente,i,m);
    $DB->ta['estado']=1;
    $DB->ta['bonos_gastados']=$bonos_gastados;
    $DB->ta['neto']=$neto*100;
    $DB->ta['total']=$total*100;
    $DB->ta['id_hoteles']=$id_hoteles;
    $DB->ta['fecha']=now();


    // SE RESTAN LOS BONOS EN CASO DE QUE 
    // SE HAYAN GASTADO
    if($bonos_gastados>0){

        $nuevos_bonos = bonos(r,$bonos_gastados,$id_cliente);
        $DB->query("update clientes set noches = '".$nuevos_bonos."' where id=".$id_cliente);

    }

    // CREAMOS LA ESTRUCTURA
    // METO YA EL HISTORICO, PARA PODER METER EL COBRO, EN CASO DE COBRADA
    $historico= new SimpleXMLElement('<?xml version="1.0"?><historico></historico>');
    // AÑADIMOS 1º REGISTRO
    $registro=$historico->addChild('registro');
    $registro->addChild('usuario',$_SESSION['nombre_usuario']);
    $registro->addChild('tipo','creacion');
    $registro->addChild('fecha',date('d/m/Y'));
    $registro->addChild('hora',date('H:i:s'));

    // PASAMOS A COBRADO Y METEMOS LOS DATOS CORRESPONDIENTES 
    // EN CASO DE SER COBRADA.
    if($id_via_cobro!=0){

        $DB->ta['cobrada']=1;
        $DB->ta['fecha_cobro']=now();
        $DB->ta['id_via_cobro']=$id_via_cobro;
        $DB->ta['operacion_tpv']=$operacion_tpv;
        // AÑADO TAMBIEN EL PASO DEL COBRO AL HISTORICO
        $nuevo_historico = sumar_historico_xml($historico->asXML(),$_SESSION['nombre_usuario'],'Cobro',$id_via_cobro,$total*100);

    
    }else if($id_via_cobro==0){

        $DB->ta['fecha_pago_cliente']=formatear_fecha($fecha_pago_cliente,i,m);
        $DB->ta['cobrada']=0;
        $nuevo_historico = $historico->asXML();

    }

    $DB->ta['historico']=$nuevo_historico;

    $voucher = array();
    foreach ($_POST as $key => $value) {
        
        //if($key!="id_reserva" && $key!="modifica_voucher_reservas"){

            if($value=="" && $key!="observaciones_reserva"){
                // con esto elimino las claves que vienen
                // vacias y consigo eliminar niños y gastos
                unset($voucher[$key]);

            }else if($key=='entrada' || $key=='salida' || $key=='fecha_pago_cliente'){

                $voucher[$key] = formatear_fecha($value,i,m);;

            }else{

                $voucher[$key] = utf8_encode(str_replace("€","&euro;",$value));

            }
        //}
    }

    $new_voucher = json_encode_bbdd($voucher);
    $DB->ta['voucher']=$new_voucher;


    $id_reserva=$DB->insert();

    // añado el movimiento bancario
    // DESPUES DE HABER ISERTADO LA RESERVA PARA EL ID
    if($id_via_cobro!=0){

        $DB->openTable('movimientos_bancarios');
        $DB->ta['id_via_cobro']=$id_via_cobro;
        $DB->ta['importe']=$total*100;
        $DB->ta['tipo']=1; // 1= suma /0=resta
        $DB->ta['procedencia']='reservas_realizadas';
        $DB->ta['id_procedencia']=$id_reserva;
        $DB->ta['fecha']=now();
        $DB->insert();

    }


    noty('Reserva Insertada');
    //location();
//}else if(isset($insertar_reservas_manuales)){
}else if(isset($datos_anuales_booking)){


               $charts= "var morrisCharts = function() {

                    Morris.Bar({
                        element: 'resultados_booking',
                        data: [";

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

                            $totales_booking=0;
                            $neto_booking=0;
                            $beneficio_booking=0;
                            $reservas_booking=0;

                            foreach ($meses as $key => $value) {

                                $DB->query('select sum(neto) as neto_total, sum(total) as total, count(id) as reservas from reservas_realizadas where  entrada BETWEEN "'.$year.'-'.$value.'-01" AND "'.$year.'-'.$value.'-31" and estado=1 and cobrada=1');

                                ${$key}=$DB->fetch();

                                if(${$key}['total']==""){
                                    ${$key}['total']=0;
                                }

                                $beneficio = ${$key}['total']-${$key}['neto_total'];

                                $totales_booking+=${$key}['total'];
                                $neto_booking+=${$key}['neto_total'];
                                $beneficio_booking+=$beneficio;
                                $reservas_booking+=${$key}['reservas'];

                                $charts.= "{ y: '".$key."', a: ".(${$key}['total']/100).", b:".(${$key}['neto_total']/100).", c:".($beneficio/100)." , d : ".${$key}['reservas']."}";
                                if($value!=12){
                                    $charts.= ",";
                                }
                            }
                            
                       $charts.= "],
                        xkey: 'y',
                        ykeys: ['a' , 'b' , 'c' , 'd'],
                        labels: ['Ingresos Totales' , 'Neto' , 'Beneficio' , 'Reservas'],
                        barColors: ['#B64645' , '#33414E' , '#FEA223' , '#3fbae4'] 
                    });

                }();";

        script('$("#resultados_booking").empty();$("#year_booking").html("'.$year.'"); 
                    $("#totales_booking").html("'.($totales_booking/100).'");
            $("#neto_booking").html("'.($neto_booking/100).'");
            $("#beneficio_booking").html("'.($beneficio_booking/100).'");
            $("#reservas_booking").html("'.$reservas_booking.'");'.$charts);


//
//}else if(isset($datos_anuales_booking)){
}else if(isset($filtro_diario_movimientos_bancarios)){


                                $TPV_SLT=0;
                                $TPV_TT=0;
                                $TPV_F=0;
                                $INGRESO=0;
                                $GASTOS=0;

                                $tabla_movimientos="";

                                $DB->query('select mb.id as ids,mb.fecha,mb.importe,mb.tipo,vc.nombre,mb.procedencia,mb.verificado
                                            from movimientos_bancarios mb inner join via_cobro vc on mb.id_via_cobro=vc.id where mb.fecha between "'.formatear_fecha($fecha_movimientos,i,m).' 00:00:00" and "'.formatear_fecha($fecha_movimientos,i,m).' 23:59:59" AND mb.id_via_cobro!=13');
                            
                                if($DB->rows()>0){

                                    while($data = $DB->fetch()){


                                        if($data['tipo']==1){
                                        //echo " al ".${$data['nombre']}." sum ". $data['importe'];
                                            ${$data['nombre']}+=$data['importe'];

                                        }else if($data['tipo']==0){
                                            
                                            ${$data['nombre']}-=$data['importe'];

                                        }

                                        $importe = ($data['importe']/100);
                                        list($date,$hora) = explode(" ",$data['fecha']);
                                        $signo = $data['tipo']==1 ? "+" : "-";
                                        $color_signo = $data['tipo']==1 ? "green" : "red";
                                        $icono_verify = $data["verificado"] == 1 ? "check" : "thumbs-down";
                                        $color_verify = $data["verificado"] == 1 ? "green" : "red";       

                                        $tabla_movimientos.="<tr class='info'>
                                                    <td>";

                                                        if($data["verificado"]==0){

                                                            $tabla_movimientos.="<input type='checkbox' class='form-check-input' id='check_mb_".$data['ids']."' id_movimiento_bancario='".$data['ids']."'>";
                                                        }

                                                     $tabla_movimientos.=formatear_fecha($date,m,i)." - ".$hora."
                                                    </td>
                                                    <td style='color : ".$color_signo.";'>".$signo.number_format($importe,2,".","")."</td>
                                                    <td>".$data["procedencia"]."</td>
                                                    <td>".$data["nombre"]."</td>
                                                    <td style='color : ".$color_verify.";'><i class='fa fa-2x fa-".$icono_verify."' id='icono_".$data['ids']."'></i></td>
                                                    <td>
                                                        <a href='#'
                                                        data-toggle='modal' 
                                                        data-target='#movimientos_bancarios_editar'
                                                        data-backdrop='static' data-keyboard='false'
                                                        name='editar'
                                                        tabla='movimientos_bancarios'
                                                        id='".$data['ids']."'>
                                                        <i class='fa fa-edit fa-2x'></i>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href='#'
                                                            data-toggle='modal' 
                                                            data-target='#borrar_registro'
                                                            data-backdrop='static' data-keyboard='false'
                                                            name='delete' 
                                                            id='".$data['ids']."''>
                                                            <i class='fa fa-times-circle fa-2x' style='color:red;'></i>
                                                        </a>
                                                    </td>
                                                </tr>";
                                        //echo "<br> ".${$data['nombre']}." ".$data['total_importe'];
                                    } // while($data = $DB->fetch()){

                                }else{ // if($DB->rows()>0){
                                    $tabla_movimientos="<h3 class='text-center'>Este d&iacute;a no hay movimientos</h3>";
                                }

                                $DB->query('select c.nombre,
                                            c.identificador,
                                            c.noches,
                                            o.nombre as operadora from clientes c inner join operadoras o on c.id_operadora = o.id where fecha="'.formatear_fecha($fecha_movimientos,i,m).'"');

                                $tabla_clientes_diarios="";

                                while ($data = $DB->fetch()) {

                                    $bonos = json_decode($data['noches'],true);

                                    $tabla_clientes_diarios.='<tr>
                                        <td>'.utf8_encode($data['nombre']).'</td>
                                        <td>'.$data['identificador'].'</td>
                                        <td>'.$data['operadora'].'</td>
                                        <td>'.$bonos['comprados'].'</td>
                                    </tr>';
                                }
                                    
        $clean   = array("\r\n", "\n", "\r","\t","\0","\x0B");
        $tabla_movimientos=str_replace($clean, '',$tabla_movimientos);
        $tabla_clientes_diarios=str_replace($clean, '',$tabla_clientes_diarios);     

                                    
        script('$("#tabla_movimientos_bancarios").html("'.$tabla_movimientos.'");
            $("#tabla_clientes_diarios").html("'.$tabla_clientes_diarios.'"); 
            $("span[name=\'dia_movimientos\']").html("'.$fecha_movimientos.'");
            $("#totales_slt").html("'.($TPV_SLT/100).'");
            $("#totales_tt").html("'.($TPV_TT/100).'");
            $("#totales_fis").html("'.($TPV_F/100).'");
            $("#totales_ing").html("'.($INGRESO/100).'");

             $("a[name=\'delete\']").click(function(e){
                    e.preventDefault();
                    var id = $(this).attr("id");
                    $("input[name=\'id_eliminar\']").val(id);
                });

                $("a[name=\'editar\']").click(function(e){
                    e.preventDefault();
                    var id = $(this).attr("id");
                    $("#id_editar").val(id);
                    var serialize = $(this).attr("data-target")+"_form";
                    $(serialize).call_ajax({
                      url:"functions/ajax.php?tabla_cargar_datos_registros=1",
                      serialice:serialize
                });
                });');

//}else if(isset($filtro_diario_movimientos_bancarios)){
}else if(isset($id_pagar_reserva)){

    # 
    # en caso masivo, agrupo los ids
    # modifico todo, saco el neto pagado
    # genero el movimiento bancario
    #

    if(isset($n_reservas)){

        $query = '';
        $esconder='';
        for ($i=0; $i < $n_reservas; $i++) { 
            

            $query.= $i==0 ? 'id='.$id_pagar_reserva[$i] : ' or id='.$id_pagar_reserva[$i];
            $esconder.='
            $("#check_rsv_'.$id_pagar_reserva[$i].'").parent().parent().hide();';


        }
 
        /// aqui podemos poner el historico de las reservas_realizadas, meter el pago al proveedor
        $DB->query('update reservas_realizadas set pagada_proveedor=1, fecha_pago="'.now().'" where '.$query);

        echo 'update reservas_realizadas set pagada_proveedor=1, fecha_pago="'.now().'" where '.$query;

        $saldo = $DB->select('sum(neto) as neto_total','reservas_realizadas',' '.$query);
        $movimiento = $saldo[f][0]['neto_total'];

        script($esconder);
        noty('reservas masivas cambiadas <br> movimiento bancario por '.($movimiento/100).'&euro;');

    }else{

        $DB->query('update reservas_realizadas set pagada_proveedor=1, fecha_pago="'.now().'" where id='.$id_pagar_reserva);

        $saldo = $DB->select('neto,localizador','reservas_realizadas','id='.$id_pagar_reserva);

        $movimiento = $saldo[f][0]['neto'];
        $concepto = "Loc ".$saldo[f][0]['localizador'];

        script('$("#check_rsv_'.$id_pagar_reserva.'").parent().parent().hide();');

        noty('reserva cambiada <br> movimiento bancario por '.($movimiento/100).'&euro;');

    }


        $DB->openTable('movimientos_bancarios');
        $DB->ta['id_via_cobro']=11;//importe
        $DB->ta['importe']=$movimiento;
        $DB->ta['tipo']=0; // 1= suma /0=resta
        $DB->ta['procedencia']='pagos_mayoristas';
        $DB->ta['concepto']=$concepto;
        $DB->ta['verificado']=1;
        $DB->ta['fecha']=now();
        $DB->insert();

        // RESTAMOS DIRECTAMENTE EL SALDO DE LA EMPRESA
        $DB->opentable('saldo_empresa');
        $DB->ta['movimiento']= ($movimiento/100);

        $DB->ta['concepto']=$concepto; // estoy hay que sacarlo en condiciones

        $resultado = mueve_saldo(($movimiento/100),0);
        $DB->ta['anterior']=$resultado['saldo_anterior'];
        $DB->ta['resultado']=$resultado['nuevo_saldo'];
        $DB->ta['tipo']=0;
        $DB->ta['creacion']=now();
        $DB->ta['ids_movimientos']=json_encode($id_pagar_reserva);
        $DB->insert(); 

        

//}else if(isset($id_pagar_reserva)){
}else if(isset($filtro_reservas_pendientes_pago)){

    $filtro_reservas_pendiente = $proveedor!="0" ? $proveedor :" Todos ";
    $filtro_reservas_pendiente .= " (".$desde." - ".$hasta.")";
    $proveedores = $proveedor!="0" ? 'and proveedor like "'.$proveedor.'" ' : ''; 

    $DB->query('select 
            r.id,
            r.estado,
            r.localizador,
            r.voucher,
            r.cobrada,
            r.proveedor,
            r.entrada,
            r.salida,
            r.total,
            r.neto, 
            c.nombre,
            c.identificador
            from reservas_realizadas r 
            inner join clientes c on r.id_cliente=c.id 
            where r.estado=1 and pagada_proveedor=0 and cobrada=1
            and entrada between "'.formatear_fecha($desde,i,m).'" and "'.formatear_fecha($hasta,i,m).'" 
            '.$proveedores.'
            order by r.entrada ASC');

    if($DB->rows()>0){


            $tabla_reservas_pagos_mayoristas='';
            $reservas=0;
            $neto=0;

            // separamos por prov
            $versys=0;
            $beds=0;
            $solole=0;
            $serhs=0;
            $veturis=0;
            $europlayas=0;
            $externo=0;

            while ($data = $DB->fetch()) {

            $reservas++;
            $neto+=$data['neto'];

            if($data['proveedor']=="id_serhs"){

                $serhs+=$data['neto'];

            }else if($data['proveedor']=="id_versys"){

                $versys+=$data['neto'];
                
            }else if($data['proveedor']=="id_bedsonline"){

                $beds+=$data['neto'];
                
            }else if($data['proveedor']=="id_veturis"){

                $veturis+=$data['neto'];

            }else if($data['proveedor']=="id_europlayas"){

                $europlayas+=$data['neto'];
                
            }else if($data['proveedor']=="id_solole"){

                $solole+=$data['neto'];
                
            }else if($data['proveedor']=="id_externo"){

                $externo+=$data['neto'];
                                            
            }

 

            $tabla_reservas_pagos_mayoristas.='<tr class=\'info\'>
                <td><input type=\'checkbox\' class=\'form-check-input\' id=\'check_rsv_'.$data['id'].'\' id_reserva=\''.$data['id'].'\'></td>
                <td>'.utf8_encode($data['nombre']).'</td>
                <td>'.formatear_fecha($data['entrada'],m,i).'</td>
                <td>'.formatear_fecha($data['salida'],m,i).'</td>
                <td>'.$data['localizador'].'</td>
                <td>'.($data['neto']/100).'</td>
                <td>'.$data['proveedor'].'</td>
                <td>
                    <button type=\'button\' class=\'btn btn-xs btn-info pagar_reserva\'
                                id_reserva=\''.$data['id'].'\'>Pagar</button>
                </td>
                </tr>';

        } //  while ($data = $DB->fetch()) {

        $clean   = array("\r\n", "\n", "\r","\t","\0","\x0B");
        $tabla_reservas_pagos_mayoristas=str_replace($clean, '',$tabla_reservas_pagos_mayoristas);   

        script('$("#tabla_reservas_pagos_mayoristas").html("'.$tabla_reservas_pagos_mayoristas.'"); 
        $("#filtro_reservas_pendiente").html("'.$filtro_reservas_pendiente.'");
        $("#externo_total").html("'.($externo/100).'");
        $("#solole_total").html("'.($solole/100).'");
        $("#europlayas_total").html("'.($europlayas/100).'");
        $("#veturis_total").html("'.($veturis/100).'");
        $("#serhs_total").html("'.($serhs/100).'");
        $("#w2m_total").html("'.($versys/100).'");
        $("#beds_total").html("'.($beds/100).'");
                    // pasar a pagado las reservas
            $(".pagar_reserva").click(function(){

                var id_reserva = $(this).attr("id_reserva");
                //escondemos el contenedor 
                $(this).parent().parent().hide();

                $.post("functions/ajax.php" ,
                    {
                        id_pagar_reserva : id_reserva
                    } , 
                    function(data){
                        $("#respuesta_ajax_js").html(data);
                    }); 
            });');

    }else{
       script('$("#tabla_reservas_pagos_mayoristas").html("<h3>No hay ninguna reserva con este filtro</h3>");'); 
    }

//}else if(isset($filtro_reservas_pendientes_pago)){
}else if(isset($filtro_gastos_personales)){


    $DB->query('select importe, concepto, fecha
        from movimientos_bancarios where id_via_cobro=13 
        and fecha between "'.formatear_fecha($desde,i,m).' 00:00:00" and "'.formatear_fecha($hasta,i,m).' 23:59:59" order by id DESC');

    $tabla_gastos_personales="";
    if ($DB->rows()==0) {

        $tabla_gastos_personales="No hay ning&uacute;n registro";
                                                    
    }else{

        
        while ($data = $DB->fetch()) {

            $tabla_gastos_personales.='<tr>
                <td>'.$data['fecha'].'</td>
                <td>'.utf8_encode($data['concepto']).'</td>
                <td style=\'color:red;\'>-'.($data['importe']/100).'</td>
            </tr>';

        } // while ($data = $DB->fetch()) {

    }

    // vamos a hacer una agrupacion de conceptos para poder
    // tener unos datos reales.
    $DB->query('select concepto from movimientos_bancarios where id_via_cobro=13 
        and fecha between "'.formatear_fecha($desde,i,m).' 00:00:00" and "'.formatear_fecha($hasta,i,m).' 23:59:59" group by concepto');

    $tabla_gastos_personales_agrupados="";
    if($DB->rows()==0) {

        $tabla_gastos_personales_agrupados="No hay registros agrupados";
                                                    
    }else{

        $consumo = array();
        $gastos_personales_agrupados=0;
        $c=0;
        while ($data = $DB->fetch()) {
            
            $consumo[$c]=$DB2->select('sum(importe) as importe_agrupado','movimientos_bancarios',' id_via_cobro=13 and concepto like "'.$data['concepto'].'"');

            $tabla_gastos_personales_agrupados.='<tr>
                <td>'.utf8_encode($data['concepto']).'</td>
                <td style=\'color:red;\'>-'.($consumo[$c][f][0]['importe_agrupado']/100).'</td>
            </tr>';

            $gastos_personales_agrupados+=($consumo[$c][f][0]['importe_agrupado']/100);

            $c++;
        }

    }

        $clean   = array("\r\n", "\n", "\r","\t","\0","\x0B");
        $tabla_gastos_personales=str_replace($clean, '',$tabla_gastos_personales); 
        $tabla_gastos_personales_agrupados=str_replace($clean, '',$tabla_gastos_personales_agrupados);   


        script('$("#tabla_gastos_personales").html("'.$tabla_gastos_personales.'");
            $("#tabla_gastos_personales_agrupados").html("'.$tabla_gastos_personales_agrupados.'");
            $("#gastos_personales_agrupados").html("'.$gastos_personales_agrupados.'");');
//

//}else if(isset($filtro_gastos_personales)){
}else if(isset($verificar_movimientos)){

    
    ####
    #
    # vamos a traernos todos los movimientos en 1 sola consulta
    # sacamos los importes por separado y los sgrupamos por via de cobro
    # metemos el saldo de la empresa, mas los movimientos que le pertenecen
    # pasamos todos los movimientos a verificado 1
    #
    #
    #

    $query = '';
    $checkear='';
    for ($i=0; $i < $n_movimientos; $i++) { 
            
        $query.= $i==0 ? 'mb.id='.$verificar_movimientos[$i] : ' or mb.id='.$verificar_movimientos[$i];

        // para cambiar los iconos a chekeado
        $checkear.='
        $("#icono_'.$verificar_movimientos[$i].'").addClass("fa-check");
        $("#icono_'.$verificar_movimientos[$i].'").removeClass("fa-thumbs-down");
        $("#icono_'.$verificar_movimientos[$i].'").css("color","green");';

    }

    $TPV_SLT=0;
    $TPV_TT=0;
    $TPV_F=0;
    $INGRESO=0;
    $json_TPV_SLT=array();
    $json_TPV_TT=array();
    $json_TPV_F=array();
    $json_INGRESO=array();

    $DB->query('select mb.id as ids,mb.fecha,mb.importe,mb.tipo,vc.nombre,mb.procedencia,mb.verificado
        from movimientos_bancarios mb inner join via_cobro vc on mb.id_via_cobro=vc.id where '.$query);

    while($data = $DB->fetch()){

        // encerramos los id
        ${"json_".$data['nombre']}[]=$data['ids'];

        if($data['tipo']==1){
                                        
            ${$data['nombre']}+=$data['importe'];

        }else if($data['tipo']==0){
                                        
            ${$data['nombre']}-=$data['importe'];

        }

    } // while($data = $DB->fetch()){

    $vias = array("TPV_SLT","TPV_TT","TPV_F","INGRESO");

    foreach ($vias as $value) {

        // EL CONTENIDO QUE VAMOS A GUARDAR DESDE 
        // DESDE LOS MOVIMIENTOS BANCARIOS, LO TENEMOS 
        // QUE AGRUPAR AQUI, YA QUE TODOS NO PERTENECEN AL MISMO. 
        
        // echo "<br>".$value." ".${$value};
        $DB->opentable('saldo_empresa');
        $DB->ta['movimiento']=(${$value}/100);
        $DB->ta['concepto']=$value;

        if(${$value}>0){

            $resultado = mueve_saldo((${$value}/100),1);
            $DB->ta['anterior']=$resultado['saldo_anterior'];
            $DB->ta['resultado']=$resultado['nuevo_saldo'];
            $DB->ta['tipo']=1;
            $DB->ta['creacion']=now();
            $DB->ta['ids_movimientos']=json_encode(${"json_".$value});
            $DB->insert();  
            

        }else if(${$value}<0){

            $resultado = mueve_saldo((${$value}/100),1);
            $DB->ta['anterior']=$resultado['saldo_anterior'];
            $DB->ta['resultado']=$resultado['nuevo_saldo'];
            $DB->ta['tipo']=0;
            $DB->ta['creacion']=now();
            $DB->ta['ids_movimientos']=json_encode(${"json_".$value});
            $DB->insert(); 

        }

    } // foreach ($vias as $value) {

    $DB->query('update movimientos_bancarios mb set verificado=1 where '.$query);


    noty("Movimiento Guardado Correctamente");
    script($checkear);

//}else if(isset($verificar_movimientos)){
}else if(isset($conceptos_cobro)){

        $output= array();
        $term = utf8_decode($term);

        $DB->query('select * from conceptos_cobro where concepto like "%'.$term.'%"');


        if($DB->rows()==0){

                $label = utf8_encode("Este concepto no existe");
                $output[] = array(
                                "label" => $label,
                                "value" => $label,
                                "desc" => "",
                                "category" => "Conceptos de Cobro"
                                
                            );

        }else{ // if($DB->rows()==0){


            while($row=$DB->fetch()){
                  

                    $label = $row['concepto'];
                    $output[] = array(
                        "label" => $label,
                        "value" => $label,
                        "category" => "Conceptos de Cobro"
                        );

            } //  while($row=$DB->fetch()){
        } // if($DB->rows()==0){ .. else


    print my_json_encode($output);





//}else if(isset($conceptos_cobro)){
}else if(isset($eliminar_hoteles)){

    $table = isset($provincias) ? 'provincias' : (isset($poblaciones) ? 'poblaciones' : 'segmentos');
    $lugar = isset($provincias) ? 'provincia' : (isset($poblaciones) ? 'poblacion' : 'segmento');

    $DB->query('delete from _hoteles_'.$table.' where id_soltury='.$id_soltury.' and id_'.$lugar.'_solt='.${'id_'.$lugar.'_solt'});

   
    noty('Hotel Borrado de la Poblacion o Segmento');
    location(2);

//}else if(isset($eliminar_hoteles)){
}else if(isset($incluir_hoteles)){

    $data_hotel = $DB->select('id_versys,
            id_bedsonline,
            id_solole,
            id_serhs,
            id_veturis,
            id_europlayas','_hoteles','id='.$id_soltury);

    $hot = $data_hotel[f][0];


    $table = $id_segmento>0 ? 'segmentos' : ($id_poblacion>0 ? 'poblaciones' : 'provincias');
    $lugar = $id_segmento>0  ? 'segmento' : ($id_poblacion>0 ? 'poblacion' : 'provincia');;
    $id_lugar = $id_segmento>0  ? $id_segmento : ($id_poblacion>0 ? $id_poblacion : $id_provincia);


    $DB->query('insert into _hoteles_'.$table.' 
                (id_soltury,
                id_versys,
                id_bedsonline,
                id_solole,
                id_serhs,
                id_veturis,
                id_europlayas,
                id_'.$lugar.'_solt) 
                values
                ('.$id_soltury.',
                "'.$hot['id_versys'].'",
                "'.$hot['id_bedsonline'].'",
                "'.$hot['id_solole'].'",
                "'.$hot['id_serhs'].'",
                "'.$hot['id_veturis'].'",
                "'.$hot['id_europlayas'].'",
                '.$id_lugar.')');

    $n_hoteles = $DB->select('count(id_'.$lugar.'_solt) as n_hoteles','_hoteles_'.$table,'id_'.$lugar.'_solt='.$id_lugar);

    $DB->query('update _'.$table.' set n_hoteles='.$n_hoteles[f][0]['n_hoteles'].' where id='.$id_lugar);

    noty();
    location(2);

//}else if(isset($incluir_hoteles)){
}else if(isset($insertar_segmento)){


        ## tenemos que añadir, que si el segmento que estamos incluyendo, 
        ## pertenece a una provincia o poblacion, ponerle como marca en su 
        ## respectiva procedencia, para que luego a la hora de la busqueda, 
        ## se coloque debajo de la misma. 

        $DB->query('insert into _segmentos 
            (nombre,id_poblacion_solt,id_provincia_solt,destino,n_hoteles) 
            values 
            ("'.$nombre_segmento.'",'.$id_poblacion_solt.','.$id_provincia_solt.','.$destino.',1)');

        if($id_poblacion_solt>0 && $id_provincia_solt>0){

            $DB->query('update _poblaciones set segmentos=1 where id='.$id_poblacion_solt);
            
            noty('Segmento de Poblacion Insertado');

        }else if($id_poblacion_solt==0 && $id_provincia_solt>0){

            $DB->query('update _provincias set segmentos=1 where id='.$id_provincia_solt);

            noty('Segmento de Provincia Insertado');


        }
        

//}else if(isset($insertar_segmento)){
}else if(isset($id_add_hotels)){  


    for($h=0;$h<$n_add_hotels;$h++){


        $data_hotel[$h] = $DB->select('id_versys,
                id_bedsonline,
                id_solole,
                id_serhs,
                id_veturis,
                id_europlayas','_hoteles','id='.$id_add_hotels[$h]);

        $hot[$h] = $data_hotel[$h][f][0];

        $DB->query('insert into _hoteles_segmentos 
                    (id_soltury,
                    id_versys,
                    id_bedsonline,
                    id_solole,
                    id_serhs,
                    id_veturis,
                    id_europlayas,
                    id_segmento_solt) 
                    values
                    ('.$id_add_hotels[$h].',
                    "'.$hot[$h]['id_versys'].'",
                    "'.$hot[$h]['id_bedsonline'].'",
                    "'.$hot[$h]['id_solole'].'",
                    "'.$hot[$h]['id_serhs'].'",
                    "'.$hot[$h]['id_veturis'].'",
                    "'.$hot[$h]['id_europlayas'].'",
                    '.$segmento.')');



    } //  for($h=0;$h<$n_add_hotels;$h++){

    $n_hoteles = $DB->select('count(id_segmento_solt) as n_hoteles','_hoteles_segmentos','id_segmento_solt='.$segmento);

    $DB->query('update _segmentos set n_hoteles='.$n_hoteles[f][0]['n_hoteles'].' where id='.$segmento);

    noty();
    location(2);

//}else if(isset($id_add_hotels)){ 
}else if(isset($descargar_info_reservas)){ 

    $C = new control($_POST,$proveedor);

    if($proveedor=="id_europlayas") $C->abrirSession();

    $resultado=$C->DetalleReserva();

    if(!isset($resultado['localizador'])){

        noty('Esta reserva no esta en este proveedor','warning');

    }else{


        $script='';

        $find = array(
                    'gastos',
                    'importe_',
                    'fecha_',
                    'habitaciones',
                    'adultos_',
                    'ninos_',
                    'edad_',
                    'SecurityCode'
                );

        $order   = array("\r\n", "\n", "\r","\t","\0","\x0B","'"); 

        foreach ($resultado as $key => $value) {
            
            if(!in_array($value,$find)){

                    if($proveedor!="id_veturis" && $proveedor!="id_bedsonline" && $proveedor!="id_serhs") $value = utf8_encode($value);

                    if($key=='observaciones_reserva' || $key=='observaciones_proveedor'){

                        $value = str_replace($order, '',strip_tags($value));

                    }

                    $script.='$(\'[name="'.$key.'"]\').val(\''.$value.'\');';

            }
            
        }

        $html = "N&ordm; Habitaciones : ".$resultado['habitaciones']."<br>";
        $html .= '<input type="hidden" name="habitaciones" value="'.$resultado['habitaciones'].'">';


        ## AQUI VAMOS A METER LOS DATOS ESPECIALES  
        ## NECESARIOS PARA LAS CANCELACIONES
        if($proveedor=="id_veturis"){

            // datos de cancelaxion de veturis
            $html .= '<input type="hidden" name="SecurityCode" value="'.$resultado['SecurityCode'].'">';
            $html .= '<input type="hidden" name="BookingID" value="'.$resultado['BookingID'].'">';

        }

        if($proveedor=="id_serhs"){

            // datos de cancelaxion de veturis
            $html .= '<input type="hidden" name="comunicacion_tpv_reservas" value="'.$resultado['comunicacion_tpv_reservas'].'">';
            $html .= '<input type="hidden" name="codigo" value="'.$resultado['codigo'].'">';

        }

        for ($h=1; $h <= $resultado['habitaciones']; $h++) { 

            $html.='<br>Habitaci&oacute;n '.$h.'&ordm; Adultos '.$resultado['adultos_'.$h].'/ Ni&ntilde;os '.$resultado['ninos_'.$h];

            $html.='<input type="hidden" name="adultos_'.$h.'" value="'.$resultado['adultos_'.$h].'">';
            $html.='<input type="hidden" name="ninos_'.$h.'" value="'.$resultado['ninos_'.$h].'">'; 

            if($resultado['ninos_'.$h]>0){

                $html.=' - ';
                for ($e=1; $e <=$resultado['ninos_'.$h] ; $e++) { 
                    
                    $html.=' '.$resultado['edad_'.$h.'_'.$e].' a&ntilde;os ';
                    $html.='<input type="hidden" name="edad_'.$h.'_'.$e.'" value="'.$resultado['edad_'.$h.'_'.$e].'">'; 

                }
            }
            
            
        }

        $html .= '<br><br>Fechas de gastos : '.$resultado['gastos'].'<br>';
        $html .= '<input type="hidden" name="gastos" value="'.$resultado['gastos'].'">';


        for ($g=0; $g < $resultado['gastos']; $g++) { 

            $html.='<br>Fecha '.($g+1).'&ordm; desde '.$resultado['fecha_'.$g].' un importe de  '.$resultado['importe_'.$g].'&euro;';
            $html.='<input type="hidden" name="importe_'.$g.'" value="'.$resultado['importe_'.$g].'">';
            $html.='<input type="hidden" name="fecha_'.$g.'" value="'.$resultado['fecha_'.$g].'">';
            
        }

        noty('Datos Descargados');
        script($script."$('#distribuciones_descargadas').html('".$html."')");
    }

//}else if(isset($descargar_info_reservas)){       
}else if(isset($dato_recupera_pass)){

// EN EL REUSO DE TT HAY QUE CAMBIAR AQUI LA DELEGACION

    $id_consulta = $id_delegacion_recupera_pass==7 ? '(id_delegacion = 7 or id_delegacion=82)' : 'id_delegacion ='.$id_delegacion_recupera_pass;

 
    $consulta_recupera_pass_1=$DB->select('id,nombre','clientes','(nombre like "%'.$dato_recupera_pass.'%" or email like "%'.$dato_recupera_pass.'%" or identificador like "'.$dato_recupera_pass.'" or telefono like "%'.$dato_recupera_pass.'%") and '.$id_consulta);
    
    if($consulta_recupera_pass_1[r]>1){

        script("$('#contenido_recupera_pass_1').html('El dato que ha introducido, coincide con mas de 1 cliente, pruebe con otro dato diferente:');");
       
    }else if($consulta_recupera_pass_1[r]==0){

        script("$('#contenido_recupera_pass_1').html('Lo sentimos, pero el dato que nos indica no coincide con ning&uacute;n cliente, pruebe con otro dato diferente:')");

    }else if($consulta_recupera_pass_1[r]==1){

        $id_cliente=$consulta_recupera_pass_1[f][0]['id'];

        $datos=$DB->inner_join('c.nombre as cliente,
                d.nombre as delegacion,
                d.web,
                c.id_delegacion,
                c.identificador,
                c.comentario,
                d.email as remitente,
                c.email as destinatario','clientes as c inner join delegaciones as d',
                'c.id_delegacion = d.id and c.id='.$id_cliente,f);

            if($datos[f][0]['destinatario']!=''){
                // el cliente si tiene email en la bbdd
                
                $mail = new phpmailer($datos[f][0]['remitente']);
                $mail->setFrom($datos[f][0]['remitente'],$datos[f][0]['delegacion']);

                // new solution here

                $j_destinatarios = json_decode($datos[f][0]['destinatario']);

                $E=1;
                foreach ($j_destinatarios as $value) {
                   
                    if($E==1){
                        $mail->AddAddress($value);
                    }else if($E>1){
                        $mail->AddCC($value);
                    }
                    
                    $E++;
                }

                // DATOS QUE VAMOS A PASAR
                // POR PARAMETROS A body_email
                $datos_cliente=array("id"=>$id_cliente,
                "nombre"=>$datos[f][0]['cliente'],
                "identificador"=>$datos[f][0]['identificador'],
                "comentario"=>$datos[f][0]['comentario']);


                // SALUDO Y BODY EMAIL, SEGUN TABLA

                $mail->Subject="Saludos de " .$datos[f][0]['delegacion']; 
                $mail->Body=body_email(c,$datos_cliente,$datos[f][0]['id_delegacion']);

                $mail->send();

                $email_estrellas = texto_estrellas($j_destinatarios[0]);

                script("$('#contenido_envia_pass_index').html('Hola ".utf8_encode($consulta_recupera_pass_1[f][0]['nombre']).", <br>Te acabamos de enviar tu usuario y contrase&ntilde;a al E-mail:<br><br> ".$email_estrellas." <br><br> Si no tienes acceso a este E-mail, puedes llamarnos al 955090269 dentro de nuestro horario comercial. Recibe un cordial saludo.');");

            // eof if($datos[f][0]['destinatario']!=''){
            }else if($datos[f][0]['destinatario']==''){
                // el cliente no tiene email en al bbdd
                script("$('#contenido_envia_pass_index').html('Hola ".utf8_encode($consulta_recupera_pass_1[f][0]['nombre'])." hemos detectado que no tienes ning&uacute;n  E-mail en nuestra base de datos. <br> Para recibir la contrase&ntilde;a introduce un E-mail abajo y pulsa Enviar:');
                    $('#enviar_pass_paso_2').removeClass('hide');$('#email_envia_pass_index').removeClass('hide');");                

            }
            
        script("$('#body_recupera_pass_index').addClass('hide');
               $('#body_envia_pass_index').removeClass('hide');
               $('#recuperar_pass_paso_1').addClass('hide');
               $('#id_cliente_envia_pass_index').val('".$consulta_recupera_pass_1[f][0]['id']."');");

    // eof }else if($consulta_recupera_pass_1[r]==1){ 
    }

// eof }else if(isset($dato_recupera_pass)){
}else if(isset($email_envia_pass_index)){

        $id_cliente=$id_cliente_envia_pass_index;

        $DB->opentable('clientes');
        $DB->edit($id_cliente);
        $DB->ta['email']=json_email($email_envia_pass_index);
        $DB->update();

        $datos=$DB->inner_join('c.nombre as cliente,
                d.nombre as delegacion,
                d.web,
                c.id_delegacion,
                c.identificador,
                c.comentario,
                d.email as remitente,
                c.email as destinatario','clientes as c inner join delegaciones as d',
                'c.id_delegacion = d.id and c.id='.$id_cliente,f);

                // el cliente si tiene email en la bbdd
                

                $mail = new phpmailer($datos[f][0]['remitente']);
                $mail->setFrom($datos[f][0]['remitente'],$datos[f][0]['delegacion']);
                $mail->AddAddress(strtolower($email_envia_pass_index));

                // DATOS QUE VAMOS A PASAR
                // POR PARAMETROS A body_email
                $datos_cliente=array("id"=>$id_cliente,
                "nombre"=>$datos[f][0]['cliente'],
                "identificador"=>$datos[f][0]['identificador'],
                "comentario"=>$datos[f][0]['comentario']);


                // SALUDO Y BODY EMAIL, SEGUN TABLA

                $mail->Subject="Saludos de " .$datos[f][0]['delegacion']; 
                $mail->Body=body_email(c,$datos_cliente,$datos[f][0]['id_delegacion']);
                $mail->send();

                script("$('#modal_recupera_pass').modal('hide');");




// eof }else if(isset($email_envia_pass_index)){    
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
}else if(isset($buscador_info_hoteles_zona_publica)){


    if($hotel_info!=0){

        $DB->query('select id, nombre, id_estrellas, poblacion, provincia, descripcion, fotos from _hoteles where id='.$hotel_info);


    }else if($segmento_info!=0){

        $DB->query('select h.id, h.nombre, h.id_estrellas, h.poblacion, h.provincia, h.descripcion, h.fotos from _hoteles h inner join _hoteles_segmentos hs on h.id = hs.id_soltury where hs.id_segmento_solt='.$segmento_info.' and fotos!="[]" and descripcion!="" and descripcion!="null"');

    }else if($poblacion_info!=0){

        $DB->query('select id, nombre, id_estrellas, poblacion, provincia, descripcion, fotos from _hoteles where id_poblacion_solt='.$poblacion_info.' and fotos!="[]" and descripcion!="" and descripcion!="null"');
        
    }else if($provincia_info!=0){

        $DB->query('select id, nombre, id_estrellas, poblacion, provincia, descripcion, fotos from _hoteles where id_provincia_solt='.$provincia_info.' and fotos!="[]" and descripcion!="" and descripcion!="null"');

        
    }

        $contenido = '<div class="mg-sec-title" id="comon_here">
                        <h2>Resultados</h2>
                        <p>De Tu B&uacute;squeda</p>
                    </div>';

         // $contenido = '';

    $h=0;
    while($data_hotel=$DB->fetch()){

        if($data_hotel['id_estrellas']==0){
            $data_hotel['id_estrellas']=1;
        }

        $arr_foto = json_decode($data_hotel['fotos']);

        $n_fotos = count($arr_foto);
        if($h==0){
            echo "
                <script>
                    $('#row_info_hoteles').removeClass('hide');
                    $('#modal_espera_hoteles_publica').modal('hide');
                    $('html,body').animate({scrollTop: $('#volver_arriba').offset().top}, 2000);
                </script>";
        }

        $contenido .= '<div class="col-md-12" style="margin-bottom:30px;">
                         <hr>
                        <div class="mg-avl-room" itemscope itemtype="http://schema.org/Hotel">
                            <div class="row">
                                <div class="col-sm-5" itemscope itemtype="http://schema.org/ImageObject">

                                    <img src="'.$arr_foto[0].'" onerror="this.src=\'/new_soltury/images/sustitucion.png\'" alt="" class="img-responsive img-center" style="height:200px;">


                                </div>
                                <div class="col-sm-7">
                                    <h4 class="mg-avl-room-title" itemprop="name">';
                                
                                if(isset($creador_segmentos)){
                                    $contenido .='<input type="checkbox" class="form-check-input check_htl_" id_hotel="'.$data_hotel['id'].'"> - ';
                                }

                                $contenido .= strtoupper(tildes_min_may($data_hotel['nombre'],h)).' ('.utf8_encode($data_hotel['poblacion']).') <strong class="pull-right">'.$data_hotel['id_estrellas'].'<sup>*</sup></strong></h4>

                                    <div class="descripcion_hoteles_listados">       
                                        '.utf8_encode(html_entity_decode($data_hotel['descripcion'])).' 
                                    </div>

                                   <!-- <div class="row mg-room-fecilities">
                                        <div class="col-sm-6">
                                            <ul>
                                                <li><i class="fp-ht-bed"></i> 2 King Beds</li>
                                                <li><i class="fp-ht-food"></i> Breakfast</li>
                                                <li><i class="fa fa-sun-o"></i> Air conditioning</li>
                                            </ul>
                                        </div>
                                        <div class="col-sm-6">
                                            <ul>
                                                <li><i class="fp-ht-dumbbell"></i> GYM fecility</li>
                                                <li><i class="fp-ht-tv"></i> TV LCD</li>
                                                <li><i class="fp-ht-computer"></i> Wi-fi service</li>
                                            </ul>
                                        </div>
                                    </div>-->
                                    <hr>
                                    <button id_hotel="'.$data_hotel['id'].'"
                                     class="btn btn-info pull-right info_hotel_modal"
                                     data-toggle="modal" data-target="#informacion_hotel_modal">
                                     Ver Hotel <i class="fa fa-angle-double-right"></i>
                                     </button>
                                </div>
                            </div>
                        </div>
                       
                    </div><!-- HOTEL COMPLETO -->';

        $h++;
    }
// comon_here
    echo $contenido;


// eof }else if(isset($buscador_info_hoteles_zona_publica)){
}else if(isset($cargar_modal_info_hotel)){

        $DB->query('select id, 
                        nombre, 
                        direccion, 
                        cp, 
                        id_estrellas, 
                        poblacion, 
                        provincia, 
                        descripcion,
                        fotos
                        from _hoteles where id='.$id_hotel);

        $data_hotel = $DB->fetch();

        if($data_hotel['id_estrellas']==0){
            $data_hotel['id_estrellas']=1;
        }

        $arr_foto = json_decode($data_hotel['fotos']);

        $n_fotos = count($arr_foto);

        echo '<div class="row">
                <div class="col-xs-12 text-center" style="margin:auto;" >
                '.strtoupper(tildes_min_may($data_hotel['nombre'],h)).' '.$data_hotel['id_estrellas'].'<sup>*</sup>
                </div>
                <div class="col-xs-12" style="padding-left:20%;">
                    <div class="col-xs-10">
                        <div id="owl-demo-info-hoteles" class="owl-carousel owl-theme" style="margin:auto;">';

                            for($x=0;$x<$n_fotos;$x++){
                               echo '<div class="item" style="border:0;">
                               <img src="'.$arr_foto[$x].'" class="img-responsive img-center" alt="">
                               </div>';
                            }
            
                echo '  </div>
                    <hr>
                
                    </div>
                </div>';

                if(isset($_SESSION['Authenticated'])){

                    echo '<div class="col-xs-12">
                        <div class="col-xs-12 text-center"><u>Descripci&oacute;n</u></div> <br /><br />
                        '.utf8_encode($data_hotel['descripcion']).'
                        <hr>
                        </div>';
                }

        echo    '<div class="col-xs-12">
                        <p class="text-center">*La ubicaci&oacute;n Puede ser Incorrecta</p>
                        <iframe
                          width="100%"
                          height="250"
                          frameborder="0" style="border:0"
                          src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDdwoRvTCjfJr774seQjG3kvkHVb3TLXDQ
                            &q='.$data_hotel['nombre'].','.utf8_encode($data_hotel['poblacion']).'">
                        </iframe>
                    </div>
            </div>
            <script>
                var owl = $("#owl-demo-info-hoteles");
                owl.owlCarousel({
                
                margin : 5,
                loop: true,
                nav : true,
                items : 1,
                center : true,
                autoHeight : true});
            </script>';

// else if(isset($cargar_modal_info_hotel)){
}else if(isset($formulario_contacto_home) || isset($formulario_contacto_modal) || isset($formulario_colaboracion_modal)){

                // el cliente si tiene email en la bbdd

                $DB->query('select 
                nombre,
                email
                from delegaciones where id='.$_SESSION['id_web']);

                $datos=$DB->fetch();

                $mail = new phpmailer($datos['email']);
                $mail->setFrom($datos['email'],$datos['nombre']);
                $mail->AddAddress(strtolower($email_contacto));

                $empresa = isset($empresa_contacto) ? " (".$empresa_contacto.")" : "";

                $texto_email='Hola '.ucwords($nombre_contacto).$empresa.',
                <br><br>
                Hemos recibido correctamente tu contacto y te contestaremos en un m&aacute;ximo de 24h laborables.
                <br><br>
                Gracias por confiar en nosotros, un saludo.';

                // SALUDO Y BODY EMAIL, SEGUN TABLA


                $mail->Subject='Contacto Recibido (' .$datos['nombre'].')'; 
                $mail->Body=body_email(t,'',$_SESSION['id_web'],$texto_email);
                $mail->send();


                $mail2 = new phpmailer($datos['email']);
                $mail2->setFrom($datos['email'],$datos['nombre']);
                $mail2->AddAddress(strtolower($datos['email']));
                $mail2->AddReplyTo(strtolower($email_contacto));
                $mail2->Subject=$asunto_contacto; 

                $email_administracion= 'Nos ha contactado '.ucwords($nombre_contacto).$empresa;

                if(isset($id_cliente_contacto_zona_privada)){

                    $email_administracion.=' ('.$id_cliente_contacto_zona_privada.') desde la zona privada.

                    '; 

                }else{

                    $email_administracion.=' desde la zona pública.

                    '; 
                }

                if(isset($es_pyme)){

                    $email_administracion.='Estos son sus datos de Contacto:  
                    Tel&eacute;fono : '.$telefono_contacto.'
                    Email : '.$email_contacto;
                }


                $email_administracion.='
                Y nos solicita: 

                '. $cuerpo_contacto;
                 


                $mail2->Body=body_email(t,'',$_SESSION['id_web'],nl2br(htmlentities($email_administracion)));
                $mail2->send();

                if(isset($formulario_contacto_home)){

                    alert('Mensaje Enviado');
                    script("$('#mensaje_email_enviado').show();
                            $('#formulario_contacto_home .input_form_white').each(function(indice, elemento){
                                    $(elemento).val('');
                                });
                        ");

                }else if(isset($es_pyme)){

                    script("$('#modal_contacto_pymes').modal('hide');");
                    noty('Mensaje Enviado');

                }else if(isset($formulario_contacto_modal)){

                    script("$('#modal_contacto').modal('hide');");
                    noty('Mensaje Enviado');

                }else if(isset($formulario_colaboracion_modal)){

                    script("$('#colaboracion_modal').modal('hide');");
                    noty('Mensaje Enviado');

                }
                


//  
// }else if(isset($formulario_contacto_home)){
}else if(isset($identificador_acceso_zona_privada)){


    $datos_login_inicio=$DB->select('id,password','clientes','identificador like "'.strtoupper($identificador_acceso_zona_privada).'"');


    if(!password_verify($password_acceso_zona_privada,$datos_login_inicio[f][0]['password'])){

        alert('El Usuario o la contraseña es incorrecta');

    }else if(password_verify($password_acceso_zona_privada,$datos_login_inicio[f][0]['password'])){

        $_SESSION['permiso_zona_privada']=1;

        // sacamos $ y hacemos la session completa 
        $DB->query('select nombre,id,identificador,id_delegacion,email,telefono 
               from clientes where identificador like "'.$identificador_acceso_zona_privada.'"');

        $fetch=$DB->fetch(MYSQLI_ASSOC);
        $decode_email = json_decode($fetch['email']);
        $fetch['email'] = $decode_email[0];

        foreach ($fetch as $key => $value) {
        
            $_SESSION[$key.'_zona_privada']=$value;
        }

        script("location.href='/zona-privada/".$_SESSION['identificador_zona_privada']."/buscador'");// HACIA DONDE LO VAMOS A REDIRIGIR.

    }

// }else if(isset($identificador_acceso_zona_privada)){
}else if(isset($recordar_usuario_zona_privada)){

    if (!isset($_COOKIE['aceptada']) || $_COOKIE['aceptada']==0) {
        setcookie("aceptada", 1, time()+864000*365,"/");
    }

    setcookie("new_autologin", $usuario, time()+864000*365, "/");
    script("$('#modal_cookie_lg').modal('hide');");

// }else if(isset($recordar_usuario_zona_privada)){
}else if(isset($cerrar_sesion_zona_privada)){

    // ROMPEMOS TODAS LAS $ DE SESSION Y COOKIE DE AUTOLOGIN
    $arr_unset = array('permiso','nombre','id','identificador','id_delegacion',
    'email','telefono');

    foreach ($arr_unset as $key) {
        unset($_SESSION[$key.'_zona_privada']);
    }
    setcookie("new_autologin", '', time()-864000*365, "/");
    script("location.href='/index.php'");
// }else if(isset($cerrar_sesion_zona_privada)){
}else if(isset($password_actual)){


    $comprueba_pass=$DB->select('password','clientes','identificador like "'.strtoupper($id_password_actual).'"');

    // verificamos que la contraseña sea correcta.
    if(!password_verify($password_actual,$comprueba_pass[f][0]['password'])){

        alert('La contraseña es incorrecta');

    }else if(password_verify($password_actual,$comprueba_pass[f][0]['password'])){

        script("$('#div_cambio_pass').removeClass('hide');
               $('.hide_pass').addClass('hide');");
    }

//}else if(isset($password_actual)){
}else if(isset($realizar_busqueda_hotel_cliente)){

    /* PASOS

    1º SI NO EXISTE CREAR UN BOOKING = ID SESSION BUSQUEDA 
    $ id_cliente + id_booking
    
    */

    // eliminamos la posibilidad de realizar busquedas 
    // sin session de clientes.
    if(!isset($_SESSION['id_zona_privada']) && !isset($_SESSION['Authenticated'])){
        script('alert("Sesion Caducada");location.reload()');
    }

    if(!isset($_SESSION['id_booking'])){

        $DB->openTable('booking');
        $DB->ta['id_cliente']=$_SESSION['id_zona_privada'];
        $DB->ta['id_estado']=2;
        $DB->ta['operadora']="On-line";

        $entrada=formatear_fecha($fecha1,i,m);
        $salida=formatear_fecha($fecha2,i,m);

        $DB->ta['entrada']=$entrada;
        $DB->ta['salida']=$salida;


        // CREAMOS LA ESTRUCTURA HISTORICO
        $historico= new SimpleXMLElement('<?xml version="1.0"?><historico></historico>');
        // AÑADIMOS 1º REGISTRO
        $registro=$historico->addChild('registro');
        $registro->addChild('usuario','On-line');
        $registro->addChild('tipo','creacion');
        $registro->addChild('fecha',date('d/m/Y'));
        $registro->addChild('hora',date('H:i:s'));


        $DB->ta['historico']=$historico->asXML();
        $DB->ta['creacion']=now();
        $_SESSION['id_booking']=$DB->insert();


    } /// LOS NUEVOS HISTORICOS, LOS SUMO EN CONTROLLER 4 AÑADIR EL ID_XML



        #####################
    #
    # id_cliente
    # id_booking
    # POST
    #  ÚLTIMA $ QUE LE PASAMPOS A disponibilidad_clientes, es la manual o auto.
    #
    # edad_max_resultados:20
    # edad_max_precios:0
    #

    /* PARA QUE TODO VUELVA A LA NORMALIDAD
    TENEMOS QUE QUITAR DE CONTROLLER ESTO

        $_SESSION['admin_ok']=1;
        $mostrar=1;            
               

        Y DE AJAX VOLVER A PODER A 1 ESTAS $

            $SA[$ide]=0;
            $AD[$ide]=0; 

    */
           
    

    //var_dump($_POST);
    sleep(3);
    $C=new control($_POST);
    $find=array("´","'","’","€");
    $changue=array('','','',"&euro;");
    $_POST = str_replace($find,$changue, $_POST);
    $url_json = json_encode_bbdd($_POST);

    $listado=$C->disponibilidad_clientes($_SESSION['id_zona_privada'],$_SESSION['id_booking'],$url_json,1);


    if(count($listado)==0){

        echo '<div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>Lo lamentamos pero esta b&uacute;squeda no tiene resultados. Prueba otra fecha, zona o distribuci&oacute;n diferente.</strong>
            </div>';

    }else if($listado>0){
        // este es el contenerdor al que agergamos 
        // tots los hoteles.

        // sacamos el nº de ocupantes + dias, para la division 
        // persona y día.

        //montamos los ocupantes para poder 
        // realizar las divisiones y mostrar persona/dia.
            $ocupantes_division=0;
            $hb=0;
            for($h=1;$h<=$habitacion;$h++){

                $ocupantes_division=${'adultos_'.$h}+$ocupantes_division;
                        
                if(${'ninos_'.$h}>0){
                                              
                    for($n=1;$n<=${'ninos_'.$h};$n++){
       
                        if(${'edad_'.$h.'_'.$n}>2){
                            $ocupantes_division++;
                        } 
                               
                    }
                }
            $hb++;
            // eof for($h=1;$h<=$habitaciones[$p]['numero'];$h++){
            }

            // aqui sacamos el total de lo que tenemos que dividir.
            $dias_division=comparar_fechas($fecha1,$fecha2);
            $total_division=$dias_division*$ocupantes_division;

                        // metemos el limite nada mas empezar.

            $tabla_busqueda='';

            if($idhotel==0 && $segmento==0){

                $tabla_busqueda = $poblacion>0 ? 'poblaciones' : 'provincias';
                $campo_busqueda = $poblacion>0 ? 'poblacion' : 'provincia';
                $valor_busqueda = $poblacion>0 ? $poblacion : $provincia;

                $DB->query('select id_soltury from _hoteles_'.$tabla_busqueda.' where id_'.$campo_busqueda.'_solt='.$valor_busqueda);

                while($data=$DB->fetch()){

                    $id_hoteles_mostrar[] = $data['id_soltury'];

                }
            }

            
        echo "<div id='contenedor_listados_hoteles'>";

        // limito a 100 por w2m
        $limite_hoteles = 80;
        // aqui podemos meter un limite de precio
        $edad_max_precios = 20000;
        $limite=0;
        foreach ($listado as $ide => $datos) {


            // filtro de hoteles que no pertenezcan a la zona, provincia
            if($tabla_busqueda=="" || in_array($ide,$id_hoteles_mostrar)){

                $limite++;

                if($limite<=$limite_hoteles){

                
                    $nombre_hotel=$DB->select('nombre,provincia, poblacion,id_estrellas, descripcion, fotos','_hoteles','id='.$ide);

                    // abrimos contenedro de cada uno de los hoteles.
                    // asignamos info esencial.

                    $arr_foto = json_decode($nombre_hotel[f][0]['fotos']);

                    $n_fotos = count($arr_foto);

                    if(!isset($arr_foto[0])){

                        $arr_foto[0]="/new_soltury/images/sustitucion.png";
                        
                    }
                    // ven aqui
                    echo '<div class="container" style="margin-bottom:30px;">
                    <hr>
                    <div class="mg-avl-room" itemscope itemtype="http://schema.org/Hotel">
                        <div class="row">
                            <div class="col-sm-5" itemscope itemtype="http://schema.org/ImageObject">
                                <a href="#" class="info_hotel_modal" id_hotel="'.$ide.'"
                                data-toggle="modal" data-target="#informacion_hotel_modal">
                                    <img src="'.$arr_foto[0].'" 
                                    onerror="src=\'/new_soltury/images/sustitucion.png\'" 
                                    alt="Foto Principal del Hotel '.strtoupper(tildes_min_may($nombre_hotel[f][0]['nombre'],h)).'" 
                                    class="img-responsive img-center" style="height:200px;">
                                </a>
                            </div> <!-- schema.org/ImageObject"> -->

                            <div class="col-sm-7">

                                <h4 class="mg-avl-room-title" itemprop="name">'.strtoupper(tildes_min_may($nombre_hotel[f][0]['nombre'],h)).' ('.utf8_encode(trim($nombre_hotel[f][0]['poblacion'])).') <strong class="pull-right">'.$nombre_hotel[f][0]['id_estrellas'].'<sup>*</sup></strong></h4>

                                <div class="descripcion_hoteles_listados">
                                    '.utf8_encode(html_entity_decode($nombre_hotel[f][0]['descripcion'])).'
                                </div>

                                <!-- <div class="row mg-room-fecilities">
                                    <div class="col-sm-6">
                                        <ul>
                                            <li><i class="fp-ht-bed"></i> 2 King Beds</li>
                                            <li><i class="fp-ht-food"></i> Breakfast</li>
                                            <li><i class="fa fa-sun-o"></i> Air conditioning</li>
                                        </ul>
                                    </div>
                                    <div class="col-sm-6">
                                        <ul>
                                            <li><i class="fp-ht-dumbbell"></i> GYM fecility</li>
                                            <li><i class="fp-ht-tv"></i> TV LCD</li>
                                            <li><i class="fp-ht-computer"></i> Wi-fi service</li>
                                        </ul>
                                    </div>
                                </div>-->
                                <hr>
                                <button id_hotel="'.$ide.'"
                                    class="btn btn-info pull-right info_hotel_modal"
                                    data-toggle="modal" data-target="#informacion_hotel_modal">
                                    Ver Hotel <i class="fa fa-angle-double-right"></i>
                                </button>
                            </div> <!-- <div class="col-sm-7"> -->


                        </div> <!-- <div class="row"> -->
                    </div> <!-- schema.org/Hotel"> -->';


                        // aqui vienen todas las ofertas 
                       echo '<hr>
                       
                        <div class="row">';


                    // aqui entraba en juego el filtro de las hb
                    // que ha sido eliminado.
                    $total_distribuciones[$ide] = count($datos['listadoPrecios']);
                    $n_datos = $datos;
         

                
                    //echo "<br> tienes " . $total_distribuciones[$ide] . " habitaciones";
                    // for por limite de distribuciones. 
                    for($i=0;$i<$total_distribuciones[$ide];$i++){

                        $data=$n_datos['listadoPrecios'][$i];

                        if($data['proveedor']=="id_bedsonline" ){
                            $data['nombreHab']=utf8_decode($data['nombreHab']);
                        }
                                            
                        $data['nombreHab']=tildes_min_may($data['nombreHab'],b);

                        $arr_hbs_txt = array(", Guest Room, 1 Queen Or 2 Twin/single Bed(s) - Genr - Tbdb",
                                    ", Queen-size Bed With Convertibles Ofa ( No Breakfast )",
                                    "...lounge Access, Free Breakfast/drinks...bathrobe, Special Amenities, Wifi",
                                    "+ Rollaway Bed, Guest Room, 2 Twin/single Bed(s)",
                                    "+ Rollaway Bed, Guest Room, 2 Twin/single Bed(s) - Famz - Tbdf",
                                    "( None )",
                                    "...comp Wifi/herman Miller Chair/32 Inch Sat Tv...serta Bed/mini Fridge/pull Out Sofa",
                                    "...comp Wifi/herman Miller Chair/32 Inch Sat Tv...serta Bed/mini Fridge/24hrs Pavillion Pantry");


                        $data['nombreHab']=str_replace($arr_hbs_txt,"",$data['nombreHab']);

                        $data['total']=format_number($data['total']);
                        $precio_division=$data['total']/$total_division;

                            // aqui metemos el form de cada hb
                            // para poder empaquetar todos los datos.
                        $cl= "";
                        if($i>=2){
                            $cl = "eliminar_padding";
                        }
                        if($total_distribuciones[$ide]>2 && $i==2){

                            echo '  <div class="panel-heading" role="tab" id="headingOne">
                                            <h4 class="panel-title">
                                                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne'.$ide.'" aria-expanded="false" aria-controls="collapseOne" class="cierra_toggle">
                                                    Mas Ofertas y Habitaciones ('.strtoupper(tildes_min_may($nombre_hotel[f][0]['nombre'],h)).')
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseOne'.$ide.'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                            <div class="panel-body" class="eliminar_padding">';

                           $end_toggle = $total_distribuciones[$ide]-1;

                        }
                            // h_'.$ide.'_r_'.$i.'
                            echo '<div class="col-xs-12 div_precios_listados_hoteles '.$cl.'">
                            <form action="/zona-privada/'.$_SESSION['identificador_zona_privada'].'/detalles_reserva" method="post" accept-charset="utf-8">

                            <div class="col-xs-12 eliminar_padding">
                                 <div class="col-sm-4 hidden-xs pull-left text-center eliminar_padding header_ofertas_listados">
                                <b>Tipo De Habitaci&oacute;n</b>
                                </div>
                                <div class="col-sm-2 hidden-xs pull-left text-center eliminar_padding header_ofertas_listados">
                                <b>Regimen</b>
                                </div>    
                                <div class="col-sm-2 hidden-xs pull-left text-center eliminar_padding header_ofertas_listados">
                                <b>Precio</b>
                                </div> 
                                <div class="col-sm-4 hidden-xs pull-left text-center eliminar_padding header_ofertas_listados">
                                <b>Oferta Soltury</b>
                                </div> 
                            </div>';
                            
                            #######################
                            ###
                            ### check para llevar o no la info de:
                            ### textos especiales ofertas hb.
                            ### entradas en gastos de clx.
                            ### estandarizar los netos de las hidden$ 
                            ###
                            #######################

                            #####
                            ##
                            ## abrevProv
                            ## offer
                            ## gastos


                            // estan son todas las $ de cada uno de los proveedores
                            // que sacamos en cada una de las habitaciones
                            // que necesitamos para pasarlas por $_POST y usarlas en
                            // $C->detalles 

                            $arr_reg = array("SA","AD","MP","PC","TI");
                            $arr_reg_repl= array("S&oacute;lo Alojamiento" ,
                            "Alojamiento y Desayuno", 
                            "Media Pensi&oacute;n", 
                            "Pensi&oacute;n Completa",
                            "Todo Incluido");
                            $data['abrevReg'] = str_replace($arr_reg,$arr_reg_repl,$data['abrevReg']);


                            include('../includes/inc_variables_listados_hoteles.php');
                  
                            // ##### seccion de impresion en pantalla 

                            // div de la HB 
                            echo "<div class='col-sm-4 col-xs-12 pull-left text-center eliminar_padding'>
                                 <div class='col-xs-12 visible-xs pull-left text-center eliminar_padding header_ofertas_listados'>
                                <b>Tipo De Habitaci&oacute;n</b>
                                </div>
                                <b>".$data['nombreHab']."</b>";
                                // icono de informacion adicional/especial de la habitacion 
                                if(isset($data['offer']) && $data['offer']!="" && strlen(trim($data['offer']))){
                                    echo " <i title='".$data['offer']."' data-placement='bottom' data-toggle='tooltip' 
                                    class='fa fa-info-circle' onclick='return true'></i>";
                                }

                            echo "<br><br></div>";

                            // DIV del regimen

                            echo "<div class='col-sm-2 col-xs-12 pull-left text-center eliminar_padding'>
                                <div class='col-xs-12 visible-xs pull-left text-center eliminar_padding header_ofertas_listados'>
                                <b>Regimen</b>
                                </div>
                                <b>".$data['abrevReg']."</b>
                           <br><br>
                            </div>";

                            
                            
                            echo "<div class='col-sm-2 col-xs-12 pull-left text-center eliminar_padding'>
                                <div class='col-xs-12 visible-xs pull-left text-center eliminar_padding header_ofertas_listados'>
                                <b>Precio</b>
                                </div> 
                            <span class='txt_red'><strike>".number_format($data['recomendado'],2,".","")." &euro;
                            </strike></span>
                           <br><br>
                            </div>";

        // title='Gastos' data-content='".$data['gastos']."'  data-toggle='popover' 
                            echo "<div class='col-sm-4 col-xs-12 pull-left text-center eliminar_padding'>
                                <div class='col-xs-12 visible-xs-block pull-left text-center eliminar_padding header_ofertas_listados'>
                                <b>Oferta Soltury</b>
                                </div>
                            <span class='text_1_9 texto_color_azul'>".number_format($data['total'],2,".","")." &euro; </span>";

                                // aqui vamos a poner los datos que necesitan cada
                                // uno de los proveedores
                                // en el id_xml, tenemos todos los datos la busqueda.
                                // equivalentes al antiguo varget

                                if($data['proveedor']=="id_europlayas"){

                                    $pedir_gastos=$data['gastos'];

                                }else if($data['proveedor']=="id_veturis"){

                                    $pedir_gastos=$data['obj']."-".$data['DATOS'];

                                }else if($data['proveedor']=="id_bedsonline"){

                                    $pedir_gastos = $data['IncomingOffice']
                                    ."-bds-".$data['token']
                                    ."-bds-".$data['ContractName']
                                    ."-bds-".$data['ticket']
                                    ."-bds-".$data['codeHab']
                                    ."-bds-".$data['codeReg']
                                    ."-bds-".$data['habCaract'];

                                }else if($data['proveedor']=="id_versys" || $data['proveedor']=="id_solole"){

                                    $pedir_gastos = $data['SequenceNmbr']
                                    ."-jnp-".$data['RatePlanCode'];

                                }else if($data['proveedor']=="id_serhs"){

                                    $pedir_gastos = $data['gastos'];
                                }

                                echo "<span class='text_1_4 texto_color_azul_claro'>
                                        <i class='fa fa-exclamation-triangle tooltip_gastos'
                                            data-toggle='modal' 
                                            data-target='#info_gastos_cancelacion'
                                            data-proveedor='".$data['proveedor']."'
                                            data-hotel='".$data['codeHotel']."'
                                            data-gastos='".$pedir_gastos."'
                                           >
                                        </i>
                                    </span>";

                           // }


                            echo "<br>
                            <small> (".number_format($precio_division,2,".","")."&euro; Per/D&iacute;a)</small>


                            </div>";


                           echo  "<div class='col-xs-12 text-center eliminar_padding'>
                           <br>
                           <button type='submit' class='btn btn-success btn-lg pull-right col-md-3 col-sm-4 col-xs-12'>Reservar</button>
                           </div>";
                            // ##### eof seccion de impresion en pantalla 

                            // filtro por límite de presupuesto,
                            // este filtro afecta a todos los régimenes.  
                                if($edad_max_precios>0 && $data['total']>=$edad_max_precios){
                                    $limite=$limite_hoteles;
                                                // if($zonas[$p]['zona'][$z]['precios']>0
                                }
                            
                            echo '</form>
                            </div>';//<div class="col-xs-12">

                        

                        if($total_distribuciones[$ide]>2 && isset($end_toggle) && $i==$end_toggle){
                            echo '</div>
                            </div>';
                        }

                    
                    } //for($i=0;$i<$total_distribuciones[$ide];$i++){  




                    echo '</div> <!-- class="row">-->
                    </div>'; // <div class="col-md-12" style="margin-bottom:30px;">
                }// if($limite<$limite_hoteles){ 

            } // if($tabla_busqueda=="" || in_array($ide,$id_hoteles_mostrar)){

        }// foreach ($listado as $ide => $datos) { 

        echo '</div>'; // echo "<hr><div id='contenedor_listados_hoteles'>";  
    } // }else if($listado>0){ 

    // añadimos los nuevos conceptos, de precio recomendado
    // viene en $xxxxx['recomendado']
         script("$('#buscador_edades_regimenes').modal('hide');
    $('#listado_paginado').removeClass('hide');
    $('#div_regimenes_edades').removeClass('hide');
    $('#gif_espera_disponibilidad').addClass('hide');
    $('[data-toggle=\"tooltip\"]').tooltip();
    $('[data-toggle=\"popover\"]').popover();");



    //return $listado;


// eof }else if($realizar_busqueda_hotel_cliente){
}else if(isset($pedir_gastos_listados)){

    // eliminamos la posibilidad de realizar confirmaciones 
    // sin session de clientes.
    if(!isset($_SESSION['id_zona_privada']) && !isset($_SESSION['Authenticated'])){
        script('alert("Sesion Caducada");location.reload()');
    }

    if($proveedor=="id_europlayas"){


        if($pedir_gastos_listados!=""){
            echo $pedir_gastos_listados;
        }else{
            echo "Sin datos disponibles, para concer los gastos de cancelaci&oacute;n pulse
            Reservar.";

        }
        
        script("$('#espera_gastos').addClass('hide');");
        
    }else if($proveedor=="id_serhs"){

        echo $pedir_gastos_listados;
        script("$('#espera_gastos').addClass('hide');");

    }else{

        if($proveedor=="id_veturis"){

            $pre_datos = explode("-", $pedir_gastos_listados);
            $_POST['obj'] = $pre_datos[0];
            $_POST['DATOS'] = $pre_datos[1];

        }else if($proveedor=="id_bedsonline"){

            $pre_datos = explode("-bds-", $pedir_gastos_listados);
            $_POST['IncomingOffice'] = $pre_datos[0];
            $_POST['token'] = $pre_datos[1];
            $_POST['ContractName'] = $pre_datos[2];
            $_POST['ticket'] = $pre_datos[3];
            $_POST['codeHab'] = $pre_datos[4];
            $_POST['codeReg'] = $pre_datos[5];
            $_POST['habCaract'] = $pre_datos[6];


        }else if($proveedor=="id_versys" || $proveedor=="id_solole"){

            $pre_datos = explode("-jnp-", $pedir_gastos_listados);
            $_POST['SequenceNmbr'] = $pre_datos[0];
            $_POST['RatePlanCode'] = $pre_datos[1];

        }


        
        $C=new control($_POST,$proveedor);
        $resultado_gastos = $C->sacar_gastos();

        echo $resultado_gastos['textos_cancelaciones'];
        script("$('#espera_gastos').addClass('hide');");

    }



// }else if($pedir_gastos_listados){
}else if(isset($reservar_pendiente)){

    // eliminamos la posibilidad de realizar confirmaciones 
    // sin session de clientes.
    if(!isset($_SESSION['id_zona_privada']) && !isset($_SESSION['Authenticated']) && (!isset($_SESSION['id_web']) || !isset($_SESSION['id_web']) && $_SESSION['id_web']==0)){
        script('alert("Sesion Caducada");location.reload()');
    }

    // lo que viene por post y necesito tener siempre:
    // $_POST['id_detalles']=time();
    // id_detalles => este va a ser usado como DS_MERCHANT_ORDER
    // id_xml
    // id_booking
    // proveedor  
    // total  
    // horario_reserva 
    // observaciones_reserva 
    // email_reserva  
    // telefono_reserva  
    // titular_reserva 

    // + datos adicionales de cada uno de los proveedores 

    // $find=array("´","'");
    // $changue=array("\´","\'");

    // tildes_to_html(str_replace($find,$changue,$data[0]),1);

    $order   = array("\r\n", "\n", "\r","\t","\0","\x0B","'"); 
    // hay que recorer esto en un array para poder quitar los tags
    foreach ($_POST as $key => $value) {
        
        $_POST[$key] = str_replace($order, '',strip_tags($value));

    }

    if($proveedor=="id_veturis"){

        $_POST['observaciones_proveedor'] = str_replace("€","&euro;",$_POST['observaciones_proveedor']);
    }

    $varpost=my_json_encode($_POST);
    $DS_MERCHANT_ORDER=time();

    $DB->opentable('comunicacion_tpv_reservas');
    $DB->ta['varpost']=$varpost;
    $DB->ta['DS_MERCHANT_ORDER']=strval($DS_MERCHANT_ORDER);
    $DB->ta['total']=$total;
    $DB->ta['proveedor']=$proveedor;
    $DB->ta['finalizada']=0;
    $DB->ta['fecha']=now();
    // la añado aqui tambien a post, para que entre por
    // parametro a la hora de llamar a la confirmacion
    // de los proveedores.
    $_POST['comunicacion_tpv_reservas']=$comunicacion_tpv_reservas=$DB->insert();

        //AQUI VAMOS A SACAR TODOS LOS DATOS QUE VARIABILIZAMOS PARA PODER PERSONALIZAR LS WEB DESDE LA BBDD EN LA TABLA DELEGACIONES.
        $DB->query('select * from delegaciones where id='.$_SESSION['id_web']);

        $datos=$DB->fetch();

        foreach ($datos as $key => $value) {
            // metemos en el array datos_web
            $datos_web[$key]=$value;

        }


    if($reservar_pendiente==0 && ! isset($_SESSION['Authenticated']) && !isset($_SESSION['id_cliente_booking'])){

        ########## IMPORTANTE LUEGOP AÑADIR EL ID DEL comunicacion_tpv_reservas DENTRO DEL ARRAY QUE 
        ########## VOY A PASARLE A LA CONFIRMACION DENTRO DEL TPV 

            $mail = new phpmailer($datos_web['email']);
            $mail->setFrom($datos_web['email'],$datos_web['nombre']);
            $mail->AddAddress('teresaotero@soltury.com');
            $mail->AddCC("pedrogil@soltury.com");
            $mail->Subject="Inicio de pago y confirmacion de RSV";
            $texto = "Datos de la reserva iniciada:<br>Comunicacion TPV ".$comunicacion_tpv_reservas;

            foreach ($_POST as $key => $value) {
                $texto.="<br>".$key." ".$value;
            }

            $mail->Body=body_email(t,"",$_SESSION['id_web'],$texto);
            $mail->send();

            // metemos el tpv
            include('redsysHMAC256_API_PHP_4.0.2/apiRedsys.php');
            $miObj = new RedsysAPI;
            $action='https://sis.redsys.es/sis/realizarPago';//produccion
            $fuc=$datos_web['usuario'];
            $kc =$datos_web['tpv_pass'];

            $terminal="001";
            $moneda="978";
            $trans="0"; // cobro 
            //$trans="3"; // devolucion automatica 

            $urlOK = "https://".$datos_web['web']."/confirmacion_de_pagos.php";
            $urlKO = "https://".$datos_web['web']."/confirmacion_de_pagos.php";
            $url_url="https://".$datos_web['web'];

            $miObj->setParameter("DS_MERCHANT_AMOUNT",$total);
            $miObj->setParameter("DS_MERCHANT_ORDER",strval($DS_MERCHANT_ORDER));
            $miObj->setParameter("DS_MERCHANT_MERCHANTCODE",$fuc);
            $miObj->setParameter("DS_MERCHANT_CURRENCY",$moneda);
            $miObj->setParameter("DS_MERCHANT_TRANSACTIONTYPE",$trans);
            $miObj->setParameter("DS_MERCHANT_TERMINAL",$terminal);
            $miObj->setParameter("DS_MERCHANT_MERCHANTURL",$url_url);
            $miObj->setParameter("DS_MERCHANT_URLOK",$urlOK);   
            $miObj->setParameter("DS_MERCHANT_URLKO",$urlKO);

            //Datos de configuración
            $version="HMAC_SHA256_V1";

            // Se generan los parámetros de la petición
            // $request = "";
            $params = $miObj->createMerchantParameters();
            $signature = $miObj->createMerchantSignature($kc);


            echo "<form name='frm' action='".$action."' method='POST' >\n";
            echo "<input type='hidden' name='Ds_SignatureVersion' value='".$version."'/>\n";
            echo "<input type='hidden' name='Ds_MerchantParameters' value='".$params."'/>\n";
            echo "<input type='hidden' name='Ds_Signature' value='".$signature."'/>\n";
            // submitamos el form 
            script('$("[name=\'frm\']").submit();');

            exit();
        

    }else if($reservar_pendiente==1 || isset($_SESSION['Authenticated']) && isset($_SESSION['id_cliente_booking'])){
        // reserva directa.


        $C = new control($_POST,$proveedor);
        $confirmacion = $C->confirmar($comunicacion_tpv_reservas);

        if($confirmacion=="" || $confirmacion==false || $confirmacion['confirmacion']==0){

            $estado_abonado = $reservar_pendiente==1 ? 'pendiente de pago' : 'abonada';
           
            $mail = new phpmailer("booking@soltury.com"); 
            $mail->setFrom("booking@soltury.com","Soltury.com"); 
            $mail->AddAddress("teresaotero@soltury.com");
            $mail->AddCC("pedrogil@soltury.com");

            $mail->Subject="Error en la CONFIRMACION con ".$proveedor; 
            // sacamos aqui el cuerpo de 
            $data_confirmacion=$DB->inner_join('id_delegacion,xml,id_cliente,id_booking','_xml_confirmaciones x inner join clientes c','x.id_cliente = c.id where x.id='.$_SESSION['id_confirmacion'],f);

            $data_xml = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '',$data_confirmacion[f][0]['xml']),true);

            $texto = "La reserva estaba ".$estado_abonado." del cliente ".$data_confirmacion[f][0]['id_cliente']." con id_booking ".$data_confirmacion[f][0]['id_booking']."y la respuesta del proveedor es :
                <br><br>" .$confirmacion['motivo']."<br><br>
                El xml devuelto es : <br><br>".$data_xml['xml_response'];

            $mail->Body=body_email(t,"",$data_confirmacion[f][0]['id_delegacion'],$texto);

            $mail->send();

            alert('Lo sentimos, debido a un error no se ha podido confirmar la reserva, vamos a volver a realizar otra búsqueda');

            if(!isset($_SESSION['Authenticated']) && !isset($_SESSION['id_cliente_booking'])){

                script("location.href='https://".$datos_web['web']."/zona-privada/".$_SESSION['identificador_zona_privada']."/buscador/".$_SESSION['id_xml']."/1'");

            }else{

                script(" $('#listados_hoteles').collapse('hide');
                    $('#resultados_hoteles').empty();
                    $('#buscador_include').call_ajax({
                        serialice: '#buscador_include',
                        respuesta : '#resultados_hoteles'
                    });");

            } // if(!isset($_SESSION['Authenticated']) && !isset($_SESSION['id_cliente_booking'])){

        }else if($confirmacion['confirmacion']==1){

            ######### IMPORTANTE : 
            #
            # TODO LO QUE MODIFIQUE AQUI TENGO QUE PASARLO A confirmar_redsys_reserva.php HASTA QUE HAGA 
            # UN METODO DE ESTO.
            #

            // VAMOS A CREAR EL RESGISTRO DE LA RESERVA EN reservas_finalizadas.
            $DB->openTable('reservas_realizadas');
            $DB->ta['id_cliente']=$id_cliente;
            $DB->ta['proveedor']=$proveedor;
            $DB->ta['localizador']=$confirmacion['localizador'];
            $DB->ta['entrada']=$entrada;
            $DB->ta['salida']=$salida;
            $DB->ta['estado']=1;

            // operacion tpv fisico, cuando se hace from booking
            if(isset($_SESSION['Authenticated'])){

               $DB->ta['operacion_tpv']=$operacion_tpv;

            }

            $DB->ta['id_comunicacion_tpv_reservas']=$comunicacion_tpv_reservas;
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

            }
                    


            if($confirmacion['observaciones_proveedor']!=""){


                $find=array("´","'","’","€");
                $changue=array('','','',"&euro;");
                $confirmacion['observaciones_proveedor']= str_replace($find,$changue, $confirmacion['observaciones_proveedor']);

                // quitar saltos de linea por que da error al json_decode en descargardocumentacion 
                $order   = array("\r\n", "\n", "\r","\t","\0","\x0B"); 

                if($proveedor!="id_europlayas"){

                    $_POST['observaciones_proveedor']=str_replace($order, '',tildes_to_html(strip_tags($confirmacion['observaciones_proveedor'])));

                }else if($proveedor=="id_europlayas"){ 

                    $_POST['observaciones_proveedor']=str_replace($order, '',strip_tags($confirmacion['observaciones_proveedor']));
                                    

                }
                // if($proveedor=='id_versys'){

                //     $_POST['observaciones_proveedor']=str_replace($order, '',tildes_to_html(strip_tags($confirmacion['observaciones_proveedor'])));

                // }else{

                //     $_POST['observaciones_proveedor']=str_replace($order, '',utf8_decode(strip_tags($confirmacion['observaciones_proveedor'])));
                // }
            }


            #
            # VERSION 1.2 ELIMINAMOS QUE DEVUELVAN 
            # TODOS LOS PROVEEDORES LOS GASTOS 
            # Y LOS METEMOS DESDE DEL LISTADO, YA ESTA ECHO POR VETURIS.
            #

            // en veturis los traemos del listado
            if($proveedor!="id_veturis"){

                // ORDENAMOS LOS QUE VENGAN DESORDENADOS Y LOS METEMOS A 
                // LA COFIRMACION PARA LUEGO PODER MONTARLOS EN EL VOUCHER.
                //array_multisort($confirmacion['cancelacion'], SORT_ASC);


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
                    echo "<br>".$_POST['fecha_'.$i]. " - ". $_POST['importe_'.$i];
                                                       
                } // for($i=0;$i<count($cancelaciones);$i++){

            }

            $_POST['titular_reserva']=utf8_encode($_POST['titular_reserva']);
            $_POST['observaciones_reserva']=utf8_encode($_POST['observaciones_reserva']);
            $_POST['regimen'] = utf8_encode($_POST['regimen']);
            $_POST['habitacion'] = utf8_encode($_POST['habitacion']);
            
            $DB->ta['voucher']=utf8_decode(my_json_encode($_POST));

            $DB->ta['id_hotel']=$codeHotel;
            $id_hoteles = $DB->select('id','_hoteles',$proveedor.' like "'.$codeHotel.'"');
            $DB->ta['id_hoteles']=$id_hoteles[f][0]['id'];
            $DB->ta['neto']=$confirmacion['neto']*100;
            $DB->ta['total']=$total;
            $DB->ta['bonos_gastados']=$resta_bonos;
            $DB->ta['fecha']=now();

            // CREAMOS LA ESTRUCTURA
            $historico= new SimpleXMLElement('<?xml version="1.0"?><historico></historico>');
            // AÑADIMOS 1º REGISTRO
            $registro=$historico->addChild('registro');

            $usuario_historico = isset($_SESSION['identificador_zona_privada']) ? $_SESSION['identificador_zona_privada'] : $_SESSION['nombre_usuario']; 

            $registro->addChild('usuario',$usuario_historico);
            $registro->addChild('tipo','creacion');
            $registro->addChild('fecha',date('d/m/Y'));
            $registro->addChild('hora',date('H:i:s'));
            
            // si viene de booking y esta pagada
            if($reservar_pendiente==0){

                $DB->ta['fecha_cobro']=now();
                $DB->ta['cobrada']=1;
                $DB->ta['id_via_cobro']=6;

                // AÑADO TAMBIEN EL PASO DEL COBRO
                $nuevo_historico = sumar_historico_xml($historico->asXML(),$usuario_historico,'Cobro',$comunicacion_tpv_reservas,$total);

                $DB->ta['historico']=$nuevo_historico;

            }else{

                $DB->ta['fecha_pago_cliente']=$fecha_pago_cliente;
                $DB->ta['historico']=$historico->asXML();
            }

            $id_reserva = $DB->insert(1);

            // añadimos el movimiento bancario en caso de 
            // que este cobrada por el personal de booking
            if($reservar_pendiente==0){

                $DB->openTable('movimientos_bancarios');
                $DB->ta['id_via_cobro']=6;//importe
                $DB->ta['importe']=$total;
                $DB->ta['tipo']=1; // 1= suma /0=resta
                $DB->ta['procedencia']='reservas_realizadas';
                $DB->ta['id_procedencia']=$id_reserva;
                $DB->ta['fecha']=now();
                $id_movimiento_bancario=$DB->insert();
                //script($esconder);
            }

            // operaciones de resta de bonos.
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
            

            // SACAMOS LOS DATOS DEL HOTEL QUE VAMOS A UTILIZAR
            $datos_hotel = $DB->select("id,nombre,poblacion,direccion,cp,pais,email,id_estrellas",
            "_hoteles",$proveedor." like '".$codeHotel."'");
            // subimos las posiciones al array
            $datos_hotel = $datos_hotel[f][0];



 #### HACER ESTO UNA FUNCION  #### HACER ESTO UNA FUNCION  #### HACER ESTO UNA FUNCION

            // aqui vamos a sacar todos los datos 
            // para la personalizacion de cliente y marca
            $datos=$DB->inner_join('c.nombre as cliente,
            d.nombre as delegacion,
            d.web,
            c.id_delegacion,
            c.identificador,
            c.comentario,
            d.email as remitente,
            d.color_email,
            d.IBAN,
            c.email as destinatario','clientes as c inner join delegaciones as d',
            'c.id_delegacion = d.id and c.id='.$id_cliente);

            $mail = new phpmailer($datos[f][0]['remitente']);
            $mail->setFrom($datos[f][0]['remitente'],$datos[f][0]['delegacion']);

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


            $mail->Subject="Confirmación de reserva en " .$datos[f][0]['delegacion'];

            $datos_cliente=array("id"=>$id_cliente,"nombre"=>utf8_decode($titular_reserva),
            "identificador"=>$datos[f][0]['identificador'],
            "comentario"=>$datos[f][0]['comentario']);

            $estado_abonado = $reservar_pendiente==1 ? 'pendiente de pago' : 'y abonado';


            // me falta meter el mensaje de que faltan bonos<
            $total_final=$total/100;
            $textos ='Tu reserva se ha confirmado <b>'.$estado_abonado.'</b> correctamente con los siguientes datos:<br><br>

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
                            <td style="width: 50%;"><b>'.resta_dias($_POST['fecha_0'],5).'</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>';

            if($reservar_pendiente==1){
                $textos.='<br>
                Puedes realizar el pago de la reserva con tu tarjeta desde la secci&oacute;n <b>&quot;Mis Reservas&quot;</b> en cualquier momento.  
                <br><br>Tambi&eacute;n puedes hacernos una <b>transferencia o ingreso</b> al siguiente IBAN:<br>
                '.$datos[f][0]['IBAN'].'

                <br><br>Si llegado d&iacute;a se te ha olvidado y no has realizado el pago, te enviaremos un avisador autom&aacute;tico para que puedas realizar el pago.
                ';
            }
            

            if($bonos_gastados==1 || $bonos_gastados==2){


                $identificador_codigo_descuento  = isset($_SESSION['identificador_zona_privada']) ? $_SESSION['identificador_zona_privada'] : $_SESSION['id_cliente_booking']; 

                $frase_agotado='agotado';
                if($bonos_gastados==2){
                    $frase_agotado='superado los bonos de';
                } // if($bonos_gastados==2){
  

                if($datos[f][0]['delegacion'] == "Soltury"){

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

                        <img src="https://'.$datos[f][0]['web'].'/new_soltury/images/cargadores/0.jpg"><br><br><span style="color : red;">*Este c&oacute;digo tiene una validez de 2 d&iacute;as desde hoy <br> **El precio real sin el descuento es de 89,00€</span>
                        <br><br>
                        <a href="https://'.$datos[f][0]['web'].'/new_soltury/index.php?proceso_entrada=regalo_cargador&id_cliente_oferta='.$id_cliente.'&codigo_personalizado='.$txt_codigo.'"
                    style="font-family: Playfair Display,serif; border-radius: 1px; font-weight: 600;border-width: 0;
                    padding: 8px 25px; transition: color .5s , border-color .5s , background-color .5s; font-size: 16px;
                    padding: 10px 35px; color: #fff; background-color: #'.$datos[f][0]['color_email'].';
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


            $textos.='<br><br>Para descargar la documentaci&oacute;n que tienes que llevar al hotel s&oacute;lo tienes que pulsar el siguiente boton:
            <br><br>
            <div style="width:100%; text-align: center;
                border-bottom:#CCC 1px dotted; 

                padding:10px 0 10px 0">
            
            <a href="https:'.$datos[f][0]['web'].'/dulceria/functions/descargar_documentacion.php?id_voucher='.$id_reserva.'"
                style="font-family: Playfair Display,serif; border-radius: 1px; font-weight: 600;border-width: 0;
                padding: 8px 25px; transition: color .5s , border-color .5s , background-color .5s; font-size: 16px;
                padding: 10px 35px; color: #fff; background-color: #'.$datos[f][0]['color_email'].';
                text-decoration:none;">
                DESCARGAR DOCUMENTACION PARA EL HOTEL
                </a>
                <br><br>
            <b style="font-family: Playfair Display,serif;">Te agradecemos que hayas confiado en nosotros y te deseamos una Feliz Estancia!</b>
            </div>
            ';


            $mail->Body=body_email(f,$datos_cliente,$datos[f][0]['id_delegacion'],$textos);
            $mail->send();

            // este email es el recordatorio de los datos de pago
            // que me pidio tere, para los pendients de pago
            if($reservar_pendiente==1){

                $mail->Subject="Datos adicionales sobre tu reserva en " .$datos[f][0]['delegacion'];

                $textos ='Te recordamos los datos mas relevantes sobre el pago de tu reserva:<br><br>

                <div style="width:100%; 
                    border-bottom:#CCC 1px dotted; 
                    border-top:#CCC 1px dotted;
                    padding:10px 0 10px 0">
                    <table style="width:100%; " >
                        <caption style="height:40px; "><b>LOCALIZADOR '.$confirmacion['localizador'].'</b></caption>

                        <tbody>
                            <tr>
                                <td style="width:50%;">Fecha L&iacute;mite de Pago:</td>
                                <td style="width: 50%;"><b>'.resta_dias($_POST['fecha_0'],5).'</b></td>
                            </tr>
                            <tr>
                                <td style="width:50%;">Importe Final:</td>
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
                                <td style="width:50%;">Importe Total:</td>
                                <td style="width: 50%;"><b>'.number_format($total_final,2,".","").' &euro;</b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>';

                $mail->Body=body_email(f,$datos_cliente,$datos[f][0]['id_delegacion'],$textos);
                $mail->send();

            } // if($reservar_pendiente==1){
 #### HACER ESTO UNA FUNCION  #### HACER ESTO UNA FUNCION  #### HACER ESTO UNA FUNCION 

            ############## EMAIL XRA BOOKING DO TOO FUNCTION

            $mail_2 = new phpmailer($datos[f][0]['remitente']);
            $mail_2->setFrom($datos[f][0]['remitente'],$datos[f][0]['delegacion']);
            $mail_2->AddAddress('teresaotero@soltury.com');
            $mail_2->AddCC("pedrogil@soltury.com");


            $mail_2->Subject="Reserva ".$estado_abonado." en " .$datos[f][0]['delegacion'];

            $datos_cliente=array("id"=>$id_cliente,"nombre"=>utf8_decode($titular_reserva),
            "identificador"=>$datos[f][0]['identificador'],
            "comentario"=>$datos[f][0]['comentario']);

            $total_final=$total/100;
            
            $textos ='Se ha confirmado una reserva en <b>'.$estado_abonado.'</b> correctamente con los siguientes datos:<br><br>

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
                            <td style="width: 50%;"><b>'.utf8_encode($datos[f][0]['cliente']).' ('.$datos[f][0]['identificador'].')</b></td>
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
                        </tr>';

                        if($reservar_pendiente==1){
                            $textos.='<tr>
                                <td style="width:50%;">Fecha L&iacute;mite de Pago:</td>
                                <td style="width: 50%;"><b>'.resta_dias($_POST['fecha_0'],5).'</b></td>
                            </tr>';
                        }
                        
                    $textos .='</tbody>
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
            
            <a href="https:'.$datos[f][0]['web'].'/dulceria/functions/descargar_documentacion.php?id_proforma='.$id_reserva.'"
                style="font-family: Playfair Display,serif; border-radius: 1px; font-weight: 600;border-width: 0;
                padding: 8px 25px; transition: color .5s , border-color .5s , background-color .5s; font-size: 16px;
                padding: 10px 35px; color: #fff; background-color: #'.$datos[f][0]['color_email'].';
                text-decoration:none;">
                DESCARGAR FACTURA PROFORMA
                </a>
                <br><br>
            </div>
            ';


            $mail_2->Body=body_email(t,"",$datos[f][0]['id_delegacion'],$textos);
            $mail_2->send();
 #### HACER ESTO UNA FUNCION  #### HACER ESTO UNA FUNCION  #### HACER ESTO UNA FUNCION 

            #################################IMPORTANTE###########################
            // la redireccion no la necesito tener en confirmar_redsys_reserva.php
            #################################IMPORTANTE###########################
            if(!isset($_SESSION['Authenticated']) && !isset($_SESSION['id_cliente_booking'])){

                script("location.href='https://".$datos[f][0]['web']."/zona-privada/".$_SESSION['identificador_zona_privada']."/reserva_finalizada/".$id_reserva."'"); 

            }else{

                alert('La reserva ha sido confirmada, en breve recibiras un email con los datos');
                script("open_pop_up('https://soltury.com/dulceria/functions/descargar_documentacion.php?id_proforma=".$id_reserva."',800,900);
                    window.close();");

            } // if(!isset($_SESSION['Authenticated']) && !isset($_SESSION['id_cliente_booking'])){

        } // else if($confirmacion['confirmacion']==1){

    } // }else if($reservar_pendiente==1){

// }else if(isset($reservar_pendiente)){   
}else if(isset($id_recibo_reserva)){

    // eliminamos la posibilidad de realizar confirmaciones 
    // sin session de clientes.
    if(!isset($_SESSION['id_zona_privada']) && !isset($_SESSION['Authenticated'])){
        script('alert("Sesion Caducada");location.reload()');
    }
    
    // Saco los datos necesarios para montar el ticket
    $data = $DB->select('localizador,total,fecha_cobro,voucher','reservas_realizadas','id='.$id_recibo_reserva);
    $ticket = $data[f][0];

    list($fecha,$hora) = explode(' ', $ticket['fecha_cobro']);

    $txt_modal = "Te confirmamos que el d&iacute;a <b>".formatear_fecha($fecha,m,i)." a las ".$hora."</b> recibimos satisfactoriamente el pago de un importe de <b>".number_format($ticket['total']/100,2,".","")." &euro;</b> referente a tu reserva con localizador ".$ticket['localizador'].".";

    script("$('#info_ticket_pago_body').html('".$txt_modal."');
    $('#info_ticket_pago').modal('show');");


//}else if(isset($id_recibo_reserva)){
}else if(isset($id_reserva_gastos)){

    // eliminamos la posibilidad de realizar confirmaciones 
    // sin session de clientes.
    if(!isset($_SESSION['id_zona_privada']) && !isset($_SESSION['Authenticated'])){
        script('alert("Sesion Caducada");location.reload()');
    }

    $C = new control($_POST,$proveedor);
    $datos_cancelacion = $C->infoCancelacion();


    $data_reserva = $DB->select('id_cliente,cobrada,total,fecha_cobro,bonos_gastados,id_via_cobro,localizador','reservas_realizadas','id='.$id_reserva_gastos);
    $reserva = $data_reserva[f][0];

    $txt_adicional = "<br><br>INFORMACION ADICIONAL <br><br>Esta reserva";

    if($reserva['cobrada']==1){
        // en caso de que este pagada aadimos informacion

        $importe_devolucion =($reserva['total']/100)-$datos_cancelacion['importe'];
        list($fecha,$hora) = explode(' ', $reserva['fecha_cobro']);
        $txt_adicional.=" ha sido abonada el d&iacute;a <b>".formatear_fecha($fecha,m,i)." a las ".$hora."</b>. El importe total que abonaste fue <b>".number_format($reserva['total']/100,2,".","")." &euro;</b>. Si cancelas en este momento te ser&aacute; devuelto <b>".number_format($importe_devolucion,2,".","") ."&euro; ";
        // en caso tpv-on_line un mensaje
        $comprobar_tpv = $DB->select('id','delegaciones','id_via_cobro_web='.$reserva['id_via_cobro']);
        if($comprobar_tpv[r]>0){
            $txt_adicional.="inmediatamente</b>";
        }else{
            $txt_adicional.="en un plazo m&aacute;ximo de 24h laborables</b>";
        }

        $txt_adicional.=" en el mismo medio que fue abonada.";
    }else if($reserva['cobrada']==0){

        $txt_adicional.=" <b>no hab&iacute;a sido abonada</b>.";
    }

    if($reserva['bonos_gastados']>0){

        $txt_adicional.=" <br><br> Consumiste ".$reserva['bonos_gastados']." bono/s y te ser&aacute;/n devuelto/s a tu cuenta en caso de que canceles.";
    }

    script("$('#info_gastos_cancelacion_body').removeClass('hide');$('#textos_gastos_cancelacion').html('".$datos_cancelacion['gastos'].$txt_adicional."');$('#espera_gastos').addClass('hide');$('[name=\"id_reserva_cancelar\"]').next('input').val('".$proveedor."');");

    // si da un error al sacar los datos, nos avisamos para poder solventar lo que sea.
    if($datos_cancelacion['error']==1){

            script("$('#boton_cancelacion').addClass('hide');");


            /// aqui tengo que coger el id del cliente para meterlo
            $datos=$DB->inner_join('c.nombre as cliente,
            d.nombre as delegacion,
            d.web,
            c.id_delegacion,
            c.identificador,
            c.comentario,
            d.email as remitente,
            d.color_email,
            d.IBAN,
            c.email as destinatario','clientes as c inner join delegaciones as d',
            'c.id_delegacion = d.id and c.id='.$reserva['id_cliente'],f);

            $mail = new phpmailer($datos[f][0]['remitente']);
            $mail->setFrom($datos[f][0]['remitente'],$datos[f][0]['delegacion']);
            $mail->AddAddress('teresaotero@soltury.com');
            $mail->Subject="Error al intentar cancelar Reserva";
            $textos = "La reserva con id ".$id_reserva_gastos." y con localizador ".$reserva['localizador']." esta dando un error al intentar cancalerla, revisarlo por si hay gastos o alg&uacute;n otro problema. El proveedor es ".$proveedor;
            // some proveedores traen mensajes en caso de error
            if(isset($datos_cancelacion['error_proveedor'])){
                $textos.= "<br><br> El proveedor dice: <br><br> " .$datos_cancelacion['error_proveedor'];
            }
            $mail->Body=body_email(t,"",$datos[f][0]['id_delegacion'],$textos);
            $mail->send();

    }
   

//}else if(isset($id_reserva_gastos)){
}else if(isset($id_reserva_cancelar)){

    // eliminamos la posibilidad de realizar confirmaciones 
    // sin session de clientes.
    if(!isset($_SESSION['id_zona_privada']) && !isset($_SESSION['Authenticated'])){
        script('alert("Sesion Caducada");location.reload()');
    }

    $data_reserva = $DB->select('cobrada,
                    total,
                    fecha_cobro,
                    bonos_gastados,
                    id_via_cobro,
                    fecha_pago_cliente,
                    localizador,
                    id_cliente,
                    id_comunicacion_tpv_reservas,historico','reservas_realizadas','id='.$id_reserva_cancelar);
    
    $reserva = $data_reserva[f][0];

            $datos=$DB->inner_join('c.nombre as cliente,
            d.nombre as delegacion,
            d.web,
            d.usuario,
            d.tpv_pass,
            c.id_delegacion,
            c.identificador,
            c.comentario,
            d.email as remitente,
            d.color_email,
            d.IBAN,
            c.email as destinatario','clientes as c inner join delegaciones as d',
            'c.id_delegacion = d.id and c.id='.$reserva['id_cliente'],f);

    $C = new control($_POST,$proveedor);
    $datos_cancelacion = $C->cancelar($reserva['id_cliente']);


    if($datos_cancelacion['cancelada']==0){



            $mail = new phpmailer($datos[f][0]['remitente']);
            $mail->setFrom($datos[f][0]['remitente'],$datos[f][0]['delegacion']);
            $mail->AddAddress('teresaotero@soltury.com');
            $mail->AddCC('pedrogil@soltury.com');
            $mail->Subject="Error al intentar cancelar Reserva";
            $textos = "La reserva con id ".$id_reserva_cancelar." y con localizador ".$reserva['localizador']." esta dando un error al intentar cancalerla, revisarlo por si hay gastos o alg&uacute;n otro problema. Si esta bien cancelada en el proveedor, cancelar en nuestro sistema.";
            // some proveedores traen mensajes en caso de error
            if(isset($datos_cancelacion['error_proveedor'])){
                $textos.= "<br><br> El proveedor dice: <br><br> " .$datos_cancelacion['error_proveedor'];
            }
            $mail->Body=body_email(t,"",$datos[f][0]['id_delegacion'],$textos);
            $mail->send();

    }else if($datos_cancelacion['cancelada']==1){

        #################################
        #
        # CAMBIAMOS LA RESERVA EN EL SISTEMA
        # CONOCER SI LA RESERVA ESTA PAGADA Y SI GASTABA BONOS => IF :TRUE
        # CANCELAMOS Y DEVOLVEMOS EL IMPORTE Y BONOS CORRESPONDIENTE EN ESE CASO 
        # AVISAMOS DE LAS CANCELACIONES A CLIENTE Y NOSOTROS


                $usuario_historico = isset($_SESSION['identificador_zona_privada']) ? $_SESSION['identificador_zona_privada'] : $_SESSION['nombre_usuario'];
                // hacemos los cambios pertinentes en reservas_realizadas
                $DB->openTable('reservas_realizadas');
                $DB->edit($id_reserva_cancelar);
                // cambiamos a estado cancelada
                $DB->ta['estado']=2;// 1 confirm ; 2 cls ; 3 facturada
                $DB->ta['fecha_cancelacion']=now();

                $nuevo_historico = sumar_historico_xml($reserva['historico'],$usuario_historico,'Cancelacion',$_SESSION['id_cancelacion']);
                $DB->ta['historico']=$nuevo_historico;

                $DB->update();

            // si esta cobrada 
            if($reserva['cobrada']==1){

                $importe_devolucion =($reserva['total']/100)-$datos_cancelacion['importe'];

                $comprobar_tpv = $DB->select('id','delegaciones','id_via_cobro_web='.$reserva['id_via_cobro']);
                if($comprobar_tpv[r]==0){
                    $txt_tpv = "en las pr&oacute;ximas 24h laborables";
                    $via_cobro = $DB->select('nombre','via_cobro','id='.$reserva['id_via_cobro']);
                    $txt_email = " y hay que hacer una devoluci&oacute;n de ".number_format($importe_devolucion,2,".","")." &euro;. El cliente pago por :".$via_cobro[f][0]['nombre'];
                }else{
                    $txt_tpv = "inmediatamente";
                    $txt_email = " y se va a hacer automaticamente la devoluci&oacute;n de ".number_format($importe_devolucion,2,".","")." &euro;, en breve te enviamos el resultado de la operaci&oacute;n.";
                }

                $datos_cancelacion['mensaje'].="<br><br>Te confirmamos que <b>vamos a realizarte una devoluci&oacute;n de ".number_format($importe_devolucion,2,".","")." &euro; ".$txt_tpv."</b>. Te enviaremos un email con la confirmaci&oacute;n de la devoluci&oacute;n.";

            }else if($reserva['cobrada']==0){

                $txt_email="<br><br>No hay que hacer ninguna devoluci&oacute;n por que no estaba pagada.";
            }

            // DEVOLUCION DE BONOS
            if($reserva['bonos_gastados']>0){

                // sacamos los datos
                $noches=$DB->select('noches','clientes','id='.$reserva['id_cliente']);
                $data_bonos=json_decode($noches[f][0]['noches'],true);

                // restamos los gastados
                $arr_noches['disponibles'] = $data_bonos['disponibles']+$reserva['bonos_gastados'];
                $arr_noches['usados'] = $data_bonos['usados']-$reserva['bonos_gastados'];
                $arr_noches['comprados'] = (int) $data_bonos['comprados'];

                $noches_json = json_encode($arr_noches);

                // modificamos en bbdd
                $DB->openTable('clientes');
                $DB->edit($reserva['id_cliente']);
                $DB->ta['noches']=$noches_json;
                $DB->update();

                $datos_cancelacion['mensaje'].="<br><br>Te confirmamos que <b>hemos devuelto a tu talonario ".$reserva['bonos_gastados']." bono/s</b> que hab&iacute;as consumido en esta reserva.";
            
            }


            $mail = new phpmailer($datos[f][0]['remitente']);
            $mail->setFrom($datos[f][0]['remitente'],$datos[f][0]['delegacion']);
            $mail->AddAddress('teresaotero@soltury.com');
            $mail->AddCC('pedrogil@soltury.com');

            $mail->Subject="Reserva cancelada";
            $textos = "La reserva:
                Id ".$id_reserva_cancelar."<br>
                Localizador ".$reserva['localizador']."<br>
                Cliente  ".utf8_encode($datos[f][0]['cliente'])."<br>";

            if($reserva['fecha_pago_cliente']!="0000-00-00"){

                $textos .=   "Fecha Pago ".formatear_fecha($reserva['fecha_pago_cliente'],m,i)."<br>";

            }else{

                $textos .= "No tiene la fecha de pago. Indicaselo a Pedro";
                
            }

            $textos .= "ha sido cancelada correctamente".$txt_email;
            $mail->Body=body_email(t,"",$datos[f][0]['id_delegacion'],$textos);
            $mail->send();

            // REALIZAMOS LA DEVOLUCION 
            if(isset($importe_devolucion) && $importe_devolucion>0 && $comprobar_tpv[r]>0){


                include('redsysHMAC256_API_PHP_4.0.2/apiRedsys.php');

                $miObj = new RedsysAPI;
                $action='https://sis.redsys.es/sis/realizarPago';//produccion
                $fuc=$datos[f][0]['usuario'];
                $kc =$datos[f][0]['tpv_pass'];
                $terminal="001";
                $moneda="978";
                //$trans="0"; // cobro 
                $trans="3"; // devolucion automatica 
                $amount=number_format($importe_devolucion,2,".","")*100;

                $DS_MERCHANT_ORDER = $DB->select('DS_MERCHANT_ORDER','comunicacion_tpv_reservas','id='.$reserva['id_comunicacion_tpv_reservas']);
                $id_operacion=$DS_MERCHANT_ORDER[f][0]['DS_MERCHANT_ORDER'];
    //echo $fuc . " ". $kc . " ".$amount. " " .$id_operacion;

                // aqui tengo que realizar una modificacion para las que vengan desde booking.

                if(isset($_SESSION['id_zona_privada']) && !isset($_SESSION['Authenticated'])){

                    $urlOK = "https://".$datos[f][0]['web']."/confirmacion_de_pagos.php?devolucion=1";
                    $urlKO = "https://".$datos[f][0]['web']."/confirmacion_de_pagos.php?devolucion=0";
                    $url_url="https://".$datos[f][0]['web'];

                }else if(isset($_SESSION['Authenticated'])){

                    $urlOK = "https://".$datos[f][0]['web']."/confirmacion_de_pagos.php?devolucion=2";
                    $urlKO = "https://".$datos[f][0]['web']."/confirmacion_de_pagos.php?devolucion=3";
                    $url_url="https://".$datos[f][0]['web'];

                }

                $miObj->setParameter("DS_MERCHANT_AMOUNT",$amount);
                $miObj->setParameter("DS_MERCHANT_ORDER",strval($id_operacion));
                $miObj->setParameter("DS_MERCHANT_MERCHANTCODE",$fuc);
                $miObj->setParameter("DS_MERCHANT_CURRENCY",$moneda);
                $miObj->setParameter("DS_MERCHANT_TRANSACTIONTYPE",$trans);
                $miObj->setParameter("DS_MERCHANT_TERMINAL",$terminal);
                $miObj->setParameter("DS_MERCHANT_MERCHANTURL",$url_url);
                $miObj->setParameter("DS_MERCHANT_URLOK",$urlOK);   
                $miObj->setParameter("DS_MERCHANT_URLKO",$urlKO);

                //Datos de configuración
                $version="HMAC_SHA256_V1";

                // Se generan los parámetros de la petición
                // $request = "";
                $params = $miObj->createMerchantParameters();
                $signature = $miObj->createMerchantSignature($kc);


                echo "<form name='frm' action='".$action."' method='POST' >\n";
                echo "<input type='hidden' name='Ds_SignatureVersion' value='".$version."'/>\n";
                echo "<input type='hidden' name='Ds_MerchantParameters' value='".$params."'/>\n";
                echo "<input type='hidden' name='Ds_Signature' value='".$signature."'/>\n";
                echo "</form>\n";

                script('$("[name=\'frm\']").submit();');

            
                exit();


            } // if(isset($importe_devolucion) && $importe_devolucion>0){

    } // }else if($datos_cancelacion['cancelada']==1){

    script("$('#info_confirmacion_cancelacion_body').html('".$datos_cancelacion['mensaje']."<br>La p&aacute;gina se actualizar&aacute; en 3 segundos');
        $('#info_confirmacion_cancelacion_body').removeClass('hide');
        $('#espera_confimacion_cancelacion').addClass('hide');setInterval('location.reload()',3000);");
 // 


//}else if(isset($id_reserva_cancelar)){
}else if(isset($id_reserva_info_cancelacion)){

    // eliminamos la posibilidad de realizar confirmaciones 
    // sin session de clientes.
    if(!isset($_SESSION['id_zona_privada']) && !isset($_SESSION['Authenticated'])){
        script('alert("Sesion Caducada");location.reload()');
    }

    $data_reserva=$DB->select('cobrada,
                    fecha_cobro,
                    total,
                    id_via_cobro,
                    fecha_cancelacion,
                    fecha_devolucion,
                    importe_devolucion,
                    id_via_cobro_devolucion','reservas_realizadas',
                    'id='.$id_reserva_info_cancelacion);

    $reserva=$data_reserva[f][0];


    list($f_clx,$h_clx)=explode(' ', $reserva['fecha_cancelacion']);
    list($f_cob,$h_cob)=explode(' ', $reserva['fecha_cobro']);
    list($f_dev,$h_dev)=explode(' ', $reserva['fecha_devolucion']);

    if($reserva['id_via_cobro']!=0){

        $data_cobro = $DB->select('dato','via_cobro','id='.$reserva['id_via_cobro']);
    }


    if($reserva['id_via_cobro_devolucion']!=0){

        $data_dev = $DB->select('dato','via_cobro','id='.$reserva['id_via_cobro_devolucion']);
    }



    $txt_pagada = $reserva['cobrada']==1 ? '<br>Habia sido pagada un importe de <b>'.number_format($reserva['total']/100,2,".","").'</b> el <b>'.formatear_fecha($f_cob,m,i).'</b> a las <b>'.$h_cob.'</b> <br class="hidden-xs">a trav&eacute;s de <b>'.$data_cobro[f][0]['dato'].'</b>.' : '<br><b>No</b> habia sido cobrada.';

    $txt_devuelto = $reserva['importe_devolucion']>0 ? '<br>Te devolvimos un importe de <b>'.number_format($reserva['importe_devolucion']/100,2,".","").' &euro;</b> el dia <b>'.formatear_fecha($f_dev,m,i).'</b> a las <b>'.$h_dev.'</b> <br class="hidden-xs">a trav&eacute;s de <b>'.$data_dev[f][0]['dato'].'</b>.' : '';

    $txt = "Esta reserva fue cancelada el <b>".formatear_fecha($f_clx,m,i)."</b> a las <b>".$h_clx."</b>.".$txt_pagada.$txt_devuelto;
                   
    script("$('#info_cancelacion_modal').modal('show');
        $('#info_cancelacion_body').html('".$txt."');");

// }else if(isset($id_reserva_info_cancelacion)){
}else if(isset($cupon_descuento)){


    $codigo = $DB->select('*','codigos_descuento','texto like "'.$cupon_descuento.'"');


    // que no exista, que este caducado o que no este asociado a este cliente.
    if($codigo[r]==0){

        alert("El Código introducido no existe");

    }else{

        $hoy = date('Y-m-d');
        $vigencia = comparar_fechas($hoy,$codigo[f][0]['vigencia'],m);


        if($codigo[f][0]['id_cliente']!="" && $codigo[f][0]['id_cliente']!=$_SESSION['id_zona_privada']){

            alert("El Código pertenece a otro cliente");

        }else if($vigencia<0){

            alert("El Código esta caducado");

        }else{

            $pvp = $DB->select('pvp','productos','id='.$id_producto);
            $precio_real = $pvp[f][0]['pvp']/100;
            $descuento =  $precio_real/100*$codigo[f][0]['porcentaje'];
            $precio_final = ($pvp[f][0]['pvp']/100)-$descuento;
            $precio_final=   str_replace('.', ',',$precio_final);
            //$precio_real=   str_replace('.', ',',$precio_real);

            script("$('#precio_final').html('".$precio_final."');$('#precio_real').html('".$precio_real.",00&euro;');$('[name=\"descuento\"]').val(".$codigo[f][0]['porcentaje'].")");
        }
    } // if($codigo[r]==0){

// }else if(isset($cupon_descuento)){ 
}else if(isset($cupon_descuento_cargador)){


    if($cupon_descuento_cargador=="facebook"){

        $precio_final = 29;

        noty('Codigo validado');
        script("$('[name=\"precio_final\"]').val('".$precio_final."');$('.precio_descuento_cargador').html('".$precio_final.",<sup>00</sup>  &euro;');");


    }else{

        $codigo = $DB->select('*','codigos_descuento','texto like "'.$cupon_descuento_cargador.'"');

    // precio_descuento_cargador

        // que no exista, que este caducado o que no este asociado a este cliente.
        if($codigo[r]==0){

            noty("El Código introducido no existe",'warning');

        }else{

            $hoy = date('Y-m-d');
            $vigencia = comparar_fechas($hoy,$codigo[f][0]['vigencia'],m);


            if($codigo[f][0]['id_cliente']!="" && $codigo[f][0]['id_cliente']!=$_SESSION['id_zona_privada']){

                noty("El Código pertenece a otro cliente",'warning');

            }else if($vigencia<0){

                noty("El Código esta caducado",'warning');

            }else if(strpos($cupon_descuento_cargador,'cargador')===false){

                noty("Este Código no es valido para este producto",'warning');

            }else{


                $pvp = $DB->select('pvp','productos','id='.$id_producto);
                $precio_real = $pvp[f][0]['pvp']/100;

                if($codigo[f][0]['porcentaje']>0){

                    $descuento =  $precio_real/100*$codigo[f][0]['porcentaje'];
                    $precio_final = ($pvp[f][0]['pvp']/100)-$descuento;
                    $precio_final = number_format($precio_final,2,'.','');

                }else if($codigo[f][0]['importe']>0){

                    $descuento =  $codigo[f][0]['importe'];
                    $precio_final = ($pvp[f][0]['pvp']/100)-$descuento;
                    $precio_final = number_format($precio_final,2,'.','');

                }

                list($eur,$cent) = explode('.',$precio_final);
                noty('Codigo validado');
                script("$('[name=\"precio_final\"]').val('".$precio_final."');$('.precio_descuento_cargador').html('".$eur.",<sup>".$cent."</sup>  &euro;');");
                

                // <?php echo $precio,<sup>00</sup>  &euro;
            }
        } // if($codigo[r]==0){

    }


// }else if(isset($cupon_descuento)){ 
}else if(isset($codigo_1)){ 



    if($codigo_1!="" && $codigo_2==""){


        $code_1 = $DB->select('*','validaciones_bdh','code like "'.$codigo_1.'"');
        
        if($code_1[r]==0){

            alert('El código 1 no existe');

        }else if($code_1[r]==1 && $code_1[f][0]['validado']==1){

            alert('El talon 1 ya esta validado');

        }else if($code_1[r]==1 && $code_1[f][0]['validado']==0){

            // valido el codigo a falta del id de cliente, que lo
            // meto luego de insertarlo.
            $DB->query('update validaciones_bdh set validado=1, fecha_val="'.now().'", ip="'.$_SERVER['REMOTE_ADDR'].'" where code like "'.$codigo_1.'"');

            // sacamos los datos de la delegacion xra reuso en cualquier web
            $comercio=$DB->select('nombre,tpv_pass,email,id_operadora_web,id_via_cobro_web,web','delegaciones','id='.$id_delegacion);

            $comprobar_cliente=$DB->select("id",
                        "clientes",
                        "email like '%".$email."%' and telefono=".$telefono." and nombre like '".$nombre."' and id_delegacion=".$id_delegacion);

            // SI NO EXISTE ENTRAMOS 
            if($comprobar_cliente[r]==0){

                // SE EJECUTA TODO EN ajax.php EN LA ZONA DE INSERT´S
                $url_cURL_redsys="https://".$comercio[f][0]['web']."/dulceria/functions/ajax.php";

                $post_cURL=array("accion"=>i,
                        "tabla_insertar"=>"clientes",
                        "nombre"=>$nombre,
                        "id_provincia"=>"",
                        "cp"=>"",
                        "telefono"=>$telefono,
                        "e_mail"=>$email,
                        "noches_personalizado"=>0,
                        "bonos_personalizados"=>$code_1[f][0]['nombre_archivo'],
                        "cabecera_personalizada"=>$code_1[f][0]['cabecera_personalizada'], 
                        "password"=>"",
                        "id_delegacion"=>$id_delegacion,
                        "id_operadora"=>$id_operadora,
                        "producto"=>$id_producto,
                        "estado_producto"=>4,
                        "nopublicidad"=>1,
                        "codigo_1"=>$codigo_1,
                        "re_login"=>$re_login);

      
                // operacion curl.
                $ch = curl_init();  
                curl_setopt($ch, CURLOPT_URL, $url_cURL_redsys);  
                curl_setopt($ch, CURLOPT_POSTFIELDS,$post_cURL);
                curl_exec($ch);    
                curl_close($ch); 

            }else if($comprobar_cliente[r]>0){ // eof if($comprobar_cliente[r]==0){

                // si el cliente existe añadimos los bonos a su cuenta 
                // SE EJECUTA TODO EN ajax.php EN LA ZONA DE INSERT´S
                $url_cURL_redsys="https://".$comercio[f][0]['web']."/dulceria/functions/ajax.php";
                
                $post_cURL=array("accion"=>"i",
                        "tabla_insertar"=>"envios",
                        "id_cliente_envios"=>$comprobar_cliente[f][0]['id'],
                        "noches_personalizado_envios"=>0,
                        "bonos_personalizados"=>$code_1[f][0]['nombre_archivo'],
                        "cabecera_personalizada"=>$code_1[f][0]['cabecera_personalizada'], 
                        "id_delegacion_envios"=>$id_delegacion,
                        "id_operadora_envios"=>$id_operadora,
                        "producto_envios"=>$id_producto,
                        "estado_producto_envios"=>4,
                        "codigo_1_envios"=>$codigo_1,
                        "re_login_envios"=>$re_login);

                $ch = curl_init();  
                curl_setopt($ch, CURLOPT_URL, $url_cURL_redsys);  
                curl_setopt($ch, CURLOPT_POSTFIELDS,$post_cURL); 
                curl_exec($ch);    
                curl_close($ch); 

            
            } // eof }else if($comprobar_cliente[r]>0){
            
        } // }else if($code_1[r]==1 && $code_1[f][0]['validado']==0){

    }else if(isset($codigo_1) && isset($codigo_2)){  // if($codigo_1!="" && $codigo_2==""){


        if($codigo_1==$codigo_2){

            alert('Los 2 talones no pueden ser iguales');

        }else if($codigo_1!=$codigo_2){


            $code_1 = $DB->select('*','validaciones_bdh','code like "'.$codigo_1.'"');


            if($code_1[r]==0){

                alert('El código 1 no existe');

            }else if($code_1[r]==1 && $code_1[f][0]['validado']==1){

                alert('El talon 1 ya esta validado');

            }else if($code_1[r]==1 && $code_1[f][0]['validado']==0){


                $code_2 = $DB->select('*','validaciones_bdh','code like "'.$codigo_2.'"');
                

                if($code_2[r]==0){

                    alert('El código 2 no existe');

                }else if($code_2[r]==1 && $code_2[f][0]['validado']==1){

                    alert('El talon 2 ya esta validado');

                }else if($code_2[r]==1 && $code_2[f][0]['validado']==0){

                    // valido el codigo a falta del id de cliente, que lo
                    // meto luego de insertarlo.
                    $DB->query('update validaciones_bdh set validado=1, fecha_val="'.now().'", ip="'.$_SERVER['REMOTE_ADDR'].'" where code like "'.$codigo_1.'" OR code like "'.$codigo_2.'"');

                    $id_producto=67;
                    // sacamos los datos de la delegacion xra reuso en cualquier web
                    $comercio=$DB->select('nombre,tpv_pass,email,id_operadora_web,id_via_cobro_web,web','delegaciones','id='.$id_delegacion);

                    $comprobar_cliente=$DB->select("id",
                        "clientes",
                        "email like '%".$email."%' and telefono=".$telefono." and nombre like '".$nombre."' and id_delegacion=".$id_delegacion);

                    // SI NO EXISTE ENTRAMOS 
                    if($comprobar_cliente[r]==0){

                        // SE EJECUTA TODO EN ajax.php EN LA ZONA DE INSERT´S
                        $url_cURL_redsys="https://".$comercio[f][0]['web']."/dulceria/functions/ajax.php";

                        $post_cURL=array("accion"=>i,
                                "tabla_insertar"=>"clientes",
                                "nombre"=>$nombre,
                                "id_provincia"=>"",
                                "cp"=>"",
                                "telefono"=>$telefono,
                                "e_mail"=>$email,
                                "noches_personalizado"=>0,
                                "bonos_personalizados"=>$code_1[f][0]['nombre_archivo'],
                                "cabecera_personalizada"=>$code_1[f][0]['cabecera_personalizada'], 
                                "password"=>"",
                                "id_delegacion"=>$id_delegacion,
                                "id_operadora"=>$id_operadora,
                                "producto"=>$id_producto,
                                "estado_producto"=>4,
                                "nopublicidad"=>1,
                                "codigo_1"=>$codigo_1,
                                "codigo_2"=>$codigo_2,
                                "re_login"=>$re_login);
 
                        // operacion curl.
                        $ch = curl_init();  
                        curl_setopt($ch, CURLOPT_URL, $url_cURL_redsys);  
                        curl_setopt($ch, CURLOPT_POSTFIELDS,$post_cURL);
                        curl_exec($ch);    
                        curl_close($ch); 

                    }else if($comprobar_cliente[r]>0){ // eof if($comprobar_cliente[r]==0){

                        // si el cliente existe añadimos los bonos a su cuenta 
                        // SE EJECUTA TODO EN ajax.php EN LA ZONA DE INSERT´S
                        $url_cURL_redsys="https://".$comercio[f][0]['web']."/dulceria/functions/ajax.php";
                        
                        $post_cURL=array("accion"=>"i",
                                "tabla_insertar"=>"envios",
                                "id_cliente_envios"=>$comprobar_cliente[f][0]['id'],
                                "noches_personalizado_envios"=>0,
                                "bonos_personalizados"=>$code_1[f][0]['nombre_archivo'],
                                "cabecera_personalizada"=>$code_1[f][0]['cabecera_personalizada'], 
                                "id_delegacion_envios"=>$id_delegacion,
                                "id_operadora_envios"=>$id_operadora,
                                "producto_envios"=>$id_producto,
                                "estado_producto_envios"=>4,
                                "codigo_1_envios"=>$codigo_1,
                                "codigo_2_envios"=>$codigo_2,
                                "re_login_envios"=>$re_login);

                        $ch = curl_init();  
                        curl_setopt($ch, CURLOPT_URL, $url_cURL_redsys);  
                        curl_setopt($ch, CURLOPT_POSTFIELDS,$post_cURL); 
                        curl_exec($ch);    
                        curl_close($ch); 

                    
                    } // eof }else if($comprobar_cliente[r]>0){
                    
                } // }else if($code_2[r]==1 && $code_2[f][0]['validado']==0){

            }

        }

    }


// }else if(isset($codigo_1)){ 
}else if(isset($id_cliente_apuntarse)){

    $DB->query('update clientes set nopublicidad=1 where id='.$id_cliente_apuntarse);

    $mensaje_alta = $DB->errorQuery=="" ? 'SI' : 'NO';
    $type= $DB->errorQuery=="" ? 'success' : 'warning';

    noty('Tu alta se '.$mensaje_alta.' ha realizado',$type);

//}else if(isset($id_cliente_apuntarse)){
}else if(isset($texto_comentario)){

    $provincia = $DB->select('nombre','_provincias','id='.$provincia_comentario);

    $id_comentario = $DB->query('insert into opiniones (nombre_comentario,email_comentario,estrellas_comentario,fecha,texto_comentario) values ("'.$nombre_comentario.' ('.$provincia[f][0]['nombre'].')","'.$email_comentario.'","'.$estrellas_comentario.'","'.date('Y-m-d').'","'.utf8_decode($texto_comentario).'")');

                

     // el cliente si tiene email en la bbdd
                

                $datos=$DB->select(' 
                nombre,
                web,
                color_email,
                email',
                'delegaciones','id='.$_SESSION['id_web']);

  

                $mail = new phpmailer($datos[f][0]['email']);
                $mail->setFrom($datos[f][0]['email'],$datos[f][0]['delegacion']);
                $mail->AddAddress('pedrogil@soltury.com');



                $texto_email="Hola Bixetoncillo,
                <br><br>
                Hemos recibido el siguiente comentario de un cliente:
                <br><br>
                ".$texto_comentario."
                <br><br>
                Si quieres publicarlo clicka abajo: ";

                $texto_email.='<div style="width:100%; text-align: center; padding:10px 0 10px 0">
                        <a href="https://'.$datos[f][0]['web'].'/validar_comentario/'.$id_comentario.'"
                            style="font-family: Playfair Display,serif; border-radius: 1px; font-weight: 600;border-width: 0;
                            padding: 8px 25px; transition: color .5s , border-color .5s , background-color .5s; font-size: 16px;
                            padding: 10px 35px; color: #fff; background-color: #'.$datos[f][0]['color_email'].';
                            text-decoration:none;">
                            CONFIRMAR EL COMENTARIO
                        </a>
                        <br><br>
                    </div>';

                $texto_email.="Gracias por confiar en nosotros, un saludo.";

                // SALUDO Y BODY EMAIL, SEGUN TABLA

                $mail->Subject="Contacto Recibido (" .$datos[f][0]['nombre'].")"; 
                $mail->Body=body_email(t,"",$_SESSION['id_web'],$texto_email);

                    
                if($mail->send()){

                    // echo $mail->Body;
                    alert('El formulario ha sido enviado correctamente. Gracias por tus comentarios.');
                    script("location.reload();");

                }else{
                    alert('No se ha podido enviar el formulario, intentalo de nuevo mas tarde.');
                }


}


/*

/* AQUI TENGO LA MODIFICACION EN LA BBDD DE LOS CODIGOS Y LA POSIBILIDAD DE PONER EL NOMBRE DEL 
ARCHVO CUANDO HAYAMOS PUESTO ALGUN BONO PERSONALIZADO.
    for($x=1;$x<=$talonario;$x++){

    $DB->query('update validaciones_bdh set validado=1, fecha_val="'.now().'", ip="'.$_SERVER['REMOTE_ADDR'].'", id_cliente="'.$id_cliente.'" where code like "'.$_POST['talon'.$x].'"');

}

$DB->query('select nombre_archivo from validaciones_bdh where code like "'.$codigo_1.'"');

$nombre_archivo = $DB->fetch();

if($nombre_archivo['nombre_archivo']==""){

    $nombre_archivo['nombre_archivo']="Bonos_Descarga.pdf";
}


*/


 ?>
