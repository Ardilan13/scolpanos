<?php include 'document_start.php'; ?>

<?php include 'sub_nav.php'; ?>
<link rel="stylesheet" href="<?php print appconfig::GetBaseURL(); ?>/assets/dropzone-master/dist/dropzone.css" type="text/css" />
<div class="push-content-220">
  <main id="main" role="main">
    <?php include 'header.php'; ?>
    <?php $UserRights= $_SESSION['UserRights'];
    if ($UserRights != "BEHEER" && $UserRights != "ADMINISTRATIE"){
      include 'redirect.php';} else{?>
        <?php
        require_once("classes/spn_leerling.php");
        $t = new spn_leerling();
        $school_id = $_SESSION["SchoolID"];

        $target=  $t->list_target_class($school_id, false);
        $from = $t->list_from_class($school_id, false);
        ?>
        <!-- <link rel="stylesheet" href="assets/css/pruebaa.css"> -->
        <section>
          <div class="container container-fs">
            <div class="row">
              <div class="default-secondary-bg-color col-md-12 inset brd-bottom">
                <h1 class="primary-color">Klas samenstellen</h1>
                <?php include 'breadcrumb.php'; ?>
              </div>
            </div>
            <div class="row mrg-bottom">
              <div class="col-md-10 col-md-offset-1 full-inset pull-left" id="div_from_class2">
                <div class="primary-bg-color brd-full">
                  <div class="box">
                    <div class="box-title full-inset brd-bottom">
                      <div class="row">
                        <h2 class="text-center">Upload Images</h2>
                      </div>
                    </div>
                    <div class="box-content full-inset default-secondary-bg-color" id="from_message">
                      <div class="row">
                        <div class="col-md-7 col-md-offset-2">
                          <div  class="alert alert-info">
                            <ul>
                              <li>1- The files should be JPG</li>
                              <li>2- All images will be uploaded at the same time</li>
                              <li>3- The name of image should be the student number exp.: "454645.jpg"</li>
                              <li>4- If any file/image fails, an X will appear in the center of the image </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                      <div class="box-content full-inset default-secondary-bg-color" id="from_pics">
                        <div class="row" id="draggable">
                          <!-- <div class="dataRequest-student_pic" id="drag_from"></div> -->
                          <form action="ajax/update_images_leerling.php" class="dropzone col-md-10 col-md-offset-1" id='images_form'>
                            <div class="fallback ">
                              <input name="leerling_images" type="file" multiple accept="image/*"/>
                            </div>
                            <input class="hidden" id="student_klas" />
                          </form>
                        </div>
                        <!-- <div class="form-group">
                        <br>
                        <button id="btn_submit_cijfers" name="btn_submit_cijfers" type="submit" class="btn btn-primary btn-m-w btn-m-h pull-right">Upload</button>
                      </div> -->
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
  <?php include 'document_end.php';}?> <!-- PLEASE CAREFULLY WIH THIS "}" THAT CLOSE SECURITY  ELSE IF -->

  <script src="<?php print appconfig::GetBaseURL(); ?>/assets/dropzone-master/dist/dropzone.js"></script>

  <script>
  $('html, body').animate({scrollTop:0}, 'fast');

  // $(function(){
  //   Dropzone.options.myAwesomeDropzone = {
  //     maxFilesize: 5,
  //     acceptedFiles: 'image/*',
  //     url: "ajax/update_images_leerling.php",
  //   };
  // })
</script>
