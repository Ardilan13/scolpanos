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

                                            $get_personalia = "SELECT studentid,code FROM personalia WHERE schoolid = $schoolid AND schooljaar = '$schooljaar' ORDER BY id";
                                            $result = mysqli_query($mysqli, $get_personalia);
                                            if ($result->num_rows > 0) { ?>
                                                <table class="table table-bordered table-colored table-houding">
                                                    <thead>
                                                        <tr>
                                                            <th>Nr</th>
                                                            <th>Achternaam</th>
                                                            <th>Alle Voornamen voluit</th>
                                                            <th>CKV</th>
                                                            <th>LO</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php while ($row = mysqli_fetch_assoc($result)) {
                                                            $code = $row["code"];
                                                            $get_cijfers = "SELECT 
                                                            CASE 
                                                              WHEN AVG(CASE WHEN v.volledigenaamvak = 'CKV' THEN c.gemiddelde END) < 6 THEN 'Onvoldoende'
                                                              WHEN AVG(CASE WHEN v.volledigenaamvak = 'CKV' THEN c.gemiddelde END) >= 6 THEN 'Vold'
                                                            END AS ckv,
                                                            CASE 
                                                              WHEN AVG(CASE WHEN v.volledigenaamvak = 'lo' THEN c.gemiddelde END) < 6 THEN 'Onvoldoende'
                                                              WHEN AVG(CASE WHEN v.volledigenaamvak = 'lo' THEN c.gemiddelde END) >= 6 THEN 'Vold'
                                                            END AS lo,
                                                            s.lastname,
                                                            s.firstname
                                                          FROM students s
                                                          LEFT JOIN le_cijfers c ON s.id = c.studentid AND c.schooljaar = '2021-2022' AND c.gemiddelde > 0
                                                          LEFT JOIN le_vakken v ON c.vak = v.ID AND v.schoolid = $schoolid AND v.volledigenaamvak IN ('CKV', 'lo')
                                                          WHERE s.id = $row[studentid]
                                                          GROUP BY s.lastname, s.firstname;";
                                                            $result1 = mysqli_query($mysqli, $get_cijfers);
                                                            while ($row1 = mysqli_fetch_assoc($result1)) { ?>
                                                                <tr>
                                                                    <td><?php echo $row["code"]; ?></td>
                                                                    <td><?php echo $row1["lastname"]; ?></td>
                                                                    <td><?php echo $row1["firstname"]; ?></td>
                                                                    <td><?php echo $row1["ckv"]; ?></td>
                                                                    <td><?php echo $row1["lo"]; ?></td>
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