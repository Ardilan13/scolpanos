<?php include 'document_start.php'; ?>

<?php include 'sub_nav.php'; ?>

<div class="push-content-220">
	<main id="main" role="main">
		<?php include 'header.php'; ?>
		<section>
			<div class="container container-fs">
				<div class="row">
					<div class="default-secondary-bg-color col-md-12 inset brd-bottom">
						<h1 class="primary-color">Avi</h1>
						<?php include 'breadcrumb.php'; ?>
					</div>
				</div>
				<div class="row">

					<div class="default-secondary-bg-color col-md-12 inset filter-bar brd-bottom clearfix">
						<form id="form-vrapportexport" class="form-inline form-data-retriever" name="filter-vak" role="form" method="GET" action="/dev_tests/test_exportexcel.php">
							<fieldset>
								<div class="form-group">
									<label for="cijfers_klassen_lijst">Klas</label>
									<select class="form-control" name="rapport_klassen_lijst" id="rapport_klassen_lijst">
										<!-- Options populated by AJAX get -->
										<!-- <option value="NONE">NONE</option> -->
									</select>
								</div>
								<div class="form-group">
									<!-- <button data-display="data-display" data-ajax-href="ajax/getvakken_tabel.php" type="submit" class="btn btn-primary btn-m-w btn-m-h">zoeken</button> -->
									<button data-display="data-display" type="submit" id="btn_avi_export" name="btn_avi_export" class="btn btn-primary btn-m-w btn-m-h">Export</button>
								</div>
								<div class="form-group">
									<ol class="breadcrumb">
										<li><a id="avi_download" href"#">DOWNLOAD CLEAN AVI EXPORT FILE</a></li>
									</ol>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
				<div class="row">
					<div class="col-md-8 full-inset">
						<div class="primary-bg-color brd-full">
							<div class="box">
								<div class="box-title full-inset brd-bottom">
									<div class="row">
										<h2 class="col-md-8">Avi</h2>
										<div class="col-md-4">

										</div>
									</div>
								</div>
								<div class="box-content full-inset sixth-bg-color">
									<div id="table_avi_detail" class="dataRetrieverOnLoad" data-ajax-href="ajax/getaviregister_tabel.php"></div>
									<div class="table-responsive data-table" data-table-type="on" data-table-search="true" data-table-pagination="true">
										<div id="table_avi_result_detail"></div>
									</div>
								</div>

								<a style="margin-top: 10px" type="submit" name="btn_avi_print" id="btn_avi_print"class="btn btn-default btn-m-w pull-right mrg-left" target="_blank">PRINT</a>
							</div>

						</div>
					</div>

					<div class="col-md-4 full-inset">
						<div class="primary-bg-color brd-full">
							<div class="box">
								<div class="box-title full-inset brd-bottom">
									<h3>Nieuwe</h3>
								</div>
								<div class="sixth-bg-color box content full-inset">
									<form class="form-horizontal align-left" role="form" name="form-avi" id="form-avi">
										<div class="alert alert-error hidden">
											<p><i class="fa fa-warning"></i> The student already has an AVI created for this Period</p>
										</div>
										<div class="alert alert-danger hidden">
											<p><i class="fa fa-warning"></i> Er zijn lege velden die ingevuld moet worden!</p>
										</div>
										<div class="alert alert-info hidden">
											<p><i class="fa fa-check"></i> The new Avi has been register!</p>
										</div>
										<div class="alert alert-info hidden" id="updated_suscessfully">
											<p><i class="fa fa-check"></i> The Avi has been Updated</p>
										</div>
										<div class="alert alert-info hidden" id="created_suscessfully">
											<p><i class="fa fa-check"></i> The Avi Has Been Created</p>
										</div>
										<div class="alert alert-info hidden" id="deleted_suscessfully">
											<p><i class="fa fa-check"></i> The Avi Has Been Deleted</p>
										</div>
										<fieldset>
											<input id="id_avi" name="id_avi" type="text" value="" hidden>
											<!-- klas -->
											<div id="lblClassAvi" class="form-group" name ="lblClassAvi">
												<label class="col-md-4 control-label" for="">Klass Avi</label>
												<div class="dataClassAvi" data-ajax-href="ajax/getlistclassavi.php"></div>
												<div class="col-md-8">
													<div id="class_list_result"></div>
												</div>
												<input id="class_hidden" name="class_hidden" type="text" value="" hidden>
											</div>
											<!-- Student -->
											<div id="student" class="form-group">
												<label class="col-md-4 control-label" for="">Student</label>
												<div class="col-md-8">
													<select id="data_student_by_class" name="data_student_by_class" class="form-control">
														<option selected>Select One Student</option>
													</select>
												</div>
												<input id="data_student_by_class_hidden" name="data_student_by_class_hidden" type="text" value="" hidden>
											</div>
											<!-- Period -->
											<div class="form-group">
												<label class="col-md-4 control-label">Period</label>
												<div class="col-md-8">
													<select id="period" name="period" class="form-control">
														<option value="1">1</option>
														<option value="2">2</option>
														<option value="3">3</option>
													</select>
												</div>
												<input id="period_hidden" name="period_hidden" type="text" value="" hidden>
											</div>
											<!-- Level -->
											<div class="form-group" id="lblLevelAvi" name="lblLevelAvi">
												<label class="col-md-4 control-label" for="level">Level</label>
												<div class="dataLevelAvi" data-ajax-href="ajax/getlistlevelavi.php"></div>
												<div class="col-md-8">
													<div id="level_list_result"></div>
												</div>
												<input id="level_hidden" name="level_hidden" type="text" value="" hidden>
											</div>
											<!-- Promoted -->
											<div class="form-group">
												<label class="col-md-4 control-label">Promoted</label>
												<div class="col-md-8">
													<label>
														<input type="radio" id="avi_promoted" name="avi_promoted" value="1" checked> Yes
														<i class="pending"></i>
													</label>
													<label>
														<input type="radio" id="avi_no_promoted" name="avi_promoted" value="0"> No
														<i class="pending"></i>
													</label>
												</div>
												<input id="promoted_hidden" name="promoted_hidden" type="text" value="" hidden>
											</div>
											<!-- Mistakes -->
											<div class="form-group">
												<label class="col-md-4 control-label">Mistakes</label>
												<div class="col-md-8">
													<input id="mistakes" class="form-control" type="text" name="mistakes" />
												</div>
											</div>
											<!-- Time length -->
											<div class="form-group">
												<label class="col-md-4 control-label" for="telefoon">Time length</label>
												<div class="col-md-8">
													<input id="time_length" class="form-control" type="text" name="time_length" />
												</div>
											</div>
											<!-- Observation -->
											<div class="form-group">
												<label class="col-md-4 control-label">Observation</label>
												<div class="col-md-8">
													<textarea id="observation" class="form-control" rows="2" name="observation" ></textarea>
												</div>
											</div>
											<!-- Buttons -->
											<div class="form-group full-inset">
												<button type="submit" class="btn btn-primary btn-m-w pull-right mrg-left" id="btn-add-avi">SAVE</button>
												<button type="reset" class="btn btn-danger btn-m-w pull-right mrg-left" id="btn-clear-avi">CLEAR</button>
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

