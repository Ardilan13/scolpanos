<?php


	require_once("../classes/spn_authentication.php");
	require_once("../config/app.config.php");

	/*
		configuration for the detail table to be shown on screen
		the $baseurl & $detailpage will be used to create the "View Details" link in the table
	*/
	$baseurl = appconfig::GetBaseURL();

	if(session_status() == PHP_SESSION_NONE)
		session_start();

	//print $m->get_test(0, 	1, appconfig::GetDummy());

// }
?>

<script type="text/javascript">

    $(function(){
        $('#dataRequest-test tr').click(function(){
					$(this).closest('tr').siblings().children('td, th').css('background-color','');
					$(this).children('td, th').css('background-color','#cccccc');
					// alert($(this).find("td").eq(3).find("[name='id_test']").val());
					$('#id_test').val($(this).find("td").eq(3).find("[name='id_test']").val());
					$('#test_date').val($(this).find("td").eq(0).text());
					$('#test_type').val($(this).find("td").eq(1).text());
					$('#test_class').val($(this).find("td").eq(2).text());
					$('#test_observation').val($(this).find("td").eq(3).text());

					$('#btn-clear-test').text("DELETE");

        });
    });

</script>
