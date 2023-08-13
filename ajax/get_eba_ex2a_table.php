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
            <th class="definitiet">(mond. Ex)</th>
            <th class="definitiet">Schr. Ex.</th>
            <th class="definitiet">Eind Cijfer</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $get_students = "SELECT p.id,
        (SELECT lastname FROM students WHERE id = p.studentid) as lastname,
        (SELECT firstname FROM students WHERE id = p.studentid) as name,
        CASE
          WHEN e.e1 = '$code' THEN 'e1'
          WHEN e.e2 = '$code' THEN 'e2'
          WHEN e.e3 = '$code' THEN 'e3'
          WHEN e.e4 = '$code' THEN 'e4'
          WHEN e.e5 = '$code' THEN 'e5'
          WHEN e.e6 = '$code' THEN 'e6'
          WHEN e.e7 = '$code' THEN 'e7'
          WHEN e.e8 = '$code' THEN 'e8'
          WHEN e.e9 = '$code' THEN 'e9'
          WHEN e.e10 = '$code' THEN 'e10'
          WHEN e.e11 = '$code' THEN 'e11'
          WHEN e.e12 = '$code' THEN 'e12'
        END AS et
      FROM eba_ex e INNER JOIN personalia p ON e.id_personalia = p.id
      WHERE e.schoolid = '$schoolid' 
        AND e.schooljaar = '$schooljaar' 
        AND e.type = 1
        AND ('$code' IN (e.e1, e.e2, e.e3, e.e4, e.e5, e.e6, e.e7, e.e8, e.e9, e.e10, e.e11, e.e12)) 
      ORDER BY e.id_personalia";
        $result = mysqli_query($mysqli, $get_students);
        while ($row1 = mysqli_fetch_assoc($result)) {
            $personalia = $row1["id"];
            $e = $row1["et"]; ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $row1["lastname"]; ?></td>
                <td><?php echo $row1["name"]; ?></td>
                <?php
                $cijfers = "SELECT $e as cijfer FROM eba_ex WHERE id_personalia = $personalia AND (type = 2 OR type = 3)";
                $result2 = mysqli_query($mysqli, $cijfers);
                $row2 = mysqli_fetch_assoc($result2);
                ?>
                <td><?php echo $row2['cijfer'] ?></td>
                <td></td>
                <td><?php echo $row2['cijfer'] ?></td>
            </tr>
        <?php
            $i++;
        }
        ?>
    </tbody>
</table>