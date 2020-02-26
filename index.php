<?php
require "header.php";
require_once __DIR__ . '/vendor/autoload.php';

const CLIENT_ID = '335756729325-f6aks4ir4up73ismsv3fool4vjij58g0.apps.googleusercontent.com';
const CLIENT_SECRET = 'u6eoPHMJ2Fi55n4xW7-fDLgd';
const REDIRECT_URI = 'https://asfplir.info';
$client = new Google_Client();
$client->setClientId(CLIENT_ID);
$client->setClientSecret(CLIENT_SECRET);
$client->setRedirectUri(REDIRECT_URI);
$client->setScopes('email');
$plus = new Google_Service_Plus($client);
?>

    <main>
      <div class="wrapper-main">
        <section class="section-default">
          <?php

if (isset($_GET['code'])) {
    $client->authenticate($_GET['code']);
    $_SESSION['access_token'] = $client->getAccessToken();
    $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
    header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
    $me = $plus->people->get('me');
    $name = $me['displayName'];
    $email = $me['emails'][0]['value'];
    $profile_image_url = $me['image']['url'];
    $_SESSION['id'] = $me['id'];
} else {
    $authUrl = $client->createAuthUrl();
}

if (isset($_GET["error"])) {
    if ($_GET["error"] == "passwordweak") {
        echo '<p class="signuperror">Your password is weak. At least 1 uppercase letter, at least 1 lowercase letter, at least 1 number, and at least 1 special char!</p>';
    }

    if ($_GET["error"] == "invalidmail") {
        echo '<p class="signuperror">Invalid e-mail!</p>';
    }

    if ($_GET["error"] == "wrongpwd") {
        echo '<p class="signuperror">Wrong Password!</p>';
    }

    if ($_GET["error"] == "wronguidpwd") {
        echo '<p class="signuperror">Your account doesnt exist!</p>';
    }

    if ($_GET["error"] == "passwordcheck") {
        echo '<p class="signuperror">Your passwords do not match!</p>';
    }

    if ($_GET["error"] == "deleted") {
        echo '<p class="signuperror">Your account has been deleted!</p>';
    } elseif ($_GET["error"] == "usertaken") {
        echo '<p class="signuperror">Email is already taken!</p>';
    }

    if ($_GET["error"] == "blocked") {
        echo '<p class="signuperror">Your account is blocked because of 3 failed attemps to log in.</p>';
    }

    if ($_GET["error"] == "successunblock") {
        echo '<p class="signuperror">Your account is unblocked. You can log in now.</p>';
    }
}

if (isset($_GET["newpwd"])) {
    if ($_GET["newpwd"] == "passwordupdated") {
        echo '<p class="signupsuccess">Your password has been reset!</p>';
    }
}

if (isset($_GET["done"])) {
    if ($_GET["done"] == "pwsuccess") {
        echo '<p class="signupsuccess">Your password has been changed!</p>';
    }

    if ($_GET["done"] == "emailsuccess") {
        echo '<p class="signupsuccess">Your email has been changed!</p>';
    }
}

if (!isset($_SESSION['id']) && isset($authUrl)) {
    echo '<p class="login-status">
                  <form class="form-signup" action="includes/login.inc.php" method="post">
                    <input type="text" name="mailuid" placeholder="Username">
                    <input type="password" name="pwd" placeholder="Password">
                    <button type="submit" name="login-submit">Login</button>
                  </form>
                  <form class="form-signup" action="reset-password.php">
                    <button type="submit" name="login-submit">Forgot your password?</button>
                  </form>


                </p>';
    echo "<p class='login-status'><a class='login' href='" . $authUrl . "'><img src='img/logingoogle.png'></a></p>";
} elseif (isset($_SESSION['id'])) {
    if (!isset($authUrl)) {
        echo "You logged in with your gmail account";
        print "<p>Name: {$name} <br />";
        print "Email: {$email} <br />";
    } else {
        $sql = "SELECT * FROM users WHERE idUsers =  '" . $_SESSION['id'] . "'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        if ($row['activeUsers'] == "active") {
            echo '<p class="login-status">You are logged in!
              <form class="form-signup" action="index.php" method="post">
                <button type="submit" name="mainmenu" value="update">Update my Details</button>
              </form>
              <form class="form-signup" action="index.php" method="post">
                <button type="submit" name="mainmenu" value="freeze">Freeze Account</button>
              </form>
              <form class="form-signup" action="index.php" method="post">
                <button type="submit" name="mainmenu" value="unfreeze">Unfreeze Account</button>
              </form>
              <form class="form-signup" action="index.php" method="post">
                <button type="submit" name="mainmenu" value="delete">Delete Account</button>
              </form>
            </p>';
        } elseif ($row['activeUsers'] == "freeze") {
            echo '<p class="login-status">Your account is frozen
                <form class="form-signup" action="index.php" method="post">
                  <button type="submit" style="text-decoration: line-through;" name="mainmenu" value="update" disabled>Update my Details</button>
                </form>
                <form class="form-signup" action="index.php" method="post">
                  <button type="submit" style="text-decoration: line-through;" name="mainmenu" value="freeze" disabled>Freeze Account</button>
                </form>
                <form class="form-signup" action="index.php" method="post">
                  <button type="submit" name="mainmenu" value="unfreeze">Unfreeze Account</button>
                </form>
                <form class="form-signup" style="text-decoration: line-through;" action="index.php" method="post">
                  <button type="submit" name="mainmenu" value="delete" disabled>Delete Account</button>
                </form>
              </p>';
        }
    }
}

if (isset($_POST["deleteaccount"])) {
    if ($_POST["deleteaccount"] == "yes") {
        $sql = "DELETE FROM users WHERE idUsers =  '" . $_SESSION['id'] . "'";
        mysqli_query($conn, $sql);
        session_unset();
        session_destroy();
        header("Location: ../index.php?error=deleted");
        exit();
    }

    if ($_POST["deleteaccount"] == "no") {
        header("Location: ../index.php");
    }
}

if (isset($_POST["mainmenu"])) {
    if ($_POST["mainmenu"] == "update") {
        $sql = "SELECT * FROM users WHERE idUsers =  '" . $_SESSION['id'] . "'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result); ?>
            <form class="form-signup" action="includes/update-details.inc.php" method="post">
              <input type="text" name="uid" placeholder="<?php
        echo $row["uidUsers"]; ?>" value="<?php
        echo $row["uidUsers"]; ?>" readonly>
              <input type="text" name="mail" placeholder="Email">
              <input type="password" name="pwd" placeholder="Password">
              <input type="password" name="pwd-repeat" placeholder="Repeat password">
              <button type="submit" name="update-details">Update</button>
            </form>
          <?php
    }

    $sqlid = $_SESSION['id'];
    if ($_POST["mainmenu"] == "freeze") {
        $sql = "UPDATE users SET activeUsers = 'freeze' WHERE idUsers =  '" . $_SESSION['id'] . "'";
        mysqli_query($conn, $sql);
        header("Refresh:0");
    }

    if ($_POST["mainmenu"] == "unfreeze") {
        $sql = "UPDATE users SET activeUsers = 'active' WHERE idUsers =  '" . $_SESSION['id'] . "'";
        mysqli_query($conn, $sql);
        header("Refresh:0");
    }

    if ($_POST["mainmenu"] == "delete") {
        echo '<form class="yesno" action="index.php" method="post">
      <button type="submit" name="deleteaccount" value="yes">Yes</button>
      <button type="submit" name="deleteaccount" value="no">NO</button>';
    }
}

?>
        </section>
      </div>
    </main>

<?php
require "footer.php";

?>
