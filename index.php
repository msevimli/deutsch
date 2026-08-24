<?php
require __DIR__ . '/db.php';
$loggedIn = isset($_SESSION['user_id']);
$pageTitle = 'Home';
require __DIR__ . '/header.php'; ?>

<main class="hero">
  <h1>Welcome to MyWordGame</h1>
  <p>A minimal web site for language learning process.</p>
  <?php if ($loggedIn): ?>
    <a class="btn" href="dashboard.php">Go to Dashboard</a>
  <?php else: ?>
    <a class="btn" href="login.php">Login</a>
  <?php endif; ?>
</main>
</body>
</html>