<?php include 'document_end.php'; ?>
<script>
// $(function () {
$("#avi_download").click(function (e) {
	$("#avi_download").attr("href","templates/AVI_template.xlsx");
});

	$("#btn_avi_print").click(function () {
		window.open("print.php?name=avi&title=Avi List");
	});

// })

$("#btn_avi_export").click(function (e)
{
	e.preventDefault();


	var SchoolID = '<?php echo $_SESSION["SchoolID"]; ?>';
	var klas = $("#rapport_klassen_lijst option:selected").val();

	if (klas!='NONE'){
		$.ajax({
			url:"ajax/check_setting.php",
			data: "SchoolID="+SchoolID,
			type  : 'POST',
			//dataType: "HTML",
			cache: false,
			async :true,
			success: function(data){
				if (parseInt(data)==1){
					$('#message_rapport').addClass('hidden');
					$('#klas_message_rapport').addClass('hidden');
					$('#setting_message_rapport').addClass('hidden');
					$('#popup_message_rapport').removeClass('hidden');
					window.open("dev_tests\\export_avi.php?avi_klas="+$("#rapport_klassen_lijst option:selected").val());
				}
				else{
					$('#message_rapport').addClass('hidden');
					$('#klas_message_rapport').addClass('hidden');
					$('#popup_message_rapport').addClass('hidden');
					$('#setting_message_rapport').removeClass('hidden');
					//alert('There should be a Setting created for this schooljaar to generate a rapport...');
				}
			}
		});
	}
	else {
		$('#message_rapport').addClass('hidden');
		$('#setting_message_rapport').addClass('hidden');
		$('#popup_message_rapport').addClass('hidden');
		$('#klas_message_rapport').removeClass('hidden');
		//alert('You must select one Klas to export rapport...');
	}
});


</script>
