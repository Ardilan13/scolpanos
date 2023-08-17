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
                            <h1 class="primary-color">Groepen</h1>
                            <?php include 'breadcrumb.php'; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 full-inset">
                            <div class="sixth-bg-color brd-full">
                                <div class="box-content full-inset default-secondary-bg-color equal-height">
                                    <div class="row">
                                        <div class="col-md-12 full-inset ">
                                            <ul class="nav nav-tabs">
                                                <!-- <li class="active"><a data-toggle="tab" href="#home">Home</a></li> -->
                                                <li class="active"><a data-toggle="tab" href="#menu1">Groepen</a></li>
                                                <li><a data-toggle="tab" href="#menu2">Students to Groups</a></li>
                                                <!-- <li><a data-toggle="tab" href="#menu3">groups to Groups</a></li> -->
                                            </ul>
                                            <div class="tab-content ">
                                                <div id="menu1" class="tab-pane fade in active">
                                                    <div class="row">
                                                        <div class="col-md-12 full-inset">
                                                            <div class="sixth-bg-color brd-full">
                                                                <div class="box">
                                                                    <div class="box-content full-inset clearfix">
                                                                        <div class="row">
                                                                            <div class="col-md-8 full-inset">
                                                                                <div class="primary-bg-color brd-full">
                                                                                    <div class="box">
                                                                                        <div class="box-title full-inset brd-bottom">
                                                                                            <div class="row">
                                                                                                <h2 class="col-md-8">Groups</h2>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="box-content full-inset sixth-bg-color">
                                                                                            <div id="tbl_group_hs" class="dataRetrieverOnLoad" data-ajax-href="ajax/get_group_hs_tabel.php"></div>
                                                                                            <div class="table-responsive data-table" data-table-type="on" data-table-search="true" data-table-pagination="true">
                                                                                                <div id="tbl_list_groups_hs_" name="tbl_list_groups_hs_"></div>
                                                                                            </div>
                                                                                            <input type="hidden" name="selected_id_group_row" id="selected_id_group_row" value="" />
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <hr>
                                                                            </div>
                                                                            <div class="col-md-4 full-inset">
                                                                                <div class="primary-bg-color brd-full">
                                                                                    <div class="box">
                                                                                        <div class="box-title full-inset brd-bottom">
                                                                                            <h3>New Group</h3>
                                                                                        </div>
                                                                                        <div class="sixth-bg-color box content full-inset">
                                                                                            <form class="form-horizontal align-left" role="form" name="frm_group_hs" id="frm_group_hs">
                                                                                                <div class="alert alert-danger hidden">
                                                                                                    <p><i class="fa fa-warning"></i> Er zijn lege velden die ingevuld moet worden!</p>
                                                                                                </div>
                                                                                                <div class="alert alert-error hidden" id='error_form_group'>
                                                                                                    <p><i class="fa fa-warning"></i> Please check the group form, all fields must be filled</p>
                                                                                                </div>
                                                                                                <div class="alert alert-error hidden" id='error_removing_group'>
                                                                                                    <p><i class="fa fa-warning"></i> Error removing group</p>
                                                                                                </div>
                                                                                                <div class="alert alert-error hidden" id='error_with_vak_and_klas'>
                                                                                                    <p><i class="fa fa-warning"></i> This grade dont have a klas like Suffix, please select another suffix</p>
                                                                                                </div>
                                                                                                <div class="alert alert-error hidden" id='error_group_exists'>
                                                                                                    <p><i class="fa fa-warning"></i> The group you are trying to create already exists and is enabled</p>
                                                                                                </div>
                                                                                                <div class="alert alert-info hidden">
                                                                                                    <p><i class="fa fa-check"></i> The new group has been register!</p>
                                                                                                </div>
                                                                                                <div class="alert alert-info hidden" id="updated_suscessfully">
                                                                                                    <p><i class="fa fa-check"></i> The group has been Updated</p>
                                                                                                </div>
                                                                                                <div class="alert alert-info hidden" id="created_suscessfully">
                                                                                                    <p><i class="fa fa-check"></i> The group Has Been Created</p>
                                                                                                </div>
                                                                                                <div class="alert alert-info hidden" id="deleted_suscessfully">
                                                                                                    <p><i class="fa fa-check"></i> The group Has Been Deleted</p>
                                                                                                </div>
                                                                                                <fieldset>
                                                                                                    <div class="form-group hidden" hidden>
                                                                                                        <label class="col-md-4 control-label">Klas</label>
                                                                                                        <div class="col-md-8">
                                                                                                            <select id="group_klas" name="group_klas" class="form-control group_klas" required>
                                                                                                                <option value="">Select a klas</option>
                                                                                                                <option value="4A" selected>4A</option>
                                                                                                                <option value="4B">4B</option>
                                                                                                                <option value="4C">4C</option>
                                                                                                                <option value="4D">4D</option>
                                                                                                            </select>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="form-group">
                                                                                                        <label class="col-md-4 control-label">Vak</label>
                                                                                                        <div class="col-md-8">
                                                                                                            <select id="group_vak" name="group_vak" class="form-control group_vak" required>
                                                                                                                <option value="">Select a vak</option>
                                                                                                            </select>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="form-group">
                                                                                                        <label class="col-md-4 control-label">Group Name</label>
                                                                                                        <div class="col-md-4 col-sm-4" style="padding-right: 0px">
                                                                                                            <input id="group_preffix_name" class="form-control" style="padding-right: 0px" name="group_preffix_name" type="text" value="" disabled />
                                                                                                        </div>
                                                                                                        <div class="col-md-4 col-sm-4" style="padding-left: 1px">
                                                                                                            <!-- <input id="group_suffix_name" style="padding-left: 1px" class="form-control" name="group_suffix_name" placeholder="Suffix group" type="text" value="" required/> -->
                                                                                                            <select id="group_suffix_name" name="group_suffix_name" style="padding-left: 1px" class="form-control" required>
                                                                                                                <option value="">Select suffix</option>
                                                                                                                <option value="1">1</option>
                                                                                                                <option value="2">2</option>
                                                                                                                <option value="3">3</option>
                                                                                                                <option value="4">4</option>
                                                                                                                <option value="5">5</option>
                                                                                                                <option value="6">6</option>
                                                                                                                <option value="7">7</option>
                                                                                                                <option value="8">8</option>
                                                                                                                <option value="9">9</option>
                                                                                                                <option value="10">10</option>
                                                                                                            </select>
                                                                                                        </div>
                                                                                                        <input id="group_name" class="hidden" name="group_name" value="" />
                                                                                                    </div>
                                                                                                    <div class="form-group ">

                                                                                                        <div class="col-md-12 pull-right">
                                                                                                            <button type="submit" class="btn btn-primary pull-right mrg-left" id="btn_create_group_hs">CREATE</button>
                                                                                                            <button type="submit" class="btn btn-primary pull-right mrg-left hidden" id="btn_update_group_hs">UPDATE</button>
                                                                                                            <button type="button" class="btn btn-white pull-left mrg-left" id="btn_clear_group_hs">CLEAR</button>
                                                                                                            <button type="button" class="btn btn-danger pull-right mrg-left hidden" id="btn_delete_group_hs">DELETE</button>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </fieldset>
                                                                                                <input hidden id="vakid" name="vakid">
                                                                                            </form>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="menu2" class="tab-pane fade ">
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
                                                                                            <div class="data-display" id='table_group'>

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
                                    </div>
                                    <div id="loader_spn" class="hidden">
                                        <div class="loader_spn"></div>
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
    } ?> <!-- PLEASE CAREFULLY WIH THIS "}" THAT CLOSE SECURITY  ELSE IF -->
