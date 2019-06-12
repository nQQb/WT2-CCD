<!-- Wenn man als Admin eingeloggt ist hat man das Usermanagement in der Navbar sichtbar und kann User verwalten-->
<?php
$root = dirname(__DIR__);
$localhostRoot = "http://localhost/BIF_SS19/Abschlussprojekt";
$isLoggedIn = false;
$isAdmin = false;
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    $isLoggedIn = true;

    $dbManager = new DbManager($username);
    $isAdmin = $dbManager->isAdmin;
}
if($isLoggedIn && $isAdmin){
    $others = json_decode($dbManager->getOtherUsers());
    ?>
<script src="<?php echo $localhostRoot;?>/res/js/usermanagement.js"></script>
<table class="table">

  <thead>
    <tr>
      <th scope="col">Username</th>
      <th scope="col">Aktiv</th>
      <th scope="col"></th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach($others as $user){
        ?>
    <tr>
      <td><?php echo $user->username; ?><td>
      <td>
          <input class="form-check-input" type="checkbox" <?php if($user->isActive == 1) echo "checked='checked'";?> onchange="toggleActive(this, <?php echo $user->id;?>)"></td>
      <td>
          <button type="button" class="btn btn-secondary" onclick="deleteUser(this, <?php echo $user->id;?>);">Löschen</button>
      </td>
      <td>
          <button type="button" class="btn btn-secondary" onclick="resetPassword(<?php echo $user->id;?>);">Passwort-Reset</button>
      </td>
    </tr>

    <?php
    }
    ?>
  </tbody>
</table>
    <?php
}
