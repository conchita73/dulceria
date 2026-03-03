<?php 

function ftp_get_contents ($conn_id, $filename) { 
    //Create temp handler: 
    $tempHandle = fopen('php://temp', 'r+'); 
    
    //Get file from FTP: 
    if (@ftp_fget($conn_id, $tempHandle, $filename, FTP_ASCII, 0)) { 
        rewind($tempHandle); 
        return stream_get_contents($tempHandle); 
    } else { 
        echo "false"; 
    } 
} 



$local_file = '20181111-voz-salientes-3345.csv'; //Nombre archivo en nuestro PC
$server_file = '20181111-voz-salientes-3345.csv'; //Nombre archivo en FTP


// Establecer la conexión
$ftp_server='cdrs.airenetworks.es';
$ftp_user_name='user3345';
$ftp_user_pass='Umbrete2';
$ruta="/t-movil/";
$conn_id = @ftp_connect($ftp_server,21);



        if($conn_id){   

             # Realizamos el login con nuestro usuario y contraseña
            if(@ftp_login($conn_id,$ftp_user_name,$ftp_user_pass)){
                
                # Canviamos al directorio especificado
                if(@ftp_chdir($conn_id,$ruta)){

                    # Subimos el fichero
                    if(@ftp_get($conn_id,$local_file,$server_file,FTP_BINARY)){
                        $mensaje.= "Fichero bajado correctamente en ";
                    }else{
                        $mensaje.= "No ha sido posible bajar el fichero";
                    }

                    $pepo = ftp_get_contents($conn_id,$server_file);

                    var_dump($pepo);

                }else{
                    $mensaje.= "No existe el directorio especificado";
                }

            }else{ // if(@ftp_login($conn_id,$user,$password)){
                $mensaje.= "El usuario o la contraseña son incorrectos";
            }
            # Cerramos la conexion ftp
            ftp_close($conn_id);
        }else{
            $mensaje.= "No ha sido posible conectar con el servidor";
        }

echo $mensaje;

?>