<?php

require_once "../classes/spn_user_hs_account.php";
require_once "../classes/spn_verzuim.php";
require_once "../config/app.config.php";

$s = new spn_verzuim();

$baseurl = appconfig::GetBaseURL();

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$vak_selected = "";
/*
if (isset($_GET["verzuim_vakken_lijst"])) {
    $vak_selected = $_GET["verzuim_vakken_lijst"];
} else {
    $vak_selected = $_GET["verzuim_vakken_lijst_disabled"];
}
*/

$period = "";

$u = new spn_user_hs_account();

$IsTutor = $u->check_mentor_in_klas($_GET["klas"], $_SESSION["UserGUID"], "Klas", appconfig::GetDummy());

$IsTutorinVak = $u->check_mentor_in_klas_and_vak($_GET["klas"], $_SESSION["UserGUID"], $vak_selected, appconfig::GetDummy());

$IsMyVak = $u->check_is_docent_vak($_GET["klas"], $_SESSION["UserGUID"], $vak_selected, appconfig::GetDummy());

if (
    isset($_SESSION["UserRights"]) &&
    isset($_SESSION["SchoolID"]) &&
    isset($_GET["klas"])
) {
    // if ($_GET["period_hs"] == "99") {
    //     for ($x = 1; $x <= 9; $x++) {
    //         $create_verzuim = $s->create_le_verzuim_student(
    //             $_SESSION["SchoolID"],
    //             $_SESSION["SchoolJaar"],
    //             $_GET["klas"],
    //             $x,
    //             $_GET["datum"],
    //             $vak_selected
    //         );
    //
    //         $period = 9;
    //     }
    // } else {
    //     $create_verzuim = $s->create_le_verzuim_student(
    //         $_SESSION["SchoolID"],
    //         $_SESSION["SchoolJaar"],
    //         $_GET["klas"],
    //         $_GET["period_hs"],
    //         $_GET["datum"],
    //         $vak_selected
    //     );
    //
    //     $period = $_GET["period_hs"];
    // }



    $create_verzuim = $s->create_le_verzuim_student(
        $_SESSION["SchoolID"],
        $_SESSION["SchoolJaar"],
        $_GET["klas"],
        0,
        $_GET["datum"],
        $vak_selected
    );

    if ($_SESSION["UserRights"] == "DOCENT") {
        if ($IsTutor == 1) {
            if ($IsMyVak == 1) {
                print $s->listverzuim_hs(
                    $_SESSION["SchoolID"],
                    $_GET["klas"],
                    $_GET["datum"],
                    $period,
                    $vak_selected
                );
            } else {
                print $s->listverzuim_hs(
                    $_SESSION["SchoolID"],
                    $_GET["klas"],
                    $_GET["datum"],
                    $period,
                    $vak_selected
                );
            }
        } else {
            print $s->listverzuim_hs(
                $_SESSION["SchoolID"],
                $_GET["klas"],
                $_GET["datum"],
                $period,
                $vak_selected
            );
        }
    } elseif (
        $_SESSION["UserRights"] == "BEHEER" ||
        $_SESSION["UserRights"] == "ADMINISTRATIE" ||
        $_SESSION["UserRights"] == "ONDERSTEUNING"
    ) {
        print $s->listverzuim_hs(
            $_SESSION["SchoolID"],
            $_GET["klas"],
            $_GET["datum"],
            $period,
            $vak_selected
        );
    }
}

