<!DOCTYPE html>
<html lang="en">
<?php
include "include/config.inc";

// set this page for later
$_SESSION[PREFIX . "_ppage"] = $_SERVER['REQUEST_URI'];

$page_name = "Login to Truth Sleuth";

if ($_POST['email'] != "" && $_POST['password'] != "" && isset($_POST['login'])){
    // get login response, first element is a boolean: 1 = good login, 0 = bad login
    $login_response = $mysqli->login($_POST['email'], $_POST['password']);

    // set session variables and check if login is good or bad
    setlogin($login_response, 0);

    // redirect to previous page if it exists
    /*
    if($_SESSION[PREFIX . "_ppage"] != ''){
        $redirect = $_SESSION[PREFIX . "_ppage"];
        header("location: " . $redirect);
        exit;
    }
    */

    // otherwise go to index
    header("location: index.php");
    exit;
} elseif (isset($_POST['signup'])) {
    // clear post
    $_POST = array();
    header("location: signup.php");
    exit;
}

function setlogin($response, $guest){
    var_dump($response);
    if($response[0] == 1){
        $_SESSION[PREFIX . '_username'] = $response[1]['email'];
    } else {
        ?>
        <script>
            alert("Your username and password are incorrect.");
        </script>
        <?php
    }
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
            <h3>Welcome to Truth Sleuth! Sign in to continue.</h3>
            <form id="form-login" name="form-login" action="" method="POST" class="form-login">
                <div class="form-input">
                    <input id="email" name="email" type="email" placeholder="Email">
                </div>

                <div class="form-input">
                    <input id="password" name="password" type="password" placeholder="Password">
                </div>

                <div class="form-btn">
                    <input class="input-btn" type="submit" id="login" name="login" value="Log In">
                </div>
            </form>

            <a class="signup-link" href="signup.php">Don't have an account? Click here to sign up!</a>
        </div>
    </div>
</body>
</html>
