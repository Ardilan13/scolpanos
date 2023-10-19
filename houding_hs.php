<?php include 'document_start.php'; ?>

<?php include 'sub_nav.php'; ?>
<div class="push-content-220">
	<?php include 'header.php'; ?>
	<?php $UserRights = $_SESSION['UserRights'];
	if ($UserRights == "ADMINISTRATIE" || $UserRights == "ONDERSTEUNING" || $UserRights == "TEACHER" || $_SESSION['SchoolType'] <= 1) {
		include 'redirect.php';
	} else { ?>
		<main id="main" role="main">
			<input hidden type="text" id="schooltype" value="<?php echo $_SESSION['SchoolType'] ?>">
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
									<div class="form-group group hidden">
										<label for="group">Group</label>
										<select class="form-control" name="group" id="group"></select>
									</div>
									<div class="form-group vaken">
										<label for="houding_vakken_lijst">Vak</label>
										<select class="form-control" name="houding_vakken_lijst" id="houding_vakken_lijst">
											<!-- Options populated by AJAX get -->
											<!--<option value="NONE">-- Kies een Vak --</option>-->
										</select>
									</div>
									<div class="form-group">
										<label for="rapport">Raport</label>
										<select class="form-control" id="rapport" name="rapport">
											<option value=1>1</option>
											<option value=2>2</option>
											<option value=3>3</option>
										</select>
									</div>
									<div class="form-group">
										<button data-display="data-display" data-ajax-href="ajax/gethouding_tabel_hs.php" type="submit" class="btn btn-primary btn-m-w btn-m-h">zoeken</button>
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
	function ajustar_klas() {
		$("#houding_klassen_lijst option[value='4A']").text('4').val('4');
		$("#houding_klassen_lijst option[value='4B']").remove();
		$("#houding_klassen_lijst option[value='4C']").remove();
		$("#houding_klassen_lijst option[value='4D']").remove();
	}

	$("#btn_houding_print").click(function() {

		window.open("print.php?name=houding&title=houding List&klas=" + $("#houding_klassen_lijst option:selected").val() + "&rapport=" + $("#rapport option:selected").val());
	});

	$(document).ready(() => {
		setTimeout(function() {
			if ($("#houding_klassen_lijst").val() == '4') {
				$.getJSON("ajax/get_groups.php", {}, function(result) {
					console.log(result);
					var vak = $("#group");
					$.each(result, function() {
						vak.append($("<option />").val(this.group).text(this.vak).attr("vak", this.id));
					});
				});
				$("#group").attr('disabled', false);
				$("#houding_vakken_lijst").attr('disabled', true);
				$(".vaken").addClass("hidden");
				$(".group").removeClass("hidden");
				$(".rapport").addClass("hidden");
			} else {
				$("#houding_vakken_lijst").attr('disabled', false);
				$("#group").attr('disabled', true);
				$(".group").addClass("hidden");
				$(".vaken").removeClass("hidden");
				$(".rapport").removeClass("hidden");
			}
			if ($("#schooltype").val() == 2) {
				ajustar_klas()
			}
		}, 1000);
	})

	$("#houding_klassen_lijst").change(function() {
		if ($("#houding_klassen_lijst").val() == '4') {
			$.getJSON("ajax/get_groups.php", {}, function(result) {
				console.log(result);
				var vak = $("#group");
				$.each(result, function() {
					if (this.vak != "msl") {
						vak.append($("<option />").val(this.group).text(this.vak).attr("vak", this.id));
					}
				});
			});
			$("#group").attr('disabled', false);
			$("#houding_vakken_lijst").attr('disabled', true);
			$(".vaken").addClass("hidden");
			$(".group").removeClass("hidden");
			$(".rapport").addClass("hidden");
		} else {
			$("#houding_vakken_lijst").attr('disabled', false);
			$("#group").attr('disabled', true);
			$(".group").addClass("hidden");
			$(".vaken").removeClass("hidden");
			$(".rapport").removeClass("hidden");
		}
	});
</script>