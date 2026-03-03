	<style type="text/css" media="screen">
		.user{
			border: 1px solid #F3DCDE;
		}
		.message-content{
			float: right;
		}
	</style>
	<div class="quick-sidebar">
			<a href="#" class="close-quick-sidebar">
				<i class="flaticon-cross"></i>
			</a>
			<div class="quick-sidebar-wrapper">
				<ul class="nav nav-tabs nav-line nav-color-info" role="tablist">
					<li class="nav-item"> <a class="nav-link active show" data-toggle="tab" href="#events" role="tab" aria-selected="true">Eventos</a> </li>
					<!-- <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#today" role="tab" aria-selected="false">Para hoy</a> </li> -->
					<!-- <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#settings" role="tab" aria-selected="false">Settings</a> </li> -->
				</ul>
				<div class="tab-content mt-3">
					<div class="tab-chat tab-pane fade show active" id="events" role="tabpanel">
						<?php // en este primer contendor estan los contactos y el ultimo mensaje de conversacion ?>
						<div class="messages-contact">
							<div class="quick-wrapper">
								<div class="quick-scroll scrollbar-outer">
									<div class="quick-content contact-content has-info">
										<span class="category-title mt-0">Pendientes</span>

										<small class="text-danger">*DobleClick pasar a "Gestionado"</small>
										<div class="contact-list contact-list-recent" id="events_activos">

											<?php 

												$DB->query('select * from tareas where fecha_realizada = "0000-00-00 00:00:00" and start<="'.date('Y-m-d').'"');

												if($DB->rows()>0){

													while ($d = $DB->fetch()) {
	                										
	                									if(strtotime(now()) >= strtotime($d['start'].' '.$d['hora'])){ 

		                									$arr_pen_event[] = array(
		                										'order'=>strtotime($d['start'].' '.$d['hora']),
		                										'id'=>$d['id'],
		                										'title'=>$d['title'], 
		                										'start'=>$d['start'], 
		                										'hora'=>$d['hora'], 
		                										'className'=>$d['className'],
		                										'notas'=>$d['notas'],
		                										'idtr'=>$d['id_tarea_recurrente']
		                									);

		                								}else if($d['start'] == date('Y-m-d')){ // if(strtotime(now()) >= strtotime($d['st ....

		                									$arr_next_event[] = array(
		                										'order'=>strtotime($d['start'].' '.$d['hora']),
		                										'id'=>$d['id'],
		                										'title'=>$d['title'], 
		                										'start'=>$d['start'], 
		                										'hora'=>$d['hora'], 
		                										'className'=>$d['className'],
		                										'notas'=>$d['notas'],
		                										'idtr'=>$d['id_tarea_recurrente']
		                									);

		                								} // }else if($d['start'] == date('Y-m-d')){

													} // while ($d = $DB->fetch()) {

										        }// if($DB->rows()>0){
												

												if(isset($arr_pen_event) && count($arr_pen_event)>0){

													arsort($arr_pen_event);

													$ev = 0;
													foreach ($arr_pen_event as $key => $value) {

														if(strpos($value['className'],'-solid') !== false){

															
															list($f,$bge[$ev],$s) = explode('-',$value['className']);
															$bge[$ev] ='bg-'.$bge[$ev].' text-white'; 

														}else{

															$bge[$ev] = '';
															list($f,$bgd[$ev]) = explode('fc-',$value['className']);
														}

														echo '<div class="user mb-2 '.$bge[$ev] .'">
																<button type="button" class="btn btn-danger btn-sm float-right gestionar_evento" id_evento="'.$value['id'].'"  data-toggle="tooltip" data-placement="top" title="Doble Click para borrar"><i class="fas fa-thumbs-up"></i></button>

									                            <a href="#" 
									                            	title="'.utf8_encode($value['title']).'" 
									                            	id_evento="'.$value['id'].'" 
									                            	hora="'.$value['hora'].'"
									                            	idtr="'.$value['idtr'].'"
									                            >
									                                <div class="user-data">
									                                    <span class="name">';

									                            if(isset($bgd[$ev])){

									                            	
									                            	echo ' <div class="div_colores_c bg-'.$bgd[$ev].'" ></div> ';

									                            }
									                                 

									                    echo       	utf8_encode($value['title']).'</span>
									                                    
									                                    <span class="message">'.formatear_fecha($value['start'],m,i).' '.$value['hora'].'</span>
									                                </div>
									                            </a>
									                        </div>';
									                    $ev ++;
													}

									            }else{ // if($DB->rows()>0){

									                echo 'No tienes eventos pendientes';

									            } // if($DB->rows()>0){ ......

											?>
											
										</div> <!-- id="events_activos"> -->
										<span class="category-title mt-0">Próximos Eventos</span>

										<div class="contact-list contact-list-recent mt-2" id="events_next">
											<?php 

												if(isset($arr_next_event) && count($arr_next_event)>0){

													arsort($arr_next_event);

													$ev = 0;
													foreach ($arr_next_event as $key => $value) {

														if(strpos($value['className'],'-solid') !== false){

															
															list($f,$bge[$ev],$s) = explode('-',$value['className']);
															$bge[$ev] ='bg-'.$bge[$ev].' text-white'; 

														}else{

															$bge[$ev] = '';
															list($f,$bgd[$ev]) = explode('fc-',$value['className']);
														}

														echo '<div class="user mb-2 '.$bge[$ev] .'">
																<button type="button" class="btn btn-danger btn-sm float-right gestionar_evento" id_evento="'.$value['id'].'"  data-toggle="tooltip" data-placement="top" title="Doble Click para borrar"><i class="fas fa-thumbs-up"></i></button>

									                            <a href="#" 
									                            	title="'.utf8_encode($value['title']).'" 
									                            	id_evento="'.$value['id'].'" 
									                            	hora="'.$value['hora'].'"
									                            	idtr="'.$value['idtr'].'"
									                            >
									                                <div class="user-data">
									                                    <span class="name">';

									                            if(isset($bgd[$ev])){

									                            	
									                            	echo ' <div class="div_colores_c bg-'.$bgd[$ev].'" ></div> ';

									                            }
									                                 

									                    echo       	utf8_encode($value['title']).'</span>
									                                    
									                                    <span class="message">'.formatear_fecha($value['start'],m,i).' '.$value['hora'].'</span>
									                                </div>
									                            </a>
									                        </div>';
									                    $ev ++;
													}

												}else{ // if($DB->rows()>0){

									                echo 'No tienes próximos eventos';

									            } // if($DB->rows()>0){ ......

											?>
										</div> <!-- id="events_activos"> -->
									</div>
								</div>
							</div>
						</div> <!-- <div class="messages-contact"> -->

						<div class="messages-wrapper">
							<?php // esta es la cabecera de una conversacion ?>

							<div class="messages-title">
								<div class="user text-center">
									<h3 class="name text_1_3" id="titulo_evento"></h3>
									<br>
									<h3 class="name text-info text_1_3" id="hor_evento"></h3>
								</div>
								<button class="return"> 
									<i class="flaticon-left-arrow-3"></i>
								</button>
							</div>
							<div class="col-12 text-center" id="div_edicion_masiva">
			            		<span class="text-danger">*Este evento pertenece a una seríe. Al modificar quieres:</span>
			            		<select name="edicion_masiva" class="custom-select">
			            			<option value="this">Modificar Sólo Este</option>
			            			<option value="all">Modificar Todos</option>
			            		</select>
			            		
			            	</div>
			            	<div class="col-12 text-center">
			            		<small class="text-danger mt-3">*Los eventos se pueden borrar en serie</small>
			            		<button type="button" class="btn btn-danger btn-block " id="borrar_evento">DobleClick : Eliminar Evento</button>
			            	</div>
							<form action="" method="post" id="edit_event_lat_form" class="form-row has-info" accept-charset="utf-8"> 
				            	<input type="hidden" name="accion" value="u">
				            	<input type="hidden" name="tabla_editar" value="tareas">
				            	<input type="hidden" name="id_editar" id="id_editar_agenda" value="0">
				            	<input type="hidden" name="visto_editar" value="0">
				            	<input type="hidden" name="fecha_realizada" value="0000-00-00 00:00:00">
				            	<input type="hidden" name="id_tarea_recurrente_editar" id="id_tarea_recurrente_editar" value="0">
									<!-- <input type="hidden" name="notify" value="1"> -->

				            	<label class="mt-3">Título <span class="text-danger">*</span>:</label>
				            	<input type="text" name="title_editar" id="title_editar" value="" class="form-control" placeholder="Título" verify="1">
				            	<label class="mt-3">Fecha <span class="text-danger">*</span>:</label>
				            	<input type="text" name="start_editar" id="start_editar" value="" class="form-control datepicker" placeholder="Fecha" verify="1">
				            	<label class="mt-3">Hora <span class="text-danger">*</span>:</label> 
				            	<input type="text" name="hora_editar" id="hora_editar" value="" class="form-control" placeholder="Hora">
				            	<label class="mt-3">Notas:</label>
				            	<textarea class="form-control" name="notas_editar" id="notas_editar" placeholder="Notas"></textarea>
				            	<label class="mt-3">Color:</label>
				            	<select name="className_editar" id="className_editar" class="selectpicker">
				            		
				            		<?php 

				            			pintar_options_agenda();
				            		?>
				            		
				            	</select>
				            	<label class="mt-3">¿Avisar por email?:</label>
				            	<select name="avisar_editar" id="avisar_editar" class="custom-select">
				            		<option value="0">No</option>
				            		<option value="1">Si</option>
				            	</select>
				            	<label class="mt-3">¿Autocompletar?:</label>
				            	<select name="autocompletar_editar" id="autocompletar_editar" class="custom-select">
				            		<option value="0">No</option>
				            		<option value="1">Si</option>
				            	</select>
				            	<label class="mt-3">¿Repetir?:</label>
				            	<select name="repetir_editar" id="repetir_editar" class="custom-select">
				            		<option value="0">No</option>
				            		<option value="1">Si</option>
				            	</select>
				            	<div class="d-none" id="div_repetir_editar">
				            		<label class="mt-3">Tipo Repetición:</label>
				            		<select name="tipo_repeticion_editar" id="tipo_repeticion_editar" class="custom-select">
					            		<option value="days">Días</option>
					            		<option value="weeks">Semanas</option>
					            		<option value="month">Meses</option>
					            		<option value="year">Años</option>
					            	</select>
				            		<label class="mt-3">Frecuencia Repetición:</label>
				            		<select name="frecuencia_repeticion_editar" id="frecuencia_repeticion_editar" class="custom-select">
				            			<?php 

				            				for ($i=1; $i <=20 ; $i++) { 
				            					echo '<option value="'.$i.'">'.$i.'</option>';
				            				} 
				            			?>
										</select>
										<label class="mt-3">¿Finaliza?:</label>
					            	<select name="finaliza_editar" id="finaliza_editar" class="custom-select">
					            		<option value="0">No</option>
					            		<option value="1">Si</option>
					            	</select>
					            	<div class="d-none" id="div_finaliza_editar"> 
					            		<label class="mt-3">Fecha <span class="text-danger">*</span>:</label>
						            	<input type="text" name="fecha_fin_editar" id="fecha_fin_editar" value="" class="form-control datepicker" placeholder="Fecha" verify="0">
							        </div>
				            	</div>
				            	
				            </form>
							<?php // este es el formulario de envio de mensajes ?>
							<div class="messages-form">
								<div class="messages-form-control pb-3">
									<button type="button" class="btn btn-info btn-block" id="edit_event_lat_btn">Editar Evento</button>
								</div>
							</div> <!-- <div class="messages-form"> -->
						</div> <!-- <div class="messages-wrapper"> -->
					</div> <!-- id="messages" -->
					<!-- <div class="tab-chat tab-pane fade" id="today" role="tabpanel">
					
					</div> -->
				</div> <!-- <div class="tab-content mt-3"> -->
			</div>
		</div>
		<div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" id="sumar_cita_modal">
		    <div class="modal-dialog" role="document">
		        <div class="modal-content">
		            <div class="modal-header">
		              <h5 class="modal-title">Gestión de Eventos</h5>
		              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		                  <span aria-hidden="true">&times;</span>
		              </button>
		            </div>

		            <div class="modal-body">
		            	<form action="" method="post" id="eventos_form" class="form-row has-info" accept-charset="utf-8"> 
		            		<input type="hidden" name="accion" value="i">
		            		<input type="hidden" name="tabla_insertar" value="tareas">
							<input type="hidden" name="notify" value="1">
							<input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
		            		<label class="mt-3">Título <span class="text-danger">*</span>:</label>
		            		<input type="text" name="title" value="" class="form-control" placeholder="Título" verify="1">
		            		<label class="mt-3">Fecha <span class="text-danger">*</span>:</label>
		            		<input type="text" name="start" value="" class="form-control datepicker" placeholder="Fecha" verify="1">
		            		<label class="mt-3">Hora <span class="text-danger">*</span>:</label> 
		            		<input type="text" name="hora" value="" class="form-control" placeholder="Hora">
		            		<label class="mt-3">Notas:</label>
		            		<textarea class="form-control" name="notas" placeholder="Notas"></textarea>
		            		<label class="mt-3">Color:</label>
		            		<select name="className" class="selectpicker">
		            			
		            			<?php 

		            				pintar_options_agenda();
		            			?>
		            			
		            		</select>
		            		<label class="mt-3">¿Avisar por email?:</label>
		            		<select name="avisar" class="custom-select">
		            			<option value="0">No</option>
		            			<option value="1">Si</option>
		            		</select>
		            		<label class="mt-3">¿Autocompletar?:</label>
		            		<select name="autocompletar" class="custom-select">
		            			<option value="0">No</option>
		            			<option value="1">Si</option>
		            		</select>
		            		<label class="mt-3">¿Repetir?:</label>
		            		<select name="repetir" class="custom-select">
		            			<option value="0">No</option>
		            			<option value="1">Si</option>
		            		</select>
		            		<div class="d-none">
		            			<label class="mt-3">Tipo Repetición:</label>
		            			<select name="tipo_repeticion" class="custom-select">
			            			<option value="days">Días</option>
			            			<option value="weeks">Semanas</option>
			            			<option value="month">Meses</option>
			            			<option value="year">Años</option>
			            		</select>
		            			<label class="mt-3">Frecuencia Repetición:</label>
		            			<select name="frecuencia_repeticion" class="custom-select">
		            				<?php 

		            					for ($i=1; $i <=20 ; $i++) { 
		            						echo '<option value="'.$i.'">'.$i.'</option>';
		            					} 
		            				?>
								</select>
								<label class="mt-3">¿Finaliza?:</label>
			            		<select name="finaliza" class="custom-select">
			            			<option value="0">No</option>
			            			<option value="1">Si</option>
			            		</select>
			            		<div class="d-none">
			            			<label class="mt-3">Fecha <span class="text-danger">*</span>:</label>
				            			<input type="text" name="fecha_fin" value="" class="form-control datepicker" placeholder="Fecha" verify="0">
					            	</div>
		            		</div>
		            		
		            	</form>
		            </div>
		            <div class="modal-footer">
		              <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
		              <button type="button" class="btn btn-success" id="crear_evento">Crear Evento</button>
		              <input type="hidden" id="nuevo_id" value="0">
		            </div>

		        </div>
		    </div>
		  </div>
		<script>

			            //Chat Open
		    $(document).on('click', '.messages-contact .user a', function(){

            	var id_evento = $(this).attr('id_evento');
            	var hora = $(this).attr('hora');
            	var title = $(this).attr('title');
            	var idtr = $(this).attr('idtr');
            	if(idtr == 0){

            		$('#div_edicion_masiva').addClass('d-none');

            	}else{

            		$('#div_edicion_masiva').removeClass('d-none');

            	}
            	$('#titulo_evento').html(title);
            	$('#hor_evento').html('('+hora+')');
            	$('#id_editar_agenda').val(id_evento);
		    	var serialize = '#edit_event_lat_form';
		        $(serialize).call_ajax({
		            url:"functions/ajax_agenda.php?tabla_cargar_datos_registros=1",
		            serialice:serialize
		        });
		        $('.tab-chat').addClass('show-chat');
		        
		    });

            $(document).on('dblclick', '.gestionar_evento', function(e){
            	
            	var id = $(this).attr('id_evento');
            	$.post('functions/ajax_agenda.php' ,
			    {
			        accion : 'u',
			        tabla_editar : 'tareas',
			        fecha_realizada_editar : '<?php echo now(); ?>',
			        id_editar : id
			    }, 
			    function(data){
			        $('#respuesta_ajax_js').html(data);
				}
			);

            });


		    $(document).on('click', '.messages-wrapper .return', function(){

            	$('#input_chat').attr('id_chat',0);
		    	$.post('functions/ajax_agenda.php' ,
		    	    {
		    	        refresacar_tareas : 1
		    	    }, 
		    	    function(data){
		    	        $('#respuesta_ajax_js').html(data);
		    		}
		    	);
		        $('.tab-chat').removeClass('show-chat');
		        
		    });

		   	$(document).on('click', '.close-quick-sidebar', function(){

            	$('#input_chat').attr('id_chat',0);
		    	$.post('functions/ajax_agenda.php' ,
		    	    {
		    	        refresacar_tareas : 1
		    	    }, 
		    	    function(data){
		    	        $('#respuesta_ajax_js').html(data);
		    		}
		    	);
		        $('.tab-chat').removeClass('show-chat')
		    });


		<?php //if(!isset($_SESSION['ip_admin'])){ ?>
			setInterval(function(){

				// var refresacar_mensajes = $('#input_chat').attr('id_chat');
				// var tipo_usuario = $('#input_chat').attr('tipo_usuario');

				$.post('functions/ajax_agenda.php' ,
				    {
				        refresacar_tareas : 1
				    }, 
				    function(data){
				        $('#respuesta_ajax_js').html(data);
					}
				);


			}, 3000);
			
		<?php //} ?>
			$('<audio id="chatAudio"><source src="functions/notify/notify.ogg" type="audio/ogg"><source src="functions/notify/notify.mp3" type="audio/mpeg"><source src="functions/notify/notify.wav" type="audio/wav"></audio>').appendTo('body');


		$('#edit_event_lat_btn').click(function(e){
		
			var te = $('[name="edicion_masiva"]').val(); 
			$('#edit_event_lat_form').form_validator_ajax({'url' : 'functions/ajax_agenda.php?notify=1&te='+te});
			$('.return').click();
			$(".quick-sidebar").animate({scrollTop: 0}, 100);
		});

		$('#crear_evento').click(function(e){
		
			if($('[name="title"]').val() == '' || $('[name="start"]').val() == '' || $('[name="hora"]').val() == ''){

				<?php noty_js('Tienes que poner título, fecha y hora','danger'); ?>

			}else{

				$('#eventos_form').form_validator_ajax({'url' : 'functions/ajax_agenda.php'});
				$('#sumar_cita_modal').modal('hide');

			}	

			// eventos_form
		}); 

		$('[name^="repetir"]').change(function(){

			if($(this).val() == 1){

				$(this).next('div').removeClass('d-none');

			}else{

				$(this).next('div').addClass('d-none');
				$(this).next('div').children('[name^="fecha_fin"]').attr('verify' , 0);

			}
		});

		$('[name^="finaliza"]').change(function(){

			if($(this).val() == 1){

				$(this).next('div').removeClass('d-none');
				$(this).next('div').children('[name^="fecha_fin"]').attr('verify' , 1);


			}else{

				$(this).next('div').addClass('d-none');
				$(this).next('div').children('[name^="fecha_fin"]').attr('verify' , 0);


			}
		});
	
		</script>
		