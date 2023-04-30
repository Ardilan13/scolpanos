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
						<h1 class="primary-color">Tussen Rapport:</h1>
						<?php include 'breadcrumb.php'; ?>
					</div>
					<p id="idSchool" style="display:none;"><?php echo $_SESSION["SchoolID"]; ?></p>
					<div class="default-secondary-bg-color col-md-12 inset filter-bar brd-bottom clearfix">
						<form id="form-vrapportexport" class="form-inline form-data-retriever" name="filter-vak" role="form" method="GET" action="/dev_tests/test_exportexcel.php">
							<fieldset>
								<div class="form-group">
									<label for="cijfers_klassen_lijst">Klas</label>
									<select class="form-control" name="rapport_klassen_lijst" id="rapport_klassen_lijst">
										<!-- Options populated by AJAX get -->

									</select>
								</div>
								<div class="form-group">
									<label for="cijfers_rapporten_lijst">SchoolJaar.</label>
									<select class="form-control" name="schooljaar_rapport" id="schooljaar_rapport">
										<!-- Options populated by AJAX get -->
										<!-- TEMPORARY ENTERED MANUALLY -->
										<!-- <option value="All">All Schooljaars</option> -->
										<option value="2022-2023">2022-2023</option>
										<option value="2021-2022">2021-2022</option>
										<option value="2020-2021">2020-2021</option>
										<option value="2019-2020">2019-2020</option>
										<option value="2018-2019">2018-2019</option>
										<option value="2017-2018">2017-2018</option>
										<option value="2016-2017" >2016-2017</option>
									</select>
								</div>
							<div class="form-group">
								<!-- <button data-display="data-display" data-ajax-href="ajax/getvakken_tabel.php" type="submit" class="btn btn-primary btn-m-w btn-m-h">zoeken</button> -->
								<button data-display="data-display" type="submit" id="btn_tussen_rapport" name="btn_tussen_rapport" class="btn btn-primary btn-m-w btn-m-h">Export</button>
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
									<p><i class="fa fa-info"></i> Selecteer de klas, vak en tussen rapport bovenaan en klik op zoeken.</p>
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

$("#btn_tussen_rapport").click(function (e) {
	e.preventDefault();
	if($('#idSchool').text()==13){
		window.open("print_klas.php?schoolJaar="+$('#schooljaar_rapport').val()+"&klas="+$('#rapport_klassen_lijst').val());
	}else{
		window.open("print_klas.php?schoolJaar="+$('#schooljaar_rapport').val()+"&klas="+$('#rapport_klassen_lijst').val());
	}	

});


</script>
