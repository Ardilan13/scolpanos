<?php

require_once("../classes/spn_user_hs_account.php");
require_once("../config/app.config.php");

/*
configuration for the detail table to be shown on screen
the $baseurl & $detailpage will be used to create the "View Details" link in the table
*/
$baseurl = appconfig::GetBaseURL();

if(session_status() == PHP_SESSION_NONE)
session_start();

$a = new spn_user_hs_account();
print $a->detail_user_account(appconfig::GetBaseURL(), $_GET["userGUID"], appconfig::GetDummy());

?>
<script type="text/javascript">

$('#tbl_detail_user_hs_acount tr').click(function(){
    $(this).closest('tr').siblings().children('td, th').css('background-color','');
	$(this).children('td, th').css('background-color','#cccccc');
    user_access_id = $(this).find("td").eq(0).find("[name='user_access_id']").val();
    $('#cijfers_klassen_lijst').val($(this).find("td").eq(0).text());
    fillVakByKlas($(this).find("td").eq(1).find("[name='vak_id']").val());

    if ($(this).find("td").eq(2).text() == 'Yes')
    {
        $('#is_tutor_yes').prop('checked', true);
        $('#is_tutor_hidden').val('Yes');
        $('#is_tutor_no').prop('checked', false);
        $('#is_tutor_yes').removeAttr('disabled');
        $('#is_tutor_no').removeAttr('disabled');
    }
    else
    {
        $('#is_tutor_yes').prop('checked', false);
        $('#is_tutor_no').prop('checked', true);
        	$('#is_tutor_hidden').val('No');
    }
    $('#btn_clear_vak_klas_hs').addClass("hidden");
    $('#btn_delete_vak_klas_hs').removeClass("hidden");

    $('#btn_add_vak_klas_hs').addClass("hidden");
    $('#btn_update_vak_klas_hs').removeClass("hidden");
    $('#user_access_id').attr("value",user_access_id);




});

function fillVakByKlas(vak_selected){
    $('#cijfers_vakken_lijst').empty();
    var $klas = $('#cijfers_klassen_lijst').val();
    $.getJSON("ajax/getvakken_json.php", { klas : $klas }, function(result) {
        var vak = $("#cijfers_vakken_lijst");
        $.each(result, function() {
            vak.append($("<option />").val(this.id).text(this.vak));
        });
        $('#cijfers_vakken_lijst').val(vak_selected);
    });
}


// $('#tbl_list_users_accounts tr').click(function(){
// 	$(this).closest('tr').siblings().children('td, th').css('background-color','#d4d423bf');
// 	$(this).children('td, th').css('background-color','#cccccc');

// 	$('#user_GUID').val($(this).find("td").eq(0).find("[name='GUID']").val());

// 	$('#user_email').val($(this).find("td").eq(0).text());
// 	$('#user_email').prop('disabled', true);
// 	$('#div_user_password').addClass('hidden');
// 	$('#user_firts_name').val($(this).find("td").eq(1).text());
// 	$('#user_firts_name').prop('disabled', true);
// 	$('#user_last_name').val($(this).find("td").eq(2).text());
// 	$('#user_last_name').prop('disabled', true);
// 	$('#user_rights').val($(this).find("td").eq(3).find("[name='user_rights']").val());
// 	$('#user_rights').val($(this).find("td").eq(3).text());
// 	// $('#user_school_id').val($(this).find("td").eq(4).find("[name='user_school_id']").val());
// 	// $('#user_school_id').val($(this).find("td").eq(4).text());
// 	$('#user_class').val($(this).find("td").eq(4).find("[name='user_class']").val());
// 	$('#user_class').val($(this).find("td").eq(4).text());

// 	$('#btn_create_user_account').addClass('hidden');
// 	$('#btn_update_user_account').removeClass('hidden');

// 	$('#btn_clear_user_account').addClass('hidden');
// 	$('#btn_delete_user_account').removeClass('hidden');

// });//End Function
</script>
