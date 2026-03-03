<?php include 'inc/inc_start.php';  
$title_heading="Crear Formulario";



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
						<img src="img/logo_admin_2.png" alt="navbar brand" class="navbar-brand mt-3">
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
										<h4 class="card-title">Campos del Formulario </h4>
										<!-- <button class="btn btn-primary btn-round ml-auto" data-toggle="modal" data-target="#addRowModal">
											<i class="fa fa-plus"></i>
											Crear Usuario
										</button> -->
									</div>
								</div>
								<div class="card-body">
									<div class="row">
									
			                                    <div class="col" style="padding-bottom:10px;">
			                                        <form action="" method="post" accept-charset="utf-8">
			                                            <legend>Datos principales del Formulario</legend>
			                                            <div class="col-xs-6">
			                                                <label for="" class="mt-3">Nombre del Form</label>
			                                                <input type="text" class="form-control" name="id_formulario" value="" id="id_formulario" placeholder="Nombre del Form">
			                                            </div> 
			                                            <div class="col-xs-6">
			                                                <label for="" class="mt-3">Titulo del Form</label>
			                                                <input type="text" class="form-control" name="title" id="title" value="" placeholder="Titulo del Form">
			                                            </div>
			                                            <div class="col-xs-6">
			                                                <label for="" class="mt-3">Submit del Form</label>
			                                                <input type="text" class="form-control" name="submit" id="submit" value="Crear/Modificar" placeholder="Submit del Form">
			                                            </div>
			                                            <div class="col-xs-6">
			                                                <label for="" class="mt-3">Warning del Form</label>
			                                                <input type="text" class="form-control" name="warning" id="warning" value="Salir" placeholder="Warning del Form">
			                                            </div>
			                                            <div class="clearfix hr"></div>
			                                        </form>
			                                        <hr>
			                                        <legend class="mt40">A&ntilde;adir Campos Formulario</legend>
			                                        <form action="#" id="sumar_campo_ajax_form" method="post" accept-charset="utf-8"> 
			                                            <input type="hidden" name="json_campos_formulario" value="1" verify="0">

			                                            <label class="mt-3">Type Del Campo:</label>
			                                                <select name="type_campo" verify="0" class="form-control">';
			                                                <?php 
			                                                    $DB->query("select id,nombre from formulario_campos");

			                                                    while($row=$DB->fetch()){
			                                                        echo '<option value="'.$row['id'].'">'.$row['nombre'].'</option>';
			                                                    }
			                                                ?>
			                                                </select>
			                                        
			                                            <label class="mt-3">Id y Nombre Del Campo:</label>
			                                                    <input type="text" 
			                                                    class="form-control" 
			                                                    name="id_nombre_campo"
			                                                    id="id_nombre_campo" 
			                                                    placeholder="Id y Nombre Del Campo"
			                                                    value=""
			                                                    verify="0">
			                                        
			                                            <label class="mt-3">Place Y Label Del Campo:</label>
			                                                    <input type="text" 
			                                                    class="form-control" 
			                                                    name="place_label_campo"
			                                                    id="place_label_campo" 
			                                                    placeholder="Place Y Label Del Campo"
			                                                    value=""
			                                                    verify="0">

			                                       
			                                            <label class="mt-3">Verify Del Campo:</label>
			                                           
			                                                    <select name="verify_campo" verify="0" class="form-control">
			                                                    <option value="0">Off</option>
			                                                    <option value="1">On</option>
			                                                    </select>
			                                                

			                                                
			                                            <div name="campos_select" style="display:none">
			                                                    
			                                                    <label class="mt-3">Extra del Select:</label>
			                                                    <br>
			                                                    <select name="extra_select" verify="0" class="form-control">
			                                                    <option value="0">Off</option>
			                                                    <option value="1">On</option>
			                                                    </select>

			                                                    <br>
			                                                    <label class="mt-3">Val del Extra:</label>
			                                                    <input type="text" 
			                                                    class="form-control" 
			                                                    name="val_extra"
			                                                    id="val_extra" 
			                                                    placeholder="Val del Extra"
			                                                    value="0"
			                                                    verify="0">
			                                                    <br>

			                                                    <label class="mt-3">Text del Extra:</label>
			                                                    <input type="text" 
			                                                    class="form-control" 
			                                                    name="text_extra"
			                                                    id="text_extra" 
			                                                    placeholder="Text del Extra"
			                                                    value="Ninguna"
			                                                    verify="0">
			                                                    <br>

			                                                    <label class="mt-3">Tabla Del Select:</label>
			                                                    <input type="text" 
			                                                    class="form-control" 
			                                                    name="tabla_select"
			                                                    id="tabla_select" 
			                                                    placeholder="Tabla Del Select"
			                                                    value=""
			                                                    verify="0">
			                                                    <br>
			                                                    <label class="mt-3">Val Del Select:</label>
			                                                    <input type="text" 
			                                                    class="form-control" 
			                                                    name="val_select"
			                                                    id="val_select" 
			                                                    placeholder="Val Del Select"
			                                                    value="id"
			                                                    verify="0">
			                                                    <br>

			                                                    <label class="mt-3">Text Del Select:</label>
			                                                    <input type="text" 
			                                                    class="form-control" 
			                                                    name="text_select"
			                                                    id="text_select" 
			                                                    placeholder="Text Del Select"
			                                                    value="nombre"
			                                                    verify="0">
			                                                    <br>
			                                                    <label class="mt-3">Where Del Select:</label>
			                                                    <input type="text" 
			                                                    class="form-control" 
			                                                    name="where_select"
			                                                    id="where_select" 
			                                                    placeholder="Where Del Select"
			                                                    value="id>0"
			                                                    verify="0">           
			                                                    
			                                            </div>

			                                            <div name="campos_select_numero" style="display:none">
			                                                
			                                                    <label class="mt-3">Comienzo del Select:</label>
			                                                    <input type="text" 
			                                                    class="form-control" 
			                                                    name="comienzo_select"
			                                                    id="comienzo_select" 
			                                                    placeholder="Comienzo del Select"
			                                                    value=""
			                                                    verify="0">
			                                                    <br>
			                                                    <label class="mt-3">Fin del Select:</label>
			                                                    <input type="text" 
			                                                    class="form-control" 
			                                                    name="fin_select"
			                                                    id="fin_select" 
			                                                    placeholder="Fin del Select"
			                                                    value=""
			                                                    verify="0">
			                                                    
			                                            </div>

			                                            <div name="campos_textArea" style="display:none">
			                                                
			                                                    <label class="mt-3">Rows Del TextArea:</label>
			                                                    <input type="text" 
			                                                    class="form-control" 
			                                                    name="rows_textArea"
			                                                    id="rows_textArea" 
			                                                    placeholder="Rows Del TextArea"
			                                                    value=""
			                                                    verify="0">
			                                                    
			                                                    <label class="mt-3">Cols Del TextArea:</label>
			                                                    <input type="text" 
			                                                    class="form-control" 
			                                                    name="cols_textArea"
			                                                    id="cols_textArea" 
			                                                    placeholder="Cols Del TextArea"
			                                                    value=""
			                                                    verify="0">
			                                                    
			                                            </div>
			                                            <div name="camposxml_textArea" >
			                                                
			                                                <label class="mt-3">Campos:</label>
			                                                <div class="col-xs-12" id="div_campos_json"></div>
			                                                    
			                                            </div>
			                                            <input  type="button" 
			                                            class="btn btn-success callAjax mt-3 pull-right" 
			                                            value="A&ntilde;adir Campo"
			                                            verify="0">
			                                            <input type="hidden" name="sumar_campo_ajax" value="1" verify="0">

			                                        </form>
			                                 
			                                    </div>

									</div> <!-- <div class="row"> --> 
								</div> <!-- <div class="card-body"> -->
								<div class="card-footer">
								    <button type="button" class="btn btn-warning pull-right" id="crear_form_button">
                                	<b>Crear Formulario</b>
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

		 $('select[name="type_campo"]').change(function(){

                    var textArea = $('div[name="campos_textArea"]');
                    var select = $('div[name="campos_select"]');
                    var selectN = $('div[name="campos_select_numero"]');
                   
                   if($(this).val()==5){

                        if(textArea.is(":visible")){
                        textArea.css("display","none");
                        }
                        if(selectN.is(":visible")){
                          selectN.css("display","none");  
                        }
                        select.css("display","block");

                    }else if($(this).val()==6){

                        if(select.is(":visible")){
                        select.css("display","none");
                        }
                        if(selectN.is(":visible")){
                          selectN.css("display","none");  
                        }
                        textArea.css("display","block");
                     
                    }else if($(this).val()==7){

                        if(select.is(":visible")){
                        select.css("display","none");
                        }
                        if(textArea.is(":visible")){
                          textArea.css("display","none");  
                        }
                        selectN.css("display","block");
                     
                    }else{

                        if(select.is(":visible")){
                        select.css("display","none");
                        }else if(textArea.is(":visible")){
                        textArea.css("display","none");
                        }else if(selectN.is(":visible")){
                        selectN.css("display","none");
                        }
                        
                    }
               // eof     $('select[name="type_campo"]').change(function(){      
                });

                $('#crear_form_button').click(function() {

                    var id_formulario = $('#id_formulario').val();
                    var title = $('#title').val();
                    var submit = $('#submit').val();
                    var warning = $('#warning').val();
                    var campo_form = new Object();
                    var b=0;

                    $('[name="campo_sum"]').each(function(e){

                        campo_form[b]= new Object();
                        campo_form[b]['types']= $(this).attr('types');
                        campo_form[b]['names']= $(this).attr('names');
                        campo_form[b]['verifys']= $(this).attr('verifys');
                        campo_form[b]['val']= $(this).attr('val');
                        campo_form[b]['placeholders']= $(this).attr('placeholders');
                        campo_form[b]['boleano']= $(this).attr('boleano');
                        campo_form[b]['mes']= $(this).attr('mes');
                        campo_form[b]['numeros']= $(this).attr('numeros');
                        campo_form[b]['comienzo']= $(this).attr('comienzo');
                        campo_form[b]['extra']= $(this).attr('extra');
                        campo_form[b]['extra_val']= $(this).attr('extra_val');
                        campo_form[b]['extra_text']= $(this).attr('extra_text');
                        campo_form[b]['tabla']= $(this).attr('tabla');
                        campo_form[b]['select_val']= $(this).attr('select_val');
                        campo_form[b]['select_text']= $(this).attr('select_text');
                        campo_form[b]['where']= $(this).attr('where');
                        campo_form[b]['rows']= $(this).attr('rows');
                        campo_form[b]['cols']= $(this).attr('cols');
                        
                        b++;
                    });

                    if(b == 0 || warning == "" || id_formulario == "" || title == "" || submit == ""){

                        noty({text: 'Faltan Campos Por Rellenar', layout: 'topRight', type: 'warning'});
                        setTimeout('$.noty.closeAll()',3000);

                    }else{

                            $.post('functions/ajax.php',{

                                    tabla_insertar: "formularios",
                                    accion : "i",
                                    formularios : 1,
                                    formulario_json : 1,
                                    warning : warning,
                                    id_formulario : id_formulario,
                                    title : title,
                                    submit : submit,
                                    campos_form : campo_form         
                                }, 
                                function(data) {
                                $("#respuesta_ajax_js").html(data);
                            });

                    }

                }); // $('#crear_form_button').click(function() {
			
	</script>
</body>
</html>