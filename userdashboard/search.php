<?php
include '../app/database/connect.php';

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

if (mysqli_num_rows($result) > 0) {
    while ($book = mysqli_fetch_assoc($result)) {
        echo '<div class="book">';
        echo '<h2>' . htmlspecialchars($book['title']) . '</h2>';
        echo '<p>' . htmlspecialchars($book['genre']) . '</p>';
        echo '<p>' . htmlspecialchars($book['author']) . '</p>';
        echo '<a href="borrow.php?id=' . $book['id'] . '">Borrow</a>';
        echo '</div>';
    }
} else {
    echo '<p>No books found.</p>';
}
?>