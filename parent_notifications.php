<?php

ob_start();

if (session_status() == PHP_SESSION_NONE) {

	session_start();
}



require_once("config/app.config.php");

require_once("classes/spn_authentication.php");


$auth = new spn_authentication();



ob_flush();



?>

<?php $timer = appconfig::GetTimerCijfer_ls(); ?>


<!DOCTYPE html>


<!--[if lt IE 7 ]> <html lang="en" dir="ltr" class="no-js lte9 lte8 lte7 ie6"> <![endif]-->


<!--[if IE 7 ]>    <html lang="en" dir="ltr" class="no-js lte9 lte8 ie7"> <![endif]-->


<!--[if IE 8 ]>    <html lang="en" dir="ltr" class="no-js lte9 ie8"> <![endif]-->


<!--[if IE 9 ]>    <html lang="en" dir="ltr" class="no-js ie9"> <![endif]-->


<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en" dir="ltr" class="no-js"> <!--<![endif]-->


<head>





	<meta charset="utf-8" />


	<meta http-equiv="X-UA-Compatible" content="IE=edge">


	<meta name="viewport" content="width=device-width, initial-scale=1">





	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">


	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />


	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800|Roboto:400,300,500" type="text/css">


	<link rel="stylesheet" href="<?php print appconfig::GetBaseURL(); ?>/assets/css/app.css" type="text/css" />


	<link rel="stylesheet" href="<?php print appconfig::GetBaseURL(); ?>/assets/css/horizontal_tab.css" type="text/css" />


	<link href="mobile/css/bootstrap.min.css" rel="stylesheet">


	<link href="mobile/css/caribedev.css" rel="stylesheet">





	<link rel="stylesheet" href="assets/css/calendar.css">


	<!-- <link href="mobile/css/caribedev.css" rel="stylesheet"> -->


	<title>SCOL PA NOS</title>





	<meta name="description" content="" />


	<meta name="author" content="XA TECHNOLOGIES / rudycroes.com" />





	<link rel="canonical" href="" />


	<link rel="shortcut icon" href="" />


	<script src="<?php print appconfig::GetBaseURL(); ?>/assets/js/lib/modernizr.min.js"></script>


<body>


	<input type="hidden" name="timer_cijfer_ls" id="timer_cijfer_ls" value=<?php echo $timer; ?> />


	<main id="main" role="main" class="home">


		<section>


			<div class="container">


				<!-- Bootstrap -->


				<nav class="navbar navbar-default navbar-fixed-top">

					<div class="container">

						<div class="navbar-header">

							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">

								<span class="sr-only">Toggle navigation</span>

								<span class="icon-bar"></span>

								<span class="icon-bar"></span>

								<span class="icon-bar"></span>

							</button>

							<a class="navbar-brand" href="#">Parents SPN</a>

						</div>

						<div id="navbar" class="navbar-collapse collapse">

							<ul class="nav navbar-nav">

								<li class="active"><a id="student_link" href="" style="padding-bottom: 21px; align:center">Student</a></li>

								<?php if ($_SESSION["SchoolID"] == 12) : ?>

									<li class="active"><a href="https://drive.google.com/drive/folders/0B9eD9d6Yv4TyVVBCVkRCb2czZFk" target="_blank" style="padding-bottom: 21px; align:center">School Documenten </a></li>

								<?php endif; ?>

							</ul>

							<ul class="nav navbar-nav navbar-right">

								<li class="active"><a type='button' id='request_open_modal_secure_pin' href="#"><span class="fa fa-key fa-2x notification"></span><span></span></a></li>

								<li class="active"><a type='button' onclick="goContactParent()"><span class="fa fa-user-plus fa-2x address"></span><span></span></a></li>

								<li class="active"><a class='message_unread' href="#"><span class="fa fa-bell fa-2x notification"></span><span class='message_unread' id="unread"></span></a></li>

								<li class="active"><a href="#" onclick="location.href='logout_parent.php'"><span class="fa fa-power-off fa-2x"></span></a></li>

							</ul>

							<!-- <ul class="nav navbar-nav navbar-right">

								<li><a href="" class="fa fa-bell notification" style="font-size:30px;color:#FFDC66;	position:relative; top: 10px;;" aria-hidden="true" id="unread"></a></i>



								<li class="fa fa-power-off" style="font-size:30px;color:#FFDC66;	position:relative; top: 10px;;" aria-hidden="true" onclick="location.href ='logout_parent.php'"</i>

							</ul> -->

						</div><!--/.nav-collapse -->

					</div>

				</nav>

				<br>


				<br>


				<div class="container container-fs">





					<div class="row">


						<div class="col-md-12 full-inset">


							<div class="sixth-bg-color brd-full">


								<div class="box">


									<div class="box-content full-inset">


										<div class="row">


											<div class="col-md-12">


												<div class="primary-bg-color brd-full">


													<div class="box">


														<div class="box-title full-inset">


															<div class="row">


																<h3 class="col-md-12">Notifications</h3>


															</div>


														</div>


														<div class="col-md-12 full-inset">


															<div class="sixth-bg-color box content full-inset">


																<form class="form-horizontal align-left" role="form" name="form-add-notifications" id="form-notifications">


																	<input type="hidden" id="student_id" name="student_id" ?>


																	<div role="tabpanel" class="tab-pane active" id="tab1">


																		<fieldset>


																			<div class="box-content full-inset sixth-bg-color">


																				<div id="table_notifications_parent" class="dataRetrieverOnLoad"></div>


																				<div class="table-responsive">


																					<div id="table_notifications_result_parent"></div>


																				</div>


																			</div>


																		</fieldset>


																	</div>


																</form>


															</div>





														</div>





													</div>


												</div>


											</div>


										</div>


									</div>


								</div>


							</div>


						</div>


					</div>





				</div>


			</div>


			<?php include 'document_end.php'; ?>


		</section>


	</main>





