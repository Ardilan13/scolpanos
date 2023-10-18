<?php


require_once("../classes/spn_cijfers.php");
require_once("../classes/spn_user_hs_account.php");
require_once("../config/app.config.php");

/*
	configuration for the detail table to be shown on screen
	the $baseurl  will be used to create the "View Details" link in the table
*/
$baseurl = appconfig::GetBaseURL();
$table = '';


if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

$u = new spn_user_hs_account;
$IsTutor = $u->check_mentor_in_klas($_GET['cijfers_klassen_lijst'], $_SESSION["UserGUID"], "Klas", appconfig::GetDummy());
$IsTutorinVak = $u->check_mentor_in_klas_and_vak($_GET['cijfers_klassen_lijst'], $_SESSION["UserGUID"], $_GET["cijfers_vakken_lijst"], appconfig::GetDummy());
$IsMyVak = $u->check_is_docent_vak($_GET['cijfers_klassen_lijst'], $_SESSION["UserGUID"], $_GET["cijfers_vakken_lijst"], appconfig::GetDummy());

if (isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"]) && isset($_GET["cijfers_klassen_lijst"]) && (isset($_GET["cijfers_vakken_lijst"]) || isset($_GET['group'])) && isset($_GET["cijfers_rapporten_lijst"])) {
	if ($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT") {
		$s = new spn_cijfers();
		if ($_GET['cijfers_klassen_lijst'] == '4') {
			$cijferswarde = $s->createcijferswaarde_first_time_group($_SESSION["SchoolID"], $_SESSION["SchoolJaar"], $_GET["cijfers_rapporten_lijst"], $_GET["group"], 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
			$result = $s->create_le_cijfer_student_group($_SESSION["SchoolJaar"], $_GET["cijfers_rapporten_lijst"], $_GET["cijfers_klassen_lijst"], $_SESSION["SchoolID"], $_GET["group"]);
			$table = print_vakken_table();
			print $table;
		} else {
			if (isset($_SESSION["Class"])) {
				//CaribeDev
				if ($_SESSION["SchoolType"] == 1 && $_SESSION["SchoolID"] != 8 && $_SESSION["SchoolID"] != 18) {
					$cijferswarde = $s->createcijferswaarde_first_time_ps($_GET["cijfers_klassen_lijst"], $_SESSION["SchoolJaar"], $_GET["cijfers_rapporten_lijst"], $_GET["cijfers_vakken_lijst"], 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
					$result = $s->create_le_cijfer_student_ps($_SESSION["SchoolJaar"], $_GET["cijfers_rapporten_lijst"], $_GET["cijfers_klassen_lijst"], $_SESSION["SchoolID"], $_GET["cijfers_vakken_lijst"]);
				} else {
					$cijferswarde = $s->createcijferswaarde_first_time($_GET["cijfers_klassen_lijst"], $_SESSION["SchoolID"], $_SESSION["SchoolJaar"], $_GET["cijfers_rapporten_lijst"], $_GET["cijfers_vakken_lijst"], 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
					$result = $s->create_le_cijfer_student($_SESSION["SchoolJaar"], $_GET["cijfers_rapporten_lijst"], $_GET["cijfers_klassen_lijst"], $_SESSION["SchoolID"], $_GET["cijfers_vakken_lijst"]);
				}

				/* if ($IsTutor >= 1 && $_SESSION["SchoolType"] != 1) {
					if ($IsMyVak == 1) {
						// print $s->listcijfers($_SESSION["SchoolID"],$_GET["cijfers_klassen_lijst"],$_GET["cijfers_vakken_lijst"],$_GET["cijfers_rapporten_lijst"],"",$_SESSION["SchoolJaar"]);
						$table = print_vakken_table();
						print $table;
					} else {
						print $s->listcijfers_for_tutor($_SESSION["SchoolID"], $_GET["cijfers_klassen_lijst"], $_GET["cijfers_vakken_lijst"], $_GET["cijfers_rapporten_lijst"], "", $_SESSION["SchoolJaar"]);
					}
				} else {
					// print $s->listcijfers($_SESSION["SchoolID"],$_GET["cijfers_klassen_lijst"],$_GET["cijfers_vakken_lijst"],$_GET["cijfers_rapporten_lijst"],"",$_SESSION["SchoolJaar"]);
					$table = print_vakken_table();
					print $table;
				} */
				$table = print_vakken_table();
				print $table;
			}
		}
	} else if ($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING") {
		$s = new spn_cijfers();

		//CaribeDev
		if ($_GET['cijfers_klassen_lijst'] == '4') {
			$cijferswarde = $s->createcijferswaarde_first_time_group($_SESSION["SchoolID"], $_SESSION["SchoolJaar"], 4, $_GET["group"], 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
			$result = $s->create_le_cijfer_student_group($_SESSION["SchoolJaar"], 4, $_GET["cijfers_klassen_lijst"], $_SESSION["SchoolID"], $_GET["group"]);
			$table = print_vakken_table();
			print $table;
		} else if ($_SESSION["SchoolType"] == 1 && $_SESSION["SchoolID"] != 8 && $_SESSION["SchoolID"] != 18) {
			$cijferswarde = $s->createcijferswaarde_first_time_ps($_GET["cijfers_klassen_lijst"], $_SESSION["SchoolJaar"], $_GET["cijfers_rapporten_lijst"], $_GET["cijfers_vakken_lijst"], 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
			$result = $s->create_le_cijfer_student_ps($_SESSION["SchoolJaar"], $_GET["cijfers_rapporten_lijst"], $_GET["cijfers_klassen_lijst"], $_SESSION["SchoolID"], $_GET["cijfers_vakken_lijst"]);
			$table = print_vakken_table();
		} else {
			$cijferswarde = $s->createcijferswaarde_first_time($_GET["cijfers_klassen_lijst"], $_SESSION["SchoolID"], $_SESSION["SchoolJaar"], $_GET["cijfers_rapporten_lijst"], $_GET["cijfers_vakken_lijst"], 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
			$result = $s->create_le_cijfer_student($_SESSION["SchoolJaar"], $_GET["cijfers_rapporten_lijst"], $_GET["cijfers_klassen_lijst"], $_SESSION["SchoolID"], $_GET["cijfers_vakken_lijst"]);
			$table = print_vakken_table();
		}

		print $table;
	}
}

function print_vakken_table()
{
	$s = new spn_cijfers();
	if ($_SESSION['SchoolType'] == 2 || $_SESSION['SchoolID'] == 8 || $_SESSION['SchoolID'] == 18) {
		if ($_GET["cijfers_klassen_lijst"] == '4') {
			print $s->listcijfers_group($_SESSION["SchoolID"], $_GET["cijfers_klassen_lijst"], $_GET["group"], 4, "", $_SESSION["SchoolJaar"]);
		} else {
			print $s->listcijfers_hs($_SESSION["SchoolID"], $_GET["cijfers_klassen_lijst"], $_GET["cijfers_vakken_lijst"], $_GET["cijfers_rapporten_lijst"], "", $_SESSION["SchoolJaar"]);
		}
	} else {
		print $s->listcijfers($_SESSION["SchoolID"], $_GET["cijfers_klassen_lijst"], $_GET["cijfers_vakken_lijst"], $_GET["cijfers_rapporten_lijst"], "", $_SESSION["SchoolJaar"]);
	}
}


?>
<script>
	$("#loader_spn").toggleClass('hidden');
	/*$("#btn_submit_cijfers").prop('disabled', false);*/
	$(document).ready(function() {
		$(".lo").change(function() {
			var $studentid = $(this).attr("data-student-id"),
				$id_cijfer = $(this).attr("id_cijfer_table"),
				$cijfername = $(this).attr("data-cijfer"),
				$klas = $(this).attr("data-klas"),
				$vak = $(this).attr("data-vak"),
				$rapport = $(this).attr("data-rapport"),
				$cijfervalue = $(this).val();
			if ($(this).val() == 10) {
				$(this).css('background-color', 'lawngreen');
			} else if ($(this).val() == 1) {
				$(this).css('background-color', 'red');
			} else {
				$(this).css('background-color', 'white');
			}
			// console.log($id_cijfer, $studentNr, $cijferName, $cijfer, $klas, $rapport, $vak);
			$.ajax({
				url: "ajax/update_cijfers.php",
				data: "id_cijfer=" +
					$id_cijfer +
					"&studentid=" +
					$studentid +
					"&cijfername=" +
					$cijfername +
					"&cijfervalue=" +
					$cijfervalue +
					"&klas=" +
					$klas +
					"&rapport=" +
					$rapport +
					"&vak=" +
					$vak,
				type: "POST",
				dataType: "HTML",
				cache: false,
				async: false,
				success: function(data) {
					check_ls_data = data;
					console.log("esto es lo que tiene data " + check_ls_data);
				},
			});
		})

		function get_clase() {
			$("td.se").each(function() {
				const existe = $('td').hasClass('se11');
				var total = 0;
				var clase = $(this).attr('class').split(' ').pop();
				if (clase.length == 3) {
					clase = clase.slice(-1);
				} else {
					clase = clase.slice(-2);
				}
				total = get_cijfers($(this), clase);
				total = total == 0 ? '' : total;
				if (clase < 9) {
					if (clase % 2 == 0) {
						$(this).next().text(total);
					} else {
						$(this).next().next().text(total);
					}
				} else if (clase < 12) {
					if (existe) {
						switch (clase) {
							case '9':
								$(this).next().next().next().text(total);
								break;
							case '10':
								$(this).next().next().text(total);
								break;
							case '11':
								$(this).next().text(total);
								break;
						}
					} else {
						switch (clase) {
							case '9':
								$(this).next().next().text(total);
								break;
							case '10':
								$(this).next().text(total);
								break;
						}
					}
				} else if (clase < 16) {
					if (total != 0 && total != '') {
						switch (clase) {
							case '14':
								$(this).next().next().text(Math.round(total));
								break;
							case '15':
								$(this).next().text(Math.round(total));
								break;
						}
					} else {
						switch (clase) {
							case '14':
								$(this).next().next().text('');
								break;
							case '15':
								$(this).next().text('');
								break;
						}
					}
				}
			});

			$(".student").each(function() {
				const textos = [];
				const existe = $('td').hasClass('se11');

				$(this).find('td.gec').each(function() {
					if (!isNaN($(this).text()) && $(this).text() != '') {
						textos.push(Number($(this).text()));
						textos.push(Number($(this).text()));
					}
				});
				if (existe) {
					if (!isNaN($(this).find('td.se11 span').text()) && $(this).find('td.se11 span').text() != '') {
						textos.push(Number($(this).find('td.se11 span').text()));
					}
				}

				const suma = textos.reduce((total, num) => total + num, 0);

				const promedio = suma / textos.length;

				if (existe && textos.length == 7) {
					$(this).find("td.gse").text(promedio.toFixed(1))
				} else if (!existe && textos.length == 6) {
					$(this).find("td.gse").text(promedio.toFixed(1))
				} else {
					$(this).find("td.gse").text("")
				}
			});
		}

		function get_cijfers(td, clase) {
			const existe = $('td').hasClass('se11');
			var se1 = 0
			var se2 = 0
			var se3 = 0
			if (clase < 9) {
				if (clase % 2 == 0) {
					se1 = parseFloat(td.children('span').text());
					se2 = parseFloat(td.prev().children('span').text());
				} else {
					se1 = parseFloat(td.children('span').text());
					se2 = parseFloat(td.next().children('span').text());
				}
				Number.isNaN(se1) ? se1 = 0 : se1 = se1;
				Number.isNaN(se2) ? se2 = 0 : se2 = se2;
			} else if (clase < 12) {
				const tdOriginal = td;
				switch (clase) {
					case '9':
						se1 = Number(tdOriginal.find('span').text());
						se2 = Number(tdOriginal.next().find('span').text());
						break;

					case '10':
						se1 = Number(tdOriginal.find('span').text());
						se2 = Number(tdOriginal.prev().find('span').text());
						break;

					case '11':
						se1 = Number(tdOriginal.prev().find('span').text());
						se2 = Number(tdOriginal.prev().prev().find('span').text());
						break;

					default:
						// código por defecto
						break;
				}
				Number.isNaN(se1) ? se1 = 0 : se1 = se1;
				Number.isNaN(se2) ? se2 = 0 : se2 = se2;
			} else if (clase < 16) {
				if (clase % 2 == 0) {
					se1 = parseFloat(td.children('span').text());
					se2 = parseFloat(td.next().children('span').text());
					se3 = parseFloat(td.prev().text());
				} else {
					se1 = parseFloat(td.prev().children('span').text());
					se2 = parseFloat(td.children('span').text());
					se3 = parseFloat(td.prev().prev().text());
				}
				Number.isNaN(se1) ? se1 = 0 : se1 = se1;
				Number.isNaN(se2) ? se2 = 0 : se2 = se2;
				Number.isNaN(se3) ? se3 = 0 : se3 = se3;
				if (se2 == 0 || se3 == 0) {
					se1 = 0;
					se2 = 0;
					se3 = 0;
				} else {
					se1 = se1 > se2 ? se1 : se2;
				}
				total = (se1 + se3) / 2;
				return total;
			}

			total = se1 > se2 ? se1 : se2;
			return total;
		}
		setInterval(function() {
			get_clase()
		}, 2000)

		get_clase();

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
				console.log("update:" + data)
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
					// $('#vak').find('tr.active td:last-child').html(data);
					$('#ge' + $row).text(data);
					$('#vak').find('tr.active').removeClass('active');
					console.log("gem:" + data)
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