<?php
ini_set('max_execution_time', 300);
ob_start();

require_once("../classes/spn_rapport.php");
require_once("../config/app.config.php");
$r = new spn_rapport();


if(session_status() == PHP_SESSION_NONE)
{
	session_start();
}


switch($_SESSION["SchoolID"])
{
	case '4':

	require_once("../classes/spn_rapport_school_4.php");

	$r = new spn_rapport_school_4();

	if(isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"]) && isset($_GET["rapport_klassen_lijst"]))
	{
		if($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT")
		{
			$result = $r->createrapport($_SESSION["SchoolID"],$_SESSION['SchoolJaar'],$_GET["rapport_klassen_lijst"],$_GET["rapport"],false);
		}
		else if($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING")
		{
			$result = $r->createrapport($_SESSION["SchoolID"],$_SESSION['SchoolJaar'],$_GET["rapport_klassen_lijst"],$_GET["rapport"],false);
		}
	}
	break;
	case '6':
	require_once("../classes/spn_rapport_school_6.php");
	$r = new spn_rapport_school_6();

	if(isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"]) && isset($_GET["rapport_klassen_lijst"]))
	{
		if($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT")
		{
			$result = $r->createrapport($_SESSION["SchoolID"],$_SESSION['SchoolJaar'],$_GET["rapport_klassen_lijst"],$_GET["rapport"],false);
		}
		else if($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING")
		{
			$result = $r->createrapport($_SESSION["SchoolID"],$_SESSION['SchoolJaar'],$_GET["rapport_klassen_lijst"],$_GET["rapport"],false);
		}
	}
	break;
	case '7':
	require_once("../classes/spn_rapport_school_7.php");
	$r = new spn_rapport_school_7();

	if(isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"]) && isset($_GET["rapport_klassen_lijst"]))
	{
		if($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT")
		{
			$result = $r->createrapport($_SESSION["SchoolID"],$_SESSION['SchoolJaar'],$_GET["rapport_klassen_lijst"],$_GET["rapport"],false);
		}
		else if($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING")
		{
			$result = $r->createrapport($_SESSION["SchoolID"],$_SESSION['SchoolJaar'],$_GET["rapport_klassen_lijst"],$_GET["rapport"],false);
		}
	}
	break;
	case '8':
	$level_klas= substr($_GET["rapport_klassen_lijst"],0,1);

	switch($level_klas)
	{
		case 1:
		require_once("../classes/spn_rapport_school_8_k_1_2.php");
		$r = new spn_rapport_school_8_k_1_2();
		break;

		case 2:
		require_once("../classes/spn_rapport_school_8_k_1_2.php");
		$r = new spn_rapport_school_8_k_1_2();
		break;

		case 3:
		require_once("../classes/spn_rapport_school_8_k_3_4_5_6.php");
		$r = new spn_rapport_school_8_k_3_4_5_6();
		break;

		case 4:
		require_once("../classes/spn_rapport_school_8_k_3_4_5_6.php");
		$r = new spn_rapport_school_8_k_3_4_5_6();
		break;

		case 5:
		require_once("../classes/spn_rapport_school_8_k_3_4_5_6.php");
		$r = new spn_rapport_school_8_k_3_4_5_6();
		break;

		case 6:
		require_once("../classes/spn_rapport_school_8_k_3_4_5_6.php");
		$r = new spn_rapport_school_8_k_3_4_5_6();
		break;

	}

		if(isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"]) && isset($_GET["rapport_klassen_lijst"]))
		{
			if($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT")
			{
				$result = $r->createrapport($_SESSION["SchoolID"],$_SESSION['SchoolJaar'],$_GET["rapport_klassen_lijst"],$_GET["rapport"],false);
			}
			else if($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING")
			{
				$result = $r->createrapport($_SESSION["SchoolID"],$_SESSION['SchoolJaar'],$_GET["rapport_klassen_lijst"],$_GET["rapport"],false);
			}
		}


	break;
	case '9':
	require_once("../classes/spn_rapport_school_9.php");
	$r = new spn_rapport_school_9();

	if(isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"]) && isset($_GET["rapport_klassen_lijst"]))
	{
		if($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT")
		{
			$result = $r->createrapport($_SESSION["SchoolID"],$_SESSION['SchoolJaar'],$_GET["rapport_klassen_lijst"],$_GET["rapport"],false);
		}
		else if($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING")
		{
			$result = $r->createrapport($_SESSION["SchoolID"],$_SESSION['SchoolJaar'],$_GET["rapport_klassen_lijst"],$_GET["rapport"],false);
		}
	}
	break;
	case '10':
	require_once("../classes/spn_rapport_school_10.php");
	$r = new spn_rapport_school_10();

	if(isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"]) && isset($_GET["rapport_klassen_lijst"]))
	{
		if($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT")
		{
			$result = $r->createrapport($_SESSION["SchoolID"],$_SESSION['SchoolJaar'],$_GET["rapport_klassen_lijst"],$_GET["rapport"],false);
		}
		else if($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING")
		{
			$result = $r->createrapport($_SESSION["SchoolID"],$_SESSION['SchoolJaar'],$_GET["rapport_klassen_lijst"],$_GET["rapport"],false);
		}
	}
	break;
	case '11':
	require_once("../classes/spn_rapport_school_11.php");
	$r = new spn_rapport_school_11();

	if(isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"]) && isset($_GET["rapport_klassen_lijst"]))
	{
		if($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT")
		{
			$result = $r->createrapport($_SESSION["SchoolID"],$_SESSION['SchoolJaar'],$_GET["rapport_klassen_lijst"],$_GET["rapport"],false);
		}
		else if($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING")
		{
			$result = $r->createrapport($_SESSION["SchoolID"],$_SESSION['SchoolJaar'],$_GET["rapport_klassen_lijst"],$_GET["rapport"],false);
		}
	}
	break;

	default:
	require_once("../classes/spn_rapport.php");
	$r = new spn_rapport();

	if(isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"]) && isset($_GET["rapport_klassen_lijst"]))
	{
		if($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT")
		{
			$result = $r->createrapport($_SESSION["SchoolID"],$_SESSION['SchoolJaar'],$_GET["rapport_klassen_lijst"],$_GET["rapport"],false);
		}
		else if($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING")
		{
			$result = $r->createrapport($_SESSION["SchoolID"],$_SESSION['SchoolJaar'],$_GET["rapport_klassen_lijst"],$_GET["rapport"],false);
		}
	}
	break;

	case '12':
	$level_klas= substr($_GET["rapport_klassen_lijst"],0,1);	
	switch($level_klas)
	{
		case 1:
		require_once("../classes/spn_rapport_school_12.php");
		$r = new spn_rapport_school_12();
		break;
		case 2:
		require_once("../classes/spn_rapport_school_12.php");
		$r = new spn_rapport_school_12();
		break;
		case 3:
		require_once("../classes/spn_rapport_school_12_k_3_4.php");
		$r = new spn_rapport_school_12_k_3_4();
		break;
		case 4:
		require_once("../classes/spn_rapport_school_12_k_3_4.php");
		$r = new spn_rapport_school_12_k_3_4();
		break;

	}

	if(isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"]) && isset($_GET["rapport_klassen_lijst"]))
	{
			$result = $r->createrapport($_SESSION["SchoolID"],$_SESSION['SchoolJaar'],$_GET["rapport_klassen_lijst"],$_GET["rapport"],false);
	}
	break;

	case '13':
	$level_klas= substr($_GET["rapport_klassen_lijst"],0,1);
	switch($level_klas)
	{
		case 1:
		require_once("../classes/spn_rapport_school_12.php");
		$r = new spn_rapport_school_12();
		break;
		case 2:
		require_once("../classes/spn_rapport_school_12.php");
		$r = new spn_rapport_school_12();
		break;
		case 3:
		require_once("../classes/spn_rapport_school_12_k_3_4.php");
		$r = new spn_rapport_school_12_k_3_4();
		break;
		case 4:
		require_once("../classes/spn_rapport_school_12_k_3_4.php");
		$r = new spn_rapport_school_12_k_3_4();
		break;
	}

	if(isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"]) && isset($_GET["rapport_klassen_lijst"]))
	{
		$result = $r->createrapport($_SESSION["SchoolID"],$_SESSION['SchoolJaar'],$_GET["rapport_klassen_lijst"],$_GET["rapport"],false);
	}
	break;

}


ob_flush();


?>
