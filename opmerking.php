<?php include 'document_start.php'; ?>

<?php include 'sub_nav.php'; ?>
<div class="push-content-220">
	<?php include 'header.php'; ?>
	<?php $UserRights = $_SESSION['UserRights'];
	if ($UserRights == "ADMINISTRATIE" || $UserRights == "ONDERSTEUNING" || $UserRights == "TEACHER") {
		include 'redirect.php';
	} else { ?>
		<?php
		require_once("classes/spn_setting.php");
		$s = new spn_setting();
		$s->getsetting_info($_SESSION['SchoolID'], false);

		echo "<input hidden name='setting_rapnumber_1_val' id='setting_rapnumber_1_val' value='" . $s->_setting_rapnumber_1 . "'>";
		echo "<input hidden name='setting_rapnumber_2_val' id='setting_rapnumber_2_val' value='" . $s->_setting_rapnumber_2 . "'>";
		echo "<input hidden name='setting_rapnumber_3_val' id='setting_rapnumber_3_val' value='" . $s->_setting_rapnumber_3 . "'>";

		?>
		<main id="main" role="main">
			<section>
				<div class="container container-fs">

					<div class="row">
						<div class="default-secondary-bg-color col-md-12 full-inset brd-bottom clearfix">
							<h1 class="primary-color">Opmerking</h1>
							<?php include 'breadcrumb.php'; ?>
						</div>
						<div class="default-secondary-bg-color col-md-12 full-inset filter-bar brd-bottom clearfix">
							<form id="form_opmerking" class="form-inline form-data-retriever" name="form_opmerking" role="form">
								<fieldset>
									<div class="form-group">
										<label for="klas">Klas</label>
										<select class="form-control" name="houding_klassen_lijst" id="houding_klassen_lijst">
											<option value="NONE">NONE</option>
											<!-- Options populated by AJAX get -->
										</select>
									</div>
									<div class="form-group">
										<label for="klas">Student</label>
										<select class="form-control" id="opmerking_student_name" name="opmerking_student_name">

										</select>
									</div>
									<!-- <div class="form-group">
								<button data-display="data-display" data-ajax-href="ajax/gethouding_opmerking.php" type="submit" class="btn btn-primary btn-m-w btn-m-h">zoeken</button>
							</div> -->
								</fieldset>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12 full-inset">
							<div class="sixth-bg-color brd-full">
								<div class="box">
									<div class="box-content full-inset">
										<div class="col-md-2">
											<textarea class="form-control hidden" name="control_texarea" id="control_texarea"></textarea>
											<label for="klas">Opmerking rap 1:</label>
										</div>
										<div class="form-group">
											<div class="col-md-8">
												<textarea class="form-control" name="opmerking_1" id="opmerking_1"></textarea>
											</div>
										</div>
										<div class="col-md-2">
											<label for="klas">Opmerking rap 2:</label>
										</div>
										<div class="form-group">
											<div class="col-md-8">
												<textarea class="form-control" name="opmerking_2" id="opmerking_2"></textarea>
											</div>
										</div>
										<div class="col-md-2">
											<label for="klas">Opmerking rap 3:</label>
										</div>
										<div class="form-group">
											<div class="col-md-8">
												<textarea class="form-control" name="opmerking_3" id="opmerking_3"></textarea>
											</div>
										</div>
										<div class="col-md-2">
											<label for="klas">Advies:</label>
										</div>
										<?php if ($_SESSION["SchoolType"] == 1) { ?>
											<div class="form-group">
												<div class="col-md-8">
													<select name="advies" id="advies" style="width: 100%;height: 25px;font-size: 14px;">
														<option value=""></option>
														<option value="0">Bevorderd naar klas</option>
														<option value="1">Over wegens leeftijd naar klas</option>
														<option value="2">Niet bevorderd</option>
														<option value="3">Verwezen naar</option>
													</select>
												</div>
											</div>
											<div class="col-md-2 advies_text" style="display:none;">
												<label for="klas">Extra:</label>
											</div>
											<div class="form-group advies_text" style="display:none;">
												<div class="col-md-8">
													<input type="text" class="form-control" name="extra" id="extra" disabled>
												</div>
											</div>
										<?php } ?>

										<div class="form-group pull-right">
											<div class="col-md-12">
												<button type="submit" class="btn btn-primary btn-m-w btn-m-h" id="save_opmerking" name="save_opmerking">Save</button>
											</div>
										</div>
										<br>
										<br>
										<div class="data-display" style="overflow-x:auto"></div>
									</div>
								</div>
							</div>
							<br />
							<!-- <div class="form-group pull-right">
						<div class="col-md-12">
						<button data-display="data-display" data-ajax-href="ajax/gethouding_omperking.php" type="submit" class="btn btn-primary btn-m-w btn-m-h">Save</button>
					</div>
				</div> -->

							<a type="submit" name="btn_houding_print" id="btn_houding_print" class="btn btn-default btn-m-w pull-right mrg-left" target="_blank">PRINT</a>
						</div>
					</div>
					</form>
				</div>
</div>
</section>
</main>
<?php include 'footer.php'; ?>
</div>

<?php include 'document_end.php';
	} ?> <!-- PLEASE CAREFULLY WIH THIS "}" THAT CLOSE SECURITY  ELSE IF -->

