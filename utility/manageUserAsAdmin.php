<?php session_start();

require '../PHPMailer-master/PHPMailerAutoload.php';

if(isset($_POST["userId"]) && isset($_POST["operation"])){
$root = dirname(__DIR__);
    $userId = $_POST["userId"];
    $op = $_POST["operation"];
    $isLoggedIn = false;
    $isAdmin = false;
    if (isset($_SESSION["username"])) {
        $username = $_SESSION["username"];
        $isLoggedIn = true;

        include($root."/utility/DbManager.php");
        $dbManager = new DbManager($username);
        $isAdmin = $dbManager->isAdmin;
    }
    if($isLoggedIn && $isAdmin){
        if($op == "deleteUser"){
            echo $dbManager->deleteUser($userId);
        }
        else if($op == "resetPassword"){
            $success = -1;
            $email = $dbManager->getEmail($userId);
            if($email != ""){
                $pwd = substr(md5(microtime()),rand(0,26),5);
                $pwdhash = password_hash($pwd, PASSWORD_DEFAULT);
                if($dbManager->setPassword($pwdhash, $userId)){
                    $success = sendMail($email, $pwd);
                }
            }
            echo $success;
        }
        else if($op == "toggleActive"){
            echo $dbManager->toggleActiveState($userId);
        }
    }
}

function sendMail($address, $pwd){
    $email = new PHPMailer();
    $email->isSMTP();
    $email->SMTPDebug = 2;
    $email->Host = "smtp.technikum-wien.at";
    $email->Port = 587;
    $email->SMTPAuth = true;
    $email->Username = "xxxxx";
    $email->Password = "xxxxxx";
    $email->SMTPSecure = "tls";

    $email->setFrom("if18b074@technikum-wien.at", "Ue3");
    $email->addAddress($address);
    $email->isHTML(true);


    $email->Subject = "Passwort vergessen";
    $email->Body = $pwd;

    if($email->send()){
        return 1;
    }
    else{
        return -1;
    }
}