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
    </style>

    <main id="main" role="main">
        <section>
            <div class="container container-fs">
                <div class="row">
                    <div class="default-secondary-bg-color col-md-12 full-inset brd-bottom clearfix">
                        <h1 class="primary-color">EX. 1-M</h1>
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

                                            $get_personalia = "SELECT e.id,e.*,p.code,s.lastname,s.firstname,s.profiel FROM eba_ex e INNER JOIN personalia p ON e.id_personalia = p.id INNER JOIN students s ON p.studentid = s.id WHERE e.schoolid = $schoolid AND e.schooljaar = '$schooljaar' AND s.SchoolID = $schoolid;";
                                            $result = mysqli_query($mysqli, $get_personalia);
                                            if ($result->num_rows > 0) { ?>
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
                                                                <td>
                                                                    <select class="profiel <?php echo $row["profiel"]; ?>" name="profiel" class="form-control" id="p<?php echo $x; ?>">
                                                                        <option value=""></option>
                                                                        <option value="MM01">MM01</option>
                                                                        <option value="MM02">MM02</option>
                                                                        <option value="MM03">MM03</option>
                                                                        <option value="MM04">MM04</option>
                                                                        <option value="MM05">MM05</option>
                                                                        <option value="MM06">MM06</option>
                                                                        <option value="MM07">MM07</option>
                                                                        <option value="MM08">MM08</option>
                                                                        <option value="MM09">MM09</option>
                                                                        <option value="MM10">MM10</option>
                                                                        <option value="MM11">MM11</option>
                                                                        <option value="MM12">MM12</option>
                                                                        <option value="NW01">NW01</option>
                                                                        <option value="NW02">NW02</option>
                                                                        <option value="NW03">NW03</option>
                                                                        <option value="NW04">NW04</option>
                                                                        <option value="NW05">NW05</option>
                                                                        <option value="NW06">NW06</option>
                                                                        <option value="NW07">NW07</option>
                                                                        <option value="NW08">NW08</option>
                                                                        <option value="NW09">NW09</option>
                                                                        <option value="HU07">HU07</option>
                                                                        <option value="HU08">HU08</option>
                                                                        <option value="HU09">HU09</option>
                                                                    </select>
                                                                </td>
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
                                                    $create_ex1 = "INSERT INTO eba_ex (schoolid,schooljaar,id_personalia) VALUES ($schoolid,'$schooljaar'," . $row["id"] . ")";
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
                $color = "lightgreen"
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
        $(".profiel").each(function() {
            var value = $(this).attr("class").split(" ")[1];
            var id = $(this).attr("id");
            if (value != null && value != "") {
                console.log(value);
                $("#" + id + ' option[value="' + value + '"]').attr("selected", true);
            }
        });
        $(".ex").each(function() {
            var value = $(this).val();
            changeColor(value);
            $(this).css("background-color", $color);
        });
        countVaks()
    });

    $(".ex").change(function() {
        var id = $(this).attr("id");
        var value = $(this).val().toUpperCase();
        var ex = $(this).attr("class").split(" ")[2];
        changeColor(value);
        $(this).css("background-color", $color);
        console.log(id + " " + value + " " + ex);
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
        var id = $(this).attr("id");
        var value = $(this).val();
        console.log(id + " " + value);
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
</script>