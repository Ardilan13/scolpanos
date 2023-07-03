
<?php
require_once("../classes/spn_leerling.php");
require_once("../classes/spn_utils.php");

$l = new spn_leerling();
$u = new spn_utils();


if ($_FILES["pictureToUpload"] != "") {
	chdir("..");
	$target_dir = getcwd() . "/profile_students/";
	// $document_id = basename($_FILES["pictureToUpload"]["name"]);
	$document_id = $_POST["studentnummer"];
	$target_file = $target_dir . $document_id . "-" . $_POST["school_id"];
	move_uploaded_file($_FILES['pictureToUpload']['tmp_name'], $target_file);
}

$_DateTime = date("Y-m-d H:i:s");
$userguid = $u->CreateGUID();

print $l->create_leerling(
	$userguid,
	date("Y-m-d H:i:s"),
	$_POST["school_id"],
	$_POST["identeits"],
	$_POST["studentnummer"],
	$_POST["student-klas"],
	($_POST["datum_inschrijving"] != "" ? $u->converttomysqldate($_POST["datum_inschrijving"]) : $u->converttomysqldate($_DateTime)),
	$_POST["voornamen"],
	$_POST["achternaam"],
	$_POST["geslacht"],
	($_POST["geboortedatum"] != "" ? $u->converttomysqldate($_POST["geboortedatum"]) : ""),
	$_POST["geboorteplaats"],
	$_POST["adres"],
	$_POST["azv_nr"],
	($_POST["expiredatum"] != "" ? $u->converttomysqldate($_POST["expiredatum"]) : ""),
	$_POST["telefoon"],
	$_POST["tellthuis"],
	'#CC2AA', //$colorcode
	$_POST["status"],
	$_POST["rooepnaam"],
	$_POST["nationaliteiten"],
	$_POST["verzorgernaschooltijd"],
	$_POST["email"],
	$_POST["ned_val"],
	$_POST["en_val"],
	$_POST["sp_val"],
	$_POST["pap_val"],
	$_POST["vo_val"],

	$_POST["spraak_val"],
	$_POST["gehoor_val"],
	$_POST["gezicht_val"],
	$_POST["motoriek_val"],

	$_POST["huisarts"],
	$_POST["telnr"],
	$_POST["anders"],
	$_POST["voorletter"],
	$_POST["telefoonnoodgeval"],
	$_POST["vorigeschool"],
	$_POST["bizjonder"],
	$_POST["notes"],
	$_POST["leerling_family"],
	$_POST["profiel"]
);
?>
