<?php

require_once("../classes/spn_message.php");
require_once("../config/app.config.php");
/*
configuration for the detail table to be shown on screen
the $baseurl & $detailpage will be used to create the "View Details" link in the table
*/
$baseurl = appconfig::GetBaseURL();


if (session_status() == PHP_SESSION_NONE) {
}


$m = new spn_message();
print $m->getcountunreadmessages($_SESSION["UserGUID"], 0, "N");

?>
<script>
  $('#notification-trigger').click(function() {
    $.post("ajax/getnotifications_tabel.php", {}, function(data) {
      $("#table_notifications_result").html(data);
    });
  });
</script>