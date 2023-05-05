<?php include 'document_start.php'; ?>

<?php include 'sub_nav.php'; ?>
<?php

$UserRights = $_SESSION["UserRights"];

?>

<div class="push-content-220">
	<?php include 'header.php'; ?>
	<?php $UserRights = $_SESSION['UserRights'];
	if ($UserRights == "ADMINISTRATIE" || $UserRights == "ONDERSTEUNING" || $UserRights == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT") {
		include 'redirect.php';
	} else { ?>
		<main id="main" role="main">
			<section>
				<div class="container container-fs">
					<div class="row">
						<!-- <div class="default-secondary-bg-color col-md-12 inset filter-bar brd-bottom clearfix">

					</div> -->

						<div class="default-secondary-bg-color col-md-12 inset filter-bar brd-bottom clearfix">
							<h1 class="primary-color">Vak:</h1>
							<ol class="breadcrumb">
								<li>School: <label id="lbl_cijfers_schools_by_teacher"></label></li>
								<li>Vak: <label id="lbl_cijfers_vakken_list_teacher"></label></li>
								<li>Klas: <label id="lbl_list_class_teacher"></label></li>
								<li>Rapnr: <label id="lbl_cijfers_rapporten_lijst"></label></li>
							</ol>
							<hr>
							<form id="form-vak" class="form-inline form-data-retriever" name="filter-vak" role="form">
								<input class="hidden" id="cijfers_user_rights" name="cijfers_user_rights" value=<?php echo $UserRights ?>>
								<fieldset>
									<div class="form-group">
										<label>School </label>
										<select id="list_school_teacher" name="list_school_teacher" class="form-control"></select>
									</div>
									<div class="form-group">
										<label for="cijfers_klassen_lijst">Klas</label>
										<select class="form-control" name="list_class_teacher" id="list_class_teacher">
											<!-- Options populated by AJAX get -->
											<!-- <option value="NONE">NONE</option> -->
										</select>
									</div>
									<div class="form-group">
										<label for="cijfers_vakken_lijst">Vak</label>
										<select class="form-control" name="cijfers_vakken_list_teacher" id="cijfers_vakken_list_teacher">
											<!-- Options populated by AJAX get -->
											<!--<option value="NONE">-- Kies een Vak --</option>-->
										</select>
									</div>
									<div class="form-group">
										<label for="cijfers_rapporten_lijst">Rapnr.</label>
										<select class="form-control" name="cijfers_rapporten_lijst" id="cijfers_rapporten_lijst">
											<!-- Options populated by AJAX get -->
											<!-- TEMPORARY ENTERED MANUALLY -->
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
										</select>
									</div>

									<div class="form-group">
										<button id="btn_submit_cijfers" name="btn_submit_cijfers" data-display="data-display" onclick="active_loader()" data-ajax-href="ajax/getvakken_tabel_by_teacher.php" type="submit" class="btn btn-primary btn-m-w btn-m-h">zoeken</button>
										&nbsp&nbsp
										&nbsp&nbsp
									</div>
								</fieldset>
							</form>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 full-inset">
							<div id="loader_spn" class="hidden">
								<div id="loader_spn" class="loader_spn"></div>
							</div>
							<div class="sixth-bg-color brd-full">
								<div class="box">
									<div class="box-content full-inset">
										<div class="full-inset alert alert-warning reset-mrg-bottom">
											<p><i class="fa fa-warning"></i> Selecteer de klas, vak en rapport nummer bovenaan en klik op zoeken.</p>
										</div>
										<div class="data-display"></div>
										<?php include 'modal_cijfers.php'; ?>
									</div>
								</div>
							</div>
						</div>
						<a type="submit" name="btn_cijfers_print" id="btn_cijfers_print" class="btn btn-default btn-m-w pull-right mrg-left" target="_blank">PRINT</a>
						<button id="btn_extra_save_cijfer" name="btn_extra_save_cijfer" class="btn btn-success btn-m-w btn-m-h pull-right"></button>
					</div>
				</div>
			</section>
		</main>
		<?php include 'footer.php'; ?>
</div>
<?php include 'document_end.php';
	} ?> <!-- PLEASE CAREFULLY WIH THIS "}" THAT CLOSE SECURITY  ELSE IF -->

