<?php

	require_once("../classes/spn_avi.php");
	require_once("../config/app.config.php");

	/*
		configuration for the detail table to be shown on screen
		the $baseurl & $detailpage will be used to create the "View Details" link in the table
	*/
	$baseurl = appconfig::GetBaseURL();

	if(session_status() == PHP_SESSION_NONE)
		session_start();

	$userGUID = $_SESSION["UserGUID"];
	$UserRights =$_SESSION["UserRights"];

	if ($UserRights=="BEHEER" || $UserRights=="ADMINISTRATIE" || $UserRights=="ONDERSTEUNING" || $UserRights=="ADMIN" )
		$class = (isset($_POST['class']) ? ($_POST['class'] == "All"? "" : $_POST['class'] ) : "");
	else
		$class = $_SESSION["Class"];

	$a = new spn_avi();
	print $a->get_avi($_SESSION["SchoolID"], $class,$_SESSION["SchoolJaar"], appconfig::GetDummy());

?>

<script type="text/javascript">

    $(function(){

			$('#table_avi_result_detail tr td').click(function(){

				$('#mistakes').val($(this).find("[name='mistakes_hidden']").val());
				$('#time_length').val($(this).find("[name='time_length_hidden']").val());
				$('#observation').val($(this).find("[name='observation_hidden']").val());

				$('#list_class_avi').val($(this).find("[name='class_hidden']").val());

				$("#list_class_avi option:selected").val();

				var varIDStudent = $(this).find("[name='student_id_hidden']").val();


				$.ajax({
					url: "ajax/getliststudentbyclass.php",
					type: "POST",
					data: {class:$('#list_class_avi').val() },
					dataType: "HTML",
					async: false,
					success: function(data) {
						$("#data_student_by_class").html(data);
					}
				});
				$('#period').val($(this).find("[name='period_hidden']").val());

				$('#level').val($(this).find("[name='level_hidden']").val());
				// Observation
				$('#observation').val($(this).find("[name='observation_hidden']").val());
				// Promoted
				if ($(this).find("[name='promoted_hidden']").val() == "Yes")
				{
					$('#avi_promoted').prop('checked', true);
					$('#avi_no_promoted').prop('checked', false);
				}
				else
				{
					$('#avi_promoted').prop('checked', false);
					$('#avi_no_promoted').prop('checked', true);
				}
				// ID Avi
				$('#id_avi').val($(this).find("[name='id_avi_hidden']").val());

				$('#data_student_by_class option[value="' + varIDStudent +'"]').prop("selected", true);

				$('#btn-clear-avi').text("DELETE");

			});


			$('#table_avi_result_detail td').click(function(){
				var row_index = $(this).parent().index();
	   		var col_index = $(this).index();
				// alert ('Row: ' + (row_index + 1) );
				// alert ('Col: ' + (col_index + 1));
			});

		});

</script>
