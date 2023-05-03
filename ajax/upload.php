<?php

require_once("../classes/spn_documents.php");
if (session_status() == PHP_SESSION_NONE)
  session_start();

$id = date("YmdHis");

chdir("..");
$target_dir = getcwd() . "/upload/";
// print('<br> target dir: '.$target_dir);
$document_id = $id . basename($_FILES["fileToUpload"]["name"]);
// print('<br> $document_id: '.$document_id);
$target_file = $target_dir . $document_id;
// print('<br> $target_file: '.$target_file);

if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {
  $s = new spn_documents();
  print $s->createDocument($_POST["id_student"], $document_id, $_POST["description"], $_SESSION["SchoolID"], $_POST["klas_student"], 'leerling_document');
} else
  print 0;
