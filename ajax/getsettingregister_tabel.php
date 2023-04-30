<?php

	require_once("../classes/spn_setting.php");
	require_once("../config/app.config.php");

	/*
		configuration for the detail table to be shown on screen
		the $baseurl & $detailpage will be used to create the "View Details" link in the table
	*/
	$baseurl = appconfig::GetBaseURL();

	if(session_status() == PHP_SESSION_NONE)
		session_start();

	$a = new spn_setting();
	print $a->get_setting($_SESSION["SchoolID"]);
?>

<script type="text/javascript">

    $(function(){

			$('#dataRequest-setting-detail tr').click(function(){

				// $('#setting_rapnumber_1').prop("checked", true);

				$('#school_id').val($(this).find("td").eq(0).text());
				$('#setting_school_jaar').val($(this).find("td").eq(0).text());
				($(this).find("td").eq(1).text() == 'Active' ? $('#setting_rapnumber_1').prop("checked", true) :  $('#setting_rapnumber_1').prop("checked", false) );
				($(this).find("td").eq(1).text() == 'Active' ? $("#setting_rapnumber_1_val").val(1) :  $("#setting_rapnumber_1_val").val(0));
				// $("#setting_rapnumber_1_val").val("1");

				$('#setting_begin_rap_1').val($(this).find("td").eq(2).text());
				$('#setting_end_rap_1').val($(this).find("td").eq(3).text());
				($(this).find("td").eq(4).text() == 'Active' ? $('#setting_rapnumber_2').prop("checked", true) :  $('#setting_rapnumber_2').prop("checked", false) );
				($(this).find("td").eq(4).text() == 'Active' ? $("#setting_rapnumber_2_val").val(1) :  $("#setting_rapnumber_2_val").val(0));

				$('#setting_begin_rap_2').val($(this).find("td").eq(5).text());
				$('#setting_end_rap_2').val($(this).find("td").eq(6).text());
				($(this).find("td").eq(7).text() == 'Active' ? $('#setting_rapnumber_3').prop("checked", true) :  $('#setting_rapnumber_3').prop("checked", false) );
				($(this).find("td").eq(7).text() == 'Active' ? $("#setting_rapnumber_3_val").val(1) :  $("#setting_rapnumber_3_val").val(0));

				$('#setting_begin_rap_3').val($(this).find("td").eq(8).text());
				$('#setting_end_rap_3').val($(this).find("td").eq(9).text());
				($(this).find("td").eq(10).text() == 'Active' ? $('#setting_mj').prop("checked", true) :  $('#setting_mj').prop("checked", false) );
				($(this).find("td").eq(10).text() == 'Active' ? $("#setting_mj_val").val(1) :  $("#setting_mj_val").val(0));
				// $("#setting_mj_val").val("1");

				$('#setting_sort').val($(this).find("td").eq(11).text());


				// alert($(this).find("td").eq(0).find("[name='id_setting']").val());

				// $('#id_setting').val($(this).find("td").eq(0).find("[name='id_setting']").val());

				$('#btn-clear-setting').text("DELETE");

			});


			$('#dataRequest-setting-detail td').click(function(){
				var row_index = $(this).parent().index();
	   		var col_index = $(this).index();
				// alert ('Row: ' + (row_index + 1) );
				// alert ('Col: ' + (col_index + 1));
			});

		});

</script>
