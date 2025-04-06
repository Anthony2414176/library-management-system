<?php
session_start();
include '../app/database/connect.php'; // Database connection

//message
$message = $_SESSION['message'] ?? null;
$message_type = $_SESSION['message_type'] ?? null;
unset($_SESSION['message']);
unset($_SESSION['message_type']);

// Fetch unique authors and genres
$authors = mysqli_query($conn, "SELECT DISTINCT author FROM books WHERE quantity > 0");
$genres = mysqli_query($conn, "SELECT DISTINCT genre FROM books WHERE quantity > 0");

// Fetch books with search and filter
$search = $_GET['search'] ?? '';
$author = $_GET['author'] ?? '';
$genre = $_GET['genre'] ?? '';

$query = "SELECT * FROM books WHERE quantity > 0";
if ($search) {
    $query .= " AND title LIKE '%$search%'";
}
if ($author) {
    $query .= " AND author='$author'";
}
if ($genre) {
    $query .= " AND genre='$genre'";
}
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library - User Dashboard</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.0.0/css/all.css">
    
</head>
<body class="home">
    <!-- header -->
    <?php include '../app/includes/header.php'; ?>


    <main>
        <section class="search-section">
            <form method="GET">
                <div class="search-container">
                    <input type="text" name="search" id="search-input" placeholder="Search book" value="<?= $search ?>">
                    <button type="submit" id="search-button"><i class="fas fa-search"></i></button>
                </div>
                <div class="filters">
                    <div class="searchBy">
                        <p>Search By:</p>
                    </div>
                    <div class="filterBy custom-dropdown">
                        <select name="author" id="author">
                            <option value="">Author</option>
                            <?php while ($row = mysqli_fetch_assoc($authors)): ?>
                                <option value="<?= $row['author'] ?>" <?= ($author == $row['author']) ? 'selected' : '' ?>><?= $row['author'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="filterBy">
                        <select name="genre" id="genre">
                            <option value="">Genre</option>
                            <?php while ($row = mysqli_fetch_assoc($genres)): ?>
                                <option value="<?= $row['genre'] ?>" <?= ($genre == $row['genre']) ? 'selected' : '' ?>><?= $row['genre'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <button type="submit" id="filter-button">Filter</button>
                </div>
            </form>
        </section>

        <!-- messagebox -->
        <?php if ($message): ?>
            <div class="message <?= $message_type ?>" id="message-box">
                <p><?= $message ?></p>
            </div>
        <?php endif; ?>

                    
        <section class="books-section">
            <h2 id="avail-books">Available Books</h2>
            <div class="books-container">
                <?php while ($book = mysqli_fetch_assoc($result)): ?>
                    <div class="book">
                        <h2><?= $book['title'] ?></h2>
                        <p><?= $book['genre'] ?></p>
                        <p><?= $book['author'] ?></p>
                        <a href="borrow.php?id=<?= $book['id'] ?>">Borrow</a>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
    </main>

    <!-- footer -->
    <?php include '../app/includes/footer.php'; ?>

    <script src="../assets/js/script.js"></script>
</body>
</html>