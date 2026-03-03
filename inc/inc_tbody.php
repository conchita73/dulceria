
    <?php 

        $datatable_on = !isset($datatable) ? 'datatable' : $datatable;

    ?>
    <table id="<?php echo $id_table; ?>" class="display table table-hover <?php echo $datatable_on ?> table-bordered table-responsive <?php echo $clase_table; ?>">
            <thead>
               <tr  class="active">
                <?php 

                    // thead 
                    foreach ($datos_cabecera as $value) {
                            
                        if($value!="id"){
                            echo "
                            <th>".ucwords(strtolower($value))."</th>
                            ";
                        }
                    }
                    echo "<th style='width: 15%'>Acciones</th>";
                ?>
                </tr>
            </thead>                                  
            <tbody>
            <?php

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
        
                // QUERY XRA CONTENIDO DE TABLA 
                if($tabla_inner!=""){

                    $resultado=$DB->inner_join($datos_query,$tabla_inner,$on);

                }else if(isset($tabla_inner) && $tabla_inner==0 || !isset($tabla_inner)){

                    if(!isset($where) || $where==""){

                        $where ="id>0";
                    }
                    $resultado=$DB->select($datos_query,$tabla,$where); 
                } // if($tabla_inner!=""){


                if($resultado[r]>0){

                    for($x=0;$x<$resultado[r];$x++){


                            if(isset($resultado[f][$x]["title"])){

                                $title='title="'.$resultado[f][$x]["title"].'"';
                            
                            }else{

                               $title='title="'.$resultado[f][$x]["id"].'"';

                            }

                        echo '<tr>';
                        //METEMOS TODOS LOS CAMPOS DE 1

                        $control_td=0; // con esta $ tengo el control de cada vuelta

                        foreach ($datos_cabecera as $key) {

                            echo '<td ';

                            // aqui añadimos en principio el id
                            // la idea, sera extrapolar a span con el fin de poder
                            // filtrar por campos que no se vean.
                            
                            if($control_td==0){
                                echo ' '.$title.' data-placement="bottom" data-toggle="tooltipC" ';
                            }

                            if($key!="id"){


                                if($tabla=="_paises"){

                                    echo 'class="info">
                                        '.utf8_encode($resultado[f][$x][$key]).'
                                        <input type="text"
                                            tabla_editar="'.$tabla.'"
                                            id_editar="'.$resultado[f][$x]["id"].'" 
                                            name="'.$key.'"
                                            class="edita_input form-control"  
                                            value="'.utf8_encode($resultado[f][$x][$key]).'" 
                                            placeholder="">
                                         
                                    </td>';

                                }else if($key=="estado" && $tabla!="estados"){

                                    if($resultado[f][$x][$key]=="1"){
                                        echo 'class="bg-success">Activo</td>';
                                    }else if($resultado[f][$x][$key]=="0"){
                                        echo 'class=".bg-danger">Baja</td>';
                                    }


                                }else if($key=="costo" || $key=="pvp"){

                                    $nuevo_importe=$resultado[f][$x][$key]/100;

                                    echo 'class="info">'.number_format($nuevo_importe,2,".","").'</td>';


                                }else if($key=="entrada" || $key=="salida" || $key=="fecha" || $key=="creacion"){

                                    $pre_fecha = explode(" ",$resultado[f][$x][$key]);
                                    $nueva_fecha=formatear_fecha($pre_fecha[0],m,i);
                                    //$nueva_fecha=formatear_fecha($resultado[f][$x][$key],m,i);
                                    $hora = isset($pre_fecha[1]) ? " ".$pre_fecha[1] : "";

                                    echo 'class="info">'.$nueva_fecha.$hora.'</td>';                        

                                }else if($tabla=="booking" && $key=="estados"){

                                    if($resultado[f][$x][$key]=="Gestionando"){
                                        $bg_class='.bg-primary';
                                    }else if($resultado[f][$x][$key]=="Pendiente"){
                                        $bg_class='.bg-danger';
                                    }else if($resultado[f][$x][$key]=="En proceso"){
                                        $bg_class='bg-warning';
                                    }else{
                                        $bg_class='bg-success';               
                                    }

                                    //Especialidad Estados de Booking   
                                    echo 'class="'.$bg_class.'">'.utf8_encode($resultado[f][$x][$key]).'</td>';

                                }else if($tabla=="saldo_empresa" && $key=="movimiento"){

                                    $signo = $resultado[f][$x]['tipo']==1 ? "+" : (strpos($resultado[f][$x][$key],"-")===false ? "-" : "");
                                    $color_signo = $resultado[f][$x]['tipo']==1 ? "green" : "red";

                                    echo 'class="info" style="color : '.$color_signo.';">'.$signo.' '.$resultado[f][$x][$key].'</td>';



                                }else if($tabla=="clientes" && $key=="nombre"){

                                    $noches_c = json_decode($resultado[f][$x]['noches'],true);
                                    $email_c = email_json_decode($resultado[f][$x]['email']);

                                    echo 'class="info"><i class="fa fa-info fa-2x" style="color:green;" data-placement="bottom" data-toggle="tooltipC" 
                                    title="Email: '.$email_c.' &#10; Telefono: '.$resultado[f][$x]['telefono'].'&#10; Disponibles: '.$noches_c['disponibles'].' &#10; Usados: '.$noches_c['usados'].' &#10; Comprados: '.$noches_c['comprados'].'&#10;"></i> - <a href="mailto:'.$email_c.'">'.utf8_encode($resultado[f][$x][$key]).'</a></td>';


                                }else{

                                    echo 'class="info">'.utf8_encode($resultado[f][$x][$key]).'</td>';

                                } // if($key=="estado" && $tabla!="estados"){ .... // if($key=="estado" && $tabla!="estados"){ ....
                                
                            } // eof if($key!="id"){
                            $control_td++;
                        
                        } // eof foreach ($datos_tabla as $key => $value


                        echo '<td>
                                <div class="form-button-action">';

                                    if(isset($enviar_email) && $enviar_email==1){

                                        echo '<button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-success btn-lg" data-original-title="Envio" data-toggle="modal" 
                                            data-target="#email_registro"
                                            data-backdrop="static" data-keyboard="false"
                                            name="enviar_email"
                                            tabla="'.$tabla.'"
                                            id="'.$resultado[f][$x]['id'].'">
                                            <i class="fa fa-envelope"></i>
                                        </button>';
                                    }

                                     if(isset($edit_pop_up) && $edit_pop_up[0]==true){
                                        // boton de editar pop_up 
                                        echo '<button type="button" title="" 
                                                class="btn btn-link btn-info btn-lg"
                                                name="editar_pop_up"
                                                tabla="'.$tabla.'"
                                                id="'.$resultado[f][$x]['id'].'">
                                                <i class="fa fa-edit" data-toggle="tooltip" data-original-title="Editar"></i>
                                               </button>';

                                    }else{
                                        
                                        echo '<button type="button" title="" 
                                            class="btn btn-link btn-info btn-lg"
                                            data-toggle="modal" 
                                            data-target="#'.$modal_1[0].'_editar"
                                            data-backdrop="static" data-keyboard="false"
                                            name="editar"
                                            tabla="'.$tabla.'"
                                            id="'.$resultado[f][$x]['id'].'">
                                            <i class="fa fa-edit" data-toggle="tooltip" data-original-title="Editar"></i>
                                        </button>';
                                    }

                                    if(isset($delete) && $delete==1){

                                        echo '<button type="button" title="" class="btn btn-link btn-danger" 
                                            data-toggle="modal" 
                                            data-target="#borrar_registro"
                                            data-backdrop="static" data-keyboard="false"
                                            name="delete" 
                                            id="'.$resultado[f][$x]['id'].'">
                                            <i class="fa fa-times" data-toggle="tooltip" data-original-title="Borrar"></i>
                                            </button>';
                                    }

                        echo    '</div>
                            </td>
                        </tr>';

                    } // for($x=0;$x<$resultado[r];$x++){

                }   // if($resultado[r]>0){     

            ?>
        </tbody>              
    </table>
            