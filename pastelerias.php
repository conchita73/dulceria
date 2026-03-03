<?php include 'inc/inc_start.php';  
$card_title ="Pastelerías";


// TABLA XRA SELECT, UPDATE, DELETE, INSERT
$tabla="delegaciones";

// posicion 0 = nombre del form que va en la modal
// posicion 1 = text del button que va a accionar esa modal
$modal_1=array("delegaciones","Crear una Pastelería");


?>
<!DOCTYPE html>
<html lang="es">
<head>

	<?php include 'inc/inc_head.php'; ?>

</head>

<body data-background-full="image" data-image="img/bg-3.png">
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
					<div class="row row-card-no-pd" style="min-height: 600px;">
						<div class="col-md-12">
							<ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
							  	<li class="nav-item">
							    	<a class="nav-link active" id="vitrinas-tab" data-toggle="tab" href="#vitrinas" role="tab" aria-controls="vitrinas" aria-selected="true">Pedidos Vitrinas</a>
							  	</li>
							  	<li class="nav-item">
							    	<a class="nav-link" id="locales-tab" data-toggle="tab" href="#locales" role="tab" aria-controls="locales" aria-selected="false">Listado de Pastelerías</a>
							  	</li>
							</ul>
							<div class="tab-content" id="myTabContent">
							  	<div class="tab-pane fade show active mt-3" id="vitrinas" role="tabpanel" aria-labelledby="vitrinas-tab">
									<div class="card">
										
										<div class="card-body">
											<div class="row">
												<div class="col-12">
													<h3 class="text-center text-success">Crear pedidos de Vitrinas:</h3>
												</div>
												<div class="col-md-6 mt-2" style="border: 1px dotted rgba(0,0,0,.1); padding: 15px;">
													<h4 class="text-info text-center" ><u>Cliente y Horarios:</u></h4>
													<form class="form-row has-info" id="paso_1_form" >

														<input type="hidden" name="id_cliente" id="" value="0">



														<div class="col-12 d-none">
															
															<label class="mt-2">Nombre y apellidos (Rellenar solo en caso de que no sea para vitrina):</label>
															<input type="text" name="nombre_cliente" class="form-control" placeholder="Puedes buscar por nombre, teléfono o email" verify="1">
															
															<label class="mt-2">Tel&eacute;fono del cliente:</label>
															<input type="text" name="telefono_cliente" class="form-control" placeholder="Tel&eacute;fono del cliente" verify="1">

															<label class="mt-2">Email del cliente:</label>
															<input type="text" name="email_cliente" class="form-control" placeholder="Email del cliente" verify="1">
															
															
														</div> <!-- <div class="col-12"> -->
														<div class="col-6 d-none">
															<label class="mt-2">Hora de entrega:</label>
															<input type="text" name="hora_entrega" value="07:00:00" placeholder="Selecciona una hora" class="form-control" verify="1">
														</div>


														<div class="col-12">
															<label class="mt-2">Local de entrega:</label>
															<select name="local_entrega" class="custom-select">
																<?php 

																 	$DB->query('SELECT id,nombre FROM delegaciones');
																  	while($d = $DB->fetch()){
																 
																		echo '<option value="'.$d['id'].'" ';

																		if($d['id'] == $_SESSION['id_delegacion']){

																			echo 'selected="selected"';
																		}

																		echo '>'.utf8_encode($d['nombre']).'</option>';

																  	}
																?>
															</select>
														</div>


														<div class="col-6">
															<label class="mt-2">Fecha de entrega:</label>
															<input type="text" name="fecha_entrega" id="fecha_entrega" class="form-control" placeholder="Fecha de entrega" verify="1">
														</div>
														<div class="col-6">
															<label class="mt-2">Usuario:</label>
															
															<select name="id_usuario" class="custom-select">
																<?php 

																 	$DB->query('SELECT id,nombre FROM usuarios where estado = 1');
																  	while($d = $DB->fetch()){
																 
																		echo '<option value="'.$d['id'].'" ';

																		if($d['id'] == $_SESSION['id_usuario']){

																			echo 'selected="selected"';
																		}

																		echo '>'.utf8_encode($d['nombre']).'</option>';

																  	}
																?>
															</select>
														</div>
															
														<div class="col-6 d-none">
															<label class="mt-2">¿Es para la vitrina?:</label>
															<select name="es_vitrina" class="custom-select">
																<option value="1" selected>Si</option>
																<option value="0">No</option>
															</select>
														</div>
														
													</form>
												</div>
												<div class="col-md-6 mt-2" style="border: 1px dotted rgba(0,0,0,.1); padding: 10px;">
													<h4 class="text-info text-center"><u>Añadir Productos</u></h4>
													<input type="hidden" name="importe_final" value="0">
														<div class="row d-none">
															<div class="col-4">
																<span class=" text_1_2 text-success">Precio Final: <span name="pvp_span"> 0</span> &euro;</span>
															</div>
															<div class="col-4">
																<span class=" text_1_2 text-success">Descuento: <span name="descuento_span"> 0</span> %</span>
															</div>
															<div class="col-4">
																<span class=" text_1_2 text-success">Habitual: <span name="recurrente_span"> No</span> </span>
															</div>
														</div>
														
													
														<ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
														  <li class="nav-item ">
														    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><i class="fas fa-2x fa-birthday-cake text-success"></i> Tartas:</a>
														  </li>
														  <li class="nav-item">
														    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><i class="fas fa-2x fa-shopping-basket text-success"></i> Productos:</a>
														  </li>
														  <li class="nav-item">
														    <a class="nav-link" id="cart-tab" data-toggle="tab" href="#cart" role="tab" aria-controls="cart" aria-selected="false"><i class="fas fa-2x fa-shopping-cart text-success"></i> Finalizar Pedido:</a>
														  </li>
														</ul>
														<div class="tab-content" id="myTabContent">
															<div class="tab-pane fade show active" id="home" role="	tabpanel" aria-labelledby="home-tab">
															<form class="form-row has-info" id="suma_tarta_form">
																<input type="hidden" name="importe_final" value="0">
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
																			
																	</div>
																</form>
								                    		</div>  <!-- id="home" -->

													  		<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
													  			<form class="form-row has-info" id="suma_producto_form">
													  				<div class="col-12">
															  			<input type="hidden" name="importe_final" value="0">
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
															<div class="tab-pane fade" id="cart" role="	tabpanel" aria-labelledby="cart-tab">
																
																<form id="finalizar_pedido" class="form-row has-info">
																	<div class="col-12 d-none">
																		<input type="hidden" name="importe_final" value="0">
																		<label class="mt-2">¿Esta cobrado?</label>
																		<select name="pedido_cobrado" class="custom-select">
																			<option value="No">No</option>
																			<option value="Si">Si</option>
																			<option value="importe">Pagada una parte</option>
																		</select> 

																		<label class="mt-2 d-none pp">Importe pagado:</label>
																		<input type="text" class="form-control  is_number d-none pp" name="importe_pagado" value="0" placeholder="Introduce un importe" verify="0">
																	</div>

																	<div class="col-12 d-none">
																		<input type="hidden" name="importe_final" value="0">
																		<input type="hidden" name="es_recurrente" value="0">
																		<div class="row">
																			<div class="col-6">
																				<label class="mt-2">¿Lleva Factura?</label>
																				<select name="facturar" class="custom-select">
																					<option value="0">No</option>
																					<option value="1">Si</option>
																				</select>
																			</div>
																			<div class="col-6">
																				<label class="mt-2">Porcentaje de descuento:</label>
																				<input type="text" class="form-control  is_number" name="descuento" value="0" placeholder="Introduce un importe" verify="0">
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
																						<input type="text" class="form-control" name="'.$camp.'" value="">
																					</div>';
																				}


																			?>
																		</div>
																	</div>
																	
																	<div id="carrito_div" class="col-12 mt-2">
																	</div>
																	<div class="col-12 text-center">
																		<button type="button" class="btn btn-warning btn-lg  mt-2" id="confirmar_pedido">Confirmar Pedido <span class=" text_1_2 d-none">Precio Final: <span name="pvp_span"> 0</span> &euro;</span></button>
																	</div>
																</form>
															</div> <!-- id="cart" -->
															<div class="col-12 text-center">
																<button type="button" class="btn btn-warning btn-lg  mt-2" id="confirmar_pedido">Guardar / Confirmar Pedido <span class=" text_1_2 d-none">Precio Final: <span name="pvp_span"> 0</span> &euro;</span></button>
															</div>

														</div> <!-- id="myTabContent" -->
												</div> <!-- <div class="col-md-6 mt-2"> -->
											</div> <!-- <div class="row"> -->
										</div> <!-- <div class="card-body"> -->
									</div> <!-- <div class="card"> -->
  								</div> <!-- id="home" -->
  								<div class="tab-pane fade mt-3" id="locales" role="tabpanel" aria-labelledby="locales-tab">
									<div class="card">
										<div class="card-header">
											<div class="card-head-row">
												<h4 class="card-title"><?php echo $card_title; ?></h4>
												
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
											<div class="row">
												<div class="table-responsive">

													<?php 

														echo pintar_tbody('delegaciones',$modal_1,' nombre not like "Ventas Online"')['body'];

													?>

												</div> <!-- <div class="table-responsive"> -->
											</div> <!-- <div class="row"> -->
										</div> <!-- <div class="card-body"> -->
									</div> <!-- <div class="card"> -->
							  	</div>  <!-- id="locales" -->
							</div> <!-- id="myTabContent" -->
						</div> <!-- <div class="col-md-12"> -->
					</div> <!-- <div class="row row-card-no-pd"> -->
				</div> <!-- <div class="page-inner"> -->
				<?php include 'inc/inc_footer.php'; ?>
			</div> <!-- <div class="content content-scroll scrollbar-inner"> -->
		</div> <!-- <div class="main-panel"> -->
	</div> <!-- <div class="wrapper"> -->

	<?php 

		include 'inc/inc_javascript.php'; 

		if(isset($modal_1) && $modal_1[0]!=""){
            pintar_modal_json($modal_1[0],i,$tabla);
            pintar_modal_json($modal_1[0],u,$tabla);
            pintar_modal_json("borrar_registro",d,$tabla);
        }

	?>
	
	<script>

			$('[name="select_tarta_predeterminada"]').change(function(){

            	var v = $(this).val();
            	var n = $('[name="select_tarta_predeterminada"] option:selected').html();
            	

            	$('[name="select_tarta"] option[value="0"]').prop('selected',true);
            	$('.personalizada, .galletas, .pastelon, .gominolas .predeterminada').addClass('d-none');
            	$('#pvp_tarta').html(v+'.00');
            	$('[name="precio_final_tarta"]').val(v+'.00');
            	$('[name="nombre_predeterminada"]').val(n);
            	$('.predeterminada').removeClass('d-none');

            	
			});


            $('[name="select_tarta"]').change(function(){

            	var v = $(this).val();
            	$('[name="select_tarta_predeterminada"] option[value="0"]').prop('selected',true);
            	$('.personalizada, .galletas, .pastelon, .gominolas .predeterminada').addClass('d-none');
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
            	$('[name="precio_final_tarta"]').val(val+'.00');


            }); // $('[name="select_tarta"]').change(function(){
					
						$('[name="tamano_gominolas"],[name="tamano_pastelon"],[name="tamano_galletas"]').change(function(){


				var name = $(this).attr('name');
				var porciones = $('[name="'+name+'"] option:selected').html();
            	var val = $(this).val();
            	$('#pvp_tarta').html(val+'.00');
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


					 if($('[name="fecha_entrega"]').val()==''){

						<?php  noty_js('Faltan fecha de entrega','danger') ?>
						$('[name="fecha_entrega"]').focus();

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
								"nombre_cliente",
								"telefono_cliente",
								"email_cliente",
								"fecha_entrega",
								"hora_entrega",
								"local_entrega",
								"importe_final",
								"pedido_cobrado",
								"importe_pagado",
								"es_vitrina",
								"id_usuario",
								"descuento",
								"es_recurrente",
								"facturar","nombre_fiscal","direccion","poblacion","cif","cp");

								foreach ($arr as $key) {

									echo $key.' : $(\'[name="'.$key.'"]\').val(),
									';
								}
				            ?>
				            id_via_cobro : 1,
				            producto : producto,
				            tarta : tarta
				        }, 
				        function(data) {
				        $("#respuesta_ajax_js").html(data);
				    });

					} // if($('[name="nombre_cliente"]').val()=='' .....

				} // $('[name="importe_final"]').val() == 0 

			}); // $('#confirmar_pedido').click(function(e){

			$('[name="concepto_producto"]').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
			  // alert(0);
			  	$('[name="unidades_producto"]').focus();
			});
			
	</script>
</body>
</html>