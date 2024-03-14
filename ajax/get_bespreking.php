<?php
require_once "../classes/DBCreds.php";
require_once "../classes/spn_setting.php";
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
<?php if ($level_klas == 4 && $_SESSION["SchoolType"] != 1) { ?>
    <label>O=Onvlodoende, V=Voldoende, G=Geslaagd, A=Afgewezen, T=Teruggetrokken</label>
<?php } ?>
<table class="table table-bordered table-colored table-houding">
    <input id="level_klas" type="text" hidden value="<?php echo $level_klas; ?>">
    <input id="schooltype" type="text" hidden value="<?php echo $_SESSION["SchoolType"]; ?>">
    <input id="schoolid" type="text" hidden value="<?php echo $_SESSION["SchoolID"]; ?>">
    <thead>
        <tr>
            <th>ID</th>
            <th>Naam</th>
            <?php if ($rapport != 4 || $_SESSION["SchoolType"] != 1) { ?>
                <th class="opmerking">Opmerking</th>
            <?php } ?>
            <?php if ($rapport == 4 && $_SESSION["SchoolType"] == 1) { ?>
                <th><?php if ($_SESSION["SchoolID"] == 18 || $_SESSION["SchoolID"] == 8) { ?>Pasa<?php } else { ?> Bevorderd <?php } ?></th>
                <?php if ($level_klas != 6 && $_SESSION["SchoolID"] != 18 && $_SESSION["SchoolID"] != 8) { ?>
                    <th>Over wegens leeftijd</th>
                <?php } ?>
                <th><?php if ($_SESSION["SchoolID"] == 18 || $_SESSION["SchoolID"] == 8) { ?>No Pasa<?php } else { ?> Niet bevorderd <?php } ?></th>
            <?php } else if ($rapport == 4 && $level_klas != 4) { ?>
                <th>Andere Schooltype</th>
                <th>Over Naar Ciclo</th>
                <th>Niet Over</th>
            <?php } ?>
            <?php if (($level_klas != 4 || $_SESSION["SchoolType"] == 1) && $_SESSION["SchoolID"] != 18) { ?>
                <th class="definitiet">Systeem</th>
            <?php }
            if (($_SESSION["SchoolID"] != 18 && $_SESSION["SchoolID"] != 8) || (($_SESSION["SchoolID"] == 18 || $_SESSION["SchoolID"] == 8) && $rapport != 4)) { ?>
                <th class="definitiet"><?php if ($level_klas == 6) { ?> Advies <?php } else { ?> Beoordeling <?php } ?></th>
            <?php } ?>
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
        if ($result_mentor->num_rows > 0 || $_SESSION["UserRights"] == "BEHEER" || ($_SESSION["SchoolID"] == 18 && $_SESSION["UserRights"] == "DOCENT")) {
            $disabled = "";
        } else {
            $disabled = "disabled";
        }

        $s = new spn_setting();
        $s->getsetting_info($schoolid, false);
        $get_students = "SELECT s.id,s.firstname,s.lastname FROM students s WHERE s.schoolid = '$schoolid' AND s.class = '$klas' ORDER BY ";
        $sql_order = " s.lastname, s.firstname ";
        if ($s->_setting_mj) {
            $get_students .= " s.sex " . $s->_setting_sort . ", " . $sql_order;
        } else {
            $get_students .=  $sql_order;
        }
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

                $radio1 = $radio1 == "true" ? "checked" : $radio1;
                $radio2 = $radio2 == "true" ? "checked" : $radio2;
                $radio3 = $radio3 == "true" ? "checked" : $radio3;

                $cuenta_pri = 0;
                $cuenta = 0;
                $last_vak = "";

                if ($level_klas != 4 && $_SESSION["SchoolType"] != 1) {
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
                } else if ($_SESSION["SchoolType"] == 1 && $_SESSION["SchoolID"] != 18 && $_SESSION["SchoolID"] != 8) {
                    $reken = 0;
                    $lezen = 0;
                    $lezen_cont = 0;
                    $neder = 0;
                    $werel = 0;
                    $prom = 0;
                    $sum = 0;
                    $cont = 0;
                    $reken_cont = 0;
                    $neder_cont = 0;
                    $werel_cont = 0;
                    if ($rapport == 4) {
                        $get_cijfers = "SELECT c.schooljaar,c.vak,c.gemiddelde FROM le_cijfers_ps c WHERE c.studentid = '$id' AND c.schooljaar = '$schooljaar' AND c.vak IN (1,2,3,6,7) AND c.gemiddelde is not NULL;";
                    } else {
                        $get_cijfers = "SELECT c.schooljaar,c.vak,c.gemiddelde FROM le_cijfers_ps c WHERE c.studentid = '$id' AND c.rapnummer = $rapport AND c.schooljaar = '$schooljaar' AND c.vak IN (1,2,3,6,7) AND c.gemiddelde is not NULL;";
                    }
                    $result2 = mysqli_query($mysqli, $get_cijfers);
                    if ($result2->num_rows > 0) {
                        while ($row3 = mysqli_fetch_assoc($result2)) {
                            switch ($row3["vak"]) {
                                case 1:
                                    if ($rapport == 4) {
                                        $reken += $row3["gemiddelde"];
                                        $reken_cont++;
                                    } else {
                                        $reken = $row3["gemiddelde"];
                                    }
                                    break;
                                case 2:
                                case 3:
                                    $lezen += $row3["gemiddelde"];
                                    $lezen_cont++;
                                    break;
                                case 6:
                                    if ($rapport == 4) {
                                        $neder += $row3["gemiddelde"];
                                        $neder_cont++;
                                    } else {
                                        $neder = $row3["gemiddelde"];
                                    }
                                    break;
                                case 7:
                                    if ($rapport == 4) {
                                        $werel += $row3["gemiddelde"];
                                        $werel_cont++;
                                    } else {
                                        $werel = $row3["gemiddelde"];
                                    }
                                    break;
                            }
                            $cont++;
                        }
                        if ($rapport == 4) {
                            if ($reken_cont > 0)
                                $reken = round($reken / $reken_cont, 1);
                            if ($neder_cont > 0)
                                $neder = round($neder / $neder_cont, 1);
                            if ($werel_cont > 0)
                                $werel = round($werel / $werel_cont, 1);
                        }
                        $prom = round(($reken + ($lezen / ($lezen_cont > 0 ? $lezen_cont : 1)) + $neder + $werel) / 4, 1);
                        $sum = round($reken + ($lezen / ($lezen_cont > 0 ? $lezen_cont : 1)) + $neder, 1);
                    }
                } else if ($_SESSION["SchoolID"] == 8) {
                    if ($rapport == 4) {
                        $get_cijfers = "SELECT c.rapnummer,v.volledigenaamvak,c.gemiddelde FROM le_cijfers c INNER JOIN le_vakken v ON v.ID = c.vak WHERE c.studentid = '$id' AND c.schooljaar = '$schooljaar' AND c.vak IN (1,2,3,6,7) AND c.gemiddelde is not NULL;";
                    } else {
                        $get_cijfers = "SELECT c.rapnummer,v.volledigenaamvak,c.gemiddelde FROM le_cijfers c INNER JOIN le_vakken v ON v.ID = c.vak WHERE c.studentid = '$id' AND c.rapnummer = $rapport AND c.schooljaar = '$schooljaar' AND v.volledigenaamvak IN () AND c.gemiddelde is not NULL;";
                    }
                    $result2 = mysqli_query($mysqli, $get_cijfers);
                    if ($result2->num_rows > 0) {
                        while ($row3 = mysqli_fetch_assoc($result2)) {
                            switch ($row3["vak"]) {
                                case 1:
                                    if ($rapport == 4) {
                                        $reken += $row3["gemiddelde"];
                                        $reken_cont++;
                                    } else {
                                        $reken = $row3["gemiddelde"];
                                    }
                                    break;
                                case 2:
                                case 3:
                                    $lezen += $row3["gemiddelde"];
                                    $lezen_cont++;
                                    break;
                                case 6:
                                    if ($rapport == 4) {
                                        $neder += $row3["gemiddelde"];
                                        $neder_cont++;
                                    } else {
                                        $neder = $row3["gemiddelde"];
                                    }
                                    break;
                                case 7:
                                    if ($rapport == 4) {
                                        $werel += $row3["gemiddelde"];
                                        $werel_cont++;
                                    } else {
                                        $werel = $row3["gemiddelde"];
                                    }
                                    break;
                            }
                            $cont++;
                        }
                        if ($rapport == 4) {
                            if ($reken_cont > 0)
                                $reken = round($reken / $reken_cont, 1);
                            if ($neder_cont > 0)
                                $neder = round($neder / $neder_cont, 1);
                            if ($werel_cont > 0)
                                $werel = round($werel / $werel_cont, 1);
                        }
                        $prom = round(($reken + ($lezen / ($lezen_cont > 0 ? $lezen_cont : 1)) + $neder + $werel) / 4, 1);
                        $sum = round($reken + ($lezen / ($lezen_cont > 0 ? $lezen_cont : 1)) + $neder, 1);
                    }
                }
                ?>
                <?php if ($rapport != 4 || $_SESSION["SchoolType"] != 1) { ?>
                    <td><input <?php echo $disabled; ?> type="text" maxlength="180" onchange="savebespreking(&#39;<?php echo $schooljaar . '&#39;,&#39;' . $klas . '&#39;,' . $id . ',' . $rapport . ',' . $i; ?>)" id="opmerking_<?php echo $i; ?>" class="opmerking_input" value="<?php echo $opmerking1; ?>"></td>
                <?php } ?>
                <?php if ($rapport == 4 && $level_klas != 4 || ($_SESSION["SchoolType"] == 1 && $rapport == 4)) { ?>
                    <td class="text-center">
                        <input <?php echo ($radio1 != "false" && $radio1 != null) ? "checked" : ""; ?> type="checkbox" onchange="savebespreking(&#39;<?php echo $schooljaar . '&#39;,&#39;' . $klas . '&#39;,' . $id . ',' . $rapport . ',' . $i; ?>)" id="radio1_<?php echo $i; ?>">
                        <?php if ($_SESSION["SchoolID"] != 18 && $_SESSION["SchoolID"] != 8) { ?>
                            <input style="max-width: 40px;" value="<?php echo ($radio1 != 'checked' && $radio1 != "false") ? $radio1 : ''; ?>" type="text" maxlength="3" onchange="savebespreking(&#39;<?php echo $schooljaar . '&#39;,&#39;' . $klas . '&#39;,' . $id . ',' . $rapport . ',' . $i; ?>)" id="radio1.1_<?php echo $i; ?>">
                        <?php } ?>
                    </td>
                    <?php if ((($_SESSION["SchoolType"] == 1 && $level_klas != 6) || $_SESSION["SchoolType"] == 2) && $_SESSION["SchoolID"] != 18 && $_SESSION["SchoolID"] != 8) { ?>
                        <td class="text-center">
                            <input <?php echo ($radio2 != "false" && $radio2 != null) ? "checked" : ""; ?> type="checkbox" onchange="savebespreking(&#39;<?php echo $schooljaar . '&#39;,&#39;' . $klas . '&#39;,' . $id . ',' . $rapport . ',' . $i; ?>)" id="radio2_<?php echo $i; ?>">
                            <input style="max-width: 40px;" value="<?php echo ($radio2 != 'checked' && $radio2 != "false") ? $radio2 : ''; ?>" type="text" maxlength="3" onchange="savebespreking(&#39;<?php echo $schooljaar . '&#39;,&#39;' . $klas . '&#39;,' . $id . ',' . $rapport . ',' . $i; ?>)" id="radio2.1_<?php echo $i; ?>">
                        </td>
                    <?php } ?>
                    <td class="text-center">
                        <input <?php echo ($radio3 != "false" && $radio3 != null) ? "checked" : ""; ?> type="checkbox" onchange="savebespreking(&#39;<?php echo $schooljaar . '&#39;,&#39;' . $klas . '&#39;,' . $id . ',' . $rapport . ',' . $i; ?>)" id="radio3_<?php echo $i; ?>">
                        <?php if ($_SESSION["SchoolID"] != 18 && $_SESSION["SchoolID"] != 8) { ?>
                            <input style="max-width: 40px;" value="<?php echo ($radio3 != 'checked' && $radio3 != "false") ? $radio3 : ''; ?>" type="text" maxlength="3" onchange="savebespreking(&#39;<?php echo $schooljaar . '&#39;,&#39;' . $klas . '&#39;,' . $id . ',' . $rapport . ',' . $i; ?>)" id="radio3.1_<?php echo $i; ?>">
                        <?php } ?>
                    </td>
                <?php } ?>
                <?php if ($level_klas != 4 && $_SESSION["SchoolType"] != 1) { ?>
                    <td class=" text-center">
                        <?php if ($cijfers[$id] < 71 || $cuenta_pri > 2 || ($cuenta + $cuenta_pri) > 3) {
                            echo "<label style='margin: 0;' class='text-danger'>O</label>";
                        } else {
                            echo "<label style='margin: 0;' class='text-primary'>V</label>";
                        } ?>
                    </td>
                <?php } else if ($_SESSION["SchoolType"] == 1 && $_SESSION["SchoolID"] != 18) { ?>
                    <td class=" text-center">
                        <?php if ($_SESSION["SchoolID"] == 8) {
                        } else {
                            $volgorde = 0;
                            switch ($level_klas) {
                                case 2:
                                    if ($reken >= 5 && ($lezen / ($lezen_cont > 0 ? $lezen_cont : 1)) >= 5 && $neder >= 5 && $sum >= 17) {
                                        $volgorde = 1;
                                    }
                                    break;
                                case 3:
                                    if ($reken >= 5 && ($lezen / ($lezen_cont > 0 ? $lezen_cont : 1)) >= 5 && $neder >= 5 && $werel >= 5 && $prom >= 5.5 && $sum >= 17) {
                                        $volgorde = 1;
                                    }
                                    break;
                                case 4:
                                case 5:
                                    if ($reken >= 5 && ($lezen / ($lezen_cont > 0 ? $lezen_cont : 1)) >= 5 && $neder >= 5 && $werel >= 5.5 && $prom >= 5.6 && $sum >= 17) {
                                        $volgorde = 1;
                                    }
                                    break;
                                case 6:
                                    $rek_pro = 0;
                                    $rek_cont = 0;
                                    $ned_pro = 0;
                                    $ned_cont = 0;
                                    $wer_pro = 0;
                                    $wer_cont = 0;
                                    $schooljaar_array = explode("-", $schooljaar);
                                    $schooljaar_pasado = $schooljaar_array[0] - 1 . "-" . $schooljaar_array[0];
                                    $get_cijfers = "SELECT c.vak,c.gemiddelde FROM le_cijfers_ps c WHERE c.studentid = '$id' AND c.schooljaar = '$schooljaar_pasado' AND c.vak IN (1,6,7) AND c.gemiddelde is not NULL;";
                                    $result4 = mysqli_query($mysqli, $get_cijfers);
                                    while ($row4 = mysqli_fetch_assoc($result4)) {
                                        if ($row4["vak"] == 1) {
                                            $rek_pro += $row4["gemiddelde"];
                                            $rek_cont++;
                                        }
                                        if ($row4["vak"] == 6) {
                                            $ned_pro += $row4["gemiddelde"];
                                            $ned_cont++;
                                        }
                                        if ($row4["vak"] == 7) {
                                            $wer_pro += $row4["gemiddelde"];
                                            $wer_cont++;
                                        }
                                    }
                                    if ($rek_cont > 0)
                                        $rek_pro = $rek_pro / $rek_cont;
                                    if ($ned_cont > 0)
                                        $ned_pro = $ned_pro / $ned_cont;
                                    if ($wer_cont > 0)
                                        $wer_pro = $wer_pro / $wer_cont;
                                    $prom = (($rek_pro + $reken) / 2) + (($ned_pro + $neder) / 2);
                                    if ((($rek_pro + $reken) / 2) >= 7.5 && (($ned_pro + $neder) / 2) >= 7.5 && ($werel + $wer_pro) / 2 >= 6) {
                                        $volgorde = 2;
                                    } else if (($rek_pro + $reken) / 2 >= 5 && ($ned_pro + $neder) / 2 >= 5 && ($werel + $wer_pro) / 2 >= 5.5 && $prom >= 12) {
                                        $volgorde = 3;
                                    } else if ($rek_pro <= 0 || $ned_pro <= 0) {
                                        $volgorde = 5;
                                    } else {
                                        $volgorde = 4;
                                    }
                                    break;
                            }
                            switch ($volgorde) {
                                case 1:
                                    echo "<label style='margin: 0;' class='text-primary'>V</label>";
                                    break;
                                case 2:
                                    echo "<label style='margin: 0;' class='text-primary'>HAVO</label>";
                                    break;
                                case 3:
                                    echo "<label style='margin: 0; color: #36d339;'>MAVO</label>";
                                    break;
                                case 4:
                                    echo "<label style='margin: 0;' class='text-danger'>EPB</label>";
                                    break;
                                case 5:
                                    echo "<label style='margin: 0;' class='text-danger'></label>";
                                    break;
                                case 0:
                                    echo "<label style='margin: 0;' class='text-danger'>O</label>";
                                    break;
                            }
                        } ?>
                    </td>
                <?php }
                if (($_SESSION["SchoolID"] != 18 && $_SESSION["SchoolID"] != 8) || (($_SESSION["SchoolID"] == 18 || $_SESSION["SchoolID"] == 8) && $rapport != 4)) { ?>
                    <td><input <?php echo $disabled; ?> type="text" onchange="savebespreking(&#39;<?php echo $schooljaar . '&#39;,&#39;' . $klas . '&#39;,' . $id . ',' . $rapport . ',' . $i; ?>)" id="definitiet_<?php echo $i; ?>" class="definitiet_input" value="<?php echo strtoupper($opmerking3); ?>"></td>
                <?php } ?>
            </tr>
        <?php $i++;
        }
        ?>
    </tbody>
