<?php include 'document_start.php'; ?>

	<?php include 'sub_nav.php'; ?>

	<div class="push-content-220">
		<main id="main" role="main">
			<?php include 'header.php'; ?>
			<section>
				<div class="container container-fs">
					<div class="row">
						<div class="default-secondary-bg-color col-md-12 inset brd-bottom">
							<h1 class="primary-color">Financial Module</h1>
							<?php include 'breadcrumb.php'; ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 full-inset">
							<div class="primary-bg-color brd-full">
								<div class="box">
									<div class="box-title full-inset brd-bottom">
										<div class="row">
											<h2 class="col-md-8"> Invoice / Payment </h2>
											<div class="col-md-4">
												<div class="input-group">
													<div class="input-group-btn">
														<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Choose <i class="fa fa-caret-down"></i></button>
														<ul class="dropdown-menu">
															<li><a href="#">All</a></li>
															<li><a href="#">Payment</a></li>
															<li><a href="#">Invoice</a></li>
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
										<div class="dataRetrieverOnLoad" data-ajax-href="ajax/getpaymentinvoice_tabel.php" data-display="data-display"></div>
										<div class="table-responsive">
											<div class="data-display"></div>
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

	<script type="text/javascript">
		$(function()
		{
			alert("Estoy aqui");
			$("#lblInvoice").hide();
			$("#invoicepaymenttype").change(function ()
			{

				var valInvoice = $("#invoicepaymenttype option:selected").val();
				if(valInvoice == 'Invoice')
				{
					$("#invoice_id,#lblInvoice").hide("slow");
				}
				else
				{
					$("#invoice_id,#lblInvoice").show("slow");
				}

			}
			);
		}
		);

	</script>
	<?php include 'document_end.php'; ?>
