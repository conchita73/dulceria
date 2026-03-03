<!DOCTYPE html>
<html lang="en">
<head>

	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>CK Creativos | Empresas</title>
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<link rel="icon" href="/favicon.ico" type="image/x-icon"/>

	<!-- Fonts and icons -->
	<script src="js/plugin/webfont/webfont.min.js"></script>
	<script>
		WebFont.load({
			google: {"families":["Lato:300,400,700,900"]},
			custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['css/fonts.min.css']},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>

	<!-- CSS Files -->
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/atlantis.min.css?<?php echo time(); ?>">
	<link rel="stylesheet" href="dist/css/bootstrap-select.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
</head>
<body class="login">
	<div class="wrapper wrapper-login wrapper-login-full p-0">
		<div class="login-aside w-60 d-flex flex-column align-items-center justify-content-center text-center bg-secondary-gradient">
			<h1 class="title fw-bold text-white mb-3"><img src="img/logo_login.png" height="150px" alt="" class="img-fluid"></h1>
			<p class="subtitle text-white op-7">Todo lo que necesitas, en un s&oacute;lo lugar</p>
		</div>
		<div class="login-aside w-40 d-flex align-items-center justify-content-center bg-white">
			<div class="container container-login container-transparent animated fadeIn">
				<h3 class="text-center">Tu Zona Privada</h3>
				<form id="Validacion">
					<input type="hidden" name="logear_empresas" value="1">
					<div class="login-form">
						<div class="form-group">
							<label for="username" class="placeholder"><b>Usuario</b></label>
							<input id="username" name="usr" type="text" class="form-control" required>
						</div>
						<div class="form-group">
							<label for="password" class="placeholder"><b>Password</b></label>
							<div class="position-relative">
								<input id="password" name="pwd" type="password" class="form-control" required>
								<div class="show-password">
									<i class="icon-eye"></i>
								</div>
							</div>
						</div>
						<div class="form-group form-action-d-flex mb-3">

							<button type="button" id="ingresar" class="btn btn-secondary col-md-5 float-right mt-3 mt-sm-0 fw-bold">Acceder</button>
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