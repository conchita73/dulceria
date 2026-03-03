//////////////////////INDICE /////////////////////
//
//
// callAjax -> llamadas Ajax
//
// mailcheck -> valida email con RegExp
//
// validaform -> Si los campos son verify o no existe este aributo, campos vacios, emails correcto, telefonos correcto
// 
// comasporpuntos -> cambia , por . a los numeros
//
//
//
//
//
//
//
// plugins externos:
//
// modernizer compatibilad antiguos navegadores
// lazyLoad xara imagenes
// enquires -> mediaQuerys 
// mobileDetector 
// filestyle xra botones input files


//  function callAjax(url,serialice,respuesta){

// /*importante pasarle las variables de los ID,s sin comillas interiores:

// ejemplo:

//  callAjax("/SOLT/ajax/generacion_codigos.php","#serialize","#respuesta");

// */


// $.ajax({
//              type: "POST",
//              url: url,
//             data: $(serialice).serialize(), // Adjuntar los campos del formulario enviado.
//              success: function(data)
//             {
//                $(respuesta).html(data); // Mostrar la respuestas del script PHP.
//              }
//           });
//       return false; // Evitar ejecutar el submit del formulario.


// // eof callAjax
//   }

   function callAjax(url,serialice,respuesta){

/*importante pasarle las variables de los ID,s sin comillas interiores:

ejemplo:

 callAjax("/SOLT/ajax/generacion_codigos.php","#serialize","#respuesta");

*/

$.post(url,$(serialice).serialize(),
               function(data) {
        $("#respuesta_ajax_js").html(data);
    });
    //return false; // Evitar ejecutar el submit del formulario.


// eof callAjax
  }



function mail_check(email){

    var emailRegex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;


    var mail_checkeado=0;
    if (emailRegex.test(email)){
    mail_checkeado=1;
    }


    return mail_checkeado;
}


// ya tengo plugins.
function validarForm(id_form,form_validado=1){

// le pasamos el ID del form  sin comillas inteiores. validaForm("#Validamos")
// Esta funcion comprueba que los campos de cualquier form no esten vacios,
// TB que el campo llamado email,e-mail,mail tenga un formato correcto
// TB que el campo llamado telefono,movil,phone tenga un formato correcto

////****** LE HE AÑADIDO, EL IF DEL ATRIBUTO HTML verify QUE ME HE INVENTADO PARA ESTA ADMIN.
////****** SI NO EXISTIESE DICHO ATRIBUTO, LO COMPROBARIA SIEMPRE. 
        


    $(id_form+' input').each(function(indice, elemento) {


        if($(elemento).attr('verify')==1 || $(elemento).attr('verify')!=0){

              if($(elemento).val()==""){
                alert("El campo "+$(elemento).attr("name")+" no puede estar vacio.");
               
                form_validado=0;
                return false;

              }else if($(elemento).attr("name")=='email' ||
                $(elemento).attr("name")=='e-mail' ||
                $(elemento).attr("name")=='mail'){

                var email = $(this).val();
                var emailRegex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

                    if (!emailRegex.test(email)){

                      alert("El e-mail intorducido tiene un formato incorrecto");
                      form_validado=0;
                      return false;

                    }
      
              }else if($(elemento).attr("name")=='telefono' || 
                $(elemento).attr("name")=='phone' ||
                $(elemento).attr("name")=='movil'){

                var phone = $(this).val();


                if(phone.length!=9 || isNaN(phone)==true){

                      alert("El teléfono sólo tiene que contener 9 números, sin guiones, espacios ni letras.");
                      form_validado=0;
                      return false;

                }

          // eof if($(elemento).attr('verify')==1){
          }
      // eof $(id_form+' input').each(function(indice, elemento)
      }



             });

            return form_validado;
 // eof validarForm

}



function comasporpuntos(id){

//$('#importe').change(function(){

    var nuevoValor=$('#'+id).val(); 
    var nuevoValorOk=nuevoValor.replace(",","."); 
    $('#'+id).val(nuevoValorOk);
    $('#'+id).html(nuevoValorOk);

//});


}


function open_pop_up(url,width,height,position=""){

/// position vamos definir varias posisiones
/// ahora mismo la vamos a centrar simplemente

left = (screen.width) ? (screen.width-width)/2 : 0;
top = (screen.height) ? (screen.height-height)/2 : 0;

      window.open(url,'_blank',"width="+width+",height="+height+",top="+top+",left="+left+",scrollbars=1");
      return false;



}


//////////// HACER FUNCOINES DE ESTAS 2 FOR USE

  //  window.moveTo(0,0); esta function mueve la ventana de lugar.
  // se puede usar para hacer efectos pulsando botones.


// funcion de poder mover lo que sea con el raton. 

// $("#owl-demo").bind('mousewheel DOMMouseScroll', function(event){
//     if (event.originalEvent.wheelDelta > 0 || event.originalEvent.detail < 0) {
//         owl.trigger('prev.owl');
//     }
//     else {

//         owl.trigger('next.owl');
//     }
// });


//////// ESTE ES EL ORIGINAL DE JQUERY , SIN PLUGINS 
////////NESTA MAS QUE MEJOR QUE EL DE ARRIBA QUE TENEMOS QUE 
// PONER PLUGINS EXTRAS
        // $('body').mousewheel(function(event, delta) {
        //    // this.scrollLeft -= (delta * 30);

        //     event.preventDefault();

        //    if(delta>0.80 && delta<1.00){
        //     console.log(delta);
        //         owl.trigger('prev.owl');
        //         return false;
        // }

        //    if(delta<-0.80 && delta>-1.00){
        //    console.log(delta);
        //         owl.trigger('next.owl');
        //         return false;
        // }

            
        // });


//////////// HACER FUNCOINES DE ESTAS 2 FOR USE



 function recoge_values(id_recorrer,tipo_elemento,atributo){

$("#"+id_recorrer+ " " + tipo_elemento).each(function(indice, elemento) {
 
  alert("El campo "+$(elemento).attr(atributo));
});

// mejora importante:

// hacer una function con esto:

//each tal y como esta en la comprobacion 
// de los formularios , eliminanado que 
// no coja los atruutos (name, type , etc..)

// y hacer en each lo encerrado en llaves 
// comentadas

// {

  

//recoge_values(id,"span","name");

  //var reg = /^filtro_/;

// $("#"+id+ " span").each(function(indice, elemento) {

// //var name = $(elemento).attr("name");

//  alert("El campo "+$(elemento).attr("name"));

// // if(reg.test(name)){
// //   alert(" el name " + name + " si es filtro");

// // }else{
// //   alert(" el name " + name + " no es filtro");
// // }

// });



 }



 ///////////////ZONA PLUGINS EXTERNOS //////////////

 /*
 * QUIERO HACER UNA REORDENACION DE ESTO. 
 ELIMINANDO TODAS LAS FUNCIONES NATIVAS Y EXTENDIENDO TODO A JQUERY.
 */

 /* Modernizr 2.8.3 (Custom Build) | MIT & BSD
 * Build: http://modernizr.com/download/#-borderradius-boxshadow-rgba-textshadow-cssgradients-geolocation-shiv-mq-cssclasses-teststyles-testprop-testallprops-prefixes-domprefixes-load
 */
