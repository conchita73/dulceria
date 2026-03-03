<?php include 'inc/inc_start.php';  

$card_title = "Contratos";

// TABLA XRA SELECT, UPDATE, DELETE, INSERT
$tabla="contratos";
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
									<div class="col-md-6 ">

										<form action="" method="post" accept-charset="utf-8" id="filtro_form">
											<input type="hidden" name="tabla" value="contratos">
											<input type="hidden" name="buscar_tables_ajax" value="1">

											<div class="form-row has-secondary">
												<div class="col-12 mb-2">
													<label for="">Bucar Por cliente:</label>
													<input type="text" name="comercio" class="form-control">
												</div>
												<div class="col-6">
													<label for="">Desde:</label>
													<input type="text" name="desde" class="form-control datepicker">
												</div>
												<div class="col-6">
													<label for="">Hasta:</label>
													<input type="text" name="hasta" class="form-control datepicker">
												</div>
											</div>
											<button type="button" class="mt-2 btn btn-secondary float-right btn_filtro" >Filtrar Contratos</button>
										</form>
									</div>
								</div>
								<div class="card-body">
									<div class="row" style="min-height: 400px;">
										<div class="table-responsive">
											<div id="ultimas_<?php echo $tabla ?>">
												<?php

													$modal_1=array("contratos_form","Crear Contratos");

											 		echo pintar_tbody('contratos',$modal_1)['body'];
 
												?>
											</div>
											<div id="resultado_<?php echo $tabla ?>" class='d-none'>
												
											</div>
										</div> <!-- <div class="table-responsive"> -->
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

			$('#crear_cliente').click(function(){

				var width = 630;
                var height = 600;
                var left = (screen.width) ? (screen.width-width)/2 : 0;
                var top = (screen.height) ? (screen.height-height)/2 : 0;
            	window.open('pop_up_subir_clientes.php','_blank',"width="+width+",height="+height+",top="+top+",left="+left+",scrollbars=1");
                return false;

			});

						// buscador hoteles, provincias, zonas

			// $('#control_acciones').DataTable({
            //     "pageLength": 10,
            //     order: [[ 0, "desc" ]],
            		//language: {
                          // processing:     "En Proceso ......",
                          // search:         "Buscar <i class='fas fa-search text-secondary'></i>",
                          // lengthMenu:    "Mostrando _MENU_ Registros",
                          // info:           "Mostrando _START_ al _END_ de _TOTAL_ Registros",
                          // infoEmpty:      "Mostrando 0 al 0 de 0 Registros",
                          // infoFiltered:   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
                          // infoPostFix:    "",
                          // loadingRecords: "Carga en Curso ...",
                          // zeroRecords:    "No hay ningún registro con estos Datos",
                          // emptyTable:     "No hay ninguna tabla creada",
                          // paginate: {
                          //     first:      "Primera",
                          //     previous:   "Atrás",
                          //     next:       "Siguiente",
                          //     last:       "Última"
                          // },
                          // aria: {
                          //     sortAscending:  ": Active para ordenar la columna en orden ascendente",
                          //     sortDescending: ": Active para ordenar la columna en orden Descendente"
                          // }
                        //}

            // });


			
	</script>
</body>
</html>