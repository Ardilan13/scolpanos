<?php

//error_reporting(-1);

require_once("config/app.config.php");

ob_start();

if(appconfig::GetHTTPMode() == "HTTPS")
{
  if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] !== "on")
  {
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: " . appconfig::GetBaseURL() . "/" .  "login_parent.php");
  }
}

ob_flush();

?>

<?php include 'document_start_unauth.php'; ?>

<main id="main" role="main" class="login">
  <div class="container">
    <div class="row">
      <div class="col-md-4"></div>
      <div class="col-md-4 txt-align-center">
        <h1 class="brand">
          <img class="img-responsive" src="<?php print appconfig::GetBaseURL(); ?>/assets/img/logo_spn_small.png" alt="Scol Pa Nos" />
        </h1>
      </div>
      <div class="col-md-4"></div>
    </div>
    <div class="row">
      <div class="col-md-4"></div>
      <div class="col-md-4">
        <div class="primary-bg-color full-inset account-wall">
          <h2><b>This is Scolpanos access for Parents</b></h2>
          <br>
          <form id="form_signin_parent" name="form_signin_parent" class="form-signin" method="POST">
            <div id="alert_wrong_signin" class="alert alert-warning hidden">
              <p><i class="fa fa-warning"></i> The combination of School, StudentNumber and SecurePIN, did not find any student !</p>
            </div>
            <div class="form-group">
              <div class="">
                <select  id="schools" name="schools" class="form-control" ></select>
              </div>
              <!-- <input id="user_class" name="user_class" type="text" value="" hidden> -->
            </div>
            <div class="form-group">
              <div class="input-group">
                <input type="text" name="studentnumber_signin" class="form-control" id="studentnumber_signin" placeholder="Student Number">
                <div class="input-group-addon quaternary-bg-color default-secondary-color">
                  <i class="fa fa-user"></i>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="input-group">
                <input type="password" name="securepin_signin" class="form-control" id="securepin_signin" placeholder="SecurePin">
                <div class="input-group-addon quaternary-bg-color default-secondary-color">
                  <i class="fa fa-lock"></i>
                </div>
              </div>
            </div>
            <button class="btn btn-primary btn-block" type="submit">Inloggen</button>
            <div id="result_search_parent"></div>
          </form>
          <!-- <a href="wachtwoordvergeten.php" class="default-secondary-color align-right">Wachtwoord vergeten?</a> -->
        </div>
      </div>
      <div class="col-md-4"></div>
    </div>
  </div>
</main>

<?php include 'document_end.php'; ?>

<script>

$.ajax({
	url:"ajax/getschools.php",
	type  : 'POST',
	dataType: "HTML",
	cache: false,
	async :false,
	success: function(data){
		$("#schools").html(data);
	}
})

$('#password_parent').keypress(function (e) {
  var key = e.which;
  if(key == 13)  // the enter key code
  {
    search_leerling_by_securepin()
  }
});

if($('#form_signin_parent').length) {
  $('#form_signin_parent').on('submit', function(e) {
    e.preventDefault();
    search_leerling_by_securepin()

  });
};

function search_leerling_by_securepin(){
  var flag_result = 1;
  $.ajax({
    url: "ajax/signin_parent.php",
    data: $('#form_signin_parent').serialize(),
    type: "POST",
    dataType: "text",
    success: function(data) {
      if (data!=0){
        $("#result_search_parent").html(data);
      }
      else {
        flag_result = 0;
        $('#alert_wrong_signin').removeClass('hidden')
        //alert ('The combination of StudentNumber and SecurePIN, did not find any student ');
      }

    },
    error: function(xhr, status, errorThrown) {
      console.log("error getting leerling name, plase contact the Developers");
    },
    complete: function(xhr,status) {
      if (flag_result==1)
      {
        var shearch_result_link = $("#redirect_leerling_parent").val();
        window.location.replace(shearch_result_link)
        //window.open(shearch_result_link, '_blank');
        //alert('im go to leerling.php')
      }
    }
  });
}

</script>
