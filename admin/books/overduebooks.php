<?php
session_start();
include "../../app/database/connect.php"; // Database connection

// Check if the admin is logged in
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../../index.php");
    exit();
}

// Fetch overdue books
$query = "
    SELECT 
        loans.id AS loan_id,
        users.name AS user_name,
        users.email AS user_email,
        books.title AS book_title,
        loans.due_date
    FROM loans
    JOIN users ON loans.user_id = users.id
    JOIN books ON loans.book_id = books.id
    WHERE loans.returned = 0 AND loans.due_date < NOW()
    ORDER BY loans.due_date ASC
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overdue Books</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
    <div class="adminControl">
        <div class="adminOptions">
            <a href="../books/books.php" class="adminOption">Book Management</a>
            <a href="../users/users.php" class="adminOption">User Management</a>
        </div>
    </div>
    <div class="overdueTable">
        <h1 class="tableTitle">Overdue Books</h1>
        <table>
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>Email</th>
                    <th>Book Title</th>
                    <th>Due Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['user_name']) ?></td>
                            <td><?= htmlspecialchars($row['user_email']) ?></td>
                            <td><?= htmlspecialchars($row['book_title']) ?></td>
                            <td><?= htmlspecialchars($row['due_date']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No overdue books found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>