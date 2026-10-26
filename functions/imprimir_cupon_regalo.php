<?php @session_start();
date_default_timezone_set('Europe/Madrid');
error_reporting(0);
include("funciones.php");

    // DEFINIMOS LA SALIDA QUE VAMOS A TENER.

    if(isset($id)){ 

        $d = $DB->inner_join('c.*,cl.nombre','cupones_descuento c inner join clientes cl','c.id_cliente = cl.id where c.id = '.$id);
        $d = $d[f][0];

        $frase = '';
        if($d['tipo'] == 'fecha_nacimiento'){

            $frase = 'Feliz Cumpleaños';
            $do = $DB->select('*','ofertas_especiales','id = 1');

        }else if($d['tipo'] == 'fecha_santo'){

            $frase = 'Feliz día de tu Santo';
            $do = $DB->select('*','ofertas_especiales','id = 2');

        }else if($d['tipo'] == 'fecha_aniversario'){

            $frase = 'Feliz Aniversario';
            $do = $DB->select('*','ofertas_especiales','id = 3');

        }else if($d['tipo'] == 'personalizada'){

            $dn = $DB->select('*','fechas_especiales','id = '.$d['id_fechas_especiales']);
            $frase = 'Feliz '.utf8_encode($dn[f][0]['mensaje_dia']);
            $d['nombre'] = ucwords(strtolower($dn[f][0]['nombre']));
            $do = $DB->select('*','ofertas_especiales','id = 4');

        }else if($d['tipo'] == 'puntual'){

            $do = $DB->select('*','ofertas_puntuales','id = '.$d['id_ofertas_puntuales']);
            $frase = 'Feliz '.utf8_encode($do[f][0]['motivo']);

        }

        $do = $do[f][0];

        if($do['tipo'] == 'descuento'){

            $frase .= '<br><b>Con un '.$do['porcentaje'].'% de Descuento</b>';

        }else{

            $dp = $DB->select('nombre_producto','productos','id = '.$do['id_producto']);
            $frase .= '<br><b>Con un '.utf8_decode($dp[f][0]['nombre_producto']).' de Regalo</b>';
        }

        $content='<page orientation="portrait" style="font-size: 14px; width: 100%; margin-left:auto; margin-right:auto">
                    <div style="width:100%; text-align:center;">
                     <h2 style="color : #'.$dd['color_email'].'">Cheque Regalo</h2>
                    </div>
                        <table style="width:100%;">
                            <tbody>
                                <tr>
                                    <td style="width:100%;">
                                        <div style="width:100%; height: 100px; text-align: center;" >
                                            <img src="https://'.$_SERVER['HTTP_HOST'].'/new_app/img/logo_documentacion.png" height="100px;" alt="">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            </table>

                            <div style="width: 450px; clear: both; text-align:center; margin : auto; border : 1px solid black; border-radius : 5px; height : 300px; overflow : hidden; position : relative;">
                                <img src="https://www.'.$_SERVER['SERVER_NAME'].'/new_app/img/img_cupon.jpg" style="height : 510px;" alt="">
                                <div style="background-color : white; height : 15px; position : absolute; border : 1px solid black; width : 130px; border-radius : 5px; top : 10px; left : 80px; padding : 5px;" >
                                    Validez : '.formatear_fecha($d['fecha_caducidad'],m,i).'
                                </div>
                                <div style="background-color : white; height : 15px; position : absolute; border : 1px solid black; width : 100px; border-radius : 5px; top : 10px; left : 400px; padding : 5px;" >
                                    '.str_pad($d['id'], 4, "0", STR_PAD_LEFT).'
                                </div>';

            if($d['utilizado'] == 1){

                $content.='
                                <div style="background-color : white; height : 30px; position : absolute; border : 1px solid black; width : 412px; border-radius : 5px; top : 120px; left : 80px; padding : 5px; color : red;" >
                                   <h3>Este cupón ya ha sido canjeado</h3>
                                </div>';

            }
            $content.='
                                <div style="background-color : white; height : 10px; position : absolute; border : 1px solid black; width : 412px; border-radius : 5px; top : 230px; left : 80px; padding : 5px;" >
                                    '.utf8_decode($d['nombre']).' te deseamos '.$frase.'
                                </div>
                            </div>
                    </page>';



    }else{

        $content.='No hay ningún Cupon descuento con este id';

    }


     // echo "helo".$content;

    require_once(dirname(__FILE__).'/html2pdf_compat.php');


    try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'es');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        $html2pdf->Output($nombre_archivo.'.pdf');
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }

?>

