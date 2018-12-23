<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="Pragma" content="no-cache">

  <title>Challenge <?= $challenge_no ?></title>

  <script src="js/lib.js"></script>

<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


</head>

<body>

<div class="alert alert-success" role="alert" id="success" style="display: none;">
  <h4 class="alert-heading">Well done!</h4>
  <p>You successfully found the XSS. To complete the challenge you need to implement <code>encode($email)</code> function in <strong>index.php</strong> to so it applies the correct encoding before echoing the e-mail. Do not assume any particular input validation.</p>
</div>

<div class="container">
    <h1>Challenge <?= $challenge_no ?></h1>

<?php

// 
function encode($email) {
    //return htmlentities($email);
    return $email;
}

?> 

<?php

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

?>

    <div class="panel panel-info">
      <div class="panel-heading">Instructions</div>
      <div class="panel-body">
<ul>
<li>Fill in the form below and see if you can find a reflected XSS, look at the source code if you need hint.</li>
<li>Identify where the parameter is reflected in the HTML source code. What mistake makes the page vulnerable?</li> 
<li>Fix the issue by applying the correct encoding in the PHP code.</li> 
</ul>
</div>
    </div>

    <div class="panel panel-primary">



      <div class="panel-heading">PHP: <em>challenge_1.php</em></div>
 <div class="panel-body">
<?php
$email = $_GET['email'];
echo isValidEmail($email);
if ($email && isValidEmail($email)) {
    echo '<div class="alert alert-warning" align="center">User with e-mail <strong>' . encode($email) . '</strong> not found!</div>';
}
?>
<h3 class="container">Login</h3>

        <form method="get" class="form-horizontal" action="."> 
<?php
$email = $_GET['email'];
if ($email && !isValidEmail($email)) {
    echo '<div class="form-group has-warning">';
} else {
    echo '<div class="form-group">';
}
?>
            <label class="control-label col-sm-2" for="email">Email:</label>
            <div class="col-sm-8">
              <input type="email" name="email" class="form-control" id="email" placeholder="Enter email">
<?php
$email = $_GET['email'];
if ($email && !isValidEmail($email)) {
    echo '<span class="help-block">Incorrect format</span>';
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
              <button type="submit" value="Submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
    </div>
</div>





</div>

</body>
</html>