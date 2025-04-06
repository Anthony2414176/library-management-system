<?php
session_start();
include "../../app/database/connect.php"; // Using this to connect database

// Checking if admin is logged in
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../../index.php");
    exit();
}

// Checking if the user id is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: users.php");
    exit();
}

$user_id = $_GET['id'];

// Prevent deleting the admin
if ($_SESSION['user_id'] == $user_id) {
    echo "You cannot delete your own account.";
    exit();
}

// Able to delete the user
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    header("Location: users.php");
    exit();
} else {
    echo "Error deleting user.";
}
?>
