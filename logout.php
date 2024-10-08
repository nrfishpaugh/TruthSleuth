<?php
include "include/config.inc";

$_SESSION = array();
$mysqli->session_delete();

header("location: index.php?action=logout");
exit;