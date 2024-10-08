<?php
include "include/config.inc";

// set this page for later
$_SESSION[PREFIX . "_ppage"] = $_SERVER['REQUEST_URI'];
// redirect to login page if session is expired
if(!($mysqli->session_update())){
    header("Location: login.php");
    exit;
}

$page_name = "The AI Fact Checking Tool";
?>
<!DOCTYPE html>
<html lang = "en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $app_name;?> - <?php echo $page_name;?></title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="response-body">
    <header>
        <h2 class="title"><?php echo $app_name; ?></h2>
        <ul>
            <?php if(isset($_SESSION[PREFIX . '_username'])){
                ?>
                <li><a href="logout.php">Log Out</a></li>
            <?php
            }
            else{
                ?>
                <li><a href="login.php">Log In</a></li>
                <li><a href="signup.php">Sign Up</a></li>
            <?php
            }?>

        </ul>
    </header>

    <div class="main-panel">
        <div class="container">
            <div class="row">
                <form id="form-response" name="form-response" action="" method="post" class="full-width">
                    <label for="response">Insert AI response here</label><textarea id="response" name="ta_response" placeholder="Enter AI Response..." rows="12"></textarea>
                    <button type="submit">Submit</button>
                </form>
            </div>

            <div class="row center">
                <div class="results full-width min-400-height center">
                    <p class="low-opacity">This box will fill with results when the Submit button is pressed.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>