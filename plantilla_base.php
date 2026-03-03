<?php include 'inc/inc_start.php';  

$card_title = "Comercios";

// TABLA XRA SELECT, UPDATE, DELETE, INSERT
$tabla="comercios";
// posicion 0 = nombre del form que va en la modal
// posicion 1 = text del button que va a accionar esa modal

$modal_1=array("comercio_form","Crear Comercio");


?>
<!DOCTYPE html>
<html lang="es">
<head>

	<?php include 'inc/inc_head.php'; ?>

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
									<div class="row">
										<div class="table-responsive">

											<?php

											 	echo pintar_tbody('comercios',$modal_1)['body'];
 
											?>

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