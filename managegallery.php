<?php session_start();

$root = dirname(__DIR__);
$isLoggedIn = false;
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    $isLoggedIn = true;

    include("utility/DbManager.php");
    $dbManager = new DbManager($username);
}
if($isLoggedIn){
    echo $dbManager->getImageData();
}