?>
<script>
    $("#loader_spn").toggleClass('hidden');

    function saveverzuimdata(SchoolJaar, schoolid, studentid, _klas, datum, verzuimid, controlid, controlp) {
        var vakid = $("#verzuim_vakken_lijst").val()

        var x = []
        var p1 = "";
        var p2 = "";
        var p3 = "";
        var p4 = "";
        var p5 = "";
        var p6 = "";
        var p7 = "";
        var p8 = "";
        var p9 = "";
        var p10 = "";

        if (controlp == "p1")
            if ($("#" + controlid).val() == 'A' || $("#" + controlid).val() == 'a' || $("#" + controlid).val() == 'L' || $("#" + controlid).val() == 'l' || $("#" + controlid).val() == 'X' || $("#" + controlid).val() == 'x' || $("#" + controlid).val() == 'S' || $("#" + controlid).val() == 's' || $("#" + controlid).val() == 'M' || $("#" + controlid).val() == 'm' || $("#" + controlid).val() == 'U' || $("#" + controlid).val() == 'u' || $("#" + controlid).val() == 'T' || $("#" + controlid).val() == 't') {
                p1 = $("#" + controlid).val().toUpperCase();
            } else {
                p1 = 0;
            }

        if (controlp == "p2")
            if ($("#" + controlid).val() == 'A' || $("#" + controlid).val() == 'a' || $("#" + controlid).val() == 'L' || $("#" + controlid).val() == 'l' || $("#" + controlid).val() == 'X' || $("#" + controlid).val() == 'x' || $("#" + controlid).val() == 'S' || $("#" + controlid).val() == 's' || $("#" + controlid).val() == 'M' || $("#" + controlid).val() == 'm' || $("#" + controlid).val() == 'U' || $("#" + controlid).val() == 'u' || $("#" + controlid).val() == 'T' || $("#" + controlid).val() == 't') {
                p2 = $("#" + controlid).val().toUpperCase();
            } else {
                p2 = 0;
            }

        if (controlp == "p3")
            if ($("#" + controlid).val() == 'A' || $("#" + controlid).val() == 'a' || $("#" + controlid).val() == 'L' || $("#" + controlid).val() == 'l' || $("#" + controlid).val() == 'X' || $("#" + controlid).val() == 'x' || $("#" + controlid).val() == 'S' || $("#" + controlid).val() == 's' || $("#" + controlid).val() == 'M' || $("#" + controlid).val() == 'm' || $("#" + controlid).val() == 'U' || $("#" + controlid).val() == 'u' || $("#" + controlid).val() == 'T' || $("#" + controlid).val() == 't') {
                p3 = $("#" + controlid).val().toUpperCase();
            } else {
                p3 = 0;
            }

        if (controlp == "p4")
            if ($("#" + controlid).val() == 'A' || $("#" + controlid).val() == 'a' || $("#" + controlid).val() == 'L' || $("#" + controlid).val() == 'l' || $("#" + controlid).val() == 'X' || $("#" + controlid).val() == 'x' || $("#" + controlid).val() == 'S' || $("#" + controlid).val() == 's' || $("#" + controlid).val() == 'M' || $("#" + controlid).val() == 'm' || $("#" + controlid).val() == 'U' || $("#" + controlid).val() == 'u' || $("#" + controlid).val() == 'T' || $("#" + controlid).val() == 't') {
                p4 = $("#" + controlid).val().toUpperCase();
            } else {
                p4 = 0;
            }

        if (controlp == "p5")
            if ($("#" + controlid).val() == 'A' || $("#" + controlid).val() == 'a' || $("#" + controlid).val() == 'L' || $("#" + controlid).val() == 'l' || $("#" + controlid).val() == 'X' || $("#" + controlid).val() == 'x' || $("#" + controlid).val() == 'S' || $("#" + controlid).val() == 's' || $("#" + controlid).val() == 'M' || $("#" + controlid).val() == 'm' || $("#" + controlid).val() == 'U' || $("#" + controlid).val() == 'u' || $("#" + controlid).val() == 'T' || $("#" + controlid).val() == 't') {
                p5 = $("#" + controlid).val().toUpperCase();
            } else {
                p5 = 0;
            }

        if (controlp == "p6")
            if ($("#" + controlid).val() == 'A' || $("#" + controlid).val() == 'a' || $("#" + controlid).val() == 'L' || $("#" + controlid).val() == 'l' || $("#" + controlid).val() == 'X' || $("#" + controlid).val() == 'x' || $("#" + controlid).val() == 'S' || $("#" + controlid).val() == 's' || $("#" + controlid).val() == 'M' || $("#" + controlid).val() == 'm' || $("#" + controlid).val() == 'U' || $("#" + controlid).val() == 'u' || $("#" + controlid).val() == 'T' || $("#" + controlid).val() == 't') {
                p6 = $("#" + controlid).val().toUpperCase();
            } else {
                p6 = 0;
            }

        if (controlp == "p7")
            if ($("#" + controlid).val() == 'A' || $("#" + controlid).val() == 'a' || $("#" + controlid).val() == 'L' || $("#" + controlid).val() == 'l' || $("#" + controlid).val() == 'X' || $("#" + controlid).val() == 'x' || $("#" + controlid).val() == 'S' || $("#" + controlid).val() == 's' || $("#" + controlid).val() == 'M' || $("#" + controlid).val() == 'm' || $("#" + controlid).val() == 'U' || $("#" + controlid).val() == 'u' || $("#" + controlid).val() == 'T' || $("#" + controlid).val() == 't') {
                p7 = $("#" + controlid).val().toUpperCase();
            } else {
                p7 = 0;
            }

        if (controlp == "p8")
            if ($("#" + controlid).val() == 'A' || $("#" + controlid).val() == 'a' || $("#" + controlid).val() == 'L' || $("#" + controlid).val() == 'l' || $("#" + controlid).val() == 'X' || $("#" + controlid).val() == 'x' || $("#" + controlid).val() == 'S' || $("#" + controlid).val() == 's' || $("#" + controlid).val() == 'M' || $("#" + controlid).val() == 'm' || $("#" + controlid).val() == 'U' || $("#" + controlid).val() == 'u' || $("#" + controlid).val() == 'T' || $("#" + controlid).val() == 't') {
                p8 = $("#" + controlid).val().toUpperCase();
            } else {
                p8 = 0;
            }

        if (controlp == "p9")
            if ($("#" + controlid).val() == 'A' || $("#" + controlid).val() == 'a' || $("#" + controlid).val() == 'L' || $("#" + controlid).val() == 'l' || $("#" + controlid).val() == 'X' || $("#" + controlid).val() == 'x' || $("#" + controlid).val() == 'S' || $("#" + controlid).val() == 's' || $("#" + controlid).val() == 'M' || $("#" + controlid).val() == 'm' || $("#" + controlid).val() == 'U' || $("#" + controlid).val() == 'u' || $("#" + controlid).val() == 'T' || $("#" + controlid).val() == 't') {
                p9 = $("#" + controlid).val().toUpperCase();
            } else {
                p9 = 0;
            }

        if (controlp == "p10")
            if ($("#" + controlid).val() == 'A' || $("#" + controlid).val() == 'a' || $("#" + controlid).val() == 'L' || $("#" + controlid).val() == 'l' || $("#" + controlid).val() == 'X' || $("#" + controlid).val() == 'x' || $("#" + controlid).val() == 'S' || $("#" + controlid).val() == 's' || $("#" + controlid).val() == 'M' || $("#" + controlid).val() == 'm' || $("#" + controlid).val() == 'U' || $("#" + controlid).val() == 'u' || $("#" + controlid).val() == 'T' || $("#" + controlid).val() == 't') {
                verzuim = $("#" + controlid).val().toUpperCase();
                x[10] = verzuim;
                fila = $("#" + controlid).attr('fila');
                for (i = 1; i <= 9; i++) {
                    if ($('#lp' + i + fila).val() == '' || $('#lp' + i + fila).val() == 0 || $('#lp' + i + fila).val() == null) {
                        $('#lp' + i + fila).val(verzuim);
                        x[i] = verzuim;
                    }
                }
                x.forEach(function(v, i) {
                    p1 = '';
                    p2 = '';
                    p3 = '';
                    p4 = '';
                    p5 = '';
                    p6 = '';
                    p7 = '';
                    p8 = '';
                    p9 = '';
                    p10 = '';

                    switch (i) {
                        case 1:
                            p1 = v;
                            break;
                        case 2:
                            p2 = v;
                            break;
                        case 3:
                            p3 = v;
                            break;
                        case 4:
                            p4 = v;
                            break;
                        case 5:
                            p5 = v;
                            break;
                        case 6:
                            p6 = v;
                            break;
                        case 7:
                            p7 = v;
                            break;
                        case 8:
                            p8 = v;
                            break;
                        case 9:
                            p9 = v;
                            break;
                    }
                    $.ajax({
                        url: "ajax/create_le_verzuim_student.php",
                        type: 'POST',
                        //dataType: "Json",
                        //data: "school_id="+schoolid,
                        data: {
                            school_id: schoolid,
                            schooljaar: SchoolJaar,
                            klas: _klas,
                            datum: datum,
                            vak: vakid,
                            studentid: studentid,
                            p1: p1,
                            p2: p2,
                            p3: p3,
                            p4: p4,
                            p5: p5,
                            p6: p6,
                            p7: p7,
                            p8: p8,
                            p9: p9,
                            p10: p10
                        },
                        success: function(response) {
                            console.log(response);
                        }
                    });
                });
                p10 = $("#" + controlid).val().toUpperCase();
            } else {
                p10 = 0;
            }
        console.log(x)
        $.ajax({
            url: "ajax/create_le_verzuim_student.php",
            type: 'POST',
            //dataType: "Json",
            //data: "school_id="+schoolid,
            data: {
                school_id: schoolid,
                schooljaar: SchoolJaar,
                klas: _klas,
                datum: datum,
                vak: vakid,
                studentid: studentid,
                p1: p1,
                p2: p2,
                p3: p3,
                p4: p4,
                p5: p5,
                p6: p6,
                p7: p7,
                p8: p8,
                p9: p9,
                p10: p10
            },
            success: function(response) {
                console.log(response);
            }
        });

    }

    $('.add_event').click(function(e) {
        e.preventDefault()
        var event = $(this).attr('event')
        $.ajax({
            url: 'ajax/get_studentid_event_hs.php',
            type: 'POST',
            async: true,
            data: {
                event: event
            },

            success: function(response) {
                console.log(response);
                $('#id_student').val(response);
            },
            error: function(error) {
                console.log(error);
            }
        })
        $(".modal").fadeIn()

    })

    $(document).ready(function() {
        $('.klasen_p').each(function() {
            if ($(this).val() == 0) {
                $(this).val('')
            }
        })

        $('.select_vaks').change(function(e) {
            e.preventDefault();
            p = $(this).attr('id');
            vak = $(this).val();
            klas = $(this).attr('klas');
            datum = $(this).attr('datum');
            $.ajax({
                url: "ajax/update_klasenboek_vak.php",
                type: 'POST',
                data: {
                    klas: klas,
                    datum: datum,
                    vak: vak,
                    p: p,
                },
                success: function(response) {
                    console.log(response);
                }
            });
        })

        $('.select_dag').change(function(e) {
            e.preventDefault();
            verzuim = $(this).val();
            fila = $(this).attr('fila');
            for (i = 1; i <= 9; i++) {
                if ($('#lp' + i + '_' + fila).val() == '' || $('#lp' + i + '_' + fila).val() == 0 || $('#lp' + i + '_' + fila).val() == null) {
                    $('#lp' + i + '_' + fila).val(verzuim);
                }
            }
        })
    })
</script>