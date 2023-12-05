<?php
require_once "../classes/DBCreds.php";
session_start();
$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$klas = $_POST["houding_klassen_lijst"];
$level_klas = substr($klas, 0, 1);
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
<?php if ($level_klas == 4) { ?>
    <label>O=Onvlodoende, V=Voldoende, G=Geslaagd, A=Afgewezen, T=Teruggetrokken</label>
<?php } ?>
<table class="table table-bordered table-colored table-houding">
    <input id="level_klas" type="text" hidden value="<?php echo $level_klas; ?>">
    <thead>
        <tr>
            <th>ID</th>
            <th>Naam</th>
            <th class="opmerking">Opmerking</th>
            <?php if ($rapport == 4 && $level_klas != 4) { ?>
                <th>Andere Schooltype</th>
                <th>Over Naar Ciclo</th>
                <th>Niet Over</th>
            <?php } ?>
            <?php if ($level_klas != 4) { ?>
                <th class="definitiet">Systeem</th>
            <?php } ?>
            <th class="definitiet">Beoordeling</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // $disabled = $_SESSION["UserRights"] == "BEHEER" ? "" : "disabled";
        $disabled = "disabled";
        $user = $_SESSION["UserGUID"];
        if ($level_klas == 4) {
            $get_mentor = "SELECT id FROM user_hs WHERE SchoolID = '$schoolid' AND klas = '4' AND tutor = 'Yes' AND user_GUID = '$user' LIMIT 1;";
        } else {
            $get_mentor = "SELECT id FROM user_hs WHERE SchoolID = '$schoolid' AND klas = '$klas' AND tutor = 'Yes' AND user_GUID = '$user' LIMIT 1;";
        }
        $result_mentor = mysqli_query($mysqli, $get_mentor);
        if ($result_mentor->num_rows > 0 || $_SESSION["UserRights"] == "BEHEER") {
            $disabled = "";
        } else {
            $disabled = "disabled";
        }


        $get_students = "SELECT id,firstname,lastname FROM students WHERE schoolid = '$schoolid' AND class = '$klas' ORDER BY lastname, firstname;";
        $result = mysqli_query($mysqli, $get_students);
        while ($row1 = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $row1["lastname"] . ", " . $row1["firstname"]; ?></td>
                <?php
                $id = $row1["id"];
                $get_opmerking = "SELECT opmerking1,opmerking2,opmerking3,advies,ciclo FROM opmerking WHERE SchoolID = '$schoolid' AND klas = '$klas' AND studentid = '$id' AND rapport = $rapport AND schooljaar ='$schooljaar' LIMIT 1;";
                $result1 = mysqli_query($mysqli, $get_opmerking);
                if ($result1->num_rows > 0) {
                    $row2 = mysqli_fetch_assoc($result1);
                    $opmerking1 = $row2["opmerking1"];
                    $radio1 = $row2["opmerking2"];
                    $opmerking3 = $row2["opmerking3"];
                    $radio2 = $row2["advies"];
                    $radio3 = $row2["ciclo"];
                } else {
                    $opmerking1 = "";
                    $opmerking3 = "";
                    $radio1 = "";
                    $radio2 = "";
                    $radio3 = "";
                }

                $radio1 = $radio1 == 1 ? "checked" : "";
                $radio2 = $radio2 == 1 ? "checked" : "";
                $radio3 = $radio3 == 1 ? "checked" : "";

                $cuenta_pri = 0;
                $cuenta = 0;
                $last_vak = "";

                if ($level_klas != 4) {
                    if ($rapport == 4) {
                        $get_cijfers = "SELECT v.volledigenaamvak as vak,ROUND(SUM(c.gemiddelde)/COUNT(c.rapnummer)) as gemiddelde FROM le_cijfers c INNER JOIN le_vakken v ON v.ID = c.vak WHERE v.volgorde > 0 AND c.studentid = '$id' AND c.klas = '$klas' AND c.schooljaar = '$schooljaar' AND c.gemiddelde is not NULL GROUP BY vak ORDER BY c.studentid,vak;";
                    } else {
                        $get_cijfers = "SELECT (SELECT volledigenaamvak FROM le_vakken WHERE ID = c.vak AND volgorde > 0) as vak,c.gemiddelde FROM le_cijfers c WHERE c.studentid = '$id' AND c.klas = '$klas' AND c.rapnummer = $rapport AND c.schooljaar = '$schooljaar' AND c.gemiddelde is not NULL;";
                    }

                    $result2 = mysqli_query($mysqli, $get_cijfers);
                    if ($result2->num_rows > 0) {
                        while ($row3 = mysqli_fetch_assoc($result2)) {
                            if ($row3["vak"] != "rk" && $row3["vak"] != NULL) {
                                $cijfers[$id] = $cijfers[$id] + $row3["gemiddelde"];
                            }
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
                }
                ?>
                <td><input <?php echo $disabled; ?> type="text" onchange="savebespreking(&#39;<?php echo $schooljaar . '&#39;,&#39;' . $klas . '&#39;,' . $id . ', ' . $rapport . ',' . $i; ?>)" id="opmerking_<?php echo $i; ?>" class="opmerking_input" value="<?php echo $opmerking1; ?>"></td>
                <?php if ($rapport == 4 && $level_klas != 4) { ?>
                    <td class="text-center"><input <?php echo $radio1; ?> type="checkbox" onchange="savebespreking(&#39;<?php echo $schooljaar . '&#39;,&#39;' . $klas . '&#39;,' . $id . ', ' . $rapport . ',' . $i; ?>)" id="radio1_<?php echo $i; ?>"></td>
                    <td class="text-center"><input <?php echo $radio2; ?> type="checkbox" onchange="savebespreking(&#39;<?php echo $schooljaar . '&#39;,&#39;' . $klas . '&#39;,' . $id . ', ' . $rapport . ',' . $i; ?>)" id="radio2_<?php echo $i; ?>"></td>
                    <td class="text-center"><input <?php echo $radio3; ?> type="checkbox" onchange="savebespreking(&#39;<?php echo $schooljaar . '&#39;,&#39;' . $klas . '&#39;,' . $id . ', ' . $rapport . ',' . $i; ?>)" id="radio3_<?php echo $i; ?>"></td>
                <?php } ?>
                <?php if ($level_klas != 4) { ?>
                    <td class=" text-center">
                        <?php if ($cijfers[$id] < 71 || $cuenta_pri > 2 || ($cuenta + $cuenta_pri) > 3) {
                            echo "<label style='margin: 0;' class='text-danger'>O</label>";
                        } else {
                            echo "<label style='margin: 0;' class='text-primary'>V</label>";
                        } ?>
                    </td>
                <?php } ?>
                <td><input <?php echo $disabled; ?> type="text" onchange="savebespreking(&#39;<?php echo $schooljaar . '&#39;,&#39;' . $klas . '&#39;,' . $id . ', ' . $rapport . ',' . $i; ?>)" id="definitiet_<?php echo $i; ?>" class="definitiet_input" value="<?php echo strtoupper($opmerking3); ?>"></td>
            </tr>
        <?php $i++;
        }
        ?>
    </tbody>
</table>
<script>
    function savebespreking(schooljaar, klas, student, rap, i) {
        var level_klas = document.getElementById("level_klas").value;
        var definitiet = document.getElementById("definitiet_" + i).value.toUpperCase();
        var radio1 = null;
        var radio2 = null;
        var radio3 = null;
        var opmerking = document.getElementById("opmerking_" + i).value;


        if (rap == 4 && level_klas != 4) {
            radio1 = document.getElementById("radio1_" + i).checked;
            radio2 = document.getElementById("radio2_" + i).checked;
            radio3 = document.getElementById("radio3_" + i).checked;
        }

        if (definitiet != "V" && definitiet != "O" && rap != 4) {
            definitiet = "";
        } else if (definitiet != "G" && definitiet != "A" && definitiet != "T" && rap == 4 && level_klas == 4) {
            definitiet = "";
        }
        var data = {
            "studentid": student,
            "schooljaar": schooljaar,
            "klas": klas,
            "rap": rap,
            "opmerking": opmerking,
            "definitiet": definitiet,
            "radio1": radio1,
            "radio2": radio2,
            "radio3": radio3,
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