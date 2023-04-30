<?php

	require_once("../classes/spn_message.php");
	require_once("../config/app.config.php");
	/*
		configuration for the detail table to be shown on screen
		the $baseurl & $detailpage will be used to create the "View Details" link in the table
	*/
	$baseurl = appconfig::GetBaseURL();

	if(session_status() == PHP_SESSION_NONE)
		session_start();

	$m = new spn_message();

	print $m->getcountunreadmessages($_POST['UserGUID'], 0, "N");
	//print $m->getcountunreadmessages("1D31DC0D-62DC-48BB-B41F-B8EA67A3F19D",0);
	//print $m->getcountunreadmessages($_POST['UserGUID'], $_SESSION["student_ID"]);

?>
