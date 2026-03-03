<?php 

  include('functions/funciones.php');

   $name = 'Facturas ('.$desde.' - '.$hasta.')';

    header("Content-Type: application/vnd.ms-excel");

    header("Expires: 0");

    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

    header("content-disposition: attachment;filename=".$name.".xls");

    date_default_timezone_set('Europe/Madrid');

    echo "<table >
            <thead>
              	<th>Raz&oacute;n Social</th>
              	<th>CIF/NIF</th>
                <th>Factura</th>
              	<th>Fecha</th>
              	<th>Base Imponible</th>
              	<th>Iva</th>
              	<th>Total</th>
            <thead>";


        $q = 'select c.nombre_fiscal,c.cif,f.importe,f.fecha_cobrada,f.numero from facturas f inner join clientes c on f.id_cliente = c.id 
            where fecha_cobro between "'.formatear_fecha($desde,i,m).'" and "'.formatear_fecha($hasta,i,m).'" and f.estado = 1 ';
        $q.= $id_cliente>0 ? ' and f.id_cliente = '.$id_cliente : '';
        
        $q.= ' order by f.numero ASC';

        $imp = 0;
        $DB->query($q);
        $f = 0;
        while($d = $DB->fetch()){

        	$iva[$f] = ($d['importe']/100)*10/100;
	        $base[$f] = ($d['importe']/100)*90/100;
	        $total[$f] = $d['importe']/100;
          list($fecha,$hora) = explode(' ',$d['fecha_cobrada']);
	        echo '<tr>
				        <td>'.utf8_encode($d['nombre_fiscal']).'</td>
              	<td>'.$d['cif'].'</td>
                <td>'.$d['numero'].'</td>
              	<td>'.formatear_fecha($fecha,m,i).'</td>
              	<td>'.number_format($base[$f],2,'.','').'</td>
              	<td>'.number_format($iva[$f],2,'.','').'</td>
              	<td>'.number_format($total[$f],2,'.','').'</td>
	        </tr>';

            $imp+=$d['importe'];
            $f++;
        }
        $iva = ($imp/100)*10/100;
        $base = ($imp/100)*90/100;
        $total = $imp/100;

        echo "<tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>".number_format($base,2,'.','')."</td>
                <td>".number_format($iva,2,'.','')."</td>
                <td>".number_format($total,2,'.','')."</td>
              </tr>
        </table>";

?>