<?php include 'inc/inc_start.php';  
$title_heading="Editar Pedido";

$pedido = $DB->select('*','pedidos','id='.$id);
$p = $pedido[f][0];

if($p['descuento']>0){

	$porciento = 100-$p['descuento'];
	$p['importe_final'] = $p['importe_final']*100;
	$unidad = (int)$p['importe_final']/$porciento;
	$p['importe_final'] = $unidad;

}


$cliente = $DB->select('*','clientes','id='.$p['id_cliente']);
$c = $cliente[f][0];

$factura = $DB->select('id','facturas','id_pedido='.$id);
$id_factura = $factura[f][0]['id'];

?>
<!DOCTYPE html>
<html lang="es">
<head>

	<?php include 'inc/inc_head.php'; ?>

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
										<h4 class="card-title text-center">Pedido de <?php echo utf8_encode($c['nombre']).' - '.$c['telefono']; ?> </h4>
										
									</div>
								</div>
								<div class="card-body">
									<div class="col-12 text-center text-danger">
										<h5>* El precio que mostramos es el original, si el cliente tiene un descuento asignado, se le volverá a aplicar cuando se modifique el pedido</h5>
									</div>
									<div class="row has-info">
										<div class="col-6 ">
											<label class="mt-2">Fecha de entrega:</label>
											<input type="text" name="fecha_entrega" id="fecha_entrega" class="form-control" placeholder="Fecha de entrega" value="<?php echo formatear_fecha($p['fecha_entrega'],m,i); ?>" verify="1">
										</div> <!-- <div class="col-6"> -->
										<div class="col-6">
											<label class="mt-2">Hora de entrega:</label>
											<input type="text" name="hora_entrega" placeholder="Selecciona una hora" class="form-control" value="<?php echo $p['hora_entrega']; ?>" verify="1">
										</div>
										<div class="col mt-2">
											<h4 class="text-info text-center"><u>Añadir Productos</u></h4>
											<input type="hidden" name="importe_final" value="<?php echo number_format($p['importe_final'],2,'.','');; ?>">
												<div class="row">
													<div class="col-4">
														<span class=" text_1_2 text-success">Precio Final: <span name="pvp_span"> <?php echo number_format($p['importe_final'],2,'.','');; ?></span> &euro;</span>
													</div>
													<div class="col-4">
														<span class=" text_1_2 text-success">Descuento: <span name="descuento_span"> <?php echo $p['descuento']; ?></span> %</span>
													</div>
													<div class="col-4">
														<span class=" text_1_2 text-success">Habitual: 
															<span name="recurrente_span"> 
																<?php 

																	$habitual = $p['es_recurrente'] == 1 ? 'Si' : 'No';
																	echo $habitual;

														 		?>
														 	</span>
														 </span>
													</div>
												</div>
												<ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
												  <li class="nav-item ">
												    <a class="nav-link " id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><i class="fas fa-2x fa-birthday-cake text-success"></i> Tartas:</a>
												  </li>
												  <li class="nav-item">
												    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><i class="fas fa-2x fa-shopping-basket text-success"></i> Productos:</a>
												  </li>
												  <li class="nav-item">
												    <a class="nav-link active" id="cart-tab" data-toggle="tab" href="#cart" role="tab" aria-controls="cart" aria-selected="false"><i class="fas fa-2x fa-shopping-cart text-success"></i> Finalizar Pedido:</a>
												  </li>
												</ul>
												<div class="tab-content" id="myTabContent">
													<div class="tab-pane fade" id="home" role="	tabpanel" aria-labelledby="home-tab">
													<form class="form-row has-info" id="suma_tarta_form">
														<input type="hidden" name="porciones" value="1">
														<input type="hidden" name="importe_final" value="<?php echo number_format($p['importe_final'],2,'.',''); ?>">
														<div class="col-12">
															<h3 class="mt-4 text-center">Elije una Tarta</h3>
														</div>
														<div class="col-6">
															<label class="mt-1">Tartas al gusto:</label>
															<select name="select_tarta" class="custom-select">
																<option value="0">Elije una tarta</option>
																<option value="gominolas">De Gominolas</option>
																<option value="personalizada">Personalizadas</option>
																<option value="galletas">De Galletas</option>
																<option value="pastelon">El Pastelón</option>
															</select>
														</div>
														<div class="col-6">
															<label class="mt-1">Tartas Predeterminadas:</label>
															<input type="hidden" name="nombre_predeterminada" value="">
															<select name="select_tarta_predeterminada" class="custom-select">
																<option value="0">Elije una tarta</option>
																<?php 
 
															        $DB->query('SELECT nombre_producto,pvp,id FROM productos where tarta_predeterminada = 1 order by nombre_producto ASC');
															        while($d = $DB->fetch()){
															            	 
															           	echo '<option value="'.($d['pvp']/100).'" nombre="'.utf8_encode($d['nombre_producto']).'">'.utf8_encode($d['nombre_producto']).' </option>';
															        
															        }

																?>
															</select>
														</div>

														<div class="col-12">
															
															<?php include 'inc/inc_select_tartas.php'; ?>
															
															</div>
															<div class="col-12 text-center mt-2">
																<input type="hidden" name="precio_final_tarta" value="">
																<button type="button" class="btn btn-warning btn-lg  mt-2" id="suma_tarta">A&ntilde;adir tarta <span class="predeterminada text_1_2 personalizada gominolas galletas pastelon d-none"> (PVP: <span id="pvp_tarta"> 0</span> &euro;)</span></button>
																<div class="row">
																	<div class="col-sm-4 offset-sm-4">
																		<label class="mt-3">Cambiar Precio:</label>
																		<input type="text" name="cambiar_precio" value="" class="is_number form-control" placeholder="">
																	</div>
																</div>
															</div>
														</form>
						                    		</div>  <!-- id="home" -->

											  		<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
											  			<form class="form-row has-info" id="suma_producto_form">
											  				<div class="col-12">
													  			<input type="hidden" name="importe_final" value="<?php echo number_format($p['importe_final'],2,'.',''); ?>">
													            <h3 class="mt-3 text-center">¿Es un producto?</h3>
													            <div class="row">
													            	<div class="col-6">
													            		
													            		<label class="mt-2">Selecciona un producto</label>
													            		<select name="concepto_producto" class="custom-select selectpicker" data-style="btn-info" data-live-search="true" verify="0">
																			<option value="0">No</option>
															            	<?php 

															            	 	$DB->query('SELECT nombre_producto,pvp,id FROM productos where tarta_predeterminada =0 order by nombre_producto ASC');
															            	  	while($d = $DB->fetch()){
															            	 
															            	 		echo '<option value="'.$d['id'].'">'.utf8_encode($d['nombre_producto']).' ('.number_format(($d['pvp']/100),2,'.','').' &euro;)</option>';
															            	  	}

															            	?>
															            </select>
													            	</div>
													            	<div class="col-6">
													            		<label class="mt-2">¿Cuantas unidades?</label>
													            		<!-- <select name="unidades_producto" class="custom-select" verify="0">
													            			<?php 

													            				for ($i=1; $i <=20 ; $i++) { 
													            					echo '<option value="'.$i.'">'.$i.'</option>';
													            				}

													            			?>
																			
															            	
															            </select> -->
															            <input type="tel" name="unidades_producto" value="" class="form-control" placeholder="¿Cuantas Unidades?">
													            	</div>
													            </div>
													            
												            	<!-- <h3 class="mt-2 text-center">¿Es personalizado?</h3> -->
													            <div class="row ">
													            	<div class="mt-2 d-none col-12" id="comentarios_pastelinos">
															            	<label>Comentario:</label>
															            	<textarea name="comentarios_pastelinos" class="form-control"></textarea>
															        </div>
																	<div class="col-6">
																		<!-- <label for="">Concepto Personalizado:</label> -->
																		<input type="hidden" class="form-control" name="conceptos_personalizado" value="" placeholder="Introduce un concepto" verify="0">
																	</div> 
																	<div class="col-6">
																		<!-- <label for="">Pvp Personalizado</label> -->
																		<input type="hidden" class="form-control is_number" name="pvp_personalizado" value="" placeholder="Introduce un pvp" verify="0">
																	</div>
																	<div class="col-12 text-center">
																		<button type="button" class="btn btn-warning btn-lg  mt-2" id="suma_concepto">A&ntilde;adir Producto</button>
																	</div>
																</div> <!-- <div class="row "> -->
															</div> <!-- <div class="col-12"> -->
											  			</form>
													</div> <!-- id="profile" -->
													<div class="tab-pane fade show active" id="cart" role="	tabpanel" aria-labelledby="cart-tab">
														
														<form id="finalizar_pedido" class="form-row has-info">
															<div class="col-12">
																<input type="hidden" name="importe_final" value="<?php echo number_format($p['importe_final'],2,'.',''); ?>">
																<label class="mt-2">¿Esta cobrado?</label>
																<?php 

																	$dnone = $p['pedido_cobrado'] == 'Si' || $p['pedido_cobrado'] == 'im' ? '' : 'd-none';

																	$pag = $p['pedido_cobrado'] == 'Si' ? 'selected="selected"' : '';

																	$imp = $p['pedido_cobrado'] == 'im' ? 'selected="selected"' : '';

																	$cant = $p['pedido_cobrado'] == 'Si' ? $p['importe_final'] : ($p['pedido_cobrado'] == 'im' ? $p['importe_pagado'] : 0);

																?>
																<select name="pedido_cobrado" class="custom-select">
																	<option value="No">No</option>
																	<option value="Si" <?php echo $pag; ?>>Si</option>
																	<option value="importe" <?php echo $imp; ?>>Pagada una parte</option>
																</select> 

																<label class="mt-2 <?php echo $dnone ?> pp">Importe pagado:</label>
																<input type="text" class="form-control  is_number <?php echo $dnone ?> pp" name="importe_pagado" value="<?php echo $p['importe_pagado']; ?>" placeholder="Introduce un importe" verify="0">
															</div>

															<div class="col-12">
																<input type="hidden" name="id_cliente" value="<?php echo $p['id_cliente']; ?>">
																<input type="hidden" name="importe_final" value="<?php echo number_format($p['importe_final'],2,'.',''); ?>">
																<input type="hidden" name="es_recurrente" value="<?php echo $p['es_recurrente']; ?>">
																<input type="hidden" name="id_factura" value="<?php echo $id_factura; ?>">
																<div class="row">
																	<div class="col-6">
																		<label class="mt-2">¿Lleva Factura?</label>
																		<?php 

																			$on_fac = $p['facturar'] == 0 ? '' : 'selected="selected"';

																		?>
																		<select name="facturar" class="custom-select">
																			<option value="0">No</option>
																			<option value="1" <?php echo $on_fac ?>>Si</option>
																		</select>
																	</div>
																	<div class="col-6">
																		<label class="mt-2">Porcentaje de descuento:</label>
																		<input type="text" class="form-control  is_number" name="descuento" value="<?php echo $p['descuento']; ?>" placeholder="Introduce un importe" verify="0">
																	</div>
																</div>
																  
																<div class="row d-none mt-2" id="datos_facturacion">
																	<div class="col-12">
																		<h3 class="text-center">Datos de facturación</h3>
																	</div>

																	<?php 

																		$arr_c = array(
																				'nombre_fiscal'=>'Nombre Fiscal',
																				'direccion'=>'Dirección',
																				'poblacion'=>'Población',
																				'cp'=>'Código Postal',
																				'cif'=>'CIF / NIF');

																		foreach ($arr_c as $camp=>$val) {
																			
																			echo '
																			<div class="col-6">
																				<label for="">'.($val).'</label>
																				<input type="text" class="form-control" name="'.$camp.'" value="'.$c[$camp].'">
																			</div>';
																		}


																	?>
																</div>
															</div>
															
															<div id="carrito_div" class="col-12 mt-2">
															<?php 

																if($p['producto']!=''){

																	
																	$arr_p = json_decode(utf8_encode($p['producto']),true,JSON_UNESCAPED_UNICODE);

																	foreach ($arr_p as $key => $value) {
																		
																		echo '<div class="alert alert-info alert-dismissible fade show mt-3" role="alert" id="alert_'.time().'"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
																		$fields = ' ';
																		foreach ($value as $k => $va) {

																			echo ($va);
																			if($k == 'cantidad'){

																				echo ' Unidades';

																			}else if($k == 'pvp'){

																				echo ' &euro;';

																			}

																			// if(isset()){

																			// }
																			echo '<br>';
																			$fields .= $k.'="'.$va.'" ';
																		}
																		echo '<input type="hidden" name="concep_sum" tipo="producto" '.$fields.' select_tarta="0"></div>';
																	}
																	
																} // if($p['producto']!=''){

																if($p['tarta']!=''){

																	 
																	$arr_t = json_decode(utf8_encode($p['tarta']),true,JSON_UNESCAPED_UNICODE);

																	foreach ($arr_t as $key => $value) {
																		
																		echo '<div class="alert alert-info alert-dismissible fade show mt-3" role="alert" id="alert_'.time().'"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';

																		$fieldst = ' ';
																		foreach ($value as $kt => $va) {

																			if($kt != 'nombre' && $kt != 'tarta'&& $kt != 'predeterminada'){
																				echo ucwords(str_replace('_',' ',$kt)).' : ';;
																			}

																			if($kt != 'tarta' && $kt != 'predeterminada'){


																				echo $va;
																				echo '<br>';
																				
																			}


																			$fieldst .= $kt.'="'.sin_tildes($va).'" ';

																		} // foreach ($value as $k => $va) {
																			
																		echo '<input type="hidden" name="concep_sum" tipo="tarta" '.$fieldst.' select_tarta="'.trim(sin_tildes($value['tarta'])).'" ></div>';

																	} // foreach ($arr_t as $key => $value) {
																	
																} // if($p['tarta']!=''){


															?>
															</div>


															<div class="col-12 text-center">
																<button type="button" class="btn btn-warning btn-lg  mt-2" id="confirmar_pedido">Modificar Pedido (<span class=" text_1_2">Precio Final: <span name="pvp_span"> <?php echo number_format($p['importe_final'],2,'.',''); ?></span> &euro;</span>)</button>
															</div>
														</form>
													</div> <!-- id="cart" -->
												</div> <!-- id="myTabContent" -->
										</div> <!-- <div class="col"> -->
									</div> <!-- <div class="row"> -->
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

		$('[name="fecha"]').datetimepicker({
                format: 'DD/MM/YYYY',
                widgetPositioning: {
                    horizontal: 'right',
                    vertical: 'bottom'
                }
        }); // $('[name="fecha"]').datetimepicker({

        $('[name="hora"]').datetimepicker({
				format: 'H:mm ', 
		});


			$('[name="cambiar_precio"]').keyup(function(){

				var v = $(this).val();
                var n = v.replace(',','.');
				var s = String(n);
            	if(s.length == 2){ s = s+".00"; }
				$('#pvp_tarta').html(s);
            	$('[name="precio_final_tarta"]').val(s);


			});


            $('[name="select_tarta_predeterminada"]').change(function(){

            	var v = $(this).val();
            	var n = $('[name="select_tarta_predeterminada"] option:selected').html();
            	

            	$('[name="select_tarta"] option[value="0"]').prop('selected',true);
            	$('.personalizada, .galletas, .pastelon, .gominolas .predeterminada').addClass('d-none');
            	$('#pvp_tarta').html(v+'.00');
            	$('[name="cambiar_precio"]').val(v);
            	$('[name="precio_final_tarta"]').val(v+'.00');
            	$('[name="porciones"]').val(1);
            	$('[name="nombre_predeterminada"]').val(n);
            	$('.predeterminada').removeClass('d-none');

            	
			});


            $('[name="select_tarta"]').change(function(){

            	var v = $(this).val();
            	$('[name="select_tarta_predeterminada"] option[value="0"]').prop('selected',true);
            	$('.personalizada, .galletas, .pastelon, .gominolas, .predeterminada').addClass('d-none');
            	$('.'+v).removeClass('d-none');

            	if(v == 'personalizada' || v == 'galletas'){

            		var val = 11;

            	}else if(v == 'gominolas'){

            		var val = 10;

            	}else if(v == 'pastelon'){

            		var val = 13;

            	}else{

            		var val = 0;
            	}

            	$('#pvp_tarta').html(val+'.00');
            	$('[name="porciones"]').val(1);
            	$('[name="cambiar_precio"]').val(val);
            	$('[name="precio_final_tarta"]').val(val+'.00');


            }); // $('[name="select_tarta"]').change(function(){
					
			$('[name="tamano_gominolas"], [name="tamano_pastelon"], [name="tamano_galletas"]').change(function(){


				var name = $(this).attr('name');
				var porciones = $('[name="'+name+'"] option:selected').html();
            	var val = $(this).val();
            	$('#pvp_tarta').html(val+'.00');
            	$('[name="cambiar_precio"]').val(val);
            	$('[name="precio_final_tarta"]').val(val+'.00');
            	$('[name="porciones"]').val(porciones);


            }); // $('[name="tamano_gominolas"],[name="taman

			$('[name="tamano_personalizada"]').change(function(){

				var name = $(this).attr('name');
				var porciones = $('[name="'+name+'"] option:selected').html();
            	var val = $(this).val();
            	if($('[name="borde_exterior_personalizada"]').val() != 'Merengue'){

            		if(val == 11){

            			val = 14;

            		}else if(val == 14){

            			val = 17;

            		}else if(val == 19){

            			val = 25;

            		}else if(val == 25){

            			val = 33;

            		}else if(val == 8){

            			val = 9;
            		}
            	}

            	$('#pvp_tarta').html(val+'.00');
            	$('[name="cambiar_precio"]').val(val);
            	$('[name="precio_final_tarta"]').val(val+'.00');
            	$('[name="porciones"]').val(porciones);

            }); // $('[name="tamano_personalizada"]').ch

            $('[name="borde_exterior_personalizada"]').change(function(){

            	var val = $('[name="tamano_personalizada"]').val();
            	if($(this).val() != 'Merengue'){

            		if(val == 11){

            			val = 14;

            		}else if(val == 14){

            			val = 17;

            		}else if(val == 19){

            			val = 25;

            		}else if(val == 25){

            			val = 33;

            		}else if(val == 8){

            			val = 9;
            		}
            	}
            	$('#pvp_tarta').html(val+'.00');
            	$('[name="cambiar_precio"]').val(val);
            	$('[name="precio_final_tarta"]').val(val+'.00');
            	

            });	// $('[name="borde_exterior_personal	

            $('[name="incluir_foto"]').change(function(event) {
            	
            	var val = parseInt($('[name="precio_final_tarta"]').val());

            	if($(this).val()=='Si'){

            		// $('[name="foto"]').removeClass('hide');
            		val = val + 4;

            	}else{

            		// $('[name="foto"]').addClass('hide');
            		val = val - 4;

            	}

            	$('#pvp_tarta').html(val+'.00');
            	$('[name="cambiar_precio"]').val(val);
            	$('[name="precio_final_tarta"]').val(val+'.00');

            }); //  $('[name="incluir_foto"]').c



           $('#suma_tarta').click(function(e){
           
           		if($('[name="select_tarta"]').val() == 0 && $('[name="select_tarta_predeterminada"]').val() == 0){

           			<?php noty_js('Tienes que seleccionar una tarta','danger'); ?>

           		}else{

           			
           			var msj = $('[name="incluir_mensaje"]').val();
           			var msj2=msj.replace("\n"," ");
           			$('[name="incluir_mensaje"]').val(msj2);
           			var cmnt = $('[name="comentarios"]').val();
           			var cmnt2=cmnt.replace("\n"," ");
           			$('[name="comentarios"]').val(cmnt2);
           			$('#suma_tarta_form').form_validator_ajax();

           		}
           });

           	$('#suma_concepto').click(function(e){
				
				var producto = $('[name="concepto_producto"]').val();
				var concepto = $('[name="concepto_personalizado"]').val();
				var pvp = $('[name="pvp_personalizado"]').val();

				if(producto == 0 && (concepto == '' || pvp == '')){

					<?php  noty_js('Tienes que seleccionar o meter un concepto','danger') ?>

				}else{


					$('#suma_producto_form').form_validator_ajax();

				}
				  
			});


			$('#confirmar_pedido').click(function(e){

				// comprobamos que el precio no este a 0 y que esten todos los datos
				if($('[name="importe_final"]').val() == 0){

					<?php  noty_js('El carrito esta vacio','danger') ?>

				}else{


					// que todos los datos del pedido esten rellenos.
					if($('[name="fecha_entrega"]').val()=='' || $('[name="hora_entrega"]').val()==''){

						<?php  noty_js('Faltan datos de Cliente y Horarios','danger') ?>
						$('[name="telefono_cliente"]').focus();

					}else if($('[name="facturar"]').val()==1 && ($('[name="nombre_fiscal"]').val()=='' || $('[name="direccion"]').val()=='' || $('[name="poblacion"]').val()=='' || $('[name="cif"]').val()=='' || $('[name="cp"]').val()=='')){

						<?php  noty_js('Faltan datos de facturación','danger') ?>
						$('[name="nombre_fiscal"]').focus();

					}else{

						<?php if(!isset($_SESSION['ip_admin'])){ ?>
							$('#espera_general').modal('show');
						<?php } ?>

					    var tarta = new Object();
					    var producto = new Object();

					    var t=1;
					    var p=1;
					    $('[name="concep_sum"]').each(function(){


					        if($(this).attr('select_tarta') == 0){

					    		producto[p] = new Object();

					        	producto[p]['nombre'] = $(this).attr('nombre');
					        	producto[p]['cantidad'] = $(this).attr('cantidad');
					        	producto[p]['pvp'] = $(this).attr('pvp');
					        	if($(this).attr('comentarios')!=''){

					        		producto[p]['comentarios'] = $(this).attr('comentarios');

					        	}
					        	p++;

					        }else{

					    		tarta[t] = new Object();
					        	tarta[t]['nombre'] = $(this).attr('nombre');
					        	tarta[t]['pvp'] = $(this).attr('pvp');
					    		tarta[t]['tarta'] = $(this).attr('select_tarta');
					    		tarta[t]['predeterminada'] = $(this).attr('predeterminada');
								tarta[t]['porciones'] = $(this).attr('porciones');
								tarta[t]['incluir_foto'] = $(this).attr('incluir_foto');
								tarta[t]['incluir_mensaje'] = $(this).attr('incluir_mensaje');
								tarta[t]['comentarios'] = $(this).attr('comentarios');


					        	if($(this).attr('select_tarta') == 'personalizada'){

					        		tarta[t]['capa_inferior'] = $(this).attr('capa_inferior');
					        		tarta[t]['capa_media'] = $(this).attr('capa_media');
					        		tarta[t]['capa_superior'] = $(this).attr('capa_superior');
					        		tarta[t]['borde_exterior'] = $(this).attr('borde_exterior');

					        	}if($(this).attr('select_tarta') == 'gominolas'){

					        		tarta[t]['forma'] = $(this).attr('forma');

					        	}// select_tarta: personalizada .....

					        	t++;

					        } // if($(this).attr('select_tarta') == 0){
					        

					    }); // $('[name="concep_sum"]').each(function(){

					    if(t == 1){

					    	tarta = '';
					    }

					    if(p == 1){

					    	producto = '';
					    }

					    $.post('functions/ajax.php',{

				            <?php 

				            	$arr = array(
				            	"id_cliente",
				            	"id_factura",
								"fecha_entrega",
								"hora_entrega",
								"pedido_cobrado",
								"importe_pagado",
								"descuento",
								"facturar","nombre_fiscal","direccion","poblacion","cif","cp");

								foreach ($arr as $key) {

									echo $key.' : $(\'[name="'.$key.'"]\').val(),
									';
								}
				            ?>
				            importe_final_editar : $('[name="importe_final"]').val(),
				            id_pedido_editar : <?php echo $id ?>,
				            producto : producto,
				            tarta : tarta
				        }, 
				        function(data) {
				        $("#respuesta_ajax_js").html(data);
				    });

					} // if($('[name="nombre_cliente"]').val()=='' .....

				} // $('[name="importe_final"]').val() == 0 

			}); // $('#confirmar_pedido').click(function(e){