</table>
<script>
    function savebespreking(schooljaar, klas, student, rap, i) {
        var level_klas = document.getElementById("level_klas").value;
        var schooltype = document.getElementById("schooltype").value;
        var schoolid = document.getElementById("schoolid").value;
        if ((schoolid != 18 && schoolid != 8) || rap != 4)
            var definitiet = document.getElementById("definitiet_" + i).value.toUpperCase();
        var radio1 = null;
        var radio2 = null;
        var radio3 = null;
        var radio1_1 = null;
        var radio2_1 = null;
        var radio3_1 = null;
        if (schooltype != 1 || rap != 4)
            var opmerking = document.getElementById("opmerking_" + i).value;

        if (rap == 4 && level_klas != 4 || schooltype == 1 && rap == 4) {
            radio1 = document.getElementById("radio1_" + i).checked;
            if (schoolid != 18 && schoolid != 8)
                radio1_1 = document.getElementById("radio1.1_" + i).value;
            if (radio1_1 != "" && radio1_1 != null && radio1 != false) {
                radio1 = radio1_1;
            }

            if (schooltype != 1 || level_klas != 6 && (schoolid != 18 && schoolid != 8)) {
                radio2 = document.getElementById("radio2_" + i).checked;
                radio2_1 = document.getElementById("radio2.1_" + i).value;
                if (radio2_1 != "" && radio2_1 != null && radio2 != false) {
                    radio2 = radio2_1;
                }
            }
            radio3 = document.getElementById("radio3_" + i).checked;
            if (schoolid != 18 && schoolid != 8)
                radio3_1 = document.getElementById("radio3.1_" + i).value;
            if (radio3_1 != "" && radio3_1 != null && radio3 != false) {
                radio3 = radio3_1;
            }
        }

        if (definitiet != "V" && definitiet != "O" && definitiet != "S" && definitiet != "I" && rap != 4) {
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