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
$students = array();
$cijfers = array();
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
            <th class="definitiet">Systeem</th>
            <th class="definitiet">Definitief</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $disabled = $_SESSION["UserRights"] == "BEHEER" ? "" : "disabled";
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

                $cuenta_pri = 0;
                $cuenta = 0;
                $get_cijfers = "SELECT (SELECT volledigenaamvak FROM le_vakken WHERE ID = c.vak AND volgorde > 0) as vak,c.gemiddelde FROM le_cijfers c WHERE c.studentid = '$id' AND c.klas = '$klas' AND c.rapnummer = $rapport AND c.schooljaar = '$schooljaar' AND c.gemiddelde is not NULL;";
                $result2 = mysqli_query($mysqli, $get_cijfers);
                if ($result2->num_rows > 0) {
                    while ($row3 = mysqli_fetch_assoc($result2)) {
                        $cijfers[$id] = $cijfers[$id] + $row3["gemiddelde"];
                        if ($row3["vak"] == "ne" || $row3["vak"] == "en" || $row3["vak"] == "wi") {
                            if ($row3["gemiddelde"] == 0 || $row3["gemiddelde"] >= 5.5 || $row3["gemiddelde"] == NULL) {
                                $cuenta_pri = $cuenta_pri + 0;
                            } else if ($row3["gemiddelde"] < 1) {
                                $cuenta_pri = $cuenta_pri + 6;
                            } else if ($row3["gemiddelde"] < 2) {
                                $cuenta_pri = $cuenta_pri + 5;
                            } else if ($row3["gemiddelde"] < 3) {
                                $cuenta_pri = $cuenta_pri + 4;
                            } else if ($row3["gemiddelde"] < 4) {
                                $cuenta_pri = $cuenta_pri + 3;
                            } else if ($row3["gemiddelde"] < 5) {
                                $cuenta_pri = $cuenta_pri + 2;
                            } else if ($row3["gemiddelde"] < 5.5) {
                                $cuenta_pri = $cuenta_pri + 1;
                            }
                        } else if ($row3["vak"] != "rk" && $row3["vak"] != NULL) {
                            if ($row3["gemiddelde"] == 0 || $row3["gemiddelde"] >= 5.5 || $row3["gemiddelde"] == NULL) {
                                $cuenta = $cuenta + 0;
                            } else if ($row3["gemiddelde"] < 1) {
                                $cuenta = $cuenta + 6;
                            } else if ($row3["gemiddelde"] < 2) {
                                $cuenta = $cuenta + 5;
                            } else if ($row3["gemiddelde"] < 3) {
                                $cuenta = $cuenta + 4;
                            } else if ($row3["gemiddelde"] < 4) {
                                $cuenta = $cuenta + 3;
                            } else if ($row3["gemiddelde"] < 5) {
                                $cuenta = $cuenta + 2;
                            } else if ($row3["gemiddelde"] < 5.5) {
                                $cuenta = $cuenta + 1;
                            }
                        }
                    }
                }
                ?>
                <td><input <?php echo $disabled; ?> type="text" onchange="savebespreking(&#39;<?php echo $schooljaar . '&#39;,&#39;' . $klas . '&#39;,' . $id . ', ' . $rapport . ',' . $i; ?>)" id="opmerking_<?php echo $i; ?>" class="opmerking_input" value="<?php echo $opmerking1; ?>"></td>
                <td class="text-center">
                    <?php if ($cijfers[$id] < 71 || $cuenta_pri > 2 || ($cuenta + $cuenta_pri) > 3) {
                        echo "<label style='margin: 0;' class='text-danger'>O</label>";
                    } else {
                        echo "<label style='margin: 0;' class='text-primary'>V</label>";
                    } ?>

                </td>
                <td><input <?php echo $disabled; ?> type="text" onchange="savebespreking(&#39;<?php echo $schooljaar . '&#39;,&#39;' . $klas . '&#39;,' . $id . ', ' . $rapport . ',' . $i; ?>)" id="definitiet_<?php echo $i; ?>" class="definitiet_input" value="<?php echo strtoupper($opmerking3); ?>"></td>
            </tr>
        <?php $i++;
        }
        ?>
    </tbody>
</table>
<script>
    function savebespreking(schooljaar, klas, student, rap, i) {
        var opmerking = document.getElementById("opmerking_" + i).value;
        var definitiet = document.getElementById("definitiet_" + i).value;
        if (definitiet != "v" && definitiet != "V" && definitiet != "o" && definitiet != "O") {
            definitiet = "";
        }
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