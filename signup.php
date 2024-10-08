<!DOCTYPE html>
<html lang="en">

<?php
include "include/config.inc";

$page_name = "Sign Up";

if (isset($_POST['back'])){
    if(isset($_SESSION[PREFIX . "_ppage"])){
        header("location: " . $_SESSION[PREFIX . "_ppage"]);
        exit;
    }
    header("location: index.php");
    exit;
}

// check if email is already taken
if($_SERVER['REQUEST_METHOD'] == "POST" && !$mysqli->user_field_check($_POST['email'], "email")){
    var_dump($_POST);

    $mysqli->add_user($_POST['email'], $_POST['password']);

    header("location: login.php");
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] == "POST" && $mysqli->user_field_check($_POST['email'], "email")) {
    ?>
    <script> alert("Email has been taken, please use another."); </script>
    <?php
    // clear post
    $_POST = array();
}
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $page_name;?></title>
<link rel="stylesheet" href="style.css">
</head>

<body class="login-body">
<div class="landing-pg">
    <div class="login-box">
        <h3>Welcome to Truth Sleuth! Sign up below.</h3>
        <form id="form-login" name="form-login" action="" method="POST" class="form-login">
            <div class="form-input">
                <input id="email" name="email" type="email" placeholder="Email">
            </div>

            <div class="form-input">
                <input id="password" name="password" type="password" placeholder="Password">
            </div>

            <div class="form-btn">
                <input class="input-btn" type="submit" id="login" name="login" value="Sign Up">
                <input class="input-btn" style="float: right;" type="submit" id="back" name="back" value="Back">
            </div>
        </form>
    </div>
</div>
</body>
</html>
