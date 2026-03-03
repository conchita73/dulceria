<?php include 'inc/inc_start.php';  
$title_heading="Colores";
$table = 'colores';
$form = 'colores_form';
$fb = isset($id) ? 'Modificar' : 'Crear';
$edt = isset($id) ? '_editar' : '';


?>
<!DOCTYPE html>
<html lang="es">
<head>

	<?php include 'inc/inc_head.php'; ?>
	
</head>

<body data-background-full="image" data-image="img/bg-3.png">
	<div class="wrapper overlay-sidebar">
		<div class="container">
			<div class="row text-center">
				<div class="col align-self-center">
						<img src="img/logo_admin.png" alt="navbar brand" class="navbar-brand mt30">
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
										<h4 class="card-title">Datos Descriptivos</h4>
										
									</div>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col">
											
											
											<?php 
												$accion = isset($id) ? u : i;
												pintar_form($form,$accion,'',$table);
			 								?>
			 								
			 								
			 								<label class="mt-3">Elije un color <span class="text-danger">*</span>:</label>
			 									
											<input type="text" maxlength="6" size="6" id="color<?php echo $edt ?>" value="00ff00" class="form-control" name="color<?php echo $edt ?>" verify="1"/>

										</div> <!-- <div class="col"> -->
									</div> <!-- <div class="row"> -->
								</div> <!-- <div class="card-body"> -->
								<div class="card-footer">
								    <button type="button" class="btn btn-warning pull-right callAjax">
                                	<b><?php echo $fb ?></b>
                            		</button>  
                            		<input type="hidden" name="<?php echo $form.$edt ?>_form" value="1">
								 </div>
							</div> <!-- <div class="card"> -->
						</div> <!-- <div class="col-md-12"> -->
					</div> <!-- <div class="row row-card-no-pd"> -->
				</div>
				<?php include 'inc/inc_footer.php'; ?>
			</div>
		</div>
	</div>
		

	<?php $no_chat=1 ; include 'inc/inc_javascript.php'; ?>
	
	<script>

		<?php if(isset($id)){ ?>

		    // con esto llenamos el formulario en caso de que 
		    // vayamos a editar el registro
		    $('[name="id_editar"]').val(<?php echo $id; ?>);
            var serialize = '#<?php echo $form ?>_editar_form';
            $(serialize).call_ajax({
                  url:"functions/ajax.php?tabla_cargar_datos_registros=1",
                  serialice:serialize
            });

        <?php } ?>
		
	</script>
</body>
</html>