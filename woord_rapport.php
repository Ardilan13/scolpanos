<?php include 'document_start.php'; ?>
<?php include 'sub_nav.php'; ?>
<div class="push-content-220">
	<?php include 'header.php'; ?>
	<?php $UserRights= $_SESSION['UserRights'];
	if ($UserRights == "ADMINISTRATIE" || $UserRights == "ONDERSTEUNING" ||$UserRights == "TEACHER" ){
		include 'redirect.php';} else{?>
	<main id="main" role="main">
		<section>
			<div class="container container-fs">
				<div class="row">
					<div class="default-secondary-bg-color col-md-12 inset brd-bottom clearfix">
						<h1 class="primary-color">Woord Rapport:</h1>
						<?php include 'breadcrumb.php'; ?>
					</div>
					<div class="default-secondary-bg-color col-md-12 inset filter-bar brd-bottom clearfix">
						<form id="form-vrapportexport" class="form-inline form-data-retriever" name="filter-vak" role="form" method="GET" action="/dev_tests/test_exportexcel.php">
							<fieldset>
								<div class="form-group">
									<label for="cijfers_klassen_lijst">Klas</label>
									<select class="form-control" name="woord_rapport_klas" id="woord_rapport_klas">
										<!-- Options populated by AJAX get -->
										<option value="1A">1A</option>
										<option value="1B">1B</option>
										<option value="1C">1C</option>
									</select>
								</div>

								<div class="form-group">
									<label>Rapnr.</label>
									<select class="form-control" name="woord_rapport_period" id="woord_rapport_period">
										<!-- Options populated by AJAX get -->
										<!-- TEMPORARY ENTERED MANUALLY -->
										<option value="1" >1</option>

									</select>
								</div>
								<div class="form-group">
									<!-- <button data-display="data-display" data-ajax-href="ajax/getvakken_tabel.php" type="submit" class="btn btn-primary btn-m-w btn-m-h">zoeken</button> -->
									<button data-display="data-display" type="submit" id="btn_woord_rapport_export" name="btn_woord_rapport_export" class="btn btn-primary btn-m-w btn-m-h">Export</button>
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
									<div class="full-inset alert alert-info reset-mrg-bottom hidden" id="popup_message_rapport" >
										<p ><i class="fa fa-info"></i> If you have problems downloading the excel file, check your pop-up settings</p>
									</div>
									<div class="full-inset alert alert-warning reset-mrg-bottom hidden" id="woord_rapport_message_klass">
										<p><i class="fa fa-warning"></i> You must select one Klas to open Woord Rapport File...</p>
									</div>
									<div class="full-inset alert alert-warning reset-mrg-bottom hidden" id="setting_message_rapport">
										<p><i class="fa fa-warning"></i> There should be a Setting created for this schooljaar to generate a rapport...</p>
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
															<h2 class="col-md-12">Woord Rapport Files</h2>
														</div>
													</div>
													<div class="col-md-8 full-inset">
														<div id="div_table_document">

														</div>

													</div>
													<!-- <div class="col-md-4 full-inset"> -->
													<div class="col-md-4 full-inset brd-full">

														<div class=" primary-bg-color full-inset">
															<h3>Upload Woord Rapport Files</h3>
														</div>
														<div class="sixth-bg-color full-inset">
															<form class="form-horizontal align-left" role="form" name="form_woord_rapport_files" id="form_woord_rapport_files">

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
																	<input type="hidden" id="klas_woord_rapport_to_upload" name="klas_woord_rapport_to_upload" value="">

																	<div class="form-group">
																		<label class="col-md-4 control-label">Bestand</label>
																		<div class="col-md-8">
																			<input class="form-control" type="file" name="woord_rapport_file_to_upload" id="woord_rapport_file_to_upload" required>
																		</div>
																	</div>
																	<div class="form-group" id="class_beheer_upload">
																		<label class="col-md-4 control-label">Klas</label>
																		<div class="col-md-8">
																			<select class="form-control" name="woord_rapport_klas_upload" id="woord_rapport_klas_upload">
																				<!-- Options populated by AJAX get -->
																				<option value="1A">1A</option>
																				<option value="1B">1B</option>
																				<option value="1C">1C</option>
																			</select>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="col-md-4 control-label">Description</label>
																		<div class="col-md-8">
																			<textarea class="form-control" id="description_woord_rapport" name="description_woord_rapport"></textarea>
																		</div>
																	</div>
																	<div class="form-group full-inset">
																		<button type="submit" class="btn btn-primary btn-m-w pull-right mrg-left" id="btn_upload_woord_rapport">Upload</button>
																		<button type="reset" class="btn btn-danger btn-m-w pull-right mrg-right" id="btn_delete_woord_rapport">Delete</button>
																	</div>
																</fieldset>
															</form>
														</div>
													</div>
													<!-- </div> -->
													<input type="hidden" name="user_rights" id="user_rights" value=<?php  echo $_SESSION["UserRights"]; ?>>
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

<?php include 'document_end.php';}?> <!-- PLEASE CAREFULLY WIH THIS "}" THAT CLOSE SECURITY  ELSE IF -->
<script>

$(document).ready(function() {

	$.get("ajax/getdocuments_woord_rapport.php", {}, function(data) {
		$("#div_table_document").append(data);
	});

});

$("#rapport_klassen_lijst").change(function(){
	$("#klas_to_upload").val($("#rapport_klassen_lijst option:selected").val());
});

$("#btn_woord_rapport_export").click(function (e)
{
	e.preventDefault();


	var SchoolID = '<?php echo $_SESSION["SchoolID"]; ?>';
	var klas = $("#rapport_klassen_lijst option:selected").val();

	$("#message_rapport").addClass("hidden");
	$("#woord_rapport_message_klass").addClass("hidden");
	$("#woord_rapport_message_date").addClass("hidden");
	$("#woord_rapport_message_dl").addClass("hidden");

	if (klas!='NONE')
	{
		$.ajax({
			url:"ajax/check_setting.php",
			data: "SchoolID="+SchoolID,
			type  : 'POST',
			//dataType: "HTML",
			cache: false,
			async :true,
			success: function(data){
				if (data==='1'){
					$('#message_rapport').addClass('hidden');
					$('#klas_message_rapport').addClass('hidden');
					$('#setting_message_rapport').addClass('hidden');
					$('#popup_message_rapport').removeClass('hidden');
					window.open("dev_tests\\export_woord_rapport.php?woord_rapport_klas="+$("#woord_rapport_klas option:selected").val()+"&rapport="+$("#woord_rapport_period option:selected").val());
				}
			}
		});
	}
});

$('#form_woord_rapport_files').on("submit", function(e)
{
	/* prevent refresh */
	e.preventDefault();

	if ($('#user_rights').val()==='BEHEER' && $("#woord_rapport_klas_upload option:selected").val()=="NONE")
	{
		alert("Sorry, you must select a Class to upload file...");

	}
	else
	{
		if($("#woord_rapport_file_to_upload").val() === 0)
		{
			$(this).find('.alert-error').removeClass('hidden');
		}
		else
		{
			/* prevent refresh */
			e.preventDefault();
			// UPLOAD Document

			/* begin post */
			$.ajax(
				{
					url: "ajax/upload_woord_rapport.php",
					// data: $('#form_woord_rapport_files').serialize(),
					cache: false,
					contentType: false,
					processData: false,
					data: new FormData(this),
					type: "POST",
					// dataType: "text",
					success: function(text) {
						if(text != 1)
						{
							$('#form_woord_rapport_files').find('.alert-error').removeClass('hidden');
							$('#form_woord_rapport_files').find('.alert-info').addClass('hidden');
							$('#form_woord_rapport_files').find('.alert-warning').addClass('hidden');
						}
						else
						{
							alert('Document upload successfully!');
							$('#description_woord_rapport').text('');
							$('#description_woord_rapport').val('');
							$.get("ajax/addaudit_document.php"),
							$.get("ajax/getdocuments_woord_rapport.php", {},
							function(data)
							{
								$('#div_table_document').empty();
								$("#div_table_document").append(data);
							}
						);
						// Clear all object of form except class
						$('#description').val('');
						$('#form_woord_rapport_files').find('.alert-error').addClass('hidden');
						$('#form_woord_rapport_files').find('.alert-info').addClass('hidden');
						$('#form_woord_rapport_files').find('.alert-warning').addClass('hidden');
					}
				},
				error: function(xhr, status, errorThrown)
				{
					console.log("error");
				}
				,
				complete: function(xhr,status)
				{
					// $('html, body').animate({scrollTop:0}, 'fast');
				}
			}
		);

	}
}
}
);

// Begin DELETE document function

$('#btn_delete_woord_rapport').click(function(e)
{
	// DELETE a document
	var r =    confirm("Delete document?");
	if (r){

		// For each checked element
		var sList = "";
		$('input[type=checkbox]').each(function () {

			if (this.name != "")
			{
				var _document_id = '';
				var _document_name = '';
				/* begin post */
				if (this.checked)
				{
					_document_id = $(this).val();
					_document_name = $(this).attr("doc");
					_document_klas = $(this).attr("klas");

					$.post(
						"ajax/deletedocument_woord_rapport.php",
						{document_id:_document_id, document_name: _document_name, document_klas:_document_klas },

						function( data ) {
							console.log(data);
						}
					).done(function(data) {
						/* it's done */
						if( data == 1 ) {
							alert('Document deleted successfully!');
							$.get("ajax/getdocuments_woord_rapport.php", {}, function(data) {
								$('#div_table_document').empty();
								$("#div_table_document").append(data);
							});

						} else {
							$('#form_woord_rapport_files').find('.alert-error').removeClass('hidden');
							$('#form_woord_rapport_files').find('.alert-info').addClass('hidden');
							$('#form_woord_rapport_files').find('.alert-warning').addClass('hidden');
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
