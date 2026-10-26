<?php @session_start();
date_default_timezone_set('Europe/Madrid');
error_reporting(E_ALL);
    include("funciones.php");

    // DEFINIMOS LA SALIDA QUE VAMOS A TENER.

            # montamos fechas
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


    if(isset($id_factura) || isset($id_pedido)){ 


        $id = isset($id_factura) ? $id_factura : $id_pedido;
        $nombre_archivo='Factura_LAR_'.$id;
        // aqui vamos a sacar los datos del contrato. 

        $where = isset($id_factura) ? 'f.id='.$id : 'f.id_pedido='.$id;

        $data = $DB->inner_join('f.id as num_fac,
                                f.importe,
                                f.fecha_cobro,
                                f.concepto,
                                f.estado,
                                f.id_delegacion,
                                f.numero,
                                f.id_pedido,
                                f.descuento,
                                f.agrupado,
                                c.nombre,
                                c.nombre_fiscal,
                                c.cif,
                                c.direccion,
                                c.poblacion,
                                c.cp',
                                'facturas f inner join clientes c',
                                'f.id_cliente = c.id where '.$where);


        $datos=$data[f][0];

        $datos['titular'] = $datos['nombre_fiscal']!='' ? $datos['nombre_fiscal'] : $datos['nombre'];

        $pr = $datos['numero'] == '' ? 'Albarán' : 'Factura ';
        $rs = $datos['numero'] == '' ? 'Albarán' : 'N: RGSAA: 20.7649/BA';

        $n_fact = $datos['numero'] == '' ? $id : $datos['numero'];

        $datos['id_delegacion'] = isset($datos['id_delegacion']) && $datos['id_delegacion']!=0 ?  $datos['id_delegacion'] : 1;

        $data_delegacion = $DB->select('*','delegaciones','id='.$datos['id_delegacion']);
        $delegacion = $data_delegacion[f][0];

        // if($datos['aplica_iva'] == 1){

            $base = ($datos['importe']/100/110);
            $base_imponible = $base*100;
            $iva_factura = $base*10;
            $fase_iva = 'Iva (10%):';


            $content='<page orientation="portrait" style="font-size: 12px; width: 100%; margin-left:auto; margin-right:auto">
                                    <table style="width:99%;">
                                        <tbody>
                                            <tr>
                                                <td style="width:50%;">
                                                    <div style="width:100%;>
                                                        <img src="https://'.$delegacion['web'].'/new_app/img/logo_documentacion.png" height="100px;" alt="">
                                                        <br><br>
                                                        <b>'.utf8_encode($delegacion['gerente']).'</b><br>
                                                        CIF : '.$delegacion['cif'].'<br>
                                                        '.$delegacion['direccion'].'  <br>
                                                        '.$delegacion['cp'].' '.$delegacion['poblacion'].' <br>
                                                        '.$rs.'
                                                    </div>
                                                </td>
                                                <td style="width:50%;">
                                                    <div style="width: 100%; text-align: right;">
                                                    <br>
                                                    Lote : '.formatear_fecha($datos['fecha_cobro'],m,i).'<br>
                                                    Caducidad : '.suma_dias($datos['fecha_cobro'],3,m,i).'<br>
                                                    Fecha : '.formatear_fecha($datos['fecha_cobro'],m,i).' <br>
                                                    '.$pr.' N&ordm; : '.$n_fact.' 
                                                    </div>
                                                    <br><br>
                                                    <table style="width: 100%;" align="right">
                                                        <thead>
                                                            <tr>
                                                                
                                                                <th style="border: 1px grey solid; 
                                                                background-color: #'.$delegacion['color_email'].'; color: white; text-align:center;">
                                                                Datos Del Cliente</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td style="border: 1px grey solid;
                                                                padding:10px;">';


                                                                $arr_dat_fac = array(
                                                                            'titular'=>'Nombre',
                                                                            'cif'=>'Nif / Cif',
                                                                            'direccion'=>'Direcci&oacute;n',
                                                                            'poblacion'=>'Localidad',
                                                                            'cp'=>'CP'
                                                                        );

                                                                foreach ($arr_dat_fac as $key => $value) {

                                                                    if($datos[$key] != '' && $datos[$key] != '0'){

                                                                        $content.='<b>'.$value.':</b> '.utf8_encode($datos[$key]).' <br>';
                                                                    }
                                                                }

                                                    $content.= '</td>
                                                            </tr>
                                                        </tbody>
                                                    </table> 
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
       
                                    <table style="width: 99%; padding-top:20px; clear: both;">
                                             <thead>
                                                 <tr>
                                                     <th style="width: 70%; 
                                                     border: 1px grey solid;
                                                     padding:5px;
                                                     background-color: #'.$delegacion['color_email'].'; color: white;
                                                     ">Concepto</th>
                                                     <th style="width: 10%; 
                                                     border: 1px grey solid;
                                                     padding:5px;
                                                     background-color: #'.$delegacion['color_email'].'; color: white;">Unidades</th>
                                                     <th style="width: 20%; 
                                                     border: 1px grey solid;
                                                     padding:5px;
                                                     background-color: #'.$delegacion['color_email'].'; color: white;">Importe</th>
                                                 </tr>
                                             </thead>
                                            <tbody>';


                                            $importe_total = 0;

                                            

                                            if($datos['agrupado'] == 1){

                                                // echo $datos['concepto'];
                                                $conceptos = json_decode(($datos['concepto']),true,JSON_UNESCAPED_UNICODE);
                                                // var_dump($conceptos);
                                                 // $content.=count($conceptos);
                                                 // echo $conceptos[0]['id_factura'] ;
                                                 $v =  count($conceptos);

                                                 foreach ($conceptos as $key => $value) { 
                                                
                                                   
                                                    // $importe_total += $conceptos[$i]['pvp'];

                                                    $content.='<tr>
                                                         <td style="width: 70%; 
                                                         border: 1px grey solid;
                                                         padding:5px;                
                                                         "> 
                                                        albarán número '.$value['id_factura'].' del '.formatear_fecha($value['fecha'],m,i).'
                                                        
                                                         </td>
                                                         <td style="width: 10%; 
                                                         border: 1px grey solid;
                                                         padding:5px;
                                                         text-align: right;  
                                                         ">1</td>
                                                         <td style="width: 20%; 
                                                         border: 1px grey solid;
                                                         padding:5px;
                                                         text-align: right;  
                                                         ">'.number_format(($value['importe']), 2, ',', '').' &euro;</td>
                                                     </tr>';
                                                    // echo "<br>";


                                                } 

                                            }else if($datos['id_pedido'] == 0){

                                                $conceptos = json_decode(utf8_encode($datos['concepto']),true,JSON_UNESCAPED_UNICODE);

                                                for ($i=1; $i <= count($conceptos) ; $i++) { 
                                                
                                                    $conceptos[$i]['cantidad'] = isset($conceptos[$i]['cantidad']) ? $conceptos[$i]['cantidad'] : 1;
                                                    $importe_total += $conceptos[$i]['pvp'];

                                                    $content.='<tr>
                                                         <td style="width: 70%; 
                                                         border: 1px grey solid;
                                                         padding:5px;                
                                                         "> 
                                                        '.($conceptos[$i]['nombre']).'
                                                        
                                                         </td>
                                                         <td style="width: 10%; 
                                                         border: 1px grey solid;
                                                         padding:5px;
                                                         text-align: right;  
                                                         ">'.$conceptos[$i]['cantidad'].'</td>
                                                         <td style="width: 20%; 
                                                         border: 1px grey solid;
                                                         padding:5px;
                                                         text-align: right;  
                                                         ">'.number_format(($conceptos[$i]['pvp']/100), 2, ',', '').' &euro;</td>
                                                     </tr>';


                                                } 

                                            }else if($datos['id_pedido'] > 0){

                                                $conceptos = json_decode(utf8_encode($datos['concepto']),true,JSON_UNESCAPED_UNICODE);

                                                for ($i=0; $i <= count($conceptos) ; $i++) { 
                                                
                                                    if(isset($conceptos[$i]['pvp']) && $conceptos[$i]['pvp'] !='' && isset($conceptos[$i]['nombre']) && $conceptos[$i]['nombre']!=''){

                                                        $conceptos[$i]['cantidad'] = isset($conceptos[$i]['cantidad']) ? $conceptos[$i]['cantidad'] : 1;

                                                        $importe_total += $conceptos[$i]['pvp'];

                                                        $content.='<tr>
                                                             <td style="width: 70%; 
                                                             border: 1px grey solid;
                                                             padding:5px;                
                                                             "> 
                                                            '.strtoupper(utf8_decode($conceptos[$i]['nombre'])).'
                                                            
                                                             </td>
                                                             <td style="width: 10%; 
                                                                 border: 1px grey solid;
                                                                 padding:5px;
                                                                 text-align: right;  
                                                                 ">'.$conceptos[$i]['cantidad'].'</td>
                                                             <td style="width: 20%; 
                                                             border: 1px grey solid;
                                                             padding:5px;
                                                             text-align: right;  
                                                             ">'.number_format(($conceptos[$i]['pvp']), 2, ',', '').' &euro;</td>
                                                         </tr>';

                                                    }
                                                } 

                                            } // if($d['id_pedido'] == 0){
                                            



                                     $content.='</tbody>
                                    </table>
                                    <br><br>
                                    <table style="width: 40%;" align="right">
                                        <tbody>';

                                    if($datos['descuento']>0){

                                        $content.='<tr>
                                                <td style="border: 1px grey solid;
                                                padding:5px;
                                                width: 70%;
                                                background-color: #'.$delegacion['color_email'].'; color: white;">
                                                <b>Importe total:</b>
                                                </td>
                                                <td style="border: 1px grey solid;
                                                padding:5px;
                                                text-align: right;
                                                width: 30%;
                                                background-color: #'.$delegacion['color_email'].'; color: white;">
                                                    '.number_format($importe_total, 2, ',', '').' &euro;
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px grey solid;
                                                padding:5px;
                                                width: 70%;">
                                                <b>Descuento Aplicado:</b>
                                                </td>
                                                <td style="border: 1px grey solid;
                                                padding:5px;
                                                text-align: right;
                                                width: 30%;">
                                                    '.$datos['descuento'].' %
                                                </td>
                                            </tr>';

                                    }

                                    if($datos['numero'] != ''){


                                    
                                        $content.='  <tr>
                                                    <td style="border: 1px grey solid;
                                                    padding:5px;
                                                    width: 70%;
                                                    background-color: #'.$delegacion['color_email'].'; color: white;">
                                                    <b>Base Imponible:</b>
                                                    </td>
                                                    <td style="border: 1px grey solid;
                                                    padding:5px;
                                                    text-align: right;
                                                    width: 30%;
                                                    background-color: #'.$delegacion['color_email'].'; color: white;">
                                                        '.number_format($base_imponible, 2, ',', '').' &euro;
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px grey solid;
                                                    padding:5px;
                                                    width: 70%;">
                                                    <b>'.$fase_iva.'</b>
                                                    </td>
                                                    <td style="border: 1px grey solid;
                                                    padding:5px;
                                                    text-align: right;
                                                    width: 30%;">
                                                        '.number_format($iva_factura, 2, ',', '').' &euro;
                                                    </td>
                                                </tr>';

                                        }

                                        $content.='   <tr>
                                                <td style="border: 1px grey solid;
                                                padding:5px;
                                                width: 70%;
                                                background-color: #'.$delegacion['color_email'].'; color: white;">
                                                <b>Total Factura:</b>
                                                </td>
                                                <td style="border: 1px grey solid;
                                                padding:5px;
                                                text-align: right;
                                                width: 30%;
                                                background-color: #'.$delegacion['color_email'].'; color: white;">
                                                '.number_format(($datos['importe']/100), 2, ',', '').' &euro;
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                      </page>';

           $content.='<page orientation="portrait" style="font-size: 12px; width: 100%; margin-left:auto; margin-right:auto">
                                    <table style="width:99%;">
                                        <tbody>
                                            <tr>
                                                <td style="width:50%;">
                                                    <div style="width:100%;>
                                                        <img src="https://'.$delegacion['web'].'/new_app/img/logo_documentacion.png" height="100px;" alt="">
                                                        <br><br>
                                                        <b>'.utf8_encode($delegacion['gerente']).'</b><br>
                                                        CIF : '.$delegacion['cif'].'<br>
                                                        '.$delegacion['direccion'].'  <br>
                                                        '.$delegacion['cp'].' '.$delegacion['poblacion'].' <br>
                                                        '.$rs.'
                                                    </div>
                                                </td>
                                                <td style="width:50%;">
                                                     <div style="width: 100%; text-align: right;">
                                                     <br>
                                                    Lote : '.formatear_fecha($datos['fecha_cobro'],m,i).'<br>
                                                    Caducidad : '.suma_dias($datos['fecha_cobro'],3,m,i).'<br>
                                                    Fecha : '.formatear_fecha($datos['fecha_cobro'],m,i).' <br>
                                                    '.$pr.' N&ordm; : '.$n_fact.$rs.' 
                                                    </div>
                                                    <br><br>
                                                    <table style="width: 100%;" align="right">
                                                        <thead>
                                                            <tr>
                                                                
                                                                <th style="border: 1px grey solid; 
                                                                background-color: #'.$delegacion['color_email'].'; color: white; text-align:center;">
                                                                Datos Del Cliente</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td style="border: 1px grey solid;
                                                                padding:10px;">';


                                                                $arr_dat_fac = array(
                                                                            'titular'=>'Nombre',
                                                                            'cif'=>'Nif / Cif',
                                                                            'direccion'=>'Direcci&oacute;n',
                                                                            'poblacion'=>'Localidad',
                                                                            'cp'=>'CP'
                                                                        );

                                                                foreach ($arr_dat_fac as $key => $value) {

                                                                    if($datos[$key] != '' && $datos[$key] != '0'){

                                                                        $content.='<b>'.$value.':</b> '.utf8_encode($datos[$key]).' <br>';
                                                                    }
                                                                }

                                                    $content.='</td>
                                                            </tr>
                                                        </tbody>
                                                    </table> 
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
       
                                    <table style="width: 99%; padding-top:20px; clear: both;">
                                             <thead>
                                                 <tr>
                                                     <th style="width: 70%; 
                                                     border: 1px grey solid;
                                                     padding:5px;
                                                     background-color: #'.$delegacion['color_email'].'; color: white;
                                                     ">Concepto</th>
                                                     <th style="width: 10%; 
                                                     border: 1px grey solid;
                                                     padding:5px;
                                                     background-color: #'.$delegacion['color_email'].'; color: white;">Unidades</th>
                                                     <th style="width: 20%; 
                                                     border: 1px grey solid;
                                                     padding:5px;
                                                     background-color: #'.$delegacion['color_email'].'; color: white;">Importe</th>
                                                 </tr>
                                             </thead>
                                            <tbody>';


                                            $importe_total = 0;

                                            $conceptos = json_decode(utf8_encode($datos['concepto']),true,JSON_UNESCAPED_UNICODE);

                                            if($datos['agrupado'] == 1){

                                            }else if($datos['id_pedido'] == 0){

                                                for ($i=1; $i <= count($conceptos) ; $i++) { 
                                                
                                                    $conceptos[$i]['cantidad'] = isset($conceptos[$i]['cantidad']) ? $conceptos[$i]['cantidad'] : 1;
                                                    $importe_total += $conceptos[$i]['pvp'];

                                                    $content.='<tr>
                                                         <td style="width: 70%; 
                                                         border: 1px grey solid;
                                                         padding:5px;                
                                                         "> 
                                                        '.($conceptos[$i]['nombre']).'
                                                        
                                                         </td>
                                                         <td style="width: 10%; 
                                                         border: 1px grey solid;
                                                         padding:5px;
                                                         text-align: right;  
                                                         ">'.$conceptos[$i]['cantidad'].'</td>
                                                         <td style="width: 20%; 
                                                         border: 1px grey solid;
                                                         padding:5px;
                                                         text-align: right;  
                                                         ">'.number_format(($conceptos[$i]['pvp']/100), 2, ',', '').' &euro;</td>
                                                     </tr>';


                                                } 

                                            }else if($datos['id_pedido'] > 0){

                                                for ($i=0; $i <= count($conceptos) ; $i++) { 
                                                
                                                    if(isset($conceptos[$i]['pvp']) && $conceptos[$i]['pvp'] !='' && isset($conceptos[$i]['nombre']) && $conceptos[$i]['nombre']!=''){

                                                        $conceptos[$i]['cantidad'] = isset($conceptos[$i]['cantidad']) ? $conceptos[$i]['cantidad'] : 1;

                                                        $importe_total += $conceptos[$i]['pvp'];

                                                        $content.='<tr>
                                                             <td style="width: 70%; 
                                                             border: 1px grey solid;
                                                             padding:5px;                
                                                             "> 
                                                            '.strtoupper(utf8_decode($conceptos[$i]['nombre'])).'
                                                            
                                                             </td>
                                                             <td style="width: 10%; 
                                                                 border: 1px grey solid;
                                                                 padding:5px;
                                                                 text-align: right;  
                                                                 ">'.$conceptos[$i]['cantidad'].'</td>
                                                             <td style="width: 20%; 
                                                             border: 1px grey solid;
                                                             padding:5px;
                                                             text-align: right;  
                                                             ">'.number_format(($conceptos[$i]['pvp']), 2, ',', '').' &euro;</td>
                                                         </tr>';

                                                    }
                                                } 

                                            } // if($d['id_pedido'] == 0){
                                            



                                     $content.='</tbody>
                                    </table>
                                    <br><br>
                                    <table style="width: 40%;" align="right">
                                        <tbody>';

                                    if($datos['descuento']>0){

                                        $content.='<tr>
                                                <td style="border: 1px grey solid;
                                                padding:5px;
                                                width: 70%;
                                                background-color: #'.$delegacion['color_email'].'; color: white;">
                                                <b>Importe total:</b>
                                                </td>
                                                <td style="border: 1px grey solid;
                                                padding:5px;
                                                text-align: right;
                                                width: 30%;
                                                background-color: #'.$delegacion['color_email'].'; color: white;">
                                                    '.number_format($importe_total, 2, ',', '').' &euro;
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px grey solid;
                                                padding:5px;
                                                width: 70%;">
                                                <b>Descuento Aplicado:</b>
                                                </td>
                                                <td style="border: 1px grey solid;
                                                padding:5px;
                                                text-align: right;
                                                width: 30%;">
                                                    '.$datos['descuento'].' %
                                                </td>
                                            </tr>';

                                    }

                                    if($datos['numero'] != ''){


                                    
                                        $content.='  <tr>
                                                    <td style="border: 1px grey solid;
                                                    padding:5px;
                                                    width: 70%;
                                                    background-color: #'.$delegacion['color_email'].'; color: white;">
                                                    <b>Base Imponible:</b>
                                                    </td>
                                                    <td style="border: 1px grey solid;
                                                    padding:5px;
                                                    text-align: right;
                                                    width: 30%;
                                                    background-color: #'.$delegacion['color_email'].'; color: white;">
                                                        '.number_format($base_imponible, 2, ',', '').' &euro;
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px grey solid;
                                                    padding:5px;
                                                    width: 70%;">
                                                    <b>'.$fase_iva.'</b>
                                                    </td>
                                                    <td style="border: 1px grey solid;
                                                    padding:5px;
                                                    text-align: right;
                                                    width: 30%;">
                                                        '.number_format($iva_factura, 2, ',', '').' &euro;
                                                    </td>
                                                </tr>';

                                        }

                                        $content.='   <tr>
                                                <td style="border: 1px grey solid;
                                                padding:5px;
                                                width: 70%;
                                                background-color: #'.$delegacion['color_email'].'; color: white;">
                                                <b>Total Factura:</b>
                                                </td>
                                                <td style="border: 1px grey solid;
                                                padding:5px;
                                                text-align: right;
                                                width: 30%;
                                                background-color: #'.$delegacion['color_email'].'; color: white;">
                                                '.number_format(($datos['importe']/100), 2, ',', '').' &euro;
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                      </page>';


    }else if(isset($id_cliente)){  //   if(isset($id_factura) || isset($id_pedido)){ 


        $nombre_archivo='albaranes_pendientes';
        // aqui vamos a sacar los datos del contrato. 

        $where = isset($desde) && isset($hasta) ? ' and fecha_cobro between "'.$desde.'" and "'.$hasta.'"' : '';

        $data = $DB->inner_join('f.id as num_fac,
                                f.importe,
                                f.fecha_cobro,
                                f.concepto,
                                f.estado,
                                f.id_delegacion,
                                f.numero,
                                f.id_pedido,
                                f.descuento,
                                c.nombre,
                                c.nombre_fiscal,
                                c.cif,
                                c.direccion,
                                c.poblacion,
                                c.cp',
                                'facturas f inner join clientes c',
                                'f.id_cliente = c.id where f.id_cliente='.$id_cliente.' and f.estado='.$pagadas.$where);
        $content='';
        for ($aa=0; $aa < $data[r]; $aa++) { 
            # code...
       
        $datos=$data[f][$aa];

        $datos['titular'] = $datos['nombre_fiscal']!='' ? $datos['nombre_fiscal'] : $datos['nombre'];

        $pr = $datos['numero'] == '' ? 'Albarán' : 'Factura ';
        $n_fact = $datos['numero'] == '' ? $datos['num_fac'] : $datos['numero'];
        $datos['id_delegacion'] = isset($datos['id_delegacion']) && $datos['id_delegacion']!='' ?  $datos['id_delegacion'] : 1;
        # datos del
        $data_delegacion = $DB->select('*','delegaciones','id='.$datos['id_delegacion']);
        $delegacion = $data_delegacion[f][0];

        // if($datos['aplica_iva'] == 1){

            $base = ($datos['importe']/100/110);
            $base_imponible = $base*100;
            $iva_factura = $base*10;
            $fase_iva = 'Iva (10%):';

        $content.='<page orientation="portrait" style="font-size: 12px; width: 100%; margin-left:auto; margin-right:auto">
                                    <table style="width:99%;">
                                        <tbody>
                                            <tr>
                                                <td style="width:50%;">
                                                    <div style="width:100%;>
                                                        <img src="https://'.$delegacion['web'].'/new_app/img/logo_documentacion.png" height="100px;" alt="">
                                                        <br><br>
                                                        <b>'.utf8_encode($delegacion['gerente']).'</b><br>
                                                        CIF : '.$delegacion['cif'].'<br>
                                                        '.$delegacion['direccion'].'  <br>
                                                        '.$delegacion['cp'].' '.$delegacion['poblacion'].' <br>
                                                    </div>
                                                </td>
                                                <td style="width:50%;">
                                                     <div style="width: 100%; text-align: right;">
                                                     <br>
                                                    Fecha : '.formatear_fecha($datos['fecha_cobro'],m,i).' <br>
                                                    '.$pr.' N&ordm; : '.$n_fact.' 
                                                </div>
                                                    <br><br>
                                                    <table style="width: 100%;" align="right">
                                                        <thead>
                                                            <tr>
                                                                
                                                                <th style="border: 1px grey solid; 
                                                                background-color: #'.$delegacion['color_email'].'; color: white; text-align:center;">
                                                                Datos Del Cliente</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td style="border: 1px grey solid;
                                                                padding:10px;">';


                                                                $arr_dat_fac = array(
                                                                            'titular'=>'Nombre',
                                                                            'cif'=>'Nif / Cif',
                                                                            'direccion'=>'Direcci&oacute;n',
                                                                            'poblacion'=>'Localidad',
                                                                            'cp'=>'CP'
                                                                        );

                                                                foreach ($arr_dat_fac as $key => $value) {

                                                                    if($datos[$key] != '' && $datos[$key] != '0'){

                                                                        $content.='<b>'.$value.':</b> '.utf8_encode($datos[$key]).' <br>';
                                                                    }
                                                                }

                                                    $content.='</td>
                                                            </tr>
                                                        </tbody>
                                                    </table> 
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
       
                                    <table style="width: 99%; padding-top:20px; clear: both;">
                                             <thead>
                                                 <tr>
                                                     <th style="width: 70%; 
                                                     border: 1px grey solid;
                                                     padding:5px;
                                                     background-color: #'.$delegacion['color_email'].'; color: white;
                                                     ">Concepto</th>
                                                     <th style="width: 10%; 
                                                     border: 1px grey solid;
                                                     padding:5px;
                                                     background-color: #'.$delegacion['color_email'].'; color: white;">Unidades</th>
                                                     <th style="width: 20%; 
                                                     border: 1px grey solid;
                                                     padding:5px;
                                                     background-color: #'.$delegacion['color_email'].'; color: white;">Importe</th>
                                                 </tr>
                                             </thead>
                                            <tbody>';


                                            $importe_total = 0;

                                            $conceptos = json_decode(utf8_encode($datos['concepto']),true,JSON_UNESCAPED_UNICODE);

                                            if($datos['id_pedido'] == 0){

                                                for ($i=1; $i <= count($conceptos) ; $i++) { 
                                                
                                                    $conceptos[$i]['cantidad'] = isset($conceptos[$i]['cantidad']) ? $conceptos[$i]['cantidad'] : 1;
                                                    $importe_total += $conceptos[$i]['pvp'];

                                                    $content.='<tr>
                                                         <td style="width: 70%; 
                                                         border: 1px grey solid;
                                                         padding:5px;                
                                                         "> 
                                                        '.($conceptos[$i]['nombre']).'
                                                        
                                                         </td>
                                                         <td style="width: 10%; 
                                                         border: 1px grey solid;
                                                         padding:5px;
                                                         text-align: right;  
                                                         ">'.$conceptos[$i]['cantidad'].'</td>
                                                         <td style="width: 20%; 
                                                         border: 1px grey solid;
                                                         padding:5px;
                                                         text-align: right;  
                                                         ">'.number_format(($conceptos[$i]['pvp']/100), 2, ',', '').' &euro;</td>
                                                     </tr>';


                                                } 

                                            }else if($datos['id_pedido'] > 0){

                                                for ($i=0; $i <= count($conceptos) ; $i++) { 
                                                
                                                    if(isset($conceptos[$i]['pvp']) && $conceptos[$i]['pvp'] !='' && isset($conceptos[$i]['nombre']) && $conceptos[$i]['nombre']!=''){

                                                        $conceptos[$i]['cantidad'] = isset($conceptos[$i]['cantidad']) ? $conceptos[$i]['cantidad'] : 1;

                                                        $importe_total += $conceptos[$i]['pvp'];

                                                        $content.='<tr>
                                                             <td style="width: 70%; 
                                                             border: 1px grey solid;
                                                             padding:5px;                
                                                             "> 
                                                            '.strtoupper(utf8_decode($conceptos[$i]['nombre'])).'
                                                            
                                                             </td>
                                                             <td style="width: 10%; 
                                                                 border: 1px grey solid;
                                                                 padding:5px;
                                                                 text-align: right;  
                                                                 ">'.$conceptos[$i]['cantidad'].'</td>
                                                             <td style="width: 20%; 
                                                             border: 1px grey solid;
                                                             padding:5px;
                                                             text-align: right;  
                                                             ">'.number_format(($conceptos[$i]['pvp']), 2, ',', '').' &euro;</td>
                                                         </tr>';

                                                    }
                                                } 

                                            } // if($d['id_pedido'] == 0){
                                            



                                     $content.='</tbody>
                                    </table>
                                    <br><br>
                                    <table style="width: 40%;" align="right">
                                        <tbody>';

                                    if($datos['descuento']>0){

                                        $content.='<tr>
                                                <td style="border: 1px grey solid;
                                                padding:5px;
                                                width: 70%;
                                                background-color: #'.$delegacion['color_email'].'; color: white;">
                                                <b>Importe total:</b>
                                                </td>
                                                <td style="border: 1px grey solid;
                                                padding:5px;
                                                text-align: right;
                                                width: 30%;
                                                background-color: #'.$delegacion['color_email'].'; color: white;">
                                                    '.number_format($importe_total, 2, ',', '').' &euro;
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px grey solid;
                                                padding:5px;
                                                width: 70%;">
                                                <b>Descuento Aplicado:</b>
                                                </td>
                                                <td style="border: 1px grey solid;
                                                padding:5px;
                                                text-align: right;
                                                width: 30%;">
                                                    '.$datos['descuento'].' %
                                                </td>
                                            </tr>';

                                    }

                                    if($datos['numero'] != ''){


                                    
                                        $content.='  <tr>
                                                    <td style="border: 1px grey solid;
                                                    padding:5px;
                                                    width: 70%;
                                                    background-color: #'.$delegacion['color_email'].'; color: white;">
                                                    <b>Base Imponible:</b>
                                                    </td>
                                                    <td style="border: 1px grey solid;
                                                    padding:5px;
                                                    text-align: right;
                                                    width: 30%;
                                                    background-color: #'.$delegacion['color_email'].'; color: white;">
                                                        '.number_format($base_imponible, 2, ',', '').' &euro;
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px grey solid;
                                                    padding:5px;
                                                    width: 70%;">
                                                    <b>'.$fase_iva.'</b>
                                                    </td>
                                                    <td style="border: 1px grey solid;
                                                    padding:5px;
                                                    text-align: right;
                                                    width: 30%;">
                                                        '.number_format($iva_factura, 2, ',', '').' &euro;
                                                    </td>
                                                </tr>';

                                        }

                                        $content.='   <tr>
                                                <td style="border: 1px grey solid;
                                                padding:5px;
                                                width: 70%;
                                                background-color: #'.$delegacion['color_email'].'; color: white;">
                                                <b>Total Factura:</b>
                                                </td>
                                                <td style="border: 1px grey solid;
                                                padding:5px;
                                                text-align: right;
                                                width: 30%;
                                                background-color: #'.$delegacion['color_email'].'; color: white;">
                                                '.number_format(($datos['importe']/100), 2, ',', '').' &euro;
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                      </page>';
         }

    } //   if(isset($id_cliente)){ 


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

