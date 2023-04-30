<?php include 'document_start.php'; ?>

	<?php include 'sub_nav.php'; ?>

	<div class="push-content-220">

		<main id="main" role="main">

			<?php include 'header.php'; ?>

			<?php $UserRights= $_SESSION['UserRights'];

			if ($UserRights == "TEACHER" ){

				include 'redirect.php';} else{?>

			<section>

				<div class="container container-fs">

					<div class="row">

						<div class="default-secondary-bg-color col-md-12 inset brd-bottom">

							<h1 class="primary-color">Planner</h1>

							<?php include 'breadcrumb.php'; ?>

						</div>

					</div>

<!-- BEGIN CARIBEDEVELOPERS-->

							<div class="row">

								<div class="col-md-8 full-inset">

									<div class="pull-right form-inline">

										<div class="btn-group">

											<button class="btn btn-primary" data-calendar-nav="prev"><< Prev</button>

											<button class="btn" data-calendar-nav="today">Today</button>

											<button class="btn btn-primary" data-calendar-nav="next">Next >></button>

										</div>

									</div>

									<div class="primary-bg-color brd-full">

										<div class="box">

											<div class="box-title full-inset brd-bottom">

												<h4></h4>

											</div>

										</div>

									</div>

									<div style="background:#ffffff;color:#000000;text-decoration:none;">

										<div id="calendar"></div>

									</div>

								</div>

								<div class="col-md-4 full-inset">

									<div class="primary-bg-color brd-full">

										<div class="box">

											<div class="box-title full-inset brd-bottom">

												<h3>Planner</h3>

											</div>

											<div class="sixth-bg-color box content full-inset">

												<form class="form-horizontal align-left" role="form" name="form-add-planner" id="form-add-planner">

													<div role="tabpanelremedial" class="tab-pane active" id="tab1">

														<div class="alert alert-danger hidden">

															<p><i class="fa fa-warning"></i> Er zijn lege velden die ingevuld moet worden!</p>

														</div>

														<div class="alert alert-info hidden">

															<p><i class="fa fa-check"></i> Uw bericht is verzonden!</p>

														</div>

														<div class="alert alert-danger hidden" id="warning_planner">

															<p><i class="fa fa-warning"></i> Excuseer me, was er een fout in het verzenden van berichten!</p>

														</div>

														<fieldset>

															<input type="hidden" id="id_planner" name="id_planner" value="0">

															<input type="hidden" id="calendar_config" name="calendar_config" value="||false|023456">

															<div id="lblDocent" class="form-group">

																<label class="col-md-4 control-label" for="">Docent</label>

																<div class="dataDocentOnLoad" data-ajax-href="ajax/getlistdocent.php"></div>

																<div class="col-md-8">

																	<div id="data_docent"></div>

																</div>

															</div>

															<div id="lblKlassen" class="form-group">

																<label class="col-md-4 control-label" for="">Class</label>

																<div class="dataKlassenOnLoad" data-ajax-href="ajax/getlistklassen.php"></div>

																<div class="col-md-8">

																	<select class="form-control" name="cijfers_klassen_lijst" id="cijfers_klassen_lijst">

																	</select>

																</div>

															</div>

															<!-- datepicker -->

															<div class="form-group">

																<label class="col-md-4 control-label">Week</label>

																<div class="col-md-8">

																	<div class="input-group date">

																		<input id="planner_week" type="text" value="" placeholder="" class="form-control calendar" name="planner_week">

																		<span class="input-group-addon"><i class="fa fa-calendar default-primary-color"></i></span>

																	</div>

																</div>

															</div>

															<!-- Class -->

															<div class="form-group">

																<label class="col-md-4 control-label" for="">Subject</label>

																<div class="col-md-8">

																	<select id="planner_subject" name="planner_subject" class="form-control">

																		<option selected value="Lezen">Lezen</option>

																		<option selected value="Schrijven">Schrijven</option>

																		<option selected value="NedTaal">Ned.Taal</option>

																		<option selected value="Rekenen">Rekenen</option>

																		<option selected value="Zaakvakken">Zaakvakken</option>

																		<option selected value="Taal">Taal</option>

																	</select>

																</div>

															</div>

															<!-- Observations -->

															<div class="form-group">

																<label class="col-md-4 control-label">Observations</label>

																<div class="col-md-8">

																	<textarea id="planner_observation" class="form-control" name="planner_observation" type="text" placeholder="Enter observation here..."></textarea>

																</div>

															</div>

															<div class="form-group full-inset">

																<button type="submit" class="btn btn-primary btn-m-w pull-right mrg-left" id="btn-add-planner">Save</button>

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

			</section>

		</main>

		<?php include 'footer.php'; ?>

	</div>

<?php include 'document_end.php';}?> <!-- PLEASE CAREFULLY WIH THIS "}" THAT CLOSE SECURITY  ELSE IF -->

<script type="text/javascript" src="assets/js/calendar.js"></script>

<script type="text/javascript" src="assets/js/app_planner.js"></script>

<script type="text/javascript">

	function delete_planner(id){

		var result = confirm("Want to delete event planning?");

		if (result) {

			var request = new XMLHttpRequest();

			request.open('GET', 'ajax/delete_planner.php?id_planner=' + id, false);

			request.send();

			if (request.responseText === '1') {

				location.reload();

			}

		}

	}

</script>

