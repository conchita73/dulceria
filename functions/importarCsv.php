<?php @session_start();
error_reporting(E_ALL);

include('includes/variables.php');
$DB=new DataAccess();

// SI HEMOS ECHO EL SUBMIT:
if(isset($_POST['accion'])){

      
      //EN CASO DE QUE HAYAS METIDO ARCHIVO VA POR AQUI
    if (strlen($_FILES['upload']['name']) > 0){ 

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

               //DE LA PRIMERA LINEA VAMOS A SACAR
                // SI ES UN ARCHIVO U OTRO PARA PREPARAR LAS
                //CADENAS PARA EL INSERT
                $columnas = explode(";",$array_csv[0]);
                 



        if($columnas[0][0]==6 || $columnas[0][0]==7){

        $tipo="movil";


            }else if($columnas[2]=="ANOTACIONES"){

        $tipo="fijo";


            }

$mes=$_POST['mes'];




//ESTRUCTURA DE LA TABLA `movildestino`

//destino (los que tengan esto vacio que siempre estan a 0 los podemos no insertar)
//telefono
//doc.titular (este dato lo podemos eliminar si quieres)
//titularlinea
//duracion
//importeconsumo
//mes
//IdFactura

$iteracciones=count($array_csv);

            $x=1;
    while($x<$iteracciones-1){

// SERIALIZAMOS LOS DATOS PARA EL INSERT
            $campos = explode(";",$array_csv[$x]);


            
            

            if($tipo=="fijo"){

                $destino=$campos[0];
                $telefono=$campos[1];
                $doctitular="";
                $titularlinea=utf8_decode($campos[2]);
                $duracion=$campos[3];
                $importeconsumo=str_replace(",",".",$campos[6]);
                $IdFactura="NULL";


            $DB->query('insert into movildestino (destino,
                telefono,
                doctitular,
                titularlinea,
                duracion,
                importeconsumo,
                mes,
                IdFactura) values ("'.$destino.'",
                '.$telefono.',
                "'.$doctitular.'",
                "'.$titularlinea.'",
                "'.$duracion.'",
                '.$importeconsumo.',
                '.$mes.',
                "'.$IdFactura.'")');



            }else if($tipo=="movil"){


                if($campos[1]!="TARIFA BASICA ION" 
                    && $campos[1]!="SOTA 3G/4G"
                    && $campos[1]!="REY - 3G/4G"
                    && $campos[1]!="POKER - 3G/4G"
                    && $campos[1]!="MINI SOTA"
                    && $campos[1]!="CABALLO - 3G/4G" ){

                $destino=$campos[1];
                $telefono=$campos[0];
                $doctitular="";
                $titularlinea="";
                $duracion="";
                $importeconsumo=str_replace(",",".",$campos[2]);
                $IdFactura="NULL";

                $DB->query('insert into movildestino (destino,
                telefono,
                doctitular,
                titularlinea,
                duracion,
                importeconsumo,
                mes,
                IdFactura) values ("'.$destino.'",
                '.$telefono.',
                "'.$doctitular.'",
                "'.$titularlinea.'",
                "'.$duracion.'",
                '.$importeconsumo.',
                '.$mes.',
                "'.$IdFactura.'")');

             }

        }

     

  $x++; 
  //END WHILE 
    }

echo "<script>alert('Importación OK, Puedes Volver a introducir mas archivos.')</script>";



                }else{
                    // SI NO HAS METIDO NINGUN ARCHIVO
                echo "<script>alert('No has subido ningún archivo')</script>";

                }
        

}




 ?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Importar Csv</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>


<div class="anchoycentrado">
    
    <img src="img/logo-net.png" alt="">

</div>

    <section class="anchoycentrado">
 <br><br>   
<span> <b>IMPORTAR CVS</b> </span>




<br><br>
*importante : El csv debe tener las columnas separadas por punto y coma. (-> ; <-)

<br><br>

<form  enctype="multipart/form-data" action="" method="post" accept-charset="utf-8">

Mes al que vamos a asociar la facturaci&oacute;n:
<br>
<select name="mes">

<?php 

for($x=1;$x<=12;$x++){
    echo '<option value="'.$x.'">'.$x.'</option>';
}

 ?>
    
</select>

<br><br>
   <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
  <input type="file" name="upload" id="upload" class="input"/>
<br><br>
<input type="hidden" name="accion" value="1">
  <input type="submit" name="insert" value="Importar Csv">


</form>


    </section> 



</body>
</html>