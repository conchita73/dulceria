		<div class="main-header">
			<!-- Logo Header -->
			<div class="logo-header">
				<?php $href = isset($_SESSION['id_comercio']) ? 'area_clientes' : 'index' ?>
				<a href="<?php echo $href ?>.php" class="logo">
					<!-- <span class="text-white">La Argentina</span> -->
					<img src="img/logo_admin_2.png" alt="navbar brand" class="navbar-brand">
				</a>
				<button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon">
						<i class="icon-menu"></i>
					</span>
				</button>
				<button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
				<div class="nav-toggle">
					<button class="btn btn-toggle toggle-sidebar">
						<i class="icon-menu"></i>
					</button>
				</div>
			</div>
			<!-- End Logo Header -->
			<!-- Navbar Header -->
			<nav class="navbar navbar-header navbar-expand-lg">
				<div class="container-fluid">
					<ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
						<li class="nav-item">
							<a href="#" class="nav-link" id="desconectar_modal">
								<i class="fas fa-sign-out-alt"></i>
							</a>
						</li> 
						<li class="nav-item submenu" id="agenda_header">
							<a href="#"  class="nav-link quick-sidebar-toggler">
								<i class="fa fa-th"></i>
								<span class="notification d-none" id="not_header">0</span>
							</a>
						</li>
					</ul>
				</div>
			</nav>
			<!-- End Navbar -->
		</div>