<?php

ob_start();

require_once("../classes/spn_overzicht.php");
require_once("../config/app.config.php");
require_once "../classes/DBCreds.php";
$r = new spn_overzicht();


if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

/* if(isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"]) && isset($_GET["klas_list"]))
	{
		if($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT")
		{
			$result = $r->createrapport($_SESSION["SchoolID"],$_SESSION['SchoolJaar'],$_GET["klas_list"]);
		}
		else if($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING")
		{
			$result = $r->createrapport($_SESSION["SchoolID"],$_SESSION['SchoolJaar'],$_GET["klas_list"]);
		}
	} */

header("Content-Type: application/vnd.ms-excel; charset=iso-8859-1");
header("Content-Disposition: attachment; filename=leerling_export.xls");

$schoolid = $_SESSION["SchoolID"];
$klas = $_GET["klas_list"];
$schooljaar = $_SESSION['SchoolJaar'];
$schoolname = $_SESSION['schoolname'];

echo utf8_decode("<table border='0'> 
						
						<tr style='height: 20px; '></tr>						

						<tr>
							<td></td>
							<td  style='padding:20px 0 0 0; font-weight:bold;'>LEERLINGENOVERZICHT</td>
							<td></td>
							<td></td>
							<td style='font-weight:bold;'>Schooljaar:</td>
							<td> $schooljaar</td>
							<td></td>
							<td style='font-weight:bold;'>Klas:</td>
							<td>$klas</td>
							<td></td>
						</tr>

						<tr style='height: 50px; '></tr>						

						<tr>
							<td></td>
							<td></td>
							<td style='vertical-align:text-top;color: blue;font-weight:bold;'>$schoolname</td>
							<td></td>
							<td></td>
							<td></td>
							<td  style='font-weight:bold;'>Par. dir.:</td>
							<td></td>
							<td></td>
							<td style='font-weight:bold;'>Aantal blz: 1</td>
							<td style='font-weight:bold;'>blz: 1</td>
						</tr>	

						<tr style='height: 20px; '></tr>						

						<tr> 
							<td style='font-weight:bold; border:1px solid #000;border-bottom:none;padding:10px;'>nr.</td>
							<td style='font-weight:bold; border:1px solid #000;border-bottom:none;padding:10px;'>Naam</td>
							<td style='font-weight:bold; border:1px solid #000;border-bottom:none;padding:10px;'>Voornamem</td>
							<td style='font-weight:bold; border:1px solid #000;border-bottom:none;padding:10px;'>v/m</td>
							<td style='font-weight:bold; border:1px solid #000;border-bottom:none;padding:10px;'>Id.</td>
							<td style='font-weight:bold; border:1px solid #000;border-bottom:none;padding:10px;'>huisadres*</td>
							<td style='font-weight:bold; border:1px solid #000;border-bottom:none;padding:10px;'>Voertaal</td>
							<td style='font-weight:bold; border:1px solid #000;border-bottom:none;padding:10px;'>Geb.</td>
							<td style='font-weight:bold; border:1px solid #000;border-bottom:none;padding:10px;'>Instr.</td>
							<td style='font-weight:bold; border:1px solid #000;padding:10px;'>Tutor 1/voogd</td>
							<td style='font-weight:bold; border:1px solid #000;padding:10px;'>Tutor 2/voogd</td>
							<td style='font-weight:bold; border:1px solid #000;border-bottom:none;padding:10px;'>Tel student</td>
							<td style='font-weight:bold; border:1px solid #000;border-bottom:none;padding:10px;'>Tel moeder</td>
							<td style='font-weight:bold; border:1px solid #000;border-bottom:none;padding:10px;'>Tel vader</td>

						</tr>
						
						<tr> 
							<td style='font-weight:bold; border:1px solid #000;border-top:none;padding:10px;'></td>
							<td style='font-weight:bold; border:1px solid #000;border-top:none;padding:10px;'></td>
							<td style='font-weight:bold; border:1px solid #000;border-top:none;padding:10px;'></td>
							<td style='font-weight:bold; border:1px solid #000;border-top:none;padding:10px;'></td>
							<td style='font-weight:bold; border:1px solid #000;border-top:none;padding:10px;'></td>
							<td style='font-weight:bold; border:1px solid #000;border-top:none;padding:10px;'></td>
							<td style='font-weight:bold; border:1px solid #000;border-top:none;padding:10px;'>thuis*</td>
							<td style='font-weight:bold; border:1px solid #000;border-top:none;padding:10px;'>land*</td>
							<td style='font-weight:bold; border:1px solid #000;border-top:none;padding:10px;'>jaar*</td>
							<td style='font-weight:bold; border:1px solid #000;padding:10px;'>huidig beroep</td>
							<td style='font-weight:bold; border:1px solid #000;padding:10px;'>huidig beroep</td>
							<td style='font-weight:bold; border:1px solid #000;border-top:none;padding:10px;'></td>
							<td style='font-weight:bold; border:1px solid #000;border-top:none;padding:10px;'></td>
							<td style='font-weight:bold; border:1px solid #000;border-top:none;padding:10px;'></td>
						</tr>
						");

$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');
$sql_query = "SELECT id,idnumber,studentnumber, lastname, firstname, sex,
		studentnumber,
		address,
		phone1,
		enrollmentdate,
		ne,en,sp,pa,voertaalanders,
		birthplace,
		nationaliteit,
		enrollmentdate,
		dob,
		@id_family:= id_family as idfamily,
		(select position_company from contact where id_family = @id_family limit 1) as mother,
		(select position_company from contact where id_family = @id_family limit 1,1) as father,
		(select mobile_phone from contact where id_family = @id_family limit 1) as mobiel_moeder,
		(select home_phone from contact where id_family = @id_family limit 1) as Huis_Telefoon_moeder,
		(select work_phone from contact where id_family = @id_family limit 1) as Work_tel_moeder,
		(select mobile_phone from contact where id_family = @id_family limit 1,1) as mobiel_vader,
		(select home_phone from contact where id_family = @id_family limit 1,1) as Huis_Telefoon_vader,
		(select work_phone from contact where id_family = @id_family limit 1,1) as Work_tel_vader
		from students where schoolid = '$schoolid' and class = '$klas' ORDER BY sex ASC, lastname ASC, firstname ASC";
$x = 1;
$resultado1 = mysqli_query($mysqli, $sql_query);
while ($row = mysqli_fetch_assoc($resultado1)) {
	$date = strtotime($row['enrollmentdate']);
	$year = date("Y", $date);
	echo utf8_decode("<tr>
							<td style='border:1px solid #000;'>" . $x . "</td>
							<td style='border:1px solid #000;'>" . $row["lastname"] . "</td>
							<td style='border:1px solid #000;'>" . $row["firstname"] . "</td>
							<td style='border:1px solid #000;'>" . $row["sex"] . "</td>
							<td style='border:1px solid #000;'>" . $row["idnumber"] . "</td>
							<td style='border:1px solid #000;'>" . $row["address"] . "</td>
							<td style='border:1px solid #000;'>" . $row["voertaalanders"] . "</td>
							<td style='border:1px solid #000;'>" . $row["birthplace"] . "</td>
							<td style='border:1px solid #000;'>" . $year . "</td>
							<td style='border:1px solid #000;'>" . $row["father"] . "</td>
							<td style='border:1px solid #000;'>" . $row["mother"] . "</td>
							<td style='border:1px solid #000;'>" . $row["phone1"] . "</td>
							<td style='border:1px solid #000;'>" . $row["mobiel_moeder"] . "</td>
							<td style='border:1px solid #000;'>" . $row["mobiel_vader"] . "</td>
						</tr>");
	$x++;
}


echo "</table>";



ob_flush();
