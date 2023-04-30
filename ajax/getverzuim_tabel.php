<?php



require_once("../classes/spn_user_hs_account.php");

require_once("../classes/spn_verzuim.php");

require_once("../config/app.config.php");

$s = new spn_verzuim();





$baseurl = appconfig::GetBaseURL();





if(session_status() == PHP_SESSION_NONE)

{

	session_start();

}



$u = new spn_user_hs_account;

$IsTutor = $u->check_mentor_in_klas($_GET["klas"],$_SESSION["UserGUID"],"Klas", appconfig::GetDummy()) ;

if(isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"])  && isset($_GET["klas"]) )

{



	if($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT")

	{
			print $s->listverzuim($_SESSION["SchoolID"],$_GET["klas"],$_GET["datum"],"");
	}

	else if($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING")

	{

		print $s->listverzuim($_SESSION["SchoolID"],$_GET["klas"],$_GET["datum"],"");



	}

}





?>

<script>

$("#loader_spn").addClass('hidden');

</script>

