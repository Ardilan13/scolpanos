<?php

ob_start();

require_once("config/app.config.php");
ob_flush();

?>

<nav id="sub-nav" role="navigation" class="primary-bg-color">
	<div class="scroll-container">
		<div class="scroll-inner">
			<h1 class="brand">
				<img src="<?php print appconfig::GetBaseURL(); ?>/assets/img/logo_spn_small.png" class="img-responsive" alt="Scol pa Nos">
			</h1>
			<ul class="nav nav-vert">

				<?php switch ($_SESSION['UserRights']) {
					case "BEHEER":
				?>
						<li>
							<a class="nav-item" href="home.php" role="button">Dashboard</a>
						</li>
						<li class="multilevel">
							<a class="nav-item" href="#" role="button">Klas <i class="fa fa-angle-left pull-right"></i></a>
							<ul class="nav nav-second-level">

								<?php if ($_SESSION['SchoolType'] == 1) : ?>
									<li><a href="avi.php">AVI</a></li>
								<?php endif; ?>
								<?php if ($_SESSION['UserRights'] == "TEACHER") { ?>
									<li><a href="cijfers_teacher.php">Cijfers</a></li>
								<?php } else { ?>
									<li><a href="cijfers.php">Cijfers</a></li>
								<?php } ?>

								<li><a href="document_klas.php">Documents Klas</a></li>
								<?php if ($_SESSION['SchoolType'] == 2) { ?>
									<li><a href="verza_hs.php">Verzamelstaten</a></li>
								<? } else { ?>
									<li><a href="rapport_export.php">Verzamelstaten</a></li>
								<?php } ?>

								<?php if ($_SESSION['SchoolType'] == 1) : ?>
									<li><a href="houding.php">Houding</a></li>
								<?php else : ?>
									<li><a href="houding_hs.php">Houding</a></li>
								<?php endif; ?>

								<li><a href="bespreking.php">Rapport bespreking</a></li>

								<?php if ($_SESSION['SchoolType'] == 1) : ?>
									<li><a href="ttr.php">TTR</a></li>
									<li><a href="woord_rapport.php">Woord Rapport</a></li>

								<?php endif; ?>



								<?php if ($_SESSION['SchoolType'] == 1) : ?>
									<li><a href="verzuim.php">Klassenboek</a></li>
								<?php else : ?>
									<li><a href="verzuim_hs.php">Klassenboek</a></li>
								<?php endif; ?>


								<?php if ($_SESSION['SchoolType'] == 1) : ?>
									<li><a href="#" class="disabled">CITO</a></li>
								<?php endif; ?>

								<li><a href="#" class="disabled">Social Emotioneel</a></li>
							</ul>
						</li>
						<li>
							<a class="nav-item" href="leerling.php" role="button">Leerling</a>
						</li>
						<li class="multilevel">
							<a class="nav-item" href="#" role="button">Kalender <i class="fa fa-angle-left pull-right"></i></a>
							<ul class="nav nav-second-level">
								<?php if ($_SESSION["SchoolType"] == 1) { ?>
									<li><a href="planner.php">Planning</a></li>
								<?php } else { ?>
									<li><a href="planner_hs.php">Planning</a></li>
								<?php } ?>
								<li><a href="calendar.php">Kalendar</a></li>
								<?php if ($_SESSION["SchoolType"] == 1) { ?>
									<li><a href="verwerkte.php">Verwerkte Stof</a></li>
								<?php } else { ?>
									<li><a href="daily.php">Verwerkte Stof</a></li>
								<?php } ?>
							</ul>
						</li>
						<li>
							<a class="nav-item disabled" disabled="disabled" href="facturen_overzicht.php" role="button">Financieel</a>
						</li>
						<?php if ($_SESSION["SchoolType"] == 2) { ?>
							<li class="multilevel">
								<a class="nav-item" href="#" role="button">Settings<i class="fa fa-angle-left pull-right"></i></a>
								<ul class="nav nav-second-level">
									<li> <a href="setting.php">Trimester wijzigingen</a></li>
									<li> <a href="rooster.php">Rooster</a></li>
								</ul>
							</li>
						<?php } else { ?>
							<li><a id="sub_nav_setting" class="nav-item" href="setting.php" role="button">Settings</a></li>
						<?php } ?>
						<li class="multilevel">
							<a class="nav-item" href="#" role="button">Groepsplan<i class="fa fa-angle-left pull-right"></i></a>
							<ul class="nav nav-second-level">
								<li> <a href="groups.php">Groepsplan Klas 4</a></li>
								<li> <a href="student_group.php">Student Naar Groep</a></li>
							</ul>
						</li>
						<li class="multilevel">
							<a class="nav-item" href="#" role="button">Overzichten<i class="fa fa-angle-left pull-right"></i></a>
							<ul class="nav nav-second-level">
								<li><a href="rapport_verzuim.php">Verzuim</a></li>
								<li><a href="leerling_export.php">Leerling Export</a></li>
								<li><a href="tussen_rapport.php">Tussen Rapport</a></li>
								<li><a href="login_parents_info.php">Pin Report</a></li>
								<li><a href="leerling_export_basic_data.php">School Export</a></li>
								<?php if ($_SESSION['SchoolID'] == 12 or $_SESSION['SchoolID'] == 13 or $_SESSION['SchoolID'] == 17 or ($_SESSION['SchoolType'] == 1)) : ?>
									<li><a href="se_kaart.php">Rapport</a></li>
								<?php endif; ?>

							</ul>
						</li>
						<?php if ($_SESSION["SchoolType"] == 2 && ($_SESSION['UserRights'] == "BEHEER" || $_SESSION['UserRights'] == "ADMINISTRATIE")) { ?>
							<li class="multilevel">
								<a class="nav-item" href="#" role="button">EBA<i class="fa fa-angle-left pull-right"></i></a>
								<ul class="nav nav-second-level">
									<li> <a href="eba_personalia.php">Personalia</a></li>
									<li> <a href="eba_geem.php">Gem Deel-DAG</a></li>
									<li> <a href="eba_ex1.php">EX.1-M</a></li>
									<li> <a href="eba_docentlist.php">Docenten lijst</a></li>
									<li> <a href="eba_docenten.php">EBA DOCENTEN LIJST</a></li>
									<li> <a href="eba_ex2.php">EX.2-M</a></li>
									<li> <a href="eba_ex2a.php">EX.2a-M</a></li>
									<li> <a href="eba_ex3.php">EX.3-M</a></li>
									<li> <a href="eba_ex3a.php">EX.3a-M</a></li>
									<li> <a href="eba_ex4.php">EX.4-M</a></li>
								</ul>
							</li>
						<?php } ?>

						<?php if ($_SESSION['SchoolID'] == 11) : ?>
							<li><a href="rapport_montessori.php" class="nav-item" role="button">Montessori</a></li>
						<?php endif; ?>


						<?php if ($_SESSION['SchoolType'] == 2) : ?>
							<li><a id="sub_nav_setting" class="nav-item" href="user_hs_account.php" role="button">Users Account</a></li>
						<?php else : ?>
							<li><a id="sub_nav_setting" class="nav-item" href="user_account.php" role="button">Users Account</a></li>
						<?php endif; ?>



						<li><a id="sub_nav_setting" class="nav-item" href="change_class.php" role="button">Klas samenstellen</a></li>
						<li><a id="sub_nav_images" class="nav-item" href="images.php" role="button">Upload Images</a></li>
						<li><a id="sub_nav_email" class="nav-item" href="email.php" role="button">Send Email</a></li>
						<li><a id="sub_nav_email" class="nav-item" href="DashBoardRapp.php" role="button">Beta</a></li>
						<?php break; ?>
					<?php
					case "DOCENT" or "ASSISTENT": ?>
						<li>
							<a class="nav-item" href="home.php" role="button">Dashboard</a>
						</li>
						<li class="multilevel">
							<a class="nav-item" href="#" role="button">Klas <i class="fa fa-angle-left pull-right"></i></a>
							<ul class="nav nav-second-level">
								<?php if ($_SESSION['SchoolType'] == 1) : ?>
									<li><a href="avi.php">AVI</a></li>
								<?php endif; ?>

								<?php if ($_SESSION['UserRights'] == "TEACHER") { ?>
									<li><a href="cijfers_teacher.php">Cijfers</a></li>
								<?php } else { ?>
									<li><a href="cijfers.php">Cijfers</a></li>
								<?php } ?> <li><a href="document_klas.php">Documents Klas</a></li>
								<?php if ($_SESSION['SchoolType'] == 2) { ?>
									<li><a href="verza_hs.php">Verzamelstaten</a></li>
								<? } else { ?>
									<li><a href="rapport_export.php">Verzamelstaten</a></li>
								<?php } ?>

								<?php if ($_SESSION['SchoolType'] == 1) : ?>
									<li><a href="houding.php">Houding</a></li>
								<?php else : ?>
									<li><a href="houding_hs.php">Houding</a></li>
								<?php endif; ?>

								<li><a href="bespreking.php">Rapport bespreking</a></li>

								<?php if ($_SESSION['SchoolType'] == 1) : ?>
									<li><a href="ttr.php">TTR</a></li>
									<li><a href="woord_rapport.php">Woord Rapport</a></li>
								<?php endif; ?>

								<?php if ($_SESSION['SchoolType'] == 1) : ?>
									<li><a href="verzuim.php">Klassenboek</a></li>
								<?php else : ?>
									<li><a href="verzuim_hs.php">Klassenboek</a></li>
								<?php endif; ?>


								<?php if ($_SESSION['SchoolType'] == 1) : ?>
									<li><a href="#" class="disabled">CITO</a></li>
								<?php endif; ?>

								<li><a href="#" class="disabled">Social Emotioneel</a></li>
							</ul>
						</li>
						<li>
							<a class="nav-item" href="leerling.php" role="button">Leerling</a>
						</li>
						<li class="multilevel">
							<a class="nav-item" href="#" role="button">Kalender <i class="fa fa-angle-left pull-right"></i></a>
							<ul class="nav nav-second-level">
								<?php if ($_SESSION["SchoolType"] == 1) { ?>
									<li><a href="planner.php">Planning</a></li>
								<?php } else { ?>
									<li><a href="planner_hs.php">Planning</a></li>
								<?php } ?>
								<li><a href="calendar.php">Kalendar</a></li>
								<?php if ($_SESSION["SchoolType"] == 1) { ?>
									<li><a href="verwerkte.php">Verwerkte Stof</a></li>
								<?php } else { ?>
									<li><a href="daily.php">Verwerkte Stof</a></li>
								<?php } ?>
							</ul>
						</li>
						<li>
							<a class="nav-item disabled" disabled="disabled" href="facturen_overzicht.php" role="button">Financieel</a>
						</li>
						<li>
							<a class="nav-item disabled" href="#" role="button">Groepsplan Klas 4</a>
						</li>
						<li class="multilevel">
							<a class="nav-item" href="#" role="button">Overzichten<i class="fa fa-angle-left pull-right"></i></a>
							<ul class="nav nav-second-level">
								<li><a href="rapport_verzuim.php">Verzuim</a></li>
								<li><a href="leerling_export.php">Leerling Export</a></li>
								<li><a href="tussen_rapport.php">Tussen Rapport</a></li>
								<li><a href="login_parents_info.php">Pin Report</a></li>
								<li><a href="leerling_export_basic_data.php">School Export</a></li>
								<?php if ($_SESSION['SchoolID'] == 12 or $_SESSION['SchoolID'] == 13 or ($_SESSION['SchoolType'] == 1 && $_SESSION['SchoolID'] != 8)) : ?>
									<li><a href="se_kaart.php">Rapport</a></li>
								<?php endif; ?>
							</ul>
						</li>
						<?php break; ?>
					<?php
					case "TEACHER": ?>
						<li><a class="nav-item disabled" href="home.php" role="button">Dashboard</a></li>
						<li><a class="nav-item" href="cijfers_teacher.php" role="button">Cijfers for teacher</a></li>
						<li class="multilevel">
							<a class="nav-item disabled" href="#" role="button">Klas <i class="fa fa-angle-left pull-right"></i></a>
							<ul class="nav nav-second-level">
								<li><a href="avi.php">AVI</a></li>
								<li><a href="cijfers_teacher.php">Cijfers</a></li>
								<?php if ($_SESSION['SchoolType'] == 1) : ?>
									<li><a href="document_klas.php">Documents Klas</a></li>
								<?php else : ?>

								<?php endif; ?>
								<!--<li><a href="document_klas.php">Documents Klas</a></li>-->
								<?php if ($_SESSION['SchoolType'] == 2) { ?>
									<li><a href="verza_hs.php">Verzamelstaten</a></li>
								<? } else { ?>
									<li><a href="rapport_export.php">Verzamelstaten</a></li>
								<?php } ?>


								<?php if ($_SESSION['SchoolType'] == 1) : ?>
									<li><a href="houding.php">Houding</a></li>
								<?php else : ?>
									<li><a href="houding_hs.php">Houding</a></li>
								<?php endif; ?>
								<li><a href="bespreking.php">Rapport bespreking</a></li>
								<li><a href="ttr.php">TTR</a></li>
								<li><a href="verzuim_hs.php">Klassenboek</a></li>
								<!-- <li><a href="verzuim.php">Verzuim</a></li>-->
								<li><a href="woord_rapport.php">Woord Rapport</a></li>
								<li><a href="#" class="disabled">CITO</a></li>
								<!-- <li><a href="#" class="disabled">Tempo toets</a></li> -->
								<li><a href="#" class="disabled">Social Emotioneel</a></li>
							</ul>
						</li>
						<li><a class="nav-item disabled" href="leerling.php" role="button">Leerling</a></li>
						<li class="multilevel">
							<a class="nav-item disabled" href="#" role="button">Kalender <i class="fa fa-angle-left pull-right"></i></a>
							<ul class="nav nav-second-level">
								<?php if ($_SESSION["SchoolType"] == 1) { ?>
									<li><a href="planner.php">Planning</a></li>
								<?php } else { ?>
									<li><a href="planner_hs.php">Planning</a></li>
								<?php } ?>
								<li><a href="calendar.php">Kalendar</a></li>
								<?php if ($_SESSION["SchoolType"] == 1) { ?>
									<li><a href="verwerkte.php">Verwerkte Stof</a></li>
								<?php } else { ?>
									<li><a href="daily.php">Verwerkte Stof</a></li>
								<?php } ?>
							</ul>
						</li>
						<li>
							<a class="nav-item disabled" disabled="disabled" href="facturen_overzicht.php" role="button">Financieel</a>
			</ul>
			</li>
			<li>
				<a class="nav-item disabled" href="#" role="button">Groepsplan Klas 4</a>
			</li>
			<li class="multilevel">
				<a class="nav-item disable" href="#" role="button">Overzichten<i class="fa fa-angle-left pull-right"></i></a>
				<ul class="nav nav-second-level">
					<li><a href="rapport_verzuim.php">Verzuim</a></li>
					<li><a href="leerling_export.php">Leerling Export</a></li>
					<li><a href="leerling_export.php">SN</a></li>
				</ul>
			</li>
			<?php if ($_SESSION['SchoolID'] == 11) : ?>
				<li><a href="rapport_montessori.php" class="nav-item" role="button">Montessori</a></li>
			<?php endif; ?>
			<li><a id="sub_nav_email" class="nav-item" href="email.php" role="button">Send Email</a></li>
			<li><a id="sub_nav_email" class="nav-item" href="DashBoardRapp.php" role="button">Beta</a></li>
			<?php break; ?>
		<?php
					case "ADMINISTRATIE": ?>
			<li>
				<a class="nav-item" href="home.php" role="button">Dashboard</a>
			</li>
			<li class="multilevel">
				<a class="nav-item" href="#" role="button">Klas <i class="fa fa-angle-left pull-right"></i></a>
				<ul class="nav nav-second-level">
					<?php if ($_SESSION['SchoolType'] == 1) : ?>
						<li><a href="verzuim.php">Klassenboek</a></li>
					<?php else : ?>
						<li><a href="verzuim_hs.php">Klassenboek</a></li>
					<?php endif; ?>
				</ul>
			</li>
			<li>
				<a class="nav-item" href="leerling.php" role="button">Leerling</a>
			</li>
			<li class="multilevel">
				<a class="nav-item" href="#" role="button">Kalender <i class="fa fa-angle-left pull-right"></i></a>
				<ul class="nav nav-second-level">
					<?php if ($_SESSION["SchoolType"] == 1) { ?>
						<li><a href="planner.php">Planning</a></li>
					<?php } else { ?>
						<li><a href="planner_hs.php">Planning</a></li>
					<?php } ?>
					<li><a href="calendar.php">Kalendar</a></li>
					<?php if ($_SESSION["SchoolType"] == 1) { ?>
						<li><a href="verwerkte.php">Verwerkte Stof</a></li>
					<?php } else { ?>
						<li><a href="daily.php">Verwerkte Stof</a></li>
					<?php } ?>
				</ul>
			</li>
			<li>
				<a class="nav-item disabled" disabled="disabled" href="facturen_overzicht.php" role="button">Financieel</a>
			</li>
			<li><a id="sub_nav_setting" class="nav-item disabled" href="setting.php" role="button">Settings</a></li>
			<li>
				<a class="nav-item disabled" href="#" role="button">Groepsplan Klas 4</a>
			</li>
			<li class="multilevel">
				<a class="nav-item" href="#" role="button">Overzichten<i class="fa fa-angle-left pull-right"></i></a>
				<ul class="nav nav-second-level">
					<li><a href="rapport_verzuim.php">Verzuim</a></li>
					<li><a href="leerling_export.php">Leerling Export</a></li>
					<li><a href="tussen_rapport.php">Tussen Rapport</a></li>
					<?php if ($_SESSION['SchoolID'] == 12 or $_SESSION['SchoolID'] == 13 or ($_SESSION['SchoolType'] == 1 && $_SESSION['SchoolID'] != 8)) : ?>
						<li><a href="se_kaart.php">Rapport</a></li>
					<?php endif; ?>
				</ul>
			</li>
			<?php if ($_SESSION["SchoolType"] == 2 && ($_SESSION['UserRights'] == "BEHEER" || $_SESSION['UserRights'] == "ADMINISTRATIE")) { ?>
				<li class="multilevel">
					<a class="nav-item" href="#" role="button">EBA<i class="fa fa-angle-left pull-right"></i></a>
					<ul class="nav nav-second-level">
						<li> <a href="eba_personalia.php">Personalia</a></li>
						<li> <a href="eba_geem.php">Gem Deel-DAG</a></li>
						<li> <a href="eba_ex1.php">EX.1-M</a></li>
						<li> <a href="eba_docentlist.php">Docenten lijst</a></li>
						<li> <a href="eba_docenten.php">EBA DOCENTEN LIJST</a></li>
						<li> <a href="eba_ex2.php">EX.2-M</a></li>
						<li> <a href="eba_ex2a.php">EX.2a-M</a></li>
						<li> <a href="eba_ex3.php">EX.3-M</a></li>
						<li> <a href="eba_ex3a.php">EX.3a-M</a></li>
						<li> <a href="eba_ex4.php">EX.4-M</a></li>
					</ul>
				</li>
			<?php } ?>
			<li><a id="sub_nav_setting" class="nav-item disabled" href="user_account.php" role="button">Users Account</a></li>
			<li><a id="sub_nav_setting" class="nav-item" href="change_class.php" role="button">Klas samenstellen</a></li>
			<li><a id="sub_nav_images" class="nav-item" href="images.php" role="button">Upload Images</a></li>
			<li><a id="sub_nav_email" class="nav-item" href="email.php" role="button">Send Email</a></li>
			<li><a id="sub_nav_email" class="nav-item" href="DashBoardRapp.php" role="button">Beta</a></li>

			<?php break; ?>
		<?php
					case "ONDERSTEUNING": ?>
			<li>
				<a class="nav-item" href="home.php" role="button">Dashboard</a>
			</li>
			<li class="multilevel">
				<a class="nav-item " href="#" role="button">Klas <i class="fa fa-angle-left pull-right"></i></a>
				<ul class="nav nav-second-level">
					<!-- <li><a href="verzuim.php">Verzuim</a></li> -->
					<li><a href="document_klas.php">Documents Klas</a></li>

				</ul>
			</li>
			<li>
				<a class="nav-item" href="leerling.php" role="button">Leerling</a>
			</li>
			<li class="multilevel">
				<a class="nav-item disabled" href="#" role="button">Kalender <i class="fa fa-angle-left pull-right"></i></a>
				<ul class="nav nav-second-level">
					<?php if ($_SESSION["SchoolType"] == 1) { ?>
						<li><a href="planner.php">Planning</a></li>
					<?php } else { ?>
						<li><a href="planner_hs.php">Planning</a></li>
					<?php } ?>
					<li><a href="calendar.php">Kalendar</a></li>
					<?php if ($_SESSION["SchoolType"] == 1) { ?>
						<li><a href="verwerkte.php">Verwerkte Stof</a></li>
					<?php } else { ?>
						<li><a href="daily.php">Verwerkte Stof</a></li>
					<?php } ?>
				</ul>
			</li>
			<li>
				<a class="nav-item disabled" disabled="disabled" href="facturen_overzicht.php" role="button">Financieel</a>
			</li>
			<li><a id="sub_nav_setting" class="nav-item disabled" href="setting.php" role="button">Settings</a></li>
			<li>
				<a class="nav-item disabled" href="#" role="button">Groepsplan Klas 4</a>
			</li>
			<li class="multilevel">
				<a class="nav-item disabled" href="#" role="button">Overzichten<i class="fa fa-angle-left pull-right"></i></a>
				<ul class="nav nav-second-level">
					<li><a href="rapport_verzuim.php">Verzuim</a></li>
					<li><a href="leerling_export.php">Leerling Export</a></li>
				</ul>
			</li>
			<?php if ($_SESSION['SchoolID'] == 11) : ?>
				<li><a href="rapport_montessori.php" class="nav-item" role="button">Montessori</a></li>
			<?php endif; ?>
			<li><a id="sub_nav_setting" class="nav-item disabled" href="user_account.php" role="button">Users Account</a></li>
			<li><a id="sub_nav_setting" class="nav-item disabled" href="change_class.php" role="button">Klas samenstellen</a></li>
			<li><a id="sub_nav_images" class="nav-item" href="images.php" role="button">Upload Images</a></li>
			<li><a id="sub_nav_email" class="nav-item" href="email.php" role="button">Send Email</a></li>
			<li><a id="sub_nav_email" class="nav-item" href="DashBoardRapp.php" role="button">Beta</a></li>
			<?php break; ?>
	<?php
					default:
						echo "";
				} ?>
	</ul>
		</div>
	</div>
</nav>