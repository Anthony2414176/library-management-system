<?php
session_start();
include '../app/database/connect.php'; // Database connection

// Fetch current user information
$user_id = $_SESSION['user_id']; 
$query = "SELECT name, email FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $update_query = "UPDATE users SET name = '$name', email = '$email' WHERE id = $user_id";
    if (mysqli_query($conn, $update_query)) {
        $_SESSION['message'] = "Profile updated successfully.";
        $_SESSION['message_type'] = "success";
        header("Location: dashboard.php");
        $_SESSION['message'] = "Failed to update profile.";
        $_SESSION['message_type'] = "error";
        header("Location: profile.php"); // Stay on the profile page in case of an error
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.0.0/css/all.css">
</head>
<body>
    <!-- header -->
    <?php include '../app/includes/header.php'; ?>

    <main>
        <h1>Update Profile</h1>
        <form method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

            <button type="submit">Update</button>
        </form>

        <!-- messagebox -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message <?= $_SESSION['message_type'] ?>">
                <p><?= $_SESSION['message'] ?></p>
            </div>
        <?php endif; ?>
    </main>

    <!-- footer -->
    <?php include '../app/includes/footer.php'; ?>
</body>
</html>
