<?php

// ** ++ COMPATIBILIDAD PHP 8.2 ++ ** //
require_once __DIR__ . '/compat_php82.php';

// ** ++ TODAS LAS FUNCIONES VAN A IR EN ESTE ARCHIVO ++ ** //
/*

INDICE: (AQUI ESCRITO LO MISMO QUE AL INICIO DE LA FUNC LOOK&FIND)

TODAS LAS $ DE POST Y DE GETA LOCALES

DEFINICION CONSTANTES ABECEDARIO MY Y MIN

// ** ++ DEFINICION TODOS LOS MESES DE NUMEROS A LETRAS ++ ** //

// ** ++ DEFINICION DE LOS CAMPOS FECHAS QUE TRATAMOS EN LAS TABLAS ++ ** //

// ** ++ DEFINICION DE LAS $ MES Y YEAR ACTUAL ++ ** //

ALERT PHP

JAVASCRIPT 4 PHP

IF_ISSET -> USAMOS POR SI EXISTE UNA VAR PINTARLA

CONSTANTES CONEXION BBDD
CLASE $DB + VAR $DB RETURN
VALIDAR FORM

XML2ARRAY



PINTAR_MODAL($nombre) crear forms modal XML->bbdd

pintar_tbody

pintar_card

JSON_ENCODE_ARR uso xara jquery UI catcomplete

JSON_ENCODE_BBDD -> CODIFICA UTF8 PARA BBDD

FORMATEAR_FECHA -> CAMBIA FECHA DE CUALQUIER A CUALQUIER MYSQL->LOCAL->XML.BEDS.SERHS

COMPARAR_FECHAS -> COMPARAR FECHAS DE ENTRADA Y SALIDA DE CUALQUIER MYSQL,LOCAL, ETC... 

SUMA_DIAS RESTA_DIAS -> SUMAN O RESTA DIAS

NOW -> SACA EL MOMENTO EN FORMATO DATETIME MYSQL

-- here --

FORMAT_NUMBER -> FORMATEA NUMEROS DE PROVEEDORES: MOSTRAR,OPERACIONES MATEMATICAS

TILDES_MIN_MAY -> HOTELES Y HABITACIONS: MIN A MAY , DE MAY A MIN RESPENTANDO TILDES

TILDES_TO_HTML -> pasamos todas las tildes a html,

sin_tildes

NUMFORMATOMYSQL -> FORMATEA NUMERO A MYSQL

ACCION_ADMIN (acciones por mi IP o localHost o usuarioPedro/tere)

GETPASS -> SACAMOS UN PASSWORD ALFANUMERICO

body_email -> MONTAMOS BODY LOS EMAILS (bienvenida,contraseña,booking, textos).

sumar_historico_xml -> SUMA HISTORICO A LOS MOVIMIENTOS DE UNA FICHA DE BOOKING.

limpiar_keys -> LIMPIA LAS KEYS DE _editar PARA PODER PASARLAS A UPDATES DE LOS REGISTROS

texto_estrellas -> para poner estrella a los tlfs y los emails

json_email -> LISTADO DE EMAILS SEPARADOS POR ; CONVERT JSON TO BBDD

email_json_decode -> JSON FROM BBDD TO LISTADO EMAILS SEPARADOS POR ; 

sort_by_orden -> ordenar arrays

bonos( -> suma o resta bonos

noty -> lanza un noty , es el sustituto amigable del alert

location -> lanza locations, 0 ambos, 1 opener, 2 solo

mueve_saldo -> mover el saldo de la empresa 

mueve_bonos_delegaciones -> mover los bonos de las delegaciones

array_orderby -> ordena arrays asociativos multudimensionales por las claves asociativas que se les pase, pongo un ejemplo en la misma funcion.

unique_multidim_array -> eliminamos las duplicidades de un array multudimensional, por el $key que le pasemos por parametro, pongo ejemplo en la misma funcion

strposa -> encuntra coincidencias de 1 string pasando 1 array y devuelve boleano

ordenar_por_fecha -> ordena por fecha en formato 2000-00-00

ConectarFTP -> conecta por ftp y devuleve la ruta asignada

datos_email -> obtiene del id cliente , todos los datos necesarios para body_email

registrar_accion -> registramos las acciones de los comerciales, desarrolladores, admon, etc...

insertar_comentarios -> en los comercios

filtro_url

instagram -> descarga datos de instagram

saber_dia_semana

nf( -> number formart desde BBD
*/

require_once(dirname(__FILE__).'/vendor/autoload.php');

// $EMAIL_ADMINISTRADOR='pedrogil@soltury.com';

$clean   = array("\r\n", "\n", "\r","\t","\0","\x0B");

// ** ++ TODAS LAS $ DE POST Y DE GETA LOCALES ++ ** //

if(isset($_POST)){
		foreach ($_POST as $key => $value) {
		    ${$key} = $value;
		}
}

if(isset($_GET)){
		foreach ($_GET as $key => $value) {
		    ${$key} = $value;
		}
}
$clean   = array("\r\n", "\n", "\r","\t","\0","\x0B");


// ** ++ DEFINICION CONSTANTES ABECEDARIO MY Y MIN ++ ** //

	for($i=65; $i<=90; $i++) {  
	    $letra_min = chr(abs($i+32)); 
	    define($letra_min , $letra_min);

	    $letra_may = chr($i); 
	    define($letra_may , $letra_may);
	}  


// ** ++ DEFINICION TODOS LOS MESES DE NUMEROS A LETRAS ++ ** //

$mes_datos=array("00"=>" ",
            "01"=>"Enero",
            "02"=>"Febrero",
            "03"=>"Marzo",
            "04"=>"Abril",
            "05"=>"Mayo",
            "06"=>"Junio",
            "07"=>"Julio",
            "08"=>"Agosto",
            "09"=> "Septiembre",
            "10"=>"Octubre",
            "11"=>"Noviembre",
            "12"=>"Diciembre");

// ** ++ DEFINICION DE LOS CAMPOS FECHAS QUE TRATAMOS EN LAS TABLAS ++ ** //


$arr_campos_fechas = array(
                'periodo',
                'entrada',
                'salida',
                'fecha_pago_cliente',
                'fecha',
                'fecha_cobro',
                'fecha_pago',
                'fecha_de_pago',
                'start',
                'fecha_fin',
                'fecha_nacimiento',
                'fecha_santo',
                'fecha_aniversario');

// ** ++ DEFINICION DE LAS $ MES Y YEAR ACTUAL ++ ** //

$mes = !isset($mes) ? date('m') : $mes;
$year = !isset($year) ? date('Y') : $year;



// ** ++ ALERT PHP ++ ** //

	function alert($mensaje){

		echo "<script>alert('".$mensaje."')</script>";

		// eof alert()
	}


// ** ++ JAVASCRIPT 4 PHP ++ ** //
	function script($script){

		echo "<script>".$script."</script>";

	}

// ** ++ IF_ISSET VAR ++ ** //

            function if_isset($txt=0){

                if(isset($txt)){
                    echo $txt;
                }

            }

