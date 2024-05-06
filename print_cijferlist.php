<?php

ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "classes/DBCreds.php";
require_once("classes/spn_setting.php");
require_once("config/app.config.php");
require_once("classes/spn_leerling.php");

$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');
$s = new spn_setting();
$s->getsetting_info($_SESSION["SchoolID"], false);
$l = new spn_leerling();

$schooljaar = $_GET['schoolJaar'];
$klas = $_GET['klas'];
$studentid = $_GET['studentid'];

switch ($_SESSION["SchoolID"]) {
    case 12:
        $img = "ceque_logo.png";
        $titleD = "Ceque College";
        break;
    case 13:
        $img = "Abrahamdeveer.jpeg";
        $titleD = "Commandant Generaal Abraham de Veer School";
        break;
    default:
        $img = "logo_spn.png";
        $titleD = $s->_setting_school_name;
}
$returnarr = array();
$array = array();

if ($studentid == 'all') {
    $array_leerling = $l->get_all_students_array_by_klas($_GET["klas"], $_GET["schoolJaar"], 4);
} else {
    $select = "SELECT id,firstname,lastname,sex,dob,class,profiel,birthplace FROM students where id = '$studentid' and status = 1";
    $resultado1 = mysqli_query($mysqli, $select);
    while ($row = mysqli_fetch_assoc($resultado1)) {
        $returnarr["studentid"] = $studentid;
        $returnarr["voornamen"] = $row["firstname"];
        $returnarr["achternaam"] = $row["lastname"];
        $returnarr["geslacht"] = $row["sex"];
        $returnarr["geboortedatum"] = $row["dob"];
        $returnarr["klas"] = $row["class"];
        $returnarr["profiel"] = $row["profiel"];
        $returnarr["birthplace"] = $row["birthplace"];
        array_push($array, $returnarr);
    }
    $array_leerling = $array;
} ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cijferlijst</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lugrasimo&family=Tinos:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 2% 10%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-size: 14px;
            font-family: "Tinos", serif;
            font-weight: 400;
            font-style: normal;
        }

        main {
            height: 1000px;
        }

        p {
            margin: 0.5rem 0;
        }

        p b {
            font-family: "Lugrasimo", cursive;
            font-weight: 700;
            font-style: normal;
            font-size: 16px;
        }

        .header {
            width: 100%;
            display: flex;
            align-items: center;
        }

        .header div {
            flex-basis: 85%;
            text-align: center;
        }

        table {
            table-layout: fixed;
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid black;
            padding: 0.2rem;
            word-wrap: break-word;
            font-size: 14px;
            font-family: "Tinos", serif;
            font-weight: 400;
            font-style: normal;
        }

        .nota {
            text-align: center;
        }

        .subtitle {
            background-color: #d0d0d0;
        }
    </style>
</head>