<script>
	var a = JSON.parse(sessionStorage.getItem("extra_ss_cijfer"));
	if (a == null) {
		$("#btn_extra_save_cijfer").text("Ex. Save: " + 0);
	} else {
		$("#btn_extra_save_cijfer").text("Ex. Save: " + (a.length - 1));
	}

	$("#form-vak").submit(function() {
		$("#lbl_cijfers_schools_by_teacher").text($('#list_school_teacher option:selected').html());
		$("#lbl_cijfers_vakken_list_teacher").text($('#cijfers_vakken_list_teacher option:selected').html());
		$("#lbl_list_class_teacher").text($('#list_class_teacher option:selected').html());
		$("#lbl_cijfers_rapporten_lijst").text($('#cijfers_rapporten_lijst option:selected').html());
	});
	$("#btn_cijfers_print").click(function() {

		var list_class_teacher = $('#list_class_teacher option:selected').text();
		var cijfers_vakken_list_teacher = $('#cijfers_vakken_list_teacher').val();
		var cijfers_rapporten_lijst = $('#cijfers_rapporten_lijst').val();

		window.open("print.php?name=cijfers_teacher&title=Cijfers List&list_class_teacher=" + list_class_teacher + "&cijfers_vakken_list_teacher=" + cijfers_vakken_list_teacher + "&cijfers_rapporten_lijst=" + cijfers_rapporten_lijst);


	});
	//SAVE CIJFERSWAARDE (ejaspe - caribeDevelopers)
	function save_cijferswaarde() {

		var
			$klas = $('#list_class_teacher option:selected').text(),
			$schooljaar = $('#schooljaar_cijferswaarde').val(),
			$rapnummer = $('#cijfers_rapporten_lijst').val(),
			$vak = $('#cijfers_vakken_list_teacher').val(),
			$cijferswaarde1 = $('#cijferswaarde1').val(),
			$cijferswaarde2 = $('#cijferswaarde2').val(),
			$cijferswaarde3 = $('#cijferswaarde3').val(),
			$cijferswaarde4 = $('#cijferswaarde4').val(),
			$cijferswaarde5 = $('#cijferswaarde5').val(),
			$cijferswaarde6 = $('#cijferswaarde6').val(),
			$cijferswaarde7 = $('#cijferswaarde7').val(),
			$cijferswaarde8 = $('#cijferswaarde8').val(),
			$cijferswaarde9 = $('#cijferswaarde9').val(),
			$cijferswaarde10 = $('#cijferswaarde10').val(),
			$cijferswaarde11 = $('#cijferswaarde11').val(),
			$cijferswaarde12 = $('#cijferswaarde12').val(),
			$cijferswaarde13 = $('#cijferswaarde13').val(),
			$cijferswaarde14 = $('#cijferswaarde14').val(),
			$cijferswaarde15 = $('#cijferswaarde15').val(),
			$cijferswaarde16 = $('#cijferswaarde16').val(),
			$cijferswaarde17 = $('#cijferswaarde17').val(),
			$cijferswaarde18 = $('#cijferswaarde18').val(),
			$cijferswaarde19 = $('#cijferswaarde19').val(),
			$cijferswaarde20 = $('#cijferswaarde20').val();

		$.post("ajax/add_cijferswaarde.php", {
				klas: $klas,
				schooljaar: $schooljaar,
				rapnummer: $rapnummer,
				vak: $vak,
				cijferswaarde1: $cijferswaarde1,
				cijferswaarde2: $cijferswaarde2,
				cijferswaarde3: $cijferswaarde3,
				cijferswaarde4: $cijferswaarde4,
				cijferswaarde5: $cijferswaarde5,
				cijferswaarde6: $cijferswaarde6,
				cijferswaarde7: $cijferswaarde7,
				cijferswaarde8: $cijferswaarde8,
				cijferswaarde9: $cijferswaarde9,
				cijferswaarde10: $cijferswaarde10,
				cijferswaarde11: $cijferswaarde11,
				cijferswaarde12: $cijferswaarde12,
				cijferswaarde13: $cijferswaarde13,
				cijferswaarde14: $cijferswaarde14,
				cijferswaarde15: $cijferswaarde15,
				cijferswaarde16: $cijferswaarde16,
				cijferswaarde17: $cijferswaarde17,
				cijferswaarde18: $cijferswaarde18,
				cijferswaarde19: $cijferswaarde19,
				cijferswaarde20: $cijferswaarde20
			},
			function(data) {
				var list_class_teacher = $('#list_class_teacher option:selected').text();
				var cijfers_vakken_list_teacher = $('#cijfers_vakken_list_teacher').val();
				var cijfers_rapporten_lijst = $('#cijfers_rapporten_lijst').val();
				var schoolid = $("#list_school_teacher").val();

				var student_id_array = [],
					els = document.getElementsByTagName('input'), // or '*' for all types of element
					i = 0;

				for (i = 0; i < els.length; i++) {
					if (els[i].hasAttribute('studentidarray')) {
						student_id_array.push(els[i].attributes[1].nodeValue);
					}
				}
				for (z = 0; z < student_id_array.length; z++) {
					$.ajax({
						url: "ajax/getgemiddelde_by_cijferwarde.php",
						data: "klas=" + list_class_teacher + "&rapport=" + cijfers_rapporten_lijst + "&vak=" + cijfers_vakken_list_teacher + "&studentid=" + student_id_array[z],
						type: 'POST',
						dataType: "HTML",
						cache: false,
						async: true,
						success: function(data) {}
					});
				}
				/* do something if needed */
				//	console.log(data);
			}).done(function(data) {
			$('#btn_submit_cijfers').trigger('click');

			/* it's done */
			if (data == 1) {

			} else {
				console.log(data);
				// RE-TRY FUNCTION
			};

		}).fail(function() {
			alert('Error, please contact developers.');
		});
	}

	// var	user = '<?php echo $_SESSION["UserRights"] ?>';
	//
	// if (user==='TEACHER'){

	$.ajax({
		url: "ajax/get_list_school_by_teacher.php",
		type: 'POST',
		dataType: "HTML",
		cache: false,
		async: false,
		success: function(data) {
			$("#list_school_teacher").html(data);
		}
	})
	// }
	// else
	// {
	// 	$.ajax({
	// 		url:"ajax/getschools.php",
	// 		type  : 'POST',
	// 		dataType: "HTML",
	// 		cache: false,
	// 		async :false,
	// 		success: function(data){
	// 			$("#list_school_teacher").html(data);
	// 		}
	// 	})
	// }

	$("#list_school_teacher").change(function() {
		var schoolid = $("#list_school_teacher").val();
		$.get("ajax/get_list_class_by_teacher.php?schoolid=" + schoolid, {}, function(data) {
			$("#list_class_teacher").html(data);
		});
	});
	$("#list_class_teacher").change(function() {
		var schoolid = $("#list_school_teacher").val();
		var teacher_class = $("#list_class_teacher option:selected").text();
		$.get("ajax/get_list_vak_by_teacher.php?schoolid=" + schoolid + "&teacher_class=" + teacher_class, {}, function(data) {
			$("#cijfers_vakken_list_teacher").html(data);
		});
	});

	function active_loader() {
		$("#loader_spn").toggleClass('hidden');
	}
</script>