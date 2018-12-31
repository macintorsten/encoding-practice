<?php
include './includes/challenge_top.php';
?>
<h3 align="center">Login</h3>
<?php
// Implement this
// return htmlentities($email);
function encode($email) {
    return $email;
}

function isValidEmail($email) {
    // Must contain a '@'
    if (!strpos($email, '@')) {
        return false;
    }

    // Parse "username" and "domain"
    // TODO: Fix correct parsing if e-mail contains multiple @-chars
    $email_parts = explode('@', $email);
    $username = current($email_parts);
    next($email_parts);
    $domain = current($email_parts);
    
    // Only allow certain characters in username
    if (!preg_match('#^[a-zA-Z0-9\\._-]+$#', $username))
        return false;

    // Only allow certain characters in domain
    if (!preg_match('#^[a-zA-Z0-9\\.-]+$#', $domain))
        return false;
    
    // Domain must contain a dot
    if (!strpos($domain, '.')) {
        return false;
    }

    return true;
}

if (isset($_GET['email']) && isValidEmail($_GET['email'])) {
    echo '<div class="alert alert-warning" align="center">User with e-mail <strong>' . encode($_GET['email']) . '</strong> not found!</div>';
}
?>


<form method="get" class="form-horizontal" action=".">
<?php
if (isset($_GET['email']) && !isValidEmail($_GET['email'])) {
    echo '<div class="form-group has-warning">';
} else {
    echo '<div class="form-group">';
}
?>
                    <label class="control-label col-sm-2" for="email">E-mail:</label>
                    <div class="col-sm-8">
                        <input type="email" name="email" class="form-control" id="email" placeholder="Enter e-mail">
                        <?php
if (isset($_GET['email']) && !isValidEmail($_GET['email'])) {
    echo '<span class="help-block">Incorrectly formatted e-mail</span>';
}
?>
                    </div>

            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Password:</label>
                <div class="col-sm-8">
                    <input type="password" class="form-control" name="pwd" id="pwd" placeholder="Enter password">
                </div>

            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-8">
                    <div class="checkbox">
                        <label><input type="checkbox" name="remember" id="remember"> Remember me</label>
                    </div>
                </div>
            </div>
            <div class="form-group" align="center">
                <button type="submit" name="submit" value="Submit" class="btn btn-primary">Submit</button>
            </div>
            </form>

<?php
include './includes/challenge_bottom.php';
?>