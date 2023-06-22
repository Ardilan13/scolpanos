<?php include 'document_start.php'; ?>



<?php include 'sub_nav.php'; ?>





<div class="push-content-220">

    <?php include 'header.php'; ?>
    <?php
    session_start();
    require_once "classes/DBCreds.php";
    require_once "classes/spn_utils.php";
    $DBCreds = new DBCreds();
    $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
    $mysqli->set_charset('utf8');

    $utils = new spn_utils();
    $schoolid = $_SESSION["SchoolID"];
    $i_klas = $_POST["klas1"];
    $i_start = $_POST["start_date"];
    $i_end = $_POST["end_date"];
    $i_end = $utils->convertfrommysqldate_new($i_end);
    $i_start = $utils->convertfrommysqldate_new($i_start);
    $schoolid = $_SESSION["SchoolID"];
    $good = ['A', 'L', 'M', 'X', 'T', 'U', 'S'];
    ?>
    <main id="main" role="main">

        <section>



            <div class="container container-fs">



                <div class="row">

                    <div class="default-secondary-bg-color col-md-12 full-inset brd-bottom clearfix">

                        <h1 class="primary-color">Klassenboek Rapport</h1>

                        <?php include 'breadcrumb.php'; ?>

                    </div>

                    <div class="default-secondary-bg-color col-md-12 full-inset filter-bar brd-bottom clearfix">
                        <form class="form-inline" id="form_excel">
                            <div class="form-group">
                                <label for="klas">Klas</label>
                                <select class="form-control" name="klas1" id="klas" value="<?php echo $i_klas; ?>">
                                    <?php
                                    $sql = "select distinct v.Klas from le_vakken v where v.SchoolID = $schoolid order by v.Klas asc;";
                                    $result = mysqli_query($mysqli, $sql);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $klas = $row["Klas"];
                                        if ($klas == $i_klas) {
                                            echo "<option value='$klas' selected>$klas</option>";
                                        } else {
                                            echo "<option value='$klas'>$klas</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Start Datum</label>
                                <div class="input-group date col-md-1">
                                    <input type="text" id="start_date" name="start_date" value="<?php echo $_POST['start_date'] ?>" calendar="full" class="form-control input-sm calendar" required autocomplete="off">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                </div>
                                <label>End Datum</label>
                                <div class="input-group date col-md-1">
                                    <input type="text" id="end_date" name="end_date" value="<?php echo $_POST['end_date'] ?>" calendar="full" class="form-control input-sm calendar" required autocomplete="off">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                </div>
                                <button type="submit" class="btn btn-primary btn-m-w btn-m-h" id="btn_download">Zoeken</button>
                            </div>
                        </form>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div id="loader_spn" class="hidden">
                        <div class="loader_spn"></div>
                    </div>

                    <div class="col-md-12 full-inset">

                        <div class="sixth-bg-color brd-full">

                            <div class="box">

                                <div class="box-content full-inset clearfix">

                                    <span><b>L</b> = Laat |</span>
                                    <span><b> A</b> = Afwezig |</span>
                                    <span><b> X</b> = Afspraak extern en komt terug op school |</span>
                                    <span><b> S</b> = Spijbelen</span><br></br>
                                    <span><b>M</b> = Met toestemming naar huis |</span>
                                    <span><b> U</b> = Uitgestuurd |</span>
                                    <span><b> T</b> = Time-out(schorsing)</span>

                                    <div class="data-display">
                                        <table class="table table-bordered table-colored table-houding">
                                            <caption>Verzuim rapport</caption>
                                            <thead>
                                                <th>Name</th>
                                                <th>Lastname</th>
                                                <th>A</th>
                                                <th>L</th>
                                                <th>X</th>
                                                <th>S</th>
                                                <th>M</th>
                                                <th>U</th>
                                                <th>T</th>
                                            </thead>
                                            <tbody>


                                                <?php $students = "SELECT id,firstname,lastname FROM students WHERE class = '$i_klas' AND schoolid = $schoolid ORDER BY lastname ASC";
                                                $resultado1 = mysqli_query($mysqli, $students);
                                                while ($row1 = mysqli_fetch_assoc($resultado1)) {
                                                    $id = $row1["id"];
                                                    foreach ($good as $key => $value) {
                                                        $$value = 0;
                                                    }

                                                    $verzuim_hs = "SELECT p1,p2,p3,p4,p5,p6,p7,p8,p9 FROM le_verzuim_hs as v INNER JOIN students as s WHERE v.studentid = s.id AND v.klas = '$i_klas' AND v.datum >= '$i_start' AND v.datum <= '$i_end' AND s.id = $id ORDER BY datum ASC";
                                                ?>
                                                    <?php $resultado = mysqli_query($mysqli, $verzuim_hs);
                                                    while ($row = mysqli_fetch_assoc($resultado)) {
                                                        foreach ($good as $key => $value) {
                                                            if ($row["p1"] == $value) {
                                                                $$value++;
                                                            }
                                                            if ($row["p2"] == $value) {
                                                                $$value++;
                                                            }
                                                            if ($row["p3"] == $value) {
                                                                $$value++;
                                                            }
                                                            if ($row["p4"] == $value) {
                                                                $$value++;
                                                            }
                                                            if ($row["p5"] == $value) {
                                                                $$value++;
                                                            }
                                                            if ($row["p6"] == $value) {
                                                                $$value++;
                                                            }
                                                            if ($row["p7"] == $value) {
                                                                $$value++;
                                                            }
                                                            if ($row["p8"] == $value) {
                                                                $$value++;
                                                            }
                                                            if ($row["p9"] == $value) {
                                                                $$value++;
                                                            }
                                                        }
                                                    ?>
                                                    <?php
                                                    } ?>
                                                    <tr>
                                                        <td><?php echo $row1["firstname"] ?></td>
                                                        <td><?php echo $row1["lastname"] ?></td>
                                                        <td><?php echo $A ?></td>
                                                        <td><?php echo $L ?></td>
                                                        <td><?php echo $X ?></td>
                                                        <td><?php echo $S ?></td>
                                                        <td><?php echo $M ?></td>
                                                        <td><?php echo $U ?></td>
                                                        <td><?php echo $T ?></td>

                                                    </tr>
                                                <?php mysqli_free_result($resultado);
                                                }
                                                ?>
                                            </tbody>
                                        </table>

                                    </div>

                                </div>



                            </div>

                        </div>
                        <a style="margin-top: 10px" type="submit" name="btn_verzuim_print" id="btn_verzuim_print" class="btn btn-default btn-m-w pull-right mrg-left" target="_blank">PRINT</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>

<?php include 'document_end.php'; ?>

<script type="text/javascript" src="assets/js/calendar.js"></script>
<!-- <script type="text/javascript" src="assets/js/app_calendarVerzuim.js"></script>-->
<script type="text/javascript" src="assets/js/app_calendar.js"></script>
<script type="text/javascript">
    $("#form_excel").submit(function(e) {
        e.preventDefault()
        $("#loader_spn").removeClass('hidden');
        $.ajax({
            url: 'ajax/getverzuim_tabel_hs-excel2.php',
            type: 'POST',
            async: true,
            data: $(this).serialize(),

            success: function(response) {
                console.log(response);
                $('.data-display').html(response);
                $("#loader_spn").addClass('hidden');
            },
            error: function(error) {
                console.log(error);
                $("#loader_spn").removeClass('hidden');
                alert('error')
            }
        })
    });

    $("#btn_verzuim_print").click(function() {
        var start = $("#start_date").val(),
            end = $("#end_date").val(),
            klas = $("#klas option:selected").val();
        window.open("print.php?name=table_verzamelstaten&title=Klassenboek List&klas1=" + klas + "&start_date=" + start + "&end_date=" + end);
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    });

    /*  $(document).ready(function() {
         setTimeout(function() {
             valor = $("#klas").val();
             $("#klas option[value=" + valor + "]").attr("selected", true).delay(3000);
         }, 5000);
     }); */
</script>