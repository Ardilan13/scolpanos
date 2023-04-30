<?php include 'document_start.php'; ?>

<?php include 'sub_nav.php'; ?>

<div class="push-content-220">
	<main id="main" role="main">
		<?php include 'header.php'; ?>
		<?php
		$m= $_GET['m'];
		$status_message = $_GET['status'];
		?>
		<section>
			<div class="container container-fs">
				<div class="row">
					<div class="default-secondary-bg-color col-md-12 inset brd-bottom">
						<h1 class="primary-color">Message Module</h1>
						<?php if ($_SESSION["UserRights"]!="TEACHER"){include 'breadcrumb.php'; }?>

					</div>
				</div>
				<div class="row">
					<!-- Inbox -->
					<div class="col-md-8 full-inset">
						<div class="primary-bg-color brd-full">
							<div class="box">
								<div class="box-title full-inset brd-bottom">
									<div class="row">
										<h2 class="col-md-2">Inbox </h2>
										<div class="col-md-10">
											<!-- <div class="input-group">
											<input type="text" class="form-control" aria-label="...">
											<div class="input-group-btn">
											<button aria-label="Help" class="btn btn-danger" type="button">
											<i class="fa fa-search"></i>
										</button>
									</div>
								</div> -->
							</div>
						</div>
					</div>
					<div class="box-content full-inset sixth-bg-color"id="div_table_message">
						<div id="table_messages" class="dataRetrieverOnLoad" data-ajax-href="ajax/getinboxmessages_tabel.php"></div>
						<div class="table-responsive">
							<div id="table_messages_result"></div>
						</div>
					</div>
					<!-- Detail of message -->
					<div class="col-md-12 full-inset">
						<!-- <div id="table_message_detail" class="dataRetrieverOnLoad" data-ajax-href="ajax/getinboxmessage_detail_tabel.php"></div> -->
						<div class="table-responsive">
							<div id="table_message_result_detail"></div>
						</div>
					</div>
					<!-- <div class="col-md-2 full-inset"></div> -->
				</div>
			</div>
		</div>

		<!-- Parte de mensajeria -->
		<div class="col-md-4 full-inset">
			<div class="primary-bg-color brd-full">
				<div class="box">
					<div class="box-title full-inset brd-bottom">
						<h3>Create a message</h3>
					</div>
					<div class="sixth-bg-color box content full-inset">
						<form class="form-horizontal align-left" role="form" name="from-create-message" id="form-sendmessage-inbox">
							<div role="tabpanel" class="tab-pane active" id="tab1">
								<div class="alert alert-danger hidden">
									<p><i class="fa fa-warning"></i> Er zijn lege velden die ingevuld moet worden!</p>
								</div>
								<div class="alert alert-info hidden">
									<p><i class="fa fa-check"></i> Uw bericht is verzonden!</p>
								</div>
								<div class="alert alert-error hidden">
									<p><i class="fa fa-warning"></i> Excuseer me, was er een fout in het verzenden van berichten!</p>
								</div>

								<fieldset>
									<!-- school, change code to read the school -->
									<input id="idSchool" name="idSchool" type="hidden" value="<?php print $_SESSION["SchoolID"] ?>">
									<div id="lblUsers" class="form-group">
										<label class="col-md-4 control-label" for="">Select to</label>
										<div class="dataUsersOnLoad" data-ajax-href="ajax/getlistuser_receiver_message.php"></div>
										<div class="col-md-8">
											<div id="data_users"></div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-4 control-label">To</label>
										<div class="col-md-8">
											<textarea id="selectedto" class="form-control" rows="5" name="selectedto" disabled></textarea>
											<input id="users_selected" type="hidden" name="users_selected" />
											<input id="count_users_selected" type="hidden" name="count_users_selected" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-4 control-label">Subject</label>
										<div class="col-md-8">
											<input id="subject_message" class="form-control" type="text" name="subject_message"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-4 control-label">Message</label>
										<div class="col-md-8">
											<textarea id="message" class="form-control" rows="4" name="message" maxlength="140"></textarea>
										</div>
									</div>
									<div class="form-group full-inset">
										<button type="submit" class="btn btn-primary btn-m-w pull-right mrg-left" id="btn-send-message">Send</button>
										<button type="reset" class="btn btn-danger btn-m-w pull-right mrg-left" id="s">Discard</button>
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

<?php include 'document_end.php'; ?>

<script>
function deleteMessage(id_message){

	var r = confirm("Do you want delete this message?");
	if (r) {
		/* begin post */
		// data = {}
		$.ajax({
			url: "ajax/delete_message.php",
			data: {id : id_message},
			type: "POST",
			dataType: "text",
			success: function (text) {
				if (text == 1) {
					location.reload();
				}
			},
			error: function (xhr, status, errorThrown) {
				console.log("error");
			}
		});
	}

}

$( document ).ready(function() {

	var url = window.location.pathname;
	var filename = url.substring(url.lastIndexOf('/')+1);


	var message_id = <?php echo $m ?>;
	var status_message =  <?php echo $status_message ?>;

	if (message_id!=""&& status_message!="" ){
		$.post("ajax/getinboxmessage_detail_tabel.php",
		{
			id_message : message_id,
			message_status : status_message
		},
		function(data){

			$("#table_message_result_detail").html(data);

		});


	}

	// alert(message_id);
	// alert(status_message);
	$('#div_table_message').css({"overflow-y": "auto", "height": "380px"});




	// if ()

});
</script>
