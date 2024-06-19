<?php include 'document_start.php'; ?>

<?php include 'sub_nav.php'; ?>
<div class="push-content-220">
    <?php include 'header.php'; ?>

    <style>
        .min {
            width: 3%;
        }

        .nr {
            width: 2%;
        }

        .paket {
            width: 4%;
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

        .cuadro_d {
            background-color: #FFFF00;
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
        <input id="schoolid" type="text" hidden value="<?php echo $_SESSION['SchoolID']; ?>">
        <section>
            <div class="container container-fs">
                <div class="row">
                    <div class="default-secondary-bg-color col-md-12 full-inset brd-bottom clearfix">
                        <h1 class="primary-color">EX. 5</h1>
                        <div>
                            <button id="btn_eba_export" class="btn btn-primary btn-m-w btn-s-h">Export</button>
                            <button id="btn_cer_export" class="btn btn-primary btn-m-w btn-s-h">data voor diploma</button>
                            <button id="btn_pub1_export" class="btn btn-primary btn-m-w btn-s-h">diploma v en a</button>
                            <button id="btn_pub2_export" class="btn btn-primary btn-m-w btn-s-h">diploma 1-vak</button>
                            <button id="btn_duimen" class="btn btn-primary btn-m-w btn-s-h">Duimen</button>
                        </div>
                        <div class="recuadro">
                            <p><b class="cuadro cuadro_x">X</b><label>Kandidaat heeft dit vak gekozen</label></p>
                            <p><b class="cuadro cuadro_h">H</b><label>Kandidaat doet herexamen in dit vak</label></p>
                            <p><b class="cuadro cuadro_ns">NS</b><label>Ziek tijdens examen</label></p>
                            <p><b class="cuadro cuadro_v">V</b><label>Vrijstelling</label></p>
                            <p><b class="cuadro cuadro_0"> </b><label>Kandidaat heeft dit vak niet gekozen</label></p>
                            <p><b class="cuadro cuadro_d"> </b><label>Duimen</label></p>
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

                                            $get_personalia1 = "SELECT e.id,e.*,s.id as studentid FROM eba_ex e INNER JOIN personalia p ON e.id_personalia = p.id INNER JOIN students s ON p.studentid = s.id WHERE e.schoolid = $schoolid AND e.schooljaar = '$schooljaar' AND s.SchoolID = $schoolid AND e.type = 5;";
                                            $result1 = mysqli_query($mysqli, $get_personalia1);
                                            if ($result1->num_rows > 0) {
                                                while ($row1 = mysqli_fetch_assoc($result1)) {
                                                    $ex = $row1["id"];
                                                    $id = $row1["studentid"];
                                                    $gse = "(IF(c.c2 > c.c1, c.c2, IF(c.c1 > 0.9, c.c1, 0)) +
                                                    IF(c.c11 > 0.9, c.c11/2, 0) +
                                                    IF(c.c6 > c.c5, c.c6, IF(c.c5 > 0.9, c.c5, 0)) +
                                                    IF(c.c10 > c.c9, c.c10, IF(c.c9 > 0.9, c.c9, 0))) / 
                                                    (IF(c.c2 > 0.9, 1, IF(c.c1 > 0.9, 1, 0)) +
                                                    IF(c.c11 > 0.9, 1/2, 0) +
                                                    IF(c.c6 > 0.9, 1, IF(c.c5 > 0.9, 1, 0)) +
                                                    IF(c.c10 > 0.9, 1, IF(c.c9 > 0.9, 1, 0)))";
                                                    $ec = "IF(c.c15 > 0.9, c.c15, IF(c.c14 > 0.9, c.c14, 0))";
                                                    $po = "IF(c.c11 > 0.9, c.c11/2, 0)";
                                                    $div = "IF(c.c15 > 0.9, 1, IF(c.c14 > 0.9, 1, 0))";
                                                    $get_cijfers = "SELECT 
                                                    TRUNCATE(AVG(CASE WHEN subquery.volledigenaamvak = 'ne' THEN subquery.avg_c END), 0) AS ne,
                                                    TRUNCATE(AVG(CASE WHEN subquery.volledigenaamvak = 'en' THEN subquery.avg_c END), 0) AS en,
                                                    TRUNCATE(AVG(CASE WHEN subquery.volledigenaamvak = 'sp' THEN subquery.avg_c END), 0) AS sp,
                                                    TRUNCATE(AVG(CASE WHEN subquery.volledigenaamvak = 'pa' THEN subquery.avg_c END), 0) AS pa,
                                                    TRUNCATE(AVG(CASE WHEN subquery.volledigenaamvak = 'wi' THEN subquery.avg_c END), 0) AS wi,
                                                    TRUNCATE(AVG(CASE WHEN subquery.volledigenaamvak = 'na' THEN subquery.avg_c END), 0) AS na,
                                                    TRUNCATE(AVG(CASE WHEN subquery.volledigenaamvak = 'sk' THEN subquery.avg_c END), 0) AS sk,
                                                    TRUNCATE(AVG(CASE WHEN subquery.volledigenaamvak = 'bi' THEN subquery.avg_c END), 0) AS bi,
                                                    TRUNCATE(AVG(CASE WHEN subquery.volledigenaamvak = 'ec' THEN subquery.avg_c END), 0) AS ec,
                                                    TRUNCATE(AVG(CASE WHEN subquery.volledigenaamvak = 'ak' THEN subquery.avg_c END), 0) AS ak,
                                                    TRUNCATE(AVG(CASE WHEN subquery.volledigenaamvak = 'gs' THEN subquery.avg_c END), 0) AS gs,
                                                    TRUNCATE(AVG(CASE WHEN subquery.volledigenaamvak = 're' THEN subquery.avg_c END), 0) AS re
                                                  FROM (
                                                    SELECT 
                                                        v.volledigenaamvak,
                                                        ROUND(IF($div > 0 ,(ROUND($gse,1) + $ec)/ 2, NULL), 0) AS avg_c
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
                                                        if ($row1["e1"] != $row2["ne"] || $row1["e2"] != $row2["en"] || $row1["e3"] != $row2["sp"] || $row1["e4"] != $row2["pa"] || $row1["e5"] != $row2["wi"] || $row1["e6"] != $row2["na"] || $row1["e7"] != $row2["sk"]  || $row1["e8"] != $row2["bi"] || $row1["e9"] != $row2["ec"] || $row1["e10"] != $row2["ak"] || $row1["e11"] != $row2["gs"] || $row1["e12"] != $row2["re"]) {
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
                                                            <th class="text-center min">TV1</th>
                                                            <th class="text-center min">TV2</th>
                                                            <th>Opmerking</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                                            <tr class="fila <?php echo $x; ?>" id="fila_<?php echo $x; ?>">
                                                                <td><?php echo $row["code"]; ?></td>
                                                                <td><?php echo $row["lastname"]; ?></td>
                                                                <td><?php echo $row["firstname"]; ?></td>
                                                                <td id="vaks_<?php echo $x; ?>"></td>
                                                                <?php for ($i = 1; $i <= 12; $i++) {
                                                                    $check = false;
                                                                    switch ($row["e$i"]) {
                                                                        case "X":
                                                                            $color = "white";
                                                                            break;
                                                                        case "V":
                                                                            $color = "dodgerblue";
                                                                            break;
                                                                        case "H":
                                                                            $color = "#ccffff";
                                                                            break;
                                                                        case "NS":
                                                                            $color = "deeppink";
                                                                            break;
                                                                        case "D":
                                                                            $color = "yellow";
                                                                            $check = true;
                                                                            break;
                                                                        default:
                                                                            $color = "lightgray";
                                                                            break;
                                                                    } ?>
                                                                    <td id="<?php echo $row['studentid']; ?>" class="text-center ex e<?php echo $i; ?> i<?php echo $x; ?>" color="<?php echo $color; ?>" style="background-color: <?php echo $color; ?>;"><span></span><input id="<?php echo $row['id']; ?>" class="duimen" type="checkbox" <?php echo $check ? " checked" : ""; ?> hidden></td>
                                                                <?php } ?>
                                                                <td><?php echo $row["profiel"]; ?></td>
                                                                <td>
                                                                    <select id="<?php echo $row['studentid']; ?>" class="add ex tv1 tv">
                                                                        <option></option>
                                                                        <option value="G">G</option>
                                                                        <option value="A">A</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <select id="<?php echo $row['studentid']; ?>" class="add ex tv2 tv">
                                                                        <option></option>
                                                                        <option value="G">G</option>
                                                                        <option value="A">A</option>
                                                                    </select>
                                                                </td>
                                                                <td><input type="text" id="<?php echo $row['studentid']; ?>" class="add ex opmerking"></td>
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
                                                        ROUND( IF(c.c15 > 0.9,c.c15, NULL), 1) AS avg_c
                                                    FROM students s
                                                    LEFT JOIN le_cijfers c ON s.id = c.studentid 
                                                      AND c.schooljaar = '$schooljaar' 
                                                      AND c.gemiddelde > 0
                                                    LEFT JOIN le_vakken v ON c.vak = v.ID 
                                                      AND v.schoolid = $schoolid 
                                                      AND v.volledigenaamvak IN ('ne', 'en', 'sp', 'pa', 'wi', 'na', 'sk', 'bi', 'ec', 'ak', 'gs', 're')
                                                    WHERE s.id = $id
                                                      AND v.volledigenaamvak IS NOT NULL
                                                    GROUP BY v.volledigenaamvak
                                                  ) AS subquery;
                                                  ";
                                                    $result1 = mysqli_query($mysqli, $get_cijfers);
                                                    while ($row1 = mysqli_fetch_assoc($result1)) {
                                                        $create_ex1 = "INSERT INTO eba_ex (type,schoolid,schooljaar,id_personalia,e1,e2,e3,e4,e5,e6,e7,e8,e9,e10,e11,e12) VALUES (5,$schoolid,'$schooljaar',$personalia,'" . $row1["ne"] . "','" . $row1["en"] . "','" . $row1["sp"] . "','" . $row1["pa"] . "','" . $row1["wi"] . "','" . $row1["na"] . "','" . $row1["sk"] . "','" . $row1["bi"] . "','" . $row1["ec"] . "','" . $row1["ak"] . "','" . $row1["gs"] . "','" . $row1["re"] . "')";
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

    // function changeColor(value) {
    //     value = value.toUpperCase();
    //     switch (value) {
    //         case "X":
    //             $color = "white"
    //             break;
    //         case "V":
    //             $color = "dodgerblue"
    //             break;
    //         case "H":
    //             $color = "#ccffff"
    //             break;
    //         case "NS":
    //             $color = "deeppink"
    //             break;
    //         default:
    //             $color = "lightgray"
    //             break;
    //     }
    // }

    $(document).ready(function() {
        active_loader();

        $(".ex").each(function() {
            if ($(this).attr("class").split(" ")[0] == "add") {
                $.ajax({
                    url: "ajax/get_eba_exdocent.php",
                    type: "POST",
                    data: {
                        id: $(this).attr("id"),
                        ex: $(this).attr("class").split(" ")[2],
                        type: 5,
                    },
                    success: function(data) {
                        if (data != null && data != "") {
                            data = JSON.parse(data);
                            xd = data.ex == "opmerking" ? "input" : "select"
                            var input = $(xd + "#" + data.id + ".ex." + data.ex);
                            var optionValue = data.personalia;

                            // Seleccionar la opción correspondiente en el select
                            input.val(optionValue);
                        }
                    }
                });
            } else {
                var $color = $(this).attr("color");
                // changeColor(value);
                // $(this).css("background-color", $color);
                if ($color == "lightgray") {
                    $(this).attr("disabled", true);
                } else {
                    $.ajax({
                        url: "ajax/get_eba_exdocent.php",
                        type: "POST",
                        data: {
                            id: $(this).attr("id"),
                            ex: $(this).attr("class").split(" ")[2],
                            type: 5,
                        },
                        success: function(data) {
                            if (data != null && data != "") {
                                data = JSON.parse(data);
                                var input = $("td#" + data.id + ".ex." + data.ex + " span");
                                input.text(data.personalia);
                                countVaks()
                            }
                        }
                    });
                }
            }
        });
        countVaks()
    });

    window.onload = function() {
        active_loader();
    }

    $("#btn_eba_export").click(function() {
        window.open("dev_tests\\export_eba_ex5.php");
    });

    $("#btn_cer_export").click(function() {
        window.open("dev_tests\\export_certificate.php");
    });

    $("#btn_pub1_export").click(function() {
        schoolid = $("#schoolid").val()
        window.open("templates\\pub1_" + schoolid + ".pub");
    });

    $("#btn_pub2_export").click(function() {
        schoolid = $("#schoolid").val()
        window.open("templates\\pub2_" + schoolid + ".pub");
    });

    $("#btn_duimen").click(function() {
        $(".ex").each(function() {
            if ($(this).attr("class").split(" ")[0] != "add") {
                var $color = $(this).attr("color");
                if ($color == "white" || $color == "yellow") {
                    let id = $(this).attr("id");
                    let ex = $(this).attr("class").split(" ")[2];
                    let ix = $(this).attr("class").split(" ")[3];
                    let span = $("td#" + id + ".ex." + ex + "." + ix + " span");
                    let input = $("td#" + id + ".ex." + ex + "." + ix + " input");
                    span.toggle();
                    input.toggle();
                }
            }
        });
    });

    $(".duimen").change(function() {
        let id = $(this).attr("id");
        let ex = $(this).parent().attr("class").split(" ")[2];
        let value = $(this).prop("checked") ? "D" : "X";
        $.ajax({
            url: "ajax/save_eba_ex.php",
            type: "POST",
            data: {
                id: id,
                value: value,
                ex: ex,
            },
            success: function(data) {
                console.log(data);
            }
        });
        if ($(this).checked || $(this).prop("checked")) {
            $(this).parent().css("background-color", "yellow");
        } else {
            $(this).parent().css("background-color", "white");
        }
    });

    $(".tv,.opmerking").change(function() {
        var student = $(this).attr("id");
        var value = $(this).val();
        var ex = $(this).attr("class").split(" ")[2];
        $.ajax({
            url: "ajax/set_eba_ex5.php",
            type: "POST",
            data: {
                student: student,
                ex: ex,
                value: value,
            },
            success: function(data) {
                console.log(data);
            }
        });
    });

    function active_loader() {
        $("#loader_spn").toggleClass('hidden');
    }
</script>