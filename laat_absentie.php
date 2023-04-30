<?php include 'document_start.php'; ?>
	
	<?php include 'sub_nav.php'; ?>

	<div class="push-content-220">
		<?php include 'header.php'; ?>
		<main id="main" role="main">
			<section>
				<div class="container container-fs">
					<div class="row">
						<div class="default-secondary-bg-color col-md-12 full-inset brd-bottom clearfix">
							<h1 class="primary-color">Te laat / Absentie</h1>
							<?php include 'breadcrumb.php'; ?>
							<form id="form-laat-absent" class="form-inline form-data-retriever" name="filter-vak" role="form">
								<fieldset>
									<div class="form-group">
										<label for="datum">Datum</label>
										<div class="input-group date">
											<input type="text" id="datum" name="datum" class="form-control input-sm calendar">
											<span class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</span>
										</div>
						            </div>
									<div class="form-group">
										<label for="klas">Klas</label>
										<select class="form-control" name="klas">
											<option value="1A">1A</option>
											<option value="1B">1B</option>
											<option value="1C">1C</option>
										</select>
									</div>
									<div class="form-group">
										<button data-display="data-display" data-ajax-href="ajax/laat_absent.php" type="submit" class="btn btn-primary btn-m-w btn-m-h">zoeken</button>
									</div>
								</fieldset>
							</form>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 full-inset">
							<div class="sixth-bg-color brd-full">
								<div class="box">							
									<div class="box-content full-inset clearfix">	
										<a data-target="#laatabsent" data-toggle="modal" href="#" class="btn btn-primary modal-btn pull-right mrg-bottom">Voeg toe <i class="fa fa-edit"></i></a>	
										<div class="data-display"></div>								
										<?php include 'modal_laat_absent.php'; ?>
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