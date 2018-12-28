<?php
header("X-XSS-Protection: 0");
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Pragma" content="no-cache">

    <title>Challenge</title>

    <script src="js/lib.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp"
        crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
</head>

<body>
    <div class="alert alert-success" role="alert" id="success" style="display: none;">
    <?php
        echo '<h4 class="alert-heading">Well done!</h4>';
    if ($SUCCESS) {
        echo $SUCCESS;
    } else {
        echo 'You successfully found the XSS.';
    }
    ?>
    </div>
    <div class="container">
        <h1>Challenge</h1>
  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#info"><span class="glyphicon glyphicon-info-sign"></span> Info</a></li>
    <li><a data-toggle="tab" href="#hints"><span class="glyphicon glyphicon-eye-open"></span> Hints</a></li>
    <li><a data-toggle="tab" href="#check"><span class="glyphicon glyphicon-check"></span> Check</a></li>
  </ul>

  <div class="tab-content">
    <div id="info" class="tab-pane fade in active">
        <p class="container">
            <?php
            if ($HELP) {
                echo $HELP;
            } else {
                echo 'Solve the challenge.';
            }
            ?>
        </p>
    </div>
    <div id="hints" class="tab-pane fade">
        <p class="container">
            <?php
            if ($HINTS) {
                echo $HINTS;
            } else {
                echo 'You successfully found the XSS.';
            }
            ?>
        </p>
    </div>
    <div id="check" class="tab-pane fade">
        <p class="container">
            <a href="javascript:PerformXSSCheck()">Click here to check for XSS</a>
        </p>
        <ul id="check_log">
        </ul>
    </div>
  </div>
        <div class="panel panel-primary">
            <div class="panel-heading">PHP: <em><?= basename(__FILE__) ?></em></div>
            <div class="panel-body">

            <!-- start -->