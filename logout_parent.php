<?php

ob_start();

if(session_status() == PHP_SESSION_NONE)
{
	session_start();
}

$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();


/* redirect to login page */
header("Location: login_parent.php");
/* redirect to login page */

ob_flush();

?>
