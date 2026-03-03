<?php include 'inc/inc_start.php';  
$table = 'blog';
$fb = isset($id) ? 'Editar' : 'Crear';
$edt = isset($id) ? '_editar' : '';

$title_heading=$fb." Publicación";

if(isset($id)){

	$dp = $DB->select('*','blog','id = '.$id);
	$dp = $dp[f][0];
}

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
										<button type="button" class="btn btn-round btn-primary atras btn-icon mr-1" ><i class="fas fa-arrow-circle-left" onclick="location.href='blog.php'"></i></button>
										<h4 class="card-title"><?php echo $title_heading; ?></h4>
									</div>
								</div>
								<div class="card-body">
									<div class="row has-info">
										<div class="col-md-6">
											<label class="mt-3">Título:</label>
											<input type="text" name="titulo" value="" class="form-control" placeholder="Introduce un título">
										</div> <!-- <div class="col"> -->
										<div class="col-md-6">
											<label class="mt-3">Fecha:</label>
											<input type="text" name="fecha" value="" class="form-control" placeholder="Introduce una fecha">
										</div> <!-- <div class="col"> -->
										<div class="col-md-6">
											<label class="mt-3">¿Publicar?:</label>
											<select name="publicado" id="">
												<option value="0">No</option>
												<option value="1">Sí</option>
											</select>
										</div> <!-- <div class="col"> -->
										<div class="col-md-6">
											<label class="mt-3">Foto Principal:</label>
											<button type="button" class="btn btn-block btn-primary" name="subir_foto"style="height: 40px;">Subir Foto</button>
											<input type="hidden" name="foto_principal" value="">
										</div> <!-- <div class="col"> -->
										<div class="col-12 text-center" name="fotos_subidas">
											<?php 

												if(isset($id) && $dp['foto_principal']!=''){

													echo '<div class="alert alert-success alert-dismissible fade show mt-3 col-6" role="alert" style="margin : auto;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><img src="/new_app/img/blog/'.$dp['foto_principal'].'" alt="" style="max-width : 120px;"></div>';
												}
											?>

										</div>
          								<div class="col-12"><hr></div>
          								<div class="col-12">
          									<h3 class="text-primary text-center">Textos</h3>
          									<div class="card">
												<div class="card-body">
													<div id="summernote"></div>
												</div>
											</div>
          								</div>
									</div> <!-- <div class="row"> -->
								</div> <!-- <div class="card-body"> -->
								<div class="card-footer">
								    <button type="button" class="btn btn-warning pull-right" id="btn_blog<?php echo $edt ?>">
                                	<b><?php echo $fb ?></b>
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
	<form enctype="multipart/form-data" id="formuploadajax" method="post" class="d-none">
    	<input  type="file" data-input="false" id="upload" name="upload" />
	</form>
	<?php $no_chat=1 ; include 'inc/inc_javascript.php'; ?>
	<!-- Summernote -->
	<script src="js/plugin/summernote/summernote-bs4.min.js"></script>
	<script>
		$(document).ready(function(){

			$(document).on('click', '.close', function(event) {

				$('[name="foto_principal"]').val('');

			});

			$('#summernote').summernote({
				placeholder: 'Introduce aquí textos, fotos y enlaces y Vídeos enlazados a youtube y otras plataformas',
				fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New'],
				tabsize: 2,
				height: 300
			});

			$('[name="subir_foto"]').click(function(e){

	            $('#upload').click();

	        });

			<?php if(!isset($id)){ ?>

				$('#btn_blog').click(function(){
					
					var insertar = 1;
					var publicar = $('[name="publicado"]').val();


					if(publicar == 1){

						titulo = $('[name="titulo"]').val();
					    fecha = $('[name="fecha"]').val();
					    texto = $('#summernote').summernote('code');
					    foto_principal = $('[name="foto_principal"]').val();
					    if(titulo == '' || fecha == '' || texto == ''|| foto_principal == ''){

					    	<?php notify_js('Para publicar todos los campos son obligatorios','danger'); ?>
					    	insertar = 0;
					    }

					}

					if(insertar == 1){

						$.post('functions/ajax.php' ,
					            {
					                accion : 'i',
					                tabla_insertar : 'blog',
					                titulo : $('[name="titulo"]').val(),
					                fecha : $('[name="fecha"]').val(),
					                texto : $('#summernote').summernote('code'),
					                foto_principal : $('[name="foto_principal"]').val(),

					            }, 
					            function(data){
					
					                    $('#respuesta_ajax_js').html(data);
					
					                }
					        );

					} // if(insertar == 1){

				}); // $('#').click(function(){

			<?php } ?>

	        /// con esto hacemos que se ejecute el formulario, cuando hayamos
	        $('#upload').change(function(){
	            
	            var fileName = this.files[0].name;
	            var fileSize = this.files[0].size;
	            var type = this.files[0].type;
	            var ext = type.split('/');

	            if(fileSize == 0){
	                
	                <?php notify_js('El archivo esta vacio','danger') ?>
	                this.value = '';
	                return false;

	            }else if(fileSize > 5000000){

	                <?php notify_js('El archivo no puede superar los 5 megas','danger'); ?>
	                this.value = '';
	                return false;

	            }else if(ext[0]!='image'){

	                <?php notify_js('El archivo tiene que ser una imagen','danger'); ?>
	                this.value = ''; 
	                return false;

	            }else{

	                var formData = new FormData(document.getElementById("formuploadajax"));
	                formData.append('subir_imagen_blog', 1);

	                $.ajax({

	                    url: "/new_app/functions/ajax.php",
	                    type: "post",
	                    dataType: "html",
	                    data: formData,
	                    cache: false,
	                    contentType: false,
	                    processData: false

	                }).done(function(res){

	                    $("#respuesta_ajax_js").html(res);
	                    //$('#upload').val();
	                });
	                        
	            }

	        }); // $('#upload').change(function(){

			<?php if(isset($id)){ ?>

				$('[name="titulo"]').val('<?php echo utf8_encode($dp['titulo']) ?>');
				$('[name="fecha"]').val('<?php echo formatear_fecha($dp['fecha'],m,i) ?>');
				$('[name="foto_principal"]').val('<?php echo utf8_encode($dp['foto_principal']) ?>');
				$('[name="publicado"] option[value="<?php echo $dp['publicado'] ?>"]').attr('selected',true);
				$('#summernote').summernote('code','<?php echo utf8_encode($dp['texto']) ?>')

				$('#btn_blog<?php echo $edt ?>').click(function(){
					
					var insertar = 1;
					var publicar = $('[name="publicado"]').val();


					if(publicar == 1){

						titulo = $('[name="titulo"]').val();
					    fecha = $('[name="fecha"]').val();
					    texto = $('#summernote').summernote('code');
					    foto_principal = $('[name="foto_principal"]').val();
					    if(titulo == '' || fecha == '' || texto == ''|| foto_principal == ''){

					    	<?php notify_js('Para publicar todos los campos son obligatorios','danger'); ?>
					    	insertar = 0;
					    }

					}

					if(insertar == 1){

						$.post('functions/ajax.php' ,
					            {
					                accion : 'u',
					                tabla_editar : 'blog',
					                id_editar : '<?php echo $id ?>',
					                titulo_editar : $('[name="titulo"]').val(),
					                fecha_editar : $('[name="fecha"]').val(),
					                texto_editar : $('#summernote').summernote('code'),
					                foto_principal_editar : $('[name="foto_principal"]').val(),
					                noty : 1 

					            }, 
					            function(data){
					
					                    $('#respuesta_ajax_js').html(data);
					
					                }
					        );

					} // if(insertar == 1){

				}); // $('#').click(function(){



	        <?php } ?>

		}); // $(document).ready(function(){
	</script>
</body>
</html>