;window.Modernizr=function(a,b,c){function A(a){j.cssText=a}function B(a,b){return A(m.join(a+";")+(b||""))}function C(a,b){return typeof a===b}function D(a,b){return!!~(""+a).indexOf(b)}function E(a,b){for(var d in a){var e=a[d];if(!D(e,"-")&&j[e]!==c)return b=="pfx"?e:!0}return!1}function F(a,b,d){for(var e in a){var f=b[a[e]];if(f!==c)return d===!1?a[e]:C(f,"function")?f.bind(d||b):f}return!1}function G(a,b,c){var d=a.charAt(0).toUpperCase()+a.slice(1),e=(a+" "+o.join(d+" ")+d).split(" ");return C(b,"string")||C(b,"undefined")?E(e,b):(e=(a+" "+p.join(d+" ")+d).split(" "),F(e,b,c))}var d="2.8.3",e={},f=!0,g=b.documentElement,h="modernizr",i=b.createElement(h),j=i.style,k,l={}.toString,m=" -webkit- -moz- -o- -ms- ".split(" "),n="Webkit Moz O ms",o=n.split(" "),p=n.toLowerCase().split(" "),q={},r={},s={},t=[],u=t.slice,v,w=function(a,c,d,e){var f,i,j,k,l=b.createElement("div"),m=b.body,n=m||b.createElement("body");if(parseInt(d,10))while(d--)j=b.createElement("div"),j.id=e?e[d]:h+(d+1),l.appendChild(j);return f=["&#173;",'<style id="s',h,'">',a,"</style>"].join(""),l.id=h,(m?l:n).innerHTML+=f,n.appendChild(l),m||(n.style.background="",n.style.overflow="hidden",k=g.style.overflow,g.style.overflow="hidden",g.appendChild(n)),i=c(l,a),m?l.parentNode.removeChild(l):(n.parentNode.removeChild(n),g.style.overflow=k),!!i},x=function(b){var c=a.matchMedia||a.msMatchMedia;if(c)return c(b)&&c(b).matches||!1;var d;return w("@media "+b+" { #"+h+" { position: absolute; } }",function(b){d=(a.getComputedStyle?getComputedStyle(b,null):b.currentStyle)["position"]=="absolute"}),d},y={}.hasOwnProperty,z;!C(y,"undefined")&&!C(y.call,"undefined")?z=function(a,b){return y.call(a,b)}:z=function(a,b){return b in a&&C(a.constructor.prototype[b],"undefined")},Function.prototype.bind||(Function.prototype.bind=function(b){var c=this;if(typeof c!="function")throw new TypeError;var d=u.call(arguments,1),e=function(){if(this instanceof e){var a=function(){};a.prototype=c.prototype;var f=new a,g=c.apply(f,d.concat(u.call(arguments)));return Object(g)===g?g:f}return c.apply(b,d.concat(u.call(arguments)))};return e}),q.geolocation=function(){return"geolocation"in navigator},q.rgba=function(){return A("background-color:rgba(150,255,150,.5)"),D(j.backgroundColor,"rgba")},q.borderradius=function(){return G("borderRadius")},q.boxshadow=function(){return G("boxShadow")},q.textshadow=function(){return b.createElement("div").style.textShadow===""},q.cssgradients=function(){var a="background-image:",b="gradient(linear,left top,right bottom,from(#9f9),to(white));",c="linear-gradient(left top,#9f9, white);";return A((a+"-webkit- ".split(" ").join(b+a)+m.join(c+a)).slice(0,-a.length)),D(j.backgroundImage,"gradient")};for(var H in q)z(q,H)&&(v=H.toLowerCase(),e[v]=q[H](),t.push((e[v]?"":"no-")+v));return e.addTest=function(a,b){if(typeof a=="object")for(var d in a)z(a,d)&&e.addTest(d,a[d]);else{a=a.toLowerCase();if(e[a]!==c)return e;b=typeof b=="function"?b():b,typeof f!="undefined"&&f&&(g.className+=" "+(b?"":"no-")+a),e[a]=b}return e},A(""),i=k=null,function(a,b){function l(a,b){var c=a.createElement("p"),d=a.getElementsByTagName("head")[0]||a.documentElement;return c.innerHTML="x<style>"+b+"</style>",d.insertBefore(c.lastChild,d.firstChild)}function m(){var a=s.elements;return typeof a=="string"?a.split(" "):a}function n(a){var b=j[a[h]];return b||(b={},i++,a[h]=i,j[i]=b),b}function o(a,c,d){c||(c=b);if(k)return c.createElement(a);d||(d=n(c));var g;return d.cache[a]?g=d.cache[a].cloneNode():f.test(a)?g=(d.cache[a]=d.createElem(a)).cloneNode():g=d.createElem(a),g.canHaveChildren&&!e.test(a)&&!g.tagUrn?d.frag.appendChild(g):g}function p(a,c){a||(a=b);if(k)return a.createDocumentFragment();c=c||n(a);var d=c.frag.cloneNode(),e=0,f=m(),g=f.length;for(;e<g;e++)d.createElement(f[e]);return d}function q(a,b){b.cache||(b.cache={},b.createElem=a.createElement,b.createFrag=a.createDocumentFragment,b.frag=b.createFrag()),a.createElement=function(c){return s.shivMethods?o(c,a,b):b.createElem(c)},a.createDocumentFragment=Function("h,f","return function(){var n=f.cloneNode(),c=n.createElement;h.shivMethods&&("+m().join().replace(/[\w\-]+/g,function(a){return b.createElem(a),b.frag.createElement(a),'c("'+a+'")'})+");return n}")(s,b.frag)}function r(a){a||(a=b);var c=n(a);return s.shivCSS&&!g&&!c.hasCSS&&(c.hasCSS=!!l(a,"article,aside,dialog,figcaption,figure,footer,header,hgroup,main,nav,section{display:block}mark{background:#FF0;color:#000}template{display:none}")),k||q(a,c),a}var c="3.7.0",d=a.html5||{},e=/^<|^(?:button|map|select|textarea|object|iframe|option|optgroup)$/i,f=/^(?:a|b|code|div|fieldset|h1|h2|h3|h4|h5|h6|i|label|li|ol|p|q|span|strong|style|table|tbody|td|th|tr|ul)$/i,g,h="_html5shiv",i=0,j={},k;(function(){try{var a=b.createElement("a");a.innerHTML="<xyz></xyz>",g="hidden"in a,k=a.childNodes.length==1||function(){b.createElement("a");var a=b.createDocumentFragment();return typeof a.cloneNode=="undefined"||typeof a.createDocumentFragment=="undefined"||typeof a.createElement=="undefined"}()}catch(c){g=!0,k=!0}})();var s={elements:d.elements||"abbr article aside audio bdi canvas data datalist details dialog figcaption figure footer header hgroup main mark meter nav output progress section summary template time video",version:c,shivCSS:d.shivCSS!==!1,supportsUnknownElements:k,shivMethods:d.shivMethods!==!1,type:"default",shivDocument:r,createElement:o,createDocumentFragment:p};a.html5=s,r(b)}(this,b),e._version=d,e._prefixes=m,e._domPrefixes=p,e._cssomPrefixes=o,e.mq=x,e.testProp=function(a){return E([a])},e.testAllProps=G,e.testStyles=w,g.className=g.className.replace(/(^|\s)no-js(\s|$)/,"$1$2")+(f?" js "+t.join(" "):""),e}(this,this.document),function(a,b,c){function d(a){return"[object Function]"==o.call(a)}function e(a){return"string"==typeof a}function f(){}function g(a){return!a||"loaded"==a||"complete"==a||"uninitialized"==a}function h(){var a=p.shift();q=1,a?a.t?m(function(){("c"==a.t?B.injectCss:B.injectJs)(a.s,0,a.a,a.x,a.e,1)},0):(a(),h()):q=0}function i(a,c,d,e,f,i,j){function k(b){if(!o&&g(l.readyState)&&(u.r=o=1,!q&&h(),l.onload=l.onreadystatechange=null,b)){"img"!=a&&m(function(){t.removeChild(l)},50);for(var d in y[c])y[c].hasOwnProperty(d)&&y[c][d].onload()}}var j=j||B.errorTimeout,l=b.createElement(a),o=0,r=0,u={t:d,s:c,e:f,a:i,x:j};1===y[c]&&(r=1,y[c]=[]),"object"==a?l.data=c:(l.src=c,l.type=a),l.width=l.height="0",l.onerror=l.onload=l.onreadystatechange=function(){k.call(this,r)},p.splice(e,0,u),"img"!=a&&(r||2===y[c]?(t.insertBefore(l,s?null:n),m(k,j)):y[c].push(l))}function j(a,b,c,d,f){return q=0,b=b||"j",e(a)?i("c"==b?v:u,a,b,this.i++,c,d,f):(p.splice(this.i++,0,a),1==p.length&&h()),this}function k(){var a=B;return a.loader={load:j,i:0},a}var l=b.documentElement,m=a.setTimeout,n=b.getElementsByTagName("script")[0],o={}.toString,p=[],q=0,r="MozAppearance"in l.style,s=r&&!!b.createRange().compareNode,t=s?l:n.parentNode,l=a.opera&&"[object Opera]"==o.call(a.opera),l=!!b.attachEvent&&!l,u=r?"object":l?"script":"img",v=l?"script":u,w=Array.isArray||function(a){return"[object Array]"==o.call(a)},x=[],y={},z={timeout:function(a,b){return b.length&&(a.timeout=b[0]),a}},A,B;B=function(a){function b(a){var a=a.split("!"),b=x.length,c=a.pop(),d=a.length,c={url:c,origUrl:c,prefixes:a},e,f,g;for(f=0;f<d;f++)g=a[f].split("="),(e=z[g.shift()])&&(c=e(c,g));for(f=0;f<b;f++)c=x[f](c);return c}function g(a,e,f,g,h){var i=b(a),j=i.autoCallback;i.url.split(".").pop().split("?").shift(),i.bypass||(e&&(e=d(e)?e:e[a]||e[g]||e[a.split("/").pop().split("?")[0]]),i.instead?i.instead(a,e,f,g,h):(y[i.url]?i.noexec=!0:y[i.url]=1,f.load(i.url,i.forceCSS||!i.forceJS&&"css"==i.url.split(".").pop().split("?").shift()?"c":c,i.noexec,i.attrs,i.timeout),(d(e)||d(j))&&f.load(function(){k(),e&&e(i.origUrl,h,g),j&&j(i.origUrl,h,g),y[i.url]=2})))}function h(a,b){function c(a,c){if(a){if(e(a))c||(j=function(){var a=[].slice.call(arguments);k.apply(this,a),l()}),g(a,j,b,0,h);else if(Object(a)===a)for(n in m=function(){var b=0,c;for(c in a)a.hasOwnProperty(c)&&b++;return b}(),a)a.hasOwnProperty(n)&&(!c&&!--m&&(d(j)?j=function(){var a=[].slice.call(arguments);k.apply(this,a),l()}:j[n]=function(a){return function(){var b=[].slice.call(arguments);a&&a.apply(this,b),l()}}(k[n])),g(a[n],j,b,n,h))}else!c&&l()}var h=!!a.test,i=a.load||a.both,j=a.callback||f,k=j,l=a.complete||f,m,n;c(h?a.yep:a.nope,!!i),i&&c(i)}var i,j,l=this.yepnope.loader;if(e(a))g(a,0,l,0);else if(w(a))for(i=0;i<a.length;i++)j=a[i],e(j)?g(j,0,l,0):w(j)?B(j):Object(j)===j&&h(j,l);else Object(a)===a&&h(a,l)},B.addPrefix=function(a,b){z[a]=b},B.addFilter=function(a){x.push(a)},B.errorTimeout=1e4,null==b.readyState&&b.addEventListener&&(b.readyState="loading",b.addEventListener("DOMContentLoaded",A=function(){b.removeEventListener("DOMContentLoaded",A,0),b.readyState="complete"},0)),a.yepnope=k(),a.yepnope.executeStack=h,a.yepnope.injectJs=function(a,c,d,e,i,j){var k=b.createElement("script"),l,o,e=e||B.errorTimeout;k.src=a;for(o in d)k.setAttribute(o,d[o]);c=j?h:c||f,k.onreadystatechange=k.onload=function(){!l&&g(k.readyState)&&(l=1,c(),k.onload=k.onreadystatechange=null)},m(function(){l||(l=1,c(1))},e),i?k.onload():n.parentNode.insertBefore(k,n)},a.yepnope.injectCss=function(a,c,d,e,g,i){var e=b.createElement("link"),j,c=i?h:c||f;e.href=a,e.rel="stylesheet",e.type="text/css";for(j in d)e.setAttribute(j,d[j]);g||(n.parentNode.insertBefore(e,n),m(c,0))}}(this,document),Modernizr.load=function(){yepnope.apply(window,[].slice.call(arguments,0))};


