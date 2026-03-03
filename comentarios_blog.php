<?php include 'inc/inc_start.php';  

$card_title = "Comentarios Blog";

// TABLA XRA SELECT, UPDATE, DELETE, INSERT
// posicion 0 = nombre del form que va en la modal
// posicion 1 = text del button que va a accionar esa modal

$modal_1=array("comentarios_blog_form","Crear Comentario");

$tabla = 'comentarios_blog';

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
										<button type="button" class="btn btn-round btn-primary atras btn-icon mr-1" ><i class="fas fa-arrow-circle-left" onclick="location.href='blog.php'"></i></button>
										<h4 class="card-title"><?php echo $card_title; ?> </h4>
									</div>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="table-responsive">

											<?php

											 	echo pintar_tbody('comentarios_blog',$modal_1)['body'];
 
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
		$(document).ready(function(){

			$('[name="aprobar_comentario"]').dblclick(function() {

				$('#espera_general').modal('show');
				var id = $(this).attr('id_comentario');
				$.post('functions/ajax.php' ,
				            {
				                aprobar_comentario : 1,
				                id : id
				            }, 
				            function(data){
				
				                    $('#respuesta_ajax_js').html(data);
				
				                }
				        );
			});

		}); // $(document).ready(function(){
	</script>
</body>
</html>