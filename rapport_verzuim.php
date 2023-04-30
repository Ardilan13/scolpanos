<?php include 'document_start.php';?>

<?php include 'sub_nav.php';?>

<div class="push-content-220">

	<?php include 'header.php';?>

	<?php $UserRights = $_SESSION['UserRights'];

if ($UserRights == "ONDERSTEUNING" || $UserRights == "TEACHER") {

    include 'redirect.php';} else {?>

	<main id="main" role="main">

		<section>

			<div class="container container-fs">

				<div class="row">

					<div class="default-secondary-bg-color col-md-12 inset brd-bottom clearfix">

						<h1 class="primary-color">Verzuim Rapport:</h1>

						<?php include 'breadcrumb.php';?>

					</div>

					<div class="default-secondary-bg-color col-md-12 inset filter-bar brd-bottom clearfix">

						<form class="form-inline col-md-12" name="frm_raport_verzuim" id="frm_raport_verzuim">

							<div class="form-group">

								<label>Klas</label>

								<select class="form-control" name="rapport_klassen_lijst" id="rapport_klassen_lijst">

									<!-- Options populated by AJAX get -->

									<option value="ALL">ALL</option>

								</select>

							</div>

							<label >Start Datum</label>

							<div class="input-group date col-md-1">

								<input type="text" id="start_date" name="start_date" class="form-control input-sm calendar">

								<span class="input-group-addon">

									<i class="fa fa-calendar"></i>

								</span>

							</div>

							<label >End Datum</label>

							<div class="input-group date col-md-1">

								<input type="text" id="end_date" name="end_date" class="form-control input-sm calendar">

								<span class="input-group-addon">

									<i class="fa fa-calendar"></i>

								</span>

							</div>

							<hr>

							<?php if ($_SESSION['SchoolType'] == 1) { ?>

							<label >Te Laat :</label>

							<?php } else { ?>

								<label >T (period 1-9) :</label>

							<?php } ?>

							<div class="input-group col-md-1">

								<input type="radio"class="form-group" name="rappor_te_laat" id="rappor_te_laat">

								<input type="hidden" name="_rappor_te_laat" id="_rappor_te_laat" value="0">

							</div>

							<?php if ($_SESSION['SchoolType'] == 1) { ?>

								<label >Absent :</label>
							
							<?php } else { ?>

								<label >A (period 10-dag) :</label>

							<?php } ?>


							<div class="input-group col-md-1">

								<input type="radio"class="form-group" name="rappor_absent" id="rappor_absent">

								<input type="hidden" name="_rappor_absent" id="_rappor_absent" value="0">

							</div>

							<?php if ($_SESSION['SchoolType'] == 1) { ?>

								<label >Toets inhalen :</label>

							<?php } else { ?>

								<label >S (period 1-9) :</label>

							<?php } ?>

							<div class="input-group col-md-1">

								<input type="radio"class="form-group" name="rappor_inhalen" id="rappor_inhalen">

								<input type="hidden" name="_rappor_inhalen" id="_rappor_inhalen" value="0">

							</div>



							<?php if ($_SESSION['SchoolType'] == 1): ?>

								<label >Naar huis :</label>

							<?php else: ?>

								<label >M (period 1-9) :</label>

							<?php endif;?>



							<div class="input-group col-md-1">

								<input type="radio"class="form-group" name="rappor_uitsturen" id="rappor_uitsturen">

								<input type="hidden" name="_rappor_uitsturen" id="_rappor_uitsturen" value="0">

							</div>

							<?php if ($_SESSION['SchoolType'] == 1) { ?>

								<label >Geen Huiswerk :</label>

							<?php } else { ?>

								<label >A (period 1-9) :</label>

							<?php } ?>

							<div class="input-group col-md-1">

								<input type="radio"class="form-group" name="rappor_huiswerk" id="rappor_huiswerk">

								<input type="hidden" name="_rappor_huiswerk" id="_rappor_huiswerk" value="0">

							</span>

						</div>

						<div class="form-group">

							<button data-display="data-display" type="submit" id="btn_rapport_verzuim" name="btn_rapport_verzuim" class="btn btn-primary btn-m-w btn-m-h">ZOEKEN</button>

						</div>

					</form>

				</div>

			</div>

			<div class="row">

				<div class="col-md-12 full-inset">

					<div class="sixth-bg-color brd-full">

						<div class="box-content full-inset default-secondary-bg-color equal-height">

							<div class="row">

								<div class="col-md-12 full-inset ">

									<ul class="nav nav-tabs">

										<!-- <li class="active"><a data-toggle="tab" href="#home">Home</a></li> -->

										<li class="active"><a data-toggle="tab" href="#menu1">Tabel Verzuim 1</a></li>

										<li><a data-toggle="tab" href="#menu2">Tabel Verzium 2</a></li>

										<li><a data-toggle="tab" href="#menu3">Leerling Grafiek</a></li>

										<li><a data-toggle="tab" href="#menu4">Klas Grafiek</a></li>

									</ul>

									<div class="tab-content ">

										<div id="menu1" class="tab-pane fade ">

											<div id="rapport_verzuim_table1" class="col-md-10"></div>

											<div class="row">

												<div class="col-md-12 full-inset">

													<div class="sixth-bg-color brd-full">

														<div class="box">

															<div class="box-content full-inset clearfix">

																<a style="margin-top: 10px" type="submit" name="btn_rapport_verzuim_print_table1" id="btn_rapport_verzuim_print_table1"class="btn btn-default btn-m-w pull-right mrg-left" target="_blank">PRINT</a>

															</div>

														</div>

													</div>

												</div>

											</div>

										</div>

										<div id="menu2" class="tab-pane fade in active">

											<div id="rapport_verzuim_table2" class="col-md-10"></div>

											<div class="row">

												<div class="col-md-12 full-inset">

													<div class="sixth-bg-color brd-full">

														<div class="box">

															<div class="box-content full-inset clearfix">

																<a style="margin-top: 10px" type="submit" name="btn_rapport_verzuim_print_table2" id="btn_rapport_verzuim_print_table2"class="btn btn-default btn-m-w pull-right mrg-left" target="_blank">PRINT</a>

															</div>

														</div>

													</div>

												</div>

											</div>

										</div>

										<div id="menu3" class="tab-pane fade">

											<div class="box telerik-plugin">



												<div id="rapport_verzuim_grafiek"></div>



											</div>



										<div class="row">

												<div class="col-md-12 full-inset">

													<div class="sixth-bg-color brd-full">

														<div class="box">

															<div class="box-content full-inset clearfix">

																<a style="margin-top: 10px" type="submit" name="btn_rapport_verzuim_grafiek_leerling" id="btn_rapport_verzuim_grafiek_leerling"class="btn btn-default btn-m-w pull-right mrg-left" target="_blank">PRINT</a>

															</div>

														</div>

													</div>

												</div>

											</div>

										</div>

										<div id="menu4" class="tab-pane fade">

											<div class="box telerik-plugin">



												<div id="rapport_verzuim_grafiek_resume"></div>



											</div>

											<div class="row">

												<div class="col-md-12 full-inset">

													<div class="sixth-bg-color brd-full">

														<div class="box">

															<div class="box-content full-inset clearfix">

																<a style="margin-top: 10px" type="submit" name="btn_rapport_verzuim_grafiek_klas" id="btn_rapport_verzuim_grafiek_klas"class="btn btn-default btn-m-w pull-right mrg-left" target="_blank">PRINT</a>

															</div>

														</div>

													</div>

												</div>

											</div>

										</div>

									</div>

									<input type="hidden" name="user_rights" id="user_rights" value=<?php echo $_SESSION["UserRights"]; ?>>

									<input type="hidden" name="selected_color_row" id="selected_color_row" value="" />

								</div>

								<input type="hidden" name="user_rights" id="user_rights" value=<?php echo $_SESSION["UserRights"]; ?>>

								<input type="hidden" name="selected_color_row" id="selected_color_row" value="" />

							</div>



						</div>

					</div>

				</div>

			</div>



		</section>

	</main>

	<?php include 'footer.php';?>

</div>



<?php include 'document_end.php';}?> <!-- PLEASE CAREFULLY WIH THIS "}" THAT CLOSE SECURITY  ELSE IF -->

