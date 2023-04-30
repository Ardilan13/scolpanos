<?php include 'document_start.php'; ?>
	
	<?php include 'sub_nav.php'; ?>

	<div class="push-content-220">
		<?php include 'header.php'; ?>
		<?php $UserRights= $_SESSION['UserRights'];
		if ($UserRights != "BEHEER"){
			include 'redirect.php';} else{?>
		<main id="main" role="main">
			<section>
				<div class="container container-fs">
					<div class="row">
						<div class="default-secondary-bg-color col-md-12 inset brd-bottom clearfix">
							<h1 class="primary-color">Vak: Getalbegrip</h1>
							<?php include 'breadcrumb.php'; ?>
							<form id="form-vak" class="form-inline form-data-retriever" name="filter-vak" role="form">
								<fieldset>
									<div class="form-group">
										<label for="klas">Klas</label>
										<select class="form-control" name="klas">
											<option value="1A">1A</option>
											<option value="1B">1B</option>
											<option value="1C">1C</option>
										</select>
									</div>
									<div class="form-group">
										<label for="vak">Vak</label>
										<select class="form-control" name="vak">
											<option value="rekenen">Rekenen</option>
											<option value="tekenen">Tekenen</option>
											<option value="lezen">Lezen</option>
										</select>
									</div>
									<div class="form-group">
										<label for="rapnr">Rapnr.</label>
										<select class="form-control" name="rapnr">
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
										</select>
									</div>
									<div class="form-group">
										<button data-display="data-display" data-ajax-href="ajax/getvakken_tabel.php" type="submit" class="btn btn-primary btn-m-w btn-m-h">zoeken</button>
									</div>
								</fieldset>
							</form>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 full-inset">
							<div class="sixth-bg-color brd-full">
								<div class="box">							
									<div class="box-content full-inset">
										<div class="full-inset alert alert-warning reset-mrg-bottom">
											<p><i class="fa fa-warning"></i> Selecteer de vak, klas en rapport nummer bovenaan en klik op zoeken.</p>
										</div>	
										<div class="data-display"></div>
										<?php include 'modal_vak.php'; ?>
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
