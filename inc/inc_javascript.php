	<div id="respuesta_ajax_js"></div>

    <!--   Core JS Files   -->
	<script src="js/core/jquery.3.2.1.min.js"></script>
	<script src="js/core/popper.min.js"></script>
	<script src="js/core/bootstrap.min.js"></script>

	<!-- jQuery UI -->
	<script src="js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js?a=<?php echo time(); ?>"></script>
	<script src="js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

	<!-- jQuery Scrollbar -->
	<script src="js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

	<!-- Moment JS -->
	<script src="js/plugin/moment/moment.min.js?aa=<?php echo time() ?>"></script>

	<!-- Chart JS -->
	<script src="js/plugin/chart.js/chart.min.js"></script>

	<!-- jQuery Sparkline  -->
	<script src="js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

	<!-- Chart Circle  -->
	<script src="js/plugin/chart-circle/circles.min.js"></script>

	<!-- Datatables -->
	<script src="js/plugin/datatables/datatables.min.js?e=1"></script>

	<!-- Bootstrap Notify -->
	<script src="js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

	<!-- Bootstrap Toggle -->
	<script src="js/plugin/bootstrap-toggle/bootstrap-toggle.min.js"></script>

	<!-- jQuery Vector Maps -->
	<script src="js/plugin/jqvmap/jquery.vmap.min.js"></script>
	<script src="js/plugin/jqvmap/maps/jquery.vmap.world.js"></script>

	<!-- Google Maps Plugin -->
	<script src="js/plugin/gmaps/gmaps.js"></script>

	<!-- Dropzone -->
	<!-- <script src="js/plugin/dropzone/dropzone.min.js"></script> -->

    
	<!-- Fullcalendar -->
	<script src="js/plugin/fullcalendar/fullcalendar.min.js?a=<?php echo time() ?>"></script>
    <!-- <script src="js/plugin/fullcalendar/es.js" type="text/javascript"></script> -->
	<!-- DateTimePicker -->
	<script src="js/plugin/datepicker/bootstrap-datetimepicker.min.js"></script>
    

	<!-- Bootstrap Tagsinput -->
	<script src="js/plugin/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>

	<!-- Bootstrap Wizard -->
	<script src="js/plugin/bootstrap-wizard/bootstrapwizard.js"></script>

	<!-- jQuery Validation -->
	<script src="js/plugin/jquery.validate/jquery.validate.min.js"></script>

	<!-- Summernote -->
	<script src="js/plugin/summernote/summernote-bs4.min.js"></script>

	<!-- Select2 -->
	<script src="js/plugin/select2/select2.full.min.js"></script>
    <script src="dist/js/bootstrap-select.js"></script>


	<!-- Sweet Alert -->
	<script src="js/plugin/sweetalert/sweetalert.min.js"></script>

	<!-- Owl Carousel -->
	<script src="js/plugin/owl-carousel/owl.carousel.min.js"></script>

	<!-- Magnific Popup -->
	<script src="js/plugin/jquery.magnific-popup/jquery.magnific-popup.min.js"></script>

	<!-- Atlantis JS -->
	<script src="js/atlantis.min.js?<?php echo time(); ?>"></script>

	<!-- MIS JS PLUGINS PROPIOS-->
    <script type="text/javascript" src="js/plugin/call_ajax.js"></script>
    <script type="text/javascript" src="js/plugin/form_validator_ajax.js"></script>
    <script src="js/plugin/dropzone/dropzone.js?a=<?php echo time(); ?>"></script>
    <?php 

        if(!isset($no_agenda) && isset($_SESSION['id_usuario']) && $_SESSION['id_usuario'] == 1){

           include 'inc/inc_agenda.php'; 

        }else{

             script('$("#agenda_header").addClass("d-none");');
        }
    ?>
    <script>
    	$(document).ready(function(){

    	/// #### ESENCIALES 
    	// log_out_admin_button
        // desconectar_modal
    	// delete
    	// editar
    	// enviar_email
    	// callAjax
    	// edita_input 
    	// is_number
    	// datatable
        // editar_pop_up
        // .datepicker 
        // .timepicker
        // select_boleano
        // btn_filtro




    		$('#log_out_admin_button').click(function() {
                             
                $("#form_log_out_admin").form_validator_ajax();
                  
            });

            $(document).on('click',"button[name='delete']", function(e){
                
                e.preventDefault();
                var id = $(this).attr("id");
                var tabla_eliminar = $(this).attr("tabla_eliminar");
                $("[name='id_eliminar']").val(id);
                $("#tabla_eliminar").val(tabla_eliminar);


            });

            $('input[type="text"]').keydown(function(){
              $(this).css({ "background-color" : "white" });
            });

            $('input[type="text"]').focus(function(){
              $(this).css({ "background-color" : "white" });
            });

            $(document).on('click',"button[name='editar']", function(e){
               
                e.preventDefault();
                var id = $(this).attr("id");
                $("[name='id_editar']").val(id);
                var serialize = $(this).attr("data-target")+"_form";
                $(serialize).call_ajax({
                  url:"functions/ajax.php?tabla_cargar_datos_registros=1",
                  serialice:serialize
                });

            });

            $(document).on('click',"[name='enviar_email']", function(e){

                e.preventDefault();
                var id = $(this).attr("id");
                var tabla = $(this).attr("tabla");
                if(tabla == 'facturas'){

                }

                $.post("functions/ajax.php", 
                 	{
                    	id: id,
                    	enviar_email : 1,
                    	tabla_enviar : tabla 
                  	}, 
                function(data) {
                    $("#respuesta_ajax_js").html(data);
                });   
            });


            $(".callAjax").click(function(e){

                var serialize = "#"+$(this).next("input").attr("name")+"_form";
                $(serialize).form_validator_ajax();
      
            }); //eof $(".callAjax").click(function(e){

            $('[name^="importe"], [name^="pvp"]').addClass('is_number');

            $(document).on('change' , '.is_number' , function(){

                var number = $(this).val();
                var new_number = number.replace(',','.');
                if(isNaN(new_number) == true){

                  <?php noty_js('Este campos solo puede contener números','danger'); ?>
                  $(this).focus();

                }
                $(this).val(new_number);

            }); 


                 
            // input iniversal para poder modificar campos de tablas
            $(document).on('change' , '.edita_input',function(event) {
                  
                var id_editar = $(this).attr('id_editar');
                var tabla_editar = $(this).attr('tabla_editar');
                var campo_editar = $(this).attr('name');
                var value = $(this).val();


                $.post('/dulceria/functions/ajax.php',
                	{
                      	accion: 'u',
                      	id_editar : id_editar,
                      	tabla_editar : tabla_editar,
                      	campo_personalizado_editar : campo_editar,
                      	value_personalizado_editar : value,
                      	noty : 1,            
                    }, 
                function(data) {

                    $('#respuesta_ajax_js').html(data);

                });
            }); // $('.edita_input').change(function(event) {

            <?php if(!isset($no_data_table)){ ?>

                $('.datatable').DataTable({
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
                
            <?php } ?>

            $(document).on('click' , '[name="editar_pop_up"]', function(){

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

            $(document).on('click','[name="ver_facturas"]',function(){

                var id = $(this).attr('id_factura');
                window.open('functions/descargar_documentacion.php?id_factura='+id,'_blank');
                return false;

            });

            $(document).on('click','[name="quitar_pago"]',function(event) {
            
                var id_factura = $(this).attr('id');
                $.post('functions/ajax.php' ,
                    {
                        quitar_pago : 1,
                        id_factura : id_factura
                    }, 
                    function(data){
                        $('#respuesta_ajax_js').html(data);
                    }
                );
            });   

            $('#desconectar_modal').click(function(e) {
                swal({
                    title: '¿Seguro que quieres Salir?',
                    text: "Nos da mucha pena que te vayas!",
                    type: 'warning',
                    buttons:{
                        confirm: {
                            text : 'Si, quiero salir!',
                            className : 'btn btn-success'
                        },
                        cancel: {
                            visible: true,
                            text : 'No, me quedo!',
                            className: 'btn btn-danger'
                        }
                    }
                }).then((Delete) => {

                    if (Delete) {
                        
                        $.post('/dulceria/functions/ajax.php',
                            {
                                log_out_admin: 1,
                                              
                        }, 
                        function(data) {

                        $('#respuesta_ajax_js').html(data);

                        });
                        
                    } else {
                        swal.close();
                    }

                });
            });
            $('select').addClass("form-control");
            // $('select').selectpicker();

            $(document).on('change','[name$="edit"]',function(){

                var id_form = $(this).parents('form').attr('id');

                $('.is_number').each(function(){

                    var number = $(this).val();
                    var new_number = number.replace(',','.');
                    if(isNaN(new_number) == true){

                      <?php noty_js('Este campos solo puede contener números','danger'); ?>
                      $(this).focus();

                    }
                    $(this).val(new_number);

                });

                $('#'+id_form).form_validator_ajax();

            });

            $('.datepicker, [id^="fecha"], [name^="fecha"]').datetimepicker({
                format: 'DD/MM/YYYY',
                widgetPositioning: {
                    horizontal: 'right',
                    vertical: 'bottom'
                }
            });

            $('.timepicker, [id^="hora"], [name^="hora"]').datetimepicker({
                format: 'H:mm', 
            });

            $('.select_boleano').html('<option value="0">No</option><option value="1">Si</option>');

            $('.btn_filtro').click(function(e){
                
                var form = $(this).parents('form');
                var id = form.attr('id');
                $('#'+id).form_validator_ajax();

            });

            // CONTROLADOR DE PILLS
            <?php if(isset($_SESSION[$_SERVER['SCRIPT_NAME']])){  ?>

                $('[href="<?php echo $_SESSION[$_SERVER['SCRIPT_NAME']] ?>"]').click();

            <?php } ?>


            $('[data-toggle="pill"], [data-toggle="tab"]').click(function(){
                
                var href = $(this).attr('href');
                $.post('functions/ajax.php' ,
                    {
                        controlador_pills : 1,
                        session : '<?php echo $_SERVER['SCRIPT_NAME'] ?>',
                        href : href
                    }, 
                    function(data){
                        $('#respuesta_ajax_js').html(data);
                    }
                );
            });                               
            // CONTROLADOR DE PILLS   
                                            
                                                
                                            
            //                                 </a>

            <?php  

                // if(isset($_SESSION['id_usuario'])){
                //   #registro de las acciones
                //       $arr_acciones = array(
                //               'click'=>array('a','button','check'),
                //               'change'=>array('select','input[type=\'text\']','textarea')
                //               );

                //       foreach ($arr_acciones as $key => $value) {
                          
                //           foreach ($value as $val) {
                              
                //               echo '$("'.$val.'").'.$key.'(function(){
                                          
                //                           var accion = "'.$key.'";
                //                           var elemento = "'.$val.'";
                //                           $.post("functions/ajax.php" ,
                //                               {
                //                                   accion : accion,
                //                                   elemento : elemento,
                //                                   id_usuario : '.$_SESSION['id_usuario'].',
                //                                   registrar_acciones : 1

                //                               }, 
                //                               function(data){
                //                                   $("#respuesta_ajax_js").html(data);
                //                               }
                //                           );

                //                       });';
                //           }// foreach ($value as $val) {

                //       } // foreach ($arr_acciones as $key => $value) {

                // } // if(isset($_SESSION['id_usuario'])){
            ?>

           
            


    	}); // $(document).ready(function(){
    </script>



    <!-- end -->