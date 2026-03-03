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


# subir_multy_imagen_producto
# borrar_imagen_insert

if(isset($subir_multy_imagen_producto)){


    if(isset($_FILES)){

    	try {

            $dir = '../img/temporal/';
            $fichero_subido = $dir . basename($_FILES['file']['name']);

            #cogemos la extensión del archivo
            $info = new SplFileInfo($nombre);
            $ext = $info->getExtension();
            list($na,$ea) = explode('.'.$ext, $nombre);

            $cnf = $DB->select('foto','imagenes_pre_productos',' foto like "'.$nombre.'" and id_imagen like "'.$id_imagenes_productos.'"');
            $new_name = $cnf[r] > 0 ? $na.'_2.'.$ext : $nombre;

            # Subimos el fichero
            if(move_uploaded_file($_FILES["file"]["tmp_name"],$fichero_subido)){

                list($width, $height, $type, $attr) = getimagesize($fichero_subido);

                // alert($width.' '.$height);
                if($width>5000  || $height>5000){

                    throw new Exception('La imágen no puede superar los 2000px de alto ni los 2000px de ancho');

                }else{


                	$DB->query('insert into imagenes_pre_productos (id_imagen,foto) values ("'.$id_imagenes_productos.'","'.$new_name.'")');

                    // fotos_subidas este es el div sobre el que vamos a tirar la foto
                    // script('$("#fotos_subidas").append(\'<div class="alert alert-success alert-dismissible fade show mt-3 col-4"  role="alert" id="alert_'.time().'"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><img src="'.$imge.'" alt="" style="max-width : 120px;"><input type="hidden" foto="'.$new_name.'" name="foto_sum"></div>\');');

                }

            }else{

                throw new Exception("No ha sido posible subir el fichero");
               
            }


        } catch (Exception $e) {
            
            
        }

    } // if(isset($_FILES)){

}else if(isset($borrar_imagen_insert)){

    $DB->query('delete from imagenes_pre_productos where id_imagen like "'.$id_imagenes_productos.'" and foto like "'.$name.'"');
    notify('Imagén borrada Correctamente','success');

}

?>
