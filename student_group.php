<?php include 'document_start.php';
require_once "classes/DBCreds.php";
$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');
?>

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
                            <h1 class="primary-color">Student To Groep</h1>
                            <?php include 'breadcrumb.php'; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 full-inset">
                            <div>
                                <div id="rapport_verzuim_table2" class="col-md-10"></div>
                                <div class="row">
                                    <div class="col-md-12 full-inset">
                                        <div class="sixth-bg-color brd-full">
                                            <div class="box">
                                                <div class="box-content full-inset clearfix">
                                                    <div class="default-secondary-bg-color col-md-12 inset brd-bottom">
                                                        <form id="form-vak" class="form-inline form-data-retriever" name="filter-vak" role="form">
                                                            <fieldset>
                                                                <div class="form-group">
                                                                    <label for="group_klas">Klas</label>
                                                                    <select class="form-control group_klas" name="group_klas" id="group_klas1">
                                                                        <option value=""></option>
                                                                        <option value="4A">4A</option>
                                                                        <option value="4B">4B</option>
                                                                        <option value="4C">4C</option>
                                                                        <option value="4D">4D</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="group">Group</label>
                                                                    <select class="form-control" name="group" id="group">
                                                                        <option value="all">All Groups</option>
                                                                        <?php
                                                                        $schoolid = $_SESSION['SchoolID'];
                                                                        $get_groups = "SELECT g.id,g.name FROM groups g WHERE g.schoolid = $schoolid ORDER BY g.name;";
                                                                        $result = mysqli_query($mysqli, $get_groups);
                                                                        while ($row = mysqli_fetch_assoc($result)) { ?>
                                                                            <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
                                                                        <?php }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <button id="btn_submit_groups" name="btn_submit_groups" type="submit" class="btn btn-primary btn-m-w btn-m-h">zoeken</button>
                                                                </div>
                                                            </fieldset>
                                                        </form>
                                                    </div>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-md-12 full-inset">
                                                            <div class="sixth-bg-color brd-full">
                                                                <div class="box">
                                                                    <div class="box-content full-inset">
                                                                        <div class="data-display table-responsive" id='table_group'>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <br />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="loader_spn" class="hidden">
                        <div class="loader_spn"></div>
                    </div>
                </div>
            </section>
        </main>
        <?php include 'footer.php'; ?>
</div>

<?php include 'document_end.php';
    } ?> <!-- PLEASE CAREFULLY WIH THIS "}" THAT CLOSE SECURITY  ELSE IF -->
<script>
    $('#btn_submit_groups').click(function(e) {
        e.preventDefault();
        $("#loader_spn").removeClass("hidden");
        $.ajax({
            url: "ajax/get_students_groups.php?klas=" + $("#group_klas1").val() + '&group=' + $('#group').val(),
            type: "GET",
            dataType: "HTML",
            async: true,
            success: function(data) {
                $("#table_group").empty();
                $("#table_group").append(data);
            },
            complete: function() {
                $("#loader_spn").addClass("hidden");
            },
        });
    });
</script>