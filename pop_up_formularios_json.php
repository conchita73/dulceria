<?php include 'inc/inc_start.php';  
$title_heading="Modificar Formulario";



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
						<img src="img/logo_admin_2.png" alt="navbar brand" class="navbar-brand">
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
										<h4 class="card-title">Modificar Formularios</h4>	
									</div>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col mb-3 mt-3">
											<ul class="nav nav-pills nav-justified mb-3" id="myTab" role="tablist">
											  <li class="nav-item">
											    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Cabeceras</a>
											  </li>
											  <li class="nav-item">
											    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Campos</a>
											  </li>
											  <li class="nav-item">
											    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">A&ntilde;adir</a>
											  </li>
											</ul>
											<div class="tab-content" id="myTabContent">
											  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
												<form action="#" method="post" id="editar_formularios" accept-charset="utf-8">
											        <input type="hidden" id="tabla_editar" name="tabla_editar" value="formulario_modal">
											        <input type="hidden" id="id_editar"  name="id_editar"  value="<?php echo $_GET['id']; ?>">
											        <input type="hidden" id="accion"  name="accion"  value="u">
											        <input type="hidden"  name="fomulario_json"  value="1" verify="0">
											        <input type="hidden"  name="formularios"  value="1" verify="0">

												<?php 
													$json=$DB->select('nombre,json','formulario_modal_json','id='.$_GET['id']);

													//echo $xml_form[f][0]['xml'];

												    $d = json_decode($json[f][0]['json'],true, 512, JSON_UNESCAPED_UNICODE);



													/// ESTOS CAMPOS SON IGUALES A TODOS LOS FORMS.
												    echo '
												    <label class="mt-3">Nombre Del Form:</label>
												    <input type="text"
												    name="id_formulario"
												    id="id_formulario"
												    class="form-control"
												    value="'.trim($json[f][0]['nombre']).'"
												    verify="1">
												    
												    <label class="mt-3">Titulo Del Form:</label>
												    <input type="text"
												    name="title"
												    id="title"
												    class="form-control"
												    value="'.trim($d['datos']['title']).'"
												    verify="1">

												    <label class="mt-3">Submit Del Form:</label>
												    <input type="text"
												    name="submit"
												    id="submit"
												    class="form-control"
												    value="'.trim($d['datos']['submit']).'"
												    verify="1">

												    <label class="mt-3">Warning Del Form:</label>
												    <input type="text"
												    name="warning"
												    id="warning"
												    class="form-control"
												    value="'.trim($d['datos']['warning']).'"
												    verify="1">';
													?>

												</form>
											</div> <!-- <div class="tab-pane fade show....... -->

											<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
												<form action="" id="campos_form_modify">
													
												    <label class="mt-3"><h3>Campos Del Form:</h3></label>
																
												    <div id="owl-demo" class="owl-carousel owl-theme">         
												    <?php 
												            $n_campos=count($d['bodyForm']['campo']);
												            $d_c=$d['bodyForm']['campo'];

												            for($x=0;$x<$n_campos;$x++){

												                echo '<div class="item">

												                <div class="checkbox pull-right">
												                    <label>

												                        <input type="checkbox"
												                        name="check_campo" 
												                        value="'.$x.'">Borrar Campo

												                        <input type="hidden" 
												                        name="borrar_campo_['.$x.']" 
												                        value="0">

												                        <input type="hidden" 
												                        name="type_campo_['.$x.']" 
												                        value="'.$d_c[$x]['types'].'">

												                    </label>
												                </div>


																<h4>
																<u>Campo Tipo '.$d_c[$x]['types'].'</u>
																</h4>



												    			<label class="mt-3">Id y Nombre Del Campo:</label>
												                <input type="text" 
												                class="form-control" 
												                name="id_nombre_campo_['.$x.']"
												                id="id_nombre_campo_['.$x.']" 
												                placeholder="Id y Nombre Del Campo"
												                value="'.$d_c[$x]['names'].'"
												                verify="1">

												                    
												    			<label class="mt-3">Place Y Label Del Campo:</label>
												                <input type="text" 
												                class="form-control" 
												                name="place_label_campo_['.$x.']"
												                id="place_label_campo_['.$x.']" 
												                placeholder="Place Y Label Del Campo"
												                value="'.trim($d_c[$x]['placeholders']).'"
												                verify="1">

												    
												    		<label class="mt-3">Verify Del Campo:</label>
												    
												            <select name="verify_campo_['.$x.']" verify="0" class="custom-select">';

												            $selected_on='';
												            $selected_off='';

												            if($d_c[$x]['verifys']==1){
												                 $selected_on='selected="selected" ';          
												            }
												            
												            if($d_c[$x]['verifys']==0){
												                $selected_off='selected="selected" ';          
												            }

												            echo '<option '.$selected_on.' value="1">On</option>
												            <option '.$selected_off.' value="0">Off</option>
												           </select>

												            

												               <label class="mt-3">Posici&oacute;n en el form:</label>

												                <select
												                name="posicion_campo_['.$x.']" 
												                position="'.($x+1).'" 
												                class="custom-select">';

												                 for($p=1;$p<=$n_campos;$p++){

												                   echo '<option value="'.$p.'" ';

												                   if($p==($x+1)){
												                    echo 'selected';
												                   } 
												                   echo' >'.$p.'</option>'; 

												                 }


												                echo '</select>
												                
												                ';
												//($x+1)

												            if($d_c[$x]['types']=='textArea'){


												                echo '
												                <label class="mt-3">Rows Del TextArea:</label>
												                <input type="text" 
												                class="form-control" 
												                name="rows_textArea_['.$x.']"
												                id="rows_textArea_['.$x.']" 
												                placeholder="Rows Del TextArea"
												                value="'.$d_c[$x]['rows'].'"
												                verify="1">
												                
												                <label class="mt-3">Cols Del TextArea:</label>
												                <input type="text" 
												                class="form-control" 
												                name="cols_textArea_['.$x.']"
												                id="cols_textArea_['.$x.']" 
												                placeholder="Cols Del TextArea"
												                value="'.$d_c[$x]['cols'].'"
												                verify="1">';

												 			// EOF   if($d_c[$x]['types']=='textArea'){               
												            }else if($d_c[$x]['types']=='select'){

												                    /*

												                    select B 
												                    select M
												                    select C
												                    select N

												                    */

												                    // seccion de tots les boleanos
												                    if($d_c[$x]['boleano']==1){
												                        $select_boleano_on[$x]="selected";
												                        $select_boleano_off[$x]="";

												                    }else if($d_c[$x]['boleano']==0){
												                        $select_boleano_on[$x]="";
												                        $select_boleano_off[$x]="selected";
												                    }

												                    if($d_c[$x]['numeros']==1){
												                        $numeros_select_on[$x]="selected";
												                        $numeros_select_off[$x]="";

												                    }else if($d_c[$x]['numeros']==0){
												                        $numeros_select_on[$x]="";
												                        $numeros_select_off[$x]="selected";
												                    }


												                    if($d_c[$x]['extra']==1){
												                        $extra_select_on[$x]="selected";
												                        $extra_select_off[$x]="";

												                    }else if($d_c[$x]['extra']==0){
												                        $extra_select_on[$x]="";
												                        $extra_select_off[$x]="selected";
												                    }


												                    echo '
												                    <label class="mt-3">Boleano del Select:</label>
												                    
												                    <select name="boleano_select_['.$x.']" verify="0" class="custom-select">
												                    <option value="1" '.$select_boleano_on[$x].'>On</option>
												                    <option value="0" '.$select_boleano_off[$x].'>Off</option>
												                    </select>

												                    

												                    <label class="mt-3">Mes del Select:</label>
												                    <input type="text" 
												                    class="form-control" 
												                    name="mes_select_['.$x.']"
												                    id="mes_select_['.$x.']" 
												                    placeholder="Mes del Select"
												                    value="'.$d_c[$x]['mes'].'"
												                    verify="1">
												                    

												                    <label class="mt-3">Numero del Select:</label>
												                    
												                    <select name="numeros_select_['.$x.']" verify="0" class="custom-select">
												                    <option value="1" '.$numeros_select_on[$x].'>On</option>
												                    <option value="0" '.$numeros_select_off[$x].'>Off</option>
												                    </select>

												                    <label class="mt-3">Comienzo del Select:</label>
												                    <input type="text" 
												                    class="form-control" 
												                    name="comienzo_select_['.$x.']"
												                    id="comienzo_select_['.$x.']" 
												                    placeholder="Comienzo del Select"
												                    value="'.$d_c[$x]['comienzo'].'"
												                    verify="1">
												                    
												                    <label class="mt-3">Fin del Select:</label>
												                    <input type="text" 
												                    class="form-control" 
												                    name="fin_select_['.$x.']"
												                    id="fin_select_['.$x.']" 
												                    placeholder="Fin del Select"
												                    value="'.$d_c[$x]['fin'].'"
												                    verify="1">
												                    
												                    <label class="mt-3">Extra del Select:</label>
												                    
												                    <select name="extra_select_['.$x.']" verify="0" class="custom-select">
												                    <option value="1" '.$extra_select_on[$x].'>On</option>
												                    <option value="0" '.$extra_select_off[$x].'>Off</option>
												                    </select>

												                    <label class="mt-3">Value Del ExtraSelect:</label>
												                    <input type="text" 
												                    class="form-control" 
												                    name="val_extra_['.$x.']"
												                    id="val_extra_['.$x.']" 
												                    placeholder="Value Del ExtraSelect"
												                    value="'.$d_c[$x]['extra_val'].'"
												                    verify="0"> 
												                    
												                    <label class="mt-3">Text Del ExtraSelect:</label>
												                    <input type="text" 
												                    class="form-control" 
												                    name="text_extra_['.$x.']"
												                    id="text_extra_['.$x.']" 
												                    placeholder="Text Del ExtraSelect"
												                    value="'.$d_c[$x]['extra_text'].'"
												                    verify="0">
												                    
												                    <label class="mt-3">Tabla Del Select:</label>
												                    <input type="text" 
												                    class="form-control" 
												                    name="tabla_select_['.$x.']"
												                    id="tabla_select_['.$x.']" 
												                    placeholder="Tabla Del Select"
												                    value="'.$d_c[$x]['tabla'].'"
												                    verify="0">
												                    
												                    <label class="mt-3">Val Del Select:</label>
												                    <input type="text" 
												                    class="form-control" 
												                    name="val_select_['.$x.']"
												                    id="val_select_['.$x.']" 
												                    placeholder="Val Del Select"
												                    value="'.$d_c[$x]['select_val'].'"
												                    verify="0">
												                    

												                    <label class="mt-3">Text Del Select:</label>
												                    <input type="text" 
												                    class="form-control" 
												                    name="text_select_['.$x.']"
												                    id="text_select_['.$x.']" 
												                    placeholder="Text Del Select"
												                    value="'.$d_c[$x]['select_text'].'"
												                    verify="0">
												                    
												                    <label class="mt-3">Where Del Select:</label>
												                    <input type="text" 
												                    class="form-control" 
												                    name="where_select_['.$x.']"
												                    id="where_select_['.$x.']" 
												                    placeholder="Where Del Select"
												                    value="'.$d_c[$x]['where'].'"
												                    verify="0">';
												  
												                }// EOF   if($d_c[$x]['types']=='select'){ 

												    			// eof echo '<div class="item">
												        		echo '</div>';
												            // eof for($x=0;$x<$n_campos;$x++){
												            }
												                
												        ?>
												        <!--EOF  <div id="owl-demo" -->
												    </div>
												</form> 
											</div> <!-- <div class="tab-pane fade" ...... -->
											<div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
												<div class="pull-right mt-3">
										          <input type="checkbox" name="check_suma_campo"> A&ntilde;adir Campos
										        </div>
										        <input type="hidden" name="suma_campo" value="0">
										        <form action="#" id="sumar_campo_ajax_form" method="post" accept-charset="utf-8"> 
            										<input type="hidden" name="json_campos_formulario" value="1" verify="0">

	                                            	<label class="mt-3">Type Del Campo:</label>
	                                                <select name="type_campo" verify="0" class="custom-select">
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
	                                           
	                                                    <select name="verify_campo" verify="0" class="custom-select">
	                                                    <option value="0">Off</option>
	                                                    <option value="1">On</option>
	                                                    </select>
	                                                

	                                                
	                                           		<div name="campos_select" style="display:none">
	                                                    
	                                                    <label class="mt-3">Extra del Select:</label>
	                                                    
	                                                    <select name="extra_select" verify="0" class="custom-select">
	                                                    <option value="0">Off</option>
	                                                    <option value="1">On</option>
	                                                    </select>

	                                                    
	                                                    <label class="mt-3">Val del Extra:</label>
	                                                    <input type="text" 
	                                                    class="form-control" 
	                                                    name="val_extra"
	                                                    id="val_extra" 
	                                                    placeholder="Val del Extra"
	                                                    value="0"
	                                                    verify="0">
	                                                    

	                                                    <label class="mt-3">Text del Extra:</label>
	                                                    <input type="text" 
	                                                    class="form-control" 
	                                                    name="text_extra"
	                                                    id="text_extra" 
	                                                    placeholder="Text del Extra"
	                                                    value="Ninguna"
	                                                    verify="0">
	                                                    

	                                                    <label class="mt-3">Tabla Del Select:</label>
	                                                    <input type="text" 
	                                                    class="form-control" 
	                                                    name="tabla_select"
	                                                    id="tabla_select" 
	                                                    placeholder="Tabla Del Select"
	                                                    value=""
	                                                    verify="0">
	                                                    
	                                                    <label class="mt-3">Val Del Select:</label>
	                                                    <input type="text" 
	                                                    class="form-control" 
	                                                    name="val_select"
	                                                    id="val_select" 
	                                                    placeholder="Val Del Select"
	                                                    value="id"
	                                                    verify="0">
	                                                    

	                                                    <label class="mt-3">Text Del Select:</label>
	                                                    <input type="text" 
	                                                    class="form-control" 
	                                                    name="text_select"
	                                                    id="text_select" 
	                                                    placeholder="Text Del Select"
	                                                    value="nombre"
	                                                    verify="0">
	                                                    
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
												</div> <!-- <div class="tab-pane fade" id="contact" -->
											</div>
										</div>
									</div> <!-- <div class="row"> -->
								</div> <!-- <div class="card-body"> -->
								<div class="card-footer">
								    <button type="button" class="btn btn-warning pull-right" id="modificar_formulario">
                                	<b>Modificar Formulario</b>
                            		</button> 
								</div> <!-- <div class="card-footer"> -->
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
		$(document).ready(function(){

		    // redimension ventana xra eliminar los pixeles,
		    // que se quedan sobrando del owl.carousel

		   // window.resizeTo(600,920);


		    // activacion del toggableTabs (imprescindible el cdn de boostrap,js)
		    $('#myTabs').click(function (e) {
		      e.preventDefault()
		      $(this).tab('show')
		    })

		    $('[id^=id_nombre_campo_').change(function(){
		        var new_val= $(this).val();
		       $(this).attr('value',new_val);
		    });

		      var owl = $("#owl-demo");
		    // DEFINICION DE CARROUSEL.
		      owl.owlCarousel({
		       // navigation : true,
		        // animateOut: 'slideOutDown', estas 2 opciones crean 
		        // animateIn: 'flipInX', efectos mas que chulis fotos.
		        margin:5,
		        nav:true,
		        items:1,
		        center:true,
		       // loop:true,
		        autoHeight : true, 
		        //responsiveClass:true
		        
		    // eof   owl.owlCarousel({
		      });


		    // utilidad de poder mover el carousel con 
		    // las flechas del teclado.
		    $('body').keydown(function(event) {

		    if(event.keyCode==37 || event.keyCode==40){
		        owl.trigger('prev.owl');
		    }

		    if(event.keyCode==39 || event.keyCode==38){
		        owl.trigger('next.owl');
		    }

		    // eof $('.item').keydown(function(event) {
		    });


		    // modificar el value de borrar campo
		    $('[name="check_campo"]').click(function(){

		        var name = $(this).val();
		        var campo = $('[name="borrar_campo_['+name+']"]')
		        var value = campo.val();

		            if(value==0){
		                var new_value = 1;
		            }else if(value==1){
		                var new_value = 0;
		            }

		        campo.val(new_value);

		    //eof $('[name="check_campo"]').click(function(){
		    });

		    $('[name="check_suma_campo"]').click(function(){

		        var campo = $('[name="suma_campo"]')
		        var value = campo.val();

		            if(value==0){
		                var new_value = 1;
		            }else if(value==1){
		                var new_value = 0;
		            }

		        campo.val(new_value);

		    //eof $('[name="check_campo"]').click(function(){
		    });



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


		    // USABLE EN CUALQUIER FORM QUE QUERAMOS USARLO.

		    // lo tengo en inc_javaScript convendría una function
		    // $(".callAjax").click(function(e){


		    //     var verificacion = $(this).attr("verify");
		    //     var serialize = "#"+$(this).next("input").attr("name")+"_form";

		    //   // alert(serialize) ;

		    //    if(verificacion==1){

		    //        var validar_formulario=validarForm(serialize);

		    //         if(validar_formulario==1){

		    //           callAjax("functions/ajax.php",serialize,"#respuesta_ajax_js");
		    //         }

		    //     }else if(verificacion==0){

		    //         callAjax("functions/ajax.php",serialize,"#respuesta_ajax_js");

		    //     }
		    // });
		    // // USABLE EN CUALQUIER FORM QUE QUERAMOS USARLO.


		    // orden de los campos
		    $('[name^="posicion_campo_"').change(function(){

		        // cogemos el val despues del change
		        // y el original.
		        var val_change = $(this).val();//tengo que ir a este elemento.
		        var val_position = $(this).attr('position');

		        // este es elem que tiene la pos que hemos elegido
		        // al change, por lo que se la cambiamos.
		        $('[position="'+val_change+'"]').val(val_position);
		        $('[position="'+val_change+'"]').attr('position',val_position);

		        // añadimos el nuevo position al elem que 
		        // hemos change.
		        $(this).attr('position',val_change);

		    // eof $('[name^="posicion_campo_"').change(function(){
		    });


		    $('#modificar_formulario').click(function(){


		        if($('[name="suma_campo"]').val()==1){

		            var sumar_campos = 1;
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
		        }else{

		            var campo_form = "";
		            var sumar_campos = 0;
		        }

		        var form_editar = $("#editar_formularios").serialize();
		        var form_campos = $("#campos_form_modify").serialize();

		        $.post('functions/ajax.php?'+form_editar+'&'+form_campos,{

		            sumar_campos : sumar_campos,
		            campos_form : campo_form         
		            }, 
		            function(data) {

		                $("#respuesta_ajax_js").html(data);

		            });

		    });


		// eof $(document).ready(function(){
		});             
	</script>
</body>
</html>