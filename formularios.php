<?php include 'inc/inc_start.php';  

$datos_cabecera=array("id",
            "nombre");

$datos_tabla=array("id",
            "nombre");

//////////////////////////////////////
//                                  //
// SI QUIERO PONER UN DATO          //
// EN UN REGISTRO CON UN TITLE      //
// SOLO TENGO QUE METER UN CAMPO    //
// LLAMANDOLO TITLE COMO ALIAS      //
// EN $datos_tabla                  //
//                                  //
//////////////////////////////////////

$tabla_inner="";
$on="";
$where="";

$tabla="formulario_modal_json";

// SI QUEREMOS USAR LA FUNCION DE POP_UP SOBRE EL EDIT
//posicion 0 = boleano
//posicion 1 = url del pop_up (add id jquery in foot)
$edit_pop_up=array(true,"pop_up_formularios");


// posicion 0 = nombre del form que va en la modal
// posicion 1 = text del button que va a accionar esa modal
$modal_1=array("formularios","Crear Formularios");
$enviar_emails=0;

$campos_tabla=count($datos_tabla);

$enviar_emails=0;
$delete=1;
// aqui vamos a meter el id de la
// table que hay en inc_tbody, por si en algun
// momento queremos extraer datos con las 
// funciones del desplegable de XML, XLS , etc...
$id_table="tabla_usuario";
// por si le quiero meter alguna clase personalizada
// a algunas de las tablas
$clase_table="table-striped";
// table-condensed -> tabla mas pequeña
// table-striped -> cebreada



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
										<h4 class="card-title">Formularios</h4>
										<button class="btn btn-primary btn-round ml-auto" id="crear_new_form">
											<i class="fa fa-plus"></i>Crear NewForm
										</button>
									</div>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="table-responsive">
											<?php include 'inc/inc_tbody.php'; ?>
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
            // pintar_modal_json($modal_1[0],i,$tabla);
            // pintar_modal_json($modal_1[0],u,$tabla);
            pintar_modal_json("borrar_registro",d,$tabla);
        }

	?>
	
	<script>
	
		
            $('#crear_new_form').click(function(){

                var width = 600;
                var height = 800;
                var left = (screen.width) ? (screen.width-width)/2 : 0;
                var top = (screen.height) ? (screen.height-height)/2 : 0;
                window.open('pop_up_crear_formulario.php','_blank',"width="+width+",height="+height+",top="+top+",left="+left+",scrollbars=1");
                return false;

                 
            });

            $('button[name="editar_pop_up"]').click(function(){
            
            	var width = 600;
                var height = 800;
                var left = (screen.width) ? (screen.width-width)/2 : 0;
                var top = (screen.height) ? (screen.height-height)/2 : 0;
            	var id = $(this).attr("id");
            	window.open('pop_up_formularios_json.php?id='+id,'_blank',"width="+width+",height="+height+",top="+top+",left="+left+",scrollbars=1");
                return false;

        		// eof $('a[name="editar_pop_up"]').click(function(){
        	});
			
	</script>
</body>
</html>