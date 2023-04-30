
<?php 

/* studentnummer, klas, achternaam, voornaam, geslacht, geboortedatum, geboorteplaats, adres, telefoon, status */
if(isset($_POST["studentnummer"]) && isset($_POST["klas"]) && isset($_POST["achternaam"]) && isset($_POST["voornaam"]) && isset($_POST["geslacht"]) && isset($_POST["geboortedatum"]) && isset($_POST["geboorteplaats"]) && isset($_POST["adres"]) && isset($_POST["telefoon"]) && isset($_POST["status"]))
{
	require_once("../classes/spn_leerling.php");
	require_once("../classes/spn_utils.php");

	$l = new spn_leerling();
	$u = new spn_utils();

	if(session_status() == PHP_SESSION_NONE)
	{
		session_start();
	}


	$session_schoolid;

	if($_SESSION["SchoolID"])
	{
		$session_schoolid = $_SESSION["SchoolID"];
	}
	else
	{
		/* abort */
		print "-1";
		exit();
	}

	/* function createleerling($schoolid,$idnumber,$studentnumber,$class,$enrollmentdate,$firstname,$lastname,$sex,$dob,$birthplace,$address,$azvnumber,$azvexpiredate,$phone1,$phone2,$colorcode) */
	$result =  $l->createleerling($session_schoolid,"",$_POST["studentnummer"],$_POST["klas"],$u->getdatetime(),$_POST["voornaam"],$_POST["achternaam"],$_POST["geslacht"],$_POST["geboortedatum"],$_POST["geboorteplaats"],$_POST["adres"],"","",$_POST["telefoon"],"","#CC2AA");

	$leerling_last_insert_id = $l->last_insert_id;
	print "Save Successfull, insert id is: " . $l->last_insert_id;

require_once("../classes/spn_avi.php");
$a = new spn_avi();
$a->createavi($leerling_last_insert_id,"2015-2016",0,$_POST["klas"],"spn",null,null,null,"","",null,null,null,"","",null,null,null,"","");

require_once("../classes/spn_houding.php");

require_once("../classes/spn_vakken.php");
require_once("../classes/spn_cijfers.php");
$h = new spn_houding();
$h->createhouding($leerling_last_insert_id,"2015-2016",1,$_POST["klas"],"spn",0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
$h->createhouding($leerling_last_insert_id,"2015-2016",2,$_POST["klas"],"spn",0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
$h->createhouding($leerling_last_insert_id,"2015-2016",3,$_POST["klas"],"spn",0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);


$v = new spn_vakken();


$vakkenarray = $v->getvakken($session_schoolid,$_POST["klas"]);

if (count($vakkenarray)>0) {
	//var_dump($vakkenarray);
  foreach ($vakkenarray as $Xvak) {
  $c = new spn_cijfers();
  print "last insert id  "  . $l->last_insert_id;
  				//function createcijfers($studentid,$schooljaar,$rapnummer,$vak,$klas,$user,$c1,$c2,$c3,$c4,$c5,$c6,$c7,$c8,$c9,$c10,$c11,$c12,$c13,$c14,$c15,$c16,$c17,$c18,$c19,$c20,$gemiddelde)
                $c->createcijfers($leerling_last_insert_id,"2015-2016",1,$Xvak,$_POST["klas"],"spn",null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
                $c->createcijfers($leerling_last_insert_id,"2015-2016",2,$Xvak,$_POST["klas"],"spn",null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
                $c->createcijfers($leerling_last_insert_id,"2015-2016",3,$Xvak,$_POST["klas"],"spn",null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
  }
              
        
}	
}
else
{
	print "0";
}

?>