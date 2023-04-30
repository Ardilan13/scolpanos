<?php
if (session_status() == PHP_SESSION_NONE)
	session_start();

require_once("../classes/spn_message.php");
require_once("../classes/spn_authentication.php");
require_once("../config/app.config.php");

/*
configuration for the detail table to be shown on screen
the $baseurl & $detailpage will be used to create the "View Details" link in the table
*/
$baseurl = appconfig::GetBaseURL();
$detailpage = "inbox_message.php";




$m = new spn_message();
print $m->listmessages($_SESSION["UserGUID"], 0, "M");

// }
?>

<script type="text/javascript">
	$(function() {

		$('#dataRequest-message tr').click(function() {

			$(this).closest('tr').siblings().children('td, th').css('background-color', '');
			$(this).children('td, th').css('background-color', '#cccccc');
			$(this).children('td, th').removeClass('info');
			var id_message = $(this).find("td").eq(4).find("[name='id_message']").val();
			var message_status = $(this).find("td").eq(4).find("[name='message_status']").val();
			// alert(message_1);
			// window.location.replace('inbox.php?m='+id_message);

			var url = window.location.pathname;
			var filename = url.substring(url.lastIndexOf('/') + 1);

			if (filename != 'inbox.php') {
				window.location.replace("inbox.php?m=" + id_message + "&status=" + message_status);
			}


			$.post("ajax/getinboxmessage_detail_tabel.php", {
					id_message: $(this).find("td").eq(4).find("[name='id_message']").val(),
					message_status: $(this).find("td").eq(4).find("[name='message_status']").val()
				},
				function(data) {

					$("#table_message_result_detail").html(data);

				});

		});
	});
</script>