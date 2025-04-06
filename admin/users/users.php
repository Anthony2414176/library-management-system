<?php
session_start();
include "../../app/database/connect.php"; // Using this to connect database

// Checking if admin is logged in
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../../index.php");
    exit();
}

// To fetch all users from database
$result = $conn->query("SELECT * FROM users");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <title>Admin - Manage Users</title>
</head>
<body>
    <div class="adminControl">
        <div class="adminOptions">
            <a href="../books/books.php" class="adminOption active">Book Managemant</a>
            <a href="users.php" class="adminOption">User Managemant</a>
        </div>
    </div>

    <div class="userTable">
        <h1 class="tableTitle">Manage Users</h1>
        <a href="adduser.php" class="btn-add">Add New User</a>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th colspan="2"> Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= $user['role'] ?></td>
                    <td><a href="edituser.php?id=<?= $user['id'] ?>" class="btn-edit">Edit</a></td>
                    <td><a href="deleteuser.php?id=<?= $user['id'] ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="../../assets/js/script.js"></script>
</body>
</html>
