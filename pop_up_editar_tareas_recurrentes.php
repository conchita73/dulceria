<?php include 'inc/inc_start.php';  
$title_heading="Tareas Recurrentes";



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
								<div class="card-body">
									<div class="row">
										<div class="col has-info">
											<div class="col-12">
												<label class="mt-2">Concepto:</label>
	
											</div>
											<div class="col-12">
												<label class="mt-2"></label>

											</div>
											<div class="col-12">
												<label class="mt-2"></label>

											</div>
											<div class="col-12">
												<label class="mt-2"></label>

											</div>
											<div class="col-12">
												<label class="mt-2"></label>

											</div>
											<div class="col-12">
												<label class="mt-2"></label>

											</div>
										</div> <!-- <div class="col"> -->
									</div> <!-- <div class="row"> -->
								</div> <!-- <div class="card-body"> -->
								<div class="card-footer">
								    <button type="button" class="btn btn-warning pull-right" id="crear_form_button">
                                	<b>Crear / Modificar Tarea Recurrente</b>
                            		</button> 
								 </div>
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