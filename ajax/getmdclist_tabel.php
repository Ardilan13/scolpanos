<?php


	require_once("../classes/spn_mdc.php");
	require_once("../classes/spn_authentication.php");
	require_once("../config/app.config.php");

	/*
		configuration for the detail table to be shown on screen
		the $baseurl & $detailpage will be used to create the "View Details" link in the table
	*/
	$baseurl = appconfig::GetBaseURL();

	if(session_status() == PHP_SESSION_NONE)
		session_start();

	$m = new spn_mdc();
	print $m->get_mdc($_SESSION["SchoolJaar"],0, 	$_GET["id"], appconfig::GetDummy());
	//print $m->get_mdc(0, 	1, appconfig::GetDummy());

// }
?>

<script type="text/javascript">

    $(function(){
        $('#dataRequest-mdc tr').click(function(){
					$(this).closest('tr').siblings().children('td, th').css('background-color','');
					$(this).children('td, th').css('background-color','#cccccc');
					//alert($(this).find("td").eq(4).find("[name='id_mdc']").val());
					$('#id_mdc').val($(this).find("td").eq(4).find("[name='id_mdc']").val());
					$('#mdc_date').val($(this).find("td").eq(1).text());
					$('#mdc_reason').val($(this).find("td").eq(2).text());
					$('#mdc_class').val($(this).find("td").eq(3).text());
					$('#mdc_observation').val($(this).find("td").eq(4).find("[name='observations_val']").val());
					if ($(this).find("td").eq(5).text() == 'Pending')
					{
						$('#mdc_pending').prop('checked', true);
						$('#mdc_no_pending').prop('checked', false);
					}
					else
					{
						$('#mdc_no_pending').prop('checked', true);
						$('#mdc_pending').prop('checked', false);
					}

					$('#btn-clear-mdc').text("DELETE");

        });
    });

</script>
