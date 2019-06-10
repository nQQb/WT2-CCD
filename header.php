<nav class="navbar navbar-expand-lg navbar-light bg-light">

    <a class="navbar-brand" href="#">
        <img src="http://cherrytreemachines.co.uk/wp-content/uploads/2017/04/example-logo.jpg" width="100" height="50" alt="">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item 
            <?php
            if (!isset($_GET["site"]) || (isset($_GET["site"]) && $_GET["site"] != "gallery" && $_GET["site"] != "infos")) {
                echo "active";
            }
            ?>
                ">
                <a class="nav-link" href="index.php?site=home">Home <?php
                    if (isset($_GET["site"]) && $_GET["site"] != "gallery" && $_GET["site"] != "infos") {
                        ?> <span class="sr-only">(current)</span> <?php
                    }
                    ?>
                </a>
            </li>
            <li class="nav-item
            <?php
            if (isset($_GET["site"]) && $_GET["site"] == "gallery") {
                echo "active";
            }
            ?>
                ">
                <a class="nav-link" href="index.php?site=gallery">Galerie <?php
                    if (isset($_GET["site"]) && $_GET["site"] == "gallery") {
                        ?> <span class="sr-only">(current)</span> <?php
                    }
                    ?></a>
            </li>
            <li class="nav-item
            <?php
            if (isset($_GET["site"]) && $_GET["site"] == "infos") {
                echo "active";
            }
            ?>
                ">
                <a class="nav-link" href="index.php?site=infos">Infos <?php
                    if (isset($_GET["site"]) && $_GET["site"] == "gallery") {
                        ?> <span class="sr-only">(current)</span> <?php
                    }
                    ?></a>
            </li>
            <?php if (isset($_SESSION["username"])) { ?>
                <li class="nav-item
                <?php
                if (isset($_GET["site"]) && $_GET["site"] == "special") {
                    echo "active";
                }
                ?>
                    ">
                    <a class="nav-link" href="index.php?site=special">Spezial <?php
                        if (isset($_GET["site"]) && $_GET["site"] == "special") {
                            ?> <span class="sr-only">(current)</span> <?php
                        }
                        ?></a>
                </li>
                <li class="nav-item
                <?php
                if (isset($_GET["site"]) && $_GET["site"] == "userdata") {
                    echo "active";
                }
                ?>
                    ">
                    <a class="nav-link" href="index.php?site=userdata">Daten bearbeiten <?php
                        if (isset($_GET["site"]) && $_GET["site"] == "userdata") {
                            ?> <span class="sr-only">(current)</span> <?php
                        }
                        ?></a>
                </li>
            <?php } ?>
        </ul>
    </div>
    <?php
    if (!$loginSuccess) {
        ?>
        <div id="loginicon" class="mr-1">
            <img src="https://cdnjs.cloudflare.com/ajax/libs/octicons/8.5.0/svg/sign-in.svg" width="30" height="30"/>
        </div>
        <div id="loginform">
            <?php
            if (isset($_GET["site"])) {
            $site = "?site=".$_GET["site"];
            } else {
            $site = "";
            }
            ?>
            <form action="index.php<?php echo $site; ?>" method="post">
                <input name="username" type="text" placeholder="Benutzername">
                <input name="password" type="password" placeholder="Passwort">
                <input name="remember" type="checkbox" value="false">
                <label>Eingeloggt bleiben</label>
                <input type="submit" value="Einloggen">
                <button id="loginForgotButton" type="button">Zugangsdaten vergessen</button>
            </form>
            <a href="index.php?site=registration">Registrieren</a>
        </div>
        <?php
    } else {
        $username = $_SESSION["username"];
        $db = @new mysqli("localhost", "root","", "abschlussprojekt");
        if(mysqli_connect_errno() == 0){
            $sql = "SELECT `firstname`, `lastname` FROM `user` WHERE `username` = ?";
            $result = $db->prepare($sql);
            $result->bind_param("s", $username);
            $result->execute();
            $result->bind_result($firstname, $lastname);
            $result->fetch();
            $result->close();
        }
        
        echo "eingeloggt als $firstname $lastname";

        if (isset($_GET["site"])) {
            $site = "&site=" . $_GET["site"];
        } else {
            $site = "";
        }
        ?>
        <a href="index.php?action=logout<?php echo $site; ?>" id="logouticon" class="mr-1">
            <img src="https://cdnjs.cloudflare.com/ajax/libs/octicons/8.5.0/svg/sign-out.svg" width="30" height="30"/>
        </a>
        <?php
    }
    ?>
</nav>
<script>
    $("#loginForgotButton").click(function(){
        let username = $("input[name='username']")[0].value;
        $.post("send.php", {
            username: username
        }, function(data, status){
            alert(data);
        });
    });
    
        $("#loginicon").click(function () {
            $("#loginform").toggle();
        });
    </script>