<?php
require_once "../classes/DBCreds.php";
session_start();
$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$klas = $_POST["houding_klassen_lijst"];
$rapport = $_POST["cijfers_rapporten_lijst"];
$schoolid = $_SESSION["SchoolID"];
$datum = date('Y-m-d');
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
            <th>ID</th>
            <th>Naam</th>
            <th class="opmerking">Opmerking</th>
            <th>Systeem</th>
            <th class="definitiet">Definitief</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $get_students = "SELECT id,firstname,lastname FROM students WHERE schoolid = '$schoolid' AND class = '$klas' ORDER BY lastname, firstname;";
        $result = mysqli_query($mysqli, $get_students);
        while ($row1 = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $row1["lastname"] . ", " . $row1["firstname"]; ?></td>
                <?php
                $id = $row1["id"];
                $get_opmerking = "SELECT opmerking1,opmerking2,opmerking3 FROM opmerking WHERE SchoolID = '$schoolid' AND klas = '$klas' AND studentid = '$id' AND rapport = $rapport AND schooljaar ='$schooljaar' LIMIT 1;";
                $result1 = mysqli_query($mysqli, $get_opmerking);
                if ($result1->num_rows > 0) {
                    $row2 = mysqli_fetch_assoc($result1);
                    $opmerking1 = $row2["opmerking1"];
                    $opmerking2 = $row2["opmerking2"];
                    $opmerking3 = $row2["opmerking3"];
                } else {
                    $opmerking1 = "";
                    $opmerking2 = "";
                    $opmerking3 = "";
                }
                ?>
                <td><input type="text" onchange="savebespreking(&#39;<?php echo $schooljaar . '&#39;,&#39;' . $klas . '&#39;,' . $id . ', ' . $rapport . ',' . $i; ?>)" id="opmerking_<?php echo $i; ?>" class="opmerking_input" value="<?php echo $opmerking1; ?>"></td>
                <td><?php echo $opmerking2; ?></td>
                <td><input type="text" onchange="savebespreking(&#39;<?php echo $schooljaar . '&#39;,&#39;' . $klas . '&#39;,' . $id . ', ' . $rapport . ',' . $i; ?>)" id="definitiet_<?php echo $i; ?>" class="definitiet_input" value="<?php echo $opmerking3; ?>"></td>
            </tr>
        <?php $i++;
        } ?>
    </tbody>
</table>
<script>
    function savebespreking(schooljaar, klas, student, rap, i) {
        var opmerking = document.getElementById("opmerking_" + i).value;
        var definitiet = document.getElementById("definitiet_" + i).value;
        var data = {
            "studentid": student,
            "schooljaar": schooljaar,
            "klas": klas,
            "rap": rap,
            "opmerking": opmerking,
            "definitiet": definitiet,
        };
        $.ajax({
            url: "ajax/save_bespreking.php",
            data: data,
            type: "POST",
            dataType: "text",
            success: function(text) {
                console.log(text);
            },
            error: function(xhr, status, errorThrown) {
                alert("error");
            },
        });
    }
</script>