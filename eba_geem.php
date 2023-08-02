<?php include 'document_start.php'; ?>

<?php include 'sub_nav.php'; ?>
<div class="push-content-220">
    <?php include 'header.php'; ?>

    <main id="main" role="main">
        <section>
            <div class="container container-fs">
                <div class="row">
                    <div class="default-secondary-bg-color col-md-12 full-inset brd-bottom clearfix">
                        <h1 class="primary-color">GEM DEEL-DAG</h1>
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

                                            $get_personalia = "SELECT c.studentid,(SELECT volledigenaamvak FROM le_vakken WHERE ID = c.vak AND SchoolID = $schoolid AND volledigenaamvak IN ('mu','bv','CKV') AND volledigenaamvak IS NOT NULL) as vak,c.gemiddelde FROM le_cijfers c INNER JOIN personalia p ON p.studentid = c.studentid WHERE c.schooljaar = '$schooljaar' AND p.schooljaar = '$schooljaar' AND p.schoolid = $schoolid AND c.gemiddelde > 0 ORDER BY c.studentid";
                                            $result = mysqli_query($mysqli, $get_personalia);
                                            if ($result->num_rows > 0) { ?>
                                                <table class="table table-bordered table-colored table-houding">
                                                    <thead>
                                                        <tr>
                                                            <th>Nr</th>
                                                            <th>Nr</th>
                                                            <th>Achternaam</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php while ($row = mysqli_fetch_assoc($result)) {
                                                            if ($row["vak"] != NULL) {
                                                                $get_student = "SELECT firstname,lastname FROM students WHERE id = " . $row["studentid"] . "";
                                                                $result_student = mysqli_query($mysqli, $get_student);
                                                                $row_name = mysqli_fetch_assoc($result_student)["firstname"];
                                                                $row_lastname = mysqli_fetch_assoc($result_student)["lastname"];
                                                        ?>
                                                                <tr>
                                                                    <td><?php echo $row_lastname; ?></td>
                                                                    <td><?php echo $row_name; ?></td>
                                                                    <td><?php echo $row["vak"]; ?></td>
                                                                    <td><?php echo $row["gemiddelde"]; ?></td>
                                                                </tr>
                                                        <?php }
                                                        } ?>
                                                    </tbody>
                                                </table>
                                            <?php } ?>
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
    $("#btn_eba_export").click(function() {
        var schoolid = $("#schoolid").val();
        var schooljaar = $("#schooljaar").val();
        window.open("dev_tests\\export_eba_gem.php");
    });
</script>