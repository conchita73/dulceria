<?php include 'inc/inc_start.php';  
$title_heading="Gestionar Facturas";

$alante = isset($id) ? '' : 'd-none';
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
										<h4 class="card-title col-10"> 
											<button type="button" class="btn btn-round btn-primary atras btn-icon d-none"><i class="fas fa-arrow-circle-left"></i></button>
											Datos de la Factura 
										</h4>
										<div class="col-2 <?php echo $alante ?>" id="alante">
											<button type="button" class="btn btn-round btn-primary alante btn-icon float-right"><i class="fas fa-arrow-circle-right"></i></button>
										</div>
									</div>
								</div>
								<div class="card-body">
									<div class="row has-info">
										<div class="col-12" id="datos_cliente">
											<h4 class=""> 
												<span class="text-danger">*</span>Primero debes asignar el cliente, en caso de que no exista, se creará uno nuevo
												<br>
												<span class="text-danger">**</span>Si modificas cualquier dato de un cliente asignado, se modificará en su ficha de cliente
											</h4>

											<form action="" class="form-row" id="form_cliente">

												<?php 

													$accion = isset($id) ? u : i;
													$edit = isset($id) ? '_editar' : '';

													echo '<input type="hidden" name="accion" value="'.$accion.'">';

													$t = isset($id) ? 'tabla_editar' : 'tabla_insertar';
													echo '<input type="hidden" name="'.$t.'" value="clientes">';

													if(isset($id)){ 

																		
														$c = $DB->inner_join('c.*','facturas f inner join clientes c','f.id_cliente = c.id where f.id='.$id);
														$e = $c[f][0];

														echo '<input type="hidden" name="id_editar" value="'.$e['id'].'">
														<input type="hidden" name="noty" value="1">';
													}


													$arr = array(
															"nombre"=>array(
																"label"=>'Buscar Cliente por Nombre,cif,telefono,email',
																"verify"=>1),
															"cif"=>array(
																"label"=>'CIF / NIF',
																"verify"=>1),
															"direccion"=>array(
																"label"=>'Direcci&oacute;n',
																"verify"=>1),
															"cp"=>array(
																"label"=>'C&oacute;digo Postal',
																"verify"=>1),
															"id_provincia"=>array(
																"label"=>'Provincia',
																"verify"=>1),
															"email"=>array(
																"label"=>'E-mail',
																"verify"=>1),
															"telefono"=>array(
																"label"=>'Tel&eacute;fono',
																"verify"=>1),
															"descuento"=>array(
																"label"=>'Descuento',
																"verify"=>1),
															"es_recurrente"=>array(
																"label"=>'¿Es recurrente?',
																"verify"=>1)

															);

													foreach ($arr as $key => $value) {
																	

														${$key} = isset($e[$key]) ? utf8_encode($e[$key]) : ($key == 'descuento' ? 0 : '');

														
														echo '<label class="mt-2">'.$value['label'].':</label>';

														
														if($key == 'id_provincia'){

												
															echo '<select name="id_provincia" class="custom-select">';
															$DB->query('SELECT id,provincia FROM provincias');
														  	while($d = $DB->fetch()){
														 		
														
														 		echo '<option value="'.$d['id'].'">'.utf8_encode($d['provincia']).'</option>';
														  	}

														  	echo '</select>';

														}else if($key == 'es_recurrente'){

															echo '<select name="es_recurrente" class="custom-select">
																<option value="0">No</option>
																<option value="1">Si</option>
																</select>';

														}else{


															echo '<input type="text" class="form-control" name="'.$key.$edit.'" id="'.$key.'" placeholder="'.$value['label'].'" value="'.${$key}.'" verify="'.$value['verify'].'">';

														}
														
																	
													}

												?>
											
												<button type="button" class="mt-3 btn btn-warning float-right ml-auto" id="asignar_cliente">
			                                		<b>Asignar Cliente</b>
			                            		</button> 
					    	
											</form>
										</div> <!-- <div class="col"> -->
										<div class="col-12 d-none" id="datos_factura">
											<h4 class=""> 
												<span class="text-danger">*</span>Primero debes añadir todos los conceptos de la factura. Esto calculará el PVP automaticamente
											</h4>
											<ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
												  <li class="nav-item ">
												    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><i class="fas fa-2x fa-birthday-cake text-success"></i> Tartas:</a>
												  </li>
												  <li class="nav-item">
												    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><i class="fas fa-2x fa-shopping-basket text-success"></i> Productos:</a>
												  </li>
												  <!-- <li class="nav-item">
												    <a class="nav-link" id="cart-tab" data-toggle="tab" href="#cart" role="tab" aria-controls="cart" aria-selected="false"><i class="fas fa-2x fa-shopping-cart text-success"></i> Finalizar Pedido:</a>
												  </li> -->
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
															<label class="mt-2 d-none galletas" for="subject">Tama&ntilde;o:</label>
									                        <select name="tamano_galletas" class="form-control d-none galletas custom-select">
									                          <option value="11">1 </option>
									                          <option value="16">2</option>
									                          <option value="21">3</option>
									                          <option value="36">4</option>
									                        </select>

									                    	<label class="mt-2 d-none pastelon" for="subject">Tama&ntilde;o:</label>
									                        <select name="tamano_pastelon" class="form-control d-none pastelon custom-select">
									                          <option value="13">1</option>
									                          <option value="16">2</option>
									                          <option value="20">3</option>
									                        </select>

									                        <!-- gominolas -->
									                   		<label class="mt-2 d-none gominolas" for="subject">Forma de la tarta:</label>
									                        <select name="forma_gominolas" class="form-control d-none gominolas custom-select">
									                          <option value="Cuadrada">Cuadrada</option>
									                          <option value="Redonda">Redonda</option>
									                        </select>

									                   		<label class="mt-2 d-none gominolas" for="subject">Tama&ntilde;o:</label>
									                        <select name="tamano_gominolas" class="form-control d-none gominolas custom-select">
									                          <option value="10">1</option>
									                          <option value="15">2</option>
									                          <option value="20">3</option>
									                        </select>
									                        <!-- gominolas -->

									                        <!-- personalizadas -->
									                   		<label class="mt-2 personalizada d-none" for="subject">Tama&ntilde;o:</label>
									                        <select name="tamano_personalizada" class="form-control personalizada d-none custom-select">
									                        	<option value="8">0</option>
									                          <option value="11" selected="selected">1</option>
									                          <option value="14">2</option>
									                          <option value="19">3</option>
									                          <option value="25">4</option>
									                        </select>

									                   		<label class="mt-2 personalizada d-none" for="subject">Capa Inferior:</label>
									                        <select name="capa_inferior_personalizada" class="personalizada d-none custom-select form-control">
									                          <option value="Crema">Crema</option>
									                          <option value="Chocolate">Chocolate</option>
									                          <option value="Nata">Nata</option>
									                          <option value="Trufa">Trufa</option>
									                          <option value="Fresa">Fresa</option>
									                          <option value="Lim&oacute;n">Lim&oacute;n</option>
									                          <option value="Choc. Kinder">Choc. Kinder</option>
									                          <option value="Moca">Moca</option>
									                        </select>

									                   		<label class="mt-2 personalizada d-none" for="subject">Capa Media:</label>
									                        <select name="capa_media_personalizada" class="personalizada d-none custom-select form-control">
									                          <option value="Crema">Crema</option>
									                          <option value="Chocolate">Chocolate</option>
									                          <option value="Nata">Nata</option>
									                          <option value="Trufa">Trufa</option>
									                          <option value="Fresa">Fresa</option>
									                          <option value="Lim&oacute;n">Lim&oacute;n</option>
									                          <option value="Choc. Kinder">Choc. Kinder</option>
									                          <option value="Moca">Moca</option>
									                        </select>

									                   		<label class="mt-2 personalizada d-none" for="subject">Capa Superior:</label>
									                        <select name="capa_superior_personalizada" class="personalizada custom-select d-none form-control">
									                          <option value="Crema">Crema</option>
									                          <option value="Chocolate">Chocolate</option>
									                          <option value="Nata">Nata</option>
									                          <option value="Trufa">Trufa</option>
									                          <option value="Fresa">Fresa</option>
									                          <option value="Lim&oacute;n">Lim&oacute;n</option>
									                          <option value="Choc. Kinder">Choc. Kinder</option>
									                          <option value="Moca">Moca</option>
									                        </select>

									                   		<label class="mt-2 personalizada d-none" for="subject">Borde Exterior:</label>
									                        <select name="borde_exterior_personalizada" class="personalizada custom-select d-none form-control">
									                          <option value="Merengue">Merengue</option>
									                          <option value="Nata">Nata</option>
									                          <option value="Trufa">Trufa</option>
									                          <option value="Moca">Moca</option>
									                          <option value="Fid. Chocolate">Fid. Chocolate</option>
									                          <option value="Almendras">Almendras</option>
									                          <option value="Sin Borde">Sin Borde</option>

									                        </select>
									                        
									                        <!-- personalizadas -->
															<!-- <label class="mt-2 personalizada gominolas galletas pastelon predeterminada d-none" for="subject">Mensaje (Opcional):</label>
															<textarea name="incluir_mensaje" class="form-control personalizada predeterminada gominolas galletas pastelon d-none"></textarea> -->

									                    	<label class="mt-2 personalizada predeterminada gominolas galletas pastelon d-none" for="subject">Foto (Opcional. Suplemento de 4&euro;):</label>
										                        <select name="incluir_foto" class="custom-select personalizada predeterminada gominolas galletas pastelon d-none">
										                          <option value="No">No</option>
										                          <option value="Si">Si</option>
										                        </select>
										                        <input type="file" class="form-control input_form_white d-none" name="foto">
															
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
													  			<input type="hidden" name="is_factura" value="1">
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
													            
												            	<h3 class="mt-2 text-center">¿Es personalizado?</h3>
													            <div class="row ">
																	<div class="col-6">
																		<label for="">Concepto Personalizado:</label>
																		<input type="text" class="form-control" name="conceptos_personalizado" value="" placeholder="Introduce un concepto" verify="0">
																	</div> 
																	<div class="col-6">
																		<label for="">Pvp Personalizado</label>
																		<input type="text" class="form-control is_number" name="pvp_personalizado" value="" placeholder="Introduce un pvp" verify="0">
																	</div>
																	<div class="col-12 text-center">
																		<button type="button" class="btn btn-warning btn-lg  mt-2" id="suma_concepto">A&ntilde;adir Producto</button>
																	</div>
																</div> <!-- <div class="row "> -->
															</div> <!-- <div class="col-12"> -->
											  			</form>
													</div> <!-- id="profile" -->
													
												</div> <!-- id="myTabContent" -->
												<div id="carrito_div" class="col-12 mt-2">
												</div>
									        
											<form class="form-horizontal" id="facturas_form_form" method="post" action="#">
												<input type="hidden" name="id_cliente" id="id_cliente_factura" value="0">
												
												<input type="hidden" name="id_via_cobro" value="1">
												<input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
												<input type="hidden" name="id_delegacion" value="<?php echo $_SESSION['id_delegacion'] ?>">
												<input type="hidden" id="accion" name="accion" value="<?php echo $accion ?>">
												<input type="hidden" name="<?php echo $t ?>" value="facturas">
												<input type="hidden" name="importe_final" id="importe_final" value="0">
                        						<div class="form-group">
	                        						<div class="col-xs-6 has-info">
									                
									                <label class="mt-2">Fecha de la factura:</label>
									                <input type="text" class="form-control" name="fecha_cobro" id="fecha_cobro" value="<?php echo date('d/m/Y'); ?>" placeholder="Fecha de la factura" value="" verify="1">
									                </div>
									                <div class="col-xs-6 has-info">
									                
									                <label class="mt-2">¿Esta cobrada? :</label><br><select verify="1" name="estado" id="estado" class="custom-select form-control"><option value="1">Si</option><option value="0">No</option></select>
									                </div>	
									                <label class="mt-2">Descuento (Se aplicará al crear la factura):</label><br>
									                <input type="text" class="form-control" name="descuento"  placeholder="Descuento" value="" verify="1" value="0">
									                </div>											
													<h3 class="text-right mt-2 text-success">Importe factura: <span id="pvp_span" name="pvp_span">0</span>&euro;</h3>
													<button type="button" class="mt-3 btn btn-warning float-right ml-auto" id="crear_factura">
				                                		<b>Crear Factura</b> 
				                            		</button> 
												</div>
											</form>
										</div> <!-- <div class="col"> -->
									</div> <!-- <div class="row"> -->
								</div> <!-- <div class="card-body"> -->
								<!-- <div class="card-footer">
								    <button type="button" class="btn btn-warning pull-right" id="crear_form_button">
                                	<b><?php echo $title_heading; ?></b>
                            		</button> 
								 </div>
 -->							</div> <!-- <div class="card"> -->
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

			$.widget("custom.catcomplete",$.ui.autocomplete,
				{_renderMenu:function(e,t)
					{
					var a=this,o="";
					$.each(t,function(t,c){
						c.category!=o&&(e.append("<li class='ui-autocomplete-category'>"+c.category+"</li>"),o=c.category),a._renderItemData(e,c)
						})
					}
				}),
					$("[name='nombre']").catcomplete({minLength:2,source:"functions/ajax.php?buscar_cliente_autocomplete=1",
						select:function(e,t){

					    	$(this).val(t.item.value),
					    	$("#id_cliente_factura").val(t.item.id_cliente),
					    	$("[name='cif']").val(t.item.cif),
					    	$("[name='direccion']").val(t.item.direccion),
					    	$("[name='telefono']").val(t.item.telefono),
					    	$("[name='nombre_fiscal']").val(t.item.nombre_fiscal),
					    	$("[name='email']").val(t.item.email),
					    	$("[name='poblacion']").val(t.item.poblacion),
					    	$("[name='cp']").val(t.item.cp),
					    	$("[name='descuento']").val(t.item.descuento),
					    	$('select[name="id_provincia"] option[value="'+t.item.id_provincia+'"]').attr('selected',true),
					    	$('select[name="es_recurrente"] option[value="'+t.item.es_recurrente+'"]').attr('selected',true),
					    	setTimeout(function(){$('#asignar_cliente').focus()},500)

					}
			}); // $.widget("custom.catcomplete",$.ui.autocomplete,


			$('#asignar_cliente').click(function(e){

				var id_cliente = $('#id_cliente_factura').val();

				if(id_cliente == 0){

					// creamos cliente nuevo
					$('#form_cliente').form_validator_ajax();

				}

				$('#datos_factura').removeClass('d-none');
				$('.atras').removeClass('d-none');
				$('.atras').focus();
				$('#datos_cliente').addClass('d-none');
				
				
			}); // $('#asignar_cliente').click(function(e){

			$('.atras').click(function(){

				$(this).addClass('d-none');
				$('#datos_factura').addClass('d-none');
				$('#datos_cliente').removeClass('d-none');
				$('#alante').removeClass('d-none');


			}); // $('.atras').click(function(){

			$('.alante').click(function(){

				$('.atras').removeClass('d-none');
				$('#datos_factura').removeClass('d-none');
				$('#alante').addClass('d-none');
				$('#datos_cliente').addClass('d-none');

			}); // $('.alante').click(function(){


			$('#estado').html('<option value="1">Si</option><option value="0">No</option>');




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


			$('#crear_factura').click(function(e){
				
				var pvp = $('[name="importe"]').val();
				var fecha_cobro = $('[name="fecha_cobro"]').val();

				if(pvp == 0 || fecha_cobro==''){

					<?php noty_js('Tienes que introducir algún concepto y la fecha','danger') ?>

				}else{


					$.post('functions/ajax.php',{

				            tabla_editar: "clientes",
				            accion : 'u',
				            noty : 1,
				            id_editar : $('[name="id_cliente"]').val(),
				            <?php 

				            	$arr = array(
								"nombre",
								"cif",
								"direccion",
								"cp",
								"id_provincia",
								"email",
								"telefono",
								"descuento");

								foreach ($arr as $key) {

									echo $key.'_editar : $(\'[name="'.$key.'"]\').val(),
									';
								}
				            ?>
				            
				        }, 
				        function(data) {
				        $("#respuesta_ajax_js").html(data);
				    });

					var concepto = new Object();
				    var b=1;
		
					    $('[name="concep_sum"]').each(function(){


					        if($(this).attr('select_tarta') == 0){

					    		concepto[b] = new Object();

					        	concepto[b]['nombre'] = $(this).attr('nombre');
					        	concepto[b]['cantidad'] = $(this).attr('cantidad');
					        	concepto[b]['pvp'] = $(this).attr('pvp');

					        	b++;

					        }else{

					    		concepto[b] = new Object();
					        	concepto[b]['nombre'] = $(this).attr('nombre');
					        	concepto[b]['pvp'] = $(this).attr('pvp');
					    		concepto[b]['tarta'] = $(this).attr('select_tarta');
					    		concepto[b]['predeterminada'] = $(this).attr('predeterminada');
								concepto[b]['porciones'] = $(this).attr('porciones');
								concepto[b]['incluir_foto'] = $(this).attr('incluir_foto');
								concepto[b]['incluir_mensaje'] = $(this).attr('incluir_mensaje');

					        	if($(this).attr('select_tarta') == 'personalizada'){

					        		concepto[b]['capa_inferior'] = $(this).attr('capa_inferior');
					        		concepto[b]['capa_media'] = $(this).attr('capa_media');
					        		concepto[b]['capa_superior'] = $(this).attr('capa_superior');
					        		concepto[b]['borde_exterior'] = $(this).attr('borde_exterior');

					        	}if($(this).attr('select_tarta') == 'gominolas'){

					        		concepto[b]['forma'] = $(this).attr('forma');

					        	}// select_tarta: personalizada .....

					        	b++;

					        } // if($(this).attr('select_tarta') == 0){
					        

					    }); // $('[name="concep_sum"]').each(function(){


				    // aqui vamos a usar finalmente la funtion post
				    // para poder pasar todos los datos que queramos
				    $.post('functions/ajax.php',{

				            <?php echo $t ?>: "facturas",
				            accion : $('[name="accion"]').val(),
				            id_via_cobro : $('[name="id_via_cobro"]').val(),
				            id_usuario : $('[name="id_usuario"]').val(),
				            fecha_cobro : $('[name="fecha_cobro"]').val(),
				            id_delegacion : $('[name="id_delegacion"]').val(),
				            id_cliente : $('[name="id_cliente"]').val(),
				            estado : $('[name="estado"]').val(),
				            descuento : $('[name="descuento"]').val(),
				            importe : ($('[name="importe_final"]').val()),
				            concepto : concepto
				        }, 
				        function(data) {
				        $("#respuesta_ajax_js").html(data);
				    });

				}
			});

			$('[name="concepto_producto"]').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
			  // alert(0);
			  	$('[name="unidades_producto"]').focus();
			});
			
			// 
			// <?php if(isset($_SESSION['ip_admin'])){ ?>
			// 	$('#datos_factura').removeClass('d-none');
			// 	$('.atras').removeClass('d-none');
			// 	$('#datos_cliente').addClass('d-none');
			// <?php } ?>
			// al crear la factura vamos a actualizar los datos del cliente

		}); // $(document).ready(function(){
	</script>
</body>
</html>