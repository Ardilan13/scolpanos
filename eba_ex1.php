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
            overflow: scroll !important;
        }
    </style>

    <main id="main" role="main">
        <section>
            <div class="container container-fs">
                <div class="row">
                    <div class="default-secondary-bg-color col-md-12 full-inset brd-bottom clearfix">
                        <h1 class="primary-color">EX. 1-M</h1>
                        <button id="btn_eba_export" class="btn btn-primary btn-m-w btn-s-h">Export</button>
                        <div class="recuadro">
                            <p><b class="cuadro cuadro_x">X</b><label>Kandidaat heeft dit vak gekozen</label></p>
                            <p><b class="cuadro cuadro_h">H</b><label>Kandidaat doet herexamen in dit vak</label></p>
                            <p><b class="cuadro cuadro_ns">NS</b><label>Ziek tijdens examen</label></p>
                            <p><b class="cuadro cuadro_v">V</b><label>Vrijstelling</label></p>
                            <p><b class="cuadro cuadro_0"> </b><label>Kandidaat heeft dit vak niet gekozen</label></p>
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

                                            $get_students = "SELECT id FROM personalia WHERE schoolid = $schoolid AND schooljaar = '$schooljaar'";
                                            $result_p = mysqli_query($mysqli, $get_students);

                                            $get_personalia = "SELECT e.id,e.*,p.code,s.lastname,s.firstname,s.profiel,s.id as studentid FROM eba_ex e INNER JOIN personalia p ON e.id_personalia = p.id INNER JOIN students s ON p.studentid = s.id WHERE e.schoolid = $schoolid AND e.schooljaar = '$schooljaar' AND s.SchoolID = $schoolid AND e.type = 0 ORDER BY";
                                            $sql_order = " lastname , firstname";
                                            if ($s->_setting_mj) {
                                                $get_personalia .= " sex " . $s->_setting_sort . ", " . $sql_order;
                                            } else {
                                                $get_personalia .=  $sql_order;
                                            }
                                            $result = mysqli_query($mysqli, $get_personalia);
                                            if ($result->num_rows > 0 && $result->num_rows == $result_p->num_rows) { ?>
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
                                                            <th class="nr">#<br>X</th>
                                                            <th class="nr">#<br>V</th>
                                                            <th class="nr">#<br>H</th>
                                                            <th class="nr">#<br>NS</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                                            <tr class="fila <?php echo $x; ?>" id="fila_<?php echo $x; ?>">
                                                                <td><?php echo $row["code"]; ?></td>
                                                                <td><?php echo $row["lastname"]; ?></td>
                                                                <td><?php echo $row["firstname"]; ?></td>
                                                                <td id="vaks_<?php echo $x; ?>"></td>
                                                                <td><input id="<?php echo $row['id']; ?>" maxlength="2" style="width: 100%;" class="text-center ex e1 i<?php echo $x; ?>" type="text" value="<?php echo $row["e1"]; ?>"></td>
                                                                <td><input id="<?php echo $row['id']; ?>" maxlength="2" style="width: 100%;" class="text-center ex e2 i<?php echo $x; ?>" type="text" value="<?php echo $row["e2"]; ?>"></td>
                                                                <td><input id="<?php echo $row['id']; ?>" maxlength="2" style="width: 100%;" class="text-center ex e3 i<?php echo $x; ?>" type="text" value="<?php echo $row["e3"]; ?>"></td>
                                                                <td><input id="<?php echo $row['id']; ?>" maxlength="2" style="width: 100%;" class="text-center ex e4 i<?php echo $x; ?>" type="text" value="<?php echo $row["e4"]; ?>"></td>
                                                                <td><input id="<?php echo $row['id']; ?>" maxlength="2" style="width: 100%;" class="text-center ex e5 i<?php echo $x; ?>" type="text" value="<?php echo $row["e5"]; ?>"></td>
                                                                <td><input id="<?php echo $row['id']; ?>" maxlength="2" style="width: 100%;" class="text-center ex e6 i<?php echo $x; ?>" type="text" value="<?php echo $row["e6"]; ?>"></td>
                                                                <td><input id="<?php echo $row['id']; ?>" maxlength="2" style="width: 100%;" class="text-center ex e7 i<?php echo $x; ?>" type="text" value="<?php echo $row["e7"]; ?>"></td>
                                                                <td><input id="<?php echo $row['id']; ?>" maxlength="2" style="width: 100%;" class="text-center ex e8 i<?php echo $x; ?>" type="text" value="<?php echo $row["e8"]; ?>"></td>
                                                                <td><input id="<?php echo $row['id']; ?>" maxlength="2" style="width: 100%;" class="text-center ex e9 i<?php echo $x; ?>" type="text" value="<?php echo $row["e9"]; ?>"></td>
                                                                <td><input id="<?php echo $row['id']; ?>" maxlength="2" style="width: 100%;" class="text-center ex e10 i<?php echo $x; ?>" type="text" value="<?php echo $row["e10"]; ?>"></td>
                                                                <td><input id="<?php echo $row['id']; ?>" maxlength="2" style="width: 100%;" class="text-center ex e11 i<?php echo $x; ?>" type="text" value="<?php echo $row["e11"]; ?>"></td>
                                                                <td><input id="<?php echo $row['id']; ?>" maxlength="2" style="width: 100%;" class="text-center ex e12 i<?php echo $x; ?>" type="text" value="<?php echo $row["e12"]; ?>"></td>
                                                                <td><?php echo $row["profiel"] ?></td>
                                                                <td id="x_<?php echo $x; ?>"></td>
                                                                <td id="v_<?php echo $x; ?>"></td>
                                                                <td id="h_<?php echo $x; ?>"></td>
                                                                <td id="ns_<?php echo $x; ?>"></td>
                                                            </tr>
                                                        <?php
                                                            $x++;
                                                        } ?>
                                                    </tbody>
                                                </table>
                                            <?php } else {
                                                $get_students = "SELECT id FROM personalia WHERE schoolid = $schoolid AND schooljaar = '$schooljaar'";
                                                $result = mysqli_query($mysqli, $get_students);
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $create_ex1 = "INSERT INTO eba_ex (type,schoolid,schooljaar,id_personalia) VALUES (0,$schoolid,'$schooljaar'," . $row["id"] . ")";
                                                    mysqli_query($mysqli, $create_ex1);
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
            var x = 0;
            var v = 0;
            var h = 0;
            var ns = 0;
            $(".i" + numero).each(function() {
                var value = $(this).val();
                if (value != null && value != "") {
                    cont++;
                    if (value.toUpperCase() == "X") {
                        x++;
                    } else if (value.toUpperCase() == "V") {
                        v++;
                    } else if (value.toUpperCase() == "H") {
                        h++;
                    } else if (value.toUpperCase() == "NS") {
                        ns++;
                    }
                }
            });
            $("#vaks_" + numero).html(cont);
            $("#x_" + numero).html(x);
            $("#v_" + numero).html(v);
            $("#h_" + numero).html(h);
            $("#ns_" + numero).html(ns);
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
        active_loader();
        $(".profiel").each(function() {
            var value = $(this).attr("class").split(" ")[1];
            var id = $(this).attr("id");
            if (value != null && value != "") {
                $("#" + id + ' option[value="' + value + '"]').attr("selected", true);
            }
        });
        $(".ex").each(function() {
            var value = $(this).val();
            changeColor(value);
            $(this).css("background-color", $color);
            if ($color == "lightgray") {
                $(this).attr("disabled", true);
            }
        });
        countVaks()
    });

    window.onload = function() {
        active_loader();
    }

    $(".ex").change(function() {
        var id = $(this).attr("id");
        var value = $(this).val().toUpperCase();
        var ex = $(this).attr("class").split(" ")[2];
        changeColor(value);
        $(this).css("background-color", $color);
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
        countVaks();
    });

    $(".profiel").change(function() {
        var id = $(this).attr("class").split(" ")[2];
        var value = $(this).children("option:selected").val();
        $.ajax({
            url: "ajax/save_eba_ex.php",
            type: "POST",
            data: {
                id: id,
                value: value,
                ex: "profiel",
            },
            success: function(data) {
                console.log(data);
            }
        });
    });

    $("#btn_eba_export").click(function() {
        window.open("dev_tests\\export_eba_ex1.php");
    });

    function active_loader() {
        $("#loader_spn").toggleClass('hidden');
    }
</script>