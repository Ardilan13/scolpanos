<?php
require_once "document_start.php";
require_once "classes/spn_leerling.php";
require_once "classes/spn_utils.php";
require_once "classes/spn_contact.php";
require_once "classes/spn_social_work.php"; //CODE CaribeDevelopers
require_once "classes/spn_mdc.php"; //CODE CaribeDevelopers
require_once "classes/spn_event.php"; //CODE CaribeDevelopers
require_once "classes/spn_remedial.php"; //CODE CaribeDevelopers
require_once "classes/spn_documents.php"; //CODE CaribeDevelopers
require_once "classes/spn_houding.php"; //CODE CaribeDevelopers
require_once "classes/spn_verzuim.php"; //CODE CaribeDevelopers
require_once "classes/spn_cijfers.php"; //CODE CaribeDevelopers
require_once "config/app.config.php";
require_once "classes/spn_avi.php"; //CODE CaribeDevelopers
require_once "classes/spn_paymentinvoice.php"; //CODE CaribeDevelopers
$l = new spn_leerling();
$utils = new spn_utils();
$c = new spn_contact();
$sw = new spn_social_work(); //CODE CaribeDevelopers
$m = new spn_mdc(); //CODE CaribeDevelopers
$ev = new spn_event(); //CODE CaribeDevelopers
$r = new spn_remedial(); //CODE CaribeDevelopers
$d = new spn_documents(); //CODE CaribeDevelopers
$h = new spn_houding(); //CODE CaribeDevelopers
$cijfer_spn = new spn_cijfers(); //CODE CaribeDeveloper
$verzium_spn = new spn_verzuim(); //CODE CaribeDeveloper
$a = new spn_avi(); //CODE CaribeDevelopers
$invoyce_spn = new spn_paymentinvoice(); //CODE CaribeDevelopers
$baseurl = appconfig::GetBaseURL();
$leerling_detail = $l->read_leerling_detail_info($_GET["id"], appconfig::GetDummy());
$contact_detail = $c->list_contacts($_GET["id_family"], null); //CODE CaribeDevelopers
//Audit by Caribe Developers
$spn_audit = new spn_audit();
$UserGUID = $_SESSION['UserGUID'];
$spn_audit->create_audit($UserGUID, 'Contact', 'List Contacts', appconfig::GetDummy());

$social_work_detail = $sw->get_social_work($_SESSION['SchoolJaar'], 0, $_GET["id"], appconfig::GetDummy()); //CODE CaribeDevelopers
$mdc_detail = $m->get_mdc($_SESSION['SchoolJaar'], 0, $_GET["id"], appconfig::GetDummy()); //CODE CaribeDevelopers
$event_detail = $ev->list_event($_SESSION['SchoolJaar'], null, $_GET["id"], appconfig::GetDummy()); //CODE CaribeDevelopers
$remedial_list = $r->get_remedial($_SESSION['SchoolJaar'], null, $_GET["id"], appconfig::GetDummy()); //CODE CaribeDevelopers
$document_list = $d->listdocuments($_GET["id"], ""); //CODE CaribeDevelopers
$document_list_new_forms = $d->list_documents_new_forms($_SESSION["schoolname"], $_SESSION["SchoolID"], $_GET["id"]);

if ($_SESSION['SchoolType'] >= 2) {
	$houding_list = $h->listhoudingbystudent_hs($_SESSION['SchoolJaar'], $_GET["id"]); //CODE CaribeDevelopers

} else {
	$houding_list = $h->listhoudingbystudent($_SESSION['SchoolJaar'], $_GET["id"]); //CODE CaribeDevelopers

}
$verzuim_table = $verzium_spn->list_verzuim_by_student($_SESSION['SchoolJaar'], $_GET["id"], appconfig::GetDummy()); //CODE CaribeDevelopers
$cijfer_table = $cijfer_spn->list_cijfers_by_student($_SESSION['SchoolJaar'], $_GET["id"], appconfig::GetDummy()); //CODE CaribeDevelopers
$document_avi = $a->list_avi_by_student($_GET["id"], $_SESSION['SchoolJaar'], appconfig::GetDummy()); //CODE CaribeDevelopers
$invoice_table = $invoyce_spn->list_invoice_by_student($_GET["id"], appconfig::GetDummy()); //CODE CaribeDevelopers
$schooljaar = $_SESSION['SchoolJaar'];
$studentid = "";
$studentnumber = "";
$voornamen = "";
$achternaam = "";
$geslacht = "";
$geboortedatum = "";
$klas = "";
if (isset($_GET["id"])) {
	$detail = $l->liststudentdetail($_GET["id"]);
	if ($detail["results"] == 1) {
		$studentid = $detail["studentid"];
		$studentnumber = $detail["studentnumber"];
		$voornamen = $detail["voornamen"];
		$achternaam = $detail["achternaam"];
		$geslacht = $detail["geslacht"];
		$geboortedatum = $detail["geboortedatum"];
		$klas = $detail["klas"];
	} else {
		$studentid = "N/A";
		$studentnumber = "N/A";
		$voornamen = "N/A";
		$achternaam = "N/A";
		$geslacht = "N/A";
		$geboortedatum = "N/A";
		$klas = "N/A";
	}
} else {
	$studentid = "N/A";
	$studentnumber = "N/A";
	$voornamen = "N/A";
	$achternaam = "N/A";
	$geslacht = "N/A";
	$geboortedatum = "N/A";
	$klas = "N/A";
}
?>
<?php include 'sub_nav.php'; ?>
<style>
	.input_radio {
		padding-right: 50px;
	}