<body>
    <?php foreach ($array_leerling as $leerling) {
        $timestamp = strtotime($leerling["geboortedatum"]);
        $dia = date("j", $timestamp);
        $mes = date("n", $timestamp);
        switch ($mes) {
            case 1:
                $mes = "januari";
                break;
            case 2:
                $mes = "februari";
                break;
            case 3:
                $mes = "maart";
                break;
            case 4:
                $mes = "april";
                break;
            case 5:
                $mes = "mei";
                break;
            case 6:
                $mes = "juni";
                break;
            case 7:
                $mes = "juli";
                break;
            case 8:
                $mes = "augustus";
                break;
            case 9:
                $mes = "september";
                break;
            case 10:
                $mes = "oktober";
                break;
            case 11:
                $mes = "november";
                break;
            case 12:
                $mes = "december";
                break;
        }
        $ano = date("Y", $timestamp);
        $fecha = $dia . " " . $mes . " " . $ano;
        switch (substr($leerling["profiel"], 0, 2)) {
            case "MM":
                $profiel = "Mens En Maatschappij";
                break;
            case "NW":
                $profiel = "Natuurwetenschappen";
                break;
            case "HU":
                $profiel = "Humaniora";
                break;
        }
        $get_paket = "SELECT g1,g2,g3,g4,p1,p2,p3,k1,k2 FROM paket WHERE paket = '" . $leerling['profiel'] . "'";
        $result_paket = mysqli_query($mysqli, $get_paket);
        while ($row_paket = mysqli_fetch_assoc($result_paket)) {
            $p1 = $row_paket["g1"];
            $p2 = $row_paket["g2"];
            $p3 = $row_paket["g3"];
            $p4 = $row_paket["g4"];
            $p5 = $row_paket["p1"];
            $p6 = $row_paket["p2"];
            $p7 = $row_paket["p3"];
            $p8 = $row_paket["k1"];
            $p9 = $row_paket["k2"];
        }
        $cijfers = array(); ?>
        <main>
            <div class="header">
                <img width='150px' style='flex-basis: 15%;' height='100px' src='<?php echo appconfig::GetBaseURL() . "/assets/img/" . $img; ?>'>
                <div style="margin-left: -40px;">
                    <h2>CIJFERLIJST</h2>
                    <h4>MIDDELBAAR ALGEMEEN VOORTGEZET ONDERWIJS</h4>
                </div>
            </div>
            <p>De ondergetekenden verklaren dat <b><?php echo $leerling["voornamen"] . ", " . $leerling["achternaam"]; ?></b></p>
            <p>geboren op <b><?php echo $fecha; ?></b> te <b><?php echo utf8_encode($leerling["birthplace"]); ?></b></p>
            <p>heeft deelgenomen aan het eindexamen middelbaar algemeen voortgezet onderwijs conform</p>
            <p>het profiel <b><?php echo $profiel; ?></b></p>
            <p>aan <b><?php echo $titleD; ?></b></p>
            <p>te <b>ARUBA</b></p>
            <p>Dit examen werd afgenomen in de zin van artikel 32 van de Landsverordening Voorgezet Onderwijs</p>
            <p>(A.B. 1989, no. GT 103).</p>

            <?php
            $get_cijfers = "SELECT
            e.type,
            CASE WHEN e0.e1 IS NOT NULL AND e0.e1 <> '' THEN e.e1 ELSE NULL END AS e1,
            CASE WHEN e0.e2 IS NOT NULL AND e0.e2 <> '' THEN e.e2 ELSE NULL END AS e2,
            CASE WHEN e0.e3 IS NOT NULL AND e0.e3 <> '' THEN e.e3 ELSE NULL END AS e3,
            CASE WHEN e0.e4 IS NOT NULL AND e0.e4 <> '' THEN e.e4 ELSE NULL END AS e4,
            CASE WHEN e0.e5 IS NOT NULL AND e0.e5 <> '' THEN e.e5 ELSE NULL END AS e5,
            CASE WHEN e0.e6 IS NOT NULL AND e0.e6 <> '' THEN e.e6 ELSE NULL END AS e6,
            CASE WHEN e0.e7 IS NOT NULL AND e0.e7 <> '' THEN e.e7 ELSE NULL END AS e7,
            CASE WHEN e0.e8 IS NOT NULL AND e0.e8 <> '' THEN e.e8 ELSE NULL END AS e8,
            CASE WHEN e0.e9 IS NOT NULL AND e0.e9 <> '' THEN e.e9 ELSE NULL END AS e9,
            CASE WHEN e0.e10 IS NOT NULL AND e0.e10 <> '' THEN e.e10 ELSE NULL END AS e10,
            CASE WHEN e0.e11 IS NOT NULL AND e0.e11 <> '' THEN e.e11 ELSE NULL END AS e11,
            CASE WHEN e0.e12 IS NOT NULL AND e0.e12 <> '' THEN e.e12 ELSE NULL END AS e12,
            e.tv1,
            p.ckv,
            p.lo,
            p.her
            FROM eba_ex e
            INNER JOIN personalia p ON e.id_personalia = p.id
            INNER JOIN students s ON p.studentid = s.id
            LEFT JOIN eba_ex e0 ON e0.id_personalia = e.id_personalia AND e0.schooljaar = e.schooljaar AND e0.type = 0
            WHERE s.id = '" . $leerling['studentid'] . "'
            AND e.schooljaar = '$schooljaar'
            AND (e.type = '0' OR e.type = '2' OR e.type = '3' OR e.type = '4' OR e.type = '5')
            ORDER BY s.lastname, s.firstname,e.type; ";
            $result = mysqli_query($mysqli, $get_cijfers);
            if (mysqli_num_rows($result) > 0) {
                $eba = array();
                while ($row = mysqli_fetch_assoc($result)) {
                    $cijfers[$row["type"]] = $row;
                }
                for ($i = 1; $i <= 12; $i++) {
                    $pos = "e" . $i;
                    if (isset($cijfers[0][$pos]) && $cijfers[0][$pos] != null) {
                        $eba[] = $pos;
                    }
                }
            }
            ?>

            <table>
                <thead>
                    <tr>
                        <th style="width: 40%;" rowspan="3">EXAMENVAKKEN</th>
                        <th colspan="4">CIJFERS BIJ HET EXAMEN VERKREGEN</th>
                    </tr>
                    <tr>
                        <th rowspan="2">Schoolexamen</th>
                        <th rowspan="2">Centraal examen</th>
                        <th colspan="2">Eindcijfer / beoordeling</th>
                    </tr>
                    <tr>
                        <th>In Cijfers</th>
                        <th>In Letters</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i = 1; $i <= 9; $i++) {
                        $vak = "p" . $i;
                        $index = false;
                        switch ($$vak) {
                            case "NE":
                                $pos = "e" . 1;
                                $vak = "Nederlandse taal en literatuur";
                                break;
                            case "EN":
                                $pos = "e" . 2;
                                $vak = "Engelse taal en literatuur";
                                break;
                            case "SP":
                                $pos = "e" . 3;
                                $vak = "Spaanse taal en literatuur";
                                break;
                            case "PA":
                                $pos = "e" . 4;
                                $vak = "Papiamentse taal en cultuur";
                                break;
                            case "WI":
                                $pos = "e" . 5;
                                $vak = "Wiskunde";
                                break;
                            case "NA":
                                $pos = "e" . 6;
                                $vak = "Natuur- en scheikunde 1";
                                break;
                            case "SK":
                                $pos = "e" . 7;
                                $vak = "Natuur- en scheikunde 2";
                                break;
                            case "BI":
                                $pos = "e" . 8;
                                $vak = "Biologie";
                                break;
                            case "EC":
                                $pos = "e" . 9;
                                $vak = "Economie / management en organisatie";
                                break;
                            case "AK":
                                $pos = "e" . 10;
                                $vak = "Aardrijkskunde";
                                break;
                            case "GS":
                                $pos = "e" . 11;
                                $vak = "Geschiedenis en staatsinrichting";
                                break;
                            case "RE":
                                $pos = "e" . 12;
                                $vak = "Religie en levensbeschouwing";
                                break;
                            case "CKV":
                                $pos = "ckv";
                                $vak = "Culturele en kunstzinnige vorming";
                                break;
                            case "LO":
                                $pos = "lo";
                                $vak = "Lichamelijke opvoeding";
                                break;
                            default:
                                $pos = "";
                                $vak = "";
                                break;
                        }
                        $cijfer = $cijfers[2][$pos];
                        $cijfer2 = $cijfers[3][$pos];
                        $cijfer3 = $cijfers[5][$pos];
                        $index = array_search($pos, $eba);
                        if ($index !== false) {
                            unset($eba[$index]);
                        }
                        switch ($cijfer3) {
                            case "1":
                                $cijfer4 = "Eén";
                                break;
                            case "2":
                                $cijfer4 = "Twee";
                                break;
                            case "3":
                                $cijfer4 = "Drie";
                                break;
                            case "4":
                                $cijfer4 = "Vier";
                                break;
                            case "5":
                                $cijfer4 = "Vijf";
                                break;
                            case "6":
                                $cijfer4 = "Zes";
                                break;
                            case "7":
                                $cijfer4 = "Zeven";
                                break;
                            case "8":
                                $cijfer4 = "Acht";
                                break;
                            case "9":
                                $cijfer4 = "Negen";
                                break;
                            case "10":
                                $cijfer4 = "Tien";
                                break;
                            default:
                                $cijfer4 = "";
                                break;
                        }
                        if ($i == 1 || $i == 5 || $i == 8) {
                            switch ($i) {
                                case 1:
                                    $sub = "Gemeenschappelijk deel";
                                    break;
                                case 5:
                                    $sub = "Profiel deel";
                                    break;
                                case 8:
                                    $sub = "Keuze deel";
                                    break;
                            }
                            echo "<tr class='subtitle'>";
                            echo "<td><b>" . $sub . "</b></td>";
                            echo "<td></td>";
                            echo "<td></td>";
                            echo "<td></td>";
                            echo "<td></td>";
                            echo "</tr>";
                        }
                        if ($pos != "") {
                            echo "<tr>";
                            echo "<td>" . $vak . "</td>";
                            if ($i == 3 || $i == 4) {
                                echo "<td></td>";
                                echo "<td></td>";
                                echo "<td></td>";
                                if ($i == 3) {
                                    echo "<td class='nota'>" . ($cijfers[2]["her"] == 1 ? "Vold." : ($cijfer3 == 1 ? "Vold." : "Onvd.")) . "</td>";
                                } else {
                                    echo "<td class='nota'>" . ($cijfer3 == 1 ? "Vold." : "Onvd.") . "</td>";
                                }
                            } else {
                                echo "<td class='nota'>" . $cijfer . "</td>";
                                echo "<td class='nota'>" . $cijfer2 . "</td>";
                                echo "<td class='nota'>" . $cijfer3 . "</td>";
                                echo "<td class='nota'>" . $cijfer4 . "</td>";
                            }
                            echo "</tr>";
                        }
                    }
                    foreach ($eba as $e) {
                        switch ($e) {
                            case "e1":
                                $vak = "Nederlandse taal en literatuur";
                                break;
                            case "e2":
                                $vak = "Engelse taal en literatuur";
                                break;
                            case "e3":
                                $vak = "Spaanse taal en literatuur";
                                break;
                            case "e4":
                                $vak = "Papiamentse taal en cultuur";
                                break;
                            case "e5":
                                $vak = "Wiskunde";
                                break;
                            case "e6":
                                $vak = "Natuur- en scheikunde 1";
                                break;
                            case "e7":
                                $vak = "Natuur- en scheikunde 2";
                                break;
                            case "e8":
                                $vak = "Biologie";
                                break;
                            case "e9":
                                $vak = "Economie / management en organisatie";
                                break;
                            case "e10":;
                                $vak = "Aardrijkskunde";
                                break;
                            case "e11":;
                                $vak = "Geschiedenis en staatsinrichting";
                                break;
                            case "e12":;
                                $vak = "Religie en levensbeschouwing";
                                break;
                            default:
                                $vak = "";
                                break;
                        }
                        echo "<tr>";
                        echo "<td>" . $vak . "</td>";
                        echo "<td class='nota'>" . $cijfers[2][$e] . "</td>";
                        echo "<td class='nota'>" . $cijfers[3][$e] . "</td>";
                        echo "<td class='nota'>" . $cijfers[5][$e] . "</td>";
                        switch ($cijfers[5][$e]) {
                            case "1":
                                $cijfer4 = "Eén";
                                break;
                            case "2":
                                $cijfer4 = "Twee";
                                break;
                            case "3":
                                $cijfer4 = "Drie";
                                break;
                            case "4":
                                $cijfer4 = "Vier";
                                break;
                            case "5":
                                $cijfer4 = "Vijf";
                                break;
                            case "6":
                                $cijfer4 = "Zes";
                                break;
                            case "7":
                                $cijfer4 = "Zeven";
                                break;
                            case "8":
                                $cijfer4 = "Acht";
                                break;
                            case "9":
                                $cijfer4 = "Negen";
                                break;
                            case "10":
                                $cijfer4 = "Tien";
                                break;
                            default:
                                $cijfer4 = "";
                                break;
                        }
                        echo "<td class='nota'>" . $cijfer4 . "</td>";
                        echo "</tr>";
                    }
                    ?>
                    <td colspan="5">Uitslag van het examen: <?php echo $cijfers[5]["tv1"] == "G" ? "Geslaagd" : ($cijfers[5]["tv1"] == "A" ? "Afgewezen" : ""); ?></td>
                </tbody>
            </table>

            <p style="text-align: right;">Aruba, 1 juli <?php echo date("Y"); ?></p>
            <p style="margin-top: 5rem;">De directeur, _____________________ <label style="margin-left: 40px;">Secretaris van het eindexamen, _____________________</label></p>
            <p style="font-size: 10px;">Doorhalen en/of wijzigingen, maken deze lijst ongeldig.</p>
            <p style="font-size: 10px;">Niet gebruikte regels en vakken in de tabel zijn ongeldig gemaakt</p>
        </main>
    <?php } ?>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            setTimeout(function() {
                window.print();
            }, 1000);
        });
    </script>
</body>

</html>