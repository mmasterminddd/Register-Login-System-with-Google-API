<?php
require '../header.php';
?>

<main>
  <div class="wrapper-main">
    <section class="section-default">
      <?php
$selector = $_GET['selector'];
$validator = $_GET['validator'];

if (empty($selector) || empty($validator)) {
    echo "Could not validate your request!";
} else {
    if (ctype_xdigit($selector) !== false && ctype_xdigit($validator) !== false) {
        require 'dbh.inc.php';

        $sql = "SELECT * FROM unblock WHERE unblockSelector= '$selector'";
        $result=mysqli_query($conn, $sql);
        $row=mysqli_fetch_assoc($result);
        $tokenEmail = $row['unblockEmail'];
        $tokenBin = hex2bin($validator);
        $tokenCheck = password_verify($tokenBin, $row['unblockToken']);

        if ($tokenCheck === false) {
            echo "Token doesnt match.";
        } elseif ($tokenCheck === true) {
            $sql = "SELECT * FROM users WHERE emailUsers='$tokenEmail'";
            $result=mysqli_query($conn, $sql);
            if (!$row = mysqli_fetch_assoc($result)) {
                echo "There was an error!!!";
                exit();
            } else {
                $sql = "UPDATE users SET activeUsers ='active' , failloginUsers = '0' WHERE emailUsers='$tokenEmail'";
            }
            mysqli_query($conn, $sql);
            $sql = "DELETE FROM unblock WHERE unblockEmail='$tokenEmail'";
            mysqli_query($conn, $sql);
            mysqli_close($con);
            header("Location: ../index.php?error=successunblock");
        }
    }
}

?>

    </section>
  </div>
</main>

<?php
require 'footer.php';
?>
