<?php

//error_reporting(-1);

require_once("config/app.config.php");

ob_start();

if (appconfig::GetHTTPMode() == "HTTPS") {
  if (!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] !== "on") {
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: " . appconfig::GetBaseURL() . "/" .  "login_parent.php");
  }
}

ob_flush();

?>

<?php include 'document_start_unauth.php'; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/Wruczek/Bootstrap-Cookie-Alert@gh-pages/cookiealert.css">

<main id="main" role="main" class="login">
  <div class="container">
    <div class="row">
      <div class="col-md-4"></div>
      <div class="col-md-4 txt-align-center">
        <h1 class="brand">
          <img style='max-width: 200px !important;' class="img-responsive" src="<?php print appconfig::GetBaseURL(); ?>/assets/img/logo_spn_small.png" alt="Scol Pa Nos" />
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
                <select id="schools" name="schools" class="form-control"></select>
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
            <div class="form-group" id="div_keep_password">
              <div class="col-md-12">
                <label class="col-md-8 control-label pull-left" style="padding-left: 0px;">wachtwoord onthouden</label>
                <!-- <input type="checkbox" name="_motoriek_val" id="_motoriek_val"> Motoriek -->
                <div class="col-md-2" style="padding-top: 7px;">
                  <input type="checkbox" id="keep_password" name="keep_password" value="1">
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
  <div class="alert text-center cookiealert" role="alert">
    <b>Wij gebruiken cookies</b> &#x1F36A;We gebruiken cookies om een goeie gebruikerservaring te garanderen op onze website. <a href="https://cookiesandyou.com/" target="_blank" style="color: #ffffff;"> Lees meer</a>

    <button type="button" class="btn btn-primary btn-sm acceptcookies" aria-label="Close">
      Akkoord
    </button>
  </div>
</main>

<?php include 'document_end.php'; ?>
<script src="https://cdn.jsdelivr.net/gh/Wruczek/Bootstrap-Cookie-Alert@gh-pages/cookiealert.js"></script>
<script>
  $(document).ready(function() {
    isCookieSet();
  });

  $('#schools').change(function() {
    var school = $(this).val();
    switch (school) {
      case '4':
        $img = "kudawecha.png";
        break;
      case '6':
        $img = "washington.jpg";
        break;
      case '9':
        $img = "reina.png";
        break;
      case '10':
        $img = "xander.png";
        break;
      case '11':
        $img = "angela.png";
        break;
      case '13':
        $img = "Abrahamdeveer.jpeg";
        break;
      case '12':
        $img = "ceque_logo.png";
        break;
      case '17':
        $img = "monplaisir.png";
        break;
      case '18':
        $img = "futuro.jpg";
        break;
      default:
        $img = "logo_spn_small.png";
        break
    }
    $('.img-responsive').attr('src', 'assets/img/' + $img)
  });

  var set_cookie = false;

  $.ajax({
    url: "ajax/getschools.php",
    type: 'POST',
    dataType: "HTML",
    cache: false,
    async: false,
    success: function(data) {
      $("#schools").html(data);
    }
  })

  $('#password_parent').keypress(function(e) {
    var key = e.which;
    if (key == 13) // the enter key code
    {
      if (set_cookie) {
        setCookie();
      } else {
        document.cookie = "studentnumber=;securepin=;schools=;expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
      }
      search_leerling_by_securepin()
    }
  });

  if ($('#form_signin_parent').length) {
    $('#form_signin_parent').on('submit', function(e) {
      e.preventDefault();
      if (set_cookie) {
        setCookie();
      } else {
        document.cookie = "studentnumber=;securepin=;schools=;expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
      }
      search_leerling_by_securepin()

    });
  };

  function search_leerling_by_securepin() {

    var flag_result = 1;
    $.ajax({
      url: "ajax/signin_parent.php",
      data: $('#form_signin_parent').serialize(),
      type: "POST",
      dataType: "text",
      success: function(data) {
        if (data != 0) {

          $("#result_search_parent").html(data);

        } else {
          flag_result = 0;
          $('#alert_wrong_signin').removeClass('hidden')
          //alert ('The combination of StudentNumber and SecurePIN, did not find any student ');
        }

      },
      error: function(xhr, status, errorThrown) {
        console.log("error getting leerling name, plase contact the Developers");
      },
      complete: function(xhr, status) {
        if (flag_result == 1) {
          var shearch_result_link = $("#redirect_leerling_parent").val();
          window.location.replace(shearch_result_link)
          //window.open(shearch_result_link, '_blank');
          //alert('im go to leerling.php')
        }
      }
    });
  }

  function setCookie() {
    var d = new Date();
    d.setTime(d.getTime() + (100 * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = "studentnumber=" + $('#studentnumber_signin').val();
    document.cookie = "securepin=" + $('#securepin_signin').val();
    document.cookie = "schools=" + $('#schools').val();
    document.cookie = expires + ";path=/";


  }

  $('#keep_password').click(function() {
    if ($(this).is(':checked')) {
      set_cookie = true;
    } else {
      set_cookie = false;
    }
  });

  function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
  };

  function isCookieSet() {

    if (getCookie("acceptCookies")) {
      var school = getCookie("schools");
      var studentnumer = getCookie("studentnumber");
      var securepin = getCookie("securepin");

      if (school != "" && studentnumer != "" && studentnumer != "") {
        $('#schools').val(getCookie("schools"));
        $('#studentnumber_signin').val(getCookie("studentnumber"));
        $('#securepin_signin').val(getCookie("securepin"));
        $('#keep_password').prop("checked", "checked");
        set_cookie = true;

      }
    } else {
      $('#div_keep_password').addClass('hidden');
      $('#keep_password').prop("checked", "false");
      set_cookie = false;
    }
  }
  $('.acceptcookies').click(function() {
    $('#div_keep_password').removeClass('hidden');
    $('#keep_password').prop("checked", false);
  })
</script>