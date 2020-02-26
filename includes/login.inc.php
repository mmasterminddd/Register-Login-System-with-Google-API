<?php
if (isset($_POST['login-submit'])) {
    require 'dbh.inc.php';
    $mailuid = $_POST['mailuid'];
    $password = $_POST['pwd'];
    if (empty($mailuid) || empty($password)) {
        header("Location: ../index.php?error=emptyfields&mailuid=".$mailuid);
        exit();
    } elseif (!preg_match('/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/', $password)) {
        header("Location: ../index.php?error=passwordweak&uid=".$mailuid);
        exit();
    } else {
        $sql = "SELECT * FROM users WHERE uidUsers= '$mailuid' OR emailUsers='$mailuid';";
        $result = mysqli_query($conn, $sql);


        if ($row = mysqli_fetch_assoc($result)) {
            $pwdCheck = password_verify($password, $row['pwdUsers']);
            if ($pwdCheck == false) {
                // Kanoume +1 to login tried.
                $sql = "UPDATE users SET failloginUsers = failloginUsers + 1 WHERE uidUsers= '$mailuid' OR emailUsers= '$mailuid' ;";
                mysqli_query($conn, $sql);
                // Elenxoume an exei ftasei tis 3 anepitixis prospathies sindesis.
                $sql = "SELECT * FROM users WHERE uidUsers= '$mailuid' OR emailUsers= '$mailuid' ;";
                $result = mysqli_query($conn, $sql);
                if ($row = mysqli_fetch_assoc($result)) {
                    if ($row['failloginUsers'] >= 3) {
                        if ($row['failloginUsers'] == 3) { // na stiloume to email gia to lock 1 Fora.
                            $selector = bin2hex(random_bytes(8));
                            $token = random_bytes(32);
                            $url = "https://asfplir.info/includes/unblock.inc.php?selector=" . $selector . "&validator=" . bin2hex($token);
                            $userEmail = $row['emailUsers'];
                            $hashedToken = password_hash($token, PASSWORD_DEFAULT);
                            $sql = "INSERT INTO unblock (unblockEmail, unblockSelector, unblockToken) VALUES ('$userEmail', '$selector', '$hashedToken')";
                            mysqli_query($conn, $sql);
                            $to = $row['emailUsers'];
                            $subject = 'Your asfplir account has been locked.';
                            $message = '<p>Your account has been locked because of 3 wrong attemps. ';
                            $message .= 'If you want to unlock it please visit the link below.</p>';
                            $message .= '<a href="' . $url . '">' . $url . '</a></p>';
                            $headers = "From: asfplir <admin@asfplir.info>\r\n";
                            $headers .= "Reply-To: admin@asfplir.info\r\n";
                            $headers .= "Content-type: text/html\r\n";
                            mail($to, $subject, $message, $headers);
                        }
                        $sql = "UPDATE users SET activeUsers = 'lock' WHERE uidUsers= '$mailuid' OR emailUsers= '$mailuid' ;";
                        mysqli_query($conn, $sql);

                        header("Location: ../index.php?error=blocked");
                        exit();
                    }
                }
                header("Location: ../index.php?error=wrongpwd");
                exit();
            } elseif ($pwdCheck == true) {
                $sql = "SELECT * FROM users WHERE uidUsers= '$mailuid' OR emailUsers= '$mailuid' ;";
                $result = mysqli_query($conn, $sql);
                if ($row = mysqli_fetch_assoc($result)) {
                    if ($row['failloginUsers'] >= 3) {
                        $sql = "UPDATE users SET activeUsers = 'lock' WHERE uidUsers= '$mailuid' OR emailUsers= '$mailuid' ;";
                        mysqli_query($conn, $sql);
                        header("Location: ../index.php?error=blocked");
                        exit();
                    }
                }

                session_start();
                $_SESSION['id'] = $row['idUsers'];
                $_SESSION['uid'] = $row['uidUsers'];
                $_SESSION['email'] = $row['emailUsers'];
                $sql = "UPDATE users SET failloginUsers = 0 WHERE uidUsers= $mailuid OR emailUsers= $mailuid ;";
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_execute($stmt);

                header("Location: ../index.php?login=success");
                exit();
            }
        } else {
            header("Location: ../index.php?login=wronguidpwd");
            exit();
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
     header("Location: ../signup.php");
     exit();
 }
