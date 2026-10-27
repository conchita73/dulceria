<?php include 'inc/inc_start.php';  

$card_title = "Contabilidad General";

// TABLA XRA SELECT, UPDATE, DELETE, INSERT
$tabla="movimientos_bancarios";
// posicion 0 = nombre del form que va en la modal
// posicion 1 = text del button que va a accionar esa modal

$modal_1=array("movimientos_bancarios","Crear Mov. Monetario");
$modal_2=array("facturas","Crear Factura");




// --- CÁLCULO CONTABILIDAD ---
$meses_cont = array(
    'Ene'=>"01",'Feb'=>"02",'Mar'=>"03",'Abr'=>"04",
    'May'=>"05",'Jun'=>"06",'Jul'=>"07",'Ago'=>"08",
    'Sep'=>"09",'Oct'=>10,'Nov'=>11,'Dic'=>12,
);
$year_cont = date('Y');
$ing_cont = '';
$gas_cont = '';
$ben_cont = '';
$comp_cont = '';
$ingreso_total = 0;
$gastos_total = 0;
$beneficio_total = 0;
$compras_total = 0;

ob_start();
foreach ($meses_cont as $key => $value) {
    $DB->query('select sum(importe) as ingresos from movimientos_bancarios where fecha BETWEEN "'.$year_cont.'-'.$value.'-01 00:00:00" AND "'.$year_cont.'-'.$value.'-31 23:59:59" and tipo = 1');
    ${$key.'_ing'} = $DB->fetch();
    ${$key.'_ing'}['ingresos'] = (${$key.'_ing'}['ingresos'] != '') ? ${$key.'_ing'}['ingresos'] : 0;
    $ing_cont .= number_format((${$key.'_ing'}['ingresos']/100),2,'.','');
    $ing_cont .= $value!=12 ? ',' : '';
    $ingreso_total += ${$key.'_ing'}['ingresos'];

    $DB->query('select sum(importe) as gastos from movimientos_bancarios where fecha BETWEEN "'.$year_cont.'-'.$value.'-01 00:00:00" AND "'.$year_cont.'-'.$value.'-31 23:59:59" and tipo = 0 and clase like "gasto"');
    ${$key.'_gast'} = $DB->fetch();
    ${$key.'_gast'}['gastos'] = (${$key.'_gast'}['gastos'] != '') ? ${$key.'_gast'}['gastos'] : 0;
    $gas_cont .= number_format((${$key.'_gast'}['gastos']/100),2,'.','');
    $gas_cont .= $value!=12 ? ',' : '';
    $gastos_total += ${$key.'_gast'}['gastos'];

    $DB->query('select sum(importe) as compras from movimientos_bancarios where fecha BETWEEN "'.$year_cont.'-'.$value.'-01 00:00:00" AND "'.$year_cont.'-'.$value.'-31 23:59:59" and tipo = 0 and clase like "compra"');
    ${$key.'_comp'} = $DB->fetch();
    ${$key.'_comp'}['compras'] = (${$key.'_comp'}['compras'] != '') ? ${$key.'_comp'}['compras'] : 0;
    $comp_cont .= number_format((${$key.'_comp'}['compras']/100),2,'.','');
    $comp_cont .= $value!=12 ? ',' : '';
    $compras_total += ${$key.'_comp'}['compras'];

    ${$key.'_ben'} = (int)${$key.'_ing'}['ingresos']-(int)${$key.'_gast'}['gastos']-(int)${$key.'_comp'}['compras'];
    $ben_cont .= number_format(((int)${$key.'_ben'}/100),2,'.','');
    $ben_cont .= $value!=12 ? ',' : '';
    $beneficio_total += ${$key.'_ben'};
}
$_cont_sql_errors = ob_get_clean();
// --- FIN CÁLCULO CONTABILIDAD ---

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
										
									</div>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-12">
											<ul class="nav nav-tabs nav-primary justify-content-center" id="pills-tab" role="tablist">
												<li class="nav-item">
													<a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Movimientos Monetarios</a>
												</li>
												<li class="nav-item">
													<a class="nav-link" id="pills-unic-tab" data-toggle="pill" href="#pills-unic" role="tab" aria-controls="pills-unic" aria-selected="false">Facturaci&oacute;n</a>
												</li>
												<li class="nav-item">
													<a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Contabilidad</a>
												</li>
											</ul>
											<div class="tab-content mt-2 mb-3" id="pills-tabContent">
												<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
													<div class="row">
														<div class="col-12 mt-3 mb-3">
															<?php 

																// if(isset($modal_1) && $modal_1[0]!=""){

																// 	echo '<button 
																// 			class="btn btn-primary btn-round float-right" 
																// 			data-toggle="modal" 
																// 			data-target="#'.$modal_1[0].'"
																// 			data-backdrop="static" 
																// 			data-keyboard="false">
																// 			<i class="fa fa-plus"></i>
																// 		'.$modal_1[1].'
																// 	</button>';
																// }

															?>
														</div>
													</div>
													<form action="" method="post" accept-charset="utf-8" id="filtro_mov_form" class="form-row has-info">
														<input type="hidden" name="tabla" value="movimientos_bancarios">
														
														<input type="hidden" name="buscar_tables_ajax" value="1">
															<div class="col-12 row">
																<div class="col-12">
																	<h3 class="text-center">Filtra los Movimientos</h3>
																</div>
																<div class="col text-success">
																	Cobrados: <span id="movimientos_ventas_span">0.00</span> &euro;
																</div>

																<div class="col text-danger">
																	Compras: <span id="movimientos_compras_span">0.00</span> &euro;
																</div>
																<div class="col text-danger">
																	Gastos: <span id="movimientos_gastos_span">0.00</span> &euro;
																</div>
															</div>
															<div class="col-md-3 mb-2">
																<label for="">Desde:</label>
																<input type="text" name="desde" class="form-control datepicker" verify="0">
															</div>
															<div class="col-md-3 mb-2">
																<label for="">Hasta:</label>
																<input type="text" name="hasta" class="form-control datepicker">
															</div>
															<div class="col-md-3 mb-2">
																<label for="">Selecciona Tienda:</label>
																<select name="id_delegacion" class="form-control">
																	<option value="0">Sin aplicar</option>
																	<?php 

                                                                        $DB->query('Select * from delegaciones');
                                                                        while($d = $DB->fetch()){
                                                                        	
                                                                        	echo '<option value="'.$d['id'].'">'.utf8_encode($d['nombre']).'</option>';
                                                                        }

																	?>
																</select>
															</div>
															<div class="col-md-3 mb-2">
																<label for="">Clase de movimiento:</label>
																<select name="clase" class="custom-select">
																	<option value="todos">Todos</option>
																	<option value="venta">Venta</option>
																	<option value="compra">Compra</option>
																	<option value="gasto">Gasto</option>
																</select>
															</div>
															<div class="col-md-12 mb-2">
																
																<button type="button" class="mt-2 btn btn-info float-right btn_filtro">Filtrar Movimientos</button>
															</div>
														
													</form>
													<div class="table-responsive text-center">
														<small class="text-danger">*Por defecto, sólo sacamos los movimientos del úmtimo mes natural</small>
														<div id="ultimas_<?php echo $tabla ?>">
															<?php


														 		$tbody_movimientos =  pintar_tbody('movimientos_bancarios',$modal_1,' where fecha >= "'.date("Y-m-d",strtotime(date("Y-m-d")."- 1 month")).' 00:00:00"');
																echo $tbody_movimientos['body'];
			 
															?>
														</div>
														<div id="resultado_<?php echo $tabla ?>" class='d-none'>
															
														</div>
													</div> <!-- <div class="table-responsive"> -->
												</div> <!-- pills-home -->
												<div class="tab-pane fade" id="pills-unic" role="tabpanel" aria-labelledby="pills-unic-tab" style="min-height: 350px;">
													<form action="" method="post" accept-charset="utf-8" id="filtro_fac_form">
														<input type="hidden" name="tabla" value="facturas">
														
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
																<label for="">Buscar Cliente <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="Se puede buscar por Nombre, Nombre Comercial, Cif, Teléfono, email"></i>:</label>
																<input type="text" name="cliente" class="form-control" verify="0" placeholder="No es Obligatorio">
																<input type="hidden" name="id_cliente" value="0">
															</div>
															<div class="col-md-4 mb-2 has-info">
																<label for="">Tipos de Fecha:</label>
																<select name="tipo_fecha" class="custom-select" verify="0">
																	<option value="fecha_cobro">Fecha Pedido</option>
																	<option value="fecha_cobrada">Fecha Pago</option>
																</select>
															</div>
															<div class="col-md-4 row mb-2">
																<div class="col-6">
																	<label for="">Fecha Desde:</label>
																	<input type="text" name="desde" class="form-control datepicker" placeholder="Desde" verify="0">
																
																</div>
																<div class="col-6">
																	<label for="">Fecha Hasta:</label>
																	<input type="text" name="hasta" class="form-control datepicker" placeholder="Hasta" verify="0">
																</div>
															</div>
															<div class="col-md-4 mb-2">
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
																	<option value="1">Si</option>
																	<option value="0">No</option>
																	<option value="2">Sacar todo</option>

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
																<button 
																	class="btn btn-success" 
																	name="editar_pop_up" 
																	tabla="facturas">
																	<i class="fa fa-plus"></i>
																	Crear Factura
																</button>
																<button type="button" class="mt-2 btn btn-info float-right btn_filtro">Filtrar Facturas</button>
															</div>
														</div> <!-- <div class="form-row has-info"> -->
													</form>
													<div class="table-responsive text-center">
														<small class="text-danger">*Por defecto, sólo sacamos los movimientos del úmtimo mes natural</small>
														<div id="ultimas_facturas">
															<?php

														 		$tbody_facturas = pintar_tbody('facturas',$modal_2,' where fecha_cobro >= "'.date("Y-m-d",strtotime(date("Y-m-d")."- 1 month")).' 00:00:00"');
																echo $tbody_facturas['body'];

			 
															?>
														</div>
														<div id="resultado_facturas" class='d-none'>
															
														</div>
													</div>
												</div> <!-- pills-unic -->
												<div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
													<div class="row">
														<div class="col-md-12">
															<div class="card">
																<div class="card-body">
																	<div class="card-sub text-center">
																		<h2 class="text-info" id="text_year">Resultados del año <span id="span_data_year"><?php echo date('Y') ?></span></h2>
																		<div class="row">
																			<div class="col-sm-3 text-center">
																				<h3 class="text-success">Ingresos Totales:</h3>
																				<h2><span id="ing_final"><?php echo number_format(((int)$ingreso_total/100),2,'.','') ?></span>&euro;</h2>
																			</div> 
																			<div class="col-sm-3 text-center">
																				<h3 class="text-success">Gastos Totales:</h3>
																				<h2><span id="gast_final"><?php echo number_format(((int)$gastos_total/100),2,'.','') ?></span>&euro;</h2>
																			</div>
																			<div class="col-sm-3 text-center">
																				<h3 class="text-success">Compras Totales:</h3>
																				<h2><span id="comp_final"><?php echo number_format(((int)$compras_total/100),2,'.','') ?></span>&euro;</h2>
																			</div>
																			<div class="col-sm-3 text-center">
																				<h3 class="text-success">Beneficios Totales:</h3>
																				<h2><span id="ben_final"><?php echo number_format(((int)$beneficio_total/100),2,'.','') ?></span>&euro;</h2>
																			</div>
																		</div>
																	</div>
																	<div class="row">
																		<div class="col-sm-6 offset-sm-3 mb-4">
																			<label for="">Filtrar Por años</label><br>
																			<div class="input-group">
																				<select name="filtro_year_contabilidad" class="custom-select" >
																					<?php 
																						for ($i=2017; $i <=date('Y') ; $i++) { 
																							echo '<option value="'.$i.'"';

																							if($i == date('Y')){
																								echo ' selected="selected"' ;
																							}
																							echo '>'.$i.'</option>';
																						}
																					?>
																				</select>
																				<select name="id_delegacion_contabilidad" class="custom-select" >
																					<option value="0">Todas las tiendas</option>
																					<?php 

				                                                                        $DB->query('Select * from delegaciones');
				                                                                        while($d = $DB->fetch()){
				                                                                        	
				                                                                        	echo '<option value="'.$d['id'].'">'.utf8_encode($d['nombre']).'</option>';
				                                                                        }

																					?>
																				</select>
																			  	<div class="input-group-append">
																			   		<button type="button" class="btn btn-outline-info" id="button_filtro_contabilidad">Filtrar</button>
																			  	</div>
																			</div>
																		</div>
																	</div> 
																	<div class="chart-container" id="one_chart">
																		<canvas id="htmlLegendsChart"></canvas>
																	</div>
																	<div id="myChartLegend"></div>
																	<div class="chart-container" id="two_chart">
																		<canvas id="htmlLegendsChart-two"></canvas>
																	</div>
																	<div id="myChartLegend-two"></div>

																</div>
															</div>
														</div>
													</div>
												</div> <!-- pills-profile --> 	
											</div> <!-- tabContent -->
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
            pintar_modal_json('movimientos_bancarios',u,$tabla);
            pintar_modal_json("borrar_registro",d,$tabla);
        }

        pintar_modal_json($modal_2[0],u,'facturas');


	?>

	<script>

			$('#crear_cliente').click(function(){

				var width = 630;
                var height = 600;
                var left = (screen.width) ? (screen.width-width)/2 : 0;
                var top = (screen.height) ? (screen.height-height)/2 : 0;
            	window.open('pop_up_subir_clientes.php','_blank',"width="+width+",height="+height+",top="+top+",left="+left+",scrollbars=1");
                return false;

			});

            $('#concepto').before('<input type="hidden" name="verificado" value="1">');


			$(document).on('dblclick','.is_factura', function(e){
				
				$('#espera_general').modal('show');
				var id_factura = $(this).attr('id_factura');
				$.post('functions/ajax.php' ,
				    {
				        pagar_factura : 1,
				        id_factura : id_factura,
				        id_via_cobro : 1

				    }, 
				    function(data){
				        $('#respuesta_ajax_js').html(data);
					}
				);
				// proceso de pasar a pagado y enviar factura al cliente.

			});
            
		var htmlLegendsChart = document.getElementById('htmlLegendsChart').getContext('2d');
		// Chart with HTML Legends

		var gradientStroke = htmlLegendsChart.createLinearGradient(500, 0, 100, 0);
		gradientStroke.addColorStop(0, '#177dff');
		gradientStroke.addColorStop(1, '#80b6f4');

		var gradientFill = htmlLegendsChart.createLinearGradient(500, 0, 100, 0);
		gradientFill.addColorStop(0, "rgba(23, 125, 255, 0.7)");
		gradientFill.addColorStop(1, "rgba(128, 182, 244, 0.3)");

		var gradientStroke2 = htmlLegendsChart.createLinearGradient(500, 0, 100, 0);
		gradientStroke2.addColorStop(0, '#f3545d');
		gradientStroke2.addColorStop(1, '#ff8990');

		var gradientFill2 = htmlLegendsChart.createLinearGradient(500, 0, 100, 0);
		gradientFill2.addColorStop(0, "rgba(243, 84, 93, 0.7)");
		gradientFill2.addColorStop(1, "rgba(255, 137, 144, 0.3)");

		var gradientStroke3 = htmlLegendsChart.createLinearGradient(500, 0, 100, 0);
		gradientStroke3.addColorStop(0, '#fdaf4b');
		gradientStroke3.addColorStop(1, '#ffc478');

		var gradientFill3 = htmlLegendsChart.createLinearGradient(500, 0, 100, 0);
		gradientFill3.addColorStop(0, "rgba(253, 175, 75, 0.7)");
		gradientFill3.addColorStop(1, "rgba(255, 196, 120, 0.3)");

		var gradientStroke4 = htmlLegendsChart.createLinearGradient(500, 0, 100, 0);
		gradientStroke3.addColorStop(0, '#256D3C');
		gradientStroke3.addColorStop(1, '#3CBB65');

		var gradientFill4 = htmlLegendsChart.createLinearGradient(500, 0, 100, 0);
		gradientFill4.addColorStop(0, "rgba(43, 128, 70, 0.7)");
		gradientFill4.addColorStop(1, "rgba(60, 187, 101, 0.3)");

		var myHtmlLegendsChart = new Chart(htmlLegendsChart, {
			type: "line",
			data: {
				labels: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
				datasets: [ {
					label: "Compras",
					borderColor: gradientStroke4,
					pointBackgroundColor: gradientStroke4,
					pointRadius: 0,
					backgroundColor: gradientFill4,
					legendColor: '#256D3C',
					fill: true,
					borderWidth: 1,

					data: [<?php echo $comp_cont; ?>]

				},{
					label: "Gastos",
					borderColor: gradientStroke2,
					pointBackgroundColor: gradientStroke2,
					pointRadius: 0,
					backgroundColor: gradientFill2,
					legendColor: '#f3545d',
					fill: true,
					borderWidth: 1,
					data: [<?php echo $gas_cont; ?>]

				},{
					label: "Beneficios",
					borderColor: gradientStroke3,
					pointBackgroundColor: gradientStroke3,
					pointRadius: 0,
					backgroundColor: gradientFill3,
					legendColor: '#fdaf4b',
					fill: true,
					borderWidth: 1,

					data: [<?php echo $ben_cont; ?>]

				},{
					label: "Ingresos",
					borderColor: gradientStroke,
					pointBackgroundColor: gradientStroke,
					pointRadius: 0,
					backgroundColor: gradientFill,
					legendColor: '#177dff',
					fill: true,
					borderWidth: 1,

					data: [<?php echo $ing_cont; ?>]

				} ]
			},
			options : {
				responsive: true, 
				maintainAspectRatio: false,
				legend: {
					display: false
				},
				tooltips: {
					bodySpacing: 4,
					mode:"nearest",
					intersect: 0,
					position:"nearest",
					xPadding:10,
					yPadding:10,
					caretPadding:10
				},
				layout:{
					padding:{left:15,right:15,top:15,bottom:15}
				},
				scales: {
					yAxes: [{
						ticks: {
							fontColor: "rgba(0,0,0,0.5)",
							fontStyle: "500",
							beginAtZero: false,
							maxTicksLimit: 5,
							padding: 20
						},
						gridLines: {
							drawTicks: false,
							display: false
						}
					}],
					xAxes: [{
						gridLines: {
							zeroLineColor: "transparent"
						},
						ticks: {
							padding: 20,
							fontColor: "rgba(0,0,0,0.5)",
							fontStyle: "500"
						}
					}]
				}, 
				legendCallback: function(chart) { 
					var text = []; 
					text.push('<ul class="' + chart.id + '-legend html-legend">'); 
					for (var i = 0; i < chart.data.datasets.length; i++) { 
						text.push('<li><span style="background-color:' + chart.data.datasets[i].legendColor + '"></span>'); 
						if (chart.data.datasets[i].label) { 
							text.push(chart.data.datasets[i].label); 
						} 
						text.push('</li>'); 
					} 
					text.push('</ul>'); 
					return text.join(''); 
				}  
			}
		});

		var myLegendContainer = document.getElementById("myChartLegend");

		// generate HTML legend
		myLegendContainer.innerHTML = myHtmlLegendsChart.generateLegend();

		// bind onClick event to all LI-tags of the legend
		var legendItems = myLegendContainer.getElementsByTagName("li");
		for (var i = 0; i < legendItems.length; i += 1) {
			legendItems[i].addEventListener("click", legendClickCallback, false);
		}



        $.widget("custom.catcomplete",$.ui.autocomplete,
				{_renderMenu:function(e,t)
					{
					var a=this,o="";
					$.each(t,function(t,c){
						c.category!=o&&(e.append("<li class='ui-autocomplete-category'>"+c.category+"</li>"),o=c.category),a._renderItemData(e,c)
						})
					}
				}),
					$("[name='cliente']").catcomplete({minLength:2,source:"functions/ajax.php?buscar_cliente_autocomplete=1",
						select:function(e,t){

					    	$(this).val(t.item.value),
					    	$("[name='id_cliente']").val(t.item.id_cliente)



					}
		}); // $.widget("custom.catcomplete",$.ui.autocomplete,

		<?php if(isset($_SESSION['ip_admin'])){ ?>

		//$('[href="#pills-profile"]').click();

			
		<?php } ?> 


		$('#button_filtro_contabilidad').click(function(e){

			$.post('functions/ajax.php' ,
			    {
			        filtro_year_contabilidad : $('[name="filtro_year_contabilidad"]').val(),
			        id_delegacion : $('[name="id_delegacion_contabilidad"]').val(),
			    }, 
			    function(data){
			        $('#respuesta_ajax_js').html(data);
				}
			);
		
		});
		 

		$('[name="motivo"]').change(function(){
			  
		  	var name = $(this).val();
		  	$('#call').attr('name',name)

		});

		$("#albaranes_cobrados_span").html("<?php echo number_format(($tbody_facturas['total_ingresos']),2,'.','')?>");
        $("#albaranes_pendientes_span").html("<?php echo number_format(($tbody_facturas['total_gastos']),2,'.','')?>");

        $("#movimientos_ventas_span").html("<?php echo number_format(($tbody_movimientos['total_ingresos']),2,'.','')?>");
        $("#movimientos_compras_span").html("<?php echo number_format(($tbody_movimientos['totales']),2,'.','')?>");
        $("#movimientos_gastos_span").html("<?php echo number_format(($tbody_movimientos['total_gastos']),2,'.','')?>");

		// $('#fecha_editar').parent('div').remove();			

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
			
	</script>
</body>
</html>