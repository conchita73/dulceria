<?php include 'inc/inc_start.php';  
if($_SESSION['id_usuario'] != 1) header('Location: panel.php');
$card_title = "Agenda";

// TABLA XRA SELECT, UPDATE, DELETE, INSERT
$tabla="comercios";
// posicion 0 = nombre del form que va en la modal
// posicion 1 = text del button que va a accionar esa modal

// $modal_1=array("comercio_form","Crear Comercio");


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
										<h4 class="card-title"><?php echo $card_title; ?> <a href="img/manuales/agenda.pdf" target="_blank"><i class="fas fa-file-download text-success"></i></a></h4>
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
									<div id="calendar">
									</div>
								</div> <!-- <div class="card-body"> -->
							</div> <!-- <div class="card"> -->
						</div> <!-- <div class="col-md-12"> -->
					</div> <!-- <div class="row row-card-no-pd"> -->
				</div> <!-- <div class="page-inner"> -->
				<?php include 'inc/inc_footer.php'; ?>
			</div> <!-- <div class="content content-scroll scrollbar-inner"> -->
		</div> <!-- <div class="main-panel"> -->
	</div> <!-- <div class="wrapper"> -->
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
	            		<input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
						<input type="hidden" name="notify" value="1">
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
	  <div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" id="editar_cita_modal">
	    <div class="modal-dialog" role="document">
	        <div class="modal-content">
	            <div class="modal-header">
	              <h5 class="modal-title">Gestión de Eventos</h5>
	              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                  <span aria-hidden="true">&times;</span>
	              </button>
	            </div>

	            <div class="modal-body">
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
	            		<small class="text-danger mt-3">*Los eventos sólo se pueden gestionar uno por uno</small>
	            		<button type="button" class="btn btn-success btn-block " id="gestionar_evento">DobleClick : Pasar a "Gestionado"</button>
	            	</div>
	            	<form action="" method="post" id="editar_eventos_form" class="form-row has-info" accept-charset="utf-8"> 
	            		<input type="hidden" name="accion" value="u">
	            		<input type="hidden" name="tabla_editar" value="tareas">
	            		<input type="hidden" name="id_editar" id="id_editar" value="0">
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
	            		<div class="d-none">
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
		            		<div class="d-none">
		            			<label class="mt-3">Fecha <span class="text-danger">*</span>:</label>
			            			<input type="text" name="fecha_fin_editar" id="fecha_fin_editar" value="" class="form-control datepicker" placeholder="Fecha" verify="0">
				            	</div>
	            		</div>
	            		
	            	</form>
	            </div>
	            <div class="modal-footer">
	              <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
	              <button type="button" class="btn btn-success" id="modificar_evento">Modificar Evento</button>
	              <input type="hidden" id="nuevo_id" value="0">
	            </div>

	        </div>
	    </div>
	  </div>
	<?php 

		$no_agenda = 1;
		include 'inc/inc_javascript.php'; 

		if(isset($modal_1) && $modal_1[0]!=""){
            pintar_modal_json($modal_1[0],i,$tabla);
            pintar_modal_json($modal_1[0],u,$tabla);
            pintar_modal_json("borrar_registro",d,$tabla);
        }

	?>
		

	
	<script>

		$(document).ready(function () {

			var date = new Date();
			var d = date.getDate();
			var m = date.getMonth();
			var y = date.getFullYear();
			var className = Array('fc-primary', 'fc-danger', 'fc-default', 'fc-success', 'fc-info', 'fc-warning', 'fc-danger-solid', 'fc-warning-solid', 'fc-success-solid', 'fc-default-solid', 'fc-success-solid', 'fc-primary-solid');

			$calendar = $('#calendar');
			$calendar.fullCalendar({
				header: {
					left: 'prev,next,today,citas',
					center: 'title',
					right: 'month,agendaWeek,agendaDay,listWeek'
				},
				weekLabel: "Sm",
				allDayHtml: "Todo<br/>el día",
				eventLimitText: "más",
				noEventsMessage: "No hay eventos para mostrar",
				firstDay : 1,
				selectable : true,
				editable : true,
				selectHelper: true,
				eventLimit : true,
				buttonText: {
				    today: "Hoy",
				    month: "Mes",
				    week: "Semana",
				    day: "Día",
				    list: "Agenda"
				},
				customButtons : {
					citas : {
						text : 'Crear Evento',
						click : function(){
						
							$('[name="start"]').val('<?php echo date('d/m/Y') ?>');
		            		$('#sumar_cita_modal').modal('show');


						} // click : function(){

					} // citas : {

				}, // customButtons : {
				select: function(start, end) {

		            $('#sumar_cita_modal').modal('show');
						
				},
				dayClick: function (date, jsEvent, view) {
            		
            		var fecha = date.format();
            		var fc = fecha.split('-');
					$('[name="start"]').val(fc[2]+'/'+fc[1]+'/'+fc[0]);
		            $('#sumar_cita_modal').modal('show');


		        }, 
		        eventClick: function (calEvent, jsEvent, view) {

		            $('[name="id_editar"]').val(calEvent.id);
		            var serialize = '#editar_eventos_form';
		            $(serialize).call_ajax({
		                  url:"functions/ajax_agenda.php?tabla_cargar_datos_registros=1",
		                  serialice:serialize
		            });
		            $('#editar_cita_modal').modal('show');

		        },
				eventDrop : function(calEvent, jsEvent, view){

					var fc = calEvent.start.format().split("T");
					var st = fc[0].split('-');
					$.post('functions/ajax_agenda.php' ,
					    {
					        accion : 'u',
					        tabla_editar : 'tareas',
					        id_editar : calEvent.id,
					        visto_editar : 0,
					        fecha_realizada_editar : '0000-00-00 00:00:00',
					        start_editar : st[2]+'/'+st[1]+'/'+st[0]
					    }, 
					    function(data){
					        $('#respuesta_ajax_js').html(data);
						}
					);


				},
				events: [
				
					<?php 

						$events = '';
						$fc =  date("Y-m-d",strtotime(date('Y-m-d')."- 2 year"));
					 	$DB->query('SELECT * FROM tareas where start>='.$fc);
					  	while($d = $DB->fetch()){
					 		
					 		$className = $d['fecha_realizada']!='0000-00-00 00:00:00' ? 'ok' : $d['className'];
					 		$events.= "{
						 			id: ".$d['id'].",
									title: '".utf8_encode($d['title'])."',
									start: '".$d['start'];

							$events.= $d['hora'] != '00:00:00' ? "T".$d['hora']."'," : "',";
							
							$events.= "
									hora : '".$d['hora']."',
									notas : '".utf8_encode($d['notas'])."',
									id_tarea_recurrente : '".$d['id_tarea_recurrente']."',
									className: '".$className."',
									fecha_realizada : '".$d['fecha_realizada']."',
									id_usuario : '".$d['id_usuario']."',
									repetir : '".$d['repetir']."',
									tipo_repeticion : '".$d['tipo_repeticion']."',
									frecuencia_repeticion : '".$d['frecuencia_repeticion']."',
									finaliza : '".$d['finaliza']."',
									fecha_fin : '".$d['fecha_fin']."',
									avisar : '".$d['avisar']."',
									autocompletar : '".$d['autocompletar']."'

								},";

						 		
					  	} // while($d = $DB->fetch()){

					  	echo $events;

					?>

				]
				

			}); // $calendar.fullCalendar({

		}); // $(document).ready(function () {

		function agregar_evento(){

			var eventData;
			var title = $('[name="title"]').val();
			var start = $('[name="start"]').val();
			var str = start.split('/');
			var hora = $('[name="hora"]').val();
			var nuevo_id = $('#nuevo_id').val();
			start = str[2]+'-'+str[1]+'-'+str[0];
			start = hora!='' ? start+'T'+hora : start;

			// esto crea un evento directo en el calendario
			if (title) {
				eventData = {
					id : nuevo_id,
					title: title,
					start: start,
					hora : hora,
					className: $('[name="className"]').val(),
					notas : $('[name="notas"]').val(),
					id_tarea_recurrente : 0,
					fecha_realizada : $('[name="fecha_realizada"').val(),
					id_usuario : <?php echo $_SESSION['id_usuario'] ?>,
					repetir : $('[name="repetir"').val(),
					tipo_repeticion : $('[name="tipo_repeticion"').val(),
					frecuencia_repeticion : $('[name="frecuencia_repeticion"').val(),
					finaliza : $('[name="finaliza"').val(),
					fecha_fin : $('[name="fecha_fin"').val(),
					avisar : $('[name="avisar"').val(),
					autocompletar : $('[name="autocompletar"').val(),
					// end: end

				};
				$calendar.fullCalendar('renderEvent', eventData, true); // stick? = true
			}
			$calendar.fullCalendar('unselect');
		}


		$('#crear_evento').click(function(e){
		
			if($('[name="title"]').val() == '' || $('[name="start"]').val() == '' || $('[name="hora"]').val() == ''){

				<?php noty_js('Tienes que poner título, fecha y hora','danger'); ?>

			}else{

				$('#eventos_form').form_validator_ajax({'url' : 'functions/ajax_agenda.php'});
				$('#sumar_cita_modal').modal('hide');

				setTimeout(function(){agregar_evento();},500);

			}	

			// eventos_form
		}); 

		$('#modificar_evento').click(function(e){
		
			if($('[name="title_editar"]').val() == '' || $('[name="start_editar"]').val() == '' || $('[name="hora_editar"]').val() == ''){

				<?php noty_js('Tienes que poner título, fecha y hora','danger'); ?>

			}else{

				var idtr = $('[name="id_tarea_recurrente_editar"]').val();
				var te = $('[name="edicion_masiva"]').val();
				
				$('#editar_eventos_form').form_validator_ajax({'url' : 'functions/ajax_agenda.php?te='+te});

				
				if(idtr == 0 || idtr>0 && te == 'this'){

					var id_editar = $('#id_editar').val();
					var eventData;
					var start = $('[name="start_editar"]').val();
					var str = start.split('/');
					var hora = $('[name="hora_editar"]').val();
					start = str[2]+'-'+str[1]+'-'+str[0];
					start = hora!='' ? start+'T'+hora : start;

					$('#editar_cita_modal').modal('hide');
					eventData = {
						id : $('[name="id_editar"]').val(),
						title: $('[name="title_editar"]').val(),
						start: start,
						hora : hora,
						className: $('[name="className_editar"]').val(),
						notas : $('[name="notas_editar"]').val(),
						id_tarea_recurrente : idtr,
						fecha_realizada : $('[name="fecha_realizada_editar"').val(),
						id_usuario : <?php echo $_SESSION['id_usuario'] ?>,
						repetir : $('[name="repetir_editar"').val(),
						tipo_repeticion : $('[name="tipo_repeticion_editar"').val(),
						frecuencia_repeticion : $('[name="frecuencia_repeticion_editar"').val(),
						finaliza : $('[name="finaliza_editar"').val(),
						fecha_fin : $('[name="fecha_fin_editar"').val(),
						avisar : $('[name="avisar_editar"').val(),
						autocompletar : $('[name="autocompletar_editar"').val(),
						// end: end

					}
					$calendar.fullCalendar('removeEvents', id_editar);
					$calendar.fullCalendar('renderEvent', eventData, true); 
					$calendar.fullCalendar('unselect');

				} // if(idtr == 0 || idtr>0 && te == 'este'){

			} // }else{	

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
			

		$('#borrar_evento').dblclick(function(e){

			var idtr = $('[name="id_tarea_recurrente_editar"]').val();
			var em = idtr!=0 ? $('[name="edicion_masiva"]').val() : 'this';
			var reload = em == 'all' ? 1 : 0;
			var id = $('[name="id_editar"]').val();
			$.post('functions/ajax_agenda.php' ,
			    {
			        borrar_registro : 1,
			        tabla_eliminar : 'tareas',
			        edicion_masiva : em,
			        idtr : idtr,
			        reload : reload,
			        id_eliminar : id
			    }, 
			    function(data){
			        $('#respuesta_ajax_js').html(data);
				}
			);

			if(em == 'this'){

				$('#editar_cita_modal').modal('hide');
				$calendar.fullCalendar('removeEvents', id);
				<?php noty_js('Evento eliminado con éxito','success'); ?>
			}
		});

		
		$('#gestionar_evento').dblclick(function(e){

			var id = $('[name="id_editar"]').val();
			$calendar.fullCalendar('removeEvents', id);

			var ee;
			var idtr = $('[name="id_tarea_recurrente_editar"]').val();
			var start = $('[name="start_editar"]').val();
			var str = start.split('/');
			var hora = $('[name="hora_editar"]').val();
			start = str[2]+'-'+str[1]+'-'+str[0];
			start = hora!='' ? start+'T'+hora : start;

			$('#editar_cita_modal').modal('hide');
			ee = {
				id : id,
				title: $('[name="title_editar"]').val(),
				start: start,
				hora : hora,
				className: 'fc-ok',
				notas : $('[name="notas_editar"]').val(),
				id_tarea_recurrente : idtr,
				fecha_realizada : '<?php echo now(); ?>',
				id_usuario : <?php echo $_SESSION['id_usuario'] ?>,
				repetir : $('[name="repetir_editar"').val(),
				tipo_repeticion : $('[name="tipo_repeticion_editar"').val(),
				frecuencia_repeticion : $('[name="frecuencia_repeticion_editar"').val(),
				finaliza : $('[name="finaliza_editar"').val(),
				fecha_fin : $('[name="fecha_fin_editar"').val(),
				avisar : $('[name="avisar_editar"').val(),
				autocompletar : $('[name="autocompletar_editar"').val(),
				// end: end

			}

			$calendar.fullCalendar('renderEvent', ee, true); 
			// $calendar.fullCalendar('unselect');
			<?php noty_js('Evento gestionado con éxito','success'); ?>

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
	</script>
</body>
</html>