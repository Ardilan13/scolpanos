<?php

require_once("../classes/spn_documents.php");
require_once("../classes/spn_authentication.php");


if(session_status() == PHP_SESSION_NONE)
session_start();

$UserGUID = $_SESSION["UserGUID"];
$schoolname = $_SESSION["schoolname"];
$schoolname = str_replace (" ", "", $schoolname);
$Class = $_POST["rapport_klassen_lijst_upload"];


$id = date("YmdHis");
chdir("..");

if($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT")
{
  $Class = $_SESSION["Class"];
}

$target_dir = getcwd () . "/upload/Documents_klas/$schoolname/$Class/";

if(!is_dir($target_dir)){
  mkdir($target_dir, 0755, true);
}

$document_id = $id .'_'. basename($_FILES["document_klas_file_to_upload"]["name"]);
$target_file = $target_dir . $document_id;

if (move_uploaded_file($_FILES['document_klas_file_to_upload']['tmp_name'], $target_file))
{
  $s = new spn_documents();

  if($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING")
  {
    print $s->create_document_klas($UserGUID, $document_id, $_POST["description_document_klas"],$_SESSION["SchoolID"],$Class);
  }
  if($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT")
  {
    print $s->create_document_klas($UserGUID, $document_id, $_POST["description_document_klas"],$_SESSION["SchoolID"],$_SESSION["Class"]);
  }

}
else
print 0;
?>
