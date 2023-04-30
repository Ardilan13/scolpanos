<?php include 'document_start.php'; ?>

<?php include 'sub_nav.php'; ?>

<div class="push-content-220">
	<main id="main" role="main">
		<?php include 'header.php'; ?>
		<?php $UserRights= $_SESSION['UserRights'];
		if ($UserRights != "BEHEER"){
			include 'redirect.php';} else{?>
		<section>
			<div class="container container-fs">
				<div class="row">
					<div class="default-secondary-bg-color col-md-12 inset brd-bottom">
						<h1 class="primary-color">School Admin</h1>
						<?php include 'breadcrumb.php'; ?>
					</div>
					<div class="default-secondary-bg-color col-md-12 full-inset filter-bar brd-bottom clearfix">
						<form id="form-laat-absent" class="form-inline form-data-retriever" name="filter-vak" role="form">
							<fieldset>
								<div class="form-group">
									<label for="datum">Datum</label>
									<div class="input-group date">
										<input type="text" id="datum" name="datum" class="form-control input-sm calendar">
										<span class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</span>
									</div>
								</div>

								<div class="form-group">
									<button name="get_school_group_charts" id="get_school_group_charts" type="submit" class="btn btn-primary btn-m-w btn-m-h">submit</button>
								</div>
							</fieldset>
						</form>
					</div>
					<!-- main div. It is the div that gets all the data from spn-->



				</div>
				<div id="school_admin_info"></div>
			</div>
		</section>
	</main>
	<?php include 'footer.php'; ?>
</div>

<?php include 'document_end.php';}?> <!-- PLEASE CAREFULLY WIH THIS "}" THAT CLOSE SECURITY  ELSE IF -->

<script>
var SchoolType = <?php echo $_SESSION["SchoolType"]; ?>;
var uitsturen = "";
if (SchoolType==1){
	uitsturen = 'Naar huis';
}
else{
	uitsturen = 'Spijbelen';
}

$(document).ready(function() {

	$.ajax({
		url:"ajax/get_dashboard_by_school_group.php",
		type  : 'POST',
		dataType: "HTML",
		cache: false,
		async :false,
		success: function(data){

			$("#school_admin_info").html(data);

		}
	})
});
$(window).load(function() {
	// 	// executes when complete page is fully loaded, including all frames, objects and images
	// 	alert("window is loaded");

	if($('.telerik-plugin').length) {

		Modernizr.load({
			test : $.kendoChart,
			nope : [WebApi.Config.baseUri + 'assets/telerik/styles/kendo.common.min.css',
			WebApi.Config.baseUri + 'assets/telerik/styles/kendo.default.min.css',
			WebApi.Config.baseUri + 'assets/telerik/js/kendo.all.min.js' ],
			complete : function executeTelerik () {

				var date_school_group = <?php echo $_GET["date"]; ?>;
				var school_group_count = $('#school_group_count').val();

				graphs_constructor(school_group_count, date_school_group);
				// school_group_count = school_group_count -1;

			} // end complete : function executeTelerik ()

		}); //end Modernizer.load
	}	//
});

$('#get_school_group_charts').click(function(){

var date_school_group = $('#datum').val();

	var school_group_count = $('#school_group_count').val();
	if (date_school_group!=""){

		graphs_constructor(school_group_count, date_school_group);

	}
	else {
		alert("You must select a Date");
	}
});

