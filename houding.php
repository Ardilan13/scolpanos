<?php include 'document_start.php'; ?>



<?php include 'sub_nav.php'; ?>

<div class="push-content-220">

	<?php include 'header.php'; ?>

	<?php $UserRights = $_SESSION['UserRights'];

	if ($UserRights == "ADMINISTRATIE" || $UserRights == "ONDERSTEUNING" || $UserRights == "TEACHER" || $_SESSION['SchoolType'] != 1) {

		include 'redirect.php';
	} else { ?>

		<main id="main" role="main">

			<section>

				<div class="container container-fs">

					<div class="row">

						<div class="default-secondary-bg-color col-md-12 full-inset brd-bottom clearfix">

							<h1 class="primary-color">Houding</h1>

							<?php include 'breadcrumb.php'; ?>

						</div>

						<div class="default-secondary-bg-color col-md-12 full-inset filter-bar brd-bottom clearfix">

							<form id="form-houding" class="form-inline form-data-retriever" name="filter-vak" role="form">

								<fieldset>

									<div class="form-group">

										<label for="klas">Klas</label>

										<select class="form-control" name="klas" id="houding_klassen_lijst">

											<!-- Options populated by AJAX get -->

										</select>

									</div>

									<div class="form-group">

										<label for="rapport">Raport</label>

										<select class="form-control" id="rapport" name="rapport">

											<option value="1">1</option>

											<option value="2">2</option>

											<option value="3">3</option>

										</select>

									</div>

									<div class="form-group mr-4">

										<button data-display="data-display" data-ajax-href="ajax/gethouding_tabel.php" type="submit" class="btn btn-primary btn-m-w btn-m-h">zoeken</button>

									</div>
									<div class="form-group" style="margin-left: 15px;">
										<table border="1" style="background-color: #ffdc66 !important; border: 1px solid black; margin: 0 !important;">
											<tbody>
												<tr>
													<td style="padding: 2px 6px;">leerkrac = <b>CD</b></td>
													<td style="padding: 2px 6px;">leerling = <b>CL</b></td>
													<td style="padding: 2px 6px;">sociaal gedrag = <b>SG</b></td>
													<td style="padding: 2px 6px;">zelfvertrouwen = <b>ZN</b></td>
													<td style="padding: 2px 6px;">nauwkeurigheid = <b>ND</b></td>
													<td style="padding: 2px 6px;">doorzettingsvermogen = <b>DN</b></td>
												</tr>
												<tr>
													<td style="padding: 2px 6px;">zelfstandigheid = <b>ZD</b></td>
													<td style="padding: 2px 6px;">werktempo = <b>WO</b></td>
													<td style="padding: 2px 6px;">werkverzorging = <b>WG</b></td>
													<td style="padding: 2px 6px;">concentratie = <b>CE</b></td>
													<td style="padding: 2px 6px;">huiswerk = <b>HK</b></td>
													<td style="padding: 2px 6px;">zwem niveau = <b>ZU</b></td>
												</tr>

											</tbody>
										</table>
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

											<p><i class="fa fa-warning"></i> Selecteer de klas en rapport nummer bovenaan en klik op zoeken.</p>

										</div>

										<div class="data-display" style="overflow-x:auto"></div>

									</div>

								</div>

							</div>

							<br />

							<a type="submit" name="btn_houding_print" id="btn_houding_print" class="btn btn-default btn-m-w pull-right mrg-left" target="_blank">PRINT</a>

						</div>

					</div>

				</div>

			</section>

		</main>

		<?php include 'footer.php'; ?>

</div>



<?php include 'document_end.php';
	} ?> <!-- PLEASE CAREFULLY WIH THIS "}" THAT CLOSE SECURITY  ELSE IF -->



<script>
	$("#btn_houding_print").click(function() {



		window.open("print.php?name=houding&title=houding List&klas=" + $("#houding_klassen_lijst option:selected").val() + "&rapport=" + $("#rapport option:selected").val());

	});
</script>