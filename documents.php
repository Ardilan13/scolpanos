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
							<h1 class="primary-color">Documents</h1>
							<?php include 'breadcrumb.php'; ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8 full-inset">
							<div id="document_list"></div>
						</div>
						<div class="col-md-4 full-inset">
							<div class="primary-bg-color brd-full">
								<div class="box">
									<div class="box-title full-inset brd-bottom">
										<h3>Upload</h3>
									</div>
									<div class="sixth-bg-color box content full-inset">
										<form class="form-horizontal align-left" role="form" name="form_document" id="form_document" action="upload.php" method="post" enctype="multipart/form-data">
											<input type="hidden" id="parent_id" value=""/>
											<div role="tabpanel" class="tab-pane active" id="tab1">
												<div class="alert alert-danger hidden">
													<p><i class="fa fa-warning"></i> Er zijn lege velden die ingevuld moet worden!</p>
												</div>
												<fieldset>
													<div class="form-group">
														<label class="col-md-4 control-label">File</label>
														<div class="col-md-8">
															<input class="form-control" type="file" name="fileToUpload" id="fileToUpload" required>
														</div>
													</div>
													<!-- klas -->
													<div class="form-group">
														<label class="col-md-4 control-label">Description</label>
														<div class="col-md-8">
															<textarea class="form-control"></textarea>
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
				</div>
			</section>			
		</main>
		<?php include 'footer.php'; ?>
	</div>

<?php include 'document_end.php';}?> <!-- PLEASE CAREFULLY WIH THIS "}" THAT CLOSE SECURITY  ELSE IF -->

	<script>
	
	$(function(){

		/* begin post */
		$.ajax({
			url: "ajax/getdocuments.php",
			data: "",
			type: "POST",
			dataType: "text",
			success: function(data)
			{
				//console.log(data);
				$("#document_list").append(data);
			},
			error: function(xhr, status, errorThrown)
			{
				//console.log("error");
			},
			complete: function(xhr,status)
			{
				//console.log("complete");
			}
		});
		
	});
	
	</script>
