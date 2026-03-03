<?php include 'inc/inc_start.php';  
$title_heading="Gestionar Facturas Agrupadas";

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
											Datos de la factura
										</h4>
									</div>
								</div>
								<div class="card-body">
									<div class="row has-info">
										<div class="col-12">
											<h4 class=""> 
												<span class="text-danger">*</span>Al Eliminar un albarán de una factura, se le quitará el importe a esta factura, pero NO al albarán que has eliminado. Esta acción no se puede deshacer
											</h4>
											
											<form class="form-horizontal" id="facturas_form_form" method="post" action="#">
												<?php 

													$df = $DB->select('importe,concepto','facturas','id = '.$id);
													$d = $df[f][0];

													$concepto = json_decode($d['concepto'],true);
													foreach ($concepto as $k => $v) {
																
														echo '<div class="alert alert-info alert-dismissible fade show mt-3" role="alert" id="alert_'.time().'"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
															Albarán n&ordm; : '.$v['id_factura'].'<br>Fecha :'.formatear_fecha($v['fecha'],m,i).' <br> Importe : '.$v['importe'].' &euro;
															<input type="hidden" name="concep_sum" id_factura="'.$v['id_factura'].'" importe="'.$v['importe'].'" fecha="'.$v['fecha'].'"></div>';											
													} 

												?>
												<input type="hidden" name="id_via_cobro" value="1">
												<input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
												<input type="hidden" name="id_delegacion" value="<?php echo $_SESSION['id_delegacion'] ?>">
												<input type="hidden" id="accion" name="accion" value="u">
												<input type="hidden" name="tabla_editar" value="facturas">
												<input type="hidden" name="importe" id="importe" value="<?php echo number_format(($d['importe']/100),2,'.',''); ?>">
                        						<div class="form-group">
	                        																
													<h3 class="text-right mt-2 text-success">Importe factura: <span id="pvp_span" name="pvp_span"><?php echo number_format(($d['importe']/100),2,'.','') ?></span>&euro;</h3>
													<button type="button" class="mt-3 btn btn-warning float-right ml-auto" id="crear_factura">
				                                		<b>Modificar Factura</b> 
				                            		</button> 
												</div>
											</form>
										</div> <!-- <div class="col"> -->
									</div> <!-- <div class="row"> -->
								</div> <!-- <div class="card-body"> -->
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
		$(document).ready(function(){





			$('#crear_factura').click(function(e){
				
				var pvp = $('[name="importe"]').val();
				var fecha_cobro = $('[name="fecha_cobro"]').val();

				if(pvp == 0){

					alert('La factura no puede estar a 0');
					location.reload();

				}else{ 

					var concepto = new Object();
				    var b=1;
		
					    $('[name="concep_sum"]').each(function(){

					    	concepto[b] = new Object();
					        concepto[b]['id_factura'] = $(this).attr('id_factura');
					        concepto[b]['fecha'] = $(this).attr('fecha');
					        concepto[b]['importe'] = $(this).attr('importe');

					        b++;

					    }); // $('[name="concep_sum"]').each(function(){


				    // aqui vamos a usar finalmente la funtion post
				    // para poder pasar todos los datos que queramos
				    $.post('functions/ajax.php',{

				            tabla_editar: "facturas",
				            accion : 'u',
				            id_editar : <?php echo $id ?>,
				            importe : $('[name="importe"]').val(),
				            concepto : concepto
				        }, 
				        function(data) {
				        $("#respuesta_ajax_js").html(data);
				    });

				}
			});

			$("[id^='alert_'").on("close.bs.alert", function () {

	            var pvp = $(this).children('[name="concep_sum"]').attr("importe");
	            var importe = $('[name="importe"]').val();
	            var final = importe-pvp;
	            // var f = final/100;
	            var s = String(final);
	            if(s.length == 2){ s = s+".00"; }
	            $('[name="pvp_span"]').html(s);
	            $('[name="importe"]').val(s); 

	        });

		}); // $(document).ready(function(){
	</script>
</body>
</html>