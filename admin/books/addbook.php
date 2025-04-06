<?php
session_start();
include "../../app/database/connect.php"; // Using this to connect database

// i used this to check if admin is logged in
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $isbn = trim($_POST['isbn']);
    $genre = trim($_POST['genre']);
    $quantity = (int)$_POST['quantity'];

    // Used this to check if isbn exists
    $stmt = $conn->prepare("SELECT id FROM books WHERE isbn = ?");
    $stmt->bind_param("s", $isbn);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "A book with this ISBN already exists.";
    } else {
        // Used this to insert book
        $stmt = $conn->prepare("INSERT INTO books (title, author, isbn, genre, quantity) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $title, $author, $isbn, $genre, $quantity);

        if ($stmt->execute()) {
            header ("Location: books.php");
            exit();
        } else {
            $error = "Failed to add book.";
        }
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <title>Add Book</title>
</head>
<body>
    <div class="mangt">
        <h1>Add New Book</h1>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form id="book-form" action="addbook.php" method="post">
            <div class="book-details">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" required>
            </div>
            <div class="book-details">
                <label for="author">Author</label>
                <input type="text" name="author" id="author" required>
            </div>
            <div class="book-details">
                <label for="isbn">ISBN</label>
                <input type="text" name="isbn" id="isbn" required>
            </div>
            <div class="book-details">
                <label for="genre">Genre</label>
                <input type="text" name="genre" id="genre" required>
            </div>
            <div class="book-details">
                <label for="quantity">Quantity</label>
                <input type="number" name="quantity" id="quantity" min="1" required>
            </div>
            <button type="submit" class="addButton">Add Book</button>
        </form>
        <a href="books.php">Back to Books</a>
    </div>

    <script src="../../assets/js/script.js"></script>
</body>
</html>
