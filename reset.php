<?php



//error_reporting(-1);



require_once("config/app.config.php");



ob_start();



if (appconfig::GetHTTPMode() == "HTTPS") {

	if (!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] !== "on") {

		header("HTTP/1.1 301 Moved Permanently");

		header("Location: " . appconfig::GetBaseURL() . "/" .  "login.php");
	}
}



ob_flush();



?>



<?php include 'document_start_unauth.php'; ?>



<main id="main" role="main" class="login">

	<div class="container">

		<div class="row">

			<div class="col-md-4"></div>

			<div class="col-md-4 txt-align-center">

				<h1 class="brand">

					<img class="img-responsive" src="<?php print appconfig::GetBaseURL(); ?>/assets/img/logo_spn_small.png" alt="Scol Pa Nos" />

				</h1>

			</div>

			<div class="col-md-4"></div>

		</div>

		<div class="row">

			<div class="col-md-4"></div>

			<div class="col-md-4">

				<div class="primary-bg-color full-inset account-wall">

					<form id="form-reset-spn" name="login" class="form-signin" method="POST">

						<div class="alert alert-warning alert-error-password hidden">

							<p><i class="fa fa-warning"></i> Wachtwoorden komen niet overeen!</p>

						</div>

						<div class="alert alert-warning alert-error-token hidden">

							<p><i class="fa fa-warning"></i>Verlopen token</p>

						</div>

						<div class="alert alert-success alert-success-password hidden">

							<p><i class="fa fa-success"></i> Wachtwoord is succesvol gewijzigd!</p>

						</div>

						<div class="form-group">

							<div class="input-group">

								<input type="password" name="password" class="form-control" id="password" placeholder="Wachtwoord">

								<div class="input-group-addon quaternary-bg-color default-secondary-color">

									<i class="fa fa-lock"></i>

								</div>

							</div>

						</div>

						<div class="form-group">

							<div class="input-group">

								<input type="password" name="password_confirm" class="form-control" id="password_confirm" placeholder="Repeat Wachtwoord">

								<div class="input-group-addon quaternary-bg-color default-secondary-color">

									<i class="fa fa-lock"></i>

								</div>

							</div>

						</div>

						<button class="btn btn-primary btn-block" type="submit" id='btn_submit_reset'>Submit</button>

						<input class='hidden' name='token' id='token' value='' />

						<input class='hidden' name='username' id='username' value='' />



					</form>

				</div>

			</div>

			<div class="col-md-4"></div>

		</div>

	</div>

</main>



<?php include 'document_end.php'; ?>

<script>
	$('#token').val(getParam("token"));

	$('#username').val(getParam("username"));



	$("#btn_submit_reset").click(function(e) {
		/* prevent refresh */

		e.preventDefault();



		if ($("#password").val() == $("#password_confirm").val()) {



			var data = $('#form-reset-spn').serialize();



			$.ajax({

				url: "ajax/reset_password.php",

				data: $('#form-reset-spn').serialize(),

				type: "POST",

				dataType: "text",

				success: function(text) {

					if (text != "1") {

						$('#form-reset-spn').find('.alert').addClass('hidden');

						$('#form-reset-spn').find('.alert-error-token').removeClass('hidden');

						console.log(text)

					} else if (text == "1") {

						// alert('user_hs_account deleted successfully!');

						$('#form-reset-spn').find('.alert').addClass('hidden');

						$('#form-reset-spn').find('.alert-success-password').removeClass('hidden');

						setTimeout(function() {

							window.location.href = "login.php";

						}, 4000);

					}

				},

				error: function(xhr, status, errorThrown) {

					console.log("error");

				}

			});

		} else {

			$('#form-reset-spn').find('.alert').addClass('hidden');

			$('#form-reset-spn').find('.alert-error-password').removeClass('hidden');



		}



	});





	function getParam(key) {

		// Find the key and everything up to the ampersand delimiter

		var value = RegExp("" + key + "[^&]+").exec(window.location.search);



		// Return the unescaped value minus everything starting from the equals sign or an empty string

		return unescape(!!value ? value.toString().replace(/^[^=]+./, "") : "");

	}
</script>