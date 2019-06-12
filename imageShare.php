<?php session_start();
if(isset($_POST["image"]) && isset($_POST["operation"])){
    $image = $_POST["image"];
    $op = $_POST["operation"];
    $isLoggedIn = false;
    if (isset($_SESSION["username"])) {
        $username = $_SESSION["username"];
        $isLoggedIn = true;

        include("utility/DbManager.php");
        $dbManager = new DbManager($username);
    }
    if($isLoggedIn){
        if(isset($_POST["username"])){
    $otherName = $_POST["username"];
            if($op == "share"){
            $dbManager->shareImage($image, $otherName);
            }
        else if($op == "remove"){
                echo $dbManager->removeShareFromImage($image, $otherName);
            }
        }
        else if($op == "query"){
            $dbManager->getSharedUsers($image);
        }
    }
}