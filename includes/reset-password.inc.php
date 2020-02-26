<?php
if (isset($_POST['reset-password-submit'])) {
    $selector = $_POST['selector'];
    $validator = $_POST['validator'];
    $password = $_POST['pwd'];
    $passwordRepeat = $_POST['pwd-repeat'];

    if (empty($password) || empty($passwordRepeat)) {
        header("Location: ../signup.php?newpwd=empty");
        exit();
    } elseif ($password != $passwordRepeat) {
        header("Location: ../signup.php?newpwd=pwdnotsame");
        exit();
    }
    $currentDate = date('U');
    require 'dbh.inc.php';
    $sql = "SELECT * FROM pwdReset WHERE pwdResetSelector='$selector' AND pwdResetExpires >= $currentDate";
    $result = mysqli_query($conn, $sql);
    if (!$row = mysqli_fetch_assoc($result)) {
        echo "You need to re-submit your reset request.";
        exit();
    } else {
        $tokenBin = hex2bin($validator);
        $tokenCheck = password_verify($tokenBin, $row['pwdResetToken']);
        if ($tokenCheck === false) {
            echo "There was an error!";
        } elseif ($tokenCheck === true) {
            $tokenEmail = $row['pwdResetEmail'];
            $sql = "SELECT * FROM users WHERE emailUsers='$tokenEmail'";
            $result = mysqli_query($conn, $sql);
            if (!$row = mysqli_fetch_assoc($result)) {
                echo "There was an error!";
                exit();
            } else {
                $newPwdHash = password_hash($password, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET pwdUsers='$newPwdHash' WHERE emailUsers='$tokenEmail'";
                mysqli_query($conn, $sql);
                $sql = "DELETE FROM pwdReset WHERE pwdResetEmail='$tokenEmail'";
                mysqli_query($conn, $sql);
                header("Location: ../index.php?newpwd=passwordupdated");
            }
        }
    }
} else {
     header("Location: ../index.php");
     exit();
 }
