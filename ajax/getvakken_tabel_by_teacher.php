<?php


require_once("../classes/spn_cijfers.php");
require_once("../config/app.config.php");

/*
configuration for the detail table to be shown on screen
the $baseurl  will be used to create the "View Details" link in the table
*/
$baseurl = appconfig::GetBaseURL();


if (session_status() == PHP_SESSION_NONE) {
	session_start();
}
$s = new spn_cijfers();
$_SESSION["SchoolID"] = $_GET["list_school_teacher"];
//CaribeDev
if ($_SESSION["SchoolType"] == 1 && $_SESSION["SchoolID"] != 8 && $_SESSION["SchoolID"] != 18) {
	$cijferswarde = $s->createcijferswaarde_first_time_ps($_GET["list_class_teacher"], $_SESSION["SchoolJaar"], $_GET["cijfers_rapporten_lijst"], $_GET["cijfers_vakken_list_teacher"], 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
	$result = $s->create_le_cijfer_student_ps($_SESSION["SchoolJaar"], $_GET["cijfers_rapporten_lijst"], $_GET["list_class_teacher"], $_SESSION["SchoolID"], $_GET["cijfers_vakken_list_teacher"]);
} else {
	$cijferswarde = $s->createcijferswaarde_first_time($_GET["list_class_teacher"], $_SESSION["SchoolID"], $_SESSION["SchoolJaar"], $_GET["cijfers_rapporten_lijst"], $_GET["cijfers_vakken_list_teacher"], 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
	$result = $s->create_le_cijfer_student($_SESSION["SchoolJaar"], $_GET["cijfers_rapporten_lijst"], $_GET["list_class_teacher"], $_SESSION["SchoolID"], $_GET["cijfers_vakken_list_teacher"]);
}

if ($_SESSION['SchoolType'] == 2 || $_SESSION['SchoolID'] == 8 || $_SESSION['SchoolID'] == 11 || $_SESSION['SchoolID'] == 18) {
	print $s->listcijfers_hs($_SESSION["SchoolID"], $_GET["list_class_teacher"], $_GET["cijfers_vakken_list_teacher"], $_GET["cijfers_rapporten_lijst"], "", $_SESSION["SchoolJaar"]);
} else {
	print $s->listcijfers($_SESSION["SchoolID"], $_GET["list_class_teacher"], $_GET["cijfers_vakken_list_teacher"], $_GET["cijfers_rapporten_lijst"], "", $_SESSION["SchoolJaar"]);
}


?>
<script>
	$(document).ready(function() {
		$("#loader_spn").toggleClass('hidden');

		function calculate_gemiddelde() {

			var $total = 0.00;
			var $count_t = 0;

			for (var $c = 1; $c <= 21; $c++) {

				var $gemiddeld = 0.00;
				var $count = 0;

				$('#lblName1[data-cijfer="c' + $c + '"]').each(function() {

					if ($(this).text() != null && $(this).text() != "" && $(this).text() != "0.0") {
						$gemiddeld = $gemiddeld + parseFloat($(this).text());
						$count++;
					}

				});
				if ($gemiddeld > 0 && $count > 0) {
					$total = $total + Math.round(($gemiddeld / $count) * 10) / 10;
					$count_t++;
				}


				$('#gemiddeld_' + $c).text(Math.round(($gemiddeld / $count) * 10) / 10);
				if ((Math.round(($gemiddeld / $count) * 10) / 10) < 5.5) {
					$('#gemiddeld_' + $c).parent('td').removeClass();
					$('#gemiddeld_' + $c).parent('td').addClass('quaternary-bg-color default-secondary-color');
				}
				if ($('#gemiddeld_' + $c).text() === "NaN") {
					$('#gemiddeld_' + $c).text("");
				}

			}

			if ($count_t)
				$('#gemiddeld_total').text(Math.round(($total / $count_t) * 10) / 10);
			else
				$('#gemiddeld_total').text(0);

		}

		if ($('.lblopmerking').length) {
			var $laatField = $('.telaat-field').is(':checked'),
				$absentField = $('.absentie-field').is(':checked'),
				$toetsInhalenField = $('.toetsinhalen-field').is(':checked'),
				$uitsturenField = $('.uitsturen-field').is(':checked'),
				$lpField = $('.lp-field').val(),
				$geenHuiswerkField = $('.geenhuiswerk-field').is(':checked'),
				$opmerking = $('.opmerking-input').val(),
				$studenteID = $(this).parent('tr').attr('data-student-id');

			$.post("ajax/update_verzuim.php", {
				telaat: $laatField,
				absent: $absentField,
				toetsinhalen: $toetsInhalenField,
				uitsturen: $uitsturenField,
				LP: $lpField,
				geenhuiswerk: $geenHuiswerkField,
				opmerking: $opmerking
			}, function(data) {
				/* do something if needed */
			}).done(function(data) {
				/* it's done */
				if (data == 1) {

				} else {
					// RE-TRY FUNCTION
				};
			}).fail(function() {
				alert('Error, please contact developers.');
			});
		}

		var n = $(this).val();

		if ((n > 0) && (n <= 10) && $(this).val() != '' && $(this).val() != '0.0') {

			$(this).closest('td').removeClass('error');

			$(this).closest('tr').addClass('active');

			$(this).hide();
			$(this).prev().html($(this).val());
			$(this).prev().show();

			var $studentNr = $(this).prev().attr('data-student-id'),
				$column = $(this).prev().attr('data-column'),
				$row = $(this).prev().attr('data-row'),
				$cijferName = $(this).prev().attr('data-cijfer'),
				$klas = $(this).prev().attr('data-klas'),
				$vak = $(this).prev().attr('data-vak'),
				$rapport = $(this).prev().attr('data-rapport'),
				$cijfer = $(this).val();

			// POST TO UPDATE 'CIJFERS'
			$.post("ajax/update_cijfers.php", {
				studentid: $studentNr,
				cijfername: $cijferName,
				cijfervalue: $cijfer,
				klas: $klas,
				rapport: $rapport,
				vak: $vak
			}, function(data) {
				/* do something if needed */
			}).done(function() {
				/*
				 *** When updated is done, get the 'gemiddelde' from the back-end
				 *** Update the 'gemiddelde' from the current row that is updated
				 */

				// POST TO UPDATE 'GEMIDDELDE'
				$.post("ajax/getgemiddelde.php", {
					studentid: $studentNr,
					klas: $klas,
					rapport: $rapport,
					vak: $vak
				}, function(data) {

				}).done(function(data) {
					/*
					 ** Update the last td in the current tr
					 */
					$('#vak').find('tr.active td:last-child').html(data);
					$('#vak').find('tr.active').removeClass('active');
				}).fail(function() {
					alert('Error, please contact developers.');
				});


			}).fail(function() {
				alert('Error, please contact developers.');
			});

			/* Original codeblock by Rudy */
			// POST TO UPDATE 'GEMIDDELDE'
			//$.post( "ajax/getgemiddelde.php", {studentid: $studentNr, klas: $klas, rapport : $rapport, vak : $vak }, function(data) {
			//
			//}).done(function(data) {
			//   /*
			//    ** Update the last td in the current tr
			//    */
			//    $('#vak').find('tr.active td:last-child').html(data);
			//    $('#vak').find('tr.active').removeClass('active');
			//}).fail(function() {
			//    alert('Error, please contact developers.');
			//});



		}
		//CALCULATE FOOT GEMIDDELDE (ejaspe - caribeDevelopers)

		calculate_gemiddelde();
	});
</script>