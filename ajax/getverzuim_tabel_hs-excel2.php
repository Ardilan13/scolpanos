<?php
require_once "../classes/DBCreds.php";
require_once "../classes/spn_utils.php";
$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$utils = new spn_utils();
$i_klas = $_POST["klas1"];
$i_start = $_POST["start_date"];
$i_end = $_POST["end_date"];
$i_end = $utils->converttomysqldate($i_end);
$i_start = $utils->converttomysqldate($i_start);

$verzuim_hs = "SELECT datum,p1,p2,p3,p4,p5,p6,p7,p8,p9,p10,s.firstname,s.lastname FROM le_verzuim_hs as v INNER JOIN students as s WHERE v.studentid = s.id AND v.klas = '$i_klas' AND v.datum >= '$i_start' AND v.datum <= '$i_end'";
$x = 1;
header("Content-Type: application/vnd.ms-excel; charset=iso-8859-1");
header("Content-Disposition: attachment; filename=KLASSENBOEK-$i_klas.xls");
?>
<table border="1">
    <caption>Verzuim rapport</caption>
    <tr>
        <th>Klas:</th>
        <th><?php echo $i_klas ?></th>
    </tr>
    <tr>
    <th>Datum:</th>
        <th><?php echo $i_start ?></th>
        <th>to</th>
        <th><?php echo $i_end ?></th>
    </tr>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Lastname</th>
        <th>Datum</th>
        <th>p1</th>
        <th>p2</th>
        <th>p3</th>
        <th>p4</th>
        <th>p5</th>
        <th>p6</th>
        <th>p7</th>
        <th>p8</th>
        <th>p9</th>
        <th>p10</th>
    </tr>
    <?php $resultado = mysqli_query($mysqli, $verzuim_hs);
while ($row = mysqli_fetch_assoc($resultado)) {
    $row["datum"] = $utils->converttomysqldate($row["datum"]);
    if (($row["p1"] != "0" || $row["p2"] != "0" || $row["p3"] != "0" || $row["p4"] != "0" || $row["p5"] != "0" || $row["p6"] != "0" || $row["p7"] != "0" || $row["p8"] != "0" || $row["p9"] != "0" || $row["p10"] != "0")) {?>
        <tr>
            <td><?php echo $x ?></td>
            <td><?php echo $row["firstname"] ?></td>
            <td><?php echo $row["lastname"] ?></td>
            <td><?php echo $row["datum"] ?></td>
            <td><?php echo $row["p1"] ?></td>
            <td><?php echo $row["p2"] ?></td>
            <td><?php echo $row["p3"] ?></td>
            <td><?php echo $row["p4"] ?></td>
            <td><?php echo $row["p5"] ?></td>
            <td><?php echo $row["p6"] ?></td>
            <td><?php echo $row["p7"] ?></td>
            <td><?php echo $row["p8"] ?></td>
            <td><?php echo $row["p9"] ?></td>
            <td><?php echo $row["p10"] ?></td>
        </tr>

        <?php $x = $x + 1;}}
mysqli_free_result($resultado);?>
</table>
