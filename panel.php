<?php include 'inc/inc_start.php';  

$card_title = "Panel Principal";

// TABLA XRA SELECT, UPDATE, DELETE, INSERT
$tabla="t_body";
// posicion 0 = nombre del form que va en la modal
// posicion 1 = text del button que va a accionar esa modal

// $modal_1=array('t_body_form','Crear un Tbody');


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

		<div class="main-panel" >
			<div class="content content-scroll scrollbar-inner">
				<div class="page-inner">

					<div class="row row-card-no-pd pt-4">
						<div class="col-md-12" style="clear: both;">
							<div class="card">
								<div class="card-header">
									<div class="card-head-row">
										<h4 class="card-title col-3"><?php echo $card_title; ?> </h4>
										<div class="col-9">
											<div class="widget widget-info widget-padding-sm ml-auto">
												<div class="  text_1_1 text-success text-right widget-subtitle plugin-date ml-auto"></div>
                                				<div class=" text_1_1 text-success text-right widget-big-int plugin-clock">00:00</div>
                                			</div>
										</div>
									</div>
									<div class="row">
										<div class="col-12 text-center">
											<button type="button" 
													class="mt-2 btn btn-outline-success mr-1"
													data-toggle="modal" data-target="#movimientos_bancarios">
													<i class="fas fa-plus"></i> Mov. Monetario
											</button>
											<?php if($_SESSION['id_usuario'] == 1){ ?>
												<button type="button" 
														class="mt-2 btn btn-outline-info mr-1"
														data-toggle="modal" data-target="#sumar_cita_modal">
														<i class="fas fa-plus"></i> Evento Agenda
												</button>
											<?php } ?>
										</div>
										<?php if(isset($_SESSION['ip_admin'])){ ?>
										<div class="col-md-4 offset-md-4">
											<div class="input-group mt-3">
											  	<input type="text" class="form-control" name="buscar_cupon" placeholder="Introduce el cupón" id="buscar_cupon">
											  	<input type="hidden" name="id_cupon" id="id_cupon" value="">
											  	<div class="input-group-append">
											    	<button class="btn btn-outline-primary" type="button" id="canjear_cupon">Canjear</button>
											  	</div>
											</div>
										</div>
										<?php } ?>
									</div>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-12">
											<h3 class="text-center text-success">Crear pedidos:</h3>
										</div>
										<div class="col-md-6 mt-2" style="border: 1px dotted rgba(0,0,0,.1); padding: 15px;">
											<h4 class="text-info text-center" ><u>Cliente y Horarios:</u></h4>
											<form class="form-row has-info" id="paso_1_form" >
												<input type="hidden" name="id_cliente" id="" value="0">
												<div class="col-12">
													
													<label class="mt-2">Nombre y apellidos (Rellenar solo en caso de que no sea para vitrina):</label>
													<input type="text" name="nombre_cliente" class="form-control" placeholder="Puedes buscar por nombre, teléfono o email" verify="1">
													
													<label class="mt-2">Tel&eacute;fono del cliente:</label>
													<input type="text" name="telefono_cliente" class="form-control" placeholder="Tel&eacute;fono del cliente" verify="1">

													<label class="mt-2">Email del cliente:</label>
													<input type="text" name="email_cliente" class="form-control" placeholder="Email del cliente" verify="1">
													
													<label class="mt-2">Fecha de entrega:</label>
													<input type="text" name="fecha_entrega" id="fecha_entrega" class="form-control" placeholder="Fecha de entrega" verify="1">
												</div> <!-- <div class="col-12"> -->
												<div class="col-6">
													<label class="mt-2">Hora de entrega:</label>
													<input type="text" name="hora_entrega" value="" placeholder="Selecciona una hora" class="form-control" verify="1">
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
													

												<div class="col-6">
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
													<label class="mt-2">¿Es para la vitrina?:</label>
													<select name="es_vitrina" class="custom-select">
														<option value="0">No</option>
														<option value="1">Si</option>
													</select>
												</div>
												
											</form>
										</div>
										<div class="col-md-6 mt-2" style="border: 1px dotted rgba(0,0,0,.1); padding: 10px;">
											<h4 class="text-info text-center"><u>Añadir Productos</u></h4>
											<input type="hidden" name="importe_final" value="0">
												<div class="row">
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
													<div class="tab-pane fade" id="cart" role="	tabpanel" aria-labelledby="cart-tab">
														
														<form id="finalizar_pedido" class="form-row has-info">
															<div class="col-12">
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

															<div class="col-12">
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
															
														</form>
													</div> <!-- id="cart" -->
													<div class="col-12 text-center mt-3">
														<button type="button" class="btn btn-warning btn-lg  mt-2" id="confirmar_pedido">Guardar / Confirmar Pedido (<span class=" text_1_2">Precio Final: <span name="pvp_span"> 0</span> &euro;</span>)</button>
													</div>
												</div> <!-- id="myTabContent" -->
										</div> <!-- <div class="col-md-6 mt-2"> -->
									</div> <!-- <div class="row"> -->
										
									<div class="row">
										<div class="col-12 text-center">
											<hr>
											<h3 class="text-success">Pedidos Por entregar 
												<div class="input-group mb-3 mt-3 has-info col-md-6 offset-md-3 col-lg-4 offset-lg-4">
												  <input type="text" class="form-control datepicker"  name="comanda_input" placeholder="Introduce un fecha" value="<?php echo date('d/m/Y'); ?>">
												  <div class="input-group-append">
												    <button class="btn btn-info" id="comanda_button" type="button">Imprimir Comanda</button>
												  </div>
												</div>
											</h3>
										</div>
										<?php 

										 	

										 	for ($i=1; $i <=4 ; $i++) { 

										 		${'tartas_'.$i} = '';
										 		${'productos_'.$i} = '';


										 		$DB->query('SELECT p.*,d.nombre,c.nombre as cliente,c.id as id_cliente, c.telefono
										 				FROM pedidos p inner join delegaciones d 
										 				on p.local_entrega = d.id 
														inner join clientes c on p.id_cliente = c.id
										 				WHERE p.estado = 0 and
										 				p.fecha_entrega = "'.date('Y-m-d').'" 
										 				and p.local_entrega = '.$i);

										 		if($DB->rows()>0){

											 		echo '<div class="col card-list box_scroll">';

											 		$v=0;
											 		while($d = $DB->fetch()){

											 			if($v == 0){

											 				echo '<div class="col-12">
																	<u class="text_1_2 ">'.utf8_encode($d['nombre']).'</u>
																	
																	<hr>
																</div>';
											 			}

											 			$h_entrega = 'Sin horario especificado';
											 			if($d['hora_entrega'] != '00:00:00'){

											 				list($hr,$mn,$sg) = explode(':',$d['hora_entrega']);
											 				$h_entrega = 'Hora entrega '.$hr.':'.$mn;

											 			}

											 			$tel = $d['telefono'] !='' ? '('.$d['telefono'].')' : '';
											 			$cliente = $d['id_cliente'] == 0 ? $d['nombre']. ' - Vitrina' : $d['cliente'];
											 			echo '<div class="item-list" >
																<div class="info-user ml-3">
																<span class="text-success">'.$h_entrega.'</span>
																<div class="username"> '.utf8_encode($cliente).'<span class="text-muted"> '.$tel.'</span>
																<h3 class="text-muted">
																<a href="/dulceria/functions/descargar_pedidos.php?id_pedido='.$d['id'].'" 
																		target="_blank"
																	 	title="">Ver pedido</a></h3>
																	 	<a href="#" class="mt-2 "
																		name="editar_pop_up" tabla="pedidos"
																		id="'.$d['id'].'">
																		<i class="fas fa-plus"></i> Editar Pedido
																	</a>
																</div>

																<div class="status text-danger text_1_2">'.number_format($d['importe_final'],2,'.','').' &euro;
																</div>
															</div>';

															if($d['id_cliente']>0){

																echo '<button class="btn btn-icon btn-info btn-round btn-sm ml-2"
																	id_pedido="'.$d['id'].'"
																	data-toggle="tooltip" 
																	name="ver_albaran"
																	data-placement="bottom" 
																	title="Click ver albarán"
																	>
																	<i class="fa fa-print"></i>
																</button>';

															}
															
															echo '<button class="btn btn-icon btn-warning btn-round btn-sm ml-2"
																id_pedido="'.$d['id'].'"
																facturar="'.$d['facturar'].'"
																es_recurrente="'.$d['es_recurrente'].'"
																data-toggle="tooltip" 
																data-placement="bottom" 
																title="Doble Click para entregar"
																name="entregar_pedido">
																<i class="fa fa-edit"></i>
															</button>
															<button class="btn btn-icon btn-danger btn-round btn-sm ml-2"
																	id_pedido="'.$d['id'].'"
																	data-toggle="tooltip" 
																	data-placement="bottom" 
																	title="Doble Click para cancelar"
																	name="cancelar_pedido">
																	<i class="fa fa-times"></i>
																</button>
															</div>';


											 			$v++;
											  		} // while($d = $DB->fetch()){

											  		echo '</div>';
											  	}


										 	} // for ($i=1; $i <=3 ; $i++) { 


										?>
										
									</div><!-- <div class="row"> -->
									<!-- <div class="row">
											<div class="col-12 text-center">
												<hr>
												<button type="button" 
													class="mt-2 btn btn-outline-primary mr-1"
													data-toggle="modal" data-target="#tareas">
													<i class="fas fa-plus"></i> Tarea
												</button>
												<button type="button" 
													class="mt-2 btn btn-outline-success mr-1"
													data-toggle="modal" data-target="#movimientos_bancarios">
													<i class="fas fa-plus"></i> Mov. Monetario
												</button>
												<button type="button" 
													class="mt-2 btn btn-outline-warning mr-1"
													name="editar_pop_up" tabla="facturas">
													<i class="fas fa-plus"></i> Factura
												</button>
												<button type="button" 
													class="mt-2 btn btn-outline-info mr-1"
													data-toggle="modal" data-target="#prevision"
													><i class="fas fa-eye"></i> Previsión Pedidos
												</button>
												<hr>
											</div>
									</div> -->
									<div class="row mt-3">
										<div class="col-12">
											<h3 class="text-center text-info">Historial de Pedidos Completos</h3>
											<div class="table-responsive">
												<?php


													echo pintar_tbody('pedidos','',' where p.estado = 0 and fecha_entrega>="'.date('Y-m-d').'"')['body'];
			 
												?>
											</div>
										</div>
									</div>
									<div class="row">
										<?php /*
										<div class="col-lg-5 col-md-6">
											<div class="card">
												<div class="card-body">
													<ul class="nav nav-pills nav-primary" id="pills-tab" role="tablist">
														<li class="nav-item">
															<a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Tareas por realizar</a>
														</li>
														<li class="nav-item">
															<a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Realizados</a>
														</li>
													</ul>
													<div class="tab-content mt-2 mb-3" id="pills-tabContent">
														<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
															<div class="card-list box_scroll">
																<?php  

																	$fd = $_SESSION['Authenticated'] == 1 ? '' : 'and t.id_delegacion='.$_SESSION['id_delegacion'] ;

																 	$DB->query('SELECT * FROM tareas t WHERE fecha between "'.$year.'-'.$mes.'-01" and "'.$year.'-'.$mes.'-31" and estado = 0 '.$fd.' order by fecha,hora ASC');

																 	$gastos_total = 0;
																  	while($d = $DB->fetch()){

																  		$gasto = '';
																  		if($d['importe']>0){

																  			$gasto = number_format(($d['importe']/100),2,'.','');
																 			$gastos_total += $gasto;

																 			$gasto.=' &euro;'; 
																  		}

																  		$hora = '';
																  		if($d['hora']!='00:00:00'){

																  			$hora =  '<br><small>'.$d['hora'].'</small>';
																  		}
																 		
																 		list($y,$m,$dia) = explode('-',$d['fecha']);

																 		echo '<div class="item-list" >
																				<span class="text_1_5 text-success">D&iacute;a '.$dia.$hora.'</span>
																				<div class="info-user ml-3">
																					<div class="username"> '.utf8_encode($d['titulo']);

																		if($d['notas']!=''){

																			echo '<br>
																				<span class="text-muted">
																					'.utf8_decode($d['notas']).'
																				</span>';
																		}


																		echo '	</div>
																					<div class="status text-danger text_1_2">'.$gasto.' 
																					</div>
																				</div>
																				<button class="btn btn-icon btn-warning btn-round btn-sm"
																					id_tarea="'.$d['id'].'"
																					id_tipo_tarea="'.$d['id_tipo_tarea'].'"
																					data-toggle="tooltip" 
																					data-placement="bottom" 
																					title="Doble Click para gestionar"
																					name="gestionar_tarea">
																					<i class="fa fa-edit"></i>
																				</button>
																				<button class="btn btn-icon btn-danger btn-round btn-sm ml-2"
																					id_tarea="'.$d['id'].'"
																					id_tipo_tarea="'.$d['id_tipo_tarea'].'"
																					data-toggle="tooltip" 
																					data-placement="bottom" 
																					title="Doble Click para Borrar"
																					name="borrar_tarea">
																					<i class="fa fa-times"></i>
																				</button>
																			</div>';

																  	}

																?>

															</div> <!-- <div class="card-list"> -->
														</div> <!-- pills-home -->
														<div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
															<div class="card-list box_scroll">
															<?php  

																 	$DB->query('SELECT t.fecha,
																 						t.importe,
																 						t.fecha_realizada,
																 						t.titulo,
																 						u.nombre
																 				FROM tareas t inner join usuarios u on t.id_usuario = u.id WHERE fecha between "'.$year.'-'.$mes.'-01" and "'.$year.'-'.$mes.'-31" and t.estado = 1 '.$fd.' order by fecha ASC');

																 	$gastos_total = 0;
																  	while($d = $DB->fetch()){

																  		$gasto = '';
																  		$span_gasto = '';
																  		if($d['importe']>0){

																  			$gasto = number_format(($d['importe']/100),2,'.','');
																 			$gastos_total += $gasto;

																 			$gasto.=' &euro;'; 

																 			$span_gasto=' <span class="text-danger "> ('.$gasto.')</span>';
																  		}
																 		
																 		list($y,$m,$dia) = explode('-',$d['fecha']);

																		$pf = explode(" ",$d['fecha_realizada']);
									                                    $date=formatear_fecha($pf[0],m,i);
									                                    //$nueva_fecha=formatear_fecha($resultado[f][$x][$key],m,i);
									                                    $date .= " ".$pf[1];

																 		echo '<div class="item-list">
																				<span class="text_1_5 text-success">D&iacute;a '.$dia.'</span>
																				<div class="info-user ml-3">
																					<div class="username">'.utf8_encode($d['titulo']).'  ('.utf8_encode($d['nombre']).')</div>
																					
																					<div class="status text_1_1"> '.$date.' '.$span_gasto.' </div>
																				</div>
																				<i class="fa fa-thumbs-up fa-2x text-success"></i>
																			</div>';

																  	}

																?>
															</div> <!-- <div class="card-list"> -->
														</div> <!-- pills-profile -->
													</div> <!-- tabContent -->
												</div> <!-- <div class="card-body"> -->
											</div> <!-- <div class="card"> -->
										</div> <!-- <div class="col-sm-5"> -->
										*/
										 if($_SESSION['Authenticated'] ==1 ){ ?>
										<div class="col-md-6 text-primary">
											<div class="card">
												<div class="card-body">
													<div class="card-title fw-mediumbold text-center mb-4">Resultado</div>
													
														
														<?php  

															$fd = $_SESSION['Authenticated'] == 1 ? '' : 'and t.id_delegacion='.$_SESSION['id_delegacion'] ;
															$m = ($mes+1);
															$m = $m==13 ? 1 : $m;

														 	$DB->query('SELECT * FROM movimientos_bancarios t where fecha between "'.date('Y-m-d').' 00:00:00" and "'.date('Y-m-d').' 23:59:59" '.$fd.' order by fecha ASC');

														 	$gastos_total = 0;
														 	$ingreso_total = 0;

														 	$li='';
														  	while($d = $DB->fetch()){

														 		$importe = number_format(($d['importe']/100),2,'.','');
														 		if($d['tipo'] == 1){

														 			$ingreso_total += $importe;
														 			$class = 'success';
														 			$importe='+ '.$importe;

														 		}else{

														 			$gastos_total += $importe;
														 			$class = 'danger';
														 			$importe='- '.$importe;

														 		}

														 		list($f,$hora) = explode(' ',$d['fecha']);

														 		$li.= '<li class="feed-item feed-item-danger">
																		<time class="date text-warning" datetime="9-25">'.$hora.'</time>
																		<span class="text">'.utf8_encode($d['concepto']).' <br>
																			<span class="text-'.$class.'">
																				'.$importe.' &euro;
																			</span>
																		</span>
																	</li>';


														  	}
														  	echo '  <ol class="activity-feed box_scroll">
															  			<li class="feed-item feed-item-danger">
																			<time class="date text-success" datetime="9-25">D&iacute;a Completo:</time>
																			<span class="text">Gasto Agrupado <br><span class="text-danger "> - '.$gastos_total.' &euro;</span></span>
																			<br>
																			<span class="text">Ingreso Agrupado <br><span class="text-success "> + '.$ingreso_total.' &euro;</span></span><br>
																			Beneficio : <br>
																			<span class="text-warning ">  '.($ingreso_total-$gastos_total).' &euro;</span></span>
																			
																		</li>
															  			'.$li.'
														  			</ol>';

														?>
													
													
												</div> <!-- <div class="card-body"> -->
											</div> <!-- <div class="card"> -->
										</div> <!-- <div class="col-sm-3"> -->

										<div class="col-md-6">
											<div class="card card-primary bg-primary-gradient mt-4">
												<div class="card-body">
													<h1 class="text-center fw-bold"><u>Ingresos <?php echo $mes_datos[$mes] ?></u></h1>

													<?php 

														$gastos = 0;
														$compras = 0;
														$beneficio_total = 0;

														for ($i=1; $i <=4 ; $i++) { 
																
															echo '<h4 class="mt-4 b-b1 pb-2 mb-4 fw-bold d-flex justify-content-between">';	

															$delg = $DB->select('nombre','delegaciones','id='.$i);

															echo '<span><i class="fas fa-store mr-1"></i> '.utf8_encode($delg[f][0]['nombre']).'</span>
																	<span>';

															$DB->query('SELECT * FROM movimientos_bancarios t where fecha between "'.date('Y-m-01').'  00:00:00" and "'.date('Y-m-31').' 23:59:59" and id_delegacion = '.$i.' order by fecha ASC');

															${'ingreso_'.$i} = 0;

															while($d = $DB->fetch()){

															  	$importe = number_format(($d['importe']/100),2,'.','');

															 	if($d['tipo'] == 1){

																	${'ingreso_'.$i} += $importe;

															 		}else{

															 			if($d['clase'] == 'Gasto'){

															 				$gastos += $importe;

															 			}else{

															 				$compras += $importe;

															 			}

															 		}

															}

															  	$beneficio_total += ${'ingreso_'.$i};
															  	echo ${'ingreso_'.$i}.' &euro;';


															echo '</span>
													</h4>';
														} // for ($i=1; $i <=4 ; $i++) {



													?>

													<h4 class="mt-4 b-b1 pb-2 mb-4 fw-bold d-flex justify-content-between">
														<span>
															<i class="fas fa-wallet"></i>
															Gastos
														</span>
														<span>
															<?php echo $gastos ?>
														</span>
													</h4>
													<h4 class="mt-4 b-b1 pb-2 mb-4 fw-bold d-flex justify-content-between">
														<span>
															<i class="fas fa-shopping-cart"></i>
															Compras
														</span>
														<span>
															<?php echo $compras ?>
														</span>
													</h4>
													<h4 class="mt-4 b-b1 pb-2 mb-4 fw-bold d-flex justify-content-between">
														<span>Ing. Totales</span> 
														<span>
															<?php 

																echo $beneficio_total.' &euro;';

															?>
														</span>
													</h4>
													<h4 class="mt-4 b-b1 pb-2 mb-4 fw-bold d-flex justify-content-between">
														<span>Gastos + Compras</span> 
														<span>
															<?php 

																$gastos_totales = $compras+$gastos;
																echo $gastos_totales.' &euro;';

															?>
														</span>
													</h4>
													<h4 class="mt-4 b-b1 pb-2 mb-4 fw-bold d-flex justify-content-between">
														<span>Beneficio Final</span> 
														<span>
															<?php 

																$beneficio_final = $beneficio_total-$gastos_totales;
																echo $beneficio_final.' &euro;';

															?>
														</span>
													</h4>
												</div>
											</div>
										</div> <!-- <div class="col-sm-4"> -->
									<?php } ?>
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
	<div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" id="prevision">
		<div class="modal-dialog" role="document">
		    <div class="modal-content">
		      	<div class="modal-header">
		        	<h5 class="modal-title">Previsión de pedidos</h5>
		        	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		                <span aria-hidden="true">&times;</span>
		            </button>
		      	</div> 
		      	<div class="modal-body row text-center has-info" >
		      		<div class="col-12">
			       		<h3 class="text-center text-info"><u>Introduce fechas:</u></h3>
		      		</div>
			       <div class="col-6">
			       	<label for="">Desde:</label>
			       	<input type="text" name="desde_prevision" class="form-control datepicker" value="" placeholder="">
			       </div>
			       <div class="col-6">
			       	<label for="">Hasta:</label>
			       	<input type="text" name="hasta_prevision" class="form-control datepicker" value="" placeholder="">
			       </div> 
			    </div>
				<div class="modal-footer">
					<button type="button" class="btn btn-info pull-right" id="previciones_button">Ver Previsiones</button> 	
				</div>
			</div>
		</div>
	</div>
	<?php 

		include 'inc/inc_javascript.php'; 

		// if(isset($modal_1) && $modal_1[0]!=""){
  //           pintar_modal_json($modal_1[0],i,$tabla);
  //           pintar_modal_json($modal_1[0],u,$tabla);
  //           pintar_modal_json("borrar_registro",d,$tabla);
  //       }
        pintar_modal_json('tareas',i,'tareas');
        pintar_modal_json('movimientos_bancarios',i,'movimientos_bancarios');
        pintar_modal_json("borrar_registro",d,'pedidos');



	?>

	<script>

		$(document).ready(function(){


			$('[name="gestionar_tarea"]').dblclick(function(e){

				var id_tarea = $(this).attr('id_tarea');
				var id_tipo_tarea = $(this).attr('id_tipo_tarea');
				$.post('functions/ajax.php' ,
				    {
				        accion : 'u',
				        id_editar : id_tarea,
				        tabla_editar : 'tareas',
				        id_tipo_tarea_editar : id_tipo_tarea,
				        panel_gestionar_tarea_editar : 1,
				        id_usuario_editar : <?php echo $_SESSION['id_usuario'] ?>,
				        estado_editar : 1,
				        fecha_realizada_editar : "<?php echo now(); ?>",
				        noty : 1
				    }, 
				    function(data){
				        $('#respuesta_ajax_js').html(data);
					}
				);

				$(this).parent('div').addClass('d-none');
			
			});  // $('[name="gestionar_tarea"]').dblclick(function(e){


			$('[name="borrar_tarea"]').dblclick(function(e){

				var id_tarea = $(this).attr('id_tarea');
				$.post('functions/ajax.php' ,
				    {
				        borrar_registro : 1,
				        id_eliminar : id_tarea,
				        tabla_eliminar : 'tareas'
				        
				    }, 
				    function(data){
				        $('#respuesta_ajax_js').html(data);
					}
				);

			
			});  // $('[name="gestionar_tarea"]').dblclick(function(e){


			$('[name="entregar_pedido"]').dblclick(function(e){

				var id_pedido = $(this).attr('id_pedido');
				var facturar = $(this).attr('facturar');
				var es_recurrente = $(this).attr('es_recurrente');

				$.post('functions/ajax.php' ,
				    {
				        accion : 'u',
				        id_editar : id_pedido,
				        facturar_editar : facturar,
				        es_recurrente_editar : es_recurrente,
				        tabla_editar : 'pedidos',
				        estado_editar : 1,
				        noty : 1
				    }, 
				    function(data){
				        $('#respuesta_ajax_js').html(data);
					}
				);


				

				$(this).parent('div').addClass('d-none');
			
			});  // $('[name="gestionar_tarea"]').dblclick(function(e){



			$('[name="cancelar_pedido"]').dblclick(function(e){

				var id_pedido = $(this).attr('id_pedido');
				$.post('functions/ajax.php' ,
				    {
				        borrar_registro : 1,
				        id_eliminar : id_pedido,
				        tabla_eliminar : 'pedidos'
				        
				    }, 
				    function(data){
				        $('#respuesta_ajax_js').html(data);
					}
				);

			
			});  // $('[name="gestionar_tarea"]').dblclick(function(e){
	

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

            

            $.widget("custom.catcomplete",$.ui.autocomplete,
				{_renderMenu:function(e,t)
					{
					var a=this,o="";
					$.each(t,function(t,c){
						c.category!=o&&(e.append("<li class='ui-autocomplete-category'>"+c.category+"</li>"),o=c.category),a._renderItemData(e,c)
						})
					}
				}),
					$("[name='nombre_cliente']").catcomplete({minLength:2,source:"functions/ajax.php?buscar_cliente_autocomplete=1",
						select:function(e,t){

					    	$(this).val(t.item.value),
					    	$("[name='id_cliente']").val(t.item.id_cliente),
					    	$("[name='telefono_cliente']").val(t.item.telefono),
					    	$("[name='email_cliente']").val(t.item.email),
					    	$("[name='nombre_fiscal']").val(t.item.nombre_fiscal),
					    	$("[name='poblacion']").val(t.item.poblacion),
					    	$("[name='direccion']").val(t.item.direccion),
					    	$("[name='cp']").val(t.item.cp),
					    	$("[name='descuento']").val(t.item.descuento),
					    	$("[name='es_recurrente']").val(t.item.es_recurrente),
					    	$("[name='cif']").val(t.item.cif)

					    	if($("[name='descuento']").val()>0){
					    		 
					    		$("[name='descuento_span']").html($("[name='descuento']").val());

					    	}

					    	if($("[name='es_recurrente']").val()==1){

					    		$("[name='recurrente_span']").html('Si');
					    		
					    	}


					}
			}); // $.widget("custom.catcomplete",$.ui.autocomplete,

            $.widget("custom.catcomplete",$.ui.autocomplete,
				{_renderMenu:function(e,t)
					{
					var a=this,o="";
					$.each(t,function(t,c){
						c.category!=o&&(e.append("<li class='ui-autocomplete-category'>"+c.category+"</li>"),o=c.category),a._renderItemData(e,c)
						})
					}
				}),
					$("#buscar_cupon").catcomplete({minLength:1,source:"functions/ajax.php?buscar_cupon=1",
						select:function(e,t){

					    		$(this).val(t.item.value),
					    		$("#id_cupon").val(t.item.id)

					    	}


			}); // $.widget("custom.catcomplete",$.ui.autocomplete,

			$('#canjear_cupon').click(function(){

				var id = $("#id_cupon").val();
				if(id == ''){

					<?php noty_js('No has seleccionado ningún cupon para canjear','danger'); ?>

				}else{

					$.post('functions/ajax.php' ,
					            {
					                canjear_cupon : 1,
					                id : id
					            }, 
					            function(data){
					
					                    $('#respuesta_ajax_js').html(data);
					
					                }
					        );
				}
			
			}); // $('#canjear_cupon').click(function(){

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


			  	var unidades_producto = $('[name="unidades_producto"]').val();



				if(producto == 0 && (concepto == '' || pvp == '')){

					<?php  noty_js('Tienes que seleccionar o meter un concepto','danger') ?>

				}else if(unidades_producto == 0){

					<?php  noty_js('Tienes que poner una cantidad','danger') ?>

				}else{
					
					// eliminamos los saltos de línea
					var cmnt = $('[name="comentarios_pastelinos"]').val();
           			var cmnt2=cmnt.replace("\n",", ");
           			$('[name="comentarios_pastelinos"]').val(cmnt2);
					$('#suma_producto_form').form_validator_ajax();
					$('#comentarios_pastelinos').addClass('d-none');
			  		$('[name="comentarios_pastelinos"]').val('');

				}
				  
			});


			$('#confirmar_pedido').click(function(e){

				// comprobamos que el precio no este a 0 y que esten todos los datos
				if($('[name="importe_final"]').val() == 0){

					<?php  noty_js('El carrito esta vacio','danger') ?>

				}else{


					// que todos los datos del pedido esten rellenos.
					if(($('[name="nombre_cliente"]').val()=='' || $('[name="telefono_cliente"]').val()=='' || $('[name="email_cliente"]').val()=='' || $('[name="fecha_entrega"]').val()=='') && $('[name="es_vitrina"]').val()==0){

						<?php  noty_js('Faltan datos de Cliente y Fecha','danger') ?>
						$('[name="telefono_cliente"]').focus();

					}else if($('[name="es_vitrina"]').val()==1 && ($('[name="fecha_entrega"]').val()=='')){

						<?php  noty_js('Faltan fecha de entrega de entrega','danger') ?>
						$('[name="fecha_entrega"]').focus();

					}else if($('[name="es_vitrina"]').val()==0 && $('[name="facturar"]').val()==1 && ($('[name="nombre_fiscal"]').val()=='' || $('[name="direccion"]').val()=='' || $('[name="poblacion"]').val()=='' || $('[name="cif"]').val()=='' || $('[name="cp"]').val()=='')){

						<?php  noty_js('Faltan datos de facturación','danger') ?>
						$('[name="nombre_fiscal"]').focus();

					}else{


						$('#espera_general').modal('show');
					    var tarta = new Object();
					    var producto = new Object();
					    // var mensaje =  new Object();
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
								
								// mensaje[t] = $(this).attr('incluir_mensaje');
								// <?php if(isset($_SESSION['ip_admin'])){ ?>
								// 	alert(mensaje[t].replace(/"/g,'\''))
								// <?php } ?>

								tarta[t]['incluir_mensaje'] = $(this).attr('incluir_mensaje');
								tarta[t]['comentarios'] = $(this).attr('comentarios');


					        	if($(this).attr('select_tarta') == 'personalizada'){

					        		tarta[t]['capa_inferior'] = $(this).attr('capa_inferior');
					        		tarta[t]['capa_media'] = $(this).attr('capa_media');
					        		tarta[t]['capa_superior'] = $(this).attr('capa_superior');
					        		tarta[t]['borde_exterior'] = $(this).attr('borde_exterior');

					        	}else if($(this).attr('select_tarta') == 'gominolas'){

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
				        	$('#espera_general').modal('hide');
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

			$('[name="ver_albaran"]').click(function(e){
				
				$('[data-toggle="tooltip"]').tooltip('hide');


				var id_pedido = $(this).attr('id_pedido');
				window.open('https://pastelerialaargentina.com/dulceria/functions/descargar_documentacion.php?id_pedido='+id_pedido);

			});

			$('#previciones_button').click(function(e){
				
				var desde = $('[name="desde_prevision"]').val();
				var hasta = $('[name="hasta_prevision"]').val();

				if(desde == '' || hasta == ''){

					<?php noty_js('Tienes que seleccionar un rango de fechas','danger') ?>

				}else{

					window.open('https://pastelerialaargentina.com/dulceria/functions/descargar_pedidos.php?desde='+desde+'&hasta='+hasta, 'Descargar Pedidos');

				}

			}); // $('#previciones_button').click(function(e){

			// $('')
			$('[name="concepto_producto"]').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {

			  	if($(this).selectpicker('val') == 124 || $(this).selectpicker('val') == 125 || $(this).selectpicker('val') == 1 || $(this).selectpicker('val') == 126){

			  		$('#comentarios_pastelinos').removeClass('d-none');

			  	}else{

			  		$('#comentarios_pastelinos').addClass('d-none');

			  	}

			  	$('[name="unidades_producto"]').focus();
			});


			$('#tipo').html('<option value="1">Venta</option><option value="0">Compra</option><option value="0">Gasto</option>');
			$('#tipo').after('<input type="hidden" name="clase" value="venta">');
			$('#tipo').change(function(){

				$('#tipo option').each(function(e,i){

					if($(this).prop('selected') == true){

						$('[name="clase"]').val($(this).html());

					}
				});
				
			});

			$('[name="fecha"]').val('<?php echo date('d/m/Y') ?>');


			$('#comanda_button').click(function(e){

				var f = $('[name="comanda_input"]').val();
				var pf = f.split('/');
				var fecha = pf[2]+'-'+pf[1]+'-'+pf[0];
				window.open('https://pastelerialaargentina.com/dulceria/functions/descargar_pedidos.php?fecha='+fecha, 'Descargar Pedidos');
			
			});

		}); // $(document).ready(function(){
	</script>
</body>
</html>