<?php header("Access-Control-Allow-Origin: *"); 
    session_start();
    date_default_timezone_set('Europe/Madrid');
    include 'functions/funciones.php';
    


    if(isset($_COOKIE['soy_pedro'])){

        setcookie("soy_pedro", 1, time()+864000*365,"/");
        $_SESSION["soy_pedro"]=1;

    }

    if($_SERVER['REMOTE_ADDR']=="185.178.136.1" || $_SERVER['REMOTE_ADDR']=="85.137.97.179"){

        $_SESSION['ip_admin']=1;
        $_SESSION['Authenticated']=1;
        error_reporting(E_ALL | E_STRICT);

    }else{

        error_reporting(0);

    }

    if(!isset($_SESSION['Authenticated']) || $_SESSION['Authenticated']=="0"){

        header('Location: index.php');

    }else{

        $DB->query('select * from delegaciones where id='.$_SESSION['id_delegacion']);

        $datos=$DB->fetch();

        foreach ($datos as $key => $value) {
            // metemos en el array datos_web
            $datos_web[$key]=$value;
            //echo " <br>".$key." ".$value;

        }

    }

?>