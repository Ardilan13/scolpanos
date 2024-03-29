<?php
require_once "../classes/DBCreds.php";
session_start();
$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$p1 = '';
$p2 = '';
$p3 = '';
$p4 = '';
$p5 = '';
$p6 = '';
$p7 = '';
$p8 = '';
$p9 = '';
$p10 = '';

$klas = $_POST["rapport_klassen_lijst"];
$schoolid = $_SESSION["SchoolID"];
$datum = date('Y-m-d');
$schooljaar = $_POST["schooljaar_rapport"]; ?>

<style>
    #table_planner,
    #table_planner th {
        border: 2px solid black;
    }

    #table_planner td {
        text-align: center !important;
    }

    #table_planner td select {
        width: 90%;
        font-size: medium;
    }


    #table_planner {
        width: 100%;
        margin: 0 auto;
        height: 30rem;
    }

    .dias {
        width: 100%;
        height: 99%;
        border: 2px solid black;
        padding: 1%;
    }

    .days {
        text-align: center;
        font-size: large;
    }

    .title {
        background-color: skyblue;
        text-align: center;
        font-size: large;
    }

    .days,
    .title {
        height: 10%;
    }

    .group {
        display: flex;
        justify-content: space-around;
        margin-top: 3%;
    }

    .form_label {
        padding-right: 5%;
        font-size: medium;
        font-weight: 700;
    }

    .form_group {
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .input-text {
        border: 1px solid black;
        border-radius: 5px;
        height: 5rem;
        width: 80%;
        font-size: 16px;
    }

    .klas {
        width: 30%;
    }

    .select_group {
        display: block !important;
        margin: 5px !important;
    }
</style>

<?php
if ($klas == 4) {
    $get_vak = "SELECT id,name FROM groups WHERE schoolid = $schoolid ORDER BY id;";
    $result = mysqli_query($mysqli, $get_vak);
    if ($result->num_rows > 0) {
        while ($row1 = mysqli_fetch_assoc($result)) {
            $vaks[] = array("id" => $row1['id'], "vak" => $row1['name']);
        }
    }
} else {
    $get_vak = "SELECT distinct v.ID, v.volledigenaamvak from le_vakken v where v.SchoolID = $schoolid and v.Klas = '$klas' and (v.volgorde <> 99 OR v.volledigenaamvak = 'msl') order by v.volgorde asc;";
    $result = mysqli_query($mysqli, $get_vak);
    if ($result->num_rows > 0) {
        while ($row1 = mysqli_fetch_assoc($result)) {
            $vaks[] = array("id" => $row1['ID'], "vak" => $row1['volledigenaamvak']);
        }
    }
} ?>
<form id="planning">
    <table id="table_planner" border="1">
        <tr>
            <td class="title">DAGEN</td>
            <td class="title">lu 1</td>
            <td class="title">lu 2</td>
            <td class="title">lu 3</td>
            <td class="title">lu 4</td>
            <td class="title">lu 5</td>
            <td class="title">lu 6</td>
            <td class="title">lu 7</td>
            <td class="title">lu 8</td>
            <td class="title">lu 9</td>
            <td class="title">Dag</td>
        </tr>
        <tr>
            <td class="days">MAANDAG</td>
            <?php
            $query = "SELECT * FROM klassenboek_vak WHERE day = 'Monday' AND klas = '$klas' AND schooljaar = '$schooljaar' AND schoolid = $schoolid;";
            $resultado = mysqli_query($mysqli, $query);
            if (mysqli_num_rows($resultado) != 0) {
                $row = mysqli_fetch_assoc($resultado);
                $p1 = $row['p1'];
                $p2 = $row['p2'];
                $p3 = $row['p3'];
                $p4 = $row['p4'];
                $p5 = $row['p5'];
                $p6 = $row['p6'];
                $p7 = $row['p7'];
                $p8 = $row['p8'];
                $p9 = $row['p9'];
                $p10 = $row['p10'];
            } else {
                $insert = "INSERT INTO klassenboek_vak (schoolid,klas,datum,day,schooljaar) VALUES ($schoolid,'$klas','$datum','Monday','$schooljaar')";
                $mysqli->query($insert);
            }
            for ($i = 1; $i <= 10; $i++) {
                $x = 'p' . $i; ?>
                <td class="th_vaks">
                    <?php if ($i == 10) { ?>
                        <select class="select_vaks" klas="<?php echo $klas; ?>" day="Monday" name="vak_<?php echo $i; ?>" id="<?php echo $i; ?>" schooljaar="<?php echo $schooljaar; ?>">
                            <option value="0">Dag</option>
                            <?php foreach ($vaks as $vak) {
                                if ($vak["id"] == $$x && $row['day'] == "Monday") {
                                    $conf = " selected";
                                } else {
                                    $conf = "";
                                } ?>
                                <option <?php echo $conf; ?> value="<?php echo $vak["id"]; ?>"><?php echo $vak["vak"]; ?> </option>
                            <?php } ?>
                        </select>
                    <?php } else if ($klas != 4) { ?>
                        <select class="select_vaks" klas="<?php echo $klas; ?>" day="Monday" name="vak_<?php echo $i; ?>" id="<?php echo $i; ?>" schooljaar="<?php echo $schooljaar; ?>">
                            <option value="0"><?php echo $i; ?></option>
                            <?php foreach ($vaks as $vak) {
                                if ($vak["id"] == $$x && $row['day'] == "Monday") {
                                    $conf = " selected";
                                } else {
                                    $conf = "";
                                } ?>
                                <option <?php echo $conf; ?> value="<?php echo $vak["id"]; ?>"><?php echo $vak["vak"]; ?> </option>
                            <?php } ?>
                        </select>
                    <?php } else {
                        $valores = explode(",", $$x); ?>
                        <select class="select_group" klas="<?php echo $klas; ?>" day="Monday" name="vak_<?php echo $i; ?>" id="<?php echo $i; ?>" schooljaar="<?php echo $schooljaar; ?>">
                            <option value="0"><?php echo $i; ?></option>
                            <?php foreach ($vaks as $vak) {
                                if ($vak["id"] == $valores[0] && $row['day'] == "Monday") {
                                    $conf = " selected";
                                } else {
                                    $conf = "";
                                } ?>
                                <option <?php echo $conf; ?> value="<?php echo $vak["id"]; ?>"><?php echo $vak["vak"]; ?> </option>
                            <?php } ?>
                        </select>
                        <select class="select_group" klas="<?php echo $klas; ?>" day="Monday" name="vak_<?php echo $i; ?>" id="<?php echo $i; ?>" schooljaar="<?php echo $schooljaar; ?>">
                            <option value="0"><?php echo $i; ?></option>
                            <?php foreach ($vaks as $vak) {
                                if ($vak["id"] == $valores[1] && $row['day'] == "Monday") {
                                    $conf = " selected";
                                } else {
                                    $conf = "";
                                } ?>
                                <option <?php echo $conf; ?> value="<?php echo $vak["id"]; ?>"><?php echo $vak["vak"]; ?> </option>
                            <?php } ?>
                        </select>
                        <select class="select_group" klas="<?php echo $klas; ?>" day="Monday" name="vak_<?php echo $i; ?>" id="<?php echo $i; ?>" schooljaar="<?php echo $schooljaar; ?>">
                            <option value="0"><?php echo $i; ?></option>
                            <?php foreach ($vaks as $vak) {
                                if ($vak["id"] == $valores[2] && $row['day'] == "Monday") {
                                    $conf = " selected";
                                } else {
                                    $conf = "";
                                } ?>
                                <option <?php echo $conf; ?> value="<?php echo $vak["id"]; ?>"><?php echo $vak["vak"]; ?> </option>
                            <?php } ?>
                        </select>
                    <?php } ?>
                </td>
            <?php } ?>
        </tr>
        <tr>
            <td class="days">DINSDAG</td>
            <?php
            $query = "SELECT * FROM klassenboek_vak WHERE day = 'Tuesday' AND klas = '$klas' AND schooljaar = '$schooljaar' AND schoolid = $schoolid;";
            $resultado = mysqli_query($mysqli, $query);
            if (mysqli_num_rows($resultado) != 0) {
                $row = mysqli_fetch_assoc($resultado);
                $p1 = $row['p1'];
                $p2 = $row['p2'];
                $p3 = $row['p3'];
                $p4 = $row['p4'];
                $p5 = $row['p5'];
                $p6 = $row['p6'];
                $p7 = $row['p7'];
                $p8 = $row['p8'];
                $p9 = $row['p9'];
                $p10 = $row['p10'];
            } else {
                $insert = "INSERT INTO klassenboek_vak (schoolid,klas,datum,day,schooljaar) VALUES ($schoolid,'$klas','$datum','Tuesday','$schooljaar')";
                $mysqli->query($insert);
            }
            for ($i = 1; $i <= 10; $i++) {
                $x = 'p' . $i;
            ?>
                <td class="th_vaks">
                    <?php if ($i == 10) { ?>
                        <select class="select_vaks" klas="<?php echo $klas; ?>" day="Tuesday" name="vak_<?php echo $i; ?>" id="<?php echo $i; ?>" schooljaar="<?php echo $schooljaar; ?>">
                            <option value="0">Dag</option>
                            <?php foreach ($vaks as $vak) {
                                if ($vak["id"] == $$x && $row['day'] == "Tuesday") {
                                    $conf = " selected";
                                } else {
                                    $conf = "";
                                } ?>
                                <option <?php echo $conf; ?> value="<?php echo $vak["id"]; ?>"><?php echo $vak["vak"]; ?> </option>
                            <?php } ?>
                        </select>
                    <?php } else if ($klas != 4) { ?>
                        <select class="select_vaks" klas="<?php echo $klas; ?>" day="Tuesday" name="vak_<?php echo $i; ?>" id="<?php echo $i; ?>" schooljaar="<?php echo $schooljaar; ?>">
                            <option value="0"><?php echo $i; ?></option>
                            <?php foreach ($vaks as $vak) {
                                if ($vak["id"] == $$x && $row['day'] == "Tuesday") {
                                    $conf = " selected";
                                } else {
                                    $conf = "";
                                } ?>
                                <option <?php echo $conf; ?> value="<?php echo $vak["id"]; ?>"><?php echo $vak["vak"]; ?> </option>
                            <?php } ?>
                        </select>
                    <?php } else {
                        $valores = explode(",", $$x); ?>
                        <select class="select_group" klas="<?php echo $klas; ?>" day="Tuesday" name="vak_<?php echo $i; ?>" id="<?php echo $i; ?>" schooljaar="<?php echo $schooljaar; ?>">
                            <option value="0"><?php echo $i; ?></option>
                            <?php foreach ($vaks as $vak) {
                                if ($vak["id"] == $valores[0] && $row['day'] == "Tuesday") {
                                    $conf = " selected";
                                } else {
                                    $conf = "";
                                } ?>
                                <option <?php echo $conf; ?> value="<?php echo $vak["id"]; ?>"><?php echo $vak["vak"]; ?> </option>
                            <?php } ?>
                        </select>
                        <select class="select_group" klas="<?php echo $klas; ?>" day="Tuesday" name="vak_<?php echo $i; ?>" id="<?php echo $i; ?>" schooljaar="<?php echo $schooljaar; ?>">
                            <option value="0"><?php echo $i; ?></option>
                            <?php foreach ($vaks as $vak) {
                                if ($vak["id"] == $valores[1] && $row['day'] == "Tuesday") {
                                    $conf = " selected";
                                } else {
                                    $conf = "";
                                } ?>
                                <option <?php echo $conf; ?> value="<?php echo $vak["id"]; ?>"><?php echo $vak["vak"]; ?> </option>
                            <?php } ?>
                        </select>
                        <select class="select_group" klas="<?php echo $klas; ?>" day="Tuesday" name="vak_<?php echo $i; ?>" id="<?php echo $i; ?>" schooljaar="<?php echo $schooljaar; ?>">
                            <option value="0"><?php echo $i; ?></option>
                            <?php foreach ($vaks as $vak) {
                                if ($vak["id"] == $valores[2] && $row['day'] == "Tuesday") {
                                    $conf = " selected";
                                } else {
                                    $conf = "";
                                } ?>
                                <option <?php echo $conf; ?> value="<?php echo $vak["id"]; ?>"><?php echo $vak["vak"]; ?> </option>
                            <?php } ?>
                        </select>
                    <?php } ?>
                </td>
            <?php } ?>
        </tr>
        <tr>
            <td class="days">WOENSDAG</td>
            <?php
            $query = "SELECT * FROM klassenboek_vak WHERE day = 'Wednesday' AND klas = '$klas' AND schooljaar = '$schooljaar' AND schoolid = $schoolid;";
            $resultado = mysqli_query($mysqli, $query);
            if (mysqli_num_rows($resultado) != 0) {
                $row = mysqli_fetch_assoc($resultado);
                $p1 = $row['p1'];
                $p2 = $row['p2'];
                $p3 = $row['p3'];
                $p4 = $row['p4'];
                $p5 = $row['p5'];
                $p6 = $row['p6'];
                $p7 = $row['p7'];
                $p8 = $row['p8'];
                $p9 = $row['p9'];
                $p10 = $row['p10'];
            } else {
                $insert = "INSERT INTO klassenboek_vak (schoolid,klas,datum,day,schooljaar) VALUES ($schoolid,'$klas','$datum','Wednesday','$schooljaar')";
                $mysqli->query($insert);
            }
            for ($i = 1; $i <= 10; $i++) {
                $x = 'p' . $i;
            ?>
                <td class="th_vaks">
                    <?php if ($i == 10) { ?>
                        <select class="select_vaks" klas="<?php echo $klas; ?>" day="Wednesday" name="vak_<?php echo $i; ?>" id="<?php echo $i; ?>" schooljaar="<?php echo $schooljaar; ?>">
                            <option value="0">Dag</option>
                            <?php foreach ($vaks as $vak) {
                                if ($vak["id"] == $$x && $row['day'] == "Wednesday") {
                                    $conf = " selected";
                                } else {
                                    $conf = "";
                                } ?>
                                <option <?php echo $conf; ?> value="<?php echo $vak["id"]; ?>"><?php echo $vak["vak"]; ?> </option>
                            <?php } ?>
                        </select>
                    <?php } else if ($klas != 4) { ?>
                        <select class="select_vaks" klas="<?php echo $klas; ?>" day="Wednesday" name="vak_<?php echo $i; ?>" id="<?php echo $i; ?>" schooljaar="<?php echo $schooljaar; ?>">
                            <option value="0"><?php echo $i; ?></option>
                            <?php foreach ($vaks as $vak) {
                                if ($vak["id"] == $$x && $row['day'] == "Wednesday") {
                                    $conf = " selected";
                                } else {
                                    $conf = "";
                                } ?>
                                <option <?php echo $conf; ?> value="<?php echo $vak["id"]; ?>"><?php echo $vak["vak"]; ?> </option>
                            <?php } ?>
                        </select>
                    <?php } else {
                        $valores = explode(",", $$x); ?>
                        <select class="select_group" klas="<?php echo $klas; ?>" day="Wednesday" name="vak_<?php echo $i; ?>" id="<?php echo $i; ?>" schooljaar="<?php echo $schooljaar; ?>">
                            <option value="0"><?php echo $i; ?></option>
                            <?php foreach ($vaks as $vak) {
                                if ($vak["id"] == $valores[0] && $row['day'] == "Wednesday") {
                                    $conf = " selected";
                                } else {
                                    $conf = "";
                                } ?>
                                <option <?php echo $conf; ?> value="<?php echo $vak["id"]; ?>"><?php echo $vak["vak"]; ?> </option>
                            <?php } ?>
                        </select>
                        <select class="select_group" klas="<?php echo $klas; ?>" day="Wednesday" name="vak_<?php echo $i; ?>" id="<?php echo $i; ?>" schooljaar="<?php echo $schooljaar; ?>">
                            <option value="0"><?php echo $i; ?></option>
                            <?php foreach ($vaks as $vak) {
                                if ($vak["id"] == $valores[1] && $row['day'] == "Wednesday") {
                                    $conf = " selected";
                                } else {
                                    $conf = "";
                                } ?>
                                <option <?php echo $conf; ?> value="<?php echo $vak["id"]; ?>"><?php echo $vak["vak"]; ?> </option>
                            <?php } ?>
                        </select>
                        <select class="select_group" klas="<?php echo $klas; ?>" day="Wednesday" name="vak_<?php echo $i; ?>" id="<?php echo $i; ?>" schooljaar="<?php echo $schooljaar; ?>">
                            <option value="0"><?php echo $i; ?></option>
                            <?php foreach ($vaks as $vak) {
                                if ($vak["id"] == $valores[2] && $row['day'] == "Wednesday") {
                                    $conf = " selected";
                                } else {
                                    $conf = "";
                                } ?>
                                <option <?php echo $conf; ?> value="<?php echo $vak["id"]; ?>"><?php echo $vak["vak"]; ?> </option>
                            <?php } ?>
                        </select>
                    <?php } ?>
                </td>
            <?php } ?>
        </tr>
        <tr>
            <td class="days">DONDERDAG</td>
            <?php
            $query = "SELECT * FROM klassenboek_vak WHERE day = 'Thursday' AND klas = '$klas' AND schooljaar = '$schooljaar' AND schoolid = $schoolid;";
            $resultado = mysqli_query($mysqli, $query);
            if (mysqli_num_rows($resultado) != 0) {
                $row = mysqli_fetch_assoc($resultado);
                $p1 = $row['p1'];
                $p2 = $row['p2'];
                $p3 = $row['p3'];
                $p4 = $row['p4'];
                $p5 = $row['p5'];
                $p6 = $row['p6'];
                $p7 = $row['p7'];
                $p8 = $row['p8'];
                $p9 = $row['p9'];
                $p10 = $row['p10'];
            } else {
                $insert = "INSERT INTO klassenboek_vak (schoolid,klas,datum,day,schooljaar) VALUES ($schoolid,'$klas','$datum','Thursday','$schooljaar')";
                $mysqli->query($insert);
            }
            for ($i = 1; $i <= 10; $i++) {
                $x = 'p' . $i;
            ?>
                <td class="th_vaks">
                    <?php if ($i == 10) { ?>
                        <select class="select_vaks" klas="<?php echo $klas; ?>" day="Thursday" name="vak_<?php echo $i; ?>" id="<?php echo $i; ?>" schooljaar="<?php echo $schooljaar; ?>">
                            <option value="0">Dag</option>
                            <?php foreach ($vaks as $vak) {
                                if ($vak["id"] == $$x && $row['day'] == "Thursday") {
                                    $conf = " selected";
                                } else {
                                    $conf = "";
                                } ?>
                                <option <?php echo $conf; ?> value="<?php echo $vak["id"]; ?>"><?php echo $vak["vak"]; ?> </option>
                            <?php } ?>
                        </select>
                    <?php } else if ($klas != 4) { ?>
                        <select class="select_vaks" klas="<?php echo $klas; ?>" day="Thursday" name="vak_<?php echo $i; ?>" id="<?php echo $i; ?>" schooljaar="<?php echo $schooljaar; ?>">
                            <option value="0"><?php echo $i; ?></option>
                            <?php foreach ($vaks as $vak) {
                                if ($vak["id"] == $$x && $row['day'] == "Thursday") {
                                    $conf = " selected";
                                } else {
                                    $conf = "";
                                } ?>
                                <option <?php echo $conf; ?> value="<?php echo $vak["id"]; ?>"><?php echo $vak["vak"]; ?> </option>
                            <?php } ?>
                        </select>
                    <?php } else {
                        $valores = explode(",", $$x); ?>
                        <select class="select_group" klas="<?php echo $klas; ?>" day="Thursday" name="vak_<?php echo $i; ?>" id="<?php echo $i; ?>" schooljaar="<?php echo $schooljaar; ?>">
                            <option value="0"><?php echo $i; ?></option>
                            <?php foreach ($vaks as $vak) {
                                if ($vak["id"] == $valores[0] && $row['day'] == "Thursday") {
                                    $conf = " selected";
                                } else {
                                    $conf = "";
                                } ?>
                                <option <?php echo $conf; ?> value="<?php echo $vak["id"]; ?>"><?php echo $vak["vak"]; ?> </option>
                            <?php } ?>
                        </select>
                        <select class="select_group" klas="<?php echo $klas; ?>" day="Thursday" name="vak_<?php echo $i; ?>" id="<?php echo $i; ?>" schooljaar="<?php echo $schooljaar; ?>">
                            <option value="0"><?php echo $i; ?></option>
                            <?php foreach ($vaks as $vak) {
                                if ($vak["id"] == $valores[1] && $row['day'] == "Thursday") {
                                    $conf = " selected";
                                } else {
                                    $conf = "";
                                } ?>
                                <option <?php echo $conf; ?> value="<?php echo $vak["id"]; ?>"><?php echo $vak["vak"]; ?> </option>
                            <?php } ?>
                        </select>
                        <select class="select_group" klas="<?php echo $klas; ?>" day="Thursday" name="vak_<?php echo $i; ?>" id="<?php echo $i; ?>" schooljaar="<?php echo $schooljaar; ?>">
                            <option value="0"><?php echo $i; ?></option>
                            <?php foreach ($vaks as $vak) {
                                if ($vak["id"] == $valores[2] && $row['day'] == "Thursday") {
                                    $conf = " selected";
                                } else {
                                    $conf = "";
                                } ?>
                                <option <?php echo $conf; ?> value="<?php echo $vak["id"]; ?>"><?php echo $vak["vak"]; ?> </option>
                            <?php } ?>
                        </select>
                    <?php } ?>
                </td>
            <?php } ?>
        </tr>
        <tr>
            <td class="days">VRIJDAG</td>
            <?php
            $query = "SELECT * FROM klassenboek_vak WHERE day = 'Friday' AND klas = '$klas' AND schooljaar = '$schooljaar' AND schoolid = $schoolid;";
            $resultado = mysqli_query($mysqli, $query);
            if (mysqli_num_rows($resultado) != 0) {
                $row = mysqli_fetch_assoc($resultado);
                $p1 = $row['p1'];
                $p2 = $row['p2'];
                $p3 = $row['p3'];
                $p4 = $row['p4'];
                $p5 = $row['p5'];
                $p6 = $row['p6'];
                $p7 = $row['p7'];
                $p8 = $row['p8'];
                $p9 = $row['p9'];
                $p10 = $row['p10'];
            } else {
                $insert = "INSERT INTO klassenboek_vak (schoolid,klas,datum,day,schooljaar) VALUES ($schoolid,'$klas','$datum','Friday','$schooljaar')";
                $mysqli->query($insert);
            }
            for ($i = 1; $i <= 10; $i++) {
                $x = 'p' . $i;
            ?>
                <td class="th_vaks">
                    <?php if ($i == 10) { ?>
                        <select class="select_vaks" klas="<?php echo $klas; ?>" day="Friday" name="vak_<?php echo $i; ?>" id="<?php echo $i; ?>" schooljaar="<?php echo $schooljaar; ?>">
                            <option value="0">Dag</option>
                            <?php foreach ($vaks as $vak) {
                                if ($vak["id"] == $$x && $row['day'] == "Friday") {
                                    $conf = " selected";
                                } else {
                                    $conf = "";
                                } ?>
                                <option <?php echo $conf; ?> value="<?php echo $vak["id"]; ?>"><?php echo $vak["vak"]; ?> </option>
                            <?php } ?>
                        </select>
                    <?php } else if ($klas != 4) { ?>
                        <select class="select_vaks" klas="<?php echo $klas; ?>" day="Friday" name="vak_<?php echo $i; ?>" id="<?php echo $i; ?>" schooljaar="<?php echo $schooljaar; ?>">
                            <option value="0"><?php echo $i; ?></option>
                            <?php foreach ($vaks as $vak) {
                                if ($vak["id"] == $$x && $row['day'] == "Friday") {
                                    $conf = " selected";
                                } else {
                                    $conf = "";
                                } ?>
                                <option <?php echo $conf; ?> value="<?php echo $vak["id"]; ?>"><?php echo $vak["vak"]; ?> </option>
                            <?php } ?>
                        </select>
                    <?php } else {
                        $valores = explode(",", $$x); ?>
                        <select class="select_group" klas="<?php echo $klas; ?>" day="Friday" name="vak_<?php echo $i; ?>" id="<?php echo $i; ?>" schooljaar="<?php echo $schooljaar; ?>">
                            <option value="0"><?php echo $i; ?></option>
                            <?php foreach ($vaks as $vak) {
                                if ($vak["id"] == $valores[0] && $row['day'] == "Friday") {
                                    $conf = " selected";
                                } else {
                                    $conf = "";
                                } ?>
                                <option <?php echo $conf; ?> value="<?php echo $vak["id"]; ?>"><?php echo $vak["vak"]; ?> </option>
                            <?php } ?>
                        </select>
                        <select class="select_group" klas="<?php echo $klas; ?>" day="Friday" name="vak_<?php echo $i; ?>" id="<?php echo $i; ?>" schooljaar="<?php echo $schooljaar; ?>">
                            <option value="0"><?php echo $i; ?></option>
                            <?php foreach ($vaks as $vak) {
                                if ($vak["id"] == $valores[1] && $row['day'] == "Friday") {
                                    $conf = " selected";
                                } else {
                                    $conf = "";
                                } ?>
                                <option <?php echo $conf; ?> value="<?php echo $vak["id"]; ?>"><?php echo $vak["vak"]; ?> </option>
                            <?php } ?>
                        </select>
                        <select class="select_group" klas="<?php echo $klas; ?>" day="Friday" name="vak_<?php echo $i; ?>" id="<?php echo $i; ?>" schooljaar="<?php echo $schooljaar; ?>">
                            <option value="0"><?php echo $i; ?></option>
                            <?php foreach ($vaks as $vak) {
                                if ($vak["id"] == $valores[2] && $row['day'] == "Friday") {
                                    $conf = " selected";
                                } else {
                                    $conf = "";
                                } ?>
                                <option <?php echo $conf; ?> value="<?php echo $vak["id"]; ?>"><?php echo $vak["vak"]; ?> </option>
                            <?php } ?>
                        </select>
                    <?php } ?>
                </td>
            <?php } ?>
        </tr>
    </table>
</form>

<script>
    $('.select_vaks').change(function(e) {
        e.preventDefault();
        p = $(this).attr('id');
        vak = $(this).val();
        klas = $(this).attr('klas');
        schooljaar = $(this).attr('schooljaar');
        day = $(this).attr('day');
        $.ajax({
            url: "ajax/update_klasenboek_vak.php",
            type: 'POST',
            data: {
                klas: klas,
                day: day,
                vak: vak,
                p: p,
                schooljaar: schooljaar,
            },
            success: function(response) {
                console.log(response);
            }
        });
    })

    $(".select_group").change(function(e) {
        e.preventDefault();
        p = $(this).attr('id');
        klas = $(this).attr('klas');
        schooljaar = $(this).attr('schooljaar');
        day = $(this).attr('day');
        var selectedValues = [];

        $(this).closest("td").find(".select_group").each(function() {
            selectedValues.push($(this).val());
        });

        var vak = selectedValues.join(",");

        $.ajax({
            url: "ajax/update_klasenboek_vak.php",
            type: 'POST',
            data: {
                klas: klas,
                day: day,
                vak: vak,
                p: p,
                schooljaar: schooljaar,
            },
            success: function(response) {
                console.log(response);
            }
        });
    })
</script>