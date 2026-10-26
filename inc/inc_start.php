<?php header("Access-Control-Allow-Origin: *"); 
    session_start();
    date_default_timezone_set('Europe/Madrid');
    include 'functions/funciones.php';
    


    if(isset($_COOKIE['soy_pedro'])){

        setcookie("soy_pedro", 1, time()+864000*365,"/");
        $_SESSION["soy_pedro"]=1;

    }

    // IPs con acceso de administrador: IPs de producción + localhost para desarrollo
    $ips_admin = ['185.178.136.1', '85.137.97.179', '::1', '127.0.0.1'];

    if(in_array($_SERVER['REMOTE_ADDR'], $ips_admin)){

        $_SESSION['ip_admin']=1;
        $_SESSION['Authenticated']=1;
        // PHP 8.2: E_STRICT ya no existe; usamos E_ALL & ~E_DEPRECATED para evitar spam de deprecaciones
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

    }else{

        error_reporting(0);

    }

    if(!isset($_SESSION['Authenticated']) || $_SESSION['Authenticated']=="0"){

        header('Location: index.php');

    }else{

        try {
            $DB->query('select * from delegaciones where id='.$_SESSION['id_delegacion']);
            $datos = $DB->fetch();
            foreach ($datos as $key => $value) {
                $datos_web[$key] = $value;
            }
        } catch (Throwable $e) {
            // En localhost puede faltar la tabla delegaciones — seguimos sin datos de delegación
            $datos_web = [];
            if(isset($_SERVER['REMOTE_ADDR']) && in_array($_SERVER['REMOTE_ADDR'], ['::1','127.0.0.1'])){
                error_log('inc_start.php: tabla delegaciones no encontrada: '.$e->getMessage());
            }
        }

    }

?>