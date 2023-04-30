<?php
require_once("../classes/spn_contact_mobile.php");
require_once("../config/app.config.php");

$baseurl = appconfig::GetBaseURL();
$detailpage = "studentdetail_mobile.html";

if(session_status() == PHP_SESSION_NONE)
{
	session_start();
}

$class= $_SESSION["Class"];
$schoolID =$_SESSION["SchoolID"];
$studentid = $_GET["id"];
$familyid = $_GET["id_family"];

$c = new spn_contact_mobile();
print $c->list_contacts($familyid,null); //CODE CaribeDevelopers
 

?>
