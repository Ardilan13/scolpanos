<?php


require_once("../classes/spn_school.php");
require_once("../config/app.config.php");
require_once ("../classes/spn_utils.php");

$u = new spn_utils();

if(session_status() == PHP_SESSION_NONE)
{
	session_start();
}

$count_absent = 0;
$count_total = 0;
$count_present = 0;


$date_school_group = $_GET["date"];
$schoolid = $_GET["school"];

if ($date_school_group==0){

	$date_school_group= date('Y-m-d');
}




	if(isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"]))
	{
		if($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT")
		{
			if(isset($_SESSION["Class"]))
			{
				$s = new spn_school();
				// function dash_verzuim($schoolid_in,$klas_in)
				$count_absent = $s->dash_absentie_school_group($u->converttomysqldate($date_school_group), $schoolid,$_SESSION["Class"]);
				//print $s->mysqlierror;
				$count_total = $s->dash_aantalstudenten_school_group($u->converttomysqldate($date_school_group), $schoolid,$_SESSION["Class"]);
				//print "count : " . $count_total;
				$count_present = $count_total - $count_absent;
				//JSON encode data - // $json_return = array("KlasPresent" => "20", "KlasAbsent" => "4");
				$json_return = array("KlasPresent" => $count_present, "KlasAbsent" => $count_absent);
				print "[" . json_encode($json_return) . "]";
			}
		}
		else if($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING")
		{
			$s = new spn_school();
			// function dash_verzuim($schoolid_in,$klas_in)
			$count_absent = $s->dash_absentie_school_group($u->converttomysqldate($date_school_group), $schoolid,"ALL");
			//print $s->mysqlierror;
			$count_total = $s->dash_aantalstudenten($schoolid,"ALL");
			//print "count : " . $count_total;
			$count_present = $count_total - $count_absent;
			//JSON encode data - // $json_return = array("KlasPresent" => "20", "KlasAbsent" => "4");
			$json_return = array("KlasPresent" => $count_present, "KlasAbsent" => $count_absent);
			print "[" . json_encode($json_return) . "]";
		}
	}



		/*  calculate present students based on total students - absent students*/

	// $json_return = array("KlasPresent" => "20", "KlasAbsent" => "4");

	// print "[" . json_encode($json_return) . "]";


?>
