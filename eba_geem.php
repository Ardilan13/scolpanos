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

                                            $currentYear = intval(substr($schooljaar, 0, 4)); // Obtenemos el año actual (2022)
                                            $previousYear = $currentYear - 1; // Calculamos el año anterior (2021)
                                            $previousSchooljaar = $previousYear . "-" . ($previousYear + 1);
                                            $get_students = "SELECT studentid FROM personalia WHERE schoolid = $schoolid AND schooljaar = '$schooljaar'";
                                            $result = mysqli_query($mysqli, $get_students);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $id = $row["studentid"];
                                                $get_cijfers = "SELECT
                                                    CASE 
                                                      WHEN AVG(CASE WHEN v.complete_name like '%CKV%' AND c.schooljaar = '$previousSchooljaar' THEN c.gemiddelde END) < 6 THEN 0
                                                      WHEN AVG(CASE WHEN v.complete_name like '%CKV%' AND c.schooljaar = '$previousSchooljaar' THEN c.gemiddelde END) >= 6 THEN 1
                                                    END AS ckv,
                                                  
                                                    CASE
                                                      WHEN AVG(CASE WHEN v.volledigenaamvak = 'lo' AND c.schooljaar = '$schooljaar' THEN c.c1 END) < 6 THEN 0 
                                                      WHEN AVG(CASE WHEN v.volledigenaamvak = 'lo' AND c.schooljaar = '$schooljaar' THEN c.c1 END) >= 6 THEN 1
                                                    END AS lo
                                                  
                                                  FROM students s
                                                  LEFT JOIN le_cijfers c 
                                                    ON s.id = c.studentid 
                                                    AND c.gemiddelde > 0
                                                  LEFT JOIN le_vakken v
                                                    ON c.vak = v.ID  AND v.schoolid = $schoolid AND (v.volledigenaamvak IN ('CKV', 'lo') || v.complete_name like '%CKV%')
                                                                                                                      WHERE s.id = $id
                                                                                                                      GROUP BY s.lastname, s.firstname";
                                                $result1 = mysqli_query($mysqli, $get_cijfers);
                                                while ($row1 = mysqli_fetch_assoc($result1)) {
                                                    $update_result = "UPDATE personalia SET ckv = '" . $row1["ckv"] . "', lo = '" . $row1["lo"] . "' WHERE studentid = $id AND schoolid = $schoolid AND schooljaar = '$schooljaar';";
                                                    mysqli_query($mysqli, $update_result);
                                                }
                                            }

                                            $get_personalia = "SELECT s.id,p.code,p.ckv,p.lo,p.her,s.lastname,s.firstname FROM personalia p INNER JOIN students s ON s.id = p.studentid WHERE p.schoolid = $schoolid AND p.schooljaar = '$schooljaar' AND (p.ckv IS NOT NULL OR p.lo IS NOT NULL) ORDER BY";
                                            $sql_order = " lastname , firstname";
                                            if ($s->_setting_mj) {
                                                $get_personalia .= " sex " . $s->_setting_sort . ", " . $sql_order;
                                            } else {
                                                $get_personalia .=  $sql_order;
                                            }
                                            $result = mysqli_query($mysqli, $get_personalia);
                                            if ($result->num_rows > 0) { ?>
                                                <table class="table table-bordered table-colored table-houding">
                                                    <thead class="group">
                                                        <tr>
                                                            <th>Nr</th>
                                                            <th>Achternaam</th>
                                                            <th>Alle Voornamen</th>
                                                            <th>CKV</th>
                                                            <th>HER CKV</th>
                                                            <th>LO</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php while ($row = mysqli_fetch_assoc($result)) {
                                                            $id = $row["id"];
                                                            $her = ($row["her"] == 1 ? " selected" : "") ?>
                                                            <tr>
                                                                <td><?php echo $row["code"]; ?></td>
                                                                <td><?php echo $row["lastname"]; ?></td>
                                                                <td><?php echo $row["firstname"]; ?></td>
                                                                <td class="text-center"><?php echo $row["ckv"] == 1 ? "<b class='text-primary'>Voldoende</b>" : ($row["ckv"] == 0 ? "<b class='text-danger'>Onvoldoende</b>" : ""); ?></td>
                                                                <td class="text-center"><?php echo $row["ckv"] == 1 ? "" : ($row["ckv"] == 0 ? "<select class='her' id='" . $id . "'><option></option><option " . $her . " value='10'>Voldoende</option></select>" : ""); ?></td>
                                                                <td class="text-center"><?php echo $row["lo"] == 1 ? "<b class='text-primary'>Voldoende</b>" : ($row["lo"] == 0 ? "<b class='text-danger'>Onvoldoende</b>" : ""); ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            <?php } else {
                                                $currentYear = intval(substr($schooljaar, 0, 4)); // Obtenemos el año actual (2022)
                                                $previousYear = $currentYear - 1; // Calculamos el año anterior (2021)
                                                $previousSchooljaar = $previousYear . "-" . ($previousYear + 1);
                                                $get_students = "SELECT studentid FROM personalia WHERE schoolid = $schoolid AND schooljaar = '$schooljaar'";
                                                $result = mysqli_query($mysqli, $get_students);
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $id = $row["studentid"];
                                                    $get_cijfers = "SELECT
                                                    CASE 
                                                      WHEN AVG(CASE WHEN v.complete_name like '%CKV%' AND c.schooljaar = '$previousSchooljaar' THEN c.gemiddelde END) < 6 THEN 0
                                                      WHEN AVG(CASE WHEN v.complete_name like '%CKV%' AND c.schooljaar = '$previousSchooljaar' THEN c.gemiddelde END) >= 6 THEN 1
                                                    END AS ckv,
                                                  
                                                    CASE
                                                      WHEN AVG(CASE WHEN v.volledigenaamvak = 'lo' AND c.schooljaar = '$schooljaar' THEN c.c1 END) < 6 THEN 0 
                                                      WHEN AVG(CASE WHEN v.volledigenaamvak = 'lo' AND c.schooljaar = '$schooljaar' THEN c.c1 END) >= 6 THEN 1
                                                    END AS lo
                                                  
                                                  FROM students s
                                                  LEFT JOIN le_cijfers c 
                                                    ON s.id = c.studentid 
                                                    AND c.gemiddelde > 0
                                                  LEFT JOIN le_vakken v
                                                    ON c.vak = v.ID  AND v.schoolid = $schoolid AND v.volledigenaamvak IN ('CKV', 'lo')
                                                                                                                      WHERE s.id = $id
                                                                                                                      GROUP BY s.lastname, s.firstname";
                                                    $result1 = mysqli_query($mysqli, $get_cijfers);
                                                    while ($row1 = mysqli_fetch_assoc($result1)) {
                                                        $update_result = "UPDATE personalia SET ckv = '" . $row1["ckv"] . "', lo = '" . $row1["lo"] . "' WHERE studentid = $id AND schoolid = $schoolid AND schooljaar = '$schooljaar';";
                                                        mysqli_query($mysqli, $update_result);
                                                    }
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
    $("#btn_eba_export").click(function() {
        window.open("dev_tests\\export_eba_geem.php");
    });

    $(document).ready(() => {
        $(".her").each(function() {
            var value = $(this).val();
            if (value == 10) {
                $(this).css("background-color", "lawngreen");
            }
        });
    })

    $(".her").change(function() {
        var value = $(this).val();
        var id = $(this).attr("id");
        if (value == null || value == "") {
            $(this).css("background-color", "white");
        } else if (value == 10) {
            $(this).css("background-color", "lawngreen");
        }
        $.ajax({
            url: "ajax/update_eba_geem.php",
            type: "POST",
            data: {
                id: id,
                value: value
            },
            success: function(data) {
                console.log(data);
            }
        });
    });
</script>