<?php include 'document_start.php'; ?>
	
	<?php include 'sub_nav.php'; ?>

	<div class="push-content-220">
		<main id="main" role="main">
			<?php include 'header.php'; ?>
			<section>
				<div class="container container-fs">
					<div class="row">
						<div class="default-secondary-bg-color col-md-12 inset brd-bottom">
							<h1 class="primary-color">Studenten</h1>
							<?php include 'breadcrumb.php'; ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8 full-inset">
							<div class="primary-bg-color brd-full">
								<div class="box">
									<div class="box-title full-inset brd-bottom">
										<div class="row">
											<h2 class="col-md-8">Studenten</h2>
											<div class="col-md-4">
												<div class="input-group">
													<div class="input-group-btn">
														<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Kies <i class="fa fa-caret-down"></i></button>
														<ul class="dropdown-menu">
															<!-- <li><a href="#">All</a></li>
															<li><a href="#">Client #</a></li>
															<li><a href="#">Address</a></li>
															<li><a href="#">Last name</a></li>
															<li><a href="#">First name</a></li> -->
														</ul>
													</div>
													<input type="text" class="form-control" aria-label="...">
													<div class="input-group-btn"> 
														<button aria-label="Help" class="btn btn-danger" type="button">
															<i class="fa fa-search"></i>
														</button>
													</div>
												</div>
											</div>
										</div>
									</div>							
									<div class="box-content full-inset sixth-bg-color">
										<div class="dataRetrieverOnLoad" data-ajax-href="ajax/studenten_tabel.php" data-display="data-display"></div>
										<div class="table-responsive">
											<div class="data-display"></div>
										</div>
									</div>
								</div>
							</div>
							<!-- <div class="default-secondary-bg-color brd-full" style="margin-top:15px;">
								<div class="box">
									<div class="box-title primary-bg-color full-inset brd-bottom">
										<div class="row">
											<h3 class="col-md-12">Demographic</h3>
										</div>
									</div>							
									<div class="box-content full-inset">
										<?php include('graphs.php'); ?>
									</div>
								</div>
							</div> -->
						</div>
						<div class="col-md-4 full-inset">
							<div class="primary-bg-color brd-full">
								<div class="box">
									<div class="box-title full-inset brd-bottom">
										<h3>Nieuwe Student aanmaken</h3>
									</div>
									<div class="sixth-bg-color box content full-inset">
										<form class="form-horizontal align-left" role="form" name="form-add-student" id="form-addStudent">
											<div role="tabpanel" class="tab-pane active" id="tab1">
												<div class="alert alert-danger hidden">
													<p><i class="fa fa-warning"></i> Er zijn lege velden die ingevuld moet worden!</p>
												</div>
												<fieldset>
													<div class="form-group">
														<label class="col-md-4 control-label">Student #</label>
														<div class="col-md-8">
															<input id="studentnummer" class="form-control" type="text" name="studentnummer"/>
														</div>
													</div>
													<!-- klas -->
													<div class="form-group">
														<label class="col-md-4 control-label">Klas</label>
														<div class="col-md-8">
															<select id="student-klas" name="klas" id="student-klas" class="form-control"></select>
														</div>
													</div>	
													<!-- klas -->
													<div class="form-group">
														<label class="col-md-4 control-label">Achternaam</label>
														<div class="col-md-8">
															<input id="achternaam" class="form-control" type="text" name="achternaam" />
														</div>
													</div>	
													<div class="form-group">
														<label class="col-md-4 control-label">Voornamen</label>
														<div class="col-md-8">
															<input id="voornaam" class="form-control" type="text" name="voornaam" />
														</div>
													</div>	
													<div class="form-group">
														<label class="col-md-4 control-label" for="geslacht">Geslacht</label>
														<div class="col-md-8">
															<select id="geslacht" name="geslacht" class="form-control">
																<option value="M">Man</option>
																<option value="V">Vrouw</option>																
															</select>
														</div>
													</div>			
													<!-- datepicker -->	    
							                        <div class="form-group">
							                        	<label class="col-md-4 control-label">Geboortedatum</label>
							                            <div class="col-md-8">
							                            	<div class="input-group date">
							                                	<input id="geboortedatum" type="text" value="" placeholder="" id="geboortedatum" class="form-control calendar" name="geboortedatum">
							                                	<span class="input-group-addon"><i class="fa fa-calendar default-primary-color"></i></span>
							                            	</div>
							                            </div>
							                        </div>
							                        <!-- datepicker -->	    
							                        <div class="form-group">
														<label class="col-md-4 control-label" for="">Geboorteplaats</label>
														<div class="col-md-8">
															<select id="geboorteplaats" name="geboorteplaats" class="form-control">
																<option value="ARUBA">Aruba</option>
																<option value="BONAIRE">Bonaire</option>
																<option value="CURACAO">Curcacao</option>
																<option value="NEDERLAND">Nederland</option>
																<option value="SURINAME">Suriname</option>
																<option value="COLOMBIA">Colombia</option>
																<option value="VENEZUELA">Venezuela</option>
																<option value="DOMINIKAANSEREPUBLIEK">Dominikaanse Republiek</option>
																<option value="CHINA">China</option>
																<option value="HAITI">Haiti</option>
																<option value="PERU">Peru</option>
																<option value="ECUADOR">Ecuador</option>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 control-label">Adres</label>
														<div class="col-md-8">
															<input id="adres" class="form-control" type="text" name="adres" />
														</div>
													</div>		        
						                        	<div class="form-group">
														<label class="col-md-4 control-label" for="telefoon">Telefoon / Mobiel</label>
														<div class="col-md-8">
															<input id="telefoon" class="form-control" type="number" name="telefoon" />
														</div>
													</div>														
													<!-- <div class="form-group">
														<label class="col-md-4 control-label">Email</label>
														<div class="col-md-8">
															<input class="form-control" type="text" name="email" />
														</div>
													</div>	 -->													
													<div class="form-group">
														<label class="col-md-4 control-label">Student status</label>
														<div class="col-md-8">
															<select id="status" name="status" class="form-control">
						                                        <option value="1">Actief</option>
						                                        <option value="0">Niet-Actief</option>
						                                        <!-- <option value="Deceased">Deceased</option>
						                                        <option value="Blacklisted">Blacklisted</option> -->
						                                    </select>
														</div>
													</div>		
													<!-- <div class="form-group">
														<label class="col-md-4 control-label">Notes</label>
														<div class="col-md-8">
															<textarea class="form-control" rows="3" name="notes"></textarea>
														</div>
													</div> -->
													<div class="form-group full-inset">												
														<button type="submit" class="btn btn-primary btn-m-w pull-right mrg-left" id="btn-add-student">Opslaan</button>
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
			</section>			
		</main>
		<?php include 'footer.php'; ?>
	</div>

<?php include 'document_end.php'; ?>