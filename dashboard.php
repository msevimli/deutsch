<?php
require __DIR__ . '/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$username = htmlspecialchars($_SESSION['username']);
$userId   = (int)$_SESSION['user_id'];
$pageTitle = 'Dashboard';
require __DIR__ . '/header.php'; ?>

<main class="dash">
  <div class="panel">
    <h1>Welcome, <?= $username ?>!</h1>
    <p>You are logged in to your dashboard.</p>
    <div class="actions">
      <a class="btn" href="game.php">Word Trainer</a>
      <a class="btn ghost" href="logout.php">Logout</a>
    </div>
    <p class="meta">Logged in as <?= $username ?> (user #<?= $userId ?>)</p>
  </div>
</main>
</body>
</html>
