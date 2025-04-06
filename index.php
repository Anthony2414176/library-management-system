<?php
session_start();
include "app/database/connect.php"; // To connect database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    // To fetch user from database
    $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ? AND role = ?");
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // To verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            // To redirect based on their role
            if ($user['role'] == "Admin") {
                header("Location: admin/books/books.php"); 
            } else {
                header("Location: userdashboard/dashboard.php"); 
            }
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "User not found.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>Login</title>
</head>
<body class="login">
    <div class="login-form">
        <h1>Library Login</h1>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form id="login-form" action="index.php" method="post">
            <div class="form-item">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="form-item">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-item">
                <label for="role">Role</label>
                <select name="role" required>
                    <option value="User">User</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>
            <button type="submit">Login</button>
            
            <a href="forgot_password.php">forgot password?</a>
            <p id="no_password">Don't have an account? <a href="register.php">Register</a></p>
            
        </form>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>
