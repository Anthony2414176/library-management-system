<?php
session_start();
include "../../app/database/connect.php"; // i connect database

// i checked if admin is logged in
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../../index.php");
    exit();
}

// i also checked if book id is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: books.php");
    exit();
}

$book_id = $_GET['id'];

// This is to delete the book
$stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
$stmt->bind_param("i", $book_id);

if ($stmt->execute()) {
    header("Location: books.php");
    exit();
} else {
    echo "Error deleting book.";
}
?>
