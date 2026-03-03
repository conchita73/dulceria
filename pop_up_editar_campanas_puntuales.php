<?php include 'inc/inc_start.php';  
$title_heading="Campañas Puntuales";
$table = 'colores';
$fb = isset($id) ? 'Modificar' : 'Crear';
$edt = isset($id) ? '_editar' : '';


?>
<!DOCTYPE html>
<html lang="es">
<head>

	<?php include 'inc/inc_head.php'; ?>
	
</head>

<body data-background-full="image" data-image="img/bg-3.png">
	<div class="wrapper overlay-sidebar">
		<div class="container">
			<div class="row text-center">
				<div class="col align-self-center">
						<img src="img/logo_admin.png" alt="navbar brand" class="navbar-brand mt30">
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
										<div class="col">
											<form action="" id="<?php echo $form.$edt ?>_form">
												<label class=" ">Fecha del envío:</label>
												<input type="text" name="fecha" class="form-control datepicker" value="" placeholder="Introduce una Fecha">
												<label class="mt-3 ">Introduce un Motivo:</label>
												<input type="text" class="form-control" name="motivo" value="" placeholder="Introduce un motivo">
												<label class="mt-3">Tipo Oferta:</label>
																	<select name="tipo">
																		<option value="descuento">Descuento</option>
																		<option value="regalo">Regalo</option>
																	</select>
																	
																	<div id="activeUsersChart"></div>
																	<div class="" id="descuento"> 
																		<label class="mt-3 ">Introduce un Porcentaje:</label>
																		<input type="text" class="form-control is_number" name="porcentaje" value="" placeholder="Introduce un porcentaje">
																	</div>
																	<div class="d-none" id="regalo">
																		<label class="mt-3 ">Selecciona un producto:</label>
																		<select name="id_producto" class="custom-select selectpicker" data-style="" data-live-search="true" verify="0">
															            	<?php 

															            	 	$DB->query('SELECT nombre_producto,pvp,id FROM productos where tarta_predeterminada =0 order by nombre_producto ASC');
															            	  	while($d = $DB->fetch()){
															            	 
															            	 		echo '<option value="'.$d['id'].'">'.utf8_encode($d['nombre_producto']).'</option>';
															            	  	}

															            	?>
															            </select>
																	</div>
																	<div class="">
																		<label class=" mt-3">¿De Cuantos días dispone para canjear?</label>
																		<select name="caducidad" id="">
																			<?php 

																				for ($i=1; $i <= 10; $i++) { 

																					echo '<option value="'.$i.'">'.$i.'</option>';

																				}
																			?>
																		</select>
																	</div>
											</form>
										</div> <!-- <div class="col"> -->
									</div> <!-- <div class="row"> -->
								</div> <!-- <div class="card-body"> -->
								<div class="card-footer">
								    <button type="button" class="btn btn-warning pull-right callAjax">
                                	<b><?php echo $fb ?></b>
                            		</button>  
                            		<input type="hidden" name="<?php echo $form.$edt ?>_form" value="1">
								 </div>
							</div> <!-- <div class="card"> -->
						</div> <!-- <div class="col-md-12"> -->
					</div> <!-- <div class="row row-card-no-pd"> -->
				</div>
				<?php include 'inc/inc_footer.php'; ?>
			</div>
		</div>
	</div>
		

	<?php $no_chat=1 ; include 'inc/inc_javascript.php'; ?>
	
	<script>
		$(document).ready(function(){

			$('[name="tipo"]').change(function(){

				var val  = $(this).val();
				if(val == 'regalo'){

					$('#regalo').removeClass('d-none');
					$('#descuento').addClass('d-none');

				}else{

					$('#regalo').addClass('d-none');
					$('#descuento').removeClass('d-none');

				}

			}); // $('[name="tipo"]').change(function(){

			$('.callAjax').click(function(){
				
				var fecha = $('[name="fecha"]').val();
				var tipo = $('[name="tipo"]').val();
				var porcentaje = $('[name="porcentaje"]').val();
				var motivo = $('[name="motivo"]').val();

				if(fecha == ''){

					<?php noty_js('Tienes que introducir una fecha','danger'); ?>

				}else if(motivo == ''){

					<?php noty_js('Tienes que introducir un motivo','danger'); ?>

				}else if(tipo == 'descuento' && porcentaje == ''){

					<?php noty_js('Tienes que introducir un porcentaje','danger'); ?>

				}else{

					$.post('functions/ajax.php' ,
					            {
					                accion : 'i',
					                tabla_insertar : 'ofertas_puntuales',
					                fecha : fecha,
					                tipo : tipo,
					                porcentaje : porcentaje,
					                motivo : motivo,
					                id_producto : $('[name="id_producto"]').val(),
					                caducidad : $('[name="caducidad"]').val()

					            }, 
					            function(data){
					
					                    $('#respuesta_ajax_js').html(data);
					
					                }
					        );
				}
				

			}); // $('.callAjax').click(function(){

		}); // $(document).ready(function(){
	</script>
</body>
</html>