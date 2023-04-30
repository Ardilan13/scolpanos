<?php
if(isset($_GET["id_remedial"]) )
{
	require_once("../classes/spn_remedial.php");
	require_once("../classes/spn_authentication.php");
	require_once("../config/app.config.php");

	/*
		configuration for the detail table to be shown on screen
		the $baseurl & $detailpage will be used to create the "View Details" link in the table
	*/
	$baseurl = appconfig::GetBaseURL();

	if(session_status() == PHP_SESSION_NONE)
		session_start();

	$r = new spn_remedial();
	print $r->get_remedial_detail(0, 	$_GET["id_remedial"], appconfig::GetDummy());
}
?>

<script type="text/javascript">

    $(function(){

			$('#dataRequest-remedial-detail tr').click(function(){
				$('#id_remedial_detail').val($(this).find("td").eq(1).find("[name='id_remedial_detail']").val());
				$('#remedial_detail_date').val($(this).find("td").eq(0).text());
				$('#remedial_detail_observation').val($(this).find("td").eq(1).text());
				$('#btn-clear-remedial-detail').text("DELETE");
			});

    });

		$("#btn_leerling_inner_detail_remedial_print").click(function () {
			var id_remedial = $(this).attr('id_remedial');
			window.open("print.php?name=leerling_inner_detail_remedial&title=Leerling Remedial Detail&id="+$('#id_student').val()+"&id_remedial="+id_remedial);
		});

</script>
