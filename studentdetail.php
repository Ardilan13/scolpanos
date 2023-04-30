<?php


	require_once("classes/leerling.php");

	$l = new leerling();

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


<?php include 'document_start.php'; ?>
	
	<?php include 'sub_nav.php'; ?>

	<div class="push-content-220">
		<?php include 'header.php'; ?>
		<main id="main" role="main">
			<section>
				<div class="container container-fs">
					<div class="row">
						<div class="default-secondary-bg-color col-md-12 inset brd-bottom">
							<h1 class="primary-color">Leerling Details</h1>
							<?php include 'breadcrumb.php'; ?>
							<form id="form-ll-details" class="form-inline form-data-retriever" name="filter-vak" role="form">
								<fieldset>
									<div class="form-group">
										<label for="studenten_lijst">Kies Student: </label>
										<select id="studenten_lijst" class="form-control" name="studenten_lijst"></select>
									</div>
									<div class="form-group">
										<button data-display="data-display" data-ajax-href="ajax/avi_data.php" type="submit" class="btn btn-primary btn-m-w btn-m-h">zoeken</button>
									</div>
								</fieldset>
							</form>
						</div>
					</div>
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
										<div class="row double-inset">
											<div class="col-md-1">
												<img src="assets/img/profile_yoda.jpg" class="img-thumbnail img-responsive" alt="{alternative}">
											</div>	
											<div class="col-md-11">
												<table class="table table-bordered table-colored">
													<tbody>
														<tr>
															<td><strong>Student nr.</strong></td>
															<td><?php print $studentnumber ?></td>
															<td><strong>Klas</strong></td>
															<td><?php print $klas ?></td>															
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
															<td><?php print $geboortedatum ?></td>
															
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
															<a data-toggle="tab" role="tab" aria-controls="tab2" href="#tab2" id="covernote-btn" class="btn btn-default btn-m-w btn-m-h">
																Contact
															</a>
														</li>
														<li class="disable" role="presentation">
															<a data-toggle="tab" role="tab" aria-controls="tab3" href="#tab3" id="attachfiles-btn" class="btn btn-default btn-m-w btn-m-h">
																Medisch
															</a>
														</li>
														<li class="disable" role="presentation">
															<a data-toggle="tab" role="tab" aria-controls="tab4" href="#tab4" id="preview-btn" class="btn btn-default btn-m-w btn-m-h">
																Nota's
															</a>
														</li>
														<li class="disable" role="presentation">
															<a data-toggle="tab" role="tab" aria-controls="tab4" href="#tab4" id="preview-btn" class="btn btn-default btn-m-w btn-m-h">
																Cijfers	
															</a>
														</li>
														<li class="disable" role="presentation">
															<a data-toggle="tab" role="tab" aria-controls="tab4" href="#tab4" id="preview-btn" class="btn btn-default btn-m-w btn-m-h">
																Logboek
															</a>
														</li>
														<li class="disable" role="presentation">
															<a data-toggle="tab" role="tab" aria-controls="tab4" href="#tab4" id="preview-btn" class="btn btn-default btn-m-w btn-m-h">
																Verzuim
															</a>
														</li>
														<li class="disable" role="presentation">
															<a data-toggle="tab" role="tab" aria-controls="tab4" href="#tab4" id="preview-btn" class="btn btn-default btn-m-w btn-m-h">
																Houding		
															</a>
														</li>
														<li class="disable" role="presentation">
															<a data-toggle="tab" role="tab" aria-controls="tab4" href="#tab4" id="preview-btn" class="btn btn-default btn-m-w btn-m-h">
																Social emotioneel		
															</a>
														</li>
														<li class="disable" role="presentation">
															<a data-toggle="tab" role="tab" aria-controls="tab4" href="#tab4" id="preview-btn" class="btn btn-default btn-m-w btn-m-h">
																Extra		
															</a>
														</li>
														<li class="disable" role="presentation">
															<a data-toggle="tab" role="tab" aria-controls="tab4" href="#tab4" id="preview-btn" class="btn btn-default btn-m-w btn-m-h">
																SMW		
															</a>
														</li>
														<li class="disable" role="presentation">
															<a data-toggle="tab" role="tab" aria-controls="tab4" href="#tab4" id="preview-btn" class="btn btn-default btn-m-w btn-m-h">
																MDC		
															</a>
														</li>
													</ul>
												</div>
												<div class="col-md-10 reset-pdng-left">
													<div class="tab-content sixth-bg-color full-inset">
														<div role="tabpanel" class="tab-pane active" id="tab1">
															<div class="table-responsive">
																<table id="dataRequest" class="table table-bordered table-colored" data-table="yes">
																	<tbody>
																		<tr>
																			<td class="bold">Roepnaam</td>
																			<td><span id="lblName1" data-student-id="10123" data-column="8" data-row="2" class="editable">Rudy</span></td>
																			<td class="bold">Voorletter</td>
																			<td><span id="lblName2" data-student-id="10123" data-column="8" data-row="2" class="editable">RA</span></td>
																		</tr>
																		<tr>
																			<td class="bold">Adres</td>
																			<td><span id="lblName3" data-student-id="10123" data-column="8" data-row="2" class="editable">Tanki Flip 64B</span></td>																		
																			<td class="bold">Tel. nr (thuis)</td>
																			<td><span id="lblName1" data-student-id="10123" data-column="8" data-row="2" class="editable">5876265</span></td>
																		</tr>
																		<tr>
																			<td class="bold">Mobiel</td>
																			<td><span id="lblName2" data-student-id="10123" data-column="8" data-row="2" class="editable">5642542</span></td>
																			<td class="bold">Verzorger na schooltijd</td>
																			<td><span id="lblName3" data-student-id="10123" data-column="8" data-row="2" class="editable">Rita Meulenkamp</span></td>
																		</tr>
																		<tr>
																			<td class="bold">Telefoon noodgeval</td>
																			<td><span id="lblName1" data-student-id="10123" data-column="8" data-row="2" class="editable">5618445</span></td>
																			<td class="bold">Geboorte plaats</td>
																			<td><span id="lblName2" data-student-id="10123" data-column="8" data-row="2" class="editable">Aruba</span></td>
																		</tr>
																		<tr>
																			<td class="bold">Nationaliteiten</td>
																			<td><span id="lblName3" data-student-id="10123" data-column="8" data-row="2" class="editable">Nederlands</span></td>
																			<td class="bold">Burgelijke staat</td>
																			<td><span id="lblName1" data-student-id="10123" data-column="8" data-row="2" class="editable">getrouwd</span></td>
																		</tr>
																		<tr>
																			<td class="bold">AZV nr.</td>
																			<td><span id="lblName2" data-student-id="10123" data-column="8" data-row="2" class="editable">12345-AA</span></td>
																			<td class="bold">Expire datum AZV</td>
																			<td><span id="lblName3" data-student-id="10123" data-column="8" data-row="2" class="editable">28-02-2019</span></td>
																		</tr>
																		<tr>
																			<td class="bold">Identiteits nr.</td>
																			<td><span id="lblName1" data-student-id="10123" data-column="8" data-row="2" class="editable">987456123BA</span></td>
																			<td class="bold">Voertaal</td>
																			<td><span id="lblName2" data-student-id="10123" data-column="8" data-row="2" class="editable">12345-AA</span></td>
																		</tr>
																		<tr>
																			<td class="bold">Expire datum AZV</td>
																			<td><span id="lblName3" data-student-id="10123" data-column="8" data-row="2" class="editable">28-02-2019</span></td>
																		
																			<td class="bold">Email adres</td>
																			<td><span id="lblName1" data-student-id="10123" data-column="8" data-row="2" class="editable">hello@rudycroes.com</span></td>
																		</tr>
																		<tr>
																			<td class="bold">Datum inschrijving</td>
																			<td><span id="lblName2" data-student-id="10123" data-column="8" data-row="2" class="editable">01-08-2015</span></td>
																			<td class="bold">Vorige school</td>
																			<td><span id="lblName3" data-student-id="10123" data-column="8" data-row="2" class="editable">Kleuterschool</span></td>
																		</tr>
																	</tbody>
																</table>
															</div>
														</div>
														<div role="tabpane2" class="tab-pane" id="tab2">
															<div class="table-responsive">
																<table class="table table-bordered table-colored" data-table="yes">
																	<thead>
																		<tr>
																			<th>Client #</th>
																			<th>Full name</th>
																			<th>Address</th>
																			<th>ID/Passport</th>
																			<th>Email</th>
																			<th>Tel. work</th>												
																			<th >Customer Status</th>
																			<th>Action</th>								
																		</tr>
																	</thead>
																	<tbody>
																		<tr role="row" class="odd"><td class="sorting_1">7</td><td>Rudy Croes</td><td>Tanki Flip 22</td><td>AB1232456</td><td>hello@rudycroes.com</td><td>5624524</td><td>Active</td><td><a class="link quaternary-color" href="http://192.168.1.211/loans/clientinfo.php?id=7">show more <i class="fa fa-angle-double-right quaternary-color"></i></a></td></tr>
																		<tr role="row" class="odd"><td class="sorting_1">7</td><td>Rudy Croes</td><td>Tanki Flip 22</td><td>AB1232456</td><td>hello@rudycroes.com</td><td>5624524</td><td>Active</td><td><a class="link quaternary-color" href="http://192.168.1.211/loans/clientinfo.php?id=7">show more <i class="fa fa-angle-double-right quaternary-color"></i></a></td></tr>
																		<tr role="row" class="odd"><td class="sorting_1">7</td><td>Rudy Croes</td><td>Tanki Flip 22</td><td>AB1232456</td><td>hello@rudycroes.com</td><td>5624524</td><td>Active</td><td><a class="link quaternary-color" href="http://192.168.1.211/loans/clientinfo.php?id=7">show more <i class="fa fa-angle-double-right quaternary-color"></i></a></td></tr>
																		<tr role="row" class="odd"><td class="sorting_1">7</td><td>Rudy Croes</td><td>Tanki Flip 22</td><td>AB1232456</td><td>hello@rudycroes.com</td><td>5624524</td><td>Active</td><td><a class="link quaternary-color" href="http://192.168.1.211/loans/clientinfo.php?id=7">show more <i class="fa fa-angle-double-right quaternary-color"></i></a></td></tr>
																		<tr role="row" class="odd"><td class="sorting_1">7</td><td>Rudy Croes</td><td>Tanki Flip 22</td><td>AB1232456</td><td>hello@rudycroes.com</td><td>5624524</td><td>Active</td><td><a class="link quaternary-color" href="http://192.168.1.211/loans/clientinfo.php?id=7">show more <i class="fa fa-angle-double-right quaternary-color"></i></a></td></tr>
																		<tr role="row" class="odd"><td class="sorting_1">7</td><td>Rudy Croes</td><td>Tanki Flip 22</td><td>AB1232456</td><td>hello@rudycroes.com</td><td>5624524</td><td>Active</td><td><a class="link quaternary-color" href="http://192.168.1.211/loans/clientinfo.php?id=7">show more <i class="fa fa-angle-double-right quaternary-color"></i></a></td></tr>
																	</tbody>
																</table>
															</div>
														</div>
														<div role="tabpane3" class="tab-pane" id="tab3">
															<p>tab 3</p>
														</div>
														<div role="tabpane4" class="tab-pane" id="tab4">
															<p>tab 4</p>
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
			</section>			
		</main>
		<?php include 'footer.php'; ?>
	</div>

<?php include 'document_end.php'; ?>