<?php

	require_once("../classes/spn_message.php");

	$m = new spn_message();

	session_start();

	if(session_status() == PHP_SESSION_NONE)
	{
		session_start();
	}
	else {
		if (isset($_POST["idSchool"]) && isset($_POST["users_selected"]) && isset($_POST["subject_message"]) && isset($_POST["message"]))
				print $m->sendmessage("M","General", $_SESSION['UserRights'],	$_SESSION["UserGUID"], $_POST["subject_message"], $_POST['message'], $_POST["users_selected"], null ,$_POST["count_users_selected"], $_POST["idSchool"]);


	}