<script>
	$(document).ready(function() {
		add_disable_opmerking();
	});

	function add_disable_opmerking() {
		if ($("#setting_rapnumber_1_val").val() == "0") {
			$("#opmerking_1").attr('disabled', 'disabled');
		}
		// if ($("#setting_rapnumber_2_val").val()=="0") {
		// 	$("#opmerking_2").attr('disabled','disabled');
		// }
		// if ($("#setting_rapnumber_3_val").val()=="0") {
		// 	$("#opmerking_3").attr('disabled','disabled');
		// }
	}

	$("#btn_houding_print").click(function() {
		window.open("print.php?name=opmerking&title=Opmerking&klas=" + $("#houding_klassen_lijst option:selected").val() + "&student=" + $("#opmerking_student_name option:selected").val());
	});

	$("#houding_klassen_lijst").change(function() {
		var varCClass = $("#houding_klassen_lijst option:selected").val();
		$.post("ajax/getliststudentbyclass.php", {
				class: varCClass
			},
			function(data) {
				$("#opmerking_student_name").html(data);
			});
		var klas = varCClass.charAt(0);
		klas = parseInt(klas) + 1;
		if (klas < 7) {
			$("#advies option[value='0']").html("Bevorderd naar klas " + klas);
			$("#advies option[value='1']").html("Over wegens leeftijd naar klas " + klas);
		} else {
			$("#advies option[value='0']").html("Bevorderd");
			$("#advies option[value='1']").html("Over wegens leeftijd");
		}

	});

	$("#advies").change(function() {
		if ($("#advies option:selected").val() == "3") {
			$(".advies_text").show();
			$("#extra").removeAttr("disabled");
			$("#extra").val('');
		} else {
			$(".advies_text").hide();
			$("#extra").attr('disabled', 'disabled');
			$("#extra").val('');
		}
	});

	$("#save_opmerking").click(function() {
		$('#opmerking_1').removeAttr("disabled");
		$('#opmerking_2').removeAttr("disabled");
		$('#opmerking_3').removeAttr("disabled");


		$.ajax({
			url: "ajax/add_opmerking.php",
			data: $('#form_opmerking').serialize(),
			type: "POST",
			dataType: "text",
			success: function(text) {
				if (text == 1) {
					alert('Opmerking Successfully Saved!');
				} else {
					alert('Error!');
				}
				console.log(text)
			},
			error: function(xhr, status, errorThrown) {
				console.log("error");
			},
			complete: function(xhr, status) {
				$('html, body').animate({
					scrollTop: 0
				}, 'fast');
				add_disable_opmerking();
			}
		});
	});

	$("#opmerking_student_name").change(function() {
		$('#opmerking_1').text("");
		$('#opmerking_1').val("");
		$('#opmerking_2').text("");
		$('#opmerking_2').val("");
		$('#opmerking_3').text("");
		$('#opmerking_3').val("");
		$("#advies option[value='']").attr("selected", true);
		$("#advies option[value='0']").attr("selected", false);
		$("#advies option[value='1']").attr("selected", false);
		$("#advies option[value='2']").attr("selected", false);
		$("#advies option[value='3']").attr("selected", false);
		$(".advies_text").hide();
		$("#extra").attr('disabled', 'disabled');
		$("#extra").val('');
		$.ajax({
			url: "ajax/get_opmerking_by_student.php",
			data: $('#form_opmerking').serialize(),
			type: "POST",
			dataType: "json",
			success: function(data) {
				if (data != 0) {
					// alert(text);
					$('#opmerking_1').text(data[0].opmerking_1);
					$('#opmerking_1').val(data[0].opmerking_1);
					$('#opmerking_2').text(data[0].opmerking_2);
					$('#opmerking_2').val(data[0].opmerking_2);
					$('#opmerking_3').text(data[0].opmerking_3);
					$('#opmerking_3').val(data[0].opmerking_3);
					if (data[0].advies != '0' && data[0].advies != '1' && data[0].advies != '2' && data[0].advies != '') {
						$("#advies option[value='']").attr("selected", false);
						$("#advies option[value='3']").attr("selected", true);
						$(".advies_text").show();
						$("#extra").removeAttr("disabled");
						$("#extra").val(data[0].advies);
					} else {
						$(".advies_text").hide();
						$("#advies option[value='']").attr("selected", false);
						$("#advies option[value=" + data[0].advies + "]").attr("selected", true);
					}
				} else {
					$('#opmerking_1').text("");
					$('#opmerking_1').val("");
					$('#opmerking_2').text("");
					$('#opmerking_2').val("");
					$('#opmerking_3').text("");
					$('#opmerking_3').val("");
					$("#advies option[value='']").attr("selected", true);
				}

			},
			error: function(xhr, status, errorThrown) {
				console.log("error");
			},
			complete: function(xhr, status) {}
		});
	});
</script>