<?php

// Gia na min grafoume ta idia kai ta idia se kathe selida mas mporoume na kanoume include to header edw. Afto exei to navigation bar mas.

require "header.php";

?>
    <main>
      <div class="wrapper-main">
        <section class="section-default">
          <h1>Signup</h1>
          <?php

// Se periptwsh pou exoume kapoio error otan kanoume submit tin forma tha enimerwnei twn user gia to error tou.

if (isset($_GET["error"])) {
    if ($_GET["error"] == "emptyfields") {
        echo '<p class="signuperror">Fill in all fields!</p>';
    } elseif ($_GET["error"] == "invaliduidmail") {
        echo '<p class="signuperror">Invalid username and e-mail!</p>';
    } elseif ($_GET["error"] == "invaliduid") {
        echo '<p class="signuperror">Invalid username!</p>';
    } elseif ($_GET["error"] == "invalidmail") {
        echo '<p class="signuperror">Invalid e-mail!</p>';
    } elseif ($_GET["error"] == "passwordcheck") {
        echo '<p class="signuperror">Your passwords do not match!</p>';
    } elseif ($_GET["error"] == "passwordweak") {
        echo '<p class="signuperror">Your password is weak. At least 1 uppercase letter, at least 1 lowercase letter, at least 1 number, and at least 1 special char!</p>';
    } elseif ($_GET["error"] == "usertaken") {
        echo '<p class="signuperror">Username is already taken!</p>';
    }
}

// Edw enimeronoume ton user oti i eggrafi tou egine epitixws.

elseif (isset($_GET["signup"])) {
    if ($_GET["signup"] == "success") {
        echo '<p class="signupsuccess">Signup successful!</p>';
    }
}

?>
          <form class="form-signup" action="includes/signup.inc.php" method="post">
            <?php

// Elenxoume to username

if (!empty($_GET["uid"])) {
    echo '<input type="text" name="uid" placeholder="Username" value="' . $_GET["uid"] . '">';
} else {
    echo '<input type="text" name="uid" placeholder="Username">';
}

// Elenxoume to email

if (!empty($_GET["mail"])) {
    echo '<input type="text" name="mail" placeholder="E-mail" value="' . $_GET["mail"] . '">';
} else {
    echo '<input type="text" name="mail" placeholder="E-mail">';
}

?>
            <input type="password" name="pwd" placeholder="Password">
            <input type="password" name="pwd-repeat" placeholder="Repeat password">
            <button type="submit" name="signup-submit">Signup</button>
          </form>
        </section>
      </div>
    </main>
<?php

// To idio me to header pano.

require "footer.php";

?>
