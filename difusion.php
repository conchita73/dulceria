<?php include 'inc/inc_start.php';  

$card_title = "Difusión";

// TABLA XRA SELECT, UPDATE, DELETE, INSERT
$tabla="difusion";
// posicion 0 = nombre del form que va en la modal
// posicion 1 = text del button que va a accionar esa modal



?>
<!DOCTYPE html>
<html lang="es">
<head>

	<?php include 'inc/inc_head.php'; ?>
	<link rel="stylesheet" href="js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.css">


</head>

<body data-background-full="image" data-image="img/bg-abstract2.png">
	<div class="wrapper sidebar_minimize">

		<?php 
			include 'inc/inc_header.php'; 
			include 'inc/inc_menu.php'; 
		?>

		<div class="main-panel">
			<div class="content content-scroll scrollbar-inner">
				<div class="page-inner">

					<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">

						<?php include 'inc/inc_widget.php'; ?>
					</div>

					<div class="row row-card-no-pd">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<div class="card-head-row">
										<h4 class="card-title"><?php echo $card_title; ?> </h4>
										<?php 

											if(isset($modal_1) && $modal_1[0]!=""){

												echo '<button 
														class="btn btn-primary btn-round ml-auto" 
														data-toggle="modal" 
														data-target="#'.$modal_1[0].'"
														data-backdrop="static" 
														data-keyboard="false">
														<i class="fa fa-plus"></i>
													'.$modal_1[1].'
												</button>';
											}

										?>
									</div>
								</div>
								<div class="card-body">
									<div class="row" style="min-height: 400px;">
										<div class="col">
											<ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
												<li class="nav-item ">
												  <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"></i>Ofertas Puntuales:</a>
												</li>
												<li class="nav-item">
												  <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Fechas Especiales:</a>
												</li>
												<li class="nav-item">
												  <a class="nav-link" id="envios-tab" data-toggle="tab" href="#envios" role="tab" aria-controls="envios" aria-selected="false">Control Envíos:</a>
												</li>
											</ul>
											<div class="tab-content" id="myTabContent">
												<div class="tab-pane fade show active" id="home" role="	tabpanel" aria-labelledby="home-tab">
													<div class="row">
														<div class="col-12 text-center mt-2 text-info">
															<?php 
																$we = $DB->select('count(id) as total','clientes',' telefono like "6%" or telefono like "7%"');

																$cw = $DB->select('costo','costo_whatsapp','id = 1');
															?>
															<h3>Whatsapp a enviar <?php echo $we[f][0]['total'].' <br>(Costo de Envío '.($we[f][0]['total']*$cw[f][0]['costo']).' &euro;)' ?> </h3>
														</div>
														<div class="col-12">
															<button 
																class="btn btn-primary btn-round float-right mt-3 mb-3" 
																name="editar_pop_up"
																tabla="campanas_puntuales">
																<i class="fa fa-plus"></i>
																Crear Campaña Puntual
															</button>
														</div>
													</div>
													<form action="" method="post" accept-charset="utf-8" id="filtro_campanas_form">
														<input type="hidden" name="tabla" value="campanas_puntuales">
														<input type="hidden" name="buscar_tables_ajax" value="1">

														<div class="form-row has-info">
															<div class="col-md-6">
																<label for="">Desde:</label>
																<input type="text" name="desde" class="form-control datepicker">
															</div>
															<div class="col-md-6">
																<label for="">Hasta:</label>
																<input type="text" name="hasta" class="form-control datepicker">
															</div>
														</div>
														<button type="button" class="mt-2 btn btn-info float-right btn_filtro mb-3" >Filtrar Datos</button>
													</form>
													<div class="table-responsive">
													
														<div id="ultimas_campanas_puntuales">
															<?php

																echo pintar_tbody('campanas_puntuales','')['body'];

															?>
														</div>
														<div id="resultado_campanas_puntuales" class='d-none'>
															
														</div>
													</div> <!-- <div class="table-responsive"> -->
												</div> <!-- home-tab -->
												<div class="tab-pane fade" id="profile" role="	tabpanel" aria-labelledby="profile-tab">


													<div class="row mt-3">
														<h3 class="col-12 text-center text-primary">Configura tus ofertas</h3>	
														<div class="col-md-3 mt-3">
															<?php 

																$dc = $DB->select('*','ofertas_especiales','id = 1');
																$dc = $dc[f][0];

																$dnd = $dc['tipo'] == 'descuento' ? '' : 'd-none';
																$dnr = $dc['tipo'] == 'regalo' ? '' : 'd-none';

															?>
															<div class="card card-primary bg-primary-gradient">
																<div class="card-body">
																	<h2 class="b-b1 pb-2 mb-4 fw-bold">Oferta Cumpleaños</h2>
																	<label class=" text-white">Tipo Oferta:</label>
																	<select name="tipo_fecha_nacimiento" fecha="fecha_nacimiento">
																		<option value="descuento" <?php if($dc['tipo'] == 'descuento') echo 'selected'; ?>>Descuento</option>
																		<option value="regalo" <?php if($dc['tipo'] == 'regalo') echo 'selected'; ?>>Regalo</option>
																	</select>
																	<div id="activeUsersChart"></div>
																	<div class="<?php echo $dnd ?>" id="descuento_fecha_nacimiento"> 
																		<label class="mt-3 text-white">Introduce un Porcentaje:</label>
																		<input type="text" class="form-control is_number" name="porcentaje_fecha_nacimiento" value="<?php echo $dc['porcentaje'] ?>" placeholder="Introduce un porcentaje">
																	</div>
																	<div class="<?php echo $dnr ?>" id="regalo_fecha_nacimiento">
																		<label class="mt-3 text-white">Selecciona un producto:</label>
																		<select name="id_producto_fecha_nacimiento" class="custom-select selectpicker" data-style="" data-live-search="true" verify="0">
															            	<?php 

															            	 	$DB->query('SELECT nombre_producto,pvp,id FROM productos where tarta_predeterminada =0 order by nombre_producto ASC');
															            	  	while($d = $DB->fetch()){
															            	 
															            	 		echo '<option value="'.$d['id'].'"';

															            	 		if($d['id'] == $dc['id_producto']) echo 'selected';
															            	 		echo '>'.utf8_encode($d['nombre_producto']).'</option>';
															            	  	}

															            	?>
															            </select>
																	</div>
																	<div class="">
																		<label class="text-white mt-3">¿De Cuantos días dispone para canjear?</label>
																		<select name="caducidad_fecha_nacimiento" id="">
																			<?php 

																				for ($i=1; $i <= 10; $i++) { 

																					echo '<option value="'.$i.'"';

															            	 		if($i == $dc['caducidad']) echo 'selected';

															            	 		echo '>'.$i.'</option>';

																				}
																			?>
																		</select>
																	</div>
																	<hr>
																	<button type="button" class="btn btn-white btn-block" fecha="fecha_nacimiento" name="btn_fechas">Modificar</button>
																</div>
															</div>
														</div>
														<div class="col-md-3 mt-3">
															<?php 

																$dc = $DB->select('*','ofertas_especiales','id = 2');
																$dc = $dc[f][0];

																$dnd = $dc['tipo'] == 'descuento' ? '' : 'd-none';
																$dnr = $dc['tipo'] == 'regalo' ? '' : 'd-none';

															?>
															<div class="card card-primary bg-primary-gradient">
																<div class="card-body">
																	<h2 class="b-b1 pb-2 mb-4 fw-bold">Oferta Santo</h2>
																	<label class=" text-white">Tipo Oferta:</label>
																	<select name="tipo_fecha_santo" fecha="fecha_santo">
																		<option value="descuento" <?php if($dc['tipo'] == 'descuento') echo 'selected'; ?>>Descuento</option>
																		<option value="regalo" <?php if($dc['tipo'] == 'regalo') echo 'selected'; ?>>Regalo</option>
																	</select>
																	<div id="activeUsersChart"></div>
																	<div class="<?php echo $dnd ?>" id="descuento_fecha_santo"> 
																		<label class="mt-3 text-white">Introduce un Porcentaje:</label>
																		<input type="text" class="form-control is_number" name="porcentaje_fecha_santo" value="<?php echo $dc['porcentaje'] ?>" placeholder="Introduce un porcentaje">
																	</div>
																	<div class="<?php echo $dnr ?>" id="regalo_fecha_santo">
																		<label class="mt-3 text-white">Selecciona un producto:</label>
																		<select name="id_producto_fecha_santo" class="custom-select selectpicker" data-style="" data-live-search="true" verify="0">
															            	<?php 

															            	 	$DB->query('SELECT nombre_producto,pvp,id FROM productos where tarta_predeterminada =0 order by nombre_producto ASC');
															            	  	while($d = $DB->fetch()){
															            	 
															            	 		echo '<option value="'.$d['id'].'"';

															            	 		if($d['id'] == $dc['id_producto']) echo 'selected';
															            	 		echo '>'.utf8_encode($d['nombre_producto']).'</option>';
															            	  	}

															            	?>
															            </select>
																	</div>
																	<div class="">
																		<label class="text-white mt-3">¿De Cuantos días dispone para canjear?</label>
																		<select name="caducidad_fecha_santo" id="">
																			<?php 

																				for ($i=1; $i <= 10; $i++) { 

																					echo '<option value="'.$i.'"';

															            	 		if($i == $dc['caducidad']) echo 'selected';

															            	 		echo '>'.$i.'</option>';

																				}
																			?>
																		</select>
																	</div>
																	<hr>
																	<button type="button" class="btn btn-white btn-block" fecha="fecha_santo" name="btn_fechas">Modificar</button>
																</div>
															</div>
														</div>
														<div class="col-md-3 mt-3">
															<?php 

																$dc = $DB->select('*','ofertas_especiales','id = 3');
																$dc = $dc[f][0];

																$dnd = $dc['tipo'] == 'descuento' ? '' : 'd-none';
																$dnr = $dc['tipo'] == 'regalo' ? '' : 'd-none';


															?>
															<div class="card card-primary bg-primary-gradient">
																<div class="card-body">
																	<h2 class="b-b1 pb-2 mb-4 fw-bold">Oferta Aniversario</h2>
																	<label class=" text-white">Tipo Oferta:</label>
																	<select name="tipo_fecha_aniversario" fecha="fecha_aniversario">
																		<option value="descuento" <?php if($dc['tipo'] == 'descuento') echo 'selected'; ?>>Descuento</option>
																		<option value="regalo" <?php if($dc['tipo'] == 'regalo') echo 'selected'; ?>>Regalo</option>
																	</select>
																	<div id="activeUsersChart"></div>
																	<div class="<?php echo $dnd ?>" id="descuento_fecha_aniversario"> 
																		<label class="mt-3 text-white">Introduce un Porcentaje:</label>
																		<input type="text" class="form-control is_number" name="porcentaje_fecha_aniversario" value="<?php echo $dc['porcentaje'] ?>" placeholder="Introduce un porcentaje">
																	</div>
																	<div class="<?php echo $dnr ?>" id="regalo_fecha_aniversario">
																		<label class="mt-3 text-white">Selecciona un producto:</label>
																		<select name="id_producto_fecha_aniversario" class="custom-select selectpicker" data-style="" data-live-search="true" verify="0">
															            	<?php 

															            	 	$DB->query('SELECT nombre_producto,pvp,id FROM productos where tarta_predeterminada =0 order by nombre_producto ASC');
															            	  	while($d = $DB->fetch()){
															            	 
															            	 		echo '<option value="'.$d['id'].'"';

															            	 		if($d['id'] == $dc['id_producto']) echo 'selected';
															            	 		echo '>'.utf8_encode($d['nombre_producto']).'</option>';
															            	  	}

															            	?>
															            </select>
																	</div>
																	<div class="">
																		<label class="text-white mt-3">¿De Cuantos días dispone para canjear?</label>
																		<select name="caducidad_fecha_aniversario" id="">
																			<?php 

																				for ($i=1; $i <= 10; $i++) { 

																					echo '<option value="'.$i.'"';

															            	 		if($i == $dc['caducidad']) echo 'selected';

															            	 		echo '>'.$i.'</option>';

																				}
																			?>
																		</select>
																	</div>
																	<hr>
																	<button type="button" class="btn btn-white btn-block" fecha="fecha_aniversario" name="btn_fechas">Modificar</button>
																</div>
															</div>
														</div>
														<div class="col-md-3 mt-3">
															<?php 

																$dc = $DB->select('*','ofertas_especiales','id = 4');
																$dc = $dc[f][0];

																$dnd = $dc['tipo'] == 'descuento' ? '' : 'd-none';
																$dnr = $dc['tipo'] == 'regalo' ? '' : 'd-none';


															?>
															<div class="card card-primary bg-primary-gradient">
																<div class="card-body">
																	<h2 class="b-b1 pb-2 mb-4 fw-bold">Oferta Personalizada</h2>
																	<label class=" text-white">Tipo Oferta:</label>
																	<select name="tipo_fecha_aniversario" fecha="fecha_personalizada">
																		<option value="descuento" <?php if($dc['tipo'] == 'descuento') echo 'selected'; ?>>Descuento</option>
																		<option value="regalo" <?php if($dc['tipo'] == 'regalo') echo 'selected'; ?>>Regalo</option>
																	</select>
																	<div id="activeUsersChart"></div>
																	<div class="<?php echo $dnd ?>" id="descuento_fecha_personalizada"> 
																		<label class="mt-3 text-white">Introduce un Porcentaje:</label>
																		<input type="text" class="form-control is_number" name="porcentaje_fecha_personalizada" value="<?php echo $dc['porcentaje'] ?>" placeholder="Introduce un porcentaje">
																	</div>
																	<div class="<?php echo $dnr ?>" id="regalo_fecha_personalizada">
																		<label class="mt-3 text-white">Selecciona un producto:</label>
																		<select name="id_producto_fecha_personalizada" class="custom-select selectpicker" data-style="" data-live-search="true" verify="0">
															            	<?php 

															            	 	$DB->query('SELECT nombre_producto,pvp,id FROM productos where tarta_predeterminada =0 order by nombre_producto ASC');
															            	  	while($d = $DB->fetch()){
															            	 
															            	 		echo '<option value="'.$d['id'].'"';

															            	 		if($d['id'] == $dc['id_producto']) echo 'selected';
															            	 		echo '>'.utf8_encode($d['nombre_producto']).'</option>';
															            	  	}

															            	?>
															            </select>
																	</div>
																	<div class="">
																		<label class="text-white mt-3">¿De Cuantos días dispone para canjear?</label>
																		<select name="caducidad_fecha_personalizada" id="">
																			<?php 

																				for ($i=1; $i <= 10; $i++) { 

																					echo '<option value="'.$i.'"';

															            	 		if($i == $dc['caducidad']) echo 'selected';

															            	 		echo '>'.$i.'</option>';

																				}

																			?>
																		</select>
																	</div>
																	<hr>
																	<button type="button" class="btn btn-white btn-block" fecha="fecha_personalizada" name="btn_fechas">Modificar</button>
																</div>
															</div>
														</div>
													</div> <!-- <div class="table-responsive mt-3"> -->
												</div> <!-- id="profile" -->
												<div class="tab-pane fade" id="envios" role="	tabpanel" aria-labelledby="envios-tab" style="min-height: 350px;">
													<h3 class="mt-3 text-center text-primary">Envios Realizados <br> <span class="text-success">(Costo de Whatsapp : <span id="costo_whatsapp_span"></span> &euro;)</span></h3>
													<hr>
													<div class="col-sm-12 col-md-6 offset-md-3">
														<div class="input-group mb-3">
															<?php $pw = $DB->select('presupuesto','costo_whatsapp','id = 1'); ?>
															<div class="input-group-prepend">
																<span class="input-group-text">Presupuesto</span>
															</div>
															 <input type="text" class="form-control" name="presupuesto" placeholder="Presupuesto de whatsapp" value="<?php echo $pw[f][0]['presupuesto'] ?>">
															<div class="input-group-append">
															    <button class="btn btn-outline-success" type="button" id="modifica_presupuesto">Modificar</button>
															</div>
														</div>
													</div>
													<div class="col-12 text-danger text-center">* Por defecto sólo se mostrará el mes en curso</div>
													<form action="" method="post" accept-charset="utf-8" id="filtro_form">
														<input type="hidden" name="tabla" value="control_envios">
														<input type="hidden" name="buscar_tables_ajax" value="1">

														<div class="form-row has-info">
															<div class="col-md-6">
																<label for="">Desde:</label>
																<input type="text" name="desde" class="form-control datepicker">
															</div>
															<div class="col-md-6">
																<label for="">Hasta:</label>
																<input type="text" name="hasta" class="form-control datepicker">
															</div>
														</div>
														<button type="button" class="mt-2 btn btn-info float-right btn_filtro mb-3" >Filtrar Datos</button>
													</form>
													<div class="table-responsive">
														<div id="ultimas_control_envios">
															<?php

																$control_envios = pintar_tbody('control_envios',$modal_1);
			 													echo $control_envios['body'];

															?>
														</div>
														<div id="resultado_control_envios" class='d-none'>
															
														</div>
													</div> <!-- <div class="table-responsive"> -->
												</div> <!-- envios-tab -->
											</div> <!-- myTabContent -->
										</div> <!-- <div class="col"> -->
									</div> <!-- <div class="row"> -->
								</div> <!-- <div class="card-body"> -->
							</div> <!-- <div class="card"> -->
						</div> <!-- <div class="col-md-12"> -->
					</div> <!-- <div class="row row-card-no-pd"> -->
				</div> <!-- <div class="page-inner"> -->
				<?php include 'inc/inc_footer.php'; ?>
			</div> <!-- <div class="content content-scroll scrollbar-inner"> -->
		</div> <!-- <div class="main-panel"> -->
	</div> <!-- <div class="wrapper"> -->
	<?php 

		// $no_data_table = 1;
		include 'inc/inc_javascript.php'; 

		if(isset($modal_1) && $modal_1[0]!=""){
            pintar_modal_json($modal_1[0],i,$tabla);
            pintar_modal_json($modal_1[0],u,$tabla);
            pintar_modal_json("borrar_registro",d,$tabla);
        }

	?>

	<script>
		$(document).ready(function(){

			$('#modifica_presupuesto').click(function(){
				
				var p = $('[name="presupuesto"]').val();
				if(isNaN(p)){

					<?php noty_js('Tienes que introducir un número','danger') ?>
					$('[name="presupuesto"]').val(0);

				}else{

					$.post('functions/ajax.php' ,
					            {
					                accion : 'u',
					                tabla_editar : 'costo_whatsapp',
					                id_editar : 1,
					                presupuesto : p,
					                noty : 1

					            }, 
					            function(data){
					
					                    $('#respuesta_ajax_js').html(data);
					
					                }
					        );
				}

			}); // $('#modifica_presupuesto').click(function(){

			$('#costo_whatsapp_span').html("<?php echo $control_envios['totales'] ?>");

			$('[name^="tipo"]').change(function(){

				var fecha = $(this).attr('fecha');
				var val = $(this).val();

				if(val == 'regalo'){

					$('#regalo_'+fecha).removeClass('d-none');
					$('#descuento_'+fecha).addClass('d-none');

				}else{

					$('#regalo_'+fecha).addClass('d-none');
					$('#descuento_'+fecha).removeClass('d-none');

				}

			}); // $('[name="tipo"]').change(function(){

			$('[name="btn_fechas"]').click(function(){
				
				var tipo = $(this).attr('fecha');
				var id = tipo == 'fecha_nacimiento' ? 1 : (tipo == 'fecha_santo' ? 2 : (tipo == 'fecha_aniversario' ? 3 : 4));


				$.post('functions/ajax.php' ,
				            {
				                tabla_editar : 'ofertas_especiales',
				                accion : 'u',
				               	id_editar : id,
				               	tipo_editar : $('[name="tipo_'+tipo+'"]').val(),
				               	porcentaje_editar : $('[name="porcentaje_'+tipo+'"]').val(),
				               	id_producto_editar : $('[name="id_producto_'+tipo+'"]').val(),
				               	caducidad_editar : $('[name="caducidad_'+tipo+'"]').val(),

				            }, 
				            function(data){
				
				                    $('#respuesta_ajax_js').html(data);
				
				                }
				        );

			}); // $('[name="btn_fechas"]').click(function(){

		}); // $(document).ready(function(){
	</script>
</body>
</html>