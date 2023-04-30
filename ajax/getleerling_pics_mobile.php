<?php
require_once("../classes/spn_leerling_mobile.php");
require_once("../config/app.config.php");

$baseurl = appconfig::GetBaseURL();
$detailpage = "studentdetail_mobile.html";

if(session_status() == PHP_SESSION_NONE)
{
	session_start();
}

// $url=$_SERVER['HTTP_REFERER'];
// $path = parse_url($url, PHP_URL_PATH);
// $pathFragments = explode('/', $path);
// $page = end($pathFragments);


// if($page=='change_class.php') {
	// $class= $_POST["class"];
// }
// else {
	$class= $_SESSION["Class"];
// }

$schoolID =$_SESSION["SchoolID"];


$s = new spn_leerling_mobile();
print $s->liststudents_picture_mobile($schoolID,$class,$baseurl,$detailpage,appconfig::GetDummy());
?>
