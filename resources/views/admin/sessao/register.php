<!DOCTYPE html>

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login on dashboard</title>
	{{links}}
	<link href="{{URL}}/resources/views/admin/styles/login.css" rel="stylesheet">
	<link href="{{URL}}/resources/views/admin/styles/contents.css" rel="stylesheet">
</head>

<body>

	<div class="limiter">

		<div class="container-login">

			<div class="wrap-login">

				<form class="login-form" method="POST" id="form" enctype="multipart/form-data">

					<span class="login-form-title">Cadastre-se</span>

					<p class="login-form-intro">Insira as informações necessárias para a criação de sua conta.</p>

					<div class="row">
						{{status}}
						{{status_middle}}
					</div>

					<div class="wrap-text-form">
						<span class="txt1">EMAIL</span>
					</div><!--wrap-text-form-->
					<div class="wrap-input">
						<input class="input" type="email" name="email" id="input" required autofocus>
						<span class="focus-input"></span>
					</div>
					<!--wrap-input-->

					<div class="wrap-text-form">
						<span class="txt1">NOME COMPLETO</span>
					</div><!--wrap-text-form-->
					<div class="wrap-input">
						<input class="input" type="text" name="name" id="input" required autofocus>
						<span class="focus-input"></span>
					</div>
					<!--wrap-input-->

					<div class="wrap-text-form">
						<span class="txt1">SENHA</span>
					</div><!--wrap-text-form-->
					<div class="wrap-input-password">
						<span class="btn-show-pass" onclick="showPass()">
							<i class="fa fa-eye-slash" id="eye"></i>
						</span>
						<input id="password" class="input" type="password" name="password" placeholder="********" required>
						<span class="focus-input"></span>
					</div>

					<div class="wrap-text-form">
						<span class="txt1">CONFIRME A SENHA</span>
					</div><!--wrap-text-form-->
					<div class="wrap-input-password">
						<span class="btn-show-pass" onclick="showPassC()">
							<i class="fa fa-eye-slash" id="eye"></i>
						</span>
						<input id="confirm_password" class="input" type="password" name="confirm_password" placeholder="********" required>
						<span class="focus-input"></span>
					</div>

					<!--wrap-input-->
					<div class="btn-container-login">
						<button class="btn-login" type="submit">Entrar</button>
					</div>

					<div class="bottom-text">
						<span>Já possui uma conta? </span><a href="{{URL}}/dashboard/login">Entre</a>
					</div>

				</form>
				<!--login-form-->
			</div>
			<!--wrapper-->

		</div>
		<!--container-login-->
	</div>
	<!--limiter-->

	{{scriptlinks}}
	<script src="{{URL}}/resources/assets/admin/js/login.js"></script>
</body>

</html>