<?php include 'document_start.php'; ?>
<?php include 'sub_nav.php'; ?>
<div class="push-content-220">
	<main id="main" role="main">
		<?php include 'header.php'; ?>
		<?php $UserRights = $_SESSION['UserRights'];
		if ($UserRights != "BEHEER") {
			include 'redirect.php';
		} else { ?>
			<section>
				<div class="container container-fs">
					<div class="row">
						<div class="default-secondary-bg-color col-md-12 inset brd-bottom">
							<h1 class="primary-color">Settings</h1>
							<?php include 'breadcrumb.php'; ?>
						</div>
					</div>
					<div class="row">
						<form class="form-horizontal align-left" role="form" name="form-setting" id="form-setting" method='POST'>
							<div class="col-md-8 full-inset">
								<div class="primary-bg-color brd-full">
									<div class="box">
										<div class="box-title full-inset brd-bottom">
											<div class="row">
												<h2 class="col-md-8">Settings</h2>
												<div class="col-md-4">

												</div>
											</div>
										</div>
									</div>

									<div class="box-content full-inset sixth-bg-color">
										<div id="tbl_settings" class="dataRetrieverOnLoad" data-ajax-href="ajax/getsettingregister_tabel.php"></div>
										<div class="table-responsive data-table" data-table-type="off" data-table-search="false" data-table-pagination="false">
											<div id="dataRequest-setting-detail"></div>
										</div>
									</div>
								</div>
							</div>


							<div class="col-md-4 full-inset">
								<div class="primary-bg-color brd-full">
									<div class="box">
										<div class="box-title full-inset brd-bottom">
											<h3>Nieuwe /bijwerken setting</h3>
										</div>
										<div class="sixth-bg-color box content full-inset">

											<div class="alert alert-danger hidden">
												<p><i class="fa fa-warning"></i> Er zijn lege velden die ingevuld moet worden!</p>
											</div>
											<div class="alert alert-info hidden">
												<p><i class="fa fa-check"></i> The new Setting has been register!</p>
											</div>
											<div class="alert alert-info hidden" id="updated_suscessfully">
												<p><i class="fa fa-check"></i> The Setting has been Updated</p>
											</div>
											<div class="alert alert-info hidden" id="created_suscessfully">
												<p><i class="fa fa-check"></i> The Setting Has Been Created</p>
											</div>
											<div class="alert alert-info hidden" id="deleted_suscessfully">
												<p><i class="fa fa-check"></i> The Setting Has Been Deleted</p>
											</div>
											<fieldset>
												<input type="hidden" id="id_calendar" name="id_calendar" value=0>
												<input type="hidden" id="calendar_config" name="calendar_config" value="||true|">
												<!-- <input id="id_setting" name="id_setting" type="text" value="" hidden> -->
												<!-- School Name -->
												<div class="form-group">
													<label class="col-md-4 control-label">School Name</label>
													<div class="col-md-8">
														<input id="school_name" class="form-control" disabled="disabled" type="text" name="school_name" value="<?php print $_SESSION['schoolname']; ?>" />
													</div>
												</div>
												<!-- School jaar -->
												<div class="form-group">
													<label class="col-md-4 control-label">School Jaar</label>
													<div class="col-md-8">
														<select class="form-control" name="setting_school_jaar" id="setting_school_jaar">
															<option value="2015-2016">2015-2016</option>
															<option value="2016-2017">2016-2017</option>
															<option value="2017-2018">2017-2018</option>
															<option value="2018-2019">2018-2019</option>
															<option value="2019-2020">2019-2020</option>
															<option value="2020-2021">2020-2021</option>
															<option value="2021-2022">2021-2022</option>
															<option value="2022-2023">2022-2023</option>
															<option value="2023-2024">2023-2024</option>
															<option value="2024-2025">2024-2025</option>
															<option value="2024-2025">2025-2026</option>
														</select>
													</div>
												</div>

												<hr>

												<!-- Rapnumber 1 -->
												<div class="form-group">
													<label class="col-md-4 control-label">Rapnumber 1</label>
													<div class="col-md-8">
														<label>
															<input type="checkbox" name="setting_rapnumber_1" id="setting_rapnumber_1" value="1">
															<input type="hidden" name="setting_rapnumber_1_val" id="setting_rapnumber_1_val" value=0>
														</label>
													</div>
												</div>
												<div class="form-group ">
													<label class="col-md-4 control-label">Begin rap 1</label>
													<div class="col-md-6">
														<div class="input-group date">
															<input id="setting_begin_rap_1" type="text" class="form-control calendar" name="setting_begin_rap_1">
															<span class="input-group-addon"><i class="fa fa-calendar default-primary-color"></i></span>
														</div>
													</div>
												</div>
												<div class="form-group ">
													<label class="col-md-4 control-label">End rap 1</label>
													<div class="col-md-6">
														<div class="input-group date">
															<input id="setting_end_rap_1" type="text" class="form-control calendar" name="setting_end_rap_1">
															<span class="input-group-addon"><i class="fa fa-calendar default-primary-color"></i></span>
														</div>
													</div>

												</div>
												<hr>
												<!-- Rapnumber 2 -->
												<div class="form-group">
													<label class="col-md-4 control-label">Rapnumber 2</label>
													<div class="col-md-8">
														<label>
															<input type="checkbox" name="setting_rapnumber_2" id="setting_rapnumber_2">
															<input type="hidden" name="setting_rapnumber_2_val" id="setting_rapnumber_2_val" value=0>
														</label>
													</div>
												</div>
												<div class="form-group ">
													<label class="col-md-4 control-label">Begin rap 2</label>
													<div class="col-md-6">
														<div class="input-group date">
															<input id="setting_begin_rap_2" type="text" placeholder="" class="form-control calendar" name="setting_begin_rap_2">
															<span class="input-group-addon"><i class="fa fa-calendar default-primary-color"></i></span>
														</div>
													</div>
												</div>
												<div class="form-group ">
													<label class="col-md-4 control-label">End rap 2</label>
													<div class="col-md-6">
														<div class="input-group date">
															<input id="setting_end_rap_2" type="text" class="form-control calendar" name="setting_end_rap_2">
															<span class="input-group-addon"><i class="fa fa-calendar default-primary-color"></i></span>
														</div>
													</div>

												</div>
												<hr>
												<!-- Rapnumber 3  -->
												<div class="form-group">
													<label class="col-md-4 control-label">Rapnumber 3</label>
													<div class="col-md-8">
														<label>
															<input type="checkbox" name="setting_rapnumber_3" id="setting_rapnumber_3">
															<input type="hidden" name="setting_rapnumber_3_val" id="setting_rapnumber_3_val" value=0>
														</label>
													</div>
												</div>
												<div class="form-group ">
													<label class="col-md-4 control-label">Begin rap 3</label>
													<div class="col-md-6">
														<div class="input-group date">
															<input id="setting_begin_rap_3" type="text" value="" placeholder="" class="form-control calendar" name="setting_begin_rap_3">
															<span class="input-group-addon"><i class="fa fa-calendar default-primary-color"></i></span>
														</div>
													</div>
												</div>
												<div class="form-group ">
													<label class="col-md-4 control-label">End rap 3</label>
													<div class="col-md-6">
														<div class="input-group date">
															<input id="setting_end_rap_3" type="text" class="form-control calendar" name="setting_end_rap_3">
															<span class="input-group-addon"><i class="fa fa-calendar default-primary-color"></i></span>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-4 control-label">Mj</label>
													<div class="col-md-8">
														<label>
															<input type="checkbox" name="setting_mj" id="setting_mj" value="1">
															<input type="hidden" name="setting_mj_val" id="setting_mj_val" value=0>
														</label>
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-4 control-label">Sort</label>
													<div class="col-md-8">
														<select class="form-control" name="setting_sort" id="setting_sort">
															<option value="DESC">DESC</option>
															<option value="ASC">ASC</option>
														</select>
													</div>
												</div>
												<?php if ($_SESSION["SchoolType"] == 2) { ?>
													<div class="form-group">
														<label class="col-md-4 control-label">CEX</label>
														<div class="col-md-8">
															<label>
																<input type="checkbox" name="setting_c1" id="setting_c1" value="1">
																<input type="hidden" name="setting_c1_val" id="setting_c1_val" value=0>
															</label>
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 control-label">HER CEX</label>
														<div class="col-md-8">
															<label>
																<input type="checkbox" name="setting_c2" id="setting_c2" value="1">
																<input type="hidden" name="setting_c2_val" id="setting_c2_val" value=0>
															</label>
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 control-label">EC</label>
														<div class="col-md-8">
															<label>
																<input type="checkbox" name="setting_c3" id="setting_c3" value="1">
																<input type="hidden" name="setting_c3_val" id="setting_c3_val" value=0>
															</label>
														</div>
													</div>
												<?php } ?>
												<hr>

												<!-- Buttons -->
												<div class="form-group full-inset">
													<button type="submit" class="btn btn-primary btn-m-w pull-right mrg-left" id="btn_add_setting" name="btn_add_setting">SAVE</button>
													<button type="reset" class="btn btn-danger btn-m-w pull-right mrg-left" id="btn_clear_setting" name="btn_clear_setting">CLEAR</button>
												</div>
											</fieldset>

										</div>
									</div>
								</div>
							</div>
						</form>
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

<script type="text/javascript">
	$('#setting_rapnumber_1').change(function() {
		if ($(this).prop('checked')) {
			$('#setting_rapnumber_1').val(1);
			$('#setting_rapnumber_1_val').val(1);

		} else {
			$('#setting_rapnumber_1').val(0);
			$('#setting_rapnumber_1_val').val(0);

		}
	});
	$('#setting_rapnumber_2').change(function() {
		if ($(this).prop('checked')) {
			$('#setting_rapnumber_2').val(1);
			$('#setting_rapnumber_2_val').val(1);

		} else {
			$('#setting_rapnumber_2').val(0);
			$('#setting_rapnumber_2_val').val(0);

		}
	});
	$('#setting_rapnumber_3').change(function() {
		if ($(this).prop('checked')) {
			$('#setting_rapnumber_3').val(1);
			$('#setting_rapnumber_3_val').val(1);

		} else {
			$('#setting_rapnumber_3').val(0);
			$('#setting_rapnumber_3_val').val(0);

		}
	});
	$('#setting_mj').change(function() {
		if ($(this).prop('checked')) {
			$('#setting_mj').val(1);
			$('#setting_mj_val').val(1);

		} else {
			$('#setting_mj').val(0);
			$('#setting_mj_val').val(0);

		}
	});
	$('#setting_c1').change(function() {
		if ($(this).prop('checked')) {
			$('#setting_c1').val(1);
			$('#setting_c1_val').val(1);

		} else {
			$('#setting_c1').val(0);
			$('#setting_c1_val').val(0);

		}
	});
	$('#setting_c2').change(function() {
		if ($(this).prop('checked')) {
			$('#setting_c2').val(1);
			$('#setting_c2_val').val(1);

		} else {
			$('#setting_c2').val(0);
			$('#setting_c2_val').val(0);

		}
	});
	$('#setting_c3').change(function() {
		if ($(this).prop('checked')) {
			$('#setting_c3').val(1);
			$('#setting_c3_val').val(1);

		} else {
			$('#setting_c3').val(0);
			$('#setting_c3_val').val(0);

		}
	});
</script>