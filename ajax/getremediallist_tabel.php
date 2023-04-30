<?php

// if(isset($_POST["id"]) )
// {
	require_once("../classes/spn_remedial.php");
	require_once("../classes/spn_authentication.php");
	require_once("../config/app.config.php");

	/*
		configuration for the detail table to be shown on screen
		the $baseurl & $detailpage will be used to create the "View Details" link in the table
	*/
	$baseurl = appconfig::GetBaseURL();

	if(session_status() == PHP_SESSION_NONE)
		session_start();

	$r = new spn_remedial();

	print $r->get_remedial($_SESSION['SchoolJaar'],0, $_GET["id"], appconfig::GetDummy());

// }
?>

<script type="text/javascript">

    $(function(){

			$('#dataRequest-remedial tr').click(function(){
				$(this).closest('tr').siblings().children('td, th').css('background-color','');
				$(this).children('td, th').css('background-color','#cccccc');
				$('#id_remedial').val($(this).find("td").eq(3).find("[name='id_remedial']").val());
				$('#remedial_school_year').val($(this).find("td").eq(2).text());
				$('#remedial_begin_date').val($(this).find("td").eq(3).text());
				$('#remedial_end_date').val($(this).find("td").eq(4).text());
				$('#remedial_subject').val($(this).find("td").eq(0).text());
				$('#id_remedial').val($(this).find("td").eq(4).find("[name='id_remedial']").val());

				$('#id_remedial_post').val($(this).find("td").eq(4).find("[name='id_remedial']").val());
				$('#btn-clear-remedial').text("DELETE");
			});
    });

		$('#div_remedial_detail').hide();

</script>
