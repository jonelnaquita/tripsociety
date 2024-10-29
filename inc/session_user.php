<?php
session_start();
if (!isset($_SESSION['user'])) {
    // If not set, redirect to index.php
    header('Location: login.php'); // Adjust the path if necessary
    exit();
}
?>