<div class="container">
    <h1>Registration</h1>
    <form method="post" action="./ajax/register.php">
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
            <label for="password2" class="col-sm-2 col-form-label">Passwort:</label>
            <div class="col-sm-4">
                <input type="password" class="form-control" name="password2"/>
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