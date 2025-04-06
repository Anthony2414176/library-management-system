<?php
session_start();
include "../../app/database/connect.php"; // Using this to connect database

// I check if admin is logged in
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../../index.php");
    exit();
}

// i check if book id is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: books.php");
    exit();
}

$book_id = $_GET['id'];

// Using this to fetch book details
$stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

if (!$book) {
    header("Location: books.php");
    exit();
}

// This is to handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $isbn = trim($_POST['isbn']);
    $genre = trim($_POST['genre']);
    $quantity = (int)$_POST['quantity'];

    $stmt = $conn->prepare("UPDATE books SET title=?, author=?, isbn=?, genre=?, quantity=? WHERE id=?");
    $stmt->bind_param("ssssii", $title, $author, $isbn, $genre, $quantity, $book_id);

    if ($stmt->execute()) {
        header("Location: books.php");
        exit();
    } else {
        $error = "Failed to update book.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <title>Edit Book</title>
</head>
<body>
    <div class="mangt">
        <h1>Edit Book</h1>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form id="book-form" action="editbook.php?id=<?= $book_id ?>" method="post">
            <div class="book-details">
                <label>Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>
            </div>
            <div class="book-details">
                <label>Author</label>
                <input type="text" name="author" value="<?= htmlspecialchars($book['author']) ?>" required>
            </div>
            <div class="book-details">
                <label>ISBN</label>
                <input type="text" name="isbn" value="<?= htmlspecialchars($book['isbn']) ?>" required>
            </div>
            <div class="book-details">
                <label>Genre</label>
                <input type="text" name="genre" value="<?= htmlspecialchars($book['genre']) ?>" required>
            </div>
            <div class="book-details">
                <label>Quantity</label>
                <input type="number" name="quantity" min="1" value="<?= $book['quantity'] ?>" required>
            </div>
            <button type="submit" class="addButton">Update Book</button>
        </form>
        <a href="books.php">Back to Books</a>
    </div>

    <script src="../../assets/js/script.js"></script>
</body>
</html>
