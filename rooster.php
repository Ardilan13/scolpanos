<?php include 'document_start.php'; ?>
<?php include 'sub_nav.php'; ?>
<div class="push-content-220">
    <main id="main" role="main">
        <?php include 'header.php'; ?>
        <?php $UserRights = $_SESSION['UserRights'];
        if ($UserRights != "BEHEER") {
            include 'redirect.php';
        } else { ?>
            <section>
                <div class="container container-fs">
                    <div class="row">
                        <div class="default-secondary-bg-color col-md-12 inset brd-bottom">
                            <h1 class="primary-color">Rooster</h1>
                            <?php include 'breadcrumb.php'; ?>
                        </div>
                    </div>
                </div>

                <div class="default-secondary-bg-color col-md-12 inset filter-bar brd-bottom clearfix">
                    <form id="form-rooster" class="form-inline form-data-retriever">
                        <fieldset>
                            <div class="form-group">
                                <label for="cijfers_klassen_lijst">Klas</label>
                                <select class="form-control" name="rapport_klassen_lijst" id="rapport_klassen_lijst">
                                    <!-- Options populated by AJAX get -->

                                </select>
                            </div>
                            <div class="form-group">
                                <label for="cijfers_rapporten_lijst">SchoolJaar</label>
                                <select class="form-control" name="schooljaar_rapport" id="schooljaar">
                                    <option value="2023-2024">2023-2024</option>
                                    <option value="2022-2023">2022-2023</option>
                                    <option value="2021-2022">2021-2022</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" id="btn_rooster" class="btn btn-primary btn-m-w btn-m-h">Zoeken</button>
                            </div>

                        </fieldset>
                    </form>
                </div>

                <div class="row">
                    <div class="col-md-12 full-inset">
                        <div class="sixth-bg-color brd-full">
                            <div class="box box_form">
                                <div class="box-content full-inset">
                                    <div id="planner" class="data-display"></div>
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

<script type="text/javascript">
    $("#btn_rooster").click(function(e) {
        e.preventDefault()

        $.ajax({
            url: "ajax/get_rooster.php",
            data: $('#form-rooster').serialize(),
            type: "POST",
            dataType: "text",
            success: function(text) {
                $("#planner").html(text);
            },
            error: function(xhr, status, errorThrown) {
                alert("error");
            },
        });
    })
</script>