<?php
// Set up session
ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

const PREFIX = 'boiler';
session_name(PREFIX);

date_default_timezone_set("America/Chicago");

session_save_path("./");

//Start session
session_start();
$_SESSION[PREFIX . '_created'] = time();
$_SESSION[PREFIX . '_last_activity'] = time();
ini_set('session.gc_maxlifetime', 1800);

$app_name = "Truth Sleuth";

require_once('mysqli_class.php');
$mysqli = new mysqli_class();
