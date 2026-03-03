<?php include 'inc/inc_start.php';  
$title_heading="Productos";
$table = 'productos_web';
$form = 'productos_web';
$fb = isset($id) ? 'Modificar Producto' : 'Crear Producto';
$edt = isset($id) ? '_editar' : '';
$dn = isset($id) ? '' : 'd-none';
$accion = isset($id) ? u : i;
$tbl = isset($id) ? 'editar' : 'insertar';
$id_imagenes_productos = isset($id) ? $id : time();

?>
<!DOCTYPE html>
<html lang="es">
<head>

	<?php include 'inc/inc_head.php'; ?>
	<link rel="stylesheet" href="js/plugin/dropzone/basic.css">
	<link rel="stylesheet" href="js/plugin/dropzone/dropzone.css">

</head>
<body data-background-full="image" data-image="img/bg-abstract2.png">
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
					<div class="row row-card-no-pd">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<div class="card-head-row">
										<h4 class="card-title">Datos Descriptivos</h4>
									</div>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-12">
											<?php 

												echo '<form id="'.$form.$edt.'_form" method="post">
			 											
			 											<input type="hidden" name="tabla_'.$tbl.'" value="'.$table.'">
			 											<input type="hidden" name="accion" value="'.$accion.'">';

			 											
			 									if(isset($id)){

			 										echo '<input type="hidden" name="fotos'.$edt.'" value="">
			 												<input type="hidden" name="id_editar" value="'.$id.'">
			 												<input type="hidden" name="tabla_editar" value="'.$table.'">';

			 									}else{

			 										echo '<input type="hidden" name="id_imagenes_productos" value="'.$id_imagenes_productos.'">';

			 									}

			 									

												pintar_campos($form,$accion,'',$table);
												// pintar_campos('productos_cbd',$accion,'',$table);
			 									

			 								?>
			 								</form>
			 								<hr>
			 								<label>Ingredientes</label>
			 								<input type="text" name="ingrediente" class="form-control" value="" placeholder="Introduce un texto">
			 								<button type="button" class="btn btn-warning mt-2 mb-2 float-right" id="crt_sum">Sumar Ingredientes</button>
			 								<div id="div_crt" class="row col-12" style="clear: both; margin-top: 20px;">
			 									
			 								</div>
			 								<?php if(isset($id)){ ?>
			 								<div class="row col-12" style="clear: both; margin-top: 20px;">
			 									<?php 

													# si es una edicion sacamos las fotos
													

													 	$arf = $DB->select('ingredientes','productos_web','id = '.$id);
													  	
													  	if($arf[f][0]['ingredientes']!='' && $arf[f][0]['ingredientes']!='[]'){

													  		echo '<div class="mt-2 col-12 text-center mb-2">Ingredientes Anteriores:</div>';

														 	$arr_f = json_decode(utf8_encode($arf[f][0]['ingredientes']),true);

														 	foreach ($arr_f as $k) {

														 		echo '<div class="alert alert-info alert-dismissible fade show col-4"  role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
														 				Ingrediente : '.$k.'<input type="hidden" value="'.$k.'" name="crt_sum"></div>';

															} // foreach ($arr_f as $k) {
													 		
													  	} // if($arf[f][0]['fotos']!='' && $arf[f][0]['fotos']!='[]'){
													  	

												?>
			 								</div>
			 								<?php } ?>
			 							</div>
			 							<div class="col-12 ">
			 								<label class="mt-3">Seleccionar Fotos<span class="text-danger">*</span>:</label>
			 								<div class="row">
			 									<small class="text-danger col-12 text-center">*Lo ideal es que la imágen sea de un mínimo de 400px de ancho y lo mas cuadrada posible</small>
			 								</div>
											<form  class="dropzone" id="dropform">
											  	<div class=" col-12">
											    	<input name="file" type="file" multiple class="d-none"/>
											  	</div>
											</form>
											
										</div> <!-- <div class="col"> -->
										<div class="col-12 row" id="fotos_subidas">
											<div class="col-12 text-center">
												<small class="text-danger <?php echo $dn ?>">*Las fotos se actualizarán sólo al modificar el producto completo</small>
											</div>	
											<?php 

												# si es una edicion sacamos las fotos
												if(isset($id)){


												 	$arf = $DB->select('fotos','productos_web','id = '.$id);
												  	
												  	if($arf[f][0]['fotos']!='' && $arf[f][0]['fotos']!='[]'){

													 	$arr_f = json_decode($arf[f][0]['fotos'],true);

													 	foreach ($arr_f as $k) {

													 		$i = explode('/',$k);
													 		$in = (count($i)-1);

													 		echo '<div class="alert alert-info alert-dismissible fade show mt-3 col-4"  role="alert" id="alert_b_'.time().'"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><img src="'.$k.'" alt="" style="max-width : 120px;"><input type="hidden" foto="'.$i[$in].'" name="foto_sum"></div>';

														} // foreach ($arr_f as $k) {
												 		
												  	} // if($arf[f][0]['fotos']!='' && $arf[f][0]['fotos']!='[]'){
												  	
												}

											?>
										</div>
									</div> <!-- <div class="row"> -->
								</div> <!-- <div class="card-body"> -->
								<div class="card-footer">
								    <button type="button" class="btn btn-success pull-right" id="btn_form<?php echo $edt ?>">
                                	<b><?php echo $fb ?></b>
                            		</button>  
                            		<input type="hidden" name="<?php echo $form.$edt ?>" value="1">
								 </div>
							</div> <!-- <div class="card"> -->
						</div> <!-- <div class="col-md-12"> -->
						<!-- <form enctype="multipart/form-data" id="formuploadajax" method="post" class="d-none">
							<input  type="file" data-input="false" id="upload" name="upload" />
						</form> -->
					</div> <!-- <div class="row row-card-no-pd"> -->
				</div>
				<?php include 'inc/inc_footer.php'; ?>
			</div>
		</div>
	</div>

		
	<?php $no_chat=1; $no_agenda = 1; include 'inc/inc_javascript.php'; ?>

	<script>


		$(document).ready(function(){


			// fotos_producto productos_form_form
			$('#btn_form').click(function(e){
				
				// esta variable es para que no se evaluen las fotos si es edicion

				if($('#nombre_producto').val() == ''){

					<?php notify_js('Tienes que asignar un nombre al producto','danger') ?>

				}else{


					var b = 0;
					$('[name="crt_sum"]').each(function(){
	
						// alert($(this).val());
						$('#descripcion').before('<input type="hidden" name="ingredientes_productos['+b+']" value="'+$(this).val()+'">');

						b++;
					});
					
					$('#<?php echo $form.$edt ?>_form').form_validator_ajax();
				}
				
			});	


			$('#url_foto').click(function(e){
			
				var url = $(this).parent('div').siblings('input[type="text"]').val();

				if(url == ''){

					<?php notify_js('Tienes que añadir una url','danger') ?>


				}else{

					$.post('functions/ajax.php' ,
					    {
					        subir_url_producto : url
					    }, 
					    function(data){
					        $('#respuesta_ajax_js').html(data);
						}
					);

				}

			});

			$('#crt_sum').click(function(){
			
				var crt = $('[name="ingrediente"]').val();

				if(crt == ''){

					<?php notify_js('Tienes que poner un texto', 'danger') ?>

				}else{

					$.post('functions/ajax.php' ,
					            {
					                caracteristica_sum : crt
					            }, 
					            function(data){
					
					                    $('#respuesta_ajax_js').html(data);
					
					                }
					        );
				}

			}); // $('#crt_sum').click(function(){
			
			<?php 

				if(isset($id)){ 

			?>


			    // con esto llenamos el formulario en caso de que 
			    // vayamos a editar el registro
			    $('[name="id_editar"]').val(<?php echo $id; ?>);
	            var serialize = '#<?php echo $form.$edt ?>_form';
	            $(serialize).call_ajax({
	                  url:"functions/ajax.php?tabla_cargar_datos_registros=1",
	                  serialice:serialize
	            });



	            $('[id^="alert_b_"]').on("close.bs.alert", function (e) {

	            	e.preventDefault();
					var btn = $(this).children('button.close');
					btn.attr('disabled','disabled');
					btn.after('<span class="text-danger">La foto será eliminada al modificar el producto<br></span>');
	            	var foto = $(this).children('input[name="foto_sum"]').attr('foto');
	            	var b_foto = $('[name="fotos_editar"]').val();
	            	var n_foto = b_foto == '' ? foto : b_foto+','+foto;
	            	$('[name="fotos_editar"]').val(n_foto);
	            	var foto = $(this).children('input[name="foto_sum"]').attr('name','foto_del');


				});


	            // fotos productos_form_form
				$('#btn_form_editar').click(function(e){
					
					// esta variable es para que no se evaluen las fotos si es edicion
					
					var fotos = '';
				    var f=0;
					$('[name="foto_del"]').each(function(){

						fotos+= f>0 ? ',' : '';
					    fotos+= $(this).attr('foto');
					    f++;

					});


					if($('#nombre_producto_editar').val() == ''){

						<?php notify_js('Tienes que asignar un nombre al producto','danger') ?>

					}else{

						var forms  = '#productos_web_editar_form';
						// metemos los valores en las fotos
						$('[name="fotos<?php echo $edt ?>"]').val(fotos);

						var b = 0;
						$('[name="crt_sum"]').each(function(){
		
							$('#descripcion_editar').before('<input type="hidden" name="ingredientes_productos['+b+']" value="'+$(this).val()+'">');

							b++;
						});
					
						var serialice = $(forms).serialize();	
						$.post('functions/ajax.php?'+serialice ,
									    {
									        accion : 'u'
									    }, 
									    function(data){
									        $('#respuesta_ajax_js').html(data);
										}
									);	

					}
					
				});	

	        <?php } // if(isset($id)){  ?>




			var myDropzone = new Dropzone("#dropform" , 
				{
					url : 'functions/ajax_multy_image.php',
					maxFilesize: 2,
				 	addRemoveLinks: true,
				 	clickable : true,
				 	acceptedFiles : '.png,.jpg,.jpeg'
			 	});



			myDropzone.on("sending", function(file, xhr, formData) {

			  	// Will send the filesize along with the file as POST data.
			    formData.append('subir_multy_imagen_producto', 1);
			    formData.append('nombre', file.name);
			    formData.append('size', file.size);
			    formData.append('id_imagenes_productos', "<?php echo $id_imagenes_productos ?>");
				

			});


			myDropzone.on("complete", function(file) {

			  	file.previewElement.addEventListener("click", function() {
				    myDropzone.removedfile(file);
				});

			});

			myDropzone.on("removedfile", function(file) {

			  	$.post('functions/ajax_multy_image.php' ,
			  	            {
			  	                borrar_imagen_insert : 1,
			  	                name : file.name,
			  	                id_imagenes_productos : '<?php echo $id_imagenes_productos ?>'
			  	            }, 
			  	            function(data){
			  	
			  	                    $('#respuesta_ajax_js').html(data);
			  	
			  	                }
			  	        );

			});


		}); // $(document).ready(function(){
	</script>
</body>
</html>