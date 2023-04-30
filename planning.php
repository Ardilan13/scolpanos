<?php include 'document_start.php'; ?>
	
	<?php include 'sub_nav.php'; ?>

	<div class="push-content-220">
		<?php include 'header.php'; ?>
		<?php $UserRights= $_SESSION['UserRights'];
		if ($UserRights != "BEHEER" ){
			include 'redirect.php';} else{?>
		<main id="main" role="main">
			<section>
				<div class="container container-fs">
					<div class="row">
						<div class="default-secondary-bg-color col-md-12 full-inset brd-bottom clearfix">
							<h1 class="primary-color">Planning</h1>
							<?php include 'breadcrumb.php'; ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 full-inset">
							<div class="sixth-bg-color brd-full">
								<div class="box">
									<div id="fullcalendar-container">
										<div class="box-title full-inset primary-bg-color">
											<div class="row">
												<div class="col-md-4">
													<h2>January 2016</h2>
												</div>
												<div class="col-md-8">
													<div class="pull-right form-inline">
														<div class="btn-group">
															<button data-calendar-nav="prev" class="btn btn-secondary"><i class="fa fa-angle-double-left"></i> Prev</button>
															<button data-calendar-nav="today" class="btn btn-success">Today</button>
															<button data-calendar-nav="next" class="btn btn-secondary">Next <i class="fa fa-angle-double-right"></i></button>
														</div>
														<div class="btn-group">
															<button data-calendar-view="year" class="btn btn-default">Year</button>
															<button data-calendar-view="month" class="btn btn-default active">Month</button>
															<button data-calendar-view="week" class="btn btn-default">Week</button>
															<button data-calendar-view="day" class="btn btn-default">Day</button>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div id="fullcalendar" class="box-content full-inset"></div>
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
