,<?php include 'document_start.php'; ?>

	<?php include 'sub_nav.php'; ?>

	<div class="push-content-220">
		<main id="main" role="main" class="home">
			<?php include 'header.php'; ?>
			<section>
				<div class="container container-fs">
					<div class="row">
						<div class="default-secondary-bg-color col-md-12 inset brd-bottom">
							<h1 class="primary-color">Welkom op Scol pa Nos!</h1>
							<?php include 'breadcrumb.php'; ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 full-inset">
							<div class="primary-bg-color brd-full">
								<div class="box">
									<div class="box-content full-inset sixth-bg-color">
										<div class="row">
											<div class="col-md-12">
												<div class="input-group">
													<span class="input-group-addon fifth-bg-color default-secondary-color" id="search-addon">Search:</span>
													<input type="text" class="form-control" id="search" aria-describedby="search-addon">
													<a class="input-group-addon tertiary-bg-color" id="home_search_leerling">
														<i class="fa fa-search"></i>
													</a>
													<div id="result_search_leerling"></div>
												</div>
											</div>
										</div>
									</div>

<!-- this is the dasboard chart section

									<div class="box-content full-inset sixth-bg-color">
										<div class="row mrg-bottom">
											<div class="col-md-3">
												<div class="databox primary-bg-color full-inset">
													<div class="demo-section k-content wide">
												        <div id="chart-absent"></div>
												    </div>
												</div>
											</div>
											<div class="col-md-3">
												<div class="databox primary-bg-color full-inset">
													<div class="demo-section k-content wide">
												        <div id="chart-laat"></div>
												    </div>
												</div>
											</div>
											<div class="col-md-3">
												<div class="databox primary-bg-color full-inset">
													<div class="demo-section k-content wide">
												        <div id="chart-uitgestuurd"></div>
												    </div>
												</div>
											</div>
											<div class="col-md-3">
												<div class="databox primary-bg-color full-inset">
													<div class="demo-section k-content wide">
												        <div id="chart-huiswerk"></div>
												    </div>
												</div>
											</div>
										</div>
										<div class="row">
										-->

											<!--
<div class="col-md-3">
												<div class="databox primary-bg-color full-inset">
													<div class="demo-section k-content wide">
												        <div id="chart-financial"></div>
												    </div>
												</div>
											</div>
											<div class="col-md-3">
												<div class="databox primary-bg-color full-inset">
													<div class="demo-section k-content wide">
												        <div id="chart-aangemeld"></div>
												    </div>
												</div>
											</div>
