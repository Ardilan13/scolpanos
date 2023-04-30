<?php

	require_once("../classes/spn_daily.php");
	require_once("../classes/spn_utils.php");
	require_once("../config/app.config.php");

	$d = new spn_daily();
	$u = new spn_utils();

	if(session_status() == PHP_SESSION_NONE)
		session_start();
	print  $d->create_daily($_POST["list_docent"],$_POST["cijfers_klassen_lijst"] ,$u->converttomysqldatetime($_POST["start_date_time"] . " " . $_POST["daily_time_start"]),$u->converttomysqldatetime($_POST["end_date_time"] . " " . $_POST["daily_time_end"]),$_POST["daily_subject"],$_POST["daily_observation"],appconfig::GetDummy());

?>
