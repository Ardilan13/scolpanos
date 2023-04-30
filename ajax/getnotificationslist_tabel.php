<?php
session_start();

require_once("../classes/spn_message.php");
require_once("../classes/spn_authentication.php");
require_once("../config/app.config.php");

/*
configuration for the detail table to be shown on screen
the $baseurl & $detailpage will be used to create the "View Details" link in the table
*/
$baseurl = appconfig::GetBaseURL();
$detailpage = "notification_create.php";



$m = new spn_message();
print $m->listmessages($_SESSION["UserGUID"], 0, "N");

// }
?>
<script type="text/javascript">
	$(function() {
		$('#dataRequest-notification tr').click(function() {


			// reload
			$(this).closest('tr').siblings().children('td, th').window.open("print.php?name=houding&title=houding List&klas=" + $("#houding_klassen_lijst option:selected").val() + "&rapport=" + $("#rapport option:selected").val());
			location = 'notifications_create.php';
			location = 'notifications_create.php' + '?id=' + $('#studenten_lijst option:selected').val();



			$(this).closest('tr').siblings().children('td, th').css('background-color', '');
			$(this).children('td, th').css('background-color', '#cccccc');
			$(this).children('td, th').removeClass('info');

			$.post("ajax/getinboxmessage_detail_tabel.php", {
					id_message: $(this).find("td").eq(1).find("[name='id_message']").val(),
					message_status: $(this).find("td").eq(1).find("[name='message_status']").val()
				},
				function(data) {
					$("#table_notification_result_").html(data);
				});
		});
	});
</script>