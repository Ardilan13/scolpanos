<?php


require_once("../classes/spn_leerling.php");
require_once("../config/app.config.php");

if(session_status() == PHP_SESSION_NONE)
{
  session_start();
}

$count_geenhuiswerk = 0;
$count_total = 0;
$count_present = 0;


$s = new spn_leerling();

$studentid = $_GET["id"];


// Get the Huiswerk of the class
$huiswerk_of_class = $s->get_huiswerk_by_class($_SESSION['SchoolJaar'],$_SESSION["SchoolID"],$studentid, false);
// echo $huiswerk_of_class;
//print $s->mysqlierror;

// Get the Huiswerk of the student
$huiswerk_of_student = $s->get_huiswerk_by_student($_SESSION['SchoolJaar'],$_SESSION["SchoolID"],$studentid,false);


// echo $huiswerk_of_student;
//$json_return = array("geenHuiswerk" => "Studenten", "huiswerk_of_class" => $huiswerk_of_class, "huiswerk_of_student" => $huiswerk_of_student);
$json_return = array("geenHuiswerk" => "Studenten", "huiswerk_of_class" => $huiswerk_of_class, "huiswerk_of_student" => $huiswerk_of_student);
print "[" . json_encode($json_return) . "]";


?>
