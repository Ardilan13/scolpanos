<?php include 'document_start.php'; ?>



<?php include 'sub_nav.php'; ?>



<div class="push-content-220">

	<?php include 'header.php'; ?>

	<?php $UserRights= $_SESSION['UserRights'];

	if ($UserRights == "ONDERSTEUNING" || $UserRights == "TEACHER" || $_SESSION['SchoolType'] != 1){

		include 'redirect.php';} else{?>

			<main id="main" role="main">

				<section>

					<div class="container container-fs">

						<div class="row">

							<div class="default-secondary-bg-color col-md-12 full-inset brd-bottom clearfix">

								<h1 class="primary-color">Verzuim</h1>

								<?php include 'breadcrumb.php'; ?>

							</div>

							<div class="default-secondary-bg-color col-md-12 full-inset filter-bar brd-bottom clearfix">

								<form id="form-laat-absent" class="form-inline form-data-retriever" name="filter-vak" role="form">

									<fieldset>

										<div class="form-group">

											<label for="datum">Datum</label>

											<div class="input-group date">

												<input type="text" id="datum" name="datum" calendar="full" class="form-control input-sm calendar" autocomplete="off">

												<span class="input-group-addon">

													<i class="fa fa-calendar"></i>

												</span>

											</div>

										</div>

										<div class="form-group">

											<label for="klas">Klas</label>

											<select class="form-control" name="klas" id="verzuim_klassen_lijst">

												<!-- Options populated by AJAX get -->

												<!-- <option value="NONE">NONE</option> -->

											</select>

										</div>

										<div class="form-group">

											<button data-display="data-display" data-ajax-href="ajax/getverzuim_tabel.php" type="submit" class="btn btn-primary btn-m-w btn-m-h">zoeken</button>

										</div>

									</fieldset>

									<input type="text" value="" class="hidden" name="period_hs" id="period_hs"/>

								</form>

							</div>

						</div>

						<div class="row">

							<div id="loader_spn" class="hidden"><div class="loader_spn"></div></div>

							<div class="col-md-12 full-inset">

								<div class="sixth-bg-color brd-full">

									<div class="box">

										<div class="box-content full-inset clearfix">

											<div class="data-display"></div>

											<?php include 'modal_laat_absent.php'; ?>

										</div>

										<a style="margin-top: 10px" type="submit" name="btn_verzuim_print" id="btn_verzuim_print"class="btn btn-default btn-m-w pull-right mrg-left" target="_blank">PRINT</a>

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

		$("#form-laat-absent").submit(function () {
			$("#loader_spn").toggleClass('hidden');
			setTimeout(function() {
				$("#loader_spn").addClass('hidden');
			}, 3000);
		});


		$(function () {

			$("#btn_verzuim_print").click(function () {

				var datum = $("#datum").val(),

				klas = $("#verzuim_klassen_lijst option:selected").val();
				window.open("print.php?name=verzuim&title=Verzuim List&datum="+datum+"&klas="+klas);

				$(document).ready(function(){
					$('[data-toggle="tooltip"]').tooltip();
				});
			});

		})

		$(function(){
			$("[calendar=full]").datepicker({

				format: 'dd-mm-yyyy',
				todayHighlight: true,
				endDate: false,
				autoclose: true
			});
		});

	</script>

