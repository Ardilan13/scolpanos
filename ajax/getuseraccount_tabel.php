<?php

require_once("../classes/spn_user_account.php");
require_once("../config/app.config.php");

/*
configuration for the detail table to be shown on screen
the $baseurl & $detailpage will be used to create the "View Details" link in the table
*/
$baseurl = appconfig::GetBaseURL();

if(session_status() == PHP_SESSION_NONE)
session_start();


$a = new spn_user_account();
print $a->list_users_accounts($_SESSION["SchoolID"], appconfig::GetDummy());

?>
<script type="text/javascript">

$('#tbl_list_users_accounts tr').click(function(){
	$(this).closest('tr').siblings().children('td, th').css('background-color','');
	$(this).children('td, th').css('background-color','#cccccc');

	$('#user_GUID').val($(this).find("td").eq(0).find("[name='GUID']").val());

	$('#user_email').val($(this).find("td").eq(0).text());
	$('#user_email').prop('disabled', true);
	$('#div_user_password').addClass('hidden');
	$('#user_firts_name').val($(this).find("td").eq(1).text());
	$('#user_firts_name').prop('disabled', false);
	$('#user_last_name').val($(this).find("td").eq(2).text());
	$('#user_last_name').prop('disabled', false);
	$('#user_rights').val($(this).find("td").eq(3).find("[name='user_rights']").val());
	$('#user_rights').val($(this).find("td").eq(3).text());
	// $('#user_school_id').val($(this).find("td").eq(4).find("[name='user_school_id']").val());
	// $('#user_school_id').val($(this).find("td").eq(4).text());
	$('#user_class').val($(this).find("td").eq(4).find("[name='user_class']").val());
	$('#user_class').val($(this).find("td").eq(4).text());

	$('#btn_create_user_account').addClass('hidden');
	$('#btn_update_user_account').removeClass('hidden');

	$('#btn_clear_user_account').addClass('hidden');
	$('#btn_delete_user_account').removeClass('hidden');

});//End Function
</script>
