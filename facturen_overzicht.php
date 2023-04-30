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
							<h1 class="primary-color">Facturen Overzicht</h1>
							<?php include 'breadcrumb.php'; ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8 full-inset">
							<div class="primary-bg-color brd-full">
								<div class="box">
									<div class="box-title full-inset brd-bottom">
										<div class="row">
											<h2 class="col-md-8">Facturen / Betalingen </h2>
											<div class="col-md-4">
												<div class="input-group">
													<div class="input-group-btn">
														<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Kies <i class="fa fa-caret-down"></i></button>
														<ul class="dropdown-menu" id="list_type" name="list_type">
															<li><a href="#">All</a></li>
															<li><a href="#">Invoice</a></li>
															<li><a href="#">Payment</a></li>
														</ul>
													</div>
													<input type="text" class="form-control" aria-label="..." id="text_search" name="text_search" />
													<div class="input-group-btn">
														<button aria-label="Help" class="btn btn-danger" type="button" id="btn_search" name="btn_search">
															<i class="fa fa-search"></i>
														</button>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="box-content full-inset sixth-bg-color">
										<div id="table_invoice" class="dataRetrieverOnLoad" data-ajax-href="ajax/getpaymentinvoice_tabel.php"></div>
										<div class="table-responsive">
											<div id="table_invoice_result"></div>
												<a type="submit" name="btn_facturen_print" id="btn_facturen_print"class="btn btn-default btn-m-w pull-right mrg-left" target="_blank">PRINT</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-4 full-inset">
							<div class="primary-bg-color brd-full">
								<div class="box">
									<div class="box-title full-inset brd-bottom">
										<h3>Nieuw factuur of betaling</h3>
									</div>
								<form class="form-horizontal align-left" role="form" name="form-add-invoicepayment" id="form-addInvoicepayment">
									<div class="sixth-bg-color box content full-inset">
										<form class="form-horizontal align-left" role="form" name="form-add-invoicepayment" id="form-addInvoicepayment">
											<div role="tabpanel" class="tab-pane active" id="tab1">
												<div class="alert alert-danger hidden">
													<p><i class="fa fa-warning"></i> Er zijn lege velden die ingevuld moet worden!</p>
												</div>
												<fieldset>
													<div class="form-group">
														<label class="col-md-4 control-label" for="">Type</label>
														<div class="col-md-8">
															<select id="invoicepaymenttype" name="invoicepaymenttype" class="form-control">
																<option selected value="Invoice">Invoice</option>
																<option value="Payment">Payment</option>
															</select>
														</div>
													</div>
												<div id="lblinvoice_type" class="form-group">
														<label class="col-md-4 control-label" for="level">Invoice Type</label>
														<div class="col-md-8">
															<select id="invoice_type" name="invoice_type" class="form-control">

																<option value="1">Single Class</option>
																<option value="2">Single Student</option>
																<option value="3">Whole Class</option>
															</select>
														</div>
														<!-- <input id="type_invoice_hidden" name="type_invoice_hidden" type="text" value="" hidden> -->
													</div>
													</div>
													<div id="lblClass" class="form-group">
														<label class="col-md-4 control-label" for="">Klas</label>
														<div class="dataClassOnLoad" data-ajax-href="ajax/getlistclass.php"></div>
														<div class="col-md-8">
															<div id="data_class"></div>
														</div>
													</div>
													<div id="student" class="form-group hidden">
														<label class="col-md-4 control-label" for="">Leerling</label>
															<div class="col-md-8">
																<select id="data_student_by_class" name="data_student_by_class" class="form-control">
																	<option selected>Select One Student</option>
																</select>
															</div>
																<!-- <input id="data_student_by_class_hidden" name="data_student_by_class_hidden" type="text" value="" hidden> -->
													</div>
													<!-- Label hidden for payment -->
													<div id="lblschool" class="form-group hidden">
														<label class="col-md-4 control-label" for="">School Name</label>
													 <div class="dataSchoolOnLoad" data-ajax-href="ajax/getlistschool.php"></div>
														<div class="col-md-8">
															<div id="data_school"></div>
														</div>
													</div>
													<div id="invoice" class="form-group hidden">
														<label class="col-md-4 control-label" for="">Factuur #</label>
															<div id ="select_invoice"class="col-md-8">
																<select id="invoice_by_student" name="invoice_by_student" class="form-control">
																	<option selected>Select One invoice</option>
																</select>
															</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 control-label">Betaling #</label>
														<div class="col-md-8">
															<input id="invoicepaymentnumber" class="form-control" type="text" name="invoicepaymentnumber"/>
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 control-label">Bedrag</label>
														<div class="col-md-8">
															<input id="invoicepaymentammount" class="form-control" type="text" name="invoicepaymentammount"/>
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 control-label">Datum</label>
														<div class="col-md-8">
															<div class="input-group date">
																<input id="invoicepaymentdate" type="text" value="" placeholder="" id="invoicepaymentdate" class="form-control calendar" name="invoicepaymentdate">
																<span class="input-group-addon"><i class="fa fa-calendar default-primary-color"></i></span>
															</div>
														</div>
													</div>
													<div id="lbldudate" class="form-group">
														<label class="col-md-4 control-label">Due date</label>
														<div class="col-md-8">
															<div class="input-group date">
																<input id="invoicepaymentduedate" type="text" value="" placeholder="" id="invoicepaymentduedate" class="form-control calendar" name="invoicepaymentduedate">
																<span class="input-group-addon"><i class="fa fa-calendar default-primary-color"></i></span>
															</div>
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 control-label">Memo</label>
														<div class="col-md-8">
															<input id="invoicepaymentmemo" class="form-control" type="text" name="invoicepaymentmemo" />
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 control-label">Status</label>
														<div class="col-md-8">
															<select id="invoicepaymentstatus" name="invoicepaymentstatus" class="form-control">
																<option value="For pay">Nog te betalen</option>
																<option value="Pay">Betaald</option>
															</select>
														</div>
													</div>
													<div class="form-group full-inset">
														<button type="submit" class="btn btn-primary btn-m-w pull-right mrg-left" id="btn-add-invoicepayments">Toevoegen</button>
														<button type="reset" class="btn btn-danger btn-m-w pull-right mrg-left" id="btn-clear">Reset</button>
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
<?php include 'document_end.php';}?> <!-- PLEASE CAREFULLY WIH THIS "}" THAT CLOSE SECURITY  ELSE IF -->
<script type="text/javascript">

    $(function () {
	$("#btn_facturen_print").click(function () {
			window.open("print.php?name=facturen&title=Financial List"); 
			
		});
    	


			$("#list_type").on("click", "li", function() {
					var id = this.firstChild.text;
					$('#text_search').val(id);
				}
			);

      $("#btn_search").click(function () {
          var selectedValue = $("#text_search").val();
          $("#dataRequest-invoice tbody tr " ).each(function(index,element ) {

                  if($(element).find("td").eq(0).text() == selectedValue){
                      $(this).show();
                  }else{
                        $(this).hide();
                  }

              if(selectedValue == 'All' || selectedValue == ''){
                  $(this).show();
              }
          });
      });
});


</script>
