<?php
$root = dirname(__DIR__);
$localhostRoot = "http://localhost/BIF_SS19/Abschlussprojekt";
$isLoggedIn = false;
$isAdmin = false;
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    $isLoggedIn = true;

    $dbManager = new DbManager($username);
}
if($isLoggedIn){
    $user = $dbManager->getUser($username);
    if($user == null){
    ?>
            <div class="alert alert-warning" role="alert">
                Es ist ein Datenbankfehler aufgetreten!
            </div>
            <?php 
    }
    else{
?>

<script src="<?php echo $localhostRoot;?>/res/js/profilemanagement.js"></script>
<div class="container">
    <h1>Benutzerdaten bearbeiten</h1>
    <form>
        <div class="form-group row">
            <label for="username" class="col-sm-2 col-form-label">Benutzername:</label>
            <div class="col-sm-4">
              <input type="text" class="form-control" name="username" value="<?php echo $user->username;?>" disabled/>
            </div>
          </div>
        <div class="form-group row">
            <label for="password" class="col-sm-2 col-form-label">Passwort:</label>
            <div class="col-sm-4">
              <input type="password" id="profilePwd" class="form-control" name="password"/>
              <span>Wenn das Feld leer gelassen ist bleibt das alte Passwort!</span>
            </div>
          </div>
        <div class="form-group row">
            <label for="firstname" class="col-sm-2 col-form-label">Vorname:</label>
            <div class="col-sm-4">
              <input type="text" id="profileFirstname" class="form-control" value="<?php echo $user->firstname;?>" name="firstname" required/>
            </div>
          </div>
        <div class="form-group row">
            <label for="lastname" class="col-sm-2 col-form-label">Nachname:</label>
            <div class="col-sm-4">
              <input type="text" id="profileLastname" class="form-control" value="<?php echo $user->lastname;?>" name="lastname" required/>
            </div>
          </div>
        <div class="form-group row">
            <label for="email" class="col-sm-2 col-form-label">E-Mail:</label>
            <div class="col-sm-4">
              <input type="email" id="profileEmail" class="form-control" value="<?php echo $user->email;?>" name="email" required/>
            </div>
          </div>
        <input type="button" class="btn btn-primary" value="Speichern" onclick="submitProfileData();"/>

        

    </form>
    <div id="profileUpdateInfo"></div>
    </div>                    
<?php 

    }
}