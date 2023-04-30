<?php

require_once("../classes/spn_documents.php");
require_once("../classes/spn_authentication.php");


if(session_status() == PHP_SESSION_NONE)
session_start();

$UserGUID = $_SESSION["UserGUID"];
$schoolname = $_SESSION["schoolname"];
$schoolname = str_replace (" ", "", $schoolname);
$Class = $_POST["woord_rapport_klas_upload"];


$id = date("YmdHis");
chdir("..");

if($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT")
{
  $Class = $_SESSION["Class"];
}

$target_dir = getcwd () . "/upload/woord_rapport/$schoolname/$Class/";

if(!is_dir($target_dir)){
  mkdir($target_dir, 0755, true);
}

$document_id = $id .'_'. basename($_FILES["woord_rapport_file_to_upload"]["name"]);
$target_file = $target_dir . $document_id;

if (move_uploaded_file($_FILES['woord_rapport_file_to_upload']['tmp_name'], $target_file))
{
  $s = new spn_documents();

  if($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING")
  {
    print $s->create_woord_rapport_document($UserGUID, $document_id, $_POST["description_woord_rapport"],$_SESSION["SchoolID"],$Class);
  }
  if($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT")
  {
    print $s->create_woord_rapport_document($UserGUID, $document_id, $_POST["description_woord_rapport"],$_SESSION["SchoolID"],$_SESSION["Class"]);
  }

}
else
print 0;
?>
