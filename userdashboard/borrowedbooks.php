<?php
session_start();
include '../app/database/connect.php';

//message
$message = $_SESSION['message'] ?? null;
$message_type = $_SESSION['message_type'] ?? null;
unset($_SESSION['message']);
unset($_SESSION['message_type']);


$user_id = $_SESSION['user_id'];
$query = "SELECT loans.id, books.title, books.author, books.genre, loans.due_date 
          FROM loans 
          JOIN books ON loans.book_id = books.id 
          WHERE loans.user_id = $user_id AND loans.returned = 0";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Borrowed Books</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.0.0/css/all.css">
</head>
<body class="homeborrowed">
    
    <!-- header -->
    <?php include '../app/includes/header.php'; ?>


    <!-- messagebox -->
    <?php if ($message): ?>
        <div class="message <?= $message_type ?>" id="message-box">
            <p><?= $message ?></p>
        </div>
    <?php endif; ?>

    <main class="borrowed">
        <h2>My Borrowed Books</h2>
        <table border="1">
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Genre</th>
                <th>Due Date</th>
                <th>Return</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $row['title'] ?></td>
                    <td><?= $row['author'] ?></td>
                    <td><?= $row['genre'] ?></td>
                    <td><?= $row['due_date'] ?></td>
                    <td><a href="return.php?id=<?= $row['id'] ?>">Return</a></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </main>

    <!-- footer -->
    <?php include '../app/includes/footer.php'; ?>

    <script src="../assets/js/script.js"></script>
</body>
</html>
