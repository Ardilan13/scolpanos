<?php

ob_start();

require_once "../classes/DBCreds.php";

header("Content-Type: application/vnd.ms-excel; charset=iso-8859-1");
header("Content-Disposition: attachment; filename=Secure_Pin.xls");

$schoolid = $_GET["scol_id"];
$klas_in = $_GET["klas_list"];

echo utf8_decode("<table border='1'> 
						<tr> 
							<td style='font-weight:bold; background: lightgrey;padding:10px;'>Klas</td>
							<td style='font-weight:bold; background: lightgrey;padding:10px;'>Student Number</td>
							<td style='font-weight:bold; background: lightgrey;padding:10px;'>Firstname</td>
							<td style='font-weight:bold; background: lightgrey;padding:10px;'>Lastname</td>
							<td style='font-weight:bold; background: lightgrey;padding:10px;'>SecurePin</td>
						</tr>
						");

$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');
if ($klas_in == 'All') {
    $sql_query = "select id, class, studentnumber, firstname, lastname, securepin from students where schoolid = $schoolid order by class;";
} else {
    $sql_query = "select id, class, studentnumber, firstname, lastname, securepin from students where schoolid = $schoolid and class = '$klas_in' order by class,lastname";
}
$resultado1 = mysqli_query($mysqli, $sql_query);
while ($row = mysqli_fetch_assoc($resultado1)) {

    echo utf8_decode("<tr>
						<td>" . $row["class"] . "</td>
						<td>" . $row["studentnumber"] . "</td>
						<td>" . $row["firstname"] . "</td>
						<td>" . $row["lastname"] . "</td>
						<td>" . $row["securepin"] . "</td>
					</tr>");
}


echo "</table>";

ob_flush();
