<?php


	require_once("../classes/spn_leerling.php");
	require_once("../classes/spn_utils.php");

	$l = new spn_leerling();
	$utils = new spn_utils();

	$studentid = "";
	$studentnumber = "";
	$voornamen = "";
	$achternaam = "";
	$geslacht = "";
	$geboortedatum = "";
	$klas = "";

	if(isset($_GET["id"]))
	{
		$detail = $l->liststudentdetail($_GET["id"]);

		if($detail["results"] == 1)
	{
		  $studentid = $detail["studentid"];
		  $studentnumber = $detail["studentnumber"];
		  $voornamen = $detail["voornamen"];
		  $achternaam = $detail["achternaam"];
		  $geslacht = $detail["geslacht"];
		  $geboortedatum = $detail["geboortedatum"];
		  $klas = $detail["klas"];
	}
	else
	{
		$studentid = "N/A";
		  $studentnumber = "N/A";
		  $voornamen = "N/A";
		  $achternaam = "N/A";
		  $geslacht = "N/A";
		  $geboortedatum = "N/A";
		  $klas = "N/A";
	}

	}
	else
	{
		$studentid = "N/A";
		  $studentnumber = "N/A";
		  $voornamen = "N/A";
		  $achternaam = "N/A";
		  $geslacht = "N/A";
		  $geboortedatum = "N/A";
		  $klas = "N/A";
	}


?>

<?php


	require_once("../classes/spn_leerling.php");
	require_once("../classes/spn_utils.php");
	require_once("../classes/spn_contact.php");
	require_once("../classes/spn_social_work.php"); //CODE CaribeDevelopers
	require_once("../classes/spn_mdc.php"); //CODE CaribeDevelopers
    require_once("../classes/spn_event.php"); //CODE CaribeDevelopers
	require_once("../classes/spn_remedial.php"); //CODE CaribeDevelopers
	require_once("../classes/spn_documents.php"); //CODE CaribeDevelopers
	require_once("../config/app.config.php");

	$l = new spn_leerling();
	$utils = new spn_utils();
	$c = new spn_contact();
	$sw = new spn_social_work(); //CODE CaribeDevelopers
	$m = new spn_mdc(); //CODE CaribeDevelopers
    $ev = new spn_event(); //CODE CaribeDevelopers
	$r = new spn_remedial(); //CODE CaribeDevelopers
	$d = new spn_documents(); //CODE CaribeDevelopers


	$baseurl = appconfig::GetBaseURL();

	$leerling_detail = $l->read_leerling_detail_info($_GET["id"],appconfig::GetDummy());
	$contact_detail = $c->list_contacts($_GET["id"],null); //CODE CaribeDevelopers
	$social_work_detail = $sw->get_social_work(0,$_GET["id"],appconfig::GetDummy()); //CODE CaribeDevelopers
	$mdc_detail = $m->get_mdc(0,$_GET["id"],appconfig::GetDummy()); //CODE CaribeDevelopers
	$test_detail = $t->get_test(0,$_GET["id"],appconfig::GetDummy()); //CODE CaribeDevelopers
  $event_detail = $ev->list_event(null,$_GET["id"],appconfig::GetDummy()); //CODE CaribeDevelopers
	$remedial_list = $r->get_remedial(null,$_GET["id"],appconfig::GetDummy()); //CODE CaribeDevelopers
	$document_list = $d->listdocuments($_GET["id"],""); //CODE CaribeDevelopers

	$studentid = "";
	$studentnumber = "";
	$voornamen = "";
	$achternaam = "";
	$geslacht = "";
	$geboortedatum = "";
	$klas = "";

	if(isset($_GET["id"]))
	{
		$detail = $l->liststudentdetail($_GET["id"]);



		if($detail["results"] == 1)
	{
		  $studentid = $detail["studentid"];
		  $studentnumber = $detail["studentnumber"];
		  $voornamen = $detail["voornamen"];
		  $achternaam = $detail["achternaam"];
		  $geslacht = $detail["geslacht"];
		  $geboortedatum = $detail["geboortedatum"];
		  $klas = $detail["klas"];
	}
	else
	{
		$studentid = "N/A";
		  $studentnumber = "N/A";
		  $voornamen = "N/A";
		  $achternaam = "N/A";
		  $geslacht = "N/A";
		  $geboortedatum = "N/A";
		  $klas = "N/A";
	}

	}
	else
	{
		$studentid = "N/A";
		  $studentnumber = "N/A";
		  $voornamen = "N/A";
		  $achternaam = "N/A";
		  $geslacht = "N/A";
		  $geboortedatum = "N/A";
		  $klas = "N/A";
	}


