<?php include 'document_start.php'; ?>

<?php include 'sub_nav.php'; ?>
<div class="push-content-220">
    <?php include 'header.php'; ?>

    <style>
        .min {
            width: 4%;
        }

        .nr {
            width: 2%;
        }

        .paket {
            width: 8%;
        }

        input {
            width: 100%;
            font-weight: bold;
        }

        .recuadro {
            display: flex;
            justify-content: space-between;
        }

        .cuadro {
            padding: 1px 5px;
            border: 1px solid #000;
            text-align: center;
            font-weight: bold;
            background-color: lightgray;
        }

        .recuadro p {
            margin: 0;
        }

        p label {
            margin: 0;
            margin-right: 5px;
        }

        .cuadro_x {
            background-color: white;
        }

        .cuadro_h {
            background-color: #ccffff;
        }

        .cuadro_ns {
            background-color: deeppink;
        }

        .cuadro_v {
            background-color: dodgerblue;
        }
    </style>

    <main id="main" role="main">
        <section>
            <div class="container container-fs">
                <div class="row">
                    <div class="default-secondary-bg-color col-md-12 full-inset brd-bottom clearfix">
                        <h1 class="primary-color">EX. 2-M</h1>
                        <button id="btn_eba_export" class="btn btn-primary btn-m-w btn-s-h">Export</button>
                        <?php include 'breadcrumb.php'; ?>
                    </div>
                    <div class="row">
                        <div class="col-md-12 full-inset">
                            <div class="sixth-bg-color brd-full">
                                <div class="box box_form">
                                    <div class="box-content full-inset">
                                        <div id="table" class="data-display">
                                            <?php
                                            require_once 'classes/DBCreds.php';
                                            require_once 'classes/spn_setting.php';
                                            require_once("classes/spn_utils.php");
                                            $u = new spn_utils();
                                            $s = new spn_setting();
                                            $DBCreds = new DBCreds();
                                            $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
                                            $mysqli->set_charset('utf8');

                                            $x = 1;
                                            $schoolid = $_SESSION["SchoolID"];
                                            $schooljaar = $_SESSION["SchoolJaar"];
                                            $s->getsetting_info($schoolid, false);

                                            $get_personalia1 = "SELECT e.id,e.*,p.code,s.lastname,s.firstname,s.profiel,s.id as studentid FROM eba_ex e INNER JOIN personalia p ON e.id_personalia = p.id INNER JOIN students s ON p.studentid = s.id WHERE e.schoolid = $schoolid AND e.schooljaar = '$schooljaar' AND s.SchoolID = $schoolid AND e.type = 2;";
                                            $result1 = mysqli_query($mysqli, $get_personalia1);
                                            if ($result1->num_rows > 0) {
                                                $get_personalia = "SELECT e.id,e.*,p.code,s.lastname,s.firstname,s.profiel,s.id as studentid FROM eba_ex e INNER JOIN personalia p ON e.id_personalia = p.id INNER JOIN students s ON p.studentid = s.id WHERE e.schoolid = $schoolid AND e.schooljaar = '$schooljaar' AND s.SchoolID = $schoolid AND e.type = 0;";
                                                $result = mysqli_query($mysqli, $get_personalia); ?>
                                                <table class="table table-bordered table-colored table-houding">
                                                    <thead>
                                                        <tr>
                                                            <th class="nr">Nr</th>
                                                            <th>Achternaam</th>
                                                            <th>Alle Voornamen</th>
                                                            <th class="nr">#<br>Vak</th>
                                                            <th class="text-center min">ne</th>
                                                            <th class="text-center min">en</th>
                                                            <th class="text-center min">sp</th>
                                                            <th class="text-center min">pa</th>
                                                            <th class="text-center min">wi</th>
                                                            <th class="text-center min">na<br>sk1</th>
                                                            <th class="text-center min">na<br>sk2</th>
                                                            <th class="text-center min">bi</th>
                                                            <th class="text-center min">ec</th>
                                                            <th class="text-center min">ak</th>
                                                            <th class="text-center min">gs</th>
                                                            <th class="text-center min">re</th>
                                                            <th class="paket">Paket</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                                            <tr class="fila <?php echo $x; ?>" id="fila_<?php echo $x; ?>">
                                                                <td><?php echo $row["code"]; ?></td>
                                                                <td><?php echo $row["lastname"]; ?></td>
                                                                <td><?php echo $row["firstname"]; ?></td>
                                                                <td id="vaks_<?php echo $x; ?>"></td>
                                                                <td id="<?php echo $row['studentid']; ?>" class="text-center ex e1 i<?php echo $x; ?>" color="<?php echo $row["e1"]; ?>"></td>
                                                                <td id="<?php echo $row['studentid']; ?>" class="text-center ex e2 i<?php echo $x; ?>" color="<?php echo $row["e2"]; ?>"></td>
                                                                <td id="<?php echo $row['studentid']; ?>" class="text-center ex e3 i<?php echo $x; ?>" color="<?php echo $row["e3"]; ?>"></td>
                                                                <td id="<?php echo $row['studentid']; ?>" class="text-center ex e4 i<?php echo $x; ?>" color="<?php echo $row["e4"]; ?>"></td>
                                                                <td id="<?php echo $row['studentid']; ?>" class="text-center ex e5 i<?php echo $x; ?>" color="<?php echo $row["e5"]; ?>"></td>
                                                                <td id="<?php echo $row['studentid']; ?>" class="text-center ex e6 i<?php echo $x; ?>" color="<?php echo $row["e6"]; ?>"></td>
                                                                <td id="<?php echo $row['studentid']; ?>" class="text-center ex e7 i<?php echo $x; ?>" color="<?php echo $row["e7"]; ?>"></td>
                                                                <td id="<?php echo $row['studentid']; ?>" class="text-center ex e8 i<?php echo $x; ?>" color="<?php echo $row["e8"]; ?>"></td>
                                                                <td id="<?php echo $row['studentid']; ?>" class="text-center ex e9 i<?php echo $x; ?>" color="<?php echo $row["e9"]; ?>"></td>
                                                                <td id="<?php echo $row['studentid']; ?>" class="text-center ex e10 i<?php echo $x; ?>" color="<?php echo $row["e10"]; ?>"></td>
                                                                <td id="<?php echo $row['studentid']; ?>" class="text-center ex e11 i<?php echo $x; ?>" color="<?php echo $row["e11"]; ?>"></td>
                                                                <td id="<?php echo $row['studentid']; ?>" class="text-center ex e12 i<?php echo $x; ?>" color="<?php echo $row["e12"]; ?>"></td>
                                                                <td><?php echo $row["profiel"]; ?></td>
                                                            </tr>
                                                        <?php
                                                            $x++;
                                                        } ?>
                                                    </tbody>
                                                </table>
                                            <?php } else {
                                                $currentYear = intval(substr($schooljaar, 0, 4)); // Obtenemos el año actual (2022)
                                                $previousYear = $currentYear - 1; // Calculamos el año anterior (2021)
                                                $previousSchooljaar = $previousYear . "-" . ($previousYear + 1);
                                                $get_students = "SELECT studentid,id FROM personalia WHERE schoolid = $schoolid AND schooljaar = '$schooljaar'";
                                                $result = mysqli_query($mysqli, $get_students);
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $id = $row["studentid"];
                                                    $personalia = $row["id"];
                                                    $get_cijfers = "SELECT 
                                                    ROUND(AVG(CASE WHEN v.volledigenaamvak = 'ne' THEN c.gemiddelde END), 1) AS ne,
                                                    ROUND(AVG(CASE WHEN v.volledigenaamvak = 'en' THEN c.gemiddelde END), 1) AS en,
                                                    ROUND(AVG(CASE WHEN v.volledigenaamvak = 'sp' THEN c.gemiddelde END), 1) AS sp,
                                                    ROUND(AVG(CASE WHEN v.volledigenaamvak = 'pa' THEN c.gemiddelde END), 1) AS pa,
                                                    ROUND(AVG(CASE WHEN v.volledigenaamvak = 'wi' THEN c.gemiddelde END), 1) AS wi,
                                                    ROUND(AVG(CASE WHEN v.volledigenaamvak = 'na' THEN c.gemiddelde END), 1) AS na,
                                                    ROUND(AVG(CASE WHEN v.volledigenaamvak = 'bi' THEN c.gemiddelde END), 1) AS bi,
                                                    ROUND(AVG(CASE WHEN v.volledigenaamvak = 'ec' THEN c.gemiddelde END), 1) AS ec,
                                                    ROUND(AVG(CASE WHEN v.volledigenaamvak = 'ak' THEN c.gemiddelde END), 1) AS ak,
                                                    ROUND(AVG(CASE WHEN v.volledigenaamvak = 'gs' THEN c.gemiddelde END), 1) AS gs,
                                                    ROUND(AVG(CASE WHEN v.volledigenaamvak = 're' THEN c.gemiddelde END), 1) AS re
                                                  FROM students s
                                                  LEFT JOIN le_cijfers c ON s.id = c.studentid AND c.schooljaar = '$previousSchooljaar' AND c.gemiddelde > 0
                                                  LEFT JOIN le_vakken v ON c.vak = v.ID AND v.schoolid = $schoolid AND v.volledigenaamvak IN ('ne', 'en', 'sp', 'pa', 'wi', 'na', 'bi', 'ec', 'ak', 'gs', 're')
                                                  WHERE s.id = $id;";
                                                    $result1 = mysqli_query($mysqli, $get_cijfers);
                                                    while ($row1 = mysqli_fetch_assoc($result1)) {
                                                        $create_ex1 = "INSERT INTO eba_ex (type,schoolid,schooljaar,id_personalia,e1,e2,e3,e4,e5,e6,e7,e8,e9,e10,e11,e12) VALUES (2,$schoolid,'$schooljaar',$personalia,'" . $row1["ne"] . "','" . $row1["en"] . "','" . $row1["sp"] . "','" . $row1["pa"] . "','" . $row1["wi"] . "','" . $row1["na"] . "','" . $row1["na"] . "','" . $row1["bi"] . "','" . $row1["ec"] . "','" . $row1["ak"] . "','" . $row1["gs"] . "','" . $row1["re"] . "')";
                                                        mysqli_query($mysqli, $create_ex1);
                                                    }
                                                }
                                                header("Refresh:0");
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 full-inset">
                            <div id="loader_spn" class="hidden">
                                <div class="loader_spn"></div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </main>
    <?php include 'footer.php'; ?>
