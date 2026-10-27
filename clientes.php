<?php include 'inc/inc_start.php';  
$card_title ="Clientes";


// TABLA XRA SELECT, UPDATE, DELETE, INSERT
$tabla="clientes";

// posicion 0 = nombre del form que va en la modal
// posicion 1 = text del button que va a accionar esa modal
$modal_1=array("clientes","Crear Cliente");


?>
<!DOCTYPE html>
<html lang="es">
<head>

	<?php include 'inc/inc_head.php'; ?>

</head>

<body data-background-full="image" data-image="img/bg-3.png">
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

							<div class="card" id="pre_load">
								<div class="card-header">
									<div class="card-head-row">
										<h4 class="card-title col"><?php echo $card_title; ?> </h4>

										<?php 

											if(isset($modal_1) && $modal_1[0]!=""){

												echo '<button 
														class="btn btn-primary btn-round ml-auto" 
														name="editar_pop_up"
														tabla="clientes">
														<i class="fa fa-plus"></i>
													'.$modal_1[1].'
												</button>';
											}

										?>
									</div>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col">
											<h3 class="text-danger text-center">Deuda Total : <span id="deuda_total"></span></h3>
											<ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
												<li class="nav-item ">
												  <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"></i> Clientes Habituales:</a>
												</li>
												<li class="nav-item">
												  <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Clientes Puntuales:</a>
												</li>
												
											</ul>
											<div class="tab-content" id="myTabContent">
												<div class="tab-pane fade show active" id="home" role="	tabpanel" aria-labelledby="home-tab">
													<div class="table-responsive mt-3">

														<?php 

															$t_body_clientes = pintar_tbody('clientes',$modal_1,' es_recurrente=1');
															echo $t_body_clientes['body'];

														?>

													</div> <!-- <div class="table-responsive mt-3"> -->
												</div> <!-- id="home" -->
												<div class="tab-pane fade" id="profile" role="	tabpanel" aria-labelledby="profile-tab">
													<div class="table-responsive mt-3">

														<?php 

															echo pintar_tbody('clientes',$modal_1,' es_recurrente=0 and id>0')['body'];

														?>

													</div> <!-- <div class="table-responsive mt-3"> -->
												</div> <!-- id="profile" -->
											</div> <!-- id="myTabContent" -->
										</div> <!-- <div class="col"> -->
									</div> <!-- <div class="row"> -->
								</div> <!-- <div class="card-body"> -->
							</div> <!-- <div class="card"> -->
							<div id="load" class="d-none">
									
							</div>
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

			$('#crear_usuario').click(function(){

				var width = 630;
                var height = 600;
                var left = (screen.width) ? (screen.width-width)/2 : 0;
                var top = (screen.height) ? (screen.height-height)/2 : 0;
            	window.open('pop_up_editar_usuarios.php','_blank',"width="+width+",height="+height+",top="+top+",left="+left+",scrollbars=1");
                return false;

			});
			
			$('#descuento').val(0);

				$('[name="load_ficha"]').click(function(){

				var id = $(this).attr('id');
				$('#pre_load').addClass('d-none');
				$('#load').removeClass('d-none');
				$('#load').load('load_ficha_cliente.php?id='+id);


		});


		$("#deuda_total").html("<?php echo nf(($t_body_clientes['totales'])) ?> &euro;");
		<?php if(isset($_SESSION['ip_admin'])){ ?>


		<?php } ?>

	</script>
</body>
</html>