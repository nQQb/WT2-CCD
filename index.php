<?php session_start(); ?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
<head>
    <meta charset="UTF-8">
    <title>Gallery</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="res/css/bootstrap.min.css">
    <link rel="stylesheet" href="croppie.css"/>
    <link rel="stylesheet" href="res/css/style.css"/>
    
    <script>
        var baseURL = '/BIF_SS19/Abschlussprojekt/';
    </script>

    <?php
    
    $root = __DIR__;
    $localhostRoot = "http://localhost/BIF_SS19/Abschlussprojekt";
    include("utility/DbManager.php");
    include("model/User.class.php");

    /*$users = array(
        "user1" => array("password" => "pw1", "mail" => "oliver@dumhart.net", "role" => "admin"),
        "user2" => array("password" => "pw2", "mail" => "oliver@dumhart.net", "role" => "user"));*/

    $loggedOut = false;
    $loginSuccess = false;
    /**/
    if (isset($_GET["action"]) && $_GET["action"] == "logout" && isset($_SESSION["username"])) {
        setCookie("username", $_SESSION["username"], time() - 3600);
        unset($_SESSION["username"]);
        session_destroy();
        $loggedOut = true;
    }

    if (isset($_COOKIE["username"]) && !$loggedOut) {
        $_SESSION["username"] = $_COOKIE["username"];
    }

    if (isset($_SESSION["username"])) {
        $loginSuccess = true;
    } else if (isset($_POST["username"]) && isset($_POST["password"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];

        $loginSuccess = false;
        $db = @new mysqli("localhost", "root", "", "abschlussprojekt");
        if (mysqli_connect_errno() == 0) {
            $sql = "SELECT `pwd` as hash FROM `user` WHERE `username` = ?";
            $result = $db->prepare($sql);
            $result->bind_param("s", $username);
            $result->execute();
            $result->bind_result($result_hash);
            $count = 0;
            while ($result->fetch()) {
                $hash = $result_hash;
                $count += 1;
            }

            if ($count == 1 && password_verify($password, $hash)) {
                $loginSuccess = true;
                $_SESSION["username"] = $username;
                if (isset($_POST["remember"]) && $_POST["remember"] == TRUE) {
                    setcookie("username", $username, time() + (60 * 60 * 24));
                }
            }
        }
        $db->close();

        if (!$loginSuccess) {
            echo "<script>alert('Falscher Benutzername oder falsches Passwort!');</script>";
        }
    }

    ?>
</head>
<body>

<header><?php include("inc/navigation.php"); ?></header>
<main>
    <?php
    if (isset($_GET["site"])) {
        $site = $_GET["site"];
        if ($site == "usermanagement") {
            include("inc/usermanagement.php");
        }else if ($site == "profilemanagement") {
            include("inc/profilemanagement.php");
        } else if ($site == "infos") {
            echo "<h1>Infos</h1>";
        } else if ($site == "login") {
            include("login.php");
        } else if ($site == "userdata" && isset($_SESSION["username"])) {
            include("userdata.php");
        } else if ($site == "registration" && !isset($_SESSION["username"])) {
            ?>
            <div class="container">
                <h1>Registration</h1>
                <form method="post" action="register.php">
                    <div class="form-group row">
                        <label for="username" class="col-sm-2 col-form-label">Benutzername:</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="username"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-sm-2 col-form-label">Passwort:</label>
                        <div class="col-sm-4">
                            <input type="password" class="form-control" name="password"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="firstname" class="col-sm-2 col-form-label">Vorname:</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="firstname"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="lastname" class="col-sm-2 col-form-label">Nachname:</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="lastname"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">E-Mail:</label>
                        <div class="col-sm-4">
                            <input type="email" class="form-control" name="email"/>
                        </div>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Registrieren"/>

                    <?php if (isset($_GET["msg"])) { ?>
                        <div class="alert alert-warning" role="alert">
                            <?php echo urldecode($_GET["msg"]); ?>
                        </div>
                    <?php } ?>

                </form>
            </div>
            <?php
        }else {
        include("inc/home.php");
    }
    } else {
        include("inc/home.php");
    }
    ?>
</main>
<footer class="text-center">
    <a href="index.php?site=imprint">Impressum</a>
</footer>
</body>
<script src="croppie.js"></script>
<script src="jquery-3.4.1.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</html>
