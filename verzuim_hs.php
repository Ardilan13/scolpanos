<?php include 'document_start.php'; ?>



<?php include 'sub_nav.php'; ?>





<div class="push-content-220">


    <?php include 'header.php'; ?>

    <?php $UserRights = $_SESSION['UserRights'];

    if ($UserRights == "ONDERSTEUNING" || $UserRights == "TEACHER" || $_SESSION['SchoolType'] != 2) {

        include 'redirect.php';
    } else { ?>

        <style>
            .select_vaks {
                font-size: 12px !important;
                font-weight: 500 !important;
            }

            .th_vaks {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            .scroll {
                overflow: auto;
            }

            .modal {
                position: fixed;
                width: 100%;
                height: 100vh;
                background: rgba(0, 0, 0, 0.81);
                display: block;
                display: none;

            }

            .bodyModal {
                width: 100%;
                height: 100%;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .box-title {
                background-color: skyblue;
                color: #ffffff;
                padding: .2rem .5rem;
                font-weight: 200;
            }
        </style>

        <main id="main" role="main">

            <section>



                <div class="container container-fs">



                    <div class="row">

                        <div class="default-secondary-bg-color col-md-12 full-inset brd-bottom clearfix">

                            <h1 class="primary-color">Klassenboek</h1>

                            <?php include 'breadcrumb.php'; ?>

                        </div>

                        <div class="default-secondary-bg-color col-md-12 full-inset filter-bar brd-bottom clearfix">

                            <form id="form-laat-absent" class="form-inline form-data-retriever" name="filter-vak" role="form">

                                <div class="form-group">

                                    <label for="datum">Datum</label>

                                    <div class="input-group date">

                                        <input type="text" id="datum" name="datum" calendar="full" class="form-control input-sm calendar" autocomplete="off">

                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>

                                    </div>

                                </div>

                                <div class="form-group">
                                    <label for="klas">Klas</label>
                                    <select class="form-control" name="klas" id="verzuim_klassen_lijst">
                                    </select>
                                </div>

                                <div class="form-group">
                                    <button data-display="data-display" id="btnzoeken" data-ajax-href="ajax/getverzuim_tabel_hs.php" type="submit" class="btn btn-primary btn-m-w btn-m-h">zoeken</button>
                                </div>

                            </form>
                            <!-- <div class="form-group">
                                     &nbsp&nbspDatum:<label id="lbl_datum_verzuim"></label>&nbsp/&nbspKlas:<label
                                        id="lbl_klas_verzuim">
                                </div> -->
                            <form class="form-inline form-data-retriever" style="text-align: right;" id="form_excel">
                                <input hidden id="klas-excel" type="text" name="klas1">
                                <div class="form-group">
                                    <label>Start Datum</label>
                                    <div class="input-group date col-md-1">
                                        <input type="text" id="start_date" name="start_date" calendar="full" class="form-control input-sm calendar" required autocomplete="off">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                    <label>End Datum</label>
                                    <div class="input-group date col-md-1">
                                        <input type="text" id="end_date" name="end_date" calendar="full" class="form-control input-sm calendar" required autocomplete="off">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-m-w btn-m-h" id="btn_download" data-display="table_event_studentid" data-ajax-href="ajax/getverzuim_tabel_hs-excel2.php">Export</button>
                                </div>
                            </form>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">

                        <div id="loader_spn" class="hidden">
                            <div class="loader_spn"></div>
                        </div>

                        <div class="col-md-8 full-inset">

                            <div class="sixth-bg-color brd-full">

                                <div class="box">

                                    <div class="box-content full-inset clearfix">

                                        <div class="data-display"></div>

                                        <?php include 'modal_laat_absent.php'; ?>

                                    </div>



                                </div>

                            </div>

                            <div style="border: 1px solid lightgray; margin-top: 2%; padding: 2%;" class="table_event_studentid" id="table_event_studentid">


                            </div>

                        </div>




                        <div class="col-md-4 full-inset">
                            <div class="row">
                                <div class="col-md-12">
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
                                        <script type="text/javascript" src="assets/js/app_calendar.js"></script>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 full-inset">
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
                                                        <p><i class="fa fa-warning"></i> Excuseer me, was er een
                                                            fout in het verzenden van berichten!</p>
                                                    </div>
                                                    <div class="alert alert-error-bad-charecter hidden">
                                                        <p><i class="fa fa-warning"></i> The text has characters
                                                            that are not accepted, remove these characters</p>
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

                                                        <div class="form-group" id='div_vak'>
                                                            <label class="col-md-4 control-label" for="">Vak</label>
                                                            <div class="col-md-8">

                                                                <select class="form-control" name="verzuim_vakken_lijst" id="verzuim_vakken_lijst">
                                                                </select>

                                                                <input class='hidden' id='verzuim_vakken_lijst_disabled' name='verzuim_vakken_lijst_disabled' value="">
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
                                    <a style="margin-top: 10px" type="submit" name="btn_verzuim_print" id="btn_verzuim_print" class="btn btn-default btn-m-w pull-right mrg-left" target="_blank">PRINT</a>
                                </div>
                            </div>
                        </div>



                    </div>


            </section>


        </main>

        <div class="modal">
            <div class="bodyModal">
                <div class="box">
                    <div class="box-title full-inset brd-bottom">
                        <h3>New Event</h3>
                    </div>
                    <div class="sixth-bg-color box content full-inset">
                        <form class="form-horizontal align-left" role="form" name="form-addevent" id="form-addevent">
                            <div role="tabpanelevent" class="tab-pane active" id="tab1">
                                <div class="alert alert-danger1 hidden">
                                    <p><i class="fa fa-warning"></i> Er zijn lege velden die ingevuld moet worden!</p>
                                </div>
                                <div class="alert alert-info hidden">
                                    <p><i class="fa fa-check"></i> Uw bericht is verzonden!</p>
                                </div>
                                <div class="alert alert-error hidden">
                                    <p><i class="fa fa-warning"></i> Excuseer me, was er een fout in het verzenden van berichten!</p>
                                </div>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Event Date</label>
                                        <div class="col-md-8">
                                            <div class="input-group date">
                                                <input type="text" value="" placeholder="" id="event_date" class="form-control calendar" name="event_date" autocomplete="off">
                                                <span class="input-group-addon"><i class="fa fa-calendar default-primary-color"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Due Date</label>
                                        <div class="col-md-8">
                                            <div class="input-group date">
                                                <input type="text" value="" placeholder="" id="due_date" class="form-control calendar" calendar="full" name="due_date" autocomplete="off">
                                                <!-- <input type="hidden" value="||true|" placeholder="" id="calendar_config" name="calendar_config"> -->
                                                <span class="input-group-addon"><i class="fa fa-calendar default-primary-color"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Reden</label>
                                        <div class="col-md-8">
                                            <input id="reason" type="text" name="reason" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Aanwezig</label>
                                        <div class="col-md-8">
                                            <input id="involved" type="text" name="involved" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Toelichting</label>
                                        <div class="col-md-12">
                                            <textarea id="observation_event" maxlength="1000" class="form-control" rows="7" name="observation_event" type="text" placeholder="Enter observation here..."></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="prive">Prive</label>
                                        <div class="col-md-8">
                                            <label>
                                                <input for="event_private" type="radio" name="event_private" id="event_private" value="1"> Ja
                                            </label>
                                            <label>
                                                <input for="event_no_private" type="radio" name="event_private" id="event_no_private" value="0"> Nee
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="">Belangrijk</label>
                                        <div class="col-md-8">
                                            <label>
                                                <input for="important_notice" type="radio" name="important_notice" id="important_notice" value="1"> Ja
                                            </label>
                                            <label>
                                                <input for="no_important_notice" type="radio" name="important_notice" id="no_important_notice" value="0"> Nee
                                            </label>
                                        </div>
                                    </div>
                                    <input type="hidden" id="important_notice_selected" name="important_notice_selected" class="form-control">
                                    <input type="hidden" id="event_private_selected" name="event_private_selected" class="form-control">
                                    <input id="id_event" type="hidden" name="id_event" class="form-control" value="0">
                                    <input id="id_student" type="hidden" name="id_student" class="form-control" value="0">

                                    <input type="submit" class="btn btn-primary btn-m-w  pull-right mrg-left" id="btn-add-event" value="Save">
                                    <input type="reset" class="btn btn-danger btn-m-w pull-right mrg-left" id="btn-clear-event" value="Clear">

                                </fieldset>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php include 'footer.php'; ?>

</div class="body">



<?php include 'document_end.php';
    } ?>
<!-- PLEASE CAREFULLY WIH THIS "}" THAT CLOSE SECURITY  ELSE IF -->

<script type="text/javascript" src="assets/js/calendar.js"></script>
<!-- <script type="text/javascript" src="assets/js/app_calendarVerzuim.js"></script>-->
<script type="text/javascript" src="assets/js/app_calendar.js"></script>
<!-- INICIO CODE CaribeDevelopers Delete Calendar -->
<script type="text/javascript">
    function delete_calendar(id) {
        var result = confirm("Want to delete event calendar?");
        if (result) {
            var request = new XMLHttpRequest();
            request.open('GET', 'ajax/delete_calendar.php?id_calendar=' + id, false);
            request.send();
            location.reload();
            if (request.responseText === 1) {
                location.reload();
            } else if (request.responseText === '2') {
                alert("You don't have permissions to do this");
            }
        }
    }

    $('#period_hs').change(function() {

        if ($('#period_hs').val() == "99") {
            $('#verzuim_vakken_lijst').attr('disabled', true);
            $('#verzuim_vakken_lijst_disabled').val($('#verzuim_vakken_lijst').val());
        } else {
            $('#verzuim_vakken_lijst').attr('disabled', false);
        }
    });

    $("#form-laat-absent").submit(function() {
        $("#loader_spn").removeClass('hidden');
        $("#lbl_datum_verzuim").text($('#datum').val());
        $("#lbl_period_verzuim").text($('#period_hs option:selected').html());
        $("#lbl_vak_verzuim").text($('#verzuim_vakken_lijst option:selected').html());
    });



    $(function() {

        $("#btn_verzuim_print").click(function() {
            var datum = $("#datum").val(),
                klas = $("#verzuim_klassen_lijst option:selected").val();
            window.open("print.php?name=verzuim&title=Verzuim List&datum=" + datum + "&klas=" + klas);
            $(document).ready(function() {
                $('[data-toggle="tooltip"]').tooltip();
            });
        });

    })


    $(function() {

        $("[calendar=full]").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            endDate: false,
            autoclose: true,
        });

        $(document).ready(function() {
            var d = new Date();
            var n = d.getDate();
            var d = new Date();

            var month = d.getMonth() + 1;
            var day = d.getDate();

            var output = (('' + day).length < 2 ? '0' : '') + day + '-' +
                (('' + month).length < 2 ? '0' : '') + month + '-' +
                d.getFullYear();

            $("#calendar_date").val(output);
            $("#datum").val(output);

            setTimeout(
                function() {
                    console.log("--------------------------------------------");
                    console.log($("#verzuim_klassen_lijst option:selected").val());
                    $("#btnzoeken").click();
                }, 600);
        });

    });

    $("#form_excel").submit(function() {
        var x = $('#verzuim_klassen_lijst option:selected').val();
        $('#klas-excel').val(x);
    });

    $("#btn-clear-event").click(function(e) {
        e.preventDefault()
        var listevent = $('#id_student').val()
        $.ajax({
            url: 'ajax/get_studentid_event_hs.php',
            type: 'POST',
            async: true,
            data: {
                listevent: listevent
            },

            success: function(response) {
                console.log(response);
                $('#table_event_studentid').html(response);
            },
            error: function(error) {
                console.log(error);
            }
        })
        $(".modal").fadeOut()
        $(".body").mouseover(function(event) {
            $(".body").addClass('scroll')
        })
    })

    $("#btn-add-event").click(function(e) {
        e.preventDefault()
        var listevent = $('#id_student').val()
        var event_date = $('#event_date').val()
        var due_date = $('#due_date').val()
        var reason = $('#reason').val()
        var involved = $('#involved').val()
        var observations = $('#observation_event').val()
        if ($("#event_private").is(':checked')) {
            var private = 1
        } else {
            var private = 0
        }
        if ($("#important_notice").is(':checked')) {
            var important = 1
        } else {
            var important = 0
        }
        if (reason != '' && reason != null) {
            $.ajax({
                url: 'ajax/get_studentid_event_hs.php',
                type: 'POST',
                async: true,
                data: {
                    listevent: listevent,
                    event_date: event_date,
                    due_date: due_date,
                    reason: reason,
                    involved: involved,
                    observations: observations,
                    private: private,
                    important: important
                },

                success: function(response) {
                    console.log(response);
                    $('#table_event_studentid').html(response);
                    alert('Event added successfully!')
                    $("#btn-clear-event").click()

                },
                error: function(error) {
                    console.log(error);
                }
            })
        } else {
            $('.alert-danger1').removeClass('hidden')
        }
        $('#event_date').val('')
        $('#due_date').val('')
        $('#reason').val('')
        $('#involved').val('')
        $('#observation_event').val('')
        $("#event_private").attr('checked', false);
        $("#event_no_private").attr('checked', false);
        $("#important_notice").attr('checked', false);
        $("#no_important_notice").attr('checked', false);
    })
</script>