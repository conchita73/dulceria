<?php header("Access-Control-Allow-Origin: *"); 
    session_start();
    date_default_timezone_set('Europe/Madrid');
    include 'functions/funciones.php';
    
    // if(isset($_COOKIE['soy_pedro'])){

    //     setcookie("soy_pedro", 1, time()+864000*365,"/");
    //     $_SESSION["soy_pedro"]=1;

    // }

    // if($_SERVER['REMOTE_ADDR']=="5.154.69.122" || $_SERVER['REMOTE_ADDR']=="185.178.136.16"){

    //     $_SESSION['ip_admin']=1;
    //     $_SESSION['Authenticated']=1;
    //     $_SESSION['id_web']=1;
    //     $_SESSION['id_usuario']=1;

    //     error_reporting(E_ALL | E_STRICT);

    // }else{

    //     error_reporting(0);

    // }


    if(!isset($_SESSION['id_web'])){

        if(isset($id_contrato)){

            $d = $DB->select('id_delegacion','contratos','id='.$id_contrato);
            $_SESSION['id_web'] = $d[f][0]['id_delegacion'];

        }else if(!isset($id_contrato)){

            script("location.href='accesos.php'");

        } // if(isset($id_contrato)){

    } // if(!isset($_SESSION['id_web'])){

    $DB->query('select * from delegaciones where id='.$_SESSION['id_web']);

    $datos=$DB->fetch();

    foreach ($datos as $key => $value) {
        
        // metemos en el array datos_web
        $datos_web[$key]=$value;

    }


?>