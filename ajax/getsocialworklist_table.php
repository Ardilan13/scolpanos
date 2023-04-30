<?php


	require_once("../classes/spn_social_work.php");
	require_once("../classes/spn_authentication.php");
	require_once("../config/app.config.php");

	/*
		configuration for the detail table to be shown on screen
		the $baseurl & $detailpage will be used to create the "View Details" link in the table
	*/
	$baseurl = appconfig::GetBaseURL();

	if(session_status() == PHP_SESSION_NONE)
		session_start();

	$m = new spn_social_work();
	print $m->get_social_work($_SESSION["SchoolJaar"],0, 	$_GET["id"], appconfig::GetDummy());

// }
?>

<script type="text/javascript">

    $(function(){
				$('#dataRequest-social_work tr').click(function(){
					$(this).closest('tr').siblings().children('td, th').css('background-color','');
					$(this).children('td, th').css('background-color','#cccccc');
					$('#id_social_work').val($(this).find("td").eq(4).find("[name='id_social_work']").val());
					$('#social_work_date').val($(this).find("td").eq(1).text());
					$('#social_work_reason').val($(this).find("td").eq(2).text());
					$('#social_work_class').val($(this).find("td").eq(3).text());
					$('#social_work_class_hidden').val($(this).find("td").eq(3).text());
					$('#social_work_observation').val($(this).find("td").eq(4).find("[name='observations_val']").val());
					if ($(this).find("td").eq(4).text() == 'Pending')
					{
						$('#social_work_pending').prop('checked', true);
						$('#social_work_no_pending').prop('checked', false);
					}
					else
					{
						$('#social_work_no_pending').prop('checked', true);
						$('#social_work_pending').prop('checked', false);
					}
					// alert($('#id_social_work').val());
					$('#btn-clear-social-work').text("DELETE");

        });
    });

</script>
