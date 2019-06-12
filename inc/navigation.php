<?php
$xml = simplexml_load_file($root . "/config/navigation.xml") or die("Error: Cannot create object");

$isLoggedIn = false;
$isAdmin = false;

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    $isLoggedIn = true;

    $dbManager = new DbManager($username);
    $isAdmin = $dbManager->isAdmin;
}
?>


<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">
        Gallery
    </a>
    <ul class="navbar-nav">
        <?php
        if ($isLoggedIn && $isAdmin) {
            $links = $xml->admin->link;
        } else if ($isLoggedIn) {
            $links = $xml->registered->link;
        } else {
            $links = $xml->anonym->link;
        }

        foreach ($links as $link) {
            ?>
            <li class="nav-item">
                <a class="nav-link" href="index.php?site=<?php echo $link['site']; ?>"><?php echo $link; ?></a>
            </li>
<?php
}
if ($isLoggedIn) {
    ?>
            <li class="nav-item">
                <span class="nav-link">eingeloggt als: <?php echo $username; ?></span>
            </li>
    <?php
}
?>
    </ul>
</nav>
