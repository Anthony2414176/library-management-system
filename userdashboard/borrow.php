<?php
session_start();
include '../app/database/connect.php'; // Database connection

$user_id = $_SESSION['user_id']; // Assuming the user ID is stored in the session
$book_id = $_GET['id']; // Get the book ID from the URL

// Check if the user has already borrowed this book
$check_borrowed = $conn->prepare("SELECT COUNT(*) AS count FROM loans WHERE user_id = ? AND book_id = ? AND returned = 0");
$check_borrowed->bind_param("ii", $user_id, $book_id);
$check_borrowed->execute();
$result = $check_borrowed->get_result();
$row = $result->fetch_assoc();

if ($row['count'] > 0) {
    // User has already borrowed this book
    $_SESSION['message'] = "You have already borrowed this book.";
    $_SESSION['message_type'] = "error";
} else {
    // Check if the book is available
    $book_check = $conn->prepare("SELECT quantity FROM books WHERE id = ?");
    $book_check->bind_param("i", $book_id);
    $book_check->execute();
    $book_result = $book_check->get_result();
    $book_row = $book_result->fetch_assoc();

    if ($book_row['quantity'] > 0) {
        // Insert loan record
        $stmt = $conn->prepare("INSERT INTO loans (user_id, book_id, due_date) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 14 DAY))");
        $stmt->bind_param("ii", $user_id, $book_id);
        if ($stmt->execute()) {
            // Update book quantity
            $update_quantity = $conn->prepare("UPDATE books SET quantity = quantity - 1 WHERE id = ?");
            $update_quantity->bind_param("i", $book_id);
            $update_quantity->execute();

            $_SESSION['message'] = "Book borrowed successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Failed to borrow the book. Please try again.";
            $_SESSION['message_type'] = "error";
        }
    } else {
        $_SESSION['message'] = "Book is not available.";
        $_SESSION['message_type'] = "error";
    }
}

// Redirect to the user dashboard page
header("Location: dashboard.php");
exit();
?>