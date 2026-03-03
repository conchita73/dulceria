<?php header("Access-Control-Allow-Origin: *");
@session_start();
if($_SERVER['REMOTE_ADDR']=="5.154.69.122" || $_SERVER['REMOTE_ADDR']=="185.178.136.16"){

    $_SESSION['ip_admin']=1;
    error_reporting(E_ALL);

}else{
    
    error_reporting(0);
}


date_default_timezone_set('Europe/Madrid');
ini_set('memory_limit', '9999M');
ini_set('max_execution_time', '120');
set_time_limit(120);
include 'funciones.php';
include 'phpMailer/PHPMailer.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


######
# suma_producto_modal -> sacamos los datos del producto en la ventana modal de la web
# suma_producto_bandeja -> sacamos los datos del producto en la ventana modal de la web para bandejas de pasteles
# suma_producto_pastas -> sacamos los datos del producto en la ventana modal de la web para las pastas y magdalenas
# suma_producto_bolleria -> sacamos los datos del producto en la ventana modal de la web para la bolleria
# suma_producto_tartas_galletas -> sacamos los datos del producto en la ventana modal de la web para las tartas de galletas
# suma_producto_pastelon -> sacamos los datos de los pastelones en la vebntana modal para la web
# suma_producto_tartas_personalizadas -> sacamos la configuración de las tartas personalizadas a la modal de la web
# suma_producto_tartas_predeterminadas -> sacamos los datos del producto en la venta pra las tartas predeterminadas
# cookies_bandejas_pasteles -> sistema de cookies de las bandejas de pasteles.
# resta_producto_bandeja -> resta el producto de la bandeja y de las cookies
# cookies_carrito -> sistema de cookies de carrito.
# recuperar_carrito -> recuperamos y montamos el carrito a través de las cookies
# borrar_linea_carrito -> borramos líneas del carrito y lo volvemo a montar
# subir_imagen_tartas -> subimos la imagen de una tarta por parte del cliente
# login_carrito -> logear usuario en el carrito para acceder con los datos
# finalizar_compra -> Finalizar la compra.
# reintentar_pago -> reintentamos el pago en una compra fallida
# login_web -> logear a la zona privada
# loguot_web -> logout de la zona privada de cliente
# modifica_pass -> modifca la conseña en la zona privada de cliente
# modifica_datos -> modifica los datos de los clientes de la zona privada
# insertar_fechas_especiales -> insertar fechas especiales
# modifica_fechas -> modificar fechas especiales.
# borrar_fecha_especial -> borrar una fecha especia personalizada 
# ver_pedido -> ver los pedidos de la zona privada
# tipo_entrega -> modificar el tipo de entrega en el carrito de la compra recalculando gastos de envío y precio final en su caso.
# email_recupera_pass -> proceso de recuperación de contraseña

