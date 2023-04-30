<?php

	require_once("../classes/spn_todo.php");
	require_once("../config/app.config.php");

	/*
		configuration for the detail table to be shown on screen
		the $baseurl & $detailpage will be used to create the "View Details" link in the table
	*/
	$baseurl = appconfig::GetBaseURL();

	if(session_status() == PHP_SESSION_NONE)
		session_start();

	$t = new spn_todo();
	print $t->get_todo($_SESSION["UserGUID"]);
?>

<script type="text/javascript">

    $(function(){

			$('#todo_message').keypress(function(e) {

    if(e.which == 13) {
	e.preventDefault();

					$.post("ajax/addtodo.php",
						{
							todo_message : $("#todo_message").val()
						},
						function (data)
							{
								$.get("ajax/gettodo_list.php", function(htmlresult) {
										$('#div_todo_list_detail').empty();
										$("#div_todo_list_detail").append(htmlresult);
								});
							}
						);

    }
});

		    $("#addtodo a").click(function(e) {

					/* prevent refresh */
		  		e.preventDefault();

							$.post("ajax/addtodo.php",
								{
									todo_message : $("#todo_message").val()
								},
								function (data)
									{
										$.get("ajax/gettodo_list.php", function(htmlresult) {
												$('#div_todo_list_detail').empty();
												$("#div_todo_list_detail").append(htmlresult);
										});
									}
								);
		    });

				$(".deletetodo_c").click(function(e) {


					/* prevent refresh */
		  		e.preventDefault();

				//	alert("id"+ $(this).attr('flag'));

					$.post("ajax/deletetodo.php",
						{
							id_todo : $(this).attr('flag')
						},
						function (data)
							{
								$.get("ajax/gettodo_list.php", function(htmlresult) {
										$('#div_todo_list_detail').empty();
										$("#div_todo_list_detail").append(htmlresult);
								});
							}
						);
		    });

				$('.chektodo').click(function(e)
				{
				  // For each checked element
				    if (this.name != "")
				    {
				      var _id_todo = '';
				      var _status_todo = '';
				      /* begin post */
				      if ($(this).hasClass('fa-square-o'))
				      {
				        _id_todo = $(this).attr('value');
				        _status =1;
								$.post(
									"ajax/updatetodo.php",
									{ id_todo:_id_todo, status: _status },
									function( data ) { console.log(data); }
								).done(function(data)
								{
										if( data )
										{
											$.get("ajax/gettodo_list.php", {id : $("#id_todo").val()}, function(data) {
												$('#div_todo_list_detail').empty();
												$("#div_todo_list_detail").append(data);
											});
										}
								}
								).fail(function()
								{
										alert('Error, please contact developers.');
								});

				      }
				    }
				});


		});

</script>
