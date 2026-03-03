(function($){

    $.fn.extend({
      
       form_validator_submit : function(options){

       	// COGEMOS EL ID PARA ATACAR A ESE FORM
       	// VALIDAMOS QUE TODOS LOS CAOMPOS ESTE RELLENOS
       	// Y SU FORMATO CORRECTO PARA TEL Y EMAIL
        //#### AÑADIDOS LA COMPROBACION  DE CHEKS DE CONDICIONES Y QUE LOS NOMBRES NO PUEDAN IR SIN APELLIDOS
       	// call_ajax serializando el form validado.

       	///// ############ IMPORTANTE EVALUAR EL RESULTADO ASI ://////

       		//		$('#form_acceso_zona_privada').form_validator();

			//			if($('#form_acceso_zona_privada').val()==true){
			//				ACCION QUE NOS DE LA XXXXXXXX
			//		}

			    	// estas son los parametros con valores por defecto
    	defaults = {    		
    		url: ''
    	//defaults = {
    	}
 		
 		// con esto añadimos los defaults o parametros pasados
 		// al objeto options.
    	var options = $.extend({}, defaults, options); 

            return this.each(function(){

            	var id = $(this).attr('id');
            	var form_validado = 1;
            	
            	$('#'+id+' input').each(function(indice, elemento) {

            		// este atributo es by me
            		if($(elemento).attr('verify')==1 || $(elemento).attr('verify')!=0){

            		    if($(elemento).val()==""){

			                alert("No puede haber campos vacios");
			                $(elemento).focus();
			                $(elemento).css({ "background-color" : "#fa6c6c" });
			                // tengo
			               	form_validado=0;	                
			                return false;

              			}else if($(elemento).attr("type")=='email'){

                			var email = $(this).val();
                			var emailRegex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

                    		if (!emailRegex.test(email)){

	                      		alert("El e-mail intorducido tiene un formato incorrecto");
                            $(elemento).css({ "background-color" : "#fa6c6c" });
	                      		form_validado=0;
	                      		return false;

                    		}
      
              			}else if($(elemento).attr("type")=='tel'){

                			var phone = $(this).val();
	                		if(phone.length!=9 || isNaN(phone)==true){

		                      	alert("El teléfono sólo tiene que contener 9 números, sin guiones, espacios ni letras.");
                            $(elemento).css({ "background-color" : "#fa6c6c" });
		                      	form_validado=0;	                      
		                      	return false;

                			}
                			
              			}else if($(elemento).attr("type")=='checkbox' && $(elemento).attr("name")=='acepto_condiciones'){

                        if(!$(this).is(':checked')){
                          alert('Debes aceptar las condiciones para poder seguir adelante');
                          form_validado=0;                        
                          return false;
                        }
                    }else if($(elemento).attr("name")=='nombre' || $(elemento).attr("name")=='name'){

                      var nombre = $(this).val();
                      var arr = nombre.split(" ");

                      if(arr.length<2){
                        alert('Tienes que intoducir por lo menos un apellido para poder seguir adelante');
                        $(elemento).css({ "background-color" : "#fa6c6c" });
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

                $('#'+id).submit();

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