<?php
session_start();
include "../../app/database/connect.php"; // Using this to connect database

// Checking if admin is logged in
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../../index.php");
    exit();
}

// Checking if user id is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: users.php");
    exit();
}

$user_id = $_GET['id'];

// To fetching the user details
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    header("Location: users.php");
    exit();
}

// To handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];

    // To check if password is provided
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET name=?, email=?, password=?, role=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $email, $password, $role, $user_id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET name=?, email=?, role=? WHERE id=?");
        $stmt->bind_param("sssi", $name, $email, $role, $user_id);
    }

    if ($stmt->execute()) {
        header("Location: users.php");
        exit();
    } else {
        $error = "Failed to update user.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <title>Edit User</title>
</head>
<body>
    <div class="mangt">
        <h1>Edit User</h1>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form id="user-form" action="edituser.php?id=<?= $user_id ?>" method="post">
            <div class="user-details">
                <label>Name</label>
                <input type="text" name="title" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>
            <div class="user-details">
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <div class="user-details">
                <label>Password (Leave blank to keep existing)</label>
                <input type="password" name="password">
            </div>
            <div>
                <label class="user-details">Role</label>
                <select name="role">
                    <option value="Admin" <?= $user['role'] === 'Admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="User" <?= $user['role'] === 'User' ? 'selected' : '' ?>>User</option>
                </select>
            </div>
            <button type="submit">Update User</button>
        </form>
        <a href="users.php">Back to Users</a>
    </div>
</body>
</html>
