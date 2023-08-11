<?php
require_once "../classes/DBCreds.php";
session_start();
$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$code = $_POST["user"];
$schoolid = $_SESSION["SchoolID"];
$schooljaar = $_SESSION["SchoolJaar"];
$i = 1;
?>
<style>
    .opmerking {
        width: 50%;
    }

    .opmerking_input {
        width: 100%;
    }

    .definitiet {
        width: 3% !important;
    }

    .definitiet_input {
        width: 5rem;
    }
</style>
<table class="table table-bordered table-colored table-houding">
    <thead>
        <tr>
            <th>Nr</th>
            <th>Achternaam</th>
            <th>Alle Voornamen voluit</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $get_students = "SELECT p.id,
        (SELECT lastname FROM students WHERE id = p.studentid) as lastname,
        (SELECT firstname FROM students WHERE id = p.studentid) as name
      FROM eba_ex e INNER JOIN personalia p ON e.id_personalia = p.id
      WHERE e.schoolid = '$schoolid' 
        AND e.schooljaar = '$schooljaar' 
        AND e.type = 1
        AND ('$code' IN (e.e1, e.e2, e.e3, e.e4, e.e5, e.e6, e.e7, e.e8, e.e9, e.e10, e.e11, e.e12)) 
      ORDER BY e.id_personalia";
        $result = mysqli_query($mysqli, $get_students);
        while ($row1 = mysqli_fetch_assoc($result)) {
            $personalia = $row1["id"]; ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $row1["lastname"]; ?></td>
                <td><?php echo $row1["name"]; ?></td>
            </tr>
        <?php
            $i++;
        }
        ?>
    </tbody>
</table>