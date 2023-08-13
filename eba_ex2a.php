<?php include 'document_start.php'; ?>

<?php include 'sub_nav.php'; ?>
<div class="push-content-220">
    <?php include 'header.php';
    $schoolid = $_SESSION["SchoolID"];
    $schooljaar = $_SESSION["SchoolJaar"];
    require_once 'classes/DBCreds.php';
    require_once 'classes/spn_setting.php';
    require_once("classes/spn_utils.php");
    $u = new spn_utils();
    $s = new spn_setting();
    $DBCreds = new DBCreds();
    $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
    $mysqli->set_charset('utf8'); ?>

    <style>
        .min {
            width: 4%;
        }

        .nr {
            width: 2%;
        }

        .paket {
            width: 8%;
        }

        input {
            width: 100%;
            font-weight: bold;
        }

        .recuadro {
            display: flex;
            justify-content: space-between;
        }

        .cuadro {
            padding: 1px 5px;
            border: 1px solid #000;
            text-align: center;
            font-weight: bold;
            background-color: lightgray;
        }

        .recuadro p {
            margin: 0;
        }

        p label {
            margin: 0;
            margin-right: 5px;
        }

        .cuadro_x {
            background-color: white;
        }

        .cuadro_h {
            background-color: #ccffff;
        }

        .cuadro_ns {
            background-color: deeppink;
        }

        .cuadro_v {
            background-color: dodgerblue;
        }
    </style>

    <main id="main" role="main">
        <section>
            <div class="container container-fs">
                <div class="row">
                    <div class="default-secondary-bg-color col-md-12 full-inset brd-bottom clearfix">
                        <h1 class="primary-color">EX. 2a-M</h1>
                        <?php include 'breadcrumb.php'; ?>
                    </div>
                    <div class="default-secondary-bg-color col-md-12 full-inset filter-bar brd-bottom clearfix">
                        <form id="form_table" class="form-inline form-data-retriever" role="form">
                            <fieldset>
                                <div class="form-group">
                                    <label for="">Docent</label>
                                    <select class="form-control" name="docent" id="docent">
                                        <option value=""></option>
                                        <?php
                                        $docents = "SELECT e.*,(SELECT CONCAT(lastname,' ',firstname) FROM app_useraccounts WHERE UserGUID = e.docent) as docente FROM eba_docentlist e WHERE e.schooljaar = '$schooljaar' AND e.schoolid = $schoolid";
                                        $result1 = mysqli_query($mysqli, $docents);
                                        while ($row1 = mysqli_fetch_assoc($result1)) { ?>
                                            <option id="<?php echo $row1["vak"]; ?>" value="<?php echo $row1["code"]; ?>"><?php echo $row1["docente"]; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="submit" id="btn_table" class="btn btn-grey btn-m-w btn-m-h">zoeken</button>
                                    <button type="button" id="btn_eba_export" class="btn btn-primary btn-m-w btn-s-h">Export</button>
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
    $("#form_table").submit(function(e) {
        e.preventDefault();
        user = $("#docent option:selected").val();
        $("#loader_spn").removeClass("hidden");
        $.ajax({
            url: "ajax/get_eba_ex2a_table.php",
            type: "POST",
            data: {
                user: user
            },
            success: function(data) {
                $("#table").html(data);
                $("#loader_spn").addClass("hidden");
            }
        });
    })

    $("#btn_eba_export").click(function() {
        user = $("#docent option:selected").val();
        name = $("#docent option:selected").text();
        vak = $("#docent option:selected").attr("id");
        window.open("dev_tests\\export_eba_ex2a.php?user=" + user + "&name=" + name + "&vak=" + vak);
    });
</script>