// ** ++ CONSTANTES CONEXION BBDD ++ ** //
// Si existe functions/config.local.php (ignorado por git), lo usa directamente.
// Si no, detecta el entorno automáticamente.
$_config_local = __DIR__ . '/config.local.php';
if (file_exists($_config_local)) {
    require_once $_config_local;   // define DB_HOST, DB_USER, DB_PASS, DB_DB
} else {
    $_is_local = (
        isset($_SERVER['REMOTE_ADDR']) && in_array($_SERVER['REMOTE_ADDR'], ['::1', '127.0.0.1']) ||
        php_uname('n') === 'localhost' ||
        (isset($_SERVER['HTTP_HOST']) && in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1']))
    );

    if ($_is_local) {
        // ---- LOCALHOST / DESARROLLO ----
        define('DB_HOST', 'localhost');
        define('DB_USER', 'root');
        define('DB_PASS', '');
        define('DB_DB',   'dulceria');
    } else {
        // ---- PRODUCCION ----
        define('DB_HOST', 'localhost');
        define('DB_USER', 'c199_largentina');
        define('DB_PASS', '***');        // <-- contraseña real de producción
        define('DB_DB',   'c199_largentina');
    }
}

  
  // ** ++ CLASE $DB + VAR $DB RETURN ++ ** //
class DataAccess {

// ** ++ DATOS DE CONEXION A BBDD ++ ** //
    var $host=DB_HOST;
    var $user=DB_USER;
    var $password=DB_PASS;
    var $dataBase=DB_DB;
// ** ++ DATOS DE CONEXION A BBDD ++ ** //


    var $link; // aqui llevamos la conexion a la BBDD
    var $query; // aqui llevamos la query
    var $errorQuery; // aqui metemos los errores de las query.
    var $return; // La vamos a usar para poder retornar resultados de alguna funcion.
    var $stmt; // ira el stament que es el resultado del prepare
    var $last_id; // ultimo id al hacer insert o update.
    var $sql; // ira la sql que voy a pasar a cualquier metodo
    var $arr_val; // iran un array con todos los campos que vamos a meter en una sql
    var $id_update; // ira el id del registro que queramos hacer update

    function __construct(){

    $this->connect();
  

    }


    private function connect(){

        //CONECTAMOS A LA BBDD
        $this->link=mysqli_connect($this->host,$this->user,$this->password,$this->dataBase);

        $this->link->set_charset("latin1");

        // SI EXISTE UN ERROR LO LANZAMOS.
        if (mysqli_connect_errno()) {

                printf("Conexión fallida: %s\n", mysqli_connect_error());
   
        }//else{ /**SOLO DESCOMENTAR PARA PROBAR SI ES OK**

                //echo "Conexion correcta";
    //   }

    //end connect
    }


    public function query($sql){

        $this->query=mysqli_query($this->link,$sql);
        $this->errorQuery=mysqli_error($this->link);

        $debug_ips = ['5.154.69.122','::1','185.178.136.16','127.0.0.1'];
        if(in_array($_SERVER['REMOTE_ADDR'], $debug_ips)){

            if($this->errorQuery!=''){
                echo "<br><b style='color:red'>Error MySQL:</b> " . $this->errorQuery;
                echo "<br><b>Query:</b> " . $sql;
            }

        }


        $this->last_id=mysqli_insert_id($this->link);

        return $this->last_id;
        
    }


    public function select($select,$from,$where='id>0',$return='a'){


        //SELECT NORMAL. 
                // VALORES PARA $return:  a=ambos[POR DEFECTO]
                // f=fetch() SI ES MAS DE 1 $ejemplo[0]['ejemplo']
                // r=rows() EL NUMERO DE REGISTROS DEVUELTOS
                // a= AMBOS ANTERIORES.
                // e= echo query.
        $this->query('select '.$select.' from '.$from.' where '.$where);

        if($return=="e"){
            echo '<br> select '.$select.' from '.$from.' where '.$where;
        }


        if($return=="f"){


            $while=$this->rows();


            if($while>0){

                $x=0;
                while($returnWhile=$this->fetch(MYSQLI_ASSOC)){

                $this->return[f][$x]=$returnWhile;
                $x++;

                }


            }


        }else if($return=="r"){

                $this->return[r]=$this->rows();

        }else if($return=="a"){


            $this->return[r]=$this->rows();

            if($this->return[r]>0){

                $x=0;
                while($returnWhile=$this->fetch()){
                $this->return[f][$x]=$returnWhile;
                $x++;

                }
            }


        }

        return $this->return;

    
    }// END SELECT

    public function inner_join($select,$from,$on,$return='a'){


        //SELECT NORMAL. 
        // VALORES PARA $return:  a=ambos[POR DEFECTO]
        // f=fetch() SI ES MAS DE 1 $ejemplo[0]['ejemplo']
        // r=rows() EL NUMERO DE REGISTROS DEVUELTOS
        // a= AMBOS ANTERIORES.
        // e= echo query.
        $this->query('select '.$select.' from '.$from.' on '.$on);

        if($return=="e"){
            echo '<br> select '.$select.' from '.$from.' on '.$on;
        }


        if($return=="f"){


            $while=$this->rows();


            if($while>0){

                $x=0;
                while($returnWhile=$this->fetch(MYSQLI_ASSOC)){

                $this->return[f][$x]=$returnWhile;
                $x++;

                }


            }


        }else if($return=="r"){

                $this->return[r]=$this->rows();

        }else if($return=="a"){


            $this->return[r]=$this->rows();

            if($this->return[r]>0){

                $x=0;
                while($returnWhile=$this->fetch(MYSQLI_ASSOC)){
                $this->return[f][$x]=$returnWhile;
                $x++;

                }
            }


        }

        return $this->return;

 // END inner_join
    }



    public function fetch($type=MYSQLI_ASSOC) {
    
            if (isset($this->query) && $this->query!='') {

                if ($row=mysqli_fetch_array($this->query,$type)) {
                    return $row;
                }

            }/*else if($this->query==''){

                    $this->alert('No puedes usar esto sin realizar antes una consulta');
                    
                }*/  

// END FETCH
    }


        public function rows(){

                if (isset($this->query) && $this->query!='') {

                    if ($row=mysqli_num_rows($this->query)) {
                    return $row;
                    }

                }

//END ROWS
        }


        public function delete($from,$valor,$where='id',$type=i){
            //+++++++++++++++++++++++//
            //++ LOS VALORES ADMITIDOS EN $type:
            //++ i = integer (por defecto);
            //++ v = varchar 

            //********
            // podriamos saber el tipo de datos que vamos a borrar y eliminar 
            // la posibilidad de error al borrar.

            if($type==i){
            $this->query("delete from ".$from." where ".$where." = " . $valor);
            }else if($type==v){
            $this->query("delete from ".$from." where ".$where." like " . $valor);   
            }


        }


        public function openTable($tablename) {

            $this->tablename = $tablename;
            $this->ta = array();
            $this->ta['primary'] = array();
            $this->ta['auto_increment'] = array();

            $this->query("SHOW COLUMNS FROM $tablename");
            
            while ($row = $this->fetch()) {
                $this->ta[$row['Field']] = $row['Default'];
                if ($row['Key'] == "PRI") {
                    $this->ta['primary'][] = $row['Field'];
                }
                if ($row['Extra'] === "auto_increment") {
                    $this->ta['auto_increment'][] = $row['Field'];
                }
            }
        }


        public  function insert($e=0) {

            $sqlcampos = "";
            $sqlvalores = "";
            
            foreach ($this->ta AS $key=>$value) {

                if ($key !== 'primary' && $key !== 'auto_increment') {

                    if (!in_array($key, $this->ta['auto_increment']) && !is_int($key) && $value != "[{empty}]") {

                        if (!empty($sqlcampos)){

                            $sqlcampos.=", ";
                        }

                        if (!empty($sqlvalores)){

                            $sqlvalores.=", ";

                        }

                        $sqlcampos.=$key;
                        $sqlvalores.="'$value'";
                    }

                } // if ($key !== 'primary' && $key !== 'auto_increment') {

            } // foreach ($this->ta AS $key=>$value) {

            $sql = sprintf("INSERT INTO %s (%s) VALUES (%s)",
                            $this->tablename, $sqlcampos, utf8_decode($sqlvalores));
            if($e==1){
             echo $sql;               
            }

            $this->query($sql);
            
            if (!empty($this->error)) {

                return false;

            }else{

                $this->last_id=mysqli_insert_id($this->link);
                return $this->last_id;
            }
        }

        public function bindParameters(&$statement, &$params) {

            $args   = array();
            $args[] = implode('', array_values($params));


            foreach ($params as $paramName => $paramType) {
                
                $args[] = &$params[$paramName];
                $params[$paramName] = null;
                
                
            }

            //echo "<br>".var_dump($args);

            if(call_user_func_array(array(&$statement, 'bind_param'), $args)){

                
                $this->stmt = $statement;
                return $this->stmt;

            }else{

                if(isset($_SESSION['ip_admin'])){

                    echo "<br> Fallo el bindParameters()";
                }
                return false;

            }
        }

        public function sql_insert ($table) {

            $this->tablename = $table;
            $keys = array_keys($this->ta);
            $vals = array_values($this->ta);
            $typeDef = array();

            $key = '';
            $val = '';
            $type = '';

            foreach ($this->ta AS $k=>$value) {
                        
                if($key == '') {

                    $key = $k;
                    $val = '?';

                } else {

                    $key .= ', '.$k;
                    $val .= ', ?';
                            
                }

                $typeDef[]= is_int($value)==true ? i : s;
                        
            } // foreach ($this->ta AS $key=>$value) {

            if (!$this->stmt = $this->link->prepare("INSERT INTO {$this->tablename} ($key) values ($val)")) {

                if(isset($_SESSION['ip_admin'])){

                    // aqui el error lo contiene $this->link
                    // por que $this->stmt ha fallado y es undefined
                    echo "Prepare Statement Error ".$this->link->error;
                    return false;
                }

            } else { // preparacion correcta


                for($i = 0; $i < count($typeDef); $i++) {
                    /* Set params value for binding */
                    $params[$keys[$i]] = $typeDef[$i];
                }

                /* Bind our params */
                $this->bindParameters($this->stmt, $params);

                for($i = 0; $i < count($typeDef); $i++) {
                    /* Set params for query */
                    $params[$keys[$i]] = $vals[$i];
                }

                /* Execute the prepared Statement */
                if(!$this->stmt->execute() && isset($_SESSION['ip_admin'])) { 

                    // aqui ya si contiene el error $this->stmt
                    printf("Execute Statement Error: %s\n", $this->stmt->error); 
                    exit; 

                }

                /* Close the statement */
                $this->stmt->close(); 
                $this->last_id=mysqli_insert_id($this->link);
                return $this->last_id;

            } // } else { // preparacion correcta

        } // public function sql_insert ($table) {

        public function sql_update($table){

            /** LA FUNCION DE VINCULACION QUE QUIERO, LA PUEDO SACAR DESDE AQUI**/
            $this->tablename = $table;
            $keys = array_keys($this->ta);
            $vals = array_values($this->ta);
            $typeDef = array();

            $key = '';
            $val = '';

            foreach ($this->ta AS $k=>$value) {
                        
                if($key == '') {

                    $key = $k.'=?';
                    
                } else {

                    $key .= ', '.$k.'=?';
                            
                }

                $typeDef[]= is_int($value)==true ? i : s;
                        
            } // foreach ($this->ta AS $key=>$value) {

            //echo "<br>update {$this->tablename} set $key where id=?";
            if (!$this->stmt = $this->link->prepare("update {$this->tablename} set $key where id=?")) {

                if(isset($_SESSION['ip_admin'])){

                    // aqui el error lo contiene $this->link
                    // por que $this->stmt ha fallado y es undefined
                    echo "Prepare Statement Error ".$this->link->error;
                    return false;
                }

            }

            for($i = 0; $i < count($typeDef); $i++) {
                /* Set params value for binding */
                $params[$keys[$i]] = $typeDef[$i];

            }

            $params['id'] = i;
            /* Bind our params */
            if(!$this->bindParameters($this->stmt, $params)){

                return false;

            }

            for($i = 0; $i < count($typeDef); $i++) {
                /* Set params for query */
                $params[$keys[$i]] = $vals[$i];

            }

            $params['id'] = $this->id_update;

            if(!$this->stmt->execute()){

                $this->stmt->close(); 
                return false;

            }else{

                $this->stmt->close(); 
                return true;
            }

        } // public function sql_update(){

        public function bucle_update($table,$arr_consultas){

            /** LA FUNCION DE VINCULACION QUE QUIERO, LA PUEDO SACAR DESDE AQUI**/
            $this->tablename = $table;
            $keys = array_keys($arr_consultas[0]);
            $vals = array_values($arr_consultas[0]);
            $typeDef = array();

            $key = '';
            $val = '';

            foreach ($arr_consultas[0] AS $k=>$value) {
                       
                if($k!='id'){ 

                    if($key == '') {

                        $key = $k.'=?';
                        
                    } else {

                        $key .= ', '.$k.'=?';
                                
                    }

                }
                //echo "<br>".$k."  ".$value;
                $typeDef[]= is_int($value)==true ? i : s;
                        
            } // foreach ($this->ta AS $key=>$value) {

            // echo "<br>update {$this->tablename} set $key where id=?";
            if (!$this->stmt = $this->link->prepare("update {$this->tablename} set $key where id=?")) {

                if(isset($_SESSION['ip_admin'])){

                    // aqui el error lo contiene $this->link
                    // por que $this->stmt ha fallado y es undefined
                    echo "Prepare Statement Error ".$this->link->error;
                    return false;
                }

            }

            for($i = 0; $i < count($typeDef); $i++) {
                /* Set params value for binding */
                $params[$keys[$i]] = $typeDef[$i];
                //echo "<br>".$params[$keys[$i]];
            }

            $params['id'] = i;
            /* Bind our params */
            if(!$this->stmt=$this->bindParameters($this->stmt, $params)){

                if(isset($_SESSION['ip_admin'])){

                    echo "Bind Error ".$this->link->error;
                    return false;

                }
            }

            $n_querys = count($arr_consultas);
        
            for($q = 0;$q < $n_querys; $q++){

                foreach ($arr_consultas[$q] as $key => $value) {
                    
                    if(!isset($info[$q])){

                        $info[$q] = $value;

                    }else{

                        $info[$q] .=",".$value; 
                    }

                    $params[$key] = $value;
                }

                $this->stmt->execute();

            } // for($q = 0;$q < $n_querys; $q++){
        
        } // public function sql_update(){

        public function bucle_querys($table,$arr_consultas,$types){

            ################################
            #
            # $types puede ser
            #       # u : update 
            #       # i : insert 
            #
            # 

            $this->tablename = $table;
            $keys = array_keys($arr_consultas[0]);
            $vals = array_values($arr_consultas[0]);
            $typeDef = array();

            $key = '';
            $val = '';

            foreach ($arr_consultas[0] AS $k=>$value) {
                       

                if($types == u){ # 4 updates

                    if($k!='id'){ 

                        if($key == '') {

                            $key = $k.'=?';
                            
                        } else {

                            $key .= ', '.$k.'=?';
                                    
                        }
                    }

                }else if($types == i){ # 4 insert

                    if($key == '') {

                        $key = $k;
                        $val = '?';

                    } else {

                        $key .= ', '.$k;
                        $val .= ', ?';
                                
                    }
                }

                //echo "<br>".$k."  ".$value;
                $typeDef[]= is_int($value)==true ? i : s;
                        
            } // foreach ($this->ta AS $key=>$value) {

            ## preparamos la consulta
            if($types == u) {

                $q = "update {$this->tablename} set $key where id=?";

            }else if($types == i){

                $q = "INSERT INTO {$this->tablename} ($key) values ($val)";

            }



             //echo "<br>$q";
            if (!$this->stmt = $this->link->prepare($q)) {

                if(isset($_SESSION['ip_admin'])){

                    // aqui el error lo contiene $this->link
                    // por que $this->stmt ha fallado y es undefined
                    echo "Prepare Statement Error ".$this->link->error;
                    return false;
                }

            }else{
                //echo "prepare ok";
            }

            for($i = 0; $i < count($typeDef); $i++) {
                /* Set params value for binding */
                $params[$keys[$i]] = $typeDef[$i];
                //echo "<br>".$params[$keys[$i]];
            }

            if($types == u){

                $params['id'] = i;
            }
            
            /* Bind our params */
            if(!$this->stmt=$this->bindParameters($this->stmt, $params)){

                if(isset($_SESSION['ip_admin'])){

                    echo "Bind Error ".$this->link->error;
                    return false;

                }
            }else{
                //echo "bind ok";
            }

            $n_querys = count($arr_consultas);
            echo "<br>vamos a lanzar ".$n_querys;
        
            for($q = 0;$q < $n_querys; $q++){

                foreach ($arr_consultas[$q] as $key => $value) {
                    
                    if(!isset($info[$q])){

                        $info[$q] = $value;

                    }else{

                        $info[$q] .=",".$value; 
                    }

                    $params[$key] = $value;
                }

                echo "<br> Con la info ". $info[$q];
                $this->stmt->execute();

            } // for($q = 0;$q < $n_querys; $q++){
        
        } // public function bucle_querys(){

        public function preparar($sql,$tipos,$parametros){

            if(!($return = $this->link->prepare($sql))) {

                // echo "<br>Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;

            }else{
                
                // echo "<br>La preparacion fue correcta";

                if(!$return->bind_param($tipos, $parametros)) {

                    // echo "<br>Falló la vinculación de parámetros: (" . $return->errno . ") " . $return->error;

                }else{

                    // echo "<br>La vinculacion fue correcta";
                }

                return $return;

            }

        } // public function preparar($sql){


        public function textCheck($value_) {
            /*
            Prepara una cadena para poder insertarla 
            en la base de datos de forma segura.
            */
    
            if ($value_ != NULL) {
                // get_magic_quotes_gpc() eliminada en PHP 8.0; ya no es necesario stripslashes
                // Colocar comillas si no es un entero
                if (!is_integer($value_)) {
                    $value_ = "" . mysqli_real_escape_string($this->link,$value_) . "";
                }
            }
            return $value_;
        }


        public function edit($id) {

            $sql = sprintf("SELECT * FROM %s WHERE id = %d",
            $this->tablename, $id);
            //echo $sql;
            $this->query($sql);

            if (!empty($this->error))
                return false;

            $aux = $this->fetch();
            if ($aux === false) return false;
            foreach ($aux as $key=>$value) {
                $this->ta[$key] = $this->textCheck($aux[$key]);
            }
            return true;
        }


        public function update($echo=0) {

            $valores = "";
            $condicion = "";
            
            foreach ($this->ta AS $key=>$value) {
                if ($key !== 'primary' && $key !== 'auto_increment') {
                    if (!in_array($key, $this->ta['primary']) && !is_int($key) && $value != "[{empty}]") {
                        if (!empty($valores))
                            $valores.=", ";
                        $valores.=$key." = '$value'";
                    }
                }
                elseif ($key === 'primary') {
                    foreach ($this->ta[$key] AS $campopri) {
                        if (!empty($condicion))
                            $condicion.=" AND ";
                        $condicion.=$campopri." = '".$this->ta[$campopri]."'";
                    }
                }
            }
           
            $sql = sprintf("UPDATE %s SET %s WHERE %s",
            $this->tablename, $valores, $condicion);
            if($echo==1)echo $sql;
            $this->query($sql);
            
            if (!empty($this->error)) {

                return false;
            } else {
                return mysqli_affected_rows($this->link);
            }
        }

        public function limpiar_editar($var,$onlyKey=1,$explode="_edit",$separador=","){

            // $var generalmente debe ser un array
            // la vamos a utilizar en los casos de que
            // tengamos que coger los datos de un registro
            // xra su posterior modificacion.

            // $onlyKey = 0 la vamos a utilizar xra TB
            // retornar una cadena de $values preparada para insertar

            // AL INTRODUCIR EL CAMPO DE BUSCADOR. 

            $b=0;
            foreach ($var as $key => $value) {

                $buscador=stripos($key,"buscador");
                if($buscador!==false){

                        $b++;
                }
            }


            $pre_n_campos=count($var);
            $n_campos=$pre_n_campos-$b;
            $x=1;
            //alert('key');
            // queremos introducir en $select , todas las $key
            // quitando los valores 
            $return=array('key'=>"" , 'value'=>"");
                foreach ($var as $key => $value) {

                    $buscador=stripos($key,"buscador");

                    if($buscador===false){
                        $buscador=stripos($key,"_noeditar"); 
                    }

                    if($buscador===false){

                   // cada uno de los campos y quitarle _editar
                    if($key!="accion" &&  $key!="tabla_editar" && $key!="id_editar" && $key!="texto_editar"){


                        if($x<$n_campos){

                            // ponemos en ambos casos el if de password
                            // por que en todas las tablas donde lo almacenemos
                            // vamos a poner un equivalente llamado comentario.
                            if($key=="password_editar"){

                                $return['key'].="comentario$separador";

                            }else{

                                $key=explode($explode,$key);
                                $key=$key[0];
                                $return['key'].=$key."$separador";

                            }

                            if($onlyKey==0){

                                $return['value'].=$value."$separador";
                            }


                        }else if($x==$n_campos){

                            if($key=="password_editar"){

                                $return['key'].="comentario";

                            }else{

                                $key=explode($explode,$key);
                                $key=$key[0];
                                $return['key'].=$key;
                            }

                            if($onlyKey==0){
                                $return['value'].=$value;
                            }
                        }

                        $x++;
                   // eof  if($key!="accion" &&  $key!="tabla_editar" && $key!="id_editar"){
                    }else{ 
                        // si es alguno de los indices que no queremos
                        // subimos una posicion en $x para acceder al 
                        // próximo campo en la siguientes iteraccion.
                        $x++;
                    }

                    // eof if($buscador===false){
                    }

            // eof     foreach ($_POST as $key => $value) {
                }

                    // echo $return['value'];
                    // echo $return['key'];
                    return $return;

        // EOF  public function limpiar_editar($var,$onlyKey=1,$explode="_edit"){
        }

        

//END DataAcess
    }


    $DB = new DataAccess();
    $DB2 = new DataAccess();
    $Q = new DataAccess();


// ** ++ VALIDAR FORM ++ ** //
    function validar_form($POST){
    //TENDRIAMOS QUE METER LA VALIDACION DEL FORMATO DEL EMAIL.
    //LOS nº DE TELEFONO TB.              
                    $formulario_validado=0;

                    foreach($POST as $key => $value){

                              if($value==''){

                      alert('El Campo '.$key.' no puede estar vacio');
                              
                              $formNoOk=0;
                            
                              break;

                              }
                      }
                    if(!isset($formNoOk)){
  
                        $formulario_validado=1;

                      }
                        return $formulario_validado;
//eof validar_form
}


// ** ++ XML2ARRAY ++ ** //
function xml2array($contents, $get_attributes=1) {

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
            // EOF xml2array
        } 

function xmlTOarray($xml){

    $xml=new simpleXMLelement($xml);
    $xml = json_encode($xml);
    $return = json_decode($xml,TRUE);
    return $return;
}


// ** ++  ++ ** //
function pintar_form($nombre,$accion,$datos,$tabla=""){

    global $DB;
    $editar = $accion==u ? "_editar" : "";

    if($datos == ''){

        $datos_modal=$DB->select("json","formulario_modal_json","nombre like '".$nombre."'");
        $datos = json_decode($datos_modal[f][0]['json'],true, 512, JSON_UNESCAPED_UNICODE);


    }
    
    echo '<form class="form-horizontal" 
                id="'.$nombre.$editar.'_form" 
                method="post" 
                action="#">
                        <div class="form-group">';
    if($accion==d){
        // AQUI VENIMOS A BORRAR EL REGISTRO.
        echo '<div class="col-md-9">
                ¿SEGURO QUE QUIERES BORRAR EL REGISTRO SELECCIONADO?
                <input type="hidden" id="id_eliminar" name="id_eliminar" value="">
                <input type="hidden" id="tabla_eliminar" name="tabla_eliminar" value="'.$tabla.'">
            </div>';


    }else if($accion==i || $accion==u){

            // AQUI VENIMOS A INSERTAR / UPDATE
        echo '<input type="hidden"
         id="accion" 
         name="accion" 
         value="'.$accion.'">';

        // marcamos i=insert / u=update 
            // esta accion va a pasar ajax para otro if.
            if($accion==i){
                echo '<input type="hidden" id="tabla_insertar" name="tabla_insertar" value="'.$tabla.'">';

            }else if($accion==u){
                echo '<input type="hidden" id="tabla_editar" name="tabla_editar" value="'.$tabla.'">
                    <input type="hidden" id="id_editar"  name="id_editar"  value="">';
            }

            $n_campos=count($datos['bodyForm']['campo']);
            $d_c=$datos['bodyForm']['campo'];

            for($x=0;$x<$n_campos;$x++){

                $d_c[$x]['val'] = !isset($d_c[$x]['val']) ? "" : $d_c[$x]['val'];
                
                if($d_c[$x]['types']=="hidden"){

                            echo '<input type="hidden" 
                            name="'.$d_c[$x]['names'].$editar.'"
                            id="'.$d_c[$x]['names'].$editar.'"
                            value="'.$d_c[$x]['val'].'"
                            verify="'.$d_c[$x]['verifys'].'">';



                }else if($d_c[$x]['types']!="hidden"){  

                $obl = $d_c[$x]['verifys'] == 1 ? ' <span class="text-danger">*</span>' : '';
                echo '<div class="col-xs-6 has-info">
                <br>
                <label class="tiempoJuego">'.$d_c[$x]['placeholders'].$obl.':</label>';
                    

                    if($d_c[$x]['types']=="text"){


                            echo '<input type="text" 
                            class="form-control" 
                            name="'.$d_c[$x]['names'].$editar.'"
                            id="'.$d_c[$x]['names'].$editar.'" 
                            placeholder="'.trim($d_c[$x]['placeholders']).'"
                            value="'.trim($d_c[$x]['val']).'"
                            verify="'.$d_c[$x]['verifys'].'">';


                           
                    // oef $d_c[$x]['types']=="text"
                    }else if($d_c[$x]['types']=="password"){

                    echo '<input type="password" 
                            class="form-control" 
                            name="'.$d_c[$x]['names'].$editar.'"
                            id="'.$d_c[$x]['names'].$editar.'" 
                            value="'.$d_c[$x]['val'].'"
                            verify="'.$d_c[$x]['verifys'].'">';

                    // $d_c[$x]['types']=="password"               
                    }else if($d_c[$x]['types']=="select"){

            // ANTES DE ESTE TENEMOS QUE MIRAR SI ES POR Nº O ES POR MESES.
                            if($d_c[$x]['mes']==1){

                                echo '<br><select verify="'.$d_c[$x]['verifys'].'"
                                name="'.$d_c[$x]['names'].$editar.'" 
                                id="'.$d_c[$x]['names'].$editar.'" 
                                class="custom-select">
                                        <option value="1">Enero</option>
                                        <option value="2">Febrero</option>
                                        <option value="3">Marzo</option>
                                        <option value="4">Abril</option>
                                        <option value="5">Mayo</option>
                                        <option value="6">Junio</option>
                                        <option value="7">Julio</option>
                                        <option value="8">Agosto</option>
                                        <option value="9">Septiembre</option>
                                        <option value="10">Octubre</option>
                                        <option value="11">Noviembre</option>
                                        <option value="12">Diciembre</option>
                                        </select>';


                            }else if($d_c[$x]['numeros']==1){

                                echo '<br><select verify="'.$d_c[$x]['verifys'].'"
                                name="'.$d_c[$x]['names'].$editar.'" 
                                id="'.$d_c[$x]['names'].$editar.'" 
                                class="custom-select">';


                                for($ai=$d_c[$x]['comienzo'];$ai<=$d_c[$x]['fin'];$ai++){

                                    echo '<option value="'.trim($ai).'">'.trim($ai).'</option>';

                                }

                                echo '</select>';


                            }else if($d_c[$x]['boleano']=="1"){

                                echo '<br><select verify="'.$d_c[$x]['verifys'].'"
                                name="'.$d_c[$x]['names'].$editar.'" 
                                id="'.$d_c[$x]['names'].$editar.'" 
                                class="custom-select select_boleano">
                                        <option value="1">On</option>
                                        <option value="0">Off</option>
                                        </select>';

                            // eof $d_c[$x]['boleano']=="1"
                            }else if($d_c[$x]['boleano']=="0"){

                           
                                echo '<br><select verify="'.$d_c[$x]['verifys'].'"
                                name="'.$d_c[$x]['names'].$editar.'" 
                                id="'.$d_c[$x]['names'].$editar.'" 
                                class="custom-select">';

                                // AQUI METEMOS CAMPOS EXTRA PRINCIPAL
                                // SI ISSET
                            if($d_c[$x]['extra']=="1"){
                                    
                                echo '<option value="'.
                                $d_c[$x]['extra_val'].'">'.
                                $d_c[$x]['extra_text'].'
                                </option>';
                                // eof $d_c[$x]['extra']['attr']['activo']=="1"
                            }

                            $val[$x]=$d_c[$x]['select_val'];
                            $text[$x]=$d_c[$x]['select_text'];

                            $select[$x]=$val[$x].",".$text[$x];


                            $from[$x]=$d_c[$x]['tabla'];

                            if(isset($d_c[$x]['where'])){

                                $where[$x]=$d_c[$x]['where'];

                            }else if(!isset($d_c[$x]['where']) || $d_c[$x]['where']==""){

                                $where[$x]="id>0";
                            }

                            if($select[$x]!="" && $from[$x]!=""){  

                                    $options[$x]=$DB->select($select[$x],$from[$x],$where[$x]);
                                    if($options[$x][r]>0){

                                        for($p=0;$p<$options[$x][r];$p++){
                 
                                        echo '<option value="'.
                                        $options[$x][f][$p][$val[$x]].'">'.
                                        utf8_encode($options[$x][f][$p][$text[$x]]).'
                                        </option>';

                                    }

                                } // // eof $options[$x][r]>0

                            } // if($select[$x]!="" && $from[$x]!=""){ 

                            echo '</select>';
                            
                        } // eof $d_c[$x]['boleano']=="0"
                     
                    }else if($d_c[$x]['types']=="textArea"){ // eof $d_c[$x]['types']=="select"

                        echo '
                        <textarea
                        class="form-control"
                        rows="'.$d_c[$x]['rows'].'"
                        cols="'.$d_c[$x]['cols'].'"
                        name="'.$d_c[$x]['names'].$editar.'"
                        id="'.$d_c[$x]['names'].$editar.'"
                         >'.$d_c[$x]['val'].'</textarea>
                       ';

                    } // eof $d_c[$x]['types']=="textarea"

                } // // eof if($d_c[$x]['types']!="hidden")
                echo '</form>
                </div>';
        
        }// eof for($x=0;$x<$n_campos;$x++){

    } // else if($accion==i || $accion==u){

} // function pintar_form($nombre,$accion,$json,$tabla=""){

// ** ++ PINTAR_MODAL ++ ** //
function pintar_modal($nombre,$accion,$tabla=""){

    // +++ DESCRIPCION DE LAS ACCIONES +++
    // i = insert
    // d = delete
    // u = update
    //
    global $DB;

$datos_modal=$DB->select("xml","formulario_modal","nombre like '".$nombre."'");

if($datos_modal[r]==0){

            echo "No hay ningun formulario con este nombre limeti";

        }else if($datos_modal[r]>1){

    
            echo "Le has puesto a 2 formularios el mismo nombre limeti";

}else if($datos_modal[r]==1){

//echo "<br><br>".$datos_modal[f][0]['xml'];
$datos=new SimpleXMLElement($datos_modal[f][0]['xml']);
//$datos=html_entity_decode($datos);

//echo "por aqui estamos pasando" . $datos;

// con esto vamos a añadir alos ID de todos los campos
// del from para poderlos rellenar de datos.
$editar="";
if($accion==u){
    $editar="_editar";
}

echo '<div class="modal fade" 
        id="'.$nombre.$editar.'" 
        tabindex="-1" 
        role="dialog" 
        aria-labelledby="myModalLabel" 
        aria-hidden="true">

        <div class="modal-dialog">
            <div class="modal-content">   
                <div class="modal-header">
                    <h3 class="modal-title" id="myModalLabel">
                        '.$datos->datos->title.'
                    </h3>
                    <button type="button" 
                        class="close" 
                        data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">Cerrar</span>
                    </button>         
                </div>
                <div id="" class="modal-body">

                <!-- modal-body (aqui es donde va el form) -->
                <form class="form-horizontal" 
                        id="'.$nombre.$editar.'_form" 
                        method="post" 
                        action="#">';

                        // estas $ son especiales
                        // pàra los forms de crear y modificar
                    if($nombre=='formularios' || $nombre=='editar_formularios'){
                       echo '<input type="hidden" 
                        name="formularios" 
                        value="1" verify="1">';
                        
                        if($nombre=='formularios'){
                            echo '<input type="hidden" 
                            name="xml_campos_formulario" 
                            value="" verify="1">';
                        }
                    }

                    echo '<div class="form-group">

                        <!-- DENTRO DE  class="form-group" 
                        VIENE TODO EL CONTENIDO DEL FORM.

                        DIVIDIDO EN DIVS CON class="col-md-9" -->';
if($accion==d){
    // AQUI VENIMOS A BORRAR EL REGISTRO.
echo    '<div class="col-md-9">'.
        $datos->bodyForm->texto
        .'
        <input type="hidden"
         id="id_eliminar" 
         name="id_eliminar" 
         value="">

        <input type="hidden"
        id="tabla_eliminar"
        name="tabla_eliminar"
        value="'.$tabla.'">
        </div>';


}else if($accion==i || $accion==u){

    // AQUI VENIMOS A INSERTAR / UPDATE
        echo '<input type="hidden"
         
         name="accion" 
         value="'.$accion.'">';

        // marcamos i=insert / u=update 
            // esta accion va a pasar ajax para otro if.
            if($accion==i){
                echo '<input type="hidden"
                    id="tabla_insertar"
                    name="tabla_insertar"
                    value="'.$tabla.'">';

            }else if($accion==u){
                echo '<input type="hidden"
                    id="tabla_editar"
                    name="tabla_editar"
                    value="'.$tabla.'">

                    <input type="hidden"
                    id="id_editar" 
                    name="id_editar" 
                    value="">';


            }

    $n_campos=$datos->bodyForm->campo->count();
    $datos_campos=$datos->bodyForm->campo;

    for($x=0;$x<$n_campos;$x++){

        // $ universales

        $name[$x]=$datos_campos[$x]['name'];
        $type[$x]=$datos_campos[$x]['type'];
        $verify[$x]=$datos_campos[$x]['verify'];
        $value[$x]=$datos_campos[$x]->value;



    if($type[$x]=="hidden"){

                echo '<input type="hidden" 
                name="'.$name[$x].$editar.'"
                id="'.$name[$x].$editar.'"
                value="'.$value[$x].'"
                verify="'.$verify[$x].'">';



    }else if($type[$x]!="hidden"){  

        $placeholder[$x]=$datos_campos[$x]->placeholder;


        echo '<div class="col-xs-6">
        <br>
        <label class="tiempoJuego">'.$placeholder[$x].':</label>';
            

        if($type[$x]=="text"){


                echo '<input type="text" 
                class="form-control" 
                name="'.$name[$x].$editar.'"
                id="'.$name[$x].$editar.'" 
                placeholder="'.trim($placeholder[$x]).'"
                value="'.trim($value[$x]).'"
                verify="'.$verify[$x].'">';


               
        // oef $type[$x]=="text"
        }else if($type[$x]=="password"){

            echo '<input type="password" 
                class="form-control" 
                name="'.$name[$x].$editar.'"
                id="'.$name[$x].$editar.'" 
                value="'.$value[$x].'"
                verify="'.$verify[$x].'">';

        // $type[$x]=="password"               
        }else if($type[$x]=="select"){

                $boleano[$x]=$datos_campos[$x]['boleano'];
                $mes[$x]=$datos_campos[$x]->mes;
                $numeros[$x]=$datos_campos[$x]->numeros['activo'];
             
// ANTES DE ESTE TENEMOS QUE MIRAR SI ES POR Nº O ES POR MESES.
                if($mes[$x]==1){

                    echo '<br><select 
                    name="'.$name[$x].$editar.'" 
                    id="'.$name[$x].$editar.'" 
                    class="form-control class="custom-select form-control" style="width: 100%;" data-style="" data-live-search="true"">
                            <option value="1">Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>
                            </select>';


                }else if($numeros[$x]==1){


                $comienzo=$datos_campos[$x]->numeros->comienzo_select;
                $fin=$datos_campos[$x]->numeros->fin_select;

                    echo '<br><select 
                    name="'.$name[$x].$editar.'" 
                    id="'.$name[$x].$editar.'" 
                    class="form-control class="custom-select form-control" style="width: 100%;" data-style="" data-live-search="true"">';


                for($ai=$comienzo;$ai<=$fin;$ai++){
                    echo '<option value="'.trim($ai).'">'.trim($ai).'</option>';
                    }


                    echo '</select>';


                }else if($boleano[$x]=="1"){

                    echo '<br>
                        <select name="'.$name[$x].$editar.'" 
                        id="'.$name[$x].$editar.'" 
                        class="custom-select form-control" style="width: 100%;" data-style="" data-live-search="true">
                            <option value="1">On</option>
                            <option value="0">Off</option>
                        </select>';

                // eof $boleano[$x]=="1"
                }else if($boleano[$x]=="0"){

               
                    echo '<br><select 
                    name="'.$name[$x].$editar.'" 
                    id="'.$name[$x].$editar.'" 
                    class="form-control class="custom-select form-control" style="width: 100%;" >';

                    // AQUI METEMOS CAMPOS EXTRA PRINCIPAL
                    // SI ISSET
                    if($datos_campos[$x]->extra['activo']=="1"){
                        
                    echo '<option value="'.
                    $datos_campos[$x]->extra->val.'">'.
                    $datos_campos[$x]->extra->text.'
                    </option>';
                    // eof $datos_campos[$x]['extra']['attr']['activo']=="1"
                    }

                    $val[$x]=$datos_campos[$x]->val->__toString();
                    $text[$x]=$datos_campos[$x]->text->__toString();

                    $select[$x]=$val[$x].",".$text[$x];


                    $from[$x]=$datos_campos[$x]->tabla;

                    if(isset($datos_campos[$x]->where)){
                    $where[$x]=$datos_campos[$x]->where;
                    }else if(!isset($datos_campos[$x]->where) || $datos_campos[$x]->where==""){
                        $where[$x]="id>0";
                    }

                    if($select[$x]!="" && $from[$x]!=""){ 
                    $options[$x]=$DB->select($select[$x],$from[$x],$where[$x]);

                    if($options[$x][r]>0){


                        for($p=0;$p<$options[$x][r];$p++){
 
                        echo '<option value="'.
                        $options[$x][f][$p][$val[$x]].'">'.
                        utf8_encode($options[$x][f][$p][$text[$x]]).'
                        </option>';

                        }

                    // eof $options[$x][r]>0
                    }

                }

                    echo '</select>';
                // eof $boleano[$x]=="0"
                }

         // eof $type[$x]=="select"
        }else if($type[$x]=="textArea"){

            $rows[$x]=$datos_campos[$x]->rows;
            $cols[$x]=$datos_campos[$x]->cols;

            echo '
            <textarea
            class="form-control"
            rows="'.$rows[$x].'"
            cols="'.$cols[$x].'"
            name="'.$name[$x].$editar.'"
            id="'.$name[$x].$editar.'"
             >'.$value[$x].'</textarea>
           ';


        // eof $type[$x]=="textarea"
        }

    // eof if($type[$x]!="hidden")
    }
    echo '</form>
    </div>';
    // eof for($x=0;$x<$n_campos;$x++){
    }

    if($nombre=="formularios"){


        // tengo que taerme cada una de las opciones
        // desde la BBDD y montar el html de cada $type.

        echo '<div class="col-md-9" style="padding-bottom:10px;">

         <legend>A&ntilde;adir Campos Formulario</legend>
        <form action="#"
            id="sumar_campo_ajax_form"
            method="post"
            accept-charset="utf-8">

            <input type="hidden" 
            name="xml_campos_formulario" 
            value="" verify="0">


        <label class="tiempoJuego">Type Del Campo:</label>
            <br>
            <select name="type_campo" verify="0" class="form-control">';

            $DB->query("select id,nombre from formulario_campos");

            while($row=$DB->fetch()){
                echo '<option value="'.$row['id'].'">'.$row['nombre'].'</option>';
            }

            echo '</select>
                        <br><br>
                        <label class="tiempoJuego">Id y Nombre Del Campo:</label>
                                    <input type="text" 
                                    class="form-control" 
                                    name="id_nombre_campo"
                                    id="id_nombre_campo" 
                                    placeholder="Id y Nombre Del Campo"
                                    value=""
                                    verify="0">
                        <br>
                        <label class="tiempoJuego">Place Y Label Del Campo:</label>
                                    <input type="text" 
                                    class="form-control" 
                                    name="place_label_campo"
                                    id="place_label_campo" 
                                    placeholder="Place Y Label Del Campo"
                                    value=""
                                    verify="0">

                        <br>
                        <label class="tiempoJuego">Verify Del Campo:</label>
                        <br>
                    <select name="verify_campo" verify="0" class="form-control">
                        <option value="1">On</option>
                        <option value="0">Off</option>
                    </select>';
        

            // CAMPOS ESPECIALES SELECT CON TABLA
            echo '<div name="campos_select" style="display:none">
                <br>
                <label class="tiempoJuego">Extra del Select:</label>
                <br>
                <select name="extra_select" verify="0" class="form-control">
                <option value="1">On</option>
                <option value="0">Off</option>
                </select>

                <br>
                <label class="tiempoJuego">Val del Extra:</label>
                <input type="text" 
                class="form-control" 
                name="val_extra"
                id="val_extra" 
                placeholder="Val del Extra"
                value="0"
                verify="0">
                <br>

                <label class="tiempoJuego">Text del Extra:</label>
                <input type="text" 
                class="form-control" 
                name="text_extra"
                id="text_extra" 
                placeholder="Text del Extra"
                value="Ninguna"
                verify="0">
                <br>

                <label class="tiempoJuego">Tabla Del Select:</label>
                <input type="text" 
                class="form-control" 
                name="tabla_select"
                id="tabla_select" 
                placeholder="Tabla Del Select"
                value=""
                verify="0">
                <br>
                <label class="tiempoJuego">Val Del Select:</label>
                <input type="text" 
                class="form-control" 
                name="val_select"
                id="val_select" 
                placeholder="Val Del Select"
                value="id"
                verify="0">
                <br>

                <label class="tiempoJuego">Text Del Select:</label>
                <input type="text" 
                class="form-control" 
                name="text_select"
                id="text_select" 
                placeholder="Text Del Select"
                value="nombre"
                verify="0">
                <br>
                <label class="tiempoJuego">Where Del Select:</label>
                <input type="text" 
                class="form-control" 
                name="where_select"
                id="where_select" 
                placeholder="Where Del Select"
                value="id>0"
                verify="0">
                
                
            </div>';


                        // CAMPOS ESPECIALES SELECT DE NºS 

            echo '<div name="campos_select_numero" style="display:none">
            <br>
                <label class="tiempoJuego">Comienzo del Select:</label>
                <input type="text" 
                class="form-control" 
                name="comienzo_select"
                id="comienzo_select" 
                placeholder="Comienzo del Select"
                value=""
                verify="0">
                <br>
                <label class="tiempoJuego">Fin del Select:</label>
                <input type="text" 
                class="form-control" 
                name="fin_select"
                id="fin_select" 
                placeholder="Fin del Select"
                value=""
                verify="0">
                
            </div>';



            // CAMPOS ESPECIALES TEXT AREA

            echo '<div name="campos_textArea" style="display:none">
            <br>
                <label class="tiempoJuego">Rows Del TextArea:</label>
                <input type="text" 
                class="form-control" 
                name="rows_textArea"
                id="rows_textArea" 
                placeholder="Rows Del TextArea"
                value=""
                verify="0">
                <br>
                <label class="tiempoJuego">Cols Del TextArea:</label>
                <input type="text" 
                class="form-control" 
                name="cols_textArea"
                id="cols_textArea" 
                placeholder="Cols Del TextArea"
                value=""
                verify="0">
                
            </div>';

            echo '<div name="camposxml_textArea" >
            <br>
            <label class="tiempoJuego">Campos XML:</label>
            <textarea
            class="form-control"
            rows="10"
            cols="5"
            name="campos_xml_textArea"
            id="campos_xml_textArea"
             ></textarea>
                
            </div>';



        echo '<br><br>
        <input  type="button" 
        class="btn btn-success callAjax" 
        value="A&ntilde;adir Campo"
        verify="0">
        <input type="hidden" name="sumar_campo_ajax" value="1" verify="0">

        </form>
 
        </div>
         ';
      // eof     if($nombre=="formularios"){

    }


 //eof else if($accion==i || $accion==u)
}
            echo '<!-- EOF class="form-group" -->
            </div> <br>
                <!-- footer del modal con el submit o cierre -->
            <div class="modal-footer">

        
            <input  type="button" 
                    class="btn btn-success callAjax" 
                    value="'.$datos->datos->submit.'"
                    verify="1">     
            <input type="hidden" name="'.$nombre.$editar.'" value="1">';

                
// ++++++ no se puede cambiar el input de debajo de ajax.
                        if(isset($datos['datos']['warning']['value'])){

                            echo  '
                            <button type="button" class="btn btn-danger" data-dismiss="modal">
                                '.$datos->datos->warning.'
                            </button>';
                        }

                if($accion==d){
                    //CIERRO EL FORM DE DELETE XRA SU CORRCTA EJECUCION
                    echo '</form>';
                }

                echo '<!-- EOF class="modal-footer" -->
                </div><!-- EOF  class="modal-body" -->
            </div><!-- EOF class="modal-content" -->
        </div><!-- EOF class="modal-dialog" -->
    </div><!-- EOF class="modal fade" -->
</div><!--EOF  Modal Escenario -->';
// EOF if($datos_modal[r]==1
 }


//OEF pintar_modal()
}

// ** ++ PINTAR_MODAL_JSON ++ ** //

function pintar_modal_json($nombre,$accion,$tabla=""){

    // +++ DESCRIPCION DE LAS ACCIONES +++
    // i = insert
    // d = delete
    // u = update
    //
    global $DB;

    $datos_modal=$DB->select("json","formulario_modal_json","nombre like '".$nombre."'");


    if($datos_modal[r]==0){

            echo "No hay ningun formulario con este nombre limeti";

    }else if($datos_modal[r]>1){

    
            echo "Le has puesto a 2 formularios el mismo nombre limeti";

    }else if($datos_modal[r]==1){



            $editar = $accion==u ? "_editar" : "";
            $datos = json_decode($datos_modal[f][0]['json'],true, 512, JSON_UNESCAPED_UNICODE);

            echo '<div class="modal fade" 
                id="'.$nombre.$editar.'" 
                tabindex="-1" 
                role="dialog" 
                aria-labelledby="myModalLabel" 
                aria-hidden="true">

                <div class="modal-dialog">
                    <div class="modal-content">   
                        <div class="modal-header">
                            <h3 class="modal-title" id="myModalLabel">
                                '.$datos['datos']['title'].'
                            </h3>
                            <button type="button" 
                                class="close" 
                                data-dismiss="modal">
                                <span aria-hidden="true">×</span>
                                <span class="sr-only">Cerrar</span>
                            </button>         
                        </div>
                        <div id="" class="modal-body">';


                        pintar_form($nombre,$accion,$datos,$tabla);

                    echo '<!-- EOF class="form-group" -->
                        </div> <br>
                        <!-- footer del modal con el submit o cierre -->
                        <div class="modal-footer">
                        <input  type="button" 
                                class="btn btn-success callAjax" 
                                value="'.$datos['datos']['submit'].'"
                                verify="1">     
                        <input type="hidden" name="'.$nombre.$editar.'" value="1">';
                        // ++++++ no se puede cambiar el input de debajo de ajax.
                            
                         
                        if(isset($datos['datos']['warning']['value'])){

                                    echo  '
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                                        '.$datos['datos']['warning'].'
                                    </button>';
                        }

                        if($accion==d){
                            //CIERRO EL FORM DE DELETE XRA SU CORRCTA EJECUCION
                            echo '</form>';
                        }

                        echo '<!-- EOF class="modal-footer" -->
                        </div><!-- EOF  class="modal-body" -->
                    </div><!-- EOF class="modal-content" -->
                </div><!-- EOF class="modal-dialog" -->
            </div><!-- EOF class="modal fade" -->
        </div><!--EOF  Modal Escenario -->';
        // EOF if($datos_modal[r]==1
    }


//OEF pintar_modal()
}

// ** ++  ++ ** //
function pintar_campos($nombre,$accion,$datos,$tabla=""){

    global $DB;
    $editar = $accion==u ? "_editar" : "";

    if($datos == ''){

        $datos_modal=$DB->select("json","formulario_modal_json","nombre like '".$nombre."'");
        $datos = json_decode($datos_modal[f][0]['json'],true, 512, JSON_UNESCAPED_UNICODE);


    }
    



            $n_campos=count($datos['bodyForm']['campo']);
            $d_c=$datos['bodyForm']['campo'];

            for($x=0;$x<$n_campos;$x++){

                if($tabla == 'filtros_productos'){

                    echo '<div class="col-3">';
                }

                $d_c[$x]['val'] = !isset($d_c[$x]['val']) ? "" : $d_c[$x]['val'];
                
                if($d_c[$x]['types']=="hidden"){

                            echo '
                            <input type="hidden" 
                            name="'.$d_c[$x]['names'].$editar.'"
                            value="'.$d_c[$x]['val'].'"
                            verify="'.$d_c[$x]['verifys'].'">';



                }else if($d_c[$x]['types']!="hidden"){  

                $obl = $d_c[$x]['verifys'] == 1 ? ' <span class="text-danger">*</span>' : '';
                echo '

                <label class="mt-2">'.ucwords(str_replace('_',' ',$d_c[$x]['placeholders'])).$obl.':</label>';
                    

                    if($d_c[$x]['types']=="text"){


                            echo '<input type="text" 
                            class="form-control" 
                            name="'.$d_c[$x]['names'].$editar.'"
                            placeholder="'.trim($d_c[$x]['placeholders']).'"
                            id="'.$d_c[$x]['names'].$editar.'" 
                            value="'.trim($d_c[$x]['val']).'"
                            verify="'.$d_c[$x]['verifys'].'">';


                           
                    // oef $d_c[$x]['types']=="text"
                    }else if($d_c[$x]['types']=="password"){

                    echo '<input type="password" 
                            class="form-control" 
                            name="'.$d_c[$x]['names'].$editar.'"
                            id="'.$d_c[$x]['names'].$editar.'" 
                            value="'.$d_c[$x]['val'].'"
                            verify="'.$d_c[$x]['verifys'].'">';

                    // $d_c[$x]['types']=="password"               
                    }else if($d_c[$x]['types']=="select"){

            // ANTES DE ESTE TENEMOS QUE MIRAR SI ES POR Nº O ES POR MESES.
                            if($d_c[$x]['mes']==1){

                                echo '<select verify="'.$d_c[$x]['verifys'].'"
                                name="'.$d_c[$x]['names'].$editar.'" 
                                id="'.$d_c[$x]['names'].$editar.'" 
                                class="custom-select">
                                        <option value="1">Enero</option>
                                        <option value="2">Febrero</option>
                                        <option value="3">Marzo</option>
                                        <option value="4">Abril</option>
                                        <option value="5">Mayo</option>
                                        <option value="6">Junio</option>
                                        <option value="7">Julio</option>
                                        <option value="8">Agosto</option>
                                        <option value="9">Septiembre</option>
                                        <option value="10">Octubre</option>
                                        <option value="11">Noviembre</option>
                                        <option value="12">Diciembre</option>
                                        </select>';


                            }else if($d_c[$x]['numeros']==1){

                                echo '<select verify="'.$d_c[$x]['verifys'].'"
                                name="'.$d_c[$x]['names'].$editar.'" 
                                id="'.$d_c[$x]['names'].$editar.'" 
                                class="custom-select">';


                                for($ai=$d_c[$x]['comienzo'];$ai<=$d_c[$x]['fin'];$ai++){

                                    echo '<option value="'.trim($ai).'">'.trim($ai).'</option>';

                                }

                                echo '</select>';


                            }else if($d_c[$x]['boleano']=="1"){

                                echo '<select verify="'.$d_c[$x]['verifys'].'"
                                name="'.$d_c[$x]['names'].$editar.'" 
                                id="'.$d_c[$x]['names'].$editar.'" 
                                class="custom-select select_boleano">
                                        <option value="0">No</option>
                                        <option value="1">Si</option>
                                        </select>';

                            // eof $d_c[$x]['boleano']=="1"
                            }else if($d_c[$x]['boleano']=="0"){

                           
                                echo '<select verify="'.$d_c[$x]['verifys'].'"
                                name="'.$d_c[$x]['names'].$editar.'" 
                                id="'.$d_c[$x]['names'].$editar.'" 
                                class="custom-select">';

                                // AQUI METEMOS CAMPOS EXTRA PRINCIPAL
                                // SI ISSET
                            if($d_c[$x]['extra']=="1"){
                                    
                                echo '<option value="'.
                                $d_c[$x]['extra_val'].'">'.
                                $d_c[$x]['extra_text'].'
                                </option>';
                                // eof $d_c[$x]['extra']['attr']['activo']=="1"
                            }

                            $val[$x]=$d_c[$x]['select_val'];
                            $text[$x]=$d_c[$x]['select_text'];

                            $select[$x]=$val[$x].",".$text[$x];


                            $from[$x]=$d_c[$x]['tabla'];

                            if(isset($d_c[$x]['where'])){

                                $where[$x]=$d_c[$x]['where'];

                            }else if(!isset($d_c[$x]['where']) || $d_c[$x]['where']==""){

                                $where[$x]="id>0";
                            }

                            if($select[$x]!="" && $from[$x]!=""){  

                                    $options[$x]=$DB->select($select[$x],$from[$x],$where[$x]);
                                    if($options[$x][r]>0){

                                        for($p=0;$p<$options[$x][r];$p++){
                 
                                        echo '<option value="'.
                                        utf8_encode($options[$x][f][$p][$val[$x]]).'">'.
                                        utf8_encode($options[$x][f][$p][$text[$x]]).'
                                        </option>';

                                    }

                                } // // eof $options[$x][r]>0

                            } // if($select[$x]!="" && $from[$x]!=""){ 

                            echo '</select>';
                            
                        } // eof $d_c[$x]['boleano']=="0"
                     
                    }else if($d_c[$x]['types']=="textArea"){ // eof $d_c[$x]['types']=="select"

                        echo '
                        <textarea
                        class="form-control"
                        rows="'.$d_c[$x]['rows'].'"
                        cols="'.$d_c[$x]['cols'].'"
                        name="'.$d_c[$x]['names'].$editar.'"
                        id="'.$d_c[$x]['names'].$editar.'" 
                         >'.$d_c[$x]['val'].'</textarea>
                       ';

                    } // eof $d_c[$x]['types']=="textarea"

                } // // eof if($d_c[$x]['types']!="hidden")
                       
                if($tabla == 'filtros_productos'){

                    echo '</div>';
                }
        }// eof for($x=0;$x<$n_campos;$x++){


} // function pintar_campos($nombre,$accion,$json,$tabla=""){


// ** ++ JSON_ENCODE_ARR ++ ** //

function pintar_tbody($nombre,$modal,$where=""){

    
    try {

            global $DB;
            global $DB2;


            $d = $DB->select('*','t_body','nombre_tbody like "'.$nombre.'"');

            if($d[r]==0){

                throw new Exception('No hay ningun tbody con ese mismo nombre');

            }

            if($d[r]>1){

                throw new Exception('Hay mas de un tbody con el mismo nombre');

            }

            $t = $d[f][0];

            $datatable_on = $t['datatable_on']==1 ? 'datatable' : '';
            $action_zone = $t['delete_']==1 || $t['editar']==1 || $t['edit_pop_up']==1 || $t['enviar_emails']==1 ? 1 : 0;

            $table['body'] = '

            <table id="'.$t['id_table'].'" class="display table table-hover '.$datatable_on.' table-bordered table-responsive table-striped">
                <thead>
                    <tr  class="active">';

                        if($nombre == 'facturas_pendientes'){

                            $table['body'] .= '<th>
                                                    <input type="checkbox" checked id="cobrar_todo"> ¿Gestionar?
                                                </th>
                                                <th>
                                                    <input type="checkbox" checked id="facturar_todo"> ¿Facturar?
                                                </th>';
                        }
                        
                        $datos_cabecera = explode('|',$t['datos_cabecera']);
                       
                        foreach ($datos_cabecera as $key => $value) {
                                
                            if($value!="id"){

                                $table['body'] .= '<th>'.ucwords(strtolower(str_replace('_',' ',$value))).'</th>';
                            }
                        }

                        if($action_zone == 1){

                            $table['body'] .= '<th style="width: 15%">Acciones</th>';

                        }



                $table['body'] .= '
                    </tr>
                </thead>                                  
                <tbody>';

                $datos_tabla = explode('|',$t['datos_tabla']);
                $campos_tabla = count($datos_tabla);
                // SI SOLO TENEMOS 1 CAMPO
                if($campos_tabla==1){
                    // LO ASIGNAMOS
                    $datos_query=$datos_tabla;

                    // SI TENEMOS + DE 1 CAMPOS(LO NORMAL)
                }else if($campos_tabla>1){

                    // LA PRIMERA POSICION
                    $datos_query=$datos_tabla[0];

                    for($d=1;$d<$campos_tabla;$d++){
                        //AÑADIMOS LAS DEMAS CON , POR DELANTE

                        $datos_query.=",".$datos_tabla[$d];
                        
                    }

                } //if($campos_tabla==1){
        
                // echo $datos_query;
                // QUERY XRA CONTENIDO DE TABLA 
                $resultado = [r => 0]; // PHP 8: inicializar para evitar Undefined variable
                if($t['tabla_inner']!=""){

                    $resultado=$DB->inner_join($datos_query,$t['tabla_inner'],$t['on_inner'].$where);

                    // DEBUG LOCALHOST
                    if(isset($_SERVER['REMOTE_ADDR']) && in_array($_SERVER['REMOTE_ADDR'],['::1','127.0.0.1'])){
                        echo '<pre style="background:#fffbe6;border:1px solid #f90;padding:6px;font-size:11px">[DEBUG inner_join] FROM '.$t['tabla_inner'].' ON '.$t['on_inner'].$where.' → '.$resultado[r].' filas</pre>';
                    }

                }else if(isset($t['tabla_inner']) && $t['tabla_inner']==0 || !isset($t['tabla_inner'])){

                    $where_query = $where!='' ? $where : (isset($t['where_query']) && $t['where_query']!="" ? $t['where_query'] : 'id>0');
                  
                    $resultado=$DB->select($datos_query,$t['tabla_body'],$where_query);

                    // DEBUG LOCALHOST
                    if(isset($_SERVER['REMOTE_ADDR']) && in_array($_SERVER['REMOTE_ADDR'],['::1','127.0.0.1'])){
                        echo '<pre style="background:#fffbe6;border:1px solid #f90;padding:6px;font-size:11px">[DEBUG select] SELECT '.$datos_query.' FROM '.$t['tabla_body'].' WHERE '.$where_query.' → '.$resultado[r].' filas</pre>';
                    }

                } // if($t['tabla_inner']!=""){

                if($resultado[r]>0){

                    $table['totales']=0;
                    $table['total_gastos']=0;
                    $table['total_ingresos']=0;

                    for($x=0;$x<$resultado[r];$x++){


                            if(isset($resultado[f][$x]["title"])){

                                $title='title="'.$resultado[f][$x]["title"].'"';
                            
                            }else{

                               $title='title="'.$resultado[f][$x]["id"].'"';

                            }

                        $table['body'].= '<tr>';
                        //METEMOS TODOS LOS CAMPOS DE 1

                        if($nombre == 'facturas_pendientes'){

                            $table['body'] .= '<td>
                                                    <input type="checkbox" checked 
                                                    name="cobrar" 
                                                    id_factura="'.$resultado[f][$x]["id"].'"> 
                                                    Gestionar
                                                </td>
                                                <td>
                                                    <input type="checkbox" checked 
                                                    name="facturar" 
                                                    id_factura="'.$resultado[f][$x]["id"].'"> 
                                                    Facturar
                                                </td>';
                        }

                        $control_td=0; // con esta $ tengo el control de cada vuelta

                        foreach ($datos_cabecera as $v => $key) {

                            $table['body'].= '<td ';

                            // aqui añadimos en principio el id
                            // la idea, sera extrapolar a span con el fin de poder
                            // filtrar por campos que no se vean.
                            
                            if($control_td==0){
                                $table['body'].= ' '.$title.' data-placement="bottom" data-toggle="tooltip" ';
                            }

                            if($key!="id"){


                                if($t['tabla_body']=="_paises"){

                                    $table['body'].= 'class="info">
                                        '.utf8_encode($resultado[f][$x][$key]).'
                                        <input type="text"
                                            tabla_editar="'.$t['tabla_body'].'"
                                            id_editar="'.$resultado[f][$x]["id"].'" 
                                            name="'.$key.'"
                                            class="edita_input form-control"  
                                            value="'.utf8_encode($resultado[f][$x][$key]).'" 
                                            placeholder="">
                                         
                                    </td>';

                                }else if($key=="publicado" || $key=="aprobado"){

                                    $cartel = $resultado[f][$x][$key] == 0 ? 'No' : 'Sí';
                                    $bg = $resultado[f][$x][$key] == 0 ? 'warning' : 'success';
                                    $table['body'].= ' class="bg-'.$bg.'">'.$cartel.'</td>';


                                }else if($key=="costo_whatsapp"){

                                    $table['body'].= '>'.$resultado[f][$x][$key].' &euro;</td>';
                                    $table['totales'] += $resultado[f][$x][$key];

                                }else if($nombre== 'campanas_puntuales' && $key=="caducidad"){

                                    $s = $resultado[f][$x][$key] == 1 ? '' : 's';
                                    $table['body'].= '>'.$resultado[f][$x][$key].' día'.$s.'</td>';

                                }else if($nombre== 'campanas_puntuales' && $key=="oferta"){

                                    if($resultado[f][$x]['tipo'] == 'descuento'){

                                        $cartel = $resultado[f][$x]['porcentaje'].'%';

                                    }else{

                                        $dp = $DB2->select('nombre_producto','productos','id = '.$resultado[f][$x]['id_producto']);
                                        $cartel = utf8_decode($dp[f][0]['nombre_producto']);
                                    }

                                    $table['body'].= '>'.$cartel.'</td>';
                                    

                                }else if($key=="estado" && $t['tabla_body']!="estados"){

                                    if($resultado[f][$x][$key]=="1"){

                                        $estado = $t['tabla_body'] == 'facturas' ? 'Cobrada' : ($t['tabla_body'] == 'tareas' ? 'Realizada' : 'Activo');

                                        $estado = $t['tabla_body'] == 'facturas' && $resultado[f][$x]['agrupado'] == 2 ? 'Agrupado' : $estado;
                                        
                                        $table['body'].= 'class="bg-success">'.$estado.'</td>';

                                    }else if($resultado[f][$x][$key]=="0"){

                                        $estado = $t['tabla_body'] == 'facturas' || $t['tabla_body'] == 'tareas' ? 'Pendiente' : 'Baja';

                                        $estado = $t['tabla_body'] == 'facturas' && $resultado[f][$x]['agrupado'] == 2 ? 'Agrupado' : $estado;

                                        $bg = $estado == 'Agrupado' ? 'success' : 'danger';

                                        $paga_fac = $t['tabla_body'] == 'facturas' ? 'id_factura="'.$resultado[f][$x]['id'].'" data-placement="bottom" data-toggle="tooltip" title="DobleClick para pasar a pagado y enviar al cliente"' : '';

                                        $table['body'].= 'class="bg-'.$bg.' is_factura" '.$paga_fac.'>'.$estado.'</td>';
                                    }


                                }else if($key=="costo" || $key=="pvp" || $key=="total" || $key=="importe" || $key=="importe_final"){


                                    $nuevo_importe = $t['tabla_body']!='pedidos' ? $resultado[f][$x][$key]/100 : $resultado[f][$x][$key];
                                    

                                    $bg = '';
                                    $tp = '';
                                    if($t['tabla_body']=='movimientos_bancarios'){

                                        $bg = $resultado[f][$x]['tipo'] == 1 ? 'text-success' : 'text-danger';
                                        $tp = $resultado[f][$x]['tipo'] == 1 ? '+ ' : '- ';

                                        if(strtolower($resultado[f][$x]['clase']) == 'gasto'){

                                            $table['total_gastos']+=$nuevo_importe;

                                        }else if(strtolower($resultado[f][$x]['clase']) == 'compra'){

                                            $table['totales']+=$nuevo_importe;

                                        }else if(strtolower($resultado[f][$x]['clase']) == 'venta'){

                                            $table['total_ingresos']+=$nuevo_importe;

                                        }

                                    }

                                    if($nombre == 'facturas_pendientes'){

                                        $table['totales']+=$nuevo_importe;

                                    }

                                    if($nombre == 'facturas'){

                                        $bg = $resultado[f][$x]['agrupado'] == 1 ? 'text-warning' : '';
                                        if($resultado[f][$x]['estado'] == 0){

                                            $table['total_gastos']+=$nuevo_importe;

                                        }else{

                                            $table['total_ingresos']+=$nuevo_importe;
                                        }
                                    }


                                    $table['body'].= 'class="info"><span class="'.$bg.'"> '.$tp.number_format($nuevo_importe,2,".","").' &euro;</span></td>';


                                }else if($key=="entrada" || $key=="salida" || $key=="creacion" || strpos($key,'fecha')!==false){


                                    $pre_fecha = explode(" ",$resultado[f][$x][$key]);
                                    $nueva_fecha=formatear_fecha($pre_fecha[0],m,i);
                                    //$nueva_fecha=formatear_fecha($resultado[f][$x][$key],m,i);
                                    $hora = isset($pre_fecha[1]) ? " ".$pre_fecha[1] : "";

                                    $timestamp = new DateTime($resultado[f][$x][$key]);
                                    $table['body'].= 'class="info"><span class="d-none">'.$timestamp->getTimestamp().'</span>'.$nueva_fecha.$hora.'</td>';                        

                                }else if($nombre=="movimientos_bancarios" && $key == 'clase'){

                                    $table['body'].= ' class="info">'.ucwords($resultado[f][$x][$key]).'</td>';

                                }else if($key=="movimiento_monetario"){

                                    if($resultado[f][$x][$key]==1){
                                        $bg_class='bg-success';
                                        $cartel = 'Si';
                                        $cartel.= $resultado[f][$x]['tipo_movimiento']==1 ? ' (Suma)' : ' (Resta)';
                                    }else if($resultado[f][$x][$key]==0){
                                        $bg_class='bg-warning';
                                        $cartel = 'No';

                                    }

                                    $table['body'].= 'class="'.$bg_class.'">'.$cartel.'</td>';

                                }else if($t['tabla_body'] == "movimientos_bancarios" && $key=="local"){

                                    $cartel = $resultado[f][$x]['clase']!='venta' ? $resultado[f][$x]['clase'] : $resultado[f][$x][$key];
                                    
                                    $table['body'].= '> '.$cartel.'</td>';

                                }else if($t['tabla_body']=="saldo_empresa" && $key=="movimiento"){

                                    $signo = $resultado[f][$x]['tipo']==1 ? "+" : (strpos($resultado[f][$x][$key],"-")===false ? "-" : "");
                                    $color_signo = $resultado[f][$x]['tipo']==1 ? "green" : "red";

                                    $table['body'].= 'class="info" style="color : '.$color_signo.';">'.$signo.' '.$resultado[f][$x][$key].'</td>';



                                }else if($nombre == 'pedidos' && $key == 'cliente' && $resultado[f][$x][$key] == 'Vitrina'){


                                    $table['body'].= 'class="info">'.$resultado[f][$x]['delegacion'].' (Vitrina)</td>';

                                }else if(strpos('telefono',$key)!==false || $key == 'whatsapp'){

                                    $table['body'].= 'class="info">'.str_replace(' ','',$resultado[f][$x][$key]).'</td>';

                                }else if($key == 'cliente'){

                                    $rs = $resultado[f][$x]['nombre_fiscal']!='' ? ' ('.utf8_encode($resultado[f][$x]['nombre_fiscal']).')' : '';

                                    $table['body'].= 'class="info">'.utf8_encode($resultado[f][$x][$key]).$rs.'</td>';

                                }else if($nombre == 'clientes' && $key == 'nombre'){

                                    $cartel = utf8_encode($resultado[f][$x][$key]);
                                    if($resultado[f][$x]['es_recurrente'] == 1){

                                        $d = $DB->select('sum(importe) as deuda','facturas','estado = 0 and agrupado<2 and id_cliente = '.$resultado[f][$x]['id']);

                                        if($d[r]>0 && $d[f][0]['deuda']>0){

                                            $table['totales'] += $d[f][0]['deuda'];
                                            $cartel = '<span class="text-warning">'.utf8_encode($resultado[f][$x][$key]).' <i class="fas fa-info-circle" data-placement="bottom" data-toggle="tooltip" title="'.number_format(($d[f][0]['deuda']/100),2,'.','').' &euro;"></i></span>';
                                        }

                                    }

                                    $table['body'].= 'class="info">'.$cartel.'</td>';

                                }else if($key == 'numero_factura' && $nombre == 'facturas'){


                                    $numero = $resultado[f][$x][$key] == '' ? 'Sin Facturar' : $resultado[f][$x][$key];

                                    $table['body'].= 'class="info">'.$numero.'</td> ';


                                }else if(strpos($key,'_edit')!==false){


                                    if(strpos($key,'fecha')!==false){

                                        $pre_fecha = explode(" ",$resultado[f][$x][$key]);
                                        $nueva_fecha=formatear_fecha($pre_fecha[0],m,i);
                                        $hora = isset($pre_fecha[1]) ? " ".$pre_fecha[1] : "";
                                        $txt = $nueva_fecha.$hora;
                                        $class = '';

                                    }else{

                                        $txt = utf8_encode($resultado[f][$x][$key]);
                                        $class = '';
                                    }

                                    $table['body'].= 'class="info"> 
                                        <span class="d-none">'.$txt.'</span>
                                        <input type="text" 
                                        class="form-control edita_input '.$class.'" 
                                        id_editar="'.$resultado[f][$x]['id'].'" 
                                        tabla_editar="'.$t['tabla_body'].'"
                                        name="'.$key.'" 
                                        value="'.$txt.'">

                                    </td>';

                                }else{ // forma_pago

                                    $table['body'].= 'class="info">'.utf8_encode($resultado[f][$x][$key]).'</td>';

                                } // if($key=="estado" && $t['tabla_body']!="estados"){ .... // if($key=="estado" && $t['tabla_body']!="estados"){ ....
                                
                            } // eof if($key!="id"){
                            $control_td++;
                        
                        
                        } // eof foreach ($datos_tabla as $key => $value

                        if($action_zone == 1){

                            $table['body'].= '<td>
                                <div class="form-button-action">';

                                    if($t['enviar_emails']==1){

                                           $table['body'].= '<button type="button" 
                                                data-toggle="tooltip" title="" 
                                                class="btn btn-round btn-icon mr-1  btn-success" 
                                                data-original-title="Envio" 
                                                data-toggle="modal" 
                                                data-target="#email_registro"
                                                data-backdrop="static" 
                                                data-keyboard="false"
                                                name="enviar_email" 
                                                tabla="'.$t['tabla_body'].'"
                                                id="'.$resultado[f][$x]['id'].'">
                                                <i class="fa fa-envelope"></i>
                                            </button>';

                                    }

                                    if($t['edit_pop_up']==1){
                                        // boton de editar pop_up 

                                        if($nombre == 'mis_clientes'){

                                            $table['body'].= '<button type="button" title="" 
                                                class="btn btn-round btn-icon mr-1 btn-info"
                                                name="load_ficha"
                                                tabla="'.$t['tabla_body'].'"
                                                id="'.$resultado[f][$x]['id'].'">
                                                <i class="fa fa-edit" data-toggle="tooltip" data-original-title="Editar"></i>
                                               </button>';

                                        }else if($nombre == 'blog'){

                                            $table['body'].= '
                                                <button type="button" title="" 
                                                    class="btn btn-round btn-icon mr-1 btn-info"
                                                    name="abrir_blog"
                                                    id_blog="'.$resultado[f][$x]['id'].'">
                                                    <i class="fa fa-edit" data-toggle="tooltip" data-original-title="Editar"></i>
                                               </button>
                                               <button type="button" title="" 
                                                    class="btn btn-round btn-icon mr-1 btn-warning"
                                                    name="ver_coments"
                                                    id_blog="'.$resultado[f][$x]['id'].'">
                                                    <i class="fas fa-comments" data-toggle="tooltip" data-original-title="Comentarios"></i>
                                               </button>
                                               ';

                                        }else if($nombre == 'facturas'){

                                            if($resultado[f][$x]['agrupado'] == 1){

                                                $tb = 'facturas_agrupadas';
                                                $idp = $resultado[f][$x]['id'];
                                            }else{

                                                $tb = 'pedidos';
                                                $idp = $resultado[f][$x]['id_pedido'];

                                            }

                                            $table['body'].= '<button type="button" title="" 
                                                class="btn btn-round btn-icon mr-1 btn-info"
                                                name="editar_pop_up"
                                                tabla="'.$tb.'"
                                                id="'.$idp.'">
                                                <i class="fa fa-edit" data-toggle="tooltip" data-original-title="Editar"></i>
                                               </button>';

                                        }else{

                                            $tbl = $nombre == 'productos_web' ? 'productos' : $t['tabla_body'];

                                            $table['body'].= '<button type="button" title="" 
                                                class="btn btn-round btn-icon mr-1 btn-info"
                                                name="editar_pop_up"
                                                tabla="'.$tbl.'"
                                                id="'.$resultado[f][$x]['id'].'">
                                                <i class="fa fa-edit" data-toggle="tooltip" data-original-title="Editar"></i>
                                               </button>';
                                        }

                                    }else if($t['editar']==1){
                                        
                                        $table['body'].= '<button type="button" title="" 
                                            class="btn btn-round btn-icon mr-1 btn-info"
                                            data-toggle="modal" 
                                            data-target="#'.$modal[0].'_editar"
                                            data-backdrop="static" 
                                            data-keyboard="false"
                                            name="editar"
                                            tabla="'.$t['tabla_body'].'"
                                            id="'.$resultado[f][$x]['id'].'">
                                            <i class="fa fa-edit"></i>
                                        </button>';

                                   
                                        if($nombre == 'comentarios_blog' &&  $resultado[f][$x]["aprobado"] == 0){

                                            $table['body'].= '<button type="button" title="" 
                                                class="btn btn-round btn-icon mr-1 btn-success"
                                                name="aprobar_comentario"
                                                tabla="'.$t['tabla_body'].'"
                                                id_comentario="'.$resultado[f][$x]['id'].'">
                                                <i class="fas fa-thumbs-up" data-toggle="tooltip" data-original-title="DobleClick para Aprobar"></i>
                                            </button>';
                                        }
                                    }

                                    if($nombre == 'facturas' || $nombre == 'facturas_pendientes'){

                                        $ver = $nombre == 'facturas' ? 'Factura' : 'Albarán';

                                        $table['body'].= '

                                            <button type="button" class="btn btn-round btn-icon mr-1  btn-warning" 
                                            name="ver_facturas" 
                                            id_factura="'.$resultado[f][$x]['id'].'" >
                                            <i class="fas fa-eye" data-toggle="tooltip" data-original-title="Ver '.$ver.'"></i>
                                            </button>';

                                        if(isset($resultado[f][$x]['estado']) && $resultado[f][$x]['estado'] == 1){

                                            $table['body'].= '

                                                <button type="button" class="btn btn-round btn-icon mr-1  btn-danger" 
                                                name="quitar_pago" 
                                                id="'.$resultado[f][$x]['id'].'" >

                                                <i class="fas fa-backspace" data-toggle="tooltip" data-original-title="Quitar Pago""></i>
                                                </button>';
                                        }

                                    }

                                    if($nombre == 'clientes'){

                                       
                                            $table['body'].= '<button type="button" class="btn btn-round btn-icon mr-1  btn-warning" 
                                            name="load_ficha" 
                                            id="'.$resultado[f][$x]['id'].'" >
                                            <i class="fas fa-eye" data-toggle="tooltip" data-original-title="Ver Pedidos"></i>
                                            </button>';

                                            // name="load_ficha"
                                    }

                                    if($t['delete_']==1){


                                        $table['body'].= '<button type="button" title="" class="btn btn-round btn-icon mr-1  btn-danger" 
                                            data-toggle="modal" 
                                            data-target="#borrar_registro"
                                            data-backdrop="static" data-keyboard="false"
                                            name="delete" 
                                            id="'.$resultado[f][$x]['id'].'"
                                            tabla_eliminar="'.$t['tabla_body'].'">
                                            <i class="fa fa-times" data-toggle="tooltip" data-original-title="Borrar"></i>
                                            </button>';
                                    }

                            $table['body'].=  '</div>
                            </td>';
                            
                        } // if($action_zone == 1){

                        $table['body'].=  '</tr>';

                    } // for($x=0;$x<$resultado[r];$x++){

                }   // if($resultado[r]>0){ 

                $table['body'] .= '</tbody>
                    </table>';    

                $table[r] = $resultado[r];

            

    } catch (Exception $e) {
        
        // $layout = isset($notify) ? 'notify' : 'topRight';
        // script("$('#modal_espera_general').modal('hide');");   
        notify('<i class="fa fa-warning"></i> '.$e->getMessage(),'warning'); 
        $table = $e->getMessage();

    }


    return $table;

}//OEF pintar_tbody

function pintar_card($nombre,$width='col-md-4'){

    try {
        
        global $DB;
        
            $d = $DB->select('*','cards','nombre_card like "'.$nombre.'"');

            if($d[r]==0){

                throw new Exception('No hay ningun card con ese mismo nombre');

            }

            if($d[r]>1){

                throw new Exception('Hay mas de un card con el mismo nombre');

            }

            $card = '   <div class="'.$width.'">
                            <div class="card full-height">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title">Siguientes Citas</div>
                                    </div>
                                </div> <!-- <div class="card-header"> -->
                                <div class="card-body">';

                                $card .= '    <div class="d-flex">
                                        <div class="avatar avatar-online">
                                            <span class="avatar-title rounded-circle border border-white bg-info">
                                                <i class="fas fa-smile-wink"></i>
                                            </span>
                                        </div>
                                        <div class="flex-1 ml-3 pt-1">
                                            <h6 class="text-uppercase fw-bold mb-1">Taller Pablo <span class="text-success pl-3">Ok</span></h6>
                                            <span class="text-muted">Quiere web + Mybussines</span>
                                        </div>
                                        <div class="float-right pt-1">
                                            <small class="text-muted">8:40 PM</small>
                                        </div>
                                    </div>
                                    <div class="separator-dashed"></div>';

                                $card .= '</div> <!-- <div class="card-body"> -->
                            </div> <!-- <div class="card full-height"> -->
                        </div> <!-- <div class="col-md-4"> -->';

    } catch (Exception $e) {
        
        notify('<i class="fa fa-warning"></i> '.$e->getMessage(),'warning'); 
        $car = $e->getMessage();
    }

} // function pintar_card(){
/*

*/


    function my_json_encode($arr)
    {
        //convmap since 0x80 char codes so it takes all multibyte codes (above ASCII 127). So such characters are being "hidden" from normal json_encoding
        array_walk_recursive($arr, function (&$item, $key) { if (is_string($item)) $item = mb_encode_numericentity($item, array (0x80, 0xffff, 0, 0xffff), 'UTF-8'); });
        return mb_decode_numericentity(json_encode($arr), array (0x80, 0xffff, 0, 0xffff), 'UTF-8');

    }

    // ** ++ JSON_ENCODE_BBDD ++ ** //


    function json_encode_bbdd($text){


        $return=json_encode($text, JSON_UNESCAPED_UNICODE);
        $return=utf8_decode($return);

        return $return;
    }


    // ** ++ FORMATEAR_FECHA ++ ** //
    function formatear_fecha($fecha,$tipo_entrada,$tipo_salida){

        /*
        Entrada :
        (i) De input = 08/09/2017
        (m) De Mysql = 2017-09-16
        (x) For XML (beds,sers) = 20170908
        (c) ck = 23-12-2019
        
        Salida:
        (i) For input = 08/09/2017
        (m) For Mysql = 2017-09-16
        (x) For XML (beds,sers) = 20170908
        */


        if($tipo_entrada==i){

            $is_date=strpos($fecha,"/");
            if($is_date!==false){
                list($dia,$mes,$ano)=explode("/",$fecha);
            }

        }else if($tipo_entrada==m){

            $is_date=strpos($fecha,"-");
            if($is_date!==false){
                list($ano,$mes,$dia)=explode("-",$fecha);
            }

        }else if($tipo_entrada==x){

            $ano=$fecha[0].$fecha[1].$fecha[2].$fecha[3];
            $mes=$fecha[4].$fecha[5];
            $dia=$fecha[6].$fecha[7];

        }else if($tipo_entrada == c){

            $is_date=strpos($fecha,"-");
            if($is_date!==false){
                list($dia,$mes,$ano)=explode("-",$fecha);
            }

        }

        $newDate=false;
        if($tipo_salida==i){
            if(!isset($is_date) || $is_date!==false){
                $newDate=$dia."/".$mes."/".$ano;
            }
        }else if($tipo_salida==m){
            if(!isset($is_date) || $is_date!==false){
                $newDate=$ano."-".$mes."-".$dia;
            }
        }else if($tipo_salida==x){
            $newDate=$ano.$mes.$dia;           
        }


        return $newDate;
    // eof function formatear_fecha()
    }

    // ** ++ COMPARAR_FECHAS ++ ** //
    function comparar_fechas($fecha1,$fecha2,$tipo_entrada=i){

        // date nativo de PHP, compara fechas en MySql
        // por lo que tenemos que formatear fecha en caso de que la 
        // entrada sea diferente a m

        if($tipo_entrada!=m){
            
            $fecha1 = formatear_fecha($fecha1,$tipo_entrada,m);
            $fecha2 = formatear_fecha($fecha2,$tipo_entrada,m);
        }

        $datetime1 = date_create($fecha1);
        $datetime2 = date_create($fecha2);
        $interval = date_diff($datetime1, $datetime2);
        $intervalo=$interval->format('%r%a');

        // retona numero entero o - 
        return $intervalo;

    }



    // ** ++ SUMA_DIAS ++ ** //
    function suma_dias($fecha,$ndies,$tipo_entrada,$tipo_salida,$tipo_suma='D'){
 

        if($tipo_entrada!=m){
            
            $fecha = formatear_fecha($fecha,$tipo_entrada,m);
            
        }

        if($tipo_salida==m){
            $salida = 'Y-m-d';
        }else if($tipo_salida==i){
            $salida = 'd/m/Y';
        }

        $fecha = new DateTime($fecha);
        $fecha->add(new DateInterval('P'.$ndies.$tipo_suma));

        $nueva_fecha = $fecha->format($salida);

        return $nueva_fecha; 
    } 


    // ** ++ RESTA_DIAS ++ ** //
    function resta_dias($data,$ndies){ 


                /*
        DateInterval::format() - Formatea el intervalo
    DateTime::add() - Añade una cantidad de días, meses, años, horas, minutos y segundos a un objeto DateTime
    DateTime::sub() - Sustrae una cantidad de días, meses, años, horas, minutos y segundos de un objeto DateTime
    */
        
        list($dia,$mes,$any)=explode("/",$data);
        
        $dia=$dia - $ndies; 
        $nueva=mktime(0,0,0, $mes,$dia,$any); 
        $novadata = date("d/m/Y",$nueva); 
        return $novadata; 
    }

    /// ** ++ NOW ++ ** //
    function now(){
       return date("Y-m-d H:i:s"); 
    }

    function letras_mes($mes){

        $letras = array("Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic");
        $return = $letras[$mes];

        return $return;

    }


    

    // ** ++ FORMAT_NUMER ++ ** //

    function format_number($integer){

        // devuelve tipo 10.25 1000.67
        // perfecto para sacar en pantalla
        $integer=str_replace(".","",$integer);
        $integer=str_replace(",",".",$integer);
        $integer=number_format($integer,2,".","");

        return $integer;
    }

    // ** ++ TILDES_MIN_MAY ++ ** //
    function tildes_min_may($char,$salida){

        // HOTELES Y HABITACIONS: MIN A MAY , DE MAY A MIN RESPENTANDO TILDES
        #
        # SALIDAS : 
        # h = nombre del hotel a mayuscula
        # b =nombre de la habitacion a minuscula.

        if($salida==h){


            $char=utf8_encode($char); 
            $change=array("Á","Ó","É","Í","Ú","Ñ");
            $find=array("á","ó","é","í","ú","ñ");
            $char=str_replace($find,$change,$char);

        }else if($salida==b){

            $find=array("Á","Ó","É","Í","Ú","Ñ");
            $change=array("á","ó","é","í","ú","ñ");
            $char=str_replace($find,$change,$char);
            $char=strtolower($char);
            $char=ucwords($char);

        }
                                                                        
        return $char;
    }

    // ** ++ TILDES_TO_HTML ++ ** //

    function tildes_to_html($char,$is_xml=0){

        // HOTELES Y HABITACIONS: MIN A MAY , DE MAY A MIN RESPENTANDO TILDES
        #
        # SALIDAS : 
        # h = nombre del hotel a mayuscula
        # b =nombre de la habitacion a minuscula.


        $char= $is_xml==0 ? utf8_encode($char) : $char; 
        $find=array("Á","Ó","É","Í","Ú","Ñ","á","ó","é","í","ú","ñ");
        $change=array("&Aacute;","O&acute;","&Eacute;","&Iacute;","&Uacute;","&Ntilde;",
                      "&aacute;","&oacute;","&eacute;","i&acute;","&uacute;","&ntilde;");
        $char=str_replace($find,$change,$char);


                                                                        
        return $char;
    }

        function sin_tildes($char,$is_xml=0){


        $find=array("Á","Ó","É","Í","Ú","Ñ","á","ó","é","í","ú","ñ");
        $change=array("A","O","E","I","U","N","a","o","e","i","u","n");
        $char=str_replace($find,$change,$char);


                                                                        
        return $char;
    }





//-----------ESTO HAY QUE MEJORARLO---------------//

// ** ++ NUMFORMATOMYSQL ++ ** //
function numFormatoMysql($num){
    $num=str_replace('.','',$num);
    $num=str_replace(',','.',$num);
    return quitarLetras($num);
}

// ** ++ QUITARLETRAS ++ ** //
function quitarLetras($str){
    $legalChars = "%[^0-9\-\. ]%";
    $str=preg_replace($legalChars,"",$str);
    return $str;
}

// ** ++ NUMFORMATO ++ ** //
function numFormato($num,$decimales=2){
    $resultado=number_format($num,$decimales,",",".");
    return $resultado;
}

//-----------------------------------------//

// ** ++ ACCION ADMIN ++ ** //


    function accion_admin($accion,$url=""){

    	//LAS ACCIONES LAS VAMOS A PASAR CON 1 LETRA CONSTANTE.
    		//INDICE DE LAS ACCIONES:
    			//r = redireccionar a una pagina concreta.

        // ideas-> poder leer informacion de cosultas etc 

        if($_SERVER['REMOTE_ADDR']=="5.154.69.122" || $_SERVER['REMOTE_ADDR']=="185.178.136.16"){


    	   if($accion==r){
    		
                script("location.href='".$url."'");

    	   }

//**AQUI PODEMOS SEGUIR METIENDO LAS ACCIONES QUE NOS PAREZCA*///



    	//EOF $_SERVER['REMOTE_ADDR']
        }	

     //eof accion_admin
    }

////////////////////-------------

// ** ++ GETPASS ++ ** //

    // SACAMOS UN NUEVO PASSWORD CON LA LONGITUD 
    // MARCADA EN $length
    function getPass($length){
        
        $str = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz0123456789";
        $cad = "";
        for($i=0;$i<$length;$i++) {
            $cad .= substr($str,rand(0,30),1);
        }
        if(strlen($cad)>$length){
            $cad=substr($cad,0,5);
        }
        
        
        return $cad;
    }


// ** ++ BODY_EMAIL ++ ** //
function body_email($tipo,$datos_cliente,$id_web,$textos=""){


    $Consulta_body_email=new DataAccess();

    $datos_web=$Consulta_body_email->select('nombre,web,email,color_email,nombre_comercial','delegaciones','id='.$id_web);

    // alert($datos_web[f][0]['nombre']);

    // titulo 
    // saludo cabecera

    // b -> beinvenida
    // c -> contraseña
    // p -> contrato de prestacion
    // t -> textos
    // s -> segundas compras
    // m -> mensaje usado si cliente en BBDD
    // f -> confirmacion de reserva

    if($tipo==b){
        $saludo_cabecera="BienVenid@ a";  
        $saludo_personal="Enhorabuena"; 
    }else if($tipo==p){
        $saludo_cabecera="Su Contrato de Prestaci&oacute;n"; 
        $saludo_personal="Estimad@";   
    }else if($tipo==f){
        $saludo_cabecera="Reserva Confirmada en "; 
        $saludo_personal="Enhorabuena";   
    }else{
        $saludo_cabecera="Saludos de"; 
        $saludo_personal="Estimad@";   
    }

    $url_cabecera = "https://".$datos_web[f][0]['web']."/dulceria/img/logo_argentina.png";


    $body = '<!DOCTYPE html>
    <!---[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"><![endif]-->
    <!--[if IE 7]><html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
    <!--[if IE 8]><html class="no-js lt-ie9" lang="en"> <![endif]-->
    <!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>'.utf8_encode($datos_web[f][0]['nombre_comercial']).'</title>
   
    </head>
    <body style="color:#'.$datos_web[f][0]['color_email'].'">
    <div>
    <table  cellspacing="0" cellpadding="0" style="font-family:Verdana, Geneva, sans-serif; font-size:1em; color:#'.$datos_web[f][0]['color_email'].'" border="0" width="583" align="center" >
    <tbody>
        <tr>
        <td bgcolor="#'.$datos_web[f][0]['color_email'].'" align="center" height="40" valign="top"  style="border-left:#d9d9d9 0.75px solid; padding-left:20px;border-right:#d9d9d9 0.75px solid; border-radius: 10px 10px 0px 0px; padding-top:10px;">
        <span style="font-size:1.5em; color:#fff; ">'.$saludo_cabecera.' La Argentina</span>
        </td>
        </tr>
        <tr >
        <td width="583" 
        style="border-left:#d9d9d9 1px solid;
        border-right:#d9d9d9 1px solid;">
        <a title="Publicidad" href="https://'.$datos_web[f][0]['web'].'">
        <img src="'.$url_cabecera.'" width="300"
        width="200" style="margin:10px auto; display:block;"/></a></td>
        </tr>
        <tr >
        <td  align="left" style="font-family:Verdana, Geneva, sans-serif; font-size:1.1em; padding:0px 17px; border-left:#d9d9d9 1px solid;border-right:#d9d9d9 1px solid; line-height:20px;"><br />';

        if($tipo==t || $tipo==f){

            $body.=$textos;

        }

        $body.=' </span>
        </td></tr>
        <tr>
        <td  style="border-left:#d9d9d9 1px solid;border-right:#d9d9d9 1px solid;">&nbsp;</td>
        </tr>
        <tr >
       <td  style="padding:0px 20px;border-left:#d9d9d9 1px solid;border-right:#d9d9d9 1px solid;"></td>
        </tr>
        <tr>
            <td   align="left" style="vertical-align:top; padding-left:20px;border-left:#d9d9d9 1px solid;border-right:#d9d9d9 1px solid; padding-right:20px;"><br><br />
            <p style="font-size:0.9em">En  cumplimiento de lo establecido en la Ley Org&aacute;nica 15/1999, de 13 de diciembre,  de Protecci&oacute;n de Datos de Car&aacute;cter Personal, le informamos que, mediante la  cumplimentaci&oacute;n del presente formulario, sus datos personales quedar&aacute;n  incorporados y ser&aacute;n tratados en un fichero automatizado (pudiendo ejercitar  los derechos de acceso, rectificaci&oacute;n, cancelaci&oacute;n y oposici&oacute;n, escribiendo a <a href="mailto:'.$datos_web[f][0]['email'].'">'.$datos_web[f][0]['email'].'</a>) cuyo responsable es  '.utf8_encode($datos_web[f][0]['nombre_comercial']).', debidamente inscrito en la AEPD, titularidad de '.utf8_encode($datos_web[f][0]['nombre_comercial']).' con la  finalidad de poder gestionar su solicitud, as&iacute; como para mantenerle informado  de futuras promociones, noticias y novedades relacionadas con el sector. </p>
            </td>
        </tr>
            <tr>
                <td bgcolor="#'.$datos_web[f][0]['color_email'].'" align="center" height="40" valign="top"  style="border-left:#d9d9d9 1px solid; padding-left:20px; border-right:#d9d9d9 1px solid; border-radius: 0px 0px 10px 10px; padding-top:10px; text-align: center; color:white; font-size:18px;">';

                if(isset($datos_cliente['id'])){
                    $body.= '<span>Si no desea m&aacute;s informaci&oacute;n pulse <a href="https://'.$datos_web[f][0]['web'].'/baja_email/'.$datos_cliente['id'].'" style="color:white;">aqu&iacute;</a></span>';
                }
            $body.=
            '</td>
        </tr>   
    </tbody>
    </table>
    </div>                
    </body></html>';

    return $body;
// function body_email(
}
    

        // ** ++ sumar_historico_xml ++ ** //
        function sumar_historico_xml($historico,$usuario,$tipo,$id_registro=0,$importe=0){

                    $nuevo_historico= new SimpleXMLElement($historico);
                    $registro=$nuevo_historico->addChild('registro');
                    $registro->addChild('usuario',$usuario);
                    $registro->addChild('tipo',$tipo);

                    // este se usa desde las busquedas de los clientes
                    if($usuario=="On-line"){
                        $registro->addChild('id_xml',$id_registro); 
                    }

                    // este usuario solo se usa cuando guardamos
                    // los emails enviados desde la app automaticos (ajax.php)
                    if($usuario=="Automatic"){
                        $registro->addChild('id_booking_emails',$id_registro); 
                    }

                    // cancelaciones de los clientes y nosotros (ajax.php)
                    if($tipo=="Cancelacion"){
                        $registro->addChild('id_xml_cancelacion',$id_registro); 
                    }

                    // devolcuion de importe de reservas
                    // confirmar_redsys_devolcion_reservas.php
                    if($tipo=="Devolucion"){
                        $registro->addChild('id_comunicacion_tpv_reservas',$id_registro); 
                        $registro->addChild('importe_devuelto',$importe); 
                    }

                    // importe cobro reserva
                    // confirmar_redsys_reservas.php
                    // confirmar_redsys_reservas_ppte_pago.php

                    if($tipo=="Cobro"){
                        $registro->addChild('id_comunicacion_tpv_reservas',$id_registro); 
                        $registro->addChild('importe',$importe); 
                    }

                    //pop_up_reservas
                    if($tipo=="Cambio_Bonos"){
                        $operacion = $id_registro==s ? "suma" : "resta";
                        $registro->addChild('operacion',$operacion); 
                        $registro->addChild('bonos',$importe);
                    }

                    if($tipo=="Cobro_manual" || $tipo=="Devolucion_manual"){
                        $registro->addChild('id_movimientos_bancarios',$id_registro); 
                        $registro->addChild('importe',$importe); 
                    }

                    $registro->addChild('fecha',date('d/m/Y'));
                    $registro->addChild('hora',date('H:i:s'));

                return $nuevo_historico->asXML();
                // function sumar_historico_xml(){
        }

        // ** ++ limpiar_keys ++ ** //
        function  limpiar_keys($var,$explode="_edit"){

// TENER MUY EN ECUENTA AL USAR,
// QUE DEJA DE TENER EL VALOR DE
// ${$KEY}=$VALUE QUE TENGO COSTUMBRE DE USAR
            //alert("limpiar");
                foreach ($var as $key => $value) {

                   // cada uno de los campos y quitarle _editar
                    if($key!="accion" &&  $key!="tabla_editar" && $key!="id_editar"){
                     
                        $nuevo_key=explode($explode,$key);
                        $return[$nuevo_key[0]]=$value;

                    }

                    //return ${$nuevo_name[0]};
            // eof     foreach ($_POST as $key => $value) {
                }
                return $return;

        // EOF  public function limpiar_editar($var,$onlyKey=1,$explode="_edit"){
        }

        // ** ++ texto_estrellas ++ ** //
        function texto_estrellas($txt){

            $find = stripos($txt,"@");

            if($find ===  false){

                $end = strlen($txt)/2;

                for($x=0;$x<$end;$x++){

                    $txt[$x]="*";

                }

            }else{ // if($find ===  false){

                $arr = split("@",$txt);

                $end_1 = strlen($arr[0])/2;
                $end_2 = strlen($arr[1])/2;

                for($x=0;$x<$end_1;$x++){

                    $arr[0][$x]="*";

                }

                for($v=0;$v<$end_2;$v++){

                    $arr[1][$v]="*";

                }

                $txt = $arr[0]."@".$arr[1];

            } // }else{

            return $txt;
        }


        function json_email($arr){

            $email_arr = explode(';',$arr);
            $email_arr = json_encode($email_arr);

            return $email_arr;


        }

        /*email_json_decode*/ 

        function email_json_decode($json){

            $email_arr=json_decode($json);
            $end=count($email_arr);

            // si solo 1
            if($end==1){
                $email = $email_arr[0];
            }else if($end>1){ // if($end==1){
                // si mas de 1
                $new_value="";
                for($e=1;$e<=$end;$e++){
                    if($e<$end){
                        $new_value.=$email_arr[$e-1].";";
                    }else{
                        $new_value.=$email_arr[$e-1];
                    }
                }// for($e=1;$e<=$end;$e++){
                $email=$new_value;
            }// }else if($end>1){

            return $email;
                
        } // function email_json_decode($json){



        function sort_by_orden ($a, $b) {
            return $a['orden'] - $b['orden'];
        }

        function bonos($tipo,$number,$id_cliente){

            global $DB;

            #### TENEMOS 3 OPCIONES DE $tipo
            #
            # c = compra, sumamos a comprados y a disponibles
            # r = resta, restamos a disponibles y sumamos a gastados
            # s = suma (devolucion) sumamos a disponibles y restamos a gastados

            // sacamos los datos
            $noches=$DB->select('noches','clientes','id='.$id_cliente);
            $data_bonos=json_decode($noches[f][0]['noches'],true);

            if($tipo==c){

                $arr_noches['disponibles'] = $data_bonos['disponibles']+$number;
                $arr_noches['usados'] = $data_bonos['usados'];
                $arr_noches['comprados'] = $data_bonos['comprados']+$number;

            }else if($tipo==r){

                $arr_noches['disponibles'] = $data_bonos['disponibles']-$number;
                $arr_noches['usados'] = $data_bonos['usados']+$number;
                $arr_noches['comprados'] = $data_bonos['comprados'];

            }else if($tipo==s){

                $arr_noches['disponibles'] = $data_bonos['disponibles']+$number;
                $arr_noches['usados'] = $data_bonos['usados']-$number;
                $arr_noches['comprados'] = $data_bonos['comprados'];

            }


            // EN TODOS LOS CASOS NOS SEGURAMOS DEVOLVER INT TODOS LOS VALORES
            $arr_noches['disponibles'] = (int) $arr_noches['disponibles'];
            $arr_noches['usados'] = (int) $arr_noches['usados'];
            $arr_noches['comprados'] = (int) $arr_noches['comprados'];

            $noches_json = json_encode($arr_noches);

            return $noches_json;
        
        } // function bonos($tipo,$number,$id_cliente){

        function noty($txt="Modificaci&oacute;n Realizada",$type="success"){

            script("$.notify({title: '',message: '".$txt."'},
                {type: '".$type."',allow_dismiss: true,newest_on_top: true});");

            //noty({text: 'texto', layout: 'topRight', type: 'success'});setTimeout('$.noty.closeAll()',3000);

        } // function noty

        function notify($txt="Modificaci&oacute;n Realizada",$type="success"){

            $icon = $type == 'success' ? 'fa fa-check' : 'fas fa-exclamation';
            script("
                var placementFrom = 'top';
                var placementAlign = 'right';
                var state = '".$type."';
                var content = {};

                content.message = '".$txt."';
                content.icon = '".$icon."';

                $.notify(content,{
                    type: state,
                    placement: {
                        from: placementFrom,
                        align: placementAlign
                    },
                    time: 1000,
                    delay: 3,
                });
            ");
        }

        function notify_js($txt="Modificaci&oacute;n Realizada",$type="success"){

            $icon = $type == 'success' ? 'fa fa-check' : 'fas fa-exclamation';
            
            echo "
                var placementFrom = 'top';
                var placementAlign = 'right';
                var state = '".$type."';
                var content = {};

                content.message = '".$txt."';
                content.icon = '".$icon."';

                $.notify(content,{
                    type: state,
                    placement: {
                        from: placementFrom,
                        align: placementAlign
                    },
                    time: 1000,
                    delay: 3,
                });
            ";
        }

        function noty_js($txt="Modificaci&oacute;n Realizada",$type="success"){

            $icon = $type == 'success' ? 'fa fa-check' : 'fas fa-exclamation';
            
            echo "
                var placementFrom = 'top';
                var placementAlign = 'right';
                var state = '".$type."';
                var content = {};

                content.message = '".$txt."';
                content.icon = '".$icon."';

                $.notify(content,{
                    type: state,
                    placement: {
                        from: placementFrom,
                        align: placementAlign
                    },
                    time: 1000,
                    delay: 3,
                });
            ";
        }

        function location($reload=0){

            if($reload==0){

                script('opener.location.reload();location.reload()');

            }else if($reload==1){

                script('opener.location.reload()');

            }else if($reload==2){

                script('location.reload()');
            }
            
        } // function location($reload=0){

        function mueve_saldo($importe,$tipo_operacion){

            # EL TIPO DE OPERACION PUEDE SER SUMA O RESTA
            #
            #
            #

            global $DB;

            $saldo_anterior = $DB->select('resultado','saldo_empresa','id=(select max(id) from saldo_empresa)');

            $resultado['saldo_anterior']=$saldo_anterior[f][0]['resultado'];

            if($tipo_operacion==1){

                $resultado['nuevo_saldo'] = $saldo_anterior[f][0]['resultado']+$importe;

            }else if($tipo_operacion==0){

                $resultado['nuevo_saldo'] = $saldo_anterior[f][0]['resultado']-$importe;

            }

            return $resultado;

        }

        function mueve_bonos_delegaciones($bonos,$tipo_operacion,$delegacion){

            # EL TIPO DE OPERACION PUEDE SER SUMA O RESTA
            #
            #
            #

            global $DB;

            $saldo_anterior = $DB->select('resultado','bonos_delegaciones','id=(select max(id) from bonos_delegaciones where id_delegacion='.$delegacion.')');

            $resultado['saldo_anterior']=$saldo_anterior[f][0]['resultado'];

            if($tipo_operacion==1){

                $resultado['nuevo_saldo'] = $saldo_anterior[f][0]['resultado']+$bonos;

            }else if($tipo_operacion==0){

                $resultado['nuevo_saldo'] = $saldo_anterior[f][0]['resultado']-$bonos;

            }

            return $resultado;

        }

        function array_orderby(){


            $args = func_get_args();
            $data = array_shift($args);

            foreach ($args as $n => $field) {

                if (is_string($field)) {
                    $tmp = array();
                    foreach ($data as $key => $row)
                        $tmp[$key] = $row[$field];
                    $args[$n] = $tmp;
                    }
                    
            }
            $args[] = &$data;
            call_user_func_array('array_multisort', $args);
            return array_pop($args);

            // ejemplo de uso 
            // $sorted = array_orderby($arr, 'volume', SORT_DESC, 'edition', SORT_ASC);
            // 
        } // function array_orderby(){


        function unique_multidim_array($array, $key) { 

            $temp_array = array(); 
            $i = 0; 
            $key_array = array(); 
            
            foreach($array as $val) { 
                if (!in_array($val[$key], $key_array)) { 
                    $key_array[$i] = $val[$key]; 
                    $temp_array[$i] = $val; 
                } 
                $i++; 
            } 
            return $temp_array; 

            // ejemplo de uso de la funcion
            // $arr = unique_multidim_array($arr,'key');
        }  // function unique_multidim_array($array, $key) { 

        function strposa($haystack, $needle) {

            if(!is_array($needle)) $needle = array($needle);

            $x=0;
            foreach($needle as $query) {

                // echo $query;
                if(strpos($haystack, $query) !== false && $x=0){ $x=1; alert($query); return true;} // stop on first true result
                
            }

            return false;

        } // function strposa($haystack, $needle, $offset=0) {


        function ordenar_por_fecha( $a, $b ) {

            return strtotime($a['fecha']) - strtotime($b['fecha']);
            // ordena por fecha un array multydimensional como los de los gastos de clx

            
        }



        function ConectarFTP($SERVER,$USER,$PASSWORD,$ruta){

            //Permite conectarse al Servidor FTP
            $id_ftp=ftp_connect($SERVER,21); //Obtiene un manejador del Servidor FTP
            ftp_login($id_ftp,$USER,$PASSWORD); //Se loguea al Servidor FTP
            ftp_pasv($id_ftp,true); //Establece el modo de conexión
            ftp_chdir($id_ftp,$ruta);
            //ftp_close($id_ftp);
            return $id_ftp; //Devuelve el manejador a la función

        }


        function datos_email($id_cliente){

            global $DB2;

            $return = $DB2->inner_join('c.nombre as cliente,
            d.nombre as delegacion,
            d.web,
            d.color_email,
            c.id_delegacion,
            c.identificador,
            c.comentario,
            d.email as remitente,
            c.email as destinatario','clientes as c inner join delegaciones as d',
            'c.id_delegacion = d.id and c.id='.$id_cliente,f);

            $return = $return[f][0];

            return $return;
        }

        function registrar_accion($id_usuario,$accion,$elemento=""){

            global $DB2;

            $DB2->query('insert into control_acciones 
                    (id_usuario,accion,fecha,elemento) 
                    values 
                    ('.$id_usuario.',"'.$accion.'","'.now().'","'.$elemento.'")');


        }

        function insertar_comentarios($id_editar,$comentario){

            try {
                
                if ($comentario=="") {
                    
                    throw new Exception("El comentario no puede estar vacio");
                    
                }

                global $DB2;

                $comentarios = $DB2->select('comentarios','comercios','id='.$id_editar);
                $old_coment = json_decode($comentarios[f][0]['comentarios'],true);


                if(count($old_coment)==0){

                    $new_coment[0]['usuario'] = $_SESSION['nombre_usuario'];
                    $new_coment[0]['fecha'] = date('d/m/Y H:i:s');
                    $new_coment[0]['comentario'] = $comentario;

                    $last_coment = json_encode_bbdd($new_coment);

                }else{

                    $new_index = count($old_coment) + 1;
                    $new_coment[$new_index]['usuario'] = $_SESSION['nombre_usuario'];
                    $new_coment[$new_index]['fecha'] = date('d/m/Y H:i:s');
                    $new_coment[$new_index]['comentario'] = $comentario;
                    $pre_last_coment = array_merge($old_coment,$new_coment);
                    

                    $last_coment = json_encode_bbdd($pre_last_coment);

                }

                $DB2->query("update comercios set comentarios = '".$last_coment."' where id=".$id_editar);

            } catch (Exception $e) {
                
                alert($e->getMessage());
            }

        }


        function filtro_url($url){

            if(trim($url) == ''){

                return false;

            }else{

                if (!filter_var($url, FILTER_VALIDATE_URL)) {

                    return false;
                }

                if (!preg_match("/\b(?:(?:https?|ftp|http):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|](\.)[a-z]{2}/i",$url)) {

                    return false;

                }else{

                    return true;
                }
            }

        } // function filtro_url($url){

        function instagram($id_comercio, $limit = 5 ){

            global $DB;
            # sacamos los datos que pertenecen al comercio
            $tk = $DB->select('token_instagram','comercios','id='.$id_comercio);
            $access = explode('.',$tk[f][0]['token_instagram']);

            $user_id       = $access[0];
            $access_token  = $tk[f][0]['token_instagram'];
            $item_resource = 'userid';
            $hashtag       = '';
            $limit         = $limit;

            if (!$user_id || !$access_token) {
                echo '<p class="alert alert-warning">NO HAY INFORMACIÓN COMPLETA</p>';
                return;
            }

            if( $item_resource == 'hashtag' && $hashtag!="") {

                $api = "https://api.instagram.com/v1/tags/". $hashtag  ."/media/recent/?access_token=" . $access_token . "&count=". $limit;

            } else {

                $api = "https://api.instagram.com/v1/users/". $user_id  ."/media/recent/?access_token=" . $access_token . "&count=". $limit;

            }

            if( ini_get('allow_url_fopen') ) {

                $images = @file_get_contents($api);
                @file_put_contents($cache_file, $images, LOCK_EX);

            } else {

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $api);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                $images = curl_exec($ch);
                curl_close($ch);

            }

            $json = json_decode($images);
            
            if(isset($json->data)) {
                return $json->data;
            }
            
            return array();
        }
            
        function saber_dia_semana($date) {

            $dias = array('','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
            $fecha = $dias[date('N', strtotime($date))];
            return $fecha;
        }

        function pintar_options_agenda(){

            $arr = array(
                    'primary'=>'Azul',
                    'danger'=>'Rojo',
                    // 'default'=>'Blanco',
                    'success'=>'Verde',
                    'warning'=>'Naranja'
                );

                foreach ($arr as $k => $v) {


                    echo '<option value="fc-'.$k.'"  
                                    data-content=" <div class=\'div_colores_c bg-'.$k.'\' style=\' margin-top: 3px !important;\'></div> <div style=\'display: inline-block; margin-bottom: 3px !important; \'>Punto '.$v.'</div>"> </option>
                        <option value="fc-'.$k.'-solid"  
                                        data-content=" <div class=\'div_colores bg-'.$k.'\' style=\' margin-top: 3px !important;\'></div> <div style=\'display: inline-block; margin-bottom: 3px !important; \'>'.$v.' Completo</div>"> </option>';
                    }

        }

        // ** ++ NF ++ ** //

        function nf($n){

            $n = number_format(($n/100),2,'.','');

            return $n;
        }
        
?>