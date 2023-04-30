<?php

require_once("../classes/spn_documents.php");
require_once("../config/app.config.php");

$d = new spn_documents();

if(session_status() == PHP_SESSION_NONE)
session_start();
$schoolname = str_replace (" ", "", $_SESSION["schoolname"]);
$class = $_POST["document_klas"];
$doc = $_POST["document_name"];
$carpeta = "../upload/Documents_klas/".$schoolname."/".$class."/".$doc."";
// Elimina el archivo de la carpeta

if (unlink("../upload/Documents_klas/".$schoolname."/".$class."/".$doc."")){
	
	print  $d->deletedocument($_POST["document_id"],appconfig::GetDummy());
}
else {
    echo $carpeta;
    //echo $_POST["document_id"];
	//Print "0";
}

?>
