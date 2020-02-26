<?php
if (isset($_POST['update-details'])) {
    require 'dbh.inc.php';
    $id = $_POST['uid'];
    if ($_POST['mail'] != null) {
        $email = $_POST['mail'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: ../index.php?error=invalidmail");
            exit();
        }

        $sql = "SELECT * FROM users WHERE emailUsers='$email'";
        $result = mysqli_query($conn, $sql);
        $resultCount = mysqli_num_rows($result);
        if ($resultCount > 0) {
            header("Location: ../index.php?error=usertaken");
            exit();
        }
        $sql = "UPDATE users SET emailUsers = '$email' WHERE uidUsers = '$id'";
        mysqli_query($conn, $sql);
        header("Location: ../index.php?done=emailsuccess");
        exit();
    }

    if (isset($_POST['pwd'])) {
        $password = $_POST['pwd'];
        $passwordRepeat = $_POST['pwd-repeat'];
        if (!preg_match('/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/', $password)) {
            header("Location: ../index.php?error=passwordweak");
            exit();
        } elseif ($password !== $passwordRepeat) {
            header("Location: ../index.php?error=passwordcheck");
            exit();
        }

        $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET pwdUsers = '$hashedPwd' WHERE uidUsers = '$id'";
        mysqli_query($conn, $sql);
        header("Location: ../index.php?done=pwsuccess");
        exit();
    };
} else {
    header("Location: ../index.php");
    exit();
}
