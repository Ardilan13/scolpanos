<?php include 'document_start.php'; ?>
<?php include 'sub_nav.php'; ?>
<div class="push-content-220">
    <?php include 'header.php'; ?>
    <?php $UserRights = $_SESSION['UserRights'];
    if ($UserRights == "ADMINISTRATIE" || $UserRights == "ONDERSTEUNING" || $UserRights == "TEACHER") {
        include 'redirect.php';
    } else { ?>
        <main id="main" role="main">
            <section>
                <div class="container container-fs">
                    <div class="row">
                        <div class="default-secondary-bg-color col-md-12 inset brd-bottom clearfix">
                            <h1 class="primary-color">Cijferlist:</h1>
                            <?php include 'breadcrumb.php'; ?>
                        </div>
                        <div class="default-secondary-bg-color col-md-12 inset filter-bar brd-bottom clearfix">
                            <form id="form-vrapportexport" class="form-inline form-data-retriever" name="filter-vak" role="form" method="GET" action="/dev_tests/test_exportexcel.php">
                                <fieldset>
                                    <div class="form-group">
                                        <label for="cijfers_klassen_lijst">Klas</label>
                                        <select class="form-control" name="klas_rapport" id="klas_rapport">
                                            <option value="4A">4A</option>
                                            <option value="4B">4B</option>
                                            <option value="4C">4C</option>
                                            <option value="4D">4D</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="cijfers_schooljaar_lijst">SchoolJaar</label>
                                        <select class="form-control" name="schooljaar_rapport" id="schooljaar_rapport">
                                            <option value="2023-2024">2023-2024</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="klas">Student</label>
                                        <select class="form-control" id="opmerking_student_name" name="opmerking_student_name">

                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <button data-display="data-display" type="submit" id="btn_tussen_rapport" name="btn_tussen_rapport" class="btn btn-primary btn-m-w btn-m-h">Export</button>
                                    </div>

                                </fieldset>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 full-inset">
                            <div class="sixth-bg-color brd-full">
                                <div class="box">
                                    <div class="box-content full-inset">

                                        <div class="full-inset alert alert-info reset-mrg-bottom" id="message_rapport">
                                            <p><i class="fa fa-info"></i> Selecteer de klas, vak en tussen rapport bovenaan en klik op zoeken.</p>
                                        </div>
                                        <div class="full-inset alert alert-info reset-mrg-bottom hidden" id="popup_message_rapport">
                                            <p><i class="fa fa-info"></i> If you have problems downloading the excel file, check your pop-up settings</p>
                                        </div>
                                        <div class="full-inset alert alert-warning reset-mrg-bottom hidden" id="klas_message_rapport">
                                            <p><i class="fa fa-warning"></i> You must select one Klas to export rapport...</p>
                                        </div>
                                        <div class="full-inset alert alert-warning reset-mrg-bottom hidden" id="setting_message_rapport">
                                            <p><i class="fa fa-warning"></i> There should be a Setting created for this schooljaar to generate a rapport...</p>
                                        </div>
                                        <!-- <div class="data-display"></div> -->
                                        <?php include 'modal_cijfers.php'; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <?php include 'footer.php'; ?>
</div>

<?php include 'document_end.php';
    } ?>
<!-- PLEASE CAREFULLY WIH THIS "}" THAT CLOSE SECURITY  ELSE IF -->
<script>
    $("#btn_tussen_rapport").click(function(e) {
        e.preventDefault();
        window.open("print_cijferlist.php?schoolJaar=" + $('#schooljaar_rapport').val() + "&klas=" + $('#klas_rapport').val() + "&studentid=" + $('#opmerking_student_name').val());
    });
    $("#klas_rapport").change(function() {
        var varCClass = $("#klas_rapport option:selected").val();
        $.post("ajax/getstudentrap.php", {
                class: varCClass
            },
            function(data) {
                $("#opmerking_student_name").html(data);
            });
    });
    $("#schooljaar_rapport").change(function() {
        var varCClass = $("#klas_rapport option:selected").val();
        var varSchooljaar = $("#schooljaar_rapport option:selected").val();
        $.post("ajax/getstudentrap.php", {
                class: varCClass,
                schooljaar: varSchooljaar
            },
            function(data) {
                console.log(data);
                $("#opmerking_student_name").html(data);
            });
    });
    $(document).ready(function(e) {
        setTimeout(function() {
            var varCClass = $("#klas_rapport option:selected").val();
            $.post("ajax/getstudentrap.php", {
                    class: varCClass
                },
                function(data) {
                    $("#opmerking_student_name").html(data);
                });
        }, 1000);
    });
</script>