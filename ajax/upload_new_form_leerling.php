<?php

require_once("../classes/spn_documents.php");
require_once("../classes/spn_authentication.php");


if(session_status() == PHP_SESSION_NONE)
session_start();

$UserGUID = $_SESSION["UserGUID"];
$schoolname = $_SESSION["schoolname"];
$schoolname = str_replace (" ", "", $schoolname);
$Class = $_POST["klas_student_new_forms"];
$student_id = $_POST["id_student"];

$id = date("YmdHis");
chdir("..");


$target_dir = getcwd () . "/upload/new_forms/$schoolname/$Class/";

if(!is_dir($target_dir)){
  mkdir($target_dir, 0755, true);
}

$document_id = $id .'_'. basename($_FILES["new_form_file_to_upload"]["name"]);
$target_file = $target_dir . $document_id;

if (move_uploaded_file($_FILES['new_form_file_to_upload']['tmp_name'], $target_file))
{
  $s = new spn_documents();

  if($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING")
  {
    print $s->create_new_forms_document($student_id, $document_id, $_POST["description_new_forms"],$_SESSION["SchoolID"],$Class);
  }
  if($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT")
  {
    print $s->create_new_forms_document($student_id, $document_id, $_POST["description_new_forms"],$_SESSION["SchoolID"],$Class);
  }

}
else
print 0;
?>