</div>

<?php include 'document_end.php'; ?>

<script>
    function countVaks() {
        $(".fila").each(function() {
            var numero = $(this).attr("class").split(" ")[1];
            var cont = 0;
            $(".i" + numero).each(function() {
                var value = $(this).text();
                if (value != null && value != "") {
                    cont++;
                }
            });
            $("#vaks_" + numero).html(cont);
        });
    }

    function changeColor(value) {
        value = value.toUpperCase();
        switch (value) {
            case "X":
                $color = "white"
                break;
            case "V":
                $color = "dodgerblue"
                break;
            case "H":
                $color = "#ccffff"
                break;
            case "NS":
                $color = "deeppink"
                break;
            default:
                $color = "lightgray"
                break;
        }
    }

    $(document).ready(function() {
        $(".ex").each(function() {
            var value = $(this).attr("color");
            changeColor(value);
            $(this).css("background-color", $color);
            if ($color == "lightgray") {
                $(this).attr("disabled", true);
            } else {
                $.ajax({
                    url: "ajax/get_eba_exdocent.php",
                    type: "POST",
                    data: {
                        id: $(this).attr("id"),
                        ex: $(this).attr("class").split(" ")[2],
                        type: 2,
                    },
                    success: function(data) {
                        if (data != null && data != "") {
                            data = JSON.parse(data);
                            var input = $("td#" + data.id + ".ex." + data.ex);
                            input.text(data.personalia);
                            countVaks()
                        }
                    }
                });
            }
        });
        countVaks()
    });

    $("#btn_eba_export").click(function() {
        window.open("dev_tests\\export_eba_exdocent.php?type=2");
    });
</script>