?>
<?php include '../header.php'; ?>
<div class="row">
	<div class="col-md-12 full-inset">
		<div class="primary-bg-color brd-full">
			<div class="box">
				<div class="box-title full-inset brd-bottom">
					<div class="row">
						<h2 class="col-md-12"><?php print $voornamen . chr(32). $achternaam ?></h2>
					</div>
				</div>
				<div class="box-content full-inset sixth-bg-color">
					<div class="row">
						<div class="col-md-1">
							<img src=
							<?php
									if (file_exists("profile_students/". $studentnumber .".jpg"))
										print "profile_students/". $studentnumber .".jpg";
									else
										print "profile_students/unknow.png";

								?>
								class="img-thumbnail img-responsive" alt="{alternative}">
						</div>
						<div class="col-md-11">
							<table class="table table-bordered table-colored">
								<tbody>
									<tr>
										<td><strong>Student nr.</strong></td>
										<td><?php print $studentnumber ?></td>
										<td><strong>Klas</strong></td>
										<td id="klas" name="klas"><?php print $klas ?></td>
									</tr>
									<tr>
										<td><strong>Achternaam</strong></td>
										<td><?php print $achternaam ?></td>
										<td><strong>Voornamen</strong></td>
										<td><?php print $voornamen ?></td>
									</tr>
									<tr>
										<td><strong>Geslacht</strong></td>
										<td><?php print $geslacht ?></td>
										<td><strong>Geboortedatum</strong></td>
										<td><?php print ($utils->convertfrommysqldate($geboortedatum) != false && $geboortedatum != "0000-00-00" ? htmlentities($utils->convertfrommysqldate($geboortedatum)) : htmlentities("N/A")) ?></td>

									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="row double-inset">
						<div class="custom-tab nav-vertical">
							<div class="col-md-2 reset-pdng-right">
								<ul role="tablist" class="nav nav-tabs nav-pills tabs-left hidden-xs">
									<li class="active" role="presentation">
										<a data-toggle="tab" role="tab" aria-controls="personalia" href="#personalia" class="btn btn-default btn-m-w btn-m-h">
											Personalia
										</a>
									</li>
									<li class="disable" role="presentation">
										<a data-toggle="tab" role="tab" aria-controls="contact" href="#contactpersoon" class="btn btn-default btn-m-w btn-m-h">
											Contact Persoon
										</a>
									</li>
									<li class="disable" role="presentation">
										<a data-toggle="tab" role="tab" aria-controls="logboek" href="#logboek" class="btn btn-default btn-m-w btn-m-h">
											Logboek
										</a>
									</li>
									<li class="disable" role="presentation">
										<a data-toggle="tab" role="tab" aria-controls="rapport" href="#rapport" class="btn btn-default btn-m-w btn-m-h">
											Rapport
										</a>
									</li>
									<li class="disable" role="presentation">
										<a data-toggle="tab" role="tab" aria-controls="cijfers" href="#cijfers" class="btn btn-default btn-m-w btn-m-h">
											Cijfers
										</a>
									</li>
									<li class="disable" role="presentation">
										<a data-toggle="tab" role="tab" aria-controls="houding" href="#houding" class="btn btn-default btn-m-w btn-m-h">
											Houding
										</a>
									</li>
									<li class="disable" role="presentation">
										<a data-toggle="tab" role="tab" aria-controls="verzuim" href="#verzuim" class="btn btn-default btn-m-w btn-m-h">
											Verzuim
										</a>
									</li>
									<li class="disable" role="presentation">
										<a data-toggle="tab" role="tab" aria-controls="socemotioneel" href="#socemotioneel" class="btn btn-default btn-m-w btn-m-h">
											Social emotioneel
										</a>
									</li>
									<li class="disable" role="presentation">
										<a data-toggle="tab" role="tab" aria-controls="extra" href="#documenten" class="btn btn-default btn-m-w btn-m-h">
											Documenten
										</a>
									</li>
									<li class="disable" role="presentation">
										<a data-toggle="tab" role="tab" aria-controls="smw" href="#smw" class="btn btn-default btn-m-w btn-m-h">
											SMW
										</a>
									</li>
									<li class="disable" role="presentation">
										<a data-toggle="tab" role="tab" aria-controls="mdc" href="#mdc" class="btn btn-default btn-m-w btn-m-h">
											MDC
										</a>
									</li>
									<li class="disable hidden" role="presentation">
										<a data-toggle="tab" role="tab" aria-controls="test" href="#test" class="btn btn-default btn-m-w btn-m-h">
											TEST
										</a>
									</li>
									<li class="disable" role="presentation">
										<a data-toggle="tab" role="tab" aria-controls="remedial" href="#remedial" class="btn btn-default btn-m-w btn-m-h">
											REMEDIAL
										</a>
									</li>
									<li class="disable" role="presentation">
										<a data-toggle="tab" role="tab" aria-controls="avi" href="#avi" class="btn btn-default btn-m-w btn-m-h">
											AVI
										</a>
									</li>
								</ul>
							</div>
							<div class="col-md-10 reset-pdng-left">
								<div class="tab-content sixth-bg-color full-inset">
									<div role="tabpanel" class="tab-pane active" id="personalia">
										<!-- CaribeDevelopers: Dinamic tables to show details of student -->

										<?php print $leerling_detail; ?>

										<!--  CaribeDevelopers: End dinamic tables to show details of student-->
									</div>
									<div role="tabpane2" class="tab-pane" id="contactpersoon">
										<h2 class="primary-color mrg-bottom">Contact persoon</h2>
										<div class="row mrg-bottom">
											<div class="col-md-8" id="div_table_contact" name="div_table_contact">
												<?php echo $contact_detail; ?>
											</div>
											<div class="col-md-4 full-inset">
												<div class="primary-bg-color brd-full">
													<div class="box">
														<div class="box-title full-inset brd-bottom">
															<h3>Contacts</h3>
														</div>
														<div class="sixth-bg-color box content full-inset">
															<form class="form-horizontal align-left" role="form" name="form-addContact" id="form-addContact">
																<div role="tabpanelcontact" class="tab-pane active" id="tab1">
																	<div class="alert alert-danger hidden">
																		<p><i class="fa fa-warning"></i> Er zijn lege velden die ingevuld moet worden!</p>
																	</div>
																	<div class="alert alert-info hidden">
																		<p><i class="fa fa-check"></i> Uw bericht is verzonden!</p>
																	</div>
																	<div class="alert alert-error hidden">
																		<p><i class="fa fa-warning"></i> Excuseer me, was er een fout in het verzenden van berichten!</p>
																	</div>
																	<fieldset>
																		<input type="hidden" id="id_student" name="id_student" value=<?php  echo $_GET["id"]; ?> >
																		<input type="hidden" id="id_contact" name="id_contact" value="0">
																		<div class="form-group">
																			<label class="col-md-4 control-label" for="">Tutor*</label>
																			<div class="col-md-8">
																				<select id="tutor" name="tutor" class="form-control">
																					<option selected value="">Selecteer Tutor</option>
																					<option  value="Yes">Ja</option>
																					<option  value="No">Nee</option>
																				</select>
																			</div>
																		</div>

																		<div class="form-group">
																			<label class="col-md-4 control-label" for="">Type*</label>
																			<div class="col-md-8">
																				<select id="type" name="type" class="form-control">
																					<option  value="">Selecteer een Type</option>
																					<option  value="Mother">Moeder</option>
																					<option  value="Father">Vader</option>
																					<option  value="Uncle">Oom</option>
																					<option  value="Unt">Tante</option>
																					<option  value="Brother">Broer</option>
																					<option  value="Sister">Zuz</option>
																					<option  value="Grand Ma">Groot Moeder</option>
																					<option  value="Grand Pa">Groot Vader</option>
																					<option  value="Mother">Moeder</option>
																					<option  value="Other">Other</option>
																				</select>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-md-4 control-label">ID Nummer</label>
																			<div class="col-md-8">
																				<input id="id_number_contact" class="form-control" type="text" name="id_number_contact"/>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-md-4 control-label">Naam *</label>
																			<div class="col-md-8">
																				<input id="full_name" class="form-control" type="text" name="full_name"/>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-md-4 control-label">Adres</label>
																			<div class="col-md-8">
																				<input id="address" class="form-control" type="text" name="address"/>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-md-4 control-label">Email</label>
																			<div class="col-md-8">
																				<input id="email" class="form-control" type="email" name="email"/>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-md-4 control-label">Mobiel</label>
																			<div class="col-md-8">
																				<input id="mobile_phone" class="form-control input-mask" data-mask="eg: (000) 000-0000" placeholder="000-00-0000000" type="text" name="mobile_phone"/>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-md-4 control-label">Huis telefoon</label>
																			<div class="col-md-8">
																				<input id="home_phone" class="form-control input-mask" data-mask="eg: (000) 000-0000" placeholder="000-00-0000000" type="text" name="home_phone"/>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-md-4 control-label">Werk Telefoon</label>
																			<div class="col-md-8">
																				<input id="work_phone" class="form-control input-mask" data-mask="eg: (000) 000-0000" placeholder="000-00-0000000" type="text" name="work_phone"/>
																			</div>
																		</div>
																		<div class="form-group fg-line">
																			<label class="col-md-4 control-label">Werk Ext. </label>
																			<div class="col-md-8">
																				<input id="work_phone_ext" class="form-control input-mask" data-mask="000-00-0000000" placeholder="0000" name="work_phone_ext" type="text" name= ="work_phone_ext" />
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-md-4 control-label">Company/Work</label>
																			<div class="col-md-8">
																				<input id="company" class="form-control" type="text" name="company"/>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-md-4 control-label">Positie</label>
																			<div class="col-md-8">
																				<input id="position_company" class="form-control" type="text" name="position_company"/>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-md-4 control-label">Observations</label>
																			<div class="col-md-8">
																				<textarea id="observation" class="form-control" name="observation" type="text " placeholder="Enter observation here..."></textarea>
																			</div>
																		</div>
																		<!-- <div class="form-group">
																																											<label class="col-md-4 control-label">Observations</label>
																																											<div class="col-md-8">
																																													<input id="observations" class="form-control" type="text" name="observations"/>
																																											</div>
																																									</div> -->
																		<!-- AQUI ME TRAIGO EL ID DE ESTUDIANTE DE LEERLING-->




																																									<!-- <input type="hidden" class="form-control" name="getidstudent" value="<?php echo $studentid;?>">
																																									<input type="hidden" name="baseurl"value="<?php echo $baseurl;?>"> -->

																		<div class="form-group full-inset">

																			<button type="submit" class="btn btn-primary btn-m-w pull-right mrg-left" id="btn-add-contact">OPSLAAN</button>
																			<button type="reset" class="btn btn-danger btn-m-w pull-right mrg-left" id="btn-clear-contact">CLEAR</button>

																		</div>

																	</fieldset>
																</div>
															</form>
														</div>
													</div>
												</div>
											</div>
										</div>

									</div>
									<div role="tabpane3" class="tab-pane" id="logboek">
										<h2 class="primary-color mrg-bottom">Events</h2>
										<div class="row mrg-bottom">
											<div class="col-md-8" id="div_table_event" name="div_table_event">
												<?php echo $event_detail; ?>
											</div>
											<div class="col-md-4 full-inset">
													<div class="primary-bg-color brd-full">
															<div class="box">
																	<div class="box-title full-inset brd-bottom">
																			<h3>New Event</h3>
																	</div>
																	<div class="sixth-bg-color box content full-inset">
																		<form class="form-horizontal align-left" role="form" name="form-addevent" id="form-addevent">
																			<div role="tabpanelevent" class="tab-pane active" id="tab1">
																				<div class="alert alert-danger hidden">
																					<p><i class="fa fa-warning"></i> Er zijn lege velden die ingevuld moet worden!</p>
																				</div>
																				<div class="alert alert-info hidden">
																					<p><i class="fa fa-check"></i> Uw bericht is verzonden!</p>
																				</div>
																				<div class="alert alert-error hidden">
																					<p><i class="fa fa-warning"></i> Excuseer me, was er een fout in het verzenden van berichten!</p>
																				</div>
																					<fieldset>
																							<div class="form-group">
																									<label class="col-md-4 control-label">Event Date</label>
																									<div class="col-md-8">
																											<div class="input-group date">
																													<input type="text" value="" placeholder="" id="event_date" class="form-control calendar" name="event_date">
																													<span class="input-group-addon"><i class="fa fa-calendar default-primary-color"></i></span>
																											</div>
																									</div>
																							</div>
																							<div class="form-group">
																									<label class="col-md-4 control-label">Due Date</label>
																									<div class="col-md-8">
																											<div class="input-group date">
																													<input type="text" value="" placeholder="" id="due_date" class="form-control calendar" name="due_date">
																													<span class="input-group-addon"><i class="fa fa-calendar default-primary-color"></i></span>
																											</div>
																									</div>
																							</div>
																							<div class="form-group">
																									<label class="col-md-4 control-label">Reden</label>
																									<div class="col-md-8">
																											<input id="reason" type="text" name="reason" class="form-control">
																									</div>
																							</div>
																							<div class="form-group">
																									<label class="col-md-4 control-label">Aanwezig</label>
																									<div class="col-md-8">
																											<input id="involved" type="text" name="involved" class="form-control">
																									</div>
																							</div>
																							<div class="form-group">
																									<label class="col-md-4 control-label">Observeren</label>
																									<div class="col-md-8">
																											<textarea id="observation_event" class="form-control" name="observation_event" type="text"  placeholder="Enter observation here..."></textarea>
																									</div>
																							</div>
																							<div class="form-group">
																									<label class="col-md-4 control-label" for="">Prive</label>
																									<div class="col-md-8">
																											<label>
																													<input type="radio" name="event_private" id="event_private" value="1" checked="checked"> Ja

																											</label>

																											<label>
																													<input type="radio" name="event_private" id="event_no_private" value="0"> Nee

																											</label>
																									</div>
																							</div>

																							<div class="form-group">
																									<label class="col-md-4 control-label" for="">Belangrijk</label>
																									<div class="col-md-8">
																											<label>
																													<input type="radio" name="important_notice" id="important_notice" value="1" checked="checked"> Ja

																											</label>
																											<label>
																													<input type="radio" name="important_notice" id="no_important_notice" value="0"> Nee

																											</label>
																									</div>
																							</div>
																							<input type="hidden" id="important_notice_selected" name="important_notice_selected" class="form-control">\
																							<input type="hidden" id="event_private_selected" name="event_private_selected" class="form-control">
																							<input id="id_event" type="hidden" name="id_event" class="form-control" value="0">
																							<input id="id_student" type="hidden" name="id_student" class="form-control" value=<?php  echo $_GET["id"]; ?>>

																							<input type="submit" class="btn btn-primary btn-m-w  pull-right mrg-left" id="btn-add-event" value="Save">
																							<input type="reset" class="btn btn-danger btn-m-w pull-right mrg-left" id="btn-clear-event" value="Clear">

																					</fieldset>
																			</div>
																		</form>
																	</div>
															</div>
													</div>
											</div>
										</div>
									</div>
									<div role="tabpane4" class="tab-pane" id="rapport">
										<h2 class="primary-color mrg-bottom">Rapport</h2>
									</div>
									<div role="tabpane5" class="tab-pane" id="cijfers">
										<h2 class="primary-color mrg-bottom">Cijfers</h2>
										<?php include 'leerlingdetail_cijfer.php'; ?>
									</div>
									<div role="tabpane6" class="tab-pane" id="houding">
										<h2 class="primary-color mrg-bottom">Houding</h2>
										<?php include 'leerlingdetail_cijfer.php'; ?>
									</div>
									<div role="tabpane7" class="tab-pane" id="verzuim">
										<h2 class="primary-color mrg-bottom">Verzuim</h2>
										<?php include 'leerlingdetail_cijfer.php'; ?>
									</div>
									<div role="tabpane8" class="tab-pane" id="socemotioneel">
										<h2 class="primary-color mrg-bottom">Sociaal Emotioneel</h2>
									</div>
									<div role="tabpane9" class="tab-pane" id="documenten">
										<h2 class="primary-color mrg-bottom">Documenten</h2>
										<!--  CODE CaribeDevelopers Document -->
										<div class="row mrg-bottom">
											<div class="col-md-8" id="div_table_document">
												<!--  Call spn DOCUMENT -->
												<?php echo $document_list; ?>
											</div>
											<div class="col-md-4 full-inset">
												<div class="primary-bg-color brd-full">
													<div class="box">
														<div class="box-title full-inset brd-bottom">
															<h3>Upload</h3>
														</div>
														<div class="sixth-bg-color box content full-inset">
															<form class="form-horizontal align-left" role="form" name="form-document" id="form-document">
																<div role="tabpaneldocument" class="tab-pane active" id="tab1">
																	<div class="alert alert-danger hidden">
																		<p><i class="fa fa-warning"></i> Er zijn lege velden die ingevuld moet worden!</p>
																	</div>
																	<div class="alert alert-info hidden">
																		<p><i class="fa fa-check"></i> The document has been include!</p>
																	</div>
																	<div class="alert alert-error hidden">
																		<p><i class="fa fa-warning"></i> Sorry, there was an error in the include of a document!!</p>
																	</div>
																	<fieldset>
																		<input type="hidden" id="id_student" name="id_student" value=<?php  echo $_GET["id"]; ?> >
																		<input type="hidden" id="id" name="id" value=<?php  print $_GET["id"]; ?> >
																			<div class="form-group">
																				<label class="col-md-4 control-label">Bestand</label>
																				<div class="col-md-8">
																					<input class="form-control" type="file" name="fileToUpload" id="fileToUpload" required>
																				</div>
																			</div>
																			<div class="form-group">
																				<label class="col-md-4 control-label">Description</label>
																				<div class="col-md-8">
																					<textarea class="form-control" id="description" name="description"></textarea>
																				</div>
																			</div>
																		<div class="form-group full-inset">
																			<button type="submit" class="btn btn-primary btn-m-w pull-right mrg-left" id="btn-add-document">Upload</button>
																		</div>
																	</fieldset>
																</div>
															</form>
														</div>
													</div>
												</div>
											</div>
										</div>

										<!--  END CODE CaribeDevelopers Document -->
									</div>
									<div role="tabpane10" class="tab-pane" id="smw">
										<h2 class="primary-color mrg-bottom">SMW</h2>
										<!-- INICIO CODE CaribeDevelopers SOCIAL WORK -->
										<div class="row mrg-bottom">
											<div class="col-md-8" id="div_table_social_work">
												<?php echo $social_work_detail; ?>
											</div>
											<div class="col-md-4 full-inset">
												<div class="primary-bg-color brd-full">
													<div class="box">
														<div class="box-title full-inset brd-bottom">
															<h3>Nieuw sociaal werk</h3>
														</div>
														<div class="sixth-bg-color box content full-inset">
															<form class="form-horizontal align-left" role="form" name="form-addsocialwork" id="form-addsocialwork">
																<div role="tabpanelevent" class="tab-pane active" id="tab1">
																	<div class="alert alert-danger hidden">
																		<p><i class="fa fa-warning"></i> Er zijn lege velden die ingevuld moet worden!</p>
																	</div>
																	<div class="alert alert-info hidden">
																		<p><i class="fa fa-check"></i> Uw bericht is verzonden!</p>
																	</div>
																	<div class="alert alert-error hidden">
																		<p><i class="fa fa-warning"></i> Excuseer me, was er een fout in het verzenden van berichten!</p>
																	</div>
																	<fieldset>
																		<input type="hidden" id="id_student" name="id_student" value=<?php  echo $_GET["id"]; ?> >
																		<input type="hidden" id="id_social_work" name="id_social_work" value="0">
																		<div class="form-group">
																			<input type="hidden" id="id_student" name="id_student" value=<?php  echo $_GET["id"]; ?> />
																			<label class="col-md-4 control-label" for="">School Year</label>
																			<div class="col-md-8">
																				<select id="social_work_school_year" name="social_work_school_year" class="form-control">
																					<option selected value="2015">2015</option>
																					<option selected value="2016">2016</option>
																				</select>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-md-4 control-label">Datum</label>
																				<div class="col-md-8">
																					<div class="input-group date">
																							<input id="social_work_date" type="text" value="" placeholder="" class="form-control calendar" name="social_work_date">
																							<span class="input-group-addon"><i class="fa fa-calendar default-primary-color"></i></span>
																					</div>
																				</div>
																		</div>
																		<!-- Reason -->
																		<div class="form-group">
																			<label class="col-md-4 control-label" for="">Reason</label>
																			<div class="col-md-8">
																				<select id="social_work_reason" name="social_work_reason" class="form-control">
																					<option selected value="Reason 1">Reden 1</option>
																					<option selected value="Reason 2">Reden 2</option>
																					<option selected value="Reason 3">Reden 3</option>
																				</select>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-md-4 control-label">Class</label>
																			<div class="col-md-8">
																				<input id="social_work_class" type="text" name="social_work_class" class="typeahead form-control" placeholder=<?php print $klas ?> disabled>
																			</div>
																		</div>
																		<input id="social_work_class_hidden" name="social_work_class_hidden" type="hidden">
																		<div class="form-group">
																			<label class="col-md-4 control-label">Observeren</label>
																			<div class="col-md-8">
																				<textarea id="social_work_observation" class="form-control" name="social_work_observation" type="text"  placeholder="Enter observation here..."></textarea>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-md-4 control-label" for="">Pending</label>
																			<div class="col-md-8">
																				<label>
																						 <input type="radio" name="social_work_pending" id="social_work_pending" value="1" checked> Ja
																						 <i class="social_pending"></i>
																			 </label>
																				 <label>
																						 <input type="radio" name="social_work_pending" id="social_work_no_pending" value="0"> Nee
																						 <i class="social_pending"></i>
																				 </label>
																			 </div>
																			 <input id="social_work_pending_selected" name="social_work_pending_selected" type="hidden">
																		 </div>

																		<div class="form-group full-inset">
																			<button type="submit" class="btn btn-primary btn-m-w pull-right mrg-left" id="btn-add-social-work-list">OPSLAAN</button>
																			<button type="reset" class="btn btn-danger btn-m-w pull-right mrg-left" id="btn-clear-social-work">Clear</button>
																		</div>
																	</fieldset>
																</div>
															</form>
														</div>
													</div>
												</div>
											</div>
										</div>
										<!-- FIN CODE CaribeDevelopers SOCIAL WORK -->
									</div>
									<div role="tabpane11" class="tab-pane" id="mdc">
										<!-- Begin code CaribeDevelopers MDC -->
										<h2 class="primary-color mrg-bottom">MDC</h2>
										<div class="row mrg-bottom">
											<div class="col-md-8" id="div_table_mdc">
												<!--  Call spn MDC -->
												<?php echo $mdc_detail; ?>
											</div>

											<div class="col-md-4 full-inset">
												<div class="primary-bg-color brd-full">
													<div class="box">
														<div class="box-title full-inset brd-bottom">
															<h3>New MDC</h3>
														</div>
														<div class="sixth-bg-color box content full-inset">
															<form class="form-horizontal align-left" role="form" name="form-add-mdc-registration-list" id="form-add-mdc-registration-list">
																<div role="tabpanelmdc" class="tab-pane active" id="tab1">
																	<div class="alert alert-danger hidden">
																		<p><i class="fa fa-warning"></i> Er zijn lege velden die ingevuld moet worden!</p>
																	</div>
																	<div class="alert alert-info hidden">
																		<p><i class="fa fa-check"></i> Uw bericht is verzonden!</p>
																	</div>
																	<div class="alert alert-error hidden">
																		<p><i class="fa fa-warning"></i> Excuseer me, was er een fout in het verzenden van berichten!</p>
																	</div>
																	<fieldset>
																		<input type="hidden" id="id_student" name="id_student" value=<?php  echo $_GET["id"]; ?> >
																		<input type="hidden" id="id_mdc" name="id_mdc" value="0">
																		<div class="form-group">
																			<label class="col-md-4 control-label" for="">School Year</label>
																			<div class="col-md-8">
																				<select id="mdc_school_year" name="mdc_school_year" class="form-control">
																					<option selected value="2015">2015</option>
																					<option selected value="2016">2016</option>
																				</select>
																			</div>
																		</div>
																		<!-- datepicker -->
																		<div class="form-group">
																			<label class="col-md-4 control-label">Datum</label>
																				<div class="col-md-8">
																					<div class="input-group date">
																							<input id="mdc_date" type="text" value="" placeholder="" id="mdc_date" class="form-control calendar" name="mdc_date">
																							<span class="input-group-addon"><i class="fa fa-calendar default-primary-color"></i></span>
																					</div>
																				</div>
																		</div>
																		<!-- Reason -->
																		<div class="form-group">
																			<label class="col-md-4 control-label" for="">Reason</label>
																			<div class="col-md-8">
																				<select id="mdc_reason" name="mdc_reason" class="form-control">
																					<option selected value="Reason 1">Reden 1</option>
																					<option selected value="Reason 2">Reden 2</option>
																					<option selected value="Reason 3">Reason 3</option>
																				</select>
																			</div>
																			<input type="hidden" id="mdc_reason_text" name="mdc_reason_text" value="">
																		</div>
																		<!-- Class -->
																		<div class="form-group">
																			<label class="col-md-4 control-label">Class</label>
																			<div class="col-md-8">
																				<!-- <input id="mdc_class" type="text" name="mdc_class" class="typeahead form-control" value="States of USA" disabled> -->
																				<input id="mdc_class" type="text" name="mdc_class"  class="typeahead form-control" placeholder=<?php print $klas ?> disabled>
																				<input id="mdc_class_value" type="hidden" name="mdc_class_value"  value="">
																			</div>
																		</div>
																		<!-- Observations -->
																		<div class="form-group">
																			<label class="col-md-4 control-label">Observeren</label>
																			<div class="col-md-8">
																				<textarea id="mdc_observation" class="form-control" name="mdc_observation" type="text" placeholder="Enter observation here..."></textarea>
																			</div>
																		</div>

																		<div class="form-group">
																			<label class="col-md-4 control-label" for="">Pending</label>
																			<div class="col-md-8">
																				<label>
																						 <input type="radio" id="mdc_pending" name="mdc_pending" value="1" checked> Ja
																						 <i class="pending"></i>
																			 </label>
																				 <label>
																						 <input type="radio" id="mdc_no_pending" name="mdc_pending" value="0"> Nee
																						 <i class="pending"></i>
																				 </label>
																			 </div>
																			 <input id="mdc_pending_selected" name="mdc_pending_selected" type="hidden">
																		 </div>

																		<div class="form-group full-inset">
																			<button type="submit" class="btn btn-primary btn-m-w pull-right mrg-left" id="btn-add-mdc-registration-list">OPSLAAN</button>
																			<button type="reset" class="btn btn-danger btn-m-w pull-right mrg-left" id="btn-clear-mdc">Clear</button>
																		</div>
																	</fieldset>
																</div>
															</form>
														</div>
													</div>
												</div>
											</div>
										</div>
										<!-- End code CaribeDevelopers MDC -->
									</div>
									<div role="tabpane12" class="tab-pane" id="test">
										<!-- Begin code CaribeDevelopers Test -->
										<h2 class="primary-color mrg-bottom">Test</h2>
										<div class="row mrg-bottom">
											<div class="col-md-8" id="div_table_test">
												<!--  Call spn TEST -->
												<?php echo $test_detail; ?>
											</div>

											<div class="col-md-4 full-inset">
												<div class="primary-bg-color brd-full">
													<div class="box">
														<div class="box-title full-inset brd-bottom">
															<h3>New Test</h3>
														</div>
														<div class="sixth-bg-color box content full-inset">
															<form class="form-horizontal align-left" role="form" name="form-add-test-registration-list" id="form-add-test-registration-list">
																<div role="tabpaneltest" class="tab-pane active" id="tab1">
																	<div class="alert alert-danger hidden">
																		<p><i class="fa fa-warning"></i> Er zijn lege velden die ingevuld moet worden!</p>
																	</div>
																	<div class="alert alert-info hidden">
																		<p><i class="fa fa-check"></i> Uw bericht is verzonden!</p>
																	</div>
																	<div class="alert alert-error hidden">
																		<p><i class="fa fa-warning"></i> Excuseer me, was er een fout in het verzenden van berichten!</p>
																	</div>
																	<fieldset>
																		<input type="hidden" id="id_student" name="id_student" value=<?php  echo $_GET["id"]; ?> >
																		<input type="hidden" id="id_test" name="id_test" value="0">
																		<!-- datepicker -->
																		<div class="form-group">
																			<label class="col-md-4 control-label">Date</label>
																				<div class="col-md-8">
																					<div class="input-group date">
																							<input id="test_date" type="text" value="" placeholder="" id="test_date" class="form-control calendar" name="test_date">
																							<span class="input-group-addon"><i class="fa fa-calendar default-primary-color"></i></span>
																					</div>
																				</div>
																		</div>
																		<!-- Type -->
																		<div class="form-group">
																			<label class="col-md-4 control-label" for="">Type</label>
																			<div class="col-md-8">
																				<input id="test_type" type="text" name="test_type"  class="typeahead form-control" value="">
																			</div>
																		</div>
																		<!-- Class -->
																		<div class="form-group">
																			<label class="col-md-4 control-label">Class</label>
																			<div class="col-md-8">
																				<!-- <input id="test_class" type="text" name="test_class" class="typeahead form-control" value="States of USA" disabled> -->
																				<input id="test_class" type="text" name="test_class"  class="typeahead form-control" placeholder=<?php print $klas ?> disabled>
																				<input id="test_class_value" type="hidden" name="test_class_value"  value="">
																			</div>
																		</div>
																		<!-- Observations -->
																		<div class="form-group">
																			<label class="col-md-4 control-label">Observations</label>
																			<div class="col-md-8">
																				<textarea id="test_observation" class="form-control" name="test_observation" type="text" placeholder="Enter observation here..."></textarea>
																			</div>
																		</div>

																		<div class="form-group full-inset">
																			<button type="submit" class="btn btn-primary btn-m-w pull-right mrg-left" id="btn-add-test-registration-list">Save</button>
																			<button type="reset" class="btn btn-danger btn-m-w pull-right mrg-left" id="btn-clear-test">Clear</button>
																		</div>
																	</fieldset>
																</div>
															</form>
														</div>
													</div>
												</div>
											</div>
										</div>
										<!-- End code CaribeDevelopers Test -->
									</div>
									<div role="tabpane14" class="tab-pane" id="remedial">
										<!-- Begin code CaribeDevelopers remedial -->
										<div id="div_remedial">
											<h2 class="primary-color mrg-bottom">Remedial</h2>
											<div class="row mrg-bottom">
												<div class="col-md-8" id="div_table_remedial">
													<!--  Call spn_remedial-->
													<?php echo $remedial_list; ?>
												</div>
												<div class="col-md-4 full-inset">
														<div class="primary-bg-color brd-full">
															<div class="box">
																<div class="box-title full-inset brd-bottom">
																	<h3>New Remedial</h3>
																</div>
																<div class="sixth-bg-color box content full-inset">
																	<form class="form-horizontal align-left" role="form" name="form-remedial" id="form-remedial">
																		<div role="tabpanelremedial" class="tab-pane active" id="tab1">
																			<div class="alert alert-danger hidden">
																				<p><i class="fa fa-warning"></i> Er zijn lege velden die ingevuld moet worden!</p>
																			</div>
																			<div class="alert alert-info hidden">
																				<p><i class="fa fa-check"></i> The Remedial has been created!</p>
																			</div>
																			<div class="alert alert-error hidden">
																				<p><i class="fa fa-warning"></i> Sorry, wthere was an error in the creation of Remedial!!</p>
																			</div>
																			<div class="alert alert-constrain hidden">
																				<p><i class="fa fa-warning"></i> Sorry , there are details associated with this Remedial!</p>
																			</div>
																			<div class="alert alert-date hidden">
																				<p><i class="fa fa-warning"></i> Sorry , begin date must be less than the end date!</p>
																			</div>
																			<fieldset>
																				<input type="hidden" id="id_student" name="id_student" value=<?php  print $_GET["id"]; ?> >
																				<input type="hidden" id="id_remedial" name="id_remedial" value="0">
																				<div class="form-group">
																					<label class="col-md-4 control-label" for="">School Year</label>
																					<div class="col-md-8">
																						<select id="remedial_school_year" name="remedial_school_year" class="form-control">
																							<option selected value="2015">2015</option>
																							<option selected value="2016">2016</option>
																						</select>
																					</div>
																				</div>
																				<!-- Begin Date -->
																				<div class="form-group">
																					<label class="col-md-4 control-label">Begin datum</label>
																					<div class="col-md-8">
																						<div class="input-group date">
																							<input type="text" value="" placeholder="" id="remedial_begin_date" class="form-control calendar" name="remedial_begin_date">
																							<span class="input-group-addon"><i class="fa fa-calendar default-primary-color"></i></span>
																						</div>
																					</div>
																				</div>
																				<!--  End Date -->
																				<div class="form-group">
																					<label class="col-md-4 control-label">End datum</label>
																					<div class="col-md-8">
																						<div class="input-group date">
																							<input type="text" value="" placeholder="" id="remedial_end_date" class="form-control calendar" name="remedial_end_date">
																							<span class="input-group-addon"><i class="fa fa-calendar default-primary-color"></i></span>
																						</div>
																					</div>
																				</div>
																				<!-- Subject -->
																				<div class="form-group">
																					<label class="col-md-4 control-label" for="">Subject</label>
																					<div class="col-md-8">
																						<select id="remedial_subject" name="remedial_subject" class="form-control">
																							<option selected value="Option 1">Optie 1</option>
																							<option selected value="Option 2">Optie 2</option>
																							<option selected value="Option 3">Optie 3</option>
																						</select>
																					</div>
																					<input type="hidden" id="remedial_subject_text" name="remedial_subject_text" value="">
																				</div>
																				<!-- Class -->
																				<div class="form-group">
																					<label class="col-md-4 control-label">Class</label>
																					<div class="col-md-8">
																						<input id="remedial_class" type="text" name="remedial_class"  class="typeahead form-control" placeholder=<?php print $klas ?> disabled>
																						<input id="remedial_class_value" type="hidden" name="remedial_class_value"  value=<?php print $klas ?>>
																					</div>
																				</div>
																				<!-- Docent  -->
																				<div class="form-group">
																					<label class="col-md-4 control-label">Docent</label>
																					<div class="col-md-8">
																						<!-- <input id="mdc_class" type="text" name="mdc_class" class="typeahead form-control" value="States of USA" disabled> -->
																						<input id="remedial_docent" type="text" name="remedial_docent"  class="typeahead form-control" placeholder=<?php print $fullname ?> disabled>
																						<input id="remedial_docent_value" type="hidden" name="remedial_docent_value"  value=<?php print $fullname ?>>
																					</div>
																				</div>
																				<!-- Buttons -->
																				<div class="form-group full-inset">
																					<button type="submit" class="btn btn-primary btn-m-w pull-right mrg-left" id="btn-add-remedial">Save</button>
																					<button type="reset" class="btn btn-danger btn-m-w pull-right mrg-left" id="btn-clear-remedial">Reset</button>
																				</div>
																			</fieldset>
																		</div>
																	</form>
																</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<!-- Begin code CaribeDevelopers Remedial Detail-->
										<div id="div_remedial_detail">
											<h2 class="primary-color mrg-bottom">Remedial detail</h2>
											<a id='return-remedial' href='#' ><i class="fa fa-angle-double-left quaternary-color"></i> Terug</a>
											<div class="row mrg-bottom">
												<div class="col-md-8" id="div_table_remedial_detail">
													<!--  Call spn_remedial-->
													<?php echo $remedial_detail_list; ?>
												</div>
												<div class="col-md-4 full-inset">
													<div class="primary-bg-color brd-full">
														<div class="box">
															<div class="box-title full-inset brd-bottom">
																<h3>New Remedial detail</h3>
															</div>
															<div class="sixth-bg-color box content full-inset">
																<form class="form-horizontal align-left" role="form" name="form-remedial-detail" id="form-remedial-detail">
																	<div role="tabpanelremedial" class="tab-pane active" id="tab1">
																		<div class="alert alert-danger hidden">
																			<p><i class="fa fa-warning"></i> Er zijn lege velden die ingevuld moet worden!</p>
																		</div>
																		<div class="alert alert-info hidden">
																			<p><i class="fa fa-check"></i> The Remedial detail has been created!!</p>
																		</div>
																		<div class="alert alert-error hidden">
																			<p><i class="fa fa-warning"></i> Sorry, there was an error in creating Remedial detail!</p>
																		</div>
																		<fieldset>
																			<input type="hidden" id="id_remedial_post" name="id_remedial_post" value="0">
																			<input type="hidden" id="id_remedial_detail" name="id_remedial_detail" value="0">
																			<!-- datepicker -->
																			<div class="form-group">
																				<label class="col-md-4 control-label">Date</label>
																					<div class="col-md-8">
																						<div class="input-group date">
																								<input id="remedial_detail_date" type="text" value="" placeholder="" class="form-control calendar" name="remedial_detail_date">
																								<span class="input-group-addon"><i class="fa fa-calendar default-primary-color"></i></span>
																						</div>
																					</div>
																			</div>
																			<!-- Observations -->
																			<div class="form-group">
																				 <label class="col-md-4 control-label">Observeren</label>
																					<div class="col-md-8">
																						<textarea id="remedial_detail_observation" class="form-control" name="remedial_detail_observation" type="text"  placeholder="Enter observation here..."></textarea>
																					</div>
																			</div>
																			<div class="form-group full-inset">
																				<button type="submit" class="btn btn-primary btn-m-w pull-right mrg-left" id="btn-add-remedial-detail">Save</button>
																				<button type="reset" class="btn btn-danger btn-m-w pull-right mrg-left" id="btn-clear-remedial-detail">Reset</button>
																			</div>
																		</fieldset>
																	</div>
																</form>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div role="tabpane14" class="tab-pane" id="avi">
										<h2 class="primary-color mrg-bottom">AVI</h2>
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
<!-- INICIO CODE CaribeDevelopers SOCIAL WORK -->
<script type="text/javascript">

    $(function(){
				// CaribeDevelopers: Function to select a row form table Social Work

				$('#dataRequest-social_work tr').click(function(){
					$(this).closest('tr').siblings().children('td, th').css('background-color','');
					$(this).children('td, th').css('background-color','#cccccc');
					$('#id_social_work').val($(this).find("td").eq(4).find("[name='id_social_work']").val());
					$('#social_work_date').val($(this).find("td").eq(1).text());
					$('#social_work_reason').val($(this).find("td").eq(2).text());
					$('#social_work_class').val($(this).find("td").eq(3).text());
					$('#social_work_class_hidden').val($(this).find("td").eq(3).text());
					$('#social_work_observation').val($(this).find("td").eq(4).find("[name='observations_val']").val());
					if ($(this).find("td").eq(4).text() == 'Pending')
					{
						$('#social_work_pending').prop('checked', true);
						$('#social_work_no_pending').prop('checked', false);
					}
					else
					{
						$('#social_work_no_pending').prop('checked', true);
						$('#social_work_pending').prop('checked', false);
					}
					$('#btn-clear-social-work').text("DELETE");

        });

				// CaribeDevelopers: Function to select a row form table MDC
				$('#dataRequest-mdc tr').click(function(){
					$(this).closest('tr').siblings().children('td, th').css('background-color','');
					$(this).children('td, th').css('background-color','#cccccc');
					//alert($(this).find("td").eq(4).find("[name='id_mdc']").val());
					$('#id_mdc').val($(this).find("td").eq(4).find("[name='id_mdc']").val());
					$('#mdc_date').val($(this).find("td").eq(1).text());
					$('#mdc_reason').val($(this).find("td").eq(2).text());
					$('#mdc_class').val($(this).find("td").eq(3).text());
					$('#mdc_observation').val($(this).find("td").eq(4).find("[name='observations_val']").val());
					if ($(this).find("td").eq(4).text() == 'Pending')
					{
						$('#mdc_pending').prop('checked', true);
						$('#mdc_no_pending').prop('checked', false);
					}
					else
					{
						$('#mdc_no_pending').prop('checked', true);
						$('#mdc_pending').prop('checked', false);
					}
					$('#btn-clear-mdc').text("DELETE");

        });

				// CaribeDevelopers: Function to select a row form table Test
				$('#dataRequest-test tr').click(function(){
					$(this).closest('tr').siblings().children('td, th').css('background-color','');
					$(this).children('td, th').css('background-color','#cccccc');
					//alert($(this).find("td").eq(3).find("[name='id_test']").val());
					$('#id_test').val($(this).find("td").eq(3).find("[name='id_test']").val());
					$('#test_date').val($(this).find("td").eq(0).text());
					$('#test_type').val($(this).find("td").eq(1).text());
					$('#test_class').val($(this).find("td").eq(2).text());
					$('#test_observation').val($(this).find("td").eq(3).text());

					$('#btn-clear-test').text("DELETE");

				});

				// CaribeDevelopers: Function to select a row form table contact

				$('#dataRequest-contact tr').click(function(){
					$(this).closest('tr').siblings().children('td, th').css('background-color','');
					$(this).children('td, th').css('background-color','#cccccc');

					$('#id_contact').val($(this).find("td").eq(6).find("[name='id_contact']").val());
					if($(this).find("i").eq(0).hasClass("fa fa-check"))
					{
						$('#tutor').val('Yes');
					}else {
						$('#tutor').val('No');
					}
					$('#type').val($(this).find("td").eq(1).text());
					$('#full_name').val($(this).find("td").eq(2).text());
					$('#mobile_phone').val($(this).find("td").eq(3).text());
					$('#address').val($(this).find("td").eq(4).text());
					$('#company').val($(this).find("td").eq(5).text());
					$('#position_company').val($(this).find("td").eq(6).text());
					$('#email').val($(this).find("td").eq(6).find("[name='email_contact']").val());
					$('#home_phone').val($(this).find("td").eq(6).find("[name='home_phone_contact']").val());
					$('#work_phone').val($(this).find("td").eq(6).find("[name='work_phone_contact']").val());
					$('#work_phone_ext').val($(this).find("td").eq(6).find("[name='work_phone_ext_contact']").val());
					$('#observation').val($(this).find("td").eq(6).find("[name='observations_contact']").val());
					$('#id_number_contact').val($(this).find("td").eq(6).find("[name='id_number_contact']").val());

					$('#btn-clear-contact').text("DELETE");

        });
        /*Jquery Event Caribe Developer*/
				$('#dataRequest-event tr').click(function(){
					$(this).closest('tr').siblings().children('td, th').css('background-color','');
					$(this).children('td, th').css('background-color','#cccccc');

						// alert($(this).find("td").eq(5).text());
						$("#event_date").val($(this).find("td").eq(0).text());
						$("#due_date").val($(this).find("td").eq(1).text());
						$("#reason").val($(this).find("td").eq(2).text());
						$("#involved").val($(this).find("td").eq(3).text());
						$('#observation_event').val($(this).find("td").eq(4).text());
						if($(this).find("td").eq(5).text() == "1")
				    {
							$('#event_private').prop('checked', true);
							$('#event_no_private').prop('checked', false);
				    }else{
							$('#event_private').prop('checked', false);
							$('#event_no_private').prop('checked', true);
				    }
				    if($(this).find("td").eq(6).text() == "1")
				    {
							$('#important_notice').prop('checked', true);
							$('#no_important_notice').prop('checked', false);
				    }else {
							$('#important_notice').prop('checked', false);
							$('#no_important_notice').prop('checked', true);
				    }
						$("#id_event").val($(this).find("td").eq(7).text());
						$("#id_student").val($(this).find("td").eq(8).text());

						$('#btn-clear-event').val("DELETE");
				});
        // $("#btn_add").click(function(){
				// 	/* prevent refresh */
				// 		e.preventDefault();
        //     $.post("ajax/add_event.php",
        //         {
        //             funcion       : 'create_event',
        //             xevent_date   : $("#event_date").val(),
        //             xdute_date	  : $("#due_date").val(),
        //             xreason       : $("#reason").val(),
        //             xinvolved 	  : $("#involved").val(),
        //             xobservation  : $("#observation_event").val(),
        //             xradiopri	  : $('input:radio[name=event_private]:checked').val(),
        //             xradionoti	  : $('input:radio[name=important_notice]:checked').val(),
        //             xidstudent    : '<?php echo $_GET["id"]; ?>' //aquie va el id_student #_GET["id]; desde leerling
				//
        //         },
        //         function(data){
        //             if (data){
				// 								alert(data);
        //                 alert("Event Create");
        //                 window.location = "leerlingdetail.php?id=<?php echo $_GET["id"]?>";
        //             }
        //             else{
				//
        //                 alert("Error");
				//
        //             }
        //         });
				//
				//
        // });


        // $(".accion[title='delete_event']").click(function(event){
				//
        //     //alert($(this).attr('name'));
				//
        //     var r =    confirm("Delete Event?");
        //     if (r==true){
        //         $.post("ajax/delete_event.php",
        //             {
        //                 funcion     : 'delete_event',
        //                 xid_event   : $(this).attr('name')
        //             },
        //             function(data){
        //                 if (data){
        //                     alert("Event Delete!!!");
        //                     window.location =  window.location = "leerlingdetail.php?id=<?php echo $_GET["id"]?>";
        //                 }
        //             });
        //     }
        // });


        // $("#btn_edit").click(function(event){
				//
        //     $.post("ajax/update_event.php",
        //         {
        //             funcion       : 'update_event',
        //             xevent_date   : $("#event_date").val(),
        //             xdute_date	  : $("#due_date").val(),
        //             xreason       : $("#reason").val(),
        //             xinvolved 	  : $("#involved").val(),
        //             xobservation  : $("#observation_event").val(),
        //             xradiopri	  : $('input:radio[name=event_private]:checked').val(),
        //             xradionoti	  : $('input:radio[name=important_notice]:checked').val(),
        //             xidstudent    : $("#id_student").val(),
        //             xid_event     : $("#id_event").val()
				//
				//
        //         },
        //         function(data){
        //             if (data){
				//
        //                 alert("Event Update");
        //                 window.location =  window.location = "leerlingdetail.php?id=<?php echo $_GET["id"]?>";
        //             }
        //             else{
				//
        //                 alert("Error");
				//
        //             }
        //         });



        // });


        /*Fin Jquery Event*/

				// CaribeDevelopers: Function to select a row form table Remedial

				$('#dataRequest-remedial tr').click(function(){
					$(this).closest('tr').siblings().children('td, th').css('background-color','');
					$(this).children('td, th').css('background-color','#cccccc');
					$('#id_remedial').val($(this).find("td").eq(3).find("[name='id_remedial']").val());
					$('#remedial_school_year').val($(this).find("td").eq(2).text());
					$('#remedial_begin_date').val($(this).find("td").eq(3).text());
					$('#remedial_end_date').val($(this).find("td").eq(4).text());
					$('#remedial_subject').val($(this).find("td").eq(0).text());
					$('#id_remedial').val($(this).find("td").eq(4).find("[name='id_remedial']").val());
					$('#id_remedial_post').val($(this).find("td").eq(4).find("[name='id_remedial']").val());
					$('#btn-clear-remedial').text("DELETE");

				});

				// CaribeDevelopers: End code function to selecct a row from table Remedial

		});

		// CaribeDevelopers: Begin function code to view a table Remedial Detail

		function go_remedial_detail(id_remedial_val)
		{

			$('#formAddRemedialDetail').find("[name='id_remedial_post']").val($('#id_remedial').val());
			$('#div_remedial_detail').show();
			$('#div_remedial').hide();

			$.get("ajax/getremedialdetaillist_tabel.php ", {id_remedial : id_remedial_val }, function(data) {
					$('#id_remedial_detail').val("0");
					$('#div_table_remedial_detail').empty();
					$("#div_table_remedial_detail").append(data);
			});

		}

		// CaribeDevelopers: End function code to view a table Remedial Detail

</script>
<!-- FIN CODE CaribeDevelopers SOCIAL WORK -->
