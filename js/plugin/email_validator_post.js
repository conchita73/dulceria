    $.fn.extend({

        email_validator_post : function(options){

       	// estas son los parametros con valores por defecto
    	defaults = {    		
    		email: $(this).val(),
    		url : 'functions/ajax.php',
    		serialice: '#',
    		respuesta: '#respuesta_ajax_js'
    	//defaults = {
    	}

    	 		// con esto añadimos los defaults o parametros pasados
 		// al objeto options.
    	var options = $.extend({}, defaults, options); 

            return this.each(function(){

            	//var email = $(this).val();
    			var emailRegex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

    			if (emailRegex.test(options.email)){

    				// esta llamada 
    				// $(this).call_ajax({
			    	// 	url: options.url,
			    	// 	serialice: options.serialice,
			    	// 	respuesta: options.respuesta    					
    				// });

                    if(options.url=='p'){
                        options.url='/new_app/functions/ajax.php';
                    }else if(options.url=='a'){
                        options.url='/functions/ajax.php';
                    }

    				 $.post(options.url,$(options.serialice).serialize(),
		             function(data) {
		        		$(options.respuesta).html(data); 
		        	});
    				
    			}else{
    				
    				alert('El email tiene un formato incorrecto');
    			}	

            // return this.each(function(){
            });
        // email_validator_post : function(options){
        }
    // $.fn.extend({    
    });
    

