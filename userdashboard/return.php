<?php
session_start();
include '../app/database/connect.php';

$loan_id = $_GET['id'];

// Return book
$loan = mysqli_query($conn, "SELECT book_id FROM loans WHERE id = $loan_id");
$book_id = mysqli_fetch_assoc($loan)['book_id'];

mysqli_query($conn, "UPDATE loans SET returned = 1 WHERE id = $loan_id");
mysqli_query($conn, "UPDATE books SET quantity = quantity + 1 WHERE id = $book_id");

// echo "<script>alert('Book returned successfully!'); window.location='borrowedbooks.php';</script>";
$_SESSION['message'] = "Book returned successfully!";
$_SESSION['message_type'] = "success";


header("Location: borrowedbooks.php");
exit();
?>
