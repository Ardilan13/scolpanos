<?php

//error_reporting(-1);

require_once("config/app.config.php");

ob_start();

if(appconfig::GetHTTPMode() == "HTTPS")
{
	if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] !== "on")
	{
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
						<form id="form-signin-spn" name="login" class="form-signin" method="POST">
							<div class="alert alert-warning hidden">
								<p><i class="fa fa-warning"></i> Gebruikersnaam en/of wachtwoord is onjuist!</p>
							</div>
							<div class="form-group">
								<div class="input-group">
									<input type="text" name="username" class="form-control" id="username" placeholder="E-Mail Adres">
									<div class="input-group-addon quaternary-bg-color default-secondary-color">
										<i class="fa fa-user"></i>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="input-group">
									<input type="password" name="password" class="form-control" id="password" placeholder="Wachtwoord">
									<div class="input-group-addon quaternary-bg-color default-secondary-color">
										<i class="fa fa-lock"></i>
									</div>
								</div>
							</div>
							<button class="btn btn-primary btn-block" type="submit">Inloggen</button>
						</form>						
						<a href="wachtwoordvergeten.php" class="default-secondary-color align-right">Wachtwoord vergeten?</a>
					</div>
				</div>
				<div class="col-md-4"></div>
			</div>
		</div>
	</main>

<?php include 'document_end.php'; ?>