<?php

require_once("../classes/spn_le_vakken.php");
require_once("../config/app.config.php");

/*
configuration for the detail table to be shown on screen
the $baseurl & $detailpage will be used to create the "View Details" link in the table
*/
$baseurl = appconfig::GetBaseURL();

if(session_status() == PHP_SESSION_NONE)
session_start();


$a = new spn_le_vakken();
print $a->list_le_vakken($_SESSION["SchoolID"], appconfig::GetDummy());

?>
<script type="text/javascript">

$('#tbl_list_le_vakken tr').click(function(){

	var school_id = $(this).find("td").eq(0).find("[name='id_school']").val();

	$.ajax({
		url: "ajax/get_list_class_by_school.php?school_id="+school_id,
		type: "POST",
		dataType: "HTML",
		async: false,
		success: function(data) {
			$("#le_vakken_class").html(data);
		}
	});


	$(this).closest('tr').siblings().children('td, th').css('background-color','');
	$(this).children('td, th').css('background-color','#cccccc');


	$('#le_vakken_schools').val($(this).find("td").eq(0).find("[name='id_school']").val());
	$('#le_vakken_class').val($(this).find("td").eq(1).find("[name='klas']").val());
	$('#volledigenaamvak_id').val($(this).find("td").eq(2).find("[name='id_le_vakken']").val());
	$('#volledigenaamvak_name').val($(this).find("td").eq(2).text());
	$('#le_vakken_volgorde').val($(this).find("td").eq(3).text());
	$('#le_vakken_xindex').val($(this).find("td").eq(4).text());
	// $('#le_vakken_volgorde').prop('disabled', true);

	$('#btn_create_le_vakken').addClass('hidden');
	$('#btn_create_le_vakken').attr('disabled');
	$('#btn_update_le_vakken').removeClass('hidden');

	$('#btn_clear_le_vakken').addClass('hidden');
	$('#btn_delete_le_vakken').removeClass('hidden');

});//End Function
</script>
