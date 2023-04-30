<?php


require_once("../classes/spn_leerling.php");
require_once("../config/app.config.php");

if(session_status() == PHP_SESSION_NONE)
{
  session_start();
}

$count_geenuitgestuurd = 0;
$count_total = 0;
$count_present = 0;


$s = new spn_leerling();

$studentid = $_GET["id"];


// Get the uitgestuurd of the class
$uitgestuurd_of_class = $s->get_uitgestuurd_by_class($_SESSION['SchoolJaar'],$_SESSION["SchoolID"],$studentid, false);
// echo $uitgestuurd_of_class;
//print $s->mysqlierror;

// Get the uitgestuurd of the student
$uitgestuurd_of_student = $s->get_uitgestuurd_by_student($_SESSION['SchoolJaar'],$_SESSION["SchoolID"],$studentid,false);


// echo $uitgestuurd_of_student;
//$json_return = array("geenuitgestuurd" => "Studenten", "uitgestuurd_of_class" => $uitgestuurd_of_class, "uitgestuurd_of_student" => $uitgestuurd_of_student);
$json_return = array("geenuitgestuurd" => "Studenten", "uitgestuurd_of_class" => $uitgestuurd_of_class, "uitgestuurd_of_student" => $uitgestuurd_of_student);
print "[" . json_encode($json_return) . "]";


?>
