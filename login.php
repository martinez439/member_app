<?php
declare(strict_types=1);

require_once __DIR__ . '/auth.php';

if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

$flash = flash_get();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string)($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    $user = find_user_by_email($email);
    if (!$user || !password_verify($password, $user['password_hash'])) {
        flash_set('error', 'Invalid email or password.');
        header('Location: login.php');
        exit;
    }

    login_user($user);
    flash_set('success', 'Welcome back!');
    header('Location: index.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= h(APP_NAME) ?> - Login</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <div class="container">
    <header class="topbar">
      <div class="brand"><?= h(APP_NAME) ?></div>
      <div class="topbar-right">
        <a class="btn btn-outline" href="register.php">Register</a>
      </div>
    </header>

    <?php if ($flash): ?>
      <div class="alert <?= h($flash['type']) ?>"><?= h($flash['message']) ?></div>
    <?php endif; ?>

    <main class="card">
      <h1>Login</h1>

      <form id="loginForm" method="post" novalidate>
        <label>
          Email
          <input type="email" name="email" required autocomplete="email" />
        </label>

     <label>
  Password
  <div class="password-field">
    <input
      type="password"
      name="password"
      required
      autocomplete="current-password"
    />
    
  </div>
</label>


        <div class="row">
          <button class="btn" type="submit">Login</button>
          <a class="link" href="register.php">Need an account?</a>
        </div>

        <p class="form-error" id="loginError" aria-live="polite"></p>
      </form>
    </main>
  </div>

  <script src="app.js"></script>
</body>
</html>