</body>














<script type="text/javascript" src="assets/js/calendar.js"></script>


<script type="text/javascript" src="assets/js/app_calendar.js"></script>


<!-- <script src="<?php print appconfig::GetBaseURL(); ?>/assets/js/app_v1.0.js"></script> -->


<!-- <script type="text/javascript" src="components/jquery/jquery.min.js"></script> -->


<script type="text/javascript" src="components/underscore/underscore-min.js"></script>


<script type="text/javascript" src="components/bootstrap2/js/bootstrap.min.js"></script>


<script type="text/javascript" src="components/bootstrap3/js/bootstrap.js"></script>


<script type="text/javascript" src="components/jstimezonedetect/jstz.min.js"></script>


<script type="text/javascript" src="assets/js/lib/jquery.datepicker.min.js"></script>


<script type="text/javascript" src="assets/js/language/bg-BG.js"></script>


<script type="text/javascript" src="assets/js/language/nl-NL.js"></script>


<script type="text/javascript" src="assets/js/language/fr-FR.js"></script>


<script type="text/javascript" src="assets/js/language/de-DE.js"></script>


<script type="text/javascript" src="assets/js/language/el-GR.js"></script>


<script type="text/javascript" src="assets/js/language/it-IT.js"></script>


<script type="text/javascript" src="assets/js/language/hu-HU.js"></script>


<script type="text/javascript" src="assets/js/language/pl-PL.js"></script>


<script type="text/javascript" src="assets/js/language/pt-BR.js"></script>


<script type="text/javascript" src="assets/js/language/ro-RO.js"></script>


<script type="text/javascript" src="assets/js/language/es-CO.js"></script>


<script type="text/javascript" src="assets/js/language/es-MX.js"></script>


<script type="text/javascript" src="assets/js/language/es-ES.js"></script>


<script type="text/javascript" src="assets/js/language/ru-RU.js"></script>


<script type="text/javascript" src="assets/js/language/sk-SR.js"></script>


<script type="text/javascript" src="assets/js/language/sv-SE.js"></script>


<script type="text/javascript" src="assets/js/language/zh-CN.js"></script>


<script type="text/javascript" src="assets/js/language/cs-CZ.js"></script>


<script type="text/javascript" src="assets/js/language/ko-KR.js"></script>


<script type="text/javascript" src="assets/js/language/zh-TW.js"></script>


<script type="text/javascript" src="assets/js/language/id-ID.js"></script>


<script type="text/javascript" src="assets/js/language/th-TH.js"></script>


<!-- INICIO CODE CaribeDevelopers Delete Calendar -->


<script type="text/javascript">
	function getParam(key) {


		// Find the key and everything up to the ampersand delimiter


		var value = RegExp("" + key + "[^&]+").exec(window.location.search);





		// Return the unescaped value minus everything starting from the equals sign or an empty string


		return unescape(!!value ? value.toString().replace(/^[^=]+./, "") : "");


	}


	var studentid = getParam("id");


	var familyid = getParam("id_family");








	$.post("ajax/get_number_unread_notifications_parent.php?",


		{


			id_student: studentid


		},


		function(data) {


			$("#unread").html(data);


		});





	$.post("ajax/getnotificationslistParents_tabel.php?student_id=" + studentid, {}, function(data) {


		$("#table_notifications_result_parent").html(data);





	});


	$("#unread").click(function(e) {


		e.preventDefault();


		window.location.replace("parent_notifications.php?id=" + studentid + "&id_family=" + familyid);


	});





	$("#student_link").click(function(e) {


		e.preventDefault();


		window.location.replace("home_parent.php?id=" + studentid + "&id_family=" + familyid);


	});
</script>