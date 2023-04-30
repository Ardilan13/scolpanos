    <?php

    require_once("config/app.config.php");

    ?>

    <script src="<?php print appconfig::GetBaseURL(); ?>/assets/js/lib/jquery.min.js"></script>
    <script>
        ;var WebApi = (function projectConfig (WebApi, undefined) {

            'use strict';

            WebApi.Config = {

                Project : {
                    name             :   'Scol pa Nos',
                    majorVersion     :   '2',
                    minorVersion     :   '2',
                    bugFix           :   '0',
                    protoTypeVersion :   '1'
                },
                baseUrl : '<?php print appconfig::GetBaseURL(); ?>/',
                baseUri : '<?php print appconfig::GetBaseURL(); ?>/'
            };

            return WebApi;

        }(WebApi || {}));
    </script>
    <script src="<?php print appconfig::GetBaseURL(); ?>/assets/js/app_v1.3.js"></script>
    <!-- <script type="text/javascript" src="components/jquery/jquery.min.js"></script> -->
  	<script type="text/javascript" src="components/underscore/underscore-min.js"></script>
  	<script type="text/javascript" src="components/bootstrap2/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="components/bootstrap3/js/bootstrap.js"></script>
  	<script type="text/javascript" src="components/jstimezonedetect/jstz.min.js"></script>
    <script type="text/javascript" src="assets/js/lib/jquery.datepicker.min.js"></script>
  	<script type="text/javascript" src="assets/js/language/bg-BG.js"></script>
  	<script type="text/javascript" src="assets/js/language/nl-NL.js"></script>
  	<script type="text/javascript" src="assets/js/language/fr-FR.js"></script>
  	<script type="text/javascript" src="assets/js/language/de-DE.js"></script>
  	<script type="text/javascript" src="assets/js/language/el-GR.js"></script>
  	<script type="text/javascript" src="assets/js/language/it-IT.js"></script>
  	<script type="text/javascript" src="assets/js/language/hu-HU.js"></script>
  	<script type="text/javascript" src="assets/js/language/pl-PL.js"></script>
  	<script type="text/javascript" src="assets/js/language/pt-BR.js"></script>
  	<script type="text/javascript" src="assets/js/language/ro-RO.js"></script>
  	<script type="text/javascript" src="assets/js/language/es-CO.js"></script>
  	<script type="text/javascript" src="assets/js/language/es-MX.js"></script>
  	<script type="text/javascript" src="assets/js/language/es-ES.js"></script>
  	<script type="text/javascript" src="assets/js/language/ru-RU.js"></script>
  	<script type="text/javascript" src="assets/js/language/sk-SR.js"></script>
  	<script type="text/javascript" src="assets/js/language/sv-SE.js"></script>
  	<script type="text/javascript" src="assets/js/language/zh-CN.js"></script>
  	<script type="text/javascript" src="assets/js/language/cs-CZ.js"></script>
  	<script type="text/javascript" src="assets/js/language/ko-KR.js"></script>
  	<script type="text/javascript" src="assets/js/language/zh-TW.js"></script>
  	<script type="text/javascript" src="assets/js/language/id-ID.js"></script>
  	<script type="text/javascript" src="assets/js/language/th-TH.js"></script>
	<script type="text/javascript">
		$(function() {
		var pagePathName= window.location.pathname;
    var pgurl = pagePathName.substring(pagePathName.lastIndexOf("/") + 1);
    $("#sub-nav ul li a").each(function(){
				  if($(this).attr("href") == pgurl || $(this).attr("href") == '' )
				  {
					$(this).addClass("active");
					$(this).parent('li').parent('ul').parent('li').addClass('multilevel open active');
				  }

			 })
     $("#sub-nav ul li a").click(function(){
  				if($(this).attr("href") == '#'){
  					$(this).removeClass('active');
  				}
  			});
		});

	</script>
</body>
</html>
