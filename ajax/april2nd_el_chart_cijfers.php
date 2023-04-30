<?php


require_once("../classes/spn_dashboard.php");
require_once("../config/app.config.php");

if(session_status() == PHP_SESSION_NONE)
{
	session_start();
}
//array
$cijfer_array = "";

	if(isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"]))
	{
		if($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT")
		{
			if(isset($_SESSION["Class"]))
			{
				$s = new spn_dashboard();			
				// function dash_cijfers($schoolid_in,$klas_in)				
				$cijfer_array = $s->dash_cijfers($_SESSION["SchoolID"],$_SESSION["Class"]);
				//print $s->mysqlierror;				
				
				if(count($cijfer_array > 0))
				{
					//JSON encode data - // $json_return = array("KlasPresent" => "20", "KlasTeLaat" => "4");
					$json_return = $cijfer_array;
					print json_encode($json_return);
				}
				else
				{
					print "0";
				}

				
			}
		}
		else if($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING")
		{
			$s = new spn_dashboard();		
			// function dash_cijfers($schoolid_in,$klas_in)				
			$cijfer_array = $s->dash_cijfers($_SESSION["SchoolID"],"ALL");
			//print $s->mysqlierror;	

			if(count($cijfer_array > 0))
				{
					//JSON encode data - // $json_return = array("KlasPresent" => "20", "KlasTeLaat" => "4");
					$json_return = $cijfer_array;
					print json_encode($json_return);
				}		
				else
				{
					print "0";
				}
			
			
		}
	}

?>