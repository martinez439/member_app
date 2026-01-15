<?php
declare(strict_types=1);

require_once __DIR__ . '/auth.php';
require_login();

$flash = flash_get();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= h(APP_NAME) ?> - Home</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <div class="container">
    <header class="topbar">
      <div class="brand"><?= h(APP_NAME) ?></div>
      <div class="topbar-right">
        <span class="muted"><?= h($_SESSION['user_email']) ?></span>
        <a class="btn btn-outline" href="logout.php">Logout</a>
      </div>
    </header>

    <?php if ($flash): ?>
      <div class="alert <?= h($flash['type']) ?>"><?= h($flash['message']) ?></div>
    <?php endif; ?>

    <main class="card">
      <h1 class="big">hello worldor</h1>
    </main>
  </div>
</body>
</html>