<script>
    $(document).ready(function() {
        console.log("ready!");

        $.getJSON("ajax/getvakken_json.php", {
            klas: '4A'
        }, function(result) {
            var vak = $(".group_vak");
            $.each(result, function() {
                vak.append($("<option />").val(this.id).text(this.vak));
            });
        });
    });

    /*     $("#group_klas").on("change", function() {
            $(".group_vak").empty();
            var $klas = $(this).val();

            $.getJSON("ajax/getvakken_json.php", {
                klas: $klas
            }, function(result) {
                var vak = $(".group_vak");
                vak.append($("<option/>"));
                $.each(result, function() {
                    vak.append($("<option />").val(this.id).text(this.vak));
                });
            });
        }); */

    $("#group_vak").change(function() {
        $('#group_preffix_name').val($("#group_vak option:selected").text() + '-');
    });

    $('#frm_group_hs').submit(function(e) {
        e.preventDefault();
        $('#group_name').val($('#group_preffix_name').val() + $('#group_suffix_name').val());

        if (check_group_exists()) {
            $('.alert').addClass('hidden');
            $('#error_group_exists').removeClass('hidden');
        } else {
            $.ajax({
                url: "ajax/add_group.php",
                data: $('#frm_group_hs').serialize(),
                type: "POST",
                dataType: "text",
                success: function(text) {
                    if (text == 1) {
                        $('.alert').addClass('hidden');
                        $('#created_suscessfully').removeClass('hidden');
                    } else if (text == 2) {
                        $('.alert').addClass('hidden');
                        $('#updated_suscessfully').removeClass('hidden');
                    } else {
                        $('.alert').addClass('hidden');
                        $('#frm_group_hs').find('.alert-error').removeClass('hidden');
                    }
                    console.log(text);
                },
                error: function(xhr, status, errorThrown) {
                    console.log("error");
                },
                complete: function(xhr, status) {
                    setTimeout(function() {
                        $('.alert').addClass('hidden');
                        $('#created_suscessfully').fadeOut(1500);
                        location.reload();
                    }, 2000);
                }
            });
        }
    })

    $('#btn_clear_group_hs').click(function() {
        location.reload();
    });

    $("#btn_delete_group_hs").click(function(e) {
        vakid = $('#vakid').val();
        var r = confirm("Delete this Group?");
        if (r) {
            var vak = {
                vakid: vakid,
                type: 'delete'
            };
            $.ajax({
                url: "ajax/add_group.php",
                data: vak,
                type: "POST",
                dataType: "text",
                success: function(text) {
                    if (text != 3) {
                        $('#error_removing_group').removeClass('hidden');
                    } else if (text == 3) {
                        $('#frm_group_hs').find('#deleted_suscessfully').removeClass('hidden');
                    }
                },
                error: function(xhr, status, errorThrown) {
                    console.log("error");
                },
                complete: function(xhr, status) {
                    //
                    setTimeout(function() {
                        $(".alert-info").fadeOut(1500);
                        $(".alert-error").fadeOut(1500);
                        $(".alert-warning").fadeOut(1500);
                        $('#deleted_suscessfully').fadeOut(1500);
                        location.reload();
                    }, 2000);
                }
            });
        } else {
            $('#btn_clear_group_hs').text("CLEAR");
        }
    });

    function check_group_exists() {

        $.ajax({
            url: "ajax/get_check_vak.php?group_name=" + $('#group_name').val(),
            type: "GET",
            dataType: "HTML",
            async: false,
            success: function(data) {
                if (parseInt(data) > 0) {
                    group_exists = true;
                } else {
                    group_exists = false;
                }
            }
        });
        return group_exists;
    }


    // STUDENTS TO GROUPS SECCION

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


    function updateStudentGroup(studentid, vakid) {

        $.post("ajax/update_student_group.php", {
            studentid: studentid,
            vakid: vakid
        }, function(data) {
            /* do something if needed */
            $("#loader_spn").toggleClass('hidden');
        }).done(function(data) {
            /* it's done */
            if (data == 1) {
                $("#loader_spn").addClass('hidden');

            } else {
                $("#loader_spn").addClass('hidden');
                // RE-TRY FUNCTION
            };

        }).fail(function() {
            alert('Error, please contact developers.');
            $("#loader_spn").toggleClass('hidden');
        });


    }
</script>