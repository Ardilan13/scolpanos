<?php include 'document_start.php'; ?>

	<?php include 'sub_nav.php'; ?>

	<div class="push-content-220">
		<main id="main" role="main">
			<?php include 'header.php'; ?>
			<section>
				<div class="container container-fs">
					<div class="row">
						<div class="default-secondary-bg-color col-md-12 inset brd-bottom">
							<h1 class="primary-color">To Do </h1>
							<?php include 'breadcrumb.php'; ?>
						</div>
					</div>
					<br>

					<div class="row">
						<div class="box-content full-inset sixth-bg-color">
							<div id="div_todo_list" class="dataRetrieverOnLoad" data-ajax-href="ajax/gettodo_list.php"></div>
								<div id="div_todo_list_detail"></div>
						</div>
					</div>
				</div>
			</section>
		</main>
		<?php include 'footer.php'; ?>
	</div>

<?php include 'document_end.php'; ?>
