<?php


require_once("../classes/spn_leerling.php");
require_once("../config/app.config.php");

/*
configuration for the detail table to be shown on screen
the $baseurl & $detailpage will be used to create the "View Details" link in the table
*/
$baseurl = appconfig::GetBaseURL();
$detailpage = "leerlingdetail.php";


if (session_status() == PHP_SESSION_NONE) {
	session_start();
}
/* check for class session variable only if userrights is a teacher */
if (isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"])) {
	if ($_SESSION["UserRights"] == "DOCENT" && $_SESSION["UserRights"] != "ASSISTENT") {
		if (isset($_SESSION["Class"])) {
			$s = new spn_leerling();
			print $s->liststudents($_SESSION["SchoolID"], $_SESSION["Class"], $baseurl, $detailpage);
		}
	} else if ($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING") {
		$s = new spn_leerling();
		print $s->liststudents($_SESSION["SchoolID"], "ALL", $baseurl, $detailpage);
	}
}


?>
<script type="text/javascript">
	$(function() {

		$('#dataRequest-student tr').click(function() {


			$('#student-klas').attr('disabled', 'disabled');

			if ($('#selected_id_student_row').val() != $(this).find("td").eq(6).find("[name='id']").val()) {
				$(this).closest('tr').siblings().children('td, th').css('background-color', '');
				$(this).children('td, th').css('background-color', '#cccccc');
				$('#selected_id_student_row').val($(this).find("td").eq(6).find("[name='id']").val());
				$('#btn-clear-student-selected').show();

				$('#student_id').val($(this).find("td").eq(6).find("[name='id']").val());

				$('#studentnummer').val($(this).find("td").eq(0).text());
				$('#voornamen').val($(this).find("td").eq(1).text());
				$('#achternaam').val($(this).find("td").eq(2).text());

				$('#student-klas').val($(this).find("td").eq(5).text());
				klas = $(this).find("td").eq(5).text();
				if (klas.charAt(0) == '3') {
					$('.profiel').show();
				} else {
					$('.profiel').hide();
				}
				console.log($(this).find("td").eq(6).find("[name='profiel']").val())
				$('#geboorteplaats').val($(this).find("td").eq(6).find("[name='birthplace']").val());
				$('#geslacht').val($(this).find("td").eq(3).text());
				$('#profiel').val($(this).find("td").eq(6).find("[name='profiel']").val());

				$('#geboortedatum').val($(this).find("td").eq(4).text());

				$('#telefoon').val($(this).find("td").eq(6).find("[name='phone1']").val());
				$('#adres').val($(this).find("td").eq(6).find("[name='address']").val());

				$('#telefoonnoodgeval').val($(this).find("td").eq(6).find("[name='phone3']").val());

				$('#nationaliteiten').val($(this).find("td").eq(6).find("[name='nationaliteit']").val());

				$('#identeits').val($(this).find("td").eq(6).find("[name='idnumber']").val());

				$('#anders').val($(this).find("td").eq(6).find("[name='anders']").val());

				$('#azv_nr').val($(this).find("td").eq(6).find("[name='azvnumber']").val());


				$('#bizjonder').val($(this).find("td").eq(6).find("[name='bijzondermedischeindicatie']").val());

				$('#huisarts').val($(this).find("td").eq(6).find("[name='huisarts']").val());

				$('#telnr').val($(this).find("td").eq(6).find("[name='huisartsnr']").val());

				$('#rooepnaam').val($(this).find("td").eq(6).find("[name='roepnaam']").val());
				$('#nationaliteiten').val($(this).find("td").eq(6).find("[name='nationaliteit']").val());
				$('#email').val($(this).find("td").eq(6).find("[name='email']").val());
				$('#status').val($(this).find("td").eq(6).find("[name='status']").val());
				$('#expiredatum').val($(this).find("td").eq(6).find("[name='azvexpiredate']").val());
				$('#datuminschrijving').val($(this).find("td").eq(6).find("[name='created']").val());
				$('#tellthuis').val($(this).find("td").eq(6).find("[name='phone2']").val());
				$('#vorigeschool').val($(this).find("td").eq(6).find("[name='vorigeschool']").val());
				$('#verzorgernaschooltijd').val($(this).find("td").eq(6).find("[name='vezorger']").val());
				$('#voorletter').val($(this).find("td").eq(6).find("[name='voorletter']").val());
				$('#securepin').val($(this).find("td").eq(6).find("[name='securepin']").val());
				$('#leerling_family').val($(this).find("td").eq(6).find("[name='id_family']").val());
				$('#datum_inschrijving').val($(this).find("td").eq(6).find("[name='enrollmentdate']").val().substring(0, 10));
				$('#vo_val').val($(this).find("td").eq(6).find("[name='vo']").val());
				$('#datum_uitschijving').val($(this).find("td").eq(6).find("[name='outschooldate']").val().substring(0, 10));


				if ($(this).find("td").eq(6).find("[name='ne']").val() == 1) {
					$('#_ned_val').prop('checked', true);
					$('#ned_val').val(1);

				} else {
					$('#_ned_val').prop('checked', false);
					$('#ned_val').val(0)
				}



				if ($(this).find("td").eq(6).find("[name='pa']").val() == 1) {
					$('#_pap_val').prop('checked', true);
					$('#pap_val').val(1);
				} else {
					$('#_pap_val').prop('checked', false);
					$('#pap_val').val(0);
				}



				if ($(this).find("td").eq(6).find("[name='en']").val() == 1) {
					$('#_en_val').prop('checked', true);
					$('#en_val').val(1);
				} else {
					$('#_en_val').prop('checked', false);
					$('#en_val').val(0);
				}


				if ($(this).find("td").eq(6).find("[name='sp']").val() == 1) {
					$('#_sp_val').prop('checked', true);
					$('#sp_val').val(1);
				} else {
					$('#_sp_val').prop('checked', false);
					$('#sp_val').val(0);
				}

				// Medische
				if ($(this).find("td").eq(6).find("[name='spraak']").val() == 1) {
					$('#_spraak_val').prop('checked', true);
					$('#spraak_val').val(1);
				} else {
					$('#_spraak_val').prop('checked', false);
				}
				if ($(this).find("td").eq(6).find("[name='gehoor']").val() == 1) {
					$('#_gehoor_val').prop('checked', true);
					$('#gehoor_val').val(1);
				} else {
					$('#_gehoor_val').prop('checked', false);
				}
				if ($(this).find("td").eq(6).find("[name='gezicht']").val() == 1) {
					$('#_gezicht_val').prop('checked', true);
					$('#gezicht_val').val(1);
				} else {
					$('#_gezicht_val').prop('checked', false);
				}
				if ($(this).find("td").eq(6).find("[name='motoriek']").val() == 1) {
					$('#_motoriek_val').prop('checked', true);
					$('#motoriek_val').val(1);
				} else {
					$('#_motoriek_val').prop('checked', false);
				}

				$('#notes').val($(this).find("td").eq(6).find("[name='notasval']").val());

			} else {
				$('#student_id').val("");
				$(this).closest('tr').siblings().children('td, th').css('background-color', '');
				$(this).children('td, th').css('background-color', $("#selected_color").val());
				$("#selected_id_student_row").val("");
				$("#selected_color").val("");
				$('#btn-clear-student-selected').fadeOut();

				$('input[type=text]').each(function() {
					$(this).val("");
				});
			}



		});

	}); //End Function
</script>