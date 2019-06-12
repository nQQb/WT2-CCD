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
        <title>Webshop</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="res/css/bootstrap.min.css">
        <link rel="stylesheet" href="res/css/croppie.css"/>
        <link rel="stylesheet" href="res/css/style.css"/>
        <link rel="stylesheet" type="text/css" href="res/css/basic.min.css"/>
        <link rel="stylesheet" type="text/css" href="res/css/dropzone.min.css"/>
        <link rel="stylesheet" type="text/css" href="res/css/leaflet.css"/>
        <script src="res/js/jquery-3.4.1.min.js"></script>
        <script src="res/js/bootstrap.min.js"></script>
        <script src="res/js/croppie.js"></script>
        <script src="res/js/leaflet.js"></script>

        <script>
            var baseURL = '/BIF_SS19/Abschlussprojekt/';
        </script>

        <?php
        $root = __DIR__;
        $localhostRoot = "http://localhost/BIF_SS19/Abschlussprojekt";
        include("utility/DbManager.php");
        include("model/User.class.php");

        $loginSuccess = false;

        if (isset($_COOKIE["username"])) {
            $_SESSION["username"] = $_COOKIE["username"];
        }

        if (isset($_SESSION["username"])) {
            $loginSuccess = true;
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
            } else if ($site == "profilemanagement") {
                include("inc/profilemanagement.php");
            } else if ($site == "imprint") {
                include("inc/imprint.php");
            } else if ($site == "help") {
                include("inc/help.php");
            } else if ($site == "login") {
                include("inc/login.php");
            } else if ($site == "gallery") {
                include("inc/gallery.php");
            } else if ($site == "userdata") {
                include("inc/userdata.php");
            } else if ($site == "registration") {
                include("inc/registration.php");
            } else if ($site == "logout") {
                include("inc/logout.php");
            } else {
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
</html>
