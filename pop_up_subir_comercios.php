<?php include 'inc/inc_start.php';  
	
	$title_heading="Subir Comercios";


// SI HEMOS ECHO EL button:
if(isset($_POST['accion'])){

    //EN CASO DE QUE HAYAS METIDO ARCHIVO VA POR AQUI
    if (strlen($_FILES['upload']['name']) > 0){ 

    	$clean = isset($_SESSION['ip_admin']) ? array("\r\n", "\n", "\r","\t","\0","\x0B",'"','/') : '"';
        //ABRIMOS EL ARCHIVO
        $file = fopen($_FILES['upload']['tmp_name'], "r");

        // LO ENCERRAMOS EN LA $csv
        
        $csv = array();

        while (!feof($file)) {

            $csv.=fread($file,$_FILES['upload']['size']);

        }
        // LO CERRAMOS
        fclose($file);
                    
        //HACEMOS UN ARRAY -> ACESSO CADA UNO DE LAS FILAS
        $array_csv = explode("\n",$csv);
        $r = count($array_csv);
        // DATOS DE LA PRIMERA COLUMNA
        $columnas = explode(";",$array_csv[0]);
        // $f = count($columnas);

        
        $subidos = 0;


        for ($i=1; $i < $r; $i++) { 
        	
        	$table = isset($_SESSION['ip_admin']) ? 'comercios_copy' : 'comercios';
        
        	$f[$i] = 0;
        	$q = 'insert into '.$table.' (id_sector,
        								id_usuario,
        								id_delegacion,
        								nombre_comercial,
        								telefonos,
        								cp,
        								direccion,
        								id_provincia,
        								id_comunidad,
        								poblacion,
        								web,
        								facebook,
        								instagram,
        								otros,
        								mybussines) values ('.$id_sector.','.$id_usuario.','.$_SESSION['id_delegacion'];
        	$insert[$i] = explode(';',$array_csv[$i]);
        	// echo "<br>".$array_csv[$i];

        	if($insert[$i][0]!=""){

		        // echo "<br>id _sector".$id_sector;

		        foreach ($columnas as $value) {


		        	$val[$value] = $insert[$i][$f[$i]];
		        	// echo "<br>" .$value . " tiene un valor de ". str_replace($clean,'',$val[$value]);

		        	if($value == 'direccion'){

		        		
		        		$cp = preg_match('/,\s[0-9]{5}/',str_replace($clean,'',utf8_decode(trim($insert[$i][$f[$i]]))),$m);
		        		//echo "<br>".$m[0];
		        		$cp = str_replace(', ','',$m[0]);
		        		$cnstl = $cp[0].$cp[1];
		        		// echo "<br> +++ ".$cnstl;

						// $DB->ta['cp']=$cp;
						if($cnstl!=""){

							$id_geo = $Q->select('id,comunidad_id','provincias','cp = '.$cnstl);

						}else{

							$id_geo[f][0]['id'] = 0;
							$id_geo[f][0]['comunidad_id'] = 0;
						}
						

						$arr = preg_split('/,\s[0-9]{5}/',$insert[$i][$f[$i]]);
						// $DB->ta['direccion'] = $DB->textCheck($arr[0]);
						$q.=','.$cp.',"'.utf8_decode($arr[0]).'",'.$id_geo[f][0]['id'].','.$id_geo[f][0]['comunidad_id'];
		        		// echo "<br>id direccion".$arr[0];

						if(strpos($arr[1],', ')!==false){

						    list($pob,$prov) = explode(', ',$arr[1]);
							$q.=',"'.$DB->textCheck($pob).'"';
						    // echo '<br>la poblacion es'. $pob;

						}else{

							$q.=',"'.utf8_decode($arr[1]).'"';

						    // echo '<br>la poblacion es'. $arr[1];
						}

		        	}else if($value == 'telefonos'){

		        		$ar = array(":"," ",'�','?');
		        		$insert[$i][$f[$i]] = str_replace($ar,'',$insert[$i][$f[$i]]);
		        		$q.=',"'.str_replace($clean,'',utf8_decode(trim($insert[$i][$f[$i]]))).'"';

		        	}else{

		        		$q.=',"'.str_replace($clean,'',utf8_decode(trim($insert[$i][$f[$i]]))).'"';

		        	}
		        	
		         	$f[$i]++;
		         } // foreach ($columnas as $value) {

	        	$q.=')';

	        	// if(isset($_SESSION['ip_admin'])){

	        	// 	//echo "<br>".$q;

	        	// }else{

					$DB->query($q);

	        	// }
	          	

	         } // if($insert[$i][0]!=""){


        } // for ($i=1; $i <= $r; $i++) { 

    } // if (strlen($_FILES['upload']['name']) > 0){ 

    $msj = "Has subido los comercios correctamente, vamos a actualizar";

} // if(isset($_POST['accion'])){


?>
<!DOCTYPE html>
<html lang="es">
<head>

	<?php include 'inc/inc_head.php'; ?>