/*!
 * enquire.js v2.1.6 - Awesome Media Queries in JavaScript
 * Copyright (c) 2017 Nick Williams - http://wicky.nillia.ms/enquire.js
 * License: MIT */

!function(a){if("object"==typeof exports&&"undefined"!=typeof module)module.exports=a();else if("function"==typeof define&&define.amd)define([],a);else{var b;b="undefined"!=typeof window?window:"undefined"!=typeof global?global:"undefined"!=typeof self?self:this,b.enquire=a()}}(function(){return function a(b,c,d){function e(g,h){if(!c[g]){if(!b[g]){var i="function"==typeof require&&require;if(!h&&i)return i(g,!0);if(f)return f(g,!0);var j=new Error("Cannot find module '"+g+"'");throw j.code="MODULE_NOT_FOUND",j}var k=c[g]={exports:{}};b[g][0].call(k.exports,function(a){var c=b[g][1][a];return e(c?c:a)},k,k.exports,a,b,c,d)}return c[g].exports}for(var f="function"==typeof require&&require,g=0;g<d.length;g++)e(d[g]);return e}({1:[function(a,b,c){function d(a,b){this.query=a,this.isUnconditional=b,this.handlers=[],this.mql=window.matchMedia(a);var c=this;this.listener=function(a){c.mql=a.currentTarget||a,c.assess()},this.mql.addListener(this.listener)}var e=a(3),f=a(4).each;d.prototype={constuctor:d,addHandler:function(a){var b=new e(a);this.handlers.push(b),this.matches()&&b.on()},removeHandler:function(a){var b=this.handlers;f(b,function(c,d){if(c.equals(a))return c.destroy(),!b.splice(d,1)})},matches:function(){return this.mql.matches||this.isUnconditional},clear:function(){f(this.handlers,function(a){a.destroy()}),this.mql.removeListener(this.listener),this.handlers.length=0},assess:function(){var a=this.matches()?"on":"off";f(this.handlers,function(b){b[a]()})}},b.exports=d},{3:3,4:4}],2:[function(a,b,c){function d(){if(!window.matchMedia)throw new Error("matchMedia not present, legacy browsers require a polyfill");this.queries={},this.browserIsIncapable=!window.matchMedia("only all").matches}var e=a(1),f=a(4),g=f.each,h=f.isFunction,i=f.isArray;d.prototype={constructor:d,register:function(a,b,c){var d=this.queries,f=c&&this.browserIsIncapable;return d[a]||(d[a]=new e(a,f)),h(b)&&(b={match:b}),i(b)||(b=[b]),g(b,function(b){h(b)&&(b={match:b}),d[a].addHandler(b)}),this},unregister:function(a,b){var c=this.queries[a];return c&&(b?c.removeHandler(b):(c.clear(),delete this.queries[a])),this}},b.exports=d},{1:1,4:4}],3:[function(a,b,c){function d(a){this.options=a,!a.deferSetup&&this.setup()}d.prototype={constructor:d,setup:function(){this.options.setup&&this.options.setup(),this.initialised=!0},on:function(){!this.initialised&&this.setup(),this.options.match&&this.options.match()},off:function(){this.options.unmatch&&this.options.unmatch()},destroy:function(){this.options.destroy?this.options.destroy():this.off()},equals:function(a){return this.options===a||this.options.match===a}},b.exports=d},{}],4:[function(a,b,c){function d(a,b){var c=0,d=a.length;for(c;c<d&&b(a[c],c)!==!1;c++);}function e(a){return"[object Array]"===Object.prototype.toString.apply(a)}function f(a){return"function"==typeof a}b.exports={isFunction:f,isArray:e,each:d}},{}],5:[function(a,b,c){var d=a(2);b.exports=new d},{2:2}]},{},[5])(5)});
/*
EJEMPLO DE USO DE ENQUIREJS

enquire.register("screen and (min-width: 487px)", function() {
$('#slide-box').load(url + "ajax/imgslide.php");
});
enquire.register("screen and (max-width: 487px)", function() {
$('#slide-box').load("ajax/txtslide.php");
});
*/