-->
											<div class="col-md-6">
											</br>
												<div class="primary-bg-color brd-full">
													<div class="box">
														<div class="box-title full-inset brd-bottom">
															<h2>Verjaardagslijst</i></h2>
														</div>
														<div class="box-content full-inset default-secondary-bg-color equal-height" style="height:285px; overflow-x: hidden;">
															<div id="tbl_birthdaylist" class="dataRetrieverOnLoad" data-ajax-href="ajax/getbirthday_tabel.php"></div>
															<div>
																<div id="tbl_birthdaylist_result"></div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-6">
											</br>
												<div class="pull-right form-inline">
													<div class="btn-group">
														<button class="btn btn-primary" data-calendar-nav="prev"><< Prev</button>
														<button class="btn" data-calendar-nav="today">Today</button>
														<button class="btn btn-primary" data-calendar-nav="next">Next >></button>
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
													<script type="text/javascript" src="assets/js/calendar.js"></script>
												</div>
											</div>
										</div>
									</div>

									<div class="box-content full-inset sixth-bg-color">
										<div class="row mrg-bottom">
											<div class="col-md-6">
												<div class="primary-bg-color brd-full">
													<div id="fullcalendar-container">
														<div class="box-title full-inset brd-bottom primary-bg-color">
															<h2>AGENDA SCHOOL BESTUUR</h2>																										</div>
														<div class="box-content full-inset default-secondary-bg-color equal-height">
															<iframe class="iframe" src="https://calendar.google.com/calendar/embed?showNav=0&amp;showPrint=0&amp;showTabs=0&amp;showCalendars=0&amp;showTz=0&amp;mode=MONTH&amp;height=600&amp;wkst=1&amp;bgcolor=%23ffffff&amp;src=0luo8ts4l4plmjbiui3jl02oik%40group.calendar.google.com&amp;color=%235F6B02&amp;ctz=America%2FBogota" width="100%" height="400" frameborder="0" scrolling="no"></iframe>
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="primary-bg-color brd-full">
													<div class="box">
														<div class="box-title full-inset brd-bottom">
															<h2>Mededelingen</h2>
														</div>
														<div class="box-content full-inset default-secondary-bg-color  equal-height">
														 <div class="lv-body dataRetrieverOnLoad" data-display="table_notifications_result" data-ajax-href="ajax/getnotifications_tabel.php">
																			<div class="table_notifications_result"></div>
																		</div>

														</div>
													</div>
												</div>
											</div>

										</div>
										<div class="row mrg-bottom">
											<div class="col-md-12">
												<div class="primary-bg-color brd-full">
													<div class="box">
														<div class="box-title full-inset brd-bottom clearfix">
															<h2 class="col-md-12">Cijfers</h2>
														</div>
														<div class="primary-bg-color brd-full">
															<div class="box telerik-plugin">
																<div class="box-content full-inset default-secondary-bg-color equal-height">
																	<!-- <div class="databox primary-bg-color full-inset"> -->
																		<div class="demo-section k-content wide">
																					<div id="graph-three-periods"></div>
																			</div>
																	<!-- </div> -->
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row">

												<div class="box-content full-inset sixth-bg-color">
													<div id="div_todo_list" class="dataRetrieverOnLoad" data-ajax-href="ajax/gettodo_list.php"></div>
														<div id="div_todo_list_detail"></div>
												</div>

											<div class="col-md-6">
												<div class="primary-bg-color brd-full">
													<div class="box">
														<div class="box-title full-inset brd-bottom">
															<h2>Klasse boek</h2>
														</div>
														<div class="box-content full-inset default-secondary-bg-color equal-height">
															<div class="table-responsive">
																<div id="klassenboek-home"></div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<br>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</main>
		<?php include 'footer.php'; ?>
	</div>

<?php include 'document_end.php'; ?>



<script type="text/javascript" src="assets/js/calendar.js"></script>
<script type="text/javascript" src="assets/js/app_calendar.js"></script>
													<!-- INICIO CODE CaribeDevelopers Delete Calendar -->
<script type="text/javascript">

var SchoolType = <?php echo $_SESSION["SchoolType"]; ?>;

 if (SchoolType==1){
	uit = 'Naar huis';
}
else{
	uit = 'Spijbelen';
}

    $(function(){
				// CaribeDevelopers: Function to delete a Calendar
				$('#accion_calendar').click(function(event){

            //alert($(this).attr('name'));

            var r =    confirm("Delete Event?");
            if (r==true){
                // $.post("ajax/delete_event.php",
                //     {
                //         funcion     : 'delete_event',
                //         xid_event   : $(this).attr('name')
                //     },
                //     function(data){
                //         if (data){
                //             alert("Event Delete!!!");
                //             window.location =  window.location = "leerlingdetail.php?id=<?php echo $_GET["id"]?>";
                //         }
                //     });
            }
        });
		});


$('#search').keypress(function (e) {

 var key = e.which;
 if(key == 13)  // the enter key code
  {
		search_leerling_by_fullname()
	}
});

$('#home_search_leerling').click(function(){
search_leerling_by_fullname()
});

function search_leerling_by_fullname(){
	var name_leerling = $('#search').val();
	var flag_result = 1;
	$.ajax({
		url: "ajax/get_leerling_by_name.php?name="+name_leerling,
		type: "POST",
		dataType: "text",
		success: function(data) {
			if (data!=0){
				$("#result_search_leerling").html(data);
			}
			else {
				flag_result = 0;
				alert ('Please write a valid student name');
			}

		},
		error: function(xhr, status, errorThrown) {
			console.log("error getting leerling name, plase contact the Developers");
		},
		complete: function(xhr,status) {
			if (flag_result==1)
			{
			var shearch_result_link = $("#redirect_leerling").val();
			if ( shearch_result_link.indexOf("leerling.php")!=-1)
			{
				//alert('im go to leerlingdetail.php')
			}
			else
			{
				window.location.replace(shearch_result_link)
				//window.open(shearch_result_link, '_blank');
				//alert('im go to leerling.php')
			}
		}
	}
	});
}


</script>
