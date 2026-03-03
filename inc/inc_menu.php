		
		<?php define('HOME_PATH', '/new_app');  ?>
		<!-- Sidebar -->
		<div class="sidebar sidebar-style-2">			
			<div class="sidebar-wrapper scrollbar">
				<div class="sidebar-content">
					<ul class="nav">
						<li class="nav-item">
							<a href="panel.php"> 
								<i class="fas fa-solar-panel"></i>
								<p>Panel Principal</p>
								<!-- panel.php <span class="badge badge-count">5</span> -->
							</a>
						</li>
						<!-- Menu 2 Sections -->
						<li class="nav-section">
							<span class="sidebar-mini-icon">
								<i class="fa fa-ellipsis-h"></i>
							</span>
							<h4 class="text-section">Secciones</h4>
						</li>						
						<?php 

							 	$DB->query('SELECT * FROM modulos order by orden');
							  	while($d = $DB->fetch()){
							 		
							 		if($_SESSION['Authenticated']==1 || in_array($d['id'],$_SESSION['arr_permisos']) && $d['estado'] == 1){

							 			if($d['nombre'] != 'Agenda' || $d['nombre'] == 'Agenda' && $_SESSION['id_usuario'] == 1)


											$url = $d['url'] == '#' ? '#' : HOME_PATH.'/'.$d['url'].'.php';


							 				echo '<li class="nav-item">
											    <a href="'.$url.'">  
											    	<i class="'.$d['icono'].'"></i>   
											    	<span class="sub-item">'.ucwords(utf8_encode($d['nombre'])).'</span>';

											if($d['nombre'] == 'Agenda'){

											 	echo '<span class="badge badge-success d-none" id="badge-menu">0</span>';
											}


											echo '
											    </a>
											   </li>';
									}

							  	}


								if(isset($_SESSION['ip_admin'])){

								
								
									echo '<li class="nav-section">
										<span class="sidebar-mini-icon">
											<i class="fa fa-ellipsis-h"></i>
										</span>
										<h4 class="text-section">Pedro</h4>
									</li>';
									//SUBOPCION (SOLO AÑADIR UNA POSICION + AL ARRAY)
									$sup_option=array(
										"Modulos"=>array(
													"icono"=>"fas fa-server",
													"url"=>"modulos"), //facturacion
										"T Bodys"=>array(
													"icono"=>"fas fa-table",
													"url"=>"t_body"),
										"Formularios"=>array(
														"icono"=>"fab fa-wpforms",
														"url"=>"formularios")
										);
							    

								    foreach ($sup_option as $key => $value) {

									    echo '<li class="nav-item">
											    <a href="'.HOME_PATH.'/'.$value['url'].'.php">  
											    	<i class="'.$value['icono'].'"></i>   
											    	<span class="sub-item">'.ucwords($key).'</span>
											    </a>
											   </li>';

									}// foreach ($sup_option as $key => $value) {

								}
/*
						?>
						

						<!-- Menu 1 desplegable -->
						<li class="nav-item">
							<a data-toggle="collapse" href="#productos" class="collapsed" aria-expanded="false">
								<i class="fas fa-keyboard"></i>
								<p>Productos</p>
								<span class="caret"></span>
							</a>

							<div class="collapse" id="productos">
								<ul class="nav nav-collapse">

									<?php 

										//SUBOPCION (SOLO AÑADIR UNA POSICION + AL ARRAY)
								        $sup_option=array(
								            "productos"=>"productos",
								            "tipos de productos"=>"tipos_productos",
								            "tipos de pagos"=>"tipos_pagos");

								            foreach ($sup_option as $key => $value) {

									            echo '<li class="">
											            <a href="'.HOME_PATH.'/'.$value.'.php">     
											            	<span class="sub-item">'.ucwords($key).'</span>
											            </a>
											           </li>';


									        }// foreach ($sup_option as $key => $value) {

									?>
								</ul>
							</div>
						</li> <!-- Menu 1 desplegable -->

						<?php */
					 ?>
						<!-- <li class="nav-item">
							<a href="projects.html">
								<i class="fas fa-file-signature"></i>
								<p>Projects</p>
								<span class="badge badge-count">5</span>
							</a>
						</li> -->
					</ul>
				</div>
			</div>
		</div>
		<!-- End Sidebar -->