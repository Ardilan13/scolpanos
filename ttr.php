<?php include 'document_start.php'; ?>
<?php include 'sub_nav.php'; ?>
<div class="push-content-220">
	<?php include 'header.php'; ?>
	<?php $UserRights = $_SESSION['UserRights'];
	if ($UserRights == "ADMINISTRATIE" || $UserRights == "ONDERSTEUNING" || $UserRights == "TEACHER") {
		include 'redirect.php';
	} else { ?>
		<main id="main" role="main">
			<section>
				<div class="container container-fs">
					<div class="row">
						<div class="default-secondary-bg-color col-md-12 inset brd-bottom clearfix">
							<h1 class="primary-color">TTR:</h1>
							<?php include 'breadcrumb.php'; ?>
						</div>
						<div class="default-secondary-bg-color col-md-12 inset filter-bar brd-bottom clearfix">
							<form id="form-vrapportexport" class="form-inline form-data-retriever" name="filter-vak" role="form" method="GET" action="/dev_tests/test_exportexcel.php">
								<fieldset>
									<div class="form-group">
										<label for="cijfers_klassen_lijst">Klas</label>
										<select class="form-control" name="rapport_klassen_lijst" id="rapport_klassen_lijst">
											<!-- Options populated by AJAX get -->
											<option value="NONE">NONE</option>

										</select>
									</div>

									<div class="form-group">
										<label for="ttr_period">Period</label>
										<select class="form-control" name="ttr_period" id="ttr_period">
											<option value="A">Period A</option>
											<option value="B">Period B</option>
										</select>
									</div>
									<div class="form-group">
										<label for="ttr_datum">Datum</label>
										<div class="input-group date">
											<input type="text" id="ttr_datum" name="ttr_datum" class="form-control input-sm calendar">
											<span class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</span>
										</div>
									</div>
									<div class="form-group">
										<label>DL</label>
										<input class="form-group" name="ttr_dl" id="ttr_dl">
									</div>
									<div class="form-group">
										<button data-display="data-display" type="submit" id="btn_ttr_export" name="btn_ttr_export" class="btn btn-primary btn-m-w btn-m-h">ZOEKEN</button>
									</div>
								</fieldset>
							</form>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 full-inset">
							<div class="sixth-bg-color brd-full">
								<div class="box">
									<div class="box-content full-inset">

										<div class="full-inset alert alert-info reset-mrg-bottom" id="message_rapport">
											<p><i class="fa fa-info"></i> Please select the class, period, date and the DL and then, press the button zoeken.</p>
										</div>
										<div class="full-inset alert alert-info reset-mrg-bottom hidden" id="popup_message_rapport">
											<p><i class="fa fa-info"></i> If you have problems downloading the excel file, check your pop-up settings</p>
										</div>
										<div class="full-inset alert alert-warning reset-mrg-bottom hidden" id="ttr_message_klass">
											<p><i class="fa fa-warning"></i> You must select one Klas to open TTR File...</p>
										</div>
										<div class="full-inset alert alert-warning reset-mrg-bottom hidden" id="setting_message_rapport">
											<p><i class="fa fa-warning"></i> There should be a Setting created for this schooljaar to generate a rapport...</p>
										</div>
										<div class="full-inset alert alert-warning reset-mrg-bottom hidden" id="ttr_message_date">
											<p><i class="fa fa-warning"></i> You must select a date to open TTR File...</p>
										</div>
										<div class="full-inset alert alert-warning reset-mrg-bottom hidden" id="ttr_message_dl">
											<p><i class="fa fa-warning"></i> You must set DL value...</p>
										</div>
										<!-- <div class="data-display"></div> -->
										<?php include 'modal_cijfers.php'; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
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
																<h2 class="col-md-12">TTR Files</h2>
															</div>
														</div>
														<div class="col-md-8 full-inset">
															<div id="div_table_document">

															</div>

														</div>
														<!-- <div class="col-md-4 full-inset"> -->
														<div class="col-md-4 full-inset brd-full">

															<div class=" primary-bg-color full-inset">
																<h3>Upload TTR Files</h3>
															</div>
															<div class="sixth-bg-color full-inset">
																<form class="form-horizontal align-left" role="form" name="form_ttr_files" id="form_ttr_files">

																	<div class="alert alert-danger hidden">
																		<p><i class="fa fa-warning"></i> Er zijn lege velden die ingevuld moet worden!</p>
																	</div>
																	<div class="alert alert-info hidden">
																		<p><i class="fa fa-check"></i> The document has been include!</p>
																	</div>
																	<div class="alert alert-error hidden">
																		<p><i class="fa fa-warning"></i> Sorry, there was an error in the include of a document!!</p>
																	</div>
																	<fieldset>
																		<input type="hidden" id="klas_to_upload" name="klas_to_upload" value="">
																		<input type="hidden" id="periode_to_upload" name="periode_to_upload" value="">
																		<div class="form-group">
																			<label class="col-md-4 control-label">Bestand</label>
																			<div class="col-md-8">
																				<input class="form-control" type="file" name="ttr_file_to_upload" id="ttr_file_to_upload" required>
																			</div>
																		</div>
																		<div class="form-group hidden" id="class_beheer_upload">
																			<label class="col-md-4 control-label">Klas</label>
																			<div class="col-md-8">
																				<select class="form-control" name="rapport_klassen_lijst_upload" id="rapport_klassen_lijst_upload">
																					<!-- Options populated by AJAX get -->
																					<option value="NONE">NONE</option>

																				</select>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-md-4 control-label">Description</label>
																			<div class="col-md-8">
																				<textarea class="form-control" id="description_ttr" name="description_ttr"></textarea>
																			</div>
																		</div>
																		<div class="form-group full-inset">
																			<button type="submit" class="btn btn-primary btn-m-w pull-right mrg-left" id="btn_add_ttr">Upload</button>
																			<button type="reset" class="btn btn-danger btn-m-w pull-right mrg-right" id="btn_delete_ttr">Delete</button>
																		</div>
																	</fieldset>

																</form>
															</div>

														</div>
														<!-- </div> -->
														<input type="hidden" name="user_rights" id="user_rights" value=<?php echo $_SESSION["UserRights"]; ?>>
														<input type="hidden" name="selected_color_row" id="selected_color_row" value="" />
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
			</section>
		</main>
		<?php include 'footer.php'; ?>
</div>

<?php include 'document_end.php';
	} ?>
