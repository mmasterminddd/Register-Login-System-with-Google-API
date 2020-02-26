<?php
  session_start();
  require "includes/dbh.inc.php";
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="description" content="Project gia to xeimerino eksamino sto mathima Asfaleia Pliroforiakwn Systimatwn">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <title>Asfaleia Pliroforiakwn Systimaton</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <header>
      <nav class="nav-header-main">
        <a class="header-logo" href="index.php">
          <img  src="img/logo.png" alt="logo">
        </a>
      </nav>
      <div class="header-login">
        <?php
        if (!isset($_SESSION['id'])) {
            echo '<a href="signup.php" class="header-signup">Signup</a>';
        } elseif (isset($_SESSION['id'])) {
            echo '<form action="includes/logout.inc.php" method="post">
            <button type="submit" name="login-submit">Logout</button>
          </form>';
        }
        ?>
      </div>
    </header>
