<?php
ob_start();

require_once("config/app.config.php");
require_once("classes/spn_authentication.php");

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
$auth = new spn_authentication();

ob_flush();

?>
<?php $timer = appconfig::GetTimerCijfer_ls(); ?>
<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="en" dir="ltr" class="no-js lte9 lte8 lte7 ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" dir="ltr" class="no-js lte9 lte8 ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" dir="ltr" class="no-js lte9 ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" dir="ltr" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en" dir="ltr" class="no-js"> <!--<![endif]-->

<head>

  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800|Roboto:400,300,500" type="text/css">
  <link rel="stylesheet" href="<?php print appconfig::GetBaseURL(); ?>/assets/css/app.css" type="text/css" />
  <link rel="stylesheet" href="<?php print appconfig::GetBaseURL(); ?>/assets/css/horizontal_tab.css" type="text/css" />
  <link href="mobile/css/bootstrap.min.css" rel="stylesheet">
  <link href="mobile/css/caribedev.css" rel="stylesheet">

  <link rel="stylesheet" href="assets/css/calendar.css">
  <!-- <link href="mobile/css/caribedev.css" rel="stylesheet"> -->
  <title>SCOL PA NOS</title>

  <meta name="description" content="" />
  <meta name="author" content="XA TECHNOLOGIES / rudycroes.com" />

  <style>
    @media(max-width: 600px) {
      .no-border {
        padding: 0 !important;
      }

      .table-responsive {
        border: none;
      }

      #c,

      #tbl_verzuim_by_idstudent {
        font-size: 1.2rem;
      }

      #c tr,
      #tbl_verzuim_by_idstudent tr {
        display: flex;
        width: 100%;
        flex-direction: column;
        margin-bottom: 4%;
      }

      #c tr button,
      #tbl_verzuim_by_idstudent tr button {
        /* padding: 1% 0 !important;
        font-size: 1.1rem;
        font-weight: 500; */
        display: none;
      }

      #c td,
      #tbl_verzuim_by_idstudent td {
        padding: 1% 3%;
      }

      #c tr td:last-child {
        text-align: center;
      }

      #c tr:last-child {
        margin-bottom: 0;
      }

      #c td[data-titulo],
      #tbl_verzuim_by_idstudent td[data-titulo] {
        display: flex;
      }

      #c td[data-titulo]::before,
      #tbl_verzuim_by_idstudent td[data-titulo]::before {
        content: attr(data-titulo);
        font-weight: 700;
        color: black !important;
        margin-right: 1.5%;

      }

      #c thead,
      #tbl_verzuim_by_idstudent thead {
        display: none;
      }

      #frm_leerling_schooljaar {
        padding: 0;
        display: grid;
        font-size: 1rem;
        grid-template-columns: repeat(3, 20% 13%);
        align-items: center;
        justify-content: center;
        row-gap: 5%;
        width: 100%;
      }

      .tablinks {
        padding: 2.5%;
      }

      .cijfers {
        display: none !important;
      }

      .boton_alternado {
        background-color: red;
        color: white;
        border: none;
        padding: 1.5% 5%;
        border-radius: 5px;
        font-weight: 600;
      }

      .ocultar {
        margin-top: 5%;
        display: none;
      }

      .radio_buttons {
        display: flex;
        justify-content: space-evenly;
        align-items: center;
      }

      .lp-field {
        width: 25%;
        height: 10% !important;
      }
    }

    @media(min-width: 601px) {

      .boton_alternado,
      .radio_buttons {
        display: none;
      }
    }
  </style>

  <link rel="canonical" href="" />
  <link rel="shortcut icon" href="" />
  <script src="<?php print appconfig::GetBaseURL(); ?>/assets/js/lib/modernizr.min.js"></script>

