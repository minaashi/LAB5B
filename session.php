<?php
session_start();

// Check if user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
