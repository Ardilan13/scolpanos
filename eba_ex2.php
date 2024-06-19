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

        .group {
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .table-responsive {
            height: 600px !important;
            overflow-y: scroll !important;
        }
    </style>

    <main id="main" role="main">
        <section>
            <div class="container container-fs">
                <div class="row">
                    <div class="default-secondary-bg-color col-md-12 full-inset brd-bottom clearfix">
                        <h1 class="primary-color">EX. 2-M</h1>
                        <div style="display: flex; gap: 30rem;">
                            <button id="btn_eba_export" class="btn btn-primary btn-m-w btn-s-h">Export</button>
                            <p id="aviso" style="background-color: #ffdc66; border: 1px solid black; max-width: 400px;">SE GEMIDDELD</p>
                        </div>
                        <?php include 'breadcrumb.php'; ?>
                    </div>
                    <div class="row">
                        <div class="col-md-12 full-inset">
                            <div class="sixth-bg-color brd-full">
                                <div class="box box_form">
                                    <div class="box-content full-inset">
                                        <div id="table" class="data-display table-responsive">
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

                                            $get_personalia1 = "SELECT e.id,e.*,s.id as studentid FROM eba_ex e INNER JOIN personalia p ON e.id_personalia = p.id INNER JOIN students s ON p.studentid = s.id WHERE e.schoolid = $schoolid AND e.schooljaar = '$schooljaar' AND s.SchoolID = $schoolid AND e.type = 2;";
                                            $result1 = mysqli_query($mysqli, $get_personalia1);
                                            if ($result1->num_rows > 0) {
                                                while ($row1 = mysqli_fetch_assoc($result1)) {
                                                    $ex = $row1["id"];
                                                    $id = $row1["studentid"];
                                                    $get_cijfers = "SELECT 
                                                    ROUND(AVG(CASE WHEN subquery.volledigenaamvak = 'ne' THEN subquery.avg_c END), 1) AS ne,
                                                    ROUND(AVG(CASE WHEN subquery.volledigenaamvak = 'en' THEN subquery.avg_c END), 1) AS en,
                                                    ROUND(AVG(CASE WHEN subquery.volledigenaamvak = 'sp' THEN subquery.avg_c END), 1) AS sp,
                                                    ROUND(AVG(CASE WHEN subquery.volledigenaamvak = 'pa' THEN subquery.avg_c END), 1) AS pa,
                                                    ROUND(AVG(CASE WHEN subquery.volledigenaamvak = 'wi' THEN subquery.avg_c END), 1) AS wi,
                                                    ROUND(AVG(CASE WHEN subquery.volledigenaamvak = 'na' THEN subquery.avg_c END), 1) AS na,
                                                    ROUND(AVG(CASE WHEN subquery.volledigenaamvak = 'sk' THEN subquery.avg_c END), 1) AS sk,
                                                    ROUND(AVG(CASE WHEN subquery.volledigenaamvak = 'bi' THEN subquery.avg_c END), 1) AS bi,
                                                    ROUND(AVG(CASE WHEN subquery.volledigenaamvak = 'ec' THEN subquery.avg_c END), 1) AS ec,
                                                    ROUND(AVG(CASE WHEN subquery.volledigenaamvak = 'ak' THEN subquery.avg_c END), 1) AS ak,
                                                    ROUND(AVG(CASE WHEN subquery.volledigenaamvak = 'gs' THEN subquery.avg_c END), 1) AS gs,
                                                    ROUND(AVG(CASE WHEN subquery.volledigenaamvak = 're' THEN subquery.avg_c END), 1) AS re
                                                  FROM (
                                                    SELECT 
                                                        v.volledigenaamvak,
                                                        ROUND(
                                                          AVG(
                                                            (
                                                              IF(GREATEST(IFNULL(c.c1, 0), IFNULL(c.c2, 0)) = 0, NULL,GREATEST(IFNULL(c.c1, 0), IFNULL(c.c2, 0))) + 
                                                              IF(GREATEST(IFNULL(c.c5, 0), IFNULL(c.c6, 0)) = 0, NULL, GREATEST(IFNULL(c.c5, 0), IFNULL(c.c6, 0))) + 
                                                              IF(GREATEST(IFNULL(c.c9, 0), IFNULL(c.c10, 0)) = 0, NULL, GREATEST(IFNULL(c.c9, 0), IFNULL(c.c10, 0))) +
                                                              IFNULL((c.c11/2), 0)
                                                            ) / 
                                                            (
                                                              GREATEST(IF(c.c1 > 0.9, 1, 0), IF(c.c2 > 0.9, 1, 0)) + 
                                                              GREATEST(IF(c.c5 > 0.9, 1, 0), IF(c.c6 > 0.9, 1, 0)) + 
                                                              GREATEST(IF(c.c9 > 0.9, 1, 0), IF(c.c10 > 0.9, 1, 0)) +
                                                              IF(c.c11 > 0.9, 0.5, 0)
                                                            )
                                                          ), 
                                                          1
                                                        ) AS avg_c
                                                    FROM students s
                                                    LEFT JOIN le_cijfers c ON s.id = c.studentid 
                                                      AND c.schooljaar = '$schooljaar' 
                                                      AND c.gemiddelde > 0
                                                    LEFT JOIN le_vakken v ON c.vak = v.ID 
                                                      AND v.schoolid = $schoolid 
                                                      AND v.volledigenaamvak IN ('ne', 'en', 'sp', 'pa', 'wi', 'na','sk', 'bi', 'ec', 'ak', 'gs', 're')
                                                    WHERE s.id = $id
                                                      AND v.volledigenaamvak IS NOT NULL
                                                    GROUP BY v.volledigenaamvak
                                                  ) AS subquery;";
                                                    $result2 = mysqli_query($mysqli, $get_cijfers);
                                                    while ($row2 = mysqli_fetch_assoc($result2)) {
                                                        if ($row1["e1"] != $row2["ne"] || $row1["e2"] != $row2["en"] || $row1["e3"] != $row2["sp"] || $row1["e4"] != $row2["pa"] || $row1["e5"] != $row2["wi"] || $row1["e6"] != $row2["na"] || $row1["e7"] != $row2["sk"] || $row1["e8"] != $row2["bi"] || $row1["e9"] != $row2["ec"] || $row1["e10"] != $row2["ak"] || $row1["e11"] != $row2["gs"] || $row1["e12"] != $row2["re"]) {
                                                            $create_ex1 = "UPDATE eba_ex SET e1 = '" . $row2["ne"] . "', e2 = '" . $row2["en"] . "', e3 ='" . $row2["sp"] . "',e4 ='" . $row2["pa"] . "',e5 ='" . $row2["wi"] . "',e6='" . $row2["na"] . "',e7='" . $row2["sk"] . "',e8='" . $row2["bi"] . "',e9='" . $row2["ec"] . "',e10='" . $row2["ak"] . "',e11='" . $row2["gs"] . "',e12='" . $row2["re"] . "' WHERE id = $ex;";
                                                            mysqli_query($mysqli, $create_ex1);
                                                        }
                                                    }
                                                }

                                                $get_personalia = "SELECT e.id,e.*,p.code,s.lastname,s.firstname,s.profiel,s.id as studentid FROM eba_ex e INNER JOIN personalia p ON e.id_personalia = p.id INNER JOIN students s ON p.studentid = s.id WHERE e.schoolid = $schoolid AND e.schooljaar = '$schooljaar' AND s.SchoolID = $schoolid AND e.type = 0 ORDER BY";
                                                $sql_order = " lastname , firstname";
                                                if ($s->_setting_mj) {
                                                    $get_personalia .= " sex " . $s->_setting_sort . ", " . $sql_order;
                                                } else {
                                                    $get_personalia .=  $sql_order;
                                                }
                                                $result = mysqli_query($mysqli, $get_personalia); ?>
                                                <table class="table table-bordered table-colored table-houding">
                                                    <thead class="group">
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

                                                $get_students = "SELECT studentid,id FROM personalia WHERE schoolid = $schoolid AND schooljaar = '$schooljaar'";
                                                $result = mysqli_query($mysqli, $get_students);
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $id = $row["studentid"];
                                                    $personalia = $row["id"];
                                                    $get_cijfers = "SELECT 
                                                    ROUND(AVG(CASE WHEN subquery.volledigenaamvak = 'ne' THEN subquery.avg_c END), 1) AS ne,
                                                    ROUND(AVG(CASE WHEN subquery.volledigenaamvak = 'en' THEN subquery.avg_c END), 1) AS en,
                                                    ROUND(AVG(CASE WHEN subquery.volledigenaamvak = 'sp' THEN subquery.avg_c END), 1) AS sp,
                                                    ROUND(AVG(CASE WHEN subquery.volledigenaamvak = 'pa' THEN subquery.avg_c END), 1) AS pa,
                                                    ROUND(AVG(CASE WHEN subquery.volledigenaamvak = 'wi' THEN subquery.avg_c END), 1) AS wi,
                                                    ROUND(AVG(CASE WHEN subquery.volledigenaamvak = 'na' THEN subquery.avg_c END), 1) AS na,
                                                    ROUND(AVG(CASE WHEN subquery.volledigenaamvak = 'sk' THEN subquery.avg_c END), 1) AS sk,
                                                    ROUND(AVG(CASE WHEN subquery.volledigenaamvak = 'bi' THEN subquery.avg_c END), 1) AS bi,
                                                    ROUND(AVG(CASE WHEN subquery.volledigenaamvak = 'ec' THEN subquery.avg_c END), 1) AS ec,
                                                    ROUND(AVG(CASE WHEN subquery.volledigenaamvak = 'ak' THEN subquery.avg_c END), 1) AS ak,
                                                    ROUND(AVG(CASE WHEN subquery.volledigenaamvak = 'gs' THEN subquery.avg_c END), 1) AS gs,
                                                    ROUND(AVG(CASE WHEN subquery.volledigenaamvak = 're' THEN subquery.avg_c END), 1) AS re
                                                  FROM (
                                                    SELECT 
                                                        v.volledigenaamvak,
                                                        ROUND(
                                                          AVG(
                                                            (
                                                              IF(GREATEST(IFNULL(c.c1, 0), IFNULL(c.c2, 0)) = 0, NULL,GREATEST(IFNULL(c.c1, 0), IFNULL(c.c2, 0))) + 
                                                              IF(GREATEST(IFNULL(c.c5, 0), IFNULL(c.c6, 0)) = 0, NULL, GREATEST(IFNULL(c.c5, 0), IFNULL(c.c6, 0))) + 
                                                              IF(GREATEST(IFNULL(c.c9, 0), IFNULL(c.c10, 0)) = 0, NULL, GREATEST(IFNULL(c.c9, 0), IFNULL(c.c10, 0))) +
                                                              IFNULL((c.c11/2), 0)
                                                            ) / 
                                                            (
                                                              GREATEST(IF(c.c1 > 0.9, 1, 0), IF(c.c2 > 0.9, 1, 0)) + 
                                                              GREATEST(IF(c.c5 > 0.9, 1, 0), IF(c.c6 > 0.9, 1, 0)) + 
                                                              GREATEST(IF(c.c9 > 0.9, 1, 0), IF(c.c10 > 0.9, 1, 0)) +
                                                              IF(c.c11 > 0.9, 0.5, 0)
                                                            )
                                                          ), 
                                                          1
                                                        ) AS avg_c
                                                    FROM students s
                                                    LEFT JOIN le_cijfers c ON s.id = c.studentid 
                                                      AND c.schooljaar = '$schooljaar' 
                                                      AND c.gemiddelde > 0
                                                    LEFT JOIN le_vakken v ON c.vak = v.ID 
                                                      AND v.schoolid = $schoolid 
                                                      AND v.volledigenaamvak IN ('ne', 'en', 'sp', 'pa', 'wi', 'na','sk', 'bi', 'ec', 'ak', 'gs', 're')
                                                    WHERE s.id = $id
                                                      AND v.volledigenaamvak IS NOT NULL
                                                    GROUP BY v.volledigenaamvak
                                                  ) AS subquery;
                                                  ";
                                                    $result1 = mysqli_query($mysqli, $get_cijfers);
                                                    while ($row1 = mysqli_fetch_assoc($result1)) {
                                                        $create_ex1 = "INSERT INTO eba_ex (type,schoolid,schooljaar,id_personalia,e1,e2,e3,e4,e5,e6,e7,e8,e9,e10,e11,e12) VALUES (2,$schoolid,'$schooljaar',$personalia,'" . $row1["ne"] . "','" . $row1["en"] . "','" . $row1["sp"] . "','" . $row1["pa"] . "','" . $row1["wi"] . "','" . $row1["na"] . "','" . $row1["sk"] . "','" . $row1["bi"] . "','" . $row1["ec"] . "','" . $row1["ak"] . "','" . $row1["gs"] . "','" . $row1["re"] . "')";
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
            case "D":
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
        active_loader();

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

    window.onload = function() {
        active_loader();
    }

    $("#btn_eba_export").click(function() {
        window.open("dev_tests\\export_eba_exdocent.php?type=2");
    });

    function active_loader() {
        $("#loader_spn").toggleClass('hidden');
    }
</script>