function graphs_constructor(school_group_count, date_school_group){

	for(var i = 1; i < school_group_count; i++) {

		var schoolid = $("#series_chart_absent_"+i).val();

		if( $('#graph_three_periods_'+i).length ) {
			// console.log("Esto es el school id en el cijfer"+ schoolid);

			$("#graph_three_periods_"+i).kendoChart({
				dataSource: {
					transport: {
						read: {
							//url: "ajax/cijfers-graph.php",
							url: "ajax/getcijfersgraph.php?date="+date_school_group+"&school="+schoolid,
							dataType: "json",
							async: false

						}
					},
					sort: {
						field: "vakken"
					}
				},
				title: {
					text: "Gemiddelde van alle vakken in 3 periodes"
				},
				series: [{
					field: "periode1",
					name: "Periode 1",
					color: "#8AD6E2"
				}, {
					field: "periode2",
					name: "Periode 2",
					color: "#FFDC66"
				}, {
					field: "periode3",
					name: "Periode 3",
					color: "#FF0044"
				}],
				categoryAxis: {
					labels: {
						rotation: {
							angle: -65
						}
					},
					field: "vakken",
					majorGridLines: {
						visible: false
					}
				},
				valueAxis: {
					labels: {
						format: "N0"
					},
					majorUnit: 1,
					plotBands: [{
						from: 0,
						to: 5.5,
						color: "#FF0044",
						opacity: 0.2
					}],
					max: 10,
					line: {
						visible: false
					}
				},
				tooltip: {
					visible: true,
					format: "Gemiddelde afgerond: {0:N0}"
				}
			});
		};

		if( $('#chart_absent_'+i).length ) {
			$('#chart_absent_'+i).kendoChart({
				dataSource: {
					transport: {
						read: {
							url:"ajax/chart_absent_school_group.php?date=" + date_school_group+"&school="+schoolid,
							dataType: "json",
							async: false
						}
					}
				},
				title: {
					text: "Absentie"
				},
				series: [{
					field: "KlasPresent",
					name: "Aanwezig",
					color: "#ffdc66"
				}, {
					field: "KlasAbsent",
					name: "Student",
					color: "#ff0044"
				}],
				chartArea: {
					height: 200
				},
				categoryAxis: {
					field: "Absentie",
					majorGridLines: {
						visible: true
					}
				},
				valueAxis: {
					line: {
						visible: true
					},
					majorUnit: 30
				},
				tooltip: {
					visible: true,
					format: "{0:N0}"
				}
			});
		}

		if( $('#chart_laat_'+i).length ) {

				$('#chart_laat_'+i).kendoChart({
					dataSource: {
						transport: {
							read: {
								url:"ajax/chart_telaat_school_group.php?date=" + date_school_group+"&school="+schoolid,
								dataType: "json",
								async: false
							}
						}
					},
					title: {
						text: "Te Laat"
					},
					series: [{
						field: "KlasPresent",
						name: "Aanwezig",
						color: "#ffdc66"
					}, {
						field: "KlasTeLaat",
						name: "Te Laat",
						color: "#ff0044"
					}],
					chartArea: {
						height: 200
					},
					categoryAxis: {
						field: "Telaat",
						majorGridLines: {
							visible: true
						}
					},
					valueAxis: {
						line: {
							visible: true
						},
						majorUnit: 30
					},
					tooltip: {
						visible: true,
						format: "{0:N0}"
					}
				});
		}

		if( $('#chart_uitgestuurd_'+i).length ) {

				$('#chart_uitgestuurd_'+i).kendoChart({
					dataSource: {
						transport: {
							read: {
								url:"ajax/chart_uitgestuurd_school_group.php?date="+date_school_group+"&school="+schoolid,
								dataType: "json",
								async: false
							}
						}
					},
					title: {
						text: uitsturen
					},
					series: [{
						field: "KlasPresent",
						name: "Aanwezig",
						color: "#ffdc66"
					}, {
						field: "KlasUitgestuurd",
						name: uitsturen,
						color: "#ff0044"
					}],
					chartArea: {
						height: 200
					},
					categoryAxis: {
						field: "geenuitgestuurd",
						majorGridLines: {
							visible: true
						}
					},
					valueAxis: {
						line: {
							visible: true
						},
						majorUnit: 30
					},
					tooltip: {
						visible: true,
						format: "{0:N0}"
					}
				});
		}

		if( $('#chart_huiswerk_'+i).length ) {

				$('#chart_huiswerk_'+i).kendoChart({
					dataSource: {
						transport: {
							read: {
								url:"ajax/chart_geenhuiswerk_school_group.php?date="+date_school_group+"&school="+schoolid,
								dataType: "json",
								async: false
							}
						}
					},
					title: {
						text: "Geen huiswerk"
					},
					series: [{
						field: "KlasPresent",
						name: "Aanwezig",
						color: "#ffdc66"
					}, {
						field: "KlasGeenHuiswerk",
						name: "Geen Huiswerk",
						color: "#ff0044"
					}],
					chartArea: {
						height: 200
					},
					categoryAxis: {
						field: "geenHuiswerk",
						majorGridLines: {
							visible: true
						}
					},
					valueAxis: {
						line: {
							visible: true
						},
						majorUnit: 30
					},
					tooltip: {
						visible: true,
						format: "{0:N0}"
					}
				});



		}
	}


}




</script>
