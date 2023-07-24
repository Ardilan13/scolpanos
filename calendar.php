<?php include 'document_start.php'; ?>
<?php include 'sub_nav.php'; ?>
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
							<h1 class="primary-color">Calendar</h1>
							<?php include 'breadcrumb.php'; ?>
						</div>
						<?php if ($_SESSION["SchoolType"] == 2) { ?>
							<div class="default-secondary-bg-color col-md-12 full-inset filter-bar brd-bottom clearfix">
								<form id="form_opmerking" class="form-inline form-data-retriever" name="form_opmerking" role="form">
									<fieldset>
										<div class="form-group">
											<label>Start Datum</label>
											<div class="input-group date col-md-1">
												<input type="text" id="start_date" name="start_date" calendar="full" class="form-control input-sm calendar" required autocomplete="off">
												<span class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</span>
											</div>
										</div>
										<div class="form-group">
											<label>End Datum</label>
											<div class="input-group date col-md-1">
												<input type="text" id="end_date" name="end_date" calendar="full" class="form-control input-sm calendar" required autocomplete="off">
												<span class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</span>
											</div>
										</div>
										<div class="form-group">
											<button id="btn_bespreking" class="btn btn-primary btn-m-w btn-m-h">zoeken</button>
										</div>
									</fieldset>
								</form>
							</div>
						<?php } ?>
					</div>
					<div class="row">
						<div class="col-md-12 full-inset">
							<div class="sixth-bg-color brd-full">
								<div class="box box_form">
									<div class="box-content full-inset">
										<div id="table" class="data-display"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- DESDE AQUI EL CODIGO DE CARIBEDEVELOPERS ==== DESDE AQUI EL CODIGO DE CARIBEDEVELOPERS-->
					<div class="row">
						<div class="col-md-8 full-inset">
							<div class="pull-right form-inline">
								<div class="btn-group">
									<button class="btn btn-primary" data-calendar-nav="prev">
										<< Prev</button>
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
								<div style="background:#ffffff;color:#000000;text-decoration:none; margin: 10px 20px; display: flex; justify-content: space-evenly;">
									<label><a class="pull-left event event-info"></a> Huiswerk</label>
									<label><a class="pull-left event event-success"></a> Overhoring</label>
									<label><a class="pull-left event event-warning"></a> Toets Proefwerk</label>
									<label><a class="pull-left event event-special"></a> Anders</label>
								</div>
							</div>
						</div>
						<div class="col-md-4 full-inset">
							<div class="primary-bg-color brd-full">
								<div class="box">
									<div class="box-title full-inset brd-bottom">
										<h3>Calendar</h3>
									</div>
									<div class="sixth-bg-color box content full-inset">
										<form class="form-horizontal align-left" role="form" name="form-add-calendar" id="form-add-calendar">
											<div class="alert alert-danger hidden">
												<p><i class="fa fa-warning"></i> Er zijn lege velden die ingevuld moet worden!</p>
											</div>
											<div class="alert alert-info hidden">
												<p><i class="fa fa-check"></i> Uw bericht is verzonden!</p>
											</div>
											<div class="alert alert-error hidden">
												<p><i class="fa fa-warning"></i> Excuseer me, was er een fout in het verzenden van berichten!</p>
											</div>
											<div class="alert alert-error-bad-charecter hidden">
												<p><i class="fa fa-warning"></i> The text has characters that are not accepted, remove these characters</p>
											</div>


											<fieldset>
												<input type="hidden" id="id_calendar" name="id_calendar" value="0">
												<input type="hidden" id="calendar_config" name="calendar_config" value="||true|">
												<input type="hidden" id="schoolid" name="schoolid" value="<?php echo $_SESSION['SchoolID']; ?>">
												<input type="hidden" id="schooljaar" name="schooljaar" value="<?php echo $_SESSION['SchoolJaar']; ?>">
												<div id="lblDocent" class="form-group">
													<label class="col-md-4 control-label" for="">Docent</label>
													<div class="dataDocentOnLoad" data-ajax-href="ajax/getlistdocent.php"></div>
													<div class="col-md-8">
														<div id="data_docent"></div>
													</div>
												</div>
												<div id="lblKlassen" class="form-group">
													<label class="col-md-4 control-label" for="">Klas</label>
													<div class="dataKlassenOnLoad" data-ajax-href="ajax/getlistklassen.php"></div>
													<div class="col-md-8">
														<select class="form-control" name="cijfers_klassen_lijst" id="cijfers_klassen_lijst">
														</select>
													</div>
												</div>
												<!-- datepicker -->
												<div class="form-group">
													<label class="col-md-4 control-label">Datum</label>
													<div class="col-md-8">
														<div class="input-group date">
															<input id="calendar_date" type="text" value="" placeholder="" class="form-control calendar" name="calendar_date">
															<span class="input-group-addon"><i class="fa fa-calendar default-primary-color"></i></span>
														</div>
													</div>
												</div>
												<!-- Class -->
												<div class="form-group">
													<label class="col-md-4 control-label" for="">Onderwerp</label>
													<div class="col-md-8">
														<select id="calendar_subject" name="calendar_subject" class="form-control">
															<option selected value="Homework">Huiswerk
															</option>
															<option selected value="Test">Overhoring</option>
															<option selected value="Exam">Toets Proefwerk</option>
															<option selected value="Other">Anders</option>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-4 control-label">Informatie</label>
													<div class="col-md-8">
														<textarea id="calendar_observation" class="form-control" name="calendar_observation" type="text" placeholder="Enter observation here..."></textarea>
													</div>
												</div>
												<!-- <div class="form-group">
                                                            <label class="col-md-4 control-label">Bestand</label>
                                                            <div class="col-md-8">
                                                                <input class="form-control" type="file" name="fileToUpload" id="fileToUpload">
                                                            </div>
                                                        </div> -->
												<!-- Observations -->

												<div class="form-group full-inset">
													<button style="display: none;" class="btn btn-danger btn-m" id="btn-clear-calendar">Delete</button>
													<button type="submit" class="btn btn-primary btn-m pull-right mrg-left" id="btn-add-calendar">Save</button>
												</div>
											</fieldset>
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
<?php include 'document_end.php';
		} ?> <!-- PLEASE CAREFULLY WIH THIS "}" THAT CLOSE SECURITY  ELSE IF -->
