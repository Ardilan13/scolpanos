<?php


require_once("../classes/spn_dashboard.php");
require_once("../config/app.config.php");

if(session_status() == PHP_SESSION_NONE)
{
	session_start();
}
//array
$topxtelaat_array = "";

	if(isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"]))
	{
		if($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT")
		{
			if(isset($_SESSION["Class"]))
			{
				$s = new spn_dashboard();			
				// function dash_skoa_topxtelaat(topxvalue,false);				
				$topxtelaat_array = $s->dash_skoa_topxtelaat(5,false);
				//print $s->mysqlierror;				
				
				if(count($topxtelaat_array > 0))
				{
					$json_return = $topxtelaat_array;
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
			// function dash_skoa_topxtelaat(topxvalue,false);			
			$topxtelaat_array = $s->dash_skoa_topxtelaat(5,false);
			//print $s->mysqlierror;	

			if(count($topxtelaat_array > 0))
				{
					$json_return = $topxtelaat_array;
					print json_encode($json_return);
				}		
				else
				{
					print "0";
				}
			
			
		}
		else if($_SESSION["UserRights"] == "SKOABEHEER")
		{
			$s = new spn_dashboard();		
			// function dash_skoa_topxtelaat(topxvalue,false);			
			$topxtelaat_array = $s->dash_skoa_topxtelaat(5,false);
			//print $s->mysqlierror;	

			if(count($topxtelaat_array > 0))
				{
					$json_return = $topxtelaat_array;
					print json_encode($json_return);
				}		
				else
				{
					print "0";
				}			
			
		}

	}

?>