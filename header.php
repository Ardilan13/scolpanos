<?php

require_once("classes/spn_authentication.php");
require_once("classes/spn_message.php");
require_once("config/app.config.php");

$a = new spn_authentication();

$fullname = "";
$title = "";

$session_firstname = $a->GetSessionValue("FirstName");
$session_lastname = $a->GetSessionValue("LastName");
$session_title = $a->GetSessionValue("UserRights");

if (!empty($session_firstname) && !empty($session_lastname) && !empty($session_title)) {
	$fullname = $session_firstname . chr(32) . $session_lastname;
	$title = $session_title;
} else {
	$fullname = "Scol Pa Nos User";
	$title = "User";
}

?>

<header id="header" role="banner" class="fifth-bg-color">
	<div class="container container-fs containter-reset">
		<div class="row">
			<div class="col-md-12">
				<nav id="main-nav" role="navigation">
					<ul class="nav nav-pills">
						<li>
							<a class="tertiary-bg-color" href="#" id="mobile-nav">
								<i class="fa fa-navicon"></i> MENU
							</a>
						</li>
						<li class="dropdown pull-right">
							<a href="#" id="user-profile" class="nav-item dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i> <?php print $fullname ?> <i class="fa fa-angle-down"></i></a>
							<ul class="dropdown-menu" aria-labelledby="user-profile">
								<li class="dropdown-header">Account settings</li>
								<li><a href="#"><i class="fa fa-user"></i> Edit profile</a></li>
								<li><a href="#"><i class="fa fa-gear"></i> Change password</a></li>
								<li role="separator" class="divider"></li>
								<li class="dropdown-header">Extra info</li>

								<li><a href="#" data-toggle="modal" data-target="#modalSuggesties" class="modal-btn modal-suggesties"><i class="fa fa-user"></i> Suggesties</a>



								</li>

								<li><a href="http://madworksglobal.com/files/SpNManual.pdf" target='_blank'><i class="fa fa-question-circle"></i> Help</a></li>
								<li><a href="#"><i class="fa fa-group"></i> About</a></li>

								<li role="separator" class="divider"></li>
								<li><a href="logout.php"><i class="fa fa-lock"></i>Logout</a></li>
							</ul>
						</li>
						<div class="dataRetrieverOnLoad" id="unread_notifications" data-display="unread_notifications" data-ajax-href="ajax/get_number_unread_notifications.php">
						</div>
						<li class="dropdown notification pull-right" id="count_unread_notifications">
							<?php
							// $m = new spn_message();
							// print $m->getcountunreadmessages($_SESSION["UserGUID"], 0, "N");
							// //print "8";
							?>
							<div class="dropdown-menu" aria-labelledby="user-profile">
								<div class="listview">
									<div class="lv-header">
										Notifications
									</div>
									<div class="lv-body dataRetrieverOnLoad" data-display="table_notifications_result" data-ajax-href="ajax/getnotifications_tabel.php">
										<div class="table_notifications_result"></div>
									</div>
									<a href="notifications_create.php" class="lv-footer">View All</a>
								</div>
							</div>
						</li>
						<div class="dataRetrieverOnLoad" id="unread_messages" data-display="unread_messages" data-ajax-href="ajax/get_number_unread_messages.php">
						</div>
						<li class="dropdown notification pull-right" id="count_unread_messages">
							<?php


							// $m = new spn_message();
							// print $m->getcountunreadmessages($_SESSION["UserGUID"], 0, "M");
							//print "8";
							?>
							<div class="dropdown-menu" aria-labelledby="user-profile">
								<div class="listview">
									<div class="lv-header">
										Message
									</div>
									<div class="lv-body dataRetrieverOnLoad" data-display="table_messages_result" data-ajax-href="ajax/getinboxmessages_tabel.php">
										<div class="table_messages_result"></div>
									</div>
									<a href="inbox.php" class="lv-footer">View All</a>
								</div>
							</div>
						</li>
						<?php if ($_SESSION['SchoolType'] == 2) { ?>
							<li class="pull-right" style="font-weight: bold; color: white;"><?php echo $_SESSION['schoolname']; ?></li>
						<?php } ?>
					</ul>
				</nav>
			</div>
		</div>
	</div>
</header>

<div class="modal fade" id="modalSuggesties" tabindex="-1" role="dialog" aria-labelledby="modalSuggestiesLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Suggesties</h4>
			</div>
			<div class="modal-body">
				<form role="form" id="suggestie-form">
					<div class="form-group">
						<label class="col-md-4 control-label">Subject</label>
						<div class="col-md-8">
							<input type="text" required="" name="subject" class="form-control" id="subject" maxlength="100">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">Suggestie</label>
						<div class="col-md-8">
							<textarea type="text" required="" name="suggestion" class="form-control" id="suggestion" maxlength="1000"></textarea>
						</div>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-primary pull-right">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

</script>