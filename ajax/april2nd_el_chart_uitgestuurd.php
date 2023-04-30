<?php


require_once("../classes/spn_dashboard.php");
require_once("../config/app.config.php");

if(session_status() == PHP_SESSION_NONE)
{
	session_start();
}

$count_uitgestuurd = 0;
$count_total = 0;
$count_present = 0;

	if(isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"]))
	{
		if($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT")
		{
			if(isset($_SESSION["Class"]))
			{
				$s = new spn_dashboard();
				// function dash_uitgestuurd($schoolid_in,$klas_in)
				$count_uitgestuurd = $s->dash_uitgestuurd($_SESSION['SchoolJaar'],$_SESSION["SchoolID"],$_SESSION["Class"]);
				//print $s->mysqlierror;
				$count_total = $s->dash_aantalstudenten($_SESSION['SchoolJaar'],$_SESSION["SchoolID"],$_SESSION["Class"]);
				//print "count : " . $count_total;
				$count_present = $count_total - $count_uitgestuurd;
				//JSON encode data - // $json_return = array("KlasPresent" => "20", "KlasTeLaat" => "4");
				$json_return = array("KlasPresent" => $count_present, "KlasUitgestuurd" => $count_uitgestuurd);
				print "[" . json_encode($json_return) . "]";
			}
		}
		else if($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING")
		{
			$s = new spn_dashboard();
			// function dash_uitgestuurd($schoolid_in,$klas_in)
			$count_uitgestuurd = $s->dash_uitgestuurd($_SESSION['SchoolJaar'],$_SESSION["SchoolID"],"ALL");
			//print $s->mysqlierror;
			$count_total = $s->dash_aantalstudenten($_SESSION['SchoolJaar'],$_SESSION["SchoolID"],"ALL");
			//print "count : " . $count_total;
			$count_present = $count_total - $count_uitgestuurd;
			//JSON encode data - // $json_return = array("KlasPresent" => "20", "KlasTeLaat" => "4");
			$json_return = array("KlasPresent" => $count_present, "KlasUitgestuurd" => $count_uitgestuurd);
			print "[" . json_encode($json_return) . "]";
		}
	}

?>