<body class='body-color'>
  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Parents SPN</a>
      </div>
      <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
          <li class="active"><a id="student_link" href="" style="padding-bottom: 21px; text-align:center">Student</a></li>
          <?php if ($_SESSION["SchoolID"] == 12) : ?>
            <li class="active"><a href="https://drive.google.com/drive/folders/0B9eD9d6Yv4TyVVBCVkRCb2czZFk?resourcekey=0-HeO4AwVX142tnjg6KUWBgg&usp=sharing" target="_blank" style="padding-bottom: 21px;">School Documenten </a></li>
          <?php endif; ?>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li class="active"><a type='button' onclick="PrivateChatRoom()" id='request_open_chat'><span class="fa fa-comments fa-2x"></span><span></span></a></li>
          <li class="active"><a type='button' id='request_open_modal_secure_pin' href="#"><span class="fa fa-key fa-2x notification"></span><span></span></a></li>
          <li class="active"><a type='button' onclick="goContactParent()"><span class="fa fa-user-plus fa-2x address"></span><span></span></a></li>
          <li class="active"><a class='message_unread' href="#"><span class="fa fa-bell fa-2x notification"></span><span class='message_unread' id="unread"></span></a></li>
          <li class="active"><a href="#" onclick="location.href='logout_parent.php'"><span class="fa fa-power-off fa-2x"></span></a></li>
        </ul>
        <!-- <ul class="nav navbar-nav navbar-right">
          <li><a href="" class="fa fa-bell notification" style="font-size:30px;color:#FFDC66;	position:relative; top: 10px;;" aria-hidden="true" id="unread"></a></i>

          <li class="fa fa-power-off" style="font-size:30px;color:#FFDC66;	position:relative; top: 10px;;" aria-hidden="true" onclick="location.href ='logout_parent.php'"</i>
        </ul> -->
      </div><!--/.nav-collapse -->
    </div>
  </nav>


  <div class="container">
    <br>
    <br>
    <br>
    <br>
    <!-- Main component for a primary marketing message or call to action -->

    <div class="jumbotron white">
      <div>
        <button id='btn_chat_room' onclick="PrivateChatRoom()" style="color: black;" name='btn_chat_room' type='button' class='btn btn-primary btn-m-w btn-m-h'>Docent Connect</button>
      </div>
      <div id='dataRequest-student_detail' name='dataRequest-student_detail'></div>
      <hr>

      <?php if ($_SESSION['SchoolID'] == 4) : ?>

        <div class="row">
          <div class="form-group col-md-12 full-inset">
            <a href="templates/Schoolgids 2022-2023.pdf" target="_blank" class="btn btn-info btn-m-w pull-right mrg-left" id="btn_add_contact">schoolgids 2022-2023</a>
            <a href="templates/info_bewegingso_derwijs_lessen_Scol_Primario Kudawecha_gym.pdf" target="_blank" class="btn btn-info btn-m-w pull-right mrg-left" id="btn_clear_contact">info bewegingsonderwijs lessen Scol Primario Kudawecha</a>
            <a href="templates/Regla pa mayor.pdf" target="_blank" class="btn btn-info btn-m-w pull-right mrg-left" id="btn_clear_contact">Regla pa mayor</a>
          </div>
        </div>

      <?php endif; ?>

    </div>
    <div class="jumbotron white">
      <div class="pull-right">
        <div class="btn-group">
          <button class="btn btn-primary" data-calendar-nav="prev">
            << Prev</button>
              <button class="btn" data-calendar-nav="today">Today</button>
              <button class="btn btn-primary" data-calendar-nav="next">Next >></button>
        </div>
      </div>
      <div class="primary-bg-color brd-full">
        <div class="box">
          <div class="box-title full-inset brd-bottom">
            <h4></h4>
          </div>
        </div>
      </div>
      <div style="background:#ffffff;color:#000000;text-decoration:none;">
        <div id="calendar"></div>
      </div>
    </div>
    <div class="jumbotron white">
      <h4 class="primary-color">Laatste Cijfers:</h4>
      <hr>
      <div class="container container-fs">
        <div class="row">
          <div class="box">
            <div class="box-content">
              <div class="data-display">
                <div id='dataRequest-last_cijfers_detail' name='dataRequest-last_cijfers_detail'>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <style>
      .input-group {
        width: 7%;
      }
    </style>
    <div class="jumbotron white">
      <div class="row" style="margin: 0 auto;">
        <form class="form-inline col-md-12" name="frm_home_parent" id="frm_leerling_schooljaar">
          <label>2016-2017</label>
          <div class="input-group col-md-1">
            <input type="radio" class="form-group" name="_2016_2017" id="_2016_2017" />
            <input type="hidden" name="2016_2017" id="2016_2017" value="0" />
          </div>
          <label>2017-2018</label>
          <div class="input-group col-md-1">
            <input type="radio" class="form-group" name="_2017_2018" id="_2017_2018" />
            <input type="hidden" name="2017_2018" id="2017_2018" value="0" />
          </div>
          <label>2018-2019</label>
          <div class="input-group col-md-1">
            <input type="radio" class="form-group" name="_2018_2019" id="_2018_2019" />
            <input type="hidden" name="2018_2019" id="2018_2019" value="0" />
          </div>
          <label>2019-2020</label>
          <div class="input-group col-md-1">
            <input type="radio" class="form-group" name="_2019_2020" id="_2019_2020" />
            <input type="hidden" name="2019_2020" id="2019_2020" value="0" />
          </div>
          <label>2020-2021</label>
          <div class="input-group col-md-1">
            <input type="radio" class="form-group" name="_2020_2021" id="_2020_2021" />
            <input type="hidden" name="2020_2021" id="2020_2021" value="0" />
          </div>
          <label>2021-2022</label>
          <div class="input-group col-md-1">
            <input type="radio" class="form-group" name="_2021_2022" id="_2021_2022" />
            <input type="hidden" name="2021_2022" id="2021_2022" value="0" />
          </div>
          <label>2022-2023</label>
          <div class="input-group col-md-1">
            <input type="radio" class="form-group" name="_2022_2023" id="_2022_2023" checked="checked" />
            <input type="hidden" name="2022_2023" id="2022_2023" value="1" />
          </div>
        </form>
      </div>
      <div class="row">
        <div class="default-secondary-bg-color col-md-12 inset filter-bar brd-bottom clearfix">
          <div class="tab col-md-12" style="background:#ffffff;color:#000000;text-decoration:none;">
            <button class="tablinks active" onclick="openTab(event, 'Cijfers')">Cijfers</button>
            <button class="tablinks" onclick="openTab(event, 'Houding')">Houding</button>
            <button class="tablinks" onclick="openTab(event, 'Verzuim')">Verzuim</button>

            <?php if ($_SESSION['SchoolType'] == 1) : ?>
              <button class="tablinks" onclick="openTab(event, 'Avi')">Avi</button>
            <?php endif; ?>

            <button class="tablinks" onclick="openTab(event, 'Contact')">Contact Persoon</button>
            <!--<button class="tablinks" onclick="openTab(event, 'Documents')">Documents</button>
            <button class="tablinks" onclick="openTab(event, 'LastCijfers')">Last Cijfers</button> -->


          </div>
          <!-- <div role="tabpane7" class="collapse tabcontent col-md-12 inset filter-bar brd-bottom clearfix " id="LastCijfers">
            <h2 class="primary-color mrg-bottom">Cijfers</h2>Z

          </div> -->
          <div role="tabpane6" class="collapse tabcontent" id="Documents">
            <!-- <h2 class="primary-color mrg-bottom">Contact</h2> -->
            <div class="container container-fs">
              <div class="row">
                <div class="box">
                  <div class="box-content">
                    <div class="data-display">
                      <div id='dataRequest-documents_detail' name='dataRequest-documents_detail'>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div role="tabpane5" class="collapse in tabcontent col-md-12 inset filter-bar brd-bottom clearfix " id="Cijfers">
            <!-- <h2 class="primary-color mrg-bottom">Cijfers</h2> -->
            <div class="container container-fs">
              <div class="row">
                <div class="box">
                  <div class="box-content">
                    <div class="data-display">
                      <div style="display:none" id='dataRequest-cijfers_detail' name='dataRequest-cijfers_detail'>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div role="tabpane4" class="collapse tabcontent" id="Houding" style="background:#ffffff">
            <!-- <h2 class="primary-color mrg-bottom">Houding</h2> -->
            <div class="container container-fs">
              <div class="row">
                <div class="box">
                  <div class="box-content">
                    <div class="data-display">
                      <div id='dataRequest-houding_detail' name='dataRequest-houding_detail'>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div role="tabpane3" class="collapse tabcontent" id="Verzuim" style="background:#ffffff;">
            <!-- <h2 class="primary-color mrg-bottom">Verzuim</h2> -->
            <div class="container container-fs">
              <div class="row">
                <div class="box">
                  <div class="box-content">
                    <div class="data-display">
                      <div id='dataRequest-verzuim_detail' name='dataRequest-verzuim_detail' class='col-md-10'>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php if ($_SESSION['SchoolType'] == 1) : ?>
            <div role="tabpane3" class="collapse tabcontent" id="Avi" style="background:#ffffff;">
              <!-- <h2 class="primary-color mrg-bottom">Avi</h2> -->
              <div class="container container-fs">
                <div class="row">
                  <div class="box">
                    <div class="box-content">
                      <div class="data-display">
                        <div id='dataRequest-avi_detail' name='dataRequest-avi_detail'>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php endif; ?>

          <div role="tabpane1" class="collapse tabcontent" id="Contact">
            <!-- <h2 class="primary-color mrg-bottom">Contact</h2> -->
            <div class="container container-fs">
              <div class="row">
                <div class="box">
                  <div class="box-content">
                    <div class="data-display">
                      <div id='dataRequest-contact_detail' name='dataRequest-contact_detail'>
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
  </div>
  <!-- Modal -->
  <button id='btn_open_modal_secure_pin' class='hidden' type='button' data-toggle="modal" data-target="#SecurePinModal" href="#"></button>
  <!-- <button id='btn_open_modal_secure_pin' type='button' data-toggle="modal" data-target="#SecurePinModal" href="#" ></button> -->

  <div class="modal fade" id="SecurePinModal" tabindex="-1" role="dialog" aria-labelledby="SecurePinModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" id="SecurePinModalLabel">Change Secure PIN</h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div style="text-align: center">
              <h4 id='message_modal' class="modal-title">The secure pin would be numeric</h4>
            </div>
          </div>
          <form id='frm_change_secure_pin'>

            <br>
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-4 control-label">Old Secure Pin</label>
              <div class="col-sm-8">
                <input id="old_secure_pin" name="old_secure_pin" type='number' pin="securepin" placeholder="Old SecurePin" class="form-control">
              </div>
            </div>
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-4 control-label">New Secure Pin</label>
              <div class="col-sm-8">
                <input id="new_secure_pin" name="new_secure_pin" type='number' pin="securepin" placeholder="New " class="form-control">
              </div>
            </div>
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-4 control-label">Confirm new Secure Pin</label>
              <div class="col-sm-8">
                <input id="confirm_new_secure_pin" type='number' pin="securepin" placeholder="Confirm SecurePin" class="form-control">
              </div>
            </div>
            <input name="studentid" id="studentid" class="hidden" value=''>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" id='close_modal_securepin' class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" id='btn_change_secure_pin' class="btn btn-primary">Save</button>
        </div>
      </div>
    </div>
  </div>

  <button id='btn_question_add_contact_modal' class='hidden' type='button' data-toggle="modal" data-target="#question_add_contact_modal" href="#"></button>
  <div class="modal fade" id="question_add_contact_modal" tabindex="-1" role="dialog" aria-labelledby="question_add_contactTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><i class="icon fa fa-close"></i></button>
          <h3 class="modal-title">There are no contacts with emai</h3>
        </div>
        <div class="modal-body">
          <p>It seems that your contacts do not have a defined email or do not have contacts... Do you want to add a new contact now to be able to change the SecurePIN?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" onclick="goContactParent()" class="btn btn-primary">Yes, add contact</button>
        </div>
      </div>
    </div>
  </div>

  <button id='btn_no_documents_modal' class='hidden' type='button' data-toggle="modal" data-target="#no_documents_modal" href="#"></button>
  <div class="modal fade" id="no_documents_modal" tabindex="-1" role="dialog" aria-labelledby="no_documentsTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><i class="icon fa fa-close"></i></button>
          <h3 class="modal-title">There are no documents</h3>
        </div>
        <div class="modal-body">
          <p>For this assignment there is no document attached</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" id='btn_close_no_documents_modal' data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalsee" tabindex="-1" role="dialog" aria-labelledby="no_documentsTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><i class="icon fa fa-close"></i></button>
          <h3 class="modal-title"></h3>
        </div>
        <div class="modal-body">
          <p id="idpsee"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" id='btn_modalsee' data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

