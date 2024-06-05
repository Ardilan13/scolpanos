<?php include 'document_start.php'; ?>

<?php include 'sub_nav.php'; ?>

<div class="push-content-220">
	<main id="main" role="main">
		<?php include 'header.php'; ?>
		<?php $UserRights = $_SESSION['UserRights'];
		$UserGUID = $_SESSION['UserGUID'];
		if ($UserRights != "BEHEER" || $UserGUID == "8DEFFDC8-0239-65F3-5416-A512745D5583" || $UserGUID == "391F1722-6015-F210-045D-E89FEA5A0F2A") {
			include 'redirect.php';
		} else { ?>
			<section>
				<div class="container container-fs">
					<div class="row">
						<div class="default-secondary-bg-color col-md-12 inset brd-bottom">
							<h1 class="primary-color">Users Account</h1>
							<?php include 'breadcrumb.php'; ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8 full-inset">
							<div class="primary-bg-color brd-full">
								<div class="box">
									<div class="box-title full-inset brd-bottom">
										<div class="row">
											<h2 class="col-md-8">User Account</h2>
										</div>
									</div>
									<div class="box-content full-inset sixth-bg-color">
										<div id="tbl_user_account" class="dataRetrieverOnLoad" data-ajax-href="ajax/getuseraccount_tabel.php"></div>
										<div class="table-responsive data-table" data-table-type="on" data-table-search="true" data-table-pagination="true">
											<div id="tbl_list_users_accounts" name="tbl_list_users_accounts"></div>
										</div>
										<input type="hidden" name="selected_id_user_row" id="selected_id_user_row" value="" />
										<input type="hidden" name="selected_color_row" id="selected_color_row" value="" />
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-4 full-inset">
							<div class="primary-bg-color brd-full">
								<div class="box">
									<div class="box-title full-inset brd-bottom">
										<h3>New Scolpanos User</h3>
									</div>
									<div class="sixth-bg-color box content full-inset">
										<form class="form-horizontal align-left" role="form" name="frm_user_account" id="frm_user_account">
											<div class="alert alert-danger hidden">
												<p><i class="fa fa-warning"></i> Er zijn lege velden die ingevuld moet worden!</p>
											</div>
											<div class="alert alert-info hidden">
												<p><i class="fa fa-check"></i> The new User has been register!</p>
											</div>
											<div class="alert alert-info hidden" id="updated_suscessfully">
												<p><i class="fa fa-check"></i> The User has been Updated</p>
											</div>
											<div class="alert alert-info hidden" id="created_suscessfully">
												<p><i class="fa fa-check"></i> The User Has Been Created</p>
											</div>
											<div class="alert alert-info hidden" id="deleted_suscessfully">
												<p><i class="fa fa-check"></i> The User Has Been Deleted</p>
											</div>
											<fieldset>
												<!-- klas -->
												<!-- <div id="lblClassAvi" class="form-group" name ="lblClassAvi">
													<label class="col-md-4 control-label" for="">Klass Avi</label>
													<div class="dataClassAvi" data-ajax-href="ajax/getlistclassavi.php"></div>
													<div class="col-md-8">
													<div id="class_list_result"></div>
												</div>
												<input id="class_hidden" name="class_hidden" type="text" value="" hidden>
											</div> -->

												<!-- User Email -->
												<!-- <div class="form-group"  id='div_user_GUID' name='div_user_GUID'>
											<label class="col-md-4 control-label">User GUID</label>
											<div class="col-md-8">
											<input id="user_GUID" class="form-control" type="text" name="user_GUID" />
										</div>
									</div> -->
												<div class="form-group">
													<input id="user_GUID" type="hidden" name="user_GUID" />
													<label class="col-md-4 control-label">User Email</label>
													<div class="col-md-8">
														<input id="user_email" class="form-control" type="email" name="user_email" />
													</div>
												</div>
												<div class="form-group" id="div_user_password" class="div_user_password">
													<label class="col-md-4 control-label">Password</label>
													<div class="col-md-8">
														<input id="user_password" class="form-control" type="password" name="user_password" />
													</div>
												</div>
												<!-- <div class="form-group">
									<label class="col-md-4 control-label">Rep. Password</label>
									<div class="col-md-8">
									<input id="user_password2" class="form-control" type="text" name="user_password" />
								</div>
							</div> -->
												<div class="form-group">
													<label class="col-md-4 control-label">Firts Name</label>
													<div class="col-md-8">
														<input id="user_firts_name" class="form-control" type="text" name="user_firts_name" />
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-4 control-label">Last Name</label>
													<div class="col-md-8">
														<input id="user_last_name" class="form-control" type="text" name="user_last_name" />
													</div>
												</div>
												<!-- Period -->
												<div class="form-group">
													<label class="col-md-4 control-label">User Rights</label>
													<div class="col-md-8">
														<select id="user_rights" name="user_rights" class="form-control">
															<option value="BEHEER">BEHEER</option>
															<option value="DOCENT">DOCENT</option>
															<option value="TEACHER">TEACHER</option>
															<option value="ADMINISTRATIE">ADMINISTRATIE</option>
															<option value="ONDERSTEUNING">ONDERSTEUNING</option>
															<option value="ASSISTENT">ASSISTENT</option>
														</select>
													</div>
													<!-- <input id="user_rights" name="user_rights" type="text" value="" hidden> -->
												</div>
												<!-- <div class="form-group">
							<label class="col-md-4 control-label">School</label>
							<div class="col-md-8">
							<select id="user_school_id" name="user_school_id" class="form-control">
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
						</select>
					</div>
				</div> -->
												<div class="form-group">
													<label class="col-md-4 control-label">Class</label>
													<div class="col-md-8">

														<select id="user_class" name="user_class" class="form-control">
															<option value=" "> </option>
															<?php if ($_SESSION['SchoolID'] == 18) { ?>
																<option value="1">1</option>
																<option value="2">2</option>
															<?php } else { ?>
																<option value="1A">1A</option>
																<option value="2A">2A</option>
																<option value="1B">1B</option>
																<option value="2B">2B</option>
																<option value="1C">1C</option>
																<option value="2C">2C</option>
															<?php } ?>
															<option value="3A">3A</option>
															<option value="4A">4A</option>
															<option value="5A">5A</option>
															<option value="6A">6A</option>
															<option value="3B">3B</option>
															<option value="4B">4B</option>
															<option value="5B">5B</option>
															<option value="6B">6B</option>
															<option value="3C">3C</option>
															<option value="4C">4C</option>
															<option value="5C">5C</option>
															<option value="6C">6C</option>
															<option value="k1">k1</option>
															<option value="k2">k2</option>
															<option value="k3">k3</option>
															<option value="k4">k4</option>

															<?php if ($_SESSION['SchoolID'] == 4) : ?>
																<option value="KO1">KO1</option>
																<option value="KO2">KO2</option>
																<option value="KO3">KO3</option>
															<?php endif; ?>

															<?php if ($_SESSION['SchoolID'] <= 11) : ?>
																<option value="grp1">grp1</option>
																<option value="grp2">grp2</option>
																<option value="grp3">grp3</option>
																<option value="grp4 ">grp4 </option>
															<?php endif; ?>

														</select>
													</div>
													<!-- <input id="user_class" name="user_class" type="text" value="" hidden> -->
												</div>

												<!-- Buttons -->
												<div class="form-group full-inset">
													<button type="submit" class="btn btn-primary btn-m-w pull-right mrg-left" id="btn_create_user_account">SAVE</button>
													<button class="btn btn-primary btn-m-w pull-right mrg-left hidden" id="btn_update_user_account">UPDATE</button>
													<button type="reset" class="btn btn-danger btn-m-w pull-right mrg-left" id="btn_clear_user_account">CLEAR</button>
													<button type="reset" class="btn btn-danger btn-m-w pull-right mrg-left hidden" id="btn_delete_user_account">DELETE</button>
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