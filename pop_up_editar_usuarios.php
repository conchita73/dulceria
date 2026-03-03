<?php include 'inc/inc_start.php';  
$title_heading="Usuarios";




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
										<h4 class="card-title">Crear / Modificar Usuarios</h4>
										
									</div>
								</div>
								<div class="card-body">
									<div class="row">
										<?php 
											

 										?>
										<div class="col has-info">
											<form class="form-horizontal" id="usuario_admin_form" method="post" action="#">
                        						
                        						<input type="hidden" name="notify" value="1">
                        	
                        						<?php 

                        							$accion = isset($id) ? u : i;
													$t_accion = isset($id) ? 'editar' : 'insertar';

													$edit = isset($id) ? '_editar' : '';

													if(isset($id)){

														$c = $DB->select('*','usuarios','id='.$id);
														$e = $c[f][0];
														$permisos = json_decode($e['arr_permisos'],true);
														echo '<input type="hidden" name="id_editar" value="'.$id.'">';

													}

                        							echo '<input type="hidden" id="accion" name="accion" value="'.$accion.'">
                        								<input type="hidden" id="tabla_'.$t_accion.'" name="tabla_'.$t_accion.'" value="usuarios">
                        								<input type="hidden" name="arr_permisos'.$edit.'" value="0">
                        								<input type="hidden" name="id_nivel'.$edit.'" value="2">';
                        							$arr = array(
                        								'nombre'=>'Nombre del usuario',
                        								'login'=>'Login (Esto es lo que se introduce para entrar)',
                        								'comentario'=>'Password',
                        								'email'=>'E-mail',
                        								'estado'=>'Estado'
                        										);

                        							foreach ($arr as $key => $value) {
                        								
                        								$key = $key == 'comentario' && $accion == i ? 'password' : $key;
                        								
                        								${$key} = isset($e[$key]) ? utf8_encode($e[$key]) : '';
                        								if($key != 'estado'){

                        									echo '<label class="mt-3">'.$value.':</label>
								                				<input type="text" class="form-control" name="'.$key.$edit.'" id="'.$key.'" placeholder="'.$value.'" value="'.${$key}.'" verify="1">';

								                		}else{

								                			$on = ${$key} = isset($e[$key]) && $e[$key]==1 ? 'selected' : '';
								                			$off = ${$key} = isset($e[$key]) && $e[$key]==0 ? 'selected' : '';
								                			echo '<label class="mt-3">'.$value.':</label>
								                				<select verify="1" name="'.$key.$edit.'" id="'.$key.'" class="custom-select form-control">
								                                        <option value="1" '.$on.'>On</option>
								                                        <option value="0" '.$off.'>Off</option>
							                                       </select>';
								                		}

                        							}

											
												echo '
												</form>
												<hr>
													<div class="row mt-3">
													<h3 class="text-center col-12">Permisos de Módulos</h3>';
											 	$DB->query('SELECT * FROM modulos where estado = 1 order by orden');


											  	while($d = $DB->fetch()){
											 		

											 		echo '<div class="col-sm-4 col-6 mt-3">
													 		 	<label for="">'.utf8_encode($d['nombre']).':</label><br>
													 			<input type="checkbox"
													 				data-toggle="toggle" 
																	data-onstyle="success"
																	data-offstyle="danger" 
																	data-on="Si"
																	data-off="No"
																	name="modulo"';

													if(isset($permisos) && in_array($d['id'],$permisos) || isset($e['id_nivel']) && $e['id_nivel'] == 1){

														echo 'checked';

													}

													echo '	        value="'.$d['id'].'"
																	>
													 		</div>';
											  	}
												echo '</div>';

										 	?>

										
										</div>
									</div> <!-- <div class="row"> -->
								</div> <!-- <div class="card-body"> -->
								<div class="card-footer">
								    <button type="button" class="btn btn-warning pull-right" id="crear_usuario">
                                	<b>Crear / Modificar Usuario</b>
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



            $('#crear_usuario').click(function(){

             	var modulos = '[';
				var b=0;
				$('[name="modulo"]').each(function(){

				    if($(this).is(':checked')){

				    	b++;
				    	if(b>1){ modulos+= ','; }

				        modulos+= $(this).val();
				        
				    }
				});

				modulos+=']';

				if(b == 0){

					<?php notify_js('Tienes que seleccionar algun módulo','danger'); ?>

				}else{

					$('[name^="arr_permisos"]').val(modulos);
					$('#usuario_admin_form').form_validator_ajax();

				}

            });
			
	</script>
</body>
</html>