</style>
<div class="push-content-220">
	<?php include 'header.php'; ?>
	<main id="main" role="main">
		<section>
			<div class="container container-fs">
				<div class="row">
					<div class="default-secondary-bg-color col-md-12 inset brd-bottom">
						<h1 class="primary-color">Leerling Details</h1>
						<?php include 'breadcrumb.php'; ?>
					</div>
					<div class="default-secondary-bg-color col-md-12 inset filter-bar brd-bottom">
						<form id="form-ll-details" class="form-inline form-data-retriever" name="filter-vak" role="form">
							<fieldset>
								<div class="form-group">
									<input type="hidden" name="id_student" id="id_student" value=<?php echo $_GET["id"]; ?>>
									<input type="hidden" name="id_family" id="id_family" value=<?php echo $_GET["id_family"]; ?>>
									<label for="studenten_list">Kies Student: </label>
									<select id="studenten_list" name="studenten_list" class="form-control">
										<!-- <option selected>Select One Student</option> -->
									</select>
								</div>
								<div class="form-group">
									<button type="submit" id="btn_leerling_detail_zoeken" name="btn_leerling_detail_zoeken" class="btn btn-primary btn-m-w btn-m-h">zoeken</button>
								</div>
								<!--<div class="form-group">
								<button data-display="data-display" data-ajax-href="ajax/getleerlingdetail_tabel.php" type="submit" id= "btn_leerling_detail_zoeken" name="btn_leerling_detail_zoeken" class="btn btn-primary btn-m-w btn-m-h">zoeken</button>
							</div>-->
							</fieldset>
						</form>
					</div>
				</div>
				<div class="data-display">
					<div class="row">
						<div class="col-md-12 full-inset">
							<div class="primary-bg-color brd-full">
								<div class="box">
									<div class="box-title full-inset brd-bottom">
										<div class="row">
											<h2 class="col-md-12"><?php print $voornamen . chr(32) . $achternaam ?></h2>
										</div>
									</div>
									<div class="box-content full-inset sixth-bg-color">
										<div class="row">
											<div class="col-md-1">

												<?php
												if (file_exists("profile_students/" . $studentnumber . "-" . $_SESSION["SchoolID"])) {
													$class_rotate = '';
													$exif = exif_read_data("profile_students/" . $studentnumber . "-" . $_SESSION["SchoolID"]);
													/* if (isset($exif['Orientation']) && $exif['Orientation'] == '6') {
        // rotate the image 90° clockwise
        $class_rotate = 'rotate-90';
    } else if (isset($exif['Orientation']) && $exif['Orientation'] == '8') {
        // rotate the image 270° clockwise
        // print('Rotate 270');
        $class_rotate = 'rotate-270';
    } */
													print "<img src='profile_students/" . $studentnumber . "-" . $_SESSION["SchoolID"] . "' class='img-thumbnail img-responsive " . $class_rotate . "' alt='{alternative}'>";
												} else {
													print "<img src='profile_students/unknow.png'class='img-thumbnail img-responsive' alt='{alternative}'>";
												}

												?>

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
															<td><?php print($utils->convertfrommysqldate($geboortedatum) != false && $geboortedatum != "0000-00-00" ? htmlentities($utils->convertfrommysqldate($geboortedatum)) : htmlentities("N/A")) ?></td>

														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<!-- GRAFICOS -->

										<div class="box-content full-inset sixth-bg-color">
											<div class="row mrg-bottom">
												<div class="col-md-3">
													<div class="databox primary-bg-color full-inset">
														<div class="demo-section k-content wide">
															<div id="chart_absentie_by_student"></div>
														</div>
													</div>
												</div>
												<div class="col-md-3">
													<div class="databox primary-bg-color full-inset">
														<div class="demo-section k-content wide">
															<div id="chart_laat_by_student"></div>
														</div>
													</div>
												</div>
												<div class="col-md-3">
													<div class="databox primary-bg-color full-inset">
														<div class="demo-section k-content wide">
															<div id="chart_uitgestuurd_by_student"></div>
														</div>
													</div>
												</div>
												<div class="col-md-3">
													<div class="databox primary-bg-color full-inset">
														<div class="demo-section k-content wide">
															<div id="chart_huiswerk_by_student"></div>
														</div>
													</div>
												</div>
											</div>

										</div>
										<div class="row mrg-bottom">
											<div class="col-md-6">
												<div class="primary-bg-color brd-full">
													<div class="box">
														<div class="box-title full-inset brd-bottom clearfix">
															<h2 class="col-md-12">Leerling gemiddelde</h2>
														</div>
														<!-- <div class="primary-bg-color brd-full"> -->
														<div class="box telerik-plugin">
															<!-- <div class="box-content full-inset default-secondary-bg-color equal-height"> -->
															<!-- <div class="databox primary-bg-color full-inset"> -->
															<div class="demo-section k-content wide">
																<div id="graph-three-periods_by_student"></div>
															</div>
															<!-- </div> -->
															<!-- </div> -->
														</div>
														<!-- </div> -->
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="primary-bg-color brd-full">
													<div class="box">
														<div class="box-title full-inset brd-bottom clearfix">
															<h2 class="col-md-12">Klas gemiddelde</h2>
														</div>
														<!-- <div class="primary-bg-color brd-full"> -->
														<div class="box telerik-plugin">
															<!-- <div class="box-content full-inset default-secondary-bg-color equal-height"> -->
															<!-- <div class="databox primary-bg-color full-inset"> -->
															<div class="demo-section k-content wide">
																<div id="graph-three-periods_by_class"></div>
															</div>
															<!-- </div> -->
															<!-- </div> -->
														</div>
														<!-- </div> -->
													</div>
												</div>
											</div>
										</div>



										<!-- FINAL DE GRAFICOS -->
										<div class="row double-inset" id="detaill_seccion_top">
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
															<!-- <a data-toggle="tab" role="tab" aria-controls="rapport" href="#rapport" class="btn btn-default btn-m-w btn-m-h"> -->
															<a data-toggle="tab" role="tab" aria-controls="rapport" class="btn btn-default btn-m-w btn-m-h" disabled="disabled">
																Rapport
															</a>
														</li>
														<li class="disable" role="presentation">
															<a data-toggle="tab" role="tab" aria-controls="cijfers" href="#cijfers" class="btn btn-default btn-m-w btn-m-h" id="cijfers_toggle">
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
															<!-- <a data-toggle="tab" role="tab" aria-controls="socemotioneel" href="#socemotioneel" class="btn btn-default btn-m-w btn-m-h"> -->
															<a data-toggle="tab" role="tab" aria-controls="socemotioneel" class="btn btn-default btn-m-w btn-m-h" disabled="disabled">
																Social emotioneel
															</a>
														</li>
														<li class="disable" role="presentation">
															<a data-toggle="tab" role="tab" aria-controls="extra" href="#documenten" class="btn btn-default btn-m-w btn-m-h">
																Documenten
															</a>
														</li>
														<?php if ($_SESSION['SchoolID'] == 8) : ?>

															<li class="disable" role="presentation">
																<a data-toggle="tab" role="tab" aria-controls="extra" href="#new_forms" class="btn btn-default btn-m-w btn-m-h">
																	Extra Documents
																</a>
															</li>
														<?php endif; ?>


														<?php if ($_SESSION['SchoolType'] == 1) : ?>
															<li class="disable" role="presentation">
																<a id="a_smw" data-toggle="tab" role="tab" aria-controls="smw" href="#smw" class="btn btn-default btn-m-w btn-m-h">
																	Aanmelding SMW
																</a>
															</li>
															<li class="disable" role="presentation">
																<a id="a_mdc" data-toggle="tab" role="tab" aria-controls="mdc" href="#mdc" class="btn btn-default btn-m-w btn-m-h">
																	Aanmelding MDC
																</a>
															</li>
														<?php endif; ?>
														<!-- <li class="disable hidden" role="presentation">
													<a data-toggle="tab" role="tab" aria-controls="test" href="#test" class="btn btn-default btn-m-w btn-m-h">
													TEST
												</a>
											</li> -->
														<li class="disable" role="presentation">
															<a id="a_remedial" data-toggle="tab" role="tab" aria-controls="remedial" href="#remedial" class="btn btn-default btn-m-w btn-m-h">
																REMEDIAL
															</a>
														</li>
														<li class="disable" role="presentation">
															<a data-toggle="tab" role="tab" aria-controls="avi" href="#avi" class="btn btn-default btn-m-w btn-m-h">
																AVI
															</a>
														</li>
														<li class="disable" role="presentation">
															<a data-toggle="tab" role="tab" aria-controls="financial" href="#financial" class="btn btn-default btn-m-w btn-m-h">
																FINANCIAL
															</a>
														</li>
														<li class="disable" role="presentation">
															<a data-toggle="tab" role="tab" aria-controls="report_excel" href="#report_excel" class="btn btn-default btn-m-w btn-m-h">
																Witte kaart
															</a>
														</li>
													</ul>
												</div>
												<div class="col-md-10 reset-pdng-left">
													<div class="tab-content sixth-bg-color full-inset">
														<div role="tabpanel" class="tab-pane active" id="personalia">
															<!-- CaribeDevelopers: Dinamic tables to show details of student -->

															<?php print $leerling_detail; ?>
															<a type="submit" name="btn_leerling_detail_personalia_print" id="btn_leerling_detail_personalia_print" class="btn btn-default btn-m-w pull-right mrg-left" target="_blank">PRINT</a>

															<!--  CaribeDevelopers: End dinamic tables to show details of student-->
														</div>
														<div role="tabpane2" class="tab-pane" id="contactpersoon">
															<h2 class="primary-color mrg-bottom">Contact persoon</h2>
															<div class="row mrg-bottom">
																<div class="col-md-8 table-responsive" id="div_table_contact" name="div_table_contact">
																	<?php echo $contact_detail; ?>
																	<a type="submit" name="btn_leerlin_detail_contact_print" id="btn_leerlin_detail_contact_print" class="btn btn-default btn-m-w pull-right mrg-left" target="_blank">PRINT</a>
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
																							<input type="hidden" id="id_student" name="id_student" value=<?php echo $_GET["id"]; ?>>
																							<input type="hidden" id="id_family" name="id_family" value=<?php echo $_GET["id_family"]; ?>>
																							<input type="hidden" id="id_contact" name="id_contact" value="0">
																							<div class="form-group">
																								<label class="col-md-4 control-label" for="">Voogd*</label>
																								<div class="col-md-8">
																									<select id="tutor" name="tutor" class="form-control">
																										<option selected value="">Selecteer Tutor</option>
																										<option value=1>Ja</option>
																										<option value=0>Nee</option>
																									</select>
																								</div>
																							</div>

																							<div class="form-group">
																								<label class="col-md-4 control-label" for="">Type*</label>
																								<div class="col-md-8">
																									<select id="type" name="type" class="form-control">
																										<option value="">Selecteer een Type</option>
																										<option value="Moeder">Moeder</option>
																										<option value="Vader">Vader</option>
																										<option value="Oom">Oom</option>
																										<option value="Tante">Tante</option>
																										<option value="Broer">Broer</option>
																										<option value="Zus">Zus</option>
																										<option value="Grootmoeder">Grootmoeder</option>
																										<option value="Grootvader">Grootvader</option>
																										<option value="Stiefvader">Stiefvader</option>
																										<option value="Stiefmoeder">Stiefmoeder</option>
																										<option value="Other">Other</option>
																									</select>
																								</div>
																							</div>
																							<div class="form-group">
																								<label class="col-md-4 control-label">ID Nummer</label>
																								<div class="col-md-8">
																									<input id="id_number_contact" class="form-control" type="text" name="id_number_contact" />
																								</div>
																							</div>
																							<div class="form-group">
																								<label class="col-md-4 control-label">Naam *</label>
																								<div class="col-md-8">
																									<input id="full_name" class="form-control" type="text" name="full_name" />
																								</div>
																							</div>
																							<div class="form-group">
																								<label class="col-md-4 control-label">Adres</label>
																								<div class="col-md-8">
																									<input id="address" class="form-control" type="text" name="address" />
																								</div>
																							</div>
																							<div class="form-group">
																								<label class="col-md-4 control-label">Email</label>
																								<div class="col-md-8">
																									<input id="email" class="form-control" type="email" name="email" />
																								</div>
																							</div>
																							<div class="form-group">
																								<label class="col-md-4 control-label">Mobiel</label>
																								<div class="col-md-8">
																									<input id="mobile_phone" class="form-control input-mask" data-mask="eg: (000) 000-0000" placeholder="000-00-0000000" type="text" name="mobile_phone" />
																								</div>
																							</div>
																							<div class="form-group">
																								<label class="col-md-4 control-label">Huis telefoon</label>
																								<div class="col-md-8">
																									<input id="home_phone" class="form-control input-mask" data-mask="eg: (000) 000-0000" placeholder="000-00-0000000" type="text" name="home_phone" />
																								</div>
																							</div>
																							<div class="form-group">
																								<label class="col-md-4 control-label">Werk Telefoon</label>
																								<div class="col-md-8">
																									<input id="work_phone" class="form-control input-mask" data-mask="eg: (000) 000-0000" placeholder="000-00-0000000" type="text" name="work_phone" />
																								</div>
																							</div>
																							<div class="form-group fg-line">
																								<label class="col-md-4 control-label">Werk Ext. </label>
																								<div class="col-md-8">
																									<input id="work_phone_ext" class="form-control input-mask" data-mask="000-00-0000000" placeholder="0000" name="work_phone_ext" type="text" name=="work_phone_ext" />
																								</div>
																							</div>
																							<div class="form-group">
																								<label class="col-md-4 control-label">Company/Work</label>
																								<div class="col-md-8">
																									<input id="company" class="form-control" type="text" name="company" />
																								</div>
																							</div>
																							<div class="form-group">
																								<label class="col-md-4 control-label">Positie</label>
																								<div class="col-md-8">
																									<input id="position_company" class="form-control" type="text" name="position_company" />
																								</div>
																							</div>
																							<div class="form-group">
																								<label class="col-md-12 control-label">Observations</label>
																								<div class="col-md-12">
																									<textarea id="observation" class="form-control" name="observation" type="text " rows="7" placeholder="Enter observation here..."></textarea>
																								</div>
																							</div>
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
																	<a type="submit" name="btn_leerling_detail_event_print" id="btn_leerling_detail_event_print" class="btn btn-default btn-m-w pull-right mrg-left" target="_blank">PRINT</a>
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
																										<input type="text" value="" placeholder="" id="due_date" class="form-control calendar" calendar="full" name="due_date">
																										<!-- <input type="hidden" value="||true|" placeholder="" id="calendar_config" name="calendar_config"> -->
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
																								<label class="col-md-4 control-label">Toelichting</label>
																								<div class="col-md-12">
																									<textarea id="observation_event" maxlength="1000" class="form-control" rows="7" name="observation_event" type="text" placeholder="Enter observation here..."></textarea>
																								</div>
																							</div>
																							<div class="form-group">
																								<label class="col-md-4 control-label" for="prive">Prive</label>
																								<div class="col-md-8">
																									<label>
																										<input for="event_private" type="radio" name="event_private" id="event_private" value="1"> Ja
																									</label>
																									<label>
																										<input for="event_no_private" type="radio" name="event_private" id="event_no_private" value="0"> Nee
																									</label>
																								</div>
																							</div>
																							<div class="form-group">
																								<label class="col-md-4 control-label" for="">Belangrijk</label>
																								<div class="col-md-8">
																									<label>
																										<input for="important_notice" type="radio" name="important_notice" id="important_notice" value="1"> Ja
																									</label>
																									<label>
																										<input for="no_important_notice" type="radio" name="important_notice" id="no_important_notice" value="0"> Nee
																									</label>
																								</div>
																							</div>
																							<input type="hidden" id="important_notice_selected" name="important_notice_selected" class="form-control">
																							<input type="hidden" id="event_private_selected" name="event_private_selected" class="form-control">
																							<input id="id_event" type="hidden" name="id_event" class="form-control" value="0">
																							<input id="id_student" type="hidden" name="id_student" class="form-control" value=<?php echo $_GET["id"]; ?>>

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
															<div class="row">
																<div class="default-secondary-bg-color col-md-12 inset brd-bottom">
																	<h2 class="primary-color">Select Schooljaar for Cijfers:</h2>
																</div>
																<div class="default-secondary-bg-color col-md-12 inset filter-bar brd-bottom clearfix">
																	<form class="form-inline col-md-12" name="frm_home_parent" id="frm_leerling_schooljaar">
																		<label>2016-2017</label>
																		<div class="input-group input_radio">
																			<input type="radio" class="form-group" name="_2016_2017_cijfer" id="_2016_2017_cijfer">
																			<input type="hidden" name="2016_2017_cijfer" id="2016_2017_cijfer" value="0">
																		</div>
																		<label>2017-2018</label>
																		<div class="input-group input_radio">
																			<input type="radio" class="form-group" name="_2017_2018_cijfer" id="_2017_2018_cijfer">
																			<input type="hidden" name="2017_2018_cijfer" id="2017_2018_cijfer" value="0">
																		</div>
																		<label>2018-2019</label>
																		<div class="input-group input_radio">
																			<input type="radio" class="form-group" name="_2018_2019_cijfer" id="_2018_2019_cijfer">
																			<input type="hidden" name="2018_2019_cijfer" id="2018_2019_cijfer" value="0">
																		</div>
																		<label>2019-2020</label>
																		<div class="input-group input_radio">
																			<input type="radio" class="form-group" name="_2018_2019_cijfer" id="_2019_2020_cijfer">
																			<input type="hidden" name="2019_2020_cijfer" id="2019_2020_cijfer" value="0">
																		</div>
																		<label>2020-2021</label>
																		<div class="input-group input_radio">
																			<input type="radio" class="form-group" name="_2020_2021_cijfer" id="_2020_2021_cijfer">
																			<input type="hidden" name="2020_2021_cijfer" id="2020_2021_cijfer" value="0">
																		</div>
																		<label>2021-2022</label>
																		<div class="input-group input_radio">
																			<input type="radio" class="form-group" name="_2021_2022_cijfer" id="_2021_2022_cijfer">
																			<input type="hidden" name="2021_2022_cijfer" id="2021_2022_cijfer" value="0">
																		</div>
																		<label>2022-2023</label>
																		<div class="input-group input_radio">
																			<input type="radio" class="form-group" name="_2022_2023_cijfer" id="_2022_2023_cijfer" checked="checked">
																			<input type="hidden" name="2023_2023_cijfer" id="2022_2023_cijfer" value="1">
																		</div>
																	</form>
																</div>
															</div>
															<!-- <h2 class="primary-color mrg-bottom">Cijfers</h2> -->
															<div class="container container-fs">
																<div class="row">
																	<br>
																	<div class="">
																		<div class="sixth-bg-color brd-full">
																			<div class="box">
																				<div class="box-content">
																					<div class="data-display">
																						<div class="table-responsive">
																							<div id="cijfer_by_year">
																								<?php echo $cijfer_table; ?>
																							</div>
																							<a type="submit" name="btn_leerling_detail_cijfer_print" id="btn_leerling_detail_cijfer_print" class="btn btn-default btn-m-w pull-right mrg-left" target="_blank">PRINT</a>
																						</div>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div role="tabpane6" class="tab-pane" id="houding">
															<div class="row">
																<div class="default-secondary-bg-color col-md-12 inset brd-bottom">
																	<h2 class="primary-color">Select Schooljaar for Houding:</h2>
																</div>
																<div class="default-secondary-bg-color col-md-12 inset filter-bar brd-bottom clearfix">
																	<form class="form-inline col-md-12" name="frm_home_parent" id="frm_leerling_schooljaar">
																		<label>2016-2017</label>
																		<div class="input-group col-md-1">
																			<input type="radio" class="form-group" name="_2016_2017_houding" id="_2016_2017_houding">
																			<input type="hidden" name="2016_2017_houding" id="2016_2017_houding" value="0">
																		</div>
																		<label>2017-2018</label>
																		<div class="input-group col-md-1">
																			<input type="radio" class="form-group" name="_2017_2018_houding" id="_2017_2018_houding">
																			<input type="hidden" name="2017_2018_houding" id="2017_2018_houding" value="0">
																		</div>
																		<label>2018-2019</label>
																		<div class="input-group col-md-1">
																			<input type="radio" class="form-group" name="_2018_2019_houding" id="_2018_2019_houding">
																			<input type="hidden" name="2018_2019_houding" id="2018_2019_houding" value="0">
																		</div>
																		<label>2019-2020</label>
																		<div class="input-group col-md-1">
																			<input type="radio" class="form-group" name="_2019_2020_houding" id="_2019_2020_houding">
																			<input type="hidden" name="2019_2020_houding" id="2019_2020_houding" value="0">
																		</div>
																		<label>2020-2021</label>
																		<div class="input-group col-md-1">
																			<input type="radio" class="form-group" name="_2020_2021_houding" id="_2020_2021_houding">
																			<input type="hidden" name="2020_2021_houding" id="2020_2021_houding" value="0">
																		</div>
																		<label>2021-2022</label>
																		<div class="input-group col-md-1">
																			<input type="radio" class="form-group" name="_2021-2022_houding" id="_2021-2022_houding" checked="checked">
																			<input type="hidden" name="2021-2022_houding" id="2021-2022_houding" value="1">
																		</div>
																	</form>
																</div>
															</div>
															<!-- <h2 class="primary-color mrg-bottom">Houding</h2> -->
															<div class="container container-fs">
																<div class="row">
																	<div class="">
																		<div class="sixth-bg-color brd-full">
																			<div class="box">
																				<div class="box-content">
																					<div class="data-display">
																						<div class="table-responsive">
																							<div id="houding_by_year">
																								<?php echo $houding_list; ?>
																							</div>
																							<a type="submit" name="btn_leerling_detail_houding_print" id="btn_leerling_detail_houding_print" class="btn btn-default btn-m-w pull-right mrg-left" target="_blank">PRINT</a>
																						</div>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div role="tabpane7" class="tab-pane" id="verzuim">
															<?php if ($_SESSION['SchoolID'] < 12 || $_SESSION['SchoolID'] == 16) { ?>
																<div class="row">
																	<div class="default-secondary-bg-color col-md-12 inset brd-bottom">
																		<h2 class="primary-color">Select Schooljaar for Verzuim:</h2>
																	</div>
																	<div class="default-secondary-bg-color col-md-12 inset filter-bar brd-bottom clearfix">
																		<form class="form-inline col-md-12" name="frm_home_parent" id="frm_leerling_schooljaar">
																			<label>2016-2017</label>
																			<div class="input-group col-md-1">
																				<input type="radio" class="form-group" name="_2016_2017_verzuim" id="_2016_2017_verzuim">
																				<input type="hidden" name="2016_2017_verzuim" id="2016_2017_verzuim" value="0">
																			</div>
																			<label>2017-2018</label>
																			<div class="input-group col-md-1">
																				<input type="radio" class="form-group" name="_2017_2018_verzuim" id="_2017_2018_verzuim">
																				<input type="hidden" name="2017_2018_verzuim" id="2017_2018_verzuim" value="0">
																			</div>
																			<label>2018-2019</label>
																			<div class="input-group col-md-1">
																				<input type="radio" class="form-group" name="_2018_2019_verzuim" id="_2018_2019_verzuim">
																				<input type="hidden" name="2018_2019_verzuim" id="2018_2019_verzuim" value="0">
																			</div>
																			<label>2019-2020</label>
																			<div class="input-group col-md-1">
																				<input type="radio" class="form-group" name="_2019_2020_verzuim" id="_2019_2020_verzuim">
																				<input type="hidden" name="2019_2020_verzuim" id="2019_2020_verzuim" value="0">
																			</div>
																			<label>2020-2021</label>
																			<div class="input-group col-md-1">
																				<input type="radio" class="form-group" name="_2020_2021_verzuim" id="_2020_2021_verzuim">
																				<input type="hidden" name="2020_2021_verzuim" id="2020_2021_verzuim" value="0">
																			</div>
																			<label>2021-2022</label>
																			<div class="input-group col-md-1">
																				<input type="radio" class="form-group" name="_2021_20221_verzuim" id="_2021_2022_verzuim">
																				<input type="hidden" name="2021_2022_verzuim" id="2021_2022_verzuim" value="0">
																			</div>
																			<label>2022-2023</label>
																			<div class="input-group col-md-1">
																				<input type="radio" class="form-group" name="_2022_2023_verzuim" id="_2022_2023_verzuim" checked="checked">
																				<input type="hidden" name="2022_2023_verzuim" id="2022_2023_verzuim" value="1">
																			</div>
																		</form>
																	</div>
																</div>
																<!-- <h2 class="primary-color mrg-bottom">Verzuim</h2> -->
																<div id="verzuim_by_year">
																	<?php echo $verzuim_table; ?>
																</div>
																<?php } else {
																$studentid = $_GET['id'];
																$DBCreds = new DBCreds();
																$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
																$mysqli->set_charset('utf8');
																$query = "SELECT datum,p1,p2,p3,p4,p5,p6,p7,p8,p9,p10 FROM le_verzuim_hs WHERE studentid = $studentid";

																$resultado = mysqli_query($mysqli, $query);
																if (mysqli_num_rows($resultado) != 0) { ?>
																	<table id="tbl_rappor_verzuim_table1" class="table table-striped table-bordered">
																		<thead>

																			<tr>

																				<th>Date</th>

																				<th>p1</th>

																				<th>p2</th>
																				<th>p3</th>
																				<th>p4</th>
																				<th>p5</th>
																				<th>p6</th>
																				<th>p7</th>
																				<th>p8</th>
																				<th>p9</th>
																				<th>dag</th>

																			</tr>

																		</thead>

																		<tbody>
																			<?php while ($row = mysqli_fetch_assoc($resultado)) {
																				if ($row["p1"] != '0' || $row["p2"] != '0' || $row["p3"] != '0' || $row["p4"] != '0' || $row["p5"] != '0' || $row["p6"] != '0' || $row["p7"] != '0' || $row["p8"] != '0' || $row["p9"] != '0' || $row["p10"] != '0') {
																					$xd = 1; ?>
																					<tr>

																						<td> <?php echo $row["datum"] ?></td>


																						<td><? echo $row["p1"] ?> </td>
																						<td><? echo $row["p2"] ?> </td>
																						<td><? echo $row["p3"] ?> </td>
																						<td><? echo $row["p4"] ?> </td>
																						<td><? echo $row["p5"] ?> </td>
																						<td><? echo $row["p6"] ?> </td>
																						<td><? echo $row["p7"] ?> </td>
																						<td><? echo $row["p8"] ?> </td>
																						<td><? echo $row["p9"] ?> </td>
																						<td><? echo $row["p10"] ?> </td>

																					</tr>

																			<?php }
																			} ?>

																		</tbody>

																	</table>

																<?php if ($xd == 0) {
																		echo "No results to show";
																	}
																} else {
																	echo "No results to show";
																}

																?>

															<?php } ?>
															<a type="submit" name="btn_leerling_detail_verzuim_print" id="btn_leerling_detail_verzuim_print" class="btn btn-default btn-m-w pull-right mrg-left" target="_blank">PRINT</a>
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
																							<input type="hidden" id="id_student" name="id_student" value=<?php echo $_GET["id"]; ?>>
																							<input type="hidden" id="id" name="id" value=<?php print $_GET["id"]; ?>>
																							<input type="hidden" id="klas_student" name="klas_student" value=''>

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
																								<button type="reset" class="btn btn-danger btn-m-w pull-right mrg-left" id="btn_document_delete">Delete</button>
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
															<h2 class="primary-color mrg-bottom">Aanmelding SMW</h2>
															<!-- INICIO CODE CaribeDevelopers SOCIAL WORK -->
															<div class="row mrg-bottom">
																<div class="col-md-8" id="div_table_social_work">
																	<?php echo $social_work_detail; ?>
																	<a type="submit" name="btn_leerling_detail_swv_print" id="btn_leerling_detail_swv_print" class="btn btn-default btn-m-w pull-right mrg-left" target="_blank">PRINT</a>
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
																							<input type="hidden" id="id_student" name="id_student" value=<?php echo $_GET["id"]; ?>>
																							<input type="hidden" id="id_social_work" name="id_social_work" value="0">
																							<div class="form-group">
																								<input type="hidden" id="id_student" name="id_student" value=<?php echo $_GET["id"]; ?> />
																								<label class="col-md-4 control-label" for="">School Year</label>
																								<div class="col-md-8">
																									<input id="_social_work_school_year" type="text" class="typeahead form-control" placeholder=<?php print $schooljaar ?> disabled>
																									<input id="social_work_school_year" name="social_work_school_year" type="hidden" class="typeahead form-control" value=<?php print $schooljaar ?>>
																								</div>
																							</div>
																							<div class="form-group">
																								<label class="col-md-4 control-label">Datum</label>
																								<div class="col-md-8">
																									<div class="input-group date">
																										<input id="social_work_date" type="text" value="" placeholder="" class="form-control calendar" calendar="full" name="social_work_date">
																										<span class="input-group-addon"><i class="fa fa-calendar default-primary-color"></i></span>
																									</div>
																								</div>
																							</div>
																							<!-- Reason -->
																							<div class="form-group">
																								<label class="col-md-4 control-label">Reason</label>
																								<div class="col-md-8">
																									<input id="social_work_reason" type="text" name="social_work_reason" class="form-control">
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
																								<label class="col-md-4 control-label">Toelichting</label>
																								<div class="col-md-12">
																									<textarea id="social_work_observation" maxlength="1000" rows="7" class="form-control" name="social_work_observation" type="text" placeholder="Enter observation here..."></textarea>
																								</div>
																							</div>
																							<div class="form-group">
																								<label class="col-md-4 control-label" for="">Pending</label>
																								<br>
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
															<h2 class="primary-color mrg-bottom">Aanmelding MDC</h2>
															<div class="row mrg-bottom">
																<div class="col-md-8" id="div_table_mdc">
																	<!--  Call spn MDC -->
																	<?php echo $mdc_detail; ?>
																	<a type="submit" name="btn_leerling_detail_mdc_print" id="btn_leerling_detail_mdc_print" class="btn btn-default btn-m-w pull-right mrg-left" target="_blank">PRINT</a>
																</div>

																<div class="col-md-4 full-inset">
																	<div class="primary-bg-color brd-full">
																		<div class="box">
																			<div class="box-title full-inset brd-bottom">
																				<h3>New Aanmelding MDC</h3>
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
																							<input type="hidden" id="id_student" name="id_student" value=<?php echo $_GET["id"]; ?>>
																							<input type="hidden" id="id_mdc" name="id_mdc" value="0">
																							<div class="form-group">
																								<label class="col-md-4 control-label" for="">School Year</label>
																								<div class="col-md-8">
																									<input id="_mdc_school_year" type="text" class="typeahead form-control" placeholder=<?php print $schooljaar ?> disabled>
																									<input id="mdc_school_year" name="mdc_school_year" type="hidden" class="typeahead form-control" value=<?php print $schooljaar ?>>

																								</div>
																							</div>
																							<!-- datepicker -->
																							<div class="form-group">
																								<label class="col-md-4 control-label">Datum</label>
																								<div class="col-md-8">
																									<div class="input-group date">
																										<input id="mdc_date" type="text" value="" placeholder="" id="mdc_date" class="form-control calendar" calendar="full" name="mdc_date">
																										<span class="input-group-addon"><i class="fa fa-calendar default-primary-color"></i></span>
																									</div>
																								</div>
																							</div>
																							<!-- Reason -->
																							<div class="form-group">
																								<label class="col-md-4 control-label">Reason</label>
																								<div class="col-md-8">
																									<input id="mdc_reason" type="text" name="mdc_reason" class="form-control">
																								</div>
																							</div>
																							<!-- Class -->
																							<div class="form-group">
																								<label class="col-md-4 control-label">Class</label>
																								<div class="col-md-8">
																									<!-- <input id="mdc_class" type="text" name="mdc_class" class="typeahead form-control" value="States of USA" disabled> -->
																									<input id="mdc_class" type="text" name="mdc_class" class="typeahead form-control" placeholder=<?php print $klas ?> disabled>
																									<input id="mdc_class_value" type="hidden" name="mdc_class_value" value="">
																								</div>
																							</div>
																							<!-- Observations -->
																							<div class="form-group">
																								<label class="col-md-4 control-label">Toelichting</label>
																								<div class="col-md-12">
																									<textarea id="mdc_observation" maxlength="1000" class="form-control" rows="7" name="mdc_observation" type="text" placeholder="Enter observation here..."></textarea>
																								</div>
																							</div>

																							<div class="form-group">
																								<label class="col-md-4 control-label">Pending</label>
																								<div class="col-md-8">
																									<br>
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
																							<input type="hidden" id="id_student" name="id_student" value=<?php echo $_GET["id"]; ?>>
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
																									<input id="test_type" type="text" name="test_type" class="typeahead form-control" value="">
																								</div>
																							</div>
																							<!-- Class -->
																							<div class="form-group">
																								<label class="col-md-4 control-label">Class</label>
																								<div class="col-md-8">
																									<!-- <input id="test_class" type="text" name="test_class" class="typeahead form-control" value="States of USA" disabled> -->
																									<input id="test_class" type="text" name="test_class" class="typeahead form-control" placeholder=<?php print $klas ?> disabled>
																									<input id="test_class_value" type="hidden" name="test_class_value" value="">
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
																	<div class="col-md-8">
																		<div id="div_table_remedial">
																			<!--  Call spn_remedial-->
																			<?php echo $remedial_list; ?>
																		</div>
																		<div>
																			<a type="submit" name="btn_leerling_detail_remedial_print" id="btn_leerling_detail_remedial_print" class="btn btn-default btn-m-w pull-right mrg-left" target="_blank">PRINT</a>
																		</div>
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
																								<input type="hidden" id="id_student" name="id_student" value=<?php print $_GET["id"]; ?>>
																								<input type="hidden" id="id_remedial" name="id_remedial" value="0">
																								<div class="form-group">
																									<label class="col-md-4 control-label" for="">School Year</label>
																									<div class="col-md-8">
																										<input id="_remedial_school_year" type="text" class="typeahead form-control" placeholder=<?php print $schooljaar ?> disabled>
																										<input id="remedial_school_year" name="remedial_school_year" type="hidden" class="typeahead form-control" value="<?php print $schooljaar ?>">

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
																											<input type="text" value="" placeholder="" id="remedial_end_date" class="form-control calendar" calendar="full" name="remedial_end_date">
																											<span class="input-group-addon"><i class="fa fa-calendar default-primary-color"></i></span>
																										</div>
																									</div>
																								</div>
																								<!-- Subject -->
																								<div class="form-group">
																									<label class="col-md-4 control-label" for="">Subject</label>
																									<div class="col-md-8">
																										<select id="remedial_subject" name="remedial_subject" class="form-control">
																											<option selected value="prisma">prisma</option>
																											<option selected value="begeleiding">begeleiding</option>
																											<option selected value="begeleiding rekenen">begeleiding rekenen</option>
																											<option selected value="begeleiding lezen">begeleiding lezen</option>
																											<option selected value="begeleiding spelling">begeleiding spelling</option>
																											<option selected value="extra hulp rekenen">extra hulp rekenen</option>
																											<option selected value="extra hulp lezen">extra hulp lezen</option>
																											<option selected value="extra hulp spelling">extra hulp spelling</option>
																											<option selected value="zaakvakken/taal">zaakvakken/taal</option>
																										</select>
																									</div>
																									<input type="hidden" id="remedial_subject_text" name="remedial_subject_text" value="">
																								</div>
																								<!-- Class -->
																								<div class="form-group">
																									<label class="col-md-4 control-label">Class</label>
																									<div class="col-md-8">
																										<input id="remedial_class" type="text" name="remedial_class" class="typeahead form-control" placeholder=<?php print $klas ?> disabled>
																										<input id="remedial_class_value" type="hidden" name="remedial_class_value" value=<?php print $klas ?>>
																									</div>
																								</div>
																								<!-- Docent  -->
																								<div class="form-group">
																									<label class="col-md-4 control-label">Docent</label>
																									<div class="col-md-8">
																										<!-- <input id="mdc_class" type="text" name="mdc_class" class="typeahead form-control" value="States of USA" disabled> -->
																										<input id="remedial_docent" type="text" name="remedial_docent" class="typeahead form-control" placeholder=<?php print $fullname ?> disabled>
																										<input id="remedial_docent_value" type="hidden" name="remedial_docent_value" value=<?php print $fullname ?>>
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
																<a id='return-remedial' href='#'><i class="fa fa-angle-double-left quaternary-color"></i> Terug</a>
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
																									<label class="col-md-4 control-label">Toelichting</label>
																									<div class="col-md-8">
																										<textarea id="remedial_detail_observation" class="form-control" name="remedial_detail_observation" type="text" placeholder="Enter observation here..."></textarea>
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
															<div class="row">
																<div class="default-secondary-bg-color col-md-12 inset brd-bottom">
																	<h2 class="primary-color">Select Schooljaar for Avi:</h2>
																</div>
																<div class="default-secondary-bg-color col-md-12 inset filter-bar brd-bottom clearfix">
																	<form class="form-inline col-md-12" name="frm_home_parent" id="frm_leerling_schooljaar">
																		<label>2016-2017</label>
																		<div class="input-group col-md-1">
																			<input type="radio" class="form-group" name="_2016_2017_avi" id="_2016_2017_avi">
																			<input type="hidden" name="2016_2017_avi" id="2016_2017_avi" value="0">
																		</div>
																		<label>2017-2018</label>
																		<div class="input-group col-md-1">
																			<input type="radio" class="form-group" name="_2017_2018_avi" id="_2017_2018_avi">
																			<input type="hidden" name="2017_2018_avi" id="2017_2018_avi" value="0">
																		</div>
																		<label>2018-2019</label>
																		<div class="input-group col-md-1">
																			<input type="radio" class="form-group" name="_2018_2019_avi" id="_2018_2019_avi">
																			<input type="hidden" name="2018_2019_avi" id="2018_2019_avi" value="0">
																		</div>
																		<label>2019-2020</label>
																		<div class="input-group col-md-1">
																			<input type="radio" class="form-group" name="_2019_2020_avi" id="_2019_2020_avi" checked="checked">
																			<input type="hidden" name="2019_2020_avi" id="2019_2020_avi" value="1">
																		</div>
																	</form>
																</div>
															</div>
															<!-- <h2 class="primary-color mrg-bottom">AVI</h2> -->

															<!--  INICIO AVI -->
															<div class="row mrg-bottom">
																<div class="col-md-12" id="div_table_test">
																	<!--  Call spn TEST -->
																	<div id="avi_by_schooljaar">
																		<?php echo $document_avi; ?>
																	</div>
																	<a type="submit" name="btn_leerling_detail_avi_print" id="btn_leerling_detail_avi_print" class="btn btn-default btn-m-w pull-right mrg-left" target="_blank">PRINT</a>
																</div>
															</div>

															<!-- FIN AVI -->
														</div>
														<div role="tabpane15" class="tab-pane" id="financial">
															<h2 class="primary-color mrg-bottom">FINANCIAL</h2>

															<!--  INICIO FINANCIAL -->
															<div class="row mrg-bottom">
																<div class="col-md-12" id="tbl_invoice_by_idstudent">
																	<!--  Call spn TEST -->
																	<?php echo $invoice_table; ?>
																	<a type="submit" name="btn_leerling_detail_financial_print" id="btn_leerling_detail_financial_print" class="btn btn-default btn-m-w pull-right mrg-left" target="_blank">PRINT</a>
																</div>
															</div>

															<!-- FIN FINANCIAL -->
														</div>


														<div role="tabpane16" class="tab-pane report_excel" id="report_excel">
															<h2 class="primary-color mrg-bottom">WITTE KAART</h2>
															<!--  INICIO REPPORT EXCEL -->
															<div class="row mrg-bottom">
																<div class="col-md-12" id="tbl_invoice_by_idstudent">
																	<!--  Call spn TEST -->
																	<div class="default-secondary-bg-color col-md-12 ">
																		<form id="form-vrapportexport" class="form-inline form-data-retriever" name="filter-vak" role="form" method="GET" action="/dev_tests/test_exportexcel.php">
																			<fieldset>
																				<div class="form-group">
																					<label for="cijfers_rapporten_lijst">SchoolJaar.</label>
																					<select class="form-control" name="schooljaar_rapport_excel_by_student" id="schooljaar_rapport_excel_by_student">
																						<!-- Options populated by AJAX get -->
																						<!-- TEMPORARY ENTERED MANUALLY -->
																						<option value="All">Alle schooljaren</option>
																						<option value="2023-2024">2023-2024</option>
																						<option value="2022-2023">2022-2023</option>
																						<option value="2021-2022">2021-2022</option>
																						<option value="2020-2021">2020-2021</option>
																						<option value="2019-2020">2019-2020</option>
																						<option value="2018-2019">2018-2019</option>
																						<option value="2017-2018">2017-2018</option>
																						<option value="2016-2017">2016-2017</option>
																					</select>
																				</div>
																				<div class="form-group">
																					<!-- <button data-display="data-display" data-ajax-href="ajax/getvakken_tabel.php" type="submit" class="btn btn-primary btn-m-w btn-m-h">zoeken</button> -->
																					<button data-display="data-display" type="submit" id="btn_rapport_export_by_student" name="btn_rapport_export_by_student" class="btn btn-primary btn-m-w btn-m-h">Export</button>
																				</div>
																				<div class="form-group">
																					<ol class="breadcrumb">
																						<li><a id="export_by_student_download" href"#">DOWNLOAD CLEAN EXPORT FILE</a></li>
																					</ol>
																				</div>
																			</fieldset>
																		</form>
																	</div>
																</div>
															</div>

															<!-- FIN REPORT EXCEL -->
														</div>


														<div role="tabpane17" class="tab-pane" id="new_forms">
															<h2 class="primary-color mrg-bottom">Extra Documents</h2>
															<!--  CODE CaribeDevelopers Document -->
															<div class="row mrg-bottom">
																<div class="col-md-8" id="div_table_new_forms">
																	<div class="form-group">
																		<!-- <button data-display="data-display" data-ajax-href="ajax/getvakken_tabel.php" type="submit" class="btn btn-primary btn-m-w btn-m-h">zoeken</button> -->
																		<a href="templates/doorverwijzingsformulier.doc" id="btn_rapport_export_by_student" name="btn_rapport_export_by_student" class="btn btn-primary btn-m-w btn-m-h">Doorverwijzing Prisma</a>
																		<a href="templates/Formulario_Traspaso_di_alumno_seccion_basico.doc" id="btn_rapport_export_by_student" name="btn_rapport_export_by_student" class="btn btn-secondary btn-m-w btn-m-h">Traspaso di alumno seccion</a>
																	</div>
																	<!--  Call spn DOCUMENT -->
																	<div id="document_list_new_forms">
																		<?php echo $document_list_new_forms; ?>
																	</div>
																</div>
																<div class="col-md-4 full-inset">
																	<div class="primary-bg-color brd-full">
																		<div class="box">
																			<div class="box-title full-inset brd-bottom">
																				<h3>Upload</h3>
																			</div>
																			<div class="sixth-bg-color box content full-inset">
																				<form class="form-horizontal align-left" role="form" name="form-new_forms" id="form-new_forms">
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
																							<input type="hidden" id="id_student" name="id_student" value=<?php echo $_GET["id"]; ?>>
																							<input type="hidden" id="id" name="id" value=<?php print $_GET["id"]; ?>>
																							<input type="hidden" id="klas_student_new_forms" name="klas_student_new_forms" value=''>
																							<div class="form-group">
																								<label class="col-md-4 control-label">Bestand</label>
																								<div class="col-md-8">
																									<input class="form-control" type="file" name="new_form_file_to_upload" id="new_form_file_to_upload" required>
																								</div>
																							</div>
																							<div class="form-group">
																								<label class="col-md-4 control-label">Description</label>
																								<div class="col-md-8">
																									<textarea class="form-control" id="description_new_forms" name="description_new_forms"></textarea>
																								</div>
																							</div>
																							<div class="form-group full-inset">
																								<button type="submit" class="btn btn-primary btn-m-w pull-right mrg-left" id="btn_add_new_forms">Upload</button>
																								<button type="reset" class="btn btn-danger btn-m-w pull-right mrg-left" id="btn_delete_new_forms">Delete</button>
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
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<input type="hidden" name="schooljaar_selected" id="schooljaar_selected" value="2018-2019">
			</div>
		</section>
	</main>
	<?php include 'footer.php'; ?>
