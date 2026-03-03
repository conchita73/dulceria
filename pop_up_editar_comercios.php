<?php include 'inc/inc_start.php';  
$title_heading="";



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
										<h4 class="card-title">Ficha de Cliente</h4>
										
									</div>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col">
											<ul class="nav nav-pills nav-justified" id="myTab" role="tablist">
											  	<li class="nav-item">
											    	<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Datos Principales</a>
											  	</li>
											  	<li class="nav-item">
											    	<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Contratos</a>
											  	</li>
											  	<li class="nav-item">
											    	<a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Facturas</a>
											  	</li>
											</ul>
											<div class="tab-content" id="myTabContent">
											  	<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
											  		<form action="" method="post" id="modify_comercio_form" accept-charset="utf-8" class="has-secondary">
											  			<input type="hidden" name="tabla_editar" value="comercios">
											  			<input type="hidden" name="accion" value="u">
														<?php 

															$c = $DB->select('*','comercios','id='.$id);
															$e = $c[f][0];

															echo '<input type="hidden" name="id_editar" value="'.$id.'">
																<input type="hidden" name="sin_reload" value="1">';
																

																$DB->query("SHOW COLUMNS from comercios");

																$arr = array();
																while ($d = $DB->fetch()) {

																	$arr[$d['Field']] = 0;

																}
																
																$arr_no = array(
																			'id',
																			'id_poblacion',
																			'id_provincia',
																			'id_comunidad',
																			'id_usuario',
																			'id_sector',
																			'id_delegacion',
																			'id_usuario_asignador',
																			'fecha_asignacion',
																			'id_fase_compra',
																			'fecha_rechazado',
																			'stripe',
																			'payment_method',
																			'plan_amigo',
																			'comentarios',
																			'comentario_perceptivo');

																foreach ($arr as $key => $value) {
																	
																	if(!in_array($key,$arr_no)){


																		${$key} = isset($e[$key]) ? utf8_encode($e[$key]) : '';

																		echo '<label class="mt-2">'.ucwords(str_replace('_', ' ', $key)).':</label>';

																		if($key=='comentarios' || $key=='comentario_perceptivo'){

																			echo '<textarea class="form-control" name="'.$key.$edit.'" id="'.$key.'" placeholder="'.ucwords(str_replace('_', ' ', $key)).'"  verify="'.$value.'">'.${$key}.'</textarea>';

																		}else{

																			echo '<input type="text" class="form-control" name="'.$key.$edit.'" id="'.$key.'" placeholder="'.ucwords(str_replace('_', ' ', $key)).'" value="'.${$key}.'" verify="'.$value.'">';

																		}

																	} // if(!in_array($key,$arr_no)){
																	
																} // foreach ($arr as $key => $value) {

														?>
														<button type="button" class="btn btn-warning float-right mt-2" id="modify_comercio_button">
					                                	<b>Modificar Comercio</b>
					                            		</button>
													</form>
											  	</div>
											  	<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
													<h3 class="text-center">Contratos</h3>
													<div class="table-responsive">
													
														<?php

													 		echo pintar_tbody('contratos_comercios',$modal_1,' where id_comercio = '.$id)['body'];
		 
														?>
													</div>
											  	</div>
											  	<div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
													<h3 class="text-center">Facturas</h3>
													<div class="table-responsive">
													
														<?php

													 		echo pintar_tbody('facturas_comercios',$modal_1,' where id_comercio = '.$id)['body'];
		 
														?>
													</div>
											  	</div>
											</div>
										</div> <!-- <div class="col"> -->
									</div> <!-- <div class="row"> -->
								</div> <!-- <div class="card-body"> -->
								<!-- <div class="card-footer">
								    <button type="button" class="btn btn-warning pull-right" id="crear_form_button">
                                	<b>Crear Formulario</b>
                            		</button> 
								 </div> -->
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

		
		$('#modify_comercio_button').click(function(e){
			
			$('#modify_comercio_form').form_validator_ajax();

		});

		$(document).on('dblclick','.is_factura', function(e){
				
				var id_factura = $(this).attr('id_factura');
				$.post('functions/ajax.php' ,
				    {
				        pagar_factura : 1,
				        id_factura : id_factura

				    }, 
				    function(data){
				        $('#respuesta_ajax_js').html(data);
					}
				);
				// proceso de pasar a pagado y enviar factura al cliente.

		});
			// Add Row
			
	</script>
</body>
</html>