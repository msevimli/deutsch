<?php
require __DIR__ . '/db.php';
$loggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Home</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<nav>
  <a href="index.php" class="brand">MyWordGame</a>
  <div>
    <?php if ($loggedIn): ?>
      <a href="dashboard.php">Dashboard</a>
      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="login.php">Login</a>
    <?php endif; ?>
  </div>
</nav>

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
