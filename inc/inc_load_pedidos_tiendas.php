											<?php 

												include('../functions/funciones.php');
											 	

											 	for ($i=1; $i <=4 ; $i++) { 

											 		${'tartas_'.$i} = '';
											 		${'productos_'.$i} = '';


											 		$DB->query('SELECT p.*,d.nombre,c.nombre as cliente,c.id as id_cliente, c.telefono
											 				FROM pedidos p inner join delegaciones d 
											 				on p.local_entrega = d.id 
															inner join clientes c on p.id_cliente = c.id
											 				WHERE p.estado = 0 and
											 				p.fecha_entrega = "'.date('Y-m-d').'" 
											 				and p.local_entrega = '.$i.' order by hora_entrega ASC');

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