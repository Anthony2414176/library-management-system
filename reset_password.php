<!-- filepath: c:\xampp\htdocs\library\reset_password.php -->
<?php
include "app/database/connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $newPassword = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

    // Verify the token and update the password
    $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ?");
        $stmt->bind_param("ss", $newPassword, $token);
        $stmt->execute();
        $success = "Your password has been reset successfully.";
    } else {
        $error = "Invalid or expired token.";
    }
    $stmt->close();
} elseif (isset($_GET['token'])) {
    $token = $_GET['token'];
} else {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>Reset Password</title>
</head>
<body class="login">
    <div class="login-form">
        <h1>Reset Password</h1>
        <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <?php if (!isset($success)): ?>
        <form action="reset_password.php" method="post">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
            <div class="form-item">
                <label for="password">New Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit">Reset Password</button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>