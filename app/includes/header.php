<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<header>
    <div class="logo"><span>LMS</span></div>
    <nav>
        <ul class="nav-links">
            <li><a href="dashboard.php">Home</a></li>
            <?php if ($current_page == 'dashboard.php') : ?>
                <li><a href="borrowedbooks.php" id="mbb">My Borrowed Books</a></li>
            <?php endif; ?>
            <li><a href="../../logout.php">Logout</a></li>
            <li><a href="../../userdashboard/profile.php"><i class="fas fa-user-circle "></i></a></li>
        </ul>
        <div class="menu-toggle" id="mobile-menu">
            <i class="fa fa-bars"></i>
        </div>
    </nav>
</header>
