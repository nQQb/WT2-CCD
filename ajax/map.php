<?php session_start();

if(isset($_POST["image"]) && isset($_POST["lat"]) && isset($_POST["long"])){
    $image = $_POST["image"];
    $lat = $_POST["lat"];
    $long = $_POST["long"];
    if (isset($_SESSION["username"])) {
        $username = $_SESSION["username"];
        $isLoggedIn = true;

        include("utility/DbManager.php");
        $dbManager = new DbManager($username);
        
        $dbManager->updateCoordinates($image, $lat, $long);
    }
}