/**
 * jQuery.browser.mobile (http://detectmobilebrowser.com/)
 *
 * jQuery.browser.mobile will be true if the browser is a mobile device
 *
 **/
(function(a){(jQuery.browser=jQuery.browser||{}).mobile=/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))})(navigator.userAgent||navigator.vendor||window.opera);
/* EJEMPLO DE USO:
if (jQuery.browser.mobile) {
        $('#jQueryDetection').html("hi mobile with jQuery");
    } else {
        $('#jQueryDetection').html("Hi computer with jQuery");
    }
*/


// VENTANA DE ACCESOS A ZONA PRIVADA MOBILE.
$(document).ready(function(){if($.browser.device=(/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()))){$('#enter').click(function(evento){if($("#accesos").is(":visible")){$('#accesos').fadeOut(1000);}else{$('#accesos').fadeIn(1000);}});$('#login > h1').click(function(evento){if($("#accesos").is(":visible")){$('#accesos').fadeOut(1000);}});$('#slideLogoBox').click(function(evento){if($("#accesos").is(":visible")){$('#accesos').fadeOut(1000);}});$('#nav').click(function(evento){if($("#accesos").is(":visible")){$('#accesos').fadeOut(1000);}});$('#content').click(function(evento){if($("#accesos").is(":visible")){$('#accesos').fadeOut(1000);}});$('#footer').click(function(evento){if($("#accesos").is(":visible")){$('#accesos').fadeOut(1000);}});$('#copiright').click(function(evento){if($("#accesos").is(":visible")){$('#accesos').fadeOut(1000);}});}else{$('#enter').mouseenter(function(evento){if($("#accesos").is(":hidden")){$('#accesos').fadeIn(1000);}});$('#slideLogoBox').mouseenter(function(evento){if($("#accesos").is(":visible")){$('#accesos').fadeOut(1000);}});$('#nav').mouseenter(function(evento){if($("#accesos").is(":visible")){$('#accesos').fadeOut(1000);}});$('#content').mouseenter(function(evento){if($("#accesos").is(":visible")){$('#accesos').fadeOut(1000);}});$('#footer').mouseenter(function(evento){if($("#accesos").is(":visible")){$('#accesos').fadeOut(1000);}});$('#copiright').mouseenter(function(evento){if($("#accesos").is(":visible")){$('#accesos').fadeOut(1000);}});}});




