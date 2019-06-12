<?php

if (isset($_SESSION["username"])) {
    setCookie("username", $_SESSION["username"], time() - 3600, "/"); // cookie kann man nicht unseten--auf Vergangenheit setzten
    unset($_SESSION["username"]);
    session_destroy();
    $loggedOut = true;
    header("Location: ./index.php?site=login");
}
?>