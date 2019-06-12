<?php session_start();

$root = dirname(__DIR__);
$isLoggedIn = false;
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    $isLoggedIn = true;

    include($root."/utility/DbManager.php");
    $dbManager = new DbManager($username);
}
if($isLoggedIn){
    if(isset($_POST["operation"]) && isset($_POST["image"]) && $_POST["operation"] == "delete"){
        $image = $_POST["image"];
        $success = $dbManager->deleteImage($image);
        if($success){
            $uploadPath = $root."/pictures/";
            $thumbnailPath = $uploadPath."thumbs/";
            if(file_exists($uploadPath.$image)){
                unlink($uploadPath.$image);
            }
            if(file_exists($thumbnailPath.$image)){
                unlink($thumbnailPath.$image);
            }
            echo $success;
        }
    }
    else{
        echo $dbManager->getImageData();
    }
}