if(isset($suma_producto_modal)){  

    $dp = $DB->select('*','productos_web','id = '.$id_producto);
    $dp = $dp[f][0];

    if($dp['fotos'] == ''){

        $ft = '/images/logo_argentina_min.png';

    }else{

        $arr_f = json_decode($dp['fotos'],true);
        $ft = is_array($arr_f) && isset($arr_f[0]) ? $arr_f[0] : '/images/logo_argentina_min.png';

    }


    echo '  <div class="col-lg-5 divide-right">
                <div class="row">
                    <div class="col-md-6">
                           <img src="'.$ft.'" alt="img">
                    </div>
                    <div class="col-md-6 mb-2 mb-md-0">
                        <h4 class="product-name">'.utf8_encode($dp['nombre_producto']).'</h4>';

    if($id_familia == 4){

        echo '          <h5 class="price">'.nf($dp['pvp']).' &euro;/Unidad</h5>
                        <label class="mt-2">Selecciona Unidades</label>
                        <select name="unidades_pasteles" class="form-control" id_producto="'.$id_producto.'">';

                            for ($i=6; $i <= 20; $i++) { 
                                
                                echo '<option value="'.$i.'">'.$i.'</option>';

                            }
                            
                            
        echo '          </select>
                        <button type="button" name="button_pasteles" class="btn btn-primary mt-2 btn--md">Añadir al Carro</button>';


    }

    if(!isset($_COOKIE['carrito'])){

        $n_p = 0;
        $pvp = 0;
        $g_e = 399;
        $total = 399;

    }else{

    }


    echo '          </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="modal-cart-content">
                            <p class="cart-products-count"><strong>Pedido mínimo de 5.00 &euro;. Gastos de envío Gratis a partir de 12.00 &euro;.</strong></p>

                            <p class="cart-products-count">Hay '.$n_p.' productos en tu carro.</p>

                            <p><strong>Total Productos:</strong>&nbsp;'.nf($pvp).' &euro;</p>
                            <p><strong>Total Envío:</strong>&nbsp;'.nf($g_e).' &euro;</p>
                            <p><strong>Total Carrito:</strong>&nbsp;'.nf($total).' &euro;</</p>
                    <div class="cart-content-btn">
                        <button type="button" class="btn theme-btn--dark1 btn--md mt-4"
                                    data-dismiss="modal">Continuar Comprando</button>
                        <button class="btn theme-btn--dark1 btn--md mt-4"><i
                                        class="ion-checkmark-round"></i>Finalizar Compra</button>
                    </div>
                </div>
            </div>';


// if(isset($suma_producto_modal)){  
}else if(isset($suma_producto_bandeja)){  

    $dp = $DB->select('*','productos_web','id = '.$id_producto);
    $dp = $dp[f][0];

    if($dp['fotos'] == ''){

        $ft = '/images/logo_argentina_min.png';

    }else{

        $arr_f = json_decode($dp['fotos'],true);
        $ft = is_array($arr_f) && isset($arr_f[0]) ? $arr_f[0] : '/images/logo_argentina_min.png';

    }


    echo '  <div class="col-12">
                <div class="row">
                    <div class="col-md-12 text-center mb-2">
                        <h4 class="product-name">'.utf8_encode($dp['nombre_producto']).'</h4>
                    </div>
                    <div class="col-md-12">
                           <img src="'.$ft.'" alt="img">
                    </div>
                    <div class="col-md-12 mb-2 mb-md-0">
                      <h5 class="price">'.nf($dp['pvp']).' &euro;/Unidad</h5>
                        <label class="mt-2">Selecciona Unidades</label>
                        <select name="unidades_pasteles" class="form-control" id_producto="'.$id_producto.'" pvp="'.$dp['pvp'].'">';

                            for ($i=1; $i <= 20; $i++) { 
                                
                                echo '<option value="'.$i.'">'.$i.'</option>';

                            }
                            
                            
        echo '          </select>
                        <button type="button" name="button_pasteles" class="btn btn-primary btn-block mt-2 btn--md">Añadir a la bandeja</button>
                    </div>
                </div>
            </div>';

// }else if(isset($suma_producto_bandeja)){  
}else if(isset($suma_producto_pastas)){ 

    $dp = $DB->select('*','productos_web','id = '.$id_producto);
    $dp = $dp[f][0];

    if($dp['fotos'] == ''){

        $ft = '/images/logo_argentina_min.png';

    }else{

        $arr_f = json_decode($dp['fotos'],true);
        $ft = is_array($arr_f) && isset($arr_f[0]) ? $arr_f[0] : '/images/logo_argentina_min.png';

    }


    echo '  <div class="col-12">
                <div class="row">
                    <div class="col-md-12 text-center mb-2">
                        <h4 class="product-name">'.utf8_encode($dp['nombre_producto']).'</h4>
                    </div>
                    <div class="col-md-12">
                           <img src="'.$ft.'" alt="img">
                    </div>
                    <div class="col-md-12 mb-2 mb-md-0">
                      <h5 class="price">'.nf($dp['pvp']).' &euro;/Docena</h5>
                        <label class="mt-2">Selecciona Docenas</label>
                        <select name="unidades_pastas" class="form-control" id_producto="'.$id_producto.'" pvp="'.$dp['pvp'].'">';

                            for ($i=1; $i <= 5; $i++) { 
                                
                                $s = $i > 1 ? 's' : '';
                                echo '<option value="'.$i.'">'.$i.' Docena'.$s.'</option>';

                            }
                            
                            
        echo '          </select>
                        <button type="button" name="button_pastas" class="btn btn-primary btn-block mt-2 btn--md">Añadir al Carrito</button>
                    </div>
                </div>
            </div>';

// }else if(isset($suma_producto_pastas)){  
}else if(isset($suma_producto_bolleria)){  

        $dp = $DB->select('*','productos_web','id = '.$id_producto);
    $dp = $dp[f][0];

    if($dp['fotos'] == ''){

        $ft = '/images/logo_argentina_min.png';

    }else{

        $arr_f = json_decode($dp['fotos'],true);
        $ft = is_array($arr_f) && isset($arr_f[0]) ? $arr_f[0] : '/images/logo_argentina_min.png';

    }


    echo '  <div class="col-12">
                <div class="row">
                    <div class="col-md-12 text-center mb-2">
                        <h4 class="product-name">'.utf8_encode($dp['nombre_producto']).'</h4>
                    </div>
                    <div class="col-md-12">
                           <img src="'.$ft.'" alt="img">
                    </div>
                    <div class="col-md-12 mb-2 mb-md-0">
                      <h5 class="price">'.nf($dp['pvp']).' &euro;/Unidad</h5>
                        <label class="mt-2">Selecciona Unidades</label>
                        <select name="unidades_bolleria" class="form-control" id_producto="'.$id_producto.'" pvp="'.$dp['pvp'].'">';

                            for ($i=1; $i <= 10; $i++) { 
                                
                                $s = $i > 1 ? 'es' : '';
                                echo '<option value="'.$i.'">'.$i.' Unidad'.$s.'</option>';

                            }
                            
                            
        echo '          </select>
                        <button type="button" name="button_bolleria" class="btn btn-primary btn-block mt-2 btn--md">Añadir al Carrito</button>
                    </div>
                </div>
            </div>';

// }else if(isset($suma_producto_bolleria)){  
}else if(isset($suma_producto_tartas_galletas)){  

    $dp = $DB->select('*','productos_web','id = '.$id_producto);
    $dp = $dp[f][0];

    if($dp['fotos'] == ''){

        $ft = '/images/logo_argentina_min.png';

    }else{

        $arr_f = json_decode($dp['fotos'],true);
        $ft = is_array($arr_f) && isset($arr_f[0]) ? $arr_f[0] : '/images/logo_argentina_min.png';

    }


    echo '  <div class="col-12">
                <div class="row">
                    <div class="col-md-12 text-center mb-2">
                        <h4 class="product-name">'.utf8_encode($dp['nombre_producto']).'</h4>
                    </div>
                    <div class="col-md-12">
                           <img src="'.$ft.'" alt="img">
                    </div>
                    <div class="col-md-12 mb-2 mb-md-0">
                      <h5 class="price">Desde '.nf($dp['pvp']).' &euro;</h5>';

                    if($dp['ingredientes'] != '' && $dp['ingredientes'] != 'null'){

                        $arr_i = json_decode($dp['ingredientes'] , true);
                        echo '<ul class="list-group">
                                <li class="list-group-item list-group-item-action active">Ingredientes</li>';
                        foreach ($arr_i as $k => $v) {

                            echo '<li class="list-group-item">'.$v.'</li>';
                            
                        }
                        echo '</ul>';

                    }


    echo '              <label class="mt-2">Selecciona Porciones</label>
                        <select name="porciones_tartas_galletas" class="form-control" id_producto="'.$id_producto.'" >';

                            $pvp = array(
                                        1=>array(
                                            'pvp'=>1100,
                                            'porciones'=>12,
                                            'tamano'=>1
                                            ),
                                        2=>array(
                                            'pvp'=>1600,
                                            'porciones'=>20,
                                            'tamano'=>2
                                            ),
                                        3=>array(
                                            'pvp'=>2100,
                                            'porciones'=>35,
                                            'tamano'=>3
                                            ),
                                        4=>array(
                                            'pvp'=>3600,
                                            'porciones'=>49,
                                            'tamano'=>4
                                            ),
                                        );
                            for ($i=1; $i <= 4; $i++) { 

                                echo '<option value="'.$i.'" pvp="'.$pvp[$i]['pvp'].'" porciones="'.$pvp[$i]['porciones'].'" tamano="'.$pvp[$i]['tamano'].'">'.$pvp[$i]['porciones'].' Porciones ('.nf($pvp[$i]['pvp']).' &euro;)</option>';

                            }
                            
                            
        echo '          </select>
                        <label class="mt-2">Mesaje de la tarta (Opcional):</label>
                        <textarea name="mensaje_tartas_galletas" class="form-control"></textarea>
                        <label class="mt-2">Comentarios Adicionales (Opcional):</label>
                        <textarea name="comentarios_tartas_galletas" class="form-control"></textarea>
                        <label class="mt-2">¿Añadir Foto? (Suplemento de 4€):</label><br>
                        <small class="text-danger">* La foto la podrás subir en el carrito.</small>
                        <select name="foto_tartas_galletas" class="form-control">
                            <option value="0">No</option>
                            <option value="1">Sí</option>
                        </select>
                        <button type="button" name="button_tartas_galletas" class="btn btn-primary btn-block mt-2 btn--md">Añadir al Carrito</button>
                    </div>
                </div>
            </div>';

// }else if(isset($suma_producto_tartas_galletas)){   
}else if(isset($suma_producto_pastelon)){ 

    $dp = $DB->select('*','productos_web','id = '.$id_producto);
    $dp = $dp[f][0];

    if($dp['fotos'] == ''){

        $ft = '/images/logo_argentina_min.png';

    }else{

        $arr_f = json_decode($dp['fotos'],true);
        $ft = is_array($arr_f) && isset($arr_f[0]) ? $arr_f[0] : '/images/logo_argentina_min.png';

    }


    echo '  <div class="col-12">
                <div class="row">
                    <div class="col-md-12 text-center mb-2">
                        <h4 class="product-name">'.utf8_encode($dp['nombre_producto']).'</h4>
                    </div>
                    <div class="col-md-12">
                           <img src="'.$ft.'" alt="img">
                    </div>
                    <div class="col-md-12 mb-2 mb-md-0">
                      <h5 class="price">Desde '.nf($dp['pvp']).' &euro;</h5>';

                    if($dp['ingredientes'] != '' && $dp['ingredientes'] != 'null'){

                        $arr_i = json_decode($dp['ingredientes'] , true);
                        echo '<ul class="list-group">
                                <li class="list-group-item list-group-item-action active">Ingredientes</li>';
                        foreach ($arr_i as $k => $v) {

                            echo '<li class="list-group-item">'.$v.'</li>';
                            
                        }
                        echo '</ul>';

                    }


    echo '              <label class="mt-2">Selecciona Porciones</label>
                        <select name="porciones_pastelon" class="form-control" id_producto="'.$id_producto.'" >';

                            $pvp = array(
                                        1=>array(
                                            'pvp'=>1300,
                                            'porciones'=>10,
                                            'tamano'=>1
                                            ),
                                        2=>array(
                                            'pvp'=>1600,
                                            'porciones'=>15,
                                            'tamano'=>2
                                            ),
                                        3=>array(
                                            'pvp'=>2000,
                                            'porciones'=>20,
                                            'tamano'=>3
                                            )
                                        );
                            for ($i=1; $i <= 3; $i++) { 

                                echo '<option value="'.$i.'" pvp="'.$pvp[$i]['pvp'].'" porciones="'.$pvp[$i]['porciones'].'" tamano="'.$pvp[$i]['tamano'].'">'.$pvp[$i]['porciones'].' Porciones ('.nf($pvp[$i]['pvp']).' &euro;)</option>';

                            }
                            
                            
        echo '          </select>
                        <label class="mt-2">Mesaje de la tarta (Opcional):</label>
                        <textarea name="mensaje_pastelon" class="form-control"></textarea>
                        <label class="mt-2">Comentarios Adicionales (Opcional):</label>
                        <textarea name="comentarios_pastelon" class="form-control"></textarea>
                        <label class="mt-2">¿Añadir Foto? (Suplemento de 4€):</label><br>
                        <small class="text-danger">* La foto la podrás subir en el carrito.</small>
                        <select name="foto_pastelon" class="form-control">
                            <option value="0">No</option>
                            <option value="1">Sí</option>
                        </select>
                        <button type="button" name="button_pastelon" class="btn btn-primary btn-block mt-2 btn--md">Añadir al Carrito</button>
                    </div>
                </div>
            </div>';

// }else if(isset($suma_producto_pastelon)){ 
}else if(isset($suma_producto_tartas_personalizadas)){ 

    $dp = $DB->select('*','productos_web','id = '.$id_producto);
    $dp = $dp[f][0];

    if($dp['fotos'] == ''){

        $ft = '/images/logo_argentina_min.png';

    }else{

        $arr_f = json_decode($dp['fotos'],true);
        $ft = is_array($arr_f) && isset($arr_f[0]) ? $arr_f[0] : '/images/logo_argentina_min.png';

    }


    echo '  <div class="col-12">
                <div class="row">
                    <div class="col-md-12 text-center mb-2">
                        <h4 class="product-name">'.utf8_encode($dp['nombre_producto']).'</h4>
                    </div>
                    <div class="col-md-12">
                           <img src="'.$ft.'" alt="img">
                    </div>
                    <div class="col-md-12 mb-2 mb-md-0">
                      <h5 class="price">Desde '.nf($dp['pvp']).' &euro;</h5>';

                    if($dp['ingredientes'] != '' && $dp['ingredientes'] != 'null'){

                        $arr_i = json_decode($dp['ingredientes'] , true);
                        echo '<ul class="list-group">
                                <li class="list-group-item list-group-item-action active">Ingredientes</li>';
                        foreach ($arr_i as $k => $v) {

                            echo '<li class="list-group-item">'.$v.'</li>';
                            
                        }
                        echo '</ul>';

                    }


    echo '              <label class="mt-2">Selecciona Porciones</label>
                        <select name="porciones_tartas_personalizadas" class="form-control" id_producto="'.$id_producto.'" >';

                            $pvp = array(
                                        0=>array(
                                            'pvp'=>800,
                                            'porciones'=>4,
                                            'tamano'=>0                                            
                                        ),
                                        1=>array(
                                            'pvp'=>1100,
                                            'porciones'=>10,
                                            'tamano'=>1
                                            ),
                                        2=>array(
                                            'pvp'=>1400,
                                            'porciones'=>15,
                                            'tamano'=>2
                                            ),
                                        3=>array(
                                            'pvp'=>1900,
                                            'porciones'=>20,
                                            'tamano'=>3
                                            ),
                                        4=>array(
                                            'pvp'=>2500,
                                            'porciones'=>30,
                                            'tamano'=>4
                                            )
                                        );
                            for ($i=0; $i <= 4; $i++) { 

                                echo '<option value="'.$i.'" pvp="'.$pvp[$i]['pvp'].'" porciones="'.$pvp[$i]['porciones'].'">'.$pvp[$i]['porciones'].' Porciones ('.nf($pvp[$i]['pvp']).' &euro;)</option>';

                            }

                            
        echo '          </select>
                        <label class="mt-2 personalizada " for="subject">Capa Inferior:</label>
                        <select name="capa_inferior_personalizada" class="personalizada  custom-select form-control">
                            <option value="Crema">Crema</option>
                            <option value="Chocolate">Chocolate</option>
                            <option value="Nata">Nata</option>
                            <option value="Trufa">Trufa</option>
                            <option value="Fresa">Fresa</option>
                            <option value="Lim&oacute;n">Lim&oacute;n</option>
                            <option value="Choc. Kinder">Choc. Kinder</option>
                            <option value="Moca">Moca</option>
                            <option value="Yema">Yema</option>
                            <option value="Oreo">Oreo</option>
                            <option value="Ferrero">Ferrero</option>
                        </select>
                        <label class="mt-2 personalizada " for="subject">Capa Media:</label>
                        <select name="capa_media_personalizada" class="personalizada  custom-select form-control">
                            <option value="Crema">Crema</option>
                            <option value="Chocolate">Chocolate</option>
                            <option value="Nata">Nata</option>
                            <option value="Trufa">Trufa</option>
                            <option value="Fresa">Fresa</option>
                            <option value="Lim&oacute;n">Lim&oacute;n</option>
                            <option value="Choc. Kinder">Choc. Kinder</option>
                            <option value="Moca">Moca</option>
                            <option value="Yema">Yema</option>
                            <option value="Oreo">Oreo</option>
                            <option value="Ferrero">Ferrero</option>
                        </select>
                        <label class="mt-2 personalizada " for="subject">Capa Superior:</label>
                        <select name="capa_superior_personalizada" class="personalizada custom-select  form-control">
                            <option value="Crema">Crema</option>
                            <option value="Chocolate">Chocolate</option>
                            <option value="Nata">Nata</option>
                            <option value="Trufa">Trufa</option>
                            <option value="Fresa">Fresa</option>
                            <option value="Lim&oacute;n">Lim&oacute;n</option>
                            <option value="Choc. Kinder">Choc. Kinder</option>
                            <option value="Moca">Moca</option>
                            <option value="Yema">Yema</option>
                            <option value="Oreo">Oreo</option>
                            <option value="Ferrero">Ferrero</option>
                        </select>
                        <label class="mt-2 personalizada " for="subject">Borde Exterior:</label>
                        <select name="borde_exterior_personalizada" class="personalizada custom-select  form-control">
                            <option value="Merengue">Merengue</option>
                            <option value="Sin Borde">Sin Borde</option>
                            <option value="Nata">Nata (Suplemento de 3€)</option>
                            <option value="Trufa">Trufa (Suplemento de 3€)</option>
                            <option value="Moca">Moca (Suplemento de 3€)</option>
                            <option value="Fid. Chocolate">Fid. Chocolate (Suplemento de 3€)</option>
                            <option value="Almendras">Almendras (Suplemento de 3€)</option>
                            <option value="Yema">Yema (Suplemento de 3€)</option>
                        </select>
                        <label class="mt-2">Mesaje de la tarta (Opcional):</label>
                        <textarea name="mensaje_tartas_personalizadas" class="form-control"></textarea>
                        <label class="mt-2">Comentarios Adicionales (Opcional):</label>
                        <textarea name="comentarios_tartas_personalizadas" class="form-control"></textarea>
                        <label class="mt-2">¿Añadir Foto? (Suplemento de 4€):</label><br>
                        <small class="text-danger">* La foto la podrás subir en el carrito.</small>
                        <select name="foto_tartas_personalizadas" class="form-control">
                            <option value="0">No</option>
                            <option value="1">Sí</option>
                        </select>
                        <button type="button" name="button_tartas_personalizadas" class="btn btn-primary btn-block mt-2 btn--md">Añadir al Carrito</button>
                    </div>
                </div>
            </div>';

// }else if(isset($suma_producto_tartas_personalizadas)){ 
}else if(isset($suma_producto_tartas_predeterminadas)){ 

    $dp = $DB->select('*','productos_web','id = '.$id_producto);
    $dp = $dp[f][0];

    if($dp['fotos'] == ''){

        $ft = '/images/logo_argentina_min.png';

    }else{

        $arr_f = json_decode($dp['fotos'],true);
        $ft = is_array($arr_f) && isset($arr_f[0]) ? $arr_f[0] : '/images/logo_argentina_min.png';

    }


    echo '  <div class="col-12">
                <div class="row">
                    <div class="col-md-12 text-center mb-2">
                        <h4 class="product-name">'.utf8_encode($dp['nombre_producto']).'</h4>
                    </div>
                    <div class="col-md-12">
                           <img src="'.$ft.'" alt="img">
                    </div>
                    <div class="col-md-12 mb-2 mb-md-0">
                      <h5 class="price">'.nf($dp['pvp']).' &euro;/Tarta</h5>';

                    if($dp['ingredientes'] != '' && $dp['ingredientes'] != 'null'){

                        $arr_i = json_decode($dp['ingredientes'] , true);
                        echo '<ul class="list-group">
                                <li class="list-group-item list-group-item-action active">Ingredientes</li>';
                        foreach ($arr_i as $k => $v) {

                            echo '<li class="list-group-item">'.$v.'</li>';
                            
                        }
                        echo '</ul>';

                    }


    echo '              <label class="mt-2">¿Cuantas tartas?</label>
                        <select name="numero_tartas_predeterminadas" class="form-control" id_producto="'.$id_producto.'" >';

                            
                            for ($i=1; $i <= 5; $i++) { 

                                $s = $i > 1 ? 's' : '';
                                echo '<option value="'.$i.'" pvp="'.($dp['pvp']*$i).'">'.$i.' Tarta'.$s.' - '.nf($dp['pvp']*$i).' &euro;</option>';

                            }
                            
                            
        echo '          </select>
                        <label class="mt-2">Mesaje de la tarta (Opcional):</label>
                        <textarea name="mensaje_tartas_predeterminadas" class="form-control"></textarea>
                        <label class="mt-2">Comentarios Adicionales (Opcional):</label>
                        <textarea name="comentarios_tartas_predeterminadas" class="form-control"></textarea>
                        <label class="mt-2">¿Añadir Foto? (Suplemento de 4€):</label><br>
                        <small class="text-danger">* La foto la podrás subir en el carrito.</small>
                        <select name="foto_tartas_predeterminadas" class="form-control">
                            <option value="0">No</option>
                            <option value="1">Sí</option>
                        </select>
                        <button type="button" name="button_tartas_predeterminadas" class="btn btn-primary btn-block mt-2 btn--md">Añadir al Carrito</button>
                    </div>
                </div>
            </div>';


// }else if(isset($suma_producto_tartas_predeterminadas)){  
}else if(isset($cookies_bandeja)){  

    if(!isset($_COOKIE['bandeja'])){

        $key = 0;
        $arr[0] = array(
                    'id_producto'=>$id_producto,
                    'id_familia'=>$id_familia,
                    'pvp'=>$pvp,
                    'cantidad'=>$cantidad
                    );

        $val = json_encode($arr);
        setcookie('bandeja', $val, time()+864000*365,"/");
        $_COOKIE['bandeja']=$val;

        $und = $cantidad;
        $pvp = $pvp*$cantidad;

    }else if(isset($_COOKIE['bandeja'])){

        $key = count($_COOKIE['bandeja']);
        $arr[$key] = array(
                    'id_producto'=>$id_producto,
                    'id_familia'=>$id_familia,
                    'pvp'=>$pvp,
                    'cantidad'=>$cantidad
                    );

        $arc = json_decode($_COOKIE['bandeja'],true);
        $und = $cantidad;
        $pvp = $pvp*$cantidad;
        foreach ($arc as $k => $v) {
            
            $und += $v['cantidad'];
            $pvp += $v['pvp']*$v['cantidad'];
        }
        $arf = array_merge($arc,$arr);
        $val = json_encode($arf);
        setcookie('bandeja', $val, time()+864000*365,"/");
        $_COOKIE['bandeja']=$val;

        ## tengo que sacar ahora las variables del precio y las unidades

    }

    $p = $DB->select('*','productos_web','id = '.$id_producto);
    $p = $p[f][0];
    $btn_carrito = '';
    if($und >= 6){

        $btn_carrito = '$("#btn_carrito_pasteles").removeClass("d-none")';

    }
    script('$("#bandeja").append(\'<div class="col-md-6" style="padding : 2px!important;"><div class="alert alert-info alert-dismissible fade show mt-3" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" name="quitar_pasteles" id_linea="'.$key.'"><span aria-hidden="true">&times;</span></button><b>Producto</b>: '.utf8_encode($p['nombre_producto']).'<br><b>Precio</b> : '.number_format(($p['pvp']/100),2,'.','').' &euro;/Unidad<br><b>Unidades</b>: '.$cantidad.' <br><b>Total </b>: '.number_format(($p['pvp']/100)*$cantidad,2,'.','').' &euro;<input type="hidden" id_producto="'.$id_producto.'" pvp="'.$p['pvp'].'" cantidad="'.$cantidad.'" name="producto_bandeja"></div></div>\');
        $("#mensaje_bandeja").html("Contenido de tu bandeja ('.$und.' Unidades)<br>Total : '.nf($pvp).' &euro;");
        $("#add-to-bandeja_pasteles").modal("hide");
        '.$btn_carrito);


// }else if(isset($cookies_bandeja)){ 
}else if(isset($resta_producto_bandeja)){ 

    # abrir el array de las cookies
    $arr = json_decode($_COOKIE['bandeja'],true);
    # sumarlo completo exluyendo la key que queremos quitar y montando un nuevo arr 
    $n_arr = array();
    $key = 0;
    $und = 0;
    $pvp = 0;
    $div_carro = '';
    foreach ($arr as $k => $v) {
        
        if($k != $id_linea){

            $p = $DB->select('*','productos_web','id = '.$v['id_producto']);
            $p = $p[f][0];
            $und += $v['cantidad'];
            $pvp += $v['pvp']*$v['cantidad'];
            $div_carro .= '<div class="col-md-6" style="padding : 2px!important;"><div class="alert alert-info alert-dismissible fade show mt-3" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" name="quitar_pasteles" id_linea="'.$key.'"><span aria-hidden="true">&times;</span></button><b>Producto</b>: '.utf8_encode($p['nombre_producto']).'<br><b>Precio</b> : '.number_format(($p['pvp']/100),2,'.','').' &euro;/Unidad<br><b>Unidades</b>: '.$v['cantidad'].' <br><b>Total </b>: '.number_format(($p['pvp']/100)*$v['cantidad'],2,'.','').' &euro;<input type="hidden" id_producto="'.$v['id_producto'].'" pvp="'.$p['pvp'].'" cantidad="'.$v['cantidad'].'" name="producto_bandeja"></div></div>';
            $n_arr[$key] = array(
                    'id_producto'=>$v['id_producto'],
                    'id_familia'=>$v['id_familia'],
                    'pvp'=>$v['pvp'],
                    'cantidad'=>$v['cantidad']
                    );
            $key ++;


        }
        
    }

    $val = json_encode($n_arr);
    setcookie('bandeja', $val, time()+864000*365,"/");
    $_COOKIE['bandeja']=$val;

    if($und < 6){

        $btn_carrito = '$("#btn_carrito_pasteles").addClass("d-none")';

    }
    # modificar los carteles y en caso de que sean menos de 6 unidades quitarlo del cartel
    script('
            $("#bandeja").html(\''.$div_carro.'\');
            $("#mensaje_bandeja").html("Contenido de tu bandeja ('.$und.' Unidades)<br>Total : '.nf($pvp).' &euro;");
            '.$btn_carrito
            );

// }else if(isset($resta_producto_bandeja)){ 
}else if(isset($cookies_carrito)){ 

    $extras = '';
    $li_producto = '';
    $vaciar_carro = '';
    if(!isset($_COOKIE['carrito'])){

        $vaciar_carro = '$("#ul_carrito").html("");';
        if($id_familia == 4){

            $unds = 0;
            $arr = json_decode($_COOKIE['bandeja'],true);
            $list = '';
            foreach ($arr as $k => $v) {

                $pvp_productos += $v['pvp']*$v['cantidad'];
                $unds += $v['cantidad'];
                $dp = $DB->select('nombre_producto','productos_web','id = '.$v['id_producto']);
                $list .= '<br><b>'.utf8_encode($dp[f][0]['nombre_producto']).'</b><br> '.$v['cantidad'].' Unidades x '.nf($v['pvp']).' &euro; - '.nf($v['pvp']*$v['cantidad']).' &euro;';

            }
            # aqui tengo que eliminar la bandeaj completa y volver a poner el cartel de que esta vacia.
            

            $li_producto .= '<li>
                                <a href="#" class="image">
                                    <img src="/images/bandeja_pasteles_surtidos.jpg" alt="Cart product Image">
                                </a>
                                <div class="content">1 Bandeja Pasteles Surtidos ('.$unds.' Unidades)
                                    '.$list.'
                                    <a href="#" class="remove" name="borrar_linea_carrito" key="0">×</a>
                                </div>
                            </li>';

            $arr_c[0] = array(
                            'id_familia'=>$id_familia,
                            'productos'=>$arr
                            ); 
            # vaciamos la bandeja y eliminamos la cookie
            $vaciar_carro .= '
                            $("#bandeja").html("");
                            $("#mensaje_bandeja").html("Tu bandeja esta vacía, selecciona mas de 6 unidades de nuestro pasteles y podrás añadirlo al carrito");
                            $("#btn_carrito_pasteles").addClass("d-none");
                            ';
            setcookie('bandeja','',time()-100,"/");

        }else if($id_familia == 5){ // if($id_familia == 4){


            $dp = $DB->select('nombre_producto,fotos','productos_web','id = '.$id_producto);
            $dp = $dp[f][0];

            if($dp['fotos'] == ''){

                $ft = '/images/logo_argentina_min.png';

            }else{

                $arr_f = json_decode($dp['fotos'],true);
                $ft = is_array($arr_f) && isset($arr_f[0]) ? $arr_f[0] : '/images/logo_argentina_min.png';

            }

            $pvp_productos += $pvp*$unidades;
            $li_producto .= '<li>
                                <a href="#" class="image">
                                    <img src="'.$ft.'" alt="Cart product Image">
                                </a>
                                <div class="content">
                                    <b>'.utf8_encode($dp['nombre_producto']).'</b> <br>
                                    '.$unidades.' x '.nf($pvp).' &euro; - '.nf($pvp*$unidades).' &euro;
                                    <a href="#" class="remove" name="borrar_linea_carrito" key="0">×</a>
                                </div>
                            </li>';
            $arr = array(
                        "id_producto"=> $id_producto,
                        "pvp"=> $pvp,
                        "unidades"=> $unidades
                        );
                        
            $arr_c[0] = array(
                            'id_familia'=>$id_familia,
                            'productos'=>$arr
                            );

            $extras = '$("#add-to-bandeja_pastas").modal("hide")';

            # una vez que esten los productos añadidos, tenemos que cerrar la modal
            # add-to-bandeja_pastas

        }else if($id_familia == 10){ // if($id_familia == 5){


            $dp = $DB->select('nombre_producto,fotos','productos_web','id = '.$id_producto);
            $dp = $dp[f][0];

            if($dp['fotos'] == ''){

                $ft = '/images/logo_argentina_min.png';

            }else{

                $arr_f = json_decode($dp['fotos'],true);
                $ft = is_array($arr_f) && isset($arr_f[0]) ? $arr_f[0] : '/images/logo_argentina_min.png';

            }

            $pvp_productos += $pvp*$unidades;
            $li_producto .= '<li>
                                <a href="#" class="image">
                                    <img src="'.$ft.'" alt="Cart product Image">
                                </a>
                                <div class="content">
                                    <b>'.utf8_encode($dp['nombre_producto']).'</b> <br>
                                    '.$unidades.' x '.nf($pvp).' &euro; - '.nf($pvp*$unidades).' &euro;
                                    <a href="#" class="remove" name="borrar_linea_carrito" key="0">×</a>
                                </div>
                            </li>';
            $arr = array(
                        "id_producto"=> $id_producto,
                        "pvp"=> $pvp,
                        "unidades"=> $unidades
                        );
                        
            $arr_c[0] = array(
                            'id_familia'=>$id_familia,
                            'productos'=>$arr
                            );

            $extras = '$("#add-to-bandeja_bolleria").modal("hide")';

            # una vez que esten los productos añadidos, tenemos que cerrar la modal
            # add-to-bandeja_pastas

        }else if($id_familia == 7){ // if($id_familia == 10){

            $dp = $DB->select('nombre_producto,fotos','productos_web','id = '.$id_producto);
            $dp = $dp[f][0];

            if($dp['fotos'] == ''){

                $ft = '/images/logo_argentina_min.png';

            }else{

                $arr_f = json_decode($dp['fotos'],true);
                $ft = is_array($arr_f) && isset($arr_f[0]) ? $arr_f[0] : '/images/logo_argentina_min.png';

            }

            $pvp_productos += $pvp;
            if($foto_tartas_galletas == 1){

                $pvp_productos += 400;

            }
            $llft = $foto_tartas_galletas == 1 ? 'Sí' : 'No';
            $li_producto .= '<li>
                                <a href="#" class="image">
                                    <img src="'.$ft.'" alt="Cart product Image">
                                </a>
                                <div class="content">
                                    <b>'.utf8_encode($dp['nombre_producto']).'</b> <br>
                                    '.$porciones.' x '.nf($pvp).' &euro; <br>
                                    <b>¿Lleva Foto? </b> : '. $llft;

                                    if($mensaje_tarta != ''){

                                        $li_producto .= '<br><b>Mensaje Tarta :</b> <br>'.$mensaje_tarta;
                                    }

                                    if($comentarios_tarta != ''){

                                        $li_producto .= '<br><b>Comentarios Adicionales:</b> <br>'.$comentarios_tarta;
                                    }
                                    
            $li_producto .= '                        
                                    <a href="#" class="remove" name="borrar_linea_carrito" key="0">×</a>
                                </div>
                            </li>';

            $arr = array(
                        "id_producto"=> $id_producto,
                        "pvp"=> $pvp,
                        "porciones"=> $porciones,
                        "tamano"=> $tamano,
                        "mensaje_tarta" => ($mensaje_tarta),
                        "foto_tartas_galletas" => $foto_tartas_galletas,
                        "comentarios_tarta" => ($comentarios_tarta)

                        );
                        
            $arr_c[0] = array(
                            'id_familia'=>$id_familia,
                            'productos'=>$arr
                            );

            $extras = '$("#add-to-tartas_galletas").modal("hide")';

        }else if($id_familia == 8){ // if($id_familia == 7){

            $dp = $DB->select('nombre_producto,fotos','productos_web','id = '.$id_producto);
            $dp = $dp[f][0];

            if($dp['fotos'] == ''){

                $ft = '/images/logo_argentina_min.png';

            }else{

                $arr_f = json_decode($dp['fotos'],true);
                $ft = is_array($arr_f) && isset($arr_f[0]) ? $arr_f[0] : '/images/logo_argentina_min.png';

            }

            $pvp_productos += $pvp;
            if($foto_pastelon == 1){

                $pvp_productos += 400;

            }
            $llft = $foto_pastelon == 1 ? 'Sí' : 'No';
            $li_producto .= '<li>
                                <a href="#" class="image">
                                    <img src="'.$ft.'" alt="Cart product Image">
                                </a>
                                <div class="content">
                                    <b>'.utf8_encode($dp['nombre_producto']).'</b> <br>
                                    '.$porciones.' x '.nf($pvp).' &euro; <br>
                                    <b>¿Lleva Foto? </b> : '. $llft;

                                    if($mensaje_tarta != ''){

                                        $li_producto .= '<br><b>Mensaje Tarta :</b> <br>'.$mensaje_tarta;
                                    }

                                    if($comentarios_tarta != ''){

                                        $li_producto .= '<br><b>Comentarios Adicionales:</b> <br>'.$comentarios_tarta;
                                    }
                                    
            $li_producto .= '                        
                                    <a href="#" class="remove" name="borrar_linea_carrito" key="0">×</a>
                                </div>
                            </li>';

            $arr = array(
                        "id_producto"=> $id_producto,
                        "pvp"=> $pvp,
                        "porciones"=> $porciones,
                        "tamano"=> $tamano,
                        "mensaje_tarta" => ($mensaje_tarta),
                        "foto_pastelon" => $foto_pastelon,
                        "comentarios_tarta" => ($comentarios_tarta)

                        );
                        
            $arr_c[0] = array(
                            'id_familia'=>$id_familia,
                            'productos'=>$arr
                            );

            $extras = '$("#add-to-pastelon").modal("hide")';

        }else if($id_familia == 9){ // if($id_familia == 8){

            $dp = $DB->select('nombre_producto,fotos','productos_web','id = '.$id_producto);
            $dp = $dp[f][0];

            if($dp['fotos'] == ''){

                $ft = '/images/logo_argentina_min.png';

            }else{

                $arr_f = json_decode($dp['fotos'],true);
                $ft = is_array($arr_f) && isset($arr_f[0]) ? $arr_f[0] : '/images/logo_argentina_min.png';

            }

            $pvp_productos += $pvp;
            if($foto_tartas_predeterminadas == 1){

                $pvp_productos += 400;

            }
            $llft = $foto_tartas_predeterminadas == 1 ? 'Sí' : 'No';
            $li_producto .= '<li>
                                <a href="#" class="image">
                                    <img src="'.$ft.'" alt="Cart product Image">
                                </a>
                                <div class="content">
                                    <b>'.utf8_encode($dp['nombre_producto']).'</b> <br>
                                    '.$porciones.' x '.nf($pvp).' &euro; <br>
                                    <b>¿Lleva Foto? </b> : '. $llft;

                                    if($mensaje_tarta != ''){

                                        $li_producto .= '<br><b>Mensaje Tarta :</b> <br>'.$mensaje_tarta;
                                    }

                                    if($comentarios_tarta != ''){

                                        $li_producto .= '<br><b>Comentarios Adicionales:</b> <br>'.$comentarios_tarta;
                                    }
                                    
            $li_producto .= '                        
                                    <a href="#" class="remove" name="borrar_linea_carrito" key="0">×</a>
                                </div>
                            </li>';

            $arr = array(
                        "id_producto"=> $id_producto,
                        "pvp"=> $pvp,
                        "unidades"=> $unidades,
                        "mensaje_tarta" => ($mensaje_tarta),
                        "foto_tartas_predeterminadas" => $foto_tartas_predeterminadas,
                        "comentarios_tarta" => ($comentarios_tarta)

                        );
                        
            $arr_c[0] = array(
                            'id_familia'=>$id_familia,
                            'productos'=>$arr
                            );

            $extras = '$("#add-to-tartas_predeterminadas").modal("hide")';
        
        }else if($id_familia == 6){ // if($id_familia == 9){

            $dp = $DB->select('nombre_producto,fotos','productos_web','id = '.$id_producto);
            $dp = $dp[f][0];

            if($dp['fotos'] == ''){

                $ft = '/images/logo_argentina_min.png';

            }else{

                $arr_f = json_decode($dp['fotos'],true);
                $ft = is_array($arr_f) && isset($arr_f[0]) ? $arr_f[0] : '/images/logo_argentina_min.png';

            }

            $pvp_productos += $pvp;
            if($foto_tartas_personalizadas == 1){

                $pvp_productos += 400;

            }
            $llft = $foto_tartas_personalizadas == 1 ? 'Sí' : 'No';
            $li_producto .= '<li>
                                <a href="#" class="image">
                                    <img src="'.$ft.'" alt="Cart product Image">
                                </a>
                                <div class="content">
                                    <b>'.utf8_encode($dp['nombre_producto']).'</b> <br>
                                    '.$porciones.' porciones x '.nf($pvp).' &euro; <br>
                                    Capa inferior : <b>'.$capa_inferior_personalizada.'</b><br>
                                    Capa media : <b>'.$capa_media_personalizada.'</b><br>
                                    Capa superior : <b>'.$capa_superior_personalizada.'</b><br>
                                    Borde exterior : <b>'.$borde_exterior_personalizada.'</b><br>
                                    <b>¿Lleva Foto? </b> : '. $llft;

                                    if($mensaje_tarta != ''){

                                        $li_producto .= '<br><b>Mensaje Tarta :</b> <br>'.$mensaje_tarta;
                                    }

                                    if($comentarios_tarta != ''){

                                        $li_producto .= '<br><b>Comentarios Adicionales:</b> <br>'.$comentarios_tarta;
                                    }
                                    
            $li_producto .= '                        
                                    <a href="#" class="remove" name="borrar_linea_carrito" key="0">×</a>
                                </div>
                            </li>';

            $arr = array(
                        "id_producto"  => $id_producto,
                        "pvp"  => $pvp,
                        "porciones" => $porciones,
                        "tamano" => $tamano,
                        "mensaje_tarta" => ($mensaje_tarta),
                        "foto_tartas_personalizadas" => $foto_tartas_personalizadas,
                        "comentarios_tarta" => ($comentarios_tarta),
                        "capa_inferior_personalizada" => $capa_inferior_personalizada,
                        "capa_media_personalizada" => $capa_media_personalizada,
                        "capa_superior_personalizada" => $capa_superior_personalizada,
                        "borde_exterior_personalizada" => $borde_exterior_personalizada

                        );
                        
            $arr_c[0] = array(
                            'id_familia'=>$id_familia,
                            'productos'=>$arr
                            );

            $extras = '$("#add-to-tartas_personalizadas").modal("hide")';

        }else if($id_familia == 11){ // if($id_familia == 6){


            $dp = $DB->select('nombre_producto,fotos','productos_web','id = '.$id_producto);
            $dp = $dp[f][0];

            if($dp['fotos'] == ''){

                $ft = '/images/logo_argentina_min.png';

            }else{

                $arr_f = json_decode($dp['fotos'],true);
                $ft = is_array($arr_f) && isset($arr_f[0]) ? $arr_f[0] : '/images/logo_argentina_min.png';

            }

            $pvp_productos += $pvp;
            $li_producto .= '<li>
                                <a href="#" class="image">
                                    <img src="'.$ft.'" alt="Cart product Image">
                                </a>
                                <div class="content">
                                    <b class="text-danger">* Oferta Especial</b> <br>
                                    <b>'.utf8_encode($dp['nombre_producto']).'</b> <br>
                                    '.nf($pvp).' &euro;
                                    <a href="#" class="remove" name="borrar_linea_carrito" key="0">×</a>
                                </div>
                            </li>';
            $arr = array(
                        "id_producto"=> $id_producto,
                        "pvp"=> $pvp,
                        "unidades"=>1
                        );
                        
            $arr_c[0] = array(
                            'id_familia'=>$id_familia,
                            'productos'=>$arr
                            );


            # una vez que esten los productos añadidos, tenemos que cerrar la modal
            # add-to-bandeja_pastas


        } // if($id_familia == 11){

        // var_dump($arr_c);
        $val = json_encode($arr_c,JSON_UNESCAPED_UNICODE);
        setcookie('carrito', $val, time()+864000*365,"/");
        $_COOKIE['carrito']=$val;
        $n_lineas = 1;


    }else if(isset($_COOKIE['carrito'])){

        $arc = json_decode($_COOKIE['carrito'],true);
        $key = count($arc);
        if($id_familia == 4){

            $unds = 0;
            $arr = json_decode($_COOKIE['bandeja'],true);
            $list = '';
            foreach ($arr as $k => $v) {

                $pvp_productos += $v['pvp']*$v['cantidad'];
                $unds += $v['cantidad'];
                $dp = $DB->select('nombre_producto','productos_web','id = '.$v['id_producto']);
                $list .= '<br><b>'.utf8_encode($dp[f][0]['nombre_producto']).'</b><br> '.$v['cantidad'].' Unidades x '.nf($v['pvp']).' &euro; - '.nf($v['pvp']*$v['cantidad']).' &euro;';

            }
            # aqui tengo que eliminar la bandeaj completa y volver a poner el cartel de que esta vacia.
            

            $li_producto .= '<li>
                                <a href="#" class="image">
                                    <img src="/images/bandeja_pasteles_surtidos.jpg" alt="Cart product Image">
                                </a>
                                <div class="content">1 Bandeja Pasteles Surtidos ('.$unds.' Unidades)
                                    '.$list.'
                                    <a href="#" class="remove" name="borrar_linea_carrito" key="'.$key.'">×</a>
                                </div>
                            </li>';

            $arr_c[$key] = array(
                            'id_familia'=>$id_familia,
                            'productos'=>$arr
                            ); 
            # vaciamos la bandeja y eliminamos la cookie
            $vaciar_carro .= '
                            $("#bandeja").html("");
                            $("#mensaje_bandeja").html("Tu bandeja esta vacía, selecciona mas de 6 unidades de nuestro pasteles y podrás añadirlo al carrito");
                            $("#btn_carrito_pasteles").addClass("d-none");
                            ';
            setcookie('bandeja','',time()-100,"/");

        }else if($id_familia == 5){ // if($id_familia == 4){

            $arc = json_decode($_COOKIE['carrito'],true);
            $dp = $DB->select('nombre_producto,fotos','productos_web','id = '.$id_producto);
            $dp = $dp[f][0];

            if($dp['fotos'] == ''){

                $ft = '/images/logo_argentina_min.png';

            }else{

                $arr_f = json_decode($dp['fotos'],true);
                $ft = is_array($arr_f) && isset($arr_f[0]) ? $arr_f[0] : '/images/logo_argentina_min.png';

            }

            $pvp_productos += $pvp*$unidades;
            $li_producto .= '<li>
                                <a href="#" class="image">
                                    <img src="'.$ft.'" alt="Cart product Image">
                                </a>
                                <div class="content">
                                    <b>'.utf8_encode($dp['nombre_producto']).'</b> <br>
                                    '.$unidades.' x '.nf($pvp).' &euro; - '.nf($pvp*$unidades).' &euro;
                                    <a href="#" class="remove" name="borrar_linea_carrito" key="'.$key.'">×</a>
                                </div>
                            </li>';
            $arr = array(
                        "id_producto"=> $id_producto,
                        "pvp"=> $pvp,
                        "unidades"=> $unidades
                        );

            $arr_c[$key] = array(
                            'id_familia'=>5,
                            'productos'=>$arr
                            );

            $extras = '$("#add-to-bandeja_pastas").modal("hide")';

            # una vez que esten los productos añadidos, tenemos que cerrar la modal
            # add-to-bandeja_pastas
        }else if($id_familia == 10){ // if($id_familia == 5){


            $dp = $DB->select('nombre_producto,fotos','productos_web','id = '.$id_producto);
            $dp = $dp[f][0];

            if($dp['fotos'] == ''){

                $ft = '/images/logo_argentina_min.png';

            }else{

                $arr_f = json_decode($dp['fotos'],true);
                $ft = is_array($arr_f) && isset($arr_f[0]) ? $arr_f[0] : '/images/logo_argentina_min.png';

            }

            $pvp_productos += $pvp*$unidades;
            $li_producto .= '<li>
                                <a href="#" class="image">
                                    <img src="'.$ft.'" alt="Cart product Image">
                                </a>
                                <div class="content">
                                    <b>'.utf8_encode($dp['nombre_producto']).'</b> <br>
                                    '.$unidades.' x '.nf($pvp).' &euro; - '.nf($pvp*$unidades).' &euro;
                                    <a href="#" class="remove" name="borrar_linea_carrito" key="'.$key.'">×</a>
                                </div>
                            </li>';
            $arr = array(
                        "id_producto"=> $id_producto,
                        "pvp"=> $pvp,
                        "unidades"=> $unidades
                        );
                        
            $arr_c[$key] = array(
                            'id_familia'=>10,
                            'productos'=>$arr
                            );

            $extras = '$("#add-to-bandeja_bolleria").modal("hide")';

            # una vez que esten los productos añadidos, tenemos que cerrar la modal
            # add-to-bandeja_pastas
            
        }else if($id_familia == 7){ // if($id_familia == 10){

            $dp = $DB->select('nombre_producto,fotos','productos_web','id = '.$id_producto);
            $dp = $dp[f][0];

            if($dp['fotos'] == ''){

                $ft = '/images/logo_argentina_min.png';

            }else{

                $arr_f = json_decode($dp['fotos'],true);
                $ft = is_array($arr_f) && isset($arr_f[0]) ? $arr_f[0] : '/images/logo_argentina_min.png';

            }

            $pvp_productos += $pvp;
            if($foto_tartas_galletas == 1){

                $pvp_productos += 400;

            }
            $llft = $foto_tartas_galletas == 1 ? 'Sí' : 'No';
            $li_producto .= '<li>
                                <a href="#" class="image">
                                    <img src="'.$ft.'" alt="Cart product Image">
                                </a>
                                <div class="content">
                                    <b>'.utf8_encode($dp['nombre_producto']).'</b> <br>
                                    '.$porciones.' x '.nf($pvp).' &euro; <br>
                                    <b>¿Lleva Foto? </b> : '. $llft;

                                    if($mensaje_tarta != ''){

                                        $li_producto .= '<br><b>Mensaje Tarta :</b> <br>'.$mensaje_tarta;
                                    }

                                    if($comentarios_tarta != ''){

                                        $li_producto .= '<br><b>Comentarios Adicionales:</b> <br>'.$comentarios_tarta;
                                    }
                                    
            $li_producto .= '                        
                                    <a href="#" class="remove" name="borrar_linea_carrito" key="'.$key.'">×</a>
                                </div>
                            </li>';

            $arr = array(
                        "id_producto"=> $id_producto,
                        "pvp"=> $pvp,
                        "porciones"=> $porciones,
                        "tamano"=> $tamano,
                        "mensaje_tarta" => ($mensaje_tarta),
                        "foto_tartas_galletas" => $foto_tartas_galletas,
                        "comentarios_tarta" => ($comentarios_tarta)

                        );
                        
            $arr_c[$key] = array(
                            'id_familia'=>$id_familia,
                            'productos'=>$arr
                            );

            $extras = '$("#add-to-tartas_galletas").modal("hide")';

        }else if($id_familia == 8){ // if($id_familia == 7){

            $dp = $DB->select('nombre_producto,fotos','productos_web','id = '.$id_producto);
            $dp = $dp[f][0];

            if($dp['fotos'] == ''){

                $ft = '/images/logo_argentina_min.png';

            }else{

                $arr_f = json_decode($dp['fotos'],true);
                $ft = is_array($arr_f) && isset($arr_f[0]) ? $arr_f[0] : '/images/logo_argentina_min.png';

            }

            $pvp_productos += $pvp;
            if($foto_pastelon == 1){

                $pvp_productos += 400;

            }
            $llft = $foto_pastelon == 1 ? 'Sí' : 'No';
            $li_producto .= '<li>
                                <a href="#" class="image">
                                    <img src="'.$ft.'" alt="Cart product Image">
                                </a>
                                <div class="content">
                                    <b>'.utf8_encode($dp['nombre_producto']).'</b> <br>
                                    '.$porciones.' x '.nf($pvp).' &euro; <br>
                                    <b>¿Lleva Foto? </b> : '. $llft;

                                    if($mensaje_tarta != ''){

                                        $li_producto .= '<br><b>Mensaje Tarta :</b> <br>'.$mensaje_tarta;
                                    }

                                    if($comentarios_tarta != ''){

                                        $li_producto .= '<br><b>Comentarios Adicionales:</b> <br>'.$comentarios_tarta;
                                    }
                                    
            $li_producto .= '                        
                                    <a href="#" class="remove" name="borrar_linea_carrito" key="'.$key.'">×</a>
                                </div>
                            </li>';

            $arr = array(
                        "id_producto"=> $id_producto,
                        "pvp"=> $pvp,
                        "porciones"=> $porciones,
                        "tamano"=> $tamano,
                        "mensaje_tarta" => ($mensaje_tarta),
                        "foto_pastelon" => $foto_pastelon,
                        "comentarios_tarta" => ($comentarios_tarta)

                        );
                        
            $arr_c[$key] = array(
                            'id_familia'=>$id_familia,
                            'productos'=>$arr
                            );

            $extras = '$("#add-to-pastelon").modal("hide")';


        }else if($id_familia == 9){ // if($id_familia == 8){

            $dp = $DB->select('nombre_producto,fotos','productos_web','id = '.$id_producto);
            $dp = $dp[f][0];

            if($dp['fotos'] == ''){

                $ft = '/images/logo_argentina_min.png';

            }else{

                $arr_f = json_decode($dp['fotos'],true);
                $ft = is_array($arr_f) && isset($arr_f[0]) ? $arr_f[0] : '/images/logo_argentina_min.png';

            }

            $pvp_productos += $pvp;
            if($foto_tartas_predeterminadas == 1){

                $pvp_productos += 400;

            }
            $llft = $foto_tartas_predeterminadas == 1 ? 'Sí' : 'No';
            $li_producto .= '<li>
                                <a href="#" class="image">
                                    <img src="'.$ft.'" alt="Cart product Image">
                                </a>
                                <div class="content">
                                    <b>'.utf8_encode($dp['nombre_producto']).'</b> <br>
                                    '.$unidades.' x '.nf($pvp).' &euro; <br>
                                    <b>¿Lleva Foto? </b> : '. $llft;

                                    if($mensaje_tarta != ''){

                                        $li_producto .= '<br><b>Mensaje Tarta :</b> <br>'.$mensaje_tarta;
                                    }

                                    if($comentarios_tarta != ''){

                                        $li_producto .= '<br><b>Comentarios Adicionales:</b> <br>'.$comentarios_tarta;
                                    }
                                    
            $li_producto .= '                        
                                    <a href="#" class="remove" name="borrar_linea_carrito" key="'.$key.'">×</a>
                                </div>
                            </li>';

            $arr = array(
                        "id_producto"=> $id_producto,
                        "pvp"=> $pvp,
                        "unidades"=> $unidades,
                        "mensaje_tarta" => ($mensaje_tarta),
                        "foto_tartas_predeterminadas" => $foto_tartas_predeterminadas,
                        "comentarios_tarta" => ($comentarios_tarta)

                        );
                        
            $arr_c[$key] = array(
                            'id_familia'=>$id_familia,
                            'productos'=>$arr
                            );

            $extras = '$("#add-to-tartas_predeterminadas").modal("hide")';

        }else if($id_familia == 6){ // if($id_familia == 9){

            $dp = $DB->select('nombre_producto,fotos','productos_web','id = '.$id_producto);
            $dp = $dp[f][0];

            if($dp['fotos'] == ''){

                $ft = '/images/logo_argentina_min.png';

            }else{

                $arr_f = json_decode($dp['fotos'],true);
                $ft = is_array($arr_f) && isset($arr_f[0]) ? $arr_f[0] : '/images/logo_argentina_min.png';

            }

            $pvp_productos += $pvp;
            if($foto_tartas_personalizadas == 1){

                $pvp_productos += 400;

            }
            $llft = $foto_tartas_personalizadas == 1 ? 'Sí' : 'No';
            $li_producto .= '<li>
                                <a href="#" class="image">
                                    <img src="'.$ft.'" alt="Cart product Image">
                                </a>
                                <div class="content">
                                    <b>'.utf8_encode($dp['nombre_producto']).'</b> <br>
                                    '.$porciones.' porciones x '.nf($pvp).' &euro; <br>
                                    Capa inferior : <b>'.$capa_inferior_personalizada.'</b><br>
                                    Capa media : <b>'.$capa_media_personalizada.'</b><br>
                                    Capa superior : <b>'.$capa_superior_personalizada.'</b><br>
                                    Borde exterior : <b>'.$borde_exterior_personalizada.'</b><br>
                                    <b>¿Lleva Foto? </b> : '. $llft;

                                    if($mensaje_tarta != ''){

                                        $li_producto .= '<br><b>Mensaje Tarta :</b> <br>'.$mensaje_tarta;
                                    }

                                    if($comentarios_tarta != ''){

                                        $li_producto .= '<br><b>Comentarios Adicionales:</b> <br>'.$comentarios_tarta;
                                    }
                                    
            $li_producto .= '                        
                                    <a href="#" class="remove" name="borrar_linea_carrito" key="'.$key.'">×</a>
                                </div>
                            </li>';

            $arr = array(
                        "id_producto"  => $id_producto,
                        "pvp"  => $pvp,
                        "porciones" => $porciones,
                        "tamano" => $tamano,
                        "mensaje_tarta" => ($mensaje_tarta),
                        "foto_tartas_personalizadas" => $foto_tartas_personalizadas,
                        "comentarios_tarta" => ($comentarios_tarta),
                        "capa_inferior_personalizada" => $capa_inferior_personalizada,
                        "capa_media_personalizada" => $capa_media_personalizada,
                        "capa_superior_personalizada" => $capa_superior_personalizada,
                        "borde_exterior_personalizada" => $borde_exterior_personalizada

                        );
                        
            $arr_c[$key] = array(
                            'id_familia'=>$id_familia,
                            'productos'=>$arr
                            );

            $extras = '$("#add-to-tartas_personalizadas").modal("hide")';

        
        }else if($id_familia == 11){ // if($id_familia == 6){


            $dp = $DB->select('nombre_producto,fotos','productos_web','id = '.$id_producto);
            $dp = $dp[f][0];

            if($dp['fotos'] == ''){

                $ft = '/images/logo_argentina_min.png';

            }else{

                $arr_f = json_decode($dp['fotos'],true);
                $ft = is_array($arr_f) && isset($arr_f[0]) ? $arr_f[0] : '/images/logo_argentina_min.png';

            }

            $pvp_productos += $pvp;
            $li_producto .= '<li>
                                <a href="#" class="image">
                                    <img src="'.$ft.'" alt="Cart product Image">
                                </a>
                                <div class="content">
                                    <b class="text-danger">* Oferta Especial</b> <br>
                                    <b>'.utf8_encode($dp['nombre_producto']).'</b> <br>
                                    '.nf($pvp).' &euro;
                                    <a href="#" class="remove" name="borrar_linea_carrito" key="'.$key.'">×</a>
                                </div>
                            </li>';
            $arr = array(
                        "id_producto"=> $id_producto,
                        "pvp"=> $pvp,
                        "unidades"=>1
                        );
                        
            $arr_c[$key] = array(
                            'id_familia'=>$id_familia,
                            'productos'=>$arr
                            );


            # una vez que esten los productos añadidos, tenemos que cerrar la modal
            # add-to-bandeja_pastas


        } // if($id_familia == 11){

        $n_lineas = $key+1;
        
        $arf = array_merge($arc,$arr_c);
        $val = json_encode($arf);
        setcookie('carrito', $val, time()+864000*365,"/");
        $_COOKIE['carrito']=$val;

    }

    # tengo que añadir:
        # el número de líneas al badge 
        # el precio al importe total 
        # la línea al carrito lateral 

    if($pvp_productos < 1200){

        $pvp_envio = 399;
        $pvp_carrito = $pvp_productos+399;

    }else{

        $pvp_envio = 0;
        $pvp_carrito = $pvp_productos;

    }

    noty('Producto añadido correctamente al carrito');
    script('
            '.$vaciar_carro.'
            $("#span_carrito").html("'.$n_lineas.'");
            $("#pvp_productos").html("'.nf($pvp_productos).'");
            $("#pvp_productos").attr("pvp","'.$pvp_productos.'");
            $("#pvp_envio").html("'.nf($pvp_envio).'");
            $("#pvp_envio").attr("pvp","'.$pvp_envio.'");
            $("#pvp_carrito").html("'.nf($pvp_carrito).'");
            $("#pvp_carrito").attr("pvp","'.$pvp_carrito.'");
            $("#ul_carrito").append(\''.str_replace($clean, '', $li_producto).'\');
            '.$extras.'
        ');

//}else if(isset($cookies_carrito)){
}else if(isset($recuperar_carrito)){

    $arr = json_decode(($_COOKIE['carrito']),true);
    $n_lineas = count($arr);
    $pvp_productos = 0;
    $li_producto = '';
    foreach ($arr as $k => $v) {
        
        if($v['id_familia'] == 4){

            $unds[$k] = 0;
            $list[$k] = '';
            foreach ($v['productos'] as $b => $c) {

                $pvp_productos += $c['pvp']*$c['cantidad'];
                $unds[$k] += $c['cantidad'];
                $dp[$k] = $DB->select('nombre_producto','productos_web','id = '.$c['id_producto']);
                $list[$k] .= '<br><b>'.utf8_encode($dp[$k][f][0]['nombre_producto']).'</b><br> '.$c['cantidad'].' Unidades x '.nf($c['pvp']).' &euro; - '.nf($c['pvp']*$c['cantidad']).' &euro;';

            }
            # aqui tengo que eliminar la bandeaj completa y volver a poner el cartel de que esta vacia.
            

            $li_producto .= '<li>
                                <a href="#" class="image">
                                    <img src="/images/bandeja_pasteles_surtidos.jpg" alt="Cart product Image">
                                </a>
                                <div class="content">1 Bandeja Pasteles Surtidos ('.$unds[$k].' Unidades)
                                    '.$list[$k].'
                                    <a href="#" class="remove" name="borrar_linea_carrito" key="'.$k.'">×</a>
                                </div>
                            </li>';

        }else if($v['id_familia'] == 5 || $v['id_familia'] == 10){ // if($id_familia == 4){


            $dp[$k] = $DB->select('nombre_producto,fotos','productos_web','id = '.$v['productos']['id_producto']);
            $dp[$k] = $dp[$k][f][0];

            if($dp[$k]['fotos'] == ''){

                $ft = '/images/logo_argentina_min.png';

            }else{

                $arr_f[$k] = json_decode($dp[$k]['fotos'],true);
                $ft = is_array($arr_f[$k]) && isset($arr_f[$k][0]) ? $arr_f[$k][0] : '/images/logo_argentina_min.png';

            }

            $pvp_productos += $v['productos']['pvp']*$v['productos']['unidades'];
            $li_producto .= '<li>
                                <a href="#" class="image">
                                    <img src="'.$ft.'" alt="Cart product Image">
                                </a>
                                <div class="content">
                                    <b>'.utf8_encode($dp[$k]['nombre_producto']).'</b> <br>
                                    '.$v['productos']['unidades'].' x '.nf($v['productos']['pvp']).' &euro; - '.nf($v['productos']['pvp']*$v['productos']['unidades']).' &euro;
                                    <a href="#" class="remove" name="borrar_linea_carrito" key="'.$k.'">×</a>
                                </div>
                            </li>';

        }else if($v['id_familia'] == 7){ // if($id_familia == 5 || $v['id_familia'] == 10){

            $dp[$k] = $DB->select('nombre_producto,fotos','productos_web','id = '.$v['productos']['id_producto']);
            $dp[$k] = $dp[$k][f][0];

            if($dp[$k]['fotos'] == ''){

                $ft = '/images/logo_argentina_min.png';

            }else{

                $arr_f[$k] = json_decode($dp[$k]['fotos'],true);
                $ft = is_array($arr_f[$k]) && isset($arr_f[$k][0]) ? $arr_f[$k][0] : '/images/logo_argentina_min.png';

            }

            $pvp_productos += $v['productos']['pvp'];
            $llft = $v['productos']['foto_tartas_galletas'] == 1 ? 'Sí' : 'No';
            if($v['productos']['foto_tartas_galletas'] == 1){

                $pvp_productos += 400;

            }
            $li_producto .= '<li>
                                <a href="#" class="image">
                                    <img src="'.$ft.'" alt="Cart product Image">
                                </a>
                                <div class="content">
                                    <b>'.utf8_encode($dp[$k]['nombre_producto']).'</b> <br>
                                    '.$v['productos']['porciones'].' x '.nf($v['productos']['pvp']).' &euro; <br>
                                    <b>¿Lleva Foto? </b> : '. $llft;

                                    if($v['productos']['mensaje_tarta'] != ''){

                                        $li_producto .= '<br><b>Mensaje Tarta :</b> <br>'.$v['productos']['mensaje_tarta'];
                                    }

                                    if($v['productos']['comentarios_tarta'] != ''){

                                        $li_producto .= '<br><b>Comentarios Adicionales:</b> <br>'.$v['productos']['comentarios_tarta'];
                                    }
                                    
            $li_producto .= '                        
                                    <a href="#" class="remove" name="borrar_linea_carrito" key="'.$k.'">×</a>
                                </div>
                            </li>';


            // $extras = '$("#add-to-tartas_galletas").modal("hide")';
        }else if($v['id_familia'] == 8){ // if($id_familia == 7){


            $dp[$k] = $DB->select('nombre_producto,fotos','productos_web','id = '.$v['productos']['id_producto']);
            $dp[$k] = $dp[$k][f][0];

            if($dp[$k]['fotos'] == ''){

                $ft = '/images/logo_argentina_min.png';

            }else{

                $arr_f[$k] = json_decode($dp[$k]['fotos'],true);
                $ft = is_array($arr_f[$k]) && isset($arr_f[$k][0]) ? $arr_f[$k][0] : '/images/logo_argentina_min.png';

            }

            $pvp_productos += $v['productos']['pvp'];
            $llft = $v['productos']['foto_pastelon'] == 1 ? 'Sí' : 'No';
            if($v['productos']['foto_pastelon'] == 1){

                $pvp_productos += 400;

            }
            $li_producto .= '<li>
                                <a href="#" class="image">
                                    <img src="'.$ft.'" alt="Cart product Image">
                                </a>
                                <div class="content">
                                    <b>'.utf8_encode($dp[$k]['nombre_producto']).'</b> <br>
                                    '.$v['productos']['porciones'].' x '.nf($v['productos']['pvp']).' &euro; <br>
                                    <b>¿Lleva Foto? </b> : '. $llft;

                                    if($v['productos']['mensaje_tarta'] != ''){

                                        $li_producto .= '<br><b>Mensaje Tarta :</b> <br>'.$v['productos']['mensaje_tarta'];
                                    }

                                    if($v['productos']['comentarios_tarta'] != ''){

                                        $li_producto .= '<br><b>Comentarios Adicionales:</b> <br>'.$v['productos']['comentarios_tarta'];
                                    }
                                    
            $li_producto .= '                        
                                    <a href="#" class="remove" name="borrar_linea_carrito" key="'.$k.'">×</a>
                                </div>
                            </li>';


            // $extras = '$("#add-to-pastelon").modal("hide")';

        }else if($v['id_familia'] == 9){ // if($id_familia == 8){

                $dp[$k] = $DB->select('nombre_producto,fotos','productos_web','id = '.$v['productos']['id_producto']);
                $dp[$k] = $dp[$k][f][0];

                if($dp[$k]['fotos'] == ''){

                    $ft = '/images/logo_argentina_min.png';

                }else{

                    $arr_f[$k] = json_decode($dp[$k]['fotos'],true);
                    $ft = is_array($arr_f[$k]) && isset($arr_f[$k][0]) ? $arr_f[$k][0] : '/images/logo_argentina_min.png';

                }

                $pvp_productos += $v['productos']['pvp'];
                if($v['productos']['foto_tartas_predeterminadas'] == 1){

                    $pvp_productos += 400;

                }
                $llft = $v['productos']['foto_tartas_predeterminadas'] == 1 ? 'Sí' : 'No';
                $li_producto .= '<li>
                                    <a href="#" class="image">
                                        <img src="'.$ft.'" alt="Cart product Image">
                                    </a>
                                    <div class="content">
                                        <b>'.utf8_encode($dp[$k]['nombre_producto']).'</b> <br>
                                        '.$v['productos']['unidades'].' x '.nf($v['productos']['pvp']).' &euro; <br>
                                        <b>¿Lleva Foto? </b> : '. $llft;

                                        if($v['productos']['mensaje_tarta'] != ''){

                                            $li_producto .= '<br><b>Mensaje Tarta :</b> <br>'.$v['productos']['mensaje_tarta'];
                                        }

                                        if($v['productos']['comentarios_tarta'] != ''){

                                            $li_producto .= '<br><b>Comentarios Adicionales:</b> <br>'.$v['productos']['comentarios_tarta'];
                                        }
                                        
                $li_producto .= '                        
                                        <a href="#" class="remove" name="borrar_linea_carrito" key="'.$k.'">×</a>
                                    </div>
                                </li>';


                // $extras = '$("#add-to-tartas_galletas").modal("hide")';

        }else if($v['id_familia'] == 6){ // if($id_familia == 9){

            $dp[$k] = $DB->select('nombre_producto,fotos','productos_web','id = '.$v['productos']['id_producto']);
            $dp[$k] = $dp[$k][f][0];

            if($dp[$k]['fotos'] == ''){

                $ft = '/images/logo_argentina_min.png';

            }else{

                $arr_f = json_decode($dp[$k]['fotos'],true);
                $ft = is_array($arr_f) && isset($arr_f[0]) ? $arr_f[0] : '/images/logo_argentina_min.png';

            }

            $pvp_productos += $v['productos']['pvp'];
            if($v['productos']['foto_tartas_personalizadas'] == 1){

                $pvp_productos += 400;

            }
            $llft = $v['productos']['foto_tartas_personalizadas'] == 1 ? 'Sí' : 'No';
            $li_producto .= '<li>
                                <a href="#" class="image">
                                    <img src="'.$ft.'" alt="Cart product Image">
                                </a>
                                <div class="content">
                                    <b>'.utf8_encode($dp[$k]['nombre_producto']).'</b> <br>
                                    '.$v['productos']['porciones'].' porciones x '.nf($v['productos']['pvp']).' &euro; <br>
                                    Capa inferior : <b>'.$v['productos']['capa_inferior_personalizada'].'</b><br>
                                    Capa media : <b>'.$v['productos']['capa_media_personalizada'].'</b><br>
                                    Capa superior : <b>'.$v['productos']['capa_superior_personalizada'].'</b><br>
                                    Borde exterior : <b>'.$v['productos']['borde_exterior_personalizada'].'</b><br>
                                    <b>¿Lleva Foto? </b> : '. $llft;

                                    if($v['productos']['mensaje_tarta'] != ''){

                                            $li_producto .= '<br><b>Mensaje Tarta :</b> <br>'.$v['productos']['mensaje_tarta'];
                                        }

                                        if($v['productos']['comentarios_tarta'] != ''){

                                            $li_producto .= '<br><b>Comentarios Adicionales:</b> <br>'.$v['productos']['comentarios_tarta'];
                                        }
                                    
            $li_producto .= '                        
                                    <a href="#" class="remove" name="borrar_linea_carrito" key="'.$k.'">×</a>
                                </div>
                            </li>';


        }else if($v['id_familia'] == 11){ // if($id_familia == 6){


            $dp[$k] = $DB->select('nombre_producto,fotos,pvp','productos_web','id = '.$v['productos']['id_producto']);
            $dp[$k] = $dp[$k][f][0];

            if($dp[$k]['fotos'] == ''){

                $ft = '/images/logo_argentina_min.png';

            }else{

                $arr_f = json_decode($dp[$k]['fotos'],true);
                $ft = is_array($arr_f) && isset($arr_f[0]) ? $arr_f[0] : '/images/logo_argentina_min.png';

            }

            $pvp_productos += $v['productos']['pvp'];
            $li_producto .= '<li>
                                <a href="#" class="image">
                                    <img src="'.$ft.'" alt="Cart product Image">
                                </a>
                                <div class="content">
                                    <b class="text-danger">* Oferta Especial</b> <br>
                                    <b>'.utf8_encode($dp[$k]['nombre_producto']).'</b> <br>
                                    '.nf($v['productos']['pvp']).' &euro;
                                    <a href="#" class="remove" name="borrar_linea_carrito" key="'.$K.'">×</a>
                                </div>
                            </li>';


            # una vez que esten los productos añadidos, tenemos que cerrar la modal
            # add-to-bandeja_pastas


        } // if($id_familia == 11){

    }

    if($pvp_productos < 1200){

        $pvp_envio = 399;
        $pvp_carrito = $pvp_productos+399;

    }else{

        $pvp_envio = 0;
        $pvp_carrito = $pvp_productos;

    }

    script('
            $("#span_carrito").html("'.$n_lineas.'");
            $("#pvp_productos").html("'.nf($pvp_productos).'");
            $("#pvp_productos").attr("pvp","'.$pvp_productos.'");
            $("#pvp_envio").html("'.nf($pvp_envio).'");
            $("#pvp_envio").attr("pvp","'.$pvp_envio.'");
            $("#pvp_carrito").html("'.nf($pvp_carrito).'");
            $("#pvp_carrito").attr("pvp","'.$pvp_carrito.'");
            $("#ul_carrito").append(\''.str_replace($clean, '', $li_producto).'\');

        ');
//}else if(isset($recuperar_carrito)){
}else if(isset($borrar_linea_carrito)){

    $arr = json_decode($_COOKIE['carrito'],true);
    $n_key = 0;
    $n_arr = array();
    $n_lineas = 0;
    $pvp_productos = 0;
    $li_producto = '';
    foreach ($arr as $k => $v) {
        
        if($k != $key){

            if($v['id_familia'] == 4){

                $unds[$k] = 0;
                $list[$k] = '';
                foreach ($v['productos'] as $b => $c) {

                    $pvp_productos += $c['pvp']*$c['cantidad'];
                    $unds[$k] += $c['cantidad'];
                    $dp[$k] = $DB->select('nombre_producto','productos_web','id = '.$c['id_producto']);
                    $list[$k] .= '<br><b>'.utf8_encode($dp[$k][f][0]['nombre_producto']).'</b><br> '.$c['cantidad'].' Unidades x '.nf($c['pvp']).' &euro; - '.nf($c['pvp']*$c['cantidad']).' &euro;';

                }
                # aqui tengo que eliminar la bandeaj completa y volver a poner el cartel de que esta vacia.
                

                $li_producto .= '<li>
                                    <a href="#" class="image">
                                        <img src="/images/bandeja_pasteles_surtidos.jpg" alt="Cart product Image">
                                    </a>
                                    <div class="content">1 Bandeja Pasteles Surtidos ('.$unds[$k].' Unidades)
                                        '.$list[$k].'
                                        <a href="#" class="remove" name="borrar_linea_carrito" key="'.$k.'">×</a>
                                    </div>
                                </li>';

            }else if($v['id_familia'] == 5 || $v['id_familia'] == 10){ // if($id_familia == 4){


                $dp[$k] = $DB->select('nombre_producto,fotos','productos_web','id = '.$v['productos']['id_producto']);
                $dp[$k] = $dp[$k][f][0];

                if($dp[$k]['fotos'] == ''){

                    $ft = '/images/logo_argentina_min.png';

                }else{

                    $arr_f[$k] = json_decode($dp[$k]['fotos'],true);
                    $ft = is_array($arr_f[$k]) && isset($arr_f[$k][0]) ? $arr_f[$k][0] : '/images/logo_argentina_min.png';

                }

                $pvp_productos += $v['productos']['pvp']*$v['productos']['unidades'];
                $li_producto .= '<li>
                                    <a href="#" class="image">
                                        <img src="'.$ft.'" alt="Cart product Image">
                                    </a>
                                    <div class="content">
                                        <b>'.utf8_encode($dp[$k]['nombre_producto']).'</b> <br>
                                        '.$v['productos']['unidades'].' x '.nf($v['productos']['pvp']).' &euro; - '.nf($v['productos']['pvp']*$v['productos']['unidades']).' &euro;
                                        <a href="#" class="remove" name="borrar_linea_carrito" key="'.$k.'">×</a>
                                    </div>
                                </li>';

            }else if($v['id_familia'] == 7){ // if($id_familia == 5 || $v['id_familia'] == 10){

                $dp[$k] = $DB->select('nombre_producto,fotos','productos_web','id = '.$v['productos']['id_producto']);
                $dp[$k] = $dp[$k][f][0];

                if($dp[$k]['fotos'] == ''){

                    $ft = '/images/logo_argentina_min.png';

                }else{

                    $arr_f[$k] = json_decode($dp[$k]['fotos'],true);
                    $ft = is_array($arr_f[$k]) && isset($arr_f[$k][0]) ? $arr_f[$k][0] : '/images/logo_argentina_min.png';

                }

                $pvp_productos += $v['productos']['pvp'];
                if($v['productos']['foto_tartas_galletas'] == 1){

                    $pvp_productos += 400;

                }
                $llft = $v['productos']['foto_tartas_galletas'] == 1 ? 'Sí' : 'No';
                $li_producto .= '<li>
                                    <a href="#" class="image">
                                        <img src="'.$ft.'" alt="Cart product Image">
                                    </a>
                                    <div class="content">
                                        <b>'.utf8_encode($dp[$k]['nombre_producto']).'</b> <br>
                                        '.$v['productos']['porciones'].' x '.nf($v['productos']['pvp']).' &euro; <br>
                                        <b>¿Lleva Foto? </b> : '. $llft;

                                        if($v['productos']['mensaje_tarta'] != ''){

                                            $li_producto .= '<br><b>Mensaje Tarta :</b> <br>'.$v['productos']['mensaje_tarta'];
                                        }

                                        if($v['productos']['comentarios_tarta'] != ''){

                                            $li_producto .= '<br><b>Comentarios Adicionales:</b> <br>'.$v['productos']['comentarios_tarta'];
                                        }
                                        
                $li_producto .= '                        
                                        <a href="#" class="remove" name="borrar_linea_carrito" key="'.$k.'">×</a>
                                    </div>
                                </li>';


                // $extras = '$("#add-to-tartas_galletas").modal("hide")';
            }else if($v['id_familia'] == 8){ // if($id_familia == 7){

                $dp[$k] = $DB->select('nombre_producto,fotos','productos_web','id = '.$v['productos']['id_producto']);
                $dp[$k] = $dp[$k][f][0];

                if($dp[$k]['fotos'] == ''){

                    $ft = '/images/logo_argentina_min.png';

                }else{

                    $arr_f[$k] = json_decode($dp[$k]['fotos'],true);
                    $ft = is_array($arr_f[$k]) && isset($arr_f[$k][0]) ? $arr_f[$k][0] : '/images/logo_argentina_min.png';

                }

                $pvp_productos += $v['productos']['pvp'];
                if($v['productos']['foto_tartas_galletas'] == 1){

                    $pvp_productos += 400;

                }
                $llft = $v['productos']['foto_tartas_galletas'] == 1 ? 'Sí' : 'No';
                $li_producto .= '<li>
                                    <a href="#" class="image">
                                        <img src="'.$ft.'" alt="Cart product Image">
                                    </a>
                                    <div class="content">
                                        <b>'.utf8_encode($dp[$k]['nombre_producto']).'</b> <br>
                                        '.$v['productos']['porciones'].' x '.nf($v['productos']['pvp']).' &euro; <br>
                                        <b>¿Lleva Foto? </b> : '. $llft;

                                        if($v['productos']['mensaje_tarta'] != ''){

                                            $li_producto .= '<br><b>Mensaje Tarta :</b> <br>'.$v['productos']['mensaje_tarta'];
                                        }

                                        if($v['productos']['comentarios_tarta'] != ''){

                                            $li_producto .= '<br><b>Comentarios Adicionales:</b> <br>'.$v['productos']['comentarios_tarta'];
                                        }
                                        
                $li_producto .= '                        
                                        <a href="#" class="remove" name="borrar_linea_carrito" key="'.$k.'">×</a>
                                    </div>
                                </li>';


                // $extras = '$("#add-to-tartas_galletas").modal("hide")';
            }else if($v['id_familia'] == 9){ // if($id_familia == 8){

                $dp[$k] = $DB->select('nombre_producto,fotos','productos_web','id = '.$v['productos']['id_producto']);
                $dp[$k] = $dp[$k][f][0];

                if($dp[$k]['fotos'] == ''){

                    $ft = '/images/logo_argentina_min.png';

                }else{

                    $arr_f[$k] = json_decode($dp[$k]['fotos'],true);
                    $ft = is_array($arr_f[$k]) && isset($arr_f[$k][0]) ? $arr_f[$k][0] : '/images/logo_argentina_min.png';

                }

                $pvp_productos += $v['productos']['pvp'];
                if($v['productos']['foto_tartas_predeterminadas'] == 1){

                    $pvp_productos += 400;

                }
                $llft = $v['productos']['foto_tartas_predeterminadas'] == 1 ? 'Sí' : 'No';
                $li_producto .= '<li>
                                    <a href="#" class="image">
                                        <img src="'.$ft.'" alt="Cart product Image">
                                    </a>
                                    <div class="content">
                                        <b>'.utf8_encode($dp[$k]['nombre_producto']).'</b> <br>
                                        '.$v['productos']['unidades'].' x '.nf($v['productos']['pvp']).' &euro; <br>
                                        <b>¿Lleva Foto? </b> : '. $llft;

                                        if($v['productos']['mensaje_tarta'] != ''){

                                            $li_producto .= '<br><b>Mensaje Tarta :</b> <br>'.$v['productos']['mensaje_tarta'];
                                        }

                                        if($v['productos']['comentarios_tarta'] != ''){

                                            $li_producto .= '<br><b>Comentarios Adicionales:</b> <br>'.$v['productos']['comentarios_tarta'];
                                        }
                                        
                $li_producto .= '                        
                                        <a href="#" class="remove" name="borrar_linea_carrito" key="'.$k.'">×</a>
                                    </div>
                                </li>';


                // $extras = '$("#add-to-tartas_galletas").modal("hide")';

            }else if($v['id_familia'] == 6){ // if($id_familia == 9){

                $dp[$k] = $DB->select('nombre_producto,fotos','productos_web','id = '.$v['productos']['id_producto']);
                $dp[$k] = $dp[$k][f][0];

                if($dp[$k]['fotos'] == ''){

                    $ft = '/images/logo_argentina_min.png';

                }else{

                    $arr_f = json_decode($dp[$k]['fotos'],true);
                    $ft = is_array($arr_f) && isset($arr_f[0]) ? $arr_f[0] : '/images/logo_argentina_min.png';

                }

                $pvp_productos += $v['productos']['pvp'];
                if($v['productos']['foto_tartas_personalizadas'] == 1){

                    $pvp_productos += 400;

                }
                $llft = $v['productos']['foto_tartas_personalizadas'] == 1 ? 'Sí' : 'No';
                $li_producto .= '<li>
                                    <a href="#" class="image">
                                        <img src="'.$ft.'" alt="Cart product Image">
                                    </a>
                                    <div class="content">
                                        <b>'.utf8_encode($dp[$k]['nombre_producto']).'</b> <br>
                                        '.$v['productos']['porciones'].' porciones x '.nf($v['productos']['pvp']).' &euro; <br>
                                        Capa inferior : <b>'.$v['productos']['capa_inferior_personalizada'].'</b><br>
                                        Capa media : <b>'.$v['productos']['capa_media_personalizada'].'</b><br>
                                        Capa superior : <b>'.$v['productos']['capa_superior_personalizada'].'</b><br>
                                        Borde exterior : <b>'.$v['productos']['borde_exterior_personalizada'].'</b><br>
                                        <b>¿Lleva Foto? </b> : '. $llft;

                                        if($v['productos']['mensaje_tarta'] != ''){

                                                $li_producto .= '<br><b>Mensaje Tarta :</b> <br>'.$v['productos']['mensaje_tarta'];
                                            }

                                            if($v['productos']['comentarios_tarta'] != ''){

                                                $li_producto .= '<br><b>Comentarios Adicionales:</b> <br>'.$v['productos']['comentarios_tarta'];
                                            }
                                        
                $li_producto .= '                        
                                        <a href="#" class="remove" name="borrar_linea_carrito" key="'.$k.'">×</a>
                                    </div>
                                </li>';


            }else if($v['id_familia'] == 11){ // if($id_familia == 6){


                $dp[$k] = $DB->select('nombre_producto,fotos,pvp','productos_web','id = '.$v['productos']['id_producto']);
                $dp[$k] = $dp[$k][f][0];

                if($dp[$k]['fotos'] == ''){

                    $ft = '/images/logo_argentina_min.png';

                }else{

                    $arr_f = json_decode($dp[$k]['fotos'],true);
                    $ft = is_array($arr_f) && isset($arr_f[0]) ? $arr_f[0] : '/images/logo_argentina_min.png';

                }

                $pvp_productos += $v['productos']['pvp'];
                $li_producto .= '<li>
                                    <a href="#" class="image">
                                        <img src="'.$ft.'" alt="Cart product Image">
                                    </a>
                                    <div class="content">
                                        <b class="text-danger">* Oferta Especial</b> <br>
                                        <b>'.utf8_encode($dp[$k]['nombre_producto']).'</b> <br>
                                        '.nf($v['productos']['pvp']).' &euro;
                                        <a href="#" class="remove" name="borrar_linea_carrito" key="'.$K.'">×</a>
                                    </div>
                                </li>';


                # una vez que esten los productos añadidos, tenemos que cerrar la modal
                # add-to-bandeja_pastas


            } // if($id_familia == 11){

            $n_arr[$n_key] = array(
                                'id_familia'=>$v['id_familia'],
                                'productos'=>$v['productos']
                                );

            $n_key++;
            $n_lineas++;

        } // if($k != $key){

    } // foreach ($arr as $k => $v) {

    $val = json_encode($n_arr);
    setcookie('carrito', $val, time()+864000*365,"/");
    $_COOKIE['carrito']=$val;


    if($pvp_productos < 1200){

        $pvp_envio = 399;
        $pvp_carrito = $pvp_productos+399;

    }else{

        $pvp_envio = 0;
        $pvp_carrito = $pvp_productos;

    }

    if($n_lineas == 0){

        $li_producto = '<li>
                            <h5>* Tu carrito esta Vacio</h5>
                        </li>';

        setcookie('carrito', '',time()-100,"/");
    }


    script('
            $("#span_carrito").html("'.$n_lineas.'");
            $("#pvp_productos").html("'.nf($pvp_productos).'");
            $("#pvp_productos").attr("pvp","'.$pvp_productos.'");
            $("#pvp_envio").html("'.nf($pvp_envio).'");
            $("#pvp_envio").attr("pvp","'.$pvp_envio.'");
            $("#pvp_carrito").html("'.nf($pvp_carrito).'");
            $("#pvp_carrito").attr("pvp","'.$pvp_carrito.'");

            $("#span_precio_final").html("'.nf($pvp_carrito).'");
            $("#span_envio").html("'.nf($pvp_envio).'");
            $("#span_envio").attr("pvp","'.($pvp_envio).'");
            $("#span_productos").html("'.nf($span_productos).'");
            $("#span_productos").attr("pvp","'.($span_productos).'");
            $("#importe_final").val("'.($pvp_carrito).'");
               
            $("#ul_carrito").html(\''.str_replace($clean, '', $li_producto).'\');

        ');

//}else if(isset($borrar_linea_carrito)){
}else if(isset($subir_imagen_tartas)){  

    try {

        $dir = '../img/pedidos_web/';
        $fichero_subido = $dir . basename($_FILES['upload']['name']);


        # Subimos el fichero
        if(move_uploaded_file($_FILES["upload"]["tmp_name"],$fichero_subido)){

            $new_name = time().'.jpg';

            rename($dir.$_FILES['upload']['name'],$dir.$new_name);
            // ftp_rename($conn_id, $_FILES["upload"]["name"], $new_name);

            $imge = 'https://pastelerialaargentina.com/new_app/img/pedidos_web/'.$new_name;
            list($width, $height, $type, $attr) = getimagesize($imge);

            if($width>3000 || $height>3000){

                throw new Exception('La imágen no puede superar los 500px de alto ni los 750 de ancho');

            }else{

                // fotos_subidas este es el div sobre el que vamos a tirar la foto
                script('$("[name=\'fotos_subidas_'.$key.'\']").append(\'<div class="alert alert-success alert-dismissible fade show mt-3 col-4"  role="alert" id="alert_'.time().'" style="margin : auto;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><img src="'.$imge.'" alt="" style="max-width : 120px;"><input type="hidden" foto="'.$new_name.'" key="'.$key.'" name="foto_sum"></div>\');');
            }

        }else{

            throw new Exception("No ha sido posible subir el fichero");
           
        }


    } catch (Exception $e) {
        
        notify($e->getMessage(),'danger');
        
    }


// }else if(isset($subir_imagen_tartas)){ 
}else if(isset($subir_imagen_emails)){  

    try {

        $dir = '../img/emails/';
        $fichero_subido = $dir . basename($_FILES['upload']['name']);


        # Subimos el fichero
        if(move_uploaded_file($_FILES["upload"]["tmp_name"],$fichero_subido)){

            $new_name = time().'.jpg';

            rename($dir.$_FILES['upload']['name'],$dir.$new_name);
            // ftp_rename($conn_id, $_FILES["upload"]["name"], $new_name);

            $imge = 'https://pastelerialaargentina.com/new_app/img/emails/'.$new_name;
            list($width, $height, $type, $attr) = getimagesize($imge);

            if($width>3000 || $height>3000){

                throw new Exception('La imágen no puede superar los 500px de alto ni los 750 de ancho');

            }else{

                // fotos_subidas este es el div sobre el que vamos a tirar la foto
                script('$("#fotos_subidas").append(\'<div class="alert alert-success alert-dismissible fade show mt-3"  role="alert" id="alert_'.time().'" style="margin : auto;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><img src="'.$imge.'" alt="" style="max-width : 120px;"></div>\');
                    $("#foto_especial").val("'.$new_name.'");
                    ');
            }

        }else{

            throw new Exception("No ha sido posible subir el fichero");
           
        }


    } catch (Exception $e) {
        
        notify($e->getMessage(),'danger');
        
    }


// }else if(isset($subir_imagen_emails)){ 
}else if(isset($login_carrito)){ 

        $DB->query('select * from clientes where email like "'.$email.'"');


        if($DB->rows()==0){

            notify("El usuario no existe","danger");

        }else if($DB->rows()==1){

            $d=$DB->fetch();

                if(!password_verify($pass,$d['password'])){

                    notify('la contraseña es incorrecta',"danger");

                }else if(password_verify($pass,$d['password'])){

                    script("
                            $('#paso_1_div').click();
                            $('#direccion').val('".utf8_encode($d['direccion'])."');
                            $('#cp').val('".$d['cp']."');
                            $('#id_cliente').val('".$d['id']."');
                            $('#email').val('".$d['email']."');
                            $('#nombre').val('".utf8_encode($d['nombre'])."');
                            $('#telefono').val('".$d['telefono']."');
                            $('#password').val('".$d['password']."');
                            $('#modal_login_carrito').modal('hide');
                            $('#txt_login').removeClass('d-none');
                        ");

                }


        }

// }else if(isset($login_carrito)){ 
}else if(isset($finalizar_compra)){ 

// fotos: 1612194358.jpg-1
// id_cliente: 33
// fecha_nacimiento: 
// email: cafeteria3m@gmail.com
// nombre: ALEX Valencia Ventoso
// telefono: 699920134
// cp: 06330
// direccion: Nuestra sra del valle 1
// publicidad: 1
// poblacion: Zafra
// tipo_pago: Tarjeta

    try {
        
        $cliente_registrado = 0;
        if($id_cliente == ''){

            $cc = $DB->select('id','clientes',' email like "'.$email.'"');
            if($cc[r] > 0){

                $id_cliente = $cc[f][0]['id'];
                $cliente_registrado = 1;
                $DB->openTable('clientes');
                $DB->edit($id_cliente);
                $DB->ta['nombre'] = $DB->textCheck($nombre);
                $DB->ta['fecha_nacimiento'] = formatear_fecha($fecha_nacimiento,i,m);
                $DB->ta['direccion'] = $DB->textCheck($direccion);
                $DB->ta['email'] = $DB->textCheck($email);
                $DB->ta['telefono'] = $DB->textCheck($telefono);
                $DB->ta['cp'] = $DB->textCheck($cp);
                $DB->ta['publicidad'] = $DB->textCheck($publicidad);
                $DB->ta['nombre'] = $DB->textCheck($nombre);
                $DB->ta['id_provincia'] = 6;
                $DB->ta['id_delegacion'] = 6;
                $DB->ta['fecha'] = date('Y-m-d');
                $pbl = $poblacion == 'Otras' ? $otra_poblacion : $poblacion;
                $DB->ta['poblacion'] = $DB->textCheck($pbl);
                $DB->update();

                $cf = $DB->select('id','fechas_especiales','id_cliente = '.$id_cliente.' and tipo like "cumple"');
                if($cf[r] > 0){

                    $DB->openTable('fechas_especiales');
                    $DB->edit($cf[f][0]['id']);
                    list($dia,$mes,$year) = explode('/',$fecha_nacimiento);
                    $DB->ta['mes'] = $mes;
                    $DB->ta['dia'] = $dia;
                    $DB->update();

                }else{

                    $DB->openTable('fechas_especiales');
                    list($dia,$mes,$year) = explode('/',$fecha_nacimiento);
                    $DB->ta['mes'] = $mes;
                    $DB->ta['dia'] = $dia;
                    $DB->ta['id_cliente'] = $id_cliente;
                    $DB->ta['tipo'] = 'cumple';
                    $DB->insert();

                }

            }else{

                $DB->openTable('clientes');
                $DB->ta['nombre'] = $DB->textCheck($nombre);
                $DB->ta['fecha_nacimiento'] = formatear_fecha($fecha_nacimiento,i,m);
                $DB->ta['direccion'] = $DB->textCheck($direccion);
                $DB->ta['email'] = $DB->textCheck($email);
                $DB->ta['telefono'] = $DB->textCheck($telefono);
                $DB->ta['cp'] = $DB->textCheck($cp);
                $DB->ta['publicidad'] = $DB->textCheck($publicidad);
                $DB->ta['nombre'] = $DB->textCheck($nombre);
                $DB->ta['id_provincia'] = 6;
                $DB->ta['id_delegacion'] = 6;
                $DB->ta['fecha'] = date('Y-m-d');
                $pbl = $poblacion == 'Otras' ? $otra_poblacion : $poblacion;
                $DB->ta['poblacion'] = $DB->textCheck($pbl);
                $id_cliente = $DB->insert();

            }

        } // if($id_cliente == ''){

        // alert($id_cliente);
        $he = $hora_entrega == 'Mañana' ? '12:00' : '18:00';
        $pc = $tipo_pago == 'En mano' ? 'No' : 1;
        $DB->openTable('pedidos');
        $DB->ta['id_delegacion'] = 6;
        $DB->ta['local_entrega'] = $tienda_de_recogida;
        $DB->ta['fecha_entrega'] = formatear_fecha($fecha_entrega,i,m);
        $DB->ta['hora_entrega'] = $he;
        $DB->ta['id_usuario'] = 47;
        $DB->ta['id_cliente'] = $id_cliente;
        $DB->ta['id_usuario_creacion'] = 47;
        $DB->ta['pedido_cobrado'] = $pc;
        $DB->ta['on_line'] = 1;
        $DB->ta['importe_final'] = nf($importe_final);
        $DB->ta['productos'] = $_COOKIE['carrito'];

        # tenemos que montar 2 arr 1 de tartas y otro de productos

        $tartas = array();
        $productos = array();
        $arr = json_decode($_COOKIE['carrito'],true);
        $ip = 0;
        $it = 0;

        foreach ($arr as $k => $v) {

            if($v['id_familia'] == 6 || $v['id_familia'] == 7 || $v['id_familia'] == 8 || $v['id_familia'] == 9){

                ## Tartas 
                # 6 -> personalizada 
                # 7 -> galletas
                # 8 -> pastelon
                # 9 -> predeterminada


                # {"1":{"nombre":"Tarta Personalizada","pvp":"18.00","tarta":"personalizada","predeterminada":"0","porciones":"1","incluir_foto":"No","incluir_mensaje":"OJO!!!!ES UN NUM,1 ESPECIAL CHOCOLATE BLANCO SIN HUEVO KINDER,RELLENO DE CHOCOLATE NORMAL Y POR FUERA CHOCOLATE BLANCO","comentarios":"PONER TEXTO FELICIDADES ALVARO","capa_inferior":"Chocolate","capa_media":"Chocolate","capa_superior":"Choc. Kinder","borde_exterior":"Sin Borde"}}

                if($v['id_familia'] == 6){

                    $tartas[$it]['nombre'] = 'Tarta Personalizada';
                    $tartas[$it]['predeterminada'] = '0';
                    $if = $v['productos']['foto_tartas_personalizadas'] = 1 ? 'Si' : 'No';
                    $tartas[$it]['incluir_foto'] = $if;
                    $tartas[$it]['capa_inferior'] = $v['productos']['capa_inferior_personalizada'];
                    $tartas[$it]['capa_media'] = $v['productos']['capa_media_personalizada'];
                    $tartas[$it]['capa_superior'] = $v['productos']['capa_superior_personalizada'];
                    $tartas[$it]['borde_exterior'] = $v['productos']['borde_exterior_personalizada'];
                    $tartas[$it]['tarta'] = 'personalizada';
                    $tartas[$it]['pvp'] = nf($v['productos']['pvp']);
                    $tartas[$it]['porciones'] = $v['productos']['tamano'];
                    $im = $v['productos']['mensaje_tarta'] == '' ? 'No' : $v['productos']['mensaje_tarta'];
                    $tartas[$it]['incluir_mensaje'] = $im;
                    $ct = $v['productos']['comentarios_tarta'] == '' ? 'No' : $v['productos']['comentarios_tarta'];
                    $tartas[$it]['comentarios_tarta'] = $ct;

                }else if($v['id_familia'] == 7){

                    $tartas[$it]['nombre'] = 'Tarta Galletas';
                    $tartas[$it]['predeterminada'] = '0';
                    $if = $v['productos']['foto_tartas_galletas'] = 1 ? 'Si' : 'No';
                    $tartas[$it]['incluir_foto'] = $if;
                    $tartas[$it]['tarta'] = 'galletas';
                    $tartas[$it]['pvp'] = nf($v['productos']['pvp']);
                    $tartas[$it]['porciones'] = $v['productos']['tamano'];
                    $im = $v['productos']['mensaje_tarta'] == '' ? 'No' : $v['productos']['mensaje_tarta'];
                    $tartas[$it]['incluir_mensaje'] = $im;
                    $ct = $v['productos']['comentarios_tarta'] == '' ? 'No' : $v['productos']['comentarios_tarta'];
                    $tartas[$it]['comentarios_tarta'] = $ct;

                }else if($v['id_familia'] == 8){

                    $tartas[$it]['nombre'] = 'Tarta Pastelon';
                    $tartas[$it]['predeterminada'] = '0';
                    $if = $v['productos']['foto_pastelon'] = 1 ? 'Si' : 'No';
                    $tartas[$it]['incluir_foto'] = $if;
                    $tartas[$it]['tarta'] = 'pastelon';
                    $tartas[$it]['pvp'] = nf($v['productos']['pvp']);
                    $tartas[$it]['porciones'] = $v['productos']['tamano'];
                    $im = $v['productos']['mensaje_tarta'] == '' ? 'No' : $v['productos']['mensaje_tarta'];
                    $tartas[$it]['incluir_mensaje'] = $im;
                    $ct = $v['productos']['comentarios_tarta'] == '' ? 'No' : $v['productos']['comentarios_tarta'];
                    $tartas[$it]['comentarios_tarta'] = $ct;

                }else if($v['id_familia'] == 9){

                    $dp = $DB->select('nombre_producto','productos_web','id = '.$v['productos']['id_producto']);
                    $tartas[$it]['nombre'] = 'Tarta '.utf8_encode($dp[f][0]['nombre_producto']);
                    $tartas[$it]['predeterminada'] = '1';
                    $if = $v['productos']['foto_tartas_predeterminadas'] = 1 ? 'Si' : 'No';
                    $tartas[$it]['incluir_foto'] = $if;
                    $tartas[$it]['tarta'] = utf8_encode($dp[f][0]['nombre_producto']);
                    $tartas[$it]['pvp'] = nf($v['productos']['pvp']);
                    $tartas[$it]['porciones'] = 'predeterminadas';
                    $im = $v['productos']['mensaje_tarta'] == '' ? 'No' : $v['productos']['mensaje_tarta'];
                    $tartas[$it]['incluir_mensaje'] = $im;
                    $ct = $v['productos']['comentarios_tarta'] == '' ? 'No' : $v['productos']['comentarios_tarta'];
                    $tartas[$it]['comentarios_tarta'] = $ct;

                }

                $it ++;

            }else{ // if($v['id_familia'] == 6 ||

                ## Productos 4,5,10
                # {"1":{"nombre":"SAN BLAS CHOCO RELLENAS","cantidad":"12","pvp":"26.40"},"2":{"nombre":"SAN BLAS RELLENAS","cantidad":"6","pvp":"13.20"},"3":{"nombre":"SAN BLAS CHOCO","cantidad":"6","pvp":"12.00"},"4":{"nombre":"BANDEJAS DE PASTELINOS (25 UNIDADES)","cantidad":"1","pvp":"12.00"},"5":{"nombre":"BOLLOS RELLENOS","cantidad":"12","pvp":"9.60"}}

                ### {"id_familia":"4","productos":[
                #{"id_producto":"8","id_familia":"4","pvp":"100","cantidad":"3"},
                #{"id_producto":"7" ,"id_familia":"4","pvp":"80","cantidad":"3"}]},

                ### {"id_familia":5,"productos":{"id_producto":"72","pvp":"200","unidades":"1"}},
                ### {"id_familia":10,"productos":{"id_producto":"12","pvp":"70","unidades":"3"}}]

                if($v['id_familia'] == 4){

                    $nombre = 'Bandeja Surtidos ';
                    $pvp_bandeja = 0;
                    $cantidad_bandeja = 0;
                    foreach ($v['productos'] as $l => $val) {

                        $pvp_bandeja += ($val['pvp']*$val['cantidad']);
                        $cantidad_bandeja += $val['cantidad'];
                        $dp = $DB->select('nombre_producto','productos_web','id = '.$val['id_producto']);
                        $nombre .= '<br> <b>'.utf8_encode($dp[f][0]['nombre_producto']).' ('.$val['cantidad'].' Unds)</b>';

                    } // foreach ($v['productos'] as $key => $value) {
                    $productos[$ip]['nombre'] = $nombre;
                    $productos[$ip]['cantidad'] = $cantidad_bandeja;
                    $productos[$ip]['pvp'] = nf($pvp_bandeja);

                }else{ // if($v['id_familia'] == 4){

                    $dp = $DB->select('nombre_producto','productos_web','id = '.$v['productos']['id_producto']);
                    $productos[$ip]['nombre'] = utf8_encode($dp[f][0]['nombre_producto']);
                    $productos[$ip]['cantidad'] = $v['productos']['unidades'];
                    $productos[$ip]['pvp'] = nf(($v['productos']['pvp']*$v['productos']['unidades']));

                } // if($v['id_familia'] == 4){.....

                $ip ++;

            } // if($v['id_familia'] == 6 || .....

        } // foreach ($arr as $k => $v) {

        $DB->ta['tarta'] = json_encode($tartas,JSON_UNESCAPED_UNICODE);
        $DB->ta['producto'] = json_encode($productos,JSON_UNESCAPED_UNICODE);
        $DB->ta['fotos'] = $fotos;
        $DB->ta['tipo_entrega'] = $tipo_entrega;

        $id_pedido = $DB->insert();

        if($tipo_pago == 'En mano'){

            $comercio = array(

                    'nombre' => 'Pastelería la Argentina',
                    'tpv_pass' => 'sq7HjrUOBfKmC576ILgskD5srU870gJ7',
                    'email' => 'web@pastelerialaargentina.com',
                    'web' => 'pastelerialaargentina.com',
                    'color_email' => '190098'

                );

            $mail = new phpmailer($comercio['email']);  
            $mail->setFrom($comercio['email'],$comercio['nombre']); 
            $mail->AddAddress('lulomon26@hotmail.com');
            $mail->AddCC('pastelerialaargentina@gmail.com');
            // $mail->AddAddress('desarrollo@ckcreativos.com');

            $mail->Subject=$comercio['nombre']." - Confirmación de Compra"; 
            $dp = $DB->inner_join('c.nombre,c.email,c.telefono,c.direccion,cp,c.poblacion,p.*','pedidos p inner join clientes c','p.id_cliente = c.id where p.id = '.$id_pedido);
            $dp = $dp[f][0];

            $entrega = $tipo_entrega == 'recogida_en_tienda' ? 'Recogida en tienda' : 'Entrega a Domicilio';

            $txt = 'Hola Compañer@s,<br><br>
                    Hemos recibido un pedido on-line, estos son los datos del Pedido:<br><br>
                    <b>Tipo de pago</b> : En mano.<br>
                    <b>Tipo de entrega</b> : '.$entrega.'.<br>
                    <h3><u>Datos del Cliente</u></h3>
                    <b>Cliente</b> : '.utf8_encode($dp['nombre']).'<br>
                    <b>Teléfono</b> : '.($dp['telefono']).'<br>
                    <b>Dirección</b> : '.utf8_encode($dp['direccion']).'<br>
                    <b>Población</b> : '.utf8_encode($dp['poblacion']).'<br>
                    <b>Código Postal</b> : '.utf8_encode($dp['cp']).'<br>
                    <h3><u>Productos</u></h3>
                    <table style="width : 100%">
                        <tbody style="width : 100%">
                            <tr style="border: 1px solid #'.$comercio['color_email'].';">
                                <td style="border: #'.$comercio['color_email'].' 1px solid; text-align: center ; padding: 5px; width : 50%;">Producto</td>
                                <td style="border: #'.$comercio['color_email'].' 1px solid; text-align: center ; padding: 5px;">Uni/Porciones</td>
                                <td style="border: #'.$comercio['color_email'].' 1px solid; text-align: center ; padding: 5px;">Pvp</td>
                                <td style="border: #'.$comercio['color_email'].' 1px solid; text-align: center ; padding: 5px;">Pr.Final</td>
                            </tr>';

                    if($dp['fotos'] != ''){

                        $arr_f = explode(',',$dp['fotos']);
                        $fotos = array();
                        foreach ($arr_f as $key => $value) {
                            
                            list($ft,$in) = explode('-',$value);
                            $fotos[$in] = $ft;
                        }

                    }
                    $pr = json_decode($dp['productos'],true);
                    $p = 1;
                    $pf = 0;
                    foreach ($pr as $k => $v) {

                        if($v['id_familia'] != 4){

                            $d = $DB->select('*','productos_web','id = '.$v['productos']['id_producto']);
                            $d = $d[f][0];
                            if($v['id_familia'] == 5 || $v['id_familia'] == 10 || $v['id_familia'] == 9){

                                $pf += ($v['productos']['pvp']*$v['productos']['unidades']); 
                                $pvp = ($v['productos']['pvp']*$v['productos']['unidades']);
                                $pvp_unidad = $v['productos']['pvp'];

                            }else{

                                $pf += $v['productos']['pvp'];
                                $pvp = $v['productos']['pvp'];
                                $pvp_unidad = $v['productos']['pvp'];

                            }

                            $cartel = $d['nombre_producto']; 
                            $cartel .= $v['id_familia'] == 11 ? '<span style="color : red;"><br>Oferta Especial</span>' : '';
                            if($v['id_familia'] == 6 || $v['id_familia'] == 7 || $v['id_familia'] == 8){

                                $unidades = $v['productos']['tamano'];

                            }else{

                                $unidades = $v['productos']['unidades'];

                            }


                            if(isset($v['productos']['foto_tartas_personalizadas']) && $v['productos']['foto_tartas_personalizadas'] == 1){

                                $cartel .= '<br><img src="https://pastelerialaargentina.com/new_app/img/pedidos_web/'.$fotos[$k].'" alt="" style="width : 100%;"><br>
                                <a href="https://pastelerialaargentina.com/new_app/img/pedidos_web/'.$fotos[$k].'" >Descargar Imagen</a>';

                            }

                            if(isset($v['productos']['foto_tartas_galletas']) && $v['productos']['foto_tartas_galletas'] == 1){

                                $cartel .= '<br><img src="https://pastelerialaargentina.com/new_app/img/pedidos_web/'.$fotos[$k].'" alt="" style="width : 100%;"><br>
                                <a href="https://pastelerialaargentina.com/new_app/img/pedidos_web/'.$fotos[$k].'" >Descargar Imagen</a>';
                            }

                            if(isset($v['productos']['foto_pastelon']) && $v['productos']['foto_pastelon'] == 1){

                                $cartel .= '<br><img src="https://pastelerialaargentina.com/new_app/img/pedidos_web/'.$fotos[$k].'" alt="" style="width : 100%;"><br>
                                <a href="https://pastelerialaargentina.com/new_app/img/pedidos_web/'.$fotos[$k].'" >Descargar Imagen</a>';
                            }

                            if(isset($v['productos']['foto_tartas_predeterminadas']) && $v['productos']['foto_tartas_predeterminadas'] == 1){

                                $cartel .= '<br><img src="https://pastelerialaargentina.com/new_app/img/pedidos_web/'.$fotos[$k].'" alt="" style="width : 100%;"><br>
                                <a href="https://pastelerialaargentina.com/new_app/img/pedidos_web/'.$fotos[$k].'" >Descargar Imagen</a>';
                            }

                        }else{

                            $cartel = 'Bandeja Pasteles Surtidos : ';
                            $pvp_bandeja = 0;
                            $cantidad_bandeja = 0;
                            foreach ($v['productos'] as $l => $val) {

                                $pvp_bandeja += ($val['pvp']*$val['cantidad']);
                                $cantidad_bandeja += $val['cantidad'];
                                $dp = $DB->select('nombre_producto','productos_web','id = '.$val['id_producto']);
                                $cartel .= '<br> <b>'.utf8_encode($dp[f][0]['nombre_producto']).' ('.$val['cantidad'].' Unds)</b>';

                            } // foreach ($v['productos'] as $key => $value) { 

                            $unidades = 1;
                            $pf += $pvp_bandeja;
                            $pvp = $pvp_bandeja;
                            $pvp_unidad = $pvp_bandeja;
                        }


                        $txt.='
                                    <tr style="border: #'.$comercio['color_email'].' 1px solid ;">
                                        <td style="border: #'.$comercio['color_email'].' 1px solid;  padding: 5px; width : 50%;">'.utf8_encode($cartel).'</td>
                                        <td style="border: #'.$comercio['color_email'].' 1px solid; text-align: right; padding: 5px;">'.$unidades.'</td>
                                        <td style="border: #'.$comercio['color_email'].' 1px solid; text-align: right; padding: 5px;">'.nf($pvp_unidad).'&euro;</td>
                                        <td style="border: #'.$comercio['color_email'].' 1px solid; text-align: right; padding: 5px;">'.nf($pvp).'&euro;</td>
                                    </tr>';

                        $p++;

                    } // foreach ($pr as $key => $value) {

                    $txt .= '
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="border: #'.$comercio['color_email'].' 1px solid; text-align: right; padding: 5px;">'.number_format(($pf/100),2,'.','').'&euro;</td>
                                </tr>
                            </tbody>
                        </table>
                        <br><br>
                        La información completa de cada uno de las tartas que pueda contener el pedido, saldrá en la comanda como en el resto de los pedidos';

            $mail->Body=body_email(t,"",1,$txt);
            $mail->send();

            #email al cliente
            $dp = $DB->inner_join('c.nombre,c.email,c.telefono,c.direccion,cp,c.poblacion,p.*','pedidos p inner join clientes c','p.id_cliente = c.id where p.id = '.$id_pedido);
            $dp = $dp[f][0];
            $mail_c = new phpmailer($comercio['email']);  
            $mail_c->setFrom($comercio['email'],$comercio['nombre']); 
            $mail_c->AddAddress($dp['email']);
            $mail_c->Subject=$comercio['nombre']." - Confirmación de Compra";
            list($nombre,$apellidos) = explode(' ',$dp['nombre']);

            // tipo_pago
            // tipo_entrega

            $frase_1 = $tipo_entrega == 'recogida_en_tienda' ? 'podrás recogerlo en la tienda seleccionada' : 'lo recibirás en casa';
            $frase_2 = $tipo_entrega == 'recogida_en_tienda' ? 'en la tienda' : 'al mensajero';
            $txt_c = 'Hola '.utf8_encode($nombre).',<br><br>
                    Te confirmamos que hemos recibido correctamente tu pedido, '.$frase_1.' en la fecha que nos has indicado. <br><br>Has seleccionado pagar en mano '.$frase_2.'.<br><br>Te recodamos los datos que nos has facilitado:
                    <br>
                    <h3><u>Tus Datos</u></h3>
                    <b>Cliente</b> : '.utf8_encode($dp['nombre']).'<br>
                    <b>Teléfono</b> : '.($dp['telefono']).'<br>
                    <b>Dirección</b> : '.utf8_encode($dp['direccion']).'<br>
                    <b>Población</b> : '.utf8_encode($dp['poblacion']).'<br>
                    <b>Código Postal</b> : '.utf8_encode($dp['cp']).'<br>
                    <h3><u>Productos</u></h3>
                    <table style="width : 100%">
                        <tbody style="width : 100%">
                            <tr style="border: 1px solid #'.$comercio['color_email'].';">
                                <td style="border: #'.$comercio['color_email'].' 1px solid; text-align: center ; padding: 5px; width : 50%;">Producto</td>
                                <td style="border: #'.$comercio['color_email'].' 1px solid; text-align: center ; padding: 5px;">Uni/Porciones</td>
                                <td style="border: #'.$comercio['color_email'].' 1px solid; text-align: center ; padding: 5px;">Pvp</td>
                                <td style="border: #'.$comercio['color_email'].' 1px solid; text-align: center ; padding: 5px;">Pr.Final</td>
                            </tr>';

                    $pr = json_decode($dp['productos'],true);
                    $p = 1;
                    $pf = 0;
                    foreach ($pr as $k => $v) {

                        if($v['id_familia'] != 4){

                            $d = $DB->select('*','productos_web','id = '.$v['productos']['id_producto']);
                            $d = $d[f][0];
                            if($v['id_familia'] == 5 || $v['id_familia'] == 10 || $v['id_familia'] == 9){

                                $pf += ($v['productos']['pvp']*$v['productos']['unidades']); 
                                $pvp = ($v['productos']['pvp']*$v['productos']['unidades']);
                                $pvp_unidad = $v['productos']['pvp'];

                            }else{

                                $pf += $v['productos']['pvp'];
                                $pvp = $v['productos']['pvp'];
                                $pvp_unidad = $v['productos']['pvp'];

                            }

                            $cartel = $d['nombre_producto']; 
                            $cartel .= $v['id_familia'] == 11 ? '<span style="color : red;"><br>Oferta Especial</span>' : '';

                            if($v['id_familia'] == 6 || $v['id_familia'] == 7 || $v['id_familia'] == 8){

                                $unidades = $v['productos']['tamano'];

                            }else{

                                $unidades = $v['productos']['unidades'];

                            }


                            if(isset($v['productos']['foto_tartas_personalizadas']) && $v['productos']['foto_tartas_personalizadas'] == 1){

                                $cartel .= '<br><img src="https://pastelerialaargentina.com/new_app/img/pedidos_web/'.$fotos[$k].'" alt="" style="width : 100%;">';

                            }

                            if(isset($v['productos']['foto_tartas_galletas']) && $v['productos']['foto_tartas_galletas'] == 1){

                                $cartel .= '<br><img src="https://pastelerialaargentina.com/new_app/img/pedidos_web/'.$fotos[$k].'" alt="" style="width : 100%;">';
                            }

                            if(isset($v['productos']['foto_pastelon']) && $v['productos']['foto_pastelon'] == 1){

                                $cartel .= '<br><img src="https://pastelerialaargentina.com/new_app/img/pedidos_web/'.$fotos[$k].'" alt="" style="width : 100%;">';
                            }

                            if(isset($v['productos']['foto_tartas_predeterminadas']) && $v['productos']['foto_tartas_predeterminadas'] == 1){

                                $cartel .= '<br><img src="https://pastelerialaargentina.com/new_app/img/pedidos_web/'.$fotos[$k].'" alt="" style="width : 100%;">';
                            }

                        }else{

                            $cartel = 'Bandeja Pasteles Surtidos : ';
                            $pvp_bandeja = 0;
                            $cantidad_bandeja = 0;
                            foreach ($v['productos'] as $l => $val) {

                                $pvp_bandeja += ($val['pvp']*$val['cantidad']);
                                $cantidad_bandeja += $val['cantidad'];
                                $dp = $DB->select('nombre_producto','productos_web','id = '.$val['id_producto']);
                                $cartel .= '<br> <b>'.utf8_encode($dp[f][0]['nombre_producto']).' ('.$val['cantidad'].' Unds)</b>';

                            } // foreach ($v['productos'] as $key => $value) { 

                            $unidades = 1;
                            $pf += $pvp_bandeja;
                            $pvp = $pvp_bandeja;
                            $pvp_unidad = $pvp_bandeja;
                        }


                        $txt_c.='
                                    <tr style="border: #'.$comercio['color_email'].' 1px solid ;">
                                        <td style="border: #'.$comercio['color_email'].' 1px solid;  padding: 5px; width : 50%;">'.utf8_encode($cartel).'</td>
                                        <td style="border: #'.$comercio['color_email'].' 1px solid; text-align: right; padding: 5px;">'.$unidades.'</td>
                                        <td style="border: #'.$comercio['color_email'].' 1px solid; text-align: right; padding: 5px;">'.nf($pvp_unidad).'&euro;</td>
                                        <td style="border: #'.$comercio['color_email'].' 1px solid; text-align: right; padding: 5px;">'.nf($pvp).'&euro;</td>
                                    </tr>';

                        $p++;

                    } // foreach ($pr as $key => $value) {

                    $txt_c .= '
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="border: #'.$comercio['color_email'].' 1px solid; text-align: right; padding: 5px;">'.number_format(($pf/100),2,'.','').'&euro;</td>
                                </tr>
                            </tbody>
                        </table>
                        <br><br>
                        Le agradecemos la confianza depositada en nosotros y esperamos volver a verte pronto por '.($comercio['nombre']).'. <br><br>Un cordial saludo del Equipo de '.($comercio['nombre']).'.';

            $mail_c->Body=body_email(t,"",1,$txt_c);
            $mail_c->send();

            script('location.href="https://pastelerialaargentina.com/gracias.php?id='.$id_pedido.'"');

        }else{

            include('redsysHMAC256_API_PHP_4.0.2/apiRedsys.php');

            $miObj = new RedsysAPI;
            // $action='https://sis.redsys.es/sis/realizarPago';//produccion
            $action='https://sis-t.redsys.es:25443/sis/realizarPago';//pruebas

            
            $fuc= '036093128';
            $kc = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7';
            $terminal="001";
            $moneda="978";
            $trans="0"; // cobro 
            //$trans="3"; // devolucion automatica 
            $amount=$importe_final;
            
            $id=time();

            $DB->query("insert into comunicacion_tpv 
            (id,DS_MERCHANT_ORDER,total,id_pedido,finalizada,fecha,id_via_cobro) values 
            (NULL,
            '".strval($id)."',
            ".$amount.",
            ".$id_pedido.",
            0,
            '".date('Y-m-d H:i:s')."',
            2)");

            $urlOK = "https://pastelerialaargentina.com/confirmacion_de_pagos.php?pago=1";
            $urlKO = "https://pastelerialaargentina.com/confirmacion_de_pagos.php?pago=0";
            $url_url="https://pastelerialaargentina.com";

            $miObj->setParameter("DS_MERCHANT_AMOUNT",$amount);
            $miObj->setParameter("DS_MERCHANT_ORDER",strval($id));
            $miObj->setParameter("DS_MERCHANT_MERCHANTCODE",$fuc);
            $miObj->setParameter("DS_MERCHANT_CURRENCY",$moneda);
            $miObj->setParameter("DS_MERCHANT_TRANSACTIONTYPE",$trans);
            $miObj->setParameter("DS_MERCHANT_TERMINAL",$terminal);
            $miObj->setParameter("DS_MERCHANT_MERCHANTURL",$url_url);
            $miObj->setParameter("DS_MERCHANT_URLOK",$urlOK);   
            $miObj->setParameter("DS_MERCHANT_URLKO",$urlKO);

            //Datos de configuración
            $version="HMAC_SHA256_V1";


            // Se generan los par&aacute;metros de la petición
            // $request = "";
            $params = $miObj->createMerchantParameters();
            $signature = $miObj->createMerchantSignature($kc);

            //if(!isset($_SESSION['ip_admin'])){

               
                echo "<form name='frm' action='".$action."' method='POST' >\n";
                echo "<input type='hidden' name='Ds_SignatureVersion' value='".$version."'/>\n";
                echo "<input type='hidden' name='Ds_MerchantParameters' value='".$params."'/>\n";
                echo "<input type='hidden' name='Ds_Signature' value='".$signature."'/>\n";
                echo "</form>\n";
                script('$("[name=\'frm\']").submit();');
                
            //}

        }


    } catch (Exception $e) {
        
    }

// }else if(isset($finalizar_compra)){ 
}else if(isset($reintentar_pago)){ 

    $DB->query('insert into pedidos select * from pedidos_fallidos where id = '.$id_pedido);

    $dp = $DB->select('importe_final','pedidos','id = '.$id_pedido);
    include('redsysHMAC256_API_PHP_4.0.2/apiRedsys.php');

    $miObj = new RedsysAPI;
    // $action='https://sis.redsys.es/sis/realizarPago';//produccion
    $action='https://sis-t.redsys.es:25443/sis/realizarPago';//pruebas

    
    $fuc= '036093128';
    $kc = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7';
    $terminal="001";
    $moneda="978";
    $trans="0"; // cobro 
    //$trans="3"; // devolucion automatica 
    $amount=($dp[f][0]['importe_final']*100);
    
    $id=time();

    $DB->query("insert into comunicacion_tpv 
    (id,DS_MERCHANT_ORDER,total,id_pedido,finalizada,fecha,id_via_cobro) values 
    (NULL,
    '".strval($id)."',
    ".$amount.",
    ".$id_pedido.",
    0,
    '".date('Y-m-d H:i:s')."',
    2)");

    $urlOK = "https://pastelerialaargentina.com/confirmacion_de_pagos.php?pago=1";
    $urlKO = "https://pastelerialaargentina.com/confirmacion_de_pagos.php?pago=0";
    $url_url="https://pastelerialaargentina.com";

    $miObj->setParameter("DS_MERCHANT_AMOUNT",$amount);
    $miObj->setParameter("DS_MERCHANT_ORDER",strval($id));
    $miObj->setParameter("DS_MERCHANT_MERCHANTCODE",$fuc);
    $miObj->setParameter("DS_MERCHANT_CURRENCY",$moneda);
    $miObj->setParameter("DS_MERCHANT_TRANSACTIONTYPE",$trans);
    $miObj->setParameter("DS_MERCHANT_TERMINAL",$terminal);
    $miObj->setParameter("DS_MERCHANT_MERCHANTURL",$url_url);
    $miObj->setParameter("DS_MERCHANT_URLOK",$urlOK);   
    $miObj->setParameter("DS_MERCHANT_URLKO",$urlKO);

    //Datos de configuración
    $version="HMAC_SHA256_V1";


    // Se generan los par&aacute;metros de la petición
    // $request = "";
    $params = $miObj->createMerchantParameters();
    $signature = $miObj->createMerchantSignature($kc);

    //if(!isset($_SESSION['ip_admin'])){

        
        echo "<form name='frm' action='".$action."' method='POST' >\n";
        echo "<input type='hidden' name='Ds_SignatureVersion' value='".$version."'/>\n";
        echo "<input type='hidden' name='Ds_MerchantParameters' value='".$params."'/>\n";
        echo "<input type='hidden' name='Ds_Signature' value='".$signature."'/>\n";
        echo "</form>\n";
        script('$("[name=\'frm\']").submit();');
                
    //}

// }else if(isset($reintentar_pago)){ 
}else if(isset($login_web)){ 

        $DB->query('select * from clientes where email like "'.$email.'"');


        if($DB->rows()==0){

            notify("El usuario no existe","danger");

        }else if($DB->rows()==1){

            $d=$DB->fetch();

                if(!password_verify($pass,$d['password'])){

                    notify('la contraseña es incorrecta',"danger");

                }else if(password_verify($pass,$d['password'])){

                    $_SESSION['id'] = $d['id'];
                    script('location.href="https://pastelerialaargentina.com/zona_privada.php"');

                }


        }

// }else if(isset($login_web)){ 
}else if(isset($loguot_web)){ 

    session_destroy();
    script('location.href="https://pastelerialaargentina.com"');

// }else if(isset($loguot_web)){  
}else if(isset($modifica_pass)){ 

    sleep(1);
    $d = $DB->select('password','clientes','id = '.$_SESSION['id']);
    $d = $d[f][0];
    if(!password_verify($pass,$d['password'])){

        script('$("#modal_espera_general").modal("hide");');
        notify('La contraseña es incorrecta',"danger");

    }else if(password_verify($pass,$d['password'])){

        $DB->openTable('clientes');
        $DB->edit($_SESSION['id']);
        $DB->ta['comentario'] = $new_pass;
        $DB->ta['password'] = password_hash($new_pass, PASSWORD_DEFAULT); 
        $DB->update();
        script('$("#modal_espera_general").modal("hide");');
        notify('Contraseña modificada Correctamente',"success");

    }

// }else if(isset($modifica_pass)){ 
}else if(isset($modifica_datos)){ 

    sleep(1);
    $DB->openTable('clientes');
    $DB->edit($_SESSION['id']);   
    $DB->ta['nombre'] = $nombre;
    $DB->ta['email'] = $email;
    $DB->ta['direccion'] = $direccion;
    $DB->ta['poblacion'] = $poblacion;
    $DB->ta['cp'] = $cp;
    $DB->ta['telefono'] = $telefono;
    $DB->update();
    script('$("#modal_espera_general").modal("hide");');
    notify('Datos Modificados Correctamente',"success");

// }else if(isset($modifica_datos)){  
}else if(isset($modifica_fechas)){

    sleep(1);
    if($fecha_nacimiento != ''){

        $DB->openTable('clientes');
        $DB->edit($_SESSION['id']);   
        $DB->ta['fecha_nacimiento'] = formatear_fecha($fecha_nacimiento,i,m);
        $DB->update();

        $cf = $DB->select('id','fechas_especiales','id_cliente = '.$_SESSION['id'].' and tipo like "cumple"');
        if($cf[r] > 0){

            $DB->openTable('fechas_especiales');
            $DB->edit($cf[f][0]['id']);
            list($dia,$mes,$year) = explode('/',$fecha_nacimiento);
            $DB->ta['mes'] = $mes;
            $DB->ta['dia'] = $dia;
            $DB->update();

        }else{

            $DB->openTable('fechas_especiales');
            list($dia,$mes,$year) = explode('/',$fecha_nacimiento);
            $DB->ta['mes'] = $mes;
            $DB->ta['dia'] = $dia;
            $DB->ta['id_cliente'] = $_SESSION['id'];
            $DB->ta['tipo'] = 'cumple';
            $DB->insert();

        }

    }

    if($fecha_aniversario != ''){

        $DB->openTable('clientes');
        $DB->edit($_SESSION['id']);   
        $DB->ta['fecha_aniversario'] = formatear_fecha($fecha_aniversario,i,m);
        $DB->update();

        $cf = $DB->select('id','fechas_especiales','id_cliente = '.$_SESSION['id'].' and tipo like "cumple"');
        if($cf[r] > 0){

            $DB->openTable('fechas_especiales');
            $DB->edit($cf[f][0]['id']);
            list($dia,$mes,$year) = explode('/',$fecha_aniversario);
            $DB->ta['mes'] = $mes;
            $DB->ta['dia'] = $dia;
            $DB->update();

        }else{

            $DB->openTable('fechas_especiales');
            list($dia,$mes,$year) = explode('/',$fecha_aniversario);
            $DB->ta['mes'] = $mes;
            $DB->ta['dia'] = $dia;
            $DB->ta['id_cliente'] = $_SESSION['id'];
            $DB->ta['tipo'] = 'aniversario';
            $DB->insert();

        }

    }

    if($fecha_santo != ''){

        $DB->openTable('clientes');
        $DB->edit($_SESSION['id']);   
        $DB->ta['fecha_santo'] = formatear_fecha($fecha_santo,i,m);
        $DB->update();

        $cf = $DB->select('id','fechas_especiales','id_cliente = '.$_SESSION['id'].' and tipo like "cumple"');
        if($cf[r] > 0){

            $DB->openTable('fechas_especiales');
            $DB->edit($cf[f][0]['id']);
            list($dia,$mes,$year) = explode('/',$fecha_santo);
            $DB->ta['mes'] = $mes;
            $DB->ta['dia'] = $dia;
            $DB->update();

        }else{

            $DB->openTable('fechas_especiales');
            list($dia,$mes,$year) = explode('/',$fecha_santo);
            $DB->ta['mes'] = $mes;
            $DB->ta['dia'] = $dia;
            $DB->ta['id_cliente'] = $_SESSION['id'];
            $DB->ta['tipo'] = 'aniversario';
            $DB->insert();

        }

    }

    script('$("#modal_espera_general").modal("hide");');
    notify('Datos Modificados Correctamente',"success");

// }else if(isset($modifica_fechas)){ 
}else if(isset($insertar_fechas_especiales)){  

    $DB->openTable('fechas_especiales');
    list($dia,$mes,$year) = explode('/',$fecha_especial);
    $DB->ta['mes'] = $mes;
    $DB->ta['dia'] = $dia;
    $DB->ta['nombre'] = $nombre_especial;
    $DB->ta['parentesco'] = $parentesco_especial;
    $DB->ta['email'] = $email_especial;
    $DB->ta['telefono'] = $telefono_especial;
    $DB->ta['id_cliente'] = $id_cliente;
    $DB->ta['puntual'] = $puntual;
    $DB->ta['foto'] = $foto_especial;
    $DB->ta['nota'] = $mensaje_especial;
    $DB->ta['mensaje_dia'] = $mensaje_dia;
    $DB->ta['tipo'] = 'personalizada';
    $id_fecha = $DB->insert(1);

    $email = $email_especial == '' ? '' : '<br><b>E-mail</b>: '.$email_especial;
    $telefono = $telefono_especial == '' ? '' : '<br><b>Teléfono</b>: '.$telefono_especial;
    $lleva_nota = $mensaje_especial == '' ? '' : '<br><b>Nota</b>: '.$mensaje_especial;
    $mensaje = $mensaje_dia == '' ? '' : '<br><b>Que le vas a felicitar</b>: '.$mensaje_dia;
    $lleva_foto = $foto_especial == '' ? '' : '<br><b>Foto</b>:<br> <img src="https://pastelerialaargentina.com/new_app/img/emails/'.$foto_especial.'" alt="">';

    $es_puntual = $puntual == 1 ? 'Si, sólo 1 vez.' : 'No, son todos los años';



    script('$("#fechas_especiales_div").prepend(\'<div class="col-md-6" style="padding : 2px!important;"><div class="alert alert-info alert-dismissible fade show mt-3" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" name="quitar_fecha" id_fecha="'.$id_fecha.'"><span aria-hidden="true">&times;</span></button><b>Día</b>: '.$dia.'<br><b>Mes</b>: '.$mes.'<br><b>Nombre</b> : '.$nombre_especial.'<br><b>Parentesco</b>: '.$parentesco_especial.$email.$telefono.'<br><b>¿Es Puntual?</b> : '.$es_puntual.$lleva_nota.$mensaje.$lleva_foto.'</div></div>\');');

// }else if(isset($insertar_fechas_especiales)){ 
}else if(isset($borrar_fecha_especial)){  

    $DB->query('delete from fechas_especiales where id = '.$id);

// }else if(isset($borrar_fecha_especial)){ 
}else if(isset($ver_pedido)){ 

    $dp = $DB->select('*','pedidos',' id = '.$id);
    $dp = $dp[f][0];
    $txt_c = '     
                    <table style="width : 100%">
                        <tbody style="width : 100%">
                            <tr style="border: 1px solid grey;">
                                <td style="border: grey 1px solid; text-align: center ; padding: 5px; width : 50%;">Producto</td>
                                <td style="border: grey 1px solid; text-align: center ; padding: 5px;">Uni/Porciones</td>
                                <td style="border: grey 1px solid; text-align: center ; padding: 5px;">Pvp</td>
                                <td style="border: grey 1px solid; text-align: center ; padding: 5px;">Pr.Final</td>
                            </tr>';

                    $pr = json_decode($dp['productos'],true);
                    $p = 1;
                    $pf = 0;
                    foreach ($pr as $k => $v) {

                        if($v['id_familia'] != 4){

                            $d = $DB->select('*','productos_web','id = '.$v['productos']['id_producto']);
                            $d = $d[f][0];
                            if($v['id_familia'] == 5 || $v['id_familia'] == 10 || $v['id_familia'] == 9){

                                $pf += ($v['productos']['pvp']*$v['productos']['unidades']); 
                                $pvp = ($v['productos']['pvp']*$v['productos']['unidades']);
                                $pvp_unidad = $v['productos']['pvp'];

                            }else{

                                $pf += $v['productos']['pvp'];
                                $pvp = $v['productos']['pvp'];
                                $pvp_unidad = $v['productos']['pvp'];

                            }

                            $cartel = $d['nombre_producto']; 
                            if($v['id_familia'] == 6 || $v['id_familia'] == 7 || $v['id_familia'] == 8){

                                $unidades = $v['productos']['tamano'];

                            }else{

                                $unidades = $v['productos']['unidades'];

                            }


                            if(isset($v['productos']['foto_tartas_personalizadas']) && $v['productos']['foto_tartas_personalizadas'] == 1){

                                $cartel .= '<br><img src="https://pastelerialaargentina.com/new_app/img/pedidos_web/'.$fotos[$k].'" alt="" style="width : 100%;">';

                            }

                            if(isset($v['productos']['foto_tartas_galletas']) && $v['productos']['foto_tartas_galletas'] == 1){

                                $cartel .= '<br><img src="https://pastelerialaargentina.com/new_app/img/pedidos_web/'.$fotos[$k].'" alt="" style="width : 100%;">';
                            }

                            if(isset($v['productos']['foto_pastelon']) && $v['productos']['foto_pastelon'] == 1){

                                $cartel .= '<br><img src="https://pastelerialaargentina.com/new_app/img/pedidos_web/'.$fotos[$k].'" alt="" style="width : 100%;">';
                            }

                            if(isset($v['productos']['foto_tartas_predeterminadas']) && $v['productos']['foto_tartas_predeterminadas'] == 1){

                                $cartel .= '<br><img src="https://pastelerialaargentina.com/new_app/img/pedidos_web/'.$fotos[$k].'" alt="" style="width : 100%;">';
                            }

                        }else{

                            $cartel = 'Bandeja Pasteles Surtidos : ';
                            $pvp_bandeja = 0;
                            $cantidad_bandeja = 0;
                            foreach ($v['productos'] as $l => $val) {

                                $pvp_bandeja += ($val['pvp']*$val['cantidad']);
                                $cantidad_bandeja += $val['cantidad'];
                                $dp = $DB->select('nombre_producto','productos_web','id = '.$val['id_producto']);
                                $cartel .= '<br> <b>'.utf8_encode($dp[f][0]['nombre_producto']).' ('.$val['cantidad'].' Unds)</b>';

                            } // foreach ($v['productos'] as $key => $value) { 

                            $unidades = 1;
                            $pf += $pvp_bandeja;
                            $pvp = $pvp_bandeja;
                            $pvp_unidad = $pvp_bandeja;
                        }


                        $txt_c.='
                                    <tr style="border: grey 1px solid ;">
                                        <td style="border: grey 1px solid;  padding: 5px; width : 50%;">'.utf8_encode($cartel).'</td>
                                        <td style="border: grey 1px solid; text-align: right; padding: 5px;">'.$unidades.'</td>
                                        <td style="border: grey 1px solid; text-align: right; padding: 5px;">'.nf($pvp_unidad).'&euro;</td>
                                        <td style="border: grey 1px solid; text-align: right; padding: 5px;">'.nf($pvp).'&euro;</td>
                                    </tr>';

                        $p++;

                    } // foreach ($pr as $key => $value) {

                    $txt_c .= '
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="border: grey 1px solid; text-align: right; padding: 5px;">'.number_format(($pf/100),2,'.','').'&euro;</td>
                                </tr>
                            </tbody>
                        </table>';

            echo $txt_c;
// }else if(isset($ver_pedido)){ 
}else if(isset($tipo_entrega)){ 

    if ($tipo_entrega == 'recogida_en_tienda') {

        $pvp_envio = 0;
        $pvp_carrito = $span_productos;

    }else{

        if($span_productos < 1200){

            $pvp_envio = 399;
            $pvp_carrito = $span_productos+399;

        }else{

            $pvp_envio = 0;
            $pvp_carrito = $span_productos;

        }

    }


    script('

            $("#span_precio_final").html("'.nf($pvp_carrito).'");
            $("#span_envio").html("'.nf($pvp_envio).'");
            $("#span_envio").attr("pvp","'.($pvp_envio).'");
            $("#span_productos").html("'.nf($span_productos).'");
            $("#span_productos").attr("pvp","'.($span_productos).'");
            $("#importe_final").val("'.($pvp_carrito).'");

        ');


        
// }else if(isset($tipo_entrega)){ 
}else if(isset($suma_favorito)){ 

    $extras = '';
    $li_producto = '';
    $vaciar_carro = '';
    if(!isset($_COOKIE['favoritos'])){

        $dp = $DB->select('nombre_producto,fotos,pvp','productos_web','id = '.$id_producto);
        $dp = $dp[f][0];

        if($dp['fotos'] == ''){

            $ft = '/images/logo_argentina_min.png';

        }else{

            $arr_f = json_decode($dp['fotos'],true);
            $ft = is_array($arr_f) && isset($arr_f[0]) ? $arr_f[0] : '/images/logo_argentina_min.png';

        }

        $li_producto .= '<li>
                            <a href="#" class="image">
                                <img src="'.$ft.'" alt="Cart product Image">
                            </a>
                            <div class="content">
                                <b>'.utf8_encode($dp['nombre_producto']).'</b> <br>
                                Precio '.nf($dp['pvp']).'
                                <a href="#" class="remove" name="borrar_favorito" key="0">×</a>
                            </div>
                        </li>';

        $arr_c[0] = array('id_producto'=>$id_producto);
        $val = json_encode($arr_c);
        setcookie('favoritos', $val, time()+864000*365,"/");
        $_COOKIE['favoritos']=$val;
        $n_lineas = 1;
        $vaciar_carro = '$("#ul_favoritos").html("")';


    }else{

        $arc = json_decode($_COOKIE['favoritos'],true);
        $key = count($arc);
        $dp = $DB->select('nombre_producto,fotos,pvp','productos_web','id = '.$id_producto);
        $dp = $dp[f][0];

        if($dp['fotos'] == ''){

            $ft = '/images/logo_argentina_min.png';

        }else{

            $arr_f = json_decode($dp['fotos'],true);
            $ft = is_array($arr_f) && isset($arr_f[0]) ? $arr_f[0] : '/images/logo_argentina_min.png';

        }

        $li_producto .= '<li>
                            <a href="#" class="image">
                                <img src="'.$ft.'" alt="Cart product Image">
                            </a>
                            <div class="content">
                                <b>'.utf8_encode($dp['nombre_producto']).'</b> <br>
                                Precio '.nf($dp['pvp']).'
                                <a href="#" class="remove" name="borrar_favorito" key="'.$key.'">×</a>
                            </div>
                        </li>';
        $arr_c[$key] = array('id_producto'=>$id_producto);
        $n_lineas = $key+1;
        $arf = array_merge($arc,$arr_c);
        $val = json_encode($arf);
        setcookie('favoritos', $val, time()+864000*365,"/");
        $_COOKIE['favoritos']=$val;
    }

    noty('Producto añadido correctamente a favoritos');
    script($vaciar_carro.'
            $("#span_favorito").html('.$n_lineas.');
            $("#ul_favoritos").append(\''.str_replace($clean, '', $li_producto).'\');
            ');

// }else if(isset($suma_favorito)){ 
}else if(isset($recuperar_favoritos)){ 

    $arr = json_decode(($_COOKIE['favoritos']),true);
    $n_lineas = count($arr);
    $li_producto = '';
    foreach ($arr as $k => $v) {

        $dp = $DB->select('nombre_producto,fotos,pvp','productos_web','id = '.$v['id_producto']);
        $dp = $dp[f][0];

        if($dp['fotos'] == ''){

            $ft = '/images/logo_argentina_min.png';

        }else{

            $arr_f = json_decode($dp['fotos'],true);
            $ft = is_array($arr_f) && isset($arr_f[0]) ? $arr_f[0] : '/images/logo_argentina_min.png';

        }

        $li_producto .= '<li>
                            <a href="#" class="image">
                                <img src="'.$ft.'" alt="Cart product Image">
                            </a>
                            <div class="content">
                                <b>'.utf8_encode($dp['nombre_producto']).'</b> <br>
                                Precio '.nf($dp['pvp']).'
                                <a href="#" class="remove" name="borrar_favorito" key="'.$k.'">×</a>
                            </div>
                        </li>';
    }

        script('
            $("#span_favorito").html('.$n_lineas.');
            $("#ul_favoritos").append(\''.str_replace($clean, '', $li_producto).'\');
            ');


// }else if(isset($recupera_favorito)){ 
}else if(isset($borrar_favorito)){ 

    alert($key);
    $arr = json_decode($_COOKIE['favoritos'],true);
    $n_key = 0;
    $n_arr = array();
    $n_lineas = 0;
    $li_producto = '';
    foreach ($arr as $k => $v) {
        
        if($k != $key){

            $dp = $DB->select('nombre_producto,fotos,pvp','productos_web','id = '.$v['id_producto']);
            $dp = $dp[f][0];

            if($dp['fotos'] == ''){

                $ft = '/images/logo_argentina_min.png';

            }else{

                $arr_f = json_decode($dp['fotos'],true);
                $ft = is_array($arr_f) && isset($arr_f[0]) ? $arr_f[0] : '/images/logo_argentina_min.png';

            }

            $li_producto .= '<li>
                                <a href="#" class="image">
                                    <img src="'.$ft.'" alt="Cart product Image">
                                </a>
                                <div class="content">
                                    <b>'.utf8_encode($dp['nombre_producto']).'</b> <br>
                                    Precio '.nf($dp['pvp']).'
                                    <a href="#" class="remove" name="borrar_favorito" key="'.$k.'">×</a>
                                </div>
                            </li>';

            $n_arr[$n_lineas] = array('id_producto'=>$v['id_producto']);
            $n_lineas ++;
        } // if($k != $key){

    } // foreach ($arr as $k => $v) {

    $val = json_encode($n_arr);
    setcookie('favoritos', $val, time()+864000*365,"/");
    $_COOKIE['favoritos']=$val;
    script('
                $("#ul_favoritos").html("");
                $("#span_favorito").html('.$n_lineas.');
                $("#ul_favoritos").append(\''.str_replace($clean, '', $li_producto).'\');
            ');


// }else if(isset($borrar_favorito)){ 
}else if(isset($email_recupera_pass)){ 

    try {

        $c = $DB->select('id','clientes','email like "'.$email_recupera_pass.'"');

        if($c[r] == 0){

            throw new Exception("Este email no exite en nuestra base de datos");

        }else{

            # avisamos al comercio y cliente
            $url_cURL="https://".$_SERVER['HTTP_HOST']."/new_app/functions/ajax.php";
            $post_cURL=array(
                    "enviar_email"=>1,
                    "tabla_enviar"=>"recupera_pass",
                    "id"=>$c[f][0]['id'],
                    "id_web"=>1);

            // operacion curl.
            $ch = curl_init();  
            curl_setopt($ch, CURLOPT_URL, $url_cURL);  
            curl_setopt($ch, CURLOPT_POSTFIELDS,$post_cURL);
            curl_exec($ch);    
            curl_close($ch);

            script('$("#modal_login_web").modal("hide");');
            noty('Acabamos de enviarte un email con la nueva contraseña, si no está en tu bandeja de entrada, revisa tu bandeja de SPAM','success');
        }

        
    } catch (Exception $e) {
        
        notify($e->getMessage(),'danger');

    }
// }else if(isset($email_recupera_pass)){ 
} 



?>