/*LAZY LOAD PARA MEJORAR LOS TIEMPOS DE CARGA DE LAS IMAGENES.*/
(function($,window){var $window=$(window);$.fn.lazyload=function(options){var elements=this;var $container;var settings={threshold:0,failure_limit:0,event:"scroll",effect:"show",container:window,data_attribute:"original",skip_invisible:true,appear:null,load:null};function update(){var counter=0;elements.each(function(){var $this=$(this);if(settings.skip_invisible&&!$this.is(":visible")){return;}
if($.abovethetop(this,settings)||$.leftofbegin(this,settings)){}else if(!$.belowthefold(this,settings)&&!$.rightoffold(this,settings)){$this.trigger("appear");}else{if(++counter>settings.failure_limit){return false;}}});}
if(options){if(undefined!==options.failurelimit){options.failure_limit=options.failurelimit;delete options.failurelimit;}
if(undefined!==options.effectspeed){options.effect_speed=options.effectspeed;delete options.effectspeed;}
$.extend(settings,options);}
$container=(settings.container===undefined||settings.container===window)?$window:$(settings.container);if(0===settings.event.indexOf("scroll")){$container.bind(settings.event,function(event){return update();});}
this.each(function(){var self=this;var $self=$(self);self.loaded=false;$self.one("appear",function(){if(!this.loaded){if(settings.appear){var elements_left=elements.length;settings.appear.call(self,elements_left,settings);}
$("<img />").bind("load",function(){$self.hide().attr("src",$self.data(settings.data_attribute))
[settings.effect](settings.effect_speed);self.loaded=true;var temp=$.grep(elements,function(element){return!element.loaded;});elements=$(temp);if(settings.load){var elements_left=elements.length;settings.load.call(self,elements_left,settings);}}).attr("src",$self.data(settings.data_attribute));}});if(0!==settings.event.indexOf("scroll")){$self.bind(settings.event,function(event){if(!self.loaded){$self.trigger("appear");}});}});$window.bind("resize",function(event){update();});update();return this;};$.belowthefold=function(element,settings){var fold;if(settings.container===undefined||settings.container===window){fold=$window.height()+$window.scrollTop();}else{fold=$(settings.container).offset().top+$(settings.container).height();}
return fold<=$(element).offset().top-settings.threshold;};$.rightoffold=function(element,settings){var fold;if(settings.container===undefined||settings.container===window){fold=$window.width()+$window.scrollLeft();}else{fold=$(settings.container).offset().left+$(settings.container).width();}
return fold<=$(element).offset().left-settings.threshold;};$.abovethetop=function(element,settings){var fold;if(settings.container===undefined||settings.container===window){fold=$window.scrollTop();}else{fold=$(settings.container).offset().top;}
return fold>=$(element).offset().top+settings.threshold+$(element).height();};$.leftofbegin=function(element,settings){var fold;if(settings.container===undefined||settings.container===window){fold=$window.scrollLeft();}else{fold=$(settings.container).offset().left;}
return fold>=$(element).offset().left+settings.threshold+$(element).width();};$.inviewport=function(element,settings){return!$.rightofscreen(element,settings)&&!$.leftofscreen(element,settings)&&!$.belowthefold(element,settings)&&!$.abovethetop(element,settings);};$.extend($.expr[':'],{"below-the-fold":function(a){return $.belowthefold(a,{threshold:0});},"above-the-top":function(a){return!$.belowthefold(a,{threshold:0});},"right-of-screen":function(a){return $.rightoffold(a,{threshold:0});},"left-of-screen":function(a){return!$.rightoffold(a,{threshold:0});},"in-viewport":function(a){return!$.inviewport(a,{threshold:0});},"above-the-fold":function(a){return!$.belowthefold(a,{threshold:0});},"right-of-fold":function(a){return $.rightoffold(a,{threshold:0});},"left-of-fold":function(a){return!$.rightoffold(a,{threshold:0});}});})(jQuery,window);



