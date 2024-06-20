<?php include 'document_start.php'; ?>
<?php include 'classes/DBCreds.php';
$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8'); ?>

<?php include 'sub_nav.php'; ?>
<div class="push-content-220">
    <?php include 'header.php'; ?>
    <?php $UserGUID = $_SESSION['UserGUID'];
    if ($UserGUID != "6AA26E3E-CDC8-81C0-56D0-793E9E793D99" && $UserGUID != "AB1EC7D8-3B43-3DDB-04F3-A4D38D18EBBE") {
        include 'redirect.php';
    } else { ?>
        <?php
        require_once("classes/spn_setting.php");
        $s = new spn_setting();
        $s->getsetting_info($_SESSION['SchoolID'], false);
        ?>
        <main id="main" role="main">
            <section>
                <div class="container container-fs">
                    <div class="row">
                        <div class="default-secondary-bg-color col-md-12 full-inset brd-bottom clearfix">
                            <h1 class="primary-color">TEMPLATES</h1>
                            <?php include 'breadcrumb.php'; ?>
                        </div>
                        <div class="default-secondary-bg-color col-md-12 full-inset filter-bar brd-bottom clearfix">
                            <form id="form_templates" class="form-inline" action="./ajax/save_template.php" method="post" enctype="multipart/form-data">
                                <fieldset>
                                    <div class=" form-group">
                                        <label for="school">School</label>
                                        <select class="form-control" name="school" id="school" required>
                                            <option value=""></option>
                                            <?php
                                            $get_schools = "SELECT id,schoolname,schooltype FROM schools WHERE id > 3 ORDER BY id";
                                            $result = $mysqli->query($get_schools);
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<option type='" . $row['schooltype'] . "' value='" . $row['id'] . "'>" . $row['schoolname'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="modulo">Modulo</label>
                                        <select class="form-control" name="modulo" id="modulo" required>

                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="modulo">Excel</label>
                                        <input type="file" accept=".xlsx" name="file" required>
                                    </div>
                                    <div class="form-group">
                                        <!-- <input type="submit" value="Upload"> -->
                                        <button id="btn_upload" class="btn btn-primary btn-m-w btn-m-h">Upload</button>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                        <div class="row">
                            <div class="col-md-12 full-inset">
                                <div class="sixth-bg-color brd-full">
                                    <div class="box box_form">
                                        <div class="box-content full-inset">
                                            <div id="table" class="data-display">
                                                <?php if (isset($_GET["msg"]) && $_GET["msg"] != "") {
                                                    if ($_GET["msg"] != "Error saving excel" && $_GET["msg"] != "No file uploaded or modulo not selected") {
                                                        echo "<div class='alert alert-success'>" . $_GET["msg"] . "</div>";
                                                    } else {
                                                        echo "<div class='alert alert-danger'>" . $_GET["msg"] . "</div>";
                                                    }
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

<?php include 'document_end.php';
    } ?> <!-- PLEASE CAREFULLY WIH THIS "}" THAT CLOSE SECURITY  ELSE IF -->

<script>
    $("#btn_upload").click(function(e) {
        e.preventDefault()
        $("#form_templates").submit();
    })

    $("#school").change(function() {
        var type = $("#school option:selected").attr("type");
        var school = $("#school").val();
        var modulo = $("#modulo");
        modulo.empty();
        if (type == 1) {
            modulo.append("<option value='1'>1</option>");
        } else if (type == 2) {
            modulo.append("<option value='verza_v2-1-2'>Verzamelstaten klas 1-2</option>");
            modulo.append("<option value='verza_v2-3-4'>Verzamelstaten klas 3</option>");
            modulo.append("<option value='verza_4'>Verzamelstaten klas 4</option>");
        }
    });

    $("#modulo").change(function() {
        switch ($(this).val()) {

        }
        // var school = $("#school").val();
        // var modulo = $("#modulo").val();
        // var form = $("#form_templates");
        // form.submit();
    });


    $(document).ready(function() {
        $("#school").val(0).change();
    })
</script>