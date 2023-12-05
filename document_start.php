<?php

ob_start();

require_once("config/app.config.php");
require_once("classes/spn_authentication.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//if(appconfig::GetHTTPMode() == "HTTPS")
//{
//    header("HTTP/1.1 301 Moved Permanently");
//   header("Location: " . appconfig::GetBaseURL() . "/" . __FILE__);
//}

$auth = new spn_authentication();
$auth->CheckSessionValidity();

if ($auth) {
?>
    <script>
        function logout() {
            alert("SESION EXPIRED");
            window.location.href = "logout.php";
        }

        document.addEventListener("DOMContentLoaded", function() {
            console.log("documento listo");
            const tiempoSesion = 45 * 60 * 1000; // 45 minutos
            let temporizador;

            function resetearSesion() {
                clearTimeout(temporizador);
                temporizador = setTimeout(logout, tiempoSesion);
            }

            // Eventos para restablecer la sesión cuando el usuario interactúa con la página
            document.addEventListener("mousemove", resetearSesion);
            document.addEventListener("keydown", resetearSesion);
            document.addEventListener("scroll", resetearSesion);
            document.addEventListener("click", resetearSesion);
            document.addEventListener("touchmove", resetearSesion);
            document.addEventListener("touchstart", resetearSesion);

            // Iniciar el temporizador al cargar la página
            resetearSesion();
        });
    </script>
<?php
}

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
    <link rel="stylesheet" href="assets/css/calendar.css">
    <title>SCOL PA NOS</title>

    <meta name="description" content="" />
    <meta name="author" content="XA TECHNOLOGIES / rudycroes.com" />

    <link rel="canonical" href="" />
    <link rel="shortcut icon" href="" />
    <script src="<?php print appconfig::GetBaseURL(); ?>/assets/js/lib/modernizr.min.js"></script>

<body>
    <input type="hidden" name="timer_cijfer_ls" id="timer_cijfer_ls" value=<?php echo $timer; ?> />