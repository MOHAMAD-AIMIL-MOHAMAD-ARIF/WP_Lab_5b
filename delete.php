<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Redirect to login page if not logged in
    header("Location: register-and-login-page.php");
    exit;
}

// Include database connection
include("db-conn.php");

// Check if the matric is provided in the URL
if (isset($_GET['matric'])) {
    $matric = $_GET['matric'];

    // Delete the record
    $stmt = $pdo->prepare("DELETE FROM users WHERE matric = :matric");
    $stmt->execute(['matric' => $matric]);

    // Redirect back to the view page
    header("Location: view-data.php");
    exit;
} else {
    // If no matric is provided, redirect back
    header("Location: view-data.php");
    exit;
}
?>
