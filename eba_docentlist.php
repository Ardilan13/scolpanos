<?php include 'document_start.php'; ?>

<?php include 'sub_nav.php'; ?>
<style>

</style>
<div class="push-content-220">
    <?php include 'header.php'; ?>

    <main id="main" role="main">
        <section>
            <div class="container container-fs">
                <input type="text" hidden value="<?php echo $_SESSION["SchoolID"]; ?>" id="schoolid">
                <input type="text" hidden value="<?php echo $_SESSION["SchoolJaar"]; ?>" id="schooljaar">

                <div class="row">
                    <div class="default-secondary-bg-color col-md-12 full-inset brd-bottom clearfix">
                        <h1 class="primary-color">Docenten lijst</h1>
                        <?php include 'breadcrumb.php'; ?>
                    </div>
                    <div class="row">
                        <div class="col-md-8 full-inset">
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

                                            $x = 1;
                                            $schoolid = $_SESSION["SchoolID"];
                                            $schooljaar = $_SESSION["SchoolJaar"];
                                            $s->getsetting_info($schoolid, false);

                                            $get_personalia = "SELECT CONCAT(a.Lastname,' ',a.Firstname) as docent,e.docent as guid,e.id,e.code,e.vak FROM eba_docentlist e INNER JOIN app_useraccounts a ON e.docent = a.UserGUID WHERE e.schoolid = " . $_SESSION["SchoolID"] . " AND e.schooljaar = '" . $_SESSION["SchoolJaar"] . "'";
                                            $result = mysqli_query($mysqli, $get_personalia);
                                            if ($result->num_rows > 0) { ?>
                                                <table class="table table-bordered table-colored table-houding">
                                                    <thead>
                                                        <tr>
                                                            <th>Nr</th>
                                                            <th>Naam Doc</th>
                                                            <th>Code</th>
                                                            <th>Vak</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                                            <tr class="docent" id="<?php echo $row['id']; ?>">
                                                                <td><?php echo $x; ?></td>
                                                                <td class="docent_name" id="<?php echo $row['guid']; ?>"><?php echo $row["docent"]; ?></td>
                                                                <td class="code"><?php echo $row["code"]; ?></td>
                                                                <td class="vak"><?php echo $row["vak"]; ?></td>
                                                            </tr>
                                                        <?php $x++;
                                                        } ?>
                                                    </tbody>
                                                </table>
                                            <?php } else {
                                                echo "No results to show.";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 full-inset">
                            <div class="primary-bg-color brd-full">
                                <div class="box">
                                    <div class="box-title full-inset brd-bottom">
                                        <h3>New Docent</h3>
                                    </div>
                                    <div class="sixth-bg-color box content full-inset">
                                        <form class="form-horizontal align-left" name="frm_docentlist" id="frm_docentlist">
                                            <fieldset>
                                                <input type="text" hidden id="id" name="id">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Docent</label>
                                                    <div class="col-md-8">
                                                        <select id="docent" name="docent" class="form-control" required> </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Code</label>
                                                    <div class="col-md-8">
                                                        <input id="code" class="form-control" type="text" maxlength="3" name="code" required />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Vak</label>
                                                    <div class="col-md-8">
                                                        <select id="vak" name="vak" class="form-control" required>
                                                            <option value=""></option>
                                                            <option value="ne">ne</option>
                                                            <option value="en">en</option>
                                                            <option value="sp">sp</option>
                                                            <option value="pa">pa</option>
                                                            <option value="wi">wi</option>
                                                            <option value="sk1">na sk1</option>
                                                            <option value="sk2">na sk2</option>
                                                            <option value="bi">bi</option>
                                                            <option value="ecmo">ecmo</option>
                                                            <option value="ak">ak</option>
                                                            <option value="gs">gs</option>
                                                            <option value="re">re</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group full-inset">
                                                    <button type="submit" class="btn btn-primary btn-m-w pull-right mrg-left" id="btn_save">SAVE</button>
                                                    <button class="btn btn-primary btn-s-w pull-right mrg-left hidden" id="btn_update">UPDATE</button>
                                                    <button type="reset" class="btn btn-danger btn-s-w pull-right mrg-left hidden" id="btn_delete">DELETE</button>
                                                    <button class="btn btn-secondary btn-s-w pull-left mrg-left hidden" id="btn_cancel">CANCEL</button>

                                                </div>
                                            </fieldset>
                                        </form>
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
    $(document).ready(function() {
        $.ajax({
            url: "ajax/getdocent.php",
            type: "POST",
            data: {},
            success: function(data) {
                var docent = JSON.parse(data);
                var docent_options = "<option value=''> </option>";
                for (var i = 0; i < docent.length; i++) {
                    docent_options += "<option value='" + docent[i].user + "'>" + docent[i].name + "</option>";
                }
                $("#docent").html(docent_options);
            }
        });
    })

    $("#btn_update").click(function(e) {
        e.preventDefault()
        $("#loader_spn").removeClass('hidden');
        $.ajax({
            url: "ajax/insert_docent.php",
            type: "POST",
            data: $("#frm_docentlist").serialize(),
            async: true,
            success: function(data) {
                console.log(data);
                $("#loader_spn").removeClass('hidden');
                location.reload();
            },
            error: function(error) {
                console.log(error);
                $("#loader_spn").removeClass('hidden');
                alert('error')
            }
        });
    })

    $("#btn_delete").click(function(e) {
        e.preventDefault()
        $("#code").val("delete")
        $("#loader_spn").removeClass('hidden');
        $.ajax({
            url: "ajax/insert_docent.php",
            type: "POST",
            data: $("#frm_docentlist").serialize(),
            async: true,
            success: function(data) {
                console.log(data);
                $("#loader_spn").removeClass('hidden');
                alert('Docent Deleted.')
                location.reload();
            },
            error: function(error) {
                console.log(error);
                $("#loader_spn").removeClass('hidden');
                alert('error')
            }
        });
    })

    $(".docent").click(function() {
        var id = $(this).attr('id');
        var docent = $(this).find('.docent_name').attr("id");
        var code = $(this).find('.code').text();
        var vak = $(this).find('.vak').text();
        $("#id").val(id);
        $("#docent").val(docent);
        $("#code").val(code);
        $("#vak").val(vak);

        $("#btn_update").removeClass('hidden');
        $("#btn_delete").removeClass('hidden');
        $("#btn_cancel").removeClass('hidden');
        $("#btn_save").addClass('hidden');
        $("h3").text('Update Docent');
    })

    $("#btn_cancel").click(function(e) {
        e.preventDefault()
        $("#id").val('');
        $("#docent").val('');
        $("#code").val('');
        $("#vak").val('');

        $("#btn_update").addClass('hidden');
        $("#btn_delete").addClass('hidden');
        $("#btn_cancel").addClass('hidden');
        $("#btn_save").removeClass('hidden');
        $("h3").text('New Docent');
    })

    $("#frm_docentlist").submit(function(e) {
        e.preventDefault()
        $("#loader_spn").removeClass('hidden');
        $.ajax({
            url: "ajax/insert_docent.php",
            type: "POST",
            data: $("#frm_docentlist").serialize(),
            async: true,
            success: function(data) {
                console.log(data);
                $("#loader_spn").removeClass('hidden');
                location.reload();
            },
            error: function(error) {
                console.log(error);
                $("#loader_spn").removeClass('hidden');
                alert('error')
            }
        });
    })

    $("#btn_eba_export").click(function() {
        var schoolid = $("#schoolid").val();
        var schooljaar = $("#schooljaar").val();
        window.open("dev_tests\\export_eba_docentlist.php");
    });
</script>