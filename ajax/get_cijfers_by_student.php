<?php
require_once("../classes/spn_cijfers.php");
require_once("../classes/spn_authentication.php");
require_once("../config/app.config.php");


$s = new spn_cijfers();
if(session_status() == PHP_SESSION_NONE)
session_start();

//echo $_POST["studentid"];

$studentid = isset($_POST["studentid"]);
print $s->list_cijfers_by_student($studentid,appconfig::GetDummy());
?>
<script type="text/javascript">

$(function() {
	  $("#tbl_cijfers_by_student td").each(function () {
	    if ($(this).text() >=1 && $(this).text() <= 5.5) {
	      $(this).addClass('quaternary-bg-color default-secondary-color');
	    }
	  });
	});

</script>