</div>

<?php include 'document_end.php'; ?>
<!-- INICIO CODE CaribeDevelopers SOCIAL WORK -->
<script type="text/javascript">
	var SchoolType = <?php echo $_SESSION["SchoolType"]; ?>;
	var uitsturen = "";
	if (SchoolType == 1) {
		uitsturen = 'Naar huis';
	} else {
		uitsturen = 'Spijbelen';
	}

	function getParam(key) {
		// Find the key and everything up to the ampersand delimiter
		var value = RegExp("" + key + "[^&]+").exec(window.location.search);

		// Return the unescaped value minus everything starting from the equals sign or an empty string
		return unescape(!!value ? value.toString().replace(/^[^=]+./, "") : "");
	}


	$(document).ready(function() {

		var studentid = getParam("id");
		var familyid = getParam("id_family");
		$("#_2022_2023_cijfer").click();
		$("#_2021_2022_houding").click();

		var userRights = "<?php echo $_SESSION["UserRights"] ?>";

		if (userRights == "ADMINISTRATIE") {
			$('#a_smw').attr("disabled", "disabled").removeAttr("href");
			$('#a_mdc').attr("disabled", "disabled").removeAttr("href");
			$('#a_remedial').attr("disabled", "disabled").removeAttr("href");
		}
		if (userRights == "ONDERSTEUNING") {
			$('#a_smw').attr("disabled", "disabled").removeAttr("href");
			$('#a_mdc').attr("disabled", "disabled").removeAttr("href");
		}
		$.post("ajax/get_leerling_by_class.php", {}, function(data) {
			$("#studenten_list").html(data);
			var studentID = <?php echo $_GET["id"]; ?>;
			$("#studenten_list option[value='" + studentID + "']").prop('selected', 'selected');

		});

		$(function() {

			$("[calendar=full]").datepicker({


				format: 'dd-mm-yyyy',
				todayHighlight: true,
				endDate: false,
				autoclose: true
			});
		});
		var SchoolID = '<?php echo $_SESSION["SchoolID"]; ?>';

		$('#export_by_student_download').click(function() {
			if (SchoolID == '8') {
				// this.prop("href", "http://www.jakcms.com")
				$('#export_by_student_download').attr('href', 'templates/wittekaart_cococo.xlsx');
			} else if (SchoolID == '9' || SchoolID == '10' || SchoolID == '11') {
				$('#export_by_student_download').attr('href', 'templates/Witte_kaart_2018 _s9_s10_s11.xlsx');
			}
		});



		if (SchoolID == '4' || SchoolID == '8' || SchoolID == '9' || SchoolID == '10' || SchoolID == '11') {
			$('#report_excel').removeClass('hidden');
		} else {
			$('#report_excel').addClass('hidden');
		}

	});

	$(function() {

		$("#btn_leerlin_detail_contact_print").click(function() {

			window.open("print.php?name=leerling_personalia&title=Leerling Personalia&id=" + <?php echo $_GET["id"] ?> + "&idfamily=" + <?php echo $_GET["id_family"] ?>);

		});
		$("#btn_leerling_detail_event_print").click(function() {

			window.open("print.php?name=leerling_detail_event&title=Leerling Event List&id=" + <?php echo $_GET["id"] ?>);

		});
		$("#btn_leerling_detail_swv_print").click(function() {

			window.open("print.php?name=leerling_detail_swv&title=Leerling Swv List&id=" + <?php echo $_GET["id"] ?>);

		});
		$("#btn_leerling_detail_mdc_print").click(function() {

			window.open("print.php?name=leerling_detail_mdc&title=Leerling Mdc List&id=" + <?php echo $_GET["id"] ?>);

		});
		$("#btn_leerling_detail_remedial_print").click(function() {

			window.open("print.php?name=leerling_detail_remedial&title=Leerling Remedial List&id=" + <?php echo $_GET["id"] ?>);

		});

		$("#btn_leerling_inner_detail_remedial_print").click(function() {
			var id_remedial = $(this).attr('id_remedial');
			window.open("print.php?name=leerling_inner_detail_remedial&title=Leerling Remedial Detail&id=" + <?php echo $_GET["id"] ?> + "&id_remedial=" + id_remedial);
		});

		$("#btn_leerling_detail_cijfer_print").click(function() {
			window.open("print.php?name=leerling_detail_cijfer&title=Leerling Cijfer List&schoolJaar=" + $('#schooljaar_selected').val() + "&id=" + <?php echo $_GET["id"] ?>);
		});

		$("#btn_leerling_detail_houding_print").click(function() {

			window.open("print.php?name=leerling_detail_houding&title=Leerling Houding List&&schoolJaar=" + $('#schooljaar_selected').val() + "&id=" + <?php echo $_GET["id"] ?>);

		});
		$("#btn_leerling_detail_verzuim_print").click(function() {

			window.open("print.php?name=leerling_detail_verzuim&title=Leerling Verzium List&schoolJaar=" + $('#schooljaar_selected').val() + "&id=" + <?php echo $_GET["id"] ?>);

		});
		$("#btn_leerling_detail_avi_print").click(function() {

			window.open("print.php?name=leerling_detail_avi&title=Leerling Avi List&schoolJaar=" + $('#schooljaar_selected').val() + "&id=" + <?php echo $_GET["id"] ?>);

		});
		$("#btn_leerling_detail_financial_print").click(function() {

			window.open("print.php?name=leerling_detail_financial&title=Leerling Financial List&id=" + <?php echo $_GET["id"] ?>);

		});

		$("#btn_leerling_detail_personalia_print").click(function() {

			window.open("print.php?name=leerling_personalia&title=Leerling Personalia&id=" + <?php echo $_GET["id"] ?> + "&idfamily=" + <?php echo $_GET["id_family"] ?>);

		});




		// CaribeDevelopers: Function to select a row form table Social Work
		$('#dataRequest-social_work tr').click(function() {
			$(this).closest('tr').siblings().children('td, th').css('background-color', '');
			$(this).children('td, th').css('background-color', '#cccccc');
			$('#id_social_work').val($(this).find("td").eq(4).find("[name='id_social_work']").val());
			$('#social_work_date').val($(this).find("td").eq(1).text());
			$('#social_work_reason').val($(this).find("td").eq(2).text());
			$('#social_work_class').val($(this).find("td").eq(3).text());
			$('#social_work_class_hidden').val($(this).find("td").eq(3).text());
			$('#social_work_observation').val($(this).find("td").eq(4).find("[name='observations_val']").val());
			if ($(this).find("td").eq(4).text() == 'Pending') {
				$('#social_work_pending').prop('checked', true);
				$('#social_work_no_pending').prop('checked', false);
			} else {
				$('#social_work_no_pending').prop('checked', true);
				$('#social_work_pending').prop('checked', false);
			}
			$('#btn-clear-social-work').text("DELETE");
		});
		// CaribeDevelopers: Function to select a row form table MDC
		$('#dataRequest-mdc tr').click(function() {
			$(this).closest('tr').siblings().children('td, th').css('background-color', '');
			$(this).children('td, th').css('background-color', '#cccccc');
			//alert($(this).find("td").eq(4).find("[name='id_mdc']").val());
			$('#id_mdc').val($(this).find("td").eq(4).find("[name='id_mdc']").val());
			$('#mdc_date').val($(this).find("td").eq(1).text());
			$('#mdc_reason').val($(this).find("td").eq(2).text());
			$('#mdc_class').val($(this).find("td").eq(3).text());
			$('#mdc_observation').val($(this).find("td").eq(4).find("[name='observations_val']").val());
			if ($(this).find("td").eq(4).text() == 'Pending') {
				$('#mdc_pending').prop('checked', true);
				$('#mdc_no_pending').prop('checked', false);
			} else {
				$('#mdc_no_pending').prop('checked', true);
				$('#mdc_pending').prop('checked', false);
			}
			$('#btn-clear-mdc').text("DELETE");
		});
		// CaribeDevelopers: Function to select a row form table Test
		$('#dataRequest-test tr').click(function() {
			$(this).closest('tr').siblings().children('td, th').css('background-color', '');
			$(this).children('td, th').css('background-color', '#cccccc');
			//alert($(this).find("td").eq(3).find("[name='id_test']").val());
			$('#id_test').val($(this).find("td").eq(3).find("[name='id_test']").val());
			$('#test_date').val($(this).find("td").eq(0).text());
			$('#test_type').val($(this).find("td").eq(1).text());
			$('#test_class').val($(this).find("td").eq(2).text());
			$('#test_observation').val($(this).find("td").eq(3).text());
			$('#btn-clear-test').text("DELETE");
		});
		// CaribeDevelopers: Function to select a row form table contact
		$('#dataRequest-contact tr').click(function() {
			$(this).closest('tr').siblings().children('td, th').css('background-color', '');
			$(this).children('td, th').css('background-color', '#cccccc');
			$('#id_contact').val($(this).find("[name='id_contact']").val());
			if ($(this).find("i").eq(0).hasClass("fa fa-check")) {
				$('#tutor').val(1);
			} else {
				$('#tutor').val(0);
			}
			$('#type').val($(this).find("td").eq(1).text());
			$('#full_name').val($(this).find("td").eq(2).text());
			$('#mobile_phone').val($(this).find("td").eq(4).text());
			$('#address').val($(this).find("td").eq(5).text());
			$('#id_number_contact').val($(this).find("td").eq(4).text());
			$('#company').val($(this).find("td").eq(7).text());
			$('#position_company').val($(this).find("td").eq(8).text());
			$('#email').val($(this).find("td").eq(3).text());
			$('#home_phone').val($(this).find("[name='home_phone_contact']").val());
			$('#work_phone').val($(this).find("[name='work_phone_contact']").val());
			$('#work_phone_ext').val($(this).find("[name='work_phone_ext_contact']").val());
			$('#observation').val($(this).find("[name='observations_contact']").val());
			$('#id_number_contact').val($(this).find("[name='id_number_contact']").val());
			$('#btn-clear-contact').text("DELETE");
		});
		/*Jquery Event Caribe Developer*/
		$('#dataRequest-event tr').click(function() {
			$(this).closest('tr').siblings().children('td, th').css('background-color', '');
			$(this).children('td, th').css('background-color', '#cccccc');
			// alert($(this).find("td").eq(5).text());
			$("#event_date").val($(this).find("td").eq(0).text());
			$("#due_date").val($(this).find("td").eq(1).text());
			$("#reason").val($(this).find("td").eq(2).text());
			$("#involved").val($(this).find("td").eq(3).text());
			$('#observation_event').val($(this).find("td").eq(4).text());
			if ($(this).find("td").eq(5).text() == "1") {
				$('#event_private').prop('checked', true);
				$('#event_no_private').prop('checked', false);
			} else {
				$('#event_private').prop('checked', false);
				$('#event_no_private').prop('checked', true);
			}
			if ($(this).find("td").eq(6).text() == "1") {
				$('#important_notice').prop('checked', true);
				$('#no_important_notice').prop('checked', false);
			} else {
				$('#important_notice').prop('checked', false);
				$('#no_important_notice').prop('checked', true);
			}
			$("#id_event").val($(this).find("td").eq(7).text());
			$("#id_student").val($(this).find("td").eq(8).text());
			$('#btn-clear-event').val("DELETE");
		});

		$('#dataRequest-remedial tr').click(function() {
			$(this).closest('tr').siblings().children('td, th').css('background-color', '');
			$(this).children('td, th').css('background-color', '#cccccc');
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

	function refresremediadetail() {
		console.log('****************');
	}
	// CaribeDevelopers: Begin function code to view a table Remedial Detail
	function go_remedial_detail(id_remedial_val) {
		$('#formAddRemedialDetail').find("[name='id_remedial_post']").val($('#id_remedial').val());
		$('#div_remedial_detail').show();
		$('#div_remedial').hide();
		$.get("ajax/getremedialdetaillist_tabel.php ", {
			id_remedial: id_remedial_val
		}, function(data) {
			$('#id_remedial_detail').val("0");
			$('#div_table_remedial_detail').empty();
			console.log('cleaning');
			console.log(data);
			$("#div_table_remedial_detail").append(data);
		});
	}

	$('#btn_leerling_detail_zoeken').click(function() {

		if ($('#studenten_list option:selected').val() === "Select One Student") {
			alert("Please, select one student");
		} else {
			var idfamily = $('#studenten_list option:selected').attr('family');
			var idstudent = $('#studenten_list option:selected').val();
			// reload
			location = 'leerlingdetail.php' + '?id=' + idstudent + "&id_family=" + idfamily;

		}
	});
	// CaribeDevelopers: End function code to view a table Remedial Detail



	//function to cijfers and houding dinamic by year
	$("#_2022_2023_cijfer").on('click', function() {

		if ($('#_2022_2023_cijfer').prop("checked", true)) {
			get_cijfer_by_schooljaar("2022-2023");
			$('#schooljaar_selected').val('2022-2023');
			$('#_2018_2019_cijfer').val(0);
			$('#_2017_2018_cijfer').val(0);
			$('#_2016_2017_cijfer').val(0);
			$('#_2019_2020_cijfer').val(0);
			$('#_2020_2021_cijfer').val(0);
			$('#_2021_2022_cijfer').val(0);
			$('#_2022_2023_cijfer').val(1);

		}
	});

	$("#_2021_2022_cijfer").on('click', function() {

		if ($('#_2021_2022_cijfer').prop("checked", true)) {
			get_cijfer_by_schooljaar("2021-2022");
			$('#schooljaar_selected').val('2021-2022');
			$('#_2018_2019_cijfer').val(0);
			$('#_2017_2018_cijfer').val(0);
			$('#_2016_2017_cijfer').val(0);
			$('#_2019_2020_cijfer').val(0);
			$('#_2020_2021_cijfer').val(0);
			$('#_2021_2022_cijfer').val(1);
			$('#_2022_2023_cijfer').val(0);


		}
	});

	$("#_2020_2021_cijfer").on('click', function() {

		if ($('#_2020_2021_cijfer').prop("checked", true)) {
			get_cijfer_by_schooljaar("2020-2021");
			$('#schooljaar_selected').val('2020-2021');
			$('#_2018_2019_cijfer').val(0);
			$('#_2017_2018_cijfer').val(0);
			$('#_2016_2017_cijfer').val(0);
			$('#_2019_2020_cijfer').val(0);
			$('#_2020_2021_cijfer').val(1);
			$('#_2021_2022_cijfer').val(0);
			$('#_2022_2023_cijfer').val(0);

		}
	});

	$("#_2019_2020_cijfer").on('click', function() {

		if ($('#_2019_2020_cijfer').prop("checked", true)) {
			get_cijfer_by_schooljaar("2019-2020");
			$('#schooljaar_selected').val('2019-2020');
			$('#_2018_2019_cijfer').val(0);
			$('#_2017_2018_cijfer').val(0);
			$('#_2016_2017_cijfer').val(0);
			$('#_2019_2020_cijfer').val(1);
			$('#_2020_2021_cijfer').val(0);
			$('#_2021_2022_cijfer').val(0);
			$('#_2022_2023_cijfer').val(0);

		}
	});


	$("#_2018_2019_cijfer").on('click', function() {
		if ($('#2018_2019_cijfer').prop("checked", true)) {
			get_cijfer_by_schooljaar("2018-2019");
			$('#schooljaar_selected').val('2018-2019');
			$('#_2018_2019_cijfer').val(1);
			$('#_2017_2018_cijfer').val(0);
			$('#_2016_2017_cijfer').val(0);
			$('#_2019_2020_cijfer').val(0);
			$('#_2020_2021_cijfer').val(0);
			$('#_2021_2022_cijfer').val(0);
			$('#_2022_2023_cijfer').val(0);

		}

	});
	$("#_2017_2018_cijfer").on('click', function() {
		if ($('#2017_2018_cijfer').prop("checked", true)) {
			get_cijfer_by_schooljaar("2017-2018");
			$('#schooljaar_selected').val('2017-2018');
			$('#_2017_2018_cijfer').val(1);
			$('#_2016_2017_cijfer').val(0);
			$('#_2018_2019_cijfer').val(0);
			$('#_2019_2020_cijfer').val(0);
			$('#_2020_2021_cijfer').val(0);
			$('#_2021_2022_cijfer').val(0);
			$('#_2022_2023_cijfer').val(0);
		}

	});
	$("#_2016_2017_cijfer").on('click', function() {

		if ($('#2016_2017_cijfer').prop("checked", true)) {
			get_cijfer_by_schooljaar("2016-2017");
			$('#schooljaar_selected').val('2016-2017');
			$('#_2017_2018_cijfer').val(0);
			$('#_2016_2017_cijfer').val(1);
			$('#_2018_2019_cijfer').val(0);
			$('#_2019_2020_cijfer').val(0);
			$('#_2020_2021_cijfer').val(0);
			$('#_2021_2022_cijfer').val(0);
			$('#_2022_2023_cijfer').val(0);
		}

	});

	// HOUDING BY YEAR
	$("#_2021_2022_houding").on('click', function() {
		if ($('#2021_2022_houding').prop("checked", true)) {
			get_houding_by_schooljaar("2021-2022");
			$('#schooljaar_selected').val('2021-2022');
			$('#_2021_2022_houding').val(1);
			$('#_2020_2021_houding').val(0);
			$('#_2019_2020_houding').val(0);
			$('#_2018_2019_houding').val(0);
			$('#_2016_2017_houding').val(0);
			$('#_2017_2018_houding').val(0);
		}
	});

	$("#_2020_2021_houding").on('click', function() {
		if ($('#2020_2021_houding').prop("checked", true)) {
			get_houding_by_schooljaar("2020-2021");
			$('#schooljaar_selected').val('2020-2021');
			$('#_2020_2021_houding').val(1);
			$('#_2019_2020_houding').val(0);
			$('#_2018_2019_houding').val(0);
			$('#_2016_2017_houding').val(0);
			$('#_2017_2018_houding').val(0);
			$('#_2021_2022_houding').val(0);
		}
	});

	$("#_2019_2020_houding").on('click', function() {
		if ($('#2019_2020_houding').prop("checked", true)) {
			get_houding_by_schooljaar("2019-2020");
			$('#schooljaar_selected').val('2019-2020');
			$('#_2019_2020_houding').val(1);
			$('#_2018_2019_houding').val(0);
			$('#_2016_2017_houding').val(0);
			$('#_2017_2018_houding').val(0);
			$('#_2021_2022_houding').val(0);
		}
	});


	$("#_2018_2019_houding").on('click', function() {
		if ($('#2018_2019_houding').prop("checked", true)) {
			get_houding_by_schooljaar("2018-2019");
			$('#schooljaar_selected').val('2018-2019');
			$('#_2018_2019_houding').val(1);
			$('#_2016_2017_houding').val(0);
			$('#_2017_2018_houding').val(0);
			$('#_2019_2020_houding').val(0);
			$('#_2021_2022_houding').val(0);
		}
	});

	$("#_2017_2018_houding").on('click', function() {
		if ($('#2017_2018_houding').prop("checked", true)) {
			get_houding_by_schooljaar("2017-2018");
			$('#schooljaar_selected').val('2017-2018');
			$('#_2017_2018_houding').val(1);
			$('#_2016_2017_houding').val(0);
			$('#_2018_2019_houding').val(0);
			$('#_2019_2020_houding').val(0);
			$('#_2021_2022_houding').val(0);
		}

	});
	$("#_2016_2017_houding").on('click', function() {

		if ($('#2016_2017_houding').prop("checked", true)) {
			$('#schooljaar_selected').val('2016-2017');
			get_houding_by_schooljaar("2016-2017");
			$('#_2017_2018_houding').val(0);
			$('#_2016_2017_houding').val(1);
			$('#_2018_2019_houding').val(0);
			$('#_2019_2020_houding').val(0);
			$('#_2021_2022_houding').val(0);
		}

	});


	//VERZUIM BY year

	$("#_2022_2023_verzuim").on('click', function() {
		if ($('#_2022_2023_verzuim').prop("checked", true)) {
			get_verzuim_by_schooljaar("2022-2023");
			$('#schooljaar_selected').val('2022-2023');
			$('#_2017_2018_verzuim').val(0);
			$('#_2016_2017_verzuim').val(0);
			$('#_2018_2019_verzuim').val(0);
			$('#_2019_2020_verzuim').val(0);
			$('#_2020_2021_verzuim').val(0);
			$('#_2021_2022_verzuim').val(0);
			$('#_2022_2023_verzuim').val(1);
		}
	});

	$("#_2021_2022_verzuim").on('click', function() {
		if ($('#_2021_2022_verzuim').prop("checked", true)) {
			get_verzuim_by_schooljaar("2021-2022");
			$('#schooljaar_selected').val('2021-2022');
			$('#_2017_2018_verzuim').val(0);
			$('#_2016_2017_verzuim').val(0);
			$('#_2018_2019_verzuim').val(0);
			$('#_2019_2020_verzuim').val(0);
			$('#_2020_2021_verzuim').val(0);
			$('#_2021_2022_verzuim').val(1);
		}
	});


	$("#_2020_2021_verzuim").on('click', function() {
		if ($('#_2020_2021_verzuim').prop("checked", true)) {
			get_verzuim_by_schooljaar("2020-2021");
			$('#schooljaar_selected').val('2020-2021');
			$('#_2017_2018_verzuim').val(0);
			$('#_2016_2017_verzuim').val(0);
			$('#_2018_2019_verzuim').val(0);
			$('#_2019_2020_verzuim').val(0);
			$('#_2020_2021_verzuim').val(1);
			$('#_2021_2022_verzuim').val(0);
		}
	});


	$("#_2019_2020_verzuim").on('click', function() {
		if ($('#_2019_2020_verzuim').prop("checked", true)) {
			get_verzuim_by_schooljaar("2019-2020");
			$('#schooljaar_selected').val('2019-2020');
			$('#_2017_2018_verzuim').val(0);
			$('#_2016_2017_verzuim').val(0);
			$('#_2018_2019_verzuim').val(0);
			$('#_2019_2020_verzuim').val(0);
			$('#_2020_2021_verzuim').val(0);
			$('#_2021_2022_verzuim').val(0);
		}
	});

	$("#_2018_2019_verzuim").on('click', function() {
		if ($('#2018_2019_verzuim').prop("checked", true)) {
			get_verzuim_by_schooljaar("2018-2019");
			$('#schooljaar_selected').val('2018-2019');
			$('#_2017_2018_verzuim').val(0);
			$('#_2016_2017_verzuim').val(0);
			$('#_2018_2019_verzuim').val(1);
			$('#_2019_2020_verzuim').val(0);
			$('#_2020_2021_verzuim').val(0);
			$('#_2021_2022_verzuim').val(0);
		}
	});

	$("#_2017_2018_verzuim").on('click', function() {
		if ($('#2017_2018_verzuim').prop("checked", true)) {
			get_verzuim_by_schooljaar("2017-2018");
			$('#schooljaar_selected').val('2017-2018');
			$('#_2017_2018_verzuim').val(1);
			$('#_2016_2017_verzuim').val(0);
			$('#_2018_2019_verzuim').val(0);
			$('#_2019_2020_verzuim').val(0);
			$('#_2020_2021_verzuim').val(0);
			$('#_2021_2022_verzuim').val(0);
		}

	});
	$("#_2016_2017_verzuim").on('click', function() {

		if ($('#2016_2017_verzuim').prop("checked", true)) {
			get_verzuim_by_schooljaar("2016-2017");
			$('#schooljaar_selected').val('2016-2017');
			$('#_2017_2018_verzuim').val(0);
			$('#_2016_2017_verzuim').val(1);
			$('#_2018_2019_verzuim').val(0);
			$('#_2019_2020_verzuim').val(0);
			$('#_2020_2021_verzuim').val(0);
			$('#_2021_2022_verzuim').val(0);
		}

	});
	//AVI BY year
	$("#_2019_2020_avi").on('click', function() {
		if ($('#2019_2020_avi').prop("checked", true)) {
			get_avi_by_schooljaar("2019-2020");
			$('#schooljaar_selected').val('2019-2020');
			$('#_2019_2020_avi').val(1);
			$('#_2018_2019_avi').val(0);
			$('#_2017_2018_avi').val(0);
			$('#_2016_2017_avi').val(0);
		}
	});


	$("#_2018_2019_avi").on('click', function() {
		if ($('#2018_2019_avi').prop("checked", true)) {
			get_avi_by_schooljaar("2018-2019");
			$('#schooljaar_selected').val('2018-2019');
			$('#_2018_2019_avi').val(1);
			$('#_2017_2018_avi').val(0);
			$('#_2016_2017_avi').val(0);
			$('#_2019_2020_avi').val(0);
		}

	});
	$("#_2017_2018_avi").on('click', function() {
		if ($('#2017_2018_avi').prop("checked", true)) {
			get_avi_by_schooljaar("2017-2018");
			$('#schooljaar_selected').val('2017-2018');
			$('#_2018_2019_avi').val(0);
			$('#_2017_2018_avi').val(1);
			$('#_2016_2017_avi').val(0);
			$('#_2019_2020_avi').val(0);
		}

	});
	$("#_2016_2017_avi").on('click', function() {

		if ($('#2016_2017_avi').prop("checked", true)) {
			get_avi_by_schooljaar("2016-2017");
			$('#schooljaar_selected').val('2016-2017');
			$('#_2018_2019_avi').val(0);
			$('#_2017_2018_avi').val(0);
			$('#_2016_2017_avi').val(1);
			$('#_2019_2020_avi').val(0);

		}
	});

	$('input[type=radio]').on('change', function() {
		$('input[type=radio]').not(this).prop('checked', false);
	});

	function get_cijfer_by_schooljaar(schooljaar) { // Cijfers

		$.get("ajax/getleerlingdetail_cijfers_tabel_mobile.php?id=" + getParam("id") + "&schoolJaar=" + schooljaar, {}, function(data) {
			$("#cijfer_by_year").empty();
			$("#cijfer_by_year").html(data);
		});
	}

	function get_houding_by_schooljaar(schooljaar) { // Cijfers

		$.get("ajax/getleerlingdetail_houding_tabel_mobile_leerlingdetail.php?id=" + getParam("id") + "&schoolJaar=" + schooljaar, {}, function(data) {
			$("#houding_by_year").empty();;
			$("#houding_by_year").html(data);
		});


	}

	function get_verzuim_by_schooljaar(schooljaar) { // Cijfers
		$.get("ajax/getleerlingdetail_verzuim_tabel_mobile_by_year.php?id=" + getParam("id") + "&schoolJaar=" + schooljaar, {}, function(data) {
			$("#verzuim_by_year").empty();;
			$("#verzuim_by_year").html(data);
		});
	}

	function get_avi_by_schooljaar(schooljaar) { // Cijfers

		$.get("ajax/getleerlingdetail_avi_tabel_mobile_leerlingdetail.php?id=" + getParam("id") + "&schoolJaar=" + schooljaar, {}, function(data) {
			$("#avi_by_schooljaar").empty();;
			$("#avi_by_schooljaar").html(data);
		});

	}

	$("#btn_rapport_export_by_student").click(function(e) {
		e.preventDefault();
		var SchoolID = '<?php echo $_SESSION["SchoolID"]; ?>';
		var id_student = $("#rapport_klassen_lijst option:selected").val();

		if (klas != 'NONE') {
			$.ajax({
				url: "ajax/check_setting.php",
				data: "SchoolID=" + SchoolID,
				type: 'POST',
				//dataType: "HTML",
				cache: false,
				async: true,
				success: function(data) {
					if (parseInt(data) == 1) {
						// alert($('#id_student').val());
						// alert($('#schooljaar_rapport_excel_by_student').val());

						$('#message_rapport').addClass('hidden');
						$('#klas_message_rapport').addClass('hidden');
						$('#setting_message_rapport').addClass('hidden');
						$('#popup_message_rapport').removeClass('hidden');
						window.open("dev_tests\\export_rapport_by_student.php?id_student=" + $('#id_student').val() + "&schooljaar_rapport=" + $('#schooljaar_rapport_excel_by_student').val());
					} else {
						$('#message_rapport').addClass('hidden');
						$('#klas_message_rapport').addClass('hidden');
						$('#popup_message_rapport').addClass('hidden');
						$('#setting_message_rapport').removeClass('hidden');
						alert('There should be a Setting created for this schooljaar to generate a rapport...');
					}
				}
			});
		} else {
			$('#message_rapport').addClass('hidden');
			$('#setting_message_rapport').addClass('hidden');
			$('#popup_message_rapport').addClass('hidden');
			$('#klas_message_rapport').removeClass('hidden');
			//alert('You must select one Klas to export rapport...');
		}
	});


	if ($('.telerik-plugin').length) {

		Modernizr.load({
			test: $.kendoChart,
			nope: [WebApi.Config.baseUri + 'assets/telerik/styles/kendo.common.min.css', WebApi.Config.baseUri + 'assets/telerik/styles/kendo.default.min.css', WebApi.Config.baseUri + 'assets/telerik/js/kendo.all.min.js'],
			complete: function executeTelerik() {
				var studentID = <?php echo $_GET["id"]; ?>;

				if ($('#chart_absentie_by_student').length) {

					$("#chart_absentie_by_student").kendoChart({
						dataSource: {
							transport: {
								read: {
									url: "ajax/get_absentie_by_student.php?id=" + studentID,
									dataType: "json"
								}
							}
						},
						title: {
							text: "Absentie"
						},
						series: [{
							field: "absentie_of_class",
							name: "Klas",
							color: "#8AD6E2"
						}, {
							field: "absentie_of_student",
							name: "Student",
							color: "#FFDC66"
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

				};

				if ($('#chart_laat_by_student').length) {

					$("#chart_laat_by_student").kendoChart({
						dataSource: {
							transport: {
								read: {
									url: "ajax/get_laat_by_student.php?id=" + studentID,
									dataType: "json"
								}
							}
						},
						title: {
							text: "Te Laat"
						},
						series: [{
							field: "laat_of_class",
							name: "Klas",
							color: "#8AD6E2"
						}, {
							field: "laat_of_student",
							name: "Student",
							color: "#FFDC66"
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

				};

				if ($('#chart_uitgestuurd_by_student').length) {

					$("#chart_uitgestuurd_by_student").kendoChart({
						dataSource: {
							transport: {
								read: {
									url: "ajax/get_uitgestuurd_by_student.php?id=" + studentID,
									dataType: "json"
								}
							}
						},
						title: {
							text: uitsturen
						},
						series: [{
							field: "uitgestuurd_of_class",
							name: "Klas",
							color: "#8AD6E2"
						}, {
							field: "uitgestuurd_of_student",
							name: "Student",
							color: "#FFDC66"
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

				};

				if ($('#chart_huiswerk_by_student').length) {


					$("#chart_huiswerk_by_student").kendoChart({
						dataSource: {
							transport: {
								read: {
									url: "ajax/get_huiswerk_by_student.php?id=" + studentID,
									dataType: "json"
								}
							}
						},
						title: {
							text: "Geen huiswerk"
						},
						series: [{
							field: "huiswerk_of_class",
							name: "Klas",
							color: "#8AD6E2"
						}, {
							field: "huiswerk_of_student",
							name: "Student",
							color: "#FFDC66"
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

				};


				if ($('#graph-three-periods_by_student').length) {


					$("#graph-three-periods_by_student").kendoChart({
						dataSource: {
							transport: {
								read: {
									//url: "ajax/cijfers-graph.php",
									url: "ajax/getcijfersgraph_by_student.php",
									dataType: "json",
									data: [studentID]
								}
							},
							sort: {
								field: "vakken"
							}
						},
						title: {
							text: ""
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
				}

				if ($('#graph-three-periods_by_class').length) {

					$("#graph-three-periods_by_class").kendoChart({
						dataSource: {
							transport: {
								read: {
									//url: "ajax/cijfers-graph.php",
									url: "ajax/getcijfersgraph_by_class.php",
									dataType: "json",
									data: [studentID]
								}
							},
							sort: {
								field: "vakken"
							}
						},
						title: {
							text: ""
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
							format: "{0:N0}"
						}
					});
				}
			}
		});
	}


	$('#form-new_forms').on("submit", function(e) {
		/* prevent refresh */
		e.preventDefault();
		$('#klas_student_new_forms').val($('#klas').text());
		if ($("#new_form_file_to_upload").val() === 0) {
			$(this).find('.alert-error').removeClass('hidden');
		} else {
			$.ajax({
				url: "ajax/upload_new_form_leerling.php",
				// data: $('#form_woord_rapport_files').serialize(),
				cache: false,
				contentType: false,
				processData: false,
				data: new FormData(this),
				type: "POST",
				// dataType: "text",
				success: function(text) {
					if (text != 1) {
						alert('error uploading new_forms')
					} else {
						var urlget = "ajax/getdocuments_new_form.php?id=" + $('#id_student').val();
						alert('Document upload successfully!');
						$('#description_new_forms').text('');
						$('#description_new_forms').val('');
						$.get("ajax/addaudit_document.php"),

							$.get(urlget, {},
								function(data) {
									$('#document_list_new_forms').empty();
									$("#document_list_new_forms").append(data);
								}
							);
						// Clear all object of form except class
						$('#description').val('');

					}
				},
				error: function(xhr, status, errorThrown) {
					console.log("error");
				},
				complete: function(xhr, status) {
					// $('html, body').animate({scrollTop:0}, 'fast');
				}
			});
		}
	});


	$('#btn_delete_new_forms').click(function(e) {
		// DELETE a document
		var r = confirm("Delete document?");
		if (r) {

			// For each checked element
			var sList = "";
			$('input[type=checkbox]').each(function() {

				if (this.name != "") {
					var _document_id = '';
					var _document_name = '';
					/* begin post */
					if (this.checked) {
						_document_id = $(this).val();
						_document_name = $(this).attr("doc");
						_document_klas = $(this).attr("klas");

						$.post(
							"ajax/deletedocument_new_forms.php", {
								document_id: _document_id,
								document_name: _document_name,
								document_klas: _document_klas
							},

							function(data) {
								console.log(data);
							}
						).done(function(data) {
							/* it's done */
							if (data == 1) {
								alert('Document deleted successfully!');
								var urlget = "ajax/getdocuments_new_form.php?id=" + $('#id_student').val();
								$.get(urlget, {}, function(data) {
									$('#document_list_new_forms').empty();
									$("#document_list_new_forms").append(data);
								});

							} else {
								$('#form_woord_rapport_files').find('.alert-error').removeClass('hidden');
								$('#form_woord_rapport_files').find('.alert-info').addClass('hidden');
								$('#form_woord_rapport_files').find('.alert-warning').addClass('hidden');
							};

						}).fail(function() {
							alert('Error, please contact developers.');
						});

					}

				}
			});
		}
	});
</script>
<!-- FIN CODE CaribeDevelopers SOCIAL WORK -->