<script type="text/javascript" src="assets/js/calendar.js"></script>
<script type="text/javascript" src="assets/js/app_calendar.js"></script>
<script type="text/javascript">
	function update_calendar(id, name, klas, vak, type, extra, date) {
		$('#id_calendar').val(id);
		$('#calendar_date').val(date);
		$('#cijfers_klassen_lijst').val(klas);
		$('#calendar_subject').val(type);
		$('#calendar_observation').val(extra);
		$('#btn-add-calendar').html('Update');
		$('#btn-clear-calendar').show();
	}

	$('#btn-clear-calendar').click(function(e) {
		e.preventDefault();
		delete_calendar($('#id_calendar').val());
	});

	$("#btn_bespreking").click(function(e) {
		e.preventDefault()

		$.ajax({
			url: "ajax/get_calendar.php",
			data: $('#form_opmerking').serialize(),
			type: "POST",
			dataType: "text",
			success: function(text) {
				$("#table").html(text);
			},
			error: function(xhr, status, errorThrown) {
				alert("error");
			},
		});
	})

	function delete_calendar(id) {
		var result = confirm("Want to delete event calendar?");
		if (result) {
			var request = new XMLHttpRequest();
			request.open('GET', 'ajax/delete_calendar.php?id_calendar=' + id, false);
			request.send();
			if (request.responseText === '1') {
				location.reload();
			} else if (request.responseText === '2') {
				alert("You don't have permissions to do this");
			}
			$('#id_calendar').val(0);
			$('#calendar_date').val('');
			$('#cijfers_klassen_lijst').val('');
			$('#calendar_subject').val('');
			$('#calendar_observation').val('');
			$('#btn-add-calendar').html('Save');
			$('#btn-clear-calendar').hide();
		}
	}
</script>