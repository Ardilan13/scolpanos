<?php include 'document_start.php'; ?>
<?php include 'sub_nav.php'; ?>
<div class="push-content-220">
	<?php include 'header.php'; ?>
	<?php $UserRights= $_SESSION['UserRights'];
	if ($UserRights == "ADMINISTRATIE" || $UserRights == "TEACHER"){
		include 'redirect.php';} else{?>
	<main id="main" role="main">
		<section>
			<div class="container container-fs">
				<div class="row">
					<div class="default-secondary-bg-color col-md-12 inset brd-bottom clearfix">
						<h1 class="primary-color">Document Klas:</h1>
						<?php include 'breadcrumb.php'; ?>
					</div>
					<div class="default-secondary-bg-color col-md-12 inset filter-bar brd-bottom clearfix">
						<form id="form-vrapportexport" class="form-inline form-data-retriever" name="filter-vak" role="form" method="GET" action="/dev_tests/test_exportexcel.php">
							<fieldset>
								<div class="form-group">
									<label for="cijfers_klassen_lijst">Klas</label>
									<select class="form-control" name="rapport_klassen_lijst" id="rapport_klassen_lijst">
										<!-- Options populated by AJAX get -->
										<option value="Prisma/Begeleiding">Prisma/Begeleiding</option>
										<option value="Begeleiding">Begeleiding</option>
										<option value="Extra hulp">Extra hulp</option>

									</select>
								</div>
								<div class="form-group">
									<button data-display="data-display" type="submit" id="btn_document_klas_filter" name="btn_document_klas_filter" class="btn btn-primary btn-m-w btn-m-h">ZOEKEN</button>
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
										<p><i class="fa fa-info"></i> Please select the Klas and then, press the button zoeken.</p>
									</div>
									<div class="full-inset alert alert-info reset-mrg-bottom hidden" id="popup_message_rapport" >
										<p ><i class="fa fa-info"></i> If you have problems downloading the excel file, check your pop-up settings</p>
									</div>
									<div class="full-inset alert alert-warning reset-mrg-bottom hidden" id="document_klas_message_klass">
										<p><i class="fa fa-warning"></i> You must select one Klas to open Document Klas File...</p>
									</div>
									<div class="full-inset alert alert-warning reset-mrg-bottom hidden" id="setting_message_rapport">
										<p><i class="fa fa-warning"></i> There should be a Setting created for this schooljaar to generate a rapport...</p>
									</div>
									<div class="full-inset alert alert-warning reset-mrg-bottom hidden" id="document_klas_message_date">
										<p><i class="fa fa-warning"></i> You must select a date to open Document Klas File...</p>
									</div>
									<div class="full-inset alert alert-warning reset-mrg-bottom hidden" id="document_klas_message_dl">
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
															<h2 class="col-md-12">Document Klas Files</h2>
														</div>
													</div>
													<div class="col-md-8 full-inset">
														<div id="div_table_document">

														</div>

													</div>
													<!-- <div class="col-md-4 full-inset"> -->
													<div class="col-md-4 full-inset brd-full">

														<div class=" primary-bg-color full-inset">
															<h3>Upload Document Klas Files</h3>
														</div>
														<div class="sixth-bg-color full-inset">
															<form class="form-horizontal align-left" role="form" name="form_document_klas_files" id="form_document_klas_files">

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
																			<input class="form-control" type="file" name="document_klas_file_to_upload" id="document_klas_file_to_upload" required>
																		</div>
																	</div>
																	<div class="form-group hidden" id="class_beheer_upload">
																		<label class="col-md-4 control-label">Klas</label>
																		<div class="col-md-8">
																			<select class="form-control" name="rapport_klassen_lijst_upload" id="rapport_klassen_lijst_upload">
																				<!-- Options populated by AJAX get -->
																				<option value="Prisma/Begeleiding">Prisma/Begeleiding</option>
																				<option value="Begeleiding">Begeleiding</option>
																				<option value="Extra hulp">Extra hulp</option>


																			</select>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="col-md-4 control-label">Description</label>
																		<div class="col-md-8">
																			<textarea class="form-control" id="description_document_klas" name="description_document_klas"></textarea>
																		</div>
																	</div>
																	<div class="form-group full-inset">
																		<button type="submit" class="btn btn-primary btn-m-w pull-right mrg-left" id="btn_add_document_klas">Upload</button>
																		<button type="reset" class="btn btn-danger btn-m-w pull-right mrg-right" id="btn_delete_document_klas">Delete</button>
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

	$.get("ajax/getdocuments_klas.php", {}, function(data) {
		$("#div_table_document").append(data);
	});

	if ($('#user_rights').val()==='BEHEER')
	{
		$("#class_beheer_upload").removeClass("hidden");
		$.getJSON("ajax/getklassen_json.php", function(result){
			var klas = $("#rapport_klassen_lijst_upload");
			$.each(result, function(){
				klas.append($("<option />").val(this.klas).text(this.klas));
			});
		});

	}


});

