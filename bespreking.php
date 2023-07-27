<?php include 'document_start.php'; ?>

<?php include 'sub_nav.php'; ?>
<div class="push-content-220">
    <?php include 'header.php'; ?>
    <?php $UserRights = $_SESSION['UserRights'];
    if ($UserRights == "ADMINISTRATIE" || $UserRights == "ONDERSTEUNING" || $UserRights == "TEACHER") {
        include 'redirect.php';
    } else { ?>
        <?php
        require_once("classes/spn_setting.php");
        $s = new spn_setting();
        $s->getsetting_info($_SESSION['SchoolID'], false);

        echo "<input hidden name='setting_rapnumber_1_val' id='setting_rapnumber_1_val' value='" . $s->_setting_rapnumber_1 . "'>";
        echo "<input hidden name='setting_rapnumber_2_val' id='setting_rapnumber_2_val' value='" . $s->_setting_rapnumber_2 . "'>";
        echo "<input hidden name='setting_rapnumber_3_val' id='setting_rapnumber_3_val' value='" . $s->_setting_rapnumber_3 . "'>";

        ?>
        <main id="main" role="main">
            <section>
                <div class="container container-fs">
                    <input type="text" hidden value="<?php echo $_SESSION["SchoolID"]; ?>" id="schoolid">

                    <div class="row">
                        <div class="default-secondary-bg-color col-md-12 full-inset brd-bottom clearfix">
                            <h1 class="primary-color">RAPPORT BESPREKING</h1>
                            <?php include 'breadcrumb.php'; ?>
                        </div>
                        <div class="default-secondary-bg-color col-md-12 full-inset filter-bar brd-bottom clearfix">
                            <form id="form_opmerking" class="form-inline form-data-retriever" name="form_opmerking" role="form">
                                <fieldset>
                                    <div class="form-group">
                                        <label for="klas">Klas</label>
                                        <select class="form-control" name="houding_klassen_lijst" id="houding_klassen_lijst">
                                            <option value="NONE">NONE</option>
                                            <!-- Options populated by AJAX get -->
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="cijfers_rapporten_lijst">Rapnr.</label>
                                        <select class="form-control" name="cijfers_rapporten_lijst" id="cijfers_rapporten_lijst">
                                            <!-- Options populated by AJAX get -->
                                            <!-- TEMPORARY ENTERED MANUALLY -->
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <button id="btn_bespreking" class="btn btn-primary btn-m-w btn-m-h">zoeken</button>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                        <div class="row">
                            <div class="col-md-12 full-inset">
                                <div class="sixth-bg-color brd-full">
                                    <div class="box box_form">
                                        <div class="box-content full-inset">
                                            <div id="table" class="data-display"></div>
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
                        <!-- <div class="form-group pull-right">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-m-w btn-m-h" id="save_opmerking">Save</button>
                            </div>
                        </div> -->
                    </div>
            </section>
        </main>
        <?php include 'footer.php'; ?>
</div>

<?php include 'document_end.php';
    } ?> <!-- PLEASE CAREFULLY WIH THIS "}" THAT CLOSE SECURITY  ELSE IF -->

<script>
    $("#btn_bespreking").click(function(e) {
        e.preventDefault()
        $("#loader_spn").removeClass("hidden");
        $.ajax({
            url: "ajax/get_bespreking.php",
            data: $('#form_opmerking').serialize(),
            type: "POST",
            dataType: "text",
            success: function(text) {
                $("#loader_spn").addClass("hidden");
                $("#table").html(text);
            },
            error: function(xhr, status, errorThrown) {
                alert("error");
                location.reload();
            },
        });
    })
</script>