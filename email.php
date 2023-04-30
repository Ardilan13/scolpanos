<?php include 'document_start.php'; ?>
	<?php include 'sub_nav.php'; ?>
	<div class="push-content-220">
		<main id="main" role="main">
			<?php include 'header.php'; ?>
			<?php $UserRights= $_SESSION['UserRights'];
			if ($UserRights == "TEACHER"){
				include 'redirect.php';} else{?>
			<section>
				<div class="container container-fs">
					<div class="row">
						<div class="default-secondary-bg-color col-md-12 inset brd-bottom">
							<h1 class="primary-color">Emails</h1>
							<?php include 'breadcrumb.php'; ?>
						</div>
					</div>

          <div class="row mrg-bottom">
            <div class="col-md-10 col-md-offset-1 full-inset pull-left" id="div_from_class2">
              <div class="primary-bg-color brd-full">
                <div class="box">
                  <div class="box-title full-inset brd-bottom">
                    <div class="row">
                      <h2 class="text-center">Send Email</h2>
                    </div>
                  </div>
                  <div class="box-content full-inset default-secondary-bg-color" id="from_message">
                    <div class="row">
                      <div class="col-md-7 col-md-offset-2">
                        <table>
                          <tbody>
                              <tr>
                                  <td id="idframe">

                                  </td>
                              </tr>
                          </tbody>
                      </table>
                      </div>
                    </div>
                </div>
              </div>
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
<script type="text/javascript" src="assets/js/calendar.js"></script>
<script type="text/javascript" src="assets/js/app_calendar.js"></script>
<script type="text/javascript">
$(function() {

    var id='<?php echo $_SESSION["UserGUID"];?>';
    var idschool='<?php echo $_SESSION["SchoolID"];?>';
  	//var url = "https://digiroom.madworksglobal.com/?room=" + room + "&id=" + id + "&code=" + code + "&type=1" + "&idroom=" + idroom + "&school=1" + "&ts=" + time;
    var url = "https://digiroom.madworksglobal.com/SendMail/Index?userid=" + id + "&scoolid=" + idschool;
    var ifram = "<iframe src='" + url +"' style='border:0;overflow:hidden;'' scrolling='no' width=1000 height=1000 />"
    $('#idframe').append(ifram);
    console.log(id);
});
</script>