// pedido_cobrado importe
			$('[name="pedido_cobrado"]').change(function(){

				var val  = $(this).val();
				if(val == 'importe'){

					$('.pp').removeClass('d-none');

				}else{

					$('.pp').addClass('d-none');

				}
			});

			$('[name="facturar"]').change(function(){

				var val  = $(this).val();
				if(val == 1){

					$('#datos_facturacion').removeClass('d-none');

				}else{

					$('#datos_facturacion').addClass('d-none');

				}
			});

			$("[id^='alert_'").on("close.bs.alert", function () {

	            var pvp = $(this).children('[name="concep_sum"]').attr("pvp");
	            var importe = $('[name="importe_final"]').val();
	            var final = importe-pvp;
	            // var f = final/100;
	            var s = String(final);
	            if(s.length == 2){ s = s+".00"; }
	            $('[name="pvp_span"]').html(s);
	            $('[name="importe_final"]').val(s); 

	        });

			$('[name="concepto_producto"]').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {

				if($(this).selectpicker('val') == 124 || $(this).selectpicker('val') == 125 || $(this).selectpicker('val') == 1 || $(this).selectpicker('val') == 126){

			  		$('#comentarios_pastelinos').removeClass('d-none');

			  	}else{

			  		$('#comentarios_pastelinos').addClass('d-none');

			  	}

			  	$('[name="unidades_producto"]').focus();
			  	
			});


			
	</script>
</body>
</html>