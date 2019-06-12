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
<!-- Dies Navbar wird mittels Bootstrap erstellt --->

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="#">
        Gallery
    </a>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <?php
 // Hier wird mittels php überprüft ob man als Admin oder normaler User eingeloggt ist
 // Admin hat höhere rechter and der normale User und kann deshalb User verwalten.
            if($isLoggedIn && $isAdmin) {
                $links = $xml->admin->link;
                }
                else if($isLoggedIn){
                $links = $xml->registered->link;
                }
                else{
                    $links = $xml->anonym->link;
                }

            foreach ($links as $link) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?site=<?php echo $link['site'];?>"><?php echo $link; ?></a>
                </li>
            <?php } ?>
        </ul>
    </div>



<?php
// Wenn eingeloggt, zeigt es an das man eingeloggt ist-->
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
