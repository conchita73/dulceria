<?php 



include 'functions/funciones.php';

accion_admin(r,"functions/ajax.php?logear_usuario_inicio=1&usr=***&pwd=*****");



/*



logo en admin, correos , facturas 

email y pass en PHPMailer



*/



?>



<!DOCTYPE html>

<html lang="en">

<head>



	<?php include 'inc/inc_head.php'; ?>

</head>

<body class="login">

	<div class="wrapper wrapper-login wrapper-login-full p-0">

		<div class="login-aside w-50 d-flex flex-column bg-white align-items-center justify-content-center text-center ">

			<h1 class="title fw-bold text-white mb-3"><img src="img/logo_argentina.png" height="150px" alt="" class="img-fluid"></h1>

			<p class="subtitle text-white op-7">Bienvenido a tu nuevo Futuro</p>

		</div>

		<div class="login-aside w-50 d-flex align-items-center justify-content-center  bg-primary-gradient">

			<div class="container container-login container-transparent animated fadeIn">

				<h3 class="text-center text-white">Entrar en la Administraci&oacute;n</h3>

				<form id="Validacion">

					<input type="hidden" name="logear_usuario_inicio" value="1">

					<div class="login-form">

						<div class="form-group">

							<label for="username" class="placeholder text-white"><b>Usuario</b></label>

							<input id="username" name="usr" type="text" class="form-control" required>

						</div>

						<div class="form-group">

							<label for="password" class="placeholder text-white"><b>Password</b></label>

							<div class="position-relative">

								<input id="password" name="pwd" type="password" class="form-control" required>

								<div class="show-password">

									<i class="icon-eye"></i>

								</div>

							</div>

						</div>

						<div class="form-group form-action-d-flex mb-3">



							<button type="button" id="ingresar" class="btn btn-white col-md-5 float-right mt-3 mt-sm-0 fw-bold">Acceder</button>

						</div>

						

					</div>

				</form>

			</div>

		</div>

	</div>

	<?php include 'inc/inc_javascript.php'; ?>

	<script type="text/javascript">



        $(document).ready(function(){



            $("#ingresar").click(function(){



                $('#Validacion').form_validator_ajax({url:'a'});



            });

                

            $('input').keydown(function(){

                $(this).css('background-color','white');

            });



        });

    </script>

</body>

</html>