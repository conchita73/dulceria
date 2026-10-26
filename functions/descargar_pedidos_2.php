<?php @session_start();
date_default_timezone_set('Europe/Madrid');

if(isset($_COOKIE['soy_pedro'])){

    error_reporting(E_ALL);

}else{

    error_reporting(0);

}
    include("funciones.php");

    if(isset($fecha)){
    
        $DB->query("SELECT p.*,d.nombre,c.nombre as cliente, c.telefono
                        FROM pedidos p inner join delegaciones d 
                        on p.local_entrega = d.id 
                        inner join clientes c on p.id_cliente = c.id
                        WHERE p.estado = 0 and
                        p.fecha_entrega = '".$fecha."' and es_vitrina = 0 and tarta!='' and tarta like '%predeterminada\":\"0\"%'");

        $content = '';
        if($DB->rows()>0){

            $content .= '<page orientation="portrait" style="font-size: 13px">
                            <div style="width: 100%; height : 10px; text-align: center;">
                                <h3>Tartas al Gusto</h3>
                            </div>
                            <table style="width: 98%; height:470px;">';

            $tr=1;
            while ($d = $DB->fetch()) {
                
                $pay = $d['pedido_cobrado'] == 'No' ? 'No' : ($d['pedido_cobrado'] == 'Si' ? 'Si' : number_format($d['importe_pagado'],2,'.','').' &euro;');

                list($h,$m,$s) = explode(':',$d['hora_entrega']);

                $h_entrega = 'Sin Hora Entrega';
                if($d['hora_entrega'] != '00:00:00'){

                    list($hr,$mn,$sg) = explode(':',$d['hora_entrega']);
                    $h_entrega = 'Hora entrega '.$hr.':'.$mn;

                }

                ## si el pedido no es predeterminado
                $arr_t = json_decode(utf8_encode($d['tarta']),true,JSON_UNESCAPED_UNICODE);
                foreach ($arr_t as $t => $r) {

                    $content .= $tr==1 ? '<tr>' : '';

                    $content .='<td style="width: 49%; border: dotted 1px rgba(0,0,0,.1);">
                                <div style="width: 50%; height: 90%; padding: 5px;">
                                    <table style="width: 100%;">
                                        <tbody>
                                            <tr>
                                                <td style="width:49%; margin-top:0px; border-bottom: dotted 1px rgba(0,0,0,.1);">Fecha Entrega :  '.formatear_fecha($d['fecha_entrega'],m,i).' 
                                                    <br>'.$h_entrega.'
                                                </td>
                                                <td style="text-align: right; border-bottom: dotted 1px rgba(0,0,0,.1); width:42%; padding-right: 5px;">Importe: '.number_format($d['importe_final'],2,'.','').'&euro; <br>
                                                Pagado : '.$pay.'</td>
                                            </tr>
                                            <tr >
                                                <td style=" width:350px;" height: 230px; colspan="2">';



                        

                            if($r['predeterminada'] == 0){
                                $content .='<br>';
                                foreach ($r as $h => $z) {

                                    if($h != 'predeterminada' && $h != 'tarta'){

                                        $content .='<br>'.str_replace('_',' ',$h).' : '.$z;

                                    }

                                }

                            } // if($r['predeterminada'] == 0){



                    $content .='                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div style="width: 100%; height: 10%; padding: 5px;">
                                    <table style="width: 100%;">
                                        <tbody>
                                            <tr style="width:100%">
                                                <td style="width:49%; ">'.$d['telefono'].'<br>'.utf8_encode($d['nombre']).'</td>
                                               <td style="text-align: right; width:49%; padding-right: 5px;  ">'.utf8_encode($d['cliente']).'</td>
                                            </tr> 
                                        </tbody>
                                    </table>
                                </div>
                            </td>';


                    $tr++;
                    if($tr == 3){

                        # cerramos tr cada 2 vueltas
                        $content .= '</tr>';
                        $tr=1;

                    }

                } // foreach ($arr_t as $t => $r) {

            } //  while ($d = $DB->fetch()) {

            # si al acabar no se han completado las 2 vueltas, cerramos
            $content .= $tr == 2 ? '</tr>' : '';
            

            $content .= '</table>
            </page>';

        } // if($DB->rows()>0){

        $DB->query("SELECT p.*,d.nombre,c.nombre as cliente, c.telefono
                        FROM pedidos p inner join delegaciones d 
                        on p.local_entrega = d.id 
                        inner join clientes c on p.id_cliente = c.id
                        WHERE p.estado = 0 and
                        p.fecha_entrega = '".$fecha."' and es_vitrina = 0 and tarta!='' and tarta like '%predeterminada\":\"1\"%'");

        if($DB->rows()>0){

                    $content .= '<page orientation="portrait" style="font-size: 13px">
                                    <div style="width: 100%; height : 10px; text-align: center;">
                                        <h3>Tartas Predeterminadas</h3>
                                    </div>
                                    <table style="width: 98%; height:470px;">';

                    $tr=1;
                    while ($d = $DB->fetch()) {
                        
                        $pay = $d['pedido_cobrado'] == 'No' ? 'No' : ($d['pedido_cobrado'] == 'Si' ? 'Si' : number_format($d['importe_pagado'],2,'.','').' &euro;');

                        $h_entrega = 'Sin Hora Entrega';
                        if($d['hora_entrega'] != '00:00:00'){

                            list($hr,$mn,$sg) = explode(':',$d['hora_entrega']);
                            $h_entrega = 'Hora entrega '.$hr.':'.$mn;

                        }
                        # abrimos tr
                        $content .= $tr==1 ? '<tr>' : '';

                        $content .='<td style="width: 49%; border: dotted 1px rgba(0,0,0,.1);">
                                    <div style="width: 50%; height: 90%; padding: 5px;">
                                        <table style="width: 100%;">
                                            <tbody>
                                                <tr>
                                                    <td style="width:49%; margin-top:0px; border-bottom: dotted 1px rgba(0,0,0,.1);">Fecha Entrega :  '.formatear_fecha($d['fecha_entrega'],m,i).' 
                                                        <br>'.$h_entrega.'
                                                    </td>
                                                    <td style="text-align: right; border-bottom: dotted 1px rgba(0,0,0,.1); width:42%; padding-right: 5px;">Importe: '.number_format($d['importe_final'],2,'.','').'&euro; <br>
                                                    Pagado : '.$pay.'</td>
                                                </tr>
                                                <tr >
                                                    <td style=" width:350px;" height: 230px; colspan="2">';



                            ## si el pedido no es predeterminado
                            $arr_t = json_decode(utf8_encode($d['tarta']),true,JSON_UNESCAPED_UNICODE);
                            foreach ($arr_t as $t => $r) {

                                
                                if($r['predeterminada'] == 1){
                                    $content .='<br>';
                                    foreach ($r as $h => $z) {

                                        if($h == 'nombre'){

                                            $content .='<br>'.str_replace('_',' ',$h).' : '.str_replace('Âº','&ordm;',$z);

                                        }

                                    }
                                    
                                }

                            }



                        $content .='                </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div style="width: 100%; height: 10%; padding: 5px;">
                                        <table style="width: 100%;">
                                            <tbody>
                                                <tr style="width:100%">
                                                    <td style="width:49%; ">'.$d['telefono'].'<br>'.utf8_encode($d['nombre']).'</td>
                                                   <td style="text-align: right; width:49%; padding-right: 5px;  ">'.utf8_encode($d['cliente']).'</td>
                                                </tr> 
                                            </tbody>
                                        </table>
                                    </div>
                                </td>';
                        $tr++;
                        if($tr == 3){

                            # cerramos tr cada 2 vueltas
                            $content .= '</tr>';
                            $tr=1;

                        }

                    } //  while ($d = $DB->fetch()) {

                    # si al acabar no se han completado las 2 vueltas, cerramos
                    $content .= $tr == 2 ? '</tr>' : '';
                    

                    $content .= '</table>
                    </page>';


        } // if($DB->rows()>0){

            $DB->query('SELECT p.*,d.nombre,c.nombre as cliente, c.telefono
                        FROM pedidos p inner join delegaciones d 
                        on p.local_entrega = d.id 
                        inner join clientes c on p.id_cliente = c.id
                        WHERE p.estado = 0 and
                        p.fecha_entrega = "'.$fecha.'" and es_vitrina = 0 and producto!=""');

    if($DB->rows()>0){

            $content .= '<page orientation="portrait" style="font-size: 13px">
                        <div style="width: 100%; height : 10px; text-align: center;">
                                <h3>Productos</h3>
                            </div>
                            <table style="width: 98%; height:470px;">';

            $tr=1;
            while ($d2 = $DB->fetch()) {
                
                $pay = $d2['pedido_cobrado'] == 'No' ? 'No' : ($d2['pedido_cobrado'] == 'Si' ? 'Si' : number_format($d2['importe_pagado'],2,'.','').' &euro;');

                $h_entrega = 'Sin Hora Entrega';
                if($d['hora_entrega'] != '00:00:00'){

                    list($hr,$mn,$sg) = explode(':',$d['hora_entrega']);
                    $h_entrega = 'Hora entrega '.$hr.':'.$mn;

                }
                # abrimos tr
                $content .= $tr==1 ? '<tr>' : '';

                $content .='<td style="width: 49%; border: dotted 1px rgba(0,0,0,.1);">
                            <div style="width: 50%; height: 90%; padding: 5px;">
                                <table style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td style="width:49%; margin-top:0px; border-bottom: dotted 1px rgba(0,0,0,.1);">Fecha Entrega :  '.formatear_fecha($d2['fecha_entrega'],m,i).' 
                                                <br>'.$h_entrega.'
                                            </td>
                                            <td style="text-align: right; border-bottom: dotted 1px rgba(0,0,0,.1); width:42%; padding-right: 5px;">Importe: '.number_format($d2['importe_final'],2,'.','').'&euro; <br>
                                            Pagado : '.$pay.'</td>
                                        </tr>
                                        <tr >
                                            <td style=" width:350px;" height: 200px; colspan="2">';



                    $arr_p = json_decode(utf8_encode($d2['producto']),true,JSON_UNESCAPED_UNICODE);
                    ksort($arr_p);

                    foreach ($arr_p as $pas => $clas) {

                        $content .='<br>'.utf8_decode($clas['nombre']).' - '.$clas['cantidad'].' Und.';
                        
                    }

                $content .='                </td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
                            <div style="width: 100%; height: 10%; padding: 5px;">
                                <table style="width: 100%;">
                                    <tbody>
                                        <tr style="width:100%">
                                            <td style="width:49%; ">'.$d2['telefono'].'<br>'.utf8_encode($d2['nombre']).'</td>
                                           <td style="text-align: right; width:49%; padding-right: 5px;  ">'.utf8_encode($d2['cliente']).'</td>
                                        </tr> 
                                    </tbody>
                                </table>
                            </div>
                        </td>';
                $tr++;
                if($tr == 3){

                    # cerramos tr cada 2 vueltas
                    $content .= '</tr>';
                    $tr=1;

                }

            } //  while ($d = $DB->fetch()) {

            # si al acabar no se han completado las 2 vueltas, cerramos
            $content .= $tr == 2 ? '</tr>' : '';
            $content .= '</table>
            </page>';

        } // if($DB->rows()>0){

        $DB->query('SELECT p.*,d.nombre,c.nombre as cliente, c.telefono
                        FROM pedidos p inner join delegaciones d 
                        on p.local_entrega = d.id 
                        inner join clientes c on p.id_cliente = c.id
                        WHERE p.estado = 0 and
                        p.fecha_entrega = "'.$fecha.'" and es_vitrina = 1 and id_cliente>0');


        if($DB->rows()>0){


            $content .= '<page orientation="portrait" style="font-size: 12px">
                            <div style="width: 100%; height : 20px; text-align: center;">
                                <h3>Vitrinas De clientes</h3>
                            </div>
                            <table style="width: 100%; height:450px;">';
                $tr=1;
            while ($d = $DB->fetch()) {
                
                # abrimos tr
                $content .= $tr==1 ? '<tr>' : '';

                $content .='<td style="width: 49%; height: 45%; border: dotted 1px rgba(0,0,0,.1);">
                            <div style="width: 100%;  padding: 5px;">
                                <table style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td style=" height: 20px;">Fecha Entrega :  '.formatear_fecha($d['fecha_entrega'],m,i).' <br>
                                            '.utf8_encode($d['cliente']).' de '.utf8_encode($d['nombre']).'
                                                
                                            </td>
                                            
                                        </tr>
                                        <tr >
                                            <td style="height: 450px;">';

                if($d['tarta']!=''){

                    $arr_t = json_decode(utf8_encode($d['tarta']),true,JSON_UNESCAPED_UNICODE);
                    foreach ($arr_t as $key => $value) {

                        $content .='<br>';
                        foreach ($value as $k => $va) {

                            
                            if($k != 'predeterminada' && $k != 'tarta'){

                                $content .='<br>'.str_replace('_',' ',$k).' : '.$va;

                            }

                        }

                    }

                } // if($d['tarta']!=''){

                if($d['producto']!=''){

                    $arr_p = json_decode(utf8_encode($d['producto']),true,JSON_UNESCAPED_UNICODE);
                    ksort($arr_p);

                    foreach ($arr_p as $key => $value) {

                        $content .='<br>'.utf8_decode($value['nombre']).' - '.$value['cantidad'].' Und.';       

                        
                    }

                } // if($d['tarta']!=''){


                $content .='                </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>';
                $tr++;
                if($tr == 3){

                    # cerramos tr cada 2 vueltas
                    $content .= '</tr>';
                    $tr=1;

                }

            } //  while ($d = $DB->fetch()) {

            # si al acabar no se han completado las 2 vueltas, cerramos
            $content .= $tr == 2 ? '</tr>' : '';
            

            $content .= '</table>
            </page>';

        } // if($DB->rows()>0){

        $DB->query('SELECT p.*,d.nombre
                        FROM pedidos p inner join delegaciones d 
                        on p.local_entrega = d.id 
                        WHERE p.estado = 0 and
                        p.fecha_entrega = "'.$fecha.'" and es_vitrina = 1 and id_cliente = 0');



        if($DB->rows()>0){

            $content .= '<page orientation="portrait" style="font-size: 12px" backtop="10mm" backbottom="0mm" backleft="0mm" backright="0mm">
                    <page_header>
                        <div style="width: 100%; height : 20px; text-align: center;">
                            <h3>Vitrinas</h3>
                        </div>
                    </page_header>
                            
                            <table style="width: 100%; height:450px;">';
                $tr=1;
            while ($d = $DB->fetch()) {
                
                # abrimos tr
                $content .= $tr==1 ? '<tr>' : '';

                $content .='<td style="width: 49%; height: 45%; border: dotted 1px rgba(0,0,0,.1);">
                            <div style="width: 100%;  padding: 5px;">
                                <table style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td style=" height: 20px;">Fecha Entrega :  '.formatear_fecha($d['fecha_entrega'],m,i).' <br>
                                            Vitrina '.utf8_encode($d['nombre']).'
                                                
                                            </td>
                                            
                                        </tr>
                                        <tr >
                                            <td style="height: 450px;">';

                if($d['tarta']!=''){

                    $arr_t = json_decode(utf8_encode($d['tarta']),true,JSON_UNESCAPED_UNICODE);
                    foreach ($arr_t as $key => $value) {

                    
                        foreach ($value as $k => $va) {

                            if($k=='nombre'){

                                $content .='<br>'.str_replace('_',' ',$k).' : '.$va;

                            }
                           
                        }

                    }

                } // if($d['tarta']!=''){

                if($d['producto']!=''){

                    $arr_p = json_decode(utf8_encode($d['producto']),true,JSON_UNESCAPED_UNICODE);
                    $pr = array();
                    foreach ($arr_p as $key => $value) {

                        $pr[$value['nombre']] = $value['cantidad'];
                        
                    }
                    
                    ksort($pr);
                    foreach ($pr as $k => $v) {

                        $content .='<br>'.utf8_decode($k).' - '.$v.' Und.'; 
                        
                    }


                } // if($d['tarta']!=''){


                $content .='                </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>';
                $tr++;
                if($tr == 3){

                    # cerramos tr cada 2 vueltas
                    $content .= '</tr>';
                    $tr=1;

                }

            } //  while ($d = $DB->fetch()) {

            # si al acabar no se han completado las 2 vueltas, cerramos
            $content .= $tr == 2 ? '</tr>' : '';
            

            $content .= '</table>
            </page>';

        } // if($DB->rows()>0){

            $DB->query('SELECT p.*,d.nombre
                        FROM pedidos p inner join delegaciones d 
                        on p.local_entrega = d.id 
                        WHERE p.estado = 0 and
                        p.fecha_entrega = "'.$fecha.'"');

            $content .= '<page orientation="portrait" style="font-size: 15px">
                            <div style="width: 100%; height : 30px; text-align: center;">
                                <h3>Comanda General</h3>
                            </div>
                            <div style="width: 100%; height: 670px; padding: 5px;">';

            $comanda = array();


                while ($d = $DB->fetch()) {

                    // if($d['tarta']!=''){

                    //     $arr_t = json_decode(utf8_encode($d['tarta']),true,JSON_UNESCAPED_UNICODE);
                    //     foreach ($arr_t as $key => $value) {

                    //         foreach ($value as $k => $va) {

                    //             $content .='<br>'.str_replace('_',' ',$k).' : '.$va;

                    //         }

                    //     }

                    // } // if($d['tarta']!=''){

                    if($d['producto']!=''){


                        $arr_p = json_decode(utf8_encode($d['producto']),true,JSON_UNESCAPED_UNICODE);

                        ksort($arr_p);
                        foreach ($arr_p as $key => $value) {

                            if(!array_key_exists(strtoupper(sin_tildes($value['nombre'])), $comanda)){

                                $comanda[strtoupper(sin_tildes($value['nombre']))] = (int)$value['cantidad'];
                                // echo "<br>creamos el indice" . $value['nombre'].' con '.(int)$value['cantidad'];

                            }else{

                                $comanda[strtoupper(sin_tildes($value['nombre']))] += (int)$value['cantidad'];
                                // echo "<br>sumamos al indice" . $value['nombre'].' con '.(int)$value['cantidad'];
                            }

                        }

                    } // if($d['tarta']!=''){


                } //  while ($d = $DB->fetch()) {

                ksort($comanda);
                foreach ($comanda as $k => $v) {

                    $content .='<br>'.$k.' - '.$v.' Und.';
                            
                }

            $content .='</div>
                </page>';


    }else if(isset($id_pedido)){

        $DB->query('SELECT p.*,d.nombre,c.nombre as cliente, c.telefono
                    FROM pedidos p inner join delegaciones d 
                    on p.local_entrega = d.id 
                    inner join clientes c on p.id_cliente = c.id
                    WHERE p.estado = 0 and
                    p.id = '.$id_pedido);

        $tr=1;

        while ($d = $DB->fetch()) {

            if($d['es_vitrina'] == 1){

                $content = '<page orientation="portrait" style="font-size: 15px">
                    <table style="width: 100%; height:450px;">';


                $content .= $tr==1 ? '<tr>' : '';

                $content .='<td style="width: 49%; height: 45%; border: dotted 1px rgba(0,0,0,.1);">
                            <div style="width: 100%;  padding: 5px;">
                                <table style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td style=" height: 20px;">Fecha Entrega :  '.formatear_fecha($d['fecha_entrega'],m,i).' <br>
                                            '.utf8_encode($d['cliente']).' de '.utf8_encode($d['nombre']).'
                                                
                                            </td>
                                            
                                        </tr>
                                        <tr >
                                            <td style="height: 450px;">';

                        if($d['tarta']!=''){

                            $arr_t = json_decode(utf8_encode($d['tarta']),true,JSON_UNESCAPED_UNICODE);
                            foreach ($arr_t as $key => $value) {

                                $content .='<br>';
                                foreach ($value as $h => $z) {

                                    if($h != 'predeterminada' && $h != 'tarta'){

                                        $content .='<br>'.str_replace('_',' ',$h).' : '.$z;

                                    }
                                   

                                }
                            
                            }

                        } // if($d['tarta']!=''){

                        if($d['producto']!=''){

                            $arr_p = json_decode(utf8_encode($d['producto']),true,JSON_UNESCAPED_UNICODE);

                            ksort($arr_p);
                            foreach ($arr_p as $key => $value) {

                                $content .='<br>'.utf8_decode($value['nombre']).' - '.$value['cantidad'].' Und.';
                                
                                
                            }

                        } // if($d['tarta']!=''){


                        $content .='                </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </page>';
                       

            }else if($d['es_vitrina'] == 0){


                    $content = '<page orientation="portrait" style="font-size: 15px">
                                    <table style="width: 98%; height:500px;">';

                    $tr = 1;
                    if($d['producto']!=''){

                        
                        $pay = $d['pedido_cobrado'] == 'No' ? 'No' : ($d['pedido_cobrado'] == 'Si' ? 'Si' : number_format($d['importe_pagado'],2,'.','').' &euro;');

                        $h_entrega = 'Sin Hora Entrega';
                        if($d['hora_entrega'] != '00:00:00'){

                            list($hr,$mn,$sg) = explode(':',$d['hora_entrega']);
                            $h_entrega = 'Hora entrega '.$hr.':'.$mn;

                        }
                        # abrimos tr
                        

                        $content .='<tr>
                                <td style="width: 49%; border: dotted 1px rgba(0,0,0,.1);">
                                    <div style="width: 50%; height: 90%; padding: 5px;">
                                        <table style="width: 100%;">
                                            <tbody>
                                                <tr>
                                                    <td style="width:49%; margin-top:0px; border-bottom: dotted 1px rgba(0,0,0,.1);">Fecha Entrega :  '.formatear_fecha($d['fecha_entrega'],m,i).' 
                                                        <br>'.$h_entrega.'
                                                    </td>
                                                    <td style="text-align: right; border-bottom: dotted 1px rgba(0,0,0,.1); width:42%; padding-right: 5px;">Importe: '.number_format($d['importe_final'],2,'.','').'&euro; <br>
                                                    Pagado : '.$pay.'</td>
                                                </tr>
                                                <tr >
                                                    <td style=" width:350px;" height: 230px; colspan="2">';



                            $arr_p = json_decode(utf8_encode($d['producto']),true,JSON_UNESCAPED_UNICODE);
                            ksort($arr_p);
                            foreach ($arr_p as $pas => $clas) {

                                $content .='<br>'.utf8_decode($clas['nombre']).' - '.$clas['cantidad'].' Und.';
                                
                            }

                        $content .='                </td>
                                                </tr>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                    <div style="width: 100%; height: 10%; padding: 5px;">
                                        <table style="width: 100%;">
                                            <tbody>
                                                <tr style="width:100%">
                                                    <td style="width:49%; ">'.$d['telefono'].'<br>'.utf8_encode($d['nombre']).'</td>
                                                   <td style="text-align: right; width:49%; padding-right: 5px;  ">'.utf8_encode($d['cliente']).'</td>
                                                </tr> 
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            ';
                        $tr++;
                } // if($d['producto']!=''){



                if($d['tarta']!=''){


                    $arr_t = json_decode(utf8_encode($d['tarta']),true,JSON_UNESCAPED_UNICODE);
                    foreach ($arr_t as $t => $r) {

                        $content .= $tr == 1 ? '<tr>' : '';


                        $pay = $d['pedido_cobrado'] == 'No' ? 'No' : ($d['pedido_cobrado'] == 'Si' ? 'Si' : number_format($d['importe_pagado'],2,'.','').' &euro;');

                        $h_entrega = 'Sin Hora Entrega';
                        if($d['hora_entrega'] != '00:00:00'){

                            list($hr,$mn,$sg) = explode(':',$d['hora_entrega']);
                            $h_entrega = 'Hora entrega '.$hr.':'.$mn;

                        }

                        # abrimos tr

                        $content .='
                                <td style="width: 49%; border: dotted 1px rgba(0,0,0,.1);">
                                    <div style="width: 50%; height: 90%; padding: 5px;">
                                        <table style="width: 100%;">
                                            <tbody>
                                                <tr>
                                                    <td style="width:49%; margin-top:0px; border-bottom: dotted 1px rgba(0,0,0,.1);">Fecha Entrega :  '.formatear_fecha($d['fecha_entrega'],m,i).' 
                                                        <br>'.$h_entrega.'
                                                    </td>
                                                    <td style="text-align: right; border-bottom: dotted 1px rgba(0,0,0,.1); width:42%; padding-right: 5px;">Importe: '.number_format($d['importe_final'],2,'.','').'&euro; <br>
                                                    Pagado : '.$pay.'</td>
                                                </tr>
                                                <tr>
                                                    <td style=" width:350px;" height: 230px; colspan="2">';


                            $content .='<br>';
                            foreach ($r as $h => $z) {

                                if($r['predeterminada'] == 0){

                                    if($h != 'predeterminada' && $h != 'tarta'){

                                        $content .='<br>'.str_replace('_',' ',$h).' : '.$z;

                                    }

                                }else{

                                    if($h == 'nombre'){

                                        $content .='<br>'.str_replace('_',' ',$h).' : '.$z;

                                    }
                                }
                                

                            }



                        $content .='       </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div style="width: 100%; height: 10%; padding: 5px;">
                                <table style="width: 100%;">
                                    <tbody>
                                        <tr style="width:100%">
                                            <td style="width:49%; ">'.$d['telefono'].'<br>'.utf8_encode($d['nombre']).'</td>
                                           <td style="text-align: right; width:49%; padding-right: 5px;  ">'.utf8_encode($d['cliente']).'</td>
                                        </tr> 
                                    </tbody>
                                </table>
                            </div>
                        </td>';

                        $tr++;
                        if($tr == 3){

                            # cerramos tr cada 2 vueltas
                            $content .= '</tr>';
                            $tr=1;

                        }

                    } // foreach ($arr_t as $t => $r) {

                    $content .= $tr == 2 ? '</tr>' : '';


                } // if($d['tarta']!=''){


                $content .='   
                        </table>
                    </page>';

            } // else if($d['es_vitrina'] == 0){

        } // while ($d = $DB->fetch()) {



    }else if(isset($desde) && isset($hasta)){


        $DB->query('SELECT p.*,d.nombre
                FROM pedidos p inner join delegaciones d 
                on p.local_entrega = d.id 
                WHERE p.estado = 0 and
                p.fecha_entrega between "'.formatear_fecha($desde,i,m).'" and "'.formatear_fecha($hasta,i,m).'"');

            $content .= '<page orientation="portrait" style="font-size: 15px">
                            <div style="width: 100%; height : 30px; text-align: center;">
                                <h3>Previsión desde '.$desde.' hasta '.$hasta.'</h3>
                            </div>
                            <div style="width: 100%; height: 670px; padding: 5px;">';

            $comanda = array();


                while ($d = $DB->fetch()) {

                    if($d['tarta']!=''){

                        $arr_t = json_decode(utf8_encode($d['tarta']),true,JSON_UNESCAPED_UNICODE);
                        foreach ($arr_t as $key => $value) {

                            if(!array_key_exists(strtoupper(sin_tildes($value['nombre'])), $comanda)){

                                $comanda['tartas'][strtoupper(sin_tildes($value['nombre']))] = 1;
                                 //echo "<br>creamos el indice" . strtoupper(sin_tildes($value['nombre'])).' con 1';

                            }else{

                                $comanda['tartas'][strtoupper(sin_tildes($value['nombre']))] += 1;
                                 //echo "<br>sumamos al indice" . $value['nombre'].' con 1';
                            }

                        }

                    } // if($d['tarta']!=''){

                    if($d['producto']!=''){


                        $arr_p = json_decode(utf8_encode($d['producto']),true,JSON_UNESCAPED_UNICODE);

                        ksort($arr_p);
                        foreach ($arr_p as $key => $value) {

                            if(!array_key_exists(strtoupper(sin_tildes($value['nombre'])), $comanda)){

                                $comanda['productos'][strtoupper(sin_tildes($value['nombre']))] = (int)$value['cantidad'];
                                // echo "<br>creamos el indice" . strtoupper($value['nombre']).' con '.(int)$value['cantidad'];

                            }else{

                                $comanda['productos'][strtoupper(sin_tildes($value['nombre']))] += (int)$value['cantidad'];
                                // echo "<br>sumamos al indice" . $value['nombre'].' con '.(int)$value['cantidad'];
                            }

                        }

                    } // if($d['tarta']!=''){


                } //  while ($d = $DB->fetch()) {

                $content .='<div style="width: 100%; height : 30px;">
                                <h4>Listado de Productos</h4>
                            </div>';

                ksort($comanda['productos']);
                foreach ($comanda['productos'] as $k => $v) {

                    $content .='<br>'.($k).' - '.$v.' Und.';
                            
                }

                $content .='<br><br><br><h4>Listado de Tartas</h4>';

                foreach ($comanda['tartas'] as $k => $v) {

                    $content .='<br>'.($k).' - '.$v.' Und.';
                            
                }

            $content .='</div>
                </page>';


    } // else if(isset($id_pedido)){


    if(isset($_SESSION['ip_admine'])){

        echo $content;

    }else{

        // convert to PDF
        require_once(dirname(__FILE__).'/html2pdf_compat.php');
        try
        {
            $html2pdf = new HTML2PDF('P', 'A4', 'es');
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
            $html2pdf->Output('pedidos_'.date('d-m-Y').'.pdf');
        }
        catch(HTML2PDF_exception $e) {
            echo $e;
            exit;
        }

    }

