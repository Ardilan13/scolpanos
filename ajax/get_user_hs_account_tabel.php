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
print $a->list_users_accounts(appconfig::GetBaseURL(), $_SESSION["SchoolID"], appconfig::GetDummy());

?>
<script type="text/javascript">

$('#tbl_list_users_accounts_hs tr').click(function(){
	$(this).closest('tr').siblings().children('td, th').css('background-color','');
	$(this).children('td, th').css('background-color','#cccccc');

	$('#user_hs_GUID').val($(this).find("td").eq(0).find("[name='GUID']").val());

	$('#user_hs_email').val($(this).find("td").eq(0).text());
	$('#user_hs_email').prop('disabled', true);
	$('#div_user_hs_password').addClass('hidden');
	$('#div_user_hs_password_confirm').addClass('hidden');
	$('#user_hs_firts_name').val($(this).find("td").eq(1).text());
	$('#user_hs_firts_name').prop('disabled', true);
	$('#user_hs_last_name').val($(this).find("td").eq(2).text());
	$('#user_hs_last_name').prop('disabled', true);
	$('#user_hs_rights').val($(this).find("td").eq(3).find("[name='user_hs_rights']").val());
	$('#user_hs_rights').val($(this).find("td").eq(3).text());
	$('#user_class').val($(this).find("td").eq(4).find("[name='user_class']").val());
	$('#user_class').val($(this).find("td").eq(4).text());

	$('#btn_create_user_hs_account').addClass('hidden');
	$('#btn_update_user_hs_account').removeClass('hidden');

	$('#btn_clear_user_hs_account').addClass('hidden');
	$('#btn_delete_user_hs_account').removeClass('hidden');

});//End Function
</script>
