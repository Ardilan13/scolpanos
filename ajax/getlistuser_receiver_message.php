<?php

	require_once("../classes/spn_message.php");
	require_once("../classes/spn_utils.php");

	$m = new spn_message();

	if(session_status() == PHP_SESSION_NONE)
		session_start();

	print  $m->listusersreceivermessage($_SESSION["UserGUID"], $_SESSION["SchoolID"]);

?>
<script type="text/javascript">
	$("#users").on("change",
		function()
		{
			var texto = "";
			$("#users option:selected").each(
			function()
			{
				//alert($(this).text());
				if ($(this).text() != "Select user(s)" && $(this).text() != "All")
				{
					if ($("#selectedto").val() == "")
					{
						$("#users_selected").val($(this).val());
						texto = $(this).text();
						$("#count_users_selected").val("1");
					}
					else
					{
						/* Recorrer todo los usuarios para validar que ya no este */
						/* BUSCAR ESTE VALOR EN #selectedto $(this).text() */
						var users = $("#selectedto").val().split(",");
						texto = $("#selectedto").val();
						// alert('Useres:' + users + 'Tamano: ' + users.length);
						// alert('Text: ' + texto);
						var find = false;
						var i = 0;

						while (!find && i < users.length) {
							if ($(this).text() == users[i].trim())
								find = true;
							else
								i++;
						}
						if (!find)
						{
							// alert("No lo consiguio");
							$("#users_selected").val($("#users_selected").val() + "," + $(this).val());
							texto = $("#selectedto").val() + ", " + $(this).text();
							$("#count_users_selected").val((parseInt($("#count_users_selected").val()) + 1).toString());
						}
						else {
							/* Retirar el seleccionado */
							var users = $("#selectedto").val().split(",");
							texto = $("#selectedto").val();
							// alert('Useres:' + users + 'Tamano: ' + users.length);
							// alert('Text: ' + texto);
							var find = false;
							var i = 0;

							while (!find && i < users.length) {
								if ($(this).text() == users[i].trim())
									find = true;
								else
									i++;
							}
							if (find)
							{
								// alert("Lo consiguio");
								// alert($(this).text());
								// $("#users_selected").val($("#users_selected").val() + "," + $(this).val());
								// texto = $("#selectedto").val() + ", " + $(this).text();
								// $("#count_users_selected").val((parseInt($("#count_users_selected").val()) + 1).toString());
							}
						}
					}
				}
				else if($(this).text() == "All")
				{
					var i = 0;
					$("#users_selected").val('');
					$("#count_users_selected").val('0');

					$("#users option").each(function() {
						if ($(this).val() != '-1' && $(this).val() != '0')
						{
							if ($("#users_selected").val() == '')
							{
								$("#users_selected").val($(this).val());
								texto = $(this).text();
								$("#count_users_selected").val("1");
							}
							else
							{
								$("#users_selected").val($("#users_selected").val() + "," + $(this).val());
								texto = texto + ", " + $(this).text();
								$("#count_users_selected").val((parseInt($("#count_users_selected").val()) + 1).toString());
							}
						}
						i++;
					});
				}
				else if ($(this).text() == "Select user(s)")
				{
					texto = "";
					$("#users_selected").val('');
					$("#count_users_selected").val('');
				}
			});
			$("#selectedto").val(texto);
		});

</script>
