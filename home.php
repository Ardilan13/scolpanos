<?php include 'document_start.php'; ?>

<?php include 'sub_nav.php'; ?>

<div class="push-content-220">
    <main id="main" role="main" class="home">
        <?php include 'header.php'; ?>
        <section>
            <div class="container container-fs">
                <div class="row">
                    <div class="default-secondary-bg-color col-md-12 inset brd-bottom">
                        <h1 class="primary-color">Welkom op Scol pa Nos!</h1>
                        <?php include 'breadcrumb.php'; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 full-inset">
                        <div class="primary-bg-color brd-full">
                            <div class="box">

                                <div class="box-content full-inset sixth-bg-color">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <span class="input-group-addon fifth-bg-color default-secondary-color" id="search-addon">Search:</span>
                                                <input type="text" class="form-control" id="search" aria-describedby="search-addon">
                                                <a class="input-group-addon tertiary-bg-color" id="home_search_leerling">
                                                    <i class="fa fa-search"></i>
                                                </a>
                                                <div id="result_search_leerling"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="box-content full-inset sixth-bg-color">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <div class="btn-group">

                                                    <div class="col-md-4">
                                                        <?php if ($_SESSION['SchoolType'] == 1) : ?>
                                                            <a class="btn btn-primary" href="verzuim.php">Klassenboek</a></li>
                                                        <?php else : ?>
                                                            <a class="btn btn-primary" href="verzuim_hs.php">Klassenboek</a>
                                                            </li>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <a class="btn btn-primary" href="cijfers.php">Cijfers</a></li>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <?php if ($_SESSION['SchoolType'] == 1) { ?>
                                                            <a class="btn btn-primary" href="houding.php">Houding</a></li>
                                                        <?php } else { ?>
                                                            <a class="btn btn-primary" href="houding_hs.php">Houding</a></li>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    </br>
                                    <div class="primary-bg-color brd-full">
                                        <div class="box">
                                            <div class="box-title full-inset brd-bottom">
                                                <h2>Verjaardagslijst</i></h2>
                                            </div>
                                            <div class="box-content full-inset default-secondary-bg-color equal-height" style="height:285px; overflow-x: hidden;">
                                                <div id="tbl_birthdaylist" class="dataRetrieverOnLoad" data-ajax-href="ajax/getbirthday_tabel.php"></div>
                                                <div>
                                                    <div id="tbl_birthdaylist_result"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    </br>
                                    <div class="pull-right form-inline">
                                        <div class="btn-group">
                                            <button class="btn btn-primary" data-calendar-nav="prev">
                                                << Prev</button>
                                                    <button class="btn" data-calendar-nav="today">Today</button>
                                                    <button class="btn btn-primary" data-calendar-nav="next">Next
                                                        >></button>
                                        </div>
                                    </div>
                                    <div class="primary-bg-color brd-full">
                                        <div class="box">
                                            <div class="box-title full-inset brd-bottom">
                                                <h4></h4>
                                            </div>
                                        </div>
                                    </div>

                                    <div style="background:#ffffff;color:#000000;text-decoration:none;">
                                        <div id="calendar"></div>
                                        <script type="text/javascript" src="assets/js/calendar.js"></script>
                                    </div>

                                </div>

                                <div class="col-md-3 full-inset">
                                    <div class="primary-bg-color brd-full">
                                        <div class="box">
                                            <div class="box-title full-inset brd-bottom">
                                                <h3>Calendar</h3>
                                            </div>
                                            <div class="sixth-bg-color box content full-inset">
                                                <form class="form-horizontal align-left" role="form" name="form-add-calendar" id="form-add-calendar">
                                                    <div class="alert alert-danger hidden">
                                                        <p><i class="fa fa-warning"></i> Er zijn lege velden die
                                                            ingevuld moet worden!</p>
                                                    </div>
                                                    <div class="alert alert-info hidden">
                                                        <p><i class="fa fa-check"></i> Uw bericht is verzonden!</p>
                                                    </div>
                                                    <div class="alert alert-error hidden">
                                                        <p><i class="fa fa-warning"></i> Excuseer me, was er een fout in
                                                            het verzenden van berichten!</p>
                                                    </div>
                                                    <div class="alert alert-error-bad-charecter hidden">
                                                        <p><i class="fa fa-warning"></i> The text has characters that
                                                            are not accepted, remove these characters</p>
                                                    </div>


                                                    <fieldset>
                                                        <input type="hidden" id="id_calendar" name="id_calendar" value="0">
                                                        <input type="hidden" id="calendar_config" name="calendar_config" value="||true|">
                                                        <input type="hidden" id="schoolid" name="schoolid" value="<?php echo $_SESSION['SchoolID']; ?>">
                                                        <input type="hidden" id="schooljaar" name="schooljaar" value="<?php echo $_SESSION['SchoolJaar']; ?>">
                                                        <div id="lblDocent" class="form-group">
                                                            <label class="col-md-4 control-label" for="">Docent</label>
                                                            <div class="dataDocentOnLoad" data-ajax-href="ajax/getlistdocent.php"></div>
                                                            <div class="col-md-8">
                                                                <div id="data_docent"></div>
                                                            </div>
                                                        </div>
                                                        <div id="lblKlassen" class="form-group">
                                                            <label class="col-md-4 control-label" for="">Klas</label>
                                                            <div class="dataKlassenOnLoad" data-ajax-href="ajax/getlistklassen.php"></div>
                                                            <div class="col-md-8">
                                                                <select class="form-control" name="cijfers_klassen_lijst" id="cijfers_klassen_lijst">
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <!-- datepicker -->
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label">Datum</label>
                                                            <div class="col-md-8">
                                                                <div class="input-group date">
                                                                    <input id="calendar_date" type="text" value="" placeholder="" class="form-control calendar" name="calendar_date">
                                                                    <span class="input-group-addon"><i class="fa fa-calendar default-primary-color"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Class -->
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label" for="">Onderwerp</label>
                                                            <div class="col-md-8">
                                                                <select id="calendar_subject" name="calendar_subject" class="form-control">
                                                                    <option selected value="Homework">Huiswerk
                                                                    </option>
                                                                    <option selected value="Test">Overhoring</option>
                                                                    <option selected value="Exam">Proefwerk</option>
                                                                    <option selected value="Other">Other</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label">Informatie</label>
                                                            <div class="col-md-8">
                                                                <textarea id="calendar_observation" class="form-control" name="calendar_observation" type="text" placeholder="Enter observation here..."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label">Bestand</label>
                                                            <div class="col-md-8">
                                                                <input class="form-control" type="file" name="fileToUpload" id="fileToUpload">
                                                            </div>
                                                        </div>
                                                        <!-- Observations -->

                                                        <div class="form-group full-inset">
                                                            <button type="submit" class="btn btn-primary btn-m-w pull-right mrg-left" id="btn-add-calendar">Save</button>
                                                        </div>
                                                    </fieldset>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
                <div class="row">

                    <div class="box-content full-inset sixth-bg-color">
                        <div class="row">
                            <div class="box-content full-inset sixth-bg-color">
                                <div id="div_todo_list" class="dataRetrieverOnLoad" data-ajax-href="ajax/gettodo_list.php"></div>
                                <div id="div_todo_list_detail"></div>
                            </div>

                        </div>
                        <br>
                    </div>
                </div>

            </div>
</div>
</div>
</section>
</main>
<?php include 'footer.php'; ?>
</div>

<?php include 'document_end.php'; ?>



<script type="text/javascript" src="assets/js/calendar.js"></script>
<script type="text/javascript" src="assets/js/app_calendar.js"></script>
<!-- INICIO CODE CaribeDevelopers Delete Calendar -->
<script type="text/javascript">
    var SchoolType = <?php echo $_SESSION["SchoolType"]; ?>;

    if (SchoolType == 1) {
        uit = 'Naar huis';
    } else {
        uit = 'Spijbelen';
    }

    function delete_calendar(id) {
        var result = confirm("Want to delete event calendar?");
        if (result) {
            var request = new XMLHttpRequest();
            request.open('GET', 'ajax/delete_calendar.php?id_calendar=' + id, false);
            request.send();
            if (request.responseText === '1') {
                location.reload();
            } else if (request.responseText === '2') {
                alert("You don't have permissions to do this");
            }
        }
    }
    $(function() {
        // CaribeDevelopers: Function to delete a Calendar
        $('#accion_calendar').click(function(event) {

            //alert($(this).attr('name'));

            var r = confirm("Delete Event?");
            if (r == true) {
                // $.post("ajax/delete_event.php",
                //     {
                //         funcion     : 'delete_event',
                //         xid_event   : $(this).attr('name')
                //     },
                //     function(data){
                //         if (data){
                //             alert("Event Delete!!!");
                //             window.location =  window.location = "leerlingdetail.php?id=<?php echo $_GET["id"] ?>";
                //         }
                //     });
            }
        });
    });


    $('#search').keypress(function(e) {

        var key = e.which;
        if (key == 13) // the enter key code
        {
            search_leerling_by_fullname()
        }
    });

    $('#home_search_leerling').click(function() {
        search_leerling_by_fullname()
    });

    function search_leerling_by_fullname() {
        var name_leerling = $('#search').val();
        var flag_result = 1;
        $.ajax({
            url: "ajax/get_leerling_by_name.php?name=" + name_leerling,
            type: "POST",
            dataType: "text",
            success: function(data) {
                if (data != 0) {
                    $("#result_search_leerling").html(data);
                } else {
                    flag_result = 0;
                    alert('Please write a valid student name');
                }

            },
            error: function(xhr, status, errorThrown) {
                console.log("error getting leerling name, plase contact the Developers");
            },
            complete: function(xhr, status) {
                if (flag_result == 1) {
                    var shearch_result_link = $("#redirect_leerling").val();
                    if (shearch_result_link.indexOf("leerling.php") != -1) {
                        //alert('im go to leerlingdetail.php')
                    } else {
                        window.location.replace(shearch_result_link)
                        //window.open(shearch_result_link, '_blank');
                        //alert('im go to leerling.php')
                    }
                }
            }
        });
    }
</script>