<script>



var user_right = "<?php echo $_SESSION["UserRights"]; ?>";



if (user_right =="DOCENT" || user_right =="TEACHER")

{

	$("#rapport_klassen_lijst option[value='ALL']").remove();

}

$('input[type=radio]').on('change', function(){

	// this.val()==1;

	$('input[type=radio]').not(this).prop('checked', false);

	// $('input[type=radio]').not(this).val()==0);

});

$("#rappor_te_laat").on('click', function() {

	if ($('#rappor_te_laat').prop("checked",true))

	{

		$('#_rappor_te_laat').val(1);

		$('#_rappor_absent').val(0);

		$('#_rappor_inhalen').val(0);

		$('#_rappor_uitsturen').val(0);

		$('#_rappor_huiswerk').val(0);

	}



});

$("#rappor_absent").on('click', function() {

	if ($('#rappor_absent').prop("checked",true))

	{

		$('#_rappor_te_laat').val(0);

		$('#_rappor_absent').val(1);

		$('#_rappor_inhalen').val(0);

		$('#_rappor_uitsturen').val(0);

		$('#_rappor_huiswerk').val(0);

	}



});

$("#rappor_inhalen").on('click', function() {

	if ($('#rappor_inhalen').prop("checked",true))

	{

		$('#_rappor_te_laat').val(0);

		$('#_rappor_absent').val(0);

		$('#_rappor_inhalen').val(1);

		$('#_rappor_uitsturen').val(0);

		$('#_rappor_huiswerk').val(0);

	}



});

