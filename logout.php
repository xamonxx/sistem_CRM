<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

if (isset($_SESSION['user_id'])) {
    logAktivitas($conn, $_SESSION['user_id'], "User logout");
}

session_destroy();
header("Location: index.php");
exit();
?>
