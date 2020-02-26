<?php
if (isset($_POST['reset-request-submit'])) {
    $selector = bin2hex(random_bytes(8));
    $token = random_bytes(32);
    $url = "https://asfplir.info/create-new-password.php?selector=" . $selector . "&validator=" . bin2hex($token);
    $expires = date("U") + 1800;
    require 'dbh.inc.php';
    $userEmail = $_POST["email"];
    $sql = "DELETE FROM pwdReset WHERE pwdResetEmail='$userEmail'";
    mysqli_query($conn, $sql);
    $hashedToken = password_hash($token, PASSWORD_DEFAULT);
    $sql = "INSERT INTO pwdReset (pwdResetEmail, pwdResetSelector, pwdResetToken, pwdResetExpires) VALUES ('$userEmail', '$selector', '$hashedToken', '$expires')";
    mysqli_query($conn, $sql);
    $to = $userEmail;
    $subject = 'Reset your password for asfplir';
    $message = '<p>We recieved a password reset request. The link to reset your password is below. ';
    $message .= 'If you did not make this request, you can ignore this email</p>';
    $message .= '<p>Here is your password reset link: </br>';
    $message .= '<a href="' . $url . '">' . $url . '</a></p>';
    $headers = "From: asfplir <admin@asfplir.info>\r\n";
    $headers .= "Reply-To: admin@asfplir.info\r\n";
    $headers .= "Content-type: text/html\r\n";
    mail($to, $subject, $message, $headers);
    header("Location: ../reset-password.php?reset=success");
} else {
    header("Location: ../signup.php");
    exit();
}