</head>

<?php 

$imga = isset($_SESSION['ip_admin']) ? '' : 'img/bg-abstract2.png';

 ?>
<body data-background-full="image" data-image="<?php echo $imga ?>">
	<div class="wrapper overlay-sidebar">
		<div class="container">
			<div class="row text-center">
				<div class="col align-self-center">
						<img src="img/logo_admin_2.png" alt="navbar brand" class="navbar-brand mt30">
						<h2 class="text-white mt-3 fw-bold"><?php echo $title_heading; ?></h2>
				</div>
							
			</div>
		</div>

		<div class="main-panel"  style="margin-top: -60px !important;">
			<div class="content content-scroll scrollbar-inner" >
				<div class="page-inner">
					<div class="row row-card-no-pd" style="height: 400px;">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<div class="card-head-row">
										<h4 class="card-title">Selecciona un CSV</h4>
										
									</div>
								</div>
								<form method="post" enctype="multipart/form-data" action="" class="" id="subir_csv_form" method="post" accept-charset="utf-8">
									<input type="hidden" name="accion" value="1">
								<div class="card-body">
									<div class="row">
											<div class="col-6 mb-3">
											<label for="">Elije un sector</label>
												<select name="id_sector" class="selectpicker" data-live-search="true" title="Elige un sector" data-style="btn-secondary">
													<?php 

														$DB->query('select sector,id from sectores');
														while ($d = $DB->fetch()) {
															echo '<option value="'.$d['id'].'">'.utf8_encode($d['sector']).'</option>';
															
														}

													?>
												</select>
											</div>
											<div class="col-6 mb-3">
												<label for="">Elije un Usuario (No obligatorio)</label>
												<select name="id_usuario" class="selectpicker" data-live-search="true"data-style="btn-secondary">
													<option value="0" selected>Ninguno</option>
													<?php 

														$DB->query('select nombre,id from usuarios');
														while ($d = $DB->fetch()) {
															echo '<option value="'.$d['id'].'">'.utf8_encode($d['nombre']).'</option>';
															
														}

													?>
												</select>
											</div>
											<div class="col-12 mb-3">
												<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
			                                	<input type="file" name="upload" id="upload" class="filestyle float-right"  data-text="<span class='btn-label'>
															<i class='fas fa-file-csv fa-2x'></i>
														</span> Seleccionar CSV" 
												data-btnClass="btn-secondary" data-badge="true"
												data-input="false">
											</div>
												
                                			
										</div> <!-- <div class="col"> -->
									</div> <!-- <div class="row"> -->
									<div class="card-footer">
								    	<button type="button" class="btn btn-success float-right mt-4" id="subir_csv_btn">Subir Archivo</button> 
								 	</div>
								</form>

								</div> <!-- <div class="card-body"> -->
							</div> <!-- <div class="card"> -->
						</div> <!-- <div class="col-md-12"> -->
					</div> <!-- <div class="row row-card-no-pd"> -->
				</div>
				<?php include 'inc/inc_footer.php'; ?>
			</div>
		</div>
	</div>
		
	<?php include 'inc/inc_javascript.php'; ?>

	<script>
		$(document).ready(function(){

			$(document).on('change','input[type="file"]',function(){
				
				var fileName = this.files[0].name;
				var fileSize = this.files[0].size;

				if(fileSize == 0){

					
					<?php notify_js('El archivo esta vacio','warning') ?>
					this.value = '';
					this.files[0].name = '';
					return false;

				}else if(fileSize > 500000){

					
					<?php notify_js('El archivo no puede superar los 500k','warning'); ?>
					this.value = '';
					this.files[0].name = '';
					return false;

				}else{
					// recuperamos la extensión del archivo
					var ext = fileName.split('.').pop();

					if(ext!='csv'){

						
						<?php notify_js('El archivo tiene que ser un CSV','warning'); ?>
						this.value = ''; // reset del valor
						this.files[0].name = '';
						return false;

					}else{

						
						<?php notify_js('Archivo Validado para subirlo','success'); ?>
						
					}
				}

			}); // $(document).on('change','input[type="file"]',function(){

			$('#subir_csv_btn').click(function(){

				
				if($('[name="upload"]').val() == ""){

					<?php notify_js('Tienes que subir un archivo Valido','warning'); ?>

				}else if($('[name="id_sector"]').val() == ""){

					<?php notify_js('Tienes que seleccionar un sector','warning'); ?>

				}else{

					$(this).parents('form').submit();
				}


			});


			<?php 

				if(isset($msj)){

					notify_js($msj,'success');
					if(!isset($_SESSION['ip_admin'])){

						echo "setTimeout('opener.location.reload();location.href=\"https://ckcreativos.com/dulceria/pop_up_subir_comercios.php\"',2000);";
					 
					}
					
				} 
			?>
		});// $(document).ready(function(){	
	</script>
</body>
</html>