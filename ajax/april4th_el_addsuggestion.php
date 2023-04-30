
<?php 

if(session_status() == PHP_SESSION_NONE)
	{
		session_start();
	}

/* studentnummer, klas, achternaam, voornaam, geslacht, geboortedatum, geboorteplaats, adres, telefoon, status */
if(true)
//if(isset($_POST["subject"]) && isset($_POST["suggestion"]))
{
	require_once("../classes/spn_suggestions.php");
	$s = new spn_suggestions();	

	$session_schoolid;

	if(isset($_SESSION["SchoolID"]) && isset($_SESSION["UserGUID"]) && isset($_SESSION["User"]))
	{
		$session_schoolid = $_SESSION["SchoolID"];
		$session_userguid = $_SESSION["UserGUID"];
		$session_user = $_SESSION["User"];

		$subject = $_POST["subject"];
		$suggestion = $_POST["suggestion"];
		
		/* function createsuggestion($schoolid,$user,$userguid,$subject,suggestion) */
		$result = $s->createsuggestion($session_schoolid,$session_user,$session_userguid,$subject,$suggestion);
		print $result;
		print $s->mysqlierror;
	}
	else
	{
		/* abort */
		print "0";
		exit();
	}

	
              
}
else
{
	print "-1";
}

?>