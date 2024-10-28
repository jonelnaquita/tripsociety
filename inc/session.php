<?php
session_start();
// Check if the admin session is active
if (!isset($_SESSION['admin'])) {
    // If not set, redirect to index.php
    header('Location: ../index.php'); // Adjust the path if necessary
    exit();
}
?>