<?php include 'document_start.php'; ?>
<?php include 'sub_nav.php'; ?>
<div class="push-content-220">
	<main id="main" role="main">
		<?php include 'header.php'; ?>
		<?php $UserRights = $_SESSION['UserRights'];
		if ($UserRights != "BEHEER" || $_SESSION['SchoolType'] != 2) {
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
										<div id="tbl_user_hs_account" class="dataRetrieverOnLoad" data-ajax-href="ajax/get_user_hs_account_tabel.php"></div>
										<div class="table-responsive data-table" data-table-type="on" data-table-search="true" data-table-pagination="true">
											<div id="tbl_list_users_hs_accounts" name="tbl_list_users_hs_accounts"></div>
										</div>
										<input type="hidden" name="selected_id_user_row" id="selected_id_user_row" value="" />
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
										<form class="form-horizontal align-left" role="form" name="frm_user_hs_account" id="frm_user_hs_account">
											<div class="alert alert-danger hidden">
												<p>
													<i class="fa fa-warning"></i> Er zijn lege velden die ingevuld moet worden!
												</p>
											</div>
											<div class="alert alert-error hidden">
												<p>
													<i class="fa fa-warning"></i> Please check the user form
												</p>
											</div>
											<div class="alert alert-error hidden" id='error_removing_user'>
												<p>
													<i class="fa fa-warning"></i> Error removing user
												</p>
											</div>
											<div class="alert alert-error-password hidden">
												<p>
													<i class="fa fa-warning"></i> The password and password confirmation do not match
												</p>
											</div>
											<div class="alert alert-info hidden">
												<p>
													<i class="fa fa-check"></i> The new User has been register!
												</p>
											</div>
											<div class="alert alert-info hidden" id="updated_suscessfully">
												<p>
													<i class="fa fa-check"></i> The User has been Updated
												</p>
											</div>
											<div class="alert alert-info hidden" id="created_suscessfully">
												<p>
													<i class="fa fa-check"></i> The User Has Been Created
												</p>
											</div>
											<div class="alert alert-info hidden" id="deleted_suscessfully">
												<p>
													<i class="fa fa-check"></i> The User Has Been Deleted
												</p>
											</div>
											<fieldset>
												<div class="form-group">
													<input id="user_hs_GUID" type="hidden" name="user_hs_GUID" />
													<label class="col-md-4 control-label">User Email</label>
													<div class="col-md-8">
														<input id="user_hs_email" class="form-control" type="email" name="user_hs_email" />
													</div>
												</div>
												<div class="form-group" id="div_user_hs_password" class="div_user_hs_password">
													<label class="col-md-4 control-label">Password</label>
													<div class="col-md-8">
														<input id="user_hs_password" class="form-control" type="password" name="user_hs_password" />
													</div>
												</div>
												<div class="form-group" id="div_user_hs_password_confirm" class="div_user_hs_password_confirm">
													<label class="col-md-4 control-label">Confirm Password</label>
													<div class="col-md-8">
														<input id="user_hs_password_confirmed" class="form-control" type="password" name="user_hs_password_confirmed" />
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-4 control-label">Firts Name</label>
													<div class="col-md-8">
														<input id="user_hs_firts_name" class="form-control" type="text" name="user_hs_firts_name" />
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-4 control-label">Last Name</label>
													<div class="col-md-8">
														<input id="user_hs_last_name" class="form-control" type="text" name="user_hs_last_name" />
													</div>
												</div>
												<!-- Period -->
												<div class="form-group">
													<label class="col-md-4 control-label">User Rights</label>
													<div class="col-md-8">
														<select id="user_hs_rights" name="user_hs_rights" class="form-control">
															<option value="BEHEER">BEHEER</option>
															<option value="DOCENT">DOCENT</option>
															<option value="ADMINISTRATIE">ADMINISTRATIE</option>
														</select>
													</div>
													<input type="text" value="<?php echo $_SESSION['SchoolID']; ?>" name="user_hs_school" hidden>
													<!-- <input id="user_rights" name="user_rights" type="text" value="" hidden> -->
												</div>
												<!-- Buttons -->
												<div class="form-group full-inset">
													<button type="button" class="btn btn-primary btn-m-w pull-right mrg-left" id="btn_create_user_hs_account">SAVE</button>
													<button class="btn btn-primary btn-m-w pull-right mrg-left hidden" id="btn_update_user_hs_account">SAVE</button>
													<button type="reset" class="btn btn-danger btn-m-w pull-right mrg-left" id="btn_clear_user_hs_account">CLEAR</button>
													<button type="button" class="btn btn-danger btn-m-w pull-right mrg-left hidden" id="btn_delete_user_hs_account">DELETE</button>
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
		} ?>
<!-- PLEASE CAREFULLY WIH THIS "}" THAT CLOSE SECURITY  ELSE IF -->

<script>
	$(document).ready(function() {
		//User Account table
		if ($('#tbl_user_hs_account').length) {
			var hrefLink = $('#tbl_user_hs_account').attr('data-ajax-href'),
				replaceDiv = $('#tbl_user_hs_account').attr('data-display');

			$.get(hrefLink, function(data) {
				$('#tbl_list_users_hs_accounts').html(data);
			}).done(function() {

			}).fail(function() {
				alert("error");
			});
		};
	});
	//@ljbello Begin Code form user account Caribe Developer
	$frm_user_hs_account = $('#frm_user_hs_account'),

		$("#btn_create_user_hs_account").click(function(e) {
			/* prevent refresh */
			e.preventDefault();
			if ($("#user_hs_email").val().length === 0 ||
				$("#user_hs_password").val().length === 0 ||
				$("#user_hs_firts_name").val().length === 0 ||
				$("#user_hs_last_name").val().length === 0 ||
				$("#user_hs_rights").val().length === 0 ||
				$("#user_hs_password_confirmed").val().length === 0) {
				$frm_user_hs_account.find('.alert-error').removeClass('hidden');
			} else if ($("#user_hs_password").val() != $("#user_hs_password_confirmed").val()) {
				$frm_user_hs_account.find('.alert-error-password').removeClass('hidden');
			} else {
				$.ajax({
					url: "ajax/adduser_hs_account.php",
					data: $frm_user_hs_account.serialize(),
					type: "POST",
					dataType: "text",
					success: function(text) {
						/* if (text != "1") {
							$frm_user_hs_account.find('.alert-error').removeClass('hidden');
						}  else if (text == "1") {*/
						$frm_user_hs_account.find('#created_suscessfully').removeClass('hidden');
						$.get("ajax/get_user_hs_account_tabel.php", function(data) {
							$('#tbl_list_users_hs_accounts').empty();
							$("#tbl_list_users_hs_accounts").append(data);
						});
						//  $('#id_user_hs_account').val("0");
						$('#btn_clear_user').text("CLEAR");
						// Clear all object of form
						$('input[type=text]').each(function() {
							$(this).val('');
						});
						$('input[type=email]').each(function() {
							$(this).val('');
						});
						$('input[type=password]').each(function() {
							$(this).val('');
						});
						$('input[type=password]').each(function() {
							$(this).val('');
						});

						// }
					},
					error: function(xhr, status, errorThrown) {
						alert('ERROR');
						console.log("error");
					},
					complete: function(xhr, status) {
						$('html, body').animate({
							scrollTop: 0
						}, 'fast');
						setTimeout(function() {
							//$(".alert-info").fadeOut(1500);
							//  $(".alert-error").fadeOut(1500);
							//$fromuser_hs_accountRegistration.find('.alert-info').addClass('hidden');
							//$fromuser_hs_accountRegistration.find('.alert-error').addClass('hidden');
							$('#created_suscessfully').fadeOut(1500);
						}, 3000);
					}
				});
			}
		});
	$("#btn_update_user_hs_account").on("click", function(e) {
		e.preventDefault();
		// UPDATE a user_hs_account

		/* begin post */

		$.ajax({
			url: "ajax/updateuser_hs_account_rights.php",
			data: $frm_user_hs_account.serialize(),
			type: "POST",
			dataType: "text",
			success: function(text) {
				if (text != 1) {
					$frm_user_hs_account.find('.alert-error').removeClass('hidden');
				} else if (text == 1) {
					// alert('user_hs_account update successfully!');
					$frm_user_hs_account.find('#updated_suscessfully').removeClass('hidden');
					$.get("ajax/getuseraccount_tabel.php", function(data) {
						$('#tbl_list_users_accounts').empty();
						$("#tbl_list_users_accounts").append(data);
					});
					$('#btn_clear_user_hs_account').text("CLEAR");
					// Clear all object of form
					$('input[type=text]').each(function() {
						$(this).val('');
					});

				}
			},
			error: function(xhr, status, errorThrown) {
				console.log("error");
			},
			complete: function(xhr, status) {
				$('html, body').animate({
					scrollTop: 0
				}, 'fast');

				setTimeout(function() {
					$(".alert-info").fadeOut(1500);
					$(".alert-error").fadeOut(1500);
					$(".alert-warning").fadeOut(1500);
					$('#updated_suscessfully').fadeOut(1500);
					location.reload();
				}, 3000);
			}
		});
	});
	$("#btn_delete_user_hs_account").on("click", function(e) {
		e.preventDefault();
		$.ajax({
			url: "ajax/deleteuser_hs_account.php",
			data: $frm_user_hs_account.serialize(),
			type: "POST",
			dataType: "text",
			success: function(text) {
				if (text != "1") {
					$('#error_removing_user').removeClass('hidden');
					//		$fromuser_hs_accountRegistration.find('.alert-info').addClass('hidden');
					//		$fromuser_hs_accountRegistration.find('.alert-warning').addClass('hidden');
				} else if (text == "1") {
					// alert('user_hs_account deleted successfully!');
					$frm_user_hs_account.find('#deleted_suscessfully').removeClass('hidden');
					$('#btn_clear_user_hs_account').text("CLEAR");
					// Clear all object of form
					$('input[type=text]').each(function() {
						$(this).val('');
					});
					//$fromuser_hs_accountRegistration.find('.alert-error').addClass('hidden');
					//$fromuser_hs_accountRegistration.find('.alert-info').addClass('hidden');
					//$fromuser_hs_accountRegistration.find('.alert-warning').addClass('hidden');
				}
			},
			error: function(xhr, status, errorThrown) {
				console.log("error");
			},
			complete: function(xhr, status) {
				$('html, body').animate({
					scrollTop: 0
				}, 'fast');
				$('#id_remedial').val("");
				$('#btn_clear_user_hs_account').text("CLEAR");
				setTimeout(function() {
					$(".alert-info").fadeOut(1500);
					$(".alert-error").fadeOut(1500);
					$(".alert-warning").fadeOut(1500);
					$('#deleted_suscessfully').fadeOut(1500);
					location.reload();
				}, 3000);
			}
		});
	});
	$("#btn_clear_user_hs_account").on("click", function(e) {
		$('input[type=text]').each(function() {
			$(this).val('');
		});
		$('.alert').each(function() {
			$(this).addClass('hidden');
		});
	});
</script>