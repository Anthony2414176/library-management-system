<?php
session_start();
include "../../app/database/connect.php"; // Using this to connect database

// checking if admin is logged in
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $role);

    if ($stmt->execute()) {
        header("Location: users.php");
        exit();
    } else {
        $error = "Failed to add user.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <title>Add User</title>
</head>
<body>
    <div class="mangt">
        <h1>Add New User</h1>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form id="user-form" action="adduser.php" method="post">
            <div class="user-details">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div class="user-details">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
            <div class="user-details">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="user-details">
                <label for="role">Role</label>
                <select name="role" id="role" required>
                    <option value="Admin">Admin</option>
                    <option value="User">User</option>
                </select>
            </div>
            <button type="submit" name="submit" class="addButton">Add User</button>
        </form>
        
        <a href="users.php">Back to Users</a>
    </div>

    <script src="../../assets/js/script.js"></script>
</body>
</html>
