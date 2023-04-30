<?php include 'document_start.php'; ?>

<?php include 'sub_nav.php'; ?>
<div class="push-content-220">
	<main id="main" role="main">
		<?php include 'header.php'; ?>
		<section>
			<div class="container container-fs">
				<div class="row">
					<div class="default-secondary-bg-color col-md-12 inset brd-bottom">
						<h1 class="primary-color">Leerlingen</h1>
						<?php include 'breadcrumb.php'; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 full-inset">
						<div class="primary-bg-color brd-full">
							<div class="box">
								<div class="box-title full-inset brd-bottom">
									<div class="row">
										<div class="col-md-11">
											<h3>Nieuwe leerling</h3>
										</div>
										<div class="col-md-1">
											<button aria-label="Help" class="btn btn-info" type="button" id="btn_show_leerling_new" name="btn_show_leerling_new">
												<i class="fa fa-exchange" aria-hidden="true"></i>
											</button>
										</div>
									</div>
								</div>
								<div class="sixth-bg-color box content full-inset" id="leerling_new" name="leerling_new">
									<form class="form-horizontal align-left" role="form" name="form-add-student" id="form-addStudent1">
										<div class="alert alert-danger hidden">
											<p><i class="fa fa-warning"></i> Er zijn lege velden die ingevuld moet worden!</p>
										</div>
										<div class="alert alert-info hidden">
											<p><i class="fa fa-check"></i> Bedankt voor het toevoegen van een nieuwe leerling!</p>
										</div>
										<div class="alert alert-info-update hidden">
											<p><i class="fa fa-check"></i> The student was updated successfully!</p>
										</div>
										<fieldset>
											<input type="hidden" id="calendar_config" name="calendar_config" value="||true|">
											<input type="hidden" name="student_id" id="student_id" value="" />
											<input type="hidden" name="school_id" id="school_id" value="<?php echo $_SESSION["SchoolID"]; ?>" />
											<input type="hidden" name="baseurl" id="baseurl" value=<?php require_once("config/app.config.php");
																									print appconfig::GetBaseURL(); ?> />
											<input type="hidden" name="detailpage" id="detailpage" value="leerlingdetail.php" />
											<div class="row">
												<div class="col-sm-4">
													<label class="col-md-4 control-label">Leerling #</label>
													<div class="col-md-6">
														<input id="studentnummer" class="form-control" type="text" name="studentnummer" required />
													</div>
												</div>
												<div class="col-sm-4">
													<label class="col-md-4 control-label">Klas</label>
													<div class="col-md-6">
														<select name="student-klas" id="student-klas" class="form-control">
															<option value="AF">AF</option>
														</select>
													</div>
												</div>
												<div class="col-sm-4">
													<label class="col-md-4 control-label">Achternaam</label>
													<div class="col-md-6">
														<input id="achternaam" class="form-control" type="text" name="achternaam" required />
													</div>
												</div>
											</div>
											<br>
											<div class="row">
												<div class="col-sm-4">
													<label class="col-md-4 control-label">Voornamen</label>
													<div class="col-md-6">
														<input id="voornamen" class="form-control" type="text" name="voornamen" required />
													</div>
												</div>
												<div class="col-sm-4">
													<label class="col-md-4 control-label" for="geslacht">Geslacht</label>
													<div class="col-md-6">
														<select id="geslacht" name="geslacht" class="form-control">
															<option value="M">Man</option>
															<option value="V">Vrouw</option>
														</select>
													</div>
												</div>
												<div class="col-sm-4">
													<label class="col-md-4 control-label">Geboortedatum</label>
													<div class="col-md-6">
														<div class="input-group date">
															<input id="geboortedatum" type="text" value="" placeholder="" id="geboortedatum" class="form-control calendar" name="geboortedatum">
															<span class="input-group-addon"><i class="fa fa-calendar default-primary-color"></i></span>
														</div>
													</div>
												</div>
											</div>
											<br>
											<div class="row">
												<div class="col-sm-4">
													<label class="col-md-4 control-label">Roepnaam</label>
													<div class="col-md-6">
														<input id="rooepnaam" class="form-control" type="text" name="rooepnaam" />
													</div>
												</div>
												<div class="col-sm-4">
													<label class="col-md-4 control-label">Voorletter</label>
													<div class="col-md-6">
														<input id="voorletter" class="form-control" type="text" name="voorletter" />
													</div>
												</div>

												<div class="col-sm-4">
													<label class="col-md-4 control-label">Adres</label>
													<div class="col-md-6">
														<input id="adres" class="form-control" type="text" name="adres" />
													</div>
												</div>
											</div>
											<br>
											<div class="row">
												<div class="col-sm-4">
													<label class="col-md-4 control-label">Tel. nr (thuis)</label>
													<div class="col-md-6">
														<input id="tellthuis" class="form-control" type="text" name="tellthuis" />
													</div>
												</div>
												<div class="col-sm-4">
													<label class="col-md-4 control-label" for="telefoon">Telefoon / Mobiel</label>
													<div class="col-md-6">
														<input id="telefoon" class="form-control" type="text" name="telefoon" />
													</div>
												</div>
												<div class="col-sm-4">
													<label class="col-md-4 control-label">Verzorger na schooltijd</label>
													<div class="col-md-6">
														<input id="verzorgernaschooltijd" class="form-control" type="text" name="verzorgernaschooltijd" />
													</div>
												</div>
											</div>
											<br>
											<div class="row">
												<div class="col-sm-4">
													<label class="col-md-4 control-label">Telefoon noodgeval</label>
													<div class="col-md-6">
														<input id="telefoonnoodgeval" class="form-control" type="text" name="telefoonnoodgeval" />
													</div>
												</div>
												<div class="col-sm-4">
													<label class="col-md-4 control-label" for="">Geboorteplaats</label>
													<div class="col-md-6">
														<select id="geboorteplaats" name="geboorteplaats" class="form-control">
															<option value="">Select an Option</option>
															<option value="ARUBA">Aruba</option>
															<option value="BONAIRE">Bonaire</option>
															<option value="CHINA">China</option>
															<option value="COLOMBIA">Colombia</option>
															<option value="CURACAO">Curaçao</option>
															<option value="DOMINICAANSE REPUBLIEK">Dominicaanse Republiek</option>
															<option value="ECUADOR">Ecuador</option>
															<option value="HAITI">Haiti</option>
															<option value="INDIA">India</option>
															<option value="NEDERLAND">Nederland</option>
															<option value="PERU">Peru</option>
															<option value="SURINAME">Suriname</option>
															<option value="VENEZUELA">Venezuela</option>
															<option value="JAMAICA">Jamaica</option>
															<option value="SINT MAARTEN">Sint Maarten</option>
															<option value="SINT EUSTATIUS">Sint Eustatius</option>
															<option value="SABA">Saba</option>
															<option value="VERENIGDE STATEN">Verenigde Staten</option>
														</select>
													</div>
												</div>
												<div class="col-sm-4">
													<label class="col-md-4 control-label">Nationaliteiten</label>
													<div class="col-md-6">
														<input id="nationaliteiten" class="form-control" type="text" name="nationaliteiten" />
													</div>
												</div>
											</div>
											<br>
											<div class="row">
												<div class="col-sm-4">
													<label class="col-md-4 control-label">AZV nr</label>
													<div class="col-md-6">
														<input id="azv_nr" class="form-control" type="text" name="azv_nr" />
													</div>
												</div>
												<div class="col-sm-4">
													<label class="col-md-4 control-label">Expire datum AZV</label>
													<div class="col-md-6">
														<div class="input-group date">
															<input id="expiredatum" type="text" value="" placeholder="" class="form-control calendar" name="expiredatum">
															<span class="input-group-addon"><i class="fa fa-calendar default-primary-color"></i></span>
														</div>
													</div>
												</div>
												<div class="col-sm-4">
													<label class="col-md-4 control-label">Identeits nr.</label>
													<div class="col-md-6">
														<input id="identeits" class="form-control" type="text" name="identeits" />
													</div>
												</div>
											</div>
											<br>
											<div class="row">
												<div class="col-sm-4">
													<label class="col-md-4 control-label">Voertaal</label>
													<div class="col-md-6">
														<input id="vo_val" class="form-control" type="text" name="vo_val" />
													</div>

													<input type="hidden" name="ned_val" id="ned_val" value="0">
													<input type="hidden" name="pap_val" id="pap_val" value="0">
													<input type="hidden" name="en_val" id="en_val" value="0">
													<input type="hidden" name="sp_val" id="sp_val" value="0">
												</div>
												<div class="col-sm-4">
													<label class="col-md-4 control-label">Email adres</label>
													<div class="col-md-6">
														<input id="email" class="form-control" type="text" name="email" />
													</div>
												</div>
												<div class="col-sm-4">
													<label class="col-md-4 control-label">Vorige school</label>
													<div class="col-md-6">
														<input id="vorigeschool" class="form-control" type="text" name="vorigeschool" />
													</div>
												</div>

											</div>
											<br>
											<div class="row">
												<div class="col-sm-4">
													<label class="col-md-4 control-label">Student status</label>
													<div class="col-md-6">
														<select id="status" name="status" class="form-control" required>
															<option value="1">Actief</option>
															<option value="0">Niet-Actief</option>
															<!-- <option value="Deceased">Deceased</option>
															<option value="Blacklisted">Blacklisted</option> -->
														</select>
													</div>
												</div>
												<div class="col-sm-4">
													<label class="col-md-4 control-label">Family</label>
													<div class="col-md-6">
														<select id="leerling_family" name="leerling_family" class="form-control">
														</select>
													</div>
												</div>
												<div class="col-sm-4">
													<label class="col-md-4 control-label">PIN</label>
													<div class="col-md-6">
														<input id="securepin" class="form-control" type="text" name="securepin" disabled />
													</div>
												</div>
											</div>
											<br>
											<div class="row">
												<div class="col-sm-4">
													<label class="col-md-4 control-label">Datum inschrijving</label>
													<div class="col-md-6">
														<div class="input-group date">
															<input id="datum_inschrijving" type="text" value="" placeholder="" class="form-control calendar" name="datum_inschrijving">
															<span class="input-group-addon"><i class="fa fa-calendar default-primary-color"></i></span>
														</div>
													</div>
												</div>
												<!--	<div class="col-sm-4">
													<label class="col-md-4 control-label">Datum Uitschijving</label>
													<div class="col-md-6">
														<div class="input-group date">
															<input id="datum_uitschijving" type="text" value="" placeholder="" class="form-control calendar" name="datum_uitschijving" >
															<span class="input-group-addon"><i class="fa fa-calendar default-primary-color"></i></span>
														</div>
													</div>
												</div>-->
											</div>

											<br>
											<br>
											<div class="form-group col-md-12">
												<h2 class="primary-color mrg-bottom">Medische Gegevens</h2>
												<div class="form-group">
													<label class="col-md-2 control-label">Lichamelijkegebrek</label>
													<input type="checkbox" name="_spraak_val" id="_spraak_val">Spraak
													<input type="checkbox" name="_gehoor_val" id="_gehoor_val">Gehoor
													<input type="checkbox" name="_gezicht_val" id="_gezicht_val">Gezicht
													<input type="checkbox" name="_motoriek_val" id="_motoriek_val"> Motoriek
													<input type="hidden" name="spraak_val" id="spraak_val" value="0">
													<input type="hidden" name="gehoor_val" id="gehoor_val" value="0">
													<input type="hidden" name="gezicht_val" id="gezicht_val" value="0">
													<input type="hidden" name="motoriek_val" id="motoriek_val" value="0">
												</div>
											</div>
											<div class="row">
												<div class="col-sm-6">
													<label class="col-md-4 control-label">Anders n.I.</label>
													<div class="col-md-6">
														<input id="anders" class="form-control" type="text" name="anders" />
													</div>
												</div>
												<div class="col-sm-6">
													<label class="col-md-4 control-label">Huis Arts</label>
													<div class="col-md-6">
														<input id="huisarts" class="form-control" type="text" name="huisarts" />
													</div>
												</div>
											</div>
											<br>
											<div class="row">
												<div class="col-sm-6">
													<label class="col-md-4 control-label">Bijzonder medische indicatie</label>
													<div class="col-md-6">
														<input id="bizjonder" class="form-control" type="text" name="bizjonder" />
													</div>
												</div>
												<div class="col-sm-6">
													<label class="col-md-4 control-label">Tel.nr</label>
													<div class="col-md-6">
														<input id="telnr" class="form-control" type="text" name="telnr" />
													</div>
												</div>
											</div>
											<br>
											<div class="form-group col-md-12">
												<h2 class="primary-color mrg-bottom">Nota's & Picture</h2>
												<div class="form-group">
													<label class="col-md-1 control-label">Notes</label>
													<div class="col-md-5">
														<textarea id="notes" class="form-control" name="notes" type="text"></textarea>
													</div>
													<label class="col-md-1 control-label">Picture</label>
													<div class="col-md-5">
														<input class="form-control" type="file" name="pictureToUpload" id="pictureToUpload">
													</div>
												</div>
											</div>
										</fieldset>
										<div class="form-group full-inset">
											<button type="submit" class="btn btn-primary btn-m-w pull-right mrg-left" id="btn-add-student">Save</button>
											<button type="reset" class="btn btn-danger btn-m-w pull-right mrg-left" id="btn-clear-student-selected">Clear</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 full-inset">
						<div class="primary-bg-color brd-full">
							<div class="box">
								<div class="box-title full-inset brd-bottom">
									<div class="row">
										<h2 class="col-md-6">Leerlingen</h2>
										<div class="col-md-6">
											<ul class="col-md-6 list list-toggle">
												<li>
													<a href="#" id="toggle-leerlinglist">
														<i class="fa fa-list-ul"></i>
													</a>
												</li>
												<li>
													<a href="#" id="toggle-koppenkaart">
														<i class="fa fa-th-large"></i>
													</a>
												</li>
											</ul>
										</div>
									</div>
									<input type="hidden" name="selected_id_student_row" id="selected_id_student_row" value="" />
									<input type="hidden" name="selected_color" id="selected_color" value="" />
								</div>
								<div class="box-content full-inset sixth-bg-color listleerling">
									<div class="dataRetrieverOnLoad" data-ajax-href="ajax/getleerling_tabel.php" data-display="data-leerling-summary"></div>
									<div class="table-responsive data-table" data-table-type="on" data-table-search="true" data-table-pagination="true">
										<div class="data-leerling-summary"></div>

										<a type="submit" name="btn_leerling_print" id="btn_leerling_print" class="btn btn-default btn-m-w pull-right mrg-left" target="_blank">PRINT</a>

									</div>
								</div>
								<div class="box-content full-inset sixth-bg-color koppenkaart">
									<div class="row">
										<div id="dataRequest-student_pic"></div>
										<!--This section its for the profiles pictures.-->
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
</div>
</div>
</div>


