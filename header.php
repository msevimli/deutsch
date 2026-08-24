<?php
$pageTitle = $pageTitle ?? 'MyWordGame';
$loggedInHeader = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($pageTitle) ?></title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<nav>
  <a href="index.php" class="brand">MyWordGame</a>
  <div>
    <a href="index.php">Home</a>
    <?php if ($loggedInHeader): ?>
      <a href="dashboard.php">Dashboard</a>
      <a href="game.php">Word Trainer</a>
      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="login.php">Login</a>
    <?php endif; ?>
  </div>
</nav>