</body>


<?php include 'document_end.php'; ?>
<script type="text/javascript" src="assets/js/calendar.js"></script>
<script type="text/javascript" src="assets/js/app_calendar.js"></script>
<script type="text/javascript" src="assets/js/parent.js?06"></script>
<script type="text/javascript">
  function ChatRoom(vak, rapnr) {

    var id = '<?php echo $_SESSION["UserGUID"]; ?>';
    var code = '<?php echo $_SESSION["Identity"]; ?>';
    var klass = '<?php echo $_SESSION["Class"]; ?>';
    var idschool = '<?php echo $_SESSION["SchoolID"]; ?>';
    var d = new Date();
    d.setMinutes(d.getMinutes() + 10)
    var time = d.getTime() / 1000;
    var room = klass + "-" + vak + "-" + rapnr;
    var idroom = klass + "-" + vak + "-" + rapnr + "-" + idschool;
    //var url = "https://localhost:44399/?" + room + "&id=" id;
    //var url = "https://localhost:44399/?room=" + room + "&id=" + id + "&code=" + code + "&type=2" + "&idschool=" + idroom;
    var url = "https://digiroom.madworksglobal.com/?room=" + room + "&id=" + id + "&code=" + code + "&type=2" + "&idroom=" + idroom + "&school=1" + "&ts=" + time;
    window.open(url, "_blank");
  }

  function PrivateChatRoom() {

    var id = '<?php echo $_SESSION["UserGUID"]; ?>';
    var id = '<?php echo $_SESSION["UserGUID"]; ?>';
    var code = '<?php echo $_SESSION["Identity"]; ?>';
    var idschool = '<?php echo $_SESSION["SchoolID"]; ?>';
    var FirstName = '<?php echo $_SESSION["FirstName"]; ?>';
    var LastName = '<?php echo $_SESSION["LastName"]; ?>';
    var d = new Date();
    d.setMinutes(d.getMinutes() + 10)
    var time = d.getTime() / 1000;
    var room = FirstName + " " + LastName;
    var idroom = id + "-" + idschool;
    //var url = "https://localhost:44399/?" + room + "&id=" id;
    //var url = "https://localhost:44399/?room=" + room + "&id=" + id + "&code=" + code + "&type=2" + "&idschool=" + idroom;
    var url = "https://digiroom.madworksglobal.com/?room=" + room + "&id=" + id + "&code=" + code + "&type=4" + "&idroom=" + idroom + "&school=1" + "&ts=" + time;
    window.open(url, "_blank");
  }
</script>