$("#rappor_uitsturen").on('click', function() {

	if ($('#rappor_uitsturen').prop("checked",true))

	{

		$('#_rappor_te_laat').val(0);

		$('#_rappor_absent').val(0);

		$('#_rappor_inhalen').val(0);

		$('#_rappor_uitsturen').val(1);

		$('#_rappor_huiswerk').val(0);

	}



});

$("#rappor_huiswerk").on('click', function() {

	if ($('#rappor_huiswerk').prop("checked",true))

	{

		$('#_rappor_te_laat').val(0);

		$('#_rappor_absent').val(0);

		$('#_rappor_inhalen').val(0);

		$('#_rappor_uitsturen').val(0);

		$('#_rappor_huiswerk').val(1);

	}



});



$("#btn_rapport_verzuim").click(function (e)

{	e.preventDefault();



	if ($('#start_date').val()==="" ||$('#end_date').val()==="" || $('#_rappor_te_laat').val()=="0" &&	$('#_rappor_absent').val()=="0" && $('#_rappor_inhalen').val()=="0" &&	$('#_rappor_uitsturen').val()=="0" && $('#_rappor_huiswerk').val()=="0")

	{

		alert ("Please complete all fields");

	}

	else

	{

		var rapport_klassen_lijst = $('#rapport_klassen_lijst').val(),

		start_date = $('#start_date').val(),

		end_date = $('#end_date').val(),

		_rappor_te_laat = $('#_rappor_te_laat').val(),

		_rappor_absent = $('#_rappor_absent').val(),

		_rappor_inhalen = $('#_rappor_inhalen').val(),

		_rappor_uitsturen = $('#_rappor_uitsturen').val(),

		_rappor_huiswerk = $('#_rappor_huiswerk').val(),

		src="<?php print appconfig::GetBaseURL();?>/assets/js/cableado.json";

		Modernizr.load({

			test : $.kendoChart,

			// nope : [WebApi.Config.baseUri + 'assets/telerik/styles/kendo.common.min.css', WebApi.Config.baseUri + 'assets/telerik/styles/kendo.default.min.css', WebApi.Config.baseUri + 'assets/telerik/js/kendo.all.min.js' ],

			complete : function executeTelerik () {

				$("#rapport_verzuim_grafiek_resume").kendoChart({



					dataSource: {

						transport: {

							read: {

								//url: "ajax/cijfers-graph.php",

								url: "ajax/get_rapport_verzuim_grafiek_resume.php?rapport_klassen_lijst="+

								rapport_klassen_lijst+

								"&start_date="+start_date+

								"&end_date="+end_date+

								"&rappor_te_laat="+_rappor_te_laat+

								"&rappor_absent="+_rappor_absent+

								"&rappor_inhalen="+_rappor_inhalen+

								"&rappor_uitsturen="+_rappor_uitsturen+

								"&rappor_huiswerk="+_rappor_huiswerk,

								dataType: "json"

							}

						},

						sort: {

							field: "klas",

							dir: "asc"

						}

					},

					title: {

						text: "Verzuim Resume by Klas"

					},

					legend: {

						position: "top"

					},

					seriesDefaults: {

						type: "column"

					},

					series:

					[{labels: {

						visible: true

					},

					field: "aantal",

					name: "Aantal",

					color: "#8AD6E2"

				}],

				categoryAxis: {

					field: "klas",

					labels: {

						rotation: -90

					},

					majorGridLines: {

						visible: true

					}

				},

				valueAxis: {

					labels: {

						format: "N0"

					},

					majorUnit: 10,

					line: {

						visible: true

					}

				},

				tooltip: {

					visible: true,

					format: "N0"

				}

			});



			$("#rapport_verzuim_grafiek").kendoChart({

				dataSource: {

					transport: {

						read: {

							url: "ajax/get_rapport_verzuim_grafiek.php?rapport_klassen_lijst="+

							rapport_klassen_lijst+

							"&start_date="+start_date+

							"&end_date="+end_date+

							"&rappor_te_laat="+_rappor_te_laat+

							"&rappor_absent="+_rappor_absent+

							"&rappor_inhalen="+_rappor_inhalen+

							"&rappor_uitsturen="+_rappor_uitsturen+

							"&rappor_huiswerk="+_rappor_huiswerk,

							dataType: "json"

						}

					},

					sort: {

						field: "leerling",

						dir: "asc"

					}

				},

				title: {

					text: "Verzuim Resume by Leerling"

				},

				legend: {

					position: "top"

				},

				seriesDefaults: {

					type: "column"

				},

				series:

				[{labels: {

					visible: true

				},

				field: "aantal",

				name: "Aantal",

				color: "#8AD6E2"

			}],

			categoryAxis: {

				field: "leerling",

				labels: {

					rotation: -90

				},

				majorGridLines: {

					visible: true

				}

			},

			valueAxis: {

				labels: {

					format: "N0"

				},

				majorUnit: 10,

				line: {

					visible: true

				}

			},

			tooltip: {

				visible: true,

				format: "N0"

			}

		});

	}

});









$.ajax({

	url: "ajax/get_rapport_verzuim_table1.php",

	data: $('#frm_raport_verzuim').serialize(),

	type: "POST",

	dataType: "text",

	success: function(text) {



		if(text.trim() == "0") {

			$formAddEvent.find('.alert-error').removeClass('hidden');

			$formAddEvent.find('.alert-info').addClass('hidden');

			$formAddEvent.find('.alert-warning').addClass('hidden');

		} else if(text.trim() != "0"){

			$("#rapport_verzuim_table1").html(text);



		}



	},

	error: function(xhr, status, errorThrown) {

		console.log("error");

	},

	complete: function(xhr,status) {

		$('#rapport_verzuim_table1').css({"overflow-y": "auto", "height": "450px"});

	}

});



$.ajax({

	url: "ajax/get_rapport_verzuim_table2.php",

	data: $('#frm_raport_verzuim').serialize(),

	type: "POST",

	dataType: "text",

	success: function(text) {



		if(text.trim() == "0") {

			$formAddEvent.find('.alert-error').removeClass('hidden');

			$formAddEvent.find('.alert-info').addClass('hidden');

			$formAddEvent.find('.alert-warning').addClass('hidden');

		} else if(text.trim() != "0"){

			$("#rapport_verzuim_table2").html(text);



		}



	},

	error: function(xhr, status, errorThrown) {

		console.log("error");

	},

	complete: function(xhr,status) {

		$('#rapport_verzuim_table2').css({"overflow-y": "auto", "height": "450px"});

	}

});



}





});

