<?php
require_once ("spn_audit.php");
class spn_utils
{


	/* Create GUID */
	function CreateGUID()
	{
        $CalcGuid=0;

		try
		{
			if (function_exists('com_create_guid'))
			{
				$CalcGuid = trim(com_create_guid(),"{}");
			}
			else{
                mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
                $charid = strtoupper(md5(uniqid(rand(), true)));
                $hyphen = chr(45);// "-"
                $CalcGuid = substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12);
            }

            return $CalcGuid;
        }
        catch(Exception $e){
        	$CalcGuid = "";
            return $CalcGuid;
        }

    }




	/* Get date for inserting into DB, based on our local timezone */
 	function getdate($timezone = "America/Aruba")
 	{
 		$date="";
 		if(date_default_timezone_set($timezone))
 		{
 			$date = date("Y-m-d");
 		}
 		return $date;
 	}

 	/* Get time for inserting into DB, based on our local timezone */
 	function gettime($timezone = "America/Aruba")
 	{
 		$time="";
 		if(date_default_timezone_set($timezone))
 		{
 			$time = date("H:i:s");
 		}
 		return $time;
 	}

 	/* Get time for inserting into DB, based on our local timezone */
 	function getdatetime($timezone = "America/Aruba")
 	{
 		$datetime="";
 		if(date_default_timezone_set($timezone))
 		{
 			$datetime = date("Y-m-d H:i:s");
 		}
 		return $datetime;
 	}


    /* New functions to parse date from web form */
    function converttomysqldate($datestring)
    {
        if($d = date_parse($datestring))
        {
            return $d["year"] . "-" . $d["month"] . "-" . $d["day"];
        }
        else
        {
            return false;
        }
    }

    function convertfrommysqldate($datestring)
    {
        if($d = date_parse($datestring))
        {
            return ($d["day"] < 9 ? "0" . $d["day"] : $d["day"]) . "-" . ($d["month"] < 9 ? "0" . $d["month"] : $d["month"]) . "-" . $d["year"];
        }
        else
        {
            return false;
        }
    }

	function convertfrommysqldate_new($datestring)
    {
        if($d = date_parse($datestring))
        {
            return $d["year"] . "-" . ($d["month"] < 10 ? "0" . $d["month"] : $d["month"]) . "-" . ($d["day"] < 10 ? "0" . $d["day"] : $d["day"]);
        }
        else
        {
            return false;
        }
    }

		// Begin CaribeDevelopers
		function converttomysqldatetime($datestring)
		{
				if($d = date_parse($datestring))
				{
						return $d["year"] . "-" . $d["month"] . "-" . $d["day"] . " " . $d["hour"] . ":" . $d["minute"] . ":" . $d["second"];
				}
				else
				{
						return false;
				}
		}
		// End CaribeDevelopers

}
