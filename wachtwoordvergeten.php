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



					<form id="form-forgot-pass-spn" name="login" class="form-signin" method="POST">

						<div id='user_not_found' class="alert alert-danger hidden">

							<p><i class="fa fa-warning"></i> Gebruiker niet gevonden</p>

						</div>



						<div id='check_email' class="alert alert-success hidden">

							<p><i class="fa fa-success"></i> Please check your email</p>

						</div>

						<div class="form-group">

							<div class="input-group">

								<input type="text" name="username" class="form-control" id="username" placeholder="E-Mail Adres">

								<div class="input-group-addon quaternary-bg-color default-secondary-color">

									<i class="fa fa-user"></i>

								</div>

							</div>

						</div>

						<button class="btn btn-primary btn-block" type="submit">Nieuwe wachtwoord aanvragen</button>

					</form>

					<a id='back_login' href="login.php" class="default-secondary-color align-right">Terug naar login scherm</a>

				</div>

			</div>

			<div class="col-md-4"></div>

		</div>

	</div>

</main>



<?php include 'document_end.php'; ?>



<script>
	$('#form-forgot-pass-spn').submit(function(e) {

		e.preventDefault();

		var check = checkUser($("#username").val());



		if (check == 'Error') {

			$('#user_not_found').removeClass('hidden');

		} else if (check == 0 || !check || check == NaN) {

			$('#user_not_found').removeClass('hidden');

		} else {

			// $('#form-forgot-pass-spn').addClass('hidden');

			$('#check_email').removeClass('hidden');



			setTimeout(function() {

				window.location.href = "login.php";

			}, 5000);

		}



	});



	function checkUser(email) {

		var c = null;

		var hrefLink = 'ajax/get_check_user.php?email=' + email;

		$.ajax({

			url: hrefLink,

			type: 'GET',

			async: false,

			cache: false,

			error: function() {

				c = 0;

			},

			success: function(data) {

				c = parseInt(data);

			}

		});

		return c;

	}
</script>