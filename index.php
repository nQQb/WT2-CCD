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
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="res/css/bootstrap.min.css">
    <link rel="stylesheet" href="croppie.css" />
</head>
<body>

<header><?php include("inc/navigation.php"); ?></header>
<main>
    <?php
    /*if (isset($_GET["site"])) {
        $site = $_GET["site"];
        if ($site == "gallery") {
            include("gallery.php");
        } else if ($site == "infos") {
            echo "<h1>Infos</h1>";
        } else if ($site == "special" && isset($_SESSION["username"])) {
            echo "<h1>Spezial</h1>";
        } else {
            echo "<h1>Home</h1>";
        }
    } else {
        echo "<h1>Home</h1>";
    }*/
    ?>
</main>
</body>
<script src="croppie.js"></script>
<script src="jquery-3.4.1.min.js"></script>
</html>
