<?php include 'document_start.php'; ?>
	
	<?php include 'sub_nav.php'; ?>

	<div class="push-content-220">
		<?php include 'header.php'; ?>
		<main id="main" role="main">
			<section>
				<div class="container container-fs">
					<div class="row">
						<div class="default-secondary-bg-color col-md-12 inset brd-bottom">
							<h1 class="primary-color">Customers</h1>
							<?php include 'breadcrumb.php'; ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8 full-inset">
							<div class="primary-bg-color brd-full">
								<div class="box">
									<div class="box-title full-inset brd-bottom">
										<div class="row">
											<h2 class="col-md-8">Tab design</h2>
											<div class="col-md-4">
												<div class="input-group">
													<div class="input-group-btn">
														<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Choose <i class="fa fa-caret-down"></i></button>
														<ul class="dropdown-menu">
															<li><a href="#">All</a></li>
															<li><a href="#">Client #</a></li>
															<li><a href="#">Address</a></li>
															<li><a href="#">Last name</a></li>
															<li><a href="#">First name</a></li>
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
										<div class="custom-tab">
											<ul role="tablist" class="nav nav-tabs nav-pills hidden-xs">
												<li class="active" role="presentation">
													<a data-toggle="tab" role="tab" aria-controls="tab1" href="#tab1" class="btn btn-default btn-m-w btn-m-h">
														Tab Item 1
													</a>
												</li>
												<li class="disable" role="presentation">
													<a data-toggle="tab" role="tab" aria-controls="tab2" href="#tab2" id="covernote-btn" class="btn btn-default btn-m-w btn-m-h">
														Tab Item 2
													</a>
												</li>
												<li class="disable" role="presentation">
													<a data-toggle="tab" role="tab" aria-controls="tab3" href="#tab3" id="attachfiles-btn" class="btn btn-default btn-m-w btn-m-h">
														Tab Item 3
													</a>
												</li>
												<li class="disable" role="presentation">
													<a data-toggle="tab" role="tab" aria-controls="tab4" href="#tab4" id="preview-btn" class="btn btn-default btn-m-w btn-m-h">
														Tab Item 4
													</a>
												</li>
											</ul>
											<div class="tab-content sixth-bg-color full-inset">
												<div role="tabpanel" class="tab-pane active" id="tab1">
													<div class="table-responsive">
														<table id="dataRequest" class="table table-bordered table-colored" data-table="yes">
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
																<tr role="row" class="odd"><td class="sorting_1">7</td><td>Rudy Croes</td><td>Tanki Flip 22</td><td>RB1232456</td><td>hello@rudycroes.com</td><td>5624524</td><td>Active</td><td><a class="link quaternary-color" href="http://192.168.1.211/loans/clientinfo.php?id=7">show more <i class="fa fa-angle-double-right quaternary-color"></i></a></td></tr>
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
						<div class="col-md-4 full-inset">
							<div class="primary-bg-color brd-full">
								<div class="box">
									<div class="box-title full-inset brd-bottom">
										<h3>Create a new Customer</h3>
									</div>
									<div class="sixth-bg-color box content full-inset">
										<form class="form-horizontal align-left" role="form" name="form-addcustomer" id="form-addcustomer">
											<div role="tabpanel" class="tab-pane active" id="tab1">
												<fieldset>
													<div class="form-group">
														<label class="col-md-4 control-label">Company Name</label>
														<div class="col-md-8">
															<input class="form-control" type="text" name="company"/>
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 control-label">Full name</label>
														<div class="col-md-8">
															<input class="form-control" type="text" name="fullname" />
														</div>
													</div>	
													<div class="form-group">
														<label class="col-md-4 control-label">ID / Passport #</label>
														<div class="col-md-8">
															<input class="form-control" type="text" name="identificationnumber" />
														</div>
													</div>	
													<!-- <div class="form-group">
														<label class="col-md-4 control-label">Address</label>
														<div class="col-md-8">
															<input class="form-control" type="text" />
														</div>
													</div>	 -->	

													 <!-- Typeahead -->					    
						                        <div class="form-group">
						                        	<label class="col-md-4 control-label">Address</label>
						                            <div class="col-md-8">
						                            	<div id="prefetch">
						                                	<input type="text" value="" placeholder="" id="address" class="form-control typeahead" name="address">
						                            	</div>
						                            </div>
						                        </div>        
						                        <!-- Typeahead -->	

													<div class="form-group">
														<label class="col-md-4 control-label">Telephone work</label>
														<div class="col-md-8">
															<input class="form-control" type="number" name="telephone1" />
														</div>
													</div>	
													<div class="form-group">
														<label class="col-md-4 control-label">Mobile</label>
														<div class="col-md-8">
															<input class="form-control" type="number" name="telephone2" />
														</div>
													</div>		
													<div class="form-group">
														<label class="col-md-4 control-label">Email</label>
														<div class="col-md-8">
															<input class="form-control" type="text" name="email" />
														</div>
													</div>	
													<div class="form-group">
														<label class="col-md-4 control-label" for="country">Country</label>
														<div class="col-md-8">
															<select name="country" class="form-control">
																<option value="Aruba">Aruba</option>
																<option value="Bonaire">Bonaire</option>
																<option value="Curacao">Curcacao</option>
															</select>
														</div>
													</div>	
													<div class="form-group">
														<label class="col-md-4 control-label">Customer status</label>
														<div class="col-md-8">
															<select name="status" class="form-control">
						                                        <option value="Active">Active</option>
						                                        <option value="Inactive">Inactive</option>
						                                        <option value="Deceased">Deceased</option>
						                                        <option value="Blacklisted">Blacklisted</option>
						                                    </select>
														</div>
													</div>		
													<div class="form-group">
														<label class="col-md-4 control-label">Notes</label>
														<div class="col-md-8">
															<textarea class="form-control" rows="3" name="notes"></textarea>
														</div>
													</div>
													<div class="form-group full-inset">												
														<a href="#" class="btn btn-primary btn-m-w pull-right mrg-left" id="btn-addcustomer">Add Customer</a>
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