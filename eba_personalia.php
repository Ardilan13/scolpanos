<?php include 'document_start.php'; ?>

<?php include 'sub_nav.php'; ?>
<style>

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

                                            $schoolid = $_SESSION["SchoolID"];
                                            $schooljaar = $_SESSION["SchoolJaar"];
                                            $s->getsetting_info($schoolid, false);

                                            $get_personalia = "SELECT s.id,p.code,p.opmerking,s.lastname,s.firstname,s.sex,s.dob,s.birthplace FROM personalia p INNER JOIN students s ON s.id = p.studentid WHERE p.schoolid = $schoolid AND p.schooljaar = '$schooljaar' ORDER BY p.code;";
                                            $result = mysqli_query($mysqli, $get_personalia);
                                            if ($result->num_rows > 0) { ?>
                                                <table class="table table-bordered table-colored table-houding">
                                                    <thead>
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
                                                            if ($row["dob"] != null && $row["dob"] != "0000-00-00") {
                                                                $dob = new DateTime($row["dob"]);
                                                            } else {
                                                                $dob = null;
                                                            }
                                                        ?>
                                                            <tr>
                                                                <td><?php echo $row["code"]; ?></td>
                                                                <td><?php echo $row["lastname"]; ?></td>
                                                                <td><?php echo $row["firstname"]; ?></td>
                                                                <td><?php echo $row["sex"]; ?></td>
                                                                <td><?php echo $dob != null ? $dob->format("d M Y") : ""; ?></td>
                                                                <td><?php echo $row["birthplace"]; ?></td>
                                                                <td><input type="text" id="<?php echo $row['id']; ?>" style="width: 100%;" class="opmerking" value="<?php echo $row["opmerking"]; ?>"></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            <?php } else {
                                                $x = 1;
                                                $get_students = "SELECT id FROM students WHERE schoolid = $schoolid AND class LIKE '4%' ORDER BY";
                                                $sql_order = " lastname " . $s->_setting_sort . ", firstname";
                                                if ($s->_setting_mj) {
                                                    $get_students .= " sex " . $s->_setting_sort . ", " . $sql_order;
                                                } else {
                                                    $get_students .=  $sql_order;
                                                }
                                                $result = mysqli_query($mysqli, $get_students);
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $id = $row["id"];
                                                    $create_personalia = "INSERT INTO personalia (code,studentid, schoolid, schooljaar) VALUES ($x,$id, $schoolid, '$schooljaar');";
                                                    $result1 = mysqli_query($mysqli, $create_personalia);
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