$("#btn_rapport_verzuim_print_table1").click(function () {

	var start_date = $("#start_date").val(),

	end_date = $("#end_date").val(),

	klas = $("#rapport_klassen_lijst option:selected").val(),

	te_laat= $("#_rappor_te_laat").val(),

	absent= $("#_rappor_absent").val(),

	inhalen= $("#_rappor_inhalen").val(),

	uitsturen= $("#_rappor_uitsturen").val(),

	huiswerk= $("#_rappor_huiswerk").val();



	window.open("print.php?name=rapport_verzuim1&title=Rapport Verzuim 1&start_date="+start_date+"&end_date="+end_date+"&klas="+klas+"&te_laat="+te_laat+

	"&absent="+absent+"&inhalen="+inhalen+"&uitsturen="+uitsturen+"&huiswerk="+huiswerk);







});

$("#btn_rapport_verzuim_print_table2").click(function () {

	var start_date = $("#start_date").val(),

	end_date = $("#end_date").val(),

	klas = $("#rapport_klassen_lijst option:selected").val(),

	te_laat= $("#_rappor_te_laat").val(),

	absent= $("#_rappor_absent").val(),

	inhalen= $("#_rappor_inhalen").val(),

	uitsturen= $("#_rappor_uitsturen").val(),

	huiswerk= $("#_rappor_huiswerk").val();



	window.open("print.php?name=rapport_verzuim2&title=Rapport Verzuim 2&start_date="+start_date+"&end_date="+end_date+"&klas="+klas+"&te_laat="+te_laat+

	"&absent="+absent+"&inhalen="+inhalen+"&uitsturen="+uitsturen+"&huiswerk="+huiswerk);







});

$("#btn_rapport_verzuim_grafiek_leerling").click(function () {

	var start_date = $("#start_date").val(),

	end_date = $("#end_date").val(),

	klas = $("#rapport_klassen_lijst option:selected").val(),

	te_laat= $("#_rappor_te_laat").val(),

	absent= $("#_rappor_absent").val(),

	inhalen= $("#_rappor_inhalen").val(),

	uitsturen= $("#_rappor_uitsturen").val(),

	huiswerk= $("#_rappor_huiswerk").val();



	window.open("print.php?name=rapport_verzuim_grafiek_leerling&title=Grafiek Verzuim Leenlings&start_date="+start_date+"&end_date="+end_date+"&klas="+klas+"&te_laat="+te_laat+

	"&absent="+absent+"&inhalen="+inhalen+"&uitsturen="+uitsturen+"&huiswerk="+huiswerk);







});

$("#btn_rapport_verzuim_grafiek_klas").click(function () {

	var start_date = $("#start_date").val(),

	end_date = $("#end_date").val(),

	klas = $("#rapport_klassen_lijst option:selected").val(),

	te_laat= $("#_rappor_te_laat").val(),

	absent= $("#_rappor_absent").val(),

	inhalen= $("#_rappor_inhalen").val(),

	uitsturen= $("#_rappor_uitsturen").val(),

	huiswerk= $("#_rappor_huiswerk").val();



	window.open("print.php?name=rapport_verzuim_grafiek_klas&title=Grafiek Verzuim klas&start_date="+start_date+"&end_date="+end_date+"&klas="+klas+"&te_laat="+te_laat+

	"&absent="+absent+"&inhalen="+inhalen+"&uitsturen="+uitsturen+"&huiswerk="+huiswerk);

});

</script>

