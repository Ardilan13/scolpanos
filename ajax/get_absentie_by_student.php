<?php


require_once("../classes/spn_leerling.php");
require_once("../config/app.config.php");

if(session_status() == PHP_SESSION_NONE)
{
  session_start();
}

$count_geenabsentie = 0;
$count_total = 0;
$count_present = 0;


$s = new spn_leerling();

$studentid = $_GET["id"];


// Get the absentie of the class
$absentie_of_class = $s->get_absentie_by_class($_SESSION['SchoolJaar'], $_SESSION["SchoolID"],$studentid, false);
// echo $absentie_of_class;
//print $s->mysqlierror;

// Get the absentie of the student
$absentie_of_student = $s->get_absentie_by_student($_SESSION['SchoolJaar'], $_SESSION["SchoolID"],$studentid,false);


// echo $absentie_of_student;
//$json_return = array("geenabsentie" => "Studenten", "absentie_of_class" => $absentie_of_class, "absentie_of_student" => $absentie_of_student);
$json_return = array("Absentie" => "Studenten", "absentie_of_class" => $absentie_of_class, "absentie_of_student" => $absentie_of_student);
print "[" . json_encode($json_return) . "]";


?>
