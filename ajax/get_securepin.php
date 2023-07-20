<?php
require_once "../classes/DBCreds.php";
session_start();
$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$klas = $_POST["rapport_klassen_lijst"];
$schoolid = $_SESSION["SchoolID"];
$datum = date('Y-m-d');
$schooljaar = $_POST["schooljaar_rapport"]; ?>

<?php
echo utf8_decode("<table class='table table-bordered table-colored table-houding'> 
<thead>
<tr> 
    <th>Klas</th>
    <th>Student Number</th>
    <th>Firstname</th>
    <th>Lastname</th>
    <th>SecurePin</th>
</tr>
</thead>
<tbody>
");
if ($klas == 'All') {
    $sql_query = "select id, class, studentnumber, firstname, lastname, securepin from students where schoolid = $schoolid order by class,lastname,firstname;";
} else {
    $sql_query = "select id, class, studentnumber, firstname, lastname, securepin from students where schoolid = $schoolid and class = '$klas' order by class,lastname";
}
$resultado1 = mysqli_query($mysqli, $sql_query);
while ($row = mysqli_fetch_assoc($resultado1)) {
    echo utf8_decode("<tr>
						<td>" . $row["class"] . "</td>
						<td>" . $row["studentnumber"] . "</td>
						<td>" . utf8_encode($row["firstname"]) . "</td>
						<td>" . utf8_encode($row["lastname"]) . "</td>
						<td>" . $row["securepin"] . "</td>
					</tr>");
}
echo "</tbody></table>";
