<?php include 'document_start.php'; ?>

<?php include 'sub_nav.php'; ?>
<style>
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
<div class="push-content-220">
    <?php include 'header.php'; ?>

    <main id="main" role="main">
        <section>
            <div class="container container-fs">
                <input type="text" hidden value="<?php echo $_SESSION["SchoolID"]; ?>" id="schoolid">
                <input type="text" hidden value="<?php echo $_SESSION["SchoolJaar"]; ?>" id="schooljaar">

                <div class="row">
                    <div class="default-secondary-bg-color col-md-12 full-inset brd-bottom clearfix">
                        <h1 class="primary-color">PERSONALIA</h1>
                        <button id="btn_eba_export" class="btn btn-primary btn-m-w btn-s-h">Export</button>
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

                                            $schoolid = $_SESSION["SchoolID"];
                                            $schooljaar = $_SESSION["SchoolJaar"];
                                            $s->getsetting_info($schoolid, false);

                                            $get_students = "SELECT id FROM personalia WHERE schoolid = $schoolid AND schooljaar = '$schooljaar'";
                                            $result_p = mysqli_query($mysqli, $get_students);

                                            $get_personalia = "SELECT s.id,p.code,p.opmerking,s.lastname,s.firstname,s.sex,s.dob,s.birthplace FROM personalia p INNER JOIN students s ON s.id = p.studentid WHERE p.schoolid = $schoolid AND p.schooljaar = '$schooljaar' ORDER BY";
                                            $sql_order = " s.lastname , s.firstname";
                                            if ($s->_setting_mj) {
                                                $get_personalia .= " s.sex " . $s->_setting_sort . ", " . $sql_order;
                                            } else {
                                                $get_personalia .=  $sql_order;
                                            }
                                            $result = mysqli_query($mysqli, $get_personalia);
                                            if ($result->num_rows > 0 && $result->num_rows == $result_p->num_rows) { ?>
                                                <table class="table table-bordered table-colored table-houding">
                                                    <thead class="group">
                                                        <tr>
                                                            <th>Nr</th>
                                                            <th>Achternaam</th>
                                                            <th>Alle Voornamen</th>
                                                            <th>V/M</th>
                                                            <th>Geboorte Datum</th>
                                                            <th>Geboorte Land</th>
                                                            <th>Opmerkingen</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php while ($row = mysqli_fetch_assoc($result)) {
                                                            if ($row["dob"] != "0000-00-00" && $row["dob"] != null) {
                                                                $timestamp = strtotime($row["dob"]);
                                                                $dia = date("j", $timestamp);
                                                                $mes = date("n", $timestamp);
                                                                $ano = date("Y", $timestamp);
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
                                                                $fecha = $dia . " " . $mes . " " . $ano;
                                                            } else {
                                                                $fecha = "";
                                                            }
                                                        ?>
                                                            <tr>
                                                                <td><?php echo $row["code"]; ?></td>
                                                                <td><?php echo $row["lastname"]; ?></td>
                                                                <td><?php echo $row["firstname"]; ?></td>
                                                                <td><?php echo strtolower($row["sex"]); ?></td>
                                                                <td><?php echo $fecha; ?>
                                                                </td>
                                                                <td><?php echo ucwords(strtolower($row["birthplace"])); ?></td>
                                                                <td><input type="text" id="<?php echo $row['id']; ?>" style="width: 100%;" class="opmerking" value="<?php echo $row["opmerking"]; ?>"></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            <?php } else {
                                                $x = 1;
                                                $get_students = "SELECT id FROM students WHERE schoolid = $schoolid AND class LIKE '4%' ORDER BY";
                                                $sql_order = " lastname , firstname";
                                                if ($s->_setting_mj) {
                                                    $get_students .= " sex " . $s->_setting_sort . ", " . $sql_order;
                                                } else {
                                                    $get_students .=  $sql_order;
                                                }
                                                $result = mysqli_query($mysqli, $get_students);
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $id = $row["id"];
                                                    $select_personalia = "SELECT id FROM personalia WHERE studentid = $id AND schoolid = $schoolid AND schooljaar = '$schooljaar';";
                                                    $result1 = mysqli_query($mysqli, $select_personalia);
                                                    if ($result1->num_rows == 0) {
                                                        $create_personalia = "INSERT INTO personalia (code,studentid, schoolid, schooljaar) VALUES ($x,$id, $schoolid, '$schooljaar');";
                                                        $result1 = mysqli_query($mysqli, $create_personalia);
                                                    }

                                                    $x++;
                                                }
                                                header("Refresh:0");
                                            }
                                            ?>
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
    $(".opmerking").change(function() {
        var id = $(this).attr("id");
        var opmerking = $(this).val();
        $.ajax({
            url: "ajax/save_personalia.php",
            type: "POST",
            data: {
                id: id,
                opmerking: opmerking,
            },
            success: function(data) {
                console.log(data);
            }
        });
    });

    $("#btn_eba_export").click(function() {
        var schoolid = $("#schoolid").val();
        var schooljaar = $("#schooljaar").val();
        window.open("dev_tests\\export_eba_personalia.php");
    });
</script>