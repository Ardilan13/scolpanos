<?php
session_start();
require_once "../classes/DBCreds.php";
require_once "../classes/spn_utils.php";
$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$utils = new spn_utils();
$i_klas = $_POST["verzuim_klassen_lijst"];
$i_start = $_POST["start_date"];
$i_end = $_POST["end_date"];
$i_end = $utils->convertfrommysqldate_new($i_end);
$i_start = $utils->convertfrommysqldate_new($i_start);
$schoolid = $_SESSION["SchoolID"];
$good = ['A', 'L', 'M', 'X', 'T', 'U', 'S'];
?>

<table class="table table-bordered table-colored table-houding">
    <caption>Verzuim rapport</caption>
    <thead>
        <th>Name</th>
        <th>Lastname</th>
        <th>A</th>
        <th>L</th>
        <th>X</th>
        <th>S</th>
        <th>M</th>
        <th>U</th>
        <th>T</th>
    </thead>
    <tbody>


        <?php $students = "SELECT id,firstname,lastname FROM students WHERE class = '$i_klas' AND schoolid = $schoolid ORDER BY lastname ASC";
        $resultado1 = mysqli_query($mysqli, $students);
        while ($row1 = mysqli_fetch_assoc($resultado1)) {
            $id = $row1["id"];
            foreach ($good as $key => $value) {
                $$value = 0;
            }

            $verzuim_hs = "SELECT p1,p2,p3,p4,p5,p6,p7,p8,p9 FROM le_verzuim_hs as v INNER JOIN students as s WHERE v.studentid = s.id AND v.klas = '$i_klas' AND v.datum >= '$i_start' AND v.datum <= '$i_end' AND s.id = $id ORDER BY datum ASC";
        ?>
            <?php $resultado = mysqli_query($mysqli, $verzuim_hs);
            while ($row = mysqli_fetch_assoc($resultado)) {
                foreach ($good as $key => $value) {
                    if ($row["p1"] == $value) {
                        $$value++;
                    }
                    if ($row["p2"] == $value) {
                        $$value++;
                    }
                    if ($row["p3"] == $value) {
                        $$value++;
                    }
                    if ($row["p4"] == $value) {
                        $$value++;
                    }
                    if ($row["p5"] == $value) {
                        $$value++;
                    }
                    if ($row["p6"] == $value) {
                        $$value++;
                    }
                    if ($row["p7"] == $value) {
                        $$value++;
                    }
                    if ($row["p8"] == $value) {
                        $$value++;
                    }
                    if ($row["p9"] == $value) {
                        $$value++;
                    }
                }
            ?>
            <?php
            } ?>
            <tr>
                <td><?php echo $row1["firstname"] ?></td>
                <td><?php echo $row1["lastname"] ?></td>
                <td><?php echo $A ?></td>
                <td><?php echo $L ?></td>
                <td><?php echo $X ?></td>
                <td><?php echo $S ?></td>
                <td><?php echo $M ?></td>
                <td><?php echo $U ?></td>
                <td><?php echo $T ?></td>

            </tr>
        <?php mysqli_free_result($resultado);
        }
        ?>
    </tbody>
</table>