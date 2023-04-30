<?php include 'document_start.php'; ?>

<?php include 'sub_nav.php'; ?>

<?php session_start(); ?>

<div class="push-content-220">

	<main id="main" role="main">

		<?php include 'header.php'; ?>

		<?php $UserRights = $_SESSION['UserRights'];

		if ($UserRights == "TEACHER") {

			include 'redirect.php';
		} else { ?>

			<section>

				<div class="container container-fs">

					<div class="row">

						<div class="default-secondary-bg-color col-md-12 inset brd-bottom">

							<h1 class="primary-color">Planner</h1>

							<?php include 'breadcrumb.php'; ?>

						</div>

						<div class="default-secondary-bg-color col-md-12 inset filter-bar brd-bottom clearfix">
							<form id="form-week" class="form-inline form-data-retriever" name="filter-vak" role="form">
								<fieldset>
									<input type="hidden" id="calendar_config" name="calendar_config" value="||false|023456">
									<div class="form-group">
										<label class="col-md-2" style="padding-top: 1.2%; ">Week</label>
										<div class="col-md-8" style="padding: 0; ">
											<div class="input-group date">
												<input id="planner_week" type="text" value="" placeholder="" class="form-control calendar" name="planner_week">
												<span class="input-group-addon"><i class="fa fa-calendar default-primary-color"></i></span>
											</div>
										</div>
									</div>

									<?php if ($_SESSION['UserRights'] == "BEHEER") { ?>
										<div class="form-group">
											<label for="docent">Docent</label>
											<select class="form-control" name="user" id="user">
												<?php
												$school = $_SESSION["SchoolID"];
												require_once "classes/DBCreds.php";
												$DBCreds = new DBCreds();
												$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
												$sql_query_text = "select UserGUID as user,FirstName,LastName from app_useraccounts where SchoolID = $school and AccountEnabled = 1 and UserRights != 'BEHEER';";
												$resultado1 = mysqli_query($mysqli, $sql_query_text);
												while ($row = mysqli_fetch_assoc($resultado1)) {
												?>
													<option value="<?php echo $row['user']; ?>"><?php echo $row['FirstName'] . ' ' . $row['LastName']; ?></option>
												<?php
												} ?>
											</select>

										</div>
									<?php } else { ?>
										<input type="hidden" id="user" name="user" value="<?php echo $_SESSION['UserGUID']; ?>">
									<?php } ?>

									<div class="form-group">
										<button id="btn_get_form" type="submit" class="btn btn-primary btn-m-w btn-m-h">zoeken</button>
									</div>
								</fieldset>
							</form>
						</div>

					</div>

					<div class="row">
						<div class="col-md-12 full-inset">
							<div class="sixth-bg-color brd-full">
								<div class="box box_form">
									<div class="box-content full-inset">
										<div id="planner" class="data-display"></div>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>

</div>

</section>

</main>

<?php include 'footer.php'; ?>

</div>

<?php include 'document_end.php';
		} ?> <!-- PLEASE CAREFULLY WIH THIS "}" THAT CLOSE SECURITY  ELSE IF -->

<script type="text/javascript" src="assets/js/calendar.js"></script>

<script type="text/javascript" src="assets/js/app_planner.js"></script>

<script type="text/javascript">
	$("#btn_get_form").click(function(e) {
		e.preventDefault()

		$.ajax({
			url: "ajax/get_planner.php",
			data: $('#form-week').serialize(),
			type: "POST",
			dataType: "text",
			success: function(text) {
				$("#planner").html(text);
			},
			error: function(xhr, status, errorThrown) {
				alert("error");
			},
		});
	})
</script>