$("#rapport_klassen_lijst").change(function(){
	$("#klas_to_upload").val($("#rapport_klassen_lijst option:selected").val());
});

$("#btn_document_klas_filter").click(function (e)
{
	e.preventDefault();

	var SchoolID = '<?php echo $_SESSION["SchoolID"]; ?>';
	var klas = $("#rapport_klassen_lijst option:selected").val();

	$.get("ajax/getdocuments_klas_filter.php?klas_filter="+klas, {}, function(data) {
			$('#div_table_document').empty();
		$("#div_table_document").append(data);
	});


});

$('#form_document_klas_files').on("submit", function(e)
{
	/* prevent refresh */
	e.preventDefault();

	if ($('#user_rights').val()==='BEHEER' && $("#rapport_klassen_lijst_upload option:selected").val()=="NONE")
	{
		alert("Sorry, you must select a Class to upload file...");

	}
	else
	{
		if($("#document_klas_file_to_upload").val() === 0)
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
					url: "ajax/upload_document_klas.php",
					// data: $('#form_document_klas_files').serialize(),
					cache: false,
					contentType: false,
					processData: false,
					data: new FormData(this),
					type: "POST",
					// dataType: "text",
					success: function(text) {
						if(text != 1)
						{
							$('#form_document_klas_files').find('.alert-error').removeClass('hidden');
							$('#form_document_klas_files').find('.alert-info').addClass('hidden');
							$('#form_document_klas_files').find('.alert-warning').addClass('hidden');
						}
						else
						{
							alert('Document upload successfully!');
							$.get("ajax/addaudit_document.php"),
							$.get("ajax/getdocuments_klas.php", {},
							function(data)
							{
								$('#div_table_document').empty();
								$("#div_table_document").append(data);
							}
						);
						// Clear all object of form except class
						$('#description').val('');
						$('#form_document_klas_files').find('.alert-error').addClass('hidden');
						$('#form_document_klas_files').find('.alert-info').addClass('hidden');
						$('#form_document_klas_files').find('.alert-warning').addClass('hidden');
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

$('#btn_delete_document_klas').click(function(e)
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
						"ajax/deletedocument_klas.php",
						{document_id:_document_id, document_name: _document_name, document_klas:_document_klas },

						function( data ) {
							console.log(data);
						}
					).done(function(data) {
						/* it's done */
						if( data == 1 ) {
							alert('Document deleted successfully!');
							$.get("ajax/getdocuments_klas.php", {}, function(data) {
								$('#div_table_document').empty();
								$("#div_table_document").append(data);
							});

						} else {
							$('#form_document_klas_files').find('.alert-error').removeClass('hidden');
							$('#form_document_klas_files').find('.alert-info').addClass('hidden');
							$('#form_document_klas_files').find('.alert-warning').addClass('hidden');
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
