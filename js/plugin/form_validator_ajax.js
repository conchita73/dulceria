(function($){

    $.fn.extend({
      
       form_validator_ajax : function(options){

       	// COGEMOS EL ID PARA ATACAR A ESE FORM
       	// VALIDAMOS QUE TODOS LOS CAOMPOS ESTE RELLENOS
       	// Y SU FORMATO CORRECTO PARA TEL Y EMAIL.
       	// call_ajax serializando el form validado.

       	///// ############ IMPORTANTE EVALUAR EL RESULTADO ASI ://////

       		//		$('#form_acceso_zona_privada').form_validator();

			//			if($('#form_acceso_zona_privada').val()==true){
			//				ACCION QUE NOS DE LA XXXXXXXX
			//		}

			    	// estas son los parametros con valores por defecto
    	defaults = {    	

    		url: '/new_app/functions/ajax.php',
        respuesta_ajax:'#respuesta_ajax_js',
        aviso:'alert'
    	
    	} //defaults = {
 		
 		// con esto añadimos los defaults o parametros pasados
 		// al objeto options.
    	var options = $.extend({}, defaults, options); 

            return this.each(function(){

            	var id = $(this).attr('id');
            	var form_validado = 1;
            	
            	$('#'+id+' input,textarea').each(function(indice, elemento) {

                // este atributo es by me
                if($(elemento).attr('verify')==1){

                    if($(elemento).val()==""){
                      
                      var txt = 'No puede haber campos vacios';
                      if(options.aviso=='alert'){

                        alert(txt);

                      }else if(options.aviso=='noty'){

                        noty({
                            text: txt, 
                            layout: 'topRight', 
                            type: 'warning'
                        });
                        setTimeout('$.noty.closeAll()',3000);


                      }else if(options.aviso=='notify'){
                            
                          var placementFrom = 'top';
                          var placementAlign = 'right';
                          var state = 'danger';
                          var content = {};

                          content.message = txt;
                          content.icon = 'fas fa-exclamation';

                          $.notify(content,{
                              type: state,
                              placement: {
                                  from: placementFrom,
                                  align: placementAlign
                              },
                              time: 1000,
                              delay: 3,
                          });

                      }



                      $(elemento).focus();
                      $(elemento).css({ "background-color" : "#EA899A" });
                      // tengo
                      form_validado=0;                  
                      return false;

                    }else if($(elemento).attr("type")=='email'){

                      var email = $(this).val();
                      var emailRegex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

                        if (!emailRegex.test(email)){

                            var txt ='El e-mail intorducido tiene un formato incorrecto';

                            if(options.aviso=='alert'){

                              alert(txt);


                            }else if(options.aviso=='noty'){

                              noty({
                                  text: txt, 
                                  layout: 'topRight', 
                                  type: 'warning'
                              });
                              setTimeout('$.noty.closeAll()',3000);


                            }else if(options.aviso=='notify'){

                                var placementFrom = 'top';
                                var placementAlign = 'right';
                                var state = 'danger';
                                var content = {};

                                content.message = txt;
                                content.icon = 'fas fa-exclamation';

                                $.notify(content,{
                                    type: state,
                                    placement: {
                                        from: placementFrom,
                                        align: placementAlign
                                    },
                                    time: 1000,
                                    delay: 3,
                                });

                            }

                            form_validado=0;
                            return false;

                        }
      
                    }else if($(elemento).attr("type")=='tel'){

                      var phone = $(this).val();
                      if(phone.length!=9 || isNaN(phone)==true){

                            var txt = 'El teléfono sólo tiene que contener 9 números, sin guiones, espacios ni letras.';
                            if(options.aviso=='alert'){

                              alert(txt);

                            }else if(options.aviso=='noty'){

                              noty({
                                  text: txt, 
                                  layout: 'topRight', 
                                  type: 'warning'
                              });
                              setTimeout('$.noty.closeAll()',3000);


                            }else if(options.aviso=='notify'){

                                var placementFrom = 'top';
                                var placementAlign = 'right';
                                var state = 'danger';
                                var content = {};

                                content.message = txt;
                                content.icon = 'fas fa-exclamation';

                                $.notify(content,{
                                    type: state,
                                    placement: {
                                        from: placementFrom,
                                        align: placementAlign
                                    },
                                    time: 1000,
                                    delay: 3,
                                });

                            } 

                            form_validado=0;                        
                            return false;

                      }
                      
                    }else if($(elemento).attr("type")=='checkbox' && $(elemento).attr("name")=='acepto_condiciones'){

                        if(!$(this).is(':checked')){
                          
                          var txt = 'Debes aceptar las condiciones para poder seguir adelante'
                          if(options.aviso=='alert'){

                            alert(txt);

                          
                          }else if(options.aviso=='noty'){

                            noty({
                                text: txt, 
                                layout: 'topRight', 
                                type: 'warning'
                            });
                            setTimeout('$.noty.closeAll()',3000);


                          }else if(options.aviso=='notify'){

                              var placementFrom = 'top';
                              var placementAlign = 'right';
                              var state = 'danger';
                              var content = {};

                              content.message = txt;
                              content.icon = 'fas fa-exclamation';

                              $.notify(content,{
                                  type: state,
                                  placement: {
                                      from: placementFrom,
                                      align: placementAlign
                                  },
                                  time: 1000,
                                  delay: 3,
                              });

                          }

                          form_validado=0;                        
                          return false;
                        }
                    }else if($(elemento).attr("name")=='nombre' || $(elemento).attr("name")=='name'){

                      var nombre = $(this).val();
                      var arr = nombre.split(" ");

                      if(arr.length<2){
                        
                        var txt = 'Tienes que intoducir por lo menos un apellido para poder seguir adelante';
                        if(options.aviso=='alert'){

                          alert(txt);

                        }else if(options.aviso=='noty'){

                          noty({
                              text: txt, 
                              layout: 'topRight', 
                              type: 'warning'
                          });
                          setTimeout('$.noty.closeAll()',3000);


                        }else if(options.aviso=='notify'){

                            var placementFrom = 'top';
                            var placementAlign = 'right';
                            var state = 'danger';
                            var content = {};

                            content.message = txt;
                            content.icon = 'fas fa-exclamation';

                            $.notify(content,{
                                type: state,
                                placement: {
                                    from: placementFrom,
                                    align: placementAlign
                                },
                                time: 1000,
                                delay: 3,
                            });

                        }

                        $(elemento).css({ "background-color" : "#EA899A" });
                        form_validado=0;                        
                        return false;
                      }

                    }
                  // if($(elemento).attr('verify')==1 || $(elemento).attr('verify')!=0){
                  }

                // $('#'+id+' input').each(function(indice, elemento) {
              });

            	// SI EL FORM ES VALIDO, 
            	// SU AJAX CORRESPONDIENTE Y VOLANDO.
            	if(form_validado==1){

                    // envio el uso a publica o app

                    if(options.url=='p'){
                        options.url='/new_app/functions/ajax.php';
                    }else if(options.url=='a'){
                        options.url='functions/ajax.php';
                    }

            		$(this).call_ajax({
      						url: options.url,
      						serialice: $(this),
                  respuesta: options.respuesta_ajax
      					});

            	}else{

                return false;
              }
            	//alert('esta validado ' + form_validado);

            // return this.each(function(){
            });
        // paginate : function(){
        }
    // $.this.fn.$.this.extend({    
    });
    
// (function($){
})(jQuery) 