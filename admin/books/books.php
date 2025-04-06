<?php
session_start();
include "../../app/database/connect.php"; // Using this to connect database

// I check if admin is logged in
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../../index.php");
    exit();
}

// I fetch all books from data
$result = $conn->query("SELECT * FROM books");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <title>Admin - Manage Books</title>
</head>
<body>
    <div class="adminControl">
        <div class="adminOptions">
            <a href="books.php" class="adminOption active">Book Managemant</a>
            <a href="../users/users.php" class="adminOption">User Managemant</a>
        </div>
    </div>
    <div class="bookTable">
        <h1 class="tableTitle">Manage Books</h1>
        <a href="addbook.php" class="btn-add">Add New Book</a>
        <a href="overduebooks.php" class="due_date">Overdue Books</a>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>ISBN</th>
                    <th>Genre</th>
                    <th>Quantity</th>
                    <th colspan="2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($book = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($book['title']) ?></td>
                    <td><?= htmlspecialchars($book['author']) ?></td>
                    <td><?= htmlspecialchars($book['isbn']) ?></td>
                    <td><?= htmlspecialchars($book['genre']) ?></td>
                    <td><?= $book['quantity'] ?></td>
                    <td><a href="editbook.php?id=<?= $book['id'] ?>" class="btn-edit">Edit</a></td>
                    <td><a href="deletebook.php?id=<?= $book['id'] ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this book?')">Delete</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="../../assets/js/script.js"></script>

</body>
</html>