//// PLUGIN FILESTYLE, PARA LA PERSONALIZACION BOOTRAP DE LOS ELEMENTOS FILE.
(function($){var nextId=0;var Filestyle=function(element,options){this.options=options;this.$elementFilestyle=[];this.$element=$(element)};Filestyle.prototype={clear:function(){this.$element.val("");this.$elementFilestyle.find(":text").val("");this.$elementFilestyle.find(".badge").remove()},destroy:function(){this.$element.removeAttr("style").removeData("filestyle");this.$elementFilestyle.remove()},disabled:function(value){if(value===true){if(!this.options.disabled){this.$element.attr("disabled","true");this.$elementFilestyle.find("label").attr("disabled","true");this.options.disabled=true}}else{if(value===false){if(this.options.disabled){this.$element.removeAttr("disabled");this.$elementFilestyle.find("label").removeAttr("disabled");this.options.disabled=false}}else{return this.options.disabled}}},buttonBefore:function(value){if(value===true){if(!this.options.buttonBefore){this.options.buttonBefore=true;if(this.options.input){this.$elementFilestyle.remove();this.constructor();this.pushNameFiles()}}}else{if(value===false){if(this.options.buttonBefore){this.options.buttonBefore=false;if(this.options.input){this.$elementFilestyle.remove();this.constructor();this.pushNameFiles()}}}else{return this.options.buttonBefore}}},icon:function(value){if(value===true){if(!this.options.icon){this.options.icon=true;this.$elementFilestyle.find("label").prepend(this.htmlIcon())}}else{if(value===false){if(this.options.icon){this.options.icon=false;this.$elementFilestyle.find(".icon-span-filestyle").remove()}}else{return this.options.icon}}},input:function(value){if(value===true){if(!this.options.input){this.options.input=true;if(this.options.buttonBefore){this.$elementFilestyle.append(this.htmlInput())}else{this.$elementFilestyle.prepend(this.htmlInput())}this.$elementFilestyle.find(".badge").remove();this.pushNameFiles();this.$elementFilestyle.find(".group-span-filestyle").addClass("input-group-btn")}}else{if(value===false){if(this.options.input){this.options.input=false;this.$elementFilestyle.find(":text").remove();var files=this.pushNameFiles();if(files.length>0&&this.options.badge){this.$elementFilestyle.find("label").append(' <span class="badge">'+files.length+"</span>")}this.$elementFilestyle.find(".group-span-filestyle").removeClass("input-group-btn")}}else{return this.options.input}}},size:function(value){if(value!==undefined){var btn=this.$elementFilestyle.find("label"),input=this.$elementFilestyle.find("input");btn.removeClass("btn-lg btn-sm");input.removeClass("input-lg input-sm");if(value!="nr"){btn.addClass("btn-"+value);input.addClass("input-"+value)}}else{return this.options.size}},placeholder:function(value){if(value!==undefined){this.options.placeholder=value;this.$elementFilestyle.find("input").attr("placeholder",value)}else{return this.options.placeholder}},buttonText:function(value){if(value!==undefined){this.options.buttonText=value;this.$elementFilestyle.find("label .buttonText").html(this.options.buttonText)}else{return this.options.buttonText}},buttonName:function(value){if(value!==undefined){this.options.buttonName=value;this.$elementFilestyle.find("label").attr({"class":"btn "+this.options.buttonName})}else{return this.options.buttonName}},iconName:function(value){if(value!==undefined){this.$elementFilestyle.find(".icon-span-filestyle").attr({"class":"icon-span-filestyle "+this.options.iconName})}else{return this.options.iconName}},htmlIcon:function(){if(this.options.icon){return'<span class="icon-span-filestyle '+this.options.iconName+'"></span> '}else{return""}},htmlInput:function(){if(this.options.input){return'<input type="text" class=" '+(this.options.size=="nr"?"":"input-"+this.options.size)+'" placeholder="'+this.options.placeholder+'" disabled> '}else{return""}},pushNameFiles:function(){var content="",files=[];if(this.$element[0].files===undefined){files[0]={name:this.$element[0]&&this.$element[0].value}}else{files=this.$element[0].files}for(var i=0;i<files.length;i++){content+=files[i].name.split("\\").pop()+", "}if(content!==""){this.$elementFilestyle.find(":text").val(content.replace(/\, $/g,""))}else{this.$elementFilestyle.find(":text").val("")}return files},constructor:function(){var _self=this,html="",id=_self.$element.attr("id"),files=[],btn="",$label;if(id===""||!id){id="filestyle-"+nextId;_self.$element.attr({id:id});nextId++}btn='<span class="group-span-filestyle '+(_self.options.input?"input-group-btn":"")+'"><label for="'+id+'" class="btn '+_self.options.buttonName+" "+(_self.options.size=="nr"?"":"btn-"+_self.options.size)+'" '+(_self.options.disabled?'disabled="true"':"")+">"+_self.htmlIcon()+'<span class="buttonText">'+_self.options.buttonText+"</span></label></span>";html=_self.options.buttonBefore?btn+_self.htmlInput():_self.htmlInput()+btn;_self.$elementFilestyle=$('<div class="bootstrap-filestyle" style="width:120px; display:inline;">'+html+"</div>");_self.$elementFilestyle.find(".group-span-filestyle").attr("tabindex","0").keypress(function(e){if(e.keyCode===13||e.charCode===32){_self.$elementFilestyle.find("label").click();return false}});_self.$element.css({display:"none",clip:"rect(0px 0px 0px 0px)"}).attr("tabindex","-1").after(_self.$elementFilestyle);if(_self.options.disabled){_self.$element.attr("disabled","true")}_self.$element.change(function(){var files=_self.pushNameFiles();if(_self.options.input==false&&_self.options.badge){if(_self.$elementFilestyle.find(".badge").length==0){_self.$elementFilestyle.find("label").append(' <span class="badge">'+files.length+"</span>")}else{if(files.length==0){_self.$elementFilestyle.find(".badge").remove()}else{_self.$elementFilestyle.find(".badge").html(files.length)}}}else{_self.$elementFilestyle.find(".badge").remove()}});if(window.navigator.userAgent.search(/firefox/i)>-1){_self.$elementFilestyle.find("label").click(function(){_self.$element.click();return false})}}};var old=$.fn.filestyle;$.fn.filestyle=function(option,value){var get="",element=this.each(function(){if($(this).attr("type")==="file"){var $this=$(this),data=$this.data("filestyle"),options=$.extend({},$.fn.filestyle.defaults,option,typeof option==="object"&&option);if(!data){$this.data("filestyle",(data=new Filestyle(this,options)));data.constructor()}if(typeof option==="string"){get=data[option](value)}}});if(typeof get!==undefined){return get}else{return element}};$.fn.filestyle.defaults={buttonText:"Choose file",iconName:"glyphicon glyphicon-folder-open",buttonName:"btn-default",size:"nr",input:true,badge:true,icon:true,buttonBefore:false,disabled:false,placeholder:""};$.fn.filestyle.noConflict=function(){$.fn.filestyle=old;return this};$(function(){$(".filestyle").each(function(){var $this=$(this),options={input:$this.attr("data-input")==="false"?false:true,icon:$this.attr("data-icon")==="false"?false:true,buttonBefore:$this.attr("data-buttonBefore")==="true"?true:false,disabled:$this.attr("data-disabled")==="true"?true:false,size:$this.attr("data-size"),buttonText:$this.attr("data-buttonText"),buttonName:$this.attr("data-buttonName"),iconName:$this.attr("data-iconName"),badge:$this.attr("data-badge")==="false"?false:true,placeholder:$this.attr("data-placeholder")};$this.filestyle(options)})})})(window.jQuery);


// /* Inicialización en español para la extensión 'UI date picker' para jQuery. */

$.datepicker.regional['es'] = {
 closeText: 'Cerrar',
 prevText: '< Ant',
 nextText: 'Sig >',
 currentText: 'Hoy',
 monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
 monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
 dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
 dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
 dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
 weekHeader: 'Sm',
 dateFormat: 'dd/mm/yy',
 firstDay: 1,
 isRTL: false,
 showMonthAfterYear: false,
 changeMonth: true,
 changeYear: true,
 yearSuffix: ''
 };
 
 
 $.datepicker.setDefaults($.datepicker.regional['es']);


function fechaCorrecta(fecha1, fecha2){

    //Split de las fechas recibidas para separarlas
    var x = fecha1.split("/");
    var z = fecha2.split("/");

    //Cambiamos el orden al formato americano, de esto dd/mm/yyyy a esto mm/dd/yyyy
    fecha1 = x[1] + "-" + x[0] + "-" + x[2];
    fecha2 = z[1] + "-" + z[0] + "-" + z[2];

    //Comparamos las fechas
    if (Date.parse(fecha1) > Date.parse(fecha2)){
        return false;
    }else{
        return true;
    }
}

// FUNCIOONES DE FECHAS:
// SACAR SI ES BISIESTO, SUMAR Y RESTAR 1 DIA
function esBisiesto(e){return e%4==0&&e%100!=0||e%400==0}function suma1Dia(e){var n=e.split("/"),a=parseInt(n[0]),u=parseInt(n[1]),v=parseInt(n[2]);if(2==u)if(esBisiesto(v)){if(29==a)return nuevaFecha="01/03/"+v,nuevaFecha;if(a<29)return nuevoDia=a+1,nuevoDia<10?nuevaFecha="0"+nuevoDia+"/02/"+v:nuevoDia>=10&&(nuevaFecha=nuevoDia+"/02/"+v),nuevaFecha}else{if(28==a)return nuevaFecha="01/03/"+v,nuevaFecha;if(a<28)return nuevoDia=a+1,nuevoDia<10?nuevaFecha="0"+nuevoDia+"/02/"+v:nuevoDia>=10&&(nuevaFecha=nuevoDia+"/02/"+v),nuevaFecha}else if(12==u){if(31==a)return nuevoAno=v+1,nuevaFecha="01/01/"+nuevoAno,nuevaFecha;if(a<31)return nuevoDia=a+1,nuevoDia<10?nuevaFecha="0"+nuevoDia+"/12/"+v:nuevoDia>=10&&(nuevaFecha=nuevoDia+"/12/"+v),nuevaFecha}else if(1==u||3==u||5==u||7==u||8==u||10==u){if(31==a)return u<10?(sumaMes=u+1,nuevoMes="0"+sumaMes):nuevoMes=u+1,nuevaFecha="01/"+nuevoMes+"/"+v,nuevaFecha;if(a<31)return a<9?(nuevoDia=a+1,nuevoDia="0"+nuevoDia):a>=9&&(nuevoDia=a+1),u<10&&(u="0"+u),nuevaFecha=nuevoDia+"/"+u+"/"+v,nuevaFecha}else if(4==u||6==u||9==u||11==u){if(30==a)return u<9?(nuevoMes=u+1,nuevoMes="0"+nuevoMes):u>=9&&(nuevoMes=u+1),nuevaFecha="01/"+nuevoMes+"/"+v,nuevaFecha;if(a<30)return a<9?(nuevoDia=a+1,nuevoDia="0"+nuevoDia):a>=9&&(nuevoDia=a+1),u<10&&(u="0"+u),nuevaFecha=nuevoDia+"/"+u+"/"+v,nuevaFecha}}function resta1Dia(e){var n=e.split("/"),a=parseInt(n[0]),u=parseInt(n[1]),v=parseInt(n[2]);if(1==u){if(1==a)return nuevoAno=v-1,nuevaFecha="31/12/"+nuevoAno,nuevaFecha;if(a<=31&&a>1)return nuevoDia=a-1,nuevoDia<10?nuevaFecha="0"+nuevoDia+"/12/"+v:nuevoDia>=10&&(nuevaFecha=nuevoDia+"/12/"+v),nuevaFecha}else if(2==u||4==u||6==u||8==u||9==u||11==u){if(1==a)return nuevoMes=u-1,nuevoMes>10&&(nuevoMes="0"+nuevoMes),nuevaFecha="31/"+nuevoMes+"/"+v,nuevaFecha;if(a<=31&&a>1)return nuevoDia=a-1,nuevoDia<=9&&(nuevoDia="0"+nuevoDia),11!=u&&(u="0"+u),nuevaFecha=nuevoDia+"/"+u+"/"+v,nuevaFecha}else if(12==u||10==u||7==u||5==u){if(1==a)return nuevoMes=u-1,nuevoMes<10&&(nuevoMes="0"+nuevoMes),nuevaFecha="30/"+nuevoMes+"/"+v,nuevaFecha;if(a<=31&&a>1)return nuevoDia=a-1,nuevoDia<=9&&(nuevoDia="0"+nuevoDia),u<=10&&(u="0"+u),nuevaFecha=nuevoDia+"/"+u+"/"+v,nuevaFecha}else if(3==u){if(1==a)return esBisiesto(v)?nuevaFecha="29/02/"+v:nuevaFecha="28/02/"+v,nuevaFecha;if(a<=29&&a>1)return nuevoDia=a-1,nuevoDia<=9&&(nuevoDia="0"+nuevoDia),nuevaFecha=nuevoDia+"/03/"+v,nuevaFecha}}


//////// ESTA FUNCTION ES PARA HACER REDIRECCIONES CUANDO PASA X TIME
 // var segundos = 5; //Segundos de la cuenta atrás 
 //    function tiempo(){  
 //  var t = setTimeout("tiempo()",1000);  
 //  document.getElementById('contenedor').innerHTML = 'Serás redireccionado en '+segundos--+" segundos.";  
 //  if (segundos==-1){

 //        window.location.href='http://www.soltury.com/descarga-tu-guia';  //Págiana a la que redireccionará a X segundos
  
 //   clearTimeout(t);  
 //  }  
 // }  
 // tiempo()


 /// FUNCION PARA CONTROLAR EL MOVIMIENTO DE LA PANTALLA MOVIL


   //             // Escuchar cambios de orientación
//    window.addEventListener("orientationchange", function() {
//        // Hacer algo cuando cambia la orientación
//        //alert(window.orientation);
//.      
//        window.orientation==0(esta de pie)
//        if(window.orientation==90 || window.orientation==-90){

//          alert("Esta de lado");
//        }else {
//          alert("esta de pie");

//        }

//    }, false);



/*
 * jquery-filestyle
 * doc: http://markusslima.github.io/jquery-filestyle/
 * github: https://github.com/markusslima/jquery-filestyle
 *
 * Copyright (c) 2017 Markus Vinicius da Silva Lima
 * Version 2.1.0
 * Licensed under the MIT license.

version 1
$(":file").filestyle({
  buttonText: "Selecciona Archivo", 
  input: false
//placeholder: "Sube Un Bixi-Archivo",
//buttonBefore: true,
//theme: "blue"
});


version 2
$(":file").jfilestyle({
  text: "Selecciona Archivo", 
  input: false
//placeholder: "Sube Un Bixi-Archivo",
//buttonBefore: true,
//theme: "blue"
});

 */
