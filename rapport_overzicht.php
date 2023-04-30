<?php include 'document_start.php'; ?>
<?php include 'sub_nav.php'; ?>
<div class="push-content-220">
	<?php include 'header.php'; ?>
	<?php $UserRights= $_SESSION['UserRights'];
	if ($UserRights == "ADMINISTRATIE" || $UserRights == "ONDERSTEUNING" ||$UserRights == "TEACHER" ){
		include 'redirect.php';} else{?>
	<main id="main" role="main">
		<section>
			<div class="container container-fs">
				<div class="row">
					<div class="default-secondary-bg-color col-md-12 inset brd-bottom clearfix">
						<h1 class="primary-color">Rapport Overzicht:</h1>
						<?php include 'breadcrumb.php'; ?>
					</div>
					<div class="default-secondary-bg-color col-md-12 inset filter-bar brd-bottom clearfix">
						<form id="form-vrapportexport" class="form-inline form-data-retriever" name="filter-vak" role="form" method="GET" action="/dev_tests/test_exportexcel.php">
							<fieldset>
								<div class="form-group">
									<label for="cijfers_klassen_lijst">Klas</label>
									<select class="form-control" name="rapport_klassen_lijst" id="rapport_klassen_lijst">
										<!-- Options populated by AJAX get -->
										<option value="NONE">NONE</option>
									</select>
								</div>
							<div class="form-group">
								<!-- <button data-display="data-display" data-ajax-href="ajax/getvakken_tabel.php" type="submit" class="btn btn-primary btn-m-w btn-m-h">zoeken</button> -->
								<button data-display="data-display" type="submit" id="btn_rapport_export" name="btn_rapport_export" class="btn btn-primary btn-m-w btn-m-h">Export</button>
							</div>
							<div class="form-group">
								<ol class="breadcrumb">
								    <li><a id="ttr_download" href"#">DOWNLOAD CLEAN RAPPORT OVERZICHT</a></li>
								</ol>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 full-inset">
					<div class="sixth-bg-color brd-full">
						<div class="box">
							<div class="box-content full-inset">

								<div class="full-inset alert alert-info reset-mrg-bottom" id="message_rapport">
									<p><i class="fa fa-info"></i> Selecteer de klas, vak en rapport nummer bovenaan en klik op zoeken.</p>
								</div>
								<div class="full-inset alert alert-info reset-mrg-bottom hidden" id="popup_message_rapport" >
									<p ><i class="fa fa-info"></i> If you have problems downloading the excel file, check your pop-up settings</p>
								</div>
								<div class="full-inset alert alert-warning reset-mrg-bottom hidden" id="klas_message_rapport">
									<p><i class="fa fa-warning"></i> You must select one Klas to export rapport...</p>
								</div>
								<div class="full-inset alert alert-warning reset-mrg-bottom hidden" id="setting_message_rapport">
									<p><i class="fa fa-warning"></i> There should be a Setting created for this schooljaar to generate a rapport...</p>
								</div>
								<!-- <div class="data-display"></div> -->
								<?php include 'modal_cijfers.php'; ?>
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

<?php include 'document_end.php';}?> <!-- PLEASE CAREFULLY WIH THIS "}" THAT CLOSE SECURITY  ELSE IF -->
<script>

$("#btn_rapport_export").click(function (e) {
	e.preventDefault();
	var SchoolID = '<?php echo $_SESSION["SchoolID"]; ?>';
	var klas = $("#rapport_klassen_lijst option:selected").val();

	if (klas!='NONE'){
		$.ajax({
			url:"ajax/check_setting.php",
			data: "SchoolID="+SchoolID,
			type  : 'POST',
			//dataType: "HTML",
			cache: false,
			async :true,
			success: function(data){
				if (data==='1'){
					$('#message_rapport').addClass('hidden');
					$('#klas_message_rapport').addClass('hidden');
					$('#setting_message_rapport').addClass('hidden');
					$('#popup_message_rapport').removeClass('hidden');
					window.open("dev_tests\\export_overzicht.php?klas_list="+$("#rapport_klassen_lijst option:selected").val());

				}
				else{
					$('#message_rapport').addClass('hidden');
					$('#klas_message_rapport').addClass('hidden');
					$('#popup_message_rapport').addClass('hidden');
					$('#setting_message_rapport').removeClass('hidden');
				//alert('There should be a Setting created for this schooljaar to generate a rapport...');
				}
			}
		});
	}
	else {
		$('#message_rapport').addClass('hidden');
		$('#setting_message_rapport').addClass('hidden');
		$('#popup_message_rapport').addClass('hidden');
		$('#klas_message_rapport').removeClass('hidden');
		//alert('You must select one Klas to export rapport...');
}
});

 $("#ttr_download").click(function (e) {
	 $("#ttr_download").attr("href","templates/overzicht_SPN.xlsx");
});

//
// var SchoolID = '<?php echo $_SESSION["SchoolID"]; ?>';
// switch (SchoolID){
//
//
// 					case "1" :
// 						$("#ttr_download").attr("href","");
// 					break;
//
// 					case "2" :
// 						$("#ttr_download").attr("href","templates/RAPPORT_TEMPLATE_BLANK.xlsm");
// 					break;
//
// 					case "3" :
// 						$("#ttr_download").attr("href","templates/RAPPORT_TEMPLATE_BLANK.xlsm");
// 					break;
//
// 					case "4" :
// 					  $("#ttr_download").attr("href","templates/4_kudawecha.xlsm");
// 					break;
//
// 					case "5" :
// 						$("#ttr_download").attr("href","templates/RAPPORT_TEMPLATE_BLANK.xlsm");
// 					break;
//
// 					case "6" :
// 						$("#ttr_download").attr("href","templates/6_Washington.xlsm");
// 					break;
//
// 					case "7" :
// 						$("#ttr_download").attr("href","templates/7_Prinses_Amalia.xlsm");
// 					break;
//
// 					case "8" :
// 						$("#ttr_download").attr("href","templates/8_verzamelstaatSML.xlsx");
// 					break;
//
// 					case "9" :
// 						$("#ttr_download").attr("href","templates/9_Reina_beatrix.xlsm");
// 					break;
//
// 					case "10" :
// 						$("#ttr_download").attr("href","templates/10_pieterboer.xlsm");
// 					break;
//
// 					case "11" :
// 						$("#ttr_download").attr("href","templates/11_Hilario.xlsm");
// 					break;
// 				};
// 				});

</script>
