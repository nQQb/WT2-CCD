<!----------------------------------------
-----------DESIGN OBERFLÄCHE--------------
----------------------------------------->
<div class="container">
    <h1>Login</h1>
    <form method="post" action="./ajax/process.php">
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
            <input type="checkbox" class="form-check-input" id="checkbox" name="remember">
            <label class="form-check-label" for="exampleCheck1">Remember me</label>
        </div>
        <input type="submit" class="btn btn-primary" value="Log in"/>
    </form>
    <?php if (isset($_GET["msg"])) { ?>
            <div class="alert alert-warning" role="alert">
                <?php echo urldecode($_GET["msg"]); ?>
            </div>
        <?php } ?>
</div>