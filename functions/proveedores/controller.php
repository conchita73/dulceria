<?php 	@session_start(); 
	//error_reporting(E_ALL);
	date_default_timezone_set('Europe/Madrid');
	
	/*	Listado de metodos:


			__construct
			
			disponibilidad
			disponibilidad_clientes
			comprobarDistribucion
			listados
		
			
			detalles
			sacar_gastos
			detallesHotel
			
			confirmar
			
			infoCancelacion
			cancelar
			
			modificar
			
			listadoReservas
			DetalleReserva
			
			registrarError
			
			formatearDetalleReserva
			formatearListadoReservas
			formatearModificar
			formatearCancelacion
			formatearInfoCancelacion
			formatearDetallesConfirm
			formatearDetallesHotel
			formatearDetalles()
			
			listadoRegimenes
			listadoHabitaciones
			
			eliminarDuplicados
			ordenarPrecios
			xml2array
	  		
			idioma
			
			
	  		Parametros que se envian en una busqueda:
			  pais
			  provincia
			  poblacion2
			  poblacion �?
			  fecha1
			  fecha2
			  regimen
			  idhotel*/

	//include_once("../funciones.php");
	include("MultiCurl.class.php");
	
	include("serhs.php");
	include("versys.php");
	include("bedsonline.php");
	include("europlayas.php");
    include("solole.php");
    include("veturis.php");
	
	class control{
		
		var $curl;
		var $parametros=array();
		
		var $bedsonline;
		var $serhs;
		var $versys;
		var $europlayas;
        var $solole;
        var $veturis;

        var $id_cliente;
        var $id_booking;
        var $busqueda; 
		
		var $DB;
		var $sql_listados;
		var $preparar_sql;
		var $ListadoHoteles=array();
		var $proveedor="";
		
		var $dir_serhs="";
		var $dir_versys="";
		var $dir_bedsonline="";
		var $dir_europlayas="";
        var $dir_solole="";
        var $dir_veturis="";
		
		var $ListadoReservas=array();
		
		//var $iva=1.18;
		
		var $regimenes=array();
		var $habitaciones=array();
		
		public function __construct($VAR='',$proveedor=''){
			
			$this->parametros=$VAR;
			//$this->parametros['idioma']=$this->idioma();
			
			$this->curl=new MyMultiCurl();
			$this->DB=new DataAccess();
			
			
			$this->serhs=new serhs($this->parametros,$this->DB->link);
			$this->bedsonline=new bedsonline($this->parametros,$this->DB->link);
			$this->europlayas=new europlayas($this->parametros,$this->DB->link);
			$this->versys=new versys($this->parametros,$this->DB->link);
            $this->solole=new solole($this->parametros,$this->DB->link);
            $this->veturis=new veturis($this->parametros,$this->DB->link);
			
			$this->dir_serhs=$this->serhs->url;
			$this->dir_bedsonline=$this->bedsonline->url;
			$this->dir_europlayas=$this->europlayas->url;
			$this->dir_veturis=$this->veturis->url;
		
			$this->proveedor=$proveedor;

		}

		########################### SECCION DE LLAMADAS 
		
		

		public function disponibilidad_segmentada($id_cliente='',$id_booking='',$busqueda='',$auto_gestion=0,$sin_filtros_regimen=0){
			//$this->listadoRegimenes();
			//$this->listadoHabitaciones();

			// insertamos pet_xml para hacer debajo el seguimiento.
			$id_xml=$this->DB->query("insert into _xml_peticiones (
			busqueda,
			dominio,
			id_booking,
			id_cliente,
			creacion) values (
			'".$busqueda."',
			'".$_SERVER['HTTP_HOST']."',
			".$id_booking.",
			".$id_cliente.",
			'".date('Y-m-d H:i:s')."')");


			// añadimos un nuevo historico al booking EN AJAX.PHP cuando acaba el envio y lo asocia a
			// la tabla booking_emails

			$estado_comprobar = isset($_SESSION['busqueda_pedro']) ? 'estado_pedro' : 'estado_booking';

			$serhs_=$this->DB->select($estado_comprobar,'mayoristas','nombre like "SERHS"');

			if($serhs_[f][0][$estado_comprobar]==1){

				if($this->serhs->disponibilidad_segmentada()!=false){

					$this->curl->addUrl($this->serhs->url,array(CURLOPT_SSL_VERIFYPEER => FALSE,  CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip", CURLOPT_POSTFIELDS =>$this->serhs->disponibilidad_segmentada()));
					
				//if($this->serhs->disponibilidad_segmentada()!=false){
				}else{ $false_serhs="Serhs No mapeado";}
			//if($serhs_[f][0][$estado_comprobar]==1){  
			}else{ $false_serhs="Serhs Desactivado";}
							
			$europlayas_=$this->DB->select($estado_comprobar,'mayoristas','nombre like "EUROPLAYAS"');

			if($europlayas_[f][0][$estado_comprobar]==1){	

				if($this->comprobarDistribucionEuro()){
					$this->abrirSession();
					if($this->europlayas->disponibilidad_segmentada()!=false){

			  			$this->curl->addUrl($this->europlayas->url,array(CURLOPT_SSL_VERIFYPEER => FALSE,  CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip", CURLOPT_POSTFIELDS =>$this->europlayas->disponibilidad_segmentada()));
			  		//if($this->europlayas->disponibilidad_segmentada()!=false){
					}else{ $false_euro="Euro No mapeado";}
				//if($this->comprobarDistribucionEuro()){	
				}else{ $false_euro="Euro Distribucion no permitida";}
			// if($europlayas_[f][0][$estado_comprobar]==1){	
			}else{ $false_euro="Euro Desactivado";}

			$bedsonline_=$this->DB->select($estado_comprobar,'mayoristas','nombre like "BEDSONLINE"');

			if($bedsonline_[f][0][$estado_comprobar]==1){  
				// esto va a desaparecer.
				if($this->comprobarDistribucion()){
			//echo $this->bedsonline->disponibilidad_segmentada();
					if($this->bedsonline->disponibilidad_segmentada()!=false){

						$this->curl->addUrl($this->bedsonline->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->bedsonline->disponibilidad_segmentada($sin_filtros_regimen)));
					//if($this->bedsonline->disponibilidad_segmentada()!=false){
					}else{ $false_beds="Beds No mapeado";}
				// eof if($this->comprobarDistribucion()){	
				}else{ $false_beds="Distribucion no permitida";}
			// eof if($bedsonline_[f][0][$estado_comprobar]==1){  
			}else{ $false_beds="Beds Desactivado"; }


			$new_=$this->DB->select($estado_comprobar,'mayoristas','nombre like "NEW_TRAVELERS"');


			if($new_[f][0][$estado_comprobar]==1){  
				
          
			if($this->versys->disponibilidad_segmentada()!=false){

			        $this->url=$this->dir_versys=$this->versys->url.'OTA_HotelAvail.asmx';

			        $this->curl->addUrl($this->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->versys->disponibilidad_segmentada()));

			    //if($this->versys->disponibilidad_segmentada()!=false){ 
				}else{ $false_new="W2M No mapeado";}
			//if($new_[f][0][$estado_comprobar]==1){
			}else{ $false_new="W2M Desactivado";}

			$solole_=$this->DB->select($estado_comprobar,'mayoristas','nombre like "SOLOLE"');

			if($solole_[f][0][$estado_comprobar]==1){ 

				if($this->solole->disponibilidad_segmentada()!=false){ 

				    $this->url_solole=$this->solole->url.'OTA_HotelAvail.asmx';
					$this->dir_solole=$this->solole->url.'OTA_HotelAvail.asmx';

					$this->curl->addUrl($this->url_solole,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->solole->disponibilidad_segmentada()));
				//if($this->solole->disponibilidad_segmentada()!=false){ 
				}else{ $false_solole="Soloe No mapeado";}
			//if($solole_[f][0][$estado_comprobar]==1){
			}else{ $false_solole="Soloe Desactivado";}
			 

			 $veturis_=$this->DB->select($estado_comprobar,'mayoristas','nombre like "VETURIS"');


			if($veturis_[f][0][$estado_comprobar]==1){  

				if($this->veturis->disponibilidad_segmentada()!=false){
				///////// HEMOS DADO UNOS PASOS EN LA POSIBILIDAD DE PODER 
				// EVALUAR SI TARDA MAS DE LA CUENTA Y NO ESPERAMOS CON LOS DEMAS.
				// ${"inicio".$this->dir_veturis}=microtime(true); //ENTRADA ALA LLAMADA
				
			        $this->curl->addUrl($this->veturis->url,array(CURLOPT_POST=>1,CURLOPT_POSTFIELDS =>$this->veturis->disponibilidad_segmentada(),CURLOPT_RETURNTRANSFER=>1,CURLOPT_SSL_VERIFYPEER => FALSE,  CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>40,CURLOPT_ENCODING =>"gzip"));

				// ${"fin".$this->dir_veturis}=microtime(true); // EXIT DE LA LLAMADA
			    //$total_tiempo=(${"fin".$this->dir_veturis} - ${"inicio".$this->dir_veturis}); // TOTAL DE RESPUESTA
				//echo "el tiempo usado es " . $total_tiempo;

			    // if($this->veturis->disponibilidad_segmentada()!=false){
			    }else{ $false_veturis="Veturis No mapeado";}//else if($this->veturis->disponibilidad_segmentada()==false){
			        	// DEPURACION DE PETICIONES.
			       // 	echo "no hemos lanzado nada";
			       // }
			//if($veturis_[f][0]['estado_booking']==1){
			}else{ $false_veturis="Veturis Desactivado";}


			// damos la respuesta sobre el false que recibimos

			$proveach=array('beds','serhs','euro','new','solole','veturis');

			$mensaje_proveedores='';
			foreach ($proveach as $value) {
			
				if(isset(${'false_'.$value})){
				$this->DB->query("update _xml_peticiones set ".$value."='".${'false_'.$value}."' where id=".$id_xml);

				if($auto_gestion==0){
					// este mensaje se imrpimira en la
					// pantalla de la busqueda del presonal bkng;
					$mensaje_proveedores.=${'false_'.$value}."<br>";
					
				}

				}

			}

			script("$('#resultados_proveedores').html('".$mensaje_proveedores."')");

// 

			$this->curl->wait();
			$resultados=$this->curl->resultado;

			if(count($resultados)>0){
				foreach($resultados as $dir=>$data){

					// if($data[1]["url"]==$this->dir_serhs){

					// 	 $serhs=array(
					// 	 	"tiempo"=>$data[1]['total_time'],
					// 	 	"xml_request"=>htmlspecialchars($this->serhs->disponibilidad_segmentada(), ENT_QUOTES),
					// 	 	"xml_response"=>htmlspecialchars($data[0], ENT_QUOTES)
					// 	 	);
					// 	 $serhs=my_json_encode($serhs);

			 	// 		$this->DB->query("update _xml_peticiones set serhs='".$serhs."' where id=".$id_xml);

			 	// 		// echo "update _xml_peticiones set serhs='".$serhs."' where id=".$id_xml;

					// }else if($data[1]["url"]==$this->dir_europlayas){

					// 	 $euro=array(
					// 	 	"tiempo"=>$data[1]['total_time'],
					// 	 	"xml_request"=>htmlspecialchars($this->europlayas->disponibilidad_segmentada(), ENT_QUOTES),
					// 	 	"xml_response"=>htmlspecialchars($data[0], ENT_QUOTES)
					// 	 	);
					// 	 $euro=my_json_encode($euro);

			 	// 		$this->DB->query("update _xml_peticiones set euro='".$euro."' where id=".$id_xml);

					// }else if($data[1]["url"]==$this->dir_bedsonline){

					// 	 $beds=array(
					// 	 	"tiempo"=>$data[1]['total_time'],
					// 	 	"xml_request"=>htmlspecialchars($this->bedsonline->disponibilidad_segmentada(), ENT_QUOTES),
					// 	 	"xml_response"=>htmlspecialchars($data[0], ENT_QUOTES)
					// 	 	);
					// 	 $beds=my_json_encode($beds);

			 	// 		$this->DB->query("update _xml_peticiones set beds='".$beds."' where id=".$id_xml);

					// }else if($data[1]["url"]==$this->dir_versys){

					// 	 $new=array(
					// 	 	"url"=>$data[1]["url"],
					// 	 	"tiempo"=>$data[1]['total_time'],
					// 	 	"xml_request"=>htmlspecialchars($this->versys->disponibilidad_segmentada(), ENT_QUOTES),
					// 	 	"xml_response"=>htmlspecialchars($data[0], ENT_QUOTES)
					// 	 	);
					// 	 $new=my_json_encode($new);

			 	// 		$this->DB->query("update _xml_peticiones set new='".$new."' where id=".$id_xml);

					// }else if($data[1]["url"]=='http://xml.solole.es/webservice/OTA_HotelAvail.asmx'){

					// 	 $solole=array(
					// 	 	"tiempo"=>$data[1]['total_time'],
					// 	 	"xml_request"=>htmlspecialchars($this->solole->disponibilidad_segmentada(), ENT_QUOTES),
					// 	 	"xml_response"=>htmlspecialchars($data[0], ENT_QUOTES)
					// 	 	);
					// 	 $solole=my_json_encode($solole);

			 	// 		$this->DB->query("update _xml_peticiones set solole='".$solole."' where id=".$id_xml);

					// }else if($data[1]["url"]==$this->dir_veturis && isset($_SESSION['ip_admin'])){

						 // $veturis=array(
						 // 	"tiempo"=>$data[1]['total_time'],
						 // 	"xml_request"=>htmlspecialchars($this->veturis->disponibilidad_segmentada()['xmlRQ'], ENT_QUOTES),
						 // 	"xml_response"=>htmlspecialchars($data[0], ENT_QUOTES)
						 // 	);
						 // $veturis=my_json_encode($veturis);

			 			// $this->DB->query("update _xml_peticiones set veturis='".$data[0]."' where id=".$id_xml);

					//}

					
					$this->listados($dir,$data,$auto_gestion,$sin_filtros_regimen);

				// eof if(count($resultados)>0){	
				}
				
			}else if(count($resultados)==0){
				// aki va a venir la 2ª peticion en caso de 
				// que las peticiones no traigan nada.
			}

			$this->ListadoHoteles=$this->ordenarPrecios($this->ListadoHoteles,array('precioFormat'=>SORT_ASC),1);
			
			foreach($this->ListadoHoteles as $hotel=>$dataOfertas){

				$array2 = $this->ordenarPrecios($dataOfertas['listadoPrecios'],array('totalFormat'=>SORT_ASC));
				$this->ListadoHoteles[$hotel]['listadoPrecios'] = $this->eliminarDuplicados($array2);
				// $this->ListadoHoteles[$hotel]['listadoPrecios']=$array2;

			}

			$this->DB->query('update _xml_peticiones set fin="'.date('Y-m-d H:i:s').'" where id='.$id_xml);
			$_SESSION['id_xml'] = $id_xml;
			return $this->ListadoHoteles;

		// 	public function disponibilidad_segmentada(){
		}
		

		public function disponibilidad_clientes($id_cliente='',$id_booking='',$busqueda='',$auto_gestion=0,$sin_filtros_regimen=0){

			//$this->listadoRegimenes();
			//$this->listadoHabitaciones();

			// insertamos pet_xml para hacer debajo el seguimiento.
			$id_xml=$this->DB->query("insert into _xml_peticiones (
			busqueda,
			dominio,
			id_booking,
			id_cliente,
			creacion) values (
			'".$busqueda."',
			'".$_SERVER['HTTP_HOST']."',
			".$id_booking.",
			".$id_cliente.",
			'".date('Y-m-d H:i:s')."')");


			// añadimos un nuevo historico al booking 
	        $historico = $this->DB->select('historico','booking','id='.$id_booking);
	        $this->DB->openTable('booking');
	        $this->DB->edit($id_booking);
	        $nuevo_historico = sumar_historico_xml($historico[f][0]['historico'],'On-line','busqueda',$id_xml);
	        $this->DB->ta['historico'] = $nuevo_historico;
	        $this->DB->update();


			$serhs_=$this->DB->select('estado_clientes','mayoristas','nombre like "SERHS"');

			if($serhs_[f][0]['estado_clientes']==1){

				if($this->serhs->disponibilidad_segmentada()!=false){

					$this->curl->addUrl($this->serhs->url,array(CURLOPT_SSL_VERIFYPEER => FALSE,  CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip", CURLOPT_POSTFIELDS =>$this->serhs->disponibilidad_segmentada()));
				//if($this->serhs->disponibilidad_segmentada()!=false){
				}else{ $false_serhs="Serhs No mapeado";}
			//if($serhs_[f][0]['estado_clientes']==1){  
			}else{ $false_serhs="Serhs Desactivado";}
							
			$europlayas_=$this->DB->select('estado_clientes','mayoristas','nombre like "EUROPLAYAS"');

			if($europlayas_[f][0]['estado_clientes']==1){	

				if($this->comprobarDistribucionEuro()){

					$this->abrirSession();

					if($this->europlayas->disponibilidad_segmentada()!=false){ // equi es donde se repite el xml

			  			$this->curl->addUrl($this->europlayas->url,array(CURLOPT_SSL_VERIFYPEER => FALSE,  CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip", CURLOPT_POSTFIELDS =>$this->europlayas->disponibilidad_segmentada()));
			  		//if($this->europlayas->disponibilidad_segmentada()!=false){
					}else{ $false_euro="Euro No mapeado";}
				//if($this->comprobarDistribucionEuro()){	
				}else{ $false_euro="Euro Distribucion no permitida";}
			// if($europlayas_[f][0]['estado_clientes']==1){	
			}else{ $false_euro="Euro Desactivado";}

			$bedsonline_=$this->DB->select('estado_clientes','mayoristas','nombre like "BEDSONLINE"');

			if($bedsonline_[f][0]['estado_clientes']==1){  
				// esto va a desaparecer.
				if($this->comprobarDistribucion()){
			//echo $this->bedsonline->disponibilidad_segmentada();
					if($this->bedsonline->disponibilidad_segmentada()!=false){

						$this->curl->addUrl($this->bedsonline->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->bedsonline->disponibilidad_segmentada($sin_filtros_regimen)));
					//if($this->bedsonline->disponibilidad_segmentada()!=false){
					}else{ $false_beds="Beds No mapeado";}
				// eof if($this->comprobarDistribucion()){	
				}else{ $false_beds="Distribucion no permitida";}
			// eof if($bedsonline_[f][0]['estado_clientes']==1){  
			}else{ $false_beds="Beds Desactivado"; }


			$new_=$this->DB->select('estado_clientes','mayoristas','nombre like "NEW_TRAVELERS"');


			if($new_[f][0]['estado_clientes']==1){  
				
					if($this->versys->disponibilidad_segmentada()!=false){

			        $this->url=$this->dir_versys=$this->versys->url.'OTA_HotelAvail.asmx';

			        $this->curl->addUrl($this->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->versys->disponibilidad_segmentada()));
			    //if($this->versys->disponibilidad_segmentada()!=false){ 
				}else{ $false_new="W2M No mapeado";}
			//if($new_[f][0]['estado_clientes']==1){
			}else{ $false_new="W2M Desactivado";}

			$solole_=$this->DB->select('estado_clientes','mayoristas','nombre like "SOLOLE"');

			if($solole_[f][0]['estado_clientes']==1){ 

				if($this->solole->disponibilidad_segmentada()!=false){ 

				    $this->url_solole=$this->solole->url.'OTA_HotelAvail.asmx';
					$this->dir_solole=$this->solole->url.'OTA_HotelAvail.asmx';

					$this->curl->addUrl($this->url_solole,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->solole->disponibilidad_segmentada()));
				//if($this->solole->disponibilidad_segmentada()!=false){ 
				}else{ $false_solole="Soloe No mapeado";}
			//if($solole_[f][0]['estado_clientes']==1){
			}else{ $false_solole="Soloe Desactivado";}
			 

			 $veturis_=$this->DB->select('estado_clientes','mayoristas','nombre like "VETURIS"');


			if($veturis_[f][0]['estado_clientes']==1){  

				if($this->veturis->disponibilidad_segmentada()!=false){
				///////// HEMOS DADO UNOS PASOS EN LA POSIBILIDAD DE PODER 
				// EVALUAR SI TARDA MAS DE LA CUENTA Y NO ESPERAMOS CON LOS DEMAS.
				// ${"inicio".$this->dir_veturis}=microtime(true); //ENTRADA ALA LLAMADA
				
			        $this->curl->addUrl($this->veturis->url,array(CURLOPT_POST=>1,CURLOPT_POSTFIELDS =>$this->veturis->disponibilidad_segmentada(),CURLOPT_RETURNTRANSFER=>1,CURLOPT_SSL_VERIFYPEER => FALSE,  CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>40,CURLOPT_ENCODING =>"gzip"));

				// ${"fin".$this->dir_veturis}=microtime(true); // EXIT DE LA LLAMADA
			    //$total_tiempo=(${"fin".$this->dir_veturis} - ${"inicio".$this->dir_veturis}); // TOTAL DE RESPUESTA
				//echo "el tiempo usado es " . $total_tiempo;

			    // if($this->veturis->disponibilidad_segmentada()!=false){
			    }else{ $false_veturis="Veturis No mapeado";}//else if($this->veturis->disponibilidad_segmentada()==false){
			        	// DEPURACION DE PETICIONES.
			       // 	echo "no hemos lanzado nada";
			       // }
			//if($veturis_[f][0]['estado_clientes']==1){
			}else{ $false_veturis="Veturis Desactivado";}


			// damos la respuesta sobre el false que recibimos

			$proveach=array('beds','serhs','euro','new','solole','veturis');

			foreach ($proveach as $value) {
			
				if(isset(${'false_'.$value})){
				$this->DB->query("update _xml_peticiones set ".$value."='".${'false_'.$value}."' where id=".$id_xml);


				}

			}

			$this->curl->wait();
			$resultados=$this->curl->resultado;

			if(count($resultados)>0){

				foreach($resultados as $dir=>$data){
								
				//$data[0]=>XML RECIBIDO
				//$data[1]=>DATOS HTTP

				//echo "<br><br>" . var_dump($data[1]);
				//echo "<br><br>" .$data[0];
				/// ["total_time"] , $data[0]
						

					// if($data[1]["url"]==$this->dir_serhs){

					// 	 $serhs=array(
					// 	 	"tiempo"=>$data[1]['total_time'],
					// 	 	"xml_request"=>htmlspecialchars($this->serhs->disponibilidad(), ENT_QUOTES),
					// 	 	"xml_response"=>htmlspecialchars($data[0], ENT_QUOTES)
					// 	 	);
					// 	 $serhs=my_json_encode($serhs);

			 	// 		$this->DB->query("update _xml_peticiones set serhs='".$serhs."' where id=".$id_xml);

			 	// 		// echo "update _xml_peticiones set serhs='".$serhs."' where id=".$id_xml;

					// }else if($data[1]["url"]==$this->dir_europlayas && $europlayas_[f][0]['estado_clientes']==1){

					// 	 $euro=array(
					// 	 	"tiempo"=>$data[1]['total_time'],
					// 	 	"xml_request"=>htmlspecialchars($this->europlayas->disponibilidad(), ENT_QUOTES),
					// 	 	"xml_response"=>htmlspecialchars($data[0], ENT_QUOTES)
					// 	 	);
					// 	 $euro=my_json_encode($euro);

			 	// 		$this->DB->query("update _xml_peticiones set euro='".$euro."' where id=".$id_xml);

					// }else if($data[1]["url"]==$this->dir_bedsonline){

					// 	 $beds=array(
					// 	 	"tiempo"=>$data[1]['total_time'],
					// 	 	"xml_request"=>htmlspecialchars($this->bedsonline->disponibilidad(), ENT_QUOTES),
					// 	 	"xml_response"=>htmlspecialchars($data[0], ENT_QUOTES)
					// 	 	);
					// 	 $beds=my_json_encode($beds);

			 	// 		$this->DB->query("update _xml_peticiones set beds='".$beds."' where id=".$id_xml);

					// }else if($data[1]["url"]==$this->dir_versys){

					// 	 $new=array(
					// 	 	"url"=>$data[1]["url"],
					// 	 	"tiempo"=>$data[1]['total_time'],
					// 	 	"xml_request"=>htmlspecialchars($this->versys->disponibilidad(), ENT_QUOTES),
					// 	 	"xml_response"=>htmlspecialchars($data[0], ENT_QUOTES)
					// 	 	);
					// 	 $new=my_json_encode($new);

			 	// 		$this->DB->query("update _xml_peticiones set new='".$new."' where id=".$id_xml);

					// }else if($data[1]["url"]=='http://xml.solole.es/webservice/OTA_HotelAvail.asmx'){

					// 	 $solole=array(
					// 	 	"tiempo"=>$data[1]['total_time'],
					// 	 	"xml_request"=>htmlspecialchars($this->solole->disponibilidad(), ENT_QUOTES),
					// 	 	"xml_response"=>htmlspecialchars($data[0], ENT_QUOTES)
					// 	 	);
					// 	 $solole=my_json_encode($solole);

			 	// 		$this->DB->query("update _xml_peticiones set solole='".$solole."' where id=".$id_xml);

					// }else if($data[1]["url"]==$this->dir_veturis){

					// 	 $veturis=array(
					// 	 	"tiempo"=>$data[1]['total_time'],
					// 	 	"xml_request"=>htmlspecialchars($this->veturis->disponibilidad()['xmlRQ'], ENT_QUOTES),
					// 	 	"xml_response"=>htmlspecialchars($data[0], ENT_QUOTES)
					// 	 	);
					// 	 $veturis=my_json_encode($veturis);

			 	// 		$this->DB->query("update _xml_peticiones set veturis='".$veturis."' where id=".$id_xml);

					// }

					//echo "<br><br>".var_dump($data[1]);
					 // if($data[1]['url']=="http://api.interface-xml.com/appservices/http/FrontendService"){
					 // 	echo "La Url" . $data[0];
					 // }
					
						//error_reporting(E_ALL);
					$this->listados($dir,$data,$auto_gestion,0);

				// eof if(count($resultados)>0){	
				}
				
			}else if(count($resultados)==0){
				// aki va a venir la 2ª peticion en caso de 
				// que las peticiones no traigan nada.
			}

			$this->ListadoHoteles=$this->ordenarPrecios($this->ListadoHoteles,array('precioFormat'=>SORT_ASC),1);
			
			foreach($this->ListadoHoteles as $hotel=>$dataOfertas){
				$array2 = $this->ordenarPrecios($dataOfertas['listadoPrecios'],array('totalFormat'=>SORT_ASC));
				$array2 = $this->eliminarDuplicados($array2);
				$this->ListadoHoteles[$hotel]['listadoPrecios']=$array2;
			}

			$this->DB->query('update _xml_peticiones set fin="'.date('Y-m-d H:i:s').'" where id='.$id_xml);

			$_SESSION['id_xml'] = $id_xml;
			return $this->ListadoHoteles;

		// 	public function disponibilidad(){
		}

public function disponibilidad($id_cliente='',$id_booking='',$busqueda='',$auto_gestion=0){
			//$this->listadoRegimenes();
			//$this->listadoHabitaciones();

			// insertamos pet_xml para hacer debajo el seguimiento.
			$id_xml=$this->DB->query("insert into _xml_peticiones (
			busqueda,
			dominio,
			id_booking,
			id_cliente,
			creacion) values (
			'".$busqueda."',
			'".$_SERVER['HTTP_HOST']."',
			".$id_booking.",
			".$id_cliente.",
			'".date('Y-m-d H:i:s')."')");


			// añadimos un nuevo historico al booking EN AJAX.PHP cuando acaba el envio y lo asocia a
			// la tabla booking_emails

			$serhs_=$this->DB->select('estado_booking','mayoristas','nombre like "SERHS"');

			if($serhs_[f][0]['estado_booking']==1){

				if($this->serhs->disponibilidad()!=false){

					$this->curl->addUrl($this->serhs->url,array(CURLOPT_SSL_VERIFYPEER => FALSE,  CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip", CURLOPT_POSTFIELDS =>$this->serhs->disponibilidad()));
				//if($this->serhs->disponibilidad()!=false){
				}else{ $false_serhs="Serhs No mapeado";}
			//if($serhs_[f][0]['estado_booking']==1){  
			}else{ $false_serhs="Serhs Desactivado";}
							
			$europlayas_=$this->DB->select('estado_booking','mayoristas','nombre like "EUROPLAYAS"');

			if($europlayas_[f][0]['estado_booking']==1){	

				if($this->comprobarDistribucionEuro()){
					$this->abrirSession();
					if($this->europlayas->disponibilidad()!=false){

			  			$this->curl->addUrl($this->europlayas->url,array(CURLOPT_SSL_VERIFYPEER => FALSE,  CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip", CURLOPT_POSTFIELDS =>$this->europlayas->disponibilidad()));
			  		//if($this->europlayas->disponibilidad()!=false){
					}else{ $false_euro="Euro No mapeado";}
				//if($this->comprobarDistribucionEuro()){	
				}else{ $false_euro="Euro Distribucion no permitida";}
			// if($europlayas_[f][0]['estado_booking']==1){	
			}else{ $false_euro="Euro Desactivado";}

			$bedsonline_=$this->DB->select('estado_booking','mayoristas','nombre like "BEDSONLINE"');

			if($bedsonline_[f][0]['estado_booking']==1){  
				// esto va a desaparecer.
				if($this->comprobarDistribucion()){
			//echo $this->bedsonline->disponibilidad();
					if($this->bedsonline->disponibilidad()!=false){

						$this->curl->addUrl($this->bedsonline->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->bedsonline->disponibilidad()));
					//if($this->bedsonline->disponibilidad()!=false){
					}else{ $false_beds="Beds No mapeado";}
				// eof if($this->comprobarDistribucion()){	
				}else{ $false_beds="Distribucion no permitida";}
			// eof if($bedsonline_[f][0]['estado_booking']==1){  
			}else{ $false_beds="Beds Desactivado"; }


			$new_=$this->DB->select('estado_booking','mayoristas','nombre like "NEW_TRAVELERS"');


			if($new_[f][0]['estado_booking']==1){  
				
				if($this->versys->disponibilidad()!=false){

			        $this->url=$this->dir_versys=$this->versys->url.'OTA_HotelAvail.asmx';

			        $this->curl->addUrl($this->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->versys->disponibilidad()));
			    //if($this->versys->disponibilidad()!=false){ 
				}else{ $false_new="W2M No mapeado";}
			//if($new_[f][0]['estado_booking']==1){
			}else{ $false_new="W2M Desactivado";}

			$solole_=$this->DB->select('estado_booking','mayoristas','nombre like "SOLOLE"');

			if($solole_[f][0]['estado_booking']==1){ 

				if($this->solole->disponibilidad()!=false){ 

				    $this->url_solole=$this->solole->url.'OTA_HotelAvail.asmx';
					$this->dir_solole=$this->solole->url.'OTA_HotelAvail.asmx';

					$this->curl->addUrl($this->url_solole,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->solole->disponibilidad()));
				//if($this->solole->disponibilidad()!=false){ 
				}else{ $false_solole="Soloe No mapeado";}
			//if($solole_[f][0]['estado_booking']==1){
			}else{ $false_solole="Soloe Desactivado";}
			 

			 $veturis_=$this->DB->select('estado_booking','mayoristas','nombre like "VETURIS"');


			if($veturis_[f][0]['estado_booking']==1){  

				if($this->veturis->disponibilidad()!=false){
				///////// HEMOS DADO UNOS PASOS EN LA POSIBILIDAD DE PODER 
				// EVALUAR SI TARDA MAS DE LA CUENTA Y NO ESPERAMOS CON LOS DEMAS.
				// ${"inicio".$this->dir_veturis}=microtime(true); //ENTRADA ALA LLAMADA
				
			        $this->curl->addUrl($this->veturis->url,array(CURLOPT_POST=>1,CURLOPT_POSTFIELDS =>$this->veturis->disponibilidad(),CURLOPT_RETURNTRANSFER=>1,CURLOPT_SSL_VERIFYPEER => FALSE,  CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>40,CURLOPT_ENCODING =>"gzip"));

				// ${"fin".$this->dir_veturis}=microtime(true); // EXIT DE LA LLAMADA
			    //$total_tiempo=(${"fin".$this->dir_veturis} - ${"inicio".$this->dir_veturis}); // TOTAL DE RESPUESTA
				//echo "el tiempo usado es " . $total_tiempo;

			    // if($this->veturis->disponibilidad()!=false){
			    }else{ $false_veturis="Veturis No mapeado";}//else if($this->veturis->disponibilidad()==false){
			        	// DEPURACION DE PETICIONES.
			       // 	echo "no hemos lanzado nada";
			       // }
			//if($veturis_[f][0]['estado_booking']==1){
			}else{ $false_veturis="Veturis Desactivado";}


			// damos la respuesta sobre el false que recibimos

			$proveach=array('beds','serhs','euro','new','solole','veturis');

			$mensaje_proveedores='';
			foreach ($proveach as $value) {
			
				if(isset(${'false_'.$value})){
				$this->DB->query("update _xml_peticiones set ".$value."='".${'false_'.$value}."' where id=".$id_xml);

				if($auto_gestion==0){
					// este mensaje se imrpimira en la
					// pantalla de la busqueda del presonal bkng;
					$mensaje_proveedores.=${'false_'.$value}."<br>";
					
				}

				}

			}

			script("$('#resultados_proveedores').html('".$mensaje_proveedores."')");



			$this->curl->wait();
			$resultados=$this->curl->resultado;

			if(count($resultados)>0){
				foreach($resultados as $dir=>$data){


									
				//$data[0]=>XML RECIBIDO
				//$data[1]=>DATOS HTTP

				//echo "<br><br>" . var_dump($data[1]);
				//echo "<br><br>" .$data[0];
				/// ["total_time"] , $data[0]
		

					if($data[1]["url"]==$this->dir_serhs){

						 $serhs=array(
						 	"tiempo"=>$data[1]['total_time'],
						 	"xml_request"=>htmlspecialchars($this->serhs->disponibilidad(), ENT_QUOTES),
						 	"xml_response"=>htmlspecialchars($data[0], ENT_QUOTES)
						 	);
						 $serhs=my_json_encode($serhs);

			 			$this->DB->query("update _xml_peticiones set serhs='".$serhs."' where id=".$id_xml);

			 			// echo "update _xml_peticiones set serhs='".$serhs."' where id=".$id_xml;

					}else if($data[1]["url"]==$this->dir_europlayas && $europlayas_[f][0]['estado_booking']==1){

						 $euro=array(
						 	"tiempo"=>$data[1]['total_time'],
						 	"xml_request"=>htmlspecialchars($this->europlayas->disponibilidad(), ENT_QUOTES),
						 	"xml_response"=>htmlspecialchars($data[0], ENT_QUOTES)
						 	);
						 $euro=my_json_encode($euro);

			 			$this->DB->query("update _xml_peticiones set euro='".$euro."' where id=".$id_xml);

					}else if($data[1]["url"]==$this->dir_bedsonline){

						 $beds=array(
						 	"tiempo"=>$data[1]['total_time'],
						 	"xml_request"=>htmlspecialchars($this->bedsonline->disponibilidad(), ENT_QUOTES),
						 	"xml_response"=>htmlspecialchars($data[0], ENT_QUOTES)
						 	);
						 $beds=my_json_encode($beds);

			 			$this->DB->query("update _xml_peticiones set beds='".$beds."' where id=".$id_xml);

					}else if($data[1]["url"]==$this->dir_versys){

						 $new=array(

						 	"tiempo"=>$data[1]['total_time'],
						 	"xml_request"=>htmlspecialchars($this->versys->disponibilidad(), ENT_QUOTES),
						 	"xml_response"=>htmlspecialchars($data[0], ENT_QUOTES)
						 	);
						 $new=my_json_encode($new);

			 			$this->DB->query("update _xml_peticiones set new='".$new."' where id=".$id_xml);

					}else if($data[1]["url"]=='http://xml.solole.es/webservice/OTA_HotelAvail.asmx'){

						 $solole=array(
						 	"tiempo"=>$data[1]['total_time'],
						 	"xml_request"=>htmlspecialchars($this->solole->disponibilidad(), ENT_QUOTES),
						 	"xml_response"=>htmlspecialchars($data[0], ENT_QUOTES)
						 	);
						 $solole=my_json_encode($solole);

			 			$this->DB->query("update _xml_peticiones set solole='".$solole."' where id=".$id_xml);

					}else if($data[1]["url"]==$this->dir_veturis){

						 $veturis=array(
						 	"tiempo"=>$data[1]['total_time'],
						 	"xml_request"=>htmlspecialchars($this->veturis->disponibilidad()['xmlRQ'], ENT_QUOTES),
						 	"xml_response"=>htmlspecialchars($data[0], ENT_QUOTES)
						 	);
						 $veturis=my_json_encode($veturis);

			 			$this->DB->query("update _xml_peticiones set veturis='".$veturis."' where id=".$id_xml);

					}

					//echo "<br><br>".var_dump($data[1]);
					 // if($data[1]['url']=="http://api.interface-xml.com/appservices/http/FrontendService"){
					 // 	echo "La Url" . $data[0];
					 // }
					
						//error_reporting(E_ALL);
					$this->listados($dir,$data,$auto_gestion);

				// eof if(count($resultados)>0){	
				}
				
			}else if(count($resultados)==0){
				// aki va a venir la 2ª peticion en caso de 
				// que las peticiones no traigan nada.
			}

			$this->ListadoHoteles=$this->ordenarPrecios($this->ListadoHoteles,array('precioFormat'=>SORT_ASC),1);
			
			foreach($this->ListadoHoteles as $hotel=>$dataOfertas){
				$array2 = $this->ordenarPrecios($dataOfertas['listadoPrecios'],array('totalFormat'=>SORT_ASC));
				$array2 = $this->eliminarDuplicados($array2);
				$this->ListadoHoteles[$hotel]['listadoPrecios']=$array2;
			}

			$this->DB->query('update _xml_peticiones set fin="'.date('Y-m-d H:i:s').'" where id='.$id_xml);
			$_SESSION['id_xml'] = $id_xml;
			return $this->ListadoHoteles;

		// 	public function disponibilidad(){
		}


		public function vaciarCesta(){
			$this->curl->addUrl($this->bedsonline->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>40, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->bedsonline->vaciarCesta()));
			$this->curl->wait();
			/*$resultados=$this->curl->resultado;
			if(count($resultados)>0){
				foreach($resultados as $dir=>$data){
					echo print_r($this->xml2array($data[0]));
				}
			}*/
		}

		public function detalles($busqueda){

			$id_cliente_detalles= isset($_SESSION['id_zona_privada']) ? $_SESSION['id_zona_privada'] : $_SESSION['id_cliente_booking'];
        	$id_booking_detalles=isset($_SESSION['id_booking']) ? $_SESSION['id_booking'] : $_SESSION['id_booking_booking'];
			// ".$_SESSION['id_booking'].",
			// ".$_SESSION['id_zona_privada'].",
			$id_detalles=$this->DB->query("insert into _xml_detalles (
			proveedor,
			dominio,
			busqueda,
			id_booking,
			id_cliente,
			id_xml,
			creacion) values (
			'".$this->proveedor."',
			'".$_SERVER['HTTP_HOST']."',
			'".$busqueda."',
			".$id_booking_detalles.",
			".$id_cliente_detalles.",
			".$_SESSION['id_xml'].",
			'".date('Y-m-d H:i:s')."')");



			switch($this->proveedor){
				case "id_serhs":

					$request = $this->serhs->detalles();

					$this->curl->addUrl($this->serhs->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE,CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1,CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->serhs->detalles()));
				break;

				case "id_versys":

					$this->url=$this->versys->url.'OTA_HotelBookingRule.asmx';
                                $this->dir_versys=$this->versys->url.'OTA_HotelBookingRule.asmx';

                    $request = $this->versys->detalles();

					$this->curl->addUrl($this->url,array(CURLOPT_SSL_VERIFYPEER => FALSE,CURLOPT_SSL_VERIFYHOST => FALSE,CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->versys->detalles()));
				break;

 				case "id_solole":
		 			$this->url=$this->solole->url.'OTA_HotelBookingRule.asmx'; 
                 	$this->dir_solole=$this->solole->url.'OTA_HotelBookingRule.asmx';

					$this->curl->addUrl($this->url,array(CURLOPT_SSL_VERIFYPEER => FALSE,CURLOPT_SSL_VERIFYHOST => FALSE,CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->solole->detalles()));
				break;

				case "id_bedsonline":

					$request = $this->bedsonline->detalles();

					$this->curl->addUrl($this->bedsonline->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->bedsonline->detalles()));
				break;
					case "id_europlayas":

					$request = $this->europlayas->detalles();

					$this->curl->addUrl($this->europlayas->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->europlayas->detalles()));
				break;

				case "id_veturis":

					$request = $this->veturis->detalles()['xmlRQ'];

          			$this->curl->addUrl($this->veturis->url,array(CURLOPT_POST=>1,CURLOPT_POSTFIELDS =>$this->veturis->detalles(),CURLOPT_RETURNTRANSFER=>1,CURLOPT_SSL_VERIFYPEER => FALSE,  CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>45,CURLOPT_ENCODING =>"gzip"));
				break;
			}


			$this->curl->wait();
			$resultados=$this->curl->resultado;
			
			
			if(count($resultados)==1){

				foreach($resultados as $dir=>$data){

					$find=array("´","'");

					$xml_detalles=array(
						 	"tiempo"=>$data[1]['total_time'],
						 	"xml_request"=>htmlspecialchars(str_replace($find,"",$request), ENT_QUOTES),
						 	"xml_response"=>tildes_to_html(htmlspecialchars(str_replace($find,"",$data[0]),ENT_QUOTES),1)
						 	);
						 $xml_detalles=my_json_encode($xml_detalles);

			 			$this->DB->query("update _xml_detalles set xml='".$xml_detalles."', fin='".date('Y-m-d H:i:s')."' where id=".$id_detalles);
			 			$_SESSION['id_detalles'] = $id_detalles;

					$detalleOferta=$this->formatearDetalles($dir,$data);
				}
			} // if(count($resultados)==1){
			
			return $detalleOferta;

		} // public function detalles(){
		
		
		public function sacar_gastos(){


			switch($this->proveedor){
				case "id_serhs":
					$this->curl->addUrl($this->serhs->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE,CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1,CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->serhs->detalles()));
				break;

				case "id_versys":
					$this->url=$this->versys->url.'OTA_HotelBookingRule.asmx';
                                $this->dir_versys=$this->versys->url.'OTA_HotelBookingRule.asmx';

					$this->curl->addUrl($this->url,array(CURLOPT_SSL_VERIFYPEER => FALSE,CURLOPT_SSL_VERIFYHOST => FALSE,CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->versys->detalles()));
				break;

 				case "id_solole":
		 			$this->url=$this->solole->url.'OTA_HotelBookingRule.asmx'; 
                 	$this->dir_solole=$this->solole->url.'OTA_HotelBookingRule.asmx';

					$this->curl->addUrl($this->url,array(CURLOPT_SSL_VERIFYPEER => FALSE,CURLOPT_SSL_VERIFYHOST => FALSE,CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->solole->detalles()));
				break;

				case "id_bedsonline":
					$this->curl->addUrl($this->bedsonline->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->bedsonline->detalles()));
				break;
					case "id_europlayas":
					$this->curl->addUrl($this->europlayas->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->europlayas->detalles()));
				break;

				case "id_veturis":

          			 $this->curl->addUrl($this->veturis->url,array(CURLOPT_POST=>1,CURLOPT_POSTFIELDS =>$this->veturis->detalles(),CURLOPT_RETURNTRANSFER=>1,CURLOPT_SSL_VERIFYPEER => FALSE,  CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>45,CURLOPT_ENCODING =>"gzip"));
				break;
			}
			$this->curl->wait();
			$resultados=$this->curl->resultado;
			
			
			if(count($resultados)==1){
				foreach($resultados as $dir=>$data){
					$detalleGastos=$this->formatearGastos($dir,$data);
				}
			}
			
			return $detalleGastos;

		} // public function sacar_gastos(){
		
		
		public function confirmar($id_comunicacion_tpv){

			// añadir aqui el id_detalles y el id_cominicacion tpv
			// todos hay que traerlo por parametros, por que tenemos el paso previo 
			// del tpv y perdemos la session.

        	$data=$this->DB->select("varpost","comunicacion_tpv_reservas","id=".$id_comunicacion_tpv);
        	
           	$row = json_decode(utf8_encode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '',$data[f][0]['varpost'])),true);

			$this->DB->opentable('_xml_confirmaciones');
			$this->DB->ta['proveedor']=$this->proveedor;
			$this->DB->ta['dominio']=$_SERVER['HTTP_HOST'];
			$this->DB->ta['id_booking']=$row['id_booking'];
			$this->DB->ta['id_cliente']=$row['id_cliente'];
			$this->DB->ta['id_xml']=$row['id_xml'];
			$this->DB->ta['id_detalles']=$row['id_detalles'];
			$this->DB->ta['id_comunicacion_tpv']=$id_comunicacion_tpv;
			$this->DB->ta['creacion']=date('Y-m-d H:i:s');
			$id_confirmacion=$_SESSION['id_confirmacion']=$this->DB->insert();

			
			switch($this->proveedor){

				case "id_serhs":
				
					$request = $this->serhs->confirmar();

					$this->curl->addUrl($this->serhs->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE,CURLOPT_TIMEOUT =>60, CURLOPT_POST=>1,CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->serhs->confirmar()));
				break;
				

				case "id_versys":

					$this->url=$this->versys->url.'OTA_HotelRes.asmx';
         			$this->dir_versys=$this->versys->url.'OTA_HotelRes.asmx';

         			$request = $this->versys->confirmar();

					$this->curl->addUrl($this->url,array(CURLOPT_SSL_VERIFYPEER => FALSE,CURLOPT_SSL_VERIFYHOST => FALSE,CURLOPT_TIMEOUT =>60, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml;charset=UTF-8"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->versys->confirmar()));
				
				break;

				case "id_solole":
		 			$this->url=$this->solole->url.'OTA_HotelRes.asmx';
                 	$this->dir_solole=$this->solole->url.'OTA_HotelRes.asmx';
					$this->curl->addUrl($this->url,array(CURLOPT_SSL_VERIFYPEER => FALSE,CURLOPT_SSL_VERIFYHOST => FALSE,CURLOPT_TIMEOUT =>60, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->solole->confirmar()));
				break;

				case "id_bedsonline":

					$request = $this->bedsonline->confirmar();

					$this->curl->addUrl($this->bedsonline->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>60, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->bedsonline->confirmar()));

				break;

				case "id_europlayas":

					$request = $this->europlayas->confirmar();

					$this->curl->addUrl($this->europlayas->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>60, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->europlayas->confirmar()));
				break;

				case "id_veturis":
				
					$request = $this->veturis->confirmar()['xmlRQ'];

           			$this->curl->addUrl($this->veturis->url,array(CURLOPT_POST=>1,CURLOPT_POSTFIELDS =>$this->veturis->confirmar(),CURLOPT_RETURNTRANSFER=>1,CURLOPT_SSL_VERIFYPEER => FALSE,  CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>60,CURLOPT_ENCODING =>"gzip"));

				break;
			}


			$this->curl->wait();
			$resultados=$this->curl->resultado;
			
			if(count($resultados)==1){
				foreach($resultados as $dir=>$data){
					// he metido esta distincion por que misteriosamente no lo c oge 
					// de otra forma.
					if($data[1]["url"]==$this->dir_bedsonline){
						$xml_confirmacion=array(
						 	"tiempo"=>$data[1]['total_time'],
						 	"xml_request"=>htmlspecialchars($this->bedsonline->confirmar(), ENT_QUOTES),
						 	"xml_response"=>htmlspecialchars($data[0], ENT_QUOTES)
						 	);
					}else{
						$xml_confirmacion=array(
						 	"tiempo"=>$data[1]['total_time'],
						 	"xml_request"=>htmlspecialchars(str_replace("´","",$request), ENT_QUOTES),
						 	"xml_response"=>htmlspecialchars($data[0], ENT_QUOTES)
						 	);
					}
					
					$xml_confirmacion=my_json_encode($xml_confirmacion);

			 		$this->DB->query("update _xml_confirmaciones set xml='".$xml_confirmacion."', fin='".date('Y-m-d H:i:s')."' where id=".$id_confirmacion);
					$resultado=$this->formatearDetallesConfirm($dir,$data);
				}
			}
			return $resultado;
			
		} // public function confirmar($id_comunicacion_tpv){
		
		public function infoCancelacion(){


			switch($this->proveedor){
				case "id_serhs":
					$this->curl->addUrl($this->serhs->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE,CURLOPT_TIMEOUT =>35, CURLOPT_POST=>1,CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->serhs->infoCancelacion()));
				break;

				case "id_versys":
				 	 $this->url=$this->versys->url.'OTA_Cancel.asmx';
         			$this->dir_versys=$this->versys->url.'OTA_Cancel.asmx';
					$this->curl->addUrl($this->url,array(CURLOPT_SSL_VERIFYPEER => FALSE,CURLOPT_SSL_VERIFYHOST => FALSE,CURLOPT_TIMEOUT =>35, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->versys->infoCancelacion()));
				break;

				case "id_solole":
		 			$this->url=$this->solole->url.'OTA_Cancel.asmx';
                 	$this->dir_solole=$this->solole->url.'OTA_Cancel.asmx';
					$this->curl->addUrl($this->url,array(CURLOPT_SSL_VERIFYPEER => FALSE,CURLOPT_SSL_VERIFYHOST => FALSE,CURLOPT_TIMEOUT =>35, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->solole->infoCancelacion()));
				break;

				case "id_bedsonline":
					$this->curl->addUrl($this->bedsonline->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>35, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->bedsonline->infoCancelacion()));
				break;

				case "id_europlayas":

                  	$this->abrirSession();
					$this->curl->addUrl($this->europlayas->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>35, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->europlayas->infoCancelacion()));
				break;


				case "id_veturis":
          			 $this->curl->addUrl($this->veturis->url,array(CURLOPT_POST=>1,CURLOPT_POSTFIELDS =>$this->veturis->infoCancelacion(),CURLOPT_RETURNTRANSFER=>1,CURLOPT_SSL_VERIFYPEER => FALSE,  CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>45,CURLOPT_ENCODING =>"gzip"));
				break;
			}
			$this->curl->wait();
			$resultados=$this->curl->resultado;
			
			if(count($resultados)==1){
				foreach($resultados as $dir=>$data){
					$resultado=$this->formatearInfoCancelacion($dir,$data);
				}
			}
                       // echo $resultado;
			return $resultado;
		} // public function infoCancelacion(){


		public function cancelar($id_cliente=0){

			// cuando no tenemos el id_zona_privada es que estamos haciendo desde booking
			// y en este caso le vamos a pasar al id por paramentro a la cancelacion.
			
			$id_cliente_cancelar = isset($_SESSION['id_zona_privada']) ? $_SESSION['id_zona_privada'] : $id_cliente;
			$this->DB->opentable('_xml_cancelaciones');
			$this->DB->ta['proveedor']=$this->proveedor;
			$this->DB->ta['dominio']=$_SERVER['HTTP_HOST'];
			$this->DB->ta['id_reserva_realizada']=$this->parametros['id_reserva_cancelar'];
			$this->DB->ta['id_cliente']=$id_cliente_cancelar;
			$this->DB->ta['creacion']=date('Y-m-d H:i:s');
			$_SESSION['id_cancelacion']=$id_cancelacion=$this->DB->insert();

			switch($this->proveedor){

				case "id_serhs":

					$request = $this->serhs->cancelar();

					$this->curl->addUrl($this->serhs->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE,CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1,CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->serhs->cancelar()));
				break;

				case "id_versys":

					$request = $this->versys->cancelar();

				 	$this->url=$this->versys->url.'OTA_Cancel.asmx';
                                 $this->dir_versys=$this->versys->url.'OTA_Cancel.asmx';
					$this->curl->addUrl($this->url,array(CURLOPT_SSL_VERIFYPEER => FALSE,CURLOPT_SSL_VERIFYHOST => FALSE,CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->versys->cancelar()));
				break;

				case "id_solole":
				 	$this->url=$this->solole->url.'OTA_Cancel.asmx';
                                 $this->dir_solole=$this->solole->url.'OTA_Cancel.asmx';
					$this->curl->addUrl($this->url,array(CURLOPT_SSL_VERIFYPEER => FALSE,CURLOPT_SSL_VERIFYHOST => FALSE,CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->solole->cancelar()));
				break;

				case "id_bedsonline":

					$request = $this->bedsonline->cancelar();

					$this->curl->addUrl($this->bedsonline->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->bedsonline->cancelar()));
				break;

				case "id_europlayas":

					$request = $this->europlayas->cancelar();

					$this->curl->addUrl($this->europlayas->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->europlayas->cancelar()));
				break;

				case "id_veturis":

					$request = $this->veturis->cancelar()['xmlRQ'];

          			$this->curl->addUrl($this->veturis->url,array(CURLOPT_POST=>1,CURLOPT_POSTFIELDS =>$this->veturis->cancelar(),CURLOPT_RETURNTRANSFER=>1,CURLOPT_SSL_VERIFYPEER => FALSE,  CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>45,CURLOPT_ENCODING =>"gzip"));
				break;

			} // switch($this->proveedor){

			$this->curl->wait();
			$resultados=$this->curl->resultado;

			if(count($resultados)==1){

				foreach($resultados as $dir=>$data){

					$_xml_cancelaciones= array(
						 	"tiempo"=>$data[1]['total_time'],
						 	"xml_request"=>htmlspecialchars(str_replace("´","",$request), ENT_QUOTES),
						 	"xml_response"=>htmlspecialchars($data[0], ENT_QUOTES)
						 	);

						$_xml_cancelaciones=my_json_encode($_xml_cancelaciones);

			 		$this->DB->query("update _xml_cancelaciones set xml='".$_xml_cancelaciones."', fin='".date('Y-m-d H:i:s')."' where id=".$id_cancelacion);

					$resultado=$this->formatearCancelacion($dir,$data);

				} // foreach($resultados as $dir=>$data){
			} // if(count($resultados)==1){

			return $resultado;

		} // public function cancelar(){


		/////////aqui tenemos el inicio, ignoracion y cierre de Session Europlayas

        public function abrirSession(){

			$this->curl->addUrl($this->europlayas->url,array(CURLOPT_SSL_VERIFYPEER => FALSE,  CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>40, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip", CURLOPT_POSTFIELDS =>$this->europlayas->abrirSession()));

        	$this->curl->wait();
            $resultados=$this->curl->resultado;
           
            if(count($resultados)>0){
                foreach($resultados as $dir=>$data){
                    $resultado=$this->xml2array($data[0]);
                }
            }

            $_SESSION['sessionEuro']=$resultado['SesionAbrirRespuesta']['ideses']['value'];

            return $_SESSION['sessionEuro'];

		} // public function abrirSession(){


		public function SessionIgnorar(){

			$this->curl->addUrl($this->europlayas->url,array(CURLOPT_SSL_VERIFYPEER => FALSE,  CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>40, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip", CURLOPT_POSTFIELDS =>$this->europlayas->SessionIgnorar()));

				$this->curl->wait();
		        $resultados=$this->curl->resultado;
		            
		    if(count($resultados)==1){
		        foreach($resultados as $dir=>$data){
                    $arrayRespuesta=$this->xml2array($data[0]);
	            }
		    }

		    $resultado=$arrayRespuesta['SesionIgnorarRespuesta']['ideses']['value'];

		    return $resultado;

		} // public function SessionIgnorar(){


		public function SessionCerrar(){

			$this->curl->addUrl($this->europlayas->url,array(CURLOPT_SSL_VERIFYPEER => FALSE,  CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>40, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip", CURLOPT_POSTFIELDS =>$this->europlayas->SessionCerrar()));

			$this->curl->wait();
		    $resultados=$this->curl->resultado;
		            
		    if(count($resultados)==1){
		        foreach($resultados as $dir=>$data){
		            $arrayRespuesta=$this->xml2array($data[0]);                 
		        }
		    }

			$resultado=$arrayRespuesta['SesionCerrarRespuesta']['ideses']['value'];


		    return $resultado;

		} // public function SessionCerrar(){ 
		
		///////// eof el inicio, ignoracion y cierre de Session Europlayas

		public function modificar(){
			switch($this->proveedor){
				case "id_serhs":
					$this->curl->addUrl($this->serhs->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE,CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1,CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->serhs->modificar()));
				break;
				case "id_versys":
					//
				break;
				case "id_bedsonline":
					$this->curl->addUrl($this->bedsonline->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>40, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->bedsonline->modificar()));
				break;
			}
			$this->curl->wait();
			$resultados=$this->curl->resultado;
			
			if(count($resultados)==1){
				foreach($resultados as $dir=>$data){
					$resultado=$this->formatearModificar($dir,$data);
				}
			}
			return $resultado;
		} // public function modificar(){
		
		public function listadoReservas($desde,$hasta){

			// switch($proveedor){

			// 	case "id_bedsonline":

					$this->curl->addUrl($this->bedsonline->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>40, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->bedsonline->listadoReservas($desde,$hasta)));
	
				// break;

				// case "id_serhs":

					$this->curl->addUrl($this->serhs->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE,CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1,CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->serhs->listadoReservas($desde,$hasta)));

				// break;

				// case "id_veturis":

          			$this->curl->addUrl($this->veturis->url,array(CURLOPT_POST=>1,CURLOPT_POSTFIELDS =>$this->veturis->listadoReservas($desde,$hasta),CURLOPT_RETURNTRANSFER=>1,CURLOPT_SSL_VERIFYPEER => FALSE,  CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>45,CURLOPT_ENCODING =>"gzip"));
				// break;

				// case "id_europlayas":

					$this->curl->addUrl($this->europlayas->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE,CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1,CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->europlayas->listadoReservas($desde,$hasta)));

			// 	break;
					
 					$this->url=$this->dir_versys=$this->versys->url.'OTA_Read.asmx';

			        $this->curl->addUrl($this->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->versys->listadoReservas($desde,$hasta)));

			// }		

			$this->curl->wait();
			$resultados=$this->curl->resultado;
			
			foreach($resultados as $dir=>$data){

				
				$this->formatearListadoReservas($dir,$this->xml2array($data[0]));

			}

			return $this->ListadoReservas;

		} // public function listadoReservas(){
		
		
		public function DetalleReserva(){

			switch($this->proveedor){

				case "id_serhs":

					$this->curl->addUrl($this->serhs->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE,CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1,CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->serhs->DetalleReserva()));

				break;
				
				case "id_versys":

					$this->url=$this->dir_versys=$this->versys->url.'OTA_Read.asmx';
          		
					$this->curl->addUrl($this->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->versys->DetalleReserva()));
				break;

				case "id_veturis":

					$this->curl->addUrl($this->veturis->url,array(CURLOPT_POST=>1,CURLOPT_POSTFIELDS =>$this->veturis->DetalleReserva(),CURLOPT_RETURNTRANSFER=>1,CURLOPT_SSL_VERIFYPEER => FALSE,  CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>40,CURLOPT_ENCODING =>"gzip"));

				break;
				
				case "id_bedsonline":

					$this->curl->addUrl($this->bedsonline->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>40, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->bedsonline->DetalleReserva()));

				break;

				case "id_europlayas":

					$this->curl->addUrl($this->europlayas->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>40, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->europlayas->DetalleReserva()));

				break;

			}
			
			$this->curl->wait();
			$resultados=$this->curl->resultado;
			
			if(count($resultados)==1){
				foreach($resultados as $dir=>$data){

					//echo $data[0];

					//$this->DB->query("insert into _xml_confirmaciones (xml) values ('".$data[0]."')");

					$resultado=$this->formatearDetalleReserva($dir,$this->xml2array($data[0]));
				}
			}
			return $resultado;
			
		} // public function DetalleReserva(){
		
		############################### SECCION DE FORMATEO DE RESULTADOS
		#
			#
			# listados => listados ofertas hotelk
			# formatearGastos => formatearnos los gastos que sacamos a los listados
			# formatearDetalles => formateamos los detalles
			#
			#

		#

		public function listados($dir,$data,$auto_gestion=0,$sin_filtros_regimen=0){


			// metengo que montar el tipo de destino y pasarlo
			// por parametro y listo.


			switch($dir){

				case $this->dir_serhs:
                    		

				//	////
				// Esto hay que volver a montarlo todo, 
				// cerciorandonos de que coga 1 solo resultado.
				//
				//
					$resultado=$this->xml2array($data[0]);

					if(isset($resultado['response']['status']['value']) && $resultado['response']['status']['value']!='0' || count($resultado)==0 || !isset($resultado['response']['accommodations']['accommodation'])){
						// $this->registrarError(1,$data,$this->serhs->disponibilidad());

						if($auto_gestion==0){
							script("$('#resultados_proveedores').append('Serhs Sin disponibilidad o debido a un error<br> ')");

                  			echo "<br> ";
						}
						break;
					}
					
					$comision=$this->serhs->comision;
					$this->sql_listados = 'select id,estado from _hoteles where id_serhs like ?';	
					$resultado=$resultado['response']['accommodations']['accommodation'];

					// esta mejora es para eliminar directamente todos los resultados que hayan dado error
					// bien en los detalles o en las confirmaciones.
					// $consultar_error=0;
					// $this->DB->query('select id from quitar_del_listado where proveedor like "id_serhs"');
					// if($this->DB->rows()>0){
					// 	$consultar_error=1;
					// }
					
					if(isset($resultado['attr']['name']) && strlen($resultado['attr']['name'])>0){
						$hoteles[0]=$resultado;
					}else{
						$hoteles=$resultado;
					}

					if($auto_gestion==0){
						script("$('#resultados_proveedores').append('Serhs trae ". count($hoteles) ." Hoteles en ".$data[1]['total_time']." Segundos<br> ')"); 
					}

					for($i=0;$i<count($hoteles);$i++){
						$datos=array();
						$habitaciones=array();
							if(isset($hoteles[$i]['concepts']['concept']['attr']['name'])){
						if(strlen($hoteles[$i]['concepts']['concept']['attr']['name'])>0)
							$habitaciones[0]=$hoteles[$i]['concepts']['concept'];
							} 
						else
							$habitaciones=$hoteles[$i]['concepts']['concept'];
						
						$cont=0;
						for($x=0;$x<count($habitaciones);$x++){ 
							$regimenes=array();
							if(isset($habitaciones[$x]['boards']['board']['attr']['name'])){
							if(strlen($habitaciones[$x]['boards']['board']['attr']['name'])>0)
								$regimenes[0]=$habitaciones[$x]['boards']['board'];
							}
							else
								$regimenes=$habitaciones[$x]['boards']['board'];
				

							for($xx=0;$xx<count($regimenes);$xx++){

								$abrev = array(
										"BB"=>"AD",
										"HB"=>"MP",
										"FB"=>"PC",
										"ON"=>"SA",
										"SC"=>"SA",
										"AI"=>"TI"
									);


								$abreviatura = isset($abrev[$regimenes[$xx]['attr']['code']]) ? $abrev[$regimenes[$xx]['attr']['code']] : "SA";

								if($abreviatura==""){

									$abreviatura="SA";
								}

								
								$mostrar=0;

								if(isset($sin_filtros_regimen) && $sin_filtros_regimen==1){

									$mostrar=1;

								}else{

									if($this->parametros['regimen']!='0' &&
									 strpos($this->parametros['regimen'],"-")){
				
									//// BUSQUEDAS MULTYREGIMEN
										// 1º evaluo el destino:
										//2º compruebo que el regimen este contenido en los validos
										//3º asigno mostrar


										if($this->parametros['destino']==1){
										
											$buscar='no_buscar';
											if($regimenes[$xx]['attr']['code']=='HB'){
												$buscar='MP';
											}else if($regimenes[$xx]['attr']['code']=='FB'){
												$buscar='PC';
											}else if($regimenes[$xx]['attr']['code']=='AI'){
												$buscar='TI';
									   		}

									   		$comprobar_regimen=strpos($this->parametros['regimen'],$buscar);

									   		if($comprobar_regimen!==false){
									   			$mostrar=1;
									   		}

									   	// if($this->parametros['destino']==1){
										}if($this->parametros['destino']==2){
											
											$buscar='no_buscar';
											if($regimenes[$xx]['attr']['code']=='BB'){
												$buscar='AD';
											}else if($regimenes[$xx]['attr']['code']=='ON'){
												$buscar='SA';
											}

											$comprobar_regimen=strpos($this->parametros['regimen'],$buscar);

									   		if($comprobar_regimen!==false){
									   			$mostrar=1;
									   		}
									   	// }if($this->parametros['destino']==2){
										}


									// eof }else if($this->parametros['regimen']!='0'&&
									// strpos($this->parametros['regimen'],"-"){
									}else if(($this->parametros['regimen']=="AD" && $regimenes[$xx]['attr']['code']!='BB') || ($this->parametros['regimen']=="MP" && $regimenes[$xx]['attr']['code']!='HB') || ($this->parametros['regimen']=="PC" && $regimenes[$xx]['attr']['code']!='FB')|| ($this->parametros['regimen']=="SA" && $regimenes[$xx]['attr']['code']!='ON') || ($this->parametros['regimen']=="TI" && $regimenes[$xx]['attr']['code']!='AI')){
										// solucion para 1 solo filtro de regiomen

	                           				$mostrar=0;

	                        		}else if($this->parametros['destino']==1 &&
									 ($regimenes[$xx]['attr']['code']=='HB' ||
									  $regimenes[$xx]['attr']['code']=='FB' ||
									   $regimenes[$xx]['attr']['code']=='AI')){

										$mostrar=1;

									}else if($this->parametros['destino']==2 &&
									 ($regimenes[$xx]['attr']['code']=='BB' ||
									  $regimenes[$xx]['attr']['code']=='ON')){


										$mostrar=1;
									}

								} // if(isset($sin_filtros_regimen) && ...

								
								if($mostrar==1){

									$cont++;
									$datos['precios'][$cont]['abrevProv']="SH";
									$datos['codeHotel']=$hoteles[$i]['attr']['code'];
									$datos['precios'][$cont]['codeHotel']=$hoteles[$i]['attr']['code'];
									$datos['precios'][$cont]['precio']=$regimenes[$xx]['price']['attr']['amount'];
									
									$datos['precios'][$cont]['precioFormat']=numFormatoMysql($regimenes[$xx]['price']['attr']['amount']);
									$datos['precios'][$cont]['totalFormat']=(($datos['precios'][$cont]['precioFormat']*$comision)/100) + $datos['precios'][$cont]['precioFormat'];
									$datos['precios'][$cont]['total']=numFormato($datos['precios'][$cont]['totalFormat']);

									$datos['precios'][$cont]['comision']=$comision;
									$datos['precios'][$cont]['unique']=$habitaciones[$x]['attr']['code'];
									
									// seccion de precios recomendados
									$find=array(".",",");
									$chng=array("",".");

								
									$datos['precios'][$cont]['recomendado']=str_replace($find,$chng,$datos['precios'][$cont]['total']);

									if($this->parametros['destino']==1){

										$porcentaje = 	($datos['precios'][$cont]['recomendado']*25)/100;
									}else if($this->parametros['destino']==2){
										$porcentaje = ($datos['precios'][$cont]['recomendado']*10)/100;
									}

									$datos['precios'][$cont]['recomendado']=$datos['precios'][$cont]['recomendado']+$porcentaje;

									$datos['precios'][$cont]['nombreHab']=str_replace(".","",$habitaciones[$x]['attr']['name']);
									$datos['precios'][$cont]['codeHab']=$habitaciones[$x]['attr']['standardCode'];
									$datos['precios'][$cont]['concepto']=$habitaciones[$x]['attr']['code'];
									
									$datos['precios'][$cont]['cancelPolicyId']=$regimenes[$xx]['attr']['cancelPolicyId'];
									
									// voy a meter aki los gastos de cancelacion:
									// este es el array donde debo girar:
									if(isset($habitaciones[$x]['cancelPolicies']['cancelPolicy'][0]['id']['value'])){
										
										$n_gastos = count($habitaciones[$x]['cancelPolicies']['cancelPolicy']);
										
									}else if(!isset($habitaciones[$x]['cancelPolicies']['cancelPolicy'][0]['id']) && isset($habitaciones[$x]['cancelPolicies']['cancelPolicy']['attr']['id'])){
										$n_gastos=1;
										$habitaciones[$x]['cancelPolicies']['cancelPolicy'][0]=$habitaciones[$x]['cancelPolicies']['cancelPolicy'];
									
									}else{

										$n_gastos=0;
									}

								
									// tenemos que encontrar el cancelPolicyId que pertenece a la
									// dsitribucion, interpretarlo:
									// type: Tipo de cálculo. Los valores posibles serían:
									// 1: Porcentaje
									// 2: Importe
									// 3: Días.
									// value: Valor a aplicar.

									$gastos_hb[$x]='';

									if($n_gastos>0){

										for($clx=$n_gastos;$clx>=0;$clx--){


											if(isset($habitaciones[$x]['cancelPolicies']['cancelPolicy'][$clx]['attr']['id']) && isset($datos['precios'][$cont]['cancelPolicyId']) && $habitaciones[$x]['cancelPolicies']['cancelPolicy'][$clx]['attr']['id']==$datos['precios'][$cont]['cancelPolicyId']){

										//Vamos a conert a string 
										// puede resultar + de 1 gasto por hb.
											

											$fecha =formatear_fecha($habitaciones[$x]['cancelPolicies']['cancelPolicy'][$clx]['attr']['releaseDate'],x,i);
											$gastos_hb[$x].='Desde '.$fecha;

											$hora=$habitaciones[$x]['cancelPolicies']['cancelPolicy'][$clx]['attr']['releaseHour'];

											if($hora!=''){
												$gastos_hb[$x].=' a las '.$hora[0].$hora[1].':'.$hora[2].$hora[3];
											}	


											$type=$habitaciones[$x]['cancelPolicies']['cancelPolicy'][$clx]['attr']['type'];

											$value_gastos=$habitaciones[$x]['cancelPolicies']['cancelPolicy'][$clx]['attr']['value'];

											if($type==1){

											$gastos_hb[$x].=' el '.$value_gastos.'% <br>';

											}else if($type==2){


												$value_gastos = (INT) $value_gastos;
												$porcentaje = $value_gastos*$comision/100;
												$importe = $value_gastos+$porcentaje;

												$gastos_hb[$x].=' el importe de '.$value_gastos.'€ <br>';

											}else if($type==3){


												$gastos_hb[$x].=' un total de '. $value_gastos[0].' dias <br>';
											}
											// 1: Porcentaje
											// 2: Importe
											// 3: Días.
		 
											}

										} // for($clx=$n_gastos;$clx>=0;$clx--){

									}else{

										$gastos_hb[$x]='Desde '.date('d/m/Y').' el 100,00%';

									} // if($n_gastos>0){

									$datos['precios'][$cont]['gastos']=$gastos_hb[$x];

									$datos['precios'][$cont]['offer']='';
									if(isset($regimenes[$xx]['offer']['attr']['name'])){
									$datos['precios'][$cont]['offer']=$regimenes[$xx]['offer']['attr']['name'];
									}
									$datos['precios'][$cont]['nombreReg']=$regimenes[$xx]['attr']['name'];
									$datos['precios'][$cont]['abrevReg']=$abreviatura;
									$datos['precios'][$cont]['codeReg']=$regimenes[$xx]['attr']['code'];
									$datos['precios'][$cont]['ticket']=$regimenes[$xx]['attr']['ticket'];
									$datos['precios'][$cont]['proveedor']='id_serhs';
									
									$datos['proveedor']='id_serhs';

									if(isset($datos['precioFormat'])){
										$precioformat=$datos['precioFormat'];
										}else{
											$precioformat="";
											}
									if(($precioformat > $datos['precios'][$cont]['totalFormat']) || $cont==1){
										$datos['precio']=$datos['precios'][$cont]['total'];
										$datos['precioFormat']=$datos['precios'][$cont]['totalFormat'];
									}
								}
								
							}
						}


						// if(isset($_SESSION['ip_admin'])){

							$this->preparar_sql = $this->DB->link->prepare($this->sql_listados);
							$this->preparar_sql->bind_param('s', $datos['codeHotel']);
							$this->arrayHoteles($datos);
							$this->preparar_sql->close();

						// }else{

						// 	$this->arrayHoteles($datos);

						// }

					}
				break;
		

				case $this->dir_versys:
				

					$resultado=$this->xml2array($data[0]);

					$resultado = $resultado['soap:Envelope']['soap:Body']['OTA_HotelAvailServiceResponse']['OTA_HotelAvailRS'];

					if(isset($resultado['Errors']['ErrorType']) || count($resultado)==0){

						if($auto_gestion==0){

							script("$('#resultados_proveedores').append(' W2M Sin disponibilidad o debido a un error <br>')");


						}

						break;
					    
					}
					//aqui cojemos la comision.
					$comision=$this->versys->comision;
					$this->sql_listados = 'select id,estado from _hoteles where id_versys like ?';

					// aqui vemos si tenemos el listado con disponibilidad y 
					// bajamos la variable $resultados a el listado
					if(isset($resultado['RoomStays'])){

						$SequenceNmbr=$resultado['attr']['SequenceNmbr'];

						if(isset($resultado['RoomStays']['RoomStay'][0])){
		
							$resultado = $resultado['RoomStays']['RoomStay'];
							$hoteles=$resultado;
							
						}else if(!isset($resultado['RoomStays']['RoomStay'][0])){

                    		$hoteles[0]=$resultado['RoomStays'];
		
						}

					} // if(isset($resultado['RoomStays'])){

/*
		<Warnings>
						<WarningType Type="enFORMATERROR" ShortText=" Code [JP831903, JP607337, JP743810, JP654288, JP030107, JP829801, JP073233, JP066719, JP089883, JP192949, JP099510, JP362872, JP673161, JP167818, JP372039, JP814830, JP708345, JP194755, JP001485, JP195530, JP609090, JP096891, JP255965, JP794594, JP152691, JP082711, JP710385, JP906945, JP098546, JP639206, JP340059, JP708401, JP099378, JP662294, JP120923, JP269925, JP394538, JP460230, JP284047, JP758640, JP824952, JP666358, JP851970, JP683753, JP001618, JP059053, JP124337, JP149689, JP006024, JP182779, JP671559, JP230028, JP058750, JP033169, JP000306, JP219061, JP532441, JP020684, JP487489] is not valid" /></Warnings>

*/


//echo "<br> Los hoteles que tenemos son " . count($hoteles);
               // aqui lo que queremos es contar el nº de hoteles que tenemos.
					if($auto_gestion==0){
						script("$('#resultados_proveedores').append('W2M trae " . count($hoteles). "  Hoteles en ".$data[1]['total_time']." Segundos<br> ')");
						
					}

					for($i=0;$i<count($hoteles);$i++){ //este for es el de los hoteles.
						$datos=array();
						$habitaciones=array();

						// if($auto_gestion==0 && isset($hoteles[$i]['BasicPropertyInfo']['attr']['HotelCode'])){

		    //          		$code_new_hotel = $hoteles[$i]['BasicPropertyInfo']['attr']['HotelCode'];

			   //            	$comprobacion_hotel = $this->DB->select('id','_hoteles','id_versys like "'.$code_new_hotel.'"');
			   //            	if($comprobacion_hotel[r]==0){
			   //            		//echo '<br> el hotel '.$code_new_hotel.' no esta mapeado';
			   //            	}
						// }

						if(count($hoteles)>1){


							 		//$habitaciones=$hoteles[$i]['RoomRates']['RoomRate'];

							if (isset($hoteles[$i]['RoomRates']['RoomRate'][0])){

								$habitaciones=$hoteles[$i]['RoomRates']['RoomRate'];

							}else if(!isset($hoteles[$i]['RoomStay']['RoomRates']['RoomRate'][0])){

								$habitaciones[0]=$hoteles[$i]['RoomRates'];

							} // if (isset($hoteles[$i]['RoomRates']['RoomRate'][0])){

							$hot=2;

						}else if(count($hoteles)==1){ // if(count($hoteles)>1){

							$hot=1;

							if (isset($hoteles[$i]['RoomStay']['RoomRates']['RoomRate'][0])){

								$habitaciones=$hoteles[$i]['RoomStay']['RoomRates']['RoomRate'];

							}else if(!isset($hoteles[$i]['RoomStay']['RoomRates']['RoomRate'][0])){

								$habitaciones[0]=$hoteles[$i]['RoomStay']['RoomRates'];
							}

						}	// if(count($hoteles)>1){				

						$cont=0;

						for($x=0;$x<count($habitaciones);$x++){ // este for contienen las habitaciones.
						

							//_JuniperMealplan
										
							

							if($hot==2 && isset($hoteles[$i]['RoomRates']['RoomRate'][0]) || $hot==1 && isset($hoteles[$i]['RoomStay']['RoomRates']['RoomRate'][0])){

								$idregimenes='';
								$codigoregimen='';

								if(isset($habitaciones[$x]['TPA_Extensions']['Mealplan']['value'])){
									$idregimenes=$habitaciones[$x]['TPA_Extensions']['Mealplan']['value'];	
								}

								if(isset($habitaciones[$x]['TPA_Extensions']['Mealplan']['value'])){
									$codigoregimen=$habitaciones[$x]['TPA_Extensions']['Mealplan']['value'];
								}


								
							}else if($hot==1 && !isset($hoteles[$i]['RoomStay']['RoomRates']['RoomRate'][0]) || $hot==2 && !isset($hoteles[$i]['RoomRates']['RoomRate'][0])){

								$idregimenes='';
								$codigoregimen='';

								if(isset($habitaciones[$x]['RoomRate']['TPA_Extensions']['Mealplan']['value'])){
									$idregimenes=$habitaciones[$x]['RoomRate']['TPA_Extensions']['Mealplan']['value'];	
								}
								if(isset($habitaciones[$x]['RoomRate']['TPA_Extensions']['Mealplan']['value'])){
									$codigoregimen=$habitaciones[$x]['RoomRate']['TPA_Extensions']['Mealplan']['value'];	
								}


							}	


							//echo "<br> el id es ". $idregimenes;

							// parchetazo puntual 
							if($idregimenes==''){

								$regimenes="SA";

							}else if($idregimenes!=''){

								$abrev = array(
										"0"=>"SA",
										"2"=>"AD",
										"1"=>"MP",
										"4"=>"PC",
										"3"=>"SA",
										"5"=>"TI",
										"6"=>"SA"
									);

								$regimenes=$abrev[$idregimenes];

								if($regimenes==""){

									$regimenes="SA";
								}
								
							}
								

							//$codigoregimen=$habitaciones[$x]['TPA_Extensions']['Mealplan']['attr']['id'];
							//$regimenes=$habitaciones[$x]['TPA_Extensions']['Mealplan']['attr']['Category'];


							if($this->parametros['habitacion']==1){

								if(isset($habitaciones[$x]['Rates']['Rate']['RateDescription']['Text']['value'])){
									$nombrehabitacion=$habitaciones[$x]['Rates']['Rate']['RateDescription']['Text']['value'];
								}else if(isset($habitaciones[$x]['RoomRate']['Rates']['Rate']['RateDescription']['Text']['value'])){
									$nombrehabitacion=$habitaciones[$x]['RoomRate']['Rates']['Rate']['RateDescription']['Text']['value'];
								}

								if(isset($habitaciones[$x]['Rates']['Rate']['RateDescription']['Text']['value'])){
									$nombreHbPost=$habitaciones[$x]['Rates']['Rate']['RateDescription']['Text']['value'];
								}else if(isset($habitaciones[$x]['RoomRate']['Rates']['Rate']['RateDescription']['Text']['value'])){
									$nombreHbPost=$habitaciones[$x]['RoomRate']['Rates']['Rate']['RateDescription']['Text']['value'];
								}


							//$nombrehabitacion=$habitaciones[$x]['Rates']['Rate']['RateDescription']['Text']['value'];
							//$nombreHbPost=$habitaciones[$x]['Rates']['Rate']['RateDescription']['Text']['value'];

							}else{

								if(isset($habitaciones[$x]['Rates']['Rate'][0]['RateDescription']['Text']['value'])){
								  $nombrehabitacion=$habitaciones[$x]['Rates']['Rate'][0]['RateDescription']['Text']['value']; 
								}
									$numberHabit=$this->parametros['habitacion'];

								for($h=0;$h<$numberHabit;$h++){

									if(isset($habitaciones[$x]['Rates']['Rate'][$h]['RateDescription']['Text']['value'])){
										$nombreHbPost[$h]=$habitaciones[$x]['Rates']['Rate'][$h]['RateDescription']['Text']['value'];
									}

								}

							}



							if(isset($habitaciones[$x]['TPA_Extensions']['NettPrice']['value'])){

								$precioregimenes=str_replace(".",",",$habitaciones[$x]['TPA_Extensions']['NettPrice']['value']);

							}else if(isset($habitaciones[$x]['RoomRate']['TPA_Extensions']['NettPrice']['value'])){

								$precioregimenes=str_replace(".",",",$habitaciones[$x]['RoomRate']['TPA_Extensions']['NettPrice']['value']);

							}

							if(isset($habitaciones[$x]['TPA_Extensions']['NonRefundable']['value'])){

								$NonRefundable=$habitaciones[$x]['TPA_Extensions']['NonRefundable']['value'];

								

							}else if(isset($habitaciones[$x]['RoomRate']['TPA_Extensions']['NonRefundable']['value'])){

								$NonRefundable=$habitaciones[$x]['RoomRate']['TPA_Extensions']['NonRefundable']['value'];

							}


							if(isset($habitaciones[$x]['TPA_Extensions']['Elements']['Element']['Description']['value'])){

								$ElementsDescription=$habitaciones[$x]['TPA_Extensions']['Elements']['Element']['Description']['value'];

								

							}else if(isset($habitaciones[$x]['RoomRate']['TPA_Extensions']['Elements']['Element']['Description']['value'])){

								$ElementsDescription=$habitaciones[$x]['RoomRate']['TPA_Extensions']['Elements']['Element']['Description']['value'];

							}


							$mostrar=1;	
                     		$urbVac=1;											

                     		// aqui sacamos si es vacacional o urbano
							// por que no tengo los codigos de los regimens de juniper. 

                     		if(!isset($sin_filtros_regimen) || isset($sin_filtros_regimen) && $sin_filtros_regimen==0){


								if(($this->parametros['destino']==2 && $regimenes!="SA" && $regimenes!="AD") 
								|| ($this->parametros['destino']==1 && $regimenes!="MP" && $regimenes!="PC" && $regimenes!="TI"))
								{
									$urbVac=0;
								}


									if($this->parametros['regimen']!='0' &&
									 strpos($this->parametros['regimen'],"-")){
				
									//// BUSQUEDAS MULTYREGIMEN
									// 1º evaluo el destino:
										//2º compruebo que el regimen este contenido en los validos
										//3º asigno mostrar

										if($this->parametros['destino']==1){
										
											$buscar='no_buscar';
											if($regimenes=='MP' || $regimenes=='PC' || $regimenes=='TI'){
												$buscar=$regimenes;
											}
									   		
									   		$comprobar_regimen=strpos($this->parametros['regimen'],$buscar);

									   		if($comprobar_regimen===false){
									   			$mostrar=0;
									   		}

									   	// if($this->parametros['destino']==1){
										}if($this->parametros['destino']==2){
											
											$buscar='no_buscar';
											if($regimenes=='AD' || $regimenes=='SA'){
												$buscar=$regimenes;
											}

											$comprobar_regimen=strpos($this->parametros['regimen'],$buscar);

									   		if($comprobar_regimen===false){
									   			$mostrar=0;
									   		}
									   	// }if($this->parametros['destino']==2){
										}


									// eof }else if($this->parametros['regimen']!='0'&&
									// strpos($this->parametros['regimen'],"-"){
									}else if(($this->parametros['regimen']=="AD" && $regimenes!="AD") || ($this->parametros['regimen']=="MP" && $regimenes!="MP") || ($this->parametros['regimen']=="PC" && $regimenes!="PC")|| ($this->parametros['regimen']=="SA" && $regimenes!="SA") || ($this->parametros['regimen']=="TI" && $regimenes!="TI")){
	                           				$mostrar=0;
	                        		}															

                        		} // if(!isset($sin_filtros_regimen) || isset($sin_f								


								if($urbVac==1  && $mostrar==1){
								//este es el if de mostrar.

								//estas variables no llegan al listado

								// cuando venga un solo hotel le ponemos por delante ['RoomStay']
								if($hot==2){

									$datos['codeHotel']=$hoteles[$i]['BasicPropertyInfo']['attr']['HotelCode'];
									// a partir de aqui son las que llegan al listado.
									$datos['precios'][$cont]['codeHotel']=$hoteles[$i]['BasicPropertyInfo']['attr']['HotelCode'];

								}else if($hot==1){

									$datos['codeHotel']=$hoteles[$i]['RoomStay']['BasicPropertyInfo']['attr']['HotelCode'];
									// a partir de aqui son las que llegan al listado.
									$datos['precios'][$cont]['codeHotel']=$hoteles[$i]['RoomStay']['BasicPropertyInfo']['attr']['HotelCode'];

								}						

								$datos['precios'][$cont]['abrevProv']="W2M";
								$datos['precios'][$cont]['precio']=$precioregimenes;
								$datos['precios'][$cont]['precioFormat']=numFormatoMysql($precioregimenes);
								$datos['precios'][$cont]['SequenceNmbr']=$SequenceNmbr;
								$datos['precios'][$cont]['totalFormat']=(($datos['precios'][$cont]['precioFormat']*$comision)/100) + $datos['precios'][$cont]['precioFormat'];
								 $datos['precios'][$cont]['total']=numFormato($datos['precios'][$cont]['totalFormat']);
										
								$datos['precios'][$cont]['offer']="";

								if(isset($NonRefundable) && $NonRefundable==1){
									$datos['precios'][$cont]['offer'].="No Reembolsable";
								}	


								if(isset($ElementsDescription) && $ElementsDescription!=""){

									// si viene algun dato de arriba hacemos un salto de linea
									if($datos['precios'][$cont]['offer']!=""){
										$datos['precios'][$cont]['offer'].="&#10";
									}

									$datos['precios'][$cont]['offer'].=$ElementsDescription;
								}


								//aqui hay que hacer un arreglo en el rate plan code, por que 
								// cuando no hay nas de una HB , no lo coge como Array.
								//$datos['precios'][$cont]['RatePlanCode']=$hoteles[$i]['RoomRates']['RoomRate']['attr']['RatePlanCode'];

								if(count($habitaciones)==1){

									if(isset($hoteles[$i]['RoomRates']['RoomRate']['attr']['RatePlanCode'])){

										$datos['precios'][$cont]['RatePlanCode']=$hoteles[$i]['RoomRates']['RoomRate']['attr']['RatePlanCode'];

									}else if(isset($hoteles[$i]['RoomStay']['RoomRates']['RoomRate']['attr']['RatePlanCode'])){

										$datos['precios'][$cont]['RatePlanCode']=$hoteles[$i]['RoomStay']['RoomRates']['RoomRate']['attr']['RatePlanCode'];
									}

								}else{

									if(isset($hoteles[$i]['RoomRates']['RoomRate'][$x]['attr']['RatePlanCode'])){

										$datos['precios'][$cont]['RatePlanCode']=$hoteles[$i]['RoomRates']['RoomRate'][$x]['attr']['RatePlanCode'];

									}else if(isset($hoteles[$i]['RoomStay']['RoomRates']['RoomRate'][$x]['attr']['RatePlanCode'])){

										$datos['precios'][$cont]['RatePlanCode']=$hoteles[$i]['RoomStay']['RoomRates']['RoomRate'][$x]['attr']['RatePlanCode'];

									}

								}

								$datos['precios'][$cont]['comision']=$comision;
								$datos['precios'][$cont]['nombreHab']=$nombrehabitacion;
								$datos['precios'][$cont]['nombreReg']=$regimenes;							
								$datos['precios'][$cont]['codeReg']=$codigoregimen;
											

								if($this->parametros['habitacion']==1){

									$datos['precios'][$cont]['nombreHbPost'][0]=$nombreHbPost."nomreg".$regimenes;

								}else{

									$numberHabit=$this->parametros['habitacion'];

									for($h=0;$h<$numberHabit;$h++){

										$datos['precios'][$cont]['nombreHbPost'][$h]=$nombreHbPost[$h]."nomreg".$regimenes;


									}

								}        					

									// seccion de precios recomendados
									$find=array(".",",");
									$chng=array("",".");
									$datos['precios'][$cont]['recomendado']=str_replace($find,$chng,$datos['precios'][$cont]['total']);

									if($this->parametros['destino']==1){
										$porcentaje = 	($datos['precios'][$cont]['recomendado']*25)/100;
									}else if($this->parametros['destino']==2){
										$porcentaje = ($datos['precios'][$cont]['recomendado']*10)/100;
									}

									$datos['precios'][$cont]['recomendado']=$datos['precios'][$cont]['recomendado']+$porcentaje;

									//////// VOY A LANZAR LOS GASTOS VACIOS EN ESTOS MOMENTOS:

									$datos['precios'][$cont]['gastos']="";
									$datos['precios'][$cont]['unique']=$datos['precios'][$cont]['RatePlanCode'];						
									$datos['precios'][$cont]['abrevReg']=$regimenes;	
									$datos['precios'][$cont]['proveedor']='id_versys';
									$datos['proveedor']='id_versys';
								
                      
								//este if empieza 
                        		$datos['precio']="";
								if((numFormatoMysql($datos['precio']) > $datos['precios'][$cont]['totalFormat']) || $cont==0){

									$datos['precio']=$datos['precios'][$cont]['total'];
									$datos['precioFormat']=$datos['precios'][$cont]['totalFormat'];

								}// este if acaba.


								$cont++;
							} // aqui acaba el if de mostrar.

						}
						
						// if(isset($_SESSION['ip_admin'])){

							$this->preparar_sql = $this->DB->link->prepare($this->sql_listados);
							$this->preparar_sql->bind_param('s', $datos['codeHotel']);
							$this->arrayHoteles($datos);
							$this->preparar_sql->close();

						// }else{

						// 	$this->arrayHoteles($datos);

						// }
					}
				break; //case $this->dir_versys:



			case $this->dir_solole:


					$resultado=$this->xml2array($data[0]);
//echo $data[0];
					
					//echo print_r($resultado);
					/*if($_SERVER['REMOTE_ADDR']=="88.26.243.236"){
						echo print_r($resultado);
					}*/

					//aqui registramos los errores, podemos imitar esto para así guardar todo loque recibimos.
					
					

					if(isset($resultado['soap:Envelope']['soap:Body']['OTA_HotelAvailServiceResponse']['OTA_HotelAvailRS']['Errors']['ErrorType']) || count($resultado)==0){
					if($resultado['soap:Envelope']['soap:Body']['OTA_HotelAvailServiceResponse']['OTA_HotelAvailRS']['Errors'] || count($resultado)==0){
						if($auto_gestion==0){
							script("$('#resultados_proveedores').append('Solole Sin disponibilidad o debido a un error<br> ')");
							
						}
						break;
					   }
					}
					//aqui cojemos la comision.
					$comision=$this->solole->comision;
					$this->sql_listados = 'select id,estado from _hoteles where id_solole like ?';

// aqui vemos si tenemos el listado con disponibilidad y bajamos la variable $resultados a el listado
					if(isset($resultado['soap:Envelope']['soap:Body']['OTA_HotelAvailServiceResponse']['OTA_HotelAvailRS']['RoomStays'])){

$SequenceNmbr=$resultado['soap:Envelope']['soap:Body']['OTA_HotelAvailServiceResponse']['OTA_HotelAvailRS']['attr']['SequenceNmbr'];

// AQUI TENEMOS EL PROBLEMA DE CUANDO VIENE UN SOLO HOTEL 

if(isset($resultado['soap:Envelope']['soap:Body']['OTA_HotelAvailServiceResponse']['OTA_HotelAvailRS']['RoomStays']['RoomStay'][0])){
					$resultado = $resultado['soap:Envelope']['soap:Body']['OTA_HotelAvailServiceResponse']['OTA_HotelAvailRS']['RoomStays']['RoomStay'];
					$hoteles=$resultado;
					
				}else if(!isset($resultado['soap:Envelope']['soap:Body']['OTA_HotelAvailServiceResponse']['OTA_HotelAvailRS']['RoomStays']['RoomStay'][0])){

                    $hoteles[0]=$resultado['soap:Envelope']['soap:Body']['OTA_HotelAvailServiceResponse']['OTA_HotelAvailRS']['RoomStays'];
					

				}



					}

//echo "<br> Los hoteles que tenemos son " . count($hoteles);
               // aqui lo que queremos es contar el nº de hoteles que tenemos.

					if($auto_gestion==0){
						script("$('#resultados_proveedores').append(' Solole trae " . count($hoteles). " hoteles<br> ')");
					}

					for($i=0;$i<count($hoteles);$i++){ //este for es el de los hoteles.
						$datos=array();
						$habitaciones=array();

              $code_new_hotel = $hoteles[$i]['BasicPropertyInfo']['attr']['HotelCode'];

              $comprobacion_hotel = $this->DB->select('id','_hoteles','id_solole like "'.$code_new_hotel.'"');
              if($comprobacion_hotel[r]==0){
              	//echo '<br> el hotel '.$code_new_hotel.' no esta mapeado';
              }

              	if($auto_gestion==0 && isset($hoteles[$i]['BasicPropertyInfo']['attr']['HotelCode'])){

             		$code_new_hotel = $hoteles[$i]['BasicPropertyInfo']['attr']['HotelCode'];

	              $comprobacion_hotel = $this->DB->select('id','_hoteles','id_solole like "'.$code_new_hotel.'"');
	              if($comprobacion_hotel[r]==0){
	              	// echo '<br> el hotel '.$code_new_hotel.' no esta mapeado';
	              }
				}
				

if(count($hoteles)>1){


	 		//$habitaciones=$hoteles[$i]['RoomRates']['RoomRate'];

	 			if (isset($hoteles[$i]['RoomRates']['RoomRate'][0])){

$habitaciones=$hoteles[$i]['RoomRates']['RoomRate'];

}else if(!isset($hoteles[$i]['RoomStay']['RoomRates']['RoomRate'][0])){

	$habitaciones[0]=$hoteles[$i]['RoomRates'];
}



$hot=2;
	 		}else if(count($hoteles)==1){
$hot=1;
	 			if (isset($hoteles[$i]['RoomStay']['RoomRates']['RoomRate'][0])){

$habitaciones=$hoteles[$i]['RoomStay']['RoomRates']['RoomRate'];

}else if(!isset($hoteles[$i]['RoomStay']['RoomRates']['RoomRate'][0])){

	$habitaciones[0]=$hoteles[$i]['RoomStay']['RoomRates'];
}

	 		}					


//$cuenta = count($habitaciones);
//echo "<br> cuenat $i " . count($habitaciones); 

$cont=0;

   		

				for($x=0;$x<count($habitaciones);$x++){ // este for contienen las habitaciones.
						

//_JuniperMealplan
			
//3 -> SA
//2 -> AD
//1 -> MP	
//4 -> PC
//5-> TI

if($hot==2 && isset($hoteles[$i]['RoomRates']['RoomRate'][0]) || $hot==1 && isset($hoteles[$i]['RoomStay']['RoomRates']['RoomRate'][0])){

	$idregimenes='';
	$codigoregimen='';

	if(isset($habitaciones[$x]['TPA_Extensions']['Mealplan']['value'])){
		$idregimenes=$habitaciones[$x]['TPA_Extensions']['Mealplan']['value'];	
	}

	if(isset($habitaciones[$x]['TPA_Extensions']['Mealplan']['value'])){
		$codigoregimen=$habitaciones[$x]['TPA_Extensions']['Mealplan']['value'];
	}


	
}else if($hot==1 && !isset($hoteles[$i]['RoomStay']['RoomRates']['RoomRate'][0]) || $hot==2 && !isset($hoteles[$i]['RoomRates']['RoomRate'][0])){

	$idregimenes='';
	$codigoregimen='';

	if(isset($habitaciones[$x]['RoomRate']['TPA_Extensions']['Mealplan']['value'])){
		$idregimenes=$habitaciones[$x]['RoomRate']['TPA_Extensions']['Mealplan']['value'];	
	}
	if(isset($habitaciones[$x]['RoomRate']['TPA_Extensions']['Mealplan']['value'])){
		$codigoregimen=$habitaciones[$x]['RoomRate']['TPA_Extensions']['Mealplan']['value'];	
	}


}	


//echo "<br> el id SOLOLE es ". $idregimenes;

$this->DB->query('select abreviatura from _regimenes where id_juniper='.$idregimenes);
$reg=$this->DB->fetch();

$regimenes=$reg['abreviatura'];

// SOLUCION AL MEALPLAN VACIO DE JUNIPER
if($regimenes==''){
	$regimenes="SA";
}
	

//$codigoregimen=$habitaciones[$x]['TPA_Extensions']['Mealplan']['attr']['id'];
//$regimenes=$habitaciones[$x]['TPA_Extensions']['Mealplan']['attr']['Category'];


if($this->parametros['habitacion']==1){

	if(isset($habitaciones[$x]['Rates']['Rate']['RateDescription']['Text']['value'])){
$nombrehabitacion=$habitaciones[$x]['Rates']['Rate']['RateDescription']['Text']['value'];
}else if(isset($habitaciones[$x]['RoomRate']['Rates']['Rate']['RateDescription']['Text']['value'])){
	$nombrehabitacion=$habitaciones[$x]['RoomRate']['Rates']['Rate']['RateDescription']['Text']['value'];
}

if(isset($habitaciones[$x]['Rates']['Rate']['RateDescription']['Text']['value'])){
$nombreHbPost=$habitaciones[$x]['Rates']['Rate']['RateDescription']['Text']['value'];
}else if(isset($habitaciones[$x]['RoomRate']['Rates']['Rate']['RateDescription']['Text']['value'])){
$nombreHbPost=$habitaciones[$x]['RoomRate']['Rates']['Rate']['RateDescription']['Text']['value'];
}


//$nombrehabitacion=$habitaciones[$x]['Rates']['Rate']['RateDescription']['Text']['value'];
//$nombreHbPost=$habitaciones[$x]['Rates']['Rate']['RateDescription']['Text']['value'];

}else{

	if(isset($habitaciones[$x]['Rates']['Rate'][0]['RateDescription']['Text']['value'])){
  $nombrehabitacion=$habitaciones[$x]['Rates']['Rate'][0]['RateDescription']['Text']['value']; 
}
$numberHabit=$this->parametros['habitacion'];

for($h=0;$h<$numberHabit;$h++){

if(isset($habitaciones[$x]['Rates']['Rate'][$h]['RateDescription']['Text']['value'])){
$nombreHbPost[$h]=$habitaciones[$x]['Rates']['Rate'][$h]['RateDescription']['Text']['value'];
}

}

}



if(isset($habitaciones[$x]['TPA_Extensions']['NettPrice']['value'])){

$precioregimenes=str_replace(".",",",$habitaciones[$x]['TPA_Extensions']['NettPrice']['value']);

}else if(isset($habitaciones[$x]['RoomRate']['TPA_Extensions']['NettPrice']['value'])){

$precioregimenes=str_replace(".",",",$habitaciones[$x]['RoomRate']['TPA_Extensions']['NettPrice']['value']);

}


if(isset($habitaciones[$x]['TPA_Extensions']['NonRefundable']['value'])){

	$NonRefundable=$habitaciones[$x]['TPA_Extensions']['NonRefundable']['value'];

	

}else if(isset($habitaciones[$x]['RoomRate']['TPA_Extensions']['NonRefundable']['value'])){

	$NonRefundable=$habitaciones[$x]['RoomRate']['TPA_Extensions']['NonRefundable']['value'];

}


if(isset($habitaciones[$x]['TPA_Extensions']['Elements']['Element']['Description']['value'])){

	$ElementsDescription=$habitaciones[$x]['TPA_Extensions']['Elements']['Element']['Description']['value'];

	

}else if(isset($habitaciones[$x]['RoomRate']['TPA_Extensions']['Elements']['Element']['Description']['value'])){

	$ElementsDescription=$habitaciones[$x]['RoomRate']['TPA_Extensions']['Elements']['Element']['Description']['value'];

}


					$mostrar=1;	
                     $urbVac=1;											

// aqui sacamos si es vacacional o urbano


if(($this->parametros['destino']==2 && $regimenes!="SA" && $regimenes!="AD") || ($this->parametros['destino']==1 && $regimenes!="MP" && $regimenes!="PC" && $regimenes!="TI")){
									$urbVac=0;
									$mostrar=0;	
								}

								if($this->parametros['regimen']!='0' &&
								 strpos($this->parametros['regimen'],"-")){
			
								//// BUSQUEDAS MULTYREGIMEN
								// 1º evaluo el destino:
									//2º compruebo que el regimen este contenido en los validos
									//3º asigno mostrar

									if($this->parametros['destino']==1){
									
										$buscar='no_buscar';
										if($regimenes=='MP' || $regimenes=='PC' || $regimenes=='TI'){
											$buscar=$regimenes;
										}
								   		
								   		$comprobar_regimen=strpos($this->parametros['regimen'],$buscar);

								   		if($comprobar_regimen===false){
								   			$mostrar=0;
								   		}

								   	// if($this->parametros['destino']==1){
									}if($this->parametros['destino']==2){
										
										$buscar='no_buscar';
										if($regimenes=='AD' || $regimenes=='SA'){
											$buscar=$regimenes;
										}

										$comprobar_regimen=strpos($this->parametros['regimen'],$buscar);

								   		if($comprobar_regimen===false){
								   			$mostrar=0;
								   		}
								   	// }if($this->parametros['destino']==2){
									}


								// eof }else if($this->parametros['regimen']!='0'&&
								// strpos($this->parametros['regimen'],"-"){
								}else if(($this->parametros['regimen']=="AD" && $regimenes!="AD") || ($this->parametros['regimen']=="MP" && $regimenes!="MP") || ($this->parametros['regimen']=="PC" && $regimenes!="PC")|| ($this->parametros['regimen']=="SA" && $regimenes!="SA") || ($this->parametros['regimen']=="TI" && $regimenes!="TI")){
                           				$mostrar=0;
                        		}																		

								// if(isset($_SESSION['admin_ok']) && $_SESSION['admin_ok']==1){
        //                 			//$_SESSION['admin_ok']=1; 
								// 			$mostrar=1;
        //                 		}					

			//$_SESSION['admin_ok']=1; //$mostrar=1;					

if($urbVac==1 /*|| isset($_SESSION['admin_ok']) && $_SESSION['admin_ok']==1)*/ && $mostrar==1){
								//este es el if de mostrar.

							//estas variables no llegan al listado

	// cuando venga un solo hotel le ponemos por delante ['RoomStay']
if($hot==2){
$datos['codeHotel']=$hoteles[$i]['BasicPropertyInfo']['attr']['HotelCode'];


// a partir de aqui son las que llegan al listado.
$datos['precios'][$cont]['codeHotel']=$hoteles[$i]['BasicPropertyInfo']['attr']['HotelCode'];
}else if($hot==1){
$datos['codeHotel']=$hoteles[$i]['RoomStay']['BasicPropertyInfo']['attr']['HotelCode'];


// a partir de aqui son las que llegan al listado.
$datos['precios'][$cont]['codeHotel']=$hoteles[$i]['RoomStay']['BasicPropertyInfo']['attr']['HotelCode'];	
}						


$datos['precios'][$cont]['precio']=$precioregimenes;
$datos['precios'][$cont]['precioFormat']=numFormatoMysql($precioregimenes);
								/*

esto lo usabamos para los precios vinculantes en solhotelazo, pero lo vamos a desactivar ya que no tenemos la necesidad.

								if($habitaciones[$x]['SellingPriceMandatory']['value']=="Y" && $_SERVER['SERVER_NAME']=="www.solhotelazo.com"){
									$datos['precios'][$cont]['vinculante']=1;
									$datos['precios'][$cont]['totalFormat']=numFormatoMysql($habitaciones[$x]['TotalPrice']['value']);
									$datos['precios'][$cont]['total']=$habitaciones[$x]['TotalPrice']['value'];
								}else{*/

$datos['precios'][$cont]['SequenceNmbr']=$SequenceNmbr;
$datos['precios'][$cont]['totalFormat']=(($datos['precios'][$cont]['precioFormat']*$comision)/100) + $datos['precios'][$cont]['precioFormat'];
 $datos['precios'][$cont]['total']=numFormato($datos['precios'][$cont]['totalFormat']);

 $datos['precios'][$cont]['offer']="";

if(isset($NonRefundable) && $NonRefundable==1){
	$datos['precios'][$cont]['offer'].="No Reembolsable";
}	


if(isset($ElementsDescription) && $ElementsDescription!=""){

	// si viene algun dato de arriba hacemos un salto de linea
	if($datos['precios'][$cont]['offer']!=""){
		$datos['precios'][$cont]['offer'].="&#10";
	}

	$datos['precios'][$cont]['offer'].=$ElementsDescription;
}

								/*}*/
//aqui hay que hacer un arreglo en el rate plan code, por que 
// cuando no hay nas de una HB , no lo coge como Array.
//$datos['precios'][$cont]['RatePlanCode']=$hoteles[$i]['RoomRates']['RoomRate']['attr']['RatePlanCode'];

if(count($habitaciones)==1){

if(isset($hoteles[$i]['RoomRates']['RoomRate']['attr']['RatePlanCode'])){
$datos['precios'][$cont]['RatePlanCode']=$hoteles[$i]['RoomRates']['RoomRate']['attr']['RatePlanCode'];
}else if(isset($hoteles[$i]['RoomStay']['RoomRates']['RoomRate']['attr']['RatePlanCode'])){
$datos['precios'][$cont]['RatePlanCode']=$hoteles[$i]['RoomStay']['RoomRates']['RoomRate']['attr']['RatePlanCode'];
}

}else{

if(isset($hoteles[$i]['RoomRates']['RoomRate'][$x]['attr']['RatePlanCode'])){
$datos['precios'][$cont]['RatePlanCode']=$hoteles[$i]['RoomRates']['RoomRate'][$x]['attr']['RatePlanCode'];
}else if(isset($hoteles[$i]['RoomStay']['RoomRates']['RoomRate'][$x]['attr']['RatePlanCode'])){
$datos['precios'][$cont]['RatePlanCode']=$hoteles[$i]['RoomStay']['RoomRates']['RoomRate'][$x]['attr']['RatePlanCode'];

}

}
$datos['precios'][$cont]['comision']=$comision;
$datos['precios'][$cont]['nombreHab']=$nombrehabitacion;
$datos['precios'][$cont]['nombreReg']=$regimenes;							
$datos['precios'][$cont]['codeReg']=$codigoregimen;
			

 if($this->parametros['habitacion']==1){

$datos['precios'][$cont]['nombreHbPost'][0]=$nombreHbPost."nomreg".$regimenes;

}else{

$numberHabit=$this->parametros['habitacion'];

for($h=0;$h<$numberHabit;$h++){

$datos['precios'][$cont]['nombreHbPost'][$h]=$nombreHbPost[$h]."nomreg".$regimenes;


}

}      

									// seccion de precios recomendados
									$find=array(".",",");
									$chng=array("",".");
									$datos['precios'][$cont]['recomendado']=str_replace($find,$chng,$datos['precios'][$cont]['total']);
									if($this->parametros['destino']==1){
										$porcentaje = 	($datos['precios'][$cont]['recomendado']*25)/100;
									}else if($this->parametros['destino']==2){
										$porcentaje = ($datos['precios'][$cont]['recomendado']*10)/100;
									}

									$datos['precios'][$cont]['recomendado']=$datos['precios'][$cont]['recomendado']+$porcentaje;

									//////// VOY A LANZAR LOS GASTOS VACIOS EN ESTOS MOMENTOS:

									$datos['precios'][$cont]['gastos']="";  					
									// $datos['precios'][$cont]['unique']=microtime()+rand(0,10000000000);
									$datos['precios'][$cont]['abrevReg']=$regimenes;								
									$datos['precios'][$cont]['proveedor']='id_solole';
									$datos['proveedor']='id_solole';
								
                      
								//este if empieza 
                       			 $datos['precio']="";
								if((numFormatoMysql($datos['precio']) > $datos['precios'][$cont]['totalFormat']) || $cont==0){

									$datos['precio']=$datos['precios'][$cont]['total'];
									$datos['precioFormat']=$datos['precios'][$cont]['totalFormat'];

								}// este if acaba.


								$cont++;
							} // aqui acaba el if de mostrar.

						}

						if(isset($_SESSION['ip_admin'])){

							$this->preparar_sql = $this->DB->link->prepare($this->sql_listados);
							$this->preparar_sql->bind_param('s', $datos['codeHotel']);
							$this->arrayHoteles($datos);
							$this->preparar_sql->close();

						}else{

							$this->arrayHoteles($datos);

						}
					}
				break;


				case $this->dir_europlayas:

                    $resultado=$this->xml2array($data[0]);

                    if(isset($resultado['PublisherError']) || isset($resultado['DisponibilidadHotelRespuesta']['coderr']) || count($resultado)==0 || !isset($resultado['DisponibilidadHotelRespuesta']['infhot'])){

                    	if($auto_gestion==0){

                    		script("$('#resultados_proveedores').append('Euro Sin disponibilidad o debido a un error<br> ')");

                  			echo "<br> ";
                  		}
                        break;
                       }
                    
                    //aqui cojemos la comision.
                    $comision=$this->europlayas->comision;
					$this->sql_listados = 'select id,estado from _hoteles where id_europlayas like ?';


// aqui vemos si tenemos el listado con disponibilidad y bajamos la variable $resultados a el listado
   if(isset($resultado['DisponibilidadHotelRespuesta']['infhot'])){

 		$resultado = $resultado['DisponibilidadHotelRespuesta'];

    }

//Aqui metemos los hoteles, en la variable $hoteles y al aplicarle count() contamos 
//el nº de hotels que tenemos en el array


               // aqui lo que queremos es contar el nº de hoteles que tenemos.


                     if(isset($resultado['infhot']['attr']['id']) && strlen($resultado['infhot']['attr']['id'])>0){
                        $hoteles[0]=$resultado['infhot'];
                     }else{
                        $hoteles=$resultado['infhot'];
                     }


// Aqui contamos el nº de hoteles que tenemos y los mostramos 
// en pantalla para poder comprobar que es correctom el resultado que
                     //tenemos.
//		echo "hoteles 0 vale ".  count($hoteles[0]);
                    if($auto_gestion==0){

                    	script("$('#resultados_proveedores').append('Euro trae ". count($hoteles) ." Hoteles en ".$data[1]['total_time']." Segundos<br> ')");
					
					}


                    for($i=0;$i<count($hoteles);$i++){ //este for es el de los hoteles.
                        $datos=array();
                        $habitaciones=array();


               
// aqui hacemos la misma operacion pero con las habitaciones que puede tener cada hotel
                       

// $this->parametros['habitacion'] esta es la variable por la que 
//sabemos el nº de Hb que tenemos en la peticion. 

                        if(isset($hoteles[$i]['infhab'][0])){


                         if(strlen($hoteles[$i]['impbas']['value'])>0){
                            $habitaciones=$hoteles[$i]['infhab'];
                        }else{
                            $habitaciones[0]=$hoteles[$i]['infhab'];
                        }


}else if(!isset($hoteles[$i]['infhab'][0])){

$habitaciones[0]=$hoteles[$i]['infhab'];

}

      
 //echo "<br> habitaciones 0 vale ".  count($habitaciones[0]);
 //echo "<br> habitaciones  vale ".  count( $habitaciones);


if($this->parametros['habitacion']==1){
    
      if(isset($hoteles[$i]['infhab'][0])){

       $nhabitaciones= count($habitaciones);

}else if(!isset($hoteles[$i]['infhab'][0])){


$nhabitaciones=1;

}

//echo "<br> El n de habitacuines es " . $nhabitaciones;

                    }else if($this->parametros['habitacion']==2){


// aqui saco el nº de habitaciones reales
$nhabitaciones= count($habitaciones) / $this->parametros['habitacion'];

//con el nº de habitaciones, meter dentro de $habitaciones[$x] los valores
//que vamos luego a mostrar
//voy a agrupar todos los valores de las HB completos.
//abajo separo los que no necesito agrupar y dejo los agrupados. 

$h=0;

for($p=0;$p<count($habitaciones);$p++){

if($habitaciones[$p]['attr']['refdis']==1 && $habitaciones[$p+1]['attr']['refdis']==2){

$habitaciones[$h]['codral']['value']=$habitaciones[$p]['codral']['value'];
$habitaciones[$h]['codsmo']['value']=$habitaciones[$p]['codsmo']['value'];
$habitaciones[$h]['codcha']['value']=$habitaciones[$p]['codcha']['value'];

for($n=0;$n<$this->parametros['habitacion'];$n++){

$nombreHbPost[$n]=strtoupper($habitaciones[$p+$n]['codsmo']['value']) ." ". strtoupper($habitaciones[$p+$n]['codcha']['value']);

}

$habitaciones[$h]['attr']['id']=$habitaciones[$p]['attr']['id']."-".$habitaciones[$p+1]['attr']['id'];
$habitaciones[$h]['impnoc']['value']=str_replace(",",".",$habitaciones[$p]['impnoc']['value']) + str_replace(",",".",$habitaciones[$p+1]['impnoc']['value']);
$h++;
}


}

               // fin del parametros HB ==2      	
              }else if($this->parametros['habitacion']==3){


// aqui saco el nº de habitaciones reales
$nhabitaciones= count($habitaciones) / $this->parametros['habitacion'];

//con el nº de habitaciones, meter dentro de $habitaciones[$x] los valores
//que vamos luego a mostrar
//voy a agrupar todos los valores de las HB completos.
//abajo separo los que no necesito agrupar y dejo los agrupados. 

$h=0;

for($p=0;$p<count($habitaciones);$p++){

if($habitaciones[$p]['attr']['refdis']==1 && $habitaciones[$p+1]['attr']['refdis']==2 && $habitaciones[$p+2]['attr']['refdis']==3){

$habitaciones[$h]['codral']['value']=$habitaciones[$p]['codral']['value'];
$habitaciones[$h]['codsmo']['value']=$habitaciones[$p]['codsmo']['value'];
$habitaciones[$h]['codcha']['value']=$habitaciones[$p]['codcha']['value'];
$habitaciones[$h]['attr']['id']=$habitaciones[$p]['attr']['id']."-".$habitaciones[$p+1]['attr']['id']."-".$habitaciones[$p+2]['attr']['id'];


for($n=0;$n<$this->parametros['habitacion'];$n++){

$nombreHbPost[$n]=strtoupper($habitaciones[$p+$n]['codsmo']['value']) ." ". strtoupper($habitaciones[$p+$n]['codcha']['value']);

}

$habitaciones[$h]['impnoc']['value']=str_replace(",",".",$habitaciones[$p]['impnoc']['value']) + str_replace(",",".",$habitaciones[$p+1]['impnoc']['value']) + str_replace(",",".",$habitaciones[$p+2]['impnoc']['value']);
$h++;
}


}

               // fin del parametros HB ==3    	
              }else if($this->parametros['habitacion']==4){

// aqui saco el nº de habitaciones reales
$nhabitaciones= count($habitaciones) / $this->parametros['habitacion'];

//con el nº de habitaciones, meter dentro de $habitaciones[$x] los valores
//que vamos luego a mostrar
//voy a agrupar todos los valores de las HB completos.
//abajo separo los que no necesito agrupar y dejo los agrupados. 

$h=0;

for($p=0;$p<count($habitaciones);$p++){

if($habitaciones[$p]['attr']['refdis']==1 && $habitaciones[$p+1]['attr']['refdis']==2 && $habitaciones[$p+2]['attr']['refdis']==3 && $habitaciones[$p+3]['attr']['refdis']==4){

$habitaciones[$h]['codral']['value']=$habitaciones[$p]['codral']['value'];
$habitaciones[$h]['codsmo']['value']=$habitaciones[$p]['codsmo']['value'];
$habitaciones[$h]['codcha']['value']=$habitaciones[$p]['codcha']['value'];

for($n=0;$n<$this->parametros['habitacion'];$n++){

$nombreHbPost[$n]=strtoupper($habitaciones[$p+$n]['codsmo']['value']) ." ". strtoupper($habitaciones[$p+$n]['codcha']['value']);

}

$habitaciones[$h]['attr']['id']=$habitaciones[$p]['attr']['id']."-".$habitaciones[$p+1]['attr']['id']."-".$habitaciones[$p+2]['attr']['id']."-".$habitaciones[$p+3]['attr']['id'];
$habitaciones[$h]['impnoc']['value']=str_replace(",",".",$habitaciones[$p]['impnoc']['value']) + str_replace(",",".",$habitaciones[$p+1]['impnoc']['value']) + str_replace(",",".",$habitaciones[$p+2]['impnoc']['value']) + str_replace(",",".",$habitaciones[$p+3]['impnoc']['value']);
$h++;
}


}

               // fin del parametros HB ==4    	
}else if($this->parametros['habitacion']==5){

			// aqui saco el nº de habitaciones reales
			$nhabitaciones= count($habitaciones) / $this->parametros['habitacion'];

			//con el nº de habitaciones, meter dentro de $habitaciones[$x] los valores
			//que vamos luego a mostrar

			// voy a agrupar todos los valores de las HB completos.

			//abajo separo los que no necesito agrupar y dejo los agrupados. 

			$h=0;

			for($p=0;$p<count($habitaciones);$p++){

			if($habitaciones[$p]['attr']['refdis']==1 && $habitaciones[$p+1]['attr']['refdis']==2 && $habitaciones[$p+2]['attr']['refdis']==3 && $habitaciones[$p+3]['attr']['refdis']==4 && $habitaciones[$p+3]['attr']['refdis']==5){

			$habitaciones[$h]['codral']['value']=$habitaciones[$p]['codral']['value'];
			$habitaciones[$h]['codsmo']['value']=$habitaciones[$p]['codsmo']['value'];
			$habitaciones[$h]['codcha']['value']=$habitaciones[$p]['codcha']['value'];

			for($n=0;$n<$this->parametros['habitacion'];$n++){

			$nombreHbPost[$n]=strtoupper($habitaciones[$p+$n]['codsmo']['value']) ." ". strtoupper($habitaciones[$p+$n]['codcha']['value']);

			}

			$habitaciones[$h]['attr']['id']=$habitaciones[$p]['attr']['id']."-".$habitaciones[$p+1]['attr']['id']."-".$habitaciones[$p+2]['attr']['id']."-".$habitaciones[$p+3]['attr']['id']."-".$habitaciones[$p+4]['attr']['id'];
			$habitaciones[$h]['impnoc']['value']=str_replace(",",".",$habitaciones[$p]['impnoc']['value']) + str_replace(",",".",$habitaciones[$p+1]['impnoc']['value']) + str_replace(",",".",$habitaciones[$p+2]['impnoc']['value']) + str_replace(",",".",$habitaciones[$p+3]['impnoc']['value']) + str_replace(",",".",$habitaciones[$p+4]['impnoc']['value']);
			$h++;
			}


			}

               // fin del parametros HB ==5  	
}




$cont=0;



                for($x=0;$x<$nhabitaciones;$x++){ // este for contienen las habitaciones.
                        

$regimenes=$habitaciones[$x]['codral']['value'];

if($regimenes=="MC"){
	$regimenes="MP";
}


$codigoregimen=$habitaciones[$x]['codral']['value'];

$codsmo="";
if(isset($habitaciones[$x]['codsmo']['value'])){
$codsmo=strtoupper($habitaciones[$x]['codsmo']['value']);
}

$codcha="";
if(isset($habitaciones[$x]['codcha']['value'])){
$codcha=strtoupper($habitaciones[$x]['codcha']['value']);
}

$nombrehabitacion=$codsmo ." ". $codcha;

$idhabitacion=$habitaciones[$x]['attr']['id'];

if(isset($habitaciones[$x]['codtts']['value'])){
$escapada=$habitaciones[$x]['codtts']['value'];
}else{
	$escapada="";
}


$precioregimenes=str_replace(".",",",$habitaciones[$x]['impnoc']['value']);

 //echo "<br> regiemens vale " . $regimenes;
 //echo "<br> codigoregimen vale " . $codigoregimen;
 // echo "<br> nombrehabitacion vale " . $nombrehabitacion;
 // echo "<br> idhabitacion vale " . $idhabitacion;
 // echo "<br> precios regimen " . $precioregimenes;


                   			$mostrar=1;	
                     		$urbVac=1;	

                     		if($escapada!=""){
								$mostrar=0;
							}


							if(!isset($sin_filtros_regimen) || isset($sin_filtros_regimen) && $sin_filtros_regimen==0){

								if(($this->parametros['destino']==2 && $regimenes!="SA" && $regimenes!="AD") 
								    || ($this->parametros['destino']==1 && $regimenes!="MP" 
								        && $regimenes!="PC" && $regimenes!="TI")){
										$urbVac=0;
									}

								

								if($this->parametros['regimen']!='0' &&
									 strpos($this->parametros['regimen'],"-")){
				
									//// BUSQUEDAS MULTYREGIMEN
									// 1º evaluo el destino:
									//2º compruebo que el regimen este contenido en los validos
									//3º asigno mostrar

										if($this->parametros['destino']==1){
										
											$buscar='no_buscar';
											if($regimenes=='MP' || $regimenes=='PC' || $regimenes=='TI'){
												$buscar=$regimenes;
											}
									   		
									   		$comprobar_regimen=strpos($this->parametros['regimen'],$buscar);

									   		if($comprobar_regimen===false){
									   			$mostrar=0;
									   		}

									   	// if($this->parametros['destino']==1){
										}if($this->parametros['destino']==2){
											
											$buscar='no_buscar';
											if($regimenes=='AD' || $regimenes=='SA'){
												$buscar=$regimenes;
											}

											$comprobar_regimen=strpos($this->parametros['regimen'],$buscar);

									   		if($comprobar_regimen===false){
									   			$mostrar=0;
									   		}
									   	// }if($this->parametros['destino']==2){
										}


									// eof }else if($this->parametros['regimen']!='0'&&
									}else if(($this->parametros['regimen']=="AD" && $regimenes!="AD") 
									|| ($this->parametros['regimen']=="MP" && $regimenes!="MP") 
									|| ($this->parametros['regimen']=="PC" && $regimenes!="PC") 
									|| ($this->parametros['regimen']=="SA" && $regimenes!="SA") 
									|| ($this->parametros['regimen']=="TI" && $regimenes!="TI")){
	                           				$mostrar=0;
	                        		}

	                        	} // if(!isset($sin_filtros_regimen) || isset($si ...

//dentro de estos if vamos a desglosar los ID de HB para ver si lo smostramos o no
// en el listado, por si no contienen la distribucion de toda slas habitacione.

if($this->parametros['habitacion']==2){
	$idhabitaciones=explode("-",$idhabitacion);
	if(!isset($idhabitaciones[1])){
		$mostrar=0;
	
   }

}else if($this->parametros['habitacion']==3){

$idhabitaciones=explode("-",$idhabitacion);
	if(!isset($idhabitaciones[1]) || !isset($idhabitaciones[2])){
		$mostrar=0;
	
   }

}else if($this->parametros['habitacion']==4){

$idhabitaciones=explode("-",$idhabitacion);
	if(!isset($idhabitaciones[1]) || !isset($idhabitaciones[2]) || !isset($idhabitaciones[3])){
		$mostrar=0;
	
   }

}else if($this->parametros['habitacion']==5){

$idhabitaciones=explode("-",$idhabitacion);
	if(!isset($idhabitaciones[1]) || !isset($idhabitaciones[2]) || !isset($idhabitaciones[3]) || !isset($idhabitaciones[4])){
		$mostrar=0;
	
   }

}



								// if(isset($_SESSION['admin_ok']) && $_SESSION['admin_ok']==1){
        //                 			//$_SESSION['admin_ok']=1; 
								// 			$mostrar=1;
        //                 		}	
                            
if($urbVac==1 /*|| isset($_SESSION['admin_ok']) && $_SESSION['admin_ok']==1)*/ && $mostrar==1){
                                //este es el if de mostrar.

                            //estas variables no llegan al listado
$datos['precios'][$cont]['abrevProv']="EU";
$datos['codeHotel']=$hoteles[$i]['attr']['id'];

//$datos['estrellas']=$hoteles[$i]['codsca']['value'];

 //echo "<br> datos['nombreHotel'] vale " . $datos['nombreHotel'];
 //echo "<br> datos['codeHotel'] " . $datos['codeHotel'];
 //echo "<br> datos['estrellas'] " . $datos['estrellas'];


// a partir de aqui son las que llegan al listado.
$datos['precios'][$cont]['codeHotel']=$hoteles[$i]['attr']['id'];  
$datos['precios'][$cont]['precio']=$precioregimenes;
$datos['precios'][$cont]['precioFormat']=numFormatoMysql($precioregimenes);
$datos['precios'][$cont]['totalFormat']=(($datos['precios'][$cont]['precioFormat']*$comision)/100) + $datos['precios'][$cont]['precioFormat'];
$datos['precios'][$cont]['total']=numFormato($datos['precios'][$cont]['totalFormat']);
$datos['precios'][$cont]['comision']=$comision;
$datos['precios'][$cont]['nombreHab']=$nombrehabitacion;
$datos['precios'][$cont]['nombreReg']=$regimenes;                           
$datos['precios'][$cont]['codeReg']=$codigoregimen;
$datos['precios'][$cont]['codeHab']=$idhabitacion;


$datos['precios'][$cont]['offer']='';
if(isset($habitaciones[$x]['nomtrf']['value'])){
	$datos['precios'][$cont]['offer'].=$habitaciones[$x]['nomtrf']['value'].'
';
}


if(isset($habitaciones[$x]['infofe']['value'])){
	$datos['precios'][$cont]['offer'].=$habitaciones[$x]['infofe']['codofe']['value'].'
';
	$datos['precios'][$cont]['offer'].=$habitaciones[$x]['infofe']['nomofe']['value'].'
';
}

if(isset($habitaciones[$x]['notser']['txtinf']['value'])){



		$string = str_replace("////////","",$habitaciones[$x]['notser']['txtinf']['value']);

		$find = array('\\\\','\\',"////","///","//","/");
		$string = str_replace($find,"&#10;",$string);

		//  $string =$habitaciones[$x]['notser']['txtinf']['value'];
		if($string!=""){

			$datos['precios'][$cont]['offer'].=htmlspecialchars_decode($string);
		}




	// 	$datos['precios'][$cont]['offer']=$habitaciones[$x]['notser']['txtinf']['value'].'
	// ';
}


	// PRECIOS RECOMENDADOS A SALIR EN LOS LISTADOS.

	$find=array(".",",");
	$chng=array("",".");
	$datos['precios'][$cont]['recomendado']=str_replace($find,$chng,$datos['precios'][$cont]['total']);

	
	if($this->parametros['destino']==1){
		$porcentaje = 	($datos['precios'][$cont]['recomendado']*25)/100;
	}else if($this->parametros['destino']==2){
		$porcentaje = ($datos['precios'][$cont]['recomendado']*10)/100;
	}

	$datos['precios'][$cont]['recomendado']=$datos['precios'][$cont]['recomendado']+$porcentaje;




$datos['precios'][$cont]['gastos']='';

if(isset($habitaciones[$x]['rstcan']['impcan']['value'])){


	if(isset($habitaciones[$x]['rstcan']['txtinf']['value'])){
		$datos['precios'][$cont]['gastos'] = $habitaciones[$x]['rstcan']['txtinf']['value'];
	}else{
		$fecha = $habitaciones[$x]['rstcan']['feccan']['value'];
		$importe = str_replace($find,$chng,$habitaciones[$x]['rstcan']['impcan']['value']);
		$porcentaje = ($importe*$comision)/100;
		$importe_final = $importe+$porcentaje;
		$datos['precios'][$cont]['gastos'].='Desde el '.$fecha.' un importe de '.number_format($importe_final,2,".",""). ' &euro;';
	}// if(isset($habitaciones[$x]['rstcan']['txtinf']['value'])){

}else if(isset($habitaciones[$x]['rstcan'][0]['impcan']['value'])){

	$n_gastos=count($habitaciones[$x]['rstcan']);
	
	for($clx=0;$clx<$n_gastos;$clx++){

		if(isset($habitaciones[$x]['rstcan'][$clx]['txtinf']['value'])){
			$datos['precios'][$cont]['gastos'] = $habitaciones[$x]['rstcan'][$clx]['txtinf']['value'];
		}else{

 			$fecha = $habitaciones[$x]['rstcan'][$clx]['feccan']['value'];
 			$importe = str_replace($find,$chng,$habitaciones[$x]['rstcan'][$clx]['impcan']['value']);
 			$porcentaje = ($importe*$comision)/100;
 			$importe_final = $importe+$porcentaje;

 			if($clx>0){
 				// añadimos un salto de linea cuando venga
 				// + de 1 gasto 
 				$datos['precios'][$cont]['gastos'].='<br>';	
 			}

 			$datos['precios'][$cont]['gastos'].='Desde el '.$fecha.' un importe de '.number_format($importe_final,2,".",""). ' &euro;';	
		}
	}//for($clx=0;$clx<$n_gastos;$clx++){

}



if($this->parametros['habitacion']==1){

	$datos['precios'][$cont]['nombreHbPost'][0]=$nombrehabitacion."nomreg".$regimenes;

	}else{

	$numberHabit=$this->parametros['habitacion'];

	for($h=0;$h<$numberHabit;$h++){

		echo "entro";
		$datos['precios'][$cont]['nombreHbPost'][$h]=$nombreHbPost[$h]."nomreg".$regimenes;

	}

} 
							
							$datos['precios'][$cont]['unique'] = $datos['precios'][$cont]['nombreHbPost'][0];

							$datos['precios'][$cont]['abrevReg']=$regimenes;  
							$datos['precios'][$cont]['proveedor']='id_europlayas';
							$datos['proveedor']='id_europlayas';

			                $datos['precio']="";
							if((numFormatoMysql($datos['precio']) > $datos['precios'][$cont]['totalFormat']) || $cont==0){

			                $datos['precio']=$datos['precios'][$cont]['total'];
			                $datos['precioFormat']=$datos['precios'][$cont]['totalFormat'];

			                }// este if acaba.


                                $cont++;
                               
                            } // aqui acaba el if de mostrar.

                        }
                       
                        if(isset($_SESSION['ip_admin'])){

							$this->preparar_sql = $this->DB->link->prepare($this->sql_listados);
							$this->preparar_sql->bind_param('s', $datos['codeHotel']);
							$this->arrayHoteles($datos);
							$this->preparar_sql->close();

						}else{

							$this->arrayHoteles($datos);

						}
                       
                    }
                   
                break;
                 
				
////////////////////////////

				case $this->dir_bedsonline:

					// cuando arreglemos la dispo de mas de 1 hb hay que arreglar el 
					// bucle de los pasajeris en la confirmacion de las reservas
					

					$resultado=$this->xml2array($data[0]);
					if(isset($resultado['HotelValuedAvailRS']['ErrorList']) || count($resultado)==0 || !isset($resultado['HotelValuedAvailRS']['ServiceHotel'])){

						if($auto_gestion==0){

					    	script("$('#resultados_proveedores').append('Beds sin disponibilidad o debido a un error<br>')");
						}
						break;
						
					}
					
					$comision=$this->bedsonline->comision;
					$this->sql_listados = 'select id,estado from _hoteles where id_bedsonline like ?';
					$resultado=$resultado['HotelValuedAvailRS']['ServiceHotel'];
					
					if(isset($resultado['attr']['xsi:type']) && strlen($resultado['attr']['xsi:type'])>0)
						$hoteles[0]=$resultado;
					else
						$hoteles=$resultado;


					
					$reg_urb=array();
					$str1="SC-BR2,RO-CN3,RO-CR1,RO-D01,SH-D01,RO-T01,SC-U02,SH-U02,SC-U03,SC-U10,RO-U10,SC-E10,RO-ID1,SC-ID1,RO-P01,RO-RD1,RO-TH2,SC-RD1,SC-TH1,SC-TH2,RO-E10,RO-G01,SC-P01,RO-U03,SC-MX1,SH-MX1,RO-MY1,RO-BR2,SC-CN1,SC-CN3,RO-MX1,RO-MY2,SC-T01,RO-TH1,SH-ID1,SC-MY2,SC-RF1,SC-D01,SC-G01,SC-CR1,SC-MY1,SC-U01,RO-U02,SC-SG1,SC-U11,RO-U11,RO-CN1,RO-RF1,RO-SG1,RO-U01,SH-E10,SH-P01,SA-E10,AB-BR2,SB-BR2,DB-MY1,BB-CN1,DB-CN1,GB-CN3,BB-CN3,DB-CN3,SB-CR1,AB-D01,IB-D01,SB-D01,AB-E10,AB-ID1,DB-ID1,BB-MX1,AB-MY2,IB-P01,BB-P01,AB-RF1,IB-T01,IB-TH1,SB-U01,HD-U01,GB-U01,CB-U03,HD-U03,IB-U03,DB-U11,GB-U11,DB-D01,DB-E10,DB-MY2,GB-P01,DB-RF1,GB-RF1,DB-T01,AB-TH1,AB-U01,GB-U02,BB-U03,HD-SG1,BB-BR2,IB-CR1,CB-CR1,BB-MY2,CB-MY2,AB-P01,BB-RF1,DB-SG1,GB-T01,DB-U02,AB-U10,HD-CN1,HD-D01,HD-MY1,DB-BR2,GB-CN1,BB-CR1,GB-E10,HD-ID1,DB-RD1,AB-CN3,IB-G01,GB-ID1,DB-MX1,GB-MY2,CB-P01,CB-RD1,AB-RD1,SB-RD1,CB-SG1,CB-TH1,SB-TH1,CB-TH2,AB-TH2,IB-U01,SB-U02,AB-U03,DB-U03,SB-U03,BB-U10,CB-U11,HD-MX1,HD-P01,HD-TH1,DB-TH1,CB-BR2,CB-CN3,AB-CR1,CB-D01,GB-D01,BB-G01,AB-G01,DB-G01,IB-ID1,SB-MX1,AB-MY1,IB-MY2,SB-P01,BB-SG1,IB-SG1,GB-SG1,BB-T01,BB-TH1,SB-TH2,AB-U02,CB-U10,HD-G01,HD-T01,HD-U02,IB-BR2,AB-CN1,DB-CR1,GB-CR1,IB-E10,SB-G01,BB-ID1,SB-ID1,SB-MY1,SB-RF1,CB-T01,AB-T01,IB-TH2,CB-U01,CB-U02,IB-U02,BB-U02,DB-U10,IB-U10,GB-U10,BB-U11,IB-U11,HD-CN3,AB-U11,HD-RD1,GB-BR2,CB-CN1,SB-CN1,IB-CN3,SB-E10,CB-G01,GB-G01,CB-MX1,AB-MX1,IB-MY1,CB-MY1,GB-MY1,SB-MY2,DB-P01,IB-RD1,IB-RF1,AB-SG1,SB-T01,GB-TH2,DB-U01,SB-U10,HD-E10,HD-CR1,HD-U10,HD-MY2,IB-CN1,SB-CN3,BB-D01,BB-E10,CB-E10,CB-ID1,IB-MX1,GB-MX1,BB-MY1,GB-RD1,BB-RD1,CB-RF1,SB-SG1,GB-TH1,BB-TH2,DB-TH2,BB-U01,GB-U03,SB-U11,HD-TH2";
					$reg_urb=explode(",",$str1);


					$reg_vac=array();
					$str2="BH-CN3,MB-CN3,HB-CN3,MB-CR1,BH-E01,BH-G01,BH-ID1,BH-MX1,HB-MY1,MB-MY2,BH-P01,MB-TH1,HB-U03,HB-U10,MP-MX1,HB-CN1,BH-CR1,HB-D01,HB-E10,HB-RF1,HB-TH2,BH-U10,BH-BR2,HB-CR1,MB-E10,HB-ID1,HB-RD1,MB-RD1,MB-RF1,HB-SG1,BH-TH1,HB-U01,BH-U01,MP-U02,BH-D01,HB-MY2,BH-RF1,MB-SG1,HB-T01,MB-T01,MB-U01,HB-U02,MB-U03,MB-U11,HB-BR2,MB-BR2,BH-CN1,BH-E10,MB-ID1,BH-MY2,HB-P01,HB-TH1,MB-TH2,MB-U10,HB-U11,MP-E10,MB-CN1,MB-G01,MB-MX1,BH-MY1,BH-RD1,BH-T01,BH-TH2,MB-U02,BH-U03,MB-D01,MB-MY1,MB-P01,BH-U02,BH-U11,MP-ID1,HB-G01,HB-MX1,BH-SG1,MP-P01,PB-BR2,FB-CN3,FH-CN3,PB-D01,FH-G01,FB-MY1,PC-P01,PB-RD1,FB-SG1,PB-TH1,FH-TH1,PB-U01,FB-U01,FB-U03,PB-U03,PB-U10,FB-MX1,FB-D01,FB-E10,FH-E10,PB-G01,FB-MY2,PB-CN1,FB-RF1,FH-SG1,FH-T01,FH-TH2,PB-U02,FH-U10,FH-D01,FH-BR2,FB-G01,FB-ID1,FH-RD1,FB-RD1,PB-SG1,PB-TH2,PC-E10,FB-TH1,FB-BR2,FB-CN1,PB-CR1,PB-MX1,FH-MY2,PB-MY2,PB-P01,FH-RF1,FB-T01,PB-T01,FB-TH2,FH-U01,FB-U02,FH-U03,FH-U11,FB-U11,PB-U11,FH-E01,PC-MX1,PC-U02,PB-CN3,FH-CR1,PB-E10,FH-MY1,FB-P01,FH-U02,FB-U10,PC-ID1,FH-CN1,FB-CR1,FH-ID1,PB-ID1,FH-MX1,PB-MY1,FH-P01,PB-RF1,AS-D01,AI-D01,AS-E10,AI-G01,AI-ID1,AI-SG1,AI-TH2,TI-U02,AI-U02,AS-CR1,AS-MX1,AS-MY1,AI-MY1,AI-RF1,AI-U10,TI-P01,AI-RD1,AI-E10,AI-MY2,AI-T01,AI-CR1,AI-MX1,AS-RD1,AI-TH1,AS-TH1,AS-U01,TG-E10,AI-CN1,AS-G01,AS-P01,AS-SG1,AS-T01,AI-U01,AI-U03,AI-BR2,AS-MY2,AS-RF1,AS-U03,AS-U10,AI-U11,TI-MX1,TI-ID1,AS-BR2,AS-CN1,AI-CN3,AS-CN3,AS-ID1,AI-P01,AS-TH2,AS-U02,AS-U11,TI-E10";
					$reg_vac=explode(",",$str2);
					

					if($auto_gestion==0){

					    script("$('#resultados_proveedores').append('Beds trae ". count($hoteles) ." Hoteles en ".$data[1]['total_time']." Segundos<br>')");
					}

					for($i=0;$i<count($hoteles);$i++){
						$datos=array();
						$habitaciones=array();

						if(isset($hoteles[$i]['AvailableRoom']['HotelOccupancy']['RoomCount']['value']) && strlen($hoteles[$i]['AvailableRoom']['HotelOccupancy']['RoomCount']['value'])>0){

							$habitaciones[0]=$hoteles[$i]['AvailableRoom'];

						}else{

							$habitaciones=$hoteles[$i]['AvailableRoom'];

						}
						

						$cont=0;
						for($x=0;$x<count($habitaciones);$x++){ 

							$mostrar=0;	

							if(isset($sin_filtros_regimen) && $sin_filtros_regimen==1){

								$mostrar=1;

							}else if($this->parametros['regimen']!=0 || $this->parametros['destino']=="" || $this->parametros['regimen']!=""){

								$mostrar=1;

							}else if($this->parametros['destino']==1 && in_array($habitaciones[$x]['HotelRoom']['Board']['attr']['code'],$reg_vac)){

								$mostrar=1;
								
							}else if($this->parametros['destino']==2 && in_array($habitaciones[$x]['HotelRoom']['Board']['attr']['code'],$reg_urb)){

								$mostrar=1;
								
							}

							
							if($mostrar==1){
								$cont++;
								$datos['precios'][$cont]['abrevProv']="BD";
								$datos['nombreHotel']=$hoteles[$i]['HotelInfo']['Name']['value'];
								$datos['codeHotel']=$hoteles[$i]['HotelInfo']['Code']['value'];
								$datos['estrellas']=$hoteles[$i]['HotelInfo']['Category']['value'];
								$datos['ciudad']=$hoteles[$i]['HotelInfo']['Destination']['Name']['value'];
								$datos['poblacion']=$hoteles[$i]['HotelInfo']['Destination']['ZoneList']['Zone']['value'];
								
								$datos['precios'][$cont]['codeHotel']=$hoteles[$i]['HotelInfo']['Code']['value'];
								$datos['precios'][$cont]['precio']=numFormato($habitaciones[$x]['HotelRoom']['Price']['NetPrice']['value']);
								$datos['precios'][$cont]['precioFormat']=$habitaciones[$x]['HotelRoom']['Price']['NetPrice']['value'];

								$datos['precios'][$cont]['totalFormat']=(($datos['precios'][$cont]['precioFormat']*$comision)/100) + $datos['precios'][$cont]['precioFormat'];

								$datos['precios'][$cont]['total']=numFormato($datos['precios'][$cont]['totalFormat']);
								
								$datos['precios'][$cont]['comision']=$comision;
								$datos['precios'][$cont]['nombreHab']=utf8_encode($habitaciones[$x]['HotelRoom']['RoomType']['value']);
								$datos['precios'][$cont]['habCaract']=$habitaciones[$x]['HotelRoom']['RoomType']['attr']['characteristic'];
								$datos['precios'][$cont]['codeHab']=$habitaciones[$x]['HotelRoom']['RoomType']['attr']['code'];
								
								$datos['precios'][$cont]['nombreReg']=$habitaciones[$x]['HotelRoom']['Board']['value'];
								$datos['precios'][$cont]['codeReg']=$habitaciones[$x]['HotelRoom']['Board']['attr']['code'];
								
								// vuelo aka
								$pre_offer=strpos($habitaciones[$x]['HotelRoom']['Board']['value'],"BEBIDAS");
								$datos['precios'][$cont]['offer']="";
								if($pre_offer!==false){
									$datos['precios'][$cont]['offer']=$habitaciones[$x]['HotelRoom']['Board']['value'];
								}

								// seccion de precios recomendados
								$find=array(".",",");
								$chng=array("",".");
								$datos['precios'][$cont]['recomendado']=str_replace($find,$chng,$datos['precios'][$cont]['total']);
								
								if($this->parametros['destino']==1){
									$porcentaje = 	($datos['precios'][$cont]['recomendado']*25)/100;
								}else if($this->parametros['destino']==2){
									$porcentaje = ($datos['precios'][$cont]['recomendado']*10)/100;
								}

								$datos['precios'][$cont]['recomendado']=$datos['precios'][$cont]['recomendado']+$porcentaje;


								$abreviaturas=$this->DB->select('abreviatura,id','_regimenes','id_bedsonline like "%'.$habitaciones[$x]['HotelRoom']['Board']['attr']['code'].'%"');

								
								
								$datos['precios'][$cont]['gastos']="";
								$datos['precios'][$cont]['abrevReg']=$abreviaturas[f][0]['abreviatura'];
								$datos['precios'][$cont]['ticket']=$habitaciones[$x]['HotelRoom']['attr']['SHRUI'];
								$datos['precios'][$cont]['proveedor']='id_bedsonline';
								$datos['precios'][$cont]['token']=$hoteles[$i]['attr']['availToken'];
								$datos['precios'][$cont]['ContractName']=$hoteles[$i]['ContractList']['Contract']['Name']['value'];
								$datos['precios'][$cont]['IncomingOffice']=$hoteles[$i]['ContractList']['Contract']['IncomingOffice']['attr']['code'];
								$datos['proveedor']='id_bedsonline';
								
								$datos['precios'][$cont]['unique']=$datos['precios'][$cont]['token'].$datos['precios'][$cont]['ticket'];

								if(isset($datos['precio']) && (numFormatoMysql($datos['precio']) > $datos['precios'][$cont]['totalFormat']) || $cont==1){
									$datos['precio']=$datos['precios'][$cont]['total'];
									$datos['precioFormat']=$datos['precios'][$cont]['totalFormat'];
								}
							}
								
						}


						if(isset($_SESSION['ip_admin'])){

							$this->preparar_sql = $this->DB->link->prepare($this->sql_listados);
							$this->preparar_sql->bind_param('s', $datos['codeHotel']);
							$this->arrayHoteles($datos);
							$this->preparar_sql->close();

						}else{

							$this->arrayHoteles($datos);

						}
						
					}
				break;

//////////////////////

				case $this->dir_veturis;

				$resultado=$this->xml2array($data[0]);

				//$response=$this->veturis->disponibilidad();

				$this->sql_listados = 'select id,estado from _hoteles where id_veturis like ?';



					if(isset($resultado['resultadosRS']['MultiplesResults'])  || count($resultado)==0 || isset($resultado['ResponseDetails']['Status']) && $resultado['ResponseDetails']['Status']=="KO"){

						if($auto_gestion==0){

							script("$('#resultados_proveedores').append('Vetruris sin disponibilidad o debido a un error<br>')");
						}

						break;
					}



					if(isset($resultado['resultadosRS']['Response']['obj'])){

						$comision=$this->veturis->comision;
						$obj=$resultado['resultadosRS']['Response']['obj']['value'];
						$response=$resultado['resultadosRS']['Response']['Hotels'];
						$n_hoteles=$resultado['resultadosRS']['Response']['AvailablesHotel']['value'];
						
					}


					if($n_hoteles>1){

						
						$a_hoteles=$response['Hotel'];

					}else if($n_hoteles==1){

						$a_hoteles[0]=$response['Hotel'];

					}

					// los hoteles que tenemos hasta aqui es correcto-
					  
					  // echo "<br> el obj es  " . $obj;
	if($auto_gestion==0){
	 
	 	script("$('#resultados_proveedores').append('Veturis trae ".$n_hoteles." Hoteles en ".$data[1]['total_time']." Segundos<br>')");
	}
	
	for($h=0;$h<$n_hoteles;$h++){
		// giraremos sobre los hoteles, entre las habitaciones y los regimenes 
		// tengo que conseguir un acceso directo y diferenciarlo para qe no me 
		// suceda el error anterior. 
	$id_hotel[$h]="";	
	if(isset($a_hoteles[$h]['HotelDetails']['ID']['value'])){
		$id_hotel[$h]=$a_hoteles[$h]['HotelDetails']['ID']['value'];
	}
 // echo "<br> Este es el ID $h - " .$id_hotel[$h];


// con este bloque vamos a evitar que si el hotel no se encuentra mapeado
// estemos girando sobre el.
// $this->DB->query('select nombre from _hoteles where id_veturis='.$id_hotel[$h]);
// $mapeado=$this->DB->rows();


// if($mapeado==1){

 // echo " y si se encuentra en la BBDD ";
// LO QUE TENEMOS QUE HACER AQUI, ES ENCERRAR CADA UNO DE LOS REGIMENES EXISTENTES 
// DENTRO DE CADA UNA DE LAS HABITACIONES EXISTENTES COMO SI FUERA UNA SOLA UNIDAD.

// CON UN BUCLE PRINCIPAL QUE PASE POR EL ['Room'][$X] Y LUEGO UNO DENTRO DE 
if(isset($a_hoteles[$h]['Accommodations']['Room'][0])){


		// teniendo varios Room dentro de un hotel
		// tenemos que sacar la info llegando a ella con un bucle.
		
		// con esta $ conseguimos el nº de veces que vamos a girar sobre las 
		// room que contenga este hote y poder llegar a cada uno de los boards 
		$rooms[$h]=count($a_hoteles[$h]['Accommodations']['Room']);
		

		// esta la creamos y la ponemos a 0, parq eu cuando se empiece a sumar con sigo mismo
		// + abajo, eliminemos el warning de la primera vuelta.
		$n_habitaciones[$h]=0;

		// esta solo la vamos a utilizar, en el momento en el que haya mas de 1 board dentro
		// de cada uno de los rooms y lo usamos como la segunda dimendion del $a_habitaciones
		$a_aumento=0;

			for($r=0;$r<$rooms[$h];$r++){

					if(isset($a_hoteles[$h]['Accommodations']['Room'][$r]['Board'][0])){

//en este caso del if, tenemos mas de 1 borad dentro del room en concreto sobre el que vamos a 
//girar, 
						// contamos los regiemenes de cada room para iterarlos abajo en el bucle.
						$n_regimen[$r]=count($a_hoteles[$h]['Accommodations']['Room'][$r]['Board']);


						// aqui iteramos los regiemenes del room y autoincrementamos el $a_aumento
						// unificando la 2ª dimension del a_habitaciones
							for($b=0;$b<$n_regimen[$r];$b++){

								$name_hb[$h][$a_aumento]=$a_hoteles[$h]['Accommodations']['Room'][$r]['RoomType'];
								$a_habitaciones[$h][$a_aumento]=$a_hoteles[$h]['Accommodations']['Room'][$r]['Board'][$b];
								$a_aumento++;
							 }

							 // aqui sumammos el nº final de cada board, convirtiendolo asi
							 // en lo que luego consideraremos room.
							$n_habitaciones[$h]=$n_habitaciones[$h]+$n_regimen[$r];

					}else if(!isset($a_hoteles[$h]['Accommodations']['Room'][$r]['Board'][0])){

						$n_habitaciones[$h]++;
						$a_habitaciones[$h][$a_aumento]=$a_hoteles[$h]['Accommodations']['Room'][$r]['Board'];
						$name_hb[$h][$a_aumento]=$a_hoteles[$h]['Accommodations']['Room'][$r]['RoomType'];
						$a_aumento++;

					}

			// eof 	for($r=0;$r<$rooms[$h];$r++){
			}

		// ahora aqui dentro de cada una de las rooms puede haber 1 o varios boards.

// eof if(isset($a_hoteles[$h]['Accommodations']['Room'][0])){
}else if(!isset($a_hoteles[$h]['Accommodations']['Room'][0])){
 // echo " Entramos 3 ";
// AQUI ESTAMOS ENCERRANDO CUANDO EL HOTEL QUE PASAMOS POR AQUI
	// SOLO TRAE UNA <Room> CONTENIENDO UNO A MAS REGIMENES.

	if(isset($a_hoteles[$h]['Accommodations']['Room']['Board'][0])){
 // echo " Entramos 4 ";
		$n_habitaciones[$h]=count($a_hoteles[$h]['Accommodations']['Room']['Board']);
		$a_habitaciones[$h]=$a_hoteles[$h]['Accommodations']['Room']['Board'];
		$name_hb[$h][0]=$a_hoteles[$h]['Accommodations']['Room']['RoomType'];


	}else if(!isset($a_hoteles[$h]['Accommodations']['Room']['Board'][0])){
 // echo " Entramos 5 ";
		$n_habitaciones[$h]=1;
		$a_habitaciones[$h][0]=$a_hoteles[$h]['Accommodations']['Room']['Board'];
		$name_hb[$h][0]=$a_hoteles[$h]['Accommodations']['Room']['RoomType'];
		
	}


// eof }else if(!isset($a_hoteles[$h]['Accommodations']['Room'][0])){
}

// // echo " con estas habitaciones " . $n_habitaciones[$h];


// ahora aqui tenemos que meter el urb/vac + mostrar 
// montando el bucle del nº de habkitaciones, que ya he sacado las vueltas necesarias 
// justo arriba.

$cont=0;
	for($hb=0;$hb<$n_habitaciones[$h];$hb++){

		
		// DE PRIMERAS MARCAMOS TODOS COMO VISIBLES YA SEAN PARA ADMIN O CLIENTES
		$mostrar=1;	
        $urbVac=1;	
		$regimenes[$hb]=$a_habitaciones[$h][$hb]['Board_type']['ID']['value'];


		if(!isset($sin_filtros_regimen) || isset($sin_filtros_regimen) && $sin_filtros_regimen==0){
			// aqui quitamos todo lo urbano vacacional o en su caso si veien de pago directo
			// ya que no contemplamos la posibilidad de este tipo de pago.
			if($this->parametros['regimen']!='0' &&
				strpos($this->parametros['regimen'],"-")){
				
				//// BUSQUEDAS MULTYREGIMEN
				// 1º evaluo el destino:
				//2º compruebo que el regimen este contenido en los validos
				//3º asigno mostrar

					if($this->parametros['destino']==1){
										
							$buscar='no_buscar';

							if($regimenes[$hb]==3){
								$buscar='MP';
							}else if($regimenes[$hb]==4){
								$buscar='PC';
							}else if($regimenes[$hb]==5){
								$buscar='TI';
							}
											
							$comprobar_regimen=strpos($this->parametros['regimen'],$buscar);

							if($comprobar_regimen===false){
								$mostrar=0;
							}

									   	
					}if($this->parametros['destino']==2){ // if($this->parametros['destino']==1){
											
							$buscar='no_buscar';
							if($regimenes[$hb]==1){
								$buscar='SA';
							}else if($regimenes[$hb]==2){
								$buscar='AD';
							}

							$comprobar_regimen=strpos($this->parametros['regimen'],$buscar);

							if($comprobar_regimen===false){
								$mostrar=0;
							}
									   	
					}// }if($this->parametros['destino']==2){


				// eof }else if($this->parametros['regimen']!='0'&&
				// strpos($this->parametros['regimen'],"-"){
				}else if(($this->parametros['destino']==2 && $regimenes[$hb]!=1 && $regimenes[$hb]!=2) 
				|| ($this->parametros['destino']==1 && $regimenes[$hb]!=3 && $regimenes[$hb]!=4 && $regimenes[$hb]!=5)){

					$urbVac=0;
					$mostrar=0;
				}
				
			} // if(!isset($sin_filtros_regimen) || iss

			if(isset($a_regimenes[$hb]['DirectPayment']['value']) && $a_regimenes[$hb]['DirectPayment']['value']==1){

				$urbVac=0;
				$mostrar=0;
			}

			///////*****************************************//
			// 	indicamos que el array:						//
		    //												//
		    //	$datos[xxx] es para pasarlo a 				//
		    // 	$this->arrayHoteles($datos);				//
		    //												//
		    //	$datos['precios'][$cont] es para sacarlo	//
		    //	en el listado de los hoteles.				//
		    //												//
		    //												//
		    ///////*****************************************//

		    // 	if(isset($_SESSION['admin_ok']) && $_SESSION['admin_ok']==1){
      //                   //$_SESSION['admin_ok']=1; 
						// $mostrar=1;
      //           }	

				if($urbVac==1 && $mostrar==1){
		 			
				// este es el if de mostrar, aqui es donde vamos a crear todas las $
					// que luego vamos a mostrar en los listados.


					$datos['precios'][$cont]['abrevProv']="VT";
					$datos['proveedor']="id_veturis";
					$datos['precios'][$cont]['proveedor']="id_veturis";

					$datos['codeHotel']=$a_hoteles[$h]['HotelDetails']['ID']['value'];
					$datos['nombreHotel']=$a_hoteles[$h]['HotelDetails']['Name']['value'];
			
					$datos['precios'][$cont]['codeHotel']=$a_hoteles[$h]['HotelDetails']['ID']['value'];
					$datos['precios'][$cont]['obj']=$obj;


					// añadimos este if, por si la distribucion solicitada es diferente de 1.
					// la $name_hb[$h][$hb] ya trae consigo el roomtype.
	
                    if(isset($name_hb[$h][$cont][0]['Name']['value'])){

                        $datos['precios'][$cont]['nombreHab']=$name_hb[$h][$cont][0]['Name']['value'];
                        // $amenities[$h][0]=$name_hb[$h][$cont][0];

                    }else if(!isset($name_hb[$h][$cont][0]['Name']['value']) && isset($name_hb[$h][$cont]['Name']['value'])){

                       
                        // nombre de la habitacion
                        $datos['precios'][$cont]['nombreHab']=$name_hb[$h][$cont]['Name']['value'];

                        // nuevo arreglo de los amenities
                        if(isset($name_hb[$h][$cont]['Amenities']['Amenity']['ID']['value'])){

                            $this->DB->query('select name from _amenities_veturis where id_veturis='.$name_hb[$h][$cont]['Amenities']['Amenity']['ID']['value']);

                                $amenity=$this->DB->fetch();

                            $datos['precios'][$cont]['nombreHab'].=" ".utf8_encode($amenity['name']);

                        }else if(isset($name_hb[$h][$cont]['Amenities']['Amenity'][0]['ID']['value'])){

                            $n_amenities=count($name_hb[$h][$cont]['Amenities']['Amenity']);
                            $a_amenities=$name_hb[$h][$cont]['Amenities']['Amenity'];


                            for($am=0;$am<$n_amenities;$am++){

                                $this->DB->query('select name from _amenities_veturis where id_veturis='.$a_amenities[$am]['ID']['value']);

                                $amenity=$this->DB->fetch();

                                $datos['precios'][$cont]['nombreHab'].=" ".utf8_encode($amenity['name']);

                            // eof for($am=0;$am<=$n_amenities;$am++){
                            }

                        }
                        // $amenities[$h][0]=$name_hb[$h][0];

                    }
				// // echo "la habitacion se llama ".$datos['precios'][$cont]['nombreHab'];

					$abrev = array(
						"1"=>array("nombre"=>"Solo Alojamiento","abreviatura"=>"SA"),
						"2"=>array("nombre"=>"Alojamiento y Desayuno","abreviatura"=>"AD"),
						"3"=>array("nombre"=>"Media Pension","abreviatura"=>"MP"),
						"4"=>array("nombre"=>"Pension Completa","abreviatura"=>"PC"),
						"5"=>array("nombre"=>"Todo incluido","abreviatura"=>"TI"),
						"6"=>array("nombre"=>"Media Pension","abreviatura"=>"MP"),
						"7"=>array("nombre"=>"Media Pension","abreviatura"=>"MP"),
						"8"=>array("nombre"=>"Media Pension","abreviatura"=>"MP"),
						"9"=>array("nombre"=>"Solo Alojamiento","abreviatura"=>"SA"),
						"10"=>array("nombre"=>"Pension Completa","abreviatura"=>"PC"),
						"11"=>array("nombre"=>"Media Pension","abreviatura"=>"MP"),
						"12"=>array("nombre"=>"Pension Completa","abreviatura"=>"PC"), 
						"13"=>array("nombre"=>"Alojamiento y Desayuno","abreviatura"=>"AD"), 
						"14"=>array("nombre"=>"Media Pension","abreviatura"=>"MP"), 
						"15"=>array("nombre"=>"Media Pension","abreviatura"=>"MP"), 
						"16"=>array("nombre"=>"Media Pension","abreviatura"=>"MP"), 
						"17"=>array("nombre"=>"Media Pension","abreviatura"=>"MP"), 
						"18"=>array("nombre"=>"Media Pension","abreviatura"=>"MP"), 
						"19"=>array("nombre"=>"Media Pension","abreviatura"=>"MP"), 
						"20"=>array("nombre"=>"Media Pension","abreviatura"=>"MP"), 
						"21"=>array("nombre"=>"Media Pension","abreviatura"=>"MP"), 
						"22"=>array("nombre"=>"Media Pension","abreviatura"=>"MP"),
						"23"=>array("nombre"=>"Pension Completa","abreviatura"=>"PC"), 
						"24"=>array("nombre"=>"Todo incluido","abreviatura"=>"TI")
					);


					$abreviatura = isset($abrev[$regimenes[$hb]]) ? $abrev[$regimenes[$hb]] : 1;

					if($abreviatura==""){

						$abreviatura['nombre']="Solo Alojamiento";
						$abreviatura['abreviatura']="AD";

					}

					$datos['precios'][$cont]['codeReg']=$regimenes[$hb];
					$datos['precios'][$cont]['nombreReg']=$abreviatura['nombre'];
					$datos['precios'][$cont]['abrevReg']=$abreviatura['abreviatura'];
					 // echo " con la breviatura " . $datos['precios'][$cont]['abrevReg'];

					// ********** $a_habitaciones[$h][$hb] ESTE ES EL REPREENTANTE DE :
					// <Board> POR LO QUE AQUI DENTRO TENEMOS TODOS LOS VALORES 
					// QUE NECESITAMOS PARA PODER SACAR A LA LUZ.
					// aqui tenemos el tag de vinvulantes, para cuando deseemos/necesitemos cogerlo.
					//$a_regimenes[$rg]['Price']['attr']['mandatory'];

					$comision_sumar=$a_habitaciones[$h][$hb]['PriceAgency']['value']/100*$comision;

					//ALL TAGS ARE MANDATORY FOR $this->arrayHoteles($datos)
					$datos['precios'][$cont]['totalFormat']=$a_habitaciones[$h][$hb]['PriceAgency']['value']+$comision_sumar;
					$datos['precios'][$cont]['precio']=$a_habitaciones[$h][$hb]['PriceAgency']['value'];
					$datos['precios'][$cont]['total']=numFormato($datos['precios'][$cont]['totalFormat']);
					$datos['precio']=$a_habitaciones[$h][$hb]['PriceAgency']['value'];
					$datos['precioFormat']=$datos['precios'][$cont]['totalFormat'];
					$datos['precios'][$cont]['precioFormat']=$datos['precios'][$cont]['total'];

					//echo "<br>".$datos['precios'][$cont]['precio'];
					// seccion de precios recomendados
					$find=array(".",",");
					$chng=array("",".");
					$datos['precios'][$cont]['recomendado']=str_replace($find,$chng,$datos['precios'][$cont]['total']);
				

					if($this->parametros['destino']==1){
						$porcentaje = ($datos['precios'][$cont]['recomendado']*25)/100;
					}else if($this->parametros['destino']==2){
						$porcentaje = ($datos['precios'][$cont]['recomendado']*10)/100;
					}

					$datos['precios'][$cont]['recomendado']=$datos['precios'][$cont]['recomendado']+$porcentaje;
					$datos['precios'][$cont]['offer']="";

					if($a_habitaciones[$h][$hb]['Refundable']['value']=="N"){
						$datos['precios'][$cont]['offer']="Oferta No Reembolsable";
					}

					// hay que sacar los gastos vacios 
					$datos['precios'][$cont]['gastos']="";
					$datos['precios'][$cont]['unique']=$a_habitaciones[$h][$hb]['DATOS']['value'];
					$datos['precios'][$cont]['DATOS']=$a_habitaciones[$h][$hb]['DATOS']['value'];

				// echo " y su dato es " . $datos['precios'][$cont]['DATOS'];


	//*********************************************************//

						$cont++;
						
						} // eof if(($urbVac==1 || ....
						//isset($_SESSION['admin_ok']) && $_SESSION['admin_ok']==1)...

						// if(isset($_SESSION['ip_admin'])){

							$this->preparar_sql = $this->DB->link->prepare($this->sql_listados);
							$this->preparar_sql->bind_param('s', $datos['codeHotel']);
							$this->arrayHoteles($datos);
							$this->preparar_sql->close();

					// }else{

					// 	$this->arrayHoteles($datos);

					// }

				//} // if(isset($datos)){
	
			} //eof for($hb=0;$hb<$n_habitaciones[$h];$hb++){
			
		//}// eof if($mapeado==1)

	}//for($h=0;$h<$n_hoteles;$h++){


 	break;


			// eof switch($dir){
			}

		// EOF public function listados($dir,$data){
		}

			public function formatearGastos($dir,$data){

			$resultado=array();
			switch($dir){				
				 
				case $this->dir_versys:


					$arrayRespuesta=$this->xml2array($data[0]);

					// echo $data[0];

					$resultado = $arrayRespuesta['soap:Envelope']['soap:Body']['OTA_HotelBookingRuleServiceResponse']['OTA_HotelBookingRuleRS'];

	               if(isset($resultado['Errors']['ErrorType']) 
	                || count($arrayRespuesta)==0 
	               	|| isset($resultado['Warnings']['WarningsType']['Warning']['attr']['Type']) 
	               	&& $resultado['Warnings']['WarningsType']['Warning']['attr']['Type']!=1){

	               		$resultado['textos_cancelaciones']="Sin datos disponibles, para concer los gastos de cancelaci&oacute;n pulse Reservar.";
							
						break;		
	            	} // if(isset($resultado['Errors']['ErrorType']) 


					$comision=$this->versys->comision;
					$arrayRespuesta=$resultado['RuleMessage'];
					$resultado['textos_cancelaciones']="";

					if(isset($arrayRespuesta['BookingRules']['BookingRule']['attr']['AbsoluteCutoff'])){

						$resultado['fechaDcancelacion']=$arrayRespuesta['BookingRules']['BookingRule']['attr']['AbsoluteCutoff'];

						list($resultado['fechaDcancelacion'],$resultado['horaDcancelacion'])=explode("T",$resultado['fechaDcancelacion']);

						list($añoc,$mesc,$diac)=explode("-",$resultado['fechaDcancelacion']);

						$resultado['fechaDcancelacion']=$diac."/".$mesc."/".$añoc;

					}
					


					if(isset($arrayRespuesta['BookingRules']['BookingRule']['TPA_Extensions']['CancellationPolicyRules']['attr']['nonRefundable']) && $arrayRespuesta['BookingRules']['BookingRule']['TPA_Extensions']['CancellationPolicyRules']['attr']['nonRefundable']=="True"){

						$resultado['textos_cancelaciones'].="Oferta No Reembolsable. No Admite cambios ni cancelaciones.";

						break;

               		}else if(isset($arrayRespuesta['BookingRules']['BookingRule']['TPA_Extensions']['CancellationPolicyRules']['Rule'][0]['attr']['DateFrom'])){

						$cancelaciones=$arrayRespuesta['BookingRules']['BookingRule']['TPA_Extensions']['CancellationPolicyRules'];

               		}else if(isset($cancelacion['CancellationPolicyRules']['Rule'])){

						$cancelaciones['Rule'][0]=$cancelacion['CancellationPolicyRules']['Rule'];

               		}else{

               			$cancelaciones = 0;
               		}


// aqquuii			
               		if($cancelaciones==0){

               			$precio_entero = $arrayRespuesta['BookingRules']['BookingRule']['TPA_Extensions']['TotalPrice']['value'];
						$porcentaje = ($precio_entero*$comision)/100;
						$precio_entero+=$porcentaje;
               			

               			$resultado['textos_cancelaciones']="Cancelando desde el ".date('d/m/Y'). "  un importe de " . numFormato($precio_entero)."<br>";

               		}else{

	               		//$resultado['textos_cancelaciones'].="Puedes Cancelar Gratis antes del ".$resultado['fechaDcancelacion']. " a las " . $resultado['horaDcancelacion']. "<br>";

						for($i=0;$i<count($cancelaciones);$i++){


							$importe_cancelacion[$i]=$cancelaciones['Rule'][$i]['attr']['FixedPrice'];
							$noches_cancelacion[$i]=$cancelaciones['Rule'][$i]['attr']['Nights'];
							$percent_cancelacion[$i]=$cancelaciones['Rule'][$i]['attr']['PercentPrice'];

						  	$HyFcancelacion[$i]=$cancelaciones['Rule'][$i]['attr']['DateFrom'];            
							list($fecha[$i],$hora[$i])=explode("T",$HyFcancelacion[$i]);
							$fecha[$i]=formatear_fecha($fecha[$i],m,i);



							if($percent_cancelacion[$i]>0){

								$resultado['textos_cancelaciones'].="Cancelando desde el ".$fecha[$i]. "  un " . $percent_cancelacion[$i]."% sobre el total de la Reserva.<br>";

							}else if($importe_cancelacion[$i]>0){ // if($percent_cancelacion[$i]>0){

								$importe_cancelacion[$i]=str_replace(".", ",",$importe_cancelacion[$i]);
								$importe_cancelacion[$i]=numFormatoMysql($importe_cancelacion[$i]);
								$importe_final[$i]=(($importe_cancelacion[$i]*$comision)/100)+$importe_cancelacion[$i];
								$importe_final[$i]=numFormato($importe_final[$i]);

								$resultado['textos_cancelaciones'].="Cancelando desde el ".$fecha[$i]. "  un importe de " . $importe_final[$i]."<br>";

							}else if($noches_cancelacion[$i]>0){

								$resultado['textos_cancelaciones'].="Cancelando desde el ".$fecha[$i]. "  el importe equivalente  " . $noches_cancelacion[$i]." noche/s.<br>";

							}// }else if($importe_cancelacion[$i]>0){

						}	// for($i=0;$i<count($cancelaciones);$i++){
					}

				break;

/////////////////////////////	

				case $this->dir_solole:

					$arrayRespuesta=$this->xml2array($data[0]);

					// echo $data[0];

					$resultado = $arrayRespuesta['soap:Envelope']['soap:Body']['OTA_HotelBookingRuleServiceResponse']['OTA_HotelBookingRuleRS'];

	               if(isset($resultado['Errors']['ErrorType']) 
	                || count($arrayRespuesta)==0 
	               	|| isset($resultado['Warnings']['WarningsType']['Warning']['attr']['Type']) 
	               	&& $resultado['Warnings']['WarningsType']['Warning']['attr']['Type']!=1){

	               		$resultado['textos_cancelaciones']="Sin datos disponibles, para concer los gastos de cancelaci&oacute;n pulse Reservar.";
							
						break;		
	            	} // if(isset($resultado['Errors']['ErrorType']) 


					$comision=$this->solole->comision;
					$arrayRespuesta=$resultado['RuleMessage'];
					$resultado['textos_cancelaciones']="";
					$resultado['fechaDcancelacion']=$arrayRespuesta['BookingRules']['BookingRule']['attr']['AbsoluteCutoff'];

					list($resultado['fechaDcancelacion'],$resultado['horaDcancelacion'])=explode("T",$resultado['fechaDcancelacion']);

					list($añoc,$mesc,$diac)=explode("-",$resultado['fechaDcancelacion']);

					$resultado['fechaDcancelacion']=$diac."/".$mesc."/".$añoc;



					if(isset($arrayRespuesta['BookingRules']['BookingRule']['TPA_Extensions']['CancellationPolicyRules']['attr']['nonRefundable']) && $arrayRespuesta['BookingRules']['BookingRule']['TPA_Extensions']['CancellationPolicyRules']['attr']['nonRefundable']=="True"){

						$resultado['textos_cancelaciones'].="Oferta No Reembolsable. No Admite cambios ni cancelaciones.";

						break;

               		}else if(isset($arrayRespuesta['BookingRules']['BookingRule']['TPA_Extensions']['CancellationPolicyRules']['Rule'][0]['attr']['DateFrom'])){

						$cancelaciones=$arrayRespuesta['BookingRules']['BookingRule']['TPA_Extensions']['CancellationPolicyRules'];

               		}else{

						$cancelaciones[0]=$arrayRespuesta['BookingRules']['BookingRule']['TPA_Extensions']['CancellationPolicyRules'];

               		}

               		$resultado['textos_cancelaciones'].="Puedes Cancelar Gratis antes del ".$resultado['fechaDcancelacion']. " a las " . $resultado['horaDcancelacion']. "<br>";




					for($i=0;$i<count($cancelaciones);$i++){


						$importe_cancelacion[$i]=$cancelaciones['Rule'][$i]['attr']['FixedPrice'];

						$percent_cancelacion[$i]=$cancelaciones['Rule'][$i]['attr']['PercentPrice'];
					  	$HyFcancelacion[$i]=$cancelaciones['Rule'][$i]['attr']['DateFrom'];

					               
						list($fecha[$i],$hora[$i])=explode("T",$HyFcancelacion[$i]);
						list($year[$i],$mes[$i],$dia[$i])=explode("-",$fecha[$i]);
						$fecha[$i]=$dia[$i]."/".$mes[$i]."/".$year[$i];


						if($percent_cancelacion[$i]>0){

							$resultado['textos_cancelaciones'].="Cancelando desde el ".$fecha[$i]. " a las ".$hora[$i]. " un " . $percent_cancelacion[$i]."% sobre el total de la Reserva.<br>";

						}else if($importe_cancelacion[$i]>0){ // if($percent_cancelacion[$i]>0){

							$importe_cancelacion[$i]=str_replace(".", ",",$importe_cancelacion[$i]);
							$importe_cancelacion[$i]=numFormatoMysql($importe_cancelacion[$i]);
							$importe_final[$i]=(($importe_cancelacion[$i]*$comision)/100)+$importe_cancelacion[$i];
							$importe_final[$i]=numFormato($importe_final[$i]);

							$resultado['textos_cancelaciones'].="Cancelando desde el ".$fecha[$i]. " a las ".$hora[$i]. " un importe de " . $importe_final[$i]."<br>";
						}// }else if($importe_cancelacion[$i]>0){

					}	// for($i=0;$i<count($cancelaciones);$i++){



				break;



				case $this->dir_europlayas:

				// ##### IMPORTANTE ########//
				#
				#
				# HE COMENTADO TODO LO RELACIONADO CON + DE 1 HB 
				# WHEN LO SOLUCIONE, TENGO QUE PASAR POR AQUI TAMBIEN.
				#
				#

				$arrayRespuesta=$this->xml2array($data[0]);

               	if(isset($arrayRespuesta['PublisherError']) || isset($arrayRespuesta['BloqueoServicioRespuesta']['coderr']) || count($arrayRespuesta)==0){

					
               			$resultado['textos_cancelaciones']="Sin datos disponibles, para concer los gastos de cancelaci&oacute;n pulse Reservar.";
						break;	
            	}


				$arrayRespuesta=$arrayRespuesta['BloqueoServicioRespuesta'];
				$resultado['prereserva']=0;	
				$resultado['neto']=$arrayRespuesta['impnoc']['value'];            
				$resultado['netoFormato']=numFormatoMysql($resultado['neto']);
				$resultado['totalFormateado']=(($resultado['netoFormato']*$this->europlayas->comision)/100)+$resultado['netoFormato'];
				$resultado['total']=numFormato($resultado['totalFormateado']);
				$resultado['comision']=$this->europlayas->comision;
				$idHb=$arrayRespuesta['resser']['attr']['id'];
				$numHb=explode("-",$idHb);
				$numHb=count($numHb);

				if($numHb==1){

					if(isset($arrayRespuesta['resser']['estsmo']['rstcan'][0]['feccan']['value'])){

						$resultado['fechaDcancelacion']=$arrayRespuesta['resser']['estsmo']['rstcan'][0]['feccan']['value'];

					}else if(isset($arrayRespuesta['resser']['estsmo']['rstcan']['feccan']['value'])){

						$resultado['fechaDcancelacion']=$arrayRespuesta['resser']['estsmo']['rstcan']['feccan']['value'];
					}

				} // if($numHb==1){

				$resultado['horaDcancelacion']='00:00 ';

				$resultado['total_mayorista']=$resultado['total'];
				$resultado['codeHotel']=$arrayRespuesta['resser']['codser']['value'];

				$resultado['entradan']=$arrayRespuesta['resser']['fecini']['value'];
				list($fecha,$hora)=explode(" ",$resultado['entradan']);
				$resultado['entradan']=$fecha;

				$resultado['entrada']=$arrayRespuesta['resser']['fecini']['value'];
				list($resultado['entrada'],$hora)=explode(" ",$resultado['entrada']);

				$resultado['salidan']=$arrayRespuesta['resser']['fecfin']['value'];
				list($fecha,$hora)=explode(" ",$resultado['salidan']);
				$resultado['salidan']=$fecha;

				$resultado['salida']=$arrayRespuesta['resser']['fecfin']['value'];
				list($resultado['salida'],$hora)=explode(" ",$resultado['salida']);

				$resultado['noches']=comparar_fechas($resultado['entradan'],$resultado['salidan'],i);
				            
				$resultado['proveedor']="id_europlayas";
					
				if($numHb==1){
				//si el n de hb es solo 1

					if(isset($arrayRespuesta['resser']['estsmo']['rstcan']['feccan']['value'])){
	
					// aqui cuando el nº de cancelaciones es solo 1
						
						$ncancelaciones=1;
						$cancelaciones[0]=$arrayRespuesta['resser']['estsmo']['rstcan'];

						$aplicacion=$cancelaciones[0]['aplcan']['value'];


						//echo "resultado total es " . $resultado['total'];

						if($aplicacion=="T"){


							if(isset($cancelaciones[0]['impcan']['value'])){

								$clxFormato=numFormatoMysql($cancelaciones[0]['impcan']['value']);
								$totalFormateado=(($clxFormato*$this->europlayas->comision)/100)+$clxFormato;
								$cancelaciones[0]['total']['value']=numFormato($totalFormateado);

							}else if(isset($cancelaciones[0]['porcan']['value'])){

								$cancelaciones[$x]['total']['value']=number_format(str_replace(',','.',$resultado['total'])*$cancelaciones[0]['porcan']['value']/100,"2",".","");

							}

							$resultado['textos_cancelaciones'] = "Desde el " . $cancelaciones[0]['feccan']['value'] . " un importe de " . $cancelaciones[0]['total']['value'] . "&euro;";

						//la P sobre la primera noche.
						}else if($aplicacion=="P"){ // if($aplicacion=="T"){

							//tenemos que sacar el nº de noches y dividir el total sobre este.
							// luego aplicarle el % al result

							if(isset($cancelaciones[0]['impcan']['value'])){
								// si existe importe lo tramaos como arriba.
								$clxFormato=numFormatoMysql($cancelaciones[0]['impcan']['value']);
								$totalFormateado=(($clxFormato*$this->europlayas->comision)/100)+$clxFormato;
								$cancelaciones[0]['total']['value']=numFormato($totalFormateado);

							}else if(isset($cancelaciones[0]['porcan']['value'])){

								// en caso contrario, saco el importe de cada noche.
								$importeNoches=str_replace(',','.',$resultado['total'])/$resultado['noches'];
								// lo multiplico por el numero de noches.
								$cancelaciones[0]['total']['value']=number_format($importeNoches*$cancelaciones[0]['impcan']['value'],"2",".","");

							}

							$resultado['textos_cancelaciones'] = "Desde el " . $cancelaciones[0]['feccan']['value'] . " un importe de " . $cancelaciones[0]['total']['value'] . "&euro;";

							echo $resultado['textos_cancelaciones'];
						} // }else if($aplicacion=="P"){


					//aqui entramos si el nº de cancelaciones es superior de 1
					}else if(isset($arrayRespuesta['resser']['estsmo']['rstcan'][0]['feccan']['value'])){

						$ncancelaciones=count($arrayRespuesta['resser']['estsmo']['rstcan']);

						//echo "n de cancelaciones " . $ncancelaciones;

						for($x=0;$x<$ncancelaciones;$x++){

							$cancelaciones[$x]=$arrayRespuesta['resser']['estsmo']['rstcan'][$x];
							
							$aplicacion[$x]=$arrayRespuesta['resser']['estsmo']['rstcan'][$x]['aplcan']['value'];



							if($aplicacion[$x]=="T"){


							if(isset($cancelaciones[$x]['impcan']['value'])){

								$clxFormato=numFormatoMysql($cancelaciones[$x]['impcan']['value']);
								$totalFormateado=(($clxFormato*$this->europlayas->comision)/100)+$clxFormato;
								$cancelaciones[$x]['total']['value']=numFormato($totalFormateado);

							}else if($cancelaciones[$x]['porcan']['value']){

								$cancelaciones[$x]['total']['value']=number_format(str_replace(',','.',$resultado['total'])*$cancelaciones[$x]['porcan']['value']/100,"2",".","");

							}

							if($x>0){
								$resultado['textos_cancelaciones']="&#10;";
							}


							$resultado['textos_cancelaciones'] = "Desde el " . $cancelaciones[$x]['feccan']['value'] . " un importe de " . $cancelaciones[$x]['total']['value'] . "&euro;";



							//la P sobre la primera noche.
							}else if($aplicacion=="P"){ // if($aplicacion=="T"){

								//tenemos que sacar el nº de noches y dividir el total sobre este.
								// luego aplicarle el % al resultado.

								$nDnoches=$resultado['noches'];
								//echo "noches " . $nDnoches;

								$importeNoches=str_replace(',','.',$resultado['total'])/$resultado['noches'];
								//echo "importe noches es " . $importeNoches;
								//$cancelaciones[0]['total']['value']=$importeNoches*$porcentaje/100;
								$cancelaciones[$x]['total']['value']=number_format($importeNoches*$porcentaje/100,"2",".","");


							} // }else if($aplicacion=="P"){

						} // for($x=0;$x<$ncancelaciones;$x++){

					} // }else if(isset($arrayRespuesta['resser']['estsmo']['rstcan'][0]['feccan']['value'])){

				}

					break;
				
    ////////////////////////////// EUROPLAYAS 
				
				case $this->dir_bedsonline: 


					$arrayRespuesta=$this->xml2array($data[0]);

					if(isset($_SESSSION['ip_admin'])){

						var_dump($data[0]);

					}
					

					if(isset($arrayRespuesta['ServiceAddRS']['ErrorList']['Error']['Code']['value']) && strlen($arrayRespuesta['ServiceAddRS']['ErrorList']['Error']['Code']['value'])>0 || count($arrayRespuesta)==0 || !isset($arrayRespuesta['ServiceAddRS']['Purchase']['ServiceList']['Service']['AvailableRoom']['HotelRoom'])){
						
						$resultado['textos_cancelaciones']="Sin datos disponibles, para concer los gastos de cancelaci&oacute;n pulse Reservar.";
						break;
					}

					$comision=$this->bedsonline->comision;
				
					$arrayRespuesta=$arrayRespuesta['ServiceAddRS']['Purchase']['ServiceList']['Service']['AvailableRoom']['HotelRoom'];

					
					$resultado['textos_cancelaciones']="";

					if(isset($arrayRespuesta['CancellationPolicy']['Price'][0]['Amount']['value'])){
						$cancelaciones=$arrayRespuesta['CancellationPolicy']['Price'];
					}else{
						$cancelaciones[0]=$arrayRespuesta['CancellationPolicy']['Price'];
					}
					
					for($i=0;$i<count($cancelaciones);$i++){


						$gasto_neto[$i] = (($cancelaciones[$i]['Amount']['value']*$comision)/100)+$cancelaciones[$i]['Amount']['value'];

						$gasto_total[$i]=numFormato($gasto_neto[$i]);
						$fecha[$i]=formatear_fecha($cancelaciones[$i]['DateTimeFrom']['attr']['date'],x,i);

						$hora[$i]=$cancelaciones[$i]['DateTimeFrom']['attr']['time'];
						$hora_f[$i]=$hora[$i][0].$hora[$i][1].":".$hora[$i][2].$hora[$i][3];

						if($i>0){
							$resultado['textos_cancelaciones'].="<br>";
						}

						$resultado['textos_cancelaciones'].="Cancelando desde el ".	$fecha[$i]." a las ".$hora_f[$i]." un importe de ".$gasto_total[$i]." &euro;";	
					}
					

				break;


					//////////////////////////////

				case $this->dir_veturis:


					$arrayRespuesta=$this->xml2array($data[0]);
					$response=$this->veturis->detalles();

					if(isset($arrayRespuesta['AdditionalInformationRS']['Error']) || count($arrayRespuesta)==0){

						$resultado['textos_cancelaciones']="Sin datos disponibles, para concer los gastos de cancelaci&oacute;n pulse Reservar.";
						break;
					}

					$comision=$this->veturis->comision;

					$resultado=$arrayRespuesta['AdditionalInformationRS'];

					$resultado['textos_cancelaciones']="";

					// fecha limite sin gastos
					$resultado['f_mostrar']=formatear_fecha($resultado['AdditionalInformation']['fechaLimiteSinGastos']['value'],m,i);
					//$resultado['h_mostrar']=$resultado['AdditionalInformation']['horaLimiteSinGastos']['value'];

					$resultado['textos_cancelaciones'].="Puedes cancelar Gratis Hasta ".$resultado['f_mostrar']."<br>";

					$resultado['cancelacion']=$resultado['AdditionalInformation']['Cancellation'];

					if(isset($resultado['cancelacion']['Period'][0])){

						$n_cancelaciones=count($resultado['cancelacion']['Period']);

					}else if(!isset($resultado['cancelacion']['Period'][0]) && isset($resultado['cancelacion']['Period']['From'])){

						$n_cancelaciones=1;
						$resultado['cancelacion']['Period'][0]=$resultado['cancelacion']['Period'];

					}


					for($clx=0;$clx<$n_cancelaciones;$clx++){

						$resultado['cancelacion'][$clx]['fecha']=formatear_fecha($resultado['cancelacion']['Period'][$clx]['From']['value'],m,i);

						//$resultado['cancelacion'][$clx]['hora']=$resultado['cancelacion']['Period'][$clx]['Hour']['value'];

						$resultado['cancelacion'][$clx]['total']=
						number_format(($resultado['cancelacion']['Period'][$clx]['PriceAgency']['value']*$comision/100)+$resultado['cancelacion']['Period'][$clx]['PriceAgency']['value'],2,".","");

						$resultado['textos_cancelaciones'].="Cancelando desde ".$resultado['cancelacion'][$clx]['fecha']."  un importe de " . $resultado['cancelacion'][$clx]['total']."&euro;<br> ";

					} // for($clx=0;$clx<$n_cancelaciones;$clx++){

				break; // eof case $this->dir_veturis:
				
			} // eof switch($dir){

			return $resultado;
		} // public function formatearGastos($dir,$data){
		
		public function formatearDetalles($dir,$data){

			####
			# DEVOLVER OBLIGATORIO
			#
			# $resultado['cancelacion'][$i]['total'] => importante 1º importe 4 order by total
			# $resultado['cancelacion'][$i]['fecha']
			# $resultado['observaciones'] = aqui unificaremos todo lo que traigan 
			#  //// las $ que cada uno necesite para poder confirmar


			$resultado=array();

			switch($dir){

				case $this->dir_serhs:


					$arrayRespuesta=$this->xml2array($data[0]);

					if(isset($arrayRespuesta['response']['status']['value']) && $arrayRespuesta['response']['status']['value']!='0' || count($arrayRespuesta)==0){

						// encerramo el ticket, con la intencion de poder eliminarlo
						// de las opcions que posteriormente vamos a sacar en la 
						// siguiente consulta de disponibilidad.
						if(isset($arrayRespuesta['response']['ticket']['value'])){

							$this->DB->query('insert into quitar_del_listado (proveedor,indicador) values ("id_serhs","'.$arrayRespuesta['response']['ticket']['value'].'")');

						}
						
						return false;
						break;
					}

					$find=array(".",",");
					$chng=array("",".");

					$comision=$this->serhs->comision;
					$arrayRespuesta=$arrayRespuesta['response'];

					$resultado['preBooking']=$arrayRespuesta['preBooking']['attr']['code'];	# esta es la $ que necesitamos para el siguiente paso.

					// si cuando hagamos las comprobaciones pertinentes este no
					// es el mismo para las mismas opciones, lo quitamos del medio.
					$resultado['ticket']=$arrayRespuesta['accommodation']['attr']['ticket']; # para eliminar el resultado del listado.


					$resultado['neto']=$arrayRespuesta['amount_details']['nett_amount']['value'];

					// devolvemos un nuevo neto calculado con lo que recibimos, por si ha cambiado.
					$resultado['nuevo_total'] = str_replace($find,$chng,$arrayRespuesta['amount_details']['nett_amount']['value']);
					$nuevo_porcentaje = ($resultado['nuevo_total']*$comision)/100;
					$resultado['nuevo_total']+=$nuevo_porcentaje;
					$resultado['nuevo_total'] = number_format($resultado['nuevo_total'],2,".","");


					if(isset($arrayRespuesta['cancelPolicies']['cancelPolicy'][0]['attr']['amount'])){

						$cancelaciones=$arrayRespuesta['cancelPolicies']['cancelPolicy'];

					}else if(!isset($arrayRespuesta['cancelPolicies']['cancelPolicy'][0]['attr']['amount']) 
					    && isset($arrayRespuesta['cancelPolicies']['cancelPolicy']['attr']['amount'])){

						$cancelaciones[0]=$arrayRespuesta['cancelPolicies']['cancelPolicy'];

					} // if(isset($arrayRespuesta['cancelPolicies']['cancelPolicy'][0]['attr']['amount'])){


					
					for($i=0;$i<count($cancelaciones);$i++){
						// importante fecha detras para que al ordenarlo en la salida 
						// ordene por el importe menor ascendentemente.
						$resultado['cancelacion'][$i]['total']=str_replace($find,$chng,$cancelaciones[$i]['attr']['amount']);
						$porcentaje[$i] = ($resultado['cancelacion'][$i]['total']*$comision)/100;
						$resultado['cancelacion'][$i]['total']+=$porcentaje[$i];
						$resultado['cancelacion'][$i]['fecha']=formatear_fecha($cancelaciones[$i]['attr']['releaseDate'],x,i);

						
					} // for($i=0;$i<count($cancelaciones);$i++){



					$resultado['observaciones']="";

					if(isset($arrayRespuesta['remarks']['value']) && $arrayRespuesta['remarks']['value']!=""){

						$resultado['observaciones'].=$arrayRespuesta['remarks']['value'];

					}

					if(isset($arrayRespuesta['remarks']['collects']['collect']['address']['value']) 
					   && $arrayRespuesta['remarks']['collects']['collect']['address']['value']!=""
					){


						$recoge_llaves = $arrayRespuesta['remarks']['collects']['collect'];
						if(isset($recoge_llaves['zip_code']['value']) && $recoge_llaves['zip_code']['value']!="" &&
						   isset($recoge_llaves['city']['value']) && $recoge_llaves['city']['value']!="" &&
						   isset($recoge_llaves['province']['value']) && $recoge_llaves['province']['value']!="" &&
						   isset($recoge_llaves['country']['value']) && $recoge_llaves['country']['value']!="" &&
						   isset($recoge_llaves['phone']['value']) && $recoge_llaves['phone']['value']!="" &&
						   isset($recoge_llaves['hours']['value']) && $recoge_llaves['hours']['value']!=""
						){
							if($resultado['observaciones']!=""){
								$resultado['observaciones'].="<br><br>";
							}

							// posibilidad de recogida de llaves en los apartamentos.
							$resultado['observaciones'] .= "Los Clientes deben de recoger las llaves en:<br> <br>
							".$recoge_llaves['address']['value']." situado en " .$recoge_llaves['city']['value']. "<br>(".$recoge_llaves['province']['value'].") CP - ".$recoge_llaves['zip_code']['value']." <br>TLF:".$recoge_llaves['phone']['value']."<br>".$recoge_llaves['hours']['value'];

						}else{

							if($resultado['observaciones']!=""){
								$resultado['observaciones'].="<br><br>";
							}
							// resto de posobilidades vienen las codiciones recogidas en address
							$resultado['observaciones'].=$recoge_llaves['address']['value'];
						}


					}

					$resultado['observaciones']=utf8_encode($resultado['observaciones']);
				break; // case $this->dir_serhs:
				
				 
				case $this->dir_versys:

                   //echo $data[0];
					$arrayRespuesta=$this->xml2array($data[0]);
					$arrayRespuesta = $arrayRespuesta['soap:Envelope']['soap:Body']['OTA_HotelBookingRuleServiceResponse']['OTA_HotelBookingRuleRS'];

               		if(isset($arrayRespuesta['Errors']['ErrorType']) || count($arrayRespuesta)==0 ||
               			isset($arrayRespuesta['Warnings']['WarningsType']['Warning']['attr']['Type']) 
               			&& $arrayRespuesta['Warnings']['WarningsType']['Warning']['attr']['Type']!=1){

						// meter la solucion de quitar de los listados los errores de aqiu

						return false;
						break;
					} //if(isset($arrayRespuesta['Errors']['ErrorType']) || count($arrayRespuesta)==0 || .....


					if(isset($arrayRespuesta['Warnings']['WarningsType']['Warning']['attr']['Type']) 
					   && $arrayRespuesta['Warnings']['WarningsType']['Warning']['attr']['Type']==1 || isset($arrayRespuesta['Warnings']['Warning']['attr']['Type']) 
					   && $arrayRespuesta['Warnings']['Warning']['attr']['Type']==1){

						echo "<script>alert('Por motivos de Ocupacion, el hotel a cambiado su precio. Le mostramos el nuevo precio en el total.')</script>";

					} // if(isset($arrayRespuesta['Warnings']['WarningsType']['Warning']['attr']['Type']) ....

					$comision=$this->versys->comision;
					$resultado['SequenceNmbr']=$arrayRespuesta['attr']['SequenceNmbr'];
					$arrayRespuesta=$arrayRespuesta['RuleMessage'];
					$resultado['RatePlanCode']=$arrayRespuesta['StatusApplication']['attr']['RatePlanCode'];	

					$cancelacion=$arrayRespuesta['BookingRules']['BookingRule']['TPA_Extensions'];
					$resultado['neto'] = $cancelacion['TotalPrice']['value'];

					// devolvemos un nuevo neto calculado con lo que recibimos, por si ha cambiado.
					// $find=array(".",",");
					// $chng=array("",".");
					// $resultado['nuevo_total'] = str_replace($find,$chng,$cancelacion['TotalPrice']['value']);

					$resultado['nuevo_total'] = $cancelacion['TotalPrice']['value'];
					
					$nuevo_porcentaje = ($resultado['nuevo_total']*$comision)/100;
					$resultado['nuevo_total']+=$nuevo_porcentaje;
					$resultado['nuevo_total'] = number_format($resultado['nuevo_total'],2,".","");

					$resultado['token']=$cancelacion['TotalPrice']['value']*100;

					if(isset($cancelacion['CancellationPolicyRules']['Rule'][0]['attr']['DateFrom'])){
						$cancelaciones=$cancelacion['CancellationPolicyRules']['Rule'];

               		}else if(isset($cancelacion['CancellationPolicyRules']['Rule'])){

						$cancelaciones['Rule'][0]=$cancelacion['CancellationPolicyRules']['Rule'];

               		}else{

               			$cancelaciones = 0;
               		}


// aqquuii			
               		if($cancelaciones==0 || !isset($cancelaciones[0])){

               			$precio_entero = $cancelacion['TotalPrice']['value'];
						$porcentaje = ($precio_entero*$comision)/100;
						$precio_entero+=$porcentaje;
               			$resultado['cancelacion'][0]['total']=$precio_entero;
               			$resultado['cancelacion'][0]['fecha']=date('d/m/Y');


               		}else if(isset($cancelaciones[0])){


						for($i=0;$i<count($cancelaciones);$i++){


							$importe_cancelacion[$i]=$cancelaciones[$i]['attr']['FixedPrice'];
							$noches_cancelacion[$i]=$cancelaciones[$i]['attr']['Nights'];
							$percent_cancelacion[$i]=$cancelaciones[$i]['attr']['PercentPrice'];
							$sacar_gasto[$i]=0;

							if($importe_cancelacion[$i]>0){

								$resultado['cancelacion'][$i]['total']=$cancelaciones[$i]['attr']['FixedPrice'];
								$porcentaje[$i] = ($resultado['cancelacion'][$i]['total']*$comision)/100;
								$resultado['cancelacion'][$i]['total']+=$porcentaje[$i];
								$sacar_gasto[$i]=1;

							}else if($percent_cancelacion[$i]>0 || $noches_cancelacion[$i]>0){ // if($importe_cancelacion[$i]>0){

								$precio_entero[$i] = $cancelacion['TotalPrice']['value'];
								$porcentaje[$i] = ($precio_entero[$i]*$comision)/100;
								$precio_entero[$i]+=$porcentaje[$i];
								$sacar_gasto[$i]=1;

								if($percent_cancelacion[$i]>0){

									$resultado['cancelacion'][$i]['total']=($precio_entero[$i]/100)*$percent_cancelacion[$i];

								}else if($noches_cancelacion[$i]>0){ // if($percent_cancelacion[$i]>100){

									$Start=$arrayRespuesta['StatusApplication']['attr']['Start'];
									$End=$arrayRespuesta['StatusApplication']['attr']['End'];
									$noches = comparar_fechas($Start,$End,m);

									$resultado['cancelacion'][$i]['total']=($precio_entero[$i]/$noches)*$noches_cancelacion[$i];

								} // }else if($noches_cancelacion[$i]>0){ 
								

							} // }else if($percent_cancelacion[$i]>0 || $noches_cancelacion[$i]>0){

							// eliminamos el error que sucede cuando vieene un registro de gastos
							// pero con todos los valores a 0 
							if($sacar_gasto[$i]==1){

								$resultado['HyFcancelacion'][$i]=$cancelaciones[$i]['attr']['DateFrom'];     
								list($resultado['cancelacion'][$i]['fecha'],$resultado['cancelacion'][$i]['hora'])=explode("T",$resultado['HyFcancelacion'][$i]);
								$resultado['cancelacion'][$i]['fecha']=formatear_fecha($resultado['cancelacion'][$i]['fecha'],m,i);

							}


						}	// for($i=0;$i<count($cancelaciones);$i++){ 
					}

					$resultado['observaciones']="";
					if(isset($arrayRespuesta['BookingRules']['BookingRule']['Description']['Text']['value'])){
						$resultado['observaciones']=utf8_encode($arrayRespuesta['BookingRules']['BookingRule']['Description']['Text']['value']);
					}
					

				break; //case $this->dir_versys:

				//////////////////////////////


			case $this->dir_solole:


                  //echo $data[0];
					$arrayRespuesta=$this->xml2array($data[0]);

               if(isset($arrayRespuesta['soap:Envelope']['soap:Body']['OTA_HotelBookingRuleServiceResponse']['OTA_HotelBookingRuleRS']['Errors']['ErrorType']) || count($arrayRespuesta)==0){

						
						break;
					
            }




if (isset($arrayRespuesta['soap:Envelope']['soap:Body']['OTA_HotelBookingRuleServiceResponse']['OTA_HotelBookingRuleRS']['Warnings']['WarningsType']['Warning']['attr']['Type']) && $arrayRespuesta['soap:Envelope']['soap:Body']['OTA_HotelBookingRuleServiceResponse']['OTA_HotelBookingRuleRS']['Warnings']['WarningsType']['Warning']['attr']['Type']!=1) {
  
// en este caso , podemos guardar los rate plan code de los errrores
// y usarlos para que nos se muestren en las siguientes disponibilidades 
//que vamos a recibir al irnos atras en el listado, ya que lo vamos a volver a recibir.

   // $this->registrarError(2,$data,$this->solole->detalles());
  
  break;

}else if(isset($arrayRespuesta['soap:Envelope']['soap:Body']['OTA_HotelBookingRuleServiceResponse']['OTA_HotelBookingRuleRS']['Warnings']['WarningsType']['Warning']['attr']['Type']) && $arrayRespuesta['soap:Envelope']['soap:Body']['OTA_HotelBookingRuleServiceResponse']['OTA_HotelBookingRuleRS']['Warnings']['WarningsType']['Warning']['attr']['Type']==1){

echo "<script>alert('Por motivos de Ocupacion, el hotel a cambiado su precio. Le mostramos el nuevo precio en el total.')</script>";

}



					$comision=$this->solole->comision;
	
$SequenceNmbr=$arrayRespuesta['soap:Envelope']['soap:Body']['OTA_HotelBookingRuleServiceResponse']['OTA_HotelBookingRuleRS']['attr']['SequenceNmbr'];

$arrayRespuesta=$arrayRespuesta['soap:Envelope']['soap:Body']['OTA_HotelBookingRuleServiceResponse']['OTA_HotelBookingRuleRS']['RuleMessage'];





					$resultado['prereserva']=0;

		

//Asi es como estaba			
$resultado['neto']=str_replace(".", ",",$arrayRespuesta['BookingRules']['BookingRule']['TPA_Extensions']['TotalPrice']['value']);
$prenetoFormato=str_replace(".", ",", $resultado['neto']);   
$resultado['netoFormato']=numFormatoMysql($prenetoFormato);
$resultado['totalFormateado']=(($resultado['netoFormato']*$comision)/100)+$resultado['netoFormato'];
$resultado['total']=numFormato($resultado['totalFormateado']);



//Esto es lo que hay que subir on-line.
//$resultado['total']=$arrayRespuesta['BookingRules']['BookingRule']['TPA_Extensions']['TotalPrice']['value'];

$resultado['comision']=$comision;
       



$resultado['fechaDcancelacion']=$arrayRespuesta['BookingRules']['BookingRule']['attr']['AbsoluteCutoff'];
list($resultado['fechaDcancelacion'],$resultado['horaDcancelacion'])=split("T",$resultado['fechaDcancelacion']);

list($añoc,$mesc,$diac)=split("-",$resultado['fechaDcancelacion']);

$resultado['fechaDcancelacion']=$diac."/".$mesc."/".$añoc;



$resultado['total_mayorista']=$resultado['total'];
$resultado['codeHotel']=$arrayRespuesta['attr']['HotelCode'];
$resultado['RatePlanCode']=$arrayRespuesta['StatusApplication']['attr']['RatePlanCode'];
$resultado['SequenceNmbr']=$SequenceNmbr;

$resultado['entradan']=$arrayRespuesta['StatusApplication']['attr']['Start'];
list($añoe,$mese,$diae)=explode("-",$resultado['entradan']);
$resultado['entradan']=$diae."-".$mese."-".$añoe;

$resultado['entrada']=$arrayRespuesta['StatusApplication']['attr']['Start'];
list($añoe,$mese,$diae)=explode("-",$resultado['entrada']);
$resultado['entrada']=$diae."/".$mese."/".$añoe;

$resultado['salidan']=$arrayRespuesta['StatusApplication']['attr']['End'];
list($años,$mess,$dias)=explode("-",$resultado['salidan']);
$resultado['salidan']=$dias."-".$mess."-".$años;

$resultado['salida']=$arrayRespuesta['StatusApplication']['attr']['End'];
list($años,$mess,$dias)=explode("-",$resultado['salida']);
$resultado['salida']=$dias."/".$mess."/".$años;




$resultado['noches']=compararFechas($resultado['salidan'],$resultado['entradan']);
           
					$resultado['proveedor']="id_solole";
					
        

        

								if(strlen($arrayRespuesta['BookingRules']['BookingRule']['TPA_Extensions']['CancellationPolicyRules']['Rule'][0]['attr']['DateFrom'])>0){
						$cancelaciones=$arrayRespuesta['BookingRules']['BookingRule']['TPA_Extensions']['CancellationPolicyRules'];
               }else{
						$cancelaciones[0]=$arrayRespuesta['BookingRules']['BookingRule']['TPA_Extensions']['CancellationPolicyRules'];
               }


						$cancela=count($cancelaciones);
                  //echo "<br> La variable cancela vale" . count($cancelaciones);
//echo "<br> La variable cancela][0] vale" . count($cancelaciones[0]);

					for($i=0;$i<count($cancelaciones);$i++){

   //$cancelaciones=$arrayRespuesta['BookingRules']['BookingRule']['TPA_Extensions']['CancellationPolicyRules'];

	$resultado['cancelacion'][$i]['total']=$cancelaciones['Rule'][$i]['attr']['FixedPrice'];
  $resultado['HyFcancelacion'][$i]=$cancelaciones['Rule'][$i]['attr']['DateFrom'];

               
list($resultado['cancelacion'][$i]['fecha'],$resultado['cancelacion'][$i]['hora'])=explode("T",$resultado['fechaDcancelacion']);

//list($añoc,$mesc,$diac)=split("-",$resultado['fechaDcancelacion']);

//$resultado['fechaDcancelacion']=$diac."/".$mesc."/".$añoc;



						//$resultado['cancelacion'][$i]['fecha']=$cancelaciones[$i]['Date']['value'];
						//$resultado['cancelacion'][$i]['hora']=$cancelaciones[$i]['Time']['value'];
					}	



					$resultado['observaciones']=$arrayRespuesta['BookingRules']['BookingRule']['Description']['Text']['value'];


	$resultado['condiciones']=$arrayRespuesta['BookingRules']['BookingRule']['CancelPenalties']['CancelPenalty']['PenaltyDescription']['Text']['value'];
			//$resultado['condiciones']="";


				break;



				case $this->dir_europlayas:

				// ##### IMPORTANTE ########//
				#
				#
				# HE COMENTADO TODO LO RELACIONADO CON + DE 1 HB 
				# WHEN LO SOLUCIONE, TENGO QUE PASAR POR AQUI TAMBIEN.
				#
				#


				$arrayRespuesta=$this->xml2array($data[0]);

               	if(isset($arrayRespuesta['PublisherError']) || isset($arrayRespuesta['BloqueoServicioRespuesta']['coderr']) || count($arrayRespuesta)==0){

						return false;
						break;	
            	}


				$arrayRespuesta=$arrayRespuesta['BloqueoServicioRespuesta'];
				$resultado['sessionEuro']=$arrayRespuesta['ideses']['value']; // este se pasa para la confirmacion
				$comision=$this->europlayas->comision;
			
				$resultado['neto']=$arrayRespuesta['impnoc']['value'];

				// devolvemos un nuevo neto calculado con lo que recibimos, por si ha cambiado.
				$find=array(".",",");
				$chng=array("",".");
				$resultado['nuevo_total'] = str_replace($find,$chng,$arrayRespuesta['impnoc']['value']);	
				$nuevo_porcentaje = ($resultado['nuevo_total']*$comision)/100;
				$resultado['nuevo_total']+=$nuevo_porcentaje;
				$resultado['nuevo_total'] = number_format($resultado['nuevo_total'],2,".","");

				/*$idHb=$arrayRespuesta['resser']['attr']['id'];

				$numHb=explode("-",$idHb);

				$numHb=count($numHb);

				estoy12345
				*/

				$numHb=1;

				if($numHb==1){

					if(isset($arrayRespuesta['resser']['estsmo']['rstcan'][0]['feccan']['value'])){

						$resultado['fechaDcancelacion']=$arrayRespuesta['resser']['estsmo']['rstcan'][0]['feccan']['value'];

					}else if(isset($arrayRespuesta['resser']['estsmo']['rstcan']['feccan']['value'])){

						$resultado['fechaDcancelacion']=$arrayRespuesta['resser']['estsmo']['rstcan']['feccan']['value'];
					}

				}/*else if($numHb>1){


				if(isset($arrayRespuesta['resser']['estsmo'][0]['rstcan'][0]['feccan']['value'])){

				$resultado['fechaDcancelacion']=$arrayRespuesta['resser']['estsmo'][0]['rstcan'][0]['feccan']['value'];

				}else if(isset($arrayRespuesta['resser']['estsmo'][0]['rstcan']['feccan']['value'])){

					$resultado['fechaDcancelacion']=$arrayRespuesta['resser']['estsmo'][0]['rstcan']['feccan']['value'];
				}


				} // else if($numHb>1){*/


					
				if($numHb==1){
				//si el n de hb es solo 1


					if(isset($arrayRespuesta['resser']['estsmo']['rstcan'][0]['feccan']['value'])){

						$cancelaciones=$arrayRespuesta['resser']['estsmo']['rstcan'];
						$ncancelaciones=count($arrayRespuesta['resser']['estsmo']['rstcan']);

					}else if(isset($arrayRespuesta['resser']['estsmo']['rstcan']['feccan']['value'])){

						$cancelaciones[0]=$arrayRespuesta['resser']['estsmo']['rstcan'];
						$ncancelaciones=1;

					}

					
########################HE MEJORARO MUCHO EL METODO DE SACAR LOS GASTOS TENERLO EN CUENTA CUANDO ARREGLE + DE 1 HB############################


					for($i=0;$i<$ncancelaciones;$i++){

						$aplicacion[$i]=$cancelaciones[$i]['aplcan']['value'];

							if(isset($cancelaciones[$i]['impcan']['value'])){

								$resultado['cancelacion'][$i]['total']=str_replace($find,$chng,$cancelaciones[$i]['impcan']['value']);
								$porcentaje[$i] = ($resultado['cancelacion'][$i]['total']*$comision)/100;
								$resultado['cancelacion'][$i]['total']+=$porcentaje[$i];

							}else if(isset($cancelaciones[$i]['porcan']['value'])){

								$total[$i]=str_replace($find,$chng,$arrayRespuesta['impnoc']['value']);
								$porcentaje[$i]=($total[$i]*$comision)/100;
								$total[$i]+=$porcentaje[$i];
								$total[$i] = number_format($total[$i],"2",".","");
								$porcan[$i]=$cancelaciones[$i]['porcan']['value'];

								if($aplicacion[$i]=="T"){

									$resultado['cancelacion'][$i]['total']=($total[$i]*$porcan[$i])/100;

								}else if($aplicacion[$i]=="P"){ // if($aplicacion[$i]=="T"){

									//tenemos que sacar el nº de noches y dividir el total sobre este.
									// luego aplicarle el % al resultado.
	 								$entrada = explode(" ",$arrayRespuesta['resser']['fecini']['value']);
	 								$salida = explode(" ",$arrayRespuesta['resser']['fecfin']['value']);
									$nDnoches=comparar_fechas($entrada[0],$salida[0],i);
									$importeNoches=$total[$i]/$nDnoches;
									$resultado['cancelacion'][$i]['total']=($importeNoches*$porcan[$i])/100;

								} // }else if($aplicacion[$i]=="P"){
								
							} // }else if(isset($cancelaciones[$i]['porcan']['value'])){

							$resultado['cancelacion'][$i]['fecha']=$cancelaciones[$i]['feccan']['value'];

					} // for($i=0;$i<$ncancelaciones;$i++){

				}/*else if($numHb>1){ // if($numHb==1){

					// por ahora no vamos a entrar por aqui, asi que no hay problema. 

					//aqui cuando las HB son mas que una.
					if(isset($arrayRespuesta['resser']['estsmo'][0]['rstcan']['feccan']['value'])){
					//cuando solo tiene una cancelacion.
					$ncancelaciones=1;
					$cancelaciones[0]=$arrayRespuesta['resser']['estsmo'][0]['rstcan'];

					$porcentaje=$arrayRespuesta['resser']['estsmo'][0]['rstcan']['porcan']['value'];
					$aplicacion=$arrayRespuesta['resser']['estsmo'][0]['rstcan']['aplcan']['value'];


					//echo "resultado total es " . $resultado['total'];

					if($aplicacion=="T"){

					$cancelaciones[0]['total']['value']=number_format(str_replace(',','.',$resultado['total'])*$porcentaje/100,"2",".","");

					//la P sobre la primera noche.
					}else if($aplicacion=="P"){
					//tenemos que sacar el nº de noches y dividir el total sobre este.
					// luego aplicarle el % al resultado.

					$nDnoches=$resultado['noches'];
					//echo "noches " . $nDnoches;

					$importeNoches=str_replace(',','.',$resultado['total'])/$resultado['noches'];
					//echo "importe noches es " . $importeNoches;
					//$cancelaciones[0]['total']['value']=$importeNoches*$porcentaje/100;
					$cancelaciones[0]['total']['value']=number_format($importeNoches*$porcentaje/100,"2",".","");


					}


					}else if(isset($arrayRespuesta['resser']['estsmo'][0]['rstcan'][0]['feccan']['value'])){
						// aqui venimos cuando el nº de cancelaciones es mas que 1
						$ncancelaciones=count($arrayRespuesta['resser']['estsmo'][0]['rstcan']);

									//echo "n de cancelaciones " . $ncancelaciones;

						for($x=0;$x<$ncancelaciones;$x++){

							if(isset($arrayRespuesta['resser']['estsmo'][0]['rstcan'][$x])){
								$cancelaciones[$x]=$arrayRespuesta['resser']['estsmo'][0]['rstcan'][$x];
							}

							if(isset($arrayRespuesta['resser']['estsmo'][0]['rstcan'][$x]['porcan']['value'])){
								$porcentaje=$arrayRespuesta['resser']['estsmo'][0]['rstcan'][$x]['porcan']['value'];
							}

							if(isset($arrayRespuesta['resser']['estsmo'][0]['rstcan'][$x]['aplcan']['value'])){
								$aplicacion=$arrayRespuesta['resser']['estsmo'][0]['rstcan'][$x]['aplcan']['value'];
							}
							
							
							

									//echo "resultado total es " . $resultado['total'];

									if($aplicacion=="T"){

									$cancelaciones[$x]['total']['value']=number_format(str_replace(',','.',$resultado['total'])*$porcentaje/100,"2",".","");

									//la P sobre la primera noche.
									}else if($aplicacion=="P"){
									//tenemos que sacar el nº de noches y dividir el total sobre este.
									// luego aplicarle el % al resultado.

									$nDnoches=$resultado['noches'];
									//echo "noches " . $nDnoches;

									$importeNoches=str_replace(',','.',$resultado['total'])/$resultado['noches'];
									//echo "importe noches es " . $importeNoches;
									//$cancelaciones[0]['total']['value']=$importeNoches*$porcentaje/100;
									$cancelaciones[$x]['total']['value']=number_format($importeNoches*$porcentaje/100,"2",".","");

				                }
				           }
				    } // }else if(isset($arrayRespuesta['resser']['estsmo'][0]['rstcan'][0]['feccan']['value'])){
				}*/

				#########################################################################################################################



						if($numHb==1){

							if(isset($arrayRespuesta['resser']['notser']['txtinf']['value'])){

								$resultado['observaciones']=utf8_encode($arrayRespuesta['resser']['notser']['txtinf']['value']);

							}

						}else{

							$resultado['observaciones']=utf8_encode($arrayRespuesta['resser']['estsmo'][0]['notser']['txtinf']['value']);

						}


					break; // case $this->dir_europlayas:
				
				
					case $this->dir_bedsonline: 
				

						$arrayRespuesta=$this->xml2array($data[0]);
						
						if(isset($arrayRespuesta['ServiceAddRS']['ErrorList']['Error']['Code']['value']) 
						   && $arrayRespuesta['ServiceAddRS']['ErrorList']['Error']['Code']['value']!="" || count($arrayRespuesta)==0){

							return false;
							break;
						}

						$comision=$this->bedsonline->comision;
						$arrayRespuesta=$arrayRespuesta['ServiceAddRS']['Purchase'];
						$resultado['token']=$arrayRespuesta['attr']['purchaseToken'];
						$resultado['neto']=number_format($arrayRespuesta['TotalPrice']['value'],2,".","");
						$resultado['nuevo_total']=number_format($arrayRespuesta['TotalPrice']['value'],2,".","");

						$nuevo_porcentaje = ($resultado['neto']*$comision)/100;
						$resultado['nuevo_total']+=$nuevo_porcentaje;
						$resultado['nuevo_total'] = number_format($resultado['nuevo_total'],2,".","");

								
						$resultado['agencia']=$arrayRespuesta['Agency']['Code']['value']; //codigo agencia
						$resultado['spui']=$arrayRespuesta['ServiceList']['Service']['attr']['SPUI']; //codigo del servicio/habitacion


						if(isset($arrayRespuesta['ServiceList']['Service']['AvailableRoom']['HotelRoom']['CancellationPolicy']['Price']['Amount']['value']) &&
						   strlen($arrayRespuesta['ServiceList']['Service']['AvailableRoom']['HotelRoom']['CancellationPolicy']['Price']['Amount']['value'])>0){
							$cancelaciones[0]=$arrayRespuesta['ServiceList']['Service']['AvailableRoom']['HotelRoom']['CancellationPolicy']['Price'];
						}else{
							$cancelaciones=$arrayRespuesta['ServiceList']['Service']['AvailableRoom']['HotelRoom']['CancellationPolicy']['Price'];
						}


						for($i=0;$i<count($cancelaciones);$i++){

							$resultado['cancelacion'][$i]['total']=$cancelaciones[$i]['Amount']['value'];
							$porcentaje[$i] = ($resultado['cancelacion'][$i]['total']*$comision)/100;
							$resultado['cancelacion'][$i]['total']=$resultado['cancelacion'][$i]['total']+$porcentaje[$i];
							$resultado['cancelacion'][$i]['fecha']=formatear_fecha($cancelaciones[$i]['DateTimeFrom']['attr']['date'],x,i);

						}
						
						$resultado['observaciones']=utf8_encode($arrayRespuesta['ServiceList']['Service']['ContractList']['Contract']['CommentList']['Comment']['value']);

					break; // case $this->dir_bedsonline: 


					//////////////////////////////

				case $this->dir_veturis:


					$arrayRespuesta=$this->xml2array($data[0]);
	
					if(isset($arrayRespuesta['AdditionalInformationRS']['Error']) || count($arrayRespuesta)==0){

						//// aqui podemos meter ['DATOS'] && ['obj'] 4 quitar de los listados.
						return false;
						break;
					}

					$comision=$this->veturis->comision;
					$resultado['obj']=$this->parametros['obj']; // para confirm
					$resultado['DATOS']=$this->parametros['DATOS']; // para confirm
					$arrayRespuesta=$arrayRespuesta['AdditionalInformationRS'];
					$resultado['token']=$arrayRespuesta['PriceAgency']['value']*100;
					$resultado['neto']=$arrayRespuesta['PriceAgency']['value'];
					$cancelaciones=$arrayRespuesta['AdditionalInformation']['Cancellation'];

					$resultado['nuevo_total']=$arrayRespuesta['PriceAgency']['value'];
					$nuevo_porcentaje = ($resultado['nuevo_total']*$comision)/100;
					$resultado['nuevo_total']+=$nuevo_porcentaje;
					$resultado['nuevo_total'] = number_format($resultado['nuevo_total'],2,".","");

							
					if(isset($cancelaciones['Period'][0])){

						$n_cancelaciones=count($cancelaciones['Period']);

					}else if(!isset($cancelaciones['Period'][0]) && isset($cancelaciones['Period']['From'])){

						$n_cancelaciones=1;
						$cancelaciones['Period'][0]=$cancelaciones['Period'];

					} // if(isset($cancelaciones['Period'][0])){

					for($i=0;$i<$n_cancelaciones;$i++){

						$resultado['cancelacion'][$i]['total']=$cancelaciones['Period'][$i]['PriceAgency']['value'];
						$porcentaje[$i] = ($resultado['cancelacion'][$i]['total']*$comision)/100;
						$resultado['cancelacion'][$i]['total']+=$porcentaje[$i];
						$resultado['cancelacion'][$i]['fecha']=formatear_fecha($cancelaciones['Period'][$i]['From']['value'],m,i);

					} // for($i=0;$i<$n_cancelaciones;$i++){

					$resultado['observaciones']="";

					if(isset($arrayRespuesta['AdditionalInformation']['EssentialInformation'])){

						$EssentialInformation=$arrayRespuesta['AdditionalInformation']['EssentialInformation'];

						if(isset($EssentialInformation['Information']['Description']) && !isset($EssentialInformation['Information'][0])){

							$EssentialInformation['Information'][0]=$EssentialInformation['Information'];
							$n_observaciones=1;

						}else if(isset($EssentialInformation['Information'][0])){

							$n_observaciones=count($EssentialInformation['Information']);

						} // if(isset($EssentialInformation['Information']['Description']) && ....

						for($ds=0;$ds<$n_observaciones;$ds++){

							if(isset($EssentialInformation['Information'][$ds]['Description']['value'])){

								$resultado['observaciones'].="<br>" . $EssentialInformation['Information'][$ds]['Description']['value'];
							}
							
						} // for($ds=0;$ds<$n_observaciones;$ds++){

					}// eof if(isset($resultado['AdditionalInformation']['EssentialInformation'])){


					if(isset($arrayRespuesta['AdditionalInformation']['Discounts'])){

						$Discounts=$arrayRespuesta['AdditionalInformation']['Discounts'];

						if(isset($Discounts['Discount']['Description']) && !isset($Discounts['Discount'][0])){

							$Discounts['Discount'][0]=$Discounts['Discount'];
							$n_descuentos=1;

						}else if(isset($Discounts['Discount'][0])){

							$n_descuentos=count($Discounts['Discount']);

						} // if(isset($Discounts['Discount']['Description']) && ......

						if(isset($n_descuentos) && $n_descuentos>0){

							for($ds=0;$ds<$n_descuentos;$ds++){

								$resultado['observaciones'].="<br>" . $Discounts['Discount'][$ds]['Description']['value'];

							} // for($ds=0;$ds<$n_descuentos;$ds++){

						} // if(isset($n_descuentos) && $n_descuentos>0){

					}// eof if(isset($resultado['AdditionalInformation']['Discounts'])){

					$resultado['observaciones']=utf8_encode($resultado['observaciones']);

					// la pasamos para meterla como parametro en la cofirmacion
					$resultado['observaciones_proveedor']=$resultado['observaciones'];
			
				break; // eof case $this->dir_veturis:
				
			} // eof switch($dir){

			return $resultado;
		} // public function formatearDetalles(

		public function formatearDetallesConfirm($dir,$data){

			#
			# DEVLVER OBLIGATORIO:
			# ['confirmacion'] (bool)
			# ['cancelacion'] array => fecha , importe + comision
			# ['localizador']
			# ['pagadero']
			# ['neto'] => no lo traigo de las $ del listado
			# ['observaciones_proveedor'] => para el proveedor que las traiga.
			#
			# SERHS Y VETURIS DEVUELVEN  DATOS PARA LAS CANCELACIONES // 4 clx
			#

			$resultado=array();

			switch($dir){

				case $this->dir_serhs:

					$arrayRespuesta=$this->xml2array($data[0]);
					
					if(isset($arrayRespuesta['response']['status']['value']) && $arrayRespuesta['response']['status']['value']!='0' || count($arrayRespuesta)==0){
   	 					
						// GUARDAMOS EL TICKET PARA QUE NO SAQUEMOS DE NUEVO ESTE RESLTADO
   	 					// $this->DB->query('insert into quitar_del_listado (proveedor,indicador) values ("id_serhs","'.$this->parametros['ticket'].'")');

   	 					$resultado['confirmacion']=0;

   	 					if(isset($arrayRespuesta['response']['status']['value'])){
   	 						$resultado['motivo'] = $arrayRespuesta['response']['status']['value'];
   	 					}else{
   	 						$resultado['motivo'] = "Motivo desconocido";
   	 					}
   	 					 
    					break;
					}
					
					
					$arrayRespuesta=$arrayRespuesta['response'];


					// devolvemos tambien los gastos
					// esto hay que ver si es unificado en todos los proveedores
					if(isset($arrayRespuesta['cancelPolicies']['cancelPolicy'][0]['attr']['amount'])){

						$cancelaciones=$arrayRespuesta['cancelPolicies']['cancelPolicy'];

					}else if(!isset($arrayRespuesta['cancelPolicies']['cancelPolicy'][0]['attr']['amount']) 
					    && isset($arrayRespuesta['cancelPolicies']['cancelPolicy']['attr']['amount'])){

						$cancelaciones[0]=$arrayRespuesta['cancelPolicies']['cancelPolicy'];

					} // if(isset($arrayRespuesta['cancelPolicies']['cancelPolicy'][0]['attr']['amount'])){


					$find=array(".",",");
					$chng=array("",".");
					for($i=0;$i<count($cancelaciones);$i++){

						
						$resultado['cancelacion'][$i]['fecha']=formatear_fecha($cancelaciones[$i]['attr']['releaseDate'],x,i);

						$resultado['cancelacion'][$i]['total']=str_replace($find,$chng,$cancelaciones[$i]['attr']['amount']);
						$porcentaje[$i] = ($resultado['cancelacion'][$i]['total']*$comision)/100;
						$resultado['cancelacion'][$i]['total']+=$porcentaje[$i];

						
					} // for($i=0;$i<count($cancelaciones);$i++){

					$resultado['observaciones_proveedor']="";
					if(isset($arrayRespuesta['remarks']['collects']['collect']['address']['value'])){
						$resultado['observaciones_proveedor']=$arrayRespuesta['remarks']['collects']['collect']['address']['value'];
					}


					$resultado['confirmacion']=1;
					$resultado['codigo']=$arrayRespuesta['bookings']['booking']['attr']['code']; // 4 clx
					$resultado['neto']=$arrayRespuesta['amount_details']['nett_amount']['value'];
					$resultado['localizador']=$arrayRespuesta['payable_by']['locator']['value'];
					$resultado['pagadero']=$arrayRespuesta['payable_by']['name']['value'];

					

				break; // case $this->dir_serhs:
				
				case $this->dir_versys:

					
					$arrayRespuesta=$this->xml2array($data[0]);

					$arrayRespuesta=$arrayRespuesta['soap:Envelope']['soap:Body']['OTA_HotelResV2ServiceResponse']['OTA_HotelResRS'];

					// error directo
					if(isset($arrayRespuesta['Errors']['ErrorType']) || count($arrayRespuesta)==0){

						$resultado['confirmacion']=0;
						$resultado['motivo']=$arrayRespuesta['Errors']['ErrorType']['attr']['ShortText'];
						break;

					}

					// motivo secundario.

					$arrayRespuesta=$arrayRespuesta['HotelReservations']['HotelReservation'];
					$estado_reserva = $arrayRespuesta['attr']['ResStatus'];

					if($estado_reserva=="Can" || $estado_reserva=="CaC" || $estado_reserva=="PRe" || $estado_reserva=="PDi" || $estado_reserva=="Ini" || $estado_reserva=="Quo"){

						$resultado['confirmacion']=0;
						$resultado['motivo']="Fallo en la confirmaci&oacute;n con el Proveedor de New Travelers.";
						break;

					}

					// REserva confirmada.
					$resultado['confirmacion']=1;
					$comision=$this->versys->comision;

					$sd=$arrayRespuesta['RoomStays']['RoomStay'];
					$resultado['observaciones_proveedor']=$sd['BasicPropertyInfo']['VendorMessages']['VendorMessage']['SubSection']['Paragraph']['Text']['value'];
					$resultado['neto']=$sd['Total']['attr']['AmountAfterTax'];

					$lc=$arrayRespuesta['ResGlobalInfo']['HotelReservationIDs']['HotelReservationID'];
					$resultado['localizador']=$lc['attr']['ResID_Value'];
					$localizador_ext=$lc['attr']['ResID_Source'];

					$cancelacion=$arrayRespuesta['TPA_Extensions']['CancellationPolicy'];

					if(isset($cancelacion['CancellationPolicyRules']['Rule'][0]['attr']['DateFrom'])){
						$cancelaciones=$cancelacion['CancellationPolicyRules']['Rule'];

               		}else{
						$cancelaciones[0]=$cancelacion['CancellationPolicyRules']['Rule'];

               		}

					for($i=0;$i<count($cancelaciones);$i++){


						$importe_cancelacion[$i]=$cancelaciones[$i]['attr']['FixedPrice'];
						$noches_cancelacion[$i]=$cancelaciones[$i]['attr']['Nights'];
						$percent_cancelacion[$i]=$cancelaciones[$i]['attr']['PercentPrice'];

						if($importe_cancelacion[$i]>0){

							$resultado['cancelacion'][$i]['total']=$cancelaciones[$i]['attr']['FixedPrice'];
							$porcentaje[$i] = ($resultado['cancelacion'][$i]['total']*$comision)/100;
							$resultado['cancelacion'][$i]['total']+=$porcentaje[$i];

						}else if($percent_cancelacion[$i]>0 || $noches_cancelacion[$i]>0){ // if($importe_cancelacion[$i]>0){

							$precio_entero[$i] = $resultado['neto'];
							$porcentaje[$i] = ($precio_entero[$i]*$comision)/100;
							$precio_entero[$i]+=$porcentaje[$i];

							if($percent_cancelacion[$i]>0){

								$resultado['cancelacion'][$i]['total']=($precio_entero[$i]/100)*$percent_cancelacion[$i];

							}else if($noches_cancelacion[$i]>0){ // if($percent_cancelacion[$i]>100){

								$Start=$arrayRespuesta['RoomStays']['RoomStay']['TimeSpan']['attr']['Start'];
								$End=$arrayRespuesta['RoomStays']['RoomStay']['TimeSpan']['attr']['End'];
								$noches = comparar_fechas($Start,$End,m);

								$resultado['cancelacion'][$i]['total']=($precio_entero[$i]/$noches)*$noches_cancelacion[$i];

							} // }else if($noches_cancelacion[$i]>0){ 
							
						} // }else if($percent_cancelacion[$i]>0 || $noches_cancelacion[$i]>0){

						// eliminamos la posibilidad de que aparezcan fechas sin importes
						if(isset($resultado['cancelacion'][$i]['total']) && $resultado['cancelacion'][$i]['total']>0){

							$resultado['HyFcancelacion'][$i]=$cancelaciones[$i]['attr']['DateFrom'];     
							list($resultado['cancelacion'][$i]['fecha'],$resultado['cancelacion'][$i]['hora'])=explode("T",$resultado['HyFcancelacion'][$i]);
							$resultado['cancelacion'][$i]['fecha']=formatear_fecha($resultado['cancelacion'][$i]['fecha'],m,i);
						}
						

					}	// for($i=0;$i<count($cancelaciones);$i++){ 

					if(isset($arrayRespuesta['TPA_Extensions']['Payable']['value'])){

						$resultado['pagadero']="(".$localizador_ext.") ".trim($arrayRespuesta['TPA_Extensions']['Payable']['value']);

					}else if(!isset($arrayRespuesta['TPA_Extensions']['Payable']['value'])){

						$resultado['pagadero']="W2M (".$localizador_ext.")";

					}
					
					
				break; // case $this->dir_versys:

case $this->dir_solole:

					$arrayRespuesta=$this->xml2array($data[0]);


if(isset($arrayRespuesta['soap:Envelope']['soap:Body']['OTA_HotelResV2ServiceResponse']['OTA_HotelResRS']['HotelReservations']['HotelReservation'])){

$errorRes=$arrayRespuesta['soap:Envelope']['soap:Body']['OTA_HotelResV2ServiceResponse']['OTA_HotelResRS']['HotelReservations']['HotelReservation']['attr']['ResStatus'];

}else{

   $errorRes=0;

}


					if(isset($arrayRespuesta['soap:Envelope']['soap:Body']['OTA_HotelResV2ServiceResponse']['OTA_HotelResRS']['Errors']['ErrorType']) || count($arrayRespuesta)==0 || $errorRes=="Can" || $errorRes=="CaC" || $errorRes=="PRe" || $errorRes=="PDi" || $errorRes=="Ini" || $errorRes=="Quo"){
						// $this->registrarError(3,$data,$this->solole->confirmar(),$this->parametros['idReserva']);
						break;
					}

					if($arrayRespuesta['soap:Envelope']['soap:Body']['OTA_HotelResV2ServiceResponse']['OTA_HotelResRS']['Errors']['ErrorType'] ){
						$resultado['estado']=1;
				      $resultado['error']=$arrayRespuesta['soap:Envelope']['soap:Body']['OTA_HotelResV2ServiceResponse']['OTA_HotelResRS']['Errors']['ErrorType']['attr']['ShortText'];
					}else{
						$arrayRespuesta=$arrayRespuesta['soap:Envelope']['soap:Body']['OTA_HotelResV2ServiceResponse']['OTA_HotelResRS']['HotelReservations']['HotelReservation'];
						$resultado['estado']=0;

						$resultado['codigo']=$arrayRespuesta['ResGlobalInfo']['HotelReservationIDs']['HotelReservationID']['attr']['ResID_Value'];

						$resultado['localizador']=$arrayRespuesta['ResGlobalInfo']['HotelReservationIDs']['HotelReservationID']['attr']['ResID_Value'];



if(isset($arrayRespuesta['ResGlobalInfo']['HotelReservationIDs']['HotelReservationID']['attr']['ResID_Source'])){

$resultado['localizador_ext']=$arrayRespuesta['ResGlobalInfo']['HotelReservationIDs']['HotelReservationID']['attr']['ResID_Source'];

					}else if(!isset($arrayRespuesta['ResGlobalInfo']['HotelReservationIDs']['HotelReservationID']['attr']['ResID_Source']) && isset($arrayRespuesta['ResGlobalInfo']['HotelReservationIDs']['HotelReservationID']['attr']['ResPAYASource'])){

$resultado['localizador_ext']=$arrayRespuesta['ResGlobalInfo']['HotelReservationIDs']['HotelReservationID']['attr']['ResPAYASource'];

$cabeceras="MIME-Version: 1.0\n";
					$cabeceras .= "Content-type: text/html; charset=utf-8\n";
					$cabeceras.="From: Reserva ResPAYASource <pedrogil@soltury.com>\n";
					$cabeceras.="Reply-To: pedrogil@soltury.com\n";
					$asunto='Reserva ResPAYASource';
					$cuerpo='La reserva con loc:'.$resultado['localizador'].' <br>';
					$cuerpo.='Contiene el atributo ResPAYASource<br />';
					$cuerpo.='El proveedor es: <br><br><br>';
					$cuerpo.='Solole br>';
				
					
					$mail="pedrogil@soltury.com";
					@mail("pedrogil@soltury.com", $asunto, $cuerpo,$cabeceras);	



					}


if(isset($arrayRespuesta['TPA_Extensions']['Payable']['value'])){

	$resultado['pagadero']="(".$resultado['localizador_ext'].")".$arrayRespuesta['TPA_Extensions']['Payable']['value'];
}else if(!isset($arrayRespuesta['TPA_Extensions']['Payable']['value'])){

$resultado['pagadero']="Solole (".$resultado['localizador_ext'].")";

}



						//$resultado['pagadero']=$arrayRespuesta['TPA_Extensions']['Payable']['value'];
					}
					
				break;

				//////////////////
				case $this->dir_europlayas:

					$arrayRespuesta=$this->xml2array($data[0]);

					if(isset($arrayRespuesta['PublisherError'])){
						
						$resultado['confirmacion']=0;
						$resultado['motivo']=$arrayRespuesta['PublisherError']['txterr']['value'];
						break;

					}else if(isset($arrayRespuesta['ReservaCerrarRespuesta']['coderr'])){

						$resultado['confirmacion']=0;
						$resultado['motivo']=$arrayRespuesta['ReservaCerrarRespuesta']['txterr']['value'];
						break;


					}else if(count($arrayRespuesta)==0 || 
					isset($arrayRespuesta['ReservaCerrarRespuesta']['cupest']) && $arrayRespuesta['ReservaCerrarRespuesta']['cupest']['value']!="CM"){

						$resultado['confirmacion']=0;
						$resultado['motivo']="Reserva no confirmada por el proveedor";
						break;

					}



					$arrayRespuesta=$arrayRespuesta['ReservaCerrarRespuesta'];

					$resultado['confirmacion']=1;
					$resultado['localizador']=$arrayRespuesta['locata']['value'];
					$resultado['pagadero']='Reservado y pagadero por VIAJES EUROMAR S.A CIF: A07031008 ';
					$comision=$this->europlayas->comision;


					if(isset($arrayRespuesta['resser']['estsmo']['rstcan'][0]['feccan']['value'])){

						$cancelaciones=$arrayRespuesta['resser']['estsmo']['rstcan'];
						$ncancelaciones=count($arrayRespuesta['resser']['estsmo']['rstcan']);

					}else if(isset($arrayRespuesta['resser']['estsmo']['rstcan']['feccan']['value'])){

						$cancelaciones[0]=$arrayRespuesta['resser']['estsmo']['rstcan'];
						$ncancelaciones=1;

					}

					$find=array(".",",");
					$chng=array("",".");

					for($i=0;$i<$ncancelaciones;$i++){

						$aplicacion[$i]=$cancelaciones[$i]['aplcan']['value'];

							if(isset($cancelaciones[$i]['impcan']['value'])){

								$resultado['cancelacion'][$i]['total']=str_replace($find,$chng,$cancelaciones[$i]['impcan']['value']);
								$porcentaje[$i] = ($resultado['cancelacion'][$i]['total']*$comision)/100;
								$resultado['cancelacion'][$i]['total']+=$porcentaje[$i];

							}else if(isset($cancelaciones[$i]['porcan']['value'])){

								$total[$i]=str_replace($find,$chng,$arrayRespuesta['impnoc']['value']);
								$porcentaje[$i]=($total[$i]*$comision)/100;
								$total[$i]+=$porcentaje[$i];
								$porcan[$i]=$cancelaciones[$i]['porcan']['value'];

								if($aplicacion[$i]=="T"){

									$resultado['cancelacion'][$i]['total']=($total[$i]*$porcan[$i])/100;

								}else if($aplicacion[$i]=="P"){ // if($aplicacion[$i]=="T"){

									//tenemos que sacar el nº de noches y dividir el total sobre este.
									// luego aplicarle el % al resultado.
	 								$entrada = explode(" ",$arrayRespuesta['resser']['fecini']['value']);
	 								$salida = explode(" ",$arrayRespuesta['resser']['fecfin']['value']);
									$nDnoches=comparar_fechas($entrada[0],$salida[0],i);
									$importeNoches=$total[$i]/$nDnoches;
									$resultado['cancelacion'][$i]['total']=($importeNoches*$porcan[$i])/100;

								} // }else if($aplicacion[$i]=="P"){
								
							} // }else if(isset($cancelaciones[$i]['porcan']['value'])){

							$resultado['cancelacion'][$i]['fecha']=$cancelaciones[$i]['feccan']['value'];

					} // for($i=0;$i<$ncancelaciones;$i++){


					if(isset($arrayRespuesta['resser']['notser']['txtinf']['value'])){

						$resultado['observaciones_proveedor']=utf8_encode($arrayRespuesta['resser']['notser']['txtinf']['value']);

					}else if(isset($arrayRespuesta['resser']['estsmo'][0]['notser']['txtinf']['value'])){

						$resultado['observaciones_proveedor']=utf8_encode($arrayRespuesta['resser']['estsmo'][0]['notser']['txtinf']['value']);
								
					}

					
					$resultado['neto']=str_replace($find,$chng,$arrayRespuesta['resser']['estsmo']['impnoc']['value']);

				break; // case $this->dir_europlayas:


///////////////////
				
				case $this->dir_bedsonline:


					$arrayRespuesta=$this->xml2array($data[0]);
					$comision = $this->bedsonline->comision;
					
					if(isset($arrayRespuesta['PurchaseConfirmRS']['ErrorList']['Error']['Message']['value']) || count($arrayRespuesta)==0){

						$resultado['confirmacion']=0;

   	 					if(isset($arrayRespuesta['PurchaseConfirmRS']['ErrorList']['Error']['Message']['value'])){
   	 						$resultado['motivo'] = $arrayRespuesta['PurchaseConfirmRS']['ErrorList']['Error']['Message']['value'];
   	 					}else{
   	 						$resultado['motivo'] = "Motivo desconocido";
   	 					}

						break;
					}


					$resultado['confirmacion']=	1;
					$arrayRespuesta=$arrayRespuesta['PurchaseConfirmRS']['Purchase'];

					$resultado['neto'] = $arrayRespuesta['TotalPrice']['value'];


					$resultado['localizador']=$arrayRespuesta['Reference']['IncomingOffice']['attr']['code']."-".$arrayRespuesta['Reference']['FileNumber']['value'];

					if(isset($arrayRespuesta['ServiceList']['Service']['AvailableRoom']['HotelRoom']['CancellationPolicy']['Price']['Amount']['value']) &&
					   strlen($arrayRespuesta['ServiceList']['Service']['AvailableRoom']['HotelRoom']['CancellationPolicy']['Price']['Amount']['value'])>0){
						$cancelaciones[0]=$arrayRespuesta['ServiceList']['Service']['AvailableRoom']['HotelRoom']['CancellationPolicy']['Price'];
					}else{
						$cancelaciones=$arrayRespuesta['ServiceList']['Service']['AvailableRoom']['HotelRoom']['CancellationPolicy']['Price'];
					}


					for($i=0;$i<count($cancelaciones);$i++){

						$resultado['cancelacion'][$i]['total']=$cancelaciones[$i]['Amount']['value'];
						$porcentaje[$i] = ($resultado['cancelacion'][$i]['total']*$comision)/100;
						$resultado['cancelacion'][$i]['total']+=$porcentaje[$i];
						$resultado['cancelacion'][$i]['fecha']=formatear_fecha($cancelaciones[$i]['DateTimeFrom']['attr']['date'],x,i);

					}


					
					$resultado['observaciones_proveedor']="";
					if(isset($arrayRespuesta['ServiceList']['Service']['ContractList']['Contract']['CommentList']['Comment']['value'])){
						$resultado['observaciones_proveedor']=$arrayRespuesta['ServiceList']['Service']['ContractList']['Contract']['CommentList']['Comment']['value'];
					}
					
					$resultado['pagadero']=$arrayRespuesta['ServiceList']['Service']['Supplier']['attr']['name'];
					
					
				break; //case $this->dir_bedsonline:

				//////////////////////
				case $this->dir_veturis:

					$arrayRespuesta=$this->xml2array($data[0]);

					// este nuevo tipo de error lo he descubierto haciendo pruebas
					if(isset($arrayRespuesta['ResponseDetails']['Error']['value'])){

						$resultado['confirmacion']=0;
						$resultado['motivo']=$arrayRespuesta['ResponseDetails']['Error']['value'];
						break;
					}

					$arrayRespuesta=$arrayRespuesta['BookingConfirmationRS'];

					if(isset($arrayRespuesta['ERROR']) || count($arrayRespuesta)==0){


						$response=$arrayRespuesta['Errors'];

						if(isset($response['Error'][0])){

							$n_errores=count($response['Error']);

						}else if(!isset($response['Error'][0]) && isset($response['Error']['value'])){

							$n_errores=1;
							$response['Error'][0]=$response['Error'];

						}


						$resultado['confirmacion']=0;
						$resultado['motivo']="";
						for($err=0;$err<$n_errores;$err++){

							if($err>0){
								$resultado['motivo'].="<br>";
							}

							if($response['Error'][$err]['value']=="Tipo de pago no permitido/Payment method not allowed"){
								$resultado['motivo'].="Limite de credito superado";
							}else{
								$resultado['motivo'].=$response['Error'][$err]['value'];
							}
	
						}


						break;

					} // if(isset($arrayRespuesta['BookingConfirmationRS']['ERROR']) || count($arrayRespuesta)==0){

					$resultado['confirmacion']=1;
					$resultado['neto']=$this->parametros['token']/100;
					$resultado['observaciones_proveedor']=$this->parametros['observaciones_proveedor'];
					$resultado['SecurityCode']=$arrayRespuesta['SecurityCode']['value']; // 4 clx
					$resultado['BookingID']=$arrayRespuesta['BookingID']['value']; // 4 clx
					$resultado['localizador']=$arrayRespuesta['Locator']['value'];
					$resultado['pagadero']="Reservado y pagadero por Veturis Travel S.A.";

				break; // eof case $this->dir_veturis:

			} // eof switch($dir){

			return $resultado;
		} // public function formatearDetallesConfirm($dir,$data){

		public function formatearInfoCancelacion($dir,$data){

			##############################
			#	DEVOLVER OBLIGARTORIO
			# ['error'] => boleano (vamos a usarlo como avisador)
			# ['gastos'] => txt con los datos de las cancelaciones
			# ['importe'] => importe que hay de gastos en este instante
			### OCASIONAL EN CASO DE QUE VENGA
			# ['error_proveedor'] => si el proveedor trae mensaje de error
			#
			#
			#

			$resultado=array();

			switch($dir){

				case $this->dir_serhs:

					$arrayRespuesta=$this->xml2array($data[0]);				
					$arrayRespuesta=$arrayRespuesta['response'];

					if($arrayRespuesta['status']['value']!='0' || count($arrayRespuesta)==0){
   	 					

						$resultado['error']=1;
						$resultado['error_proveedor']=$arrayRespuesta['status']['value'];
						$resultado['gastos']="Debido a un error no hemos podido obtener los gastos de cancelaci&oacute;n por favor intentelo de nuevo mas tarde.";

    					break;
					}
					
					$comision=$this->serhs->comision;
					$resultado['importe']=(int)$arrayRespuesta['amount_details']['nett_amount']['value'];
					$resultado['error']=0;

					if($resultado['importe']>0){


						$find=array(".",",");
						$chng=array("",".");
						$resultado['importe']=str_replace($find,$chng,$resultado['importe']);
						$porcentaje[$i] = ($resultado['importe']*$comision)/100;
						$resultado['importe']+=$porcentaje[$i];


					}

					$resultado['gastos']="Tienes unos gastos de <b>".number_format($resultado['importe'],2,".","")." &euro;</b> si cancelas en estos momentos.";

					$data = $this->DB->select('voucher','reservas_realizadas','id='.$this->parametros['id_reserva_gastos']);
            		$row = json_decode(utf8_encode($data[f][0]['voucher']),true);

	                for($i=0;$i<$row['gastos'];$i++){

	                	// eliminamos la posibilidad de que salagn resultados con 0€
	                	if($row['importe_'.$i]>0){
	                		$resultado['gastos'].="<br>Cancelando desde <b>".$row['fecha_'.$i]."</b> tienes unos gastos de <b>".number_format( $row['importe_'.$i],2,".","")." &euro;</b>";
	                	}
	                                                       
	                } // for($i=0;$i<count($cancelaciones);$i++){

				break; // case $this->dir_serhs:
				
				case $this->dir_versys:

					$arrayRespuesta=$this->xml2array($data[0]);
					$arrayRespuesta=$arrayRespuesta['soap:Envelope']['soap:Body']['OTA_CancelServiceResponse']['OTA_CancelRS'];

					if(isset($arrayRespuesta['Errors']['ErrorType']) || count($arrayRespuesta)==0){
							
						$resultado['error']=1;
						$resultado['gastos'] ="Debido a un error no hemos podido obtener los gastos de cancelaci&oacute;n por favor intentelo de nuevo mas tarde.";
						$resultado['error_proveedor']=$arrayRespuesta['Errors']['ErrorType']['attr']['ShortText'];
						break;
					}


					if(isset($arrayRespuesta['Warnings']['WarningType']['value'])){

						$resultado['error']=0;
						$comision=$this->versys->comision;
						$txt_gastos=$arrayRespuesta['Warnings']['WarningType']['value'];

						list($importe,$m)=explode(" ", $txt_gastos);
						$resultado['importe']=(int)$importe;

						if($resultado['importe']>0){

							//$resultado['importe']=str_replace($find,$chng,$resultado['importe']);
							$porcentaje[$i] = ($resultado['importe']*$comision)/100;
							$resultado['importe']+=$porcentaje[$i];


						}

						$resultado['gastos']="Tienes unos gastos de <b>".number_format($resultado['importe'],2,".","")." &euro;</b> si cancelas en estos momentos.";

						// saco los gastos desde la reserva
						$data = $this->DB->select('voucher','reservas_realizadas','id='.$this->parametros['id_reserva_gastos']);
	            		$row = json_decode(utf8_encode($data[f][0]['voucher']),true);

		                for($i=0;$i<$row['gastos'];$i++){

		                	// eliminamos la posibilidad de que salagn resultados con 0€
		                	if($row['importe_'.$i]>0){
		                		$resultado['gastos'].="<br>Cancelando desde <b>".$row['fecha_'.$i]."</b> tienes unos gastos de <b>".number_format( $row['importe_'.$i],2,".","")." &euro;</b>";
		                	}
		                                                       
		                } // for($i=0;$i<count($cancelaciones);$i++){


					}else{

						$resultado['error']=1;
						$resultado['gastos'] ="Debido a un error no hemos podido obtener los gastos de cancelaci&oacute;n por favor intentelo de nuevo mas tarde.";
						$resultado['error_proveedor']="Cancellation cost could not be calculated. Reservation was not cancelled.";
						break;

					}
                                 
				break; // case $this->dir_versys:

				case $this->dir_solole:
					$arrayRespuesta=$this->xml2array($data[0]);

// $this->registrarError(6,$data,$this->solole->infoCancelacion(),$this->parametros['idReserva']);
					

					if(isset($arrayRespuesta['soap:Envelope']['soap:Body']['OTA_CancelServiceResponse']['OTA_CancelRS']['Errors']['ErrorType']) || count($arrayRespuesta)==0){
						// $this->registrarError(6,$data,$this->solole->infoCancelacion(),$this->parametros['idReserva']);
						break;
					}


					
$arrayRespuesta=$arrayRespuesta['soap:Envelope']['soap:Body']['OTA_CancelServiceResponse']['OTA_CancelRS'];

if(isset($arrayRespuesta['Warnings']['WarningType']['value'])){
$resultado['gastos']=$arrayRespuesta['Warnings']['WarningType']['value'];
}else{

$resultado['gastos']=$arrayRespuesta['Warnings']['WarningType']['attr']['ShortText'];

}


                                      
				break;


				case $this->dir_europlayas: 

						$arrayRespuesta=$this->xml2array($data[0]);


						if(isset($arrayRespuesta['PublisherError']) ||  isset($arrayRespuesta['ReservaAbrirRespuesta']['coderr']) || count($arrayRespuesta)==0 ){
							
							$resultado['error']=1;
							$resultado['error_proveedor'] = isset($arrayRespuesta['ReservaAbrirRespuesta']['txterr']['value']) ? $arrayRespuesta['ReservaAbrirRespuesta']['txterr']['value'] : $arrayRespuesta['PublisherError']['txterr']['value'];
							$resultado['gastos']="Debido a un error no hemos podido obtener los gastos de cancelaci&oacute;n por favor intentelo de nuevo mas tarde.";

							break;
						}

						$arrayRespuesta=$arrayRespuesta['ReservaAbrirRespuesta'];
						$resultado['error']=0;
						$comision = $this->europlayas->comision;

						// en su momento añadiremos todo lo de + de 1 hb

						$numHb=1;

						if($numHb==1){

							if(isset($arrayRespuesta['resser']['estsmo']['rstcan'][0]['feccan']['value'])){

								$resultado['fechaDcancelacion']=$arrayRespuesta['resser']['estsmo']['rstcan'][0]['feccan']['value'];

							}else if(isset($arrayRespuesta['resser']['estsmo']['rstcan']['feccan']['value'])){

								$resultado['fechaDcancelacion']=$arrayRespuesta['resser']['estsmo']['rstcan']['feccan']['value'];
							}

						}

						if($numHb==1){
					

						if(isset($arrayRespuesta['resser']['estsmo']['rstcan'][0]['feccan']['value'])){

							$cancelaciones=$arrayRespuesta['resser']['estsmo']['rstcan'];
							$ncancelaciones=count($arrayRespuesta['resser']['estsmo']['rstcan']);

						}else if(isset($arrayRespuesta['resser']['estsmo']['rstcan']['feccan']['value'])){

							$cancelaciones[0]=$arrayRespuesta['resser']['estsmo']['rstcan'];
							$ncancelaciones=1;

						}

											
	########################HE MEJORARO MUCHO EL METODO DE SACAR LOS GASTOS TENERLO EN CUENTA CUANDO ARREGLE + DE 1 HB############################

						$find=array(".",",");
						$chng=array("",".");
						$hoy=date('d/m/Y');

						for($i=0;$i<$ncancelaciones;$i++){

							$aplicacion[$i]=$cancelaciones[$i]['aplcan']['value'];

								if(isset($cancelaciones[$i]['impcan']['value'])){

									$total[$i]=str_replace($find,$chng,$cancelaciones[$i]['impcan']['value']);
									$porcentaje[$i] = ($total[$i]*$comision)/100;
									$total[$i]+=$porcentaje[$i];

								}else if(isset($cancelaciones[$i]['porcan']['value'])){

									$total[$i]=str_replace($find,$chng,$arrayRespuesta['impnoc']['value']);
									$porcentaje[$i]=($total[$i]*$comision)/100;
									$total[$i]+=$porcentaje[$i];
									$porcan[$i]=$cancelaciones[$i]['porcan']['value'];

									if($aplicacion[$i]=="T"){

										$total[$i]=($total[$i]*$porcan[$i])/100;

									}else if($aplicacion[$i]=="P"){ // if($aplicacion[$i]=="T"){

										//tenemos que sacar el nº de noches y dividir el total sobre este.
										// luego aplicarle el % al resultado.
		 								$entrada = explode(" ",$arrayRespuesta['resser']['fecini']['value']);
		 								$salida = explode(" ",$arrayRespuesta['resser']['fecfin']['value']);
										$nDnoches=comparar_fechas($entrada[0],$salida[0],i);
										$importeNoches=$total[$i]/$nDnoches;
										$total[$i]=($importeNoches*$porcan[$i])/100;

									} // }else if($aplicacion[$i]=="P"){
									
								} // }else if(isset($cancelaciones[$i]['porcan']['value'])){


								$fecha[$i]=$cancelaciones[$i]['feccan']['value'];

							if($i==0){
								// poder hacer el primer mensaje y determinar si tenemos o no gastos
								if(comparar_fechas($hoy,$fecha[$i])>0){
									$resultado['importe']=0.00;
								}else if(comparar_fechas($hoy,$fecha[$i])<0){
									$resultado['importe']=$total[$i];
								}

								$resultado['gastos']="Tienes unos gastos de <b>".number_format($resultado['importe'],2,".","")." &euro;</b> si cancelas en estos momentos.";

							} // if($c==0){
								$resultado['gastos'].="<br>Cancelando desde <b>".$fecha[$i]."</b> tienes unos gastos de <b>".number_format($total[$i],2,".","")." &euro;</b>";

						} // for($i=0;$i<$ncancelaciones;$i++){

					} // if($numHb==1){

				break; // case $this->dir_europlayas: 

				case $this->dir_bedsonline:
				
					$arrayRespuesta=$this->xml2array($data[0]);
					$arrayRespuesta=$arrayRespuesta['PurchaseCancelRS'];
					
					if(isset($arrayRespuesta['ErrorList']) || count($arrayRespuesta)==0){


						$resultado['error']=1;
						$resultado['error_proveedor']=$arrayRespuesta['ErrorList']['Error']['DetailedMessage']['value'];
						$resultado['gastos']="Debido a un error no hemos podido obtener los gastos de cancelaci&oacute;n por favor intentelo de nuevo mas tarde.";

						break;
					}
					
					$arrayRespuesta=$arrayRespuesta['Purchase']['ServiceList']['Service']['AvailableRoom']['HotelRoom']['CancellationPolicy'];
					$resultado['error']=0;
					$comision = $this->bedsonline->comision;
					
					if(isset($arrayRespuesta['Price']['Amount']['value'])){

						$cancelaciones[0]=$arrayRespuesta['Price'];
						$n_cancelaciones = 1;

					}else{

						$cancelaciones=$arrayRespuesta['Price'];
						// puede que vengam desordenados
                		array_multisort($cancelaciones, SORT_ASC);
						$n_cancelaciones=count($arrayRespuesta['Price']);
					}

					$hoy=date("d/m/Y");

					for($c=0;$c<$n_cancelaciones;$c++){

						$importe[$c]=$cancelaciones[$c]['Amount']['value'];
						$porcentaje[$c]=$importe[$c]/100*$comision;
						$importe[$c]+=$porcentaje[$c];
						$fecha[$c]=formatear_fecha($cancelaciones[$c]['DateTimeFrom']['attr']['date'],x,i);

						if($c==0){
							// poder hacer el primer mensaje y determinar si tenemos o no gastos
							if(comparar_fechas($hoy,$fecha[$c])>0){
								$resultado['importe']=0.00;
							}else if(comparar_fechas($hoy,$fecha[$c])<0){
								$resultado['importe']=$importe[$c];
							}

							$resultado['gastos']="Tienes unos gastos de <b>".number_format($resultado['importe'],2,".","")." &euro;</b> si cancelas en estos momentos.";

						} // if($c==0){

						$resultado['gastos'].="<br>Cancelando desde <b>".$fecha[$c]."</b> tienes unos gastos de <b>".number_format($importe[$c],2,".","")." &euro;</b>";


					}// for($c=0;$c<$n_cancelaciones;$c++){
	
				break; // case $this->dir_bedsonline:

				case $this->dir_veturis:

						$arrayRespuesta=$this->xml2array($data[0]);
						$arrayRespuesta=$arrayRespuesta['BookingCancellationRS'];

						
						if(isset($arrayRespuesta['ERROR']) && $arrayRespuesta['ERROR']['value']==1 || count($arrayRespuesta)==0){

							$resultado['error']=1;
							$resultado['error_proveedor']=$arrayRespuesta['Errors']['Error']['value'];
							$resultado['gastos']="Debido a un error no hemos podido obtener los gastos de cancelaci&oacute;n por favor intentelo de nuevo mas tarde.";

						}else if(!isset($arrayRespuesta['ERROR']) && $arrayRespuesta['BookingStatus']['value']!=C){

							$resultado['error']=1;
							$resultado['gastos']="Esta reserva ya ha sido cancelada anteriormente o no esta bien confirmada, nuestro equipo se encargara de ello. Te contactaremos con una soluci&oacute;n antes de 24h laborables.";

						}else if(!isset($arrayRespuesta['ERROR']) && isset($arrayRespuesta['CurrentCancellationPrice']) && $arrayRespuesta['BookingStatus']['value']==C){

							$comision = $this->veturis->comision;
							$resultado['error']=0;
							// echo " la comision es ".$comision;

							$porcentaje=$arrayRespuesta['CurrentCancellationPrice']['value']*$comision/100;
							$resultado['importe'] =$arrayRespuesta['CurrentCancellationPrice']['value']+$porcentaje;
							
							$resultado['gastos']="Tienes unos gastos de <b>".number_format($resultado['importe'],2,".","")." &euro;</b> si cancelas en estos momentos.";

							$cancelaciones=$arrayRespuesta['CancellationPolicies'];

							if(isset($cancelaciones['CancellationPolicy'][0]['FromDate'])){

								$n_cancelaciones=count($cancelaciones['CancellationPolicy']);

							}else if(!isset($cancelaciones['CancellationPolicy'][0]['FromDate']) 
									&& isset($cancelaciones['CancellationPolicy']['FromDate']['value'])){

								$n_cancelaciones=1;
								$cancelaciones['CancellationPolicy'][0]=$cancelaciones['CancellationPolicy'];

							} // if(isset($cancelaciones['Period'][0])){

							for($i=0;$i<$n_cancelaciones;$i++){

								$resultado['cancelacion'][$i]['total']=$cancelaciones['CancellationPolicy'][$i]['Price']['value'];
								$porcentaje = ($resultado['cancelacion'][$i]['total']*$comision)/100;
								$resultado['cancelacion'][$i]['total']+=$porcentaje;
								$resultado['cancelacion'][$i]['fecha']=formatear_fecha($cancelaciones['CancellationPolicy'][$i]['FromDate']['value'],m,i);
								$resultado['gastos'].="<br>Cancelando desde <b>".$resultado['cancelacion'][$i]['fecha']."</b> tienes unos gastos de <b>".number_format($resultado['cancelacion'][$i]['total'],2,".","")." &euro;</b>";

							} // for($i=0;$i<$n_cancelaciones;$i++){

						} /// }else if(!isset($arrayRespuesta['ERROR']) && ......
				
				break; //eof case $this->dir_veturis:
				
			} // //OEF switch($dir){
			return $resultado;
		} // public function formatearInfoCancelacion($dir,$data){
		
		public function formatearCancelacion($dir,$data){

			#############################################
			#
			# ['cancelada'] => boleano
			# ['importe_gastos'] => importe con los gastos que vamos a mostrar en pantalla
			#
			#

			$resultado=array();

			switch($dir){

				case $this->dir_serhs:

					echo $data[0];
					$arrayRespuesta=$this->xml2array($data[0]);
					$arrayRespuesta=$arrayRespuesta['response'];

					if($arrayRespuesta['status']['value']!='0' || count($arrayRespuesta)==0){
   	 					

						$resultado['cancelada']=0;
						$resultado['error_proveedor']=$arrayRespuesta['status']['value'];
						$resultado['mensaje']="Debido a un error no hemos podido cancelar esta reserva, en breve lo solucionamos y te contactaremos por email con la soluci&oacute;n. Disculpa las molestias ocasionadas.";

    					break;
					}
					
					
					if($arrayRespuesta['status']['value']==0 && $arrayRespuesta['booking']['attr']['cancelled']=="yes" && isset($arrayRespuesta['cancel']['attr']['code']) && $arrayRespuesta['cancel']['attr']['code']!=""){

						$resultado['cancelada']=1;
						$comision=$this->serhs->comision;
						$resultado['importe']=(int)$arrayRespuesta['amount_details']['nett_amount']['value'];
					
						if($resultado['importe']>0){

							$find=array(".",",");
							$chng=array("",".");
							$resultado['importe']=str_replace($find,$chng,$resultado['importe']);
							$porcentaje = ($resultado['importe']*$comision)/100;
							$resultado['importe']+=$porcentaje;

						}
						
						$resultado['mensaje']="La reserva se ha cancelado correctamente y tienes un gasto de <b>".number_format($resultado['importe'],2,".","")." &euro;</b>";

					}else{// if($arrayRespuesta['status']['value']==0 &&...
						
						$resultado['cancelada']=0;
						$resultado['mensaje']="Debido a un error no hemos podido cancelar esta reserva, en breve lo solucionamos y te contactaremos por email con la soluci&oacute;n. Disculpa las molestias ocasionadas.";

    					break;
					}

				break; // case $this->dir_serhs
				
				case $this->dir_versys:


					echo $data[0];
					$arrayRespuesta=$this->xml2array($data[0]);

					$arrayRespuesta=$arrayRespuesta['soap:Envelope']['soap:Body']['OTA_CancelServiceResponse']['OTA_CancelRS'];

					if(isset($arrayRespuesta['Errors']['ErrorType']) || count($arrayRespuesta)==0){
							
						$resultado['cancelada']=0;
						$resultado['gastos'] ="Debido a un error no hemos podido obtener los gastos de cancelaci&oacute;n por favor intentelo de nuevo mas tarde.";
						$resultado['error_proveedor']=$arrayRespuesta['Errors']['ErrorType']['attr']['ShortText'];
						break;
					}


					if(isset($arrayRespuesta['Warnings']['WarningType']['value']) && $arrayRespuesta['Warnings']['WarningType']['attr']['Type']==9){

						$resultado['cancelada']=1;
						$comision=$this->versys->comision;
						$txt_gastos=$arrayRespuesta['Warnings']['WarningType']['value'];

						list($importe,$m)=explode(" ", $txt_gastos);
						$resultado['importe']=(int)$importe;

						if($resultado['importe']>0){

							//$resultado['importe']=str_replace($find,$chng,$resultado['importe']);
							$porcentaje[$i] = ($resultado['importe']*$comision)/100;
							$resultado['importe']+=$porcentaje[$i];


						}

						$resultado['mensaje']="La reserva se ha cancelado correctamente y tienes un gasto de <b>".number_format($resultado['importe'],2,".","")." &euro;</b>";
						
					}else{// isset($arrayRespuesta['Warnings']['WarningType'...
						
						$resultado['cancelada']=0;
						$resultado['mensaje']="Debido a un error no hemos podido cancelar esta reserva, en breve lo solucionamos y te contactaremos por email con la soluci&oacute;n. Disculpa las molestias ocasionadas.";

    					break;
					}


					// if ($arrayRespuesta['soap:Envelope']['soap:Body']['OTA_CancelServiceResponse']['OTA_CancelRS']['Warnings']['WarningType']['attr']['Type']==9){
					// 	$resultado['estado']=0;
					// 	$resultado['codigoCancelacion']=0;
					// }else{
						
					// }

				break; // case $this->dir_versys:

                case $this->dir_solole:
					$arrayRespuesta=$this->xml2array($data[0]);


					
					if ($arrayRespuesta['soap:Envelope']['soap:Body']['OTA_CancelServiceResponse']['OTA_CancelRS']['Warnings']['WarningType']['attr']['Type']==9){
						$resultado['estado']=0;
						$resultado['codigoCancelacion']=0;

					}else{

						 // $this->registrarError(4,$data,$this->solole->cancelar(),$this->parametros['idReserva']);
					}
				break;


				case $this->dir_europlayas:

					$arrayRespuesta=$this->xml2array($data[0]);


					if(isset($arrayRespuesta['PublisherError']) ||  isset($arrayRespuesta['ReservaCancelarRespuesta']['coderr']) || count($arrayRespuesta)==0){

						$resultado['cancelada']=0;
						$resultado['error_proveedor']=isset($arrayRespuesta['ReservaCancelarRespuesta']['txterr']['value']) ? $arrayRespuesta['ReservaCancelarRespuesta']['txterr']['value'] : $arrayRespuesta['PublisherError']['txterr']['value'];
						$resultado['mensaje']="Debido a un error no hemos podido cancelar esta reserva, en breve lo solucionamos y te contactaremos por email con la soluci&oacute;n. Disculpa las molestias ocasionadas.";
						break;
					}

					$arrayRespuesta=$arrayRespuesta['ReservaCancelarRespuesta'];
					
					if (isset($arrayRespuesta['locata']) && isset($arrayRespuesta['impcan'])){

						$comision=$this->europlayas->comision;
						$resultado['cancelada']=1;
						$resultado['importe']=$arrayRespuesta['impcan']['value'];

						if($resultado['importe']>0){

							$find=array(".",",");
							$chng=array("",".");
							$porcentaje = $resultado['importe']/100*6;
							$resultado['importe']+=$porcentaje;

						}
						

						$resultado['mensaje']="La reserva se ha cancelado correctamente y tienes un gasto de <b>".number_format($resultado['importe'],2,".","")." &euro;</b>";

					}else{

						$resultado['cancelada']=0;
						$resultado['mensaje']="Debido a un error no hemos podido cancelar esta reserva, en breve lo solucionamos y te contactaremos por email con la soluci&oacute;n. Disculpa las molestias ocasionadas.";

					}

				break; // case $this->dir_europlayas:
			 
				case $this->dir_bedsonline:
					//echo $data[0];

						$arrayRespuesta=$this->xml2array($data[0]);
						$arrayRespuesta=$arrayRespuesta['PurchaseCancelRS'];
						
						if(isset($arrayRespuesta['ErrorList']) || count($arrayRespuesta)==0){

								$resultado['cancelada']=0;
								$resultado['error_proveedor']=$arrayRespuesta['ErrorList']['Error']['DetailedMessage']['value'];
								$resultado['mensaje']="Debido a un error no hemos podido cancelar esta reserva, en breve lo solucionamos y te contactaremos por email con la soluci&oacute;n. Disculpa las molestias ocasionadas.";
							break;
						}


						if(isset($arrayRespuesta['Purchase']['Status']['value']) &&  $arrayRespuesta['Purchase']['Status']['value']=="CANCELLED"){

							$comision = $this->bedsonline->comision;
							$resultado['cancelada']=1;


							$resultado['importe'] =$arrayRespuesta['Purchase']['TotalPrice']['value'];
							$porcentaje=$resultado['importe']*$comision/100;
							$resultado['importe']+=$porcentaje;
							
							$resultado['mensaje']="La reserva se ha cancelado correctamente y tienes un gasto de <b>".number_format($resultado['importe'],2,".","")." &euro;</b>";

						}else{

							$resultado['cancelada']=0;
							$resultado['mensaje']="Debido a un error no hemos podido cancelar esta reserva, en breve lo solucionamos y te contactaremos por email con la soluci&oacute;n. Disculpa las molestias ocasionadas.";

						}

				break; // case $this->dir_bedsonline:

				case $this->dir_veturis:

						$arrayRespuesta=$this->xml2array($data[0]);
						$arrayRespuesta=$arrayRespuesta['BookingCancellationRS'];

						
						if(isset($arrayRespuesta['ERROR']) && $arrayRespuesta['ERROR']['value']==1  && $arrayRespuesta['BookingStatus']['value']!=A || count($arrayRespuesta)==0){

							$resultado['cancelada']=0;
							$resultado['error_proveedor']=$arrayRespuesta['Errors']['Error']['value'];
							$resultado['mensaje']="Debido a un error no hemos podido cancelar esta reserva, en breve lo solucionamos y te contactaremos por email con la soluci&oacute;n. Disculpa las molestias ocasionadas.";

						}else if(isset($arrayRespuesta['ERROR']) && $arrayRespuesta['BookingStatus']['value']==A){

							$resultado['cancelada']=1;
							$resultado['mensaje']="Esta reserva ya ha sido cancelada anteriormente o no estaba bien confirmada, por lo que puedes darla por cancelada.";

						}else if(!isset($arrayRespuesta['ERROR']) && isset($arrayRespuesta['CancellationPrice']) && $arrayRespuesta['BookingStatus']['value']==A){

							$comision = $this->veturis->comision;
							$resultado['cancelada']=1;

							$porcentaje=$arrayRespuesta['CancellationPrice']['value']*$comision/100;
							$resultado['importe'] =$arrayRespuesta['CancellationPrice']['value']+$porcentaje;
							
							$resultado['mensaje']="La reserva se ha cancelado correctamente y tienes un gasto de <b>".number_format($resultado['importe'],2,".","")." &euro;</b>";

						} /// }else if(!isset($arrayRespuesta['ERROR']) && ......
				
				break; //eof case $this->dir_veturis:

			} // switch($dir){

			return $resultado;

		} // public function formatearCancelacion($dir,$data){
	

		
		public function detallesHotel($id_hotel,$proveedor){
			
			switch($proveedor){
				case "id_serhs":
					$this->curl->addUrl($this->serhs->url_info,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE,CURLOPT_TIMEOUT =>40, CURLOPT_POST=>1,CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->serhs->detallesHotel()));
				break;

				case "id_versys":

          		$this->url=$this->dir_versys="http://wspro.w2m.travel/webservice/OTA_HotelDescriptiveInfo.asmx";

					$this->curl->addUrl($this->url,array(CURLOPT_SSL_VERIFYPEER => FALSE,CURLOPT_SSL_VERIFYHOST => FALSE,CURLOPT_TIMEOUT =>40, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->versys->detallesHotel($id_hotel)));


				break;

				case "id_bedsonline":
					$this->curl->addUrl($this->bedsonline->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>40, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->bedsonline->detallesHotel()));
				break;
			}

			$this->curl->wait();
			$resultados=$this->curl->resultado;
			if(count($resultados)==1){
				foreach($resultados as $dir=>$data){
					//echo $data[0];
					$detalleHotel = xml2array($data[0]);
					
				}
			}
			return $detalleHotel;
		}
		
		public function formatearDetallesHotel($dir,$data){

			$resultado=array();
			switch($dir){
				case $this->serhs->url_info:
					$data=$data['S:Envelope']['S:Body']['ns2:GET_ACCOMMODATION_DETAILSResponse']['accommodation'];
					
					$resultado['nombre']=$data['attr']['name'];
					$resultado['direccion']=$data['address']['value'];
					$resultado['ciudad']=$data['region']['attr']['name'];
					$resultado['pais']=$data['country']['attr']['name'];
					$resultado['estrellas']=$data['category']['attr']['code'];
					
					$resultado['latitud']=$data['gpsCoordinates']['attr']['latitude'];
					$resultado['longitud']=$data['gpsCoordinates']['attr']['longitude'];
					
					$tel=$data['phones']['phone'];
					for($i=0;$i<count($tel);$i++){
						if($tel[$i]['attr']['type']=="TEL"){
							$resultado['tlf']=$tel[$i]['attr']['number'];
						}elseif($tel[$i]['attr']['type']=="FAX"){
							$resultado['fax']=$tel[$i]['attr']['number'];
						}
					}
					
					$resultado['descripcion']=$data['description']['value']."<br />".$data['infoDescription'][value];
					
					
					if(strlen($data['facilities']['facility']['attr']['type'])>0){
						$facilidades[0]=$data['facilities']['facility'];
					}else{
						$facilidades=$data['facilities']['facility'];
					}
					for($i=0;$i<count($facilidades);$i++){
						if($facilidades[$i]['attr']['type']=="G"){
							$resultado['facilidades'][$facilidades[$i]['attr']['name']]=array();
							$pos=$facilidades[$i]['attr']['name'];
						}else{ //if($facilidades[$i]['attr']['type']=="B")
							$resultado['facilidades'][$pos][]=$facilidades[$i]['attr']['name'];
						}
					}	
					
					if(strlen($data['mediaItems']['mediaItem']['attr']['quality'])>0){
						$img[0]=$data['mediaItems']['mediaItem'];
					}else{
						$img=$data['mediaItems']['mediaItem'];
					}
					for($i=0;$i<count($img);$i++){
						if($img[$i]['attr']['type']=="IMAGE" && $img[$i]['attr']['quality']=="abc"){
							$resultado['imagenes'][]=$img[$i]['attr']['url'];
						}
					}
				break;
				
				case $this->dir_versys:
					
					$data=$data['HotelDossierQueryResponse']['Hotel'];
					$resultado['nombre']=$data['Hotel']['HotelName']['value'];
					$resultado['direccion']=$data['Hotel']['NormalAdress']['Address']['value'];
					$resultado['ciudad']=$data['City']['CityName']['value'];
					$resultado['pais']=$data['Country']['CountryName']['value'];
					$resultado['estrellas']=$data['Category']['CategoryLevel']['value'];
					$resultado['latitud']="";
					$resultado['longitud']="";
					$resultado['tlf']=$data['Hotel']['Phone']['value'];
					$resultado['fax']=$data['Hotel']['Fax']['value'];
					$resultado['email']=$data['Hotel']['Email1']['value'];
					$resultado['descripcion']=$data['Description']['value'];
					
					if(strlen($data['Activities']['Activity']['ActivityName']['value'])>0){
						$facilidades[0]=$data['Activities']['Activity'];
					}else{
						$facilidades=$data['Activities']['Activity'];
					}
					
					for($i=0;$i<count($facilidades);$i++){
						$resultado['facilidades']['Actividades'][]=$facilidades[$i]['ActivityName']['value'];
					}	
					
					if(strlen($data['GeneralPhotos']['Photo']['Url']['value'])>0){
						$img[0]=$data['GeneralPhotos']['Photo'];
					}else{
						$img=$data['GeneralPhotos']['Photo'];
					}
					
					for($i=0;$i<count($img);$i++){
						$resultado['imagenes'][]=$img[$i]['Url']['value'];
					}
					
				break;
			}
			return $resultado;
		}
		
		
		
		
	private function comprobarDistribucionEuro(){
			
		$resultado=false;
			if($this->parametros['habitacion']==1){
				$resultado=true;
			}

			return $resultado;

	} // private function comprobarDistribucionEuro(){

		
		private function comprobarDistribucion(){
			$resultado=false;
			if($this->parametros['habitacion']==1){
				$resultado=true;
			}else if($this->parametros['habitacion']==2){
				if($this->parametros['adultos_1']==$this->parametros['adultos_2'] && $this->parametros['ninos_1']==$this->parametros['ninos_2']){
					$resultado=true;
					}
				}else if($this->parametros['habitacion']==3){
				if($this->parametros['adultos_1']==$this->parametros['adultos_2'] && $this->parametros['adultos_2']==$this->parametros['adultos_3'] && $this->parametros['ninos_1']==$this->parametros['ninos_2'] && $this->parametros['ninos_2']==$this->parametros['ninos_3']){
					$resultado=true;
					}
				}else if($this->parametros['habitacion']==4){
				if($this->parametros['adultos_1']==$this->parametros['adultos_2'] && $this->parametros['adultos_2']==$this->parametros['adultos_3'] && $this->parametros['adultos_3']==$this->parametros['adultos_4'] && $this->parametros['ninos_1']==$this->parametros['ninos_2'] && $this->parametros['ninos_2']==$this->parametros['ninos_3'] && $this->parametros['ninos_3']==$this->parametros['ninos_4']){
					$resultado=true;
					}
				}else if($this->parametros['habitacion']==5){
				if($this->parametros['adultos_1']==$this->parametros['adultos_2'] && $this->parametros['adultos_2']==$this->parametros['adultos_3'] && $this->parametros['adultos_3']==$this->parametros['adultos_4'] && $this->parametros['adultos_4']==$this->parametros['adultos_5'] && $this->parametros['ninos_1']==$this->parametros['ninos_2'] && $this->parametros['ninos_2']==$this->parametros['ninos_3'] && $this->parametros['ninos_3']==$this->parametros['ninos_4'] && $this->parametros['ninos_4']==$this->parametros['ninos_5']){
					$resultado=true;
					}
				}
			
			return $resultado;
		}
		
		
		
		
		
		public function arrayHoteles($datos){
			
			if(count($datos)>0){
				
				// if(isset($_SESSION['ip_admin'])){

					!$this->preparar_sql->execute();


					if($resultado = $this->preparar_sql->get_result()){

						$DatosHotel=$resultado->fetch_assoc();
						$idHotel=$DatosHotel['id'];
						$estadoHotel=$DatosHotel['estado'];

					}else{

						$idHotel='';
						$estadoHotel=1;
					}

				// }else{


				// 	$this->DB->query("select id,estado from _hoteles where ".$datos['proveedor']." like '".$datos['codeHotel']."'");
				
				// 	if($this->DB->rows()>0){
				// 		$DatosHotel=$this->DB->fetch();
				// 		$idHotel=$DatosHotel['id'];
				// 		$estadoHotel=$DatosHotel['estado'];
				// 	}

				// }

				
				if(!empty($idHotel) && $estadoHotel==0){

					if(!array_key_exists($idHotel,$this->ListadoHoteles)){
						$this->ListadoHoteles[$idHotel]=$datos;
						unset($this->ListadoHoteles[$idHotel]['precios']); 
					}else{
						if($datos['precioFormat'] < $this->ListadoHoteles[$idHotel]['precioFormat']){
							$this->ListadoHoteles[$idHotel]['precio']=$datos['precio'];		
							$this->ListadoHoteles[$idHotel]['precioFormat']=$datos['precioFormat'];		
						}
					// if(!array_key_exists($idHotel,$this->ListadoHoteles)){	
					}
					
		
					if(isset($this->ListadoHoteles[$idHotel]['listadoPrecios']) && is_array($this->ListadoHoteles[$idHotel]['listadoPrecios'])){ 

						$this->ListadoHoteles[$idHotel]['listadoPrecios']=array_merge($this->ListadoHoteles[$idHotel]['listadoPrecios'],$datos['precios']);

					}else{

						$this->ListadoHoteles[$idHotel]['listadoPrecios']=$datos['precios'];
					// isset($this->ListadoHoteles[$idHotel]['listadoPrecios'])	
					}

				// if(!empty($idHotel) && $estadoHotel==0){
				}
			// if(count($datos)>0){	
			}
		// public function arrayHoteles($datos){
		}
		



		public function formatearDetalleReserva($dir,$data){

			########### var que hay que sacar
			#
			# neto
			# total
			# entrada
			# salida
			# observaciones_reserva
			# observaciones_proveedor
			# creacion
			#  titular_reserva
			# pagadero
			# localizador
			# nombre_hotel
			# id_hoteles
			# habitaciones (numero de hb)
			# regimen
			# habitacion (tipo de habitacion)
			# adultos_
			# ninos_
			# edad_ _	
			# gastos (n_gastos)
			# importe_
			# fecha_

			$resultado=array();

			switch($dir){

				case $this->dir_serhs:

					$comision = $this->serhs->comision;


					$data=$data['response']['booking'];

					$resultado['localizador']=$data['locator']['value'];

					$resultado['codigo']=$data['locator']['value'];

					$resultado['comunicacion_tpv_reservas']=$data['clients']['client']['client_locator']['value'];

					$resultado['creacion']=formatear_fecha($data['date']['value'],x,i);
				
					$resultado['pagadero']=$data['invoice_by']['name']['value'];

					$resultado['entrada']=formatear_fecha($data['period']['date_in']['value'],x,i);

					$resultado['salida']=formatear_fecha($data['period']['date_out']['value'],x,i);

					$resultado['nombre_hotel']=$data['accommodation']['name']['value'];

					// 
					$id_hotel = $this->DB->select('id','_hoteles','id_serhs like "'.$data['accommodation']['code']['value'].'"');
					$resultado['id_hoteles']=$id_hotel[f][0]['id'];
					
					$resultado['titular_reserva']=$data['clients']['client']['name']['value'];
					

					$resultado['observaciones']='Sin observaciones del cliente';
					if(isset($data['clients']['client']['remarks']['value'])){
					
						$resultado['observaciones']=$data['clients']['client']['remarks']['value'];
					}


					$resultado['observaciones_proveedor']='Sin observaciones del hotel';
					if(isset($data['remarks']['collects']['collect']['address']['value'])){

						$resultado['observaciones_proveedor']=$data['remarks']['collects']['collect']['address']['value'];

					}

					$find=array(".",",");
					$chng=array("",".");
					$resultado['neto']=str_replace($find,$chng,$data['amount_details']['nett_amount']['value']);

					$resultado['total']=(($resultado['neto']*$comision)/100) + $resultado['neto'];

					$resultado['total']=number_format($resultado['total'],2,".","");
					
					if(isset($data['clients']['client']['rooms']['room']['name']['value'])){
					
						$distribucion[0]=$data['clients']['client']['rooms']['room'];
					
					}else{
					
						$distribucion=$data['clients']['client']['rooms']['room'];
					
					}

					$resultado['habitacion']=$distribucion[0]['name']['value'];
					$resultado['habitaciones']=count($distribucion);
					$resultado['regimen']=$data['board']['name']['value'];


					for($i=0;$i<count($distribucion);$i++){

						
						$resultado['adultos_'.($i+1)]=$distribucion[$i]['adults']['value'];
						$resultado['ninos_'.($i+1)]=$distribucion[$i]['children']['value'];

						if($resultado['ninos_'.($i+1)]==1){

							$resultado['edad_'.($i+1).'_1']=$distribucion[$i]['children_ages']['value'];

						}else{

							$edades=explode('#',$distribucion[$i]['children_ages']['value']);

							for ($e=0; $e <count($edades) ; $e++) { 
								
								$resultado['edad_'.($i+1).'_'.($e+1)]=$edades[$e];

							}

						}

					} // for($i=0;$i<count($distribucion);$i++){

					if(isset($data['cancelpolicies']['cancelPolicy']['attr']['amount'])){
					
						$cancelaciones[0]=$data['cancelpolicies']['cancelPolicy'];
					
					}else{
					
						$cancelaciones=$data['cancelpolicies']['cancelPolicy'];
					
					}

					$resultado['gastos']=count($cancelaciones);


					for($i=0;$i<count($cancelaciones);$i++){

						
						$resultado['importe_'.$i]=str_replace($find,$chng,$cancelaciones[$i]['attr']['amount']);

						$resultado['importe_'.$i]+=(($resultado['neto']*$comision)/100);

						$resultado['importe_'.$i]=number_format($resultado['total'],2,".","");


						$resultado['fecha_'.$i]=formatear_fecha($cancelaciones[$i]['attr']['releaseDate'],x,i);
	
					}

					

				break;
				
				case $this->dir_versys:
					

					$comision = $this->versys->comision;


					$data=$data['soap:Envelope']['soap:Body']['OTA_ReadServiceResponse']['OTA_ResRetrieveRS']['ReservationsList'];

					if(!isset($data['HotelReservation']['attr']['ResStatus'])){

						alert('Esta formato de esta reserva no esta contemplado, avisa a Pedro');

					}else{

						$data=$data['HotelReservation'];


						$resultado['localizador'] = $data['UniqueID']['attr']['ID'];

						$nombre = $data['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['PersonName']['GivenName']['value'];

						$apellidos = $data['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['PersonName']['Surname']['value'];

						$resultado['titular_reserva'] = $nombre.' '.$apellidos;

						$resultado['neto']=number_format($data['ResGlobalInfo']['Total']['attr']['AmountAfterTax'],2,".","");


						$resultado['total']=(($resultado['neto']*$comision)/100) + $resultado['neto'];

						$resultado['total']=number_format($resultado['total'],2,".","");

						$tpa_ext = $data['TPA_Extensions'];

						$resultado['observaciones_reserva']=$tpa_ext['Comments']['value'];

						$resultado['pagadero'] = '('.$tpa_ext['ExternalReference']['value'].') '.$tpa_ext['Payable']['value'];
 

						$RoomStays = $data['RoomStays']['RoomStay'];

						if(isset($RoomStays['RoomTypes']['RoomType']['RoomDescription']['Text']['value'])){


							$distribucion[0] = $RoomStays['RoomTypes']['RoomType'];
							
							
						}else{

							$distribucion = $RoomStays['RoomTypes']['RoomType'];

						}

						$resultado['habitaciones'] = count($distribucion);

						$resultado['habitacion'] = $distribucion[0]['RoomDescription']['Text']['value'];


						for ($h=0; $h <$resultado['habitaciones'] ; $h++) { 
							
							$resultado['adultos_'.($h+1)]=0;
							$resultado['ninos_'.($h+1)]=0;
							$edad[($h+1)]=1;

							$ocupacion = $distribucion[$h]['TPA_Extensions']['Guests'];

							if(isset($ocupacion['Guest']['attr']['Name'])){
								
								$resultado['adultos_'.($h+1)]++;

							}else if(!isset($ocupacion['Guest']['attr']['Name']) && isset($ocupacion['Guest'][0]['attr']['Name'])){

								for ($p=0; $p <count($ocupacion['Guest']) ; $p++) { 
									
									if($ocupacion['Guest'][$p]['attr']['Age']==40){

										$resultado['adultos_'.($h+1)]++;

									}else if($ocupacion['Guest'][$p]['attr']['Age']<20){

										$resultado['ninos_'.($h+1)]++;
										$resultado['edad_'.($h+1).'_'.$edad[($h+1)]]=$ocupacion['Guest'][$p]['attr']['Age'];
										$edad[($h+1)]++;

									}

								}
							} // if(isset($ocupacion['Guest']['attr']['Name'])) ...

						} // for ($h=0; $h <$resultado['habitaciones'] ; $h++) { 

						$resultado['regimen'] = $RoomStays['RatePlans']['RatePlan']['attr']['RatePlanName'];

						$resultado['entrada'] = formatear_fecha($RoomStays['TimeSpan']['attr']['Start'],m,i);

						$resultado['salida'] = formatear_fecha($RoomStays['TimeSpan']['attr']['End'],m,i);

						$resultado['observaciones_proveedor'] = '';

						if(isset($RoomStays['BasicPropertyInfo']['VendorMessages']['VendorMessage']['SubSection']['Paragraph']['Text']['value'])){

							$resultado['observaciones_proveedor'] = $RoomStays['BasicPropertyInfo']['VendorMessages']['VendorMessage']['SubSection']['Paragraph']['Text']['value'];
						}
						
						$tpa_ext['Comments']['value'] = '';
						if(isset($tpa_ext['Comments']['value'])){
						
							$resultado['observaciones_reserva']=$tpa_ext['Comments']['value'];

						}
						
						$resultado['nombre_hotel']=$RoomStays['BasicPropertyInfo']['attr']['HotelName'];

						$id_hotel = $this->DB->select('id','_hoteles','id_versys like "'.$RoomStays['BasicPropertyInfo']['attr']['JPCode'].'"');

						$resultado['id_hoteles'] = $id_hotel[f][0]['id'];

						$cancelacion=$tpa_ext['CancellationPolicy'];

						if(isset($cancelacion['CancellationPolicyRules']['Rule'][0]['attr']['DateFrom'])){

							$cancelaciones=$cancelacion['CancellationPolicyRules']['Rule'];

	               		}else{

							$cancelaciones[0]=$cancelacion['CancellationPolicyRules']['Rule'];

	               		}

	               		$resultado['gastos'] = count($cancelaciones);

	               		$c = 0;
						for($i=0;$i<count($cancelaciones);$i++){


							$importe_cancelacion[$i]=$cancelaciones[$i]['attr']['FixedPrice'];
							$noches_cancelacion[$i]=$cancelaciones[$i]['attr']['Nights'];
							$percent_cancelacion[$i]=$cancelaciones[$i]['attr']['PercentPrice'];

							if($importe_cancelacion[$i]>0){

								$resultado['importe_'.$c]=$cancelaciones[$i]['attr']['FixedPrice'];
								$porcentaje[$i] = ($resultado['importe_'.$c]*$comision)/100;
								

								$resultado['importe_'.$c] = number_format($resultado['importe_'.$c]+$porcentaje[$i],2,".","");

							}else if($percent_cancelacion[$i]>0 || $noches_cancelacion[$i]>0){ // if($importe_cancelacion[$i]>0){

								$precio_entero[$i] = $resultado['neto'];
								$porcentaje[$i] = ($precio_entero[$i]*$comision)/100;
								$precio_entero[$i]+=$porcentaje[$i];

								if($percent_cancelacion[$i]>0){

									$resultado['importe_'.$c] = number_format(($precio_entero[$i]/100)*$percent_cancelacion[$i],2,".","");

								}else if($noches_cancelacion[$i]>0){ // if($percent_cancelacion[$i]>100){

									$Start=$RoomStays['TimeSpan']['attr']['Start'];
									$End=$RoomStays['TimeSpan']['attr']['End'];

									$noches = comparar_fechas($Start,$End,m);

									$resultado['importe_'.$c] = number_format(($precio_entero[$i]/$noches)*$noches_cancelacion[$i],2,".","");

								} // }else if($noches_cancelacion[$i]>0){ 
								
							} // }else if($percent_cancelacion[$i]>0 || $noches_cancelacion[$i]>0){

							// eliminamos la posibilidad de que aparezcan fechas sin importes
							if(isset($resultado['importe_'.$c]) && $resultado['importe_'.$c]>0){

								$HyFcancelacion[$i]=$cancelaciones[$i]['attr']['DateFrom'];  

								list($resultado['fecha_'.$c],$hora)=explode("T",$HyFcancelacion[$i]);

								$resultado['fecha_'.$c]=formatear_fecha($resultado['fecha_'.$c],m,i);

								$c++;
							}
							
							
						}	// for($i=0;$i<count($cancelaciones);$i++){ 

	               		$resultado['gastos'] = $c;

// 
					} // if(!isset($data['HotelReservation']['attr' .....

				break;
				
				case $this->dir_veturis:


					$comision = $this->veturis->comision;

					$data = $data['VoucherRS']['Booking'];

					$resultado['neto']=number_format($data['NetPrice']['value'],2,".","");

					$resultado['total']=(($data['NetPrice']['value']*$comision)/100) + $data['NetPrice']['value'];

					$resultado['total']=number_format($resultado['total'],2,".","");
					
					$resultado['entrada']=formatear_fecha($data['CheckInDate']['value'],m,i);

					$resultado['salida']=formatear_fecha($data['CheckOutDate']['value'],m,i);

					$resultado['creacion']=formatear_fecha($data['CreationDate']['value'],m,i);

					$coments = $data['Comments'];

					if(isset($coments['Comment']['value'])){

						$comentarios[0]=$coments['Comment'];

					}else{

						$comentarios=$coments['Comment'];

					}

					$resultado['observaciones_reserva']='';
					$resultado['observaciones_proveedor']='';

					// echo "hay ".count($comentarios). " Comentarios";
					for ($c=0; $c<count($comentarios) ; $c++) { 
						
						if($comentarios[$c]['attr']['type']=='customer'){

							$resultado['observaciones_reserva'].=$comentarios[$c]['value'];

						}else if($comentarios[$c]['value']!='Generica'){


							$resultado['observaciones_proveedor'].=$comentarios[$c]['value'];

						}

					}

					$resultado['titular_reserva']=$data['CustomerName']['value'];

					$resultado['nombre_hotel']=$data['Hotel']['value'];

					$id_hotel = $this->DB->select('id','_hoteles','id_veturis like "'.$data['Hotel']['attr']['id'].'"');

					$resultado['id_hoteles']=$id_hotel[f][0]['id'];

					$resultado['SecurityCode'] = $data['SecurityCode']['value'];
					$resultado['BookingID'] = $data['attr']['id'];

					$resultado['localizador'] = $data['Locator']['value'];

					$resultado['pagadero']='Reservado y pagadero por Veturis Travel S.A.';

					// tenemos que sacar:
					// si solo hay 1 room el number de hay, Si ha mas rooms, el number de contarl los nodos

					$habitaciones = $data['Rooms'];

					// 1 o mas habitaciones con la misma distribucion
					if(isset($habitaciones['Room']['attr']['roomsnumber'])){

						$distribucion = $habitaciones['Room'];

						$resultado['habitaciones'] = $distribucion['attr']['roomsnumber'];
						$resultado['habitacion'] = $distribucion['CompleteRoomName']['value'];
						$resultado['regimen'] = $distribucion['BoardType']['value'];

						$ninos_totales = $distribucion['Children']['attr']['number'] * $resultado['habitaciones'];

						$edad = 0;
						for ($d=0; $d <$resultado['habitaciones'] ; $d++) { 
							
							$resultado['adultos_'.($d+1)] = $distribucion['Adults']['attr']['number'];
							$resultado['ninos_'.($d+1)] = $distribucion['Children']['attr']['number'];

							if($ninos_totales>1){

								for($e=0;$e<$resultado['ninos_'.($d+1)];$e++){

									$resultado['edad_'.($d+1).'_'.($e+1)] = $distribucion['Children']['Child'][$edad]['attr']['age'];

									$edad++;

								}

							}else if($ninos_totales==1){

								$resultado['edad_'.($d+1).'_1'] = $distribucion['Children']['Child']['attr']['age'];

							}  # }else if($ninos_totales==1){

						} // for ($d=0; $d <$resultado['habitaciones'] ; $d++) { 

					}else{ // if(isset($habitaciones['Room'] .....
						# + de 1 hb con difernte distribucion

						$distribucion = $habitaciones['Room'];
						$resultado['habitaciones'] = count($habitaciones['Room']);
						$resultado['habitacion'] = $distribucion[0]['CompleteRoomName']['value'];
						$resultado['regimen'] = $distribucion[0]['BoardType']['value'];

						for ($d=0; $d <$resultado['habitaciones'] ; $d++) { 
							
							$resultado['adultos_'.($d+1)] = $distribucion[$d]['Adults']['attr']['number'];
							$resultado['ninos_'.($d+1)] = $distribucion[$d]['Children']['attr']['number'];

							
							if($resultado['ninos_'.($d+1)]==1){

								$resultado['edad_'.($d+1).'_1'] = $distribucion[$d]['Children']['Child']['attr']['age'];

							}else if($resultado['ninos_'.($d+1)]>1){

								for($e=0;$e<$resultado['ninos_'.($d+1)];$e++){

									$resultado['edad_'.($d+1).'_'.($e+1)] = $distribucion[$d]['Children']['Child'][$e]['attr']['age'];

								} # for($e=0;$e<$resultado['ninos_'.($d+1)];$e++){

							} // }else if($resultado['ninos_'.($d+1)]>1){
							
						} // for ($d=0; $d <$resultado['habitaciones'] ; $d++) { 

					} // }else{ ....

					$cancelaciones = $data['CancellationPolicies'];

					if(isset($cancelaciones['CancellationPolicy']['FromDate']['value'])){

						$resultado['gastos']=1;

						$cancelaciones['CancellationPolicy'][0] = $cancelaciones['CancellationPolicy']; 

					}else if(!isset($cancelaciones['CancellationPolicy']['FromDate']['value']) && isset($cancelaciones['CancellationPolicy'][0]['FromDate']['value'])){

						$resultado['gastos'] = count($cancelaciones['CancellationPolicy']);
					}


					for ($g=0; $g <$resultado['gastos'] ; $g++) { 
						
						$resultado['fecha_'.$g] =  formatear_fecha($cancelaciones['CancellationPolicy'][$g]['FromDate']['value'],m,i);

						$resultado['importe_'.$g]=(($cancelaciones['CancellationPolicy'][$g]['Price']['value']*$comision)/100) + $cancelaciones['CancellationPolicy'][$g]['Price']['value'];

						$resultado['importe_'.$g]=number_format($resultado['importe_'.$g],2,".","");



					}

				break;

				case $this->dir_bedsonline:
					
					$comision = $this->bedsonline->comision;

					$data=$data['PurchaseDetailRS']['Purchase'];
					
					$resultado['neto']=number_format($data['TotalPrice']['value'],2,".","");

					$resultado['total']=(($data['TotalPrice']['value']*$comision)/100) + $data['TotalPrice']['value'];

					$resultado['total']=number_format($resultado['total'],2,".","");

					$service = $data['ServiceList']['Service'];
					
					$resultado['entrada']=formatear_fecha($service['DateFrom']['attr']['date'],x,i);

					$resultado['salida']=formatear_fecha($service['DateTo']['attr']['date'],x,i);

					$resultado['observaciones_reserva']=$service['CommentList']['Comment']['value'];

					$resultado['observaciones_proveedor']=utf8_decode($service['ContractList']['Contract']['CommentList']['Comment']['value']);

					$resultado['creacion']=formatear_fecha($data['CreationDate']['attr']['date'],x,i);

					$resultado['titular_reserva']=$data['Holder']['Name']['value'];
					// ." ".$data['Holder']['LastName']['value'];

					$resultado['pagadero']=$service['Supplier']['attr']['name'];

					$resultado['localizador']=$data['Reference']['IncomingOffice']['attr']['code']."-".$data['Reference']['FileNumber']['value'];

					$resultado['nombre_hotel']=$service['HotelInfo']['Name']['value'];
					$id_hotel = $this->DB->select('id','_hoteles','id_bedsonline like "'.$service['HotelInfo']['Code']['value'].'"');
					$resultado['id_hoteles']=$id_hotel[f][0]['id'];


					// $resultado['direccion']=$service['HotelInfo']['Destination']['ZoneList']['Zone']['value'].", ".$service['HotelInfo']['Destination']['Name']['value'];
					
					if(strlen($service['AvailableRoom']['HotelOccupancy']['RoomCount']['value'])>0){

						$distribucion[0]=$service['AvailableRoom'];

					}else{

						$distribucion=$service['AvailableRoom'];

					}

					$resultado['habitaciones'] = $distribucion[0]['HotelOccupancy']['RoomCount']['value'];

					$resultado['regimen']=$distribucion[0]['HotelRoom']['Board']['value'];

					$resultado['habitacion'] = $distribucion[0]['HotelRoom']['RoomType']['value'];

					// tengo que hacer un contador de pasajeros y atacar a los ids 
					
					$edades = $distribucion[0]['HotelOccupancy']['Occupancy']['GuestList'];
					$id_p = 0;
					for($i=0;$i<$resultado['habitaciones'];$i++){

				
						$resultado['adultos_'.($i+1)]=$distribucion[0]['HotelOccupancy']['Occupancy']['AdultCount']['value'];

						// echo "<br> la vuelta $i empieza en ". $id_p;
						// con esto su los id de pasajeros de los adultos
						$id_p+=$resultado['adultos_'.($i+1)];
						
						// echo "<br> la vuelta $i despues de los adutos vale en ". $id_p;

						$resultado['ninos_'.($i+1)]=$distribucion[0]['HotelOccupancy']['Occupancy']['ChildCount']['value'];


						if($resultado['ninos_'.($i+1)]>0){

							for($e=0;$e<$resultado['ninos_'.($i+1)];$e++){

								$id_p++;
								$resultado['edad_'.($i+1).'_'.($e+1)]=$edades['Customer'][($id_p-1)]['Age']['value'];
								// echo "<br> la vuelta $i el niño ".($e+1)." de los adutos vale en ". $id_p;
	
							}
							

						}else{

							$id_pasajero+=$resultado['ninos_'.($i+1)];

						}

						// AQUI TENEMOS QUE METER UN BUCLE CON LAS EDADES DE LOS NIÑOS 
						//$resultado['']=$distribucion[$i]['ChildrenAge1']['value'];
						
						// $resultado['distribucion'][$i]['regimen']=$distribucion[$i]['HotelRoom']['Board']['value'];
					}
					
					if(strlen($service['AvailableRoom']['HotelRoom']['CancellationPolicy']['Price']['Amount']['value'])>0){

						$cancelaciones[0]=$service['AvailableRoom']['HotelRoom']['CancellationPolicy']['Price'];

					}else{

						$cancelaciones=$service['AvailableRoom']['HotelRoom']['CancellationPolicy']['Price'];

					}

					// gastos_ importe_ fecha_

					
					$resultado['gastos'] = count($cancelaciones);


					
					for($i=0;$i<count($cancelaciones);$i++){

						// $resultado['importe_'.$i]=number_format($cancelaciones[$i]['Amount']['value'],2,".","");

						$resultado['importe_'.$i]=(($cancelaciones[$i]['Amount']['value']*$comision)/100) + $cancelaciones[$i]['Amount']['value'];

						$resultado['importe_'.$i]=number_format($resultado['importe_'.$i],2,".","");

						$resultado['fecha_'.$i]=formatear_fecha($cancelaciones[$i]['DateTimeFrom']['attr']['date'],x,i);

						// $resultado['cancelaciones'][$i]['hora']=$cancelaciones[$i]['DateTimeFrom']['attr']['time'];

						//$resultado['']=$data['CancellationConditions']['Cancellation']['NetPenaltyFee']['value'];
					}
					//$resultado['']=$data['BreakDown']['AgencyComission']['value'];

				break;

				case $this->dir_europlayas:

					$comision = $this->europlayas->comision;

					$data=$data['ReservaAbrirRespuesta'];
					
					$resultado['neto']=number_format(str_replace(",",".",$data['impnoc']['value']),2,".","");

					$resultado['total']=(($resultado['neto']*$comision)/100) + $resultado['neto'];

					$resultado['total']=number_format($resultado['total'],2,".","");
					
					$resultado['entrada']=$data['fecini']['value'];

					$resultado['salida']=$data['fecfin']['value'];

					$resultado['observaciones_reserva']="Sin observaciones";

					$resultado['observaciones_proveedor']=$data['notser']['txtinf']['value'];

					$resultado['creacion']=$data['feccre']['value'];

					$resultado['pagadero']='Reservado y pagadero por VIAJES EUROMAR S.A CIF: A07031008 ';

					if(isset($data['respas']['nombre'])){

						$titular[0] = $data['respas'];
						$pasajeros = 1;

					}else if(isset($data['respas'][0]['nombre'])){

						$titular = $data['respas'];
						$pasajeros = count($data['respas']);

					}

					$resultado['habitaciones'] = 1;
					$resultado['adultos_1']=0;
					$resultado['ninos_1']=0;
					$edad = 1;


					for ($p=0; $p < $pasajeros; $p++) { 
						
						
						if($titular[$p]['fecnac']['value']=='10/10/1980'){

							$resultado['adultos_1']++;

						}else{

							$resultado['ninos_1']++;

							list($d,$m,$y) = explode("/",$titular[$p]['fecnac']['value']);

							$resultado['edad_1_'.$edad] = date('Y') - $y;

							$edad++;
						}


					}
					


					$resultado['titular_reserva'] = $titular[0]['nombre']['value']." ".$titular[0]['priape']['value']." ".$titular[0]['segape']['value']; 

					$resultado['localizador']=$data['locata']['value'];

					$resultado['nombre_hotel']=$data['resser']['nomser']['value'];

					$id_hotel = $this->DB->select('id','_hoteles','id_europlayas like "'.$data['resser']['codser']['value'].'"');

					$resultado['id_hoteles']=$id_hotel[f][0]['id'];

					// pasajeros 

					// gastos 
					$servicio = $data['resser']['estsmo'];

					$resultado['regimen'] = $servicio['codral']['value'];
					$resultado['habitacion'] = $servicio['codsmo']['value'];

					$resultado['habitacion'] .= isset($servicio['codcha']['value']) ? " ".$servicio['codcha']['value'] : "";


					if(isset($servicio['rstcan'][0]['feccan']['value'])){

						$cancelaciones=$servicio['rstcan'];
						$ncancelaciones=count($servicio['rstcan']);

					}else if(isset($servicio['rstcan']['feccan']['value'])){

						$cancelaciones[0]=$servicio['rstcan'];
						$ncancelaciones=1;

					}

					$resultado['gastos'] = $ncancelaciones;

					for($i=0;$i<$ncancelaciones;$i++){

						$aplicacion[$i]=$cancelaciones[$i]['aplcan']['value'];

							if(isset($cancelaciones[$i]['impcan']['value'])){

								$resultado['importe_'.$i]=str_replace(",",".",$cancelaciones[$i]['impcan']['value']);
								$porcentaje[$i] = ($resultado['importe_'.$i]*$comision)/100;

								$resultado['importe_'.$i]+=$porcentaje[$i];

							}else if(isset($cancelaciones[$i]['porcan']['value'])){

								$total[$i]=str_replace(",",".",$data['impnoc']['value']);
								$porcentaje[$i]=($total[$i]*$comision)/100;
								$total[$i]+=$porcentaje[$i];
								$porcan[$i]=$cancelaciones[$i]['porcan']['value'];

								if($aplicacion[$i]=="T"){

									$resultado['importe_'.$i]=($total[$i]*$porcan[$i])/100;

								}else if($aplicacion[$i]=="P"){ // if($aplicacion[$i]=="T"){

									//tenemos que sacar el nº de noches y dividir el total sobre este.
									// luego aplicarle el % al resultado.
	 								$entrada = explode(" ",$data['resser']['fecini']['value']);
	 								$salida = explode(" ",$data['resser']['fecfin']['value']);
									$nDnoches=comparar_fechas($entrada[0],$salida[0],i);
									$importeNoches=$total[$i]/$nDnoches;
									$resultado['importe_'.$i]=($importeNoches*$porcan[$i])/100;

								} // }else if($aplicacion[$i]=="P"){
								
							} // }else if(isset($cancelaciones[$i]['porcan']['value'])){

							$resultado['fecha_'.$i]=$cancelaciones[$i]['feccan']['value'];
							$resultado['importe_'.$i] = number_format($resultado['importe_'.$i],2,".","");;
					} // for($i=0;$i<$ncancelaciones;$i++){




				break;

			} // switch($dir){

			return $resultado;

		} // public function formatearDetalleReserva($dir,$data){
		
		
		public function formatearListadoReservas($dir,$data){

			$resultado=array();

			switch($dir){

				case $this->dir_serhs:

					$data=$data['response']['bookings'];

					if(isset($data['booking']['locator']['value'])){

						$reservas[0]=$data['booking'];

					}else{

						$reservas=$data['booking'];

					}
					$x=0;
					for($i=0;$i<count($reservas);$i++){

						// if($reservas[$i]['booking_status']['code']['value']==0 || $reservas[$i]['booking_status']['code']['value']==2){
							$x++;
							$resultado[$x]['localizador']=$reservas[$i]['locator']['value'];
						
							//$resultado[$x]['total']=$reservas[$i]['TotalPrice']['value'];
							$resultado[$x]['neto']=$reservas[$i]['amount_details']['nett_amount']['value'];
							$resultado[$x]['entrada']=formatear_fecha($reservas[$i]['date_begin']['value'],x,i);
							$resultado[$x]['salida']=formatear_fecha($reservas[$i]['date_end']['value'],x,i);
							// $resultado[$x]['creacion']=formatear_fecha($reservas[$i]['date']['value'],x,i);
							$resultado[$x]['proveedor']="id_serhs";
							
							//$resultado[$x]['cliente']=$reservas[$i]['CustomerName']['value'];
						// }
					}

				break;
				
				case $this->dir_veturis:

					$data=$data['BookingListRS'];

					if(isset($data['Booking']['attr']['id'])){

						$reservas[0]=$data['Booking'];

					}else{

						$reservas=$data['Booking'];

					}


					for($i=0;$i<count($reservas);$i++){
						
						$resultado[$i]['localizador']=$reservas[$i]['Locator']['value'];
						$resultado[$i]['neto']=$reservas[$i]['NetPrice']['value'];
						$resultado[$i]['entrada']=$reservas[$i]['CheckInDate']['value'];
						$resultado[$i]['salida']=$reservas[$i]['CheckOutDate']['value'];
						// $resultado[$i]['creacion']=$reservas[$i]['CreationDate']['value'];
						$resultado[$i]['titular']=$reservas[$i]['CustomerName']['value'];
						$resultado[$i]['proveedor']="id_veturis";
						
					}

				break;

				case $this->dir_europlayas:

					$data=$data['ReservaListarRespuesta'];

					if(isset($data['estres']['locata']['value'])){

						$reservas[0]=$data['estres'];

					}else{

						$reservas=$data['estres'];

					}


					for($i=0;$i<count($reservas);$i++){
						
						$resultado[$i]['localizador']=$reservas[$i]['locata']['value'];
						$resultado[$i]['neto']=$reservas[$i]['impnoc']['value'];
						$resultado[$i]['entrada']=$reservas[$i]['fecini']['value'];
						$resultado[$i]['salida']=$reservas[$i]['fecfin']['value'];
						// $resultado[$i]['creacion']=$reservas[$i]['feccre']['value'];
						$resultado[$i]['proveedor']="id_europlayas";
						
					}

				break;
				
				case $this->dir_bedsonline:
					
					$data=$data['PurchaseListRS'];

					if(isset($data['Purchase']['Reference']['FileNumber']['value'])){

						$reservas[0]=$data['Purchase'];

					}else{

						$reservas=$data['Purchase'];

					}

						for($i=0;$i<count($reservas);$i++){

							$resultado[$i]['localizador']=$reservas[$i]['Reference']['IncomingOffice']['attr']['code']."-".$reservas[$i]['Reference']['FileNumber']['value'];
							
							$resultado[$i]['neto']=numFormato($reservas[$i]['TotalPrice']['value']);
							
							$resultado[$i]['entrada']=formatear_fecha($reservas[$i]['ServiceList']['Service']['DateFrom']['attr']['date'],x,i);
							
							$resultado[$i]['salida']=formatear_fecha($reservas[$i]['ServiceList']['Service']['DateTo']['attr']['date'],x,i);
							
							// $resultado[$i]['creacion']=formatear_fecha($reservas[$i]['CreationDate']['attr']['date'],x,i);
							
							$resultado[$i]['proveedor']="id_bedsonline";
							
						}
					
				break;
			

				case $this->dir_versys:


					$data=$data['soap:Envelope']['soap:Body']['OTA_ReadServiceResponse']['OTA_ResRetrieveRS']['ReservationsList'];

					if(isset($data['HotelReservation']['attr']['ResStatus'])){

						$reservas[0]=$data['HotelReservation'];

					}else{

						$reservas=$data['HotelReservation'];

					}

						for($i=0;$i<count($reservas);$i++){



							$resultado[$i]['localizador']=$reservas[$i]['UniqueID']['attr']['ID'];
							$fechas[$i] = $reservas[$i]['RoomStays']['RoomStay']['TimeSpan'];
							$resultado[$i]['entrada']=formatear_fecha($fechas[$i]['attr']['Start'],m,i);
							$resultado[$i]['salida']=formatear_fecha($fechas[$i]['attr']['End'],m,i);
							$resultado[$i]['neto']=numFormato($reservas[$i]['RoomStays']['RoomStay']['Total']['attr']['AmountAfterTax']);
							$resultado[$i]['proveedor']="id_versys";
							
						}
					
				break;

			} // switch($dir){

			$this->ListadoReservas=array_merge($this->ListadoReservas,$resultado);

		} // public function formatearListadoReservas($dir,$data){
		
		
		public function formatearModificar($dir,$data){
			$resultado=array();
			switch($dir){
				case $this->dir_serhs:
					$arrayRespuesta=$this->xml2array($data[0]);
					$arrayRespuesta=$arrayRespuesta['response'];
					if($arrayRespuesta['status']['value']==0){
						$resultado['estado']=0;
					}else{
//this->registrarError(5,$data,$this->serhs->modificar());
					}
					
				break;
				
				case $this->dir_versys:
					//
				break;
			}
			return $resultado;
		}
		


		public function HotelList($id_poblacion,$proveedor){

			if($proveedor=="veturis"){

          			$this->curl->addUrl($this->veturis->url,array(CURLOPT_POST=>1,CURLOPT_POSTFIELDS =>$this->veturis->HotelList($id_poblacion),CURLOPT_RETURNTRANSFER=>1,CURLOPT_SSL_VERIFYPEER => FALSE,  CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>45,CURLOPT_ENCODING =>"gzip"));

			}else if($proveedor=="versys"){ 
				
          		$this->url=$this->dir_versys=$this->versys->url.'JP_HotelList.asmx';
          		
				$this->curl->addUrl($this->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->versys->HotelList($id_poblacion)));
				// echo $this->versys->HotelList($id_poblacion);
          	}



			$this->curl->wait();
			$resultados=$this->curl->resultado;

			if(count($resultados)==1){

				foreach($resultados as $dir=>$data){
					echo $data[0];

					$resultado=$this->xml2array($data[0]);
					
				}

			}


			return $resultado;


		}


		public function ZoneList($proveedor="veturis"){

			if($proveedor=="veturis"){


          		$this->curl->addUrl($this->veturis->url,array(CURLOPT_POST=>1,CURLOPT_POSTFIELDS =>$this->veturis->ZoneList(),CURLOPT_RETURNTRANSFER=>1,CURLOPT_SSL_VERIFYPEER => FALSE,  CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>45,CURLOPT_ENCODING =>"gzip"));
				
          	}else if($proveedor=="versys"){ 
				
          		$this->url=$this->dir_versys=$this->versys->url.'JP_ZoneList.asmx';
          		
				$this->curl->addUrl($this->url,array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_TIMEOUT =>45, CURLOPT_POST=>1, CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),CURLOPT_ENCODING =>"gzip",CURLOPT_POSTFIELDS =>$this->versys->ZoneList()));

          	}
			
			$this->curl->wait();
			$resultados=$this->curl->resultado;

			if(count($resultados)==1){

				foreach($resultados as $dir=>$data){

					//echo utf8_decode($data[0]);
					$resultado=$this->xml2array($data[0]);

					
				}

			}


			return $resultado;


		}
		// private function listadoRegimenes(){
		// 	$this->DB->query("Select * from _regimenes");
		// 	while($datos=$this->DB->fetch()){
		// 		$this->regimenes['versys'][$datos['id_juniper']][0]=$datos['id'];
		// 		$this->regimenes['versys'][$datos['id_juniper']][1]=$datos['abreviatura'];

		// 		$this->regimenes['solole'][$datos['id_juniper']][0]=$datos['id'];
		// 		$this->regimenes['solole'][$datos['id_juniper']][1]=$datos['abreviatura'];

		// 		$this->regimenes['veturis'][$datos['id_veturis']][0]=$datos['id'];
		// 		$this->regimenes['veturis'][$datos['id_veturis']][1]=$datos['abreviatura'];
		// 		$this->regimenes['europlayas'][$datos['id_europlayas']][0]=$datos['id'];
		// 		$this->regimenes['europlayas'][$datos['id_europlayas']][1]=$datos['abreviatura'];
				
		// 		$this->regimenes['serhs'][$datos['id_serhs']][0]=$datos['id'];
		// 		$this->regimenes['serhs'][$datos['id_serhs']][1]=$datos['abreviatura'];
				
		// 		if(strpos($datos['id_bedsonline'],","))
		// 			$reg=explode(",",$datos['id_bedsonline']);
		// 		else
		// 			$reg[0]=$datos['id_bedsonline'];
		// 		for($i=0;$i<count($reg);$i++){
		// 			$this->regimenes['bedsonline'][$reg[$i]][0]=$datos['id'];
		// 			$this->regimenes['bedsonline'][$reg[$i]][1]=$datos['abreviatura'];
		// 		}
		// 	}
		// }
		
		// private function listadoHabitaciones(){
		// 	$this->DB->query("Select * from _habitaciones");
		// 	while($datos=$this->DB->fetch()){
		// 		$this->habitaciones['versys'][$datos['id_versys']]=$datos['id'];
				
		// 		if(strpos($datos['id_serhs'],","))
		// 			$reg=explode(",",$datos['id_serhs']);
		// 		else
		// 			$reg[0]=$datos['id_serhs'];
		// 		for($i=0;$i<count($reg);$i++){
		// 			$this->habitaciones['serhs'][$reg[$i]]=$datos['id'];
		// 		}
				
		// 		if(strpos($datos['id_bedsonline'],","))
		// 			$reg=explode(",",$datos['id_bedsonline']);
		// 		else
		// 			$reg[0]=$datos['id_bedsonline'];
		// 		for($i=0;$i<count($reg);$i++){
		// 			$this->habitaciones['bedsonline'][$reg[$i]]=$datos['id'];
		// 		}
		// 	}

		// }
		
		private function eliminarDuplicados($array,$campo="unique"){


			foreach ($array as $sub){
	   	 		$cmp[] = $sub[$campo];
  			}

  			$unique = array_unique($cmp);
  			foreach ($unique as $k => $campo){
		    	$resultado[] = $array[$k];
  			}
  			return $resultado;

		}
		
		private function ordenarPrecios($array, $cols, $keys=0){
			$colarr = array();
			foreach ($cols as $col => $order) {
				$colarr[$col] = array();
				foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
			}
			$eval = 'array_multisort(';
			foreach ($cols as $col => $order) {
				$eval .= '$colarr[\''.$col.'\'],'.$order.',';
			}
			$eval = substr($eval,0,-1).');';
			eval($eval);
			$ret = array();
			$cont=0;
			foreach ($colarr as $col => $arr) {
				foreach ($arr as $k => $v) {
					$k = substr($k,1);
					if($keys==1) $cont=$k;
					if (!isset($ret[$cont])) {
						$ret[$cont] = $array[$k];
					}
					//$ret[$k][$col] = $array[$k][$col];
					$ret[$cont][$col] = $array[$k][$col];
					$cont++;
				}
			}
			return $ret;
		}
		
		private function xml2array($contents, $get_attributes=1) {
			if(!$contents) return array();
		
			if(!function_exists('xml_parser_create')) {
				return array();
			}
			$parser = xml_parser_create();
			xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING,0);
			xml_parser_set_option( $parser, XML_OPTION_SKIP_WHITE,1);
			xml_parse_into_struct( $parser, $contents, $xml_values);
			xml_parser_free( $parser );
		
			if(!$xml_values) return;
			$xml_array = array();
			$parents = array();
			$opened_tags = array();
			$arr = array();
			$current = &$xml_array;
		
			foreach($xml_values as $data) {
				unset($attributes,$value);
				extract($data);
				$result = '';
				if($get_attributes) {
					$result = array();
					if(isset($value)) $result['value'] = $value;
		
					if(isset($attributes)) {
						foreach($attributes as $attr => $val) {
							if($get_attributes == 1) $result['attr'][$attr] = $val; 
						}
					}
				} elseif(isset($value)) {
					$result = $value;
				}
				if($type == "open") {
					$parent[$level-1] = &$current;
					if(!is_array($current) or (!in_array($tag, array_keys($current)))) {
						$current[$tag] = $result;
						$current = &$current[$tag];
		
					} else { 
						if(isset($current[$tag][0])) {
							array_push($current[$tag], $result);
						} else {
							$current[$tag] = array($current[$tag],$result);
						}
						$last = count($current[$tag]) - 1;
						$current = &$current[$tag][$last];
					}
				} elseif($type == "complete") { 
					if(!isset($current[$tag])) { 
						$current[$tag] = $result;
					} else { 
						if((is_array($current[$tag]) and $get_attributes == 0)
								or (isset($current[$tag][0]) and is_array($current[$tag][0]) and $get_attributes == 1)) {
							array_push($current[$tag],$result); 
						} else { 
							$current[$tag] = array($current[$tag],$result);
						}
					}
				} elseif($type == 'close') {
					$current = &$parent[$level-1];
				}
			}
			return($xml_array);
		} 
		
		private function idioma(){
			$resultado='';
			$campos = explode ('/',$_SERVER['REQUEST_URI']);
			if($_SERVER['SERVER_NAME']=="cw" || $_SERVER['SERVER_NAME']=="192.168.1.222"){
				if (count($campos) > 5) {
					$resultado=$campos[4];
				}
			}else{
				if (count($campos) > 2 && !strstr($_SERVER['REQUEST_URI'],'app')) {
					$resultado=$campos[1];
				}
			}
			return $resultado;
		}
	}
?>