<?php include 'document_start.php'; ?>

<?php include 'sub_nav.php'; ?>
<div class="push-content-220">
    <main id="main" role="main">
        <?php include 'header.php'; ?>
        <section>
            <div class="container container-fs">
                <div class="row">
                    <div class="default-secondary-bg-color col-md-12 inset brd-bottom">
                        <h1 class="primary-color">Rapport Montessori</h1>
                        <?php include 'breadcrumb.php'; ?>
                    </div>
                    <div class="default-secondary-bg-color col-md-12 inset filter-bar brd-bottom clearfix">
                        <form id="form-vak" class="form-inline form-data-retriever" name="filter-vak" role="form">
                            <fieldset>
                                <div class="form-group">
                                    <label for="klas">Klas</label>
                                    <select class="form-control" name="houding_klassen_lijst" id="houding_klassen_lijst">
                                        <option value="NONE">NONE</option>
                                        <!-- Options populated by AJAX get -->
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="klas">Student</label>
                                    <select class="form-control" id="opmerking_student_name" name="opmerking_student_name">

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="klas">Periode</label>
                                    <select class="form-control" id="periode" name="periode">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button id="btn_get_form" type="submit" class="btn btn-primary btn-m-w btn-m-h">zoeken</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 full-inset">
                        <div class="sixth-bg-color brd-full">
                            <div class="box box_form">
                                <div class="box-content full-inset">
                                    <div id="table_cijfer" class="data-display"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <a type="submit" name="btn_cijfers_print" id="btn_cijfers_print" class="btn btn-default btn-m-w pull-right mrg-left" target="_blank">PRINT</a>
        </section>
    </main>
</div>

<?php include 'footer.php'; ?>
<?php include 'document_end.php'; ?>

<script>
    $("#houding_klassen_lijst").change(function() {
        var varCClass = $("#houding_klassen_lijst option:selected").val();
        $.post("ajax/getliststudentbyclass.php", {
                class: varCClass
            },
            function(data) {
                $("#opmerking_student_name").html(data);
            });
    });
    $("#btn_get_form").click(function(e) {
        e.preventDefault()

        $.ajax({
            url: "ajax/form_montessori.php",
            data: $('#form-vak').serialize(),
            type: "POST",
            dataType: "text",
            success: function(text) {
                $("#table_cijfer").html(text);
            },
            error: function(xhr, status, errorThrown) {
                alert("error");
            },
        });
    })
    $("#btn_cijfers_print").click(function() {
        var klas = $("#houding_klassen_lijst option:selected").val(),
            student = $("#opmerking_student_name option:selected").val(),
            rapport = $("#periode option:selected").val();

        window.open("print.php?name=montessori&title=RAPPORT MONTESSORI&klas=" + klas + "&student=" + student + "&rapport=" + rapport);
    });
</script>