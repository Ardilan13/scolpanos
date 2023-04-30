<?php


require_once("../classes/spn_leerling.php");
require_once("../config/app.config.php");

if(session_status() == PHP_SESSION_NONE)
{
  session_start();
}

$count_geenlaat = 0;
$count_total = 0;
$count_present = 0;


$s = new spn_leerling();

$studentid = $_GET["id"];


// Get the laat of the class
$laat_of_class = $s->get_laat_by_class($_SESSION['SchoolJaar'], $_SESSION["SchoolID"],$studentid, false);
// echo $laat_of_class;
//print $s->mysqlierror;

// Get the laat of the student
$laat_of_student = $s->get_laat_by_student($_SESSION['SchoolJaar'], $_SESSION["SchoolID"],$studentid,false);


// echo $laat_of_student;
//$json_return = array("telaat" => "Studenten", "laat_of_class" => $laat_of_class, "laat_of_student" => $laat_of_student);
$json_return = array("Telaat" => "Studenten", "laat_of_class" => $laat_of_class, "laat_of_student" => $laat_of_student);
print "[" . json_encode($json_return) . "]";


?>