!function(e){"use strict";var t=0,i=function(t,i){this.options=i,this.$elementjFilestyle=[],this.$element=e(t)};i.prototype={clear:function(){this.$element.val(""),this.$elementjFilestyle.find(":text").val(""),this.$elementjFilestyle.find(".count-jfilestyle").remove()},destroy:function(){this.$element.removeAttr("style").removeData("jfilestyle").val(""),this.$elementjFilestyle.remove()},dragdrop:function(e){return e!==!0&&e!==!1?this.options.dragdrop:void(this.options.dragdrop=e)},disabled:function(e){if(e===!0)this.options.disabled||(this.$element.attr("disabled","true"),this.$elementjFilestyle.find("label").attr("disabled","true"),this.options.disabled=!0);else{if(e!==!1)return this.options.disabled;this.options.disabled&&(this.$element.removeAttr("disabled"),this.$elementjFilestyle.find("label").removeAttr("disabled"),this.options.disabled=!1)}},buttonBefore:function(e){if(e===!0)this.options.buttonBefore||(this.options.buttonBefore=!0,this.options.input&&(this.$elementjFilestyle.remove(),this.constructor(),this.pushNameFiles()));else{if(e!==!1)return this.options.buttonBefore;this.options.buttonBefore&&(this.options.buttonBefore=!1,this.options.input&&(this.$elementjFilestyle.remove(),this.constructor(),this.pushNameFiles()))}},input:function(e){if(e===!0)this.options.input||(this.options.input=!0,this.$elementjFilestyle.find("label").before(this.htmlInput()),this.$elementjFilestyle.find(".count-jfilestyle").remove(),this.pushNameFiles());else{if(e!==!1)return this.options.input;if(this.options.input){this.options.input=!1,this.$elementjFilestyle.find(":text").remove();var t=this.pushNameFiles();t.length>0&&this.$elementjFilestyle.find("label").append(' <span class="count-jfilestyle">'+t.length+"</span>")}}},text:function(e){return void 0===e?this.options.text:(this.options.text=e,void this.$elementjFilestyle.find("label span").html(this.options.text))},theme:function(e){return void 0===e?this.options.theme:(console.log(this.$elementjFilestyle.attr("class").replace(/.*(jfilestyle-theme-.*).*/,"$1")),this.$elementjFilestyle.removeClass(this.$elementjFilestyle.attr("class").replace(/.*(jfilestyle-theme-.*).*/,"$1")),this.options.theme=e,this.$elementjFilestyle.addClass("jfilestyle-theme-"+this.options.theme),void 0)},inputSize:function(e){return void 0===e?this.options.inputSize:(this.options.inputSize=e,void this.$elementjFilestyle.find(":text").css("width",this.options.inputSize))},placeholder:function(e){return void 0===e?this.options.placeholder:(this.options.placeholder=e,void this.$elementjFilestyle.find(":text").attr("placeholder",e))},htmlInput:function(){return this.options.input?'<input type="text" style="width:'+this.options.inputSize+'" placeholder="'+this.options.placeholder+'" disabled> ':""},pushNameFiles:function(){var e="",t=[];void 0===this.$element[0].files?t[0]={name:this.$element.value}:t=this.$element[0].files;for(var i=0;i<t.length;i++)e+=t[i].name.split("\\").pop()+", ";return""!==e?this.$elementjFilestyle.find(":text").val(e.replace(/\, $/g,"")):this.$elementjFilestyle.find(":text").val(""),t},constructor:function(){var i=this,n="",l=i.$element.attr("id");""!==l&&l||(l="jfilestyle-"+t,i.$element.attr({id:l}),t++),n='<span class="focus-jfilestyle"><label for="'+l+'" '+(i.options.disabled?'disabled="true"':"")+"><span>"+i.options.text+"</span></label></span>",i.options.buttonBefore===!0?n+=i.htmlInput():n=i.htmlInput()+n,i.$elementjFilestyle=e('<div class="jfilestyle '+(i.options.input?"jfilestyle-corner":"")+" "+(this.options.buttonBefore?" jfilestyle-buttonbefore":"")+" "+(i.options.theme?"jfilestyle-theme-"+i.options.theme:"")+'"><div name="filedrag"></div>'+n+"</div>"),i.$elementjFilestyle.find(".focus-jfilestyle").attr("tabindex","0").keypress(function(e){return 13===e.keyCode||32===e.charCode?(i.$elementjFilestyle.find("label").click(),!1):void 0}),i.$element.css({position:"absolute",clip:"rect(0px 0px 0px 0px)"}).attr("tabindex","-1").after(i.$elementjFilestyle),i.options.disabled&&i.$element.attr("disabled","true"),i.$elementjFilestyle.find('[name="filedrag"]').css({position:"absolute",width:"100%",height:i.$elementjFilestyle.height()+"px","z-index":-1}),i.$element.change(function(){var e=i.pushNameFiles();0==i.options.input?0==i.$elementjFilestyle.find(".count-jfilestyle").length?i.$elementjFilestyle.find("label").append(' <span class="count-jfilestyle">'+e.length+"</span>"):0==e.length?i.$elementjFilestyle.find(".count-jfilestyle").remove():i.$elementjFilestyle.find(".count-jfilestyle").html(e.length):i.$elementjFilestyle.find(".count-jfilestyle").remove(),i.options.onChange(e)}),window.navigator.userAgent.search(/firefox/i)>-1&&this.$elementjFilestyle.find("label").click(function(){return i.$element.click(),!1}),e(document).on("dragover",function(t){t.preventDefault(),t.stopPropagation(),i.options.dragdrop||e('[name="filedrag"]').css("z-index","9")}).on("drop",function(t){t.preventDefault(),t.stopPropagation(),i.options.dragdrop||e('[name="filedrag"]').css("z-index","-1")}),i.$elementjFilestyle.find('[name="filedrag"]').on("dragover",function(e){e.preventDefault(),e.stopPropagation()}).on("dragenter",function(e){e.preventDefault(),e.stopPropagation()}).on("drop",function(t){if(t.originalEvent.dataTransfer&&!i.options.disabled&&i.options.dragdrop&&t.originalEvent.dataTransfer.files.length){t.preventDefault(),t.stopPropagation(),i.$element[0].files=t.originalEvent.dataTransfer.files;var n=i.pushNameFiles();0==i.options.input?0==i.$elementjFilestyle.find(".count-jfilestyle").length?i.$elementjFilestyle.find("label").append(' <span class="count-jfilestyle">'+n.length+"</span>"):0==n.length?i.$elementjFilestyle.find(".count-jfilestyle").remove():i.$elementjFilestyle.find(".count-jfilestyle").html(n.length):i.$elementjFilestyle.find(".count-jfilestyle").remove(),e('[name="filedrag"]').css("z-index","-1")}})}};var n=e.fn.jfilestyle;e.fn.jfilestyle=function(t,n){var l="",s=this.each(function(){if("file"===e(this).attr("type")){var s=e(this),o=s.data("jfilestyle"),a=e.extend({},e.fn.jfilestyle.defaults,t,"object"==typeof t&&t);o||(s.data("jfilestyle",o=new i(this,a)),o.constructor()),"string"==typeof t&&(l=o[t](n))}});return void 0!==typeof l?l:s},e.fn.jfilestyle.defaults={text:"Choose file",input:!0,disabled:!1,buttonBefore:!1,inputSize:"200px",placeholder:"",dragdrop:!0,theme:"default",onChange:function(){}},e.fn.jfilestyle.noConflict=function(){return e.fn.jfilestyle=n,this},e(function(){e(".jfilestyle").each(function(){var t=e(this),i={text:t.attr("data-text"),input:"false"!==t.attr("data-input"),disabled:"true"===t.attr("data-disabled"),buttonBefore:"true"===t.attr("data-buttonBefore"),inputSize:t.attr("data-inputSize"),placeholder:t.attr("data-placeholder"),theme:t.attr("data-theme"),dragdrop:"false"!==t.attr("data-dragdrop")};t.jfilestyle(i)})})}(window.jQuery);
















