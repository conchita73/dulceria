<?php include 'inc/inc_start.php';  

$card_title = "T bodys";

// TABLA XRA SELECT, UPDATE, DELETE, INSERT
$tabla="t_body";
// posicion 0 = nombre del form que va en la modal
// posicion 1 = text del button que va a accionar esa modal

$modal_1=array('t_body_form','Crear un Tbody');

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

											<?php // include 'inc/inc_tbody.php'; 

											echo pintar_tbody('t_body',$modal_1)['body'];

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

			/*$('#crear_cliente').click(function(){

				var width = 630;
                var height = 600;
                var left = (screen.width) ? (screen.width-width)/2 : 0;
                var top = (screen.height) ? (screen.height-height)/2 : 0;
            	window.open('pop_up_subir_clientes.php','_blank',"width="+width+",height="+height+",top="+top+",left="+left+",scrollbars=1");
                return false;

			});*/


			$(document).ready(function(){

				$('textarea[id^="datos_"]').append('id|ids|');


			});
			
	</script>
</body>
</html>