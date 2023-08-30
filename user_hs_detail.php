<?php include 'document_start.php';
require_once "classes/DBCreds.php";
$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8'); ?>
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
							<h1 class="primary-color" id="user_name_hs"></h1>
							<?php include 'breadcrumb.php'; ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8 full-inset">
							<div class="primary-bg-color brd-full">
								<div class="box">
									<div class="box-title full-inset brd-bottom">
										<div class="row">
											<h2 class="col-md-8">RECHTEN DOCENT NAAR VAKKEN</h2>
										</div>
									</div>
									<div class="box-content full-inset sixth-bg-color">
										<div id="tbl_user_detail_hs_account" class="dataRetrieverOnLoad" data-ajax-href="ajax/get_detail_user_hs_account.php"></div>
										<div class="table-responsive data-table" data-table-type="on" data-table-search="true" data-table-pagination="true">
											<div id="tbl_detail_account" name="tbl_detail_account"></div>
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
										<h3>Add or Modify Klas</h3>
									</div>
									<div class="sixth-bg-color box content full-inset">
										<form class="form-horizontal align-left" role="form" name="frm_vak_klas_hs" id="frm_vak_klas_hs">
											<div class="alert alert-danger hidden">
												<p>
													<i class="fa fa-warning"></i> Er zijn lege velden die ingevuld moet worden!
												</p>
											</div>
											<div class="alert alert-error hidden">
												<p>
													<i class="fa fa-warning"></i> Please check the form
												</p>
											</div>
											<div class="alert alert-error-password hidden">
												<p>
													<i class="fa fa-warning"></i> The password and password confirmation do not match
												</p>
											</div>
											<div class="alert alert-info hidden">
												<p>
													<i class="fa fa-check"></i> The new vak and klas has been register for this Docent!
												</p>
											</div>
											<div class="alert alert-info hidden" id="updated_suscessfully">
												<p>
													<i class="fa fa-check"></i> The vak and klas has been Updated
												</p>
											</div>
											<div class="alert alert-info hidden" id="created_suscessfully">
												<p>
													<i class="fa fa-check"></i> The vak and klas Has Been Created
												</p>
											</div>
											<div class="alert alert-info hidden" id="deleted_suscessfully">
												<p>
													<i class="fa fa-check"></i> The vak and klas Has Been Deleted
												</p>
											</div>
											<fieldset>
												<!-- Period -->
												<div class="form-group">
													<label class="col-md-4 control-label">Class</label>
													<div class="col-md-8">
														<select id="cijfers_klassen_lijst" name="cijfers_klassen_lijst" class="form-control">
															<option value=" "> </option>
														</select>
													</div>
													<!-- <input id="user_class" name="user_class" type="text" value="" hidden> -->
												</div>
												<div class="form-group vaken" style="margin-bottom: 1px;">
													<label class="col-md-4 control-label">Vak</label>
													<div class="col-md-8">
														<select id="cijfers_vakken_lijst" name="cijfers_vakken_lijst" class="form-control">
															<option value=" "> </option>
														</select>
													</div>
													<!-- <input id="user_class" name="user_class" type="text" value="" hidden> -->
												</div>
												<div class="form-group group hidden" style="margin-bottom: 1px;">
													<label for="group" class="col-md-4 control-label">Group</label>
													<div class="col-md-8">
														<select class="form-control" name="group" id="group">
															<option value="all">All Groups</option>
															<?php
															$schoolid = $_SESSION['SchoolID'];
															$get_groups = "SELECT g.id,g.name FROM groups g WHERE g.schoolid = $schoolid ORDER BY g.id;";
															$result = mysqli_query($mysqli, $get_groups);
															while ($row = mysqli_fetch_assoc($result)) { ?>
																<option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
															<?php }
															?>
														</select>
													</div>
												</div>
												<div class="form-group" id="div_tutor">
													<label class="col-md-4 control-label" for="" style="padding-top: 17px;">Is Mentor</label>
													<br>
													<div class="col-md-8">
														<label>
															<input type="radio" name="is_tutor" id="is_tutor_yes" value="Yes"> Yes &nbsp; &nbsp;
															<i class="is_tutor"></i>
														</label>
														<label>
															<input type="radio" name="is_tutor" id="is_tutor_no" value="No"> No
															<input name="is_tutor_hidden" class="hidden" id="is_tutor_hidden" value="">
															<i class="is_tutor"></i>
														</label>
													</div>
												</div>
												<div class="form-group hidden" id="has_tutor">
													<label class="col-md-2"></label>
													<label class="col-md-8 control-label" for="" id="lbl_has_tutor"></label>
												</div>
												<input type="text" name="userGUID" id="userGUID" value="" class="hidden">
												<input type="text" name="user_access_id" id="user_access_id" value="" class="hidden">


												<!-- Buttons -->
												<div class="form-group full-inset">
													<button type="button" class="btn btn-primary btn-s-w pull-right mrg-left" id="btn_add_vak_klas_hs">SAVE</button>
													<button class="btn btn-primary btn-s-w pull-right mrg-left hidden" id="btn_update_vak_klas_hs">UPDATE</button>
													<button class="btn btn-s-w pull-right mrg-left" id="btn_clear_vak_klas_hs">CLEAR</button>
													<button class="btn btn-danger btn-s-w pull-right mrg-left hidden" id="btn_delete_vak_klas_hs">DELETE</button>
												</div>
											</fieldset>

										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="loader_spn" class="hidden">
					<div class="loader_spn"></div>
				</div>
			</section>
	</main>
	<?php include 'footer.php'; ?>
</div>

<?php include 'document_end.php';
		} ?>
<!-- PLEASE CAREFULLY WIH THIS "}" THAT CLOSE SECURITY  ELSE IF -->
<script>
	var user_access_id = "";
	var check = null;
	var active_check = true;
	var message_tutor = null;
	$(document).ready(function() {

		$('#userGUID').val(getParam("userGUID"));
		//User Account table
		get_user_name();
		user_detail_hs_account();
		check_docent_is_tutor_or_klas("Docent");
		/* 		$("#cijfers_klassen_lijst").val('');
		 */
		setTimeout(function() {
			$("#cijfers_klassen_lijst option").each(function() {
				if ($(this).val() == '4A') {
					console.log($(this).val());
					$(this).text('4');
				} else if ($(this).val()[0] == '4') {
					$(this).attr('disabled', true);
					$(this).addClass('hidden');
				}
			});
		}, 1500);
	});

	$("#btn_clear_vak_klas_hs").click(function(e) {
		e.preventDefault();
		$("#cijfers_klassen_lijst").val('');
		$("#cijfers_vakken_lijst").val('');
		$("#group").val('');
		$("#is_tutor_yes").prop('checked', false);
		$("#is_tutor_no").prop('checked', false);
		$("#is_tutor_hidden").val('');
		$("#btn_add_vak_klas_hs").removeClass("hidden");
		$("#btn_update_vak_klas_hs").addClass("hidden");
		$("#btn_delete_vak_klas_hs").addClass("hidden");
		console.log("clear")
	})

	$frm_vak_klas_hs = $('#frm_vak_klas_hs'),

		$("#btn_add_vak_klas_hs").click(function(e) {
			e.preventDefault();
			if ($("#cijfers_klassen_lijst").val().length === 0 ||
				($("#cijfers_vakken_lijst").val().length === 0 && $("#group").val().length === 0) ||
				$("input[name='is_tutor']:checked").val().length === 0) {
				$frm_vak_klas_hs.find('.alert-error').removeClass('hidden');
			} else {
				$.ajax({
					url: "ajax/add_vak_klas_hs.php",
					data: $frm_vak_klas_hs.serialize(),
					type: "POST",
					dataType: "text",
					success: function(text) {
						if (text != 1) {
							$frm_vak_klas_hs.find('.alert-error').removeClass('hidden');
						} else {
							$frm_vak_klas_hs.find('#created_suscessfully').removeClass('hidden');
							$('#btn_clear_user').text("CLEAR");
							// Clear all object of form
							$('select').each(function() {
								$(this).val('');
							});

							$('input[type=radio]').each(function() {
								$(this).attr('checked', false);
							});

						}
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
							location.reload();
						}, 3000);
					}
				});
			}
		});
	$("#btn_update_vak_klas_hs").on("click", function(e) {
		e.preventDefault();
		$('#user_access_id').val(user_access_id);
		$.ajax({
			url: "ajax/updateuser_hs_account.php",
			data: $frm_vak_klas_hs.serialize(),
			type: "POST",
			dataType: "text",
			success: function(text) {
				if (text != 1) {
					$frm_vak_klas_hs.find('.alert-error').removeClass('hidden');
				} else {
					$frm_vak_klas_hs.find('#updated_suscessfully').removeClass('hidden');
					window.location.reload();
					$('#btn_clear_user').text("CLEAR");
					$('select').each(function() {
						$(this).val('');
					});
					$('input[type=radio]').each(function() {
						$(this).attr('checked', false);
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
				}, 3000);
			}
		});
	});
	$("#btn_delete_vak_klas_hs").on("click", function(e) {
		e.preventDefault();
		access_id = $('#user_access_id').val();
		var r = confirm("Delete this vak and klas for this docent?");
		if (r) {
			/* begin post */
			$.ajax({
				url: "ajax/deleteuser_hs_vak.php",
				data: "access_id=" + access_id,
				type: "POST",
				dataType: "text",
				success: function(text) {
					if (text != 1) {
						$frm_vak_klas_hs.find('.alert-error').removeClass('hidden');
					} else {
						$frm_vak_klas_hs.find('#deleted_suscessfully').removeClass('hidden');
						window.location.reload();
						$('#btn_clear_user').text("CLEAR");
						$('select').each(function() {
							$(this).val('');
						});
						$('input[type=radio]').each(function() {
							$(this).attr('checked', false);
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
					$('#id_remedial').val("");
					$('#btn_clear_user_hs_account').text("CLEAR");
					setTimeout(function() {
						$(".alert-info").fadeOut(1500);
						$(".alert-error").fadeOut(1500);
						$(".alert-warning").fadeOut(1500);
						$('#deleted_suscessfully').fadeOut(1500);
					}, 3000);
				}
			});
		} else {
			$('#btn_clear_user_hs_account').text("CLEAR");
		}
	});
	$("#btn_clear_user_hs_account").on("click", function(e) {
		$('input[type=text]').each(function() {
			$(this).val('');
		});
		$('.alert').each(function() {
			$(this).addClass('hidden');
		});
	});


	function getParam(key) {
		// Find the key and everything up to the ampersand delimiter
		var value = RegExp("" + key + "[^&]+").exec(window.location.search);

		// Return the unescaped value minus everything starting from the equals sign or an empty string
		return unescape(!!value ? value.toString().replace(/^[^=]+./, "") : "");
	}

	function user_detail_hs_account() {

		var hrefLink = 'ajax/get_detail_user_hs_account.php?userGUID=' + getParam("userGUID")
		$.get(hrefLink, function(data) {
			$('#tbl_detail_account').empty();
			$('#tbl_detail_account').html(data);
		}).done(function() {

		}).fail(function() {
			alert("error");
		});
	}

	function get_user_name() {

		var hrefLink = 'ajax/get_user_info.php?userGUID=' + getParam("userGUID");
		$.get(hrefLink, function(data) {
			$('#user_name_hs').html(data);
		}).done(function() {

		}).fail(function() {
			alert("error");
		});

	}

	function check_tutor(type_check) {
		var c = null;
		var hrefLink = 'ajax/get_check_tutor?klas=' + $("#cijfers_klassen_lijst").val() + '&userGUID=' + getParam('userGUID') + '&type_check=' + type_check;

		$.ajax({
			url: hrefLink,
			type: 'GET',
			async: false,
			cache: false,
			error: function() {
				c = 0;
			},
			success: function(data) {
				c = parseInt(data);
			}
		});
		return c;
	}

	$('#cijfers_klassen_lijst').change(function() {
		$("#loader_spn").removeClass("hidden");
		var klas = $(this).val()[0];
		if (klas == '4') {
			$('.vaken').addClass('hidden');
			$('.group').removeClass('hidden');
			$("#cijfers_vakken_lijst").attr('disabled', true);

		} else {
			$('.vaken').removeClass('hidden');
			$('.group').addClass('hidden');
			$("#cijfers_vakken_lijst").attr('disabled', false);

		}
		console.log(klas);

		check_docent_is_tutor_or_klas("Klas");
		$("#group").val('');
	});


	$('#is_tutor_yes').change(function() {
		if ($(this).prop('checked', true)) {
			$('#is_tutor_hidden').val('Yes');
		}
	})
	$('#is_tutor_no').change(function() {
		if ($(this).prop('checked', true)) {
			$('#is_tutor_hidden').val('No');
		}
	})

	function check_docent_is_tutor_or_klas(type_check) {
		check = check_tutor(type_check);
		if (check == 0) {
			if (!message_tutor) {
				$('#has_tutor').addClass('hidden');
			}
			if (!active_check) {
				$('#is_tutor_hidden').val('Yes');
				$('#is_tutor_no').attr('disabled', true);
				$('#is_tutor_yes').attr('disabled', true);
			} else {
				$('#is_tutor_no').removeAttr('disabled');
				$('#is_tutor_yes').removeAttr('disabled');
			}
		} else {
			$('#has_tutor').removeClass('hidden');
			$('#is_tutor_no').attr('checked', true);
			$('#is_tutor_hidden').val('No');
			$('#is_tutor_no').attr('disabled', true);
			$('#is_tutor_yes').attr('disabled', true);
			if (type_check == "Klas") {
				$('#lbl_has_tutor').text("This Klas already has a mentor");
			} else {
				$('#lbl_has_tutor').text("This Docent is already a mentor");
			}
			active_check = false;
			message_tutor = true;
		}
		$("#loader_spn").addClass("hidden");
	}
</script>