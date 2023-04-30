<?php

	require_once("../classes/spn_documents.php");
	require_once("../config/app.config.php");

	$d = new spn_documents();

	if(session_status() == PHP_SESSION_NONE)
		session_start();

	// Elimina el archivo de la carpeta

	if (unlink("../upload/".$_POST["document_name"]))
			print  $d->deletedocument($_POST["document_id"],appconfig::GetDummy());
		else {
			Print "0";
		 			}

?>