<!-- PLEASE CAREFULLY WIH THIS "}" THAT CLOSE SECURITY  ELSE IF -->
<script>
	$(document).ready(function() {

		$.get("ajax/getdocuments_ttr.php", {}, function(data) {
			$("#div_table_document").append(data);
		});

		if ($('#user_rights').val() === 'BEHEER') {
			$("#class_beheer_upload").removeClass("hidden");
			$.getJSON("ajax/getklassen_json.php", function(result) {
				var klas = $("#rapport_klassen_lijst_upload");
				$.each(result, function() {
					klas.append($("<option />").val(this.klas).text(this.klas));
				});
			});

		}


	});

	$("#rapport_klassen_lijst").change(function() {
		$("#klas_to_upload").val($("#rapport_klassen_lijst option:selected").val());
	});

	$("#btn_ttr_export").click(function(e) {
		e.preventDefault();


		var SchoolID = '<?php echo $_SESSION["SchoolID"]; ?>';
		var klas = $("#rapport_klassen_lijst option:selected").val();

		$("#message_rapport").addClass("hidden");
		$("#ttr_message_klass").addClass("hidden");
		$("#ttr_message_date").addClass("hidden");
		$("#ttr_message_dl").addClass("hidden");

		if ($("#rapport_klassen_lijst").val() == "NONE" || $("#ttr_datum").val() == "" || $("#ttr_dl").val() == "") {

			if ($("#rapport_klassen_lijst").val() == "NONE") {
				$("#ttr_message_klass").removeClass("hidden");
			}
			if ($("#ttr_datum").val() == "") {
				$("#ttr_message_date").removeClass("hidden");
			}
			if ($("#ttr_dl").val() == "") {
				$("#ttr_message_dl").removeClass("hidden");
			}

		} else {

			if (klas != 'NONE') {
				$.ajax({
					url: "ajax/check_setting.php",
					data: "SchoolID=" + SchoolID,
					type: 'POST',
					//dataType: "HTML",
					cache: false,
					async: true,
					success: function(data) {
						if (parseInt(data) === 1) {
							$('#message_rapport').addClass('hidden');
							$('#klas_message_rapport').addClass('hidden');
							$('#setting_message_rapport').addClass('hidden');
							$('#popup_message_rapport').removeClass('hidden');
							window.open("dev_tests\\export_ttr.php?ttr_klas_list=" + $("#rapport_klassen_lijst option:selected").val() + "&period=" + $("#ttr_period option:selected").val() + "&ttr_datum=" + $("#ttr_datum").val() + "&ttr_dl=" + $("#ttr_dl").val());
						} else {
							$('#message_rapport').addClass('hidden');
							$('#klas_message_rapport').addClass('hidden');
							$('#popup_message_rapport').addClass('hidden');
							$('#setting_message_rapport').removeClass('hidden');
							//alert('There should be a Setting created for this schooljaar to generate a rapport...');
						}
					}
				});
			} else {
				$('#message_rapport').addClass('hidden');
				$('#setting_message_rapport').addClass('hidden');
				$('#popup_message_rapport').addClass('hidden');
				$('#klas_message_rapport').removeClass('hidden');
				//alert('You must select one Klas to export rapport...');
			}
		}
	});

	$('#form_ttr_files').on("submit", function(e) {
		/* prevent refresh */
		e.preventDefault();

		if ($('#user_rights').val() === 'BEHEER' && $("#rapport_klassen_lijst_upload option:selected").val() == "NONE") {
			alert("Sorry, you must select a Class to upload file...");

		} else {
			if ($("#ttr_file_to_upload").val() === 0) {
				$(this).find('.alert-error').removeClass('hidden');
			} else {
				/* prevent refresh */
				e.preventDefault();
				// UPLOAD Document

				/* begin post */
				$.ajax({
					url: "ajax/upload_ttr.php",
					// data: $('#form_ttr_files').serialize(),
					cache: false,
					contentType: false,
					processData: false,
					data: new FormData(this),
					type: "POST",
					// dataType: "text",
					success: function(text) {
						if (text != 1) {
							$('#form_ttr_files').find('.alert-error').removeClass('hidden');
							$('#form_ttr_files').find('.alert-info').addClass('hidden');
							$('#form_ttr_files').find('.alert-warning').addClass('hidden');
						} else {
							alert('Document upload successfully!');
							$.get("ajax/addaudit_document.php"),
								$.get("ajax/getdocuments_ttr.php", {},
									function(data) {
										$('#div_table_document').empty();
										$("#div_table_document").append(data);
									}
								);
							// Clear all object of form except class
							$('#description').val('');
							$('#form_ttr_files').find('.alert-error').addClass('hidden');
							$('#form_ttr_files').find('.alert-info').addClass('hidden');
							$('#form_ttr_files').find('.alert-warning').addClass('hidden');
						}
					},
					error: function(xhr, status, errorThrown) {
						console.log("error");
					},
					complete: function(xhr, status) {
						// $('html, body').animate({scrollTop:0}, 'fast');
					}
				});

			}
		}
	});

	// Begin DELETE document function

	$('#btn_delete_ttr').click(function(e) {
		// DELETE a document
		var r = confirm("Delete document?");
		if (r) {

			// For each checked element
			var sList = "";
			$('input[type=checkbox]').each(function() {

				if (this.name != "") {
					var _document_id = '';
					var _document_name = '';
					/* begin post */
					if (this.checked) {
						_document_id = $(this).val();
						_document_name = $(this).attr("doc");
						_document_klas = $(this).attr("klas");

						$.post(
							"ajax/deletedocument_ttr.php", {
								document_id: _document_id,
								document_name: _document_name,
								document_klas: _document_klas
							},

							function(data) {
								console.log(data);
							}
						).done(function(data) {
							/* it's done */
							if (data == 1) {
								alert('Document deleted successfully!');
								$.get("ajax/getdocuments_ttr.php", {}, function(data) {
									$('#div_table_document').empty();
									$("#div_table_document").append(data);
								});

							} else {
								$('#form_ttr_files').find('.alert-error').removeClass('hidden');
								$('#form_ttr_files').find('.alert-info').addClass('hidden');
								$('#form_ttr_files').find('.alert-warning').addClass('hidden');
							};

						}).fail(function() {
							alert('Error, please contact developers.');
						});

					}

				}
			});
		}
	});
</script>