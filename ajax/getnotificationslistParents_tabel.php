<?php


require_once("../classes/spn_message.php");
require_once("../classes/spn_authentication.php");
require_once("../config/app.config.php");

/*
configuration for the detail table to be shown on screen
the $baseurl & $detailpage will be used to create the "View Details" link in the table
*/
$baseurl = appconfig::GetBaseURL();
$detailpage = "notification_create.php";


if(session_status() == PHP_SESSION_NONE)
session_start();

$m = new spn_message();
print $m->listmessages_parents($_GET["student_id"]);

// }
?>
<script type="text/javascript">
function getParam(key) {
	// Find the key and everything up to the ampersand delimiter
	var value = RegExp("" + key + "[^&]+").exec(window.location.search);

	// Return the unescaped value minus everything starting from the equals sign or an empty string
	return unescape(!!value ? value.toString().replace(/^[^=]+./, "") : "");
}

var studentid = getParam("id");


$(function(){
	$('#dataRequest-notification tr').click(function(){

		$(this).closest('tr').siblings().children('td, th').css('background-color','');
		$(this).children('td, th').css('background-color','#cccccc');
		$(this).children('td, th').removeClass('info');

		var id_message = $(this).find("td").eq(1).find("[name='id_message']").val();
		var message_status = $(this).find("td").eq(1).find("[name='message_status']").val();

		if (message_status==0){
			$.post("ajax/set_read_message_parent.php?",
			{
				id_message : id_message,
				id_student : studentid
			},
			function(data){
				$("#table_notification_result_").html(data);
			});
		}

	});
});

</script>