</section>
</main>
<?php include 'footer.php'; ?>


<?php include 'document_end.php'; ?>
<script type="text/javascript">
	function ChatRoom($schoolid, $studentid, $uuid) {

		var id = '<?php echo $_SESSION["UserGUID"]; ?>';
		var code = '<?php echo $_SESSION["Identity"]; ?>';

		var idroom = $uuid + "-" + $schoolid;
		//var room = $voornamen + " " + $achternaam;;
		var room = $uuid;
		var d = new Date();
		d.setMinutes(d.getMinutes() + 10)
		var time = d.getTime() / 1000;

		var url = "https://digiroom.madworksglobal.com/?room=" + room + "&id=" + id + "&code=" + code + "&type=3" + "&idroom=" + idroom + "&school=1" + "&ts=" + time;
		window.open(url, "_blank");
	}

	$(function() {

		$(document).ready(function() {

			$.get("ajax/getfamilies.php", function(data) {
				$('#leerling_family').append(data);
			});
		});

		$("#btn_leerling_print").click(function() {
			window.open("print.php?name=leerling&title=Leerling List");
		});

		$('#leerling_new').hide();

		$('#btn_show_leerling_new').on("click", function() {
			$('#leerling_new').toggle("")
		});


		$('#btn-clear-student-selected').hide();

		$('#btn-clear-student-selected').click(function() {
			$('#student_id').val("");
			$('#btn-clear-student-selected').fadeOut();

			$(this).closest('tr').siblings().children('td, th').css('background-color', '');
			$(this).children('td, th').css('background-color', $("#selected_color").val());
			$("#selected_id_student_row").val("");
			$("#selected_color").val("");
			$('#btn-clear-student-selected').fadeOut();

		});

		$("#_ned_val").on('click', function() {

			if ($('#_ned_val').is(":checked")) {
				$('#ned_val').val(1);
			} else {
				$('#ned_val').val(0);
			}
		});

		$("#_pap_val").on('click', function() {

			if ($('#_pap_val').is(":checked")) {
				$('#pap_val').val(1);
			} else {
				$('#pap_val').val(0);
			}
		});

		$("#_en_val").on('click', function() {

			if ($('#_en_val').is(":checked")) {
				$('#en_val').val(1);
			} else {
				$('#en_val').val(0);
			}
		});

		$("#_sp_val").on('click', function() {

			if ($('#_sp_val').is(":checked")) {
				$('#sp_val').val(1);
			} else {
				$('#sp_val').val(0);
			}
		});

		$("#_spraak_val").on('click', function() {

			if ($('#_spraak_val').is(":checked")) {
				$('#spraak_val').val(1);
			} else {
				$('#spraak_val').val(0);
			}
		});

		$("#_gehoor_val").on('click', function() {

			if ($('#_gehoor_val').is(":checked")) {
				$('#gehoor_val').val(1);
			} else {
				$('#gehoor_val').val(0);
			}
		});

		$("#_gezicht_val").on('click', function() {

			if ($('#_gezicht_val').is(":checked")) {
				$('#gezicht_val').val(1);
			} else {
				$('#gezicht_val').val(0);
			}
		});

		$("#_motoriek_val").on('click', function() {

			if ($('#_motoriek_val').is(":checked")) {
				$('#motoriek_val').val(1);
			} else {
				$('#motoriek_val').val(0);
			}
		});


	}); //End Function
</script>