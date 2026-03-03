$.fn.extend({  

	// nombremos plugin
    call_ajax: function(options) {

    	// estas son los parametros con valores por defecto
    	defaults = {    		
    		url: '/new_app/functions/ajax.php',
    		serialice: '#',
    		respuesta: '#respuesta_ajax_js'
    	//defaults = {
    	}
 		
 		// con esto añadimos los defaults o parametros pasados
 		// al objeto options.
    	var options = $.extend({}, defaults, options); 
 
        return this.each(function() { 

                    // envio el uso a publica o app
                    if(options.url=='p'){
                        options.url='/new_app/functions/ajax.php';
                    }else if(options.url=='a'){
                        options.url='functions/ajax.php';
                    }

        			// para poder usar los parametros pasados
        			// tenemos que llamar asi:  options.variable_nombrada
    				$.post(options.url,$(options.serialice).serialize(),
		            function(data) {
		       		$(options.respuesta).html(data);
		       	});

        // return this.each(function() { 
        });

    //call_ajax: function(options) {  
    }
// $.fn.extend({   
});
