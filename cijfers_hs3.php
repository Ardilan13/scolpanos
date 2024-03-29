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
							<h1 class="primary-color">Vak:</h1>
							<?php include 'breadcrumb.php'; ?>
						</div>
						<div class="default-secondary-bg-color col-md-12 inset filter-bar brd-bottom clearfix">
							<form id="form-vak" class="form-inline form-data-retriever" name="filter-vak" role="form">
								<fieldset>
									<div class="form-group">
										<label for="cijfers_klassen_lijst">Group</label>
										<select class="form-control" name="cijfers_group_lijst" id="cijfers_group_lijst">
											<!-- Options populated by AJAX get -->
											<!-- <option value="NONE">NONE</option> -->
										</select>
										<input class='hidden'name= 'cijfers_group_name' id='cijfers_group_name' value="">
									</div>
									<!-- <div class="form-group">
										<label for="cijfers_vakken_lijst">Vak</label>
										<select class="form-control" name="cijfers_vakken_lijst" id="cijfers_vakken_lijst">

										</select>
									</div> -->
									<div class="form-group">
										<label for="cijfers_rapporten_lijst">Rapnr.</label>
										<select class="form-control" name="cijfers_rapporten_lijst" id="cijfers_rapporten_lijst">
											<!-- Options populated by AJAX get -->
											<!-- TEMPORARY ENTERED MANUALLY -->
											<option value="1" >1</option>
											<option value="2" >2</option>
											<option value="3" >3</option>
										</select>
									</div>
									<div class="form-group">
										<button id="btn_submit_cijfers" name="btn_submit_cijfers" onclick="active_loader()" data-display="data-display" data-ajax-href="ajax/getvakken_tabel_for_groups.php" type="submit" class="btn btn-primary btn-m-w btn-m-h">zoeken</button>
									</div>
									<ol class="breadcrumb">
										&nbsp&nbsp
										<li>Klas:	<label id="lbl_cijfers_klassen_lijst"></label></li>
										<li>Vak: <label id="lbl_cijfers_vakken_lijst"></label></li>
										<li>Rapnr: <label id="lbl_cijfers_rapporten_lijst"></label></li>
									</ol>
								</fieldset>
							</form>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 full-inset">
							<div id="loader_spn" class="hidden"><div class="loader_spn"></div></div>
							<div class="sixth-bg-color brd-full">
								<div class="box">
									<div class="box-content full-inset">
										<div class="full-inset alert alert-warning reset-mrg-bottom">
											<p><i class="fa fa-warning"></i> Selecteer de klas, vak en rapport nummer bovenaan en klik op zoeken.</p>
										</div>
										<div id="table_cijfer" class="data-display"></div>
										<?php include 'modal_cijfers.php'; ?>
									</div>
								</div>
							</div>
						</div>
						<a type="submit" name="btn_cijfers_print" id="btn_cijfers_print"class="btn btn-default btn-m-w pull-right mrg-left" target="_blank">PRINT</a>
						<button id="btn_extra_save_cijfer" name="btn_extra_save_cijfer" class="btn btn-success btn-m-w btn-m-h pull-right"></button>
					</div>
				</div>
			</section>
		</main>
		<?php include 'footer.php'; ?>
	</div>
<?php include 'document_end.php';}?> <!-- PLEASE CAREFULLY WIH THIS "}" THAT CLOSE SECURITY  ELSE IF -->
<script>

$( document ).ready(function() {


	$.ajax({
		url: "ajax/get_list_groups_by_school.php",
		type: "GET",
		dataType: "HTML",
		async: false,
		success: function(data) {
			$("#cijfers_group_lijst").append(data);
		}
	});
	$('#cijfers_group_name').val($('#cijfers_group_lijst').find(":selected").text());
});
	var a = JSON.parse(sessionStorage.getItem("extra_ss_cijfer"));
	if (a == null) {
		$("#btn_extra_save_cijfer").text("Ex. Save: " + 0);
	}
	else {
		$("#btn_extra_save_cijfer").text("Ex. Save: " + (a.length - 1));
	}

	$("#form-vak").submit(function () {
		$("#lbl_cijfers_klassen_lijst").text($('#cijfers_klassen_lijst option:selected').html());
		$("#lbl_cijfers_vakken_lijst").text($('#cijfers_vakken_lijst option:selected').html());
		$("#lbl_cijfers_rapporten_lijst").text($('#cijfers_rapporten_lijst option:selected').html());
	});
	$("#btn_cijfers_print").click(function () {
		var cijfers_klassen_lijst = $("#cijfers_klassen_lijst option:selected").val(),
			cijfers_vakken_lijst = $("#cijfers_vakken_lijst option:selected").val(),
			cijfers_rapporten_lijst = $("#cijfers_rapporten_lijst option:selected").val();

		window.open("print.php?name=cijfers&title=Cijfers List&cijfers_klassen_lijst=" + cijfers_klassen_lijst + "&cijfers_vakken_lijst=" + cijfers_vakken_lijst + "&cijfers_rapporten_lijst=" + cijfers_rapporten_lijst);


	});
	//SAVE CIJFERSWAARDE (ejaspe - caribeDevelopers)
	function save_cijferswaarde() {

		var
			$klas = $('#cijfers_klassen_lijst').val(),
			$schooljaar = "<?php echo $_SESSION["SchoolJaar"];?>";
		$rapnummer = $('#cijfers_rapporten_lijst').val(),
			$vak = $('#cijfers_vakken_lijst').val(),
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
			function (data) {
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
						data: "klas=" + $klas + "&rapport=" + $rapnummer + "&vak=" + $vak + "&studentid=" + student_id_array[z],
						type: 'POST',
						dataType: "HTML",
						cache: false,
						async: true,
						success: function (data) {
						}
					});
				}
				/* do something if needed */
				//	console.log(data);
			}).done(function (data) {

				$('#btn_submit_cijfers').trigger('click');

				/* it's done */
				if (data == 1) {

				} else {
					//	console.log(data);
					// RE-TRY FUNCTION
				};

			}).fail(function () {
				alert('Error, please contact developers.');
			});
	}
	function active_loader() {
		$("#loader_spn").toggleClass('hidden');
		/*document.getElementById("btn_submit_cijfers").disabled = true;*/
	}




$('#cijfers_group_lijst').change(function() {
	$('#cijfers_group_name').val($('#cijfers_group_lijst').find(":selected").text());
});





</script>
