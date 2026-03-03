<?php include 'inc/inc_start.php';  
$title_heading="";

$deuda = 0;
$d = $DB->select('sum(importe) as deuda','facturas','estado = 0 and id_cliente = '.$id);

$deuda = $d[f][0]['deuda']!='' && $d[f][0]['deuda']>0 ? $d[f][0]['deuda'] : 0;

?>

							<div class="card">
								<div class="card-header">
									<div class="card-head-row">
										<button type="button" class="btn btn-round btn-info atras btn-icon mr-1" ><i class="fas fa-arrow-circle-left"></i></button>
										<h4 class="card-title">Compras de clientes:</h4>
										<?php if($deuda > 0){ ?>
											<a href="/new_app/functions/descargar_documentacion.php?id_cliente=<?php echo $id; ?>&pagadas=0"
												target="_blank"
												class="btn btn-warning btn-round ml-auto ver_albaranes">
													<span id="deuda_span">Ver albaranes Pendientes por <?php echo number_format(($deuda/100),2,'.',''); ?> &euro;</span>
											</a>
										<?php }else{ ?>

											<button class="btn btn-success btn-round ml-auto" disabled="disabled">
													No hay cuentas pendientes
											</button>

										<?php } ?>
									</div>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col">
											<ul class="nav nav-tabs nav-justified" id="myTab_2" role="tablist">
											  	<li class="nav-item">
											    	<a class="nav-link active" id="home-tab_2" data-toggle="tab" href="#home_2" role="tab" aria-controls="home_2" aria-selected="true">Cobros Pendientes</a>
											  	</li>
											  	<li class="nav-item">
											    	<a class="nav-link" id="profile-tab_2" data-toggle="tab" href="#profile_2" role="tab" aria-controls="profile" aria-selected="false">Historial de Compras</a>
											  	</li>
											</ul>
											<div class="tab-content" id="myTabContent_2">
											  	<div class="tab-pane fade show active" id="home_2" role="tabpanel" aria-labelledby="home-tab_2">

													<form action="" method="post" accept-charset="utf-8" id="filtro_form">
														<input type="hidden" name="tabla" value="facturas_pendientes">
														<input type="hidden" name="id_cliente" value="<?php echo $id; ?>">
														<input type="hidden" name="buscar_tables_ajax" value="1">
														<div class="form-row has-info">
															<div class="col-12">
																<h3 class="text-center">Filtra los albaranes</h3>
															</div>
															<div class="col-md-6 mb-2">
																<label for="">Desde:</label>
																<input type="text" name="desde" class="form-control datepicker is_albaran" verify="1">
															</div>
															<div class="col-md-6 mb-2">
																<label for="">Hasta:</label>
																<input type="text" name="hasta" class="form-control datepicker is_albaran">
															</div>
															<div class="col-md-12 mb-2">
																<button type="button" class="mt-2 btn btn-success" data-toggle="modal" data-target="#fecha_cobrada_modal" >Cobrar Selección</button>
																<button type="button" class="mt-2 btn btn-info float-right btn_filtro">Filtrar Albaranes</button>
															</div>
														</div> <!-- <div class="form-row has-info"> -->
													</form>
													<div class="table-responsive">
														<div id="ultimas_facturas_pendientes" >
															<div class="col-12 text-center">
																<small class="text-danger">*Aqu&iacute; se visualizan el 100% de todo lo que tiene pendiente</small>
															</div>
														<?php 
														
															echo pintar_tbody('facturas_pendientes','',' where id_cliente='.$id.' and f.estado = 0 and agrupado<2')['body'];

														?>
														</div>
														<div id="resultado_facturas_pendientes" class='d-none'>
															
														</div>

													</div> <!-- <div class="table-responsive"> -->
											  	</div>  <!-- id="home" -->
											  	<div class="tab-pane fade" id="profile_2" role="tabpanel" aria-labelledby="profile-tab_2" style="min-height: 350px;">
											  		<form action="" method="post" accept-charset="utf-8" id="filtro_fac_form">
														<input type="hidden" name="tabla" value="facturas">
														<input type="hidden" name="id_cliente" value="<?php echo $id ?>">
														<input type="hidden" name="buscar_tables_ajax" value="1" id="call">
														<div class="form-row has-info">
															<div class="col-12 row">
																<h3 class="text-center col-12">Filtra las Facturas</h3>
																<div class="col text-success">
																	Cobrados: <span id="albaranes_cobrados_span">0.00</span> &euro;
																</div>
																<div class="col text-danger">
																	Pendientes: <span id="albaranes_pendientes_span">0.00</span> &euro;
																</div>
															</div>
															<div class="col-md-4 mb-2">
																<label for="">Fecha Desde:</label>
																<input type="text" name="desde" class="form-control datepicker" placeholder="Desde" verify="0">
															</div>
															<div class="col-md-4 mb-2">
																<label for="">Fecha Hasta:</label>
																<input type="text" name="hasta" class="form-control datepicker hasta" placeholder="Hasta" verify="0">
															</div>
															<div class="col-md-4 mb-2 has-info">
																<label for="">Tipos de Fecha:</label>
																<select name="tipo_fecha" class="custom-select" verify="0">
																	<option value="fecha_cobro">Fecha Pedido</option>
																	<option value="fecha_cobrada">Fecha Pago</option>
																</select>
															</div>
															<div class="col-md-4 mb-2 has-info">
																<label for="">Estado:</label>
																<select name="estado" class="custom-select" verify="0">
																	<option value="2">Todos</option>
																	<option value="1">Pagadas</option>
																	<option value="0">Pendientes</option>
																</select>
															</div>
															<div class="col-md-4 mb-2">
																<label for="">¿Esta Facturada?</label>
																<select name="facturada" class="custom-select" verify="0">
																	<option value="2">Sacar todo</option>
																	<option value="1">Si</option>
																	<option value="0">No</option>
																</select>
															</div>
															<div class="col-md-4 mb-2">
																<label for="">Motivo:</label>
																<select name="motivo" class="custom-select">
																	<option value="buscar_tables_ajax">Listado de Facturas</option>
																	<option value="buscar_datos_gestoria">Datos Gestoría (Sólo lo pagado)</option>
																</select>
															</div>
															<div class="col-md-12 mb-2">
																
																<button type="button" class="mt-2 btn btn-info float-right btn_filtro_2">Filtrar Facturas</button>
															</div>
														</div> <!-- <div class="form-row has-info"> -->
													</form>
													<div class="table-responsive pt-3">
														<div id="ultimas_facturas">
															<?php 
															
																$tbody_facturas = pintar_tbody('facturas','',' where id_cliente='.$id);
																echo $tbody_facturas['body'];

															?>
														</div>
														<div id="resultado_facturas" class=''>
															
														</div>
													</div> <!-- <div class="table-responsive"> -->
											  	</div> <!-- id="profile" -->
											</div> <!-- id="myTabContent" -->
										</div> <!-- <div class="col"> -->
									</div> <!-- <div class="row"> -->
								</div> <!-- <div class="card-body"> -->
							</div> <!-- <div class="card"> -->
<?php $no_data_table=1;
	//include 'inc/inc_javascript.php'; ?>

	<!-- -->

  <div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" id="fecha_cobrada_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Introduce una fecha:</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            	<span class="text-danger">* Todos los albaranes o facturas que hayas seleccionado, van a tener como fecha de cobro la fecha que este seleccionada</span>
            <input type="text" name="fecha_cobrada" value="<?php echo date('d/m/Y') ?>" placeholder="Fecha Cobrada" class="form-control datepicker">  
            <label class="mt-2">Hacer Movimientos Bancarios:</label><br>
            <span class="text-danger">* Si no haces los movimientos no se pasará a pagado.</span>
            <select name="hacer_movimientos" class="custom-select">
            	<option value="0">No</option>
            	<option value="1">Si</option>
            </select>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
              <button type="button" class="btn btn-success cobrar_selecion">Cobrar Selección</button>
            </div>
        </div>
    </div>
  </div>

<script>
	
	$(document).ready(function(){


		$(document).on('dblclick','.is_factura', function(e){
				
				var id_factura = $(this).attr('id_factura');
				$.post('functions/ajax.php' ,
				    {
				        pagar_factura : 1,
				        id_factura : id_factura

				    }, 
				    function(data){
				        $('#respuesta_ajax_js').html(data);
					}
				);
				// proceso de pasar a pagado y enviar factura al cliente.

		});

		$('.atras').click(function(e){
			
			$('#pre_load').removeClass('d-none');
			$('#load').addClass('d-none');
			$('[type="search"]').focus();

		});// $('.atras')

		$('.datepicker').datetimepicker({
                format: 'DD/MM/YYYY',
                widgetPositioning: {
                    horizontal: 'right',
                    vertical: 'bottom'
                }
		});// $('.datepicker').datetimepicker({

		$('[data-toggle="tooltip"]').tooltip();

		$('[id^="facturacion"]').dataTable({
              				"pageLength": 10,
                      stateSave: true,
                      order: [[ 0, "desc" ]],
                      language: {
                          processing:     "En Proceso ......",
                          search:         "Buscar <i class='fas fa-search text-info'></i>",
                          lengthMenu:    "Mostrando _MENU_ Registros",
                          info:           "Mostrando _START_ al _END_ de _TOTAL_ Registros",
                          infoEmpty:      "Mostrando 0 al 0 de 0 Registros",
                          infoFiltered:   "(Hay un total de _MAX_ elementos)",
                          infoPostFix:    "",
                          loadingRecords: "Carga en Curso ...",
                          zeroRecords:    "No hay ningún registro con estos Datos",
                          emptyTable:     "No hay ninguna tabla creada",
                          paginate: {
                              first:      "Primera",
                              previous:   "Atrás",
                              next:       "Siguiente",
                              last:       "Última"
                          },
                          aria: {
                              sortAscending:  ": Active para ordenar la columna en orden ascendente",
                              sortDescending: ": Active para ordenar la columna en orden Descendente"
                          }
                        }
              			});


		


		$('.btn_filtro').click(function(e){

			if($('[name="hasta"]').val() == ''){

				$('[name="hasta"]').val('<?php echo date('d/m/Y'); ?>');

			}

			$('#filtro_form').form_validator_ajax({aviso: 'notify'});

		}); // $('.btn_filtro').click(function(e){

		$('.btn_filtro_2').click(function(e){

			if($('.hasta').val() == ''){

				$('.hasta').val('<?php echo date('d/m/Y'); ?>');

			}

			$('#filtro_fac_form').form_validator_ajax({aviso: 'notify'});

		}); // $('.btn_filtro').click(function(e){


		$(document).on('change','#cobrar_todo, #facturar_todo',function(){

			var pre_tipo  = $(this).attr('id');
			var tipo  = pre_tipo.split('_todo');

			if($(this).is(':checked')){

				$('[name="'+tipo[0]+'"]').prop('checked',true);

			}else{

				$('[name="'+tipo[0]+'"]').prop('checked',false);

			}

		});
             
          

        $('.cobrar_selecion').click(function(e){

        	    var facturar = 0;
				var facturados = new Object();

				var cobrar = 0;
				var cobrados = new Object();


            	$('[name="cobrar"]').each(function(){

            		if($(this).is(':checked') && $(this).is(':visible')){ 

              			cobrados[cobrar] = $(this).attr('id_factura');
            			cobrar++; 
            		}
           		});

            	$('[name="facturar"]').each(function(){

            		if($(this).is(':checked') && $(this).is(':visible')){ 

              			facturados[facturar] = $(this).attr('id_factura');
            			facturar++; 
            		}
           		});

            	if(cobrar==0 && facturar==0){

            		<?php noty_js('Selecciona algun Albarán para cobrar','danger'); ?>

            	}else{


            		<?php if(!isset($_SESSION['ip_admin'])){ ?>
							$('#espera_general').modal('show');
					<?php } ?>
            		var href = $('.ver_albaranes').attr('href');
            		var desde = $('[name="desde"].is_albaran').val();
            		var hasta = $('[name="hasta"].is_albaran').val();
            		var fecha_cobrada = $('[name="fecha_cobrada"]').val();
            		var hacer_movimientos = $('[name="hacer_movimientos"]').val();

            		// en el post, tengo que hacer hide de cada uno de los seleccionados
 					$.post('functions/ajax.php' ,
                    {
                    	cobrado_masivo : 1,
                    	cobrados : cobrados,
                    	n_cobros : cobrar,
                    	facturados : facturados,
                    	n_facturas : facturar,
                    	desde : desde,
                    	hasta : hasta,
                    	href : href,
                    	fecha_cobrada : fecha_cobrada,
                    	hacer_movimientos : hacer_movimientos
                    } , 
                    function(data){
                        $('#respuesta_ajax_js').html(data);
                    }); 


            	}

		}); // $('.cobrar_selecion').click(function(e){ 


            // $(document).on('click','[name="ver_facturas"]',function(){

            //     var id = $(this).attr('id_factura');
            //     window.open('functions/descargar_documentacion.php?id_factura='+id,'_blank');
            //     return false;

            // });

            // $(document).on('click','[name="quitar_pago"]',function(event) {
            
            //     var id_factura = $(this).attr('id');
            //     $.post('functions/ajax.php' ,
            //         {
            //             quitar_pago : 1,
            //             id_factura : id_factura
            //         }, 
            //         function(data){
            //             $('#respuesta_ajax_js').html(data);
            //         }
            //     );
            // });  



            $("#albaranes_cobrados_span").html("<?php echo number_format(($tbody_facturas['total_ingresos']),2,'.','')?>");
            $("#albaranes_pendientes_span").html("<?php echo number_format(($tbody_facturas['total_gastos']),2,'.','')?>");

            $('input[type="text"]').keydown(function(){
              $(this).css({ "background-color" : "white" });
            });

            $('input[type="text"]').change(function(){
              $(this).css({ "background-color" : "white" });
            });

            $('[name="editar_pop_up"]').click(function(){

                var width = 680;
                var height = 800;
                var left = (screen.width) ? (screen.width-width)/2 : 0;
                var top = (screen.height) ? (screen.height-height)/2 : 0;
                var id = $(this).attr('id');
                var edit = id != undefined ? '?id='+id : '';
                var tabla = $(this).attr('tabla');
                window.open('pop_up_editar_'+tabla+'.php'+edit,'_blank',"width="+width+",height="+height+",top="+top+",left="+left+",scrollbars=1");
                return false;

            });

    }); // 	$(document).ready(function(){
			
</script>
