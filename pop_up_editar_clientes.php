<?php include 'inc/inc_start.php';  
$title_heading="clientes";
$table = 'clientes';
$form = 'clientes';
$fb = isset($id) ? 'Modificar Cliente' : 'Crear Cliente';
$edt = isset($id) ? '_editar' : '';
$dn = isset($id) ? '' : 'd-none';
$accion = isset($id) ? u : i;
$tbl = isset($id) ? 'editar' : 'insertar';
$id_imagenes_productos = isset($id) ? $id : time();


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
										<h4 class="card-title">Datos Descriptivos</h4>
										
									</div>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col">
											
											
											<?php 

												echo '<form id="'.$form.$edt.'_form" method="post">
			 											
			 											<input type="hidden" name="tabla_'.$tbl.'" value="'.$table.'">
			 											<input type="hidden" name="accion" value="'.$accion.'">';

			 									if(isset($id)){

			 										echo '
			 												<input type="hidden" name="id_editar" value="'.$id.'">
			 												';

			 									}

												$accion = isset($id) ? u : i;
												pintar_campos($form,$accion,'',$table);

			 								?>
			 									
			 									<label class="mt-2">Sexo</label>
			 									<select name="sexo" id="sexo">
			 										<option value="hombre">Hombre</option>
			 										<option value="mujer">Mujer</option>
			 									</select>
			 									
			 								
			 								</form>
										</div> <!-- <div class="col"> -->
									</div> <!-- <div class="row"> -->
								</div> <!-- <div class="card-body"> -->
								<div class="card-footer">
								    <button type="button" class="btn btn-success pull-right" id="btn_form<?php echo $edt ?>">
                                	<b><?php echo $fb ?></b>
                            		</button>  
                            		<input type="hidden" name="<?php echo $form.$edt ?>" value="1">
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

					// fotos_producto productos_form_form
			var _popup_enviando = false;
			$('#btn_form<?php echo $edt ?>').click(function(e){

				// evitar doble envío si el usuario pulsa dos veces
				if(_popup_enviando) return;
				_popup_enviando = true;
				var $btn = $(this);
				var textoOriginal = $btn.find('b').text();
				$btn.prop('disabled', true).find('b').text('Guardando...');

				// esta variable es para que no se evaluen las fotos si es edicion
				$('#<?php echo $form.$edt ?>_form').form_validator_ajax({url: 'functions/ajax.php?es_popup=1'});

				// Si la validación falla (alert síncrono) o el AJAX devuelve error,
				// reactivamos el botón tras 3 s para que el usuario pueda reintentar.
				// Si el AJAX tuvo éxito, el popup se cerrará antes de que se cumpla el timer.
				setTimeout(function(){
					_popup_enviando = false;
					$btn.prop('disabled', false).find('b').text(textoOriginal);
